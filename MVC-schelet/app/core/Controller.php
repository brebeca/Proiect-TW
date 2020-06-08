<?php
class Controller{

    protected $view=null;
    protected $model=null;

    /**
     * @param $viewName
     * @param array $data
     * se seteaza numele view-ului
     * @return View
     */
    protected function view($viewName, $data=[]){
       $this->view= new View($viewName,$data);
       return $this->view;
    }

    /**
     * @param $modelName
     * @param array $data
     * se steteaza modeulul ca cel memorat in fisierul cu numele $modelName din directorul de modele
    * daca nu exista se intoarce fals pt a semala eroare
     */
    protected function model($modelName, $data=[]){
        if(file_exists(MODEL.$modelName. '.php'))
          { 
            require MODEL.$modelName. '.php'; 
            $this->model=new $modelName;
          }
        return false;
    }

}
