/**
 * This document is where all javascript functions will be called on
 * currently:
 * 		getCookie(c_name):returns the value of the cookie 'c_name';
 * 		userLogin(): retrieves data from the form, formats it, sends it to the ajax function, then handles the response;
 * 		userCheck(): Checks if the user is logged in and verifys that the userID exists in the database;
 * 		userSignup(): retrives data from the form, formats it, sends it to the ajax function, then handles the response;
 * 		ajax(fID, data): Handles the exchange of data between this script and functions.php. fID is the method to execute, data is the formated string data to send
 * 		showSignup(): Shows the signup area and makes it work...
 * 
 */

var response;


function getCookie(c_name){//Pretty straight forward, this function retrieves the value stored in the cookie 'c_name'
var i,x,y,ARRcookies=document.cookie.split(";");
for (i=0;i<ARRcookies.length;i++){
  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  x=x.replace(/^\s+|\s+$/g,"");
  if (x==c_name){
    return unescape(y);
    }
  }
}
function userLogin(){
	var rmEl=document.getElementById('garble');
	if(rmEl!=null) rmEl.parentNode.removeChild(rmEl);
	var elements=document.getElementsByClassName("loginData");
	var data="uname="+elements[0].value+"&pwd="+elements[1].value; 
	var errorBox=document.createElement('span');
	errorBox.setAttribute('class','login');
	errorBox.setAttribute('id','garble');
	errorBox.style.color="red";
	errorBox.style.padding="5px";
	response=ajax(1,data);
	var check=setInterval(function(){
		if(response != null){
			if(response=="Incorrect Username"){
				errorBox.innerHTML=response;
			}else if(response=="Incorrect Password"){
				errorBox.innerHTML=response;
			}else{
				document.getElementById("dimmer").style.visibility="hidden";
				document.getElementById('loginForm').style.visibility="hidden";
			}
			document.getElementById("loginForm").appendChild(errorBox);
			clearInterval(check);
			}	
		},10);

}
function userCheck(){
	var userID=getCookie("userID");
	if(userID!=null && userID!=""){
		ajax(0,userID);	
	}else{
		alert("please login!");
		ajax(0,"109");
	}
		if(response==null){// Because the request is asynchronous we have to have this system so that code can be executed when the response is done.
			var rescheck=setInterval(
				function(){
					if (response!=null){
						if(response==0){
							document.getElementById('loginForm').style.visibility="visible";
							document.getElementById("dimmer").style.visibility="visible";
							document.getElementById('loginButtons').style.marginTop="-20px";
						}else{
							document.getElementById("dimmer").style.visibility="hidden";
							document.getElementById('loginForm').style.visibility="hidden";
						}						
						clearInterval(rescheck);
					}	
				},10); //number of miliseconds between trys
		}
} 
function userSignup(){
	var data=new Array(3);
	data[0]=document.getElementById('uname').value;
	if(document.getElementById('paswd').value==document.getElementById('confpaswd').value){
		data[1]=document.getElementById('paswd').value;
		data[2]=document.getElementById('email').value;
		var dataString="uname="+data[0]+"&pswd="+data[1]+"&email="+data[2];
		ajax(2,dataString);
		var rescheck=setInterval(function(){
			if(response!=null){
				alert(response);
				clearInterval(rescheck);	
			}
			clearInterval(rescheck);
		},10);
	}else{
		alert("bad");
	}
}
/**The ajax function send asyncronous requests to the server and is currently setup for the following functions:
*		0. User verification
*		1. User login
* 		2. TODO: user signup ajax function 
* 
*/			
function ajax(fID, data){
	var url="script/functions.php?";
	//alert("ajax");
	if(window.XMLHttpRequest){
		xmlhttp=new XMLHttpRequest;
	}else{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function(){
				if(xmlhttp.readyState==4 && xmlhttp.status==200){
					response=xmlhttp.responseText;
				}
			}
	switch(fID){
		case 0:
			xmlhttp.open("GET",url+"userID="+data+"&option=verify",true);
			xmlhttp.send();
		break;
		case 1:
			xmlhttp.open("GET",url+data+"&option=login",true);
			xmlhttp.send();
		break;
		case 2:
			xmlhttp.open("GET",url+data+"&option=signup",true);
			xmlhttp.send();
		break;
		default:
			alert(fID+" "+data);
		break;
		}	
}
function showSignup(){
	var rmEl=document.getElementById('garble');
	if(rmEl!=null) rmEl.parentNode.removeChild(rmEl);
	//TODO: handle username checking by adding functionality here.
	document.getElementById('loginButtons').style.visibility="hidden";
	var elements=document.getElementsByClassName('signup');
	for(var i=0,j=elements.length; i<j; i++){
	  elements[i].style.visibility="visible";
	  elements[i].style.position="relative";
	};
}
