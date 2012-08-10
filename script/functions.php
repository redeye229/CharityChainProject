<?php
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
?>