<?php
class signUpController extends Controller {


    public function signUp($data=""){
        
        if($data==""){
            $this->view('signUp/signUp');

           $this->view->render();
        }
        else {
            $this->model('ModelRegister'); 
            $params =explode('&',  $data);

            $name= urldecode(explode('=',  $params[0])[1]);
            $email=urldecode(explode('=',  $params[1])[1]);
            $password=urldecode(explode('=',  $params[2])[1]);


            if( $this->model->sePoateIregistra($email, $name)==true)
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
   public function google($data=''){
       include( ROOT. 'public' . DIRECTORY_SEPARATOR ."googleConfig.php");
       $ok=false;
       if(isset($_GET["code"]))
       {
           echo $_GET["code"];
           if (!empty($google_client)) {
               $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
           }
           if(!isset($token['error']))
           {

               $google_client->setAccessToken($token['access_token']);
               $google_service = new Google_Service_Oauth2($google_client);
               $data = $google_service->userinfo->get();
               $name="google_user";
               if(!empty($data['given_name']))
               {
                   $name= $data['given_name'];
               }

               if(!empty($data['email']))
               {
                   $this->model('ModelRegister');
                   $exista=$this->model->sePoateIregistra($data['email']);
                  if( $exista===true) {
                      $res=$this->model->addUser($name, "no_pass", $data['email']);
                      if ( $res!= false){
                          header("Location:/index.php?login_succes=1&user=".$name."&id=".$res);
                      }
                      else header("Location:/index.php?error=1");
                  }
                  else{
                      header("Location:/index.php?login_succes=1&user=".$name."&id=".$exista);
                  }

               }
               else header("Location:/index.php?no_email=1");


           }
       }

   }
}