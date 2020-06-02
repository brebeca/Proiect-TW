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
            $numar_de_produse_returnate = 20;
            
            header("Location:/produse/produse?nume-produs=".$produs_de_cuatat."&nr-produse=".$numar_de_produse_returnate);
           
        }


    }
    public function cookie(){
        //$_GET['value']
        function getRequestHeaders() {
            $headers = array();
            foreach($_SERVER as $key => $value) {
                if (substr($key, 0, 5) <> 'HTTP_') {
                    continue;
                }
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                $headers[$header] = $value;
            }
            return $headers;
        }
        $headers = getRequestHeaders();
        if(!isset($_COOKIE['user'])){
            $value=$headers['Cookie'];
            setcookie("user",$value , time()+24*60*60);
            $this->model('ModelRegister');
            $this->model->send_cookie( $value);
            echo "se seteaza din nou cu ".$headers['Cookie'];
        }
        else echo $_COOKIE['user'];

    }

}
