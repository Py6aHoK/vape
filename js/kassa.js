var TABLES = [
    {'order':null},
    {'order':null},
    {'order':null},
    {'order':null},
    {'order':null},
    {'order':null},
    {'order':null},
    {'order':null},
    {'order':null},
    {'order':null},
    {'order':null},
    {'order':null}
];
var DISH_TYPES = [];
var DISHES = [];
var DISCOUNTS = [];
var SELECTED_TYPE = 0;
var SELECTED_TABLE = 0;

function LoadTypes(callback){
    var request = false;
    NProgress.start();

    request = getTypes();
    request.success(function(response){
        NProgress.done();
        if(response.message !== undefined){
            alert(response.message);
            return true;
        }
        
        DISH_TYPES = response.data;
        DISH_TYPES.unshift({'id':0,'name':'Столы'});
        callback();
    });
}
function RenderTypesList(){
    let $container = $('nomenclature-types');
    $container.html("");
    
    DISH_TYPES.forEach(function(element){
        let $item_html = $('<span>');
            $item_html.attr("type-id",element.id);
            $item_html.text(element.name);
            
            if(element.id == SELECTED_TYPE){
                if(SELECTED_TYPE == 0){
                    RenderTablesList();
                }
                $item_html.addClass('active');
            }
        $container.append($item_html);
    });
}
function LoadNomenclatures(type_id,callback){
    var request = false;
    NProgress.start();

    request = getNomenclatures(type_id);
    request.success(function(response){
        NProgress.done();
        if(response.message !== undefined){
            alert(response.message);
            return true;
        }
        
        DISHES = response.data;
        callback();
    });
}
function RenderTablesList(){
    let $container = $('nomenclature-container');
        $container.html("");
        
        TABLES.forEach(function(element,id){
            let $table = $('<nomenclature class="table">');
                $table.attr("item-id",(id + 1));
                $table.text("Стол №" + (id + 1));
                if(id == SELECTED_TABLE){
                    $table.addClass('active');
                }
            $container.append($table);
        })
}
function RenderNomenclaturesList(){
    let $container = $('nomenclature-container');
        $container.html("");
        
        DISHES.forEach(function(element){
            let $dish = $('<nomenclature>');
            let $cost = $('<cost>');
                $dish.attr("item-id",element.id);
                $dish.text(element.name);
                $cost.text(element.cost);

            $dish.append($cost);
            $container.append($dish);
        })
}
function RenderButtons(){
    $('.buttons > *').removeClass('disabled');
    
    if(GetOrder().selected_dish === null){
        $('btn-plus').addClass('disabled');
        $('btn-minus').addClass('disabled');
        if(GetOrder().guests.length == 1){
            $('btn-remove').addClass('disabled');
        }
    }else{
        $('btn-percent').addClass('disabled');
    }
}

