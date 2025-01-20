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
$ls_kode_pelaporan				= $_POST['v_kode_pelaporan'];
$ls_kode_sebab_klaim			= $_POST['v_kode_sebab_klaim'];
$ls_kode_segmen					= $_POST['v_kode_segmen'];
$ls_kode_jenis_kasus			= $_POST['v_kode_jenis_kasus'];
$ls_kode_lokasi_kecelakaan		= $_POST['v_kode_lokasi_kecelakaan'];
$ls_kode_akibat_diderita				= $_POST['v_kode_akibat_diderita'];
$ls_kode_kondisi_terakhir		= $_POST['v_kode_kondisi_terakhir'];
// $ls_no_urut				= $_POST['v_no_urut'];
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

if($ls_type=="lov_kode_sebab_klaim"){
	$sql="select kode_sebab_klaim, nama_sebab_klaim from sijstk.pn_kode_sebab_klaim";
	// print_r($sql);

	$stmt = $DB->parse($sql);
	if($DB->execute()){ 
		$jsondata = json_encode($DB->result($stmt));
		echo '{"ret":0,"data": '.$jsondata.'}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
	}
}else if($ls_type=="lov_kode_jenis_kasus"){
	$sql="select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus";
	// print_r($sql);

	$stmt = $DB->parse($sql);
	if($DB->execute()){ 
		$jsondata = json_encode($DB->result($stmt));
		echo '{"ret":0,"data": '.$jsondata.'}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
	}
}else if($ls_type=="lov_kode_lokasi_kecelakaan"){
	$sql="select kode_lokasi_kecelakaan, nama_lokasi_kecelakaan from sijstk.pn_kode_lokasi_kecelakaan";
	// print_r($sql);

	$stmt = $DB->parse($sql);
	if($DB->execute()){ 
		$jsondata = json_encode($DB->result($stmt));
		echo '{"ret":0,"data": '.$jsondata.'}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
	}
}else if($ls_type=="lov_kode_akibat_diderita"){
	$sql="select kode_akibat_diderita, nama_akibat_diderita from sijstk.pn_kode_akibat_diderita";
	// print_r($sql);

	$stmt = $DB->parse($sql);
	if($DB->execute()){ 
		$jsondata = json_encode($DB->result($stmt));
		echo '{"ret":0,"data": '.$jsondata.'}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
	}
}else if($ls_type=="lov_kode_kondisi_terakhir"){
	$sql="select kode_kondisi_terakhir, nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir";
	// print_r($sql);

	$stmt = $DB->parse($sql);
	if($DB->execute()){ 
		$jsondata = json_encode($DB->result($stmt));
		echo '{"ret":0,"data": '.$jsondata.'}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
	}
}else if($ls_type=="new"){
	$sql = "insert into pn.pn_kode_pelaporan (
				kode_pelaporan,
                kode_sebab_klaim,
                kode_segmen,
                kode_jenis_kasus,
                kode_lokasi_kecelakaan,
                kode_akibat_diderita,
                kode_kondisi_terakhir,
                status_nonaktif,
                tgl_nonaktif,
                keterangan,
                tgl_rekam,
                petugas_rekam)
			values(
				'$ls_kode_pelaporan',
				'$ls_kode_sebab_klaim',
				'$ls_kode_segmen',
				'$ls_kode_jenis_kasus',
				'$ls_kode_lokasi_kecelakaan',
				'$ls_kode_akibat_diderita',
				'$ls_kode_kondisi_terakhir',
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
	$sql = "update sijstk.pn_kode_pelaporan
			   set kode_pelaporan = '$ls_kode_pelaporan',
			       kode_sebab_klaim = '$ls_kode_sebab_klaim',
			       kode_segmen = '$ls_kode_segmen',
			       kode_jenis_kasus = '$ls_kode_jenis_kasus',
			       kode_lokasi_kecelakaan = '$ls_kode_lokasi_kecelakaan',
			       kode_akibat_diderita = '$ls_kode_akibat_diderita',
			       kode_kondisi_terakhir = '$ls_kode_kondisi_terakhir',
			       status_nonaktif = '$ls_status_nonaktif',
			       keterangan = '$ls_keterangan',
			       tgl_ubah = sysdate,
			       ".$optionalQuery."
			       petugas_ubah = '$ls_user'
			 where kode_pelaporan = '$ls_kode_pelaporan'";
	$DB->parse($sql);
	if($DB->execute()){ 
		echo '{"ret":0,"msg":"Sukses, data berhasil diupdate!"}';
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}

}else if($ls_type=="view"){
	$sql="select kode_pelaporan,
		       kode_sebab_klaim,
		       kode_segmen,
		       kode_jenis_kasus,
		       kode_lokasi_kecelakaan,
		       kode_akibat_diderita,
		       kode_kondisi_terakhir,
		       status_nonaktif,
		       to_char(tgl_nonaktif,'dd/mm/yyyy') tgl_nonaktif,
		       keterangan
		  from sijstk.pn_kode_pelaporan
		 where kode_pelaporan='".$ls_search_keyword."'";
    $DB->parse($sql);
    if($DB->execute()){ 
		$querySql = $DB->get_row_data($sql);
		echo json_encode($querySql);
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, data gagal diupdate!"}';
	}
	
	
}else if($ls_type=="delete"){
	$sql="delete from sijstk.pn_kode_pelaporan
      		where kode_pelaporan ='".$ls_kode_pelaporan."'";
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
	    $order = "ORDER BY KODE_PELAPORAN ";
	}else if($order[0]['column']=='1'){
	    $order = "ORDER BY KODE_PELAPORAN ";
	}else if($order[0]['column']=='2'){
	    $order = "ORDER BY KODE_SEBAB_KLAIM ";
	}else if($order[0]['column']=='3'){
	    $order = "ORDER BY KODE_SEGMEN ";
	}else if($order[0]['column']=='4'){
	    $order = "ORDER BY KODE_JENIS_KASUS ";
	}else if($order[0]['column']=='5'){
	    $order = "ORDER BY KODE_LOKASI_KECELAKAAN ";
	}else if($order[0]['column']=='6'){
	    $order = "ORDER BY KODE_AKIBAT_DIDERITA ";
	}else if($order[0]['column']=='7'){
	    $order = "ORDER BY KODE_KONDISI_TERAKHIR ";
	}
	$order .= $by;

	$sql = "";
	$sql="select * from (SELECT ROWNUM no,
                 a.kode_pelaporan,
                 a.kode_sebab_klaim,
                 a.kode_segmen,
                 a.kode_jenis_kasus || '-' || b.nama_jenis_kasus
                    kode_jenis_kasus,
                 a.kode_lokasi_kecelakaan || '-' || c.nama_lokasi_kecelakaan
                    kode_lokasi_kecelakaan,
                 a.kode_akibat_diderita || '-' || d.nama_akibat_diderita
                    kode_akibat_diderita,
                 a.kode_kondisi_terakhir || '-' || e.nama_kondisi_terakhir
                    kode_kondisi_terakhir,
                 a.keterangan
            FROM sijstk.pn_kode_pelaporan a,
                 sijstk.pn_kode_jenis_kasus b,
                 sijstk.pn_kode_lokasi_kecelakaan c,
                 sijstk.pn_kode_akibat_diderita d,
                 sijstk.pn_kode_kondisi_terakhir e
           WHERE     a.kode_jenis_kasus = b.kode_jenis_kasus
                 AND a.kode_lokasi_kecelakaan = c.kode_lokasi_kecelakaan
                 AND a.kode_akibat_diderita = d.kode_akibat_diderita
                 AND a.kode_kondisi_terakhir = e.kode_kondisi_terakhir
		  ".$condition." ".$order." )
		 where no between ".$start." and ".$end;

	$queryTotal = "SELECT COUNT (1) FROM SIJSTK.PN_KODE_PELAPORAN WHERE 1=1 ".$condition;
	// print_r($sql);

	$recordsTotal = $DB->get_data($queryTotal);      

	$DB->parse($sql);
	if($DB->execute()){ 
		$i = 0;
		while($data = $DB->nextrow())
		{
		    $data['ACTION'] = '<button class="btn green" name="view_pelaporan_'.$data['KODE_PELAPORAN'].'" id="view_pelaporan_'.$data['KODE_PELAPORAN'].'" kode_pelaporan="'.$data['KODE_PELAPORAN'].'"  title="Klik untuk view detil data"><i class="fa fa-search" aria-hidden="true"></i></button>'.' '.' <button class="btn green" name="edit_pelaporan_'.$data['KODE_PELAPORAN'].'" id="edit_pelaporan_'.$data['KODE_PELAPORAN'].'" kode_pelaporan="'.$data['KODE_PELAPORAN'].'"  title="Klik untuk edit data"><i class="fa fa-pencil" aria-hidden="true"></i></button> <button class="btn hapus" name="hapus_pelaporan_'.$data['KODE_PELAPORAN'].'" kode_pelaporan="'.$data['KODE_PELAPORAN'].'" title="Klik untuk hapus data"><i class="fa fa-trash" aria-hidden="true"></i></button>
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

	
}


?>