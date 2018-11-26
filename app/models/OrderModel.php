<?php

class OrderModel extends Model{    
    public static function doPrint(int $id){
        $print_server = require_once ROOT . '/app/config/print_server_config.php';
        $order = self::Get($id);
        $result = '';
        
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><print-order/>');

        $xml->addAttribute('table',$order->table);
        $xml->addAttribute('date',$order->date);
        $xml->addAttribute('discount',$order->discount);
        $xml->addAttribute('user',$order->user);
        $guests = $xml->addChild('guests');

        foreach($order->GetGuests() as $guest){
            $g = $guests->addChild('guest');
            $g->addAttribute('name',$guest->name);
            $g->addAttribute('discount',$guest->discount);
            $dishes = $g->addChild('dishes');

            foreach($guest->GetDishes() as $dish){
                $d = $dishes->addChild('dish');
                $d->addAttribute('name',$dish->name);
                $d->addAttribute('cost',$dish->cost);
                $d->addAttribute('count',$dish->count);
                $d->addAttribute('summa',$dish->summa);
            }
        }
        $xml->addChild('summ',$order->Summ());

        $data = $xml->asXML();

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket === false) {
            throw new Exception("Не удалось создать сокет");
        }

        $connection = @socket_connect($socket, $print_server['ip'], $print_server['port']);
        if ($connection === false) {
            throw new Exception("Не удалось соединиться с принт-сервером");
        }

