<?php

class NomenclatureModel {
    public static function changeState($id){
        $DB = DB::getInstance()->getConnection();
        $sql = 'update dishes set d_state = not d_state where d_id = :id';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':id', $id, PDO::PARAM_INT);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();

        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        return true;
    }
    public static function getType(int $id): array {
        $DB = DB::getInstance()->getConnection();
        $sql = 'select dt_id as id,dt_name as name from dish_types where dt_id = :id';
        
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
        throw new Exception("Раздел не найден");
    }
    public static function getTypes(): array {
        $DB = DB::getInstance()->getConnection();
        $qrslt = $DB->query('select dt_id as id,dt_name as naim from dish_types order by dt_name');
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        
        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        $result = [];
        while($row = $qrslt->fetch()){
            $result[] = $row;
        }
        return $result;
    }
    public static function getList(int $type = 0,bool $all = false): array {
        $DB = DB::getInstance()->getConnection();
        $allSql = ($all)?',0':'';
        $sql = "select d_id as id,d_name as name,d_type as type,d_min_cost as min_cost,d_cost as cost, d_state as state from dishes where d_type = :type and d_state in(1$allSql) order by d_id";
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':type', $type, PDO::PARAM_INT);
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
    public static function getItemById(int $id): array {
        $DB = DB::getInstance()->getConnection();
        $sql = 'select d_id as id,d_type as type,d_name as name,d_min_cost as minCost, d_cost as cost,d_state as state from dishes where d_id = :id';
        
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
        throw new Exception("Позиция не найдена");
    }
    public static function Add($name,$type,$minCost,$cost,$state): bool {
        $DB = DB::getInstance()->getConnection();
        $sql = 'insert into dishes (d_name,d_type,d_min_cost,d_cost,d_state) values(:name,:type,:minCost,:cost,:state)';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':name',    $name,    PDO::PARAM_STR);
        $qrslt->bindParam(':type',    $type,    PDO::PARAM_INT);
        $qrslt->bindParam(':minCost', $minCost, PDO::PARAM_STR);
        $qrslt->bindParam(':cost',    $cost,    PDO::PARAM_STR);
        $qrslt->bindParam(':state',   $state,   PDO::PARAM_INT);
        $qrslt->execute();
        
        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        return true;
    }
    public static function Update($id,$name,$type,$minCost,$cost,$state): bool {
        $DB = DB::getInstance()->getConnection();
        $sql = 'update dishes set d_name = :name,d_type = :type, d_min_cost = :minCost, d_cost = :cost, d_state = :state where d_id = :id';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':id',      $id,      PDO::PARAM_INT);
        $qrslt->bindParam(':name',    $name,    PDO::PARAM_STR);
        $qrslt->bindParam(':type',    $type,    PDO::PARAM_INT);
        $qrslt->bindParam(':minCost', $minCost, PDO::PARAM_STR);
        $qrslt->bindParam(':cost',    $cost,    PDO::PARAM_STR);
        $qrslt->bindParam(':state',   $state,   PDO::PARAM_INT);
        $qrslt->execute();
        
        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        return true;
    }
}