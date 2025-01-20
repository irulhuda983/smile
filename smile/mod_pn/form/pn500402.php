<?
$pagetype="report";
$gs_pagetitle = "PN5004 - PEMBAYARAN KLAIM LUMPSUM (SENTRALISASI REKENING KANTOR PUSAT)";
//require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/header_app.php";
require_once "../../includes/conf_global.php";
$mid = $_REQUEST["mid"];
/*--------------------- Form History -----------------------------------------
File: pn500401.php

Deskripsi:
-----------
File ini dipergunakan untuk pembayaran klaim berkala

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
Hist: - 02/10/2017 : Pembuatan Form (Tim SIJSTK)	
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
//--------------------- start button action ----------------------------------
$ls_rg_kategori	= !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];
if ($ls_rg_kategori=="")
{
 	 $ls_rg_kategori = "3";
}

$ls_kode_cara_bayar			= $_POST['kode_cara_bayar'];																																								
$ls_kode_buku						= $_POST['kode_buku'];
$ls_kode_bank						= $_POST['kode_bank'];
$ls_nama_bank						= $_POST['nama_bank'];
//------------------- Function WS Sentralisasi ---------------------------------

//WS Get ID Bank yang di ingin dicari berdasakan kode bank ---------------------
function get_id_bank(){
		global $wsIp;
		$USER = $_SESSION["USER"];
		$url 	= $wsIp.'/JSOPG/GetListBank';
		
		// set HTTP header
    $headers = array(
      'Content-Type'=> 'application/json',
    	'X-Forwarded-For'=> $ipfwd,
    );
   
    // set POST params
    $data = array(
    	'chId'  => 'CORE',
			'reqId' => $USER
    );
		
    // Open connection
    $ch = curl_init();
    
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    // Execute post
    $result 	 		 	 		= curl_exec($ch);
    $resultArray 				= json_decode($result);
		return $resultArray;
}

//WS Send Email-----------------------------------------------------------------
function send_email($data_array, $kd_klaim){
		$resultArrayDoPayment	  = $data_array;
		$res_email 	 		 				= $resultArrayDoPayment->data->EMAIL;
		$res_id_dokumen 				= $resultArrayDoPayment->data->ID_DOKUMEN;
		$res_mata_uang					= $resultArrayDoPayment->data->MATA_UANG;
		$res_nama_bank_penerima = $resultArrayDoPayment->data->BANK_TUJUAN;
		$res_nama_rek_penerima 	= $resultArrayDoPayment->data->NAMA_REK_TUJUAN;
		$res_norek_penerima			= $resultArrayDoPayment->data->NOREK_TUJUAN;
		$res_tgl_transfer 			= $resultArrayDoPayment->data->TGL_TRANSFER;
		$res_tgl_transaksi_bank = $resultArrayDoPayment->data->TGL_TRANSAKSI_BANK;
		$res_nominal 						= $resultArrayDoPayment->data->NOMINAL;
		$res_kode_ref_bank 			= $resultArrayDoPayment->data->KODE_REFERENSI_BANK;
		$res_keterangan 				= $resultArrayDoPayment->data->KETERANGAN;
		
		$email_to		 = $res_email; //----INI NANTI DIGANTI DENGAN EMAIL PENERIMA DOKUMEN	
		$data_arrays = array('SUBJECT'=> 'Notifikasi Pembayaran Klaim - No : '.$kd_klaim,
										 		 'BODY'		=> '',
										 		 'CONTENT'=> '<P>Telah dibayarkan perihal klaim dengan rincian sebagai berikut :</P><br/>ID Klaim : '.$kd_klaim.'<br/>Nama Penerima : '.$res_nama_rek_penerima.'<br/>No Rekening Penerima : '.$res_norek_penerima.'<br/>Bank Penerima : '.$res_nama_bank_penerima.'<br/>Tanggal Transfer : '.$res_tgl_transfer.'<br/>Keterangan : '.$res_keterangan.'<br/>Nilai Pencairan Klaim: Rp '.number_format($res_nominal,0,',','.').'<br/>Nama Pengirim : BPJS Ketenagakerjaan<br/><br/><p>Surel ini dikirimkan secara otomatis dan tidak untuk dibalas. Terima kasih.</p>');
		$client = new SoapClient(WSCOM, array("exceptions" => 0, "trace" => 1, "encoding" => "UTF-8", 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd.'')))));
		//"location"=>str_replace("?wsdl","",WSCOM),
		$subject = $data_arrays['SUBJECT'];
		$body 	 = $data_arrays['BODY'];
		$content = $data_arrays['CONTENT'];
		$body_html = '<div id="mainbox" style="font-family:Verdana, Geneva, sans-serif;font-size:12px;background-color:white;">'.
                 '<div id="body" style="width:100%;background-color:#fff;">'.$content.'<br/>';
		$body_html.= '<div class="clearMe" style="float:none;clear:both;"></div></div>'.
                 '<div class="clearMe" style="float:none;clear:both;"></div><br/><br/>'.
                 '<div id="footer" style="width:100%;background-color:white;font-size:9px;"></div>'.
                 '</div>';
 		$response = $client->sendEmail(array
																				('cfg'		 => "noreply.smile",
                                         'from'		 => "noreply@bpjsketenagakerjaan.go.id",
                                         'to'			 => $email_to,
                                         'cc'			 => '',
                                         'bcc'		 => 'aditya.permana@bpjsketenagakerjaan.go.id',
                                         'subject' => $subject, 
                                         'body'		 => 'ASD',
                                         'isHTML'	 => 'Y',
                                  			 'isAttach'=> 'N',
                                  			 'attach'	 => '',
                         								 'bodyHTML'=> base64_encode($body_html)
                    										 )
																	);
		//var_dump($response);exit; 
}		 

//WS Do Payment ----------------------------------------------------------------
function do_payment($kode_trf){
		global $wsIp;
		$USER = $_SESSION["USER"];
		$url 	= $wsIp.'/JSOPG/DoPayment';
		
		// set HTTP header
    $headers = array(
      'Content-Type'=> 'application/json',
    	'X-Forwarded-For'=> $ipfwd,
    );
   
    // set POST params
    $data = array(
    	'chId'  => 'CORE',
			'reqId' => $USER,
			'KODE_TRANSFER' => $kode_trf
    );
		
		// var_dump($data);exit;
    // Open connection
    $ch = curl_init();
    
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    // Execute post
    $result 	 		 	 		= curl_exec($ch);
    $resultArray 				= json_decode($result);
		return $resultArray;
}

//WS Request Transfer ----------------------------------------------------------
function request_transfer($bank_asal, $kode_bank_asal, $nama_bank_penerima, $metode_transfer, $norek_asal, $norek_tujuan, $nama_rek_tujuan, $tanggal_transfer, $mata_uang, $nominal, $ls_kode_klaim, $ls_kd_prg, $ls_kode_tipe_penerima, $keterangan_trf, $email, $hp_penerima, $kode_bank_penerima, $kode_transfer){
		global $wsIp;
		$USER = $_SESSION["USER"];
		/* KETERANGAN REQUEST :
			 $bank_asal 	 			=> Pilihan BANK dari /GetListBank
			 $kode_bank_asal 		=> Pilihan KODE_BANK dari /GetListBank
			 $bank_tujuan 			=> Pilihan NAMA_BANK dari /GetListAntarBank (pilihan BANK_ASAL) utk antar bank
			 $metode_transfer 	=> Pilihan METODE_TRANSFER dari pilihan /GetListBank (BANK)
			 $norek_asal 		 		=> NOMOR REK DEBET (BPJSTK)
			 $norek_tujuan 	 		=> NOMOR REK KREDIT (PENERIMA)
			 $nama_rek_tujuan 	=> NAMA REK PENERIMA
			 $tgl_transfer 	 		=> TANGGAL TRANSFER DD-MM-YYYY HH24:MI:SS
			 $mata_uang 			 	=> Pilihan MATA_UANG dari pilihan /GetListBank (BANK)
			 $nominal 				 	=> NOMINAL TRANSFER ANGKA TANPA GROUP (TITIK UTK DESIMAL)
			 $p_kd_klaim 		 		=> KODE KLAIM JURNAL DARI APPROVAL
			 $p_kd_prg 			 		=> 1 (JHT), 2 (JKK), 3 (JKM), 4 (JP)
			 $p_tipe_penerima 	=> Pilihan: TENAGA KERJA, PERUSAHAAN, AHLI WARIS, dll
			 $keterangan 		 		=> OPTINAL KETERANGAN TRANSFER
			 $email 					 	=> OPTIONAL EMAIL PENERIMA
			 $hp_penerima 		 	=> OPTIONAL NOMOR HP PENERIMA
			 $kode_bank_tujuan 	=> UNTUK TRANSFER ANTAR BANK, pilihan KODE dari /GetListAntarBank
			 $kode_transfer 		=> MANDATORY UNTUK PROSES TRANSFER ULANG
		*/
		
		//replace karakter . dan - 
		$norek_asal = str_replace('.','',$norek_asal);
		$norek_asal = str_replace('-','',$norek_asal);
		$norek_asal = str_replace(' ','',$norek_asal);
		
		$url = $wsIp.'/JSOPG/SaveTxTrfBank';
		
		// set HTTP header
    $headers = array(
      'Content-Type'=> 'application/json',
    	'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params
		$data = array(
    	"chId"  => "CORE",
    	"reqId" => $USER,
    	"listDataTrx" => array(	
											 				array (
                          					 "BANK_ASAL" 				=> $bank_asal,   
                              			 "KODE_BANK_ASAL"		=> $kode_bank_asal,   
                              			 "BANK_TUJUAN"			=> $nama_bank_penerima,   
                              			 "METODE_TRANSFER"	=> $metode_transfer,   
                              			 "NOREK_ASAL"				=> $norek_asal,   
                              			 "NOREK_TUJUAN"			=> $norek_tujuan,   
                              			 "NAMA_REK_TUJUAN" 	=> $nama_rek_tujuan,   
                              			 "TGL_TRANSFER"			=> $tanggal_transfer,   
                              			 "MATA_UANG"				=> $mata_uang,   
                              			 "NOMINAL"					=> $nominal,   
                              			 "P_KD_KLAIM"				=> $ls_kode_klaim,   
                              			 "P_KD_PRG"					=> $ls_kd_prg,   
                              			 "P_TIPE_PENERIMA" 	=> $ls_kode_tipe_penerima,   
                              			 "KETERANGAN"			 	=> $keterangan_trf,   
                              			 "EMAIL"						=> $email,   
                              			 "HP_PENERIMA"			=> $hp_penerima,   
                              			 "KODE_BANK_TUJUAN"	=> $kode_bank_penerima,   
                              			 "KODE_TRANSFER"	  => $kode_transfer  
                  									)
											)
    );
		//$resultArray = json_encode($data);
		//var_dump($resultArray);exit;
    
		// Open connection
    $ch = curl_init();
    
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    // Execute post
    $result = curl_exec($ch);
    $resultArray = json_decode($result);
		return $resultArray;
}
//------------------- End of Function WS Sentralisasi --------------------------
//switch form ------------------------------------------------------------------
if ($ls_rg_kategori=="4") //jika yg diklik adalah LUMPSUM
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn500403.php?mid=$mid');";
  echo "</script>";
}else if ($ls_rg_kategori=="2") //jika yg diklik adalah BERKALA
{
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('../form/pn500401.php?mid=$mid');";
  echo "</script>";
}


