<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    define('ROOT', dirname(__FILE__));
    session_start();

    require_once ROOT . '/app/components/Autoloader.php';
    spl_autoload_register('autoloader');
    $router = new Router();
    
    try{
        $router->Run();
    } catch (ArrayException $e){
        print_r($e->getArray());
    } catch (Exception $e){
        echo $e->getMessage();
    }