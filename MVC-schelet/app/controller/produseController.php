<?php
class produseController extends Controller {



    public function produse(){

       $this->view('produse\produse');
       $this->view->render();
     }
     public function alegere_ebay($data=''){
            $params= explode('?',$_SERVER['REQUEST_URI'])[1];
            $id=explode("&id=",$params)[1];
            $params=str_replace('-','/',explode("&id=", $params)[0]);
            $this->model('produseModel');
            $this->model->trimite_produs($id,$params);
            header("Location:/produse/produse?nume-produs=".urlencode($_GET['key_word'])."&nr-produse="."10");

    }
    public function compara(){
        $this->view('produse\compara');
        $this->view->render();

    }
    public function scrap(){
        //model

        $this->view('produse\produse_scrap?categorie=');
        $this->view->render();
    }

    public function sterge_produs(){
        $this->model('produseModel');
        $this->model->sterge($_GET['product_id'],$_GET['session']);
    }



}