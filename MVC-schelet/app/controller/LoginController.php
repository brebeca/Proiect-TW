<?php
class LoginController extends Controller {

  public function login($data="")
  {
    if ($data == "" || strpos($data, 'fail')) {
      $this->view('login\login');
      $this->view->render();
    } else {
      $this->model('LoginModel');
      $params = explode('&', $data);
      $email = explode('=', $params[0])[1];
      $password = explode('=', $params[1])[1];
      $email = str_replace("%40", '@', $email);

      $result_of_db_search = $this->model->performLogin($password, $email);
      if ($result_of_db_search != null) {

        header("Location:/index.php?login_succes=1&user=".$result_of_db_search[0]['username']."&id=".$result_of_db_search[0]['session']);
      } else {
       header("Location:/login/login?login_fail=1");
     }
   }
 }

 public function google($data=''){
  include('googleConfig.php');
}

public function logout($data=""){
  session_start();
  session_destroy();
  header("Location:/index.php?logout=1");
}

public function changePassword($data=""){
  $this->view('changePassword\changePassword');
  $this->view->render();
}

}