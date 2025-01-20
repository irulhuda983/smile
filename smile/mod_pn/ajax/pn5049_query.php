<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE				= $_POST["TYPE"];
$KEYWORD 		= $_POST["KEYWORD"];
$TYPE2			= $_POST["TYPE2"];
$TYPE3			= $_POST["TYPE3"];
$KEYWORD2A 	= $_POST["KEYWORD2A"];
$KEYWORD2B 	= $_POST["KEYWORD2B"];
$KEYWORD2C 	= $_POST["KEYWORD2C"];
$KEYWORD2D 	= $_POST["KEYWORD2D"];
$KEYWORD2E 	= $_POST["KEYWORD2E"];
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$USER 			= $_SESSION["USER"];
$TGLAWALDISPLAY		= $_POST["TGLAWALDISPLAY"];
$TGLAKHIRDISPLAY	= $_POST["TGLAKHIRDISPLAY"];
$KATEGORI					= $_POST["KATEGORI"];

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
      $order = "ORDER BY A.TGLTRANS ";
  }else if($order[0]['column']=='2'){
      $order = "ORDER BY A.KPJ ";
  }else if($order[0]['column']=='3'){
      $order = "ORDER BY A.NAMA_PENGAMBIL_KLAIM ";
  }else if($order[0]['column']=='4'){
      $order = "ORDER BY A.KET_TIPE_KLAIM ";
  }else if($order[0]['column']=='5'){
      $order = "ORDER BY A.TGLKEJADIAN ";			
  }else if($order[0]['column']=='6'){
      $order = "ORDER BY A.KODE_SEGMEN ";
  }else if($order[0]['column']=='7'){
      $order = "ORDER BY A.KODE_KANTOR ";
  }else if($order[0]['column']=='8'){
      $order = "ORDER BY A.STATUS_KLAIM ";
  }else if($order[0]['column']=='9'){
      $order = "ORDER BY A.KODE_KLAIM_PERTAMA,A.TGLTRANS ";			
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
	if (($KEYWORD2E != '') && ($KEYWORD2E != 'null')) {
		$filter_layanan = "";
		if ($KEYWORD2E == "sc_all") {
			$filter_layanan = " and 1=1";
		}
		if ($KEYWORD2E == "sc_manual") {
			$filter_layanan = " and a.kanal_pelayanan not in ('24','25','26','27','28', '29')";
		}
		if ($KEYWORD2E == "sc_bpjstku") {
		$filter_layanan = " and a.kanal_pelayanan in ('25')";
		}
		if ($KEYWORD2E == "sc_online") {
		$filter_layanan = " and a.kanal_pelayanan in ('26','27')";
		}
		if ($KEYWORD2E == "sc_onsite_wa") {
		$filter_layanan = " and a.kanal_pelayanan in ('28')";
		}
		if ($KEYWORD2E == "sc_antol") {
		$filter_layanan = " and a.kanal_pelayanan in ('24')";
		}
		if ($KEYWORD2E == "sc_onsite_web") {
		$filter_layanan = " and a.kanal_pelayanan in ('29')";
		}
	}			
	  				
  }

  


	
	if ($TYPE=="KODE_KLAIM_PERTAMA")
	{
    $ls_filter_kantor = ""; //lepas filter kantor
    $ls_filter_tgl_tapawal  = "and a.tgl_klaim between to_date('01/01/1900','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') ";	
    $ls_filter_tgl_tapulang = "and a.tgl_penetapan between to_date('01/01/1900','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') ";														
	}else
	{
  	if ($KATEGORI=="1")
  	{
  	 	 $ls_filter_kantor = "and a.kode_kantor = '$KD_KANTOR' ";
  	}elseif ($KATEGORI=="2")
  	{
  	 	 $ls_filter_kantor = "and a.kode_kantor != '$KD_KANTOR' ";
  	}
	 	$ls_filter_tgl_tapawal  = "and a.tgl_klaim between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') ";	
	 	$ls_filter_tgl_tapulang = "and a.tgl_penetapan between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') ";				
	}
	
	//query data -----------------------------------------------------------------						
	$sql = "SELECT * FROM
					(
            SELECT rownum no, A.* FROM 
						(
  						select kode_klaim, tgltrans, tgl_klaim, tgl_trans, kpj,nama_pengambil_klaim, ket_tipe_klaim,
									kode_segmen, kode_kantor, status_klaim, kode_tipe_klaim, kode_sebab_klaim, tglkejadian, tgl_kejadian, 
									decode(jenis,'PENETAPAN_ULANG',kode_klaim_pertama,kode_klaim) kode_klaim_pertama, 
									decode(jenis,'PENETAPAN_ULANG',kode_klaim_pertama,'-') display_kode_klaim_pertama,
									kanal_pelayanan
							from
							(		
  							select  
                    'PENETAPAN_AWAL' jenis, a.kode_klaim, to_char(a.tgl_klaim,'yyyymmdd') tgltrans, a.tgl_klaim, to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_trans, a.kpj,
  									decode(nvl(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (decode(a.kode_segmen,'JAKON',a.nama_tk,a.nama_tk))) nama_pengambil_klaim,
  									(select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim)||' '||a.kode_pointer_asal ket_tipe_klaim,
  									a.kode_segmen, a.kode_kantor, a.status_klaim, a.kode_tipe_klaim, a.kode_sebab_klaim, 
										to_char(a.tgl_kejadian,'yyyymmdd') tglkejadian, to_char(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
  									'-' kode_klaim_pertama,
									case
										when a.kanal_pelayanan in ('24') and a.tgl_rekam < '03-AUG-2020' then 'ANTOL'
										when a.kanal_pelayanan in ('24') and a.tgl_rekam >= '03-AUG-2020' then 'ANTOL'
										when a.kanal_pelayanan in ('25') then 'BPJSTKU'
										when a.kanal_pelayanan in ('26','27') then 'ONLINE'
										when a.kanal_pelayanan in ('28') then 'ONSITE WA'
										when a.kanal_pelayanan in ('29') then 'ONSITE WEB'
										else
											'MANUAL'
									end kanal_pelayanan
									-- decode(a.kanal_pelayanan,'26','ONLINE','27','ONLINE','28','ONSITE','29','ONSITE','MANUAL') kanal_pelayanan
                from sijstk.pn_klaim a
                where a.kode_klaim_induk is null
  							$ls_filter_tgl_tapawal 
  							$ls_filter_kantor
							$filter_layanan  
  							UNION ALL
  							select  
                    'PENETAPAN_ULANG' jenis, a.kode_klaim, to_char(a.tgl_penetapan,'yyyymmdd') tgltrans, a.tgl_klaim, to_char(a.tgl_penetapan,'dd/mm/yyyy') tgl_trans, a.kpj,
  									decode(nvl(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (decode(a.kode_segmen,'JAKON',a.nama_tk,a.nama_tk))) nama_pengambil_klaim,
  									(select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim)||' '||a.kode_pointer_asal ket_tipe_klaim,
  									a.kode_segmen, a.kode_kantor, a.status_klaim, a.kode_tipe_klaim, a.kode_sebab_klaim, 
										to_char(a.tgl_kejadian,'yyyymmdd') tglkejadian, to_char(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
  									nvl(
  									(
                          select kode_klaim from sijstk.pn_klaim where kode_klaim_induk is null
                          start with kode_klaim = a.kode_klaim and nvl(status_batal,'T')='T' 
                          connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' 
                      ),'-') kode_klaim_pertama,
						case
							when a.kanal_pelayanan in ('24') and a.tgl_rekam < '03-AUG-2020' then 'ANTOL'
							when a.kanal_pelayanan in ('24') and a.tgl_rekam >= '03-AUG-2020' then 'ANTOL'
							when a.kanal_pelayanan in ('25') then 'BPJSTKU'
							when a.kanal_pelayanan in ('26','27') then 'ONLINE'
							when a.kanal_pelayanan in ('28') then 'ONSITE WA'
							when a.kanal_pelayanan in ('29') then 'ONSITE WEB'
							else
								'MANUAL'
						end kanal_pelayanan
						-- decode(a.kanal_pelayanan,'26','ONLINE','27','ONLINE','28','ONSITE','29','ONSITE','MANUAL') kanal_pelayanan
                from sijstk.pn_klaim a
                where a.kode_klaim_induk is not null
  							$ls_filter_tgl_tapulang
  							$ls_filter_kantor
							$filter_layanan   
							) A
						) A WHERE 1=1 ".$condition." ".$order."
					) A 
					WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end." ";			
	//echo $sql;		
	$queryTotalRows = "SELECT count(1) FROM 
										(										 
             						select rownum no, a.* from
												(
              						select kode_klaim, tgltrans, tgl_klaim, tgl_trans, kpj,nama_pengambil_klaim, ket_tipe_klaim,
            									kode_segmen, kode_kantor, status_klaim, kode_tipe_klaim, kode_sebab_klaim, tglkejadian, tgl_kejadian, 
            									decode(jenis,'PENETAPAN_ULANG',kode_klaim_pertama,kode_klaim) kode_klaim_pertama, 
            									decode(jenis,'PENETAPAN_ULANG',kode_klaim_pertama,'-') display_kode_klaim_pertama,
												kanal_pelayanan
            							from
            							(		
              							select  
                                'PENETAPAN_AWAL' jenis, a.kode_klaim, to_char(a.tgl_klaim,'yyyymmdd') tgltrans, a.tgl_klaim, to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_trans, a.kpj,
              									decode(nvl(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (decode(a.kode_segmen,'JAKON',a.nama_tk,a.nama_tk))) nama_pengambil_klaim,
              									(select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim)||' '||a.kode_pointer_asal ket_tipe_klaim,
              									a.kode_segmen, a.kode_kantor, a.status_klaim, a.kode_tipe_klaim, a.kode_sebab_klaim,
																to_char(a.tgl_kejadian,'yyyymmdd') tglkejadian, to_char(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian, 
              									'-' kode_klaim_pertama,
												case
													when a.kanal_pelayanan in ('24') and a.tgl_rekam < '03-AUG-2020' then 'ANTOL'
													when a.kanal_pelayanan in ('24') and a.tgl_rekam >= '03-AUG-2020' then 'ANTOL'
													when a.kanal_pelayanan in ('25') then 'BPJSTKU'
													when a.kanal_pelayanan in ('26','27') then 'ONLINE'
													when a.kanal_pelayanan in ('28') then 'ONSITE WA'
													when a.kanal_pelayanan in ('29') then 'ONSITE WEB'
													else
														'MANUAL'
												end kanal_pelayanan
												-- decode(a.kanal_pelayanan,'26','ONLINE','27','ONLINE','28','ONSITE','29','ONSITE','MANUAL') kanal_pelayanan
                            from sijstk.pn_klaim a
                            where a.kode_klaim_induk is null
              							$ls_filter_tgl_tapawal 
              							$ls_filter_kantor
										$filter_layanan   
              							UNION ALL
              							select  
                                'PENETAPAN_ULANG' jenis, a.kode_klaim, to_char(a.tgl_penetapan,'yyyymmdd') tgltrans, a.tgl_klaim, to_char(a.tgl_penetapan,'dd/mm/yyyy') tgl_trans, a.kpj,
              									decode(nvl(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (decode(a.kode_segmen,'JAKON',a.nama_tk,a.nama_tk))) nama_pengambil_klaim,
              									(select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim)||' '||a.kode_pointer_asal ket_tipe_klaim,
              									a.kode_segmen, a.kode_kantor, a.status_klaim, a.kode_tipe_klaim, a.kode_sebab_klaim, 
																to_char(a.tgl_kejadian,'yyyymmdd') tglkejadian, to_char(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
              									nvl(
              									(
                                      select kode_klaim from sijstk.pn_klaim where kode_klaim_induk is null
                                      start with kode_klaim = a.kode_klaim and nvl(status_batal,'T')='T' 
                                      connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' 
                                  ),'-') kode_klaim_pertama,
									case
										when a.kanal_pelayanan in ('24') and a.tgl_rekam < '03-AUG-2020' then 'ANTOL'
										when a.kanal_pelayanan in ('24') and a.tgl_rekam >= '03-AUG-2020' then 'ANTOL'
										when a.kanal_pelayanan in ('25') then 'BPJSTKU'
										when a.kanal_pelayanan in ('26','27') then 'ONLINE'
										when a.kanal_pelayanan in ('28') then 'ONSITE WA'
										when a.kanal_pelayanan in ('29') then 'ONSITE WEB'
										else
											'MANUAL'
									end kanal_pelayanan
									-- decode(a.kanal_pelayanan,'26','ONLINE','27','ONLINE','28','ONSITE','29','ONSITE','MANUAL') kanal_pelayanan
                            from sijstk.pn_klaim a
                            where a.kode_klaim_induk is not null
              							$ls_filter_tgl_tapulang
              							$ls_filter_kantor
										$filter_layanan  
            							) A
												) A
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
        //$data['ACTION'] = '<input type="checkbox" id="CHECK_'.$i.'" urut="'.$i.'" KODE="'.$data['KODE_KLAIM'].'" KODE2="'.$data['KODE_KLAIM'].'" name="CHECK['.$i.']"> <input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_KLAIM'].'">';
			  $data['KODE_KLAIM'] = '<a href="#" onClick="window.location.replace(\'http://'.$HTTP_HOST.'/mod_pn/form/pn5049.php?task=View&dataid='.$data['KODE_KLAIM'].'&kode_klaim='.$data['KODE_KLAIM'].'&tglawaldisplay='.$TGLAWALDISPLAY.'&tglakhirdisplay='.$TGLAKHIRDISPLAY.'&rg_kategori='.$KATEGORI.'\',\'PN5049 - Daftar Klaim\')"><font color="#009999">'.$data['KODE_KLAIM'].'</font> </a>';
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
