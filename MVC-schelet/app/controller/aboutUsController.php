<?php
class aboutUsController extends Controller {



    public function aboutUs(){

        if(isset($_SESSION["login"])) {
            if(isLoginSessionExpired())  {
                header("Location:index.php?session_expired=1");
            }
        }
        $this->view('aboutUs\aboutUs');
            $this->view->render();
     }
}