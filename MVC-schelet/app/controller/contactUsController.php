<?php
class contactUsController extends Controller {

    public function contactUS(){
        $this->view('contactUs\contactUs');
        $this->view->render();
    }

    public function contact_unregister($data=''){
            $this->model('contactModel');
            $email=$_GET['email'];
            $telephone_nr=$_GET['telephone'];
            $message=$_GET['text'];
            $name=$_GET['name'];
            gettype($this->model->addAnonimusContact( $name,$email, $telephone_nr, $message));
            header("Location:/index.php?message=Mesaj trimis");
     }

}