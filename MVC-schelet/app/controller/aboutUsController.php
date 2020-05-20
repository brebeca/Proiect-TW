<?php
class aboutUsController extends Controller {



    public function aboutUs()
    {

        $this->view('aboutUs\aboutUs');
        $this->view->render();
    }
}