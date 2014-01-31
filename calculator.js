//CALCULATOR JAVASCRIPT

var num1 = document.getElementById("num1");
var num2 = document.getElementById("num2");
var ans = document.getElementById("ans");
var operView = document.getElementById("operView");
var operList = document.getElementByName("oper");


var calc = function(){
	document.getElementById("ans").innerHTML = "CALC";
}

num1.addEventListener("keyup",calc,false);
num2.addEventListener("keyup",calc,false);
operList.addEventListener("click",calc,false);

