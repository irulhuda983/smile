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
$ls_kode_group_icd	= $_POST['v_kode_group_icd'];
$ls_nama_group_icd	= $_POST['v_nama_group_icd'];
$ls_no_urut				= $_POST['v_no_urut'];
$ls_tgl_nonaktif		= $_POST['v_tgl_nonaktif'];
$ls_status_nonaktif		= isset($_POST['v_status_nonaktif']) ? "Y":"T" ;
$ls_keterangan 			= $_POST['v_keterangan'];


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
	$sql = "insert into sijstk.pn_kode_group_icd (
				kode_group_icd,
				nama_group_icd,
                no_urut,
                status_nonaktif,
                tgl_nonaktif,
                keterangan,
                tgl_rekam,
                petugas_rekam)
			values(
				'$ls_kode_group_icd',
				'$ls_nama_group_icd',
			    '$ls_no_urut',
			    '$ls_status_nonaktif',
			    to_date('$ls_tgl_nonaktif','dd/mm/yyyy'),
			    '$ls_keterangan',
			    sysdate,
			    '$ls_user')";
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil ditambahkan!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan!"}';
	}

}else if($ls_type=="edit"){
	if($ls_status_nonaktif=='Y'){
		$optionalQuery = "tgl_nonaktif = to_date('$ls_tgl_nonaktif','dd/mm/yyyy'), petugas_nonaktif='$ls_user',";
	}
	$sql = "update sijstk.pn_kode_group_icd
			   set nama_group_icd = '$ls_nama_group_icd',
			       no_urut = '$ls_no_urut',
			       status_nonaktif = '$ls_status_nonaktif',
			       keterangan = '$ls_keterangan',
			       tgl_ubah = sysdate,
			       ".$optionalQuery."
			       petugas_ubah = '$ls_user'
			 where kode_group_icd = '$ls_kode_group_icd'";
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil diupdate!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}

}else if($ls_type=="view"){
	$sql="select kode_group_icd,
		       nama_group_icd,
		       no_urut,
		       status_nonaktif,
		       to_char(tgl_nonaktif,'dd/mm/yyyy') tgl_nonaktif,
		       keterangan
		  from sijstk.pn_kode_group_icd
		 where kode_group_icd='".$ls_search_keyword."'";
    $DB->parse($sql);
    if($DB->execute()){ 
		$querySql = $DB->get_row_data($sql);
		echo json_encode($querySql);
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}
	
	
}else if($ls_type=="delete"){
	$sql="delete from sijstk.pn_kode_group_icd
      		where kode_group_icd ='".$ls_kode_group_icd."'";
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
	    $order = "ORDER BY KODE_GROUP_ICD ";
	}else if($order[0]['column']=='2'){
	    $order = "ORDER BY NAMA_GROUP_ICD ";
	}else if($order[0]['column']=='3'){
	    $order = "ORDER BY NO_URUT ";
	}else if($order[0]['column']=='4'){
	    $order = "ORDER BY TGL_NONAKTIF ";
	}else if($order[0]['column']=='5'){
	    $order = "ORDER BY STATUS_NONAKTIF ";
	}
	$order .= $by;

	$sql = "";
	$sql="select * from (select rownum no, kode_group_icd,
		       nama_group_icd,
		       status_nonaktif,
		       tgl_nonaktif,
		       keterangan
		  from sijstk.pn_kode_group_icd
		 where 1 = 1  ".$condition." ".$order.")
		 where 1=1 and no between ".$start." and ".$end;

	$queryTotal = "SELECT COUNT (1) FROM SIJSTK.PN_KODE_GROUP_ICD WHERE 1=1  ".$condition;
	// print_r($sql);
	// print_r($querySql);

	$recordsTotal = $DB->get_data($queryTotal);      

	$DB->parse($sql);
	if($DB->execute()){ 
		$i = 0;
		while($data = $DB->nextrow())
		{
			$data['ACTION'] = '<button class="btn green" name="view_group_icd_'.$data['KODE_GROUP_ICD'].'" id="view_group_icd_'.$data['KODE_GROUP_ICD'].'" kode_group_icd="'.$data['KODE_GROUP_ICD'].'"  title="Klik untuk view detil data"><i class="fa fa-search" aria-hidden="true"></i></button>'.' '.' <button class="btn green" name="edit_group_icd_'.$data['KODE_GROUP_ICD'].'" id="edit_group_icd_'.$data['KODE_GROUP_ICD'].'" kode_group_icd="'.$data['KODE_GROUP_ICD'].'"  title="Klik untuk edit data"><i class="fa fa-pencil" aria-hidden="true"></i></button> <button class="btn hapus" name="hapus_group_icd_'.$data['KODE_GROUP_ICD'].'" kode_group_icd="'.$data['KODE_GROUP_ICD'].'" title="Klik untuk hapus data"><i class="fa fa-trash" aria-hidden="true"></i></button>
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