<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
 
$TYPE			= $_POST["TYPE"];
$DATAID		= $_POST["DATAID"];
$DATAID2	= $_POST["DATAID2"];

$ls_kode_klaim_kelayakan							= $_POST["kode_klaim"];
$ls_kode_kantor							= $_POST["kode_kantor"];	
$ls_kode_segmen			 				= $_POST["kode_segmen"];
$ld_tgl_klaim			 					= $_POST["tgl_klaim"];
$ls_kpj											= $_POST["kpj"];
$ls_nama_tk									= $_POST["nama_tk"];
$ls_kode_tk									= $_POST["kode_tk"];
$ls_nomor_identitas					= $_POST["nomor_identitas"];
$ls_jenis_identitas					= $_POST["jenis_identitas"];
$ls_kode_kantor_tk					= $_POST["kode_kantor_tk"];
$ls_no_proyek								= $_POST["no_proyek"];
$ls_nama_proyek							= $_POST["nama_proyek"];	
$ls_kode_proyek							= $_POST["kode_proyek"];
$ls_npp											= $_POST["npp"];
$ls_nama_perusahaan					= $_POST["nama_perusahaan"];
$ls_kode_perusahaan					= $_POST["kode_perusahaan"];
$ls_kode_divisi 						= $_POST["kode_divisi"];
$ls_nama_divisi							= $_POST["nama_divisi"];
$ls_kode_tipe_klaim					= $_POST["kode_tipe_klaim"];
$ls_nama_tipe_klaim					= $_POST["nama_tipe_klaim"];
$ls_kode_sebab_klaim				= $_POST["kode_sebab_klaim"];
$ls_nama_sebab_klaim				= $_POST["nama_sebab_klaim"];
$ld_tgl_lapor			 					= $_POST["tgl_lapor"];
$ls_keterangan							= $_POST["keterangan"];
$ls_jenis_klaim							= $_POST["jenis_klaim"];
$ls_mode_transaksi					= $_POST["mode_transaksi"];
$ls_status_klaim						= $_POST["status_klaim"];
$ld_tgl_kejadian			 			= $_POST["tgl_kejadian"];
$ls_status_kepesertaan			= $_POST["status_kepesertaan"];
$ls_kode_perlindungan				= $_POST["kode_perlindungan"];
$ld_tgl_awal_perlindungan	  = $_POST["tgl_awal_perlindungan"];
$ld_tgl_akhir_perlindungan	= $_POST["tgl_akhir_perlindungan"];
$ls_flag_vokasi             = $_POST["flag_vokasi"];

if ($ls_kode_segmen	!="TKI")
{
 	 $ls_kode_perlindungan = $ls_kode_segmen;
}
if ($ld_tgl_kejadian=="")
{
 $ld_tgl_kejadian = $ld_tgl_klaim;
}

/* Penambahan Post Input Data Integrasi Agenda Klaim dan Sistem Antrian*/
$ls_token_sisla				= $_POST["token_antrian"];
$ls_kode_jenis_antrian		= $_POST["kode_jenis_antrian"];
$ls_kode_status_antrian		= "ST01"; //SETUJU  //$_POST["kode_status_antrian"];
$ls_kode_sisla				= $_POST["kode_sisla"];
$ls_kode_kantor_antrian		= $_POST["kode_kantor_antrian"];
$ls_nomor_antrian			= $_POST["no_antrian"];
$ld_tgl_ambil_antrian		= $_POST["tgl_ambil_antrian"];
$ld_tgl_panggil_antrian		= $_POST["tgl_panggil_antrian"];
$ls_kode_petugas_antrian	= $_POST["kode_petugas_antrian"];
// $ls_nomor_identitas_antrian	= $_POST["nomor_identitas_antrian"];
$ls_no_hp_antrian	        = $_POST["no_hp_antrian"];
$ls_email_antrian	        = $_POST["email_antrian"];

// if ($ls_kode_tipe_klaim == 'CKJ01'){
	$ls_kode_jenis_antrian_detil = 'SA01CKJ01';
// }
$ls_kode_pointer_asal  		= 'PN5037';

$ls_kode_antrian		    = $_POST["kode_antrian"];

