<?php

class AjaxController extends Controller{
    function __construct() {
        parent::__construct();
        $this->view->header = '';
        $this->view->footer = '';
    }
    
    public function actionTypes(): bool {
        $result = [];
        try{
            try{
                $this->checkRights(1,false);
                $result['data']    = TypeModel::getList();
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }
    
    public static function actionNomenclature(int $id = 0): bool {
        $result = [];
        try{
            try{
                $this->checkRights(1,false);
                $result['data'] = NomenclatureModel::getItemById($id);
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }
    public static function actionNomenclatures($typeId): bool {
        $result = [];
        try{
            try{
                $this->checkRights(1,false);
                $all = isset($_GET['all']);
                $result['data']    = NomenclatureModel::getList($typeId,$all);
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }

    public static function actionDiscount(int $id = 0): bool {
        $result = [];
        try{
            try{
                $this->checkRights(1,false);
                $result['data'] = DiscountModel::getItemById($id);
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }
    public static function actionDiscounts(): bool {
        $result = [];
        try{
            try{
                $this->checkRights(1,false);
                $all = isset($_GET['all']);
                $result['data']    = DiscountModel::getList($all);
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }    
}