<?php

class Product {
		public $title;
		public $detailsURL;
		public $photoURL;
		public $items = array();
		public $price;
		public $rating;
	}
class BD2{
    private static $conexiune_bd = NULL;
    public static function obtine_conexiune(){
        if (is_null(self::$conexiune_bd))
        {
            self::$conexiune_bd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME2, DB_USER2, DB_PASS2);
        }
        return self::$conexiune_bd;
    }
}
class BD3{
    private static $conexiune_bd = NULL;
    public static function obtine_conexiune(){
        if (is_null(self::$conexiune_bd))
        {
            self::$conexiune_bd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME3, DB_USER2, DB_PASS2);
        }
        return self::$conexiune_bd;
    }
}
class BD4{
    private static $conexiune_bd = NULL;
    public static function obtine_conexiune(){
        if (is_null(self::$conexiune_bd))
        {
            self::$conexiune_bd = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME4, DB_USER4, DB_PASS4);
        }
        return self::$conexiune_bd;
    }
}
class ProduseModel extends Model{
    private $bd_emag;
    private $bd_altex;
    private $bd_cel;

    /**
     * ProduseModel constructor.
     * se intializeaza conexiunile la bazele de date cu produse
     */
    public function __construct(){
        $this->bd_emag= new BD2;
        $this->bd_altex= new BD3;
        $this->bd_cel= new BD4;
    }