if($trigersubmit=='1')
{	
		$msg = '';
  	// Proses akan dimulai dari data yang pertama sampai data terakhir
  	for($i=0, $max_i=ExtendedFunction::count($cebox); $i<$max_i; $i++) 
  	{
  		if (${"s".$cebox[$i]} !="")
      {
    			$ls_kode_klaim  			 = ${"kode_klaim".$cebox[$i]};
  				$ls_kode_tipe_penerima = ${"kode_tipe_penerima".$cebox[$i]};
  				$ls_kd_prg  					 = ${"kd_prg".$cebox[$i]};
					$ln_no_level  				 = ${"no_level".$cebox[$i]};
  				$ln_max_level  				 = ${"max_level".$cebox[$i]};
					$kode_bank_asal 			 = ${"kode_bank_asal".$cebox[$i]};
					$kode_bank_penerima  	 = ${"kode_bank_penerima".$cebox[$i]};
					$nama_bank_penerima		 = ${"nama_bank_penerima".$cebox[$i]};
					$norek_asal 					 = ${"norek_asal".$cebox[$i]};
					$norek_tujuan					 = ${"norek_tujuan".$cebox[$i]};
					$nama_rek_tujuan  		 = ${"nama_rek_penerima".$cebox[$i]};
					$tanggal_transfer 		 = date("d-m-Y,h:m:s");
					$mata_uang 						 = "IDR"; //Masih di hardcode
					$nominal 							 = ${"nominal_bayar".$cebox[$i]};
					$hp_penerima					 = ${"hp_penerima".$cebox[$i]};
					//$email 								 = 'aditya.permana@bpjsketenagakerjaan.com'; //email dummy
					$keterangan_trf 			 = 'TEST SENTRALISASI';
					$kode_transfer				 = ""; //Kosong karena diisi ketika digunakan transfer ulang di hari yang sama
					
  				//approval penetapan -------------------------------------------------
					if ($ln_no_level==$ln_max_level)
					{
					 	//baca ws transfer -------------------------------------------------
						$resultArray = get_id_bank();
          		for($i=0;$i<=($resultArray->numrows)-1;$i++){
          				if($resultArray->data[$i]->KODE_BANK==$kode_bank_asal){
          						$id_bank_selected = $resultArray->data[$i]->BANK;
											$metode_transfer  = $resultArray->data[$i]->METODE_TRANSFER[0]->KODE;																	
          				}
          	}
						$bank_asal = $id_bank_selected;
						
						//get email account
						$sql_get_email = "select email from sijstk.pn_klaim_penerima_manfaat where kode_klaim = '$ls_kode_klaim' and kode_tipe_penerima = '$ls_kode_tipe_penerima' ";
						$DB->parse($sql_get_email);
						$DB->execute();
						$data = $DB->nextrow();
						$email = $data['EMAIL'];			
						
						//hit ws request transfer
						//kondisi ketika transfer antar rekening di dalam satu bank yang sama
						if($kode_bank_asal==$kode_bank_penerima){
								$kode_bank_penerima = "";
								$nama_bank_penerima = $bank_asal;
								$metode_transfer 		= "ATR";
						}
	
						if($bank_asal!=""){
							 $resultArrayReqTrf = request_transfer($bank_asal, $kode_bank_asal, $nama_bank_penerima, $metode_transfer, $norek_asal, $norek_tujuan, $nama_rek_tujuan, $tanggal_transfer, $mata_uang, $nominal, $ls_kode_klaim, $ls_kd_prg, $ls_kode_tipe_penerima, $keterangan_trf, $email, $hp_penerima, $kode_bank_penerima, $kode_transfer);
							 $kode_transfer 		= $resultArrayReqTrf->data[0]->KODE_TRANSFER;
						}
						
						//hit ws do payment
						if($kode_transfer!=""){
							 $resultArrayDoPayment = do_payment($kode_transfer);
							 if($resultArrayDoPayment->ret=="0"){
							 		echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  								echo "alert('TRANSFER SUKSES');";
  								echo "</script>";
									$ls_sukses = "1";
									//var_dump($resultArrayDoPayment);exit;
									//send_email($resultArrayDoPayment, $ls_kode_klaim);							
							 }else{
							 		echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  								echo "alert('GAGAL TRANSFER');";
  								echo "</script>";
									$ls_sukses = "00";
									//var_dump($resultArrayDoPayment);exit;
							 }
						}
						//jika transfer sukses --------------------------------------------
					  if ($ls_sukses=="1")
						{
								$qry = "BEGIN SIJSTK.P_PN_PN5001.X_POST_APPROVAL_SENTRALISASI ( ".
      						 	 "			'$ls_kode_klaim','$ls_kode_tipe_penerima','$ls_kd_prg','$ln_no_level','$ln_max_level', ".
      						 	 "			'$username',:p_sukses,:p_mess);END;";											 	
                $proc = $DB->parse($qry);				
                oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
             		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
                $DB->execute();				
                $ls_sukses = $p_sukses;	
            		$ls_mess 	 = $p_mess;
      					$msg 			.= "Approval klaim siap bayar untuk kode kolaim ".${"kode_klaim".$cebox[$i]}." berhasil..."."<br>";
									
								//Hit ws untuk send email
								send_email($resultArrayDoPayment, $ls_kode_klaim);					 
						}else
						{
							$msg .= "Approval klaim siap bayar untuk kode klaim ".${"kode_klaim".$cebox[$i]}." gagal..."."<br>";
						}  
					}else
					{
        		$qry = "BEGIN SIJSTK.P_PN_PN5001.X_POST_APPROVAL_SENTRALISASI ( ".
    						 	 "			'$ls_kode_klaim','$ls_kode_tipe_penerima','$ls_kd_prg','$ln_no_level','$ln_max_level', ".
    						 	 "			'$username',:p_sukses,:p_mess);END;";											 	
            $proc = $DB->parse($qry);				
            oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
        		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
            $DB->execute();				
            $ls_sukses = $p_sukses;	
        		$ls_mess = $p_mess;
						$msg .= "Approval klaim siap bayar untuk kode klaim ".${"kode_klaim".$cebox[$i]}." berhasil..."."<br>";		
  				}//end if ($ln_no_level==$ln_max_level) 
  		}	//end if (${"s".$cebox[$i]} !="")
  	} //end for($i=0, $max_i=ExtendedFunction::count($cebox); $i<$max_i; $i++) 
}
//--------------------- end button action ------------------------------------
	
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript">
function doBayar() {
	window.open('../ajax/pn500401_popupbayar.php','bayar','width=400,height=100,top=100,left=100,scrollbars=yes');
}			
</script>
<?
//--------------------- end fungsi lokal javascript --------------------------
?>

