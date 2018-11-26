<?php

class Top5Widget extends Widget{
    protected $template = 'top5';

    private function createSectorsArray($summs,$percent): array {
        $emptyArray = [0,0,0,0,0];
        $result = [];
        foreach($summs as $summ){
            $result []= round($summ / $percent,2);
        }
        return array_replace($emptyArray,$result);
    }
    
    function Run(){
        $topDishes = OrderModel::getTopDishes();
        
        $summs = array_column($topDishes, "summ");
        $percent = array_sum($summs) / 100;
        $sectorsArray = $this->createSectorsArray($summs,$percent);
        
        //$percent = 
        //$maxSectorValue = 
        $this->vars['sectorsArray'] = $sectorsArray;
        $this->vars['topDishes'] = $topDishes;
    }
}
