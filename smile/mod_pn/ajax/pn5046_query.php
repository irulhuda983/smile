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
$TGLAWALDISPLAY		= $_POST["TGLAWALDISPLAY"];
$TGLAKHIRDISPLAY	= $_POST["TGLAKHIRDISPLAY"];

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
      $order = "ORDER BY A.KODE_KLAIM ";
  }else if($order[0]['column']=='1'){
      $order = "ORDER BY A.KODE_KLAIM ";
  }else if($order[0]['column']=='2'){
      $order = "ORDER BY A.TGLKLAIM ";
  }else if($order[0]['column']=='3'){
      $order = "ORDER BY A.KPJ ";
  }else if($order[0]['column']=='4'){
      $order = "ORDER BY A.NAMA_PENGAMBIL_KLAIM ";
  }else if($order[0]['column']=='5'){
      $order = "ORDER BY A.KET_TIPE_KLAIM ";
  }else if($order[0]['column']=='6'){
      $order = "ORDER BY A.KODE_SEGMEN ";
  }else if($order[0]['column']=='7'){
      $order = "ORDER BY A.KODE_KANTOR ";
  }else if($order[0]['column']=='8'){
      $order = "ORDER BY A.STATUS_KLAIM ";
  }				
  $order .= $by;
	
	//penanganan untuk filter data -----------------------------------------------				
  if($TYPE != ''){							
  	if (($KEYWORD != '') && ($KEYWORD != 'null')) {
  		if (preg_match("/%/i", $KEYWORD)) {			
  			$condition .= ' AND A.'.$TYPE . " LIKE '".$KEYWORD."' ";
  		} else {
  			$condition .= ' AND A.'.$TYPE . " = '".$KEYWORD."' ";
  		};
  	}
	}
  if($TYPE2 != ''){
  	if (($KEYWORD2A != '') && ($KEYWORD2A != 'null')) {
  		if (preg_match("/%/i", $KEYWORD2A)) {	
  			$condition .= ' AND A.'.$TYPE2 . " LIKE '".$KEYWORD2A."' ";
  		} else {
  			$condition .= ' AND A.'.$TYPE2 . " = '".$KEYWORD2A."' ";
  		}
  	}
  	if (($KEYWORD2B != '') && ($KEYWORD2B != 'null')) {
  		if (preg_match("/%/i", $KEYWORD2B)) {	
  			$condition .= ' AND A.'.$TYPE2 . " LIKE '".$KEYWORD2B."' ";
  		} else {
  			$condition .= ' AND A.'.$TYPE2 . " = '".$KEYWORD2B."' ";
  		}
  	}
  	if (($KEYWORD2C != '') && ($KEYWORD2C != 'null')) {
  		if (preg_match("/%/i", $KEYWORD2C)) {	
  			$condition .= ' AND A.'.$TYPE2 . " LIKE '".$KEYWORD2C."' ";
  		} else {
  			$condition .= ' AND A.'.$TYPE2 . " = '".$KEYWORD2C."' ";
  		}
  	}
  	if (($KEYWORD2D != '') && ($KEYWORD2D != 'null')) {
  		if (preg_match("/%/i", $KEYWORD2D)) {	
  			$condition .= ' AND A.'.$TYPE2 . " LIKE '".$KEYWORD2D."' ";
  		} else {
  			$condition .= ' AND A.'.$TYPE2 . " = '".$KEYWORD2D."' ";
  		}
  	}				
  }

	//filter kantor --------------------------------------------------------------
	if (strlen($gs_kantor_aktif)==3) 
	{
	 	 $filterkantor = "and a.kode_kantor = '$KD_KANTOR' "; 
	}else
	{
	 	 $filterkantor = "and a.kode_kantor in ".
		 							 	 "(	select kode_kantor from sijstk.ms_kantor ".
										 "	start with kode_kantor = '$KD_KANTOR' ".
										 "	connect by prior kode_kantor = kode_kantor_induk ".
										 "	) ";
	}
			
	//query data -----------------------------------------------------------------						
	$sql = "SELECT * FROM
					(
            SELECT rownum no, A.* FROM 
						(
  						select 
                  a.kode_klaim, to_char(a.tgl_klaim,'yyyymmdd') tglklaim, to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim, 
									a.kpj,
									decode(
												 nvl(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, 
												 (decode(a.kode_segmen,'JAKON',(select no_proyek||'-'||nama_proyek from sijstk.jn_proyek where kode_proyek = a.kode_proyek),a.nama_tk))
									) nama_pengambil_klaim,
									(select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim)||' '||a.kode_pointer_asal ket_tipe_klaim,
									a.kode_segmen, a.kode_kantor, a.status_klaim, a.kode_tipe_klaim, a.kode_sebab_klaim, a.kode_klaim_induk									 							
              from sijstk.pn_klaim a
              where a.tgl_penetapan between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
							and a.kode_klaim_induk is not null and a.status_klaim = 'PENETAPAN' and nvl(a.status_batal,'T')='T'
              $filterkantor
						) A WHERE 1=1 ".$condition." ".$order."
					) A 
					WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end." ";			
	//echo $sql;		
			
	$queryTotalRows = "SELECT count(1) FROM 
										(										
                        select rownum no, 
                          a.kode_klaim, to_char(a.tgl_klaim,'yyyymmdd') tglklaim, to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim, 
                          a.kpj,
        									decode(
        												 nvl(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, 
        												 (decode(a.kode_segmen,'JAKON',(select no_proyek||'-'||nama_proyek from sijstk.jn_proyek where kode_proyek = a.kode_proyek),a.nama_tk))
        									) nama_pengambil_klaim,
                          (select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim)||' '||a.kode_pointer_asal ket_tipe_klaim,
                          a.kode_segmen, a.kode_kantor, a.status_klaim, a.kode_tipe_klaim, a.kode_sebab_klaim									 							
                        from sijstk.pn_klaim a
                        where a.tgl_penetapan between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
												and a.kode_klaim_induk is not null and a.status_klaim = 'PENETAPAN' and nvl(a.status_batal,'T')='T'
                        $filterkantor
										) A WHERE 1=1 ".$condition;
  //echo $queryTotalRows;
	$recordsTotal = $DB->get_data($queryTotalRows);      
  $recordsTotal = $recordsTotal;

  $DB->parse($sql);
  if($DB->execute())
	{ 
    $i = 0;
    while($data = $DB->nextrow())
    {
        $data['ACTION'] = '<input type="checkbox" id="CHECK_'.$i.'" urut="'.$i.'" KODE="'.$data['KODE_KLAIM'].'" KODE2="'.$data['KODE_KLAIM'].'" name="CHECK['.$i.']"> <input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_KLAIM'].'">';
			//$data['NOM_TAGIHAN'] ="Rp. ". number_format($data['NOM_TAGIHAN'],2,",",".");
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