<?
//--------------------- start list data --------------------------------------
// Definisikan Filter Field
$arr_filter = array('A.NAMA_TK'  	=> 'Nama TK',
										'A.KPJ'  	=> 'No. Referensi',					
										'A.KODE_KLAIM' => 'Kode Klaim' 														 
											);
if (isset($searchtxt) && $searchtxt!="")
{
	$searchtxt					=	 strtoupper($searchtxt);
	if ($pilihsearch		== "")
	{
  	$filtersearch='and (';
  	$arr_key = array_keys($arr_filter);
  	for($i=0, $max_i=ExtendedFunction::count($arr_filter); $i<$max_i; $i++) {
  		$filtersearch		.=	 (($i>0)?'or ':'').$arr_key[$i]." like '".$searchtxt."' ";
  	} unset($arr_key);
  	$filtersearch.=') ';
	}else
	{
  	if(strtoupper(substr($pilihsearch,0,5))=='DATE(')
  	{
    	$arr_dateparam = explode(',',substr($pilihsearch,5,strlen($pilihsearch)-6));
    	$fieldsearch   = " TO_CHAR(".$arr_dateparam[0].", '".$arr_dateparam[1]."') ";
    	unset($arr_dateparam);
    
    	$filtersearch		=	 "and ".$fieldsearch." like '".$searchtxt."' ";
  	}else{
  		$filtersearch		=	 "and ".$pilihsearch." like '".$searchtxt."' ";
  	}
	}			   
}
//filter kantor
if (strlen($gs_kantor_aktif)==3) 
{
 	$filterkantor = "and a.kode_kantor_klaim = '$gs_kantor_aktif' "; 
}else
{
 	$filterkantor = "and a.kode_kantor_klaim in ".
      						"(	select kode_kantor from sijstk.ms_kantor ".
      						"		start with kode_kantor = '$gs_kantor_aktif' ".
      						"		connect by prior kode_kantor = kode_kantor_induk ".
      						"	) ";
}
// Order
$o = strtoupper($_GET['o']);
if($o!='' && $o=='DESC') $o='DESC'; else $o='ASC';
if($_GET['f']!=''){
	$ls_order = ' ORDER BY '.$_GET['f'].' '.$o.' ';
}else{
	$ls_order = ' ORDER BY a.kode_klaim, a.kode_tipe_penerima,a.kd_prg '.$o.' ';
}
		
