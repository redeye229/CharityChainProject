<?php
$URL="localhost";
//this part at the top is used in response to AJAX requests and sends the requests to the proper functions
if(isset($_GET['option'])){
	$option=strtoupper($_GET['option']);
	switch ($option) {
		case 'VERIFY':
				userCheck($_GET['userID']);
			break;
		case 'LOGIN':
			userLogin($_GET['uname'],$_GET['pwd']);
			break;
		case 'SIGNUP':
			userSignup($_GET['uname'], $_GET['pswd'], $_GET['email']);
			break;
		default:
				echo "Fatal ERROR: Option flag not recognized";			
			break;
	}
}



//The contentGen function takes an XML file, looks through it and returns the text between the corrisponding LOCATION_ID tags
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
 function userSignup($uname,$pswd,$email){
 		if(filter_var($email,FILTER_VALIDATE_EMAIL)){
 			$confcode=md5(uniqid(rand()));
			$data=array($confcode,$uname,$pswd,$email);
			$message=contentGen("content.xml", "confirm_email")+"\r\n $URL?conf=$confcode";
			$message=wordwrap($message,70);		
			mail($email, "Confirm your acount with the Charity Chain", $message);
			$response=db("temp_signup",$data);
			if($response===TRUE){
				echo "A confirmation email has been sent to the given address!";
			}else{
				echo $response+" RESPONSE";
			}
 		}else{
 			echo "Invalid Email $email";
 		}	
			
 	
 }
 /**
 * Function db($options,$data) is a way to consolidate all mySQL functions to a single space. Options are not case sensitive 
 * Currently supported options:
 * 		'VERIFY': requires a user id number and returns true if the user exists and false otherwise. 
 * 		'LOGIN': Returns userID on sucess and error strings on failure
 * 		'SIGNUP': Create db signup function
 *		'CONF_SIGNUP': Add the user to the temp database for eventual addition to the actual database
 * 	 
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
			case 'TEMP_SIGNUP':
				$query="INSERT INTO `Chain`.`tempusers` (`confID`,`username`,`password`,`email`) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]')";
				$result = mysql_query($query);
				$row=mysql_fetch_array($result);
				if($row!=NULL && $row!=""){
					return TRUE;
				}else{
					return mysql_error();
				}
			break;
			case 'SIGNUP':
				$query="INSERT INTO `Chain`.`users` (`username`,`password`,`email`,`refID`) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]')";
				$result = mysql_query($query);
				$row=mysql_fetch_array($result);
				if($row!=NULL && $row!=""){
					return TRUE;
				}else{
					return mysql_error();
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