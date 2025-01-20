<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$ls_type				= $_POST["type"];
$ls_search_pilihan		= $_POST["pilihan"];
$ls_search_keyword 		= $_POST["keyword"];
$ls_user 				= $_SESSION["USER"];

//form serialize
$ls_kode_sebab_klaim					= $_POST['c_kode_sebab_klaim'];
$ls_kode_sebab_dokumen					= $_POST['v_kode_sebab_dokumen'];
$ls_kode_sebab_perlindungan				= $_POST['v_kode_sebab_perlindungan'];
$ls_syarat_tahap_ke_sebab_dokumen		= $_POST['v_syarat_tahap_ke_sebab_dokumen'];
$ls_flag_mandatory_sebab_dokumen		= isset($_POST['v_flag_mandatory_sebab_dokumen']) ? "Y":"T";
$ls_no_urut_sebab_dokumen				= $_POST['v_no_urut_sebab_dokumen'];
$ls_tgl_nonaktif_sebab_dokumen			= $_POST['v_tgl_nonaktif_sebab_dokumen'];
$ls_status_nonaktif_sebab_dokumen		= isset($_POST['v_status_nonaktif_sebab_dokumen']) ? "Y":"T" ;
$ls_keterangan_sebab_dokumen 			= $_POST['v_keterangan_sebab_dokumen'];


function handleError($errno, $errstr,$error_file,$error_line) {
    if($errno == 2){
        $errorMsg = $errstr;
        if (strpos($errstr, 'failed to open stream:') !== false) {
            $errorMsg = 'Terdapat masalah dengan koneksi WebService.';
        } elseif(strpos($errstr, 'oci_connect') !== false) {
            $errorMsg = 'Terdapat masalah dengan koneksi database.';
        } else {
            $errorMsg = $errstr;
        }
    	echo '{
                "ret":-1,
                "msg":"<b>Error:</b> '.$errorMsg.'"
            }';
		die();
    }
}
set_error_handler("DefaultGlobalErrorHandler");


