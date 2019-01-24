<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Translate extends CI_Controller{
	function __construct(){
        parent::__construct();
	}
	
	function get_coarri_edi_to_flt(){
		$this->load->model('m_translate');
		$this->m_translate->get_coarri_edi_to_flt();
	}
	
	function get_coarri_flt_to_db(){
		$this->load->model('m_translate');
		$this->m_translate->get_coarri_flt_to_db();
	}
	
	function get_codeco_edi_to_flt(){
		$this->load->model('m_translate');
		$this->m_translate->get_codeco_edi_to_flt();
	}
	
	function get_codeco_flt_to_db(){
		$this->load->model('m_translate');
		$this->m_translate->get_codeco_flt_to_db();
	}
}