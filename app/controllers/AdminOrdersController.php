<?php

class AdminOrdersController extends Controller{
    public function actionIndex(): bool {
        $this->checkRights(2,true);
        
        $list = OrderModel::getList();
        
        $this->view->content = ROOT . '/app/views/admin/orders/index.php';
        $this->view->siteTitle = 'Заказы';
        $this->view->ordersList = $list;
        return true;
    }
    public function actionView(int $id = 0): bool {
        $this->view->header = '';
        $this->view->footer = '';
        $result = [];
        try{
            try{
                $this->checkRights(2,false);
        
                $result['data'] = OrderModel::get($id);
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }
    public function actionPrint(int $id = 0): bool {
        $this->view->header = '';
        $this->view->footer = '';
        $result = [];
        try{
            try{
                $this->checkRights(2,false);
        
                $result['data'] = OrderModel::doPrint($id);
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }
}