if($ls_type=="new"){
	$sql = "insert into pn.pn_kode_sebab_dokumen (
				kode_sebab_klaim,
                kode_dokumen,
                kode_perlindungan,
                syarat_tahap_ke,
                flag_mandatory,
                no_urut,
                status_nonaktif,
                tgl_nonaktif,
                keterangan,
                tgl_rekam,
                petugas_rekam)
			values(
				'$ls_kode_sebab_klaim',
				'$ls_kode_sebab_dokumen',
				'$ls_kode_sebab_perlindungan',
				'$ls_syarat_tahap_ke_sebab_dokumen',
				'$ls_flag_mandatory_sebab_dokumen',
			    '$ls_no_urut_sebab_dokumen',
			    '$ls_status_nonaktif_sebab_dokumen',
			    to_date('$ls_tgl_nonaktif_sebab_dokumen','dd/mm/yyyy'),
			    '$ls_keterangan_sebab_dokumen',
			    sysdate,
			    '$ls_user')";
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil ditambahkan!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan!"}';
	}

}else if($ls_type=="edit"){
	$sql = "update sijstk.pn_kode_sebab_dokumen
			   set 
					kode_perlindungan = '$ls_kode_sebab_perlindungan',
					syarat_tahap_ke = '$ls_syarat_tahap_ke_sebab_dokumen',
					flag_mandatory = '$ls_flag_mandatory_sebab_dokumen',
			   		no_urut = '$ls_no_urut_sebab_dokumen',
			       status_nonaktif = '$ls_status_nonaktif_sebab_dokumen',
			       keterangan = '$ls_keterangan_sebab_dokumen',
			       tgl_ubah = sysdate,
			       petugas_ubah = '$ls_user'
			 where kode_dokumen = '$ls_kode_sebab_dokumen'";
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil diupdate!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}

}else if($ls_type=="view"){
	$sql="select kode_dokumen,
				kode_perlindungan,
				syarat_tahap_ke,
				flag_mandatory,
		       no_urut,
		       status_nonaktif,
		       to_char(tgl_nonaktif,'dd/mm/yyyy') tgl_nonaktif,
		       keterangan
		  from sijstk.pn_kode_sebab_dokumen
		 where kode_dokumen='".$ls_search_keyword."'";
    $DB->parse($sql);
    if($DB->execute()){ 
		$querySql = $DB->get_row_data($sql);
		echo json_encode($querySql);
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}
	
	
}else if($ls_type=="delete"){
	$sql="delete from sijstk.pn_kode_sebab_dokumen
      		where kode_dokumen ='".$ls_kode_sebab_dokumen."' and kode_sebab_klaim='".$ls_kode_sebab_klaim."'";
    $DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil didelete!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal didelete!"}';
	}
	
}else if($ls_type=="query"){
	$draw = 1;
	if(isset($_POST['draw'])){
	    $draw = $_POST['draw']; 
	}else{
	    $draw = 1;       
	}

	$start = $_POST['start']+1;
	$length = $_POST['length'];
	$end = ($start-1) + $length;

	$condition ="";
	//add query condition for keyword
	if($ls_search_pilihan!=""){
		$condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
	} 

	$order      = $_POST["order"];
	$by = $order[0]['dir'];

	if($order[0]['column']=='0'){
	    $order = "ORDER BY NO_URUT ";
	}else if($order[0]['column']=='1'){
	    $order = "ORDER BY KODE_PERLINDUNGAN ";
	}else if($order[0]['column']=='2'){
	    $order = "ORDER BY SYARAT_TAHAP_KE ";
	}else if($order[0]['column']=='2'){
	    $order = "ORDER BY FLAG_MANDATORY ";
	}else if($order[0]['column']=='3'){
	    $order = "ORDER BY NO_URUT ";
	}else if($order[0]['column']=='4'){
	    $order = "ORDER BY TGL_NONAKTIF ";
	}else if($order[0]['column']=='5'){
	    $order = "ORDER BY STATUS_NONAKTIF ";
	}
	$order .= $by;

	$sql = "";
	$sql="select * from (select rownum no, kode_dokumen,
				kode_perlindungan,
				syarat_tahap_ke,
				flag_mandatory,
		       no_urut,
		       status_nonaktif,
		       tgl_nonaktif,
		       keterangan
		  from sijstk.pn_kode_sebab_dokumen
		 where 1 = 1 and kode_sebab_klaim='$ls_kode_sebab_klaim'  ".$condition." ".$order.")
		 where 1=1 and no between ".$start." and ".$end;

	$queryTotal = "SELECT COUNT (1) FROM SIJSTK.PN_KODE_SEBAB_DOKUMEN WHERE 1=1 and kode_sebab_klaim='$ls_kode_sebab_klaim' ".$condition;
	// print_r($sql);
	// print_r($querySql);

	$recordsTotal = $DB->get_data($queryTotal);      

	$DB->parse($sql);
	if($DB->execute()){ 
		$i = 0;
		while($data = $DB->nextrow())
		{
		// 	$data['ACTION'] = '<input type="checkbox" class="btn green" id="CHECK_'.$data['KODE_SEBAB_KLAIM'].'" name="CHECK_'.$data['KODE_SEBAB_KLAIM'].'" kode_sebab_klaim="'.$data['KODE_SEBAB_KLAIM'].'"  title="Pilih untuk pengaturan data">
		// ';
		//     $data['DETAIL'] = '<button class="btn green" id="CHECK_'.$data['KODE_SEBAB_KLAIM'].'" name="DOKUMEN_'.$data['KODE_SEBAB_KLAIM'].'" kode_tipe_klaim="'.$data['KODE_SEBAB_KLAIM'].'"  title="Pilih untuk penambahan dokumen"><i class="fa fa-file-text" aria-hidden="true"></i> Dokumen</button>&nbsp
		//     <button value="Segmen" class="btn green" id="CHECK_'.$data['KODE_SEBAB_KLAIM'].'" name="CHECK_'.$data['KODE_SEBAB_KLAIM'].'" kode_tipe_klaim="'.$data['KODE_SEBAB_KLAIM'].'"  title="Pilih untuk penambahan dokumen"><i class="fa fa-university" aria-hidden="true"></i> Segmen</button>
		// ';
			$data['ACTION'] = '<button class="btn green" name="view_sebab_dokumen_'.$data['KODE_DOKUMEN'].'" id="view_sebab_dokumen_'.$data['KODE_DOKUMEN'].'" kode_sebab_dokumen="'.$data['KODE_DOKUMEN'].'"  title="Klik untuk view detil data"><i class="fa fa-search" aria-hidden="true"></i></button>'.' '.' <button class="btn green" name="edit_sebab_dokumen_'.$data['KODE_DOKUMEN'].'" id="edit_sebab_dokumen_'.$data['KODE_DOKUMEN'].'" kode_sebab_dokumen="'.$data['KODE_DOKUMEN'].'"  title="Klik untuk edit data"><i class="fa fa-pencil" aria-hidden="true"></i></button> <button class="btn hapus" name="hapus_sebab_dokumen'.$data['KODE_DOKUMEN'].'" kode_sebab_dokumen="'.$data['KODE_DOKUMEN'].'" title="Klik untuk hapus data"><i class="fa fa-trash" aria-hidden="true"></i></button>
				';
		    $jsondata .= json_encode($data);
		    $jsondata .= ',';
		    $i++;
		}
		$jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
		$jsondata .= ']}';
		$jsondata = $jsondataStart . str_replace('},]}', '}]}', $jsondata);
		echo $jsondata;
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
	}

	
}


?>