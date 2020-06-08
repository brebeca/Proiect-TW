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
            header('Location:/home/index?error=1');
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
                header('Location:/home/index?error=1');
            }
    }

    public function allUserMessages(){
        $this->model('ContactModel');
        $messages=$this->model->getAllMessages();
        if($messages!==false){
          AdminView::displayMessages($messages);
        }
        else {
            header('Location:/home/index?error=1');
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
            header('Location:/home/index?error=1');
        }
    }
    public function Raport(){
        include HTMLS.'Raport.html';
    }
    public function GidUtilizare(){
        include HTMLS.'GidUtilizare.html';
    }
    public function UML1(){
        header('Content-type:image/png');
        readfile(IMG.'UMLServer1.png');
    }
    public function UML2(){
        header('Content-type:image/png');
        readfile(IMG.'UMLSv2.png');
    }
    public function Arhitectura(){
        header('Content-type:image/png');
        readfile(IMG.'Arhitectura.png');
    }


}