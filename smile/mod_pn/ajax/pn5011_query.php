<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE				= $_POST["TYPE"];
$KEYWORD 		= $_POST["KEYWORD"];
$TYPE2			= $_POST["TYPE2"];
$KEYWORD2A 	= $_POST["KEYWORD2A"];
$KEYWORD2B 	= $_POST["KEYWORD2B"];
$KEYWORD2C 	= $_POST["KEYWORD2C"];
$KEYWORD2D 	= $_POST["KEYWORD2D"];
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$USER 			= $_SESSION["USER"];

if($TYPE!=''){

  function handleError($errno, $errstr,$error_file,$error_line) 
	{
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
  
  $draw = 1;
  if(isset($_POST['draw']))
	{
    $draw = $_POST['draw']; 
  }else{
    $draw = 1;       
  }
  
  $start  = $_POST['start']+1;
  $length = $_POST['length'];
  $end    = ($start-1) + $length;
  
  $search = $_POST['search'];
  
  $condition ="";

  $order = $_POST["order"];
  $by 	 = $order[0]['dir'];
  
  $sql = "";

  if($order[0]['column']=='0')
	{
      $order = "ORDER BY A.KODE_REALISASI ";
  }else if($order[0]['column']=='1'){
      $order = "ORDER BY A.KODE_REALISASI ";
  }else if($order[0]['column']=='2'){
      $order = "ORDER BY TO_CHAR(A.TGL_REALISASI,'YYYYMMDD') ";
  }		
  $order .= $by;
	
	//penanganan untuk filter data -----------------------------------------------				
  if($TYPE != ''){							
  	if (($KEYWORD != '') && ($KEYWORD != 'null')) {
  		if (preg_match("/%/i", $KEYWORD)) {			
  			$condition .= ' AND A.'.$TYPE . " LIKE '".$KEYWORD."'";
  		} else {
  			$condition .= ' AND A.'.$TYPE . " = '".$KEYWORD."'";
  		};
  	}
	}
  if($TYPE2 != ''){
  	if (($KEYWORD2A != '') && ($KEYWORD2A != 'null')) {
  		if (preg_match("/%/i", $KEYWORD2A)) {	
  			$condition .= ' AND A.'.$TYPE2 . " LIKE '".$KEYWORD2A."'";
  		} else {
  			$condition .= ' AND A.'.$TYPE2 . " = '".$KEYWORD2A."'";
  		}
  	}
  	if (($KEYWORD2B != '') && ($KEYWORD2B != 'null')) {
  		if (preg_match("/%/i", $KEYWORD2B)) {	
  			$condition .= ' AND A.'.$TYPE2 . " LIKE '".$KEYWORD2B."'";
  		} else {
  			$condition .= ' AND A.'.$TYPE2 . " = '".$KEYWORD2B."'";
  		}
  	}
  	if (($KEYWORD2C != '') && ($KEYWORD2C != 'null')) {
  		if (preg_match("/%/i", $KEYWORD2C)) {	
  			$condition .= ' AND A.'.$TYPE2 . " LIKE '".$KEYWORD2C."'";
  		} else {
  			$condition .= ' AND A.'.$TYPE2 . " = '".$KEYWORD2C."'";
  		}
  	}
  	if (($KEYWORD2D != '') && ($KEYWORD2D != 'null')) {
  		if (preg_match("/%/i", $KEYWORD2D)) {	
  			$condition .= ' AND A.'.$TYPE2 . " LIKE '".$KEYWORD2D."'";
  		} else {
  			$condition .= ' AND A.'.$TYPE2 . " = '".$KEYWORD2D."'";
  		}
  	}				
  }
		
	//query data -----------------------------------------------------------------						
	$sql = "SELECT * FROM
					(
            SELECT * FROM 
						(
  						select rownum no, 
                    a.kode_realisasi, a.kode_kantor, to_char(a.tgl_realisasi,'dd/mm/yyyy') tgl_realisasi, 
                    a.kategori_pelaksana, 
										(select nama_tipe_penerima from sijstk.pn_kode_tipe_penerima where kode_tipe_penerima = a.kategori_pelaksana) nama_kategori_pelaksana, 
										a.nama_pelaksana, a.alamat_pelaksana, 
                    a.email_pelaksana, a.pic_pelaksana, a.no_telp_pic_pelaksana, 
                    a.kode_kegiatan, a.kode_sub_kegiatan, a.nama_detil_kegiatan, 
                    a.nom_diajukan, a.nom_disetujui, a.keterangan, 
                    a.kode_promotif, a.kode_segmen, a.kode_klaim, 
                    a.status_batal, a.tgl_batal, a.petugas_batal, 
                    a.tgl_rekam, a.petugas_rekam,
										(select status_klaim from sijstk.pn_klaim where kode_klaim = a.kode_klaim) status_klaim
            	from sijstk.pn_promotif_realisasi a
              where nvl(a.status_batal,'T')='T'              
              and a.kode_kantor in
              (   select kode_kantor from sijstk.ms_kantor
                  start with kode_kantor = '$KD_KANTOR' connect by prior kode_kantor = kode_kantor_induk
              		)
						) A WHERE 1=1 ".$condition." ".$order."
					) A WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end;			

	$queryTotalRows = "select count(1) from sijstk.pn_promotif_realisasi a
                     where nvl(a.status_batal,'T')='T'                     
										 and a.kode_kantor in
                     (   select kode_kantor from sijstk.ms_kantor
                         start with kode_kantor = '$KD_KANTOR' connect by prior kode_kantor = kode_kantor_induk
                         ) ".$condition;
  $recordsTotal = $DB->get_data($queryTotalRows);      
  $recordsTotal = $recordsTotal;

  $DB->parse($sql);
  if($DB->execute())
	{ 
    $i = 0;
    while($data = $DB->nextrow())
    {				
				//$data['NOM_TAGIHAN'] ="Rp. ". number_format($data['NOM_TAGIHAN'],2,",",".");
				$data['ACTION'] = '<input type="checkbox" id="CHECK_'.$i.'" urut="'.$i.'" KODE="'.$data['KODE_REALISASI'].'" KODE2="'.$data['KODE_REALISASI'].'" name="CHECK['.$i.']"> <input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_REALISASI'].'">';
        $jsondata .= json_encode($data);
        $jsondata .= ',';
        $i++;
    }
    $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
    $jsondata .= ']}';
    $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
    echo $jsondata;
  } else 
	{
     echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
  }		
}
?>