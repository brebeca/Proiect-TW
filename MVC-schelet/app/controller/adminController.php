<?php


class adminController extends Controller
{

    public function home()
    {
        //echo $_GET['code'];

        $this->model('loginModel');
        if(isset($_GET['code']))
            if($this->model->admin_login($_GET['code'])===true){
                $this->view('admin\home');
                $this->view->render();
            }
        else {
            //$this->login();
            echo "error";
           // include error_page
        }
    }
    public function login(){
        $this->view('admin\login');
        $this->view->render();
    }
    public function change_password(){
        $this->model('loginModel');
        if(isset($_GET['new_password']))
            if($this->model->admin_change_password($_GET['new_password'])===true){
                $this->view('admin\home');
                $this->view->render();
            }
            else {
                //$this->login();
                echo "error";
                // include error_page
            }
    }

    public function all_user_messages(){
        $this->model('contactModel');
        $messages=$this->model->getAllMessages();
        if($messages!==false){
          adminView::display_messages($messages);
        }
        else {
            echo "error";
        }
    }

    public function new_user_messages(){
        $this->model('contactModel');
        $messages=$this->model->getNewMessages();
        if($messages!==false){
            adminView::display_messages($messages);
            $this->model->updateToSeen();
        }
        else {
            echo "error";
        }
    }


}