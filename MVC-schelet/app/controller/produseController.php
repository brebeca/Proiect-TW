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
           // echo $id;
            $params=explode("&id=", $params)[0];
           // echo $params;
            $this->model('produseModel');
            $this->model->trimite_produs($id,$params);
          //  echo json_encode(array("params"=>$params));

    }
    public function compara(){
        $this->view('produse\compara');
        $this->view->render();

    }
    public function incarcaProduse(){
        $this->model('produseModel');
        $produse=$this->model->toate_produsele(md5($_GET['id']));
        echo json_encode($produse['produse']);
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
        $source='';
        if(strpos($_GET['source'], 'emag') !==false)
            $source='emag';
        else  if(strpos($_GET['source'], 'altex') !==false)
            $source='altex';
        $produs=$this->model->get_produs_db($_GET['index'], $_GET['category'],$source);
        if(isset($produs['rating']))
            $rating=$produs['rating'];
        else $rating=0;
        $de_trimis=array("category"=>$_GET['category'],"title"=>$produs['nume'],"link"=>$produs['link'],"img_link"=>$produs['imagine']
        ,"price"=>$produs['pret'],"rating"=>$rating,
            "source"=>$source);
        $this->model->trimite_produs2($de_trimis,$id);

    }



}