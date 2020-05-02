<?php
class signUpController extends Controller {


    public function signUp($data=""){
        
        if($data==""){
            $this->view('signUp/signUp');
           
           $this->view->render();
           // var_dump($this);
          
        }
        else {
            $this->model('ModelRegister'); 
            $params =explode('&',  $data);

            $name= explode('=',  $params[0])[1];
            $email=explode('=',  $params[1])[1];
            $password=explode('=',  $params[2])[1];
            $email=str_replace ( "%40",  '@' ,  $email);

            if( $this->model->sePoateIregistra($email, $name))
             {
                $this->model->addUser($name, $password, $email);
                $this->view('login/login');
                $this->view->render();
             }
            else{
                $this->view('signUp/signUp');
                $this->view->render();
            }
           
        }
    }
}