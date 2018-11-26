function applyFilter(){
    var state = ($('#filter-state').prop("checked"))?'all':'';
    var request = false;
    var callback;

    if(TABLE == 'nomenclature'){
        NProgress.start();
        let id   = $('#filter-types').val();
        request  = getNomenclatures(id,state);
        callback = rebuildNomenclaturesGrid;
    }else if(TABLE == 'discounts'){
        NProgress.start();
        request  = getDiscounts(state);
        callback = rebuildDiscountsGrid;
    }else if(TABLE == 'users'){
        NProgress.start();
        request  = getUsers(state);
        callback = rebuildUsersGrid;
    }else{
        return false;
    }
    request.success(function(response){
        NProgress.done();
        if(response.message !== undefined){
            alert(response.message);
            return true;
        }
        callback(response.data);
    });
}
function closeDialog(){
    $('.dialog-wrapper').hide();
}
function parseResponse(response,rebuilder){
    let $errors = $('#dialog').find('#errors');
    $errors.html('');

    if(response.message !== undefined){
        if(typeof(response.message) === 'string'){
                $span = $('<span>');
                $span.text(response.message);
                $errors.append($span);
        }else{
            response.message.forEach(function(error){
                $span = $('<span>');
                $span.text(error);
                $errors.append($span);
            });
        }
        $errors.show();
        return true;
    }
    rebuilder(response.data);
    closeDialog();
}
function rebuildNomenclaturesGrid(data){
    $grid = $('#grid-rows');
    $grid.html('');
    
    data.forEach((row)=>{
        $tr = $('<tr>');
            $tr.addClass('grid-row');
            $tr.attr("row-id",row.id);

        $tr.append($('<td>' + row.name + '</td>'));
        $tr.append($('<td>' + row.id + '</td>'));
        $tr.append($('<td>' + row.min_cost + '</td>'));
        $tr.append($('<td>' + row.cost + '</td>'));
        
        $td = $('<td>');
            $td.addClass('state-btn');
            $td.text((row.state == 0)?'восстановить':'удалить');
        $tr.append($td);

        $grid.append($tr);
    });
}
function rebuildDiscountsGrid(data){
    $grid = $('#grid-rows');
    $grid.html('');
    
    data.forEach((row)=>{
        $tr = $('<tr>');
            $tr.addClass('grid-row');
            $tr.attr("row-id",row.ds_id);

        $tr.append($('<td>' + row.ds_fio + '</td>'));
        $tr.append($('<td>' + row.ds_number + '</td>'));
        $tr.append($('<td>' + row.ds_value + '</td>'));
        
        $td = $('<td>');
            $td.addClass('state-btn');
            $td.text((row.ds_state == 0)?'восстановить':'удалить');
        $tr.append($td);

        $grid.append($tr);
    });
}
function rebuildUsersGrid(data){
    const rights = {'1':'Кассир','2':'Администратор'};
    $grid = $('#grid-rows');
    $grid.html('');
    
    data.forEach((row)=>{
        $tr = $('<tr>');
            $tr.addClass('grid-row');
            $tr.attr("row-id",row.u_id);

        $tr.append($('<td>' + row.u_name + '</td>'));
        $tr.append($('<td>' + rights[row.u_rights] + '</td>'));
        
        $td = $('<td>');
            $td.addClass('state-btn');
            $td.text((row.u_state == 0)?'восстановить':'удалить');
        $tr.append($td);

        $grid.append($tr);
    });
}

