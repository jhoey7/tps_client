<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Server extends CI_Controller{
	
	function __construct(){
        parent::__construct();
    }
	
	function index(){
		$this->load->library('nusoap');
		include_once APPPATH.'models/m_server.php';
		$server = $this->nusoap;
		$server->configureWSDL('Server', 'urn:Server');
		$server->register('sendTest',
			 array('username'=> 'xsd:string',
				   'password'=> 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:sendTest',
			'urn:sendTest#sendTest',
			'rpc',
			'encoded',
			'Send Test Data From Client'
		);
		
		$server->register('sendDocumentSP2MP',
			 array('username'=> 'xsd:string',
				   'password'=> 'xsd:string',
				   'xml'=> 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:sendDocumentSP2MP',
			'urn:sendDocumentSP2MP#sendDocumentSP2MP',
			'rpc',
			'encoded',
			'Send Data Document SP2MP From Client'
		);
		
		$server->register('sendFinishQuarantine',
			 array('username'=> 'xsd:string',
				   'password'=> 'xsd:string',
				   'xml'=> 'xsd:string'),
			array('return' => 'xsd:string'),
			'urn:sendFinishQuarantine',
			'urn:sendFinishQuarantine#sendFinishQuarantine',
			'rpc',
			'encoded',
			'Send Data Document Finish Quarantine From Client'
		);
		ob_clean();
		$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : file_get_contents('php://input');
		$server->service($HTTP_RAW_POST_DATA, $this);
	}
}
