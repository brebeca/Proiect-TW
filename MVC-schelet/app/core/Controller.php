<?php
class Controller{

    protected $view=null;
    protected $model=null;

    public function view($viewName, $data=[]){
       $this->view= new View($viewName,$data);
       return $this->view;
    }

    public function model($modelName, $data=[]){
        if(file_exists(MODEL.$modelName. '.php'))
          { 
            require MODEL.$modelName. '.php'; 
            $this->model=new $modelName;
           // echo MODEL.$modelName. '.php';
          }
          else {echo "nu ".MODEL.$modelName. '.php';}
    }

}
