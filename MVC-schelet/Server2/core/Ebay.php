<?php


class Ebay
{
    public function __construct($nume_produs,$nr_produse){
      $this->get_Data($nume_produs,$nr_produse);
    }

    public function get_Data($nume_produs,$nr_produse){

     $product= $this->get_product_xml($nume_produs,$nr_produse);
     $xml = simplexml_load_string($product);

     $title=array();

     foreach ($xml->Product as $list){
          array_push($title, (string)$list->Title );

         foreach ($list->ItemSpecifics[0] as $list1){

             foreach ( $list1->Value as $item) {
                 echo $list1->Name."=>".$item. "<br>";
             }
        }
         echo"<br>";
     }
     echo "<pre>";
     print_r($title);
     echo "</pre>";
    }

    public function get_product_xml($produs='', $nr_de_produse=1){

        if($produs=='')return false;

        $url ="https://open.api.ebay.com/shopping?callname=FindProducts&responseencoding=XML&appid=birleanu-CompIT-PRD-4c545f399-aad3d24d&siteid=0&version=967&QueryKeywords=".$produs."&AvailableItemsOnly=true&MaxEntries=".$nr_de_produse;
        $ch =curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $raspuns=curl_exec($ch);
        curl_close($ch);

        return $raspuns;
    }
}