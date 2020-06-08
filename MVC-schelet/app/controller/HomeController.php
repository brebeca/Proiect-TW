<?php

class HomeController extends Controller {

    /**
     * inrtoarce pagina de home
     */
    public function index(){
        $this->view('home\index');
        $this->view->render();
    }

    /**
     * @param string $de_cautat
     * daca actiunea esta apleata fara un nume de produs redirecteaza catre pagina pricipala
     * altfel redirecteaza catre pagina cu porduse cu paramaetrii necesari
     */
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

    /**
     * apeleaza functia din ModelRegister pentru inregistrarea cookie-ului
     */
    public function cookie(){
        $this->model('ModelRegister');
        $this->model->sendCookie($_GET['cookie']);
    }

}