        socket_write($socket, $data, strlen($data));
        while ($out = socket_read($socket, 2048)) {
            $result = $out;
        }
        socket_close($socket);
        if($result !== 'Ok'){
            throw new Exception("Ошибка печати: $result");
        }
        return $result;
    }
    public static function getList(){
        $DB = DB::getInstance()->getConnection();
        $sql = "SELECT o_id,o_date,o_discount,o_card,o_table,u_name,count(distinct(ogc_g_id)) as guests,sum(ogc_summa) as summa FROM orders LEFT JOIN order_guests on og_o_id = o_id LEFT JOIN order_guest_content on ogc_g_id = og_id LEFT JOIN users on u_id = o_user GROUP BY o_id ORDER BY o_date";
        
        $qrslt = $DB->prepare($sql);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        
        $result = [];
        if($qrslt->rowCount() > 0){
            while($row = $qrslt->fetch()){
                $result []= $row;
            }
            return $result;
        }
        throw new Exception("Заказы не найдены");
    }
    public static function get(int $id){
        $DB = DB::getInstance()->getConnection();
        $sql = "SELECT o_id,o_discount,o_card,o_table,(og_g_id + 1) AS og_g_id,og_g_discount,coalesce(ds_fio,concat('Гость №',(og_g_id + 1))) as ds_fio,d_id,d_name,ogc_count,ogc_summa,d_min_cost,d_cost,o_date,u_name FROM orders LEFT JOIN order_guests ON og_o_id = o_id LEFT JOIN order_guest_content ON ogc_g_id = og_id LEFT JOIN dishes ON d_id = ogc_d_id LEFT JOIN users ON u_id = o_user LEFT JOIN discounts ON ds_id = og_g_card WHERE o_cancelled = 0 and o_id = :id ORDER BY og_g_id";
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':id', $id, PDO::PARAM_INT);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        
        if($qrslt->rowCount() > 0){
            $started = false;
            while($row = $qrslt->fetch()){
                if(!$started){
                    $started = true;
                    $order = new Order($row['o_id'],$row['o_table'],$row['o_card'],$row['u_name'],$row['o_discount'],$row['o_date']);
                }
                $dish = new Dish($row['d_name'],$row['ogc_count'],$row['ogc_summa'],$row['d_cost']);
                $order->AddGuest($row['og_g_id'], $row['ds_fio'], $row['og_g_discount'])->AddDish($dish);
            }
            return $order;
        }
        throw new Exception("Заказ не найден");
    }
    public static function getStaffStats(int $count = 7): array {
        $DB = DB::getInstance()->getConnection();
        $sql = 'SELECT u_name as "name",count(distinct(og_id)) AS "guests",count(distinct(o_id)) AS "orders",sum(ogc_summa) as "summ"
                FROM users
                INNER JOIN orders ON o_user = u_id AND o_cancelled = 0
                LEFT JOIN order_guests ON og_o_id = o_id
                LEFT JOIN order_guest_content ON ogc_g_id = og_id
                LEFT JOIN dishes AS D1 ON D1.d_id = ogc_d_id
                GROUP BY u_id
                ORDER BY summ DESC
                LIMIT 0,:count';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':count', $count, PDO::PARAM_INT);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        
        $result = [];
        $staff_row = [];
        while($row = $qrslt->fetch()){
            $staff_row['name']   = $row['name'];
            $staff_row['guests'] = $row['guests'];
            $staff_row['summ']   = $row['summ'];
            $staff_row['avg']    = round($row['summ'] / $row['guests'],2);
            
            $result []= $staff_row;
        }
        return $result;
    }
    public static function Add(int $table,int $card = 0,int $discount = 0,Array $guests): array {
        $user = $_SESSION['user']['id'];
        $DB = DB::getInstance()->getConnection();
        $dishesCount = 0;
        
        try{
            $DB->beginTransaction();
            $sql = 'insert into orders (o_date,o_discount,o_user,o_cancelled,o_card,o_table) values (NOW(),:discount,:user,0,:card,:table)';
            $qrslt = $DB->prepare($sql);
            $qrslt->bindParam(':discount', $discount, PDO::PARAM_INT);
            $qrslt->bindParam(':user',     $user,     PDO::PARAM_INT);
            $qrslt->bindParam(':card',     $card,     PDO::PARAM_INT);
            $qrslt->bindParam(':table',    $table,    PDO::PARAM_INT);
            $qrslt->setFetchMode(PDO::FETCH_ASSOC);
            $qrslt->execute();
            $o_id = $DB->lastInsertId();

            foreach($guests as $id => $guest){
                $gcard = ($guest['card'] == null)?'0':$guest['card'];
                $sql = 'insert into order_guests (og_o_id,og_g_id,og_g_discount,og_g_card) values (' . $o_id . ',' . $id . ',' . $guest['discount'] . ',' . $gcard . ')';
                
                $qrslt = $DB->prepare($sql);
                $qrslt->setFetchMode(PDO::FETCH_ASSOC);
                $qrslt->execute();
                $g_id = $DB->lastInsertId();
                
                foreach($guest['dishes'] as $dish){
                    $dishesCount++;
                    $sql = 'insert into order_guest_content (ogc_g_id,ogc_d_id,ogc_count,ogc_summa) values (' . $g_id . ',' . $dish['id'] . ',' . $dish['count'] .',calc_cost_by_id(' . $dish['id'] . ',' . $guest['discount'] . ',' . $discount . ') * ' . $dish['count'] . ')';
                    $qrslt = $DB->prepare($sql);
                    $qrslt->setFetchMode(PDO::FETCH_ASSOC);
                    $qrslt->execute();
                }
            }
            if($dishesCount == 0){
                throw new PDOException('Заказ пуст');
            }
            
            $DB->Commit();
            try{
                self::DoPrint($o_id);
            }catch (Exception $e){}
        }catch(PDOException $e){
            $DB->Rollback();
            throw new Exception($e->getMessage());
        }
    }
    public static function getTopDishes(int $count = 5): array {
        $DB = DB::getInstance()->getConnection();
        
        $sql = 'SELECT d_name as "name",coalesce(sum(ogc_summa),0) as "summ"
                FROM orders
                INNER JOIN order_guests ON og_o_id = o_id
                INNER JOIN order_guest_content ON ogc_g_id = og_id
                INNER JOIN dishes ON d_id = ogc_d_id
                WHERE o_cancelled = 0
                GROUP BY d_id
                ORDER BY summ DESC
                LIMIT 0,:count';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':count', $count, PDO::PARAM_INT);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        
        $result = [];
        while($row = $qrslt->fetch()){
            $result []= $row;
        }
        return $result;
    }
    public static function getIntervalDishesCount(int $days = 0,string $interval = 'DAY'): float {
        $DB = DB::getInstance()->getConnection();
        $sql = 'SELECT coalesce(sum(order_guest_content.ogc_count),0) as count
                FROM orders
                INNER JOIN order_guests ON og_o_id = o_id
                INNER JOIN order_guest_content ON ogc_g_id = og_id
                WHERE DATE_FORMAT(o_date,"%Y-%m-%d") >= DATE_FORMAT(DATE_SUB(NOW(),INTERVAL :days ' . $interval . '),"%Y-%m-%d")
                and o_cancelled = 0 ';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':days', $days, PDO::PARAM_INT);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        
        $result = 0;
        if($qrslt->rowCount() > 0){
            $result = $qrslt->fetch()['count'];
        }
        return $result;
    }
    public static function getIntervalGuestsCount(int $days = 0,string $interval = 'DAY'): float {
        $DB = DB::getInstance()->getConnection();
        $sql = 'SELECT count(o_id) as "count"
                from order_guests
                inner join orders on o_id = og_o_id
                WHERE DATE_FORMAT(o_date,"%Y-%m-%d") >= DATE_FORMAT(DATE_SUB(NOW(),INTERVAL :days ' . $interval . '),"%Y-%m-%d")
                and o_cancelled = 0 ';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':days', $days, PDO::PARAM_INT);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        
        $result = 0;
        if($qrslt->rowCount() > 0){
            $result = $qrslt->fetch()['count'];
        }
        return $result;
    }
    public static function getIntervalOrdersCount(int $days = 0,string $interval = 'DAY'): float {
        $DB = DB::getInstance()->getConnection();
        $sql = 'SELECT count(o_id) as "count"
                from orders
                WHERE DATE_FORMAT(o_date,"%Y-%m-%d") >= DATE_FORMAT(DATE_SUB(NOW(),INTERVAL :days ' . $interval . '),"%Y-%m-%d")
                and o_cancelled = 0 ';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':days', $days, PDO::PARAM_INT);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        
        $result = 0;
        if($qrslt->rowCount() > 0){
            $result = $qrslt->fetch()['count'];
        }
        return $result;
    }
    public static function getIntervalProceeds(int $days = 0,string $interval = 'DAY'): float {//FIX!!!
        $DB = DB::getInstance()->getConnection();
        $sql = 'SELECT coalesce(sum(ogc_summa),0) AS summ
                FROM orders
                INNER JOIN order_guests ON og_o_id = o_id
                INNER JOIN order_guest_content ON ogc_g_id = og_id
                INNER JOIN dishes AS DD ON DD.d_id = ogc_d_id
                WHERE DATE_FORMAT(o_date,"%Y-%m-%d") >= DATE_FORMAT(DATE_SUB(NOW(),INTERVAL :days ' . $interval . '),"%Y-%m-%d") AND o_cancelled = 0';
        
        $qrslt = $DB->prepare($sql);
        $qrslt->bindParam(':days', $days, PDO::PARAM_INT);
        $qrslt->setFetchMode(PDO::FETCH_ASSOC);
        $qrslt->execute();
        
        $result = 0;
        if($qrslt->rowCount() > 0){
            $result = $qrslt->fetch()['summ'];
        }
        return $result;
    }
}

