<?php
class Controller{

    protected $view=null;
    protected $model=null;

    protected function view($viewName, $data=[]){
       $this->view= new View($viewName,$data);
       return $this->view;
    }

    protected function model($modelName, $data=[]){
        if(file_exists(MODEL.$modelName. '.php'))
          { 
            require MODEL.$modelName. '.php'; 
            $this->model=new $modelName;
          }
          else {echo "nu ".MODEL.$modelName. '.php';}
    }

}