class Guest{
    constructor(name){
        this.name = name;
        this.discount = 0;
        this.dishes = [];
        this.card = 0;
    }
    AddDish(id){
        let _dish = this.dishes.find(function(element){
            if(element.id == this){
                return true;
            }
        },id);
        let o = GetOrder();
        if(_dish == undefined){
            _dish = DISHES.find(function(element){
                if(element.id == this){
                    return true;
                }
            },id);
            
            if(_dish == undefined){
                o.Render();
                return;
            }
            let dish = Object.assign({'count':1},_dish);
            this.dishes.push(dish);
            o.Render();
            return;
        }
        _dish.count++;
        o.Render();
    };
    IncreaseDishCount(id){
        let dish = this.dishes.find(function(element){
            if(element.id == this){
                return true;
            }
        },id);
        if(dish !== undefined){
            dish.count++;
        }
        GetOrder().Render();
    }
    DecreaseDishCount(id){
        let dish = this.dishes.find(function(element){
            if(element.id == this){
                return true;
            }
        },id);
        if(dish !== undefined){
            if(dish.count == 1){
                this.DeleteDish(id);
                return true;
            }
            dish.count--;
        }
        GetOrder().Render();
    }
    DeleteDish(id){
        var dish = this.dishes.findIndex(function(element){
            if(element.id == this){
                return true;
            }
        },id);
        let o = GetOrder();
        if(dish !== undefined){
            this.dishes.splice(dish,1);
            
            if(this.dishes.length == 0){
                o.selected_dish = null;
            }else{
                o.selected_dish = (dish > this.dishes.length - 1) ? this.dishes[this.dishes.length - 1].id : this.dishes[dish].id;
            }
        }
        o.Render();
    }
    Modify(discount = 0,card = null,name = null){
        this.name = name;
        this.card = card;
        this.discount = discount;
        GetOrder().Render();
    }       
}
function Pay(card = 0){
    let o = GetOrder();
    
    o.card = card;
    var request = false;
    NProgress.start();
    
    request = SendOrder(o.Pack());
    request.success(function(response){
        NProgress.done();
        if(response.message !== undefined){
            alert(response.message);
            return true;
        }
        NewOrder();
    });
}
$('body').on('click','nomenclature',function(){
    if(SELECTED_TYPE == 0){
        SELECTED_TABLE = $(this).attr('item-id') - 1;
        if(TABLES[SELECTED_TABLE].order == null){
            TABLES[SELECTED_TABLE].order = new Order();
            GetOrder().AddGuest();
        }
        RenderTablesList();
        GetOrder().Render();
        return true;
    }
    let dish_id = $(this).attr('item-id');
    let o = GetOrder();
    let guest = o.guests[o.selected_guest];
        guest.AddDish(dish_id);
});
$('body').on('click','nomenclature-types > span',function(event){
    //Клик по кнопке типа
    event.stopPropagation();
    SELECTED_TYPE = $(this).attr('type-id');
    
    RenderTypesList();
    if(SELECTED_TYPE == 0) return true;
    
    LoadNomenclatures(SELECTED_TYPE,RenderNomenclaturesList);
});
$('body').on('click','guest > info',function(){
    let o = GetOrder();
    o.selected_guest = $(this).parent().index();
    o.selected_dish  = null;
    $('order-info .selected').removeClass('selected');
    $(this).parent().addClass('selected');
    RenderButtons();
});
$('body').on('click','guest > order > dish',function(){
    let o = GetOrder();
    o.selected_guest = $(this).parent().parent().index();
    o.selected_dish = $(this).attr('dish-id');
    $('order-info .selected').removeClass('selected');
    $(this).addClass('selected');
    RenderButtons();
});
$('btn-new-order').click(function(){
    NewOrder();
});
$('btn-new-guest').click(function(){
    GetOrder().AddGuest();
});
$('btn-remove').click(function(){
    let o = GetOrder();
    if(o.selected_dish == null){
        if(o.guests.length > 1){
            o.DeleteSelectedGuest();
        }
    }else{
        o.DeleteSelectedDish();
    }
});
$('btn-plus').click(function(){
    GetOrder().IncreaseSelectedDishCount();
});
$('btn-minus').click(function(){
    GetOrder().DecreaseSelectedDishCount();
});
$('percent').click(function(){
    let value = $(this).text();
    let $container = $('.left-container-wrape');
    
    let o = GetOrder();
    if($container.hasClass('orders-percents')){
        o.ChangeDiscount(value);
    }else{
        o.ChangeGuestDiscount(value);
    }
    $container.removeClass('orders-percents guest-percents');
});
$('btn-percents').click(function(){
    let $container = $('.left-container-wrape');
    if($container.hasClass('orders-percents')){
        $container.removeClass('orders-percents');
    }else{
        $container.removeClass('guest-percents');
        $container.addClass('orders-percents');
    }
});
$('btn-percent').click(function(){
    let $container = $('.left-container-wrape');
    if($container.hasClass('guest-percents')){
        $container.removeClass('guest-percents');
    }else{
        $container.removeClass('orders-percents');
        $('#card-number').val("");
        $container.addClass('guest-percents');
    }
});
$('btn-search-card').click(function(){
    let number = $('#card-number').val();
    if(number > 0){
        var request = false;
        NProgress.start();

        request = getDiscountById(number);
        request.success(function(response){
            NProgress.done();
            if(response.message !== undefined){
                alert(response.message);
                return true;
            }
            if(response.data.state == 1){
                GetOrder().ModifySelectedGuest(response.data.value,number,response.data.fio);
                $('.left-container-wrape').removeClass('guest-percents');
            }
        });
    }
});
$('btn-pay-card').click(function(){
    Pay(1);
})
$('btn-pay-cash').click(function(){
    Pay();
})
class Order{
    constructor(){
        this.discount = 0;
        this.guests = [];
        this.card = 0;
        this.selected_guest = null;
        this.selected_dish  = null;
    }
    AddGuest(){
        let g = new Guest(null);
        this.selected_guest = this.guests.push(g) - 1;
        this.selected_dish  = null;
        this.Render();
    }
    Pack(){
        var result = {};
            result.table = SELECTED_TABLE + 1;
            result.card  = this.card;
            result.discount = this.discount;
            result.guests = [];
            this.guests.forEach(function(guest){
                let guest_data = {};
                    guest_data.discount = guest.discount;
                    guest_data.card = guest.card;
                    guest_data.dishes = [];

                    guest.dishes.forEach(function(dish){
                        let dish_data = {};
                            dish_data.id = dish.id;
                            dish_data.count = dish.count;
                            this.push(dish_data);
                    },guest_data.dishes);
                    this.push(guest_data);
            },result.guests);
        return result;
    }
    IncreaseSelectedDishCount(){
        if(this.selected_dish !== undefined){
            this.guests[this.selected_guest].IncreaseDishCount(this.selected_dish);
        }
    }
    DecreaseSelectedDishCount(){
        if(this.selected_dish !== undefined){
            this.guests[this.selected_guest].DecreaseDishCount(this.selected_dish);
        }
    }
    ChangeGuestDiscount(value){
        this.guests[this.selected_guest].Modify(value);
        this.Render();
    }
    ChangeDiscount(value){
        this.discount = value;
        this.Render();
    }
    ModifySelectedGuest(discount,number,name){
        this.ModifyGuest(this.selected_guest,discount,number,name);
    };
    ModifyGuest(id,discount,number = null,name = null){
        this.guests[id].Modify(discount,number,name);
        this.Render();
    };
    DeleteSelectedDish(){
        this.guests[this.selected_guest].DeleteDish(this.selected_dish);
    }
    DeleteSelectedGuest(){
        this.DeleteGuest(this.selected_guest);
    }
    DeleteGuest(id){
        if(this.guests[id] !== undefined){
            if(id > 0 && id == this.guests.length - 1){
                this.selected_guest = this.guests.length - 2;
            }
            this.guests.splice(id,1);
        }
        this.Render();
    }
    Calc(){
        var Sum = 0;
        for(let g = 0; g < this.guests.length; g++){
            let guest = this.guests[g];
            guest.dishes.forEach(function (dish){
                let discount = this;
                let _cost = (dish.cost * (1 - (guest.discount * 0.01))) * (1 - (discount * 0.01));
                if(_cost < dish.min_cost){
                    _cost = dish.min_cost;
                }
                Sum += _cost * dish.count;
            },this.discount);
        }
        return (Sum).toFixed(2);
    }
    Render(){
        let sm = this.Calc();
        let _selected_guest = this.selected_guest;
        let _selected_dish  = this.selected_dish;
        
        $('order-summa').text(sm + ' р.');
        $('order-discount').text(this.discount);
        
        let $order_info = $('order-info');
        $order_info.html("");
        
        let $order_table = $('<order-table>');
            $order_table.attr("id",(SELECTED_TABLE + 1));
            $order_table.text("Стол №" + (SELECTED_TABLE + 1));
            $order_info.append($order_table);
            
        let $guests = $('<guests>');
            let guest_num = 0;
            this.guests.forEach(function(guest,id){
                let $guest = $('<guest>');

                if(_selected_dish == null && _selected_guest == id){
                    $guest.addClass('selected');
                }
                //Сбор информации о госте
                {
                    let $guest_info = $('<info>');
                        let $guest_name = $('<name>');
                            if(guest.name == null){
                                guest_num++;
                                $guest_name.text("Гость №" + guest_num);
                            }else{
                                $guest_name.text(guest.name);
                            }
                        let $guest_discount = $('<discount>');
                            $guest_discount.text(guest.discount + "%");

                        $guest_info.append($guest_name);
                        $guest_info.append($guest_discount);
                    $guest.append($guest_info);
                }
                //Сбор информации о заказе
                {
                    let $order = $('<order>');
                        guest.dishes.forEach(function(dish_info){
                            let $dish = $('<dish>');
                                if(_selected_guest == id && _selected_dish == dish_info.id){
                                    $dish.addClass('selected');
                                }
                                $dish.attr("dish-id",dish_info.id);
                                    let $dish_name = $('<dish-name>');
                                    let $dish_price = $('<dish-price>');
                                    let $dish_count = $('<dish-count>');
                                    
                                        $dish_name.text(dish_info.name);
                                        $dish_price.text(dish_info.cost + " р.");
                                        $dish_count.text("x" + dish_info.count);

                                        $dish.append($dish_name);
                                        $dish.append($dish_price);
                                        $dish.append($dish_count);

                                $order.append($dish);
                        });
                        $guest.append($order);
                }
                $guests.append($guest);
            });
            $order_info.append($guests);
        RenderButtons();            
    }
};

function GetOrder(table = SELECTED_TABLE){
    return TABLES[table].order;
}
function NewOrder(){
    TABLES[SELECTED_TABLE].order = new Order();
    GetOrder().AddGuest();
    
    LoadTypes(RenderTypesList);
}