//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $DATAID != '')
{  
	$sql = "select * from PN.PN_AGENDA_KLAIM_KELAYAKAN where kode_kelayakan = '$DATAID' ";
 echo $sql;
	$DB->parse($sql);
	$DB->execute();
	$i = 0;
	$data = $DB->nextrow();
	$jsondata = json_encode($data);
	$jsondataStart = '{"ret":0,"count":0,"data":[';
	$jsondata .= ']}';
	$jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
	$jsondata = str_replace('},]}', '}]}', $jsondata);
	print_r($jsondata);
}
else if ($TYPE=="New")
{
  // cek exists agenda kelayakan klaim yg belum cek kelayakan agenda klaim
  $sql = "select count(*) JML_AGENDA_KELAYAKAN_KLAIM from PN.PN_AGENDA_KLAIM_KELAYAKAN 
			where kode_tk = '$ls_kode_tk'
			and kode_tipe_klaim = '$ls_kode_tipe_klaim'
			and kode_sebab_klaim = '$ls_kode_sebab_klaim'
			and nvl(status_cek_kelayakan,'T') = 'T'
			";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_jml_agenda_kelayakan_klaim = $row["JML_AGENDA_KELAYAKAN_KLAIM"];
  // end cek exists agenda kelayakan klaim yg belum cek kelayakan agenda klaim
  
  if ($ls_jml_agenda_kelayakan_klaim == "0")
  {
		//INSERT ---------------------------------------------------------------------
	  //generate kode klaim --------------------------------------------------------
	  $sql = 	"select PN.P_PN_PN5037.F_GEN_KODE_AGENDA_KLAIM as v_kode_klaim from dual ";
	  $DB->parse($sql);
	  $DB->execute();
	  $row = $DB->nextrow();
	  $ls_kode_klaim_kelayakan = $row["V_KODE_KLAIM"];

	  //insert data ----------------------------------------------------	 
	  $sql = "INSERT INTO PN.PN_AGENDA_KLAIM_KELAYAKAN
			  (
				KODE_AGENDA_KELAYAKAN,
				KODE_KANTOR,
				KODE_SEGMEN,
				KODE_PERUSAHAAN,
				KODE_DIVISI,
				KODE_PROYEK,
				KODE_TK,
				NAMA_TK,
				TGL_LAHIR,
				KPJ,
				NOMOR_IDENTITAS,
				JENIS_IDENTITAS,
				KODE_KANTOR_TK,
				KODE_TIPE_KLAIM,
				KODE_SEBAB_KLAIM,
				TGL_KELAYAKAN,
				TGL_LAPOR,
				TGL_KEJADIAN,
				KODE_PELAPORAN,
				KANAL_PELAYANAN,
				KETERANGAN,
				STATUS_KELAYAKAN,
				KET_KELAYAKAN,
				KODE_POINTER_ASAL,
				ID_POINTER_ASAL,
				PETUGAS_AGENDA,
				TGL_REKAM,
				PETUGAS_REKAM
			 )
			 VALUES 
			 (
				'$ls_kode_klaim_kelayakan', 
				'$ls_kode_kantor',
				'$ls_kode_segmen',
				'$ls_kode_perusahaan',
				'$ls_kode_divisi',
				'$ls_kode_proyek',
				'$ls_kode_tk',
				'$ls_nama_tk',
				(select TGL_LAHIR from kn.vw_kn_tk where kode_tk = '$ls_kode_tk' and kode_perusahaan = '$ls_kode_perusahaan' and rownum=1),
				'$ls_kpj',
				'$ls_nomor_identitas',
				'$ls_jenis_identitas',
				'$ls_kode_kantor_tk',
				'$ls_kode_tipe_klaim',
				'$ls_kode_sebab_klaim', 
				to_date('$ld_tgl_klaim','dd/mm/yyyy'),
				to_date('$ld_tgl_lapor','dd/mm/yyyy'), 
				to_date('$ld_tgl_kejadian','dd/mm/yyyy'),
				(select KODE_PELAPORAN from PN.PN_KODE_PELAPORAN where KODE_SEBAB_KLAIM = '$ls_kode_sebab_klaim' and KODE_SEGMEN = '$ls_kode_segmen'),
				'KACAB',
				'$ls_keterangan', 
				'T',
				null,
				'PN5037',
				'$ls_kode_klaim_kelayakan',
				'$username', 
				sysdate,
				'$username'
			 )
			 ";		 
	//echo 	$sql;exit;	 
	  $DB->parse($sql); 
	  if($DB->execute())
		{		 	
			$qry = "BEGIN PN.P_PN_PN5037.X_POST_INSERT('$ls_kode_klaim_kelayakan','$username',:p_sukses,:p_mess);END;";											 	
			$proc = $DB->parse($qry);				
			oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
			oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
			$DB->execute();				
			$ls_sukses = $p_sukses;	
			$ls_mess = $p_mess;	

			/* Penambahan Terhadap Integrasi Agenda Klaim dan Sistem Antrian*/
				//if($ls_kode_sisla != ""){
					$ls_kode_antrian = "";
					$qry = "
					begin 
						pn.p_pn_antrian.x_insert_antrian
						(
							:p_kode_jenis_antrian        ,
							:p_kode_status_antrian       , 
							:p_token_antrian             ,
							:p_kode_sisla                ,
							:p_kode_kantor_antrian       ,
							:p_no_antrian                ,
							:p_tgl_ambil                 ,
							:p_tgl_panggil               ,
							:p_kode_petugas_antrian      ,
							:p_nomor_identitas           ,  
							:p_no_hp                     ,
							:p_email                     ,
							:p_kode_klaim                ,
							:p_kode_jenis_antrian_detil  ,
							:p_keterangan                , 
							:p_kode_pointer_asal         ,     
							:p_user                      , 
							:p_sukses                    , 
							:p_mess                      ,
							:p_kode_antrian       
						); 
					end;
					";
					$proc = $DB->parse($qry);     
					oci_bind_by_name($proc, ":p_token_antrian", $ls_token_sisla, 500);
					oci_bind_by_name($proc, ":p_kode_jenis_antrian", $ls_kode_jenis_antrian,10);  
					oci_bind_by_name($proc, ":p_kode_sisla", $ls_kode_sisla, 100);
					oci_bind_by_name($proc, ":p_no_antrian", $ls_nomor_antrian,30);  
					oci_bind_by_name($proc, ":p_kode_kantor_antrian", $ls_kode_kantor_antrian, 10);
					oci_bind_by_name($proc, ":p_tgl_ambil", $ld_tgl_ambil_antrian,50);  
					oci_bind_by_name($proc, ":p_tgl_panggil", $ld_tgl_panggil_antrian, 50);
					oci_bind_by_name($proc, ":p_kode_petugas_antrian", $ls_kode_petugas_antrian,30); 
					oci_bind_by_name($proc, ":p_nomor_identitas", $ls_nomor_identitas,20);  
					oci_bind_by_name($proc, ":p_no_hp", $ls_no_hp_antrian, 20);
					oci_bind_by_name($proc, ":p_email", $ls_email_antrian,50);  
					oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim_kelayakan, 30);
					oci_bind_by_name($proc, ":p_kode_status_antrian", $ls_kode_status_antrian, 10);
					oci_bind_by_name($proc, ":p_kode_jenis_antrian_detil", $ls_kode_jenis_antrian_detil,10);  
					oci_bind_by_name($proc, ":p_keterangan", $ls_keterangan, 1000);
					oci_bind_by_name($proc, ":p_kode_pointer_asal", $ls_kode_pointer_asal, 10);
					oci_bind_by_name($proc, ":p_user", $username,1000);         
					oci_bind_by_name($proc, ":p_sukses", $p_sukses, 2);
					oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
					oci_bind_by_name($proc, ":p_kode_antrian", $p_kode_antrian, 30);
					$DB->execute();				
					$ls_sukses_antrian = $p_sukses;	
					$ls_mess_antrian = $p_mess;	
					$ls_kode_antrian = $p_kode_antrian;	

					//  var_dump($ls_mess_antrian);die();

					/*
					//generate kode klaim --------------------------------------------------------
					$sql = 	"select pn.p_pn_genid.f_gen_kode_antrian as kode_antrian from dual ";
					$DB->parse($sql);
					$DB->execute();
					$row = $DB->nextrow();
					$ls_kode_antrian = $row["KODE_ANTRIAN"];

					//insert data pn_antrian----------------------------------------------------	 
					$sql = 
					"insert into pn.pn_antrian ( ".
					"	 kode_antrian, kode_jenis_antrian, kode_status_antrian, ". 
					"    token_sisla, kode_sisla, kode_kantor, nomor_antrian, ". 
					"    tgl_ambil_antrian, tgl_panggil_antrian, petugas_panggil, ".
					"    id_pointer_asal, kode_pointer_asal, nomor_identitas, no_hp, email, ".
					"    kode_klaim_agenda, keterangan, tgl_rekam, petugas_rekam) ".
					"values ( ".
					"	 '$ls_kode_antrian', '$ls_kode_jenis_antrian', '$ls_kode_status_antrian', ".
					"	 '$ls_token_sisla', '$ls_kode_sisla','$ls_kode_kantor_antrian','$ls_nomor_antrian', ".
					"	 to_date('$ld_tgl_ambil_antrian','YYYY-MM-DD HH24:MI:SS'), to_date('$ld_tgl_panggil_antrian','YYYY-MM-DD HH24:MI:SS'), '$ls_kode_petugas_antrian', ".
					"	 '$ls_kode_antrian', 'PN5001','$ls_nomor_identitas', '$ls_no_hp_antrian', '$ls_email_antrian', ".
					"	 '$ls_kode_klaim', '$ls_keterangan', sysdate, '$username' ".
					") ";				 
					$DB->parse($sql);
					$DB->execute();

					//insert data pn_antrian_program----------------------------------------------------	 
					$sql = 
					"insert into pn.pn_antrian_program ( ".
					"	 kode_antrian, kode_jenis_antrian, kode_jenis_antrian_detil, ". 
					"    keterangan, tgl_rekam, petugas_rekam) ".
					"values ( ".
					"	 '$ls_kode_antrian', '$ls_kode_jenis_antrian', '$ls_kode_jenis_antrian_detil', ".
					"	 '$ls_keterangan', sysdate, '$username' ".
					") ";				 
					$DB->parse($sql);
					$DB->execute();

					//insert data pn_antrian_dokumen----------------------------------------------------	 
					$sql = 
					"insert into pn.pn_antrian_dokumen ( ".
					"	 kode_antrian, kode_dokumen, flag_upload, ". 
					"    keterangan, tgl_rekam, petugas_rekam) ".
					"values ( ".
					"	 '$ls_kode_antrian', 'D221', 'T', ".
					"	 '$ls_keterangan', sysdate, '$username' ".
					") ";				 
					$DB->parse($sql);
					$DB->execute();
					*/
				//}
				/* End Penambahan Terhadap Integrasi Agenda Klaim dan Sistem Antrian*/
			
			// if ($ls_sukses=="1")
			// {
				echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_klaim_kelayakan.'","kodeAntrian":"'.$ls_kode_antrian.'"}';
			// }else{
			// 	echo '{"ret":0,"msg":"Gagal, Data agenda tidak berhasil diubah, session dilanjutkan..","DATAID":"'.$ls_kode_klaim_kelayakan.'","kodeAntrian":"'.$ls_kode_antrian.'"}';
			// }
		}else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
	  }
  }
  else
  {
	  $sql = "select KODE_AGENDA_KELAYAKAN from PN.PN_AGENDA_KLAIM_KELAYAKAN 
			where kode_tk = '$ls_kode_tk'
			and kode_tipe_klaim = '$ls_kode_tipe_klaim'
			and kode_sebab_klaim = '$ls_kode_sebab_klaim'
			and nvl(status_cek_kelayakan,'T') = 'T'
			and rownum=1
			";
	  $DB->parse($sql);
	  $DB->execute();
	  $row = $DB->nextrow();
	  $ls_agenda_kelayakan_klaim = $row["KODE_AGENDA_KELAYAKAN"];
	  
	  echo '{"ret":0,"msg":"Gagal, Data agenda kelayakan klaim dengan KPJ '.$ls_kpj.' sudah ada dengan kode kelayakan '.$ls_agenda_kelayakan_klaim.'"}';
  }
}
else if ($TYPE=="Edit" && $DATAID != '')
{
	$ls_sukses = "0";
	$sql = "select * from PN.PN_AGENDA_KLAIM_KELAYAKAN where kode_kelayakan = '$DATAID' ";

	$DB->parse($sql);
	$DB->execute();
	$i = 0;
	$data = $DB->nextrow();
	$jsondata = json_encode($data);
	$jsondataStart = '{"ret":0,"count":0,"data":[';
	$jsondata .= ']}';
	$jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
	$jsondata = str_replace('},]}', '}]}', $jsondata);
	print_r($jsondata);
	
	
}
else if (($TYPE =='DEL') && ($DATAID != ''))
{
 	//DELETE ---------------------------------------------------------------------  
  $qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_DELETE('$DATAID','$username',:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;	
  $ls_mess = $p_mess;	
  		 
  if ($ls_sukses=="1")
  {	
  	echo '{"ret":0,"msg":"Sukses, Data berhasil dihapus, session dilanjutkan..","DATAID":"'.$DATAID.'"}';
  }else {
  	echo '{"ret":-1,"msg":"Data agenda sudah disubmit tidak dapat dihapus...!!!"}';
  }
} 

function get_json_encode($p_url, $p_fields)
{
  // set HTTP header -----------------------------------------------------------
  $headers = array(
    'Content-Type' => 'application/json',
    'X-Forwarded-For' => $ipfwd,
  );

  $ch = curl_init();
  // Set the url, number of POST vars, POST data ----------
  curl_setopt($ch, CURLOPT_URL, $p_url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($p_fields));
  
  // Execute post ---------------------------------------
  $result = curl_exec($ch);
  // Close connection
  curl_close($ch);
	
  return $result;
}// end get_json_encode --------------------------------------------------------


?>
