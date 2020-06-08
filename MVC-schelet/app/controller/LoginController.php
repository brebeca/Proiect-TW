<?php
class LoginController extends Controller {

        /**
         * @param string $data
         * daca este apelata dintr-o redirectare intoarce pagina de login
         * altfel extrage parametrii necesati si inceraca logarea
         * daca functia de performLogin() este null redirecteaza cu mesaj de eroare
         * altfel trimtie la pagina principala cu informatia userului in query strig
         */
     public function login($data="")
      {
        if ($data == "" || strpos($data, 'fail')) {
          $this->view('login\login');
          $this->view->render();
        } else {
          $this->model('LoginModel');
          $params = explode('&', $data);
          $email = $_GET['email'];
          $password = explode('=', $params[1])[1];
         $result_of_db_search = $this->model->performLogin($password, $email);
          if ($result_of_db_search != null) {
              header("Location:/index.php?login_succes=1&user=".$result_of_db_search[0]['username']."&id=".$result_of_db_search[0]['session']);
          } else {
           header("Location:/login/login?login_fail=1");
         }
       }
     }


     public function google(){
      include('googleConfig.php');
    }

    /**
     * @param string $data
     * se distuge sesiunea inceputa la login
     * se face redirect la pagina principala
     */
    public function logout($data=""){
      session_start();
      session_destroy();
      header("Location:/index.php?logout=1");
    }

}