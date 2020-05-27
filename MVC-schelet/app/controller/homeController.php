<?php

class homeController extends Controller {

    public function index(){

     $this->view('home\index');
       $this->view->render();
    }

    public function cauta($de_cautat=''){

        if($de_cautat==''){
            header("Location:index.php");
        }
        else{
            $produs_de_cuatat=explode('=',  $de_cautat)[1];
            $numar_de_produse_returnate = 13;
            
            header("Location:/produse/produse?nume-produs=".$produs_de_cuatat."&nr-produse=".$numar_de_produse_returnate);
           
        }

    }

}