$url = 'pn500402.php';
$rows_per_page = 10; // untuk paging
$sql = 	"select ".
        "    klaim_id, kode_klaim, kode_tipe_penerima, nama_tipe_penerima, kd_prg, nm_prg, no_level, kode_user, kode_jabatan, kode_kantor_approval, ".
        "		 case when no_level = max_level then 'Bayar' else 'Approval '||no_level end ket_level, ".
				"    max_level, kode_kantor_klaim, kode_segmen, no_klaim, to_char(tgl_klaim,'dd/mm/yyyy') tgl_klaim, no_penetapan, to_char(tgl_penetapan,'dd/mm/yyyy') tgl_penetapan, ". 
        "    kpj, nama_pengambil_klaim, nom_manfaat_netto, nom_sudah_bayar, nom_sisa, nama_penerima, kode_bank_penerima, id_bank_penerima, bank_penerima, ". 
        "    no_rekening_penerima, nama_rekening_penerima, status_valid_rekening_penerima, ".
				"		 decode(a.status_valid_rekening_penerima,'Y','Valid','TIDAK VALID') ket_valid_rekening_penerima, ".
				"		 status_rekening_sentral, kantor_rekening_sentral, ". 
        "    handphone_penerima, npwp_penerima, kode_bank_pembayar, no_rekening_pembayar ".
        "from sijstk.vw_pn_approval_sentralisasi a ".
        "where a.kode_user = '$username' ".
        $filtersearch.					 					 
				$ls_order;
