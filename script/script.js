var response;
var responseFlag=false;

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
function userCheck(){
	//alert("I hear you!");
	var userID=getCookie("userID");
	if(userID!=null && userID!=""){
		alert(userID);
	ajax(0,userID);	
	}else{
		ajax(0,"1");
	}
		if(response==null){
			var rescheck=setInterval(
				function(){
					if (response!=null){
						responseFlag=true;
						alert(response);
						clearInterval(rescheck);
					}	
				},10 //number of miliseconds between trys
			);
		
		//TO DO: handle log in and sign up
	}
} 
//the ajax function send asyncronous requests to the server and is currently setup for the following functions:
//			1. User verification and login
function ajax(fID, data){
	
	//alert("ajax");
	if(window.XMLHttpRequest){
		xmlhttp=new XMLHttpRequest;
	}else{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	switch(fID){
		case 0:
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState==4 && xmlhttp.status==200){
				response=xmlhttp.responseText;
			}
		}
			xmlhttp.open("GET","script/functions.php?userID="+data,true);
			xmlhttp.send();
			break;
		}	
}

function showSignup(){
	alert("yadda yadda");
	
}
