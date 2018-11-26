<?php
    
return [
        '^ajax/nomenclatures/([0-9]+)(\?all){0,1}$' => 'Ajax/Nomenclatures/$1',
        '^ajax/nomenclature/([0-9]+)$' => 'Ajax/Nomenclature/$1',
        '^ajax/nomenclature/add$' => 'AdminNomenclatures/Add',
        '^ajax/nomenclature/update/([0-9]+)$' => 'AdminNomenclatures/Update/$1',
        '^ajax/nomenclature/delete/([0-9]+)$' => 'AdminNomenclatures/Delete/$1',
    
        '^ajax/types$' => 'Ajax/Types',
    
        '^ajax/discount(\?all){0,1}$' => 'Ajax/Discounts',
        '^ajax/discount/([0-9]+)$' => 'Ajax/Discount/$1',
        '^ajax/discount/add$' => 'AdminDiscounts/Add',
        '^ajax/discount/update/([0-9]+)$' => 'AdminDiscounts/Update/$1',
        '^ajax/discount/delete/([0-9]+)$' => 'AdminDiscounts/Delete/$1',
    
        '^ajax/user(\?all){0,1}$' => 'AdminUsers/List',
        '^ajax/user/([0-9]+)$' => 'AdminUsers/View/$1',
        '^ajax/user/add$' => 'AdminUsers/Add',
        '^ajax/user/update/([0-9]+)$' => 'AdminUsers/Update/$1',
        '^ajax/user/delete/([0-9]+)$' => 'AdminUsers/Delete/$1',

        '^ajax/order/add$' => 'Order/Add',
        '^ajax/order/([0-9]+)$' => 'AdminOrders/View/$1',
        '^ajax/order/print/([0-9]+)$' => 'AdminOrders/Print/$1',
    
        '^admin$' => 'Admin/Index',
        '^admin/nomenclature$' => 'AdminNomenclatures/Index',
        '^admin/nomenclature/type/([0-9]+)$' => 'AdminNomenclatures/Type/$1',
        '^admin/discounts$' => 'AdminDiscounts/Index',
        '^admin/users$' => 'AdminUsers/Index',
        '^admin/orders$' => 'AdminOrders/Index',

        '^print/([0-9]+)$' => 'Order/Print/$1',
    
        '^()$' => 'Main/Index',
        '^login$' => 'User/Login',
        '^logout$' => 'User/Logout',
        '^(.+)$' => 'Main/404'
    ];