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

class ProduseModel extends Model{
    protected $bd2;
    public function __construct(){
        $this->bd2= new BD2;
    }
	public static function cautaProdus($produs_de_cuatat, $numar_de_produse_returnate){
	 $product = ebay::get_product_xml($produs_de_cuatat, $numar_de_produse_returnate);
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
                 foreach ( $list1->Value as $item){
                     array_push($productObj->items, $list1->Name.": ".$item);
                 }
             }
         }
         array_push($products, $productObj);
     }
     
     return $products;
    
	}
    public static function produsele_mele($id,$category)
    {

        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:801/GetProductsByCategory?category='.$category);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Session:'.$id
        ));

        $res = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        return json_decode($res,true);

    }
    public static function toate_produsele($id)
    {

        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:801/GetProductsAllCategory');
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Session:'.$id
        ));

        $res = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        return json_decode($res,true);

    }
    public static function cauta_produs_db($word)
    {

        $cURLConnection = curl_init();
        $session=md5("dGs0bXJqOTh1bmRlZmluZWQxNTg4NDEzMjE4ODA4Y3c");
        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:801/GetProductsByName?word='.urlencode($word));
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Session:'.$session
        ));

        $res = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        return json_decode($res,true);

    }
    public  function get_produs_db($id,$categorie){
            $sql = "SELECT * FROM ".$categorie." where id = :id ";
            $cerere = $this->bd2->obtine_conexiune()->prepare($sql);
            $cerere->execute([
                'id'=>$id
            ]);
            $result=$cerere->fetch();
            /*if(sizeof( $result)==0)
                return "nimic returnat";*/
            return $result;
    }
    public function trimite_produs($id,$params){

        //print_r($responseData);
        $cURLConnection = curl_init();
         $id=md5($id);
        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:801/AppInsert?'.$params);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Session:'.$id
        ));

        $res = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        $jsonArrayResponse = json_decode($res);
        //print_r($jsonArrayResponse);
    }
    public function trimite_produs2($produs,$id){

        $ch = curl_init('http://localhost:801/AppInsert');
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

        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:801/DeleteProduct?id='.$id);
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

?>