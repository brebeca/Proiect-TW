<?php
class contactUsController extends Controller {



    public function contactUs($data=''){

        if($data==''){
            //echo "no data0;";
           $this->view('contactUs\contactUs');
         $this->view->render();
        }
        else{
            $this->model('contactModel');

            $params =explode('&',  urldecode($data));

            $email=explode('=',  $params[0])[1];
            $telephone_nr=explode('=',  $params[1])[1];
            $message=explode('=',  $params[2])[1];

            $this->model->addAnonimusContact( $email, $telephone_nr, $message);


        }
     }
}