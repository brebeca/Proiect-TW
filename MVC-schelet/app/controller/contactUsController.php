<?php
class contactUsController extends Controller {



    public function contactUs($data=''){
        if(isset($_SESSION["login"])) {
            if(isLoginSessionExpired())  {
                header("Location:index.php?session_expired=1");
            }
        }
        if($data==''){
            //echo "no data0;";
           $this->view('contactUs\contactUs');
         $this->view->render();
        }
        else{
            $this->model('contactModel'); 
            $name =explode('&',  $data);

            $email=explode('=',  $params[0])[1];
            $telephone_nr=explode('=',  $params[1])[1];
            $email=str_replace ( "%40",  '@' ,  $email);
            $message=explode('=',  $params[2])[1];

            $this->model->addAnonimusContact($name, $email, $telephone_nr, $message);


        }
     }
}