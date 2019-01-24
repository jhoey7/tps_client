<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('update_dwh_helper'))
{
	function update_dwh_helper($id, $tipe)
	{
		$CI =& get_instance();
		$tgl_sekarang = date("Y-m-d H:i:s");
		if ($tipe == "1")
		{
			$sql1 = "insert into binus_dwh.last_data(tipe_ijin,id_penerbit,no_ijin,tgl_ijin,tgl_aksi) select 1,id_penerbit,no_ijin,tgl_ijin,'".$tgl_sekarang."' from tdp.tblfcpermohonan where id=".$id;
			$sql2 = "insert into binus_dwh.per_bentuk(tipe_ijin,id_bentuk,tgl_ijin) select 1,bentuk_usaha,tgl_ijin from tdp.tblfcpermohonan where id=".$id;
			$sql3 = "insert into binus_dwh.per_dinas(tipe_ijin,id_penerbit,tgl_ijin) select 1,id_penerbit,tgl_ijin from tdp.tblfcpermohonan where id=".$id;
			$sql4 = "insert into binus_dwh.per_prop(tipe_ijin,idprop,tgl_ijin) select 1,idprop,tgl_ijin from tdp.tblfcpermohonan where id=".$id;
			$sql5 = "insert into binus_dwh.per_kab(tipe_ijin,idkab,tgl_ijin) select 1,idkab,tgl_ijin from tdp.tblfcpermohonan where id=".$id;
			$sql6 = "insert into binus_dwh.per_kec(tipe_ijin,idkec,tgl_ijin) select 1,idkec,tgl_ijin from tdp.tblfcpermohonan where id=".$id;
			$sql7 = "insert into binus_dwh.per_kel(tipe_ijin,idkel,tgl_ijin) select 1,idkel,tgl_ijin from tdp.tblfcpermohonan where id=".$id;
			$sql8 = "insert into binus_dwh.per_kbli(tipe_ijin,id_header,kbli,tgl_ijin) 
				 select 1,a.id,b.kbli,a.tgl_ijin 
				 from tdp.tblfcpermohonan a 
				 left join tdp.tblfckegiatan b on b.id_tdp=a.id 
				 where a.id=".$id;
			$sql9 = "insert into binus_dwh.tipe_input(tipe_ijin,tipe_input,tgl_ijin) select 1,asal_data,tgl_ijin from tdp.tblfcpermohonan where id=".$id;
			$CI->db->query($sql1);
			$CI->db->query($sql2);
			$CI->db->query($sql3);
			$CI->db->query($sql4);
			$CI->db->query($sql5);
			$CI->db->query($sql6);
			$CI->db->query($sql7);
			$CI->db->query($sql8);
			$CI->db->query($sql9);
		}
		if ($tipe == "2")
		{
			$sql1 = "insert into binus_dwh.last_data(tipe_ijin,id_penerbit,no_ijin,tgl_ijin,tgl_aksi) select 2,id_penerbit,no_ijin,tgl_ijin,'".$tgl_sekarang."' from siup.tblfcpermohonan where id=".$id;
			$sql2 = "insert into binus_dwh.per_bentuk(tipe_ijin,id_bentuk,tgl_ijin) select 2,bentuk_usaha,tgl_ijin from siup.tblfcpermohonan where id=".$id;
			$sql3 = "insert into binus_dwh.per_prop(tipe_ijin,idprop,tgl_ijin) select 2,idprop,tgl_ijin from siup.tblfcpermohonan where id=".$id;
			$sql4 = "insert into binus_dwh.per_kab(tipe_ijin,idkab,tgl_ijin) select 2,idkab,tgl_ijin from siup.tblfcpermohonan where id=".$id;
			$sql5 = "insert into binus_dwh.per_kec(tipe_ijin,idkec,tgl_ijin) select 2,idkec,tgl_ijin from siup.tblfcpermohonan where id=".$id;
			$sql6 = "insert into binus_dwh.per_kel(tipe_ijin,idkel,tgl_ijin) select 2,idkel,tgl_ijin from siup.tblfcpermohonan where id=".$id;
			$sql7 = "insert into binus_dwh.tipe_input(tipe_ijin,tipe_input,tgl_ijin) select 2,asal_data,tgl_ijin from siup.tblfcpermohonan where id=".$id;
			$sql9 = "insert into binus_dwh.per_dinas(tipe_ijin,id_penerbit,tgl_ijin) select 2,id_penerbit,tgl_ijin from siup.tblfcpermohonan where id=".$id;
			$sql8 = "insert into binus_dwh.per_kbli(tipe_ijin,id_header,kbli,tgl_ijin) 
					 select 2,a.id,b.kbli,a.tgl_ijin 
					 from siup.tblfcpermohonan a 
					 left join siup.tblfcsiupkbli b on b.id_header=a.id 
					 where a.id=".$id;
			$CI->db->query($sql1);
			$CI->db->query($sql2);
			$CI->db->query($sql3);
			$CI->db->query($sql4);
			$CI->db->query($sql5);
			$CI->db->query($sql6);
			$CI->db->query($sql7);
			$CI->db->query($sql8);
			$CI->db->query($sql9);
		}
			
		if ($tipe == "3")
		{
			$sql1 = "insert into binus_dwh.last_data(tipe_ijin,id_penerbit,no_ijin,tgl_ijin,tgl_aksi) select 3,id_penerbit,no_ijin,tgl_ijin,'".$tgl_sekarang."' from stpw.tblfcpermohonan where id=".$id;
			$sql2 = "insert into binus_dwh.per_bentuk(tipe_ijin,id_bentuk,tgl_ijin) select 3,pr_bentuk,tgl_ijin from stpw.tblfcpermohonan where id=".$id;
			$sql3 = "insert into binus_dwh.per_dinas(tipe_ijin,id_penerbit,tgl_ijin) select 3,id_penerbit,tgl_ijin from stpw.tblfcpermohonan where id=".$id;
			$sql4 = "insert into binus_dwh.per_prop(tipe_ijin,idprop,tgl_ijin) select 3,pr_prop,tgl_ijin from stpw.tblfcpermohonan where id=".$id;
			$sql5 = "insert into binus_dwh.per_kab(tipe_ijin,idkab,tgl_ijin) select 3,pr_kab,tgl_ijin from stpw.tblfcpermohonan where id=".$id;
			$sql6 = "insert into binus_dwh.per_kec(tipe_ijin,idkec,tgl_ijin) select 3,pr_kec,tgl_ijin from stpw.tblfcpermohonan where id=".$id;
			$sql7 = "insert into binus_dwh.per_kel(tipe_ijin,idkel,tgl_ijin) select 3,pr_kel,tgl_ijin from stpw.tblfcpermohonan where id=".$id;
			$sql8 = "insert into binus_dwh.tipe_input(tipe_ijin,tipe_input,tgl_ijin) select 3,asal_data,tgl_ijin from stpw.tblfcpermohonan where id=".$id;
			$CI->db->query($sql1);
			$CI->db->query($sql2);
			$CI->db->query($sql3);
			$CI->db->query($sql4);
			$CI->db->query($sql5);
			$CI->db->query($sql6);
			$CI->db->query($sql7);
			$CI->db->query($sql8);
		}
		
		if ($tipe == "4")
		{
			$sql1 = "insert into binus_dwh.last_data(tipe_ijin,id_penerbit,no_ijin,tgl_ijin,tgl_aksi) select 4,id_penerbit,no_ijin,tgl_ijin,'".$tgl_sekarang."' from iutm.tblfcpermohonan where id=".$id;
			$sql2 = "insert into binus_dwh.per_bentuk(tipe_ijin,id_bentuk,tgl_ijin) select 4,bidang_usaha,tgl_ijin from iutm.tblfcpermohonan where id=".$id;
			$sql3 = "insert into binus_dwh.per_dinas(tipe_ijin,id_penerbit,tgl_ijin) select 4,id_penerbit,tgl_ijin from iutm.tblfcpermohonan where id=".$id;
			$sql4 = "insert into binus_dwh.per_prop(tipe_ijin,idprop,tgl_ijin) select 4,idprop,tgl_ijin from iutm.tblfcpermohonan where id=".$id;
			$sql5 = "insert into binus_dwh.per_kab(tipe_ijin,idkab,tgl_ijin) select 4,idkab,tgl_ijin from iutm.tblfcpermohonan where id=".$id;
			$sql6 = "insert into binus_dwh.per_kec(tipe_ijin,idkec,tgl_ijin) select 4,idkec,tgl_ijin from iutm.tblfcpermohonan where id=".$id;
			$sql7 = "insert into binus_dwh.per_kel(tipe_ijin,idkel,tgl_ijin) select 4,idkel,tgl_ijin from iutm.tblfcpermohonan where id=".$id;
			$sql8 = "insert into binus_dwh.tipe_input(tipe_ijin,tipe_input,tgl_ijin) select 4,asal_data,tgl_ijin from iutm.tblfcpermohonan where id=".$id;
			$CI->db->query($sql1);
			$CI->db->query($sql2);
			$CI->db->query($sql3);
			$CI->db->query($sql4);
			$CI->db->query($sql5);
			$CI->db->query($sql6);
			$CI->db->query($sql7);
			$CI->db->query($sql8);
		}
	}
}
