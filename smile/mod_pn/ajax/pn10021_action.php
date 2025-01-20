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
$ls_kode_manfaat 		= $_POST["DATAID"];
$ls_kode_detil_manfaat	= $_POST["kode_detil_manfaat"];
$ls_nama_detil_manfaat 	= $_POST["nama_detil_manfaat"];
$ls_kode_tarif_biaya 	= $_POST["kode_tarif_biaya"];
$ls_kode_tarif_jml_item	= $_POST["kode_tarif_jml_item"];
$ls_default_jml_item	= $_POST["default_jml_item"];
$ls_status_nonaktif  	= $_POST["status_nonaktif"];
$ls_keterangan 			= $_POST["keterangan_detil_manfaat"];
//edit
$ls_kode_manfaat_edit 		 = $_POST["DATAID"];
$ls_dataid_detil_manfaat_edit= $_POST["DATAID_DTL"];
$ls_kode_detil_manfaat_edit	 = $_POST["kode_detil_manfaat_edit"];
$ls_nama_detil_manfaat_edit  = $_POST["nama_detil_manfaat_edit"];
$ls_kode_tarif_biaya_edit 	 = $_POST["kode_tarif_biaya_edit"];
$ls_kode_tarif_jml_item_edit = $_POST["kode_tarif_jml_item_edit"];
$ls_default_jml_item_edit	 = $_POST["default_jml_item_edit"];
$ls_status_nonaktif_edit  	 = $_POST["status_nonaktif_edit"];
$ls_keterangan_edit		 	 = $_POST["keterangan_detil_manfaat_edit"];
if($ls_status_nonaktif_edit == 'Y'){
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
	$sql = "insert into sijstk.pn_kode_manfaat_detil (
				kode_manfaat,
                kode_manfaat_detil,
                nama_manfaat_detil,
                kode_tarif_biayasatuan,
                kode_tarif_jmlitem,
                default_jmlitem,
                status_nonaktif,
                tgl_nonaktif,
                petugas_nonaktif,
                keterangan,
                tgl_rekam,
                petugas_rekam)
			values(
				'$ls_kode_manfaat',
				'$ls_kode_detil_manfaat',
			    '$ls_nama_detil_manfaat',
			    '$ls_kode_tarif_biaya',
			    '$ls_kode_tarif_jml_item',
			    $ls_default_jml_item,
			    '$ls_status_nonaktif',
			    to_date('$ls_tgl_nonaktif','dd/mm/yyyy'),
			    '$ls_petugas_nonaktif',
			    '$ls_keterangan',
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
	$sql = "update sijstk.pn_kode_manfaat_detil
			   set kode_manfaat_detil = '$ls_kode_detil_manfaat_edit',
			       nama_manfaat_detil = '$ls_nama_detil_manfaat_edit',
			       kode_tarif_biayasatuan = '$ls_kode_tarif_biaya_edit',
			       kode_tarif_jmlitem = '$ls_kode_tarif_jml_item_edit',
			       default_jmlitem = '$ls_default_jml_item_edit',
			       petugas_nonaktif = '$ls_petugas_nonaktif',
			       tgl_nonaktif = to_date('$ls_tgl_nonaktif','dd/mm/yyyy'),
			       status_nonaktif = '$ls_status_nonaktif_edit',
			       keterangan = '$ls_keterangan_edit',
			       tgl_ubah = sysdate,
			       petugas_ubah = '$ls_user'
			 where kode_manfaat_detil = '$ls_dataid_detil_manfaat_edit'";
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil diupdate!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}

}else if($ls_type=="view"){
	$sql="select kode_manfaat,
                kode_manfaat_detil,
                nama_manfaat_detil,
                kode_tarif_biayasatuan,
                kode_tarif_jmlitem,
                default_jmlitem,
                status_nonaktif,
                tgl_nonaktif,
                petugas_nonaktif,
                keterangan,
                tgl_rekam,
                petugas_rekam
		  from sijstk.pn_kode_manfaat_detil
		 where kode_manfaat_detil='".$ls_dataid_detil_manfaat_edit."'";
    $DB->parse($sql);
    if($DB->execute()){ 
		$querySql = $DB->get_row_data($sql);
		echo json_encode($querySql);
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}
	
	
}else if($ls_type=="delete"){
	$sql="delete from sijstk.pn_kode_manfaat_detil
      		where kode_manfaat_detil ='".$ls_dataid_detil_manfaat_edit."' and kode_manfaat='".$ls_kode_manfaat_edit."'";
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
	    $order = "ORDER BY KODE_MANFAAT_DETIL";
	}else if($order[0]['column']=='2'){
	    $order = "ORDER BY NAMA_MANFAAT_DETIL";
	}
	$order .= $by;

	$sql = "";
	$sql="select * from (select rownum no, 
			   initcap(kode_manfaat_detil) as kode_manfaat_detil,
		       initcap(nama_manfaat_detil) as nama_manfaat_detil,
		       status_nonaktif as aktif,
		       keterangan
		  from sijstk.pn_kode_manfaat_detil
		  where kode_manfaat = '".$kode_manfaat."' and 1 = 1 ".$condition." ".$order." )
		  where no between ".$start." and ".$end;

	$queryTotal = "SELECT COUNT (1) FROM SIJSTK.PN_KODE_MANFAAT_DETIL WHERE KODE_MANFAAT = '".$kode_manfaat."' AND 1=1 ".$condition;
	// print_r($sql);
	// die;

	$recordsTotal = $DB->get_data($queryTotal);      

	$DB->parse($sql);
	if($DB->execute()){ 
		$i = 0;
		while($data = $DB->nextrow())
		{
		    $data['ACTION'] = '<input type="button" class="btn green" id="BTN_EDT_'.$data['KODE_MANFAAT_DETIL'].'" name="BTN_EDT_'.$data['KODE_MANFAAT_DETIL'].'" kode_manfaat_detil="'.$data['KODE_MANFAAT_DETIL'].'"  title="Pilih untuk ubah detil manfaat" value="Ubah" onclick="edit(\''.$data['KODE_MANFAAT_DETIL'].'\')">&nbsp;|&nbsp;<input type="button" class="btn green" id="BTN_DEL_'.$data['KODE_MANFAAT_DETIL'].'" name="BTN_DEL_'.$data['KODE_MANFAAT_DETIL'].'" kode_manfaat_detil="'.$data['KODE_MANFAAT_DETIL'].'"  title="Pilih untuk hapus detil manfaat" value="Hapus" onclick="hapus(\''.$data['KODE_MANFAAT_DETIL'].'\')">
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