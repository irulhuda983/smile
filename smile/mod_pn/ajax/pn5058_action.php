<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
 
$TYPE			= $_POST["TYPE"];
$act			= $_POST["act"];
$ls_subact= $_POST["subact"];
$DATAID		= $_POST["DATAID"];
$DATAID2	= $_POST["DATAID2"];
// var_dump($ls_subact);die();
$ls_kode_agenda_pernyataan							= $_POST["kode_agenda_pernyataan"];
$ls_kode_kantor							= $_POST["kode_kantor"];	
$ls_kode_segmen			 				= $_POST["kode_segmen"];
$ls_nama_perusahaan					= $_POST["nama_perusahaan"];
$ls_kode_perusahaan					= $_POST["kode_perusahaan"];
$ls_kode_divisi 						= $_POST["kode_divisi"];
$ls_nama_divisi							= $_POST["nama_divisi"];
$ls_kode_tk									= $_POST["kode_tk"];
$ls_nama_tk									= $_POST["nama_tk"];
$ls_tempat_lahir								= $_POST["tempat_lahir"];
$ld_tgl_lahir								= $_POST["tgl_lahir"];
$ls_alamat_tk								= $_POST["alamat_tk"];
$ls_nomor_telepon_tk								= $_POST["nomor_telepon_tk"];
$ls_kpj											= $_POST["kpj"];
$ls_nomor_identitas					= $_POST["nomor_identitas"];
$ls_jenis_identitas					= $_POST["jenis_identitas"];
$ls_keterangan_pernyataan					= $_POST["keterangan_pernyataan"];
$ld_tgl_lapor			 					= $_POST["tgl_lapor"];
$ls_status_submit           = $_POST["status_submit"];

