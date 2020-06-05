<?php
class QAController extends Controller {
    public function QA(){
        $this->view('QA\QA');
            $this->view->render();
     }
}