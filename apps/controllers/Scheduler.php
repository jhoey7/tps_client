<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scheduler extends CI_Controller{
	function __construct(){
        parent::__construct();
    }
	
	function set_coarricodeco($type=""){
		$this->load->model("m_scheduler");
		$this->m_scheduler->set_coarricodeco($type);
	}
	
	function get_permit($type=""){
		$this->load->model("m_scheduler");
		$this->m_scheduler->get_permit($type);
	}
	
	function read_permit($type=""){
		$this->load->model("m_scheduler");
		$this->m_scheduler->read_permit($type);
	}
}

