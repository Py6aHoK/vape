<?php

class Router {
    public function Run(){
        $uri = trim($_SERVER['REQUEST_URI'],'/');
        $routes = require_once ROOT . '/app/config/routes.php';
        $View = View::getInstance();
        $View->header = ROOT . '/app/views/layouts/header.php';
        $View->footer = ROOT . '/app/views/layouts/footer.php';
        
        foreach ($routes as $pattern => $route){
            $result = false;
            if(preg_match("~$pattern~", $uri)){
                $internalRoute    = explode('/',preg_replace("~$pattern~", $route, $uri));
                $controllerClass  = array_shift($internalRoute) . 'Controller';
                $action           = 'action' . array_shift($internalRoute);
                $controllerObject = new $controllerClass();
                if(method_exists($controllerObject, $action)){
                    $result = call_user_func_array([$controllerObject,$action], $internalRoute);
                }
            }
            if($result){
                $View->render();
                return true;
            }
        }
        throw new Exception("Path searching error");
    }
}