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
$mid 				= $_GET["mid"];
$KATEGORI		= $_POST["KATEGORI"];

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
      $order = "ORDER BY A.KPJ ";
  }else if($order[0]['column']=='3'){
      $order = "ORDER BY A.NAMA_TK ";
  }else if($order[0]['column']=='4'){
      $order = "ORDER BY A.TGLKONFIRMASI ";
  }else if($order[0]['column']=='5'){
      $order = "ORDER BY A.NAMA_PENERIMA_BERKALA ";
  }else if($order[0]['column']=='6'){
      $order = "ORDER BY A.BLTHAWAL ";			
  //}else if($order[0]['column']=='7'){
  //    $order = "ORDER BY A.KODE_SEGMEN ";
  }else if($order[0]['column']=='7'){
      $order = "ORDER BY A.KODE_KANTOR ";
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
	
	$ls_filter_kantor = "";
	if ($KATEGORI=="1")
	{
	 	 $ls_filter_kantor = "and b.kode_kantor = '$KD_KANTOR' ";
	}elseif ($KATEGORI=="2")
	{
	 	 $ls_filter_kantor = "and b.kode_kantor != '$KD_KANTOR' ";
	}
				
	//query data -----------------------------------------------------------------	
	//konfirmasi ulang dapat dilakukan dimana saja tanpa melihat kantor	----------			
	$sql = "SELECT * FROM
					(
            SELECT rownum no,A.* FROM 
						(
						 	select 
                  kode_konfirmasi, kode_klaim, kode_kantor, kode_segmen, kode_tk, kpj, nama_tk, no_konfirmasi, tgl_konfirmasi, tglkonfirmasi,
                  kode_tipe_penerima, kode_penerima_berkala, nama_penerima_berkala,kode_penerima_berkala||'-'||nama_penerima_berkala ket_penerima_berkala,
									jml_bulan, blth_awal, blth_akhir, to_char(blth_awal,'mm-yyyy')||' s/d '||to_char(blth_akhir,'mm-yyyy') ket_periode,
                  blthawal, keterangan
              from
              (   
                  select 
                     a.kode_klaim||'-'||a.no_konfirmasi kode_konfirmasi, a.kode_klaim, b.kode_kantor, b.kode_segmen, b.kode_tk, b.kpj, b.nama_tk, 
                     a.no_konfirmasi, to_char(a.tgl_konfirmasi,'dd/mm/yyyy') tgl_konfirmasi, to_char(a.tgl_konfirmasi,'yyyymmdd') tglkonfirmasi,
                     a.kode_tipe_penerima, a.kode_penerima_berkala, 
                     (    select nama_penerima from sijstk.pn_klaim_penerima_berkala
                          where kode_klaim = a.kode_klaim
                          and kode_penerima_berkala = a.kode_penerima_berkala
                          and rownum = 1
                      ) nama_penerima_berkala,
                     a.jml_bulan, a.blth_awal, a.blth_akhir, to_char(a.blth_awal,'yyyymmdd') blthawal, a.keterangan,
                     rank () over (partition by a.kode_klaim order by a.kode_klaim, a.no_konfirmasi desc) rank 
                  from sijstk.pn_klaim_berkala a, sijstk.pn_klaim b
                  where a.kode_klaim = b.kode_klaim 
									$ls_filter_kantor
                  and a.status_batal = 'T'
                  and a.status_konfirmasi ='Y'
                  and nvl(b.status_batal,'T')='T'
                  and nvl(b.status_approval,'T')='Y'
              ) A
              where rank = 1
						) A WHERE 1=1 ".$condition." ".$order."
					) A 
					WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end;			
	
	//echo $sql;
					
	$queryTotalRows = "SELECT count(1) FROM 
										(										
        						 	select rownum no,
                          kode_konfirmasi, kode_klaim, kode_kantor, kode_segmen, kode_tk, kpj, nama_tk, no_konfirmasi, tgl_konfirmasi, tglkonfirmasi,
                          kode_tipe_penerima, kode_penerima_berkala, nama_penerima_berkala,kode_penerima_berkala||'-'||nama_penerima_berkala ket_penerima_berkala,
        									jml_bulan, blth_awal, blth_akhir, to_char(blth_awal,'mm-yyyy')||' s/d '||to_char(blth_akhir,'mm-yyyy') ket_periode,
                          blthawal, keterangan
                      from
                      (   
                          select 
                             a.kode_klaim||'-'||a.no_konfirmasi kode_konfirmasi, a.kode_klaim, b.kode_kantor, b.kode_segmen, b.kode_tk, b.kpj, b.nama_tk, 
                             a.no_konfirmasi, to_char(a.tgl_konfirmasi,'dd/mm/yyyy') tgl_konfirmasi, to_char(a.tgl_konfirmasi,'yyyymmdd') tglkonfirmasi,
                             a.kode_tipe_penerima, a.kode_penerima_berkala, 
                             (    select nama_penerima from sijstk.pn_klaim_penerima_berkala
                                  where kode_klaim = a.kode_klaim
                                  and kode_penerima_berkala = a.kode_penerima_berkala
                                  and rownum = 1
                              ) nama_penerima_berkala,
                             a.jml_bulan, a.blth_awal, a.blth_akhir, to_char(a.blth_awal,'yyyymmdd') blthawal, a.keterangan,
                             rank () over (partition by a.kode_klaim order by a.kode_klaim, a.no_konfirmasi desc) rank 
                          from sijstk.pn_klaim_berkala a, sijstk.pn_klaim b
                          where a.kode_klaim = b.kode_klaim
													$ls_filter_kantor 
                          and a.status_batal = 'T'
                          and a.status_konfirmasi ='Y'
                          and nvl(b.status_batal,'T')='T'
                          and nvl(b.status_approval,'T')='Y'
                      )
                      where rank = 1
										) A WHERE 1=1 ".$condition;
  $recordsTotal = $DB->get_data($queryTotalRows);      
  $recordsTotal = $recordsTotal;

  $DB->parse($sql);
  if($DB->execute())
	{ 
    $i = 0;
    while($data = $DB->nextrow())
    {				
				//$data['ACTION'] = '<input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_KLAIM'].'"><a href="#" onClick="NewWindow(\'http://'.$HTTP_HOST.'/mod_pn/ajax/pn5045_konfirmasi.php?task=new&root_sender=pn5045.php&sender=pn5045.php&activetab=1&sender_mid='.$mid.'&dataid='.$data['KODE_KLAIM'].'&kode_klaim='.$data['KODE_KLAIM'].'&no_konfirmasi_induk='.$data['NO_KONFIRMASI'].'\',\'PN5006 - KONFIRMASI BERKALA\',1100,580,\'yes\')"><img src="../../images/user_go.png" border="0" alt="Konfirmasi Berkala" align="absmiddle" /> <u><font color="#009999">Konfirmasi</font></u> </a>';
				//$data['ACTION'] = '<input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_KLAIM'].'"><a href="#" onClick="window.location.replace(\'http://'.$HTTP_HOST.'/mod_pn/ajax/pn5045_konfirmasi.php?task=new&root_sender=pn5045.php&sender=pn5045.php&activetab=1&sender_mid='.$mid.'&dataid='.$data['KODE_KLAIM'].'&kode_klaim='.$data['KODE_KLAIM'].'&no_konfirmasi_induk='.$data['NO_KONFIRMASI'].'\',\'PN5006 - KONFIRMASI BERKALA\',1100,580,\'yes\')"><img src="../../images/user_go.png" border="0" alt="Konfirmasi Berkala" align="absmiddle" /> <u><font color="#009999">Konfirmasi</font></u> </a>';
				$data['ACTION'] = '<a href="#" onClick="window.location.replace(\'http://'.$HTTP_HOST.'/mod_pn/form/pn5045.php?task=New&dataid='.$data['KODE_KLAIM'].'&kode_klaim='.$data['KODE_KLAIM'].'&no_konfirmasi_induk='.$data['NO_KONFIRMASI'].'&rg_kategori='.$KATEGORI.'\',\'PN5006 - Konfirmasi Berkala\')"><img src="../../images/user_go.png" border="0" alt="Konfirmasi Berkala" align="absmiddle" /> <u><font color="#009999">Konfirmasi</font></u> </a>';
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