<?php
class AboutUsController extends Controller {
    public function aboutUs()
    {
        $this->view('aboutUs\aboutUs');
        $this->view->render();
    }
}