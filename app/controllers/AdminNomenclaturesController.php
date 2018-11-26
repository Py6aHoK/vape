<?php

class AdminNomenclaturesController extends Controller{
    public function actionIndex(): bool {
        $this->checkRights(2,true);
        
        $types = NomenclatureModel::getTypes();
        if(count($types) == 0){
            throw new Exception("Список разделов пуст");
        }
        
        $typeId = current($types)['id'];
        $all    = isset($_GET['all']);
        $list   = NomenclatureModel::getList($typeId,$all);
        
        $this->view->content   = ROOT . '/app/views/admin/nomenclature/index.php';
        $this->view->typeId    = $typeId;
        $this->view->siteTitle = 'Номенклатура';
        $this->view->nomenclatureTypes = $types;
        $this->view->nomenclaturesList = $list;
        return true;
    }
    public function actionType(int $typeId): bool {
        $this->checkRights(2,true);
        
        $types = NomenclatureModel::getTypes();
        if(!in_array($typeId, array_column($types, "id"))){
            header("Location: /404");
        }
        
        $all  = isset($_GET['all']);
        $list = NomenclatureModel::getList($typeId,$all);
        
        $this->view->content   = ROOT . '/app/views/admin/nomenclature/index.php';
        $this->view->typeId    = $typeId;
        $this->view->siteTitle = 'Номенклатура';
        $this->view->nomenclatureTypeId = $typeId;
        $this->view->nomenclatureTypes  = $types;
        $this->view->nomenclaturesList  = $list;
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

                $name    = filter_var($data['name'],      FILTER_SANITIZE_STRING);
                $typeId  = filter_var($data['type'],      FILTER_VALIDATE_INT,    ['options' => ['default' => 0,'min_range' => 0]]);
                $minCost = filter_var($data['min_cost'],  FILTER_VALIDATE_FLOAT,  ['options' => ['default' => 0,'min_range' => 0,'max_range' => 9999]]);
                $cost    = filter_var($data['cost'],      FILTER_VALIDATE_FLOAT,  ['options' => ['default' => 0,'min_range' => 0,'max_range' => 9999]]);
                $state   = filter_var($data['state'],     FILTER_VALIDATE_INT,    ['options' => ['default' => 0,'min_range' => 0,'max_range' => 1]]);
                $all     = isset($data['all']);

                if(empty($name)){
                    $errors []= 'Не указано название';
                }
                if($minCost <= 0){
                    $errors []= 'Не указана минимальная цена';
                }
                if($cost <= 0){
                    $errors []= 'Не указана цена';
                }
                if(count($errors) > 0){
                    throw new ArrayException($errors);
                }

                NomenclatureModel::add($name,$typeId,$minCost,$cost,$state);
                $result['data'] = NomenclatureModel::getList($typeId,$all);
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

                $name    = filter_var($data['name'],      FILTER_SANITIZE_STRING);
                $typeId  = filter_var($data['type'],      FILTER_VALIDATE_INT,    ['options' => ['default' => 0,'min_range' => 0]]);
                $minCost = filter_var($data['min_cost'],  FILTER_VALIDATE_FLOAT,  ['options' => ['default' => 0,'min_range' => 0,'max_range' => 9999]]);
                $cost    = filter_var($data['cost'],      FILTER_VALIDATE_FLOAT,  ['options' => ['default' => 0,'min_range' => 0,'max_range' => 9999]]);
                $state   = filter_var($data['state'],     FILTER_VALIDATE_INT,    ['options' => ['default' => 0,'min_range' => 0,'max_range' => 1]]);
                $all     = isset($data['all']);

                if(empty($name)){
                    $errors []= 'Не указано название';
                }
                if($minCost <= 0){
                    $errors []= 'Не указана минимальная цена';
                }
                if($cost <= 0){
                    $errors []= 'Не указана цена';
                }
                if(count($errors) > 0){
                    throw new ArrayException($errors);
                }

                NomenclatureModel::update($id,$name,$typeId,$minCost,$cost,$state);
                $result['data'] = NomenclatureModel::getList($typeId,$all);
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
                $prevItemState = NomenclatureModel::getItemById($id)['state'];
                NomenclatureModel::changeState($id);
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