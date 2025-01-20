<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$mid = $_REQUEST["mid"];

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
      $order = "ORDER BY A.KODE_KLAIM ";
  }else if($order[0]['column']=='1'){
      $order = "ORDER BY A.TGLKLAIM ";
  }else if($order[0]['column']=='2'){
      $order = "ORDER BY A.KODE_KANTOR ";
  }else if($order[0]['column']=='3'){
      $order = "ORDER BY A.TGLBAYAR ";			
  }else if($order[0]['column']=='4'){
      $order = "ORDER BY A.KPJ ";
  }else if($order[0]['column']=='5'){
      $order = "ORDER BY A.NAMA_PENGAMBIL_KLAIM ";
  }else if($order[0]['column']=='6'){
      $order = "ORDER BY A.KET_TIPE_KLAIM ";
  }else if($order[0]['column']=='7'){
      $order = "ORDER BY A.KODE_SEGMEN ";
  }else if($order[0]['column']=='8'){
      $order = "ORDER BY A.NAMA_PENERIMA ";
  }else if($order[0]['column']=='9'){
      $order = "ORDER BY A.NOM_PEMBAYARAN ";
  }else if($order[0]['column']=='10'){
      $order = "ORDER BY A.KODE_BUKU ";
  }else if($order[0]['column']=='11'){
      $order = "ORDER BY A.KD_PRG ";
  }else if($order[0]['column']=='12'){
      $order = "ORDER BY A.FLAG_SENTRALISASI ";
  }else if($order[0]['column']=='13'){
      $order = "ORDER BY A.STATUS_BATAL ";												
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
    // query UNION untuk menampilkan data JP	
	$sql = "SELECT * FROM
					(
            SELECT rownum no, A.* FROM 
						(
              select 
                  a.kode_pembayaran, a.kode_klaim, c.kode_segmen, to_char(c.tgl_klaim,'dd/mm/yyyy') tgl_klaim, to_char(c.tgl_klaim,'yyyymmdd') tglklaim, 
                  (select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = c.kode_tipe_klaim)||' '||c.kode_pointer_asal ket_tipe_klaim,
                  c.kpj,decode(nvl(c.kode_pointer_asal,'x'),'PROMOTIF',c.nama_pelaksana_kegiatan, (decode(c.kode_segmen,'JAKON',c.kode_proyek,c.nama_tk))) nama_pengambil_klaim,
                  a.kode_tipe_penerima,b.nama_penerima, a.kd_prg, (select nm_prg from sijstk.ms_prg where kd_prg = a.kd_prg) nm_prg,
                  a.nom_manfaat_gross, a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.nom_pembayaran, a.kode_kantor, 
									to_char(a.tgl_pembayaran,'dd/mm/yyyy') tgl_pembayaran, to_char(a.tgl_pembayaran,'yyyymmdd') tglpembayaran,
                  a.kode_cara_bayar, a.kode_bank, (select nama_bank from sijstk.ms_bank where kode_bank = a.kode_bank) nama_bank, a.kode_buku, 
									nvl(a.status_batal,'T') status_batal, nvl(a.flag_sentralisasi,'T') flag_sentralisasi
              from sijstk.pn_klaim_pembayaran a, sijstk.pn_klaim_penerima_manfaat b, sijstk.pn_klaim c
              where a.kode_klaim = b.kode_klaim and a.kode_tipe_penerima = b.kode_tipe_penerima
              and a.kode_klaim = c.kode_klaim and a.status_batal<>'Y'
							and a.tgl_pembayaran between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
              $filterkantor
			  union
			  select 
                  a.kode_pembayaran, a.kode_klaim, c.kode_segmen, to_char(c.tgl_klaim,'dd/mm/yyyy') tgl_klaim, to_char(c.tgl_klaim,'yyyymmdd') tglklaim, 
                  (select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = c.kode_tipe_klaim)||' '||c.kode_pointer_asal ket_tipe_klaim,
                  c.kpj,decode(nvl(c.kode_pointer_asal,'x'),'PROMOTIF',c.nama_pelaksana_kegiatan, (decode(c.kode_segmen,'JAKON',c.kode_proyek,c.nama_tk))) nama_pengambil_klaim,
                  decode(kode_penerima_berkala,'TK','TK','AW') kode_tipe_penerima,b.nama_penerima, a.kd_prg, (select nm_prg from sijstk.ms_prg where kd_prg = a.kd_prg) nm_prg,
                  0 nom_manfaat_gross, a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.nom_pembayaran, a.kode_kantor, 
									to_char(a.tgl_pembayaran,'dd/mm/yyyy') tgl_pembayaran, to_char(a.tgl_pembayaran,'yyyymmdd') tglpembayaran,
                  a.kode_cara_bayar, a.kode_bank, (select nama_bank from sijstk.ms_bank where kode_bank = a.kode_bank) nama_bank, a.kode_buku, 
									nvl(a.status_batal,'T') status_batal, 'T' flag_sentralisasi
              from sijstk.pn_klaim_pembayaran_berkala a, sijstk.pn_klaim_penerima_berkala b, sijstk.pn_klaim c
              where a.kode_klaim = b.kode_klaim 
              and a.kode_klaim = c.kode_klaim and a.status_batal<>'Y'
							and a.tgl_pembayaran between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
							and b.no_rekening_penerima is not null
              $filterkantor
						) A WHERE 1=1 ".$condition." ".$order."
					) A 
					WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end;			

	$queryTotalRows = "SELECT count(1) FROM 
										(										
                        select rownum no,
                            a.kode_pembayaran, a.kode_klaim, c.kode_segmen, to_char(c.tgl_klaim,'dd/mm/yyyy') tgl_klaim, to_char(c.tgl_klaim,'yyyymmdd') tglklaim, 
                            (select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = c.kode_tipe_klaim)||' '||c.kode_pointer_asal ket_tipe_klaim,
                            c.kpj,decode(nvl(c.kode_pointer_asal,'x'),'PROMOTIF',c.nama_pelaksana_kegiatan, (decode(c.kode_segmen,'JAKON',c.kode_proyek,c.nama_tk))) nama_pengambil_klaim,
                            a.kode_tipe_penerima,b.nama_penerima, a.kd_prg, (select nm_prg from sijstk.ms_prg where kd_prg = a.kd_prg) nm_prg,
                            a.nom_manfaat_gross, a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.nom_pembayaran, a.kode_kantor, 
          									to_char(a.tgl_pembayaran,'dd/mm/yyyy') tgl_pembayaran, to_char(a.tgl_pembayaran,'yyyymmdd') tglpembayaran,
                            a.kode_cara_bayar, a.kode_bank, (select nama_bank from sijstk.ms_bank where kode_bank = a.kode_bank) nama_bank, a.kode_buku, 
          									nvl(a.status_batal,'T') status_batal, nvl(a.flag_sentralisasi,'T') flag_sentralisasi
                        from sijstk.pn_klaim_pembayaran a, sijstk.pn_klaim_penerima_manfaat b, sijstk.pn_klaim c
                        where a.kode_klaim = b.kode_klaim and a.kode_tipe_penerima = b.kode_tipe_penerima
                        and a.kode_klaim = c.kode_klaim
						and a.tgl_pembayaran between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
                        $filterkantor
						union
						select rownum no,
                            a.kode_pembayaran, a.kode_klaim, c.kode_segmen, to_char(c.tgl_klaim,'dd/mm/yyyy') tgl_klaim, to_char(c.tgl_klaim,'yyyymmdd') tglklaim, 
                            (select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = c.kode_tipe_klaim)||' '||c.kode_pointer_asal ket_tipe_klaim,
                            c.kpj,decode(nvl(c.kode_pointer_asal,'x'),'PROMOTIF',c.nama_pelaksana_kegiatan, (decode(c.kode_segmen,'JAKON',c.kode_proyek,c.nama_tk))) nama_pengambil_klaim,
                            decode(kode_penerima_berkala,'TK','TK','AW') kode_tipe_penerima,b.nama_penerima, a.kd_prg, (select nm_prg from sijstk.ms_prg where kd_prg = a.kd_prg) nm_prg,
                            0 nom_manfaat_gross, a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.nom_pembayaran, a.kode_kantor, 
                                              to_char(a.tgl_pembayaran,'dd/mm/yyyy') tgl_pembayaran, to_char(a.tgl_pembayaran,'yyyymmdd') tglpembayaran,
                            a.kode_cara_bayar, a.kode_bank, (select nama_bank from sijstk.ms_bank where kode_bank = a.kode_bank) nama_bank, a.kode_buku, 
                                              nvl(a.status_batal,'T') status_batal, 'T' flag_sentralisasi
                        from sijstk.pn_klaim_pembayaran_berkala a, sijstk.pn_klaim_penerima_berkala b, sijstk.pn_klaim c
                        where a.kode_klaim = b.kode_klaim 
                        and a.kode_klaim = c.kode_klaim
						and a.tgl_pembayaran between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
						and b.no_rekening_penerima is not null
                        $filterkantor
										) A WHERE 1=1 ".$condition;
  $recordsTotal = $DB->get_data($queryTotalRows);      
  $recordsTotal = $recordsTotal;

  $DB->parse($sql);
  if($DB->execute())
	{ 
    $i = 0;
    while($data = $DB->nextrow())
    {				
				$data['ACTION'] = '<input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_PEMBAYARAN'].'"><a href="#" onClick="NewWindow(\'http://'.$HTTP_HOST.'/mod_pn/ajax/pn5029_cetak.php?task=View&root_sender=pn5029.php&sender=pn5029.php&sender_mid='.$mid.'&dataid='.$data['KODE_PEMBAYARAN'].'&kode_pembayaran='.$data['KODE_PEMBAYARAN'].'\',\'PN5029 - CETAK PEMBAYARAN KLAIM\',600,500,\'yes\')"><img src="../../images/printx.png" border="0" style= "height:20px;" alt="Cetak Pembayaran Klaim" align="absmiddle" /> <u><font color="#009999">Cetak</font></u> </a>';
				$data['KODE_KLAIM'] = '<input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_KLAIM'].'"><a href="#" onClick="NewWindow4(\'http://'.$HTTP_HOST.'/mod_pn/ajax/pn5004_pembayaran.php?task=View&root_sender=pn5029.php&sender=pn5029.php&activetab=1&sender_mid='.$mid.'&dataid='.$data['KODE_KLAIM'].'&kode_klaim='.$data['KODE_KLAIM'].'\',\'PN5029 - DAFTAR PEMBAYARAN KLAIM\',1050,630,\'yes\')"> <u><font color="#009999">'.$data['KODE_KLAIM'].'</font></u> </a>';
				$data['NOM_PEMBAYARAN'] = number_format($data['NOM_PEMBAYARAN'],2,",",".");
				$data['FLAG_SENTRALISASI'] = $data['FLAG_SENTRALISASI']=="Y" ? '<img src="../../images/file_apply.gif" alt="Sentralisasi Kantor Pusat"/>' : "";
				$data['STATUS_BATAL'] = $data['STATUS_BATAL']=="Y" ? '<img src="../../images/file_cancel.gif" alt="Batal"/>' : "";
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
     echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}'.$sql;
  }		
}

?>