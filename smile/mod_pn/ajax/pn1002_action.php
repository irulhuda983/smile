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
//new
$ls_kode_manfaat_dataid	= $_POST["DATAID"];
$ls_kode_manfaat 		= $_POST["kode_manfaat"];
$ls_nama_manfaat		= $_POST["nama_manfaat"];
$ls_kategori_manfaat 	= $_POST["kategori_manfaat"];
$ls_jenis_manfaat 	 	= $_POST["jenis_manfaat"];
$ls_tipe_manfaat 		= $_POST["tipe_manfaat"];
$ls_flag_berkala 		= $_POST["flag_berkala"];
$ls_kode_formula		= $_POST["kode_formula"];
$ls_no_urut 			= $_POST["no_urut"];
$ls_status_nonaktif		= $_POST["status_nonaktif"];
$ls_keterangan 			= $_POST["keterangan"];
$url_path 				= $_POST["url_path"];
$ls_tgl_rekam 			= $_POST["tgl_rekam"];
$ls_petugas_rekam 		= $ls_user;

if($ls_status_nonaktif == 'Y'){
	$ls_petugas_nonaktif = $ls_user;
	$ls_tgl_nonaktif 	 = date('d/m/Y');
}

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
                "success":false,
                "msg":"<b>Error:</b> '.$errorMsg.'"
            }';
      die();
    }
}
set_error_handler("DefaultGlobalErrorHandler");


