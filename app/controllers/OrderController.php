<?php

class OrderController extends Controller{
    public function actionPrint(int $id): bool {
        $this->view->header = '';
        $this->view->footer = '';
        $result = [];
        $method = $_SERVER['REQUEST_METHOD'];

        try{
            try{
                $this->checkRights(1,false);
                if($method !== 'GET'){
                    throw new Exception("Метод не поддерживается");
                }

                $print_result = OrderModel::doPrint($id);
                $result['data'] = ['result' => $print_result]; 
            }catch(Exception $e){
                $result['message'] = $e->getMessage();
            }
        }finally{
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return true;
        }
    }
    public function actionAdd(): bool {
        $this->view->header = '';
        $this->view->footer = '';
        $result = [];
        $method = $_SERVER['REQUEST_METHOD'];
        
        try{
            try{
                $this->checkRights(1,false);
                if($method !== 'POST'){
                    throw new Exception("Метод не поддерживается");
                }
                $errors = [];
                $postData = file_get_contents('php://input');
                $data = json_decode($postData, true);

                $table    = filter_var($data['table'],    FILTER_VALIDATE_INT,    ['options' => ['default' => 1,'min_range' => 1,'max_range' => 12]]);
                $card     = filter_var($data['card'],     FILTER_VALIDATE_INT,    ['options' => ['default' => 0,'min_range' => 0,'max_range' => 1]]);
                $discount = filter_var($data['discount'], FILTER_VALIDATE_INT,    ['options' => ['default' => 0,'min_range' => 0,'max_range' => 100]]);
                $guests   = $data['guests'] ;
                
                if(count($guests) == 0){
                    $errors []= 'Список гостей пуст';
                }
                
                if(count($errors) > 0){
                    throw new ArrayException($errors);
                }

                OrderModel::Add($table,$card,$discount,$guests);
                $result['data'] = ['result' => 'ok'];
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
}