//echo $sql;
$total_rows  = f_count_rows($DB,$sql);
$total_pages = f_total_pages($total_rows, $rows_per_page);
$othervar		= "&mid=".$mid."&pilihsearch=".$pilihsearch."&searchtxt=".$searchtxt."&f=".$_GET['f']."&o=".$o;
if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] < 1 ) 
	{
  $_GET['page'] = 1;
} 
	else if ( $_GET['page'] > $total_pages ) 
	{
  $_GET['page'] = $total_pages;
}	
$start_row = f_page_to_row($_GET['page'], $rows_per_page);
$jmlrow		 = $rows_per_page;

?>
<table class="captionform">
	<tr>
		<td style="text-align:left;font: 14px Arial, Verdana, Helvetica, sans-serif;"><b><?=$gs_pagetitle;?></b></td> 
		<td align="right">Search By &nbsp
		<select name="pilihsearch" size="1" style="width:150px">
		<option value="">--ALL--</option>
		<?
		$arr_key = array_keys($arr_filter);
		for($i=0, $max_i=ExtendedFunction::count($arr_filter); $i<$max_i; $i++) {
		echo '<option value="'.$arr_key[$i].'"'.(($pilihsearch==$arr_key[$i])?' selected':'').'>'.$arr_filter[$arr_key[$i]].'</option>';
		} unset($arr_key);
		?>
		</select>
		<input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="30">
		<input type="submit" name="cari2" value="GO">
		</td>
	</tr>
	<tr>
		<td>
				<b>JENIS PEMBAYARAN &nbsp;&nbsp;
						<? 
            switch($ls_rg_kategori)
            {
            case '1' : $sel1="checked"; break;
            case '2' : $sel2="checked"; break;
						case '3' : $sel3="checked"; break;
						case '4' : $sel4="checked"; break;
            }
            ?>
						<input type="radio" name="rg_kategori" value="4" onclick="this.form.submit()"  <?=$sel4;?>>&nbsp;<font  color="#009999"><b>LUMPSUM</b></font>&nbsp; | &nbsp;
						<input type="radio" name="rg_kategori" value="2" onclick="this.form.submit()"  <?=$sel2;?>>&nbsp;<font  color="#009999"><b>JP BERKALA</b></font> &nbsp;&nbsp;
						<input type="radio" name="rg_kategori" value="3" onclick="this.form.submit()"  <?=$sel3;?>>&nbsp;<font  color="#009999"><b>LUMPSUM (SENTRALISASI KAPU)</b></font> &nbsp;&nbsp;
					</b>
		</td>	
	</tr>
