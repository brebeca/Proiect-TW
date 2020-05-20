<?php

class Product {
		public $title;
		public $detailsURL;
		public $photoURL;
		public $items = array();
	}

class ProduseModel extends Model{

	public static function cautaProdus($produs_de_cuatat, $numar_de_produse_returnate){
	 $product = ebay::get_product_xml($produs_de_cuatat, $numar_de_produse_returnate);
     $xml = simplexml_load_string($product);

     $products = array();

     foreach ($xml->Product as $list){
      
     	$productObj = new Product();
     	$productObj->title = (string)$list->Title;
     	$productObj->detailsURL = (string)$list->DetailsURL;

         if((string)$list->DisplayStockPhotos == 'true'){
	        $productObj->photoURL = (string)$list->StockPhotoURL;
         }

         if(isset($list->ItemSpecifics[0])){
         foreach ($list->ItemSpecifics[0] as $list1){

            foreach ( $list1->Value as $item) {
            	
            	array_push($productObj->items, $list1->Name.": ".$item);
            }
         }}
         array_push($products, $productObj);
     }
     
     return $products;
    
	}

    public static function produsele_mele($id)
    {

        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:801/GetProductsOwner');
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Session:'.$id
        ));

        $res = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        return json_decode($res);

    }

    public function trimite_produs($id,$params){

        //print_r($responseData);
        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, 'http://localhost:801/AppInsert?'.$params);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Session:'.$id
        ));

        $res = curl_exec($cURLConnection);
        curl_close($cURLConnection);

        $jsonArrayResponse = json_decode($res);
        print_r($jsonArrayResponse);
    }
}

?>