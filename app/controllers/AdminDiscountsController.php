<?php

class AdminDiscountsController extends Controller{
    public function actionIndex(): bool {
        $this->checkRights(2,true);
        
        $list = DiscountModel::getList();
        
        $this->view->content = ROOT . '/app/views/admin/discounts/index.php';
        $this->view->siteTitle = 'Скидки';
        $this->view->discountsList = $list;
        return true;
    }
    public function actionAdd(){
        $this->view->header = '';
        $this->view->footer = '';
        $result = [];
        $method = $_SERVER['REQUEST_METHOD'];
        
        try{
            try{
                $this->checkRights(2,false);
        
                if($method !== 'POST'){
                    throw new Exception("Метод не поддерживается");
                }
                $errors = [];
                $postData = file_get_contents('php://input');
                $data = json_decode($postData, true);

                $fio    = filter_var($data['fio'],    FILTER_SANITIZE_STRING);
                $number = filter_var($data['number'], FILTER_VALIDATE_INT,    ['options' => ['default' => 0,'min_range' => 1]]);
                $value  = filter_var($data['value'],  FILTER_VALIDATE_FLOAT,  ['options' => ['default' => 0,'min_range' => 0,'max_range' => 100]]);
                $state  = filter_var($data['state'],  FILTER_VALIDATE_INT,    ['options' => ['default' => 0,'min_range' => 0,'max_range' => 1]]);
                $all    = isset($data['all']);

                if(empty($fio)){
                    $errors []= 'Не указано ФИО держателя';
                }
                if($number <= 0){
                    $errors []= 'Не указан номер карты';
                }
                if($value <= 0){
                    $errors []= 'Не указан процент скидки';
                }
                if(count($errors) > 0){
                    throw new ArrayException($errors);
                }

                DiscountModel::add($fio,$number,$value,$state);
                $result['data'] = DiscountModel::getList($all);
            }catch(ArrayException $e){
                $result['message'] = $e->getArray();
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }
    public function actionUpdate(int $id = 0){
        $this->view->header = '';
        $this->view->footer = '';
        $result = [];
        $method = $_SERVER['REQUEST_METHOD'];
        
        try{
            try{
                $this->checkRights(2,false);
        
                if($method !== 'PUT'){
                    throw new Exception("Метод не поддерживается");
                }
                $errors = [];
                $postData = file_get_contents('php://input');
                $data = json_decode($postData, true);

                $fio    = filter_var($data['fio'],    FILTER_SANITIZE_STRING);
                $number = filter_var($data['number'], FILTER_VALIDATE_INT,    ['options' => ['default' => 1,'min_range' => 1]]);
                $value  = filter_var($data['value'],  FILTER_VALIDATE_FLOAT,  ['options' => ['default' => 0,'min_range' => 0,'max_range' => 100]]);
                $state  = filter_var($data['state'],  FILTER_VALIDATE_INT,    ['options' => ['default' => 0,'min_range' => 0,'max_range' => 1]]);
                $all    = isset($data['all']);

                if(empty($fio)){
                    $errors []= 'Не указано ФИО держателя';
                }
                if($number <= 0){
                    $errors []= 'Не указан номер карты';
                }
                if($value <= 0){
                    $errors []= 'Не указан процент скидки';
                }
                if(count($errors) > 0){
                    throw new ArrayException($errors);
                }

                DiscountModel::update($id,$fio,$number,$value,$state);
                $result['data'] = DiscountModel::getList($all);
            }catch(ArrayException $e){
                $result['message'] = $e->getArray();
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }
    public function actionDelete(int $id = 0){
        $this->view->header = '';
        $this->view->footer = '';
        $result = [];
        $method = $_SERVER['REQUEST_METHOD'];
        
        try{
            try{
                $this->checkRights(2,false);
        
                if($method !== 'DELETE'){
                    throw new Exception("Метод не поддерживается");
                }
                $prevItemState = DiscountModel::getItemById($id)['state'];
                DiscountModel::changeState($id);
                $result['data'] = ['state' => !$prevItemState];
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }
}