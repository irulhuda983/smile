<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SMILE";
$gs_pagetitle = "Submit Persetujuan Klaim";
if ($username==""){$username = $_SESSION["USER"];}

$ls_kode_klaim	= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];	
$ln_no_level	= !isset($_GET['no_level']) ? $_POST['no_level'] : $_GET['no_level'];	
$ls_dataid	 	 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_root_sender  = !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender 			 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_mid 			 	 = !isset($_GET['mid']) ? $_POST['mid'] : $_GET['mid'];
$ls_task 				 = !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$ls_rg_kategori	 = !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];
$ls_activetab  	 = !isset($_GET['activetab']) ? $_POST['activetab'] : $_GET['activetab'];

$ls_bulan_manfaat_jkp = !isset($_GET['bulan_manfaat_jkp']) ? $_POST['bulan_manfaat_jkp'] : $_GET['bulan_manfaat_jkp'];

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

if(isset($_POST["btnsubmit"]))

{	
	 // -----------------------------start update pending matters 09032022------------------------
	if(isset($_SESSION["USER"]))
	{	
	 // -----------------------------end update pending matters 09032022------------------------

		// 13112020, ditambahkan untuk memvalidasi klaim yang dokumen dan tanda tangan belum lengkap sehingga tidak bisa ke tahap selanjutnya. 
		$query_cek = "select count(*) jml_dokumen_digital from pn.pn_klaim where kanal_pelayanan in (select KODE from MS.MS_LOOKUP where TIPE = 'KANALKLM' and KATEGORI = 'DOKUMEN_DIGITAL') 
						and kode_klaim = '$ls_kode_klaim' ";
		$DB->parse($query_cek);
		if($DB->execute()){
			if($row=$DB->nextrow()){
				$ls_jml_dokumen_digital = $row['JML_DOKUMEN_DIGITAL'];			 
			}
		}
		// cek dokumen arsip sebelumnya
		// cek kanal BPJSTKU
		$sql_cek_bpjstku = "
				SELECT 	KANAL_PELAYANAN, KODE_TIPE_KLAIM,
				(
					select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
					where x.kode_klaim = a.kode_klaim
					and x.kode_manfaat = y.kode_manfaat
					and nvl(y.flag_berkala,'T')='Y'
					and nvl(x.nom_biaya_disetujui,0)<>0
				) cnt_berkala,
				(
					select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
					where x.kode_klaim = a.kode_klaim
					and x.kode_manfaat = y.kode_manfaat
					and nvl(y.flag_berkala,'T')='T'
					and nvl(x.nom_biaya_disetujui,0)<>0
				) cnt_lumpsum
				FROM 		PN.PN_KLAIM a
				WHERE 	KODE_KLAIM = '$ls_kode_klaim'
				AND ROWNUM = 1
				";
		$DB->parse($sql_cek_bpjstku);
		$DB->execute();
		$row = $DB->nextrow();
		$ls_kanal_layanan_bpjstku = $row["KANAL_PELAYANAN"];
		$ls_kode_tipe_klaim = $row["KODE_TIPE_KLAIM"];
		$ls_cnt_lumpsum = $row["CNT_LUMPSUM"];
		$ls_cnt_berkala = $row["CNT_BERKALA"];

		if ($ls_kanal_layanan_bpjstku == "25")
		{
			$ls_kode_jenis_dokumen = "JD105";
		}
		else
		{
			if($ls_kode_tipe_klaim == "JHT01")
			{
				$ls_kode_jenis_dokumen = "JD101";
			}
			else if($ls_kode_tipe_klaim == "JKM01")
			{
				$ls_kode_jenis_dokumen = "JD102";
			}
			else if($ls_kode_tipe_klaim == "JPN01")
			{
				// cek lumsum atau berkala
				// JD103	DOKUMEN KLAIM JPN LUMSUM
				if ($ls_cnt_lumpsum > 0)
				{
					$ls_kode_jenis_dokumen = "JD103";
				}
				// JD104	DOKUMEN KLAIM JPN BERKALA
				if ($ls_cnt_berkala > 0)
				{
					$ls_kode_jenis_dokumen = "JD104";
				}
			}
			else if($ls_kode_tipe_klaim == "JKP01")
			{
				//JD107 DOKUMEN KLAIM JKP
				$ls_kode_jenis_dokumen = "JD107";
			}
		}
		
		//submit data persetujuan klaim  --------------------------------------------
		$qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_APPROVAL('$ls_kode_klaim','$ln_no_level','$username',:p_sukses,:p_mess);END;";											 	
		$proc = $DB->parse($qry);				
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
		$DB->execute();				
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;	
		
		if ($ls_sukses == "1")
		{
			// Cek Klaim
			$qry_cek_klaim="
			select count(*) jml_approval 
			from pn.pn_klaim_approval a 
			where a.kode_klaim = '$ls_kode_klaim'
			and a.no_level =
			(
				select max(b.no_level) from pn.pn_klaim_approval b where b.kode_klaim = a.kode_klaim
			)
			and a.kode_klaim in (select kode_klaim from pn.pn_klaim where kanal_pelayanan in (select KODE from MS.MS_LOOKUP where TIPE = 'KANALKLM' and KATEGORI = 'DOKUMEN_DIGITAL'))
			";
			$DB->parse($qry_cek_klaim);
				if($DB->execute()){
					if($row=$DB->nextrow()){
						$cek_klaim=$row['JML_APPROVAL'];    
					}
				}

			// cek kanal BPJSTKU
			$sql_cek_bpjstku = "
					SELECT 	KANAL_PELAYANAN
					FROM 		PN.PN_KLAIM
					WHERE 	KODE_KLAIM = '$ls_kode_klaim'
					AND ROWNUM = 1
					";
			$DB->parse($sql_cek_bpjstku);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_kanal_layanan_bpjstku = $row["KANAL_PELAYANAN"];
			
			if($cek_klaim > 0){
			//start digital arsip utk lapak asik
			
			// start cek data user approval
			$qry_cek_user_approval = "
					select a.*, 
					(
					select b.nik from ms.sc_user b
					where b.kode_user = a.petugas_approval
					and rownum=1
					) npk,
					(
					select b.nama_user 
					from ms.sc_user b
					where b.kode_user = a.petugas_approval
					and rownum=1
					) nama_user,
					(
					select b.nama_jabatan 
					from ms.ms_jabatan b
					where b.kode_jabatan = a.kode_jabatan
					and nvl(aktif,'T') = 'Y'
					and rownum=1
					) nama_jabatan
					from pn.pn_klaim_approval a
					where a.kode_klaim = '$ls_kode_klaim'
					and a.no_level     =
					(select max(b.no_level)
					from pn.pn_klaim_approval b
					where b.kode_klaim = a.kode_klaim
					)
					and a.kode_klaim in
					(select kode_klaim from pn.pn_klaim where kanal_pelayanan in (select KODE from MS.MS_LOOKUP where TIPE = 'KANALKLM' and KATEGORI = 'DOKUMEN_DIGITAL')
					)
			";
			
			$DB->parse($qry_cek_user_approval);
				if($DB->execute()){
					if($row=$DB->nextrow()){
						$p_kode_kantor = $row['KODE_KANTOR'];
						$p_npk = $row['NPK'];
						$p_kode_user = $row['PETUGAS_APPROVAL'];
						$p_nama_user = $row['NAMA_USER'];
						$p_nama_jabatan = $row['NAMA_JABATAN'];				 
					}
				}
					
			// end cek data user approval
			
			$qry = "
			BEGIN 
				PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOK_PENETAPAN
				(
					'$ls_kode_klaim',
					:P_KODE_KANTOR      ,
					:P_NPK              ,
					:P_KODE_USER        ,
					:P_NAMA_USER        ,
					:P_NAMA_JABATAN     ,
					:P_SUKSES           ,
					:P_MESS              
				);
			END;";

			$proc = $DB->parse($qry);       
			oci_bind_by_name($proc, ":P_SUKSES", $p_sukses, 2);
			oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);
			oci_bind_by_name($proc, ":P_KODE_KANTOR", $p_kode_kantor,100);
			oci_bind_by_name($proc, ":P_NPK", $p_npk,100);
			oci_bind_by_name($proc, ":P_KODE_USER", $p_kode_user,100);
			oci_bind_by_name($proc, ":P_NAMA_USER", $p_nama_user,100);
			oci_bind_by_name($proc, ":P_NAMA_JABATAN", $p_nama_jabatan,100);

			$ls_sukses = $p_sukses;
			$ls_mess = $p_mess; 
			$ls_kode_kantor = $p_kode_kantor;
			$ls_npk = $p_npk;
			$ls_nama_jabatan = $p_nama_jabatan;
			$ls_kode_user = $p_kode_user;
				
			if ($ls_npk != "")
			{
				$headers = array(
					'Content-Type: application/json',
				'X-Forwarded-For: ' . $ipfwd
					);
					
					$sqlbucket="
						select to_char(sysdate, 'yyyymm') blth,
						(select kode_kantor from pn.pn_klaim where kode_klaim = '$ls_kode_klaim') kode_kantor
						from dual
						";

					$DB->parse($sqlbucket);
					$DB->execute();
					$row = $DB->nextrow();
					$ls_blth = $row["BLTH"];
					$ls_kode_kantor_bucket = $row["KODE_KANTOR"];


					$ls_nama_bucket_storage = "arsip";
					$ls_nama_folder_storage = "$ls_kode_kantor_bucket/$ls_blth/klaim";
					
					// cek layanan BPSJSTKU
					if ($ls_kanal_layanan_bpjstku == "25")
					{
						$ls_kode_jenis_dokumen = "JD105";
						$ls_kode_dokumen = "JD105-D1006";
					}
					else
					{
						if($ls_kode_tipe_klaim == "JHT01")
						{
							$ls_kode_jenis_dokumen = "JD101";
							$ls_kode_dokumen = "JD101-D1006";
							$ls_kode_report_penetapan = "PNR900118.rdf";
						}
						else if($ls_kode_tipe_klaim == "JKM01")
						{
							$ls_kode_jenis_dokumen = "JD102";
							$ls_kode_dokumen = "JD102-D1005";
							$ls_kode_report_penetapan = "PNR900115.rdf";
						}
						else if($ls_kode_tipe_klaim == "JKK01")
						{
							$ls_kode_jenis_dokumen = "JD108";
							$ls_kode_dokumen = "JD108-D1005";
							$ls_kode_report_penetapan = "PNR900113.rdf";
						}
						else if($ls_kode_tipe_klaim == "JPN01")
						{
							// JD103	DOKUMEN KLAIM JPN LUMSUM
							if ($ls_cnt_lumpsum > 0)
							{
								// JD103-D1005	SURAT PENETAPAN KLAIM
								$ls_kode_jenis_dokumen = "JD103";
								$ls_kode_dokumen = "JD103-D1005";
								$ls_kode_report_penetapan = "PNR900106.rdf";
							}
							// JD104	DOKUMEN KLAIM JPN BERKALA
							if ($ls_cnt_berkala > 0)
							{
								// JD104-D1005	SURAT PENETAPAN KLAIM
								$ls_kode_jenis_dokumen = "JD104";
								$ls_kode_dokumen = "JD104-D1005";
								$ls_kode_report_penetapan = "PNR900105.rdf";
							}
						}
						else if($ls_kode_tipe_klaim == "JKP01"){
							//JD107-D1005 SURAT PENETAPAN KLAIM JKP #01122021

							if($ls_tahap_jkp=="I"||$ls_tahap_jkp=="1"){
								$ls_kode_jenis_dokumen = "JD107";
								$ls_kode_dokumen = "JD107-D1005";
								$ls_kode_report_penetapan = "PNR901115.rdf"; //PENETAPAHN JKP TAHAP 1
							}else{
								$ls_kode_jenis_dokumen = "JD107";
								$ls_kode_dokumen = "JD107-D1005";
								$ls_kode_report_penetapan = "PNR902115.rdf"; //PENETAPAHN JKP TAHAP 2-6
							} 	
						}
					}
				
					
					//"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$ls_kode_report_penetapan."%26userid%3D%2Fdata%2Freports%2F%26%26QKODEKLAIM%3D%27".$ls_kode_klaim."%27", 

				$data_storedocument = array(
					"chId" => "SMILE", 
					"reqId" => $username, 
					"idDokumen" => $ls_kode_klaim, 
					"kodeJenisDokumen" => $ls_kode_jenis_dokumen, 
					"kodeDokumen" => $ls_kode_dokumen, 
					"urlDokumen" => $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$ls_kode_report_penetapan."%26userid%3D%2Fdata%2Freports%2F%26%26QKODEKLAIM%3D%27".$ls_kode_klaim."%27", 
					"namaBucketTujuan" => $ls_nama_bucket_storage, 
					"namaFolderTujuan" => $ls_nama_folder_storage 
					); 

				$result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);

				$idArsipx=$result_storedocument->idArsip;

				$data_presign = array(
					"chId" => "SMILE",
				"reqId" => $username,
				"idArsip" => $idArsipx
				);
				
				if ($result_storedocument->ret == "0") {

					$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
					if ($result_presign->ret == "0") {
					// sign document
					$idArsip = $result_presign->data->idArsip;
					$docSigns = $result_presign->data->docSigns;
					if (ExtendedFunction::count($docSigns) > 0) {
					$newDocSigns = array();
					foreach ($docSigns as $sign) {
						$sign->dataUserSign = array(
						"kodeKantor" =>  $ls_kode_kantor,
						"npk" => $ls_npk,
						"namaJabatan" => $ls_nama_jabatan,
						"petugas" =>  $ls_kode_user
						);  
					$sign->action = "sign";
					array_push($newDocSigns, $sign);
					}

					$data_sign = array(
						"chId" => "SMILE",
						"reqId" => $username,
						"idArsip" => $idArsip,
						"docSigns" => $newDocSigns
				);
					$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
					}
				}    
				}
			}
			}
			//end digital arsip
			
			echo "<script language=\"JavaScript\" type=\"text/javascript\">";
			echo "window.close();";
			echo "</script>";
		}

		// -----------------------------start update pending matters 09032022------------------------
	} else {
			$ls_msg_session = 'Mohon maaf proses pembayaran gagal karena session habis, silahkan melakukan login ulang kembali dan memproses kembali klaim.';
			echo "<script language=\"JavaScript\" type=\"text/javascript\">";
			echo "window.opener.location.replace('../ajax/$ls_sender?task=View&root_sender=$ls_root_sender&sender=$ls_root_sender&activetab=2&sender_mid=$ls_sender_mid&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&tglawaldisplay=$ld_tglawaldisplay&tglakhirdisplay=$ld_tglakhirdisplay&msg=$ls_msg_session');";
			echo "window.close();";
			echo "</script>";
	}
		// -----------------------------end update pending matters 09032022------------------------

}
		 
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?=$pagetitle;?></title>
  <meta name="Author" content="JroBalian" />
  <link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.css" />
  <script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>
  <script type="text/javascript" src="../../javascript/common.js"></script>
</head>

<style type="text/css">
<!-- 
body{
  font-family: tahoma, arial, verdana, sans-serif; 
  font-size:11px;
	background : #fbf7c8;
} 
a {
  text-decoration:none;
	color:#008040;
  }

a:hover {
	color:#68910b; 
  text-decoration:none;
  }
-->
</style>

<body>
	<img src="../../images/warning.gif" align="left" hspace="10" vspace="0"><b><font color="#ff0000">Proses...</font></b>	
	
  <form name="adminForm" id="adminForm" method="post" action="<?=$PHP_SELF;?>">
    <div id="formframe">
      <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
      <input type="hidden" id="no_level" name="no_level" value="<?=$ln_no_level;?>">	
								
			<input type="submit" id="btnsubmit" name="btnsubmit" value="" style="color:#fbf7c8;"/>
    </div>													 										
  </form>	

  <script language="javascript">
  		document.getElementById("btnsubmit").click();
  </script>
	
</body>
</html>						
	