<?php


class Scrapping
{
    /**
     * @param $link
     * @return string
     * se verifica categoria prodosului si se apeleaza funcia de scrap potrivita
     */
    public static function ebayCategory($link){
        $html = file_get_html($link);
       foreach ( $html->find('nav[class="breadcrumb clearfix"] ol li a span') as $category){
           if(strpos($category->plaintext,"Cell Phones")!==false&&strpos($category->plaintext,"Smartphones")!==false)
               return 'telefoane';
           if(strpos($category->plaintext,"Headphones")===0||strpos($category->plaintext,"Headsets")===0)
               return 'casti';
           if(strpos($category->plaintext,"PC Laptops")!==false&&strpos($category->plaintext,"Netbooks")!==false)
               return 'calculatoare';
       }
       return 'search';

    }

    /**
     * @param $link
     * @return string
     * se verifica categoria prodosului si se apeleaza funcia de scrap potrivita
     */
    public static function detaliiEmag($link,$categorie){
        if(strpos($categorie,'telefoane')!==false)
            return detaliiEmagTelefoane($link);
        else if(strpos($categorie,'calculatoare')!==false)
            return detaliiEmagCalculatoare($link);
        else if(strpos($categorie,'electrocasnice')!==false)
            return detaliiEmagElectrocasnice($link);
        else if(strpos($categorie,'casti')!==false)
            return detaliiEmagCasti($link);
        return null;
    }

    /**
     * @param $link
     * @return string
     * se verifica categoria prodosului si se apeleaza funcia de scrap potrivita
     */
    public static function detaliiAltex($link,$categorie){
        if(strpos($categorie,'telefoane')!==false)
            return detaliiAltexTelefoane($link);
        else if(strpos($categorie,'calculatoare')!==false)
            return detaliiAltexCalculatoare($link);
        else if(strpos($categorie,'electrocasnice')!==false)
            return detaliiAltexElectrocasnice($link);
        else if(strpos($categorie,'casti')!==false)
            return detaliiAltexCasti($link);
        return null;
    }

    /**
     * @param $link
     * @return string
     * se verifica categoria prodosului si se apeleaza funcia de scrap potrivita
     */
    public static function detaliiEbay($link,$categorie)
    {
        if (strpos($categorie, 'telefoane') !== false)
            return detaliiEbayTelefoane($link);

        return detaliiEbayGeneral($link);
    }

    /**
     * @param $link
     * @return string
     * se verifica categoria prodosului si se apeleaza funcia de scrap potrivita
     */
    public static function detaliiCel($link,$categorie){
        if (strpos($categorie, 'telefoane') !== false)
            return detaliiCelTelefoane($link);
        if(strpos($categorie,'calculatoare')!==false)
            return detaliiCelCalculatoare($link);
        if(strpos($categorie,'casti')!==false)
            return detaliiCelCasti($link);
        if(strpos($categorie,'electrocasnice')!==false)
            return detaliiCelElectrocasnice($link);
        return null;
    }
}

/**
 * @param $link
 * @return array
 * se incerca fisierul html de la linkul din paramentru
 * se parcurege tabelul cu detalii
 * pintr-un switch se alcatuieste array-ul asociativ cu detalile produsului
 */
function detaliiEmagElectrocasnice($link){
    $html = file_get_html($link);
    $product_det = array();
    foreach($html->find('table[class="table table-striped product-page-specifications"] tbody tr') as $a) {
        $product_det[$a->find('td.col-xs-4.text-muted', 0)->plaintext]=$a->find('td.col-xs-8', 0)->plaintext;
    }
    return $product_det;
}
function detaliiAltexElectrocasnice($link){
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
/**
 * @param $link
 * @return array
 * se incerca fisierul html de la linkul din paramentru
 * se parcurege tabelul cu detalii
 * pintr-un switch se alcatuieste array-ul asociativ cu detalile produsului
 */
function detaliiEmagTelefoane($link){
    $html = file_get_html($link);
    $product_det=array();
    $i=0;
    while($html->find('table.table.table-striped.product-page-specifications',$i)!=null){
        $table = $html->find('table.table.table-striped.product-page-specifications',$i);
        $table=$table->find('tbody',0);

        foreach($table->find('tr') as $row) {
            $tag =$row->find('td.col-xs-4.text-muted', 0)->plaintext;
            switch ($tag) {
                case "Sloturi SIM":
                {
                    if (strpos($row->find('td.col-xs-8', 0)->plaintext, "Dual")!==false)
                        $product_det[$tag] = 2;
                    else if (strpos($row->find('td.col-xs-8', 0)->plaintext, "Single")!==false)
                        $product_det[$tag] = 1;
                    break;
                }
                case "Sistem de operare":
                case "Rezolutie video":
                case "Tip SIM":
                {
                    $product_det[$tag] = $row->find('td.col-xs-8', 0)->plaintext;
                    break;
                }
                case "Numar nuclee" :
                {
                    $product_det[$tag] = intval($row->find('td.col-xs-8', 0)->plaintext);
                    break;
                }
                case "Senzori":
                {
                    $product_det[$tag] = array("Numar" => count_capitals2($row->find('td.col-xs-8', 0)->plaintext), "Senzori" => $row->find('td.col-xs-8', 0)->plaintext);
                    break;
                }
                case "Dimensiune ecran":
                {
                    $product_det[$tag . "(inch)"] = floatval(explode(' ', $row->find('td.col-xs-8', 0)->plaintext)[0]);
                   break;
                }
                case "Tip display":
                {
                    $product_det["Tip ecran"] = $row->find('td.col-xs-8', 0)->plaintext;
                    break;
                }
                case "Rezolutie (pixeli)":
                {
                    $product_det["Rezolutie ecran"] = $row->find('td.col-xs-8', 0)->plaintext;
                    break;
                }
                case "Memorie RAM":
                case "Memorie interna":
                {
                    $product_det[$tag . "(GB)"] = intval(explode(' ', $row->find('td.col-xs-8', 0)->plaintext)[0]);
                    break;
                }
                case "Rezolutie camera principala":
                {
                    $product_det["Cemera principala(MP)"] = intval(explode(' ', $row->find('td.col-xs-8', 0)->plaintext)[0]);
                    break;
                }
                case "Rezolutie camera frontala":
                {
                    $product_det["Camera frontala"] = $row->find('td.col-xs-8', 0)->plaintext;
                    break;
                }
                case "Capacitate baterie":
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
function detaliiAltexTelefoane($link){
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
            $tag=$row->find('td.Specs-cell',0)->plaintext;
            $value=$row->find('td.Specs-cell',1)->plaintext;
           switch ($tag) {
                case "Sloturi Sim":
                {
                    if (strpos($row->find('td.Specs-cell',1)->plaintext, "Dual")!==false)
                        $product_det[$tag] = 2;
                    else if (strpos($row->find('td.Specs-cell',1)->plaintext, "Single")!==false)
                        $product_det[$tag] = 1;
                    break;
                }
                case "Sistem de operare":
                case "Rezolutie video":
                case "Tip ecran":{
                    $product_det[$tag] = $row->find('td.Specs-cell',1)->plaintext;
                    break;
                }
                case "SIM":
                {
                    $product_det["Tip SIM"] = $row->find('td.Specs-cell',1)->plaintext;
                    break;
                }
                case "Tip procesor" :
                {
                    if(strpos($row->find('td.Specs-cell',1)->plaintext,'Octa')!==false)
                        $product_det["Numar nuclee"] = 8;
                    if(strpos($row->find('td.Specs-cell',1)->plaintext,'Dual')!==false)
                        $product_det["Numar nuclee"] = 2;
                    if(strpos($row->find('td.Specs-cell',1)->plaintext,'Quad')!==false)
                        $product_det["Numar nuclee"] = 4;
                    if(strpos($row->find('td.Specs-cell',1)->plaintext,'Hexa')!==false)
                        $product_det["Numar nuclee"] = 6;
                    break;
                }
                case "Senzori":
                {
                    $product_det[$tag] = array("Numar" => count_capitals2($row->find('td.Specs-cell',1)->plaintext), "Senzori" => $row->find('td.Specs-cell',1)->plaintext);
                    break;
                }
                case "Dimensiune ecran (inch)":
                {
                    $product_det["Dimensiune ecran(inch)"] = floatval(explode(' ', $value)[0]);
                    //$product_det[$tag]=array("Numar"=>count_capitals($row->find('td.col-xs-8', 0)->plaintext),"Extensie"=>$row->find('td.col-xs-8', 0)->plaintext);
                    break;
                }
                case "Rezolutie ecran (pixeli)":
                {
                    $product_det["Rezolutie ecran"] = $value;
                    break;
                }
                case "Memorie RAM":
                {
                    $product_det[ "Memorie RAM(GB)"] = intval(explode(' ', $value)[0]);
                    break;
                }
                case "Capacitate stocare":
                {
                    $product_det[ "Memorie interna(GB)"] = intval(explode(' ', $value)[0]);
                    break;
                }
                case "Rezolutie (Mp)":
                {
                    $product_det["Cemera principala(MP)"] = intval(explode('M', $value)[0]);
                    break;
                }
                case "Selfie Camera":
                {
                    $product_det["Camera frontala"] = intval(explode('M',$value)[0]);
                    break;
                }
                case "Capacitate baterie (mAh)":
                {
                    $product_det[ "Capacitate baterie(mAh)"] = intval($value);
                    break;
                }
                case "Retea 2G":
                {
                    $product_det["2G"] = 1 ;
                    break;
                }
                case "Retea 3G":
                {
                    $product_det["3G"] = 1 ;
                    break;
                }
                case "Retea 4G":
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
/**
 * @param $link
 * @return array
 * se incerca fisierul html de la linkul din paramentru
 * se parcurege tabelul cu detalii
 * pintr-un switch se alcatuieste array-ul asociativ cu detalile produsului
 */
function detaliiEmagCalculatoare($link)
{
    $html = file_get_html($link);
    $product_det = array();
    foreach($html->find('table[class="table table-striped product-page-specifications"] tbody tr') as $a){
            $tag = $a->find('td.col-xs-4.text-muted', 0)->plaintext;
            $value=$a->find('td.col-xs-8', 0)->plaintext;
            switch ($tag) {
                case "Producator procesor":
                {
                    $product_det["Tip procesor"]=$value;
                    break;
                }
                case "Tip procesor":
                {

                    $product_det["Tip procesor"]=$product_det["Tip procesor"]." ".$value;

                    break;
                }
                case "Numar nuclee":{
                    $product_det[$tag]=intval($value);
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
                    $product_det[$tag."(GB)"]=intval(explode(' ',$value)[0]);
                    break;
                }
                case "Wireless":{
                    $product_det["Wi-Fi"]=floatval(explode(' ',$value)[0]);
                    break;
                }
                case "Versiune Bluetooth":{
                    $product_det["Bluetooth"]=floatval($value);
                    break;
                }

            }
        }
    return $product_det;
}
function detaliiAltexCalculatoare($link){
    $html = file_get_html($link);
    $product_det = array();
    foreach($html->find('table[class="Specs-table"] tbody tr') as $a){
        $tag = $a->find('td.Specs-cell', 0)->plaintext;
        $value=$a->find('td.Specs-cell', 1)->plaintext;
        switch ($tag) {
            case "Procesor grafic integrat":
            case "Tip memorie":
            case "Tip stocare":
            case "Tip placa video":
            case "Tip procesor":{
                $product_det[$tag]=$value;
                break;
            }
            case "Memorie maxima (GB)":{
                $product_det["Capacitate memorie (GB)"]=intval($value);
                break;
            }
            case "Capacitate stocare (GB)":{
                $product_det["Capacitate SSD (GB)"]=intval($value);
                break;
            }
            case "Numar nuclee":{
                $product_det[$tag]=intval($value);
                break;
            }
            case "Tehnologie audio":{
                $product_det["Tehnologii audio"]=$value;
                break;
            }
            case "USB 2.0":{
                $product_det["Porturi"]=$tag;
                break;
            }
            case "USB 3.2 Type C Gen 1":
            case "HDMI":
            case "USB 3.2 Type A Gen 1":{
                if(isset($product_det["Porturi"]))
                $product_det["Porturi"]=$product_det["Porturi"].' '.$tag;
                else
                    $product_det["Porturi"]=$tag;
                break;
            }
            case "Sistem operare":{
                $product_det["Sistem de operare"]=$value;
                break;
            }
            case "Wi-Fi":{
                $product_det[$tag]=floatval(explode(' ',$value)[0]);
                 break;
            }
            case "Bluetooth":{
                $product_det[$tag]=floatval(explode('v',$value)[1]);
                break;
            }
        }
    }
    return $product_det;
}
/**
 * @param $link
 * @return array
 * se incerca fisierul html de la linkul din paramentru
 * se parcurege tabelul cu detalii
 * pintr-un switch se alcatuieste array-ul asociativ cu detalile produsului
 */
function detaliiAltexCasti($link)
{
    $html = file_get_html($link);
    $product_det = array();
    foreach ($html->find('table[class="Specs-table"] tbody tr') as $a) {
        $tag = $a->find('td.Specs-cell', 0)->plaintext;
        $value=$a->find('td.Specs-cell', 1)->plaintext;
        switch ($tag) {
            case "Tehnologie":
            {
                $product_det[$tag]=$value;
                break;
            }
            case "Stil":{
                $product_det["Tip"]=$value;
                break;
            }
            case "Raspuns in frecventa (Hz)":
            {
                $product_det["Raspuns in frecventa"]=$value;
                break;
            }
            case "Impedanta (ohm)":
            {
                $product_det[$tag]=intval(explode(' ',$value)[0]);
                break;
            }
            case "Diametru difuzor (mm)":
            {
                $product_det[$tag]=floatval(explode('m',$value)[0]);
                break;
            }
        }
    }
    return $product_det;
}
function detaliiEmagCasti($link)
{
    $html = file_get_html($link);
    $product_det = array();
    foreach ($html->find('table[class="table table-striped product-page-specifications"] tbody tr') as $a) {
        $tag = $a->find('td.col-xs-4.text-muted', 0)->plaintext;
        $value=$a->find('td.col-xs-8', 0)->plaintext;
        switch ($tag) {
            case "Tehnologie":
            case "Raspuns in frecventa":
            case "Tip":
            {
                $product_det[$tag] = $value;
                break;
            }

            case "Impedanta iesire":
            {
                $product_det["Impedanta (ohm)"] = intval(explode(' ', $value)[0]);
                break;
            }
            case "Diametru difuzor":
            {
                $product_det[$tag] = floatval(explode(' ',$value)[0]);
                break;
            }
        }

    }
    return $product_det;
}
/**
 * @param $link
 * @return array
 * se incerca fisierul html de la linkul din paramentru
 * se parcurege tabelul cu detalii
 * pintr-un switch se alcatuieste array-ul asociativ cu detalile produsului
 */
function detaliiEbayTelefoane($link){
    $html = file_get_html($link);
    $product_det = array();
    $html=$html->find('div[class="btf-content"]',0)->first_child()->first_child()->first_child()->find('div[class="description"]',0)->first_child();
     foreach($html->find('div[class="spec-row"]') as $a1) {
       // array_push($product_det,$a1->find('ul li',0)->plaintext);
        foreach ($a1->find('ul li') as $a2) {
            if (isset($a2->find('div[class="s-name"]', 0)->plaintext)) {
                $tag = $a2->find('div[class="s-name"]', 0)->plaintext;
                $value = $a2->find('div[class="s-value"]', 0)->plaintext;
                switch ($tag) {
                    case "SIM Card Slot":
                    {
                        if(strpos($value,'Single')!==false)
                            $product_det["Sloturi Sim"]=1;
                        else
                            $product_det["Sloturi Sim"]=2;
                        break;
                    }
                    case "RAM":{
                        $product_det["Memorie RAM(GB)"]=intval(explode(' ',$value)[0]);
                        break;
                    }
                    case "Processor":{
                        if(strpos($value,'Octa')!==false)
                            $product_det["Numar nuclee"] = 8;
                        if(strpos($value,'Dual')!==false)
                            $product_det["Numar nuclee"] = 2;
                        if(strpos($value,'Quad')!==false)
                            $product_det["Numar nuclee"] = 4;
                        if(strpos($value,'Hexa')!==false)
                            $product_det["Numar nuclee"] = 6;
                        break;
                    }
                    case "Camera Resolution":{
                        $product_det["Cemera principala(MP)"]=intval(explode('M',$value)[0]);
                        break;
                    }
                    case "Battery Capacity":{
                        $product_det["Capacitate baterie(mAh)"]=intval(explode('m',$value)[0]);
                        break;
                    }
                    case "Network Generation":
                    {
                        if (strpos($value, '2G') !== false)
                            $product_det["2G"] = 1;
                        else
                            $product_det["2G"] = 0;
                        if (strpos($value, "3G") !== false)
                            $product_det["3G"] = 1;
                        else
                            $product_det["3G"] = 0;
                        if (strpos($value, "4G") !== false)
                            $product_det["4G"] = 1;
                        else
                            $product_det["4G"] = 0;
                        break;
                    }
                    case "Storage Capacity":{
                        $product_det["Memorie interna(GB)"]=intval(explode(' ',$value));
                    }
                }

        }
        }
    }


    return $product_det;
}
function detaliiEbayGeneral($link){
    $html = file_get_html($link);
    $product_det = array();
    $html=$html->find('div[class="btf-content"]',0)->first_child()->first_child()->first_child()->find('div[class="description"]',0)->first_child();
    foreach($html->find('div[class="spec-row"]') as $a1) {
        // array_push($product_det,$a1->find('ul li',0)->plaintext);
        foreach ($a1->find('ul li') as $a2) {
            if (isset($a2->find('div[class="s-name"]', 0)->plaintext)) {
                $tag = $a2->find('div[class="s-name"]', 0)->plaintext;
                $value = $a2->find('div[class="s-value"]', 0)->plaintext;
                $product_det[$tag]=$value;
            }
        }
    }
    return $product_det;
}
/**
 * @param $link
 * @return array
 * se incerca fisierul html de la linkul din paramentru
 * se parcurege tabelul cu detalii
 * pintr-un switch se alcatuieste array-ul asociativ cu detalile produsului
 */
function detaliiCelTelefoane($link){
    $context = stream_context_create(
        array(
            "http" => array(
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
            )
        )
    );
    $html = file_get_html($link,false, $context);
    $product_det = array();
    $columns=array('oddTr','evenTr');
    foreach ($columns as $col) {
        foreach ($html->find('tr.' . $col) as $a) {
            $tag = $a->find('td.charName', 0)->plaintext;
            $value = '';
            foreach ($a->find('div') as $val)
                $value .= $val->plaintext . ',';
            $value = trim($value, ',');
            switch ($tag){
                case "Dual Sim:":{
                    if(strpos($value,"Da"))
                        $product_det['Sloturi SIM']=2;
                    else
                        $product_det['Sloturi SIM']=1;
                    break;
                }
                case "Sistem de operare:":{
                    $product_det['Sistem de operare']=$value;
                    break;
                }
                case "Numar nuclee procesor:":{
                    if(strpos($value,'Octa')!==false)
                        $product_det["Numar nuclee"] = 8;
                    if(strpos($value,'Dual')!==false)
                        $product_det["Numar nuclee"] = 2;
                    if(strpos($value,'Quad')!==false)
                        $product_det["Numar nuclee"] = 4;
                    if(strpos($value,'Hexa')!==false)
                        $product_det["Numar nuclee"] = 6;
                    break;
                }
                case "Tehnologie:":{
                    if(strpos($value,'3G')!==false) {
                        $product_det["3G"] = 1;
                        $product_det["2G"] = 1 ;
                    }
                    else {
                        $product_det["3G"] = 0;
                        $product_det["2G"] = 0 ;
                    }
                    if(strpos($value,'4G')!==false)
                        $product_det["4G"] = 1 ;
                    else
                        $product_det["4G"] = 0 ;
                    break;
                }
                case "Senzor:":{
                    $nr=count(explode(',',$value));
                    $product_det["Senzori"]=array("Numar" => $nr, "Senzori" => $value);
                     break;
                }
                case "Dimensiune Display (inches):":{
                    $product_det['Dimensiune ecran(inch)']=floatval($value);
                    break;
                }
                case "Tip display:":{
                    $product_det['Tip ecran']=$value;
                    break;
                }
                case "Capacitate (GB):":{
                    $product_det['Memorie interna(GB)']=intval(explode('G',$value)[0]);
                    break;
                }
                case "Memorie RAM:":{
                    $product_det['Memorie RAM(GB)']=intval(explode('G',$value)[0]);
                    break;
                }
                case "Camera:":{
                    $product_det["Cemera principala(MP)"]=intval(explode(' ',$value)[0]);
                    break;
                }
                case "Rezolutie (pixeli):":{
                    $product_det['Rezolutie ecran']=explode('pix',$value)[0];
                }
            }
        }
    }
    return $product_det;
}
function detaliiCelCalculatoare($link)
{
    $context = stream_context_create(
        array(
            "http" => array(
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
            )
        )
    );
    $html = file_get_html($link, false, $context);
    $product_det = array();
    $columns = array('oddTr', 'evenTr');
    $product_det['Porturi']='';
    foreach ($columns as $col) {
        foreach ($html->find('tr.' . $col) as $a) {
            $tag = $a->find('td.charName', 0)->plaintext;
            $value = '';
            foreach ($a->find('div') as $val)
                $value .= $val->plaintext . ',';
            $value = trim($value, ',');
            switch ($tag) {
                case "Model Procesor:":{
                    $product_det['Tip procesor']=$value;
                    break;
                }
                case "Procesor:":{
                    if(strpos($value,'Octa')!==false)
                        $product_det["Numar nuclee"] = 8;
                    if(strpos($value,'Dual')!==false)
                        $product_det["Numar nuclee"] = 2;
                    if(strpos($value,'Quad')!==false)
                        $product_det["Numar nuclee"] = 4;
                    if(strpos($value,'Hexa')!==false)
                        $product_det["Numar nuclee"] = 6;
                    break;
                }
                case "Memorie standard:":{
                    $product_det["Capacitate memorie(GB)"]=intval(explode('G',$value)[0]);
                    break;
                }
                case "Tip unitate stocare:":{
                    $product_det["Tip stocare"]=$value;
                    break;
                }
                case "Capacitate HDD:":{
                    $product_det['Capacitate SSD(GB)']=intval(explode('G',$value)[0]);
                    break;
                }
                case "Audio:":{
                    $product_det['Tehnologii audio']=$value;
                    break;
                }
                case "USB 2.0:":{
                    $product_det['Porturi'].=' USB 2.0 ';
                    break;
                }
                case "HDMI:":{
                    $product_det['Porturi'].=' HDMI ';
                    break;
                }
                case "Alte porturi:":{
                    $product_det['Porturi'].=$value;
                    break;
                }
                case "Wireless":{
                    $product_det['Wi-Fi']=floatval(explode(' ',$value)[0]);
                    break;
                }
                case "Bluetooth:":{
                    $product_det["Bluetooth"]=floatval($value);
                    break;
                }

            }
        }
    }
    return $product_det;
}
/**
 * @param $link
 * @return array
 * se incerca fisierul html de la linkul din paramentru
 * se parcurege tabelul cu detalii
 * pintr-un switch se alcatuieste array-ul asociativ cu detalile produsului
 */
function detaliiCelElectrocasnice($link){
    $context = stream_context_create(
        array(
            "http" => array(
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
            )
        )
    );
    $html = file_get_html($link, false, $context);
    $product_det = array();
    $columns = array('oddTr', 'evenTr');
    foreach ($columns as $col) {
        foreach ($html->find('tr.' . $col) as $a) {
            $tag = $a->find('td.charName', 0)->plaintext;
            $value = '';
            foreach ($a->find('div') as $val)
                $value .= $val->plaintext . ',';
            $value = trim($value, ',');
            $product_det[$tag]=$value;
        }
    }
    return $product_det;
}
function detaliiCelCasti($link){
    $context = stream_context_create(
        array(
            "http" => array(
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
            )
        )
    );
    $html = file_get_html($link, false, $context);
    $product_det = array();
    $columns = array('oddTr', 'evenTr');
    foreach ($columns as $col) {
        foreach ($html->find('tr.' . $col) as $a) {
            $tag = $a->find('td.charName', 0)->plaintext;
            $value = '';
            foreach ($a->find('div') as $val)
                $value .= $val->plaintext . ',';
            $value = trim($value, ',');
           // $product_det[$tag]=$value;
            switch($tag){
                case "Tehnologie:":
                case "Tip:":{
                    $product_det[$tag]=$value;
                    break;
                }
                case "Diametru:":{
                    $product_det["Diametru difuzor"]=floatval(explode(' ',$value)[0]);
                    break;
                }
                case "Impedanta:":{
                    $product_det["Impedanta (ohm)"] = intval(explode(' ', $value)[0]);
                    break;
                }
                case "Frecventa de raspuns:":{
                    $product_det["Raspuns in frecventa"]=$value;
                    break;
                }
            }
        }
    }
    return $product_det;
}