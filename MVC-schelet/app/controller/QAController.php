<?php
class QAController extends Controller {



    public function QA(){
        if(isset($_SESSION["login"])) {
            if(isLoginSessionExpired())  {
                header("Location:index.php?session_expired=1");
            }
        }
        $this->view('QA\QA');
            $this->view->render();
     }
}