class Order{
    public $guests = [];
    public $id = 0;
    public $discount = 0;
    public $card = 0;
    public $user = '';
    public $table = 0;
    public $date = '';
    
    function __construct(int $id, int $table, int $card, string $user, int $discount, string $date){
        $this->id    = $id;
        $this->table = $table;
        $this->card  = $card;
        $this->user  = $user;
        $this->discount = $discount;
        $this->date  = $date;
    }
    private function GuestExists(int $id){
        return (!empty($this->guests[ $id ]));
    }
    function AddGuest(int $id, string $name, int $discount){
        if(!$this->GuestExists($id)){
            $this->guests[$id] = new Guest($name, $discount);
        }
        return $this->guests[$id];
    }
    function GetGuests(): array {
        return $this->guests;
    }
    function Summ(){
        $result = 0;
        
        foreach($this->guests as $guest){
            foreach($guest->GetDishes() as $dish){
                $result += $dish->summa;
            }
        }
        
        return $result;
    }
}
class Guest{
    public $name = '';
    public $discount = 0;
    public $dishes = [];

    function __construct(string $name, int $discount){
        $this->name = $name;
        $this->discount = $discount;
    }
    function AddDish(Dish $dish){
        $this->dishes []= $dish;
    }
    function GetDishes(): array {
        return $this->dishes;
    }
}
class Dish{
    public $name = '';
    public $cost = 0;
    public $summa = 0;
    public $count = 0;
    
    function __construct(string $name, int $count, float $summa, float $cost){
        $this->name = $name;
        $this->count = $count;
        $this->cost = $cost;
        $this->summa = $summa;
    }
}