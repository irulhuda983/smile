<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName); 

$TYPE				= $_POST["TYPE"];
$KEYWORD 		= $_POST["KEYWORD"];
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
      $order = "ORDER BY A.KODE_AGENDA_PERNYATAAN ";
  }else if($order[0]['column']=='1'){
      $order = "ORDER BY A.KODE_AGENDA_PERNYATAAN ";
  }else if($order[0]['column']=='2'){
      $order = "ORDER BY A.NOMOR_IDENTITAS ";
  }else if($order[0]['column']=='3'){
      $order = "ORDER BY A.KPJ ";
  }else if($order[0]['column']=='4'){
      $order = "ORDER BY A.NAMA_TK ";
  }else if($order[0]['column']=='5'){
      $order = "ORDER BY A.KODE_SEGMEN ";
  }else if($order[0]['column']=='6'){
      $order = "ORDER BY A.KODE_KANTOR ";
  }else if($order[0]['column']=='7'){
      $order = "ORDER BY A.STATUS_SUBMIT_PERNYATAAN ";
  }else if($order[0]['column']=='8'){
      $order = "ORDER BY A.STATUS_CETAK_PERNYATAAN ";
  }else if($order[0]['column']=='9'){
      $order = "ORDER BY A.TGL_CETAK_PERNYATAAN ";
  }else if($order[0]['column']=='10'){
      $order = "ORDER BY A.PETUGAS_CETAK_PERNYATAAN ";
  }				
  $order .= $by;
	
	//penanganan untuk filter data -----------------------------------------------				
  if($TYPE != ''){							
  	if (($KEYWORD != '') && ($KEYWORD != 'null')) {
  		if (preg_match("/%/i", $KEYWORD)) {			
  			$condition .= ' AND A.'.$TYPE . " LIKE '".$KEYWORD."' ";
  		} else {
  			//$condition .= ' AND A.'.$TYPE . " = '".$KEYWORD."' ";
				$condition .= ' AND A.'.$TYPE . " LIKE '%".$KEYWORD."%' ";
  		};
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
							SELECT 
							KODE_AGENDA_PERNYATAAN,
							KODE_KANTOR,
							KODE_SEGMEN,
							KODE_PERUSAHAAN,
							KODE_DIVISI,
							KODE_TK,
							NAMA_TK,
							TEMPAT_LAHIR,
							TGL_LAHIR,
							ALAMAT_TK,
							NO_TELEPON_TK,
							KPJ,
							NOMOR_IDENTITAS,
							JENIS_IDENTITAS,
							KETERANGAN_PERNYATAAN,
							TGL_LAPOR,
							STATUS_SUBMIT_PERNYATAAN,
							TGL_SUBMIT_PERNYATAAN,
							PETUGAS_SUBMIT_PERNYATAAN,
							STATUS_CETAK_PERNYATAAN,
							case
								when nvl(a.STATUS_CETAK_PERNYATAAN,'T') = 'Y' then 'SUDAH CETAK'
								ELSE
									'BELUM CETAK'
							end KET_STATUS_CETAK_PERNYATAAN,
							TGL_CETAK_PERNYATAAN,
							PETUGAS_CETAK_PERNYATAAN,
							KODE_KANTOR_CETAK_PERNYATAAN,
							KANAL_PELAYANAN,
							KODE_POINTER_ASAL,
							ID_POINTER_ASAL,
							KETERANGAN,
							TGL_REKAM,
							PETUGAS_REKAM
							FROM PN.PN_AGENDA_PERNYATAAN a
							WHERE trunc(a.tgl_rekam) between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
							$filterkantor
						) A WHERE 1=1 ".$condition." ".$order."
					) A 
					WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end." ";			
	//echo $sql;		exit;
			
	$queryTotalRows = "SELECT count(1) FROM 
										(										
                        select rownum no, 
                        KODE_AGENDA_PERNYATAAN,
						KODE_KANTOR,
						KODE_SEGMEN,
						KODE_PERUSAHAAN,
						KODE_DIVISI,
						KODE_TK,
						NAMA_TK,
						TEMPAT_LAHIR,
						TGL_LAHIR,
						ALAMAT_TK,
						NO_TELEPON_TK,
						KPJ,
						NOMOR_IDENTITAS,
						JENIS_IDENTITAS,
						KETERANGAN_PERNYATAAN,
						TGL_LAPOR,
						STATUS_SUBMIT_PERNYATAAN,
						TGL_SUBMIT_PERNYATAAN,
						PETUGAS_SUBMIT_PERNYATAAN,
						STATUS_CETAK_PERNYATAAN,
						case
							when nvl(a.STATUS_CETAK_PERNYATAAN,'T') = 'Y' then 'SUDAH CETAK'
							ELSE
								'BELUM CETAK'
						end KET_STATUS_CETAK_PERNYATAAN,
						TGL_CETAK_PERNYATAAN,
						PETUGAS_CETAK_PERNYATAAN,
						KODE_KANTOR_CETAK_PERNYATAAN,
						KANAL_PELAYANAN,
						KODE_POINTER_ASAL,
						ID_POINTER_ASAL,
						KETERANGAN,
						TGL_REKAM,
						PETUGAS_REKAM
						FROM PN.PN_AGENDA_PERNYATAAN a
						WHERE trunc(a.tgl_rekam) between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
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
        $data['ACTION'] = '<input type="checkbox" id="CHECK_'.$i.'" urut="'.$i.'" KODE="'.$data['KODE_AGENDA_PERNYATAAN'].'" KODE2="'.$data['KODE_AGENDA_PERNYATAAN'].'" name="CHECK['.$i.']"> <input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_AGENDA_PERNYATAAN'].'">';
		if($data['STATUS_SUBMIT_PERNYATAAN'] == "Y")
		{
			$data['SURAT_PERNYATAAN'] = '<input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_TK'].'"><a href="#" onClick="NewWindow(\'http://'.$HTTP_HOST.'/mod_pn/ajax/pn5058_cetak.php?task=View&&subact=View&root_sender=pn5058.php&sender=pn5058.php&sender_mid='.$mid.'&kode_tk='.$data['KODE_TK'].'&kode_agenda_pernyataan='.$data['KODE_AGENDA_PERNYATAAN'].'&kode_kantor='.$data['KODE_KANTOR'].'&kode_segmen='.$data['KODE_SEGMEN'].'\',\'PN5058 - SURAT PERNYATAAN\',600,400,\'yes\')"><img src="../../images/printx.png" border="0" style= "height:20px;" alt="Cetak Pembayaran Klaim" align="absmiddle" /> <u><font color="#009999">Cetak</font></u> </a>';	
		}
		else
		{
			$data['SURAT_PERNYATAAN'] = "-";
		}
				//$data['NOM_TAGIHAN'] ="Rp. ". number_format($data['NOM_TAGIHAN'],2,",",".");
        $data['KODE_AGENDA_PERNYATAAN'] = '<a href="#" onClick="window.location.replace(\'http://'.$HTTP_HOST.'/mod_pn/form/pn5058.php?task=View&dataid='.$data['KODE_AGENDA_PERNYATAAN'].'&subact=View&kode_klaim='.$data['KODE_AGENDA_PERNYATAAN'].'\',\'PN5058 - Agenda Cetak Pernyataan\')"><font color="#009999">'.$data['KODE_AGENDA_PERNYATAAN'].'</font> </a>';
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