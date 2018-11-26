<?php

class UserModel {
    const RIGHTS = ['1' => 'Кассир','2' => 'Администратор'];
    
    public static function Auth($id,$pass){
        $DB = DB::getInstance()->getConnection();
        $sql = 'select u_id as id,u_rights as rights,u_name as name from users where u_state = 1 and u_id = :id and u_pass = :pass';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':id',   $id,   PDO::PARAM_INT);
        $qrslt->bindParam(':pass', $pass, PDO::PARAM_STR);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        
        if($qrslt->rowCount() == 1){
            return $qrslt->fetch();
        }
        throw new Exception("Ошибка авторизации");
    }
    public static function getList(bool $all = false): array {
        $DB = DB::getInstance()->getConnection();
        $allSql = ($all)?',0':'';
        $sql = "select u_id,u_name,u_rights,u_state from users where u_state in(1$allSql) order by u_name";
        
        $qrslt = $DB->prepare($sql);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        
        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        $result = [];
        while($row = $qrslt->fetch()){
            $result[] = $row;
        }
        return $result;
    }
    public static function changeState($id){
        $DB = DB::getInstance()->getConnection();
        $sql = 'update users set u_state = not u_state where u_id = :id';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':id', $id, PDO::PARAM_INT);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();

        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        return true;
    }
    public static function getUserById(int $id): array {
        $DB = DB::getInstance()->getConnection();
        $sql = 'select u_id as id,u_name as name,u_rights as rights, u_state as state from users where u_id = :id';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':id', $id, PDO::PARAM_INT);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        
        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        if($qrslt->rowCount() == 1){
            return $qrslt->fetch();
        }
        throw new Exception("Пользователь не найден");
    }
    public static function Add($name,$pass,$rights,$state): bool {
        $DB = DB::getInstance()->getConnection();
        $sql = 'insert into users (u_name,u_pass,u_rights,u_state) values(:name,:pass,:rights,:state)';
        
        $pass = md5($pass);
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':name',   $name,   PDO::PARAM_STR);
        $qrslt->bindParam(':pass',   $pass,   PDO::PARAM_STR);
        $qrslt->bindParam(':rights', $rights, PDO::PARAM_INT);
        $qrslt->bindParam(':state',  $state,  PDO::PARAM_INT);
        $qrslt->execute();
        
        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        return true;
    }
    public static function Update($id,$name,$pass,$rights,$state): bool {
        $DB = DB::getInstance()->getConnection();
        $sql = 'update users set u_name = :name,u_rights = :rights,u_state = :state';
        $sql .= (!empty($pass))?',u_pass = :pass':'';
        $sql .= ' where u_id = :id';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':id',     $id,      PDO::PARAM_INT);
        $qrslt->bindParam(':name',   $name,    PDO::PARAM_STR);
        $qrslt->bindParam(':rights', $rights,  PDO::PARAM_INT);
        $qrslt->bindParam(':state',  $state,   PDO::PARAM_INT);
        if(!empty($pass)){
            $pass = md5($pass);
            $qrslt->bindParam(':pass',  $pass, PDO::PARAM_STR);
        }
        $qrslt->execute();
        
        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        return true;
    }
}