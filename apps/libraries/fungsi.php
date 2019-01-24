<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(E_ERROR);
class Fungsi{
	function getExt($namaFile){
	   for($i = -1; $i> -(strlen($namaFile));$i-- ){
		  if (substr($namaFile, $i, 1)=='.')
			 return (substr($namaFile, $i));            
	   }
	}
	
	function get_format_date($tanggal)
	{
		$returnDate = "";
		
		if (($tanggal!="") and ($tanggal!="0000-00-00"))
			$returnDate = str_replace("-","",$tanggal);
		
		return $returnDate;
	}
	
	function dateformat($date){
	   if (strstr($date, "-"))   {
			   $date = preg_split("/[\/]|[-]+/", $date);
			   $date = $date[2]."/".$date[1]."/".$date[0];
			   return $date;
	   }
	   else if (strstr($date, "/"))   {
			   $date = preg_split("/[\/]|[-]+/", $date);
			   $date = $date[2]."-".$date[1]."-".$date[0];
			   return $date;
	   }
	   else if (strstr($date, ".")) {
			   $date = preg_split("[.]", $date);
			   $date = $date[2]."-".$date[1]."-".$date[0];
			   return $date;
	   }
	   return false;
	}
	
	function getIP($type) { 
		if (getenv("HTTP_CLIENT_IP") 
		&& strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
		$ip = getenv("HTTP_CLIENT_IP"); 
		else if (getenv("REMOTE_ADDR") 
		&& strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
		$ip = getenv("REMOTE_ADDR"); 
		else if (getenv("HTTP_X_FORWARDED_FOR") 
		&& strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
		$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		else if (isset($_SERVER['REMOTE_ADDR']) 
		&& $_SERVER['REMOTE_ADDR'] 
		&& strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
		$ip = $_SERVER['REMOTE_ADDR']; 
		else { 
		$ip = "unknown"; 
		return $ip; 
		} 
		if ($type==1) {return md5($ip);} 
		if ($type==0) {return $ip;} 
	}
	
	
		
}