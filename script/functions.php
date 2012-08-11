<?php
#this part at the top is used in response to AJAX requests and sends the requests to the proper functions
if(array_key_exists("userID", $_GET)){
		//echo "got";
		userCheck($_GET["userID"]);
}else{

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
		echo "DAT USER BE ALL UP IN HER";
	}else{
		echo "NA DAWG HE AIN'T HER";
	}
	//TO DO: Integrate mySQL database for user verification
}

function db($option,$data){//All Database operations will be run through this function for less replication of code
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
			default:
				echo "Well here's your problem!";
				break;
		}
	}
	mysql_close($con);
}

?>