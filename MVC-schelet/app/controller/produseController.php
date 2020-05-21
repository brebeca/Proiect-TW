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

    }
    public function compara(){
        $this->view('produse\compara');
        $this->view->render();

    }
    public function scrap(){
        $this->view('produse\produse_scrap');
        $this->view->render();
    }



}