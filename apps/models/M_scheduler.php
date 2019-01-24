<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_scheduler extends CI_Model {
	
	function set_coarricodeco($type) {
		$username = "TES";
		$password = "1234";
		$this->load->library('Nusoap');
		$client = new nusoap_client('https://tpsonline.beacukai.go.id/tps/service.asmx?wsdl',true);
		$error  = $client->getError();
		if($error) {
			echo '<h2>Constructor error</h2>'.$error;
			exit();
		}
		$queryHeader = "SELECT A.ID, A.KD_DOK, A.KD_TPS, A.NM_ANGKUT, A.NO_VOY_FLIGHT, A.CALL_SIGN, 
						DATE_FORMAT(A.TGL_TIBA,'%Y%m%d') AS TGL_TIBA, A.KD_GUDANG, A.NO_BC11, 
						DATE_FORMAT(A.TGL_BC11,'%Y%m%d') AS TGL_BC11, B.KD_KPBC, A.KD_TRADER
						FROM t_cocostshdr A
						INNER JOIN reff_tps B ON B.KD_TPS = A.KD_TPS
						WHERE ID IN (
							SELECT distinct ID 
							FROM t_cocostskms 
							WHERE FL_TRANSFER_".$type." = '100' AND WK_".$type." IS NOT NULL
						) limit 0,1";
		$result = $this->db->query($queryHeader);
		$arrayData = $result->result_array();
		$this->db->close();
		if(count($arrayData) > 0) {			
			$req_date = date('Y-m-d H:i:s');
			foreach($arrayData as $data) {
				$NO_URUT = $this->get_reff_number($data['KD_TRADER']);
				$ID = $data['ID'];
				$KD_DOK = $data['KD_DOK'];
				$REF_NUMBER = $data['KD_TPS'].date('ymd').str_pad($NO_URUT,6,0,STR_PAD_LEFT);

				if(strtoupper($type) == "OUT"){
					if($KD_DOK == "5") {
						$KD_DOK = "6";
					} elseif($KD_DOK == "7") {
						$KD_DOK = "8";
					}
				}
				$xml = '<?xml version="1.0" encoding="utf-8"?>';
				$xml .= '<DOCUMENT xmlns="cocokms.xsd">';
				$xml .= '<COCOKMS>';
					$xml .= '<HEADER>';
						$xml .= '<KD_DOK>'.$KD_DOK.'</KD_DOK>';
						$xml .= '<KD_TPS>'.$data['KD_TPS'].'</KD_TPS>';
						$xml .= '<NM_ANGKUT>'.$data['NM_ANGKUT'].'</NM_ANGKUT>';
						$xml .= '<NO_VOY_FLIGHT>'.$data['NO_VOY_FLIGHT'].'</NO_VOY_FLIGHT>';
						$xml .= '<CALL_SIGN>'.$data['CALL_SIGN'].'</CALL_SIGN>';
						$xml .= '<TGL_TIBA>'.$data['TGL_TIBA'].'</TGL_TIBA>';
						$xml .= '<KD_GUDANG>'.$data['KD_GUDANG'].'</KD_GUDANG>';
						$xml .= '<REF_NUMBER>'.$REF_NUMBER.'</REF_NUMBER>';
					$xml .= '</HEADER>';
					$xml .= '<DETIL>';
				if(strtoupper($type) == "IN") {
					$queryDetil = "SELECT A.ID, A.KD_KEMASAN, A.SERI, A.JUMLAH, A.NO_CONT_ASAL, A.NO_BL_AWB, A.NO_DAFTAR_PABEAN,
								   A.NO_MASTER_BL_AWB, B.NPWP, B.NAMA, A.BRUTO, A.NO_POS_BC11, A.KD_TIMBUN, A.NO_SEGEL_BC, 
								   A.KD_PEL_MUAT, A.KD_PEL_TRANSIT, A.KD_PEL_BONGKAR, A.KD_GUDANG_TUJUAN, A.NO_IJIN_TPS, 
								   A.KD_DOK_IN AS KD_DOK_INOUT, A.NO_DOK_IN AS NO_DOK_INOUT, 
								   A.KD_SARANA_ANGKUT_IN AS KD_SARANA_ANGKUT_INOUT, A.NO_POL_IN AS NO_POL_INOUT, 
								   DATE_FORMAT(A.TGL_IJIN_TPS ,'%Y%m%d') AS TGL_IJIN_TPS,
								   DATE_FORMAT(A.TGL_BL_AWB,'%Y%m%d') AS TGL_BL_AWB,
								   DATE_FORMAT(A.TGL_MASTER_BL_AWB,'%Y%m%d') AS TGL_MASTER_BL_AWB, 
								   DATE_FORMAT(A.TGL_DOK_IN,'%Y%m%d') AS TGL_DOK_INOUT, 
								   DATE_FORMAT(A.WK_IN,'%Y%m%d%h%i%s') AS WK_INOUT, 
								   DATE_FORMAT(A.TGL_SEGEL_BC,'%Y%m%d') AS TGL_SEGEL_BC, 
								   DATE_FORMAT(A.TGL_DAFTAR_PABEAN,'%Y%m%d') AS TGL_DAFTAR_PABEAN
								   FROM t_cocostskms A 
								   INNER JOIN t_organisasi B ON B.ID = A.KD_ORG_CONSIGNEE 
								   WHERE A.FL_TRANSFER_IN = '100' AND A.WK_IN IS NOT NULL 
								   AND A.ID = ".$ID."  
								   LIMIT 0,10";
				} elseif(strtoupper($type) == "OUT") {
					$queryDetil = "SELECT A.ID, A.KD_KEMASAN, A.SERI, A.JUMLAH, A.NO_CONT_ASAL, A.NO_BL_AWB, 
								   A.NO_MASTER_BL_AWB, B.NPWP, B.NAMA, A.BRUTO, A.NO_POS_BC11, A.KD_TIMBUN, 
								   A.NO_SEGEL AS NO_SEGEL_BC, A.KD_PEL_MUAT, A.KD_PEL_TRANSIT, A.KD_PEL_BONGKAR, 
								   A.KD_GUDANG_TUJUAN, A.NO_IJIN_TPS, A.KD_DOK_OUT AS KD_DOK_INOUT, 
								   A.NO_DOK_OUT AS NO_DOK_INOUT, A.KD_SARANA_ANGKUT_OUT AS KD_SARANA_ANGKUT_INOUT, 
								   A.NO_POL_OUT AS NO_POL_INOUT, A.NO_DAFTAR_PABEAN, 
								   DATE_FORMAT(A.TGL_IJIN_TPS ,'%Y%m%d') AS TGL_IJIN_TPS,
								   DATE_FORMAT(A.TGL_BL_AWB,'%Y%m%d') AS TGL_BL_AWB,
								   DATE_FORMAT(A.TGL_MASTER_BL_AWB,'%Y%m%d') AS TGL_MASTER_BL_AWB, 
								   DATE_FORMAT(A.TGL_DOK_OUT,'%Y%m%d') AS TGL_DOK_INOUT, 
								   DATE_FORMAT(A.WK_OUT,'%Y%m%d%h%i%s') AS WK_INOUT, 
								   DATE_FORMAT(A.TGL_SEGEL,'%Y%m%d') AS TGL_SEGEL_BC, 
								   DATE_FORMAT(A.TGL_DAFTAR_PABEAN,'%Y%m%d') AS TGL_DAFTAR_PABEAN
								   FROM t_cocostskms A 
								   INNER JOIN t_organisasi B ON B.ID = A.KD_ORG_CONSIGNEE 
								   WHERE A.FL_TRANSFER_OUT = '100' AND A.WK_OUT IS NOT NULL 
								   AND A.ID = ".$ID."  
								   LIMIT 0,10";
				}
				$arrDtl = $this->db->query($queryDetil)->result_array();
				if(count($arrDtl) > 0) {
					$seris = array();
					foreach ($arrDtl as $dtl) {
						$seris[] = $dtl['SERI'];
							$xml .= '<KMS>';
								$xml .= '<NO_BL_AWB>'.$dtl['NO_BL_AWB'].'</NO_BL_AWB>';
								$xml .= '<TGL_BL_AWB>'.$dtl['TGL_BL_AWB'].'</TGL_BL_AWB>';
								$xml .= '<NO_MASTER_BL_AWB>'.$dtl['NO_MASTER_BL_AWB'].'</NO_MASTER_BL_AWB>';
								$xml .= '<TGL_MASTER_BL_AWB>'.$dtl['TGL_MASTER_BL_AWB'].'</TGL_MASTER_BL_AWB>';
								$xml .= '<ID_CONSIGNEE>'.$dtl['NPWP'].'</ID_CONSIGNEE>';
								$xml .= '<CONSIGNEE>'.$dtl['NAMA'].'</CONSIGNEE>';
								$xml .= '<BRUTO>'.$dtl['BRUTO'].'</BRUTO>';
								$xml .= '<NO_BC11>'.$data['NO_BC11'].'</NO_BC11>';
								$xml .= '<TGL_BC11>'.$data['TGL_BC11'].'</TGL_BC11>';
								$xml .= '<NO_POS_BC11>'.$dtl['NO_POS_BC11'].'</NO_POS_BC11>';
								$xml .= '<CONT_ASAL>'.$dtl['NO_CONT_ASAL'].'</CONT_ASAL>';
								$xml .= '<SERI_KEMAS>'.$dtl['SERI'].'</SERI_KEMAS>';
								$xml .= '<KD_KEMAS>'.$dtl['KD_KEMASAN'].'</KD_KEMAS>';
								$xml .= '<JML_KEMAS>'.$dtl['JUMLAH'].'</JML_KEMAS>';
								$xml .= '<KD_TIMBUN>'.$dtl['KD_TIMBUN'].'</KD_TIMBUN>';
								$xml .= '<KD_DOK_INOUT>'.$dtl['KD_DOK_INOUT'].'</KD_DOK_INOUT>';
								$xml .= '<NO_DOK_INOUT>'.$dtl['NO_DOK_INOUT'].'</NO_DOK_INOUT>';
								$xml .= '<TGL_DOK_INOUT>'.$dtl['TGL_DOK_INOUT'].'</TGL_DOK_INOUT>';
								$xml .= '<WK_INOUT>'.$dtl['WK_INOUT'].'</WK_INOUT>';
								$xml .= '<KD_SAR_ANGKUT_INOUT>'.$dtl['KD_SARANA_ANGKUT_INOUT'].'</KD_SAR_ANGKUT_INOUT>';
								$xml .= '<PEL_MUAT>'.$dtl['KD_PEL_MUAT'].'</PEL_MUAT>';
								$xml .= '<PEL_TRANSIT>'.$dtl['KD_PEL_TRANSIT'].'</PEL_TRANSIT>';
								$xml .= '<PEL_BONGKAR>'.$dtl['KD_PEL_BONGKAR'].'</PEL_BONGKAR>';
								$xml .= '<GUDANG_TUJUAN>'.$dtl['KD_GUDANG_TUJUAN'].'</GUDANG_TUJUAN>';
								$xml .= '<KODE_KANTOR>'.$data['KD_KPBC'].'</KODE_KANTOR>';
								$xml .= '<NO_DAFTAR_PABEAN>'.$dtl['NO_DAFTAR_PABEAN'].'</NO_DAFTAR_PABEAN>';
								$xml .= '<TGL_DAFTAR_PABEAN>'.$dtl['TGL_DAFTAR_PABEAN'].'</TGL_DAFTAR_PABEAN>';
								$xml .= '<NO_SEGEL_BC>'.$dtl['NO_SEGEL_BC'].'</NO_SEGEL_BC>';
								$xml .= '<TGL_SEGEL_BC>'.$dtl['TGL_SEGEL_BC'].'</TGL_SEGEL_BC>';
								$xml .= '<NO_IJIN_TPS>'.$dtl['NO_IJIN_TPS'].'</NO_IJIN_TPS>';
								$xml .= '<TGL_IJIN_TPS>'.$dtl['TGL_IJIN_TPS'].'</TGL_IJIN_TPS>';
							$xml .= '</KMS>';
					}
						$xml .= '</DETIL>';
				}
				$xml .= '</COCOKMS>';
				$xml .= '</DOCUMENT>';
				$method = "CoCoKms_Tes";
				$param  = array('Username'=>$username, 'Password'=>$password, 'fStream'=>$xml);
				$response = $client->call($method,$param);
				if($response!=""){
					$return = $response[$method.'Result'];
					$pos = strpos(strtolower($return), 'berhasil');
					if($pos !== false){
						$arrpostbox['KD_STATUS'] = '200';
						$HEADER['FL_TRANSFER_'.strtoupper($type)] = "200";
					}else{
						$arrpostbox['KD_STATUS'] = '300';
					}	
				}else{
					$arrpostbox['KD_STATUS'] = '300';
				}

				if($KD_DOK == "5") {
					$arrpostbox['KD_APRF'] = "SENTGATEINIMPBC";
				} else if($KD_DOK == "6") {
					$arrpostbox['KD_APRF'] = "SENTGATEOUTIMPBC";
				} else if($KD_DOK == "7") {
					$arrpostbox['KD_APRF'] = "SENTGATEINEXPBC";
				} else if($KD_DOK == "8") {
					$arrpostbox['KD_APRF'] = "SENTGATEOUTEXPBC";
				}
				$arrpostbox['STR_DATA'] = $xml;
				$arrpostbox['TGL_STATUS'] = date('Y-m-d H:i:s');
				$arrpostbox['KETERANGAN'] = $return;
				#insert ke postbox for history
				$this->db->insert('postbox',$arrpostbox);

				#update reff_number 
				for ($i=0; $i < count($seris); $i++) {
					$HEADER['REF_NUMBER_'.strtoupper($type)] = $REF_NUMBER;
					$this->db->where(array('ID' => $ID, 'SERI'=>$seris[$i]));
					$this->db->update('t_cocostskms',$HEADER);
				}

				#update ref_number master
				$this->db->set('REF_NUMBER', 'REF_NUMBER + 1', FALSE);
				$this->db->where(array('ID'=>$data['KD_TRADER']));
				$this->db->update('t_organisasi');
			}
		}
	}
	
	function get_permit($type) {
		$username = "DHAR";
		$password = "TANGKI";
		$kd_tps = "DHAR";
		$kd_gudang = "DHAR";
		$this->load->library('Nusoap');
		$client = new nusoap_client('https://tpsonline.beacukai.go.id/tps/service.asmx?wsdl',true);
		$error  = $client->getError();
		if($error){
			echo '<h2>Constructor error</h2>'.$error;
			exit();
		}
		if($type == "getimporpermit_fasp") {
			$method = "GetImporPermit_FASP";  
			$param  = array('UserName'=>$username, 'Password'=>$password, 'Kd_ASP'=>$kd_tps);
			$response = $client->call($method,$param);
			if(count($response) > 0){
				$resData = $response[$method.'Result'];
				$this->db->insert('t_mailbox',array('KD_APRF' => 'GETIMPORPERMIT', 'DOKUMEN' => 'SPPB', 'STR_DATA' => $resData, 'STATUS' => 'UNREAD', 'TGL_STATUS' => date('Y-m-d H:i:s')));
				echo $resData;
			}
		}else if($type == "getimporpermit"){
			$method = "GetImporPermit";  
			$param  = array('UserName'=>$username, 'Password'=>$password, 'Kd_Gudang'=>$kd_gudang);
			$response = $client->call($method,$param);
			if(count($response) > 0){
				$resData = $response[$method.'Result'];
				$this->db->insert('t_mailbox',array('KD_APRF' => 'GETIMPORPERMIT', 'DOKUMEN' => 'SPPB', 'STR_DATA' => $resData, 'STATUS' => 'UNREAD', 'TGL_STATUS' => date('Y-m-d H:i:s')));
				echo $resData;
			}
		}else if($type == "getbc23permit_fasp"){
			$method = "GetBC23Permit_FASP"; 
			$param  = array('UserName'=>$username, 'Password'=>$password, 'Kd_ASP'=>$kd_tps);
			$response = $client->call($method,$param);
			if(count($response) > 0){
				$resData = $response[$method.'Result'];
				$this->db->insert('t_mailbox',array('KD_APRF' => 'GETIMPORPERMIT', 'DOKUMEN' => 'BC23', 'STR_DATA' => $resData, 'STATUS' => 'UNREAD', 'TGL_STATUS' => date('Y-m-d H:i:s')));
				echo $resData;
			}
		}else if($type == "getbc23permit"){
			$method = "GetBC23Permit";  
			$param  = array('UserName'=>$username, 'Password'=>$password, 'Kd_Gudang'=>$kd_gudang);
			$response = $client->call($method,$param);
			if(count($response) > 0){
				$resData = $response[$method.'Result'];
				$this->db->insert('t_mailbox',array('KD_APRF' => 'GETIMPORPERMIT', 'DOKUMEN' => 'BC23', 'STR_DATA' => $resData, 'STATUS' => 'UNREAD', 'TGL_STATUS' => date('Y-m-d H:i:s')));
				echo $resData;
			}
		}else if($type == "getimpor_sppb"){
			$query = "SELECT ID, KD_TPS, KD_GUDANG, KD_DOK_INOUT, NO_DOK_INOUT, TGL_DOK_INOUT, NPWP_CONSIGNEE
					  FROM t_request_custimp_hdr 
					  WHERE KD_STATUS = '200'
					  LIMIT 1";
			$exec = $this->db->query($query)->result_array();
			if(count($exec) > 0) {
				foreach ($exec as $val) {
					if($val['KD_DOK_INOUT'] == '1') {
						$this->db->where(array("ID"=>$val['ID']));
						$this->db->update("t_request_custimp_hdr",array("KD_STATUS"=>"300"));
						$method = "GetImpor_Sppb";  
						$param  = array('UserName'=>$username, 'Password'=>$password, 'No_Sppb'=>$val['NO_DOK_INOUT'], 'Tgl_Sppb'=>date_format(date_create($val['TGL_DOK_INOUT']), 'dmY'), "NPWP_Imp"=>str_replace(array("-","."), array("",""), $val['NPWP_CONSIGNEE']));
						$response = $client->call($method,$param);
						if(count($response) > 0){
							$resData = $response[$method.'Result'];
							$this->db->insert('mailbox',array('KD_APRF' => 'GETIMPPERMIT', 'DOKUMEN' => 'SPPB', 'STR_DATA' => $resData, 'STATUS' => 'UNREAD', 'TGL_STATUS' => date('Y-m-d H:i:s')));
						}
					}
				}
			}
		}else{
			echo "Check Method";
			exit();
		}
	}
	
	function read_permit($type="") {
		if($type=="sppb"){
			$SQL = "SELECT A.ID, A.STR_DATA
					FROM t_mailbox A
					WHERE A.STATUS = 'UNREAD'
					AND KD_APRF = 'GETIMPPERMIT'
					AND DOKUMEN = 'SPPB'
					LIMIT 35";
			#echo $SQL; die();
			$result = $this->db->query($SQL);
			$arrayData = $result->result_array();
			if(count($arrayData) > 0){
				foreach($arrayData as $row => $value){
					$str_xml = strtolower($value['STR_DATA']);
					$str_xml = str_replace('<?xml version="1.0"?>','',$str_xml);
					$res     = simplexml_load_string($str_xml);
					$json    = json_encode($res);
					$arrxml  = json_decode($json,TRUE);
					$date_now	= date('Y-m-d H:i:s');
					$arrheader  = $arrxml['sppb'];
					$arrdetail  = $arrxml['sppb'];
					if(array_key_exists('sppb', $arrxml)){
						if(array_key_exists(0, $arrheader)){
							for($a=0; $a<count($arrheader); $a++){
								$header = array();
								$header[$a]['CAR'] = escape($arrheader[$a]['header']['car']);
								$header[$a]['KD_DOK_INOUT'] = '1';
								$header[$a]['NO_DOK_INOUT'] = escape($arrheader[$a]['header']['no_sppb']);
								$header[$a]['TGL_DOK_INOUT'] = escape($arrheader[$a]['header']['tgl_sppb'],'DATESLASHS');
								$header[$a]['KD_KANTOR'] = escape($arrheader[$a]['header']['kd_kpbc']);
								$header[$a]['NO_DAFTAR_PABEAN'] = escape($arrheader[$a]['header']['no_pib']);
								$header[$a]['TGL_DAFTAR_PABEAN'] = escape($arrheader[$a]['header']['tgl_pib'],'DATESLASHS');
								$header[$a]['ID_CONSIGNEE'] = escape($arrheader[$a]['header']['npwp_imp']);
								$header[$a]['CONSIGNEE'] = escape($arrheader[$a]['header']['nama_imp']);
								$header[$a]['ALAMAT_CONSIGNEE'] = escape($arrheader[$a]['header']['alamat_imp']);
								$header[$a]['NPWP_PPJK'] = escape($arrheader[$a]['header']['npwp_ppjk']);
								$header[$a]['NAMA_PPJK'] = escape($arrheader[$a]['header']['nama_ppjk']);
								$header[$a]['ALAMAT_PPJK'] = escape($arrheader[$a]['header']['alamat_ppjk']);
								$header[$a]['NM_ANGKUT'] = escape($arrheader[$a]['header']['nm_angkut']);
								$header[$a]['NO_VOY_FLIGHT'] = escape($arrheader[$a]['header']['no_voy_flight']);
								$header[$a]['BRUTO'] = escape($arrheader[$a]['header']['bruto']);
								$header[$a]['NETTO'] = escape($arrheader[$a]['header']['netto']);
								$header[$a]['KD_GUDANG'] = escape($arrheader[$a]['header']['gudang']);
								$header[$a]['STATUS_JALUR'] = escape($arrheader[$a]['header']['status_jalur']);
								$header[$a]['JML_CONT'] = escape($arrheader[$a]['header']['jml_cont']);
								$header[$a]['NO_BC11'] = escape($arrheader[$a]['header']['no_bc11']);
								$header[$a]['TGL_BC11'] = escape($arrheader[$a]['header']['tgl_bc11'],'DATESLASHS');
								$header[$a]['NO_POS_BC11'] = escape($arrheader[$a]['header']['no_pos_bc11']);
								$header[$a]['NO_BL_AWB'] = escape($arrheader[$a]['header']['no_bl_awb']);
								$header[$a]['TGL_BL_AWB'] = escape($arrheader[$a]['header']['tg_bl_awb'],'DATESLASHS');
								$header[$a]['NO_MASTER_BL_AWB'] = escape($arrheader[$a]['header']['no_master_bl_awb']);
								$header[$a]['TGL_MASTER_BL_AWB'] = escape($arrheader[$a]['header']['tg_master_bl_awb'],'DATESLASHS');

								$SQL = "SELECT ID FROM t_permit_hdr A 
										WHERE TRIM(A.CAR) = ".$this->db->escape(escape($arrheader[$a]['header']['car']))."
										AND TRIM(A.NO_DOK_INOUT) = ".$this->db->escape(escape($arrheader[$a]['header']['no_sppb']))."
										ORDER BY A.ID DESC
										LIMIT 0, 1";
								$result = $this->db->query($SQL);
								if($result->num_rows() > 0){
									$ID = $result->row()->ID;
									$header[$a]['TGL_STATUS'] = $date_now;
									$this->db->where(array('ID' => $ID));
									$this->db->update('t_permit_hdr', $header[$a]);
								}else{
									$header[$a]['TGL_STATUS'] = $date_now;
									$this->db->insert('t_permit_hdr', $header[$a]);
									$ID = $this->db->insert_id();
								}
								#echo $ID; die();
								if($ID != 0){
									if(array_key_exists('detil', $arrdetail[$a])){
										$this->db->delete('t_permit_cont', array('ID' => $ID));
										$arrdetailcont = $arrdetail[$a]['detil']['cont'];
										#print_r($arrdetailcont); die();
										if(array_key_exists(0, $arrdetailcont)){
											for($i=0; $i<count($arrdetailcont); $i++){
												$detailcont['ID'] = $ID;
												$detailcont['CAR'] = escape($arrdetailcont[$i]['car']);
												$detailcont['NO_CONT'] = escape($arrdetailcont[$i]['no_cont']);
												$detailcont['SERI_CONT'] = ($i+1);
												$detailcont['KD_CONT_UKURAN'] = escape($arrdetailcont[$i]['size']);
												$detailcont['KD_CONT_JENIS'] = escape($arrdetailcont[$i]['jns_muat']);
												$detailcont['WK_STATUS'] = $date_now;
												$this->db->insert('t_permit_cont', $detailcont);
											}
										}else{
											$detailcont['ID'] = $ID;
											$detailcont['CAR'] = escape($arrdetailcont['car']);
											$detailcont['NO_CONT'] = escape($arrdetailcont['no_cont']);
											$detailcont['SERI_CONT'] = ($i+1);
											$detailcont['KD_CONT_UKURAN'] = escape($arrdetailcont['size']);
											$detailcont['KD_CONT_JENIS'] = escape($arrdetailcont['jns_muat']);
											$detailcont['WK_STATUS'] = $date_now;
											$this->db->insert('t_permit_cont', $detailcont);
										}
									
										$this->db->delete('t_permit_kms', array('ID' => $ID));
										$arrdetailkms = $arrdetail[$a]['detil']['kms'];
										#print_r($arrdetailkms); die();
										if(array_key_exists(0, $arrdetailkms)){
											for($j=0; $j<count($arrdetailkms); $j++){
												$detailkms['ID'] = $ID;
												$detailkms['CAR'] = escape($arrdetailkms[$j]['car']);
												$detailkms['JNS_KMS'] = escape($arrdetailkms[$j]['jns_kms']);
												$detailkms['MERK_KMS'] = escape($arrdetailkms[$j]['merk_kms']);
												$detailkms['JML_KMS'] = escape($arrdetailkms[$j]['jml_kms']);
												$detailkms['WK_STATUS'] = $date_now;
												$this->db->insert('t_permit_kms', $detailkms);
											}
										}else{
											$detailkms['ID'] = $ID;
											$detailkms['CAR'] = escape($arrdetailkms['car']);
											$detailkms['JNS_KMS'] = escape($arrdetailkms['jns_kms']);
											$detailkms['MERK_KMS'] = escape($arrdetailkms['merk_kms']);
											$detailkms['JML_KMS'] = escape($arrdetailkms['jml_kms']);
											$detailkms['WK_STATUS'] = $date_now;
											$this->db->insert('t_permit_kms', $detailkms);
										}
									}
									$this->db->where(array('ID' => $value['ID']));
									$this->db->update('t_mailbox', array('STATUS' => 'READ', 'TGL_STATUS' => $date_now));
									echo "Success\n";
								}
							}
						}else{
							$header = array();
							$header['CAR'] = escape($arrheader['header']['car']);
							$header['KD_DOK_INOUT'] = '1';
							$header['NO_DOK_INOUT'] = escape($arrheader['header']['no_sppb']);
							$header['TGL_DOK_INOUT'] = escape($arrheader['header']['tgl_sppb'],'DATESLASHS');
							$header['KD_KANTOR'] = escape($arrheader['header']['kd_kpbc']);
							$header['NO_DAFTAR_PABEAN'] = escape($arrheader['header']['no_pib']);
							$header['TGL_DAFTAR_PABEAN'] = escape($arrheader['header']['tgl_pib'],'DATESLASHS');
							$header['ID_CONSIGNEE'] = escape($arrheader['header']['npwp_imp']);
							$header['CONSIGNEE'] = escape($arrheader['header']['nama_imp']);
							$header['ALAMAT_CONSIGNEE'] = escape($arrheader['header']['alamat_imp']);
							$header['NPWP_PPJK'] = escape($arrheader['header']['npwp_ppjk']);
							$header['NAMA_PPJK'] = escape($arrheader['header']['nama_ppjk']);
							$header['ALAMAT_PPJK'] = escape($arrheader['header']['alamat_ppjk']);
							$header['NM_ANGKUT'] = escape($arrheader['header']['nm_angkut']);
							$header['NO_VOY_FLIGHT'] = escape($arrheader['header']['no_voy_flight']);
							$header['BRUTO'] = escape($arrheader['header']['bruto']);
							$header['NETTO'] = escape($arrheader['header']['netto']);
							$header['KD_GUDANG'] = escape($arrheader['header']['gudang']);
							$header['STATUS_JALUR'] = escape($arrheader['header']['status_jalur']);
							$header['JML_CONT'] = escape($arrheader['header']['jml_cont']);
							$header['NO_BC11'] = escape($arrheader['header']['no_bc11']);
							$header['TGL_BC11'] = escape($arrheader['header']['tgl_bc11'],'DATESLASHS');
							$header['NO_POS_BC11'] = escape($arrheader['header']['no_pos_bc11']);
							$header['NO_BL_AWB'] = escape($arrheader['header']['no_bl_awb']);
							$header['TGL_BL_AWB'] = escape($arrheader['header']['tg_bl_awb'],'DATESLASHS');
							$header['NO_MASTER_BL_AWB'] = escape($arrheader['header']['no_master_bl_awb']);
							$header['TGL_MASTER_BL_AWB'] = escape($arrheader['header']['tg_master_bl_awb']);
							$SQL = "SELECT ID FROM t_permit_hdr A 
									WHERE TRIM(A.CAR) = ".$this->db->escape(escape($arrheader['header']['car']))."
									AND TRIM(A.NO_DOK_INOUT) = ".$this->db->escape(escape($arrheader['header']['no_sppb']))."
									ORDER BY A.ID DESC
									LIMIT 0, 1";
							$result = $this->db->query($SQL);
							if($result->num_rows() > 0){
								$ID = $result->row()->ID;
								$header['TGL_STATUS'] = $date_now;
								$this->db->where(array('ID' => $ID));
								$this->db->update('t_permit_hdr', $header);
							}else{
								$header['TGL_STATUS'] = $date_now;
								$this->db->insert('t_permit_hdr', $header);
								$ID = $this->db->insert_id();
							}
							#echo $ID; die();
							if($ID != 0){
								if(array_key_exists('detil', $arrdetail)){
									$this->db->delete('t_permit_cont', array('ID' => $ID));
									$arrdetailcont = $arrdetail['detil']['cont'];
									if(array_key_exists(0, $arrdetailcont)){
										for($i=0; $i<count($arrdetailcont); $i++){
											$detailcont['ID'] = $ID;
											$detailcont['CAR'] = escape($arrdetailcont[$i]['car']);
											$detailcont['NO_CONT'] = escape($arrdetailcont[$i]['no_cont']);
											$detailcont['SERI_CONT'] = ($i+1);
											$detailcont['KD_CONT_UKURAN'] = escape($arrdetailcont[$i]['size']);
											$detailcont['KD_CONT_JENIS'] = escape($arrdetailcont[$i]['jns_muat']);
											$detailcont['WK_STATUS'] = $date_now;
											$this->db->insert('t_permit_cont', $detailcont);
										}
									}else{
										$detailcont['ID'] = $ID;
										$detailcont['CAR'] = escape($arrdetailcont['car']);
										$detailcont['NO_CONT'] = escape($arrdetailcont['no_cont']);
										$detailcont['SERI_CONT'] = ($i+1);
										$detailcont['KD_CONT_UKURAN'] = escape($arrdetailcont['size']);
										$detailcont['KD_CONT_JENIS'] = escape($arrdetailcont['jns_muat']);
										$detailcont['WK_STATUS'] = $date_now;
										$this->db->insert('t_permit_cont', $detailcont);
									}
									$this->db->delete('t_permit_kms', array('ID' => $ID));
									$arrdetailkms = $arrdetail['detil']['kms'];
									if(array_key_exists(0, $arrdetailkms)){
										for($j=0; $j<count($arrdetailkms); $j++){
											$detailkms['ID'] = $ID;
											$detailkms['CAR'] = escape($arrdetailkms[$j]['car']);
											$detailkms['JNS_KMS'] = escape($arrdetailkms[$j]['jns_kms']);
											$detailkms['MERK_KMS'] = escape($arrdetailkms[$j]['merk_kms']);
											$detailkms['JML_KMS'] = escape($arrdetailkms[$j]['jml_kms']);
											$detailkms['WK_STATUS'] = $date_now;
											$this->db->insert('t_permit_kms', $detailkms);
										}
									}else{
										$detailkms['ID'] = $ID;
										$detailkms['CAR'] = escape($arrdetailkms['jns_dok']);
										$detailkms['JNS_KMS'] = escape($arrdetailkms['jns_kms']);
										$detailkms['MERK_KMS'] = escape($arrdetailkms['merk_kms']);
										$detailkms['JML_KMS'] = escape($arrdetailkms['jml_kms']);
										$detailkms['WK_STATUS'] = $date_now; 
										$this->db->insert('t_permit_kms', $detailkms);
									}
								}
								$this->db->where(array('ID' => $value['ID']));
								$this->db->update('t_mailbox', array('STATUS' => 'READ', 'TGL_STATUS' => $date_now));
								echo "Success\n";
							}
						}
					}else{
						$this->db->where(array('ID' => $value['ID']));
						$this->db->update('t_mailbox', array('STATUS' => 'READ', 'TGL_STATUS' => $date_now));
						echo "Success\n";
					}
				}
			}else{
				echo "No records found";
			}
			$this->db->close();
		}
	}

	function get_reff_number($id) {
		$query = "SELECT REF_NUMBER FROM t_organisasi WHERE ID = ".$id;
		$result = $this->db->query($query);
		if($result->num_rows() > 0) {
			$DATA = $result->row();
			$REF_NUMBER = $DATA->REF_NUMBER;
		}
		return $REF_NUMBER + 1;
	}
}
?>