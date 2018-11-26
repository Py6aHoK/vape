<?php

class UserController extends Controller{
    public function actionLogin(): bool {
        $errors = false;
        if(isset($_POST['submit'])){
            $id   = filter_input(INPUT_POST, 'id',   FILTER_VALIDATE_INT, ['options' => ['default' => 0,'min_range' => 0]]);
            $pass = md5(filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING));

            try{
                $user = UserModel::Auth($id,$pass);
            }catch(Exception $e){
                $errors = $e->getMessage();
            }
            
            if(!$errors){
                $_SESSION['user'] = $user;
                header('Location:/');
                return true;
            }
        }
        $users = UserModel::getList(true);
        
        $this->view->header = ROOT . '/app/views/layouts/login_header.php';
        $this->view->footer = ROOT . '/app/views/layouts/login_footer.php';
        $this->view->content = ROOT . '/app/views/site/login.php';
        $this->view->siteTitle = 'Вход в программу';
        $this->view->errors = $errors;
        $this->view->users  = $users;
        return true;
    }
    public function actionLogout(): bool {
        unset($_SESSION['user']);
        header('Location:/login');
        return true;
    }
}
