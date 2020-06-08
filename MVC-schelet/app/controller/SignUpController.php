<?php
class SignUpController extends Controller {


    /**
     * @param string $data
     * daca se epeleaza fara marametrii se trmimite pagina de singUp
     * altfe, se preiau parametrii
     * se incearca inregistratea cu funtia sePoateIregistra() din model
     * se redireceaza catre pegina de login pentru a se loga cu contul crreat
     */
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

    /**
     * se verifica daca este setat codul de la redirecatea facuta de google
     *daca nu exista erori se preiau datele de la google si se inregistrara
     * in functie de caz, se face redirectarea
     */
   public function google(){
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