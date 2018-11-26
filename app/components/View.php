<?php

class View implements IGetterable,ISetterable{
    private static $instnce;
    private static $header  = '';
    private static $content = '';
    private static $footer  = '';
    private static $vars    = [];
    const sections = ['header','content','footer'];
    
    public static function getInstance(){
        if(is_null(self::$instnce)){
            self::$instnce = new self;
        }
        return self::$instnce;
    }
    function __get($param){
        if(in_array($param,self::sections)){
            return self::$$param;
        }
        if(array_key_exists($param,self::$vars)){
            return self::$vars[$param];
        }
        return false;
    }
    function __set($param,$value){
        (in_array($param,self::sections))? self::$$param = $value : self::$vars[$param] = $value;
        return true;
    }
    public function Render(){
        ob_start();
        foreach (self::$vars as $var => $val){
            $$var = $val;
        }
        
        if(!empty(self::$header)) include_once self::$header;
        if(!empty(self::$content)) include_once self::$content;
        if(!empty(self::$footer)) include_once self::$footer;
        ob_flush();
    }
}
