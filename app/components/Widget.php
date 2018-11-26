<?php

abstract class Widget implements IGetterable, ISetterable{
    protected $template = '';
    protected $vars = [];
    
    function __construct() {
        $this->Run();
    }
    
    function Run(){
    }
    
    function __get($param){
        if(array_key_exists($param,$this->vars)){
            return $this->vars[$param];
        }
        return false;
    }
    function __set($param,$value){
        if($param == 'template'){
            return true;
        }
        $this->vars[$param] = $value;
        return true;
    }
    
    function Render(){
        ob_start();
        foreach ($this->vars as $var => $value){
            $$var = $value;
        }
        if(!empty($this->template)) include_once ROOT . '/app/views/admin/widgets/' . $this->template . '.php';
        ob_flush();
    }
}