//VIEW -------------------------------------------------------------------------
// if ($TYPE=="View" &&  $ls_subact=="ubah")
// {  
// 	// $sql = "select * from PN.PN_AGENDA_PERNYATAAN where kode_agenda_pernyataan = '$DATAID' ";
// 	// $DB->parse($sql);
// 	// $DB->execute();
// 	// $i = 0;
// 	// $data = $DB->nextrow();
// 	// $jsondata = json_encode($data);
// 	// $jsondataStart = '{"ret":0,"count":0,"data":[';
// 	// $jsondata .= ']}';
// 	// $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
// 	// $jsondata = str_replace('},]}', '}]}', $jsondata);
// 	// print_r($jsondata);
// }
if ($TYPE=="New")
{
  //INSERT ---------------------------------------------------------------------  
  $qry = "
	  BEGIN PN.P_PN_PN5058.X_POST_INSERT
	  (
		'$ls_kode_kantor',
		'$ls_kode_segmen',
		'$ls_kode_perusahaan',
		'$ls_kode_divisi',
		'$ls_kode_tk',
		'$ls_nama_tk',
		'$ls_tempat_lahir',
		to_date('$ld_tgl_lahir','dd/mm/yyyy'),
		'$ls_alamat_tk',
		'$ls_nomor_telepon_tk',
		'$ls_kpj',
		'$ls_nomor_identitas',
		'$ls_jenis_identitas',
		'$ls_keterangan_pernyataan',
		to_date('$ld_tgl_lapor','dd/mm/yyyy'),
		'$username', 
		:p_kode_agenda_pernyataan, 
		:p_sukses,
		:p_mess
	  );
	  END;";	
  //echo $qry;exit;
  $proc = $DB->parse($qry);				
  //oci_bind_by_name($proc, ":p_kode_kantor", $ls_kode_kantor,30);
  //oci_bind_by_name($proc, ":p_kode_segmen", $ls_kode_segmen,5);
  //oci_bind_by_name($proc, ":p_kode_perusahaan", $ls_kode_perusahaan,5);
  //oci_bind_by_name($proc, ":p_kode_divisi", $ls_kode_divisi,30);
  //oci_bind_by_name($proc, ":p_kode_tk", $ls_kode_tk,30);
  //oci_bind_by_name($proc, ":p_nama_tk", $ls_nama_tk,100);
  //oci_bind_by_name($proc, ":p_tempat_lahir", $ls_tempat_lahir,100);
  //oci_bind_by_name($proc, "to_date(:p_tgl_lahir,'dd/mm/yyyy')", $ld_tgl_lahir,30);
  //oci_bind_by_name($proc, ":p_alamat_tk", $ls_alamat_tk,4000);
  //oci_bind_by_name($proc, ":p_no_telepon_tk", $ls_nomor_telepon_tk,30);
  //oci_bind_by_name($proc, ":p_kpj", $ls_kpj,30);
  //oci_bind_by_name($proc, ":p_nomor_identitas", $ls_nomor_identitas,30);
  //oci_bind_by_name($proc, ":p_jenis_identitas", $ls_jenis_identitas,30);
  //oci_bind_by_name($proc, ":p_keterangan_pernyataan", $ls_keterangan_pernyataan,4000);
  //oci_bind_by_name($proc, "to_date(:p_tgl_lapor,'dd/mm/yyyy')", $ld_tgl_lapor,30);  
  //oci_bind_by_name($proc, ":p_user", $username,30);
  oci_bind_by_name($proc, ":p_kode_agenda_pernyataan", $p_kode_agenda_pernyataan,30);
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,4000);
	//var_dump($qry);die();
  $DB->execute();		
	
  $ls_kode_agenda_pernyataan = $p_kode_agenda_pernyataan;	  
  $ls_sukses = $p_sukses;	
  $ls_mess = $p_mess;	
  // var_dump($ls_sukses); 
	if ($ls_sukses=="1")
	{
		echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_agenda_pernyataan.'"}';
	}else{
		echo '{"ret":0,"msg":"Gagal, Data agenda tidak berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_agenda_pernyataan.'"}';
	}
}
else if (($TYPE=="Edit" && $ls_subact=="ubah")||($TYPE=="View" &&  $ls_subact=="ubah"))
{
	//UBAH ---------------------------------------------------------------------  
	$qry = "
	  BEGIN PN.P_PN_PN5058.X_POST_UPDATE
	  (
		'$DATAID',
		'$ls_alamat_tk',
		'$ls_nomor_telepon_tk',
		'$ls_keterangan_pernyataan',
		'$username', 
		:p_sukses,
		:p_mess
	  );
	  END;";	
		
	$proc = $DB->parse($qry);				
	// oci_bind_by_name($proc, ":p_kode_agenda_pernyataan", $ls_kode_agenda_pernyataan,30);
	// oci_bind_by_name($proc, ":p_alamat_tk", $ls_alamat_tk,4000);
	// oci_bind_by_name($proc, ":p_no_telepon_tk", $ls_nomor_telepon_tk,30);
	// oci_bind_by_name($proc, ":p_keterangan_pernyataan", $ls_keterangan_pernyataan,4000);

	//oci_bind_by_name($proc, ":p_user", $username,30);
	oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
	oci_bind_by_name($proc, ":p_mess", $p_mess,4000);
	// var_dump($qry);die();
	$DB->execute();		

	$ls_sukses = $p_sukses;	
	$ls_mess = $p_mess;	
	// var_dump($ls_sukses);
	if ($ls_mess=="1")
	{
		echo '{"ret":0,"msg":"Sukses, Data agenda berhasil diubah, session dilanjutkan..","DATAID":"'.$DATAID.'"}';
	}else{
		echo '{"ret":0,"msg":"Gagal, Data agenda tidak berhasil diubah, session dilanjutkan..","DATAID":"'.$DATAID.'"}';
	}
}
// else if ($TYPE=="Edit" && $DATAID != '' && $act="Edit")
// {
// 	$ls_sukses = "0";
// 	$sql = "select * from PN.PN_AGENDA_PERNYATAAN where kode_agenda_pernyataan = '$DATAID' ";
// echo $sql;
// 	$DB->parse($sql);
// 	$DB->execute();
// 	$i = 0;
// 	$data = $DB->nextrow();
// 	$jsondata = json_encode($data);
// 	$jsondataStart = '{"ret":0,"count":0,"data":[';
// 	$jsondata .= ']}';
// 	$jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
// 	$jsondata = str_replace('},]}', '}]}', $jsondata);
// 	print_r($jsondata);
// }
else if (($TYPE=="Edit" && $ls_subact == 'simpan')||($TYPE=="View" &&  $ls_subact=="simpan"))
{
	//INSERT ---------------------------------------------------------------------
	  //generate kode klaim --------------------------------------------------------		 	
		$qry = "BEGIN PN.P_PN_PN5058.X_POST_SUBMIT('$DATAID','$username',:p_sukses,:p_mess);END;";											 	
		$proc = $DB->parse($qry);			
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
		
		$DB->execute();				
		$ls_sukses = $p_sukses;	
		$ls_mess = $p_mess;	
		// var_dump($ls_sukses);
		//$ls_kode_agenda_pernyataan = $p_kode_agenda_pernyataan;	
		// var_dump($qry);die();
		

		if ($ls_sukses=="1")
		{
			echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$DATAID.'"}';
		}else{
			echo '{"ret":0,"msg":"Gagal, Data agenda tidak berhasil diubah, session dilanjutkan..","DATAID":"'.$DATAID.'"}';
		}
}
else if (($TYPE=="Edit" && $ls_subact == 'batal')||($TYPE=="View" &&  $ls_subact=="batal"))
{
 	//BATAL ---------------------------------------------------------------------  
  $qry = "BEGIN PN.P_PN_PN5058.X_POST_BATAL('$DATAID','$username',:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;	
  $ls_mess = $p_mess;	
  // var_dump($ls_sukses);
  if ($ls_sukses=="1")
  {	
  	echo '{"ret":0,"msg":"Sukses, Data berhasil dibatalkan, session dilanjutkan..","DATAID":"'.$DATAID.'"}';
  }else {
  	echo '{"ret":-1,"msg":"Data agenda sudah disubmit tidak dapat dibatalkan...!!!"}';
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
