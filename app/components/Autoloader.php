<?php

function autoloader($className){
    $folders = ['components','controllers','models','widgets'];
    
    foreach($folders as $folder){
        $filename = ROOT . '/app/' . $folder . '/' . $className . '.php';
        if(file_exists($filename)){
            include_once $filename;
            return true;
        }
    }
    throw new Exception("Class $className not found");
}
