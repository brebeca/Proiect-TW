<?php
class ebay {

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
 //NU e inca functionabil
 public function save_image($url=''){
    if($url=='') return false;

    $ch =curl_init( $url);
    $file=fopen("./images/temp_ebay.jpeg","w");
    curl_setopt($ch, CURLOPT_FILE, $file);
    curl_setopt($ch, CURLOPT_HEADER,0);
    $raspuns=curl_exec($ch);
    curl_close($ch);
    echo $raspuns;
    fclose($file);
    return $raspuns;

 }
}