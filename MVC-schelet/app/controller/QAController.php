<?php
class QAController extends Controller {
    /**
     * intoarce pagina de QA
     */
    public function QA(){
        $this->view('QA\QA');
            $this->view->render();
     }
}