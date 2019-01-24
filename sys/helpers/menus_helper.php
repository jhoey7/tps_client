<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('menu_content')){
	function menu_content(){
		$content = "";
		$data = array();
		$ci =& get_instance();
		$ci->load->database();
		$KD_USER = $ci->session->userdata('ID');
		$segs_1 = $ci->uri->segment(1);
		$segs_2 = $ci->uri->segment(2);
		if($segs_2!="") $segs = trim($segs_1."/".$segs_2);
		elseif($segs_1!="") $segs = trim($segs_1);
		if($ci->session->userdata('TIPE_ORGANISASI')=="SPA"){
			$SQL = "SELECT B.ID, IFNULL(B.ID_PARENT,0) AS ID_PARENT, B.JUDUL_MENU, B.URL, B.URUTAN, B.TIPE, B.TARGET, 
					B.ACTION, B.CLS_ICON AS ICON
					FROM app_menu B
					ORDER BY B.ID_PARENT, B.URUTAN ASC"; 
		}else{
			$SQL = "SELECT B.ID, IFNULL(B.ID_PARENT,0) AS ID_PARENT, B.JUDUL_MENU, B.URL, B.URUTAN, B.TIPE, B.TARGET, 
					B.ACTION, B.CLS_ICON AS ICON
					FROM app_user_menu A 
					INNER JOIN app_menu B ON A.KD_MENU = B.ID
					WHERE A.KD_USER = ".$ci->db->escape($KD_USER)."
					ORDER BY B.ID_PARENT, B.URUTAN ASC"; 	
		}
		$result = $ci->db->query($SQL);
		if($result->num_rows() > 0){
			foreach($result->result_array() as $row){
				if($row['ID_PARENT'] == "")
					$parent_id = 0;
				else
					$parent_id = $row['ID_PARENT'];
				$data[$parent_id][] = array("ID" => $row['ID'],
											"ID_PARENT"	 => $row['ID_PARENT'],
											"JUDUL_MENU" => $row['JUDUL_MENU'],
											"URL"	 	 => $row['URL'],
											"URUTAN" 	 => $row['URUTAN'],	
											"TIPE"	 	 => $row['TIPE'],
											"TARGET"	 => $row['TARGET'],
											"ACTION"	 => $row['ACTION'],
											"ICON"	 	 => $row['ICON']
											);	
			}
			$content .= get_menu($data,$segs,$parent=0);
		}else{
			$content = "";
		}
		return $content;
	}
}

if(!function_exists('get_menu')){
	function get_menu($data=array(),$segs,&$parent){
		$ci =& get_instance();
		$ci->load->database();
		static $i = 1;
		$tab = str_repeat("\t\t", $i);
		if ($data[$parent]){
			$html = '';
			if ($parent==0){
				$html = "\n$tab<ul class=\"site-menu\"><li class='site-menu-category'>MENU</li>";
			}else{
				$html = "\n$tab<ul class=\"site-menu-sub\">";
			}
			$i++;
			for ($c = 0; $c < count($data[$parent]); $c++) {
				$child = get_menu($data, $segs, $data[$parent][$c]['ID']);
				if ($data[$parent][$c]["TIPE"] == "F"){
					$href = 'javascript:void(0)';
					$class = 'site-menu-item has-sub';
					$arrow = "<span class='site-menu-arrow'></span>";
					$ID_PARENT = array();
					$ID_PARENT = get_active('PARENT',$segs);
					if(in_array($data[$parent][$c]["ID"], $ID_PARENT)){
						$active = 'active open';
					}else{
						$active = '';
					}
				}else{
					$href = site_url($data[$parent][$c]["URL"]);
					$class = 'site-menu-item';
					$arrow = "";
					$ID = get_active('ID',$segs);
					if($ID == $data[$parent][$c]["ID"]){
						$active = 'active';
					}else{
						$active = '';
					}
				}
				$data[$parent][$c]["ICON"] = $data[$parent][$c]["ICON"] == "" ? "":$data[$parent][$c]["ICON"];
				$html .= '<li class="'.$class.' '.$active.'">
							<a href="'.$href.'" class="animsition-link">
								<i class="site-menu-icon '.$data[$parent][$c]["ICON"].'" aria-hidden="true"></i>
								<span class="site-menu-title">'.$data[$parent][$c]["JUDUL_MENU"].'</span>
								'.$arrow.'
							</a>';
	
				if ($child){
					$i--;
					$html .= $child;
					$html .= "\n\t$tab";
				}
				$html .= '</li>';
			}
			$html .= "\n$tab</ul>";
			return $html;
		} else {
			$html = "";
			return false;
		}
	}
}

if(!function_exists('get_active')){
	function get_active($type,$segs){
		$ci =& get_instance();
		$ci->load->database();
		$arrdata = array();
		switch($type){
			case 'ID'	  :
				$SQL = "SELECT IFNULL(ID,0) AS ID 
						FROM app_menu WHERE UPPER(URL) = ".$ci->db->escape($segs);
				$result = $ci->db->query($SQL);
				$arrdata = $result->row()->ID;
			break;
			case 'PARENT' :
				$SQL = "SELECT IFNULL(ID,0) AS ID 
						FROM app_menu WHERE UPPER(URL) = ".$ci->db->escape($segs);
				$result = $ci->db->query($SQL);
				$ID = $result->row()->ID;
				if($ID!=0){
					$QUERY = "SELECT func_active('".$ID."') AS active";
					$exec = $ci->db->query($QUERY);
					$ID_PARENT = $exec->row()->active;
					if($ID_PARENT!=""){
						$arrdata = explode(',',$ID_PARENT);
					}
				}
			break;
		}
		return $arrdata;
	}
}