    /**
     * @param $dbname
     * @param $categorie
     * @param $id
     * @param $link
     * se face scrapping de pe pagina produsului pentru a veifica daca exisat sichimbari si pentru a le inregistra in baza de date
     * scrapingul se face cu un switch in funcite de site-ul de oe care se doreste sa se updateze produsul
     */
    public static function updateProdus($dbname, $categorie, $id, $link) //e de ajuns unul dintre link si id, dar o interogare in minus nu strica
    {
        $servername = "localhost";
        $username = "root";
        $password = "";

        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $dom = file_get_html($link);
        if (!empty($dom)) {
            switch ($dbname) {
                case "produse_emag":
                    $pret = $dom->find('.product-new-price', 0)->find('text', 0)->innertext;
                    $pret = str_replace("&#46;", "", $pret);
                    $pret = intval($pret);
                    $rat = $dom->find('span[class=star-rating-text gtm_rp101318]', 0);
                    if (is_object($rat))
                        $rating = floatval($rat->innertext);
                    else
                        $rating = floatval(0);
                    if ($dom->find('div[class=product-highlight product-page-pricing]', 0)->children(2)->tag == "span") //la stoc limitat mai este un adaugat un tag a
                        $disp = $dom->find('div[class=product-highlight product-page-pricing]', 0)->children(2);
                    else
                        $disp = $dom->find('div[class=product-highlight product-page-pricing]', 0)->children(3);

                    $disponibilitate = $disp->innertext;
                    $sql = "UPDATE $categorie SET rating=?,pret=?,disponibilitate=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("disi", $rating, $pret, $disponibilitate, $id);
                    break;

                case "produse_altex":
                    $pret = $dom->find('.Price-int', 0)->innertext;
                    $pret = str_replace("&#46;", "", $pret);
                    $pret = str_replace(".", "", $pret);
                    $pret = intval($pret);
                    $disp=$dom->find('div[class="product-shop js-product-view-thisProduct"]', 0)->children(0)->children(0)->children(2)->children(0)->children(0);
                    $disponibilitate = $disp->innertext;
                    $sql = "UPDATE $categorie SET pret=?,disponibilitate=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isi", $pret, $disponibilitate, $id);
                    break;

                    case "produse_cel":
                    $pret = $dom->find('.productPrice', 0)->innertext;
                    $pret = intval($pret);
                    $sql = "UPDATE $categorie SET pret=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $pret, $id);
                    break;
            }
            $stmt->execute();
        }
        $conn->close();
    }

    /**
     * @param $produs_de_cuatat
     * @param $numar_de_produse_returnate
     * @return array|bool
     * se apeleaza functia care face apelul la API-il Ebay cu parametrii primiti de funtie
     * fisierul xml este parsat si transformat intr-un array de obiecte de tipul Product
     * in caz functia esueaza se returneaza false
     * altfel retureaza array-ul creat
     */
	public static function cautaProdus($produs_de_cuatat, $numar_de_produse_returnate){
	 $product = Ebay::getProductsInXml($produs_de_cuatat, $numar_de_produse_returnate);
	 if($product===false)
	     return false;
     $xml = simplexml_load_string($product);

     $products = array();

     foreach ($xml->Product as $list){
      
     	$productObj = new Product();
     	$productObj->title = (string)$list->Title;
     	$productObj->detailsURL = (string)$list->DetailsURL;

         if((string)$list->DisplayStockPhotos != 'true'){
             continue;
         }
         $productObj->photoURL = (string)$list->StockPhotoURL;
         if(isset($list->ItemSpecifics[0])){
             foreach ($list->ItemSpecifics[0] as $list1){
                 $string=$list1->Name.": ";
                 foreach ( $list1->Value as $i=>$item) {
                     if(array_search($item, (array)$list1->Value)==0)
                         $string.=$item;
                     else
                         $string.=",".$item;
                 }
                 array_push($productObj->items, $string);
             }
         }
         array_push($products, $productObj);
     }
     
     return $products;
    
	}

    /**
     * @param $id
     * @param $category
     * @return mixed
     * se face apel la al doilea server pentru returnarea produselor unui utilizator recunoscut prin id pe categorie pe ruta /GetProductsByCategory
     * se returneaza raspunsul primit
     * id-ul se trimite criptat cu funtia md5() pentru securitate
     */
    public static function produseleMele($id,$category)
    {
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:'.PORT_SERVER2.'/GetProductsByCategory?category='.$category);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Session:'.$id
        ));
        $res = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        return json_decode($res,true);
    }

    /**
     * @param $id
     * @return bool|string
     * se face cerere la al doilea server pentru a primi toate produsele unui anumit utilizator
     * se returneaza direct raspunsul care va fi interpretat in controller
     * id-ul se trimite criptat cu funtia md5() pentru securitate
     */
    public static function toateProdusele($id)
    {
        $id=md5($id);
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:'.PORT_SERVER2.'/GetProductsAllCategory');
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Session:'.$id
        ));
        $res = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        return $res;

    }

    /**
     * @param $word
     * @return mixed
     * se face cerere la al doilea server pentru a primi produsele care contin in titlu $word
     * se returneaza direct raspunsul ca un array asociativ care va fi interpretat in controller
     * adauga in headers capul session cu valoarea id-ului serverului pentru recuonasterea cererii in serverul 2
     * sessionul se trimite criptat cu funtia md5() pentru securitate
     */
    public static function cautaProdusDbDupaNumeSrver2($word)
    {

        $cURLConnection = curl_init();
        $session=md5(APP_SESSION);
        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:'.PORT_SERVER2.'/GetProductsByName?word='.urlencode($word));
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Session:'.$session
        ));

        $res = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        return json_decode($res,true);

    }

    /**
     * @param $id id-ul produslui
     * @param $categorie categoria din care face parte (tabla )
     * @param $sursa sursa produsului (baza de date in care se gaseste)
     * @return bool|mixed
     * intoarce produslu cu id-ul mentionat
     * valideaza categoiria
     * cauta produsul in talela indicata de categorie in baza de date corspondenta sursei
     * returneaza false daca nu recucunoste gategoia
     * retunreaza rezultatul interogarii altfel
     */
    public  function getProdusDb($id,$categorie,$sursa){
        if($categorie=='casti'||$categorie=='calculatoare'||$categorie=='electrocasnice'||$categorie=='telefoane') {
            $sql = "SELECT * FROM " . $categorie . " where id = :id ";
            if ($sursa == "emag")
                $cerere = $this->bd_emag->obtine_conexiune()->prepare($sql);
            else if ($sursa == "altex")
                $cerere = $this->bd_altex->obtine_conexiune()->prepare($sql);
            else if ($sursa == "cel")
                $cerere = $this->bd_cel->obtine_conexiune()->prepare($sql);
            $cerere->execute([
                'id' => $id
            ]);
            $result = $cerere->fetch();
            return $result;
        }
        else return false;
    }

    /**
     * @param $id
     * @param $params
     * trimte prin get produsul la serverul al doilea descris genral in parametrii din query string
     * adauga in headers capul session cu valoarea id-ului primit pentru recuonasterea cererii in serverul 2
     * id se trimite criptat cu funtia md5() pentru securitate
     */
    public function trimiteProdus($id,$params){
        $cURLConnection = curl_init();
         $id=md5($id);
        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:'.PORT_SERVER2.'/AppInsert?'.$params);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Session:'.$id
        ));

        $res = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        echo $res;
    }

    /**
     * @param $produs
     * @param $id
     * trimite prin post produsul primite ca parametru in body-ul cererii la serverul2
     *  adauga in headers capul session cu valoarea id-ului primit pentru recuonasterea cererii in serverul 2
     * id se trimite criptat cu funtia md5() pentru securitate
     */
    public function trimiteProdus2($produs,$id){

        $ch = curl_init('http://localhost:'.PORT_SERVER2.'/AppInsert');
        $id=md5($id);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Session:'.$id
            ),
            CURLOPT_POSTFIELDS => json_encode($produs)
        ));

        $response = curl_exec($ch);
        if($response === FALSE){
            echo "fara raspuns";
            die(curl_error($ch));
        }
        $responseData = json_decode($response, TRUE);
        print_r( $responseData);
    }

    /**
     * @param $id
     * @param $session
     * apeleaza ruta de stergere au unui produs din serverul al doilea trimitandu-i id-ul din parametrii
     * adauga in headers capul session cu valoarea id-ului primit pentru recuonasterea cererii in serverul 2
     * sessionul se trimite criptat cu funtia md5() pentru securitate
     */
    public function sterge($id, $session){
        $cURLConnection = curl_init();
        $session=md5($session);
        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:'.PORT_SERVER2.'/DeleteProduct?id='.$id);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Session:'.$session
        ));
        $res = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        $jsonArrayResponse = json_decode($res);
        print_r($jsonArrayResponse);

    }
}
