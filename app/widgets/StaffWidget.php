<?php

class StaffWidget extends Widget{
    protected $template = 'staff';
    
    function Run(){
        $this->vars['staffTable'] = OrderModel::getStaffStats();
    }
}
