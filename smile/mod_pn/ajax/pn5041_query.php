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
$KATEGORI					= $_POST["KATEGORI"];

$sql = "SELECT KODE_TIPE FROM MS.MS_KANTOR WHERE KODE_KANTOR = '$KD_KANTOR'";	
$DB->parse($sql);
$DB->execute();
$data = $DB->nextrow();
$ls_kode_tipe_kantor = (integer)$data['KODE_TIPE'];
$ls_nama_tipe_kantor = '';

if($ls_kode_tipe_kantor < 3 && $KEYWORD == '' && $KEYWORD2A == '' && $KEYWORD2B == '' && $KEYWORD2C == '' && $KEYWORD2D == ''){
	$TYPE = '';
}


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
      //$order = "ORDER BY A.KODE_KLAIM ";
	  $order = "";

	  if ($KATEGORI=="1")
		{
			$order = "ORDER BY A.TGLKLAIM ";
		}
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
  
	if($order != "")
	{	
	  $order .= $by;
	}	
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
  	// if (($KEYWORD2D != '') && ($KEYWORD2D != 'null')) {
  	// 	if (preg_match("/%/i", $KEYWORD2D)) {	
  	// 		$condition .= ' AND A.'.$TYPE2 . " LIKE '".$KEYWORD2D."'";
  	// 	} else {
  	// 		$condition .= ' AND A.'.$TYPE2 . " = '".$KEYWORD2D."'";
  	// 	}
	//   }
	if (($KEYWORD2D != '') && ($KEYWORD2D != 'null')) {
		$filter_layanan = "";
		if ($KEYWORD2D == "sc_all") {
			$filter_layanan = " and 1=1";
		}
		if ($KEYWORD2D == "sc_manual") {
			$filter_layanan = " and nvl(a.kanal_pelayanan,'0') not in ('24','25', '26', '27', '28', '29')";
		}
		if ($KEYWORD2D == "sc_bpjstku") {
		$filter_layanan = " and a.kanal_pelayanan in ('25')";
		}
		if ($KEYWORD2D == "sc_online") {
		$filter_layanan = " and a.kanal_pelayanan in ('26','27')";
		}
		if ($KEYWORD2D == "sc_onsite_wa") {
		$filter_layanan = " and a.kanal_pelayanan in ('28')";
		}
		if ($KEYWORD2D == "sc_antol") {
		$filter_layanan = " and a.kanal_pelayanan in ('24')";
		}
		if ($KEYWORD2D == "sc_onsite_web") {
		$filter_layanan = " and a.kanal_pelayanan in ('29')";
		}
		if ($KEYWORD2D == "sc_siap_kerja") {
			$filter_layanan = " and a.kanal_pelayanan in ('41')";
		}
		if ($KEYWORD2D == "sc_plkk") {
			$filter_layanan = " and a.kanal_pelayanan = 'KANAL03' ";
		}
		if ($KEYWORD2D == "sc_eklaim_pmi") {
			$filter_layanan = " and a.kanal_pelayanan in ('44')";
		}
		if ($KEYWORD2D == "sc_sipp") {
			$filter_layanan = " and a.kanal_pelayanan in ('11')";
		}
		if ($KEYWORD2D == "sc_onsite_jmo") {
			$filter_layanan = " and a.kanal_pelayanan in ('58')";
		}
		if ($KEYWORD2D == "sc_eklaim_jmo") {
			$filter_layanan = " and a.kanal_pelayanan in ('65')";
		}
	}				  				
  }

	//filter kantor --------------------------------------------------------------
	if (strlen($gs_kantor_aktif)==3) 
	{
	 	 $filterkantor = "and a.kode_kantor = '$KD_KANTOR' "; 
	}else
	{
		$sql_kantor = "select kode_kantor from ms.ms_kantor start with kode_kantor = '{$KD_KANTOR}' connect by prior kode_kantor = kode_kantor_induk ";
		$DB->parse($sql_kantor);
		$DB->execute();
		
		$listKantor = "";
		if($DB->execute()){ 
			$i = 0;
			
			while($data = $DB->nextrow())
			{
				$listKantor = $listKantor."'{$data['KODE_KANTOR']}',";
				$i++;
			}
			$listKantor = substr($listKantor, 0, -1);
		} 
		
		$filterkantor .= "and a.kode_kantor in ({$listKantor})";

	 	//  $filterkantor = "and a.kode_kantor in ".
		//  							 	 "(	select kode_kantor from sijstk.ms_kantor ".
		// 								 "	start with kode_kantor = '$KD_KANTOR' ".
		// 								 "	connect by prior kode_kantor = kode_kantor_induk ".
		// 								 "	) ";
	}

	if ($KATEGORI=="1")
	{
	 	 $ls_filter_tipeklaim = "and a.kode_tipe_klaim like 'JHT%' ";
	}elseif ($KATEGORI=="2")
	{
	 	 $ls_filter_tipeklaim = "and a.kode_tipe_klaim like 'JHM%' ";
	}elseif ($KATEGORI=="3")
	{
	 	 $ls_filter_tipeklaim = "and a.kode_tipe_klaim like 'JKK%' ";
	}elseif ($KATEGORI=="4")
	{
	 	 $ls_filter_tipeklaim = "and a.kode_tipe_klaim like 'JKM%' ";
	}elseif ($KATEGORI=="5")
	{
	 	 $ls_filter_tipeklaim = "and a.kode_tipe_klaim like 'JPN%' ";
	}elseif ($KATEGORI=="7")
	{
	 	 $ls_filter_tipeklaim = "and a.kode_tipe_klaim like 'JKP%' ";
	}
					
	//query data -----------------------------------------------------------------	
	if ($KATEGORI=="1") 
	{
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
									a.kode_segmen, a.kode_kantor, a.status_klaim, a.kode_tipe_klaim, a.kode_sebab_klaim,a.kode_pointer_asal, a.id_pointer_asal,
									'mod_pn/ajax/pn5041_penetapan.php' path_form_penetapan,
									case
										when a.kanal_pelayanan in ('24') and a.tgl_rekam < '03-AUG-2020' then 'ANTOL'
										when a.kanal_pelayanan in ('24') and a.tgl_rekam >= '03-AUG-2020' then 'ANTOL'
										when a.kanal_pelayanan in ('25') then 'BPJSTKU'
										when a.kanal_pelayanan in ('26','27') then 'ONLINE'
										when a.kanal_pelayanan in ('28') then 'ONSITE WA'
										when a.kanal_pelayanan in ('29') then 'ONSITE WEB'
										when a.kanal_pelayanan in ('41') then 'SIAP KERJA'
										when a.kanal_pelayanan in ('KANAL03') then 'PLKK'
										when a.kanal_pelayanan in ('44') then 'EKLAIM PMI'
										when a.kanal_pelayanan in ('58') then 'ONLINE JMO' 
										when a.kanal_pelayanan in ('65') then 'EKLAIM PMI JMO' 
										when a.kanal_pelayanan in ('67') then 'ANTRIAN ONSITE'
										when a.kanal_pelayanan in ('68') then 'ANTRIAN ONSITE JMO'
									else
										'MANUAL'
									end jht_kanal_pelayanan,
									(select NOM_MANFAAT_NETTO from PN.PN_KLAIM_MANFAAT where kode_klaim=a.kode_klaim) jht_nominal_klaim,
									(select KETERANGAN from PN.PN_KLAIM_MANFAAT_DETIL  where kode_klaim=a.kode_klaim and rownum=1) jht_catatan_petugas								
              from sijstk.pn_klaim a
              where nvl(a.status_batal,'T')='T'
              and a.status_klaim in ('PENGAJUAN_TAHAP_I','PENETAPAN')
							and a.kode_klaim_induk is null
							and nvl(a.kanal_pelayanan,'0') <> '40'    
              				$filterkantor
              				$filter_layanan
							$ls_filter_tipeklaim
						) A WHERE 1=1 ".$condition." ".$order."
					) A 
					WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end;		
	} else {
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
									a.kode_segmen, a.kode_kantor, a.status_klaim, a.kode_tipe_klaim, a.kode_sebab_klaim,a.kode_pointer_asal, a.id_pointer_asal,
									'mod_pn/ajax/pn5041_penetapan.php' path_form_penetapan,
									case
										when a.kanal_pelayanan in ('24') and a.tgl_rekam < '03-AUG-2020' then 'ANTOL'
										when a.kanal_pelayanan in ('24') and a.tgl_rekam >= '03-AUG-2020' then 'ANTOL'
										when a.kanal_pelayanan in ('25') then 'BPJSTKU'
										when a.kanal_pelayanan in ('26','27') then 'ONLINE'
										when a.kanal_pelayanan in ('28') then 'ONSITE WA'
										when a.kanal_pelayanan in ('29') then 'ONSITE WEB'
										when a.kanal_pelayanan in ('41') then 'SIAP KERJA'
										when a.kanal_pelayanan in ('KANAL03') then 'PLKK'
										when a.kanal_pelayanan in ('44') then 'EKLAIM PMI'
										when a.kanal_pelayanan in ('11') then 'SIPP'
										when a.kanal_pelayanan in ('58') then 'ONLINE JMO' 
										when a.kanal_pelayanan in ('65') then 'EKLAIM PMI JMO' 
									else
										'MANUAL'
									end kanal_pelayanan
              from sijstk.pn_klaim a
              where nvl(a.status_batal,'T')='T'
              and a.status_klaim in ('PENGAJUAN_TAHAP_I','PENETAPAN')
							and a.kode_klaim_induk is null
							and nvl(a.kanal_pelayanan,'0') <> '40'    
              				$filterkantor
						$filter_layanan
							$ls_filter_tipeklaim
						) A WHERE 1=1 ".$condition." ".$order."
					) A 
					WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end;		
	}

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
                        where nvl(a.status_batal,'T')='T'
                        and a.status_klaim in ('PENGAJUAN_TAHAP_I','PENETAPAN')
												and a.kode_klaim_induk is null
												and nvl(a.kanal_pelayanan,'0') <> '40'    
                        $filterkantor
						$filter_layanan
												$ls_filter_tipeklaim
										) A WHERE 1=1 ".$condition;
  $recordsTotal = $DB->get_data($queryTotalRows);

  $DB->parse($sql);
  if($DB->execute())
	{ 
    $i = 0;
    while($data = $DB->nextrow())
    {				
				$data['ACTION'] = '<input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_KLAIM'].'"><a href="#" onClick="window.location.replace(\'http://'.$HTTP_HOST.'/'.$data['PATH_FORM_PENETAPAN'].'?task=Edit&root_sender=pn5041.php&sender=pn5041.php&activetab=2&sender_mid='.$mid.'&rg_kategori='.$KATEGORI.'&dataid='.$data['KODE_KLAIM'].'&kode_klaim='.$data['KODE_KLAIM'].'\',\'PN5002 - PENGAJUAN DAN PENETAPAN KLAIM\')"><img src="../../images/user_go.png" border="0" alt="Penetapan Klaim" align="absmiddle" /> <u><font color="#009999">'.$data['STATUS_KLAIM'].'</font></u> </a>';
				if($data['JHT_NOMINAL_KLAIM']) $data['JHT_NOMINAL_KLAIM'] = number_format($data['JHT_NOMINAL_KLAIM']);
				if($data['JHT_CATATAN_PETUGAS']) $data['JHT_CATATAN_PETUGAS'] = '<a href="#" onClick="alert(\''. $data['JHT_CATATAN_PETUGAS']. '\')">Lihat</a>';
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
} else {
	$jsondata = '';
	$jsondataStart = '{"draw":"","recordsTotal":"0","recordsFiltered":"0","data":[';
	$jsondata .= ']}';
	$jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
	echo $jsondata;
}
?>
