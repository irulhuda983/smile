<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE				= $_REQUEST["TYPE"];
$KEYWORD 		= $_REQUEST["KEYWORD"];
$SEARCH1		= $_REQUEST["SEARCH1"];
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$USER 			= $_SESSION["USER"];
$TGLAWALDISPLAY		= isset($_REQUEST["TGLAWALDISPLAY"])?$_REQUEST["TGLAWALDISPLAY"]:date('d/m/Y');
$TGLAKHIRDISPLAY	= isset($_REQUEST["TGLAKHIRDISPLAY"])?$_REQUEST["TGLAKHIRDISPLAY"]:date('d/m/Y');
$KODEAGENDA				= $_REQUEST["KODE_AGENDA"];
 
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
  $by 	 = $order[0]['dir']; //print_r($_REQUEST);
  
  $sql = ""; //echo $TYPE;
if($TYPE=='l')
{
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
  					
	if (($KEYWORD != '') && ($KEYWORD != 'null')) {
		if (preg_match("/%/i", $KEYWORD)) {			
			$condition .= ' AND A.'.$SEARCH1 . " LIKE '".$KEYWORD."' ";
		} else {
			$condition .= ' AND A.'.$SEARCH1 . " = '".$KEYWORD."' ";
		};
	}
		
	//query data -----------------------------------------------------------------						
	$sql = "SELECT * FROM
					(
            	SELECT  ROWNUM NO,
                    A.KODE_KLAIM,
                    TO_CHAR (A.TGL_KLAIM, 'yyyymmdd') TGLKLAIM,
                    TO_CHAR (A.TGL_KLAIM, 'dd/mm/yyyy') TGL_KLAIM,
                    A.KPJ,
                    A.NAMA_TK NAMA_PENGAMBIL_KLAIM,
                    (SELECT NAMA_TIPE_KLAIM
                     FROM SIJSTK.PN_KODE_TIPE_KLAIM
                     WHERE KODE_TIPE_KLAIM = A.KODE_TIPE_KLAIM
                     )KET_TIPE_KLAIM,
                    A.KODE_SEGMEN,
                    A.KODE_KANTOR,
                    A.KODE_TIPE_KLAIM,
                    A.NOM_TINGKAT_PENGEMBANGAN,
                  A.NOM_TINGKAT_PENGEMBANGAN_KOR
              FROM PN.PN_AGENDA_KOREKSI_KLAIM_DATA A
              WHERE 1=1
			  AND A.KODE_TIPE_KLAIM IN ('JPN01')
			  AND A.KODE_KANTOR = '$KD_KANTOR'
			  AND NVL(A.STATUS_TINDAKLANJUT,'T') = 'T'
			  AND NOT EXISTS
			  (
				SELECT * FROM PN.PN_AGENDA_KOREKSI_KLAIM B
				WHERE B.KODE_KLAIM = A.KODE_KLAIM
				AND NVL(B.STATUS_BATAL,'T') = 'T'
			  )
              AND ROWNUM < 3000 
            {$condition} {$order}
					) A 
					WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end." ";	
	//echo $sql; die;
			
	$queryTotalRows = "SELECT count(1) FROM 
										(										
                        SELECT  ROWNUM NO,
                              A.KODE_KLAIM,
                              TO_CHAR (A.TGL_KLAIM, 'yyyymmdd') TGLKLAIM,
                              TO_CHAR (A.TGL_KLAIM, 'dd/mm/yyyy') TGL_KLAIM,
                              A.KPJ,
                              A.NAMA_TK NAMA_PENGAMBIL_KLAIM,
                              (SELECT NAMA_TIPE_KLAIM
                               FROM SIJSTK.PN_KODE_TIPE_KLAIM
                               WHERE KODE_TIPE_KLAIM = A.KODE_TIPE_KLAIM
                               )KET_TIPE_KLAIM,
                              A.KODE_SEGMEN,
                              A.KODE_KANTOR,
                              A.KODE_TIPE_KLAIM,
                              A.NOM_TINGKAT_PENGEMBANGAN,
                            A.NOM_TINGKAT_PENGEMBANGAN_KOR
                        FROM PN.PN_AGENDA_KOREKSI_KLAIM_DATA A
                        WHERE 1=1
						AND A.KODE_TIPE_KLAIM IN ('JPN01')
						AND A.KODE_KANTOR = '$KD_KANTOR'
						AND NVL(A.STATUS_TINDAKLANJUT,'T') = 'T'
						AND NOT EXISTS
						  (
							SELECT * FROM PN.PN_AGENDA_KOREKSI_KLAIM B
							WHERE B.KODE_KLAIM = A.KODE_KLAIM
							AND NVL(B.STATUS_BATAL,'T') = 'T'
						  )
                        AND ROWNUM < 3000
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
        $data['ACTION'] = "<a href=\"javascript:window.opener.getDataJhtKurangBayarSetupBunga('{$data['KODE_KLAIM']}');window.close();\">Koreksi</a>";
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
}else if($TYPE=='p')
{
    $data = array("INFORMASI_KLAIM"=>array(),"j"=>array());
    $sql_informasi_klaim = "select a.kode_klaim,a.kode_kantor,a.kode_segmen,a.kpj,nomor_identitas,a.nama_tk,
	(select nama_kantor from ms.ms_kantor where kode_kantor=a.kode_kantor) nama_kantor,
    prs.npp,prs.nama_perusahaan,a.kode_divisi,div.nama_divisi,
    to_char(a.tgl_lapor,'DD/MM/YYYY') tgl_lapor,a.keterangan,
    to_char(a.tgl_klaim,'DD/MM/YYYY') tgl_klaim,
    to_char(a.tgl_penetapan,'DD/MM/YYYY') tgl_penetapan,
    petugas_penetapan,no_penetapan,status_klaim,
    a.jenis_identitas,
    agn_klm.nom_tingkat_pengembangan,
    agn_klm.nom_tingkat_pengembangan_kor
	from pn.pn_klaim a left outer join kn.kn_perusahaan prs on a.kode_perusahaan=prs.kode_perusahaan
	left outer join kn.kn_divisi div on (a.kode_perusahaan=div.kode_perusahaan and a.kode_divisi=div.kode_divisi)
	left outer join pn.pn_agenda_koreksi_klaim_data agn_klm on a.kode_klaim=agn_klm.kode_klaim
    where a.kode_klaim='{$KEYWORD}'"; //echo $sql;
    $DB->parse($sql_informasi_klaim);
    if($DB->execute()) {
        if ($row1 = $DB->nextrow()) {
            $data['INFORMASI_KLAIM']=$row1; 
        }
		/*
        $sql = "select kode_tipe_penerima ,PERSENTASE_PENGAMBILAN,NOM_SALDO_TOTAL,NOM_SALDO_IURAN_TOTAL,NOM_SALDO_AWALTAHUN,NOM_DIAMBIL_THNBERJALAN
        ,NOM_SALDO_PENGEMBANGAN,NOM_MANFAAT_MAXBISADIAMBIL,NOM_MANFAAT_DIAMBIL,NOM_IURAN_TOTAL,NOM_IURAN_THNBERJALAN
        ,NOM_DPP_PPH,TARIF_PPH,NOM_IURAN_PENGEMBANGAN,NOM_PPH,NOM_BIAYA_DISETUJUI,NOM_PPN
        ,case when tgl_pengembangan is null then '' else to_char(tgl_pengembangan,'DD/MM/YYYY') end tgl_pengembangan
        ,case when tgl_saldo_awaltahun is null then '' else to_char(tgl_saldo_awaltahun,'DD/MM/YYYY') end tgl_saldo_awaltahun
        from pn.PN_AGENDA_KOREKSI_KLAIM_MNFDTL
        where kode_klaim='{$KEYWORD}' and kd_prg=1";//echo $sql;
            $DB->parse($sql);
            if($DB->execute())
                if($row2 = $DB->nextrow())
                {
                  $row2['NOM_TOTAL1']=$row2['NOM_SALDO_TOTAL']+$row2['NOM_IURAN_TOTAL'];
                  $row2['PERSENTASE_PENGAMBILAN']=number_format($row2['PERSENTASE_PENGAMBILAN'],2,'.',',');
                  $row2['NOM_SALDO_TOTAL']=number_format($row2['NOM_SALDO_TOTAL'],2,'.',',');
                  $row2['NOM_SALDO_IURAN_TOTAL']=number_format($row2['NOM_SALDO_IURAN_TOTAL'],2,'.',',');
                  $row2['NOM_SALDO_AWALTAHUN']=number_format($row2['NOM_SALDO_AWALTAHUN'],2,'.',',');
                  $row2['NOM_DIAMBIL_THNBERJALAN']=number_format($row2['NOM_DIAMBIL_THNBERJALAN'],2,'.',',');
                  $row2['NOM_SALDO_PENGEMBANGAN']=number_format($row2['NOM_SALDO_PENGEMBANGAN'],2,'.',',');
                  $row2['NOM_MANFAAT_MAXBISADIAMBIL']=number_format($row2[''],2,'.',',');
                  $row2['NOM_MANFAAT_DIAMBIL']=number_format($row2['NOM_MANFAAT_DIAMBIL'],2,'.',',');
                  $row2['NOM_IURAN_TOTAL']=number_format($row2['NOM_IURAN_TOTAL'],2,'.',',');
                  $row2['NOM_IURAN_TAHUNBERJALAN']=number_format($row2['NOM_IURAN_THNBERJALAN'],2,'.',',');
                  $row2['NOM_PPN']=number_format($row2['NOM_PPN'],2,'.',',');
                  $row2['NOM_PPH']=number_format($row2['NOM_PPH'],2,'.',',');
                  $row2['TARIF_PPH']=number_format($row2['TARIF_PPH'],2,'.',',');
                  $row2['NOM_IURAN_PENGEMBANGAN']=number_format($row2['NOM_IURAN_PENGEMBANGAN'],2,'.',',');
                  $row2['NOM_BIAYA_DISETUJUI']=number_format($row2['NOM_BIAYA_DISETUJUI'],2,'.',',');
                  $data['j']=$row2;
                }
				*/

	}
    echo json_encode($data);
}
?>