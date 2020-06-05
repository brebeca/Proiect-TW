<?php
class Application{

private $controller='homeController';
private $action='index';
private $params=[];

public function __construct(){
   $this-> prepareURL();

   if(file_exists(CONTROLLER.$this->controller.'.php'))
   {
       $this->controller = new $this->controller;
       
       if(method_exists($this->controller,$this->action)){
           call_user_func_array([$this->controller,$this->action],$this->params);
           return;
       }
       else {
        $this->controller = new homeController;
        $this->controller->index();
       }
       
   }
    else{
        $this->controller = new homeController;
        $this->controller->index();
    }
}

private function prepareURL(){

    $request =trim($_SERVER['REQUEST_URI'],'/');
    if(!empty($request)){

        $uri=explode('/',str_replace ( '?',  '/' ,  $request));
        $this->controller =isset($uri[0]) ? ucfirst($uri[0]).'Controller': 'HomeController';
        
        $this->action =isset($uri[1]) ? $uri[1]: 'index';

        unset($uri[0],$uri[1]);
        $this->params =!empty($uri) ? array_values($uri) : [];
        
    }

}
}
