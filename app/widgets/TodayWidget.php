<?php

class TodayWidget extends Widget{
    protected $template = 'today';
    
    function Run(){
        $this->vars['money']  = OrderModel::getIntervalProceeds();
        $this->vars['guests'] = OrderModel::getIntervalGuestsCount();
        $this->vars['checks'] = OrderModel::getIntervalOrdersCount();
        $this->vars['dishes'] = OrderModel::getIntervalDishesCount();
    }
}
