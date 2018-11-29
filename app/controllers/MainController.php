<?php

class MainController extends Controller{
    public function actionIndex(): bool {
        $this->checkRights(1,true);
        
        $this->view->content   = ROOT . '/app/views/site/index.php';
        $this->view->siteTitle = 'Рабочее место кассира';
        return true;
    }
    public function action404(): bool {
        $this->view->content = ROOT . '/app/views/site/404.php';
        $this->view->siteTitle = '404';
        return true;
    }
}