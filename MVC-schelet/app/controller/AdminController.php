<?php


class AdminController extends Controller
{

    public function home()
    {
        $this->model('loginModel');
        if(isset($_GET['code']))
            if($this->model->adminLogin($_GET['code'])===true){
                $this->view('admin\home');
                $this->view->render();
            }
        else {
            echo "error";
        }
    }

    public function login(){
        $this->view('admin\login');
        $this->view->render();
    }

    public function changePassword(){
        $this->model('LoginModel');
        if(isset($_GET['new_password']))
            if($this->model->adminChangePassword($_GET['new_password'])===true){
                $this->view('admin\home');
                $this->view->render();
            }
            else {
                echo "error";
            }
    }

    public function allUserMessages(){
        $this->model('ContactModel');
        $messages=$this->model->getAllMessages();
        if($messages!==false){
          AdminView::displayMessages($messages);
        }
        else {
            echo "error";
        }
    }

    public function newUserMessages(){
        $this->model('ContactModel');
        $messages=$this->model->getNewMessages();
        if($messages!==false){
            AdminView::displayMessages($messages);
            $this->model->updateToSeen();
        }
        else {
            echo "error";
        }
    }


}