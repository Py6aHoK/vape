<?php

class AdminUsersController extends Controller{
    public function actionIndex(): bool {
        $this->checkRights(2,true);
        
        $list = UserModel::getList();
        
        $this->view->content = ROOT . '/app/views/admin/users/index.php';
        $this->view->siteTitle = 'Пользователи';
        $this->view->usersList = $list;
        return true;
    }
    
    public function actionView(int $id = 0): bool {
        $this->view->header = '';
        $this->view->footer = '';
        $result = [];
        try{
            try{
                $this->checkRights(2,false);
        
                $result['data'] = UserModel::getUserById($id);
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }
    public function actionList(): bool {
        $this->view->header = '';
        $this->view->footer = '';
        $result = [];
        try{
            try{
                $this->checkRights(2,false);
        
                $all = isset($_GET['all']);
                $result['data']    = UserModel::getList($all);
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
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
                $sortedRights = array_keys(UserModel::RIGHTS);
                sort($sortedRights);

                $min = current($sortedRights);
                $max = end($sortedRights);

                $errors = [];
                $postData = file_get_contents('php://input');
                $data = json_decode($postData, true);

                $name   = filter_var($data['name'],       FILTER_SANITIZE_STRING);
                $pass   = trim(filter_var($data['pass'],  FILTER_SANITIZE_STRING));
                $pass2  = trim(filter_var($data['pass2'], FILTER_SANITIZE_STRING));
                $rights = filter_var($data['rights'],     FILTER_VALIDATE_INT,    ['options' => ['default' => $min,'min_range' => $min,'max_range' => $max]]);
                $state  = filter_var($data['state'],      FILTER_VALIDATE_INT,    ['options' => ['default' => 0,'min_range' => 0,'max_range' => 1]]);
                $all    = isset($data['all']);

                if(empty($name)){
                    $errors []= 'Не указано имя пользователя';
                }
                if(!isset(UserModel::RIGHTS[$rights])){
                    $errors []= 'Не указаны права пользователя';
                }
                if($method == 'POST'){
                    if(empty($pass)){
                        $errors []= 'Не указан пароль пользователя';
                    }
                }
                if(!empty($pass)){
                    if($pass !== $pass2){
                        $errors []= 'Пароль и подтверждение не совпадают';
                    }
                }
                if(count($errors) > 0){
                    throw new ArrayException($errors);
                }

                UserModel::add($name,$pass,$rights,$state);
                $result['data'] = UserModel::getList($all);
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
                $sortedRights = array_keys(UserModel::RIGHTS);
                sort($sortedRights);

                $min = current($sortedRights);
                $max = end($sortedRights);

                $errors = [];
                $postData = file_get_contents('php://input');
                $data = json_decode($postData, true);

                $name   = filter_var($data['name'],       FILTER_SANITIZE_STRING);
                $pass   = trim(filter_var($data['pass'],  FILTER_SANITIZE_STRING));
                $pass2  = trim(filter_var($data['pass2'], FILTER_SANITIZE_STRING));
                $rights = filter_var($data['rights'],     FILTER_VALIDATE_INT,    ['options' => ['default' => $min,'min_range' => $min,'max_range' => $max]]);
                $state  = filter_var($data['state'],      FILTER_VALIDATE_INT,    ['options' => ['default' => 0,'min_range' => 0,'max_range' => 1]]);
                $all    = isset($data['all']);

                if(empty($name)){
                    $errors []= 'Не указано имя пользователя';
                }
                if(!isset(UserModel::RIGHTS[$rights])){
                    $errors []= 'Не указаны права пользователя';
                }
                if($method == 'POST'){
                    if(empty($pass)){
                        $errors []= 'Не указан пароль пользователя';
                    }
                }
                if(!empty($pass)){
                    if($pass !== $pass2){
                        $errors []= 'Пароль и подтверждение не совпадают';
                    }
                }
                if(count($errors) > 0){
                    throw new ArrayException($errors);
                }

                UserModel::update($id,$name,$pass,$rights,$state);
                $result['data'] = UserModel::getList($all);
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
                $prevItemState = UserModel::getUserById($id)['state'];
                UserModel::changeState($id);
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