$('body').on('click','.grid-row',function(){
    let tableName = $(this).parent().attr('table');
    let id = $(this).attr('row-id');
    let $dialog = $('.dialog-wrapper');
    $('#dialog').find('#errors').html('');
    
    if(tableName == 'nomenclature'){
        NProgress.start();
        let request = getNomenclatureById(id);
        
        request.success(function(response){
            NProgress.done();
            if(response.message !== undefined){
                alert(response.message);
                return true;
            }

            $dialog.find('#d_id').val(response.data.id);
            $dialog.find('#d_type').val(response.data.type);
            $dialog.find('#d_name').val(response.data.name);
            $dialog.find('#d_min_cost').val(response.data.minCost);
            $dialog.find('#d_cost').val(response.data.cost);
            $dialog.find('#d_state').val(response.data.state);
            $dialog.show();
        });
    }else if(tableName == 'discounts'){
        NProgress.start();
        let request = getDiscountById(id);
        
        request.success(function(response){
            NProgress.done();
            if(response.message !== undefined){
                alert(response.message);
                return true;
            }

            $dialog.find('#ds_id').val(response.data.id);
            $dialog.find('#ds_fio').val(response.data.fio);
            $dialog.find('#ds_number').val(response.data.number);
            $dialog.find('#ds_value').val(response.data.value);
            $dialog.find('#ds_state').val(response.data.state);
            $dialog.show();
        });
    }else if(tableName == 'users'){
        NProgress.start();
        let request = getUserById(id);

        request.success(function(response){
            NProgress.done();
            if(response.message !== undefined){
                alert(response.message);
                return true;
            }

            $dialog.find('#u_id').val(response.data.id);
            $dialog.find('#u_name').val(response.data.name);
            $dialog.find('#u_pass').val('');
            $dialog.find('#u_pass2').val('');
            $dialog.find('#u_rights').val(response.data.rights);
            $dialog.find('#u_state').val(response.data.state);
            $dialog.show();
        });
    }else if(tableName == 'orders'){
        NProgress.start();
        let request = getOrderById(id);

        request.success(function(response){
            NProgress.done();
            if(response.message !== undefined){
                alert(response.message);
                return true;
            }

            let summa = 0;
            $order_info   = $('#order-info');
            $order_info.html('');
                $id       = $('<input type="hidden" id="o_id" value="' + response.data.id + '">');
                $table    = $('<div class="order-param">Стол:<span>' + response.data.table + '</span></div>');
                $date     = $('<div class="order-param">Дата:<span>' + response.data.date + '</span></div>');
                $user     = $('<div class="order-param">Кассир:<span>' + response.data.user + '</span></div>');
                $discount = $('<div class="order-param">Скидка:<span>' + response.data.discount + '%</span></div>');
                $guests   = $('<div class="guests">');
                    let guests = response.data.guests;
                    for(let g = 1; g < Object.keys(guests).length + 1; g++){
                        let guest = guests[g];
                        $guest = $('<div class="guest">');
                            $guest_name     = $('<div class="guest-param">' + guest.name + '</div>');
                            $guest_discount = $('<span>Скидка: ' + guest.discount + '%</span>');
                            $guest_name.append($guest_discount);
                            $guest.append($guest_name);
                        
                            $dishes = $('<div class="dishes">');
                            let dishes = guest.dishes;
                            for(let d = 0; d < dishes.length; d++){
                                let dish = dishes[d];
                                $dish = $('<div class="dish">');
                                    $dish_row   = $('<div class="dish-param">');
                                        $dish_name  = $('<span class="dish-name">'  + dish.name  + '</span>');
                                        $dish_summa = $('<span class="dish-summa">' + dish.summa + 'р.</span>');
                                        $dish_count = $('<span class="dish-count">x' + dish.count + '</span>');
                                        $dish_row.append($dish_name);
                                        $dish_row.append($dish_count);
                                        $dish_row.append($dish_summa);
                                    $dish.append($dish_row);
                                    $dishes.append($dish);
                                summa += dish.summa;
                            }

                            $guest.append($dishes);
                            $guests.append($guest);
                    }
                $summa = $('<div class="order-itog">Итого: <span>' + summa + 'р.</span></div>');
                
            $order_info.append($id);
            $order_info.append($table);
            $order_info.append($date);
            $order_info.append($user);
            $order_info.append($discount);
            $order_info.append($guests);
            $order_info.append($summa);
            $dialog.show();
        });
    }
});
$('body').on('click','.state-btn',function(event){
    event.stopPropagation();
    let tableName = $(this).parent().parent().attr('table');
    let id = $(this).parent().attr('row-id');
    let $stateBtn = $(this);

    let request = false;
    if(tableName == 'nomenclature'){
        NProgress.start();
        request = deleteNomenclature(id);
    }else if(tableName == 'discounts'){
        NProgress.start();
        request = deleteDiscount(id);
    }else if(tableName == 'users'){
        NProgress.start();
        request = deleteUser(id);
    }else{
        return false;
    }
    
    request.success(function(response){
        NProgress.done();
        if(response.message !== undefined){
            alert(response.message);
            return true;
        }
        
        if($('#filter-state').prop("checked")){
            $stateBtn.text((response.data.state)?'удалить':'восстановить');
        }else{
            if(!response.data.state){
                $stateBtn.parent().remove();
            }
        }
    });
});
$('#filter-state').change(function(){
    applyFilter();
});
$('#filter-types').change(function(){
    applyFilter();
});
$('#print-btn').click(function (){
    let $dialog = $('#dialog');
    let id = $dialog.find('#o_id').val();
    NProgress.start();
    if(id > 0){
        let request = printOrder(id);
        request.success(function(response){
            NProgress.done();
            parseResponse(response,applyFilter);
        });
    }
});
$('#save-btn').click(function (){
    let $dialog  = $('#dialog');
    let tableName = $dialog.attr('table-name');
    
    if(tableName == 'nomenclatures'){
        let id    = $dialog.find('#d_id').val();
        let state = $dialog.find('#d_state').val();
        let name     = $dialog.find('#d_name').val();
        let type     = $dialog.find('#d_type').val();
        let min_cost = $dialog.find('#d_min_cost').val();
        let cost     = $dialog.find('#d_cost').val();
        
        let data = {'name': name,'type': type,'min_cost': min_cost,'cost': cost,'state': state};
        NProgress.start();
        if(id > 0){
            let request = putNomenclature(id,data);
            request.success(function(response){
                NProgress.done();
                parseResponse(response,applyFilter);
            });
        }else{
            let request = postNomenclature(data);
            request.success(function (response){
                NProgress.done();
                parseResponse(response,applyFilter);
            });
        }
    }else if(tableName == 'discounts'){
        let id     = $dialog.find('#ds_id').val();
        let state  = $dialog.find('#ds_state').val();
        let fio    = $dialog.find('#ds_fio').val();
        let number = $dialog.find('#ds_number').val();
        let value  = $dialog.find('#ds_value').val();
        
        let data = {'fio': fio,'number': number,'value': value,'state': state};
        NProgress.start();
        if(id > 0){
            let request = putDiscount(id,data);
            request.success(function(response){
                NProgress.done();
                parseResponse(response,applyFilter);
            });
        }else{
            let request = postDiscount(data);
            request.success(function (response){
                NProgress.done();
                parseResponse(response,applyFilter);
            });
        }
    }else if(tableName == 'users'){
        let id     = $dialog.find('#u_id').val();
        let name   = $dialog.find('#u_name').val();
        let pass   = $dialog.find('#u_pass').val();
        let pass2  = $dialog.find('#u_pass2').val();
        let rights = $dialog.find('#u_rights').val();
        let state  = $dialog.find('#u_state').val();
        
        let data = {'name': name,'pass': pass,'pass2': pass2,'rights': rights,'state': state};
        NProgress.start();
        if(id > 0){
            let request = putUser(id,data);
            request.success(function(response){
                NProgress.done();
                parseResponse(response,applyFilter);
            });
        }else{
            let request = postUser(data);
            request.success(function (response){
                NProgress.done();
                parseResponse(response,applyFilter);
            });
        }
    }
});
$('#add-btn').click(function(){
    let $dialog = $('.dialog');
    let tableName = $dialog.attr('table-name');
    $dialog.find('#errors').html('');

    if(tableName == 'nomenclatures'){
        $dialog.find('#d_id').val('');
        $dialog.find('#d_type').val($('#filter-types').val());
        $dialog.find('#d_name').val('');
        $dialog.find('#d_min_cost').val('');
        $dialog.find('#d_cost').val('');
        $dialog.find('#d_state').val(1);
    }else if(tableName == 'discounts'){
        $dialog.find('#ds_id').val('');
        $dialog.find('#ds_fio').val('');
        $dialog.find('#ds_number').val('');
        $dialog.find('#ds_value').val('');
        $dialog.find('#ds_state').val(1);
    }else if(tableName == 'users'){
        $dialog.find('#u_id').val('');
        $dialog.find('#u_name').val('');
        $dialog.find('#u_pass').val('');
        $dialog.find('#u_pass2').val('');
        $dialog.find('#u_rights').val(1);
        $dialog.find('#u_state').val(1);        
    }else{
        return false;
    }
    $dialog.parent().parent().show();
});
$('#cancel-btn').click(function(){
    closeDialog();
});