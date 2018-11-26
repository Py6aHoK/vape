<?php

class AdminController extends Controller{
    public function actionIndex(): bool {
        $this->checkRights(2,true);
        
        $widgets = [];
        $widgets []= new TodayWidget();
        $widgets []= new Top5Widget();
        $widgets []= new AvgWidget();
        $widgets []= new StaffWidget();
        $widgets []= new TopWidget();

        $this->view->content = ROOT . '/app/views/admin/site/index.php';
        $this->view->siteTitle = 'Общая информация';
        $this->view->widgets = $widgets;
        return true;
    }
}