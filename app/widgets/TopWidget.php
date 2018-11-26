<?php

class TopWidget extends Widget{
    protected $template = 'top';
    
    function Run(){
        $this->vars['topDishes'] = OrderModel::getTopDishes(7);
    }
}
