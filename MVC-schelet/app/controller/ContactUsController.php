<?php
class ContactUsController extends Controller {

    public function contactUS(){
        $this->view('contactUs\contactUs');
        $this->view->render();
    }

    /**
     *preia datele din uri si apeleaza functia din modelul ContactModel pentru a inregistra mesajul in bd
     */
    public function contactUnregister(){
            $this->model('ContactModel');
            $email=$_GET['email'];
            $telephone_nr=$_GET['telephone'];
            $message=$_GET['text'];
            $name=$_GET['name'];
            gettype($this->model->addAnonimusContact( $name,$email, $telephone_nr, $message));
            header("Location:/index.php?message=Mesaj trimis");
     }

}