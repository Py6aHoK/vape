<?php

class AvgWidget extends Widget{
    protected $template = 'avg';

    function Run(){
        $todaySumma  = OrderModel::getIntervalProceeds();
        $todayChecks = OrderModel::getIntervalOrdersCount();
        
        $weekSumma   = OrderModel::getIntervalProceeds(7);
        $weekChecks  = OrderModel::getIntervalOrdersCount(7);

        $monthSumma   = OrderModel::getIntervalProceeds(1,'MONTH');
        $monthChecks  = OrderModel::getIntervalOrdersCount(1,'MONTH');

        $hyearSumma   = OrderModel::getIntervalProceeds(6,'MONTH');
        $hyearChecks  = OrderModel::getIntervalOrdersCount(6,'MONTH');
        
        $this->vars['moneyToday'] = round(($todayChecks > 0)?$todaySumma / $todayChecks:0,2);
        $this->vars['moneyWeek']  = round(($weekChecks  > 0)?$weekSumma  / $weekChecks :0,2);
        $this->vars['moneyMonth'] = round(($monthChecks > 0)?$monthSumma / $monthChecks:0,2);
        $this->vars['moneyHyear'] = round(($hyearChecks > 0)?$hyearSumma / $hyearChecks:0,2);
    }
}
