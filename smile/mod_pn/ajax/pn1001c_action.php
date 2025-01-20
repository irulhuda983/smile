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
$ls_kode_sebab_segmen					= $_POST['v_kode_sebab_segmen'];
$ls_no_urut_sebab_segmen				= $_POST['v_no_urut_sebab_segmen'];
$ls_tgl_nonaktif_sebab_segmen			= $_POST['v_tgl_nonaktif_sebab_segmen'];
$ls_status_nonaktif_sebab_segmen		= isset($_POST['v_status_nonaktif_sebab_segmen']) ? "Y":"T" ;
$ls_keterangan_sebab_segmen 			= $_POST['v_keterangan_sebab_segmen'];


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
	$sql = "insert into pn.pn_kode_sebab_segmen (
				kode_sebab_klaim,
                kode_segmen,
                status_nonaktif,
                tgl_nonaktif,
                keterangan,
                tgl_rekam,
                petugas_rekam)
			values(
				'$ls_kode_sebab_klaim',
				'$ls_kode_sebab_segmen',
			    '$ls_status_nonaktif_sebab_segmen',
			    to_date('$ls_tgl_nonaktif_sebab_segmen','dd/mm/yyyy'),
			    '$ls_keterangan_sebab_segmen',
			    sysdate,
			    '$ls_user')";
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil ditambahkan!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan!"}';
	}

}else if($ls_type=="edit"){
	$sql = "update sijstk.pn_kode_sebab_segmen
			   set no_urut = '$ls_no_urut_sebab_segmen',
			       status_nonaktif = '$ls_status_nonaktif_sebab_segmen',
			       keterangan = '$ls_keterangan_sebab_segmen',
			       tgl_ubah = sysdate,
			       petugas_ubah = '$ls_user'
			 where kode_segmen = '$ls_kode_sebab_segmen'";
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil diupdate!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}

}else if($ls_type=="view"){
	$sql="select kode_segmen,
		       no_urut,
		       status_nonaktif,
		       to_char(tgl_nonaktif,'dd/mm/yyyy') tgl_nonaktif,
		       keterangan
		  from sijstk.pn_kode_sebab_segmen
		 where kode_segmen='".$ls_search_keyword."'";
    $DB->parse($sql);
    if($DB->execute()){ 
		$querySql = $DB->get_row_data($sql);
		echo json_encode($querySql);
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}
	
	
}else if($ls_type=="delete"){
	$sql="delete from sijstk.pn_kode_sebab_segmen
      		where kode_segmen ='".$ls_kode_sebab_segmen."' and kode_sebab_klaim='".$ls_kode_sebab_klaim."'";
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
	    $order = "ORDER BY KODE_SEGMEN ";
	}else if($order[0]['column']=='1'){
	    $order = "ORDER BY TGL_NONAKTIF ";
	}else if($order[0]['column']=='2'){
	    $order = "ORDER BY STATUS_NONAKTIF ";
	}
	$order .= $by;

	$sql = "";
	$sql="select * from (select rownum no, kode_segmen,
		       status_nonaktif,
		       tgl_nonaktif,
		       keterangan
		  from sijstk.pn_kode_sebab_segmen
		 where 1 = 1 and kode_sebab_klaim='$ls_kode_sebab_klaim'  ".$condition." ".$order.")
		 where 1=1 and no between ".$start." and ".$end;

	$queryTotal = "SELECT COUNT (1) FROM SIJSTK.PN_KODE_SEBAB_SEGMEN WHERE 1=1 and kode_sebab_klaim='$ls_kode_sebab_klaim' ".$condition;
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
			$data['ACTION'] = '<button class="btn green" name="view_sebab_segmen_'.$data['KODE_SEGMEN'].'" id="view_sebab_segmen_'.$data['KODE_SEGMEN'].'" kode_sebab_segmen="'.$data['KODE_SEGMEN'].'"  title="Klik untuk view detil data"><i class="fa fa-search" aria-hidden="true"></i></button>'.' '.' <button class="btn green" name="edit_sebab_segmen_'.$data['KODE_SEGMEN'].'" id="edit_sebab_segmen_'.$data['KODE_SEGMEN'].'" kode_sebab_segmen="'.$data['KODE_SEGMEN'].'"  title="Klik untuk edit data"><i class="fa fa-pencil" aria-hidden="true"></i></button> <button class="btn hapus" name="hapus_sebab_segmen'.$data['KODE_SEGMEN'].'" kode_sebab_segmen="'.$data['KODE_SEGMEN'].'" title="Klik untuk hapus data"><i class="fa fa-trash" aria-hidden="true"></i></button>
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