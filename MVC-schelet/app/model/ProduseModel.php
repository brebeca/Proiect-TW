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
    private $bd2;
    private $bd3;
    private $bd4;
    public function __construct(){
        $this->bd2= new BD2;
        $this->bd3= new BD3;
        $this->bd4= new BD4;
    }
    public static function updateProdus($dbname, $categorie, $id, $link) //e de ajuns unul dintre link si id, dar o interogare in minus nu strica
    {
        $servername = "localhost";
        $username = "root";
        $password = "";

        $conn = mysqli_connect($servername, $username, $password, $dbname); //ne conectam la bd
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $dom = file_get_html($link); // si incepem sa facem scrapping de pe pagina produsului...
        // in principiu se vor schimba doar pretul,rating-ul si disponibilitatea... restul sunt constante
        if (!empty($dom)) {
            switch ($dbname) { //in functie de site 
                case "produse_emag":
                    $pret = $dom->find('.product-new-price', 0)->find('text', 0)->innertext;
                    $pret = str_replace("&#46;", "", $pret);
                    //nu putem lua pur si simplu cu intval pentru ca ia numai ce e la dreapta punctului...
                    //sistemul englez vs cel francez de numerotare... (primul foloseste . ca decimal point si virgula ca separator de grupuri de 3 cifre, al doilea(adica noi) invers)
                    $pret = intval($pret);

                    //rating
                    $rat = $dom->find('span[class=star-rating-text gtm_rp101318]', 0);
                    if (is_object($rat))
                        $rating = floatval($rat->innertext);
                    else
                        $rating = floatval(0);

                    //disponibilitate
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
                    echo "\n$pret";
                    $pret = str_replace("&#46;", "", $pret);
                    //nu putem lua pur si simplu cu intval pentru ca ia numai ce e la dreapta punctului...
                    //sistemul englez vs cel francez de numerotare... (primul foloseste . ca decimal point si virgula ca separator de grupuri de 3 cifre, al doilea(adica noi) invers)
                    echo "\n$pret";
                    $pret = intval($pret);

                    //disponibilitate
                    $disp=$dom->find('div[class="product-shop js-product-view-thisProduct"]', 0)->children(0)->children(0)->children(2)->children(0)->children(0);
                    $disponibilitate = $disp->innertext;
                    $sql = "UPDATE $categorie SET pret=?,disponibilitate=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isi", $pret, $disponibilitate, $id);
                    break;
            }
            $stmt->execute();
        }
        $conn->close();
    }
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
    public static function toateProdusele($id)
    {

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
    public static function cautaProdusDbMongo($word)
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
    public  function getProdusDb($id,$categorie,$sursa){
            $sql = "SELECT * FROM :categorie where id = :id ";
            if($sursa=="emag")
                $cerere = $this->bd2->obtine_conexiune()->prepare($sql);
            else if($sursa=="altex")
                $cerere = $this->bd3->obtine_conexiune()->prepare($sql);
            else if($sursa=="cel")
                $cerere = $this->bd4->obtine_conexiune()->prepare($sql);
            $cerere->execute([
                'categorie'=>$categorie,
                'id'=>$id
            ]);
            $result=$cerere->fetch();
            return $result;
    }
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
        //print_r($jsonArrayResponse);
    }
    public function trimiteProdus2($produs,$id){

        $ch = curl_init('http://localhost:'.PORT_SERVER2.'/AppInsert');
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
    public function sterge($id, $session){
        $cURLConnection = curl_init();

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
