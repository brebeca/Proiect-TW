<?php
class loginController extends Controller {

    public function login($data="")
    {


        if ($data == "" || strpos($data, 'fail')) {
            $this->view('login\login');
            $this->view->render();
        } else {
            $this->model('loginModel');
            $params = explode('&', $data);
            $email = explode('=', $params[0])[1];
            $password = explode('=', $params[1])[1];
            $email = str_replace("%40", '@', $email);

            $result_of_db_search = $this->model->performLogin($password, $email);
            if ($result_of_db_search != null) {
                session_start();
                $_SESSION['login'] = $result_of_db_search[0]['id'];// echo $_SESSION['login'];
                $_SESSION['loggedin_time'] = time();
                $_SESSION['username'] = $result_of_db_search[0]['username'];
                echo $_SESSION['username'];
                header("Location:/index.php?login_succes=1");
            } else {
                header("Location:/login/login?login_fail=1");
            }
        }
    }
    public function google(){
        echo $_SESSION['login'];
    }



     public function logout($data=""){
      session_start();
      session_destroy();
      header("Location:/index.php?logout=1");
     }
}