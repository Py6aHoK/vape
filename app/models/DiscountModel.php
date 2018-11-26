<?php

class DiscountModel {
    public static function getList(bool $all = false): array {
        $DB = DB::getInstance()->getConnection();
        $allSql = ($all)?',0':'';
        $sql = "select * from discounts where ds_state in(1$allSql) order by ds_number";

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
    public static function getItemById(int $id): array {
        $DB = DB::getInstance()->getConnection();
        $sql = 'select ds_id as id,ds_fio as fio,ds_number as number,ds_value as value,ds_state as state from discounts where ds_id = :id';

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
        throw new Exception("Запись не найдена");
    }
    public static function changeState($id){
        $DB = DB::getInstance()->getConnection();
        $sql = 'update discounts set ds_state = not ds_state where ds_id = :id';

        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':id', $id, PDO::PARAM_INT);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();

        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        return true;
    }
    public static function Add($fio,$number,$value,$state): bool {
        $DB = DB::getInstance()->getConnection();
        $sql = 'select ds_id from discounts where ds_number = :number';

        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':number', $number, PDO::PARAM_INT);

        $qrslt->execute();
        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        if($qrslt->rowCount() > 0){
            throw new Exception("Номер карты уже используется");
        }

        $sql = 'insert into discounts (ds_fio,ds_number,ds_value,ds_state) values(:fio,:number,:value,:state)';

        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':fio',    $fio,    PDO::PARAM_STR);
        $qrslt->bindParam(':number', $number, PDO::PARAM_INT);
        $qrslt->bindParam(':value',  $value,  PDO::PARAM_STR);
        $qrslt->bindParam(':state',  $state,  PDO::PARAM_INT);
        $qrslt->execute();
        
        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        return true;
    }
    public static function Update($id,$fio,$number,$value,$state): bool {
        $DB = DB::getInstance()->getConnection();
        $sql = 'select ds_id from discounts where ds_number = :number and ds_id <> :id';

        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':id',     $id,     PDO::PARAM_INT);
        $qrslt->bindParam(':number', $number, PDO::PARAM_INT);
        $qrslt->execute();

        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
        if($qrslt->rowCount() > 0){
            throw new Exception("Номер карты уже используется");
        }

        $DB = DB::getInstance()->getConnection();
        $sql = 'update discounts set ds_fio = :fio,ds_number = :number, ds_value = :value,ds_state = :state where ds_id = :id';

        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':id',     $id,     PDO::PARAM_INT);
        $qrslt->bindParam(':fio',    $fio,    PDO::PARAM_STR);
        $qrslt->bindParam(':number', $number, PDO::PARAM_INT);
        $qrslt->bindParam(':value',  $value,  PDO::PARAM_STR);
        $qrslt->bindParam(':state',  $state,  PDO::PARAM_INT);
        $qrslt->execute();
        
        if($qrslt->errorCode() > 0){
            throw new Exception("Ошибка при выполнении запроса");
        }
    }
}