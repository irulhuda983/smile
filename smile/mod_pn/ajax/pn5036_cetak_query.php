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
$KATEGORITGLKLAIM	= $_POST["KATEGORITGLKLAIM"];
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
	// berdasarkan tgl klaim
	if($KATEGORITGLKLAIM == "0")
	{		
		$sql = "SELECT * 
				FROM
				(
				  SELECT 
				  ROWNUM NO, A.*
				  FROM
				  (
					SELECT 
					KODE_KANTOR,
					KODE_PERUSAHAAN,
					NPP,
					NAMA_PERUSAHAAN,
					COUNT(*) JML_KLAIM,
					to_date('$TGLAWALDISPLAY','dd/mm/yyyy') PERIODE1,
					to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') PERIODE2,
					to_date('$TGLAWALDISPLAY','dd/mm/yyyy') || ' s/d ' || to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') PERIODE,
					'' STATUS_TINDAKLANJUT
					FROM
					  (SELECT rownum no,
						A.*
					  FROM
						( SELECT DISTINCT A.*
						FROM
						  (SELECT A.*
						  FROM
							(SELECT a.kode_perusahaan,
							  (SELECT prs.NPP
							  FROM kn.kn_perusahaan prs
							  WHERE prs.kode_perusahaan = a.kode_perusahaan
							  AND rownum                =1
							  ) NPP,
							  (SELECT prs.NAMA_PERUSAHAAN
							  FROM kn.kn_perusahaan prs
							  WHERE prs.kode_perusahaan = a.kode_perusahaan
							  AND rownum                =1
							  ) NAMA_PERUSAHAAN,
							  a.nomor_identitas,
							  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
							  a.petugas_rekam,
							  a.kode_klaim,
							  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
							  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
							  a.kpj,
							  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
							  (SELECT no_proyek
								||'-'
								||nama_proyek
							  FROM sijstk.jn_proyek
							  WHERE kode_proyek = a.kode_proyek
							  ),a.nama_tk)) ) nama_pengambil_klaim,
							  (SELECT nama_tipe_klaim
							  FROM sijstk.pn_kode_tipe_klaim
							  WHERE kode_tipe_klaim = a.kode_tipe_klaim
							  )
							  ||' '
							  ||a.kode_pointer_asal ket_tipe_klaim,
							  a.kode_segmen,
							  a.kode_kantor,
							  a.status_klaim,
							  a.kode_tipe_klaim,
							  a.kode_sebab_klaim
							FROM sijstk.pn_klaim a
							WHERE a.tgl_klaim between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
							AND NVL(a.status_klaim,'T')         <> 'BATAL'
							AND NVL(a.status_submit_agenda2,'T') = 'T'
							AND NVL(a.status_submit_agenda,'T')  = 'T'
							AND NVL(a.status_kelayakan,'T')      = 'Y'
							AND NVL(a.status_batal,'T')          = 'T'
							AND kode_tipe_klaim                  = 'JKK01'
							AND a.KODE_KLAIM_INDUK              IS NULL
							$filterkantor
							) A
						  WHERE 1=1
						  UNION ALL
						  SELECT A.*
						  FROM
							(SELECT a.kode_perusahaan,
							  (SELECT prs.NPP
							  FROM kn.kn_perusahaan prs
							  WHERE prs.kode_perusahaan = a.kode_perusahaan
							  AND rownum                =1
							  ) NPP,
							  (SELECT prs.NAMA_PERUSAHAAN
							  FROM kn.kn_perusahaan prs
							  WHERE prs.kode_perusahaan = a.kode_perusahaan
							  AND rownum                =1
							  ) NAMA_PERUSAHAAN,
							  a.nomor_identitas,
							  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
							  a.petugas_rekam,
							  a.kode_klaim,
							  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
							  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
							  a.kpj,
							  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
							  (SELECT no_proyek
								||'-'
								||nama_proyek
							  FROM sijstk.jn_proyek
							  WHERE kode_proyek = a.kode_proyek
							  ),a.nama_tk)) ) nama_pengambil_klaim,
							  (SELECT nama_tipe_klaim
							  FROM sijstk.pn_kode_tipe_klaim
							  WHERE kode_tipe_klaim = a.kode_tipe_klaim
							  )
							  ||' '
							  ||a.kode_pointer_asal ket_tipe_klaim,
							  a.kode_segmen,
							  a.kode_kantor,
							  a.status_klaim,
							  a.kode_tipe_klaim,
							  a.kode_sebab_klaim
							FROM sijstk.pn_klaim a
							WHERE a.tgl_klaim between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
							AND NVL(a.status_klaim,'T')           <> 'BATAL'
							AND NVL(a.status_submit_agenda,'T')    = 'Y'
							AND NVL(a.status_submit_agenda2,'T')   = 'T'
							AND NVL(a.status_submit_pengajuan,'T') = 'T'
							AND NVL(a.status_kelayakan,'T')        = 'Y'
							AND NVL(a.status_batal,'T')            = 'T'
							AND a.kode_tipe_klaim                  = 'JKK01'
							AND a.KODE_KLAIM_INDUK                IS NULL
							$filterkantor
							) A
						  WHERE 1=1
						  ) A --ORDER BY TGLKLAIM ASC
						) A
					  )
					WHERE 1 = 1
					GROUP BY KODE_KANTOR,
					  KODE_PERUSAHAAN,
					  NPP,
					  NAMA_PERUSAHAAN
					ORDER BY COUNT(*) DESC,
					  NAMA_PERUSAHAAN ASC
				  ) A
				) WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end." ";
	//echo $sql;		
										
		$queryTotalRows = "SELECT COUNT(1)  
							FROM
							(
							  SELECT 
							  ROWNUM NO, A.*
							  FROM
							  (
								SELECT 
								KODE_KANTOR,
								KODE_PERUSAHAAN,
								NPP,
								NAMA_PERUSAHAAN,
								COUNT(*) JML_KLAIM,
								to_date('$TGLAWALDISPLAY','dd/mm/yyyy') PERIODE1,
								to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') PERIODE2,
								to_date('$TGLAWALDISPLAY','dd/mm/yyyy') || ' s/d ' || to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') PERIODE,
								'' STATUS_TINDAKLANJUT
								FROM
								  (SELECT rownum no,
									A.*
								  FROM
									( SELECT DISTINCT A.*
									FROM
									  (SELECT A.*
									  FROM
										(SELECT a.kode_perusahaan,
										  (SELECT prs.NPP
										  FROM kn.kn_perusahaan prs
										  WHERE prs.kode_perusahaan = a.kode_perusahaan
										  AND rownum                =1
										  ) NPP,
										  (SELECT prs.NAMA_PERUSAHAAN
										  FROM kn.kn_perusahaan prs
										  WHERE prs.kode_perusahaan = a.kode_perusahaan
										  AND rownum                =1
										  ) NAMA_PERUSAHAAN,
										  a.nomor_identitas,
										  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
										  a.petugas_rekam,
										  a.kode_klaim,
										  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
										  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
										  a.kpj,
										  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
										  (SELECT no_proyek
											||'-'
											||nama_proyek
										  FROM sijstk.jn_proyek
										  WHERE kode_proyek = a.kode_proyek
										  ),a.nama_tk)) ) nama_pengambil_klaim,
										  (SELECT nama_tipe_klaim
										  FROM sijstk.pn_kode_tipe_klaim
										  WHERE kode_tipe_klaim = a.kode_tipe_klaim
										  )
										  ||' '
										  ||a.kode_pointer_asal ket_tipe_klaim,
										  a.kode_segmen,
										  a.kode_kantor,
										  a.status_klaim,
										  a.kode_tipe_klaim,
										  a.kode_sebab_klaim
										FROM sijstk.pn_klaim a
										WHERE a.tgl_klaim between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
										AND NVL(a.status_klaim,'T')         <> 'BATAL'
										AND NVL(a.status_submit_agenda2,'T') = 'T'
										AND NVL(a.status_submit_agenda,'T')  = 'T'
										AND NVL(a.status_kelayakan,'T')      = 'Y'
										AND NVL(a.status_batal,'T')          = 'T'
										AND kode_tipe_klaim                  = 'JKK01'
										AND a.KODE_KLAIM_INDUK              IS NULL
										$filterkantor
										) A
									  WHERE 1=1
									  UNION ALL
									  SELECT A.*
									  FROM
										(SELECT a.kode_perusahaan,
										  (SELECT prs.NPP
										  FROM kn.kn_perusahaan prs
										  WHERE prs.kode_perusahaan = a.kode_perusahaan
										  AND rownum                =1
										  ) NPP,
										  (SELECT prs.NAMA_PERUSAHAAN
										  FROM kn.kn_perusahaan prs
										  WHERE prs.kode_perusahaan = a.kode_perusahaan
										  AND rownum                =1
										  ) NAMA_PERUSAHAAN,
										  a.nomor_identitas,
										  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
										  a.petugas_rekam,
										  a.kode_klaim,
										  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
										  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
										  a.kpj,
										  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
										  (SELECT no_proyek
											||'-'
											||nama_proyek
										  FROM sijstk.jn_proyek
										  WHERE kode_proyek = a.kode_proyek
										  ),a.nama_tk)) ) nama_pengambil_klaim,
										  (SELECT nama_tipe_klaim
										  FROM sijstk.pn_kode_tipe_klaim
										  WHERE kode_tipe_klaim = a.kode_tipe_klaim
										  )
										  ||' '
										  ||a.kode_pointer_asal ket_tipe_klaim,
										  a.kode_segmen,
										  a.kode_kantor,
										  a.status_klaim,
										  a.kode_tipe_klaim,
										  a.kode_sebab_klaim
										FROM sijstk.pn_klaim a
										WHERE a.tgl_klaim between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
										AND NVL(a.status_klaim,'T')           <> 'BATAL'
										AND NVL(a.status_submit_agenda,'T')    = 'Y'
										AND NVL(a.status_submit_agenda2,'T')   = 'T'
										AND NVL(a.status_submit_pengajuan,'T') = 'T'
										AND NVL(a.status_kelayakan,'T')        = 'Y'
										AND NVL(a.status_batal,'T')            = 'T'
										AND a.kode_tipe_klaim                  = 'JKK01'
										AND a.KODE_KLAIM_INDUK                IS NULL
										$filterkantor
										) A
									  WHERE 1=1
									  ) A --ORDER BY TGLKLAIM ASC
									) A
								  )
								WHERE 1 = 1
								GROUP BY KODE_KANTOR,
								  KODE_PERUSAHAAN,
								  NPP,
								  NAMA_PERUSAHAAN
								ORDER BY COUNT(*) DESC,
								  NAMA_PERUSAHAAN ASC
							  ) A
							) WHERE 1=1 ".$condition;
	  //echo $queryTotalRows;
	}
	// berdasarkan tgl kejadian
	elseif ($KATEGORITGLKLAIM == "1")
	{
				$sql = "SELECT * 
				FROM
				(
				  SELECT 
				  ROWNUM NO, A.*
				  FROM
				  (
					SELECT 
					KODE_KANTOR,
					KODE_PERUSAHAAN,
					NPP,
					NAMA_PERUSAHAAN,
					COUNT(*) JML_KLAIM,
					to_date('$TGLAWALDISPLAY','dd/mm/yyyy') PERIODE1,
					to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') PERIODE2,
					to_date('$TGLAWALDISPLAY','dd/mm/yyyy') || ' s/d ' || to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') PERIODE,
					'' STATUS_TINDAKLANJUT
					FROM
					  (SELECT rownum no,
						A.*
					  FROM
						( SELECT DISTINCT A.*
						FROM
						  (SELECT A.*
						  FROM
							(SELECT a.kode_perusahaan,
							  (SELECT prs.NPP
							  FROM kn.kn_perusahaan prs
							  WHERE prs.kode_perusahaan = a.kode_perusahaan
							  AND rownum                =1
							  ) NPP,
							  (SELECT prs.NAMA_PERUSAHAAN
							  FROM kn.kn_perusahaan prs
							  WHERE prs.kode_perusahaan = a.kode_perusahaan
							  AND rownum                =1
							  ) NAMA_PERUSAHAAN,
							  a.nomor_identitas,
							  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
							  a.petugas_rekam,
							  a.kode_klaim,
							  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
							  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
							  a.kpj,
							  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
							  (SELECT no_proyek
								||'-'
								||nama_proyek
							  FROM sijstk.jn_proyek
							  WHERE kode_proyek = a.kode_proyek
							  ),a.nama_tk)) ) nama_pengambil_klaim,
							  (SELECT nama_tipe_klaim
							  FROM sijstk.pn_kode_tipe_klaim
							  WHERE kode_tipe_klaim = a.kode_tipe_klaim
							  )
							  ||' '
							  ||a.kode_pointer_asal ket_tipe_klaim,
							  a.kode_segmen,
							  a.kode_kantor,
							  a.status_klaim,
							  a.kode_tipe_klaim,
							  a.kode_sebab_klaim
							FROM sijstk.pn_klaim a
							WHERE a.tgl_kejadian between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
							AND NVL(a.status_klaim,'T')         <> 'BATAL'
							AND NVL(a.status_submit_agenda2,'T') = 'T'
							AND NVL(a.status_submit_agenda,'T')  = 'T'
							AND NVL(a.status_kelayakan,'T')      = 'Y'
							AND NVL(a.status_batal,'T')          = 'T'
							AND kode_tipe_klaim                  = 'JKK01'
							AND a.KODE_KLAIM_INDUK              IS NULL
							$filterkantor
							) A
						  WHERE 1=1
						  UNION ALL
						  SELECT A.*
						  FROM
							(SELECT a.kode_perusahaan,
							  (SELECT prs.NPP
							  FROM kn.kn_perusahaan prs
							  WHERE prs.kode_perusahaan = a.kode_perusahaan
							  AND rownum                =1
							  ) NPP,
							  (SELECT prs.NAMA_PERUSAHAAN
							  FROM kn.kn_perusahaan prs
							  WHERE prs.kode_perusahaan = a.kode_perusahaan
							  AND rownum                =1
							  ) NAMA_PERUSAHAAN,
							  a.nomor_identitas,
							  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
							  a.petugas_rekam,
							  a.kode_klaim,
							  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
							  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
							  a.kpj,
							  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
							  (SELECT no_proyek
								||'-'
								||nama_proyek
							  FROM sijstk.jn_proyek
							  WHERE kode_proyek = a.kode_proyek
							  ),a.nama_tk)) ) nama_pengambil_klaim,
							  (SELECT nama_tipe_klaim
							  FROM sijstk.pn_kode_tipe_klaim
							  WHERE kode_tipe_klaim = a.kode_tipe_klaim
							  )
							  ||' '
							  ||a.kode_pointer_asal ket_tipe_klaim,
							  a.kode_segmen,
							  a.kode_kantor,
							  a.status_klaim,
							  a.kode_tipe_klaim,
							  a.kode_sebab_klaim
							FROM sijstk.pn_klaim a
							WHERE a.tgl_kejadian between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
							AND NVL(a.status_klaim,'T')           <> 'BATAL'
							AND NVL(a.status_submit_agenda,'T')    = 'Y'
							AND NVL(a.status_submit_agenda2,'T')   = 'T'
							AND NVL(a.status_submit_pengajuan,'T') = 'T'
							AND NVL(a.status_kelayakan,'T')        = 'Y'
							AND NVL(a.status_batal,'T')            = 'T'
							AND a.kode_tipe_klaim                  = 'JKK01'
							AND a.KODE_KLAIM_INDUK                IS NULL
							$filterkantor
							) A
						  WHERE 1=1
						  ) A --ORDER BY TGLKLAIM ASC
						) A
					  )
					WHERE 1 = 1
					GROUP BY KODE_KANTOR,
					  KODE_PERUSAHAAN,
					  NPP,
					  NAMA_PERUSAHAAN
					ORDER BY COUNT(*) DESC,
					  NAMA_PERUSAHAAN ASC
				  ) A
				) WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end." ";
	//echo $sql;		
										
		$queryTotalRows = "SELECT COUNT(1)  
							FROM
							(
							  SELECT 
							  ROWNUM NO, A.*
							  FROM
							  (
								SELECT 
								KODE_KANTOR,
								KODE_PERUSAHAAN,
								NPP,
								NAMA_PERUSAHAAN,
								COUNT(*) JML_KLAIM,
								to_date('$TGLAWALDISPLAY','dd/mm/yyyy') PERIODE1,
								to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') PERIODE2,
								to_date('$TGLAWALDISPLAY','dd/mm/yyyy') || ' s/d ' || to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy') PERIODE,
								'' STATUS_TINDAKLANJUT
								FROM
								  (SELECT rownum no,
									A.*
								  FROM
									( SELECT DISTINCT A.*
									FROM
									  (SELECT A.*
									  FROM
										(SELECT a.kode_perusahaan,
										  (SELECT prs.NPP
										  FROM kn.kn_perusahaan prs
										  WHERE prs.kode_perusahaan = a.kode_perusahaan
										  AND rownum                =1
										  ) NPP,
										  (SELECT prs.NAMA_PERUSAHAAN
										  FROM kn.kn_perusahaan prs
										  WHERE prs.kode_perusahaan = a.kode_perusahaan
										  AND rownum                =1
										  ) NAMA_PERUSAHAAN,
										  a.nomor_identitas,
										  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
										  a.petugas_rekam,
										  a.kode_klaim,
										  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
										  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
										  a.kpj,
										  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
										  (SELECT no_proyek
											||'-'
											||nama_proyek
										  FROM sijstk.jn_proyek
										  WHERE kode_proyek = a.kode_proyek
										  ),a.nama_tk)) ) nama_pengambil_klaim,
										  (SELECT nama_tipe_klaim
										  FROM sijstk.pn_kode_tipe_klaim
										  WHERE kode_tipe_klaim = a.kode_tipe_klaim
										  )
										  ||' '
										  ||a.kode_pointer_asal ket_tipe_klaim,
										  a.kode_segmen,
										  a.kode_kantor,
										  a.status_klaim,
										  a.kode_tipe_klaim,
										  a.kode_sebab_klaim
										FROM sijstk.pn_klaim a
										WHERE a.tgl_kejadian between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
										AND NVL(a.status_klaim,'T')         <> 'BATAL'
										AND NVL(a.status_submit_agenda2,'T') = 'T'
										AND NVL(a.status_submit_agenda,'T')  = 'T'
										AND NVL(a.status_kelayakan,'T')      = 'Y'
										AND NVL(a.status_batal,'T')          = 'T'
										AND kode_tipe_klaim                  = 'JKK01'
										AND a.KODE_KLAIM_INDUK              IS NULL
										$filterkantor
										) A
									  WHERE 1=1
									  UNION ALL
									  SELECT A.*
									  FROM
										(SELECT a.kode_perusahaan,
										  (SELECT prs.NPP
										  FROM kn.kn_perusahaan prs
										  WHERE prs.kode_perusahaan = a.kode_perusahaan
										  AND rownum                =1
										  ) NPP,
										  (SELECT prs.NAMA_PERUSAHAAN
										  FROM kn.kn_perusahaan prs
										  WHERE prs.kode_perusahaan = a.kode_perusahaan
										  AND rownum                =1
										  ) NAMA_PERUSAHAAN,
										  a.nomor_identitas,
										  TO_CHAR(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian,
										  a.petugas_rekam,
										  a.kode_klaim,
										  TO_CHAR(a.tgl_klaim,'yyyymmdd') tglklaim,
										  TO_CHAR(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
										  a.kpj,
										  DECODE( NVL(a.kode_pointer_asal,'x'),'PROMOTIF',a.nama_pelaksana_kegiatan, (DECODE(a.kode_segmen,'JAKON',
										  (SELECT no_proyek
											||'-'
											||nama_proyek
										  FROM sijstk.jn_proyek
										  WHERE kode_proyek = a.kode_proyek
										  ),a.nama_tk)) ) nama_pengambil_klaim,
										  (SELECT nama_tipe_klaim
										  FROM sijstk.pn_kode_tipe_klaim
										  WHERE kode_tipe_klaim = a.kode_tipe_klaim
										  )
										  ||' '
										  ||a.kode_pointer_asal ket_tipe_klaim,
										  a.kode_segmen,
										  a.kode_kantor,
										  a.status_klaim,
										  a.kode_tipe_klaim,
										  a.kode_sebab_klaim
										FROM sijstk.pn_klaim a
										WHERE a.tgl_kejadian between to_date('$TGLAWALDISPLAY','dd/mm/yyyy') and to_date('$TGLAKHIRDISPLAY','dd/mm/yyyy')
										AND NVL(a.status_klaim,'T')           <> 'BATAL'
										AND NVL(a.status_submit_agenda,'T')    = 'Y'
										AND NVL(a.status_submit_agenda2,'T')   = 'T'
										AND NVL(a.status_submit_pengajuan,'T') = 'T'
										AND NVL(a.status_kelayakan,'T')        = 'Y'
										AND NVL(a.status_batal,'T')            = 'T'
										AND a.kode_tipe_klaim                  = 'JKK01'
										AND a.KODE_KLAIM_INDUK                IS NULL
										$filterkantor
										) A
									  WHERE 1=1
									  ) A --ORDER BY TGLKLAIM ASC
									) A
								  )
								WHERE 1 = 1
								GROUP BY KODE_KANTOR,
								  KODE_PERUSAHAAN,
								  NPP,
								  NAMA_PERUSAHAAN
								ORDER BY COUNT(*) DESC,
								  NAMA_PERUSAHAAN ASC
							  ) A
							) WHERE 1=1 ".$condition;

	  //echo $queryTotalRows;
	}

	//echo $sql;
	$recordsTotal = $DB->get_data($queryTotalRows);      
  $recordsTotal = $recordsTotal;

  $DB->parse($sql);
  if($DB->execute())
	{ 
    $i = 0;
    while($data = $DB->nextrow())
    {
        //$data['ACTION'] = '<input type="checkbox" id="CHECK_'.$i.'" urut="'.$i.'" KODE="'.$data['KODE_KLAIM'].'" KODE2="'.$data['KODE_KLAIM'].'" name="CHECK['.$i.']"> <input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_KLAIM'].'">';
		$data['ACTION'] = '<input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$data['KODE_PERUSAHAAN'].'"><a href="#" onClick="NewWindow(\'http://'.$HTTP_HOST.'/mod_pn/ajax/pn5036_cetak.php?task=View&root_sender=pn5036.php&sender=pn5036.php&sender_mid='.$mid.'&kode_perusahaan='.$data['KODE_PERUSAHAAN'].'&periode1='.$TGLAWALDISPLAY.'&periode2='.$TGLAKHIRDISPLAY.'&kategoritglklaim='.$KATEGORITGLKLAIM.'&jml_klaim='.$data['JML_KLAIM'].'&kode_kantor='.$data['KODE_KANTOR'].'\',\'PN5036 - LEMBAR PERNYATAAN KONFIRMASI\',1024,600,\'yes\')"><img src="../../images/printx.png" border="0" style= "height:20px;" alt="Cetak Pembayaran Klaim" align="absmiddle" /> <u><font color="#009999">Cetak</font></u> </a>';
				//$data['NOM_TAGIHAN'] ="Rp. ". number_format($data['NOM_TAGIHAN'],2,",",".");
        //$data['KODE_KLAIM'] = '<a href="#" onClick="window.location.replace(\'http://'.$HTTP_HOST.'/mod_pn/form/pn5036.php?task=View&dataid='.$data['KODE_KLAIM'].'&kode_klaim='.$data['KODE_KLAIM'].'\',\'pn5036 - OUT STANDING KLAIM JK TAHAP I\')"><font color="#009999">'.$data['KODE_KLAIM'].'</font> </a>';
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