if($ls_type=="new"){
	$sql = "insert into sijstk.pn_kode_manfaat (
				kode_manfaat,
                nama_manfaat,
                kategori_manfaat,
                jenis_manfaat,
                tipe_manfaat,
                flag_berkala,
                formula,
                no_urut,
                status_nonaktif,
                tgl_nonaktif,
                petugas_nonaktif,
                keterangan,
                url_path,
                tgl_rekam,
                petugas_rekam)
			values(
				'$ls_kode_manfaat',
				'$ls_nama_manfaat',
			    '$kategori_manfaat',
			    '$ls_jenis_manfaat',
			    '$ls_tipe_manfaat',
			    '$ls_flag_berkala',
			    '$ls_kode_formula',
			    '$ls_no_urut',
			    '$ls_status_nonaktif',
			    '$ls_tgl_nonaktif',
			    '$ls_petugas_nonaktif',
			    '$ls_keterangan',
			    '$ls_url_path',
			    sysdate,
			    '$ls_user')";
	// print_r($sql);
	// die;
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil ditambahkan!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan!"}';
	}

}else if($ls_type=="edit"){
	$sql = "update sijstk.pn_kode_manfaat_eligibility
			   set kode_manfaat = '$ls_kode_manfaat',
			       kode_tipe_penerima = '$ls_kode_tipe_penerima',
			       status_default = '$ls_status_default',
			       status_nonaktif = '$ls_status_nonaktif',
			       petugas_nonaktif = '$ls_petugas_nonaktif',
			       keterangan = '$ls_keterangan',
			       tgl_ubah = sysdate,
			       petugas_ubah = '$ls_user'
			 where kode_manfaat = '$ls_kode_manfaat' and kode_tipe_penerima = '$ls_kode_tipe_penerima_default'";
	// echo $sql;
	// die;
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil diupdate!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}

}else if($ls_type=="view"){
	$sql="	SELECT 
					kode_manfaat,
	                nama_manfaat,
	                kategori_manfaat,
	                jenis_manfaat,
	                tipe_manfaat,
	                flag_berkala,
	                formula,
	                no_urut,
	                status_nonaktif,
	                tgl_nonaktif,
	                petugas_nonaktif,
	                keterangan,
	                url_path,
	                tgl_rekam,
	                petugas_rekam
			FROM   	sijstk.pn_kode_manfaat
			WHERE  	kode_manfaat='".$ls_kode_manfaat_dataid."'";
	// print_r($sql);
	// die;
    $DB->parse($sql);
    if($DB->execute()){ 
		$querySql = $DB->get_row_data($sql);
		echo json_encode($querySql);
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}
	
	
}else if($ls_type=="delete"){
	$sql="delete from sijstk.pn_kode_manfaat_eligibility
      		where kode_tipe_penerima ='".$ls_kode_tipe_penerima."' and kode_manfaat='".$ls_kode_manfaat."'";
    $DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil didelete!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal didelete!"}';
	}
	
}else if($ls_type=="query"){
	$kode_manfaat = $_POST['kode_manfaat'];
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

	$order 	= $_POST["order"];
	$by 	= $order[0]['dir'];

	if($order[0]['column']=='0'){
	    $order = "ORDER BY NO ";
	}else if($order[0]['column']=='1'){
	    $order = "ORDER BY c.NAMA_MANFAAT";
	}else if($order[0]['column']=='2'){
	    $order = "ORDER BY b.NAMA_TIPE_PENERIMA";
	}
	$order .= $by;

	$sql = "";
	$sql="SELECT * FROM (
			SELECT rownum as no,
				   a.kode_manfaat,
				   a.kode_tipe_penerima,
				   a.kode_manfaat||a.kode_tipe_penerima as kode_manfaat_eligibility,
			       c.nama_manfaat,
			       b.nama_tipe_penerima,
			       a.status_default,
			       a.status_nonaktif,
			       a.keterangan
			FROM   sijstk.pn_kode_manfaat_eligibility a,
			       sijstk.pn_kode_tipe_penerima b,
			       sijstk.pn_kode_manfaat c
			WHERE  a.kode_manfaat = c.kode_manfaat
			       AND a.kode_tipe_penerima = b.kode_tipe_penerima
			       AND a.kode_manfaat = '".$kode_manfaat."'
				   AND 1 = 1  ".$condition." ".$order." )
		  where no between ".$start." and ".$end;

	$queryTotal = "SELECT COUNT (A.KODE_MANFAAT) FROM SIJSTK.PN_KODE_MANFAAT_ELIGIBILITY A, SIJSTK.PN_KODE_TIPE_PENERIMA B WHERE 1=1 AND A.KODE_TIPE_PENERIMA = B.KODE_TIPE_PENERIMA and A.KODE_MANFAAT = '".$kode_manfaat."' ".$condition;
	// echo $sql;
	// die;

	$recordsTotal = $DB->get_data($queryTotal);      

	$DB->parse($sql);
	if($DB->execute()){ 
		$i = 0;
		while($data = $DB->nextrow())
		{
		    $data['ACTION'] = '<input type="button" class="btn green" id="BTN_EDT_'.$data['KODE_MANFAAT_ELIGIBILITY'].'" name="BTN_EDT_'.$data['KODE_MANFAAT_ELIGIBILITY'].'" kode_manfaat="'.$data['KODE_MANFAAT'].'" kode_tipe_penerima="'.$data['KODE_TIPE_PENERIMA'].'"  title="Pilih untuk ubah detil manfaat eligibility" value="Ubah" onclick="edit(\''.$data['KODE_MANFAAT'].'\',\''.$data['KODE_TIPE_PENERIMA'].'\')">&nbsp;|&nbsp;<input type="button" class="btn green" id="BTN_DEL_'.$data['KODE_MANFAAT_ELIGIBILITY'].'" name="BTN_DEL_'.$data['KODE_MANFAAT_ELIGIBILITY'].'" kode_manfaat="'.$data['KODE_MANFAAT'].'" kode_tipe_penerima="'.$data['KODE_TIPE_PENERIMA'].'"  title="Pilih untuk hapus detil manfaat eligbility" value="Hapus" onclick="hapus(\''.$data['KODE_MANFAAT'].'\',\''.$data['KODE_TIPE_PENERIMA'].'\')">
		';
		    $jsondata .= json_encode($data);
		    $jsondata .= ',';
		    $i++;
		}
		$jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
		$jsondata .= ']}';
		$jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
		echo $jsondata;
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
	}

	
} else {
	echo '{"ret":-1,"msg":"Proses gagal, tidak type yang dipilih!"}';
}


?>