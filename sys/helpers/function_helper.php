<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('emptystr')){
	function emptystr($str){
		if(($str=="") || ($str=='null') || ($str=='-')  || ($str=='00000000'))
			$return = "-";
		else
			$return = WhiteSpaceXML($str);
		
		$var_data = trim($return);
		return $var_data;
	}
}

if(!function_exists('escape')){	
	function escape($text,$type){
		if(!empty($text)){
			if($type == "DATESLASH"){
				$arrdate = explode("/",$text);
				$text = $arrdate[2]."-".$arrdate[1]."-".$arrdate[0];
			}else if($type == "DATESLASHS"){
				$arrdate = explode("/",$text);
				$text = $arrdate[2]."-".$arrdate[0]."-".$arrdate[1];
			}else if($type == "DATEDASH"){
				$text = substr($text,0,4)."-".substr($text,4,2)."-".substr($text,6,2);
			}else if($type == "STRING"){
				$text = "";
			}
			$result = trim(strtoupper($text));
		}else{
			$result = null;
		}
		return $result;
	}
}

if(!function_exists('WhiteSpaceXML')){	
	function WhiteSpaceXML($text){
		$hasil = str_replace("&","&amp;",$text);
		$hasil = str_replace("'","&apos;",$hasil);
		$hasil = str_replace("\"","&quot;",$hasil);
		$hasil = str_replace("<","&lt;",$hasil);
		$hasil = str_replace(">","&gt;",$hasil);
		return trim($hasil);
	}
}

if(!function_exists('check_file')){	
	function check_file($filename){
		if(file_exists($filename)){
			return true;
		}else{
			return false;
		}
	}
}

if(!function_exists('create_file')){	
	function create_file($filename){
		$filename = fopen($filename, "w");
		fputs($filename, $txt);
		fclose($filename);
	}
}

if(!function_exists('remove_file')){
	function remove_file($filename) {
		if(file_exists($filename)){
			unlink($filename);
		}
	}
}

?>