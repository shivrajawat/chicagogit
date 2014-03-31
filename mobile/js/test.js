// JavaScript Document
function myfunc(){
    alert('pageshow triggered');
}

$(document).delegate('#welcome', 'pageshow', myfunc);