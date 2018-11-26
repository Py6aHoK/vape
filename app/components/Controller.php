<?php

class Controller {
    protected $view;
    function __construct() {
        $this->view = View::getInstance();
    }
    protected function checkRights(int $accessLevel,bool $redirect = false){
        if(!isset($_SESSION['user']['rights'])){
            if(!$redirect){
                throw new Exception('Access denied');
            }
            header('Location:/login');
        }else{
            if($_SESSION['user']['rights'] < $accessLevel){
                throw new Exception('Access denied');
            }
            $this->view->userName  = $_SESSION['user']['name'];
        }
        return true;
    }
}
