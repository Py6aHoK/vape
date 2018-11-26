function SendOrder(data){
    return $.ajax({
        url: "/ajax/order/add",
        data: JSON.stringify(data),
        type: 'POST',    
        dataType: 'json'
    });
}
function printOrder(id){
    return $.ajax({
        url: "/ajax/order/print/" + id,
        type: 'GET',
        dataType: 'json'
    });
}
function getTypes(){
    return $.ajax({
        url: "/ajax/types/",
        type: 'GET',    
        dataType: 'json'
    });
}
function getNomenclatureById(id){
    return $.ajax({
        url: "/ajax/nomenclature/" + id,
        type: 'GET',    
        dataType: 'json'
    });
}
function getNomenclatures(typeId,state){
    return $.ajax({
        url: "/ajax/nomenclatures/" + typeId,
        data: state,
        type: 'GET',    
        dataType: 'json'
    });
}
function postNomenclature(data){
    return $.ajax({
        url: "/ajax/nomenclature/add",
        type: 'POST',
        data: JSON.stringify(data),
        dataType: 'json'
    });
}
function putNomenclature(id,data){
    return $.ajax({
        url: "/ajax/nomenclature/update/" + id,
        type: 'PUT',
        data: JSON.stringify(data),
        dataType: 'json'
    });
}
function deleteNomenclature(id,state){
    return $.ajax({
        url: "/ajax/nomenclature/delete/" + id,
        type: 'DELETE',
        dataType: 'json'
    });
}
function getDiscountById(id){
    return $.ajax({
        url: "/ajax/discount/" + id,
        type: 'GET',    
        dataType: 'json'
    });
}
function getDiscounts(state){
    return $.ajax({
        url: "/ajax/discount",
        data: state,
        type: 'GET',    
        dataType: 'json'
    });
}
function postDiscount(data){
    return $.ajax({
        url: "/ajax/discount/add/",
        type: 'POST',
        data: JSON.stringify(data),
        dataType: 'json'
    });
}
function putDiscount(id,data){
    return $.ajax({
        url: "/ajax/discount/update/" + id,
        type: 'PUT',
        data: JSON.stringify(data),
        dataType: 'json'
    });
}
function deleteDiscount(id){
    return $.ajax({
        url: "/ajax/discount/delete/" + id,
        type: 'DELETE',
        dataType: 'json'
    });
}

function getOrderById(id){
    return $.ajax({
        url: "/ajax/order/" + id,
        type: 'GET',    
        dataType: 'json'
    });
}

function getUserById(id){
    return $.ajax({
        url: "/ajax/user/" + id,
        type: 'GET',    
        dataType: 'json'
    });
}
function getUsers(state){
    return $.ajax({
        url: "/ajax/user",
        data: state,
        type: 'GET',    
        dataType: 'json'
    });
}
function postUser(data){
    return $.ajax({
        url: "/ajax/user/add/",
        type: 'POST',
        data: JSON.stringify(data),
        dataType: 'json'
    });
}
function putUser(id,data){
    return $.ajax({
        url: "/ajax/user/update/" + id,
        type: 'PUT',
        data: JSON.stringify(data),
        dataType: 'json'
    });
}
function deleteUser(id){
    return $.ajax({
        url: "/ajax/user/delete/" + id,
        type: 'DELETE',
        dataType: 'json'
    });
}

