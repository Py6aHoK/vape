<?php

class TypeModel extends Model{
    
    public static function getList(): array {
        $DB = DB::getInstance()->getConnection();
        $sql = 'SELECT dt_id as "id",dt_name as "name" FROM dish_types order by dt_name';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        
        $result = [];
        while($row = $qrslt->fetch()){
            $result []= $row;
        }
        return $result;
    }
}
