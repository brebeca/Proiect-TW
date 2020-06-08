<?php
class Ebay {
    /**
     * @param string $produs
     * @param int $nr_de_produse
     * @return bool|string
     * daca nu se trimite numele produsului re returneaza false
     * altfel, se apeleaza API-ul de la eBay cu informatiile prinmite ca aprametru si se returneaza raspunsul
     */
 public static function getProductsInXml($produs='', $nr_de_produse=1){

    if($produs=='')return false;
    $produs=urlencode($produs);
    $url ="https://open.api.ebay.com/shopping?callname=FindProducts&responseencoding=XML&appid=".APP_ID."&siteid=0&version=967&QueryKeywords=".$produs."&AvailableItemsOnly=true&MaxEntries=".$nr_de_produse;
    $ch =curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $raspunse=curl_exec($ch);
    curl_close($ch);

    return $raspunse;
 }
}