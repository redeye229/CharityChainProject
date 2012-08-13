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
function werSignup(){
	//TODO:write basic signup interface
}

/**The ajax function send asyncronous requests to the server and is currently setup for the following functions:
*		0. User verification
*		1. User login
* 		2. TODO: user signup ajax function 
* 
* */			
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
			xmlhttp.open("GET",url+"userID="+data,true);
			xmlhttp.send();
		break;
		case 1:
			xmlhttp.open("GET",url+data,true);
			xmlhttp.send();
		break;
		case 2:
		
		break;
		default:
			alert(fID+" "+data);
		break;
		}	
}

function showSignup(){
	var rmEl=document.getElementById('garble');
	if(rmEl!=null) rmEl.parentNode.removeChild(rmEl);
	document.getElementById('loginButtons').style.visibility="hidden";
	var elements=document.getElementsByClassName('signup');
	for(var i=0,j=elements.length; i<j; i++){
	  elements[i].style.visibility="visible";
	  elements[i].style.position="relative";
	};
	
}
