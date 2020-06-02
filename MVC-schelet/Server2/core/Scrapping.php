<?php


class Scrapping
{
    public static function detalii_emag($link,$categorie){
        if(strpos($categorie,'telefoane')!==false)
            return detalii_emag_telefoane($link);
        else if(strpos($categorie,'calculatoare')!==false)
            return detalii_emag_calculatoare($link);
        else if(strpos($categorie,'electrocasnice')!==false)
            return detalii_emag_electrocasnice($link);
        else if(strpos($categorie,'imbracaminte')!==false)
            return detalii_emag_electrocasnice($link);
    }

    public static function detalii_altex($link,$categorie){
        if(strpos($categorie,'telefoane')!==false)
            return detalii_altex_telefoane($link);
        else if(strpos($categorie,'calculatoare')!==false)
            return detalii_altex_calculatoare($link);
        else if(strpos($categorie,'electrocasnice')!==false)
            return detalii_altex_electrocasnice($link);
    }

}
function detalii_emag_electrocasnice($link){
    $html = file_get_html($link);
    $product_det = array();
    foreach($html->find('table[class="table table-striped product-page-specifications"] tbody tr') as $a) {
        $product_det[$a->find('td.col-xs-4.text-muted', 0)->plaintext]=$a->find('td.col-xs-8', 0)->plaintext;
    }
    return $product_det;
}
function detalii_altex_electrocasnice($link){
    $html = file_get_html($link);
    $product_det = array();
    foreach($html->find('table[class="Specs-table"] tbody tr') as $a) {
        $product_det[$a->find('td.Specs-cell', 0)->plaintext] = $a->find('td.Specs-cell', 1)->plaintext;
    }
    return $product_det;
}
function count_capitals2($s) {
    return strlen(preg_replace('![^A-Z]+!', '', $s));
}
function detalii_emag_telefoane($link){
    $html = file_get_html($link);
    $product_det=array();
    $i=0;
    while($html->find('table.table.table-striped.product-page-specifications',$i)!=null){
        $table = $html->find('table.table.table-striped.product-page-specifications',$i);
        $table=$table->find('tbody',0);
        //  $rowData = array();

        foreach($table->find('tr') as $row) {
            //switch_emag_telefoane($product_det,$row,str_replace(' ','_',str_replace('.','-',$row->find('td.col-xs-4.text-muted',0)->plaintext)));
            $tag = str_replace(' ', '_', $row->find('td.col-xs-4.text-muted', 0)->plaintext);
            switch ($tag) {
                case "Sloturi_SIM":
                {
                    if (strpos($row->find('td.col-xs-8', 0)->plaintext, "Dual")!==false)
                        $product_det[$tag] = 2;
                    else if (strpos($row->find('td.col-xs-8', 0)->plaintext, "Single")!==false)
                        $product_det[$tag] = 1;
                    break;
                }
                case "Sistem_de_operare":
                case "Rezolutie_video":
                case "Tip_SIM":
                {
                    $product_det[$tag] = $row->find('td.col-xs-8', 0)->plaintext;
                    break;
                }
                case "Numar_nuclee" :
                {
                    $product_det[$tag] = intval($row->find('td.col-xs-8', 0)->plaintext);
                    break;
                }
                case "Senzori":
                {
                    $product_det[$tag] = array("Numar" => count_capitals2($row->find('td.col-xs-8', 0)->plaintext), "Senzori" => $row->find('td.col-xs-8', 0)->plaintext);
                    break;
                }
                case "Dimensiune_ecran":
                {
                    $product_det[$tag . "(inch)"] = floatval(explode(' ', $row->find('td.col-xs-8', 0)->plaintext)[0]);
                    //$product_det[$tag]=array("Numar"=>count_capitals($row->find('td.col-xs-8', 0)->plaintext),"Extensie"=>$row->find('td.col-xs-8', 0)->plaintext);
                    break;
                }
                case "Tip_display":
                {
                    $product_det["Tip_ecran"] = $row->find('td.col-xs-8', 0)->plaintext;
                    break;
                }
                case "Rezolutie_(pixeli)":
                {
                    $product_det["Rezolutie_ecran"] = $row->find('td.col-xs-8', 0)->plaintext;
                    break;
                }
                case "Memorie_RAM":
                case "Memorie_interna":
                {
                    $product_det[$tag . "(GB)"] = intval(explode(' ', $row->find('td.col-xs-8', 0)->plaintext)[0]);
                    break;
                }
                case "Rezolutie_camera_principala":
                {
                    $product_det["Cemera_principala(MP)"] = intval(explode(' ', $row->find('td.col-xs-8', 0)->plaintext)[0]);
                    break;
                }
                case "Rezolutie_camera_frontala":
                {
                    $product_det["Camera_frontala"] = $row->find('td.col-xs-8', 0)->plaintext;
                    break;
                }
                case "Capacitate_baterie":
                {
                    $product_det[$tag . "(mAh)"] = intval($row->find('td.col-xs-8', 0)->plaintext);
                    break;
                }
                case "Tehnologii":
                {
                    if(strpos($row->find('td.col-xs-8', 0)->plaintext,'2G')!==false)
                        $product_det["2G"] = 1 ;
                    else
                        $product_det["2G"] = 0 ;
                    if(strpos($row->find('td.col-xs-8', 0)->plaintext,"3G")!==false)
                        $product_det["3G"] = 1 ;
                    else
                        $product_det["3G"] = 0 ;
                    if(strpos($row->find('td.col-xs-8', 0)->plaintext,"4G")!==false)
                        $product_det["4G"] = 1 ;
                    else
                        $product_det["4G"] = 0 ;
                    break;
                }
            }
        }
        $i++;
    }
    return $product_det;

}
function detalii_altex_telefoane($link){
    $html = file_get_html($link);
    $product_det=array();
    $i=0;
    $product_det["2G"] = 0 ;
    $product_det["3G"] = 0 ;
    $product_det["4G"] = 0 ;
    while($html->find('table.Specs-table',$i)!=null){
        $table = $html->find('table.Specs-table',$i);
        $table=$table->find('tbody',0);
        foreach($table->find('tr') as $row) {
            $tag=str_replace(' ','_',str_replace('.','-',$row->find('td.Specs-cell',0)->plaintext));
            // $rowData[ str_replace(' ','_',str_replace('.','-',$row->find('td.Specs-cell',0)->plaintext))] = $row->find('td.Specs-cell',1)->plaintext;
            switch ($tag) {
                case "Sloturi_Sim":
                {
                    if (strpos($row->find('td.Specs-cell',1)->plaintext, "Dual")!==false)
                        $product_det[$tag] = 2;
                    else if (strpos($row->find('td.Specs-cell',1)->plaintext, "Single")!==false)
                        $product_det[$tag] = 1;
                    break;
                }
                case "Sistem_de_operare":
                case "Rezolutie_video":
                case "Tip_ecran":{
                    $product_det[$tag] = $row->find('td.Specs-cell',1)->plaintext;
                    break;
                }
                case "SIM":
                {
                    $product_det["Tip_SIM"] = $row->find('td.Specs-cell',1)->plaintext;
                    break;
                }
                case "Tip_procesor" :
                {
                    if(strpos($row->find('td.Specs-cell',1)->plaintext,'Octa')!==false)
                        $product_det["Numar_nuclee"] = 8;
                    if(strpos($row->find('td.Specs-cell',1)->plaintext,'Dual')!==false)
                        $product_det["Numar_nuclee"] = 2;
                    if(strpos($row->find('td.Specs-cell',1)->plaintext,'Quad')!==false)
                        $product_det["Numar_nuclee"] = 4;
                    if(strpos($row->find('td.Specs-cell',1)->plaintext,'Hexa')!==false)
                        $product_det["Numar_nuclee"] = 6;
                    break;
                }
                case "Senzori":
                {
                    $product_det[$tag] = array("Numar" => count_capitals2($row->find('td.Specs-cell',1)->plaintext), "Senzori" => $row->find('td.Specs-cell',1)->plaintext);
                    break;
                }
                case "Dimensiune_ecran_(inch)":
                {
                    $product_det["Dimensiune_ecran(inch)"] = floatval(explode(' ', $row->find('td.Specs-cell',1)->plaintext)[0]);
                    //$product_det[$tag]=array("Numar"=>count_capitals($row->find('td.col-xs-8', 0)->plaintext),"Extensie"=>$row->find('td.col-xs-8', 0)->plaintext);
                    break;
                }
                case "Rezolutie_ecran_(pixeli)":
                {
                    $product_det["Rezolutie_ecran"] = $row->find('td.Specs-cell',1)->plaintext;
                    break;
                }
                case "Memorie_RAM":
                {
                    $product_det[ "Memorie_RAM(GB)"] = intval(explode(' ', $row->find('td.Specs-cell',1)->plaintext)[0]);
                    break;
                }
                case "Capacitate_stocare":
                {
                    $product_det[ "Memorie_interna(GB)"] = intval(explode(' ', $row->find('td.Specs-cell',1)->plaintext)[0]);
                    break;
                }
                case "Rezolutie_(Mp)":
                {
                    $product_det["Cemera_principala(MP)"] = intval(explode('M', $row->find('td.Specs-cell',1)->plaintext)[0]);
                    break;
                }
                case "Selfie_Camera":
                {
                    $product_det["Camera_frontala"] = explode(',',$row->find('td.Specs-cell',1)->plaintext)[0];
                    break;
                }
                case "Capacitate_baterie_(mAh)":
                {
                    $product_det[ "Capacitate_baterie(mAh)"] = intval($row->find('td.Specs-cell',1)->plaintext);
                    break;
                }
                case "Retea_2G":
                {
                    $product_det["2G"] = 1 ;
                    break;
                }
                case "Retea_3G":
                {
                    $product_det["3G"] = 1 ;
                    break;
                }
                case "Retea_4G":
                {
                    $product_det["4G"] = 1 ;
                    break;
                }
            }
        }
        $i++;
    }
    return $product_det;
}
function detalii_emag_calculatoare($link)
{
    $html = file_get_html($link);
    $product_det = array();
    foreach($html->find('table[class="table table-striped product-page-specifications"] tbody tr') as $a){
            $tag = $a->find('td.col-xs-4.text-muted', 0)->plaintext;
            switch ($tag) {
                case "Producator procesor":
                {
                    $product_det["Tip procesor"]=$a->find('td.col-xs-8', 0)->plaintext;
                    break;
                }
                case "Tip procesor":
                {

                    $product_det["Tip procesor"]=$product_det["Tip procesor"]." ".$a->find('td.col-xs-8', 0)->plaintext;

                    break;
                }
                case "Numar nuclee":{
                    $product_det[$tag]=intval($a->find('td.col-xs-8', 0)->plaintext);
                    break;
                }
                case "Tip stocare":
                case "Tip placa video":
                case "Tehnologii audio":
                case "Sistem de operare":
                case "Porturi":
                case "Procesor grafic integrat":
                case "Tip memorie":{
                    $product_det[$tag]=$a->find('td.col-xs-8', 0)->plaintext;
                    break;
                }
                case "Capacitate SSD":
                case "Capacitate memorie":{
                    $product_det[$tag."(GB)"]=intval(explode(' ',$a->find('td.col-xs-8', 0)->plaintext)[0]);
                    break;
                }
                case "Wireless":{
                    $product_det["Wi-Fi"]=floatval(explode(' ',$a->find('td.col-xs-8', 0)->plaintext)[0]);
                    break;
                }
                case "Versiune Bluetooth":{
                    $product_det["Bluetooth"]=floatval($a->find('td.col-xs-8', 0)->plaintext);
                    break;
                }

            }
        }
    return $product_det;
}
function detalii_altex_calculatoare($link){
    $html = file_get_html($link);
    $product_det = array();
    foreach($html->find('table[class="Specs-table"] tbody tr') as $a){
        $tag = $a->find('td.Specs-cell', 0)->plaintext;
        switch ($tag) {
            case "Procesor grafic integrat":
            case "Tip memorie":
            case "Tip stocare":
            case "Tip placa video":
            case "Tip procesor":{
                $product_det[$tag]=$a->find('td.Specs-cell', 1)->plaintext;
                break;
            }
            case "Memorie maxima (GB)":{
                $product_det["Capacitate memorie (GB)"]=intval($a->find('td.Specs-cell', 1)->plaintext);
                break;
            }
            case "Capacitate stocare (GB)":{
                $product_det["Capacitate SSD (GB)"]=intval($a->find('td.Specs-cell', 1)->plaintext);
                break;
            }
            case "Numar nuclee":{
                $product_det[$tag]=intval($a->find('td.Specs-cell', 1)->plaintext);
                break;
            }
            case "Tehnologie audio":{
                $product_det["Tehnologii audio"]=$a->find('td.Specs-cell', 1)->plaintext;
                break;
            }
            case "USB 2.0":{
                $product_det["Porturi"]=$tag;
                break;
            }
            case "USB 3.2 Type C Gen 1":
            case "HDMI":
            case "USB 3.2 Type A Gen 1":{
                $product_det["Porturi"]=$product_det["Porturi"].' '.$tag;
                break;
            }
            case "Sistem operare":{
                $product_det["Sistem de operare"]=$a->find('td.Specs-cell', 1)->plaintext;
                break;
            }
            case "Wi-Fi":{
                $product_det[$tag]=floatval(explode(' ',$a->find('td.Specs-cell', 1)->plaintext)[0]);
                 break;
            }
            case "Bluetooth":{
                $product_det[$tag]=floatval(explode('v',$a->find('td.Specs-cell', 1)->plaintext)[1]);
                break;
            }
        }
    }
    return $product_det;
}