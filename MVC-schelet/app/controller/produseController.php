<?php
class produseController extends Controller {



    public function produse(){

       $this->view('produse\produse');
       $this->view->render();
     }
     public function alegere_ebay($data=''){
            $params= explode('?',$_SERVER['REQUEST_URI'])[1];
            //$id=explode("&id=",$params)[1];
            $id=$_GET['id'];
            echo $id;
            $params=explode("&id=", $params)[0];
            echo $params;
            $this->model('produseModel');
            $this->model->trimite_produs($id,$params);

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

    public function alegere(){

        $id=$_GET['id'];
        $this->model('produseModel');
        $produs=$this->model->get_produs_db($_GET['index'], $_GET['category']);
        $de_trimis=array("category"=>$_GET['category'],"title"=>$produs['nume'],"link"=>$produs['link'],"img_link"=>$produs['imagine']
        ,"price"=>$produs['pret'],"details"=>array("rating"=>$produs['rating']),"source"=>$_GET['source']);
        $this->model->trimite_produs2($de_trimis,$id);

    }



}