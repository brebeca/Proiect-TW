<?php
class produseController extends Controller {



    public function produse(){
        if(isset($_SESSION["login"])) {
            if(isLoginSessionExpired())  {
                header("Location:index.php?session_expired=1");
            }

        }
       $this->view('produse\produse');
       $this->view->render();
     }
}