</table>
<?
			
$sortvar    = "&mid=".$mid."&pilihsearch=".$pilihsearch."&searchtxt=".$searchtxt."&ld_tglawaldisplay=".$ld_tglawaldisplay."&ld_tglakhirdisplay=".$ld_tglakhirdisplay;
$o = $o=='ASC' ? 'DESC' : 'ASC';		
echo "<table  id=mydata cellspacing=0>";
echo "<tr>";
echo "<th class=awal>&nbsp;<input type=checkbox name=toggle value=\"\" onclick=\"checkAll(".$jmlrow."); \" /><b>Action</b></th>";
echo "<th style=width:150px;>&nbsp;<a href=\"$PHP_SELF?f=a.kode_klaim&o=$o$sortvar\"><b>Kode Klaim</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.no_penetapan&o=$o$sortvar\"><b>Tgl Penetapan</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.no_penetapan&o=$o$sortvar\"><b>No Penetapan</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.kpj&o=$o$sortvar\"><b>No. Ref</b></a></th>";
echo "<th>&nbsp;<a href=\"$PHP_SELF?f=a.nama_pengambil_klaim&o=$o$sortvar\"><b>Klaim a/n</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.nama_penerima&o=$o$sortvar\"><b>Nama Penerima</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.nama_penerima&o=$o$sortvar\"><b>Rek Penerima</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.nom_sisa&o=$o$sortvar\"><b>Jml Bayar</b></a></th>";
echo "<th><a href=\"$PHP_SELF?f=a.kode_kantor_klaim&o=$o$sortvar\"><b>Ktr</b></a></th>";
echo "</tr>";							 												 					 	  

$sql = f_query_perpage($sql, $start_row, $rows_per_page);
$DB->parse($sql);
$DB->execute();
$i=0;
$n=1;
while ($row = $DB->nextrow())
{	    	
	echo "<tr bgcolor=#".($n%2 ? "f3f3f3" : "ffffff").">"; 
	echo "<td class=awal>&nbsp;";
	echo "<input type=hidden name=cebox[] value=".$row["KLAIM_ID"].">";
	echo "<input type=hidden id=klaim_id".$i." name=klaim_id".$row["KLAIM_ID"]." value=".$row["KLAIM_ID"].">";
	echo "<input type=hidden id=kode_klaim".$i." name=kode_klaim".$row["KLAIM_ID"]." value=".$row["KODE_KLAIM"].">";
	echo "<input type=hidden id=kode_tipe_penerima".$i." name=kode_tipe_penerima".$row["KLAIM_ID"]." value=".$row["KODE_TIPE_PENERIMA"].">";
	echo "<input type=hidden id=kd_prg".$i." name=kd_prg".$row["KLAIM_ID"]." value=".$row["KD_PRG"].">";	
	echo "<input type=hidden id=no_level".$i." name=no_level".$row["KLAIM_ID"]." value=".$row["NO_LEVEL"].">";
	echo "<input type=hidden id=max_level".$i." name=max_level".$row["KLAIM_ID"]." value=".$row["MAX_LEVEL"].">";
	echo "<input type=hidden id=kode_kantor_klaim".$i." name=kode_kantor_klaim".$row["KLAIM_ID"]." value=".$row["KODE_KANTOR_KLAIM"].">";

	echo "<input type=hidden id=kode_bank_asal".$i." 		 name=kode_bank_asal".$row["KLAIM_ID"]." 	 		value='".$row["KODE_BANK_PEMBAYAR"]."'>";
	echo "<input type=hidden id=norek_asal".$i." 		     name=norek_asal".$row["KLAIM_ID"]." 					value='".$row["NO_REKENING_PEMBAYAR"]."'>";
	echo "<input type=hidden id=kode_bank_penerima".$i." name=kode_bank_penerima".$row["KLAIM_ID"]."  value='".$row["KODE_BANK_PENERIMA"]."'>";		
	echo "<input type=hidden id=nama_bank_penerima".$i." name=nama_bank_penerima".$row["KLAIM_ID"]." 	value='".$row["BANK_PENERIMA"]."'>";		
	echo "<input type=hidden id=norek_tujuan".$i." 			 name=norek_tujuan".$row["KLAIM_ID"]." 				value='".$row["NO_REKENING_PENERIMA"]."'>";
	echo "<input type=hidden id=nama_rek_penerima".$i."  name=nama_rek_penerima".$row["KLAIM_ID"]." 	value='".$row["NAMA_REKENING_PENERIMA"]."'>";
	echo "<input type=hidden id=nominal_bayar".$i." 		 name=nominal_bayar".$row["KLAIM_ID"]." 			value='".$row["NOM_SISA"]."'>";
	echo "<input type=hidden id=hp_penerima".$i." 			 name=hp_penerima".$row["KLAIM_ID"]." 				value='".$row["HANDPHONE_PENERIMA"]."'>";			
	
	echo "<input type=checkbox id=cb".$i." name=s".$row["KLAIM_ID"]." value=".$row["KLAIM_ID"]." onclick=\"isChecked(this.checked);\">&nbsp;".$row["KET_LEVEL"]."</td>";
	echo "<td>&nbsp;<a href=# onclick=\"NewWindow('../ajax/pn5003_approval_sentralisasi.php?task=View&kode_klaim=".$row["KODE_KLAIM"]."&dataid=".$row["KODE_KLAIM"]."&mid=$ls_mid&sender=pn500402.php','Info Klaim',1050,800,'yes')\">".$row["KODE_KLAIM"]."</a></td>";
  echo "<td>&nbsp;".$row["TGL_PENETAPAN"]."</td>";
	echo "<td>&nbsp;".$row["NO_PENETAPAN"]."</td>";
  echo "<td>&nbsp;".$row["KPJ"]."</td>";
	echo "<td>&nbsp;".$row["NAMA_PENGAMBIL_KLAIM"]."</td>";
  echo "<td>&nbsp;".$row["NAMA_PENERIMA"]."</td>";
	echo "<td>&nbsp;".$row["NO_REKENING_PENERIMA"]."-".$row["KET_VALID_REKENING_PENERIMA"]."</td>";
	echo "<td style=\"text-align:right\">&nbsp;". number_format($row["NOM_SISA"],2,".",",") ."</td>";	
	echo "<td>&nbsp;".$row["KODE_KANTOR_KLAIM"]."</td>";
	echo "</tr>";
	$i++; $n++;
}
										
