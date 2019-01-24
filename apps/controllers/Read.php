<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Read extends CI_Controller{
	function __construct(){
        parent::__construct();
    }
	
	function repository(){
		$this->load->model("m_read");
		$this->m_read->repository();	
	}
	
	function sendmail_bc11_is_null(){
		$this->load->model("m_read");
		$this->m_read->sendmail_bc11_is_null();
	}
	
	function sendmail_doc_codeco_is_null(){
		$this->load->model("m_read");
		$this->m_read->sendmail_doc_codeco_is_null();
	}
}

