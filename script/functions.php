<?php
$GLOBALS['URL']="localhost/CharityChainProject";// The final URL of the website
if(!isset($GLOBALS['fileroot'])){
$GLOBALS['fileroot']='/var/www/CharityChainProject';//The absolute path to the root of the website
}
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
		case 'CONFIRM':
			tempToReal($_GET['confcode']);
			break;
		default:
				echo "Fatal ERROR: Option flag not recognized";			
			break;
	}
}
//The contentGen function takes an XML file, looks through it and returns the text between the corrisponding LOCATION_ID tags
function contentGen($LOCATION_ID){
	$fileroot=$GLOBALS['fileroot'];
	$URI="$fileroot/content.xml";
	$LOCATION_ID=strtoupper($LOCATION_ID);
    $file=fopen($URI,"r") or exit("Couldn't open file: $URI");
			$data=fread($file, 4060);
			fclose($file);
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
 	$url=$GLOBALS['URL'];
 		if(TRUE){//filter_var($email,FILTER_VALIDATE_EMAIL)){
 			$confcode=md5(uniqid(rand()));
			$data=array($confcode,$uname,$pswd,$email);
			$response=db("temp_signup",$data);
			if($response===TRUE && mailer('confirm',array($confcode,$email))){
				echo "A confirmation email has been sent to the given address!";
			}else if($response=1062) {
				userSignup($data[1],$data[2],$data[3]);
			}else{
				echo "RESPONSE: $response \r\nMESSAGE: $message \r\nCONFCODE: $confcode \r\nURL: $url";
			}
 		}else{
 			echo "Invalid Email $email";
 		}	
 }
function tempToReal($confID){
	$response=db('temp_confirm',$confID);
	print_r($response);
	if($response!=FALSE){
		echo db('signup',$response);
	}
}
 /**
  * Function mailer($option,$data) is a function designed to generate a good looking email body for the preset messages sent from the server to the user
  * Currently supported options:
  * 		'CONFIRM': TODO:Make the CONFIRM message look good
  * 
  */
 function mailer($option,$data){
 		$option=strtoupper($option);
 		$message=NULL;
 		$url=$GLOBALS['URL'];
		$urli=substr($url,0,strpos($url,"/"));
 	switch ($option) {
		 case 'CONFIRM':
			 $messageText=wordwrap(trim(contentGen('confirm_email')," "),70);
			 $header="From: Robots <robots@$urli>". "\r\n".'MIME-Version: 1.0' . "\r\n".'Content-type: text/html; charset=iso-8859-1';
			 $subject="Confirmation code from Charity Chain";
			 $message="<html>
			 <body style:'background-color:grey;'>
			 <div id='containter' style:'margin:10px; padding:5px; background-color:white; box-shadow:5px'>
			 <p>$messageText</p>
			 <a href='$url/script/functions.php?option=confirm&confcode=$data[0]'>$url/index.php?option=confirm&confcode=$data[0]</a>
			 </div>
			 </body>
			 </html>";
			 $result=mail($data[1],$subject,$message,$header);
			 return $result;
			 break;
		 default:
			 return FALSE;
			 break;
	 }
 }
 /**
 * Function db($options,$data) is a way to consolidate all mySQL functions to a single space. Options are not case sensitive 
 * Currently supported options:
 * 		'VERIFY': requires a user id number and returns true if the user exists and false otherwise. 
 * 		'LOGIN': Returns userID on sucess and error strings on failure
 * 		'SIGNUP': Create db signup function
 *		'CONF_SIGNUP': Add the user to the temp database for eventual addition to the actual database
 *		'TEMP_CONFIRM':Confirms that the temporary user ID exists and returns the username, password and email of that user then deletes the entry from the data base 	
 * 	 
 */
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
			case 'TEMP_CONFIRM':
				$query="SELECT `username`,`password`,`email` FROM `Chain`.`tempusers` WHERE `confID`='$data'";
				$result = mysql_query($query);
				$row = mysql_fetch_array($result);
				if($row!=NULL && $row!=""){
					$query="DELETE FROM `Chain`.`tempusers` WHERE `confID`='$data'";
					$result=mysql_query($query);
					return $row;
				}else{
					return FALSE;
				}
				
				break;
			case 'TEMP_SIGNUP':
				$query="INSERT INTO `Chain`.`tempusers` (`confID`,`username`,`password`,`email`) VALUES ('$data[0]','$data[1]','$data[2]','$data[3]')";
				$result = mysql_query($query);
				if(mysql_error()=="" || mysql_error==NULL){
					return TRUE;
				}else{
					return mysql_errno($con);
				}
			break;
			case 'SIGNUP':
				$query="INSERT INTO `Chain`.`users` (`username`,`password`,`email`) VALUES ('$data[0]','$data[1]','$data[2]')";
				$result = mysql_query($query);
				if(mysql_error()=="" || mysql_error==NULL){
					return TRUE;
				}else{
					return mysql_error($con)." error number:".mysql_errno($con);
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