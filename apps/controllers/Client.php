<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Client extends CI_Controller{
	function __construct(){
        parent::__construct();
    }
	
	function index(){
		if(strtolower($_SERVER['REQUEST_METHOD'])=="post"){
			$this->load->library('Nusoap');
			$client = new nusoap_client(WSDL,true);
			if(SET_PROXY) $client->setHTTPProxy(PROXYHOST,PROXYPORT);
			$error  = $client->getError();
			if($error){
				echo '<h2>Constructor error</h2>'.$error;
				exit();
			}
			$method     = $this->input->post('txt_method');
			$username  = $this->input->post('txt_username');
			$password  = $this->input->post('txt_password');
			$xml_parse = $this->input->post('txt_xmlparse');
			
			$param  = array( 'Username'=>$username, 'Password'=>$password, 'fStream'=>$xml_parse);
			$response = $client->call($method,$param);
			if($response!=""){
				$return = $response[$method.'Result'];
				$pos = strpos(strtolower($return), 'berhasil');
				if($pos !== false){
					echo '<div style="background-color:#0066FF; color:#FFFFFF; margin:3px 3px 3px 3px; padding:3px 3px 3px 3px; font-weight:bold; font-size:16px">'.$return.'</div>';
				}else{
					echo '<div style="background-color:#0066FF; color:#FFFFFF; margin:3px 3px 3px 3px; padding:3px 3px 3px 3px; font-weight:bold; font-size:16px">'.$return.'</div>';
				}	
			}else{
				echo '<div style="background-color:#0066FF; color:#FFFFFF; margin:3px 3px 3px 3px; padding:3px 3px 3px 3px; font-weight:bold; font-size:16px">'.$return.'</div>';
			}
			exit();
		}
		$this->load->view('client');	
	}
	
	function create_xml_coco($aprf,$method){
		$this->load->model("m_client");
		$data = $this->m_client->create_xml_coco($aprf,$method);
	}
	
	function send_coco($aprf,$method){
		$this->load->model("m_client");
		$data = $this->m_client->send_coco($aprf,$method);
	}
	
	function create_xml_plp($aprf,$method){
		$this->load->model("m_client");
		$data = $this->m_client->create_xml_plp($aprf,$method);
	}
	
	function send_plp($aprf){
		$this->load->library('Nusoap');
		$client = new nusoap_client(WSDL);
		$error  = $client->getError();
		if($error){
			echo '<h2>Constructor error</h2>' . $error;
			exit();
		}else{
			$this->load->model("m_client");
			$data = $this->m_client->send_plp($aprf);	
		}
	}
}

