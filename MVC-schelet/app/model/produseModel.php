<?php

class Product {
		public $title;
		public $detailsURL;
		public $photoURL;
		public $items = array();
	}

class ProduseModel extends Model{

	public function cautaProdus($produs_de_cuatat, $numar_de_produse_returnate){
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

         foreach ($list->ItemSpecifics[0] as $list1){

            foreach ( $list1->Value as $item) {
            	
            	array_push($productObj->items, $list1->Name.": ".$item);
            }
         }
         array_push($products, $productObj);
     }
     
     return $products;
    
	}
}

?>