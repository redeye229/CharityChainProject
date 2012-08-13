<?php

#this part at the top is used in response to AJAX requests and sends the requests to the proper functions
if(isset($_GET['userID'])){
	userCheck($_GET['userID']);
}elseif (isset($_GET['uname']) && isset($_GET['pwd'])) {
	userLogin($_GET['uname'],$_GET['pwd']);
	//echo "got someting!";
}
#The contentGen function takes an XML file, looks through it and returns the text between the corrisponding LOCATION_ID tags
function contentGen($URI,$LOCATION_ID){
	$LOCATION_ID=strtoupper($LOCATION_ID);
    $file=fopen($URI,"r") or exit("Couldn't open file");
			$data=fread($file, 4060);
			$parser=xml_parser_create();
			xml_parse_into_struct($parser, $data,$values);
			xml_parser_free($parser);
			foreach ($values as $i) {
				if($i['tag']==$LOCATION_ID){
					$index=$i;
					break;
				}		
			}
			return $index['value'];
}

function userCheck($userID){
	if(db('verify',$userID)){
		echo 1;
	}else{
		echo 0;
	}
}
function userLogin($uname,$pwd){
	$data=array($uname,$pwd);
	$response=db('login',$data);
	if(!(int)($response)){
		echo $response;
	}else{
		setcookie('userID',$response,0,'/');//TODO: Make "Keep me logged in for 30 days" option
		echo "Set cookie $response";
	}
}
  
 /**
 * Function db($options,$data) is a way to consolidate all mySQL functions to a single space. Options are not case sensitive 
 * Currently supported options:
 * 		'VERIFY': requires a user id number and returns true if the user exists and false otherwise. 
 * 		'LOGIN': Returns userID on sucess and error strings on failure
 * 		'SIGNUP': TODO: Create db signup function
 **/
function db($option,$data){
	$option=strtoupper($option);
	$con=mysql_connect("localhost","php","12345");
	if(!$con){
		die("Could not connect: ".mysql_error());
	}else{
		mysql_select_db("Chain");
		switch ($option) {
			case 'VERIFY':
				//echo "Proper case <br />";
				$query="SELECT * FROM `Chain` . `users` WHERE `userID`='$data'";
				$response=mysql_query($query,$con);
				$row=mysql_fetch_array($response);
				if($row=="" || $row==NULL){
					return FALSE;
				}else{
					return TRUE;
				}
			break;
			case 'LOGIN':
				$query="SELECT * FROM `Chain`.`users` WHERE `username`='$data[0]'";
				$result = mysql_query($query,$con) or die('mySQL error:'+mysql_error());				
				$row=mysql_fetch_array($result);
				if($row=="" || $row==NULL){
					return "Incorrect Username";
				}else{
					if($data[1]==$row['password']){
						return $row['userID'];
					}else{
						return "Incorrect Password";
					}
				}
			break;
			default:
				return "Unrecognized command";
			break;
		}
	}
	mysql_close($con);
}

?>