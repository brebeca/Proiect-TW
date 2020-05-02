<?php

class homeController extends Controller {

    public function index(){
        if(isset($_SESSION["login"])) {
            if(isLoginSessionExpired())  {
                header("Location:index.php?session_expired=1");
            }
        }
       

     $this->view('home\index');
       $this->view->render();
    }

    public function cauta($de_cautat=''){
        if(isset($_SESSION["login"])) {
            if(isLoginSessionExpired())  {
                header("Location:index.php?session_expired=1");
            }
        }
        if($de_cautat==''){
            header("Location:index.php");
        }
        else{
            $produs_de_cuatat=explode('=',  $de_cautat)[1];
            $numar_de_produse_returnate = 10;
             
            //apeleaza API-ul ebay si retrage paginil xml cu informatii despre  produsele cu numle cautat 
            $raspuns_ebay=ebay::get_product_xml($produs_de_cuatat, $numar_de_produse_returnate);
            if($raspuns_ebay==false)
                header("Location:index.php?cautare_esuata=1");
            
            //domul ne ajuta sa umblam prin pagina xml 
            $dom = new DOMDocument;
            $dom->loadXML($raspuns_ebay);
             
            //daca tagul <DisplayStockPhotos> este true atunci exista tagul <StockPhotoURL> cu linkul la imaginea cu produsul 
            $has_photos = $dom->getElementsByTagName('DisplayStockPhotos');
            $titles=$dom->getElementsByTagName('Title');
            $exista_poza=array();
            
            //se verifica care elemente au link cu poze si care nu 
            for ($i = 0; $i < count($has_photos); $i++){

                if($dom->saveXML($has_photos[$i])==true){
                    echo $dom->saveXML($titles[$i])." => ";
                    $url_image=$dom->saveXML( $has_photos[$i]);
                    echo $url_image;
                    echo "<br>";
                }
                
            }
           
        }

    }

}