?>
</table>
<table class="paging">
	<tr>
		<td align="left">Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>
		<td height="15" align="right">
		<b>Page :</b> <?php echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); ?>	
		</td>
	</tr>
</table>			

</br>

<!--
<table>
	<tr>
  	<td>
      Cara Bayar &nbsp;&nbsp;&nbsp;&nbsp;*
    </td>
		<td>
			<?
			if ($ls_kode_cara_bayar=="")
			{
        $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMCRBYR' and nvl(aktif,'T')='Y' and rownum=1 ";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();		
        $ls_kode_cara_bayar = $row['KODE'];			 	 
			}
			?>	
      <select size="1" id="kode_cara_bayar" name="kode_cara_bayar" value="<?=$ls_kode_cara_bayar;?>" class="select_format" style="background-color:#ffff99;width:300px;">
      <option value="">- cara byr --</option>
      <? 
      $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMCRBYR' and nvl(aktif,'T')='Y' order by seq";
      $DB->parse($sql);
      $DB->execute();
      while($row = $DB->nextrow())
      {
      echo "<option ";
      if (($row["KODE"]==$ls_kode_cara_bayar && strlen($row["KODE"])==strlen($ls_kode_cara_bayar))) { echo " selected"; }
      echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
      } 
      ?>
      </select>   			
  	</td>
	</tr>
</table>
-->



<div class="clear5"></div>
<div id="buttonbox">				
	<input type="hidden" name="trigersubmit" value="0">
  <div><input type="button" class="btn green" name="btnbayar" onclick="doBayar()" value="      BAYAR      "></div>
</div>
<div class="clear5"></div>

<?
if (isset($msg))		
{
  ?>
  <fieldset>
  <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
  <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
  </fieldset>		
  <?
}
?>														
<?										
//------------ end data grid -----------------------------------------------
?>
</div>	 				
<div id="clear-bottom"></div>
<input type="hidden" name="currentPage" id="currentPage" value="<?php echo $_GET['page']; ?>">			
<?
include "../../includes/footer_app.php";
?>
