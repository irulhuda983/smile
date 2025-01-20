<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);

$TYPE				= $_REQUEST["TYPE"];
$KEYWORD 		= $_REQUEST["KEYWORD"];
$SEARCH1		= $_REQUEST["SEARCH1"];
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$USER 			= $_SESSION["USER"];
$TGLAWALDISPLAY		= isset($_REQUEST["TGLAWALDISPLAY"])?$_REQUEST["TGLAWALDISPLAY"]:date('d/m/Y');
$TGLAKHIRDISPLAY	= isset($_REQUEST["TGLAKHIRDISPLAY"])?$_REQUEST["TGLAKHIRDISPLAY"]:date('d/m/Y');
$KODEAGENDA				= $_REQUEST["KODE_AGENDA"];

function api_json_call($apiurl, $header, $data) {
	$curl = curl_init();

	curl_setopt_array(
	  $curl, 
	  array(
		CURLOPT_URL => $apiurl,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_HTTPHEADER => $header,
	  )
	);
  
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
  
	if ($err) {
	  $jdata["ret"] = -1;
	  $jdata["msg"] = "cURL Error #:" . $err;
	  $result = $jdata;
	} else {
	  $result = json_decode($response);
	}
  
	return $result;
}

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
			  AND A.KODE_TIPE_KLAIM IN ('JHT01','JHM01')
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
						AND A.KODE_TIPE_KLAIM IN ('JHT01','JHM01')
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
}else if($TYPE=='lovKurangBayarKodeSebab'){

    $sql="SELECT kode_sebab_kurang_bayar, nama_sebab_kurang_bayar
    FROM pn.pn_kurang_bayar_kode_sebab
    WHERE status_nonaktif = 'T'
    ORDER BY no_urut ASC";                   
    
    $DB->parse($sql);
    
    if($DB->execute()){ 
      $i = 0;
      $itotal = 0;
      $jdata = array();
      while($data = $DB->nextrow()){
        $data["NO"] = $start + $i;
        $jdata[] = $data;
        $i++;
        $itotal++;
      }
        $jsondata["ret"] = "0";
        $jsondata["data"] = $jdata;
        $jsondata["msg"] = "Sukses";
        echo json_encode($jsondata);
    } else {
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
        echo json_encode($jsondata);
    }                          
}else if($TYPE=='lovKurangBayarKodeDokumen'){

  $ls_kode_sebab_kurang_bayar = $_POST['kode_sebab_kurang_bayar'];

  $sql="SELECT a.kode_sebab_kurang_bayar,
        a.kode_dokumen,
        (SELECT b.nama_dokumen
          FROM pn.pn_kurang_bayar_kode_dok b
          WHERE b.kode_dokumen = a.kode_dokumen)
            nama_dokumen
      FROM pn.pn_kurang_bayar_sebab_dok a
      WHERE a.status_nonaktif = 'T'
      AND a.kode_sebab_kurang_bayar='$ls_kode_sebab_kurang_bayar'";                   
        
  $DB->parse($sql);
  
  if($DB->execute()){ 
    $i = 0;
    $itotal = 0;
    $jdata = array();
    while($data = $DB->nextrow()){
      $data["NO"] = $start + $i;
      $jdata[] = $data;
      $i++;
      $itotal++;
    }
      $jsondata["ret"] = "0";
      $jsondata["data"] = $jdata;
      $jsondata["msg"] = "Sukses";
      echo json_encode($jsondata);
  } else {
      $jsondata["ret"] = "-1";
      $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
      echo json_encode($jsondata);
  }                          
}else if($TYPE=='kodeBank'){

   $ls_kode_tipe_penerima = $_POST['kode_tipe_penerima'];
   $ls_kode_kantor_pembayar = $_POST['kode_kantor_pembayar'];
   $ls_kode_klaim =  $_POST['kode_klaim'];
   $ls_kode_pointer_asal= $_POST['kode_pointer_asal'];

   $sql1="select nvl(status_rekening_sentral,'T') as status_rekening_sentral from sijstk.ms_kantor ".
   "where kode_kantor = '$ls_kode_kantor_pembayar'";

   $DB->parse($sql1);
   $DB->execute();
   $data1 = $DB->nextrow();
   $ls_status_rekening_sentral1	= $data1["STATUS_REKENING_SENTRAL"];

   $sql2 = "select nvl(a.status_rekening_sentral,'T') status_rekening_sentral from sijstk.pn_klaim_penerima_manfaat a ".
   "where kode_klaim = '$ls_kode_klaim' ".
   "and kode_tipe_penerima = '$ls_kode_tipe_penerima'";
  $DB->parse($sql2);
  $DB->execute();
  $data2 = $DB->nextrow();
  $ls_status_rekening_sentral2		= $data2["STATUS_REKENING_SENTRAL"];

   if($ls_status_rekening_sentral1=="Y" || $ls_status_rekening_sentral2=="Y" ){
          $sql = "select distinct a.kode_bank, b.nama_bank from ms.ms_rekening_detil a, ms.ms_bank b ".
          "where a.kode_bank = b.kode_bank ".
          "and a.tipe_rekening='36' and a.kode_bank = '020' ". //sementara batasi bank BNI, nanti diaktifkan lg bank yg lain
          "and a.kode_kantor = 'ATP' ".
          "order by a.kode_bank";

              
   }else{


        if ($ls_kode_pointer_asal=="SPO")
        {
          $sql = "select a.kode_bank,b.nama_bank from sijstk.monitor_klaim_jspo a, ms.ms_bank b ". 
                "where a.kode_bank = b.kode_bank ".
                "and a.no_agenda = '$ls_kode_klaim' ";													
        }else
        {
          $sql = "select distinct a.kode_bank, c.nama_bank ".
                "from sijstk.ms_rekening a,sijstk.ms_rekening_detil b, sijstk.ms_bank c ". 
                "where a.kode_kantor = b.kode_kantor(+) ".  
                "and a.kode_bank = b.kode_bank(+) ".  
                "and a.kode_rekening = b.kode_rekening(+) ".  
                "and a.kode_buku = b.kode_buku(+) ".  
                "and a.kode_bank = c.kode_bank ".   
                "and a.kode_kantor = '$ls_kode_kantor_pembayar' ". 
                "and nvl(a.aktif,'T')='Y' ".
                "and b.tipe_rekening in ('13','14','15','16') ". 
                "order by a.kode_bank";
        }			 											

   }

          $DB->parse($sql);
      
          if($DB->execute()){ 
          $i = 0;
          $itotal = 0;
          $jdata = array();
            while($data = $DB->nextrow()){
              $data["NO"] = $start + $i;
              $jdata[] = $data;
              $i++;
              $itotal++;
            }
            $jsondata["ret"] = "0";
            $jsondata["data"] = $jdata;
            $jsondata["msg"] = "Sukses";
            echo json_encode($jsondata);
        } else {
                $jsondata["ret"] = "-1";
                $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
                echo json_encode($jsondata);
          }                      

  


                          
}else if($TYPE == "getListBankAsal"){

  $resultBank = api_json_call($wsIp . "/JSOPG/GetListBank", array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
    ) ,array(
      'chId'=>'SMILE',
      'reqId'=>$USER
  ));	

  $listBankPilih = array();
  if($resultBank->ret == "0"){ 

    // $adaBankTujuan = false;
    for($i = 0; $i < $resultBank->numrows; $i++)
    {
      // if($resultBank->data[$i]->KODE_BANK_ATB == $_POST['kodeBankTujuan']){
        // $adaBankTujuan = true;
        array_push($listBankPilih, array(
          'KODE_BANK' => $resultBank->data[$i]->KODE_BANK,
          'NAMA_BANK' => $resultBank->data[$i]->BANK,
          'KODE_BANK_ATB' => $resultBank->data[$i]->KODE_BANK_ATB
        ));
      // }
    }

    // if(!$adaBankTujuan){
    //   for($i = 0; $i < $resultBank->numrows; $i++){
    //     $trfJenis = false;
    //     for($j = 0; $j < ExtendedFunction::count($resultBank->data[$i]->METODE_TRANSFER);$j++){
    //       if($resultBank->data[$i]->METODE_TRANSFER[$j]->KODE == 'RTGS' || $resultBank->data[$i]->METODE_TRANSFER[$j]->KODE == 'SKN'){
    //         $trfJenis = true;
    //       }
    //     }

    //     if($trfJenis){
    //       array_push($listBankPilih, array(
    //         'KODE_BANK' => $resultBank->data[$i]->KODE_BANK,
    //         'NAMA_BANK' => $resultBank->data[$i]->BANK,
    //         'KODE_BANK_ATB' => $resultBank->data[$i]->KODE_BANK_ATB
    //       ));
    //     }
    //   }
    // }

    // $sql = "select distinct a.kode_bank, c.nama_bank ".
    // "from sijstk.ms_rekening a,sijstk.ms_rekening_detil b, sijstk.ms_bank c ". 
    // "where a.kode_kantor = b.kode_kantor(+) ".  
    // "and a.kode_bank = b.kode_bank(+) ".  
    // "and a.kode_rekening = b.kode_rekening(+) ".  
    // "and a.kode_buku = b.kode_buku(+) ".  
    // "and a.kode_bank = c.kode_bank ".   
    // "and a.kode_kantor = '$ls_kode_kantor' ". 
    // "and nvl(a.aktif,'T')='Y' ".
    // "and b.tipe_rekening in ('13','14','15','16') ". 
    // "order by a.kode_bank";
    

    $jsondata["ret"] = "0";
    $jsondata["data"] = $listBankPilih;
    $jsondata["msg"] = "Sukses";
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = "-1";
    $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }     

}
?>