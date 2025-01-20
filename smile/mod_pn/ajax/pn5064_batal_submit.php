<?
session_start();
include_once "../../includes/conf_global.php";
include_once "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
$pagetitle = "SMILE";
$gs_pagetitle = "Submit Pembatalan";
if ($username==""){$username = $_SESSION["USER"];}

$ls_kode_klaim		  = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];	
$ls_ket_batal			  = !isset($_GET['ket_batal']) ? $_POST['ket_batal'] : $_GET['ket_batal'];	
$ls_nomor_identitas = !isset($_GET['nomor_identitas']) ? $_POST['nomor_identitas'] : $_GET['nomor_identitas'];	
$ls_ket_batal			  = !isset($_GET['ket_batal']) ? $_POST['ket_batal'] : $_GET['ket_batal'];	
$ls_sender_mid		  = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];	
$gs_kantor_aktif 	  = $_SESSION['kdkantorrole'];	 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?=$pagetitle;?></title>
  <meta name="Author" content="JroBalian" />
  <!--<link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.css" />-->
	<link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.new.css?ver=1.2" />
	<script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/jquery.js"></script>
  <script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>

  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/common.js"></script>
  <script type="text/javascript" src="../../javascript/treemenu3.js"></script>
  <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
	
	<!-- tambahan baru -->
	<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
	<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
	<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
		
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
  <?
  if(isset($_POST["btnsubmit"]))
  {			
    if ($ls_kode_klaim!="" && $ls_ket_batal!="")
    {

      //-------------------------------------- START JKP 18/01/2022 -------------------------------------------------
      $sql = "SELECT KODE_TIPE_KLAIM, ID_POINTER_ASAL, KANAL_PELAYANAN, STATUS_KLAIM FROM PN.PN_KLAIM WHERE KODE_KLAIM = '$ls_kode_klaim' ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();		
      $ls_cek_kode_tipe_klaim       = $row['KODE_TIPE_KLAIM'];
      $ls_kode_pengajuan_siapkerja  = $row['ID_POINTER_ASAL'];
      $ls_cek_kanal_pelayanan       = $row['KANAL_PELAYANAN'];
      $ls_cek_status_klaim          = $row['STATUS_KLAIM'];
      

      if($ls_cek_kode_tipe_klaim == 'JKP01'){
        // 19/12/2023, [Problem ID : 802] - Penyesuaian Status Klaim
        // penambahan validasi bahwa khusus klaim JKP yang statusnya ('DISETUJUI', 'TUNDABAYAR') dan belum dibayar tidak bisa dibatalkan
        if ($ls_cek_status_klaim == 'DISETUJUI' OR $ls_cek_status_klaim == 'TUNDABAYAR')  
        {
          $ls_mess = "KODE KLAIM JKP DENGAN STATUS KLAIM DISETUJUI ATAU TUNDABAYAR TIDAK DAPAT DIBATALKAN.";
          echo "<script language=\"JavaScript\" type=\"text/javascript\">";
          echo "window.opener.fl_js_reloadFormEntry();";
          echo "window.close();";
          echo "window.opener.alert('$ls_mess')";
          echo "</script>";		
        }
        else
        {
        $ls_user = $_SESSION["USER"];
        $chId = "SMILE";

        // pembatalan klaim --------------------------------------------------
        $url1 = $wsIp.'/JSPN5044/DoPembatalan';
        
        // set HTTP header
        $headers1 = array(
          'Content-Type'=> 'application/json',
          'X-Forwarded-For'=> $ipfwd,
        );
        
        $data1 = array(
          'chId'				 			=> $chId,
          'reqId'				 			=> $ls_user,
          'KODE_KLAIM'				=> $ls_kode_klaim,
          'KET_BATAL'					=> $ls_ket_batal
        );
        
        // Open connection
        $ch1 = curl_init();
        
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data1));
        
        // Execute post ----------------------------------------------------
        $result1 = curl_exec($ch);
        $resultArray1 = json_decode(utf8_encode($result1));

        if ($resultArray1->ret==0){

                $url = $wsIp . "/JSKlaimJKP/BatalPengajuanKlaimJkp";

                $headers = array(
                  'Content-Type: application/json',
                  'X-Forwarded-For: ' . $ipfwd
                );

                $data_send_batal_klaim = array(
                  'chId'              => 'SMILE',
                  'reqId'             => $ls_user,
                  "kodePengajuan"		  => $ls_kode_pengajuan_siapkerja,
                  "keteranganBatal"   => "Dibatalkan",
                  "petugasRekam"		  => $ls_user
                );

                // Open connection
                $ch = curl_init();
                
                // Set the url, number of POST vars, POST data
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_send_batal_klaim));
                
                // Execute post ----------------------------------------------------
                $result_batal       = curl_exec($ch);
                $result_batal_klaim = json_decode(utf8_encode($result_batal));


                $sql = "SELECT A.FLAG_CEK_MENUNGGAK_IURAN from SIAPKERJA.SK_KLAIM_PENGAJUAN A WHERE A.KODE_KLAIM = '$ls_kode_klaim' ";											 	
                $ECDB->parse($sql);
                $ECDB->execute();
                $row = $ECDB->nextrow();		
                $ls_flag_cek_menunggak_iuran = $row['FLAG_CEK_MENUNGGAK_IURAN'];
                
                if($ls_flag_cek_menunggak_iuran != 'Y'){

                  $url_status = $wsIp . "/JSKlaimJKP/UpdateClaimStatus";

                  $data_send_status = array(
                    'chId'              => 'SMILE',
                    'reqId'             => $ls_user,
                    "kodePengajuan"		  => $ls_kode_pengajuan_siapkerja,
                    "statusPengajuan"	  => "KLJKP005",
                    "keterangan"		    => $ls_ket_batal,
                    "petugasRekam"		  => $ls_user
                  );
          
                  // Open connection
                  $ch = curl_init();
                  
                  // Set the url, number of POST vars, POST data
                  curl_setopt($ch, CURLOPT_URL, $url_status);
                  curl_setopt($ch, CURLOPT_POST, true);
                  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
                  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_send_status));
                  
                  // Execute post ----------------------------------------------------
                  $result_status       = curl_exec($ch);
                  $result_status_klaim = json_decode(utf8_encode($result_status));
                }

                if ($result_batal_klaim->ret==0)
                {
                  $ls_mess = "PEMBATALAN KLAIM BERHASIL, SESSION DILANJUTKAN..";
                  
                  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
                  echo "window.opener.fl_js_reload_post_submit();";
                  echo "window.close();";
                  echo "window.opener.alert('$ls_mess')";
                  echo "</script>";	 	  	 
                }else
                {
                  if ($resultArray->P_SUKSES =="-1" || $resultArray->P_SUKSES =="-2")
                  {
                    $ls_mess = "PEMBATALAN KLAIM GAGAL, ".$resultArray->P_MESS;
                  }else
                  {
                    $ls_mess = "PEMBATALAN KLAIM GAGAL, ".$resultArray->P_MESS." </br>".$resultArray->msg;	 
                  }
                  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
                  echo "window.opener.fl_js_reloadFormEntry();";
                  echo "window.close();";
                  echo "window.opener.alert('$ls_mess')";
                  echo "</script>";		
                }
        }else
        {
                if ($resultArray1->P_SUKSES =="-1" || $resultArray1->P_SUKSES =="-2")
                {
                  $ls_mess = "PEMBATALAN KLAIM GAGAL, ".$resultArray1->P_MESS;
                }else
                {
                  $ls_mess = "PEMBATALAN KLAIM GAGAL, ".$resultArray1->P_MESS." </br>".$resultArray1->msg;	 
                }
                echo "<script language=\"JavaScript\" type=\"text/javascript\">";
                echo "window.opener.fl_js_reloadFormEntry();";
                echo "window.close();";
                echo "window.opener.alert('$ls_mess')";
                echo "</script>";		
        }			
        }
      //-------------------------------------- END JKP 18/01/2022 -------------------------------------------------

      } 
    else {
        $ipDev  	= "";
        global $wsIp;
        $ls_user = $_SESSION["USER"];
        $chId = "SMILE";
        
        $url = $wsIp.'/JSPN5044/DoPembatalan';
        
        // set HTTP header
        $headers = array(
          'Content-Type'=> 'application/json',
          'X-Forwarded-For'=> $ipfwd,
        );
        
        $data = array(
          'chId'				 			=> $chId,
          'reqId'				 			=> $ls_user,
          'KODE_KLAIM'				=> $ls_kode_klaim,
          'KET_BATAL'					=> $ls_ket_batal
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
        
        // Execute post ----------------------------------------------------
        $result = curl_exec($ch);
        $resultArray = json_decode(utf8_encode($result));
        
        if ($resultArray->ret==0)
        {
          $ls_mess = "PEMBATALAN KLAIM BERHASIL, SESSION DILANJUTKAN..";

          // ========================================= start update reset antrian lapalasik 23022022 ===================================
          $query_lapakasik = "SELECT COUNT (1) KLAIM
                                FROM (SELECT a.kode_klaim_approval
                                        FROM antrian.atr_booking a
                                      WHERE     a.KODE_KANTOR = '$gs_kantor_aktif'
                                            AND a.kode_klaim_approval = '$ls_kode_klaim'
                                      UNION
                                      SELECT a.kode_klaim_approval
                                        FROM antrian.atr_booking_hist a
                                      WHERE     a.KODE_KANTOR = '$gs_kantor_aktif'
                                            AND a.kode_klaim_approval = '$ls_kode_klaim')";
          
          $ECDB->parse($query_lapakasik);
          $ECDB->execute();
          $row = $ECDB->nextrow();		
          $ls_cek_klaim_lapakasik = $row['KLAIM'];

          if($ls_cek_klaim_lapakasik > 0){ 

            $qry = "
              BEGIN 
              ANTRIAN.x_reset_pengajuan(
                  '$ls_kode_pengajuan_siapkerja',
                  '$gs_kantor_aktif',
                  '$ls_ket_batal',
                  '$ls_user',
                  :P_SUKSES,
                  :P_MESS
              ); 
              END;";


            $proc = $ECDB->parse($qry);       
            oci_bind_by_name($proc, ":P_SUKSES", $p_sukses,2);
            oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);
                

            if ($ECDB->execute()) {
              $ls_sukses = $p_sukses;
              // $ls_mess = $p_mess; 
                        
              if ($ls_sukses == "1") {
                $jsondata["ret"] = "0";
                $jsondata["msg"] = $ls_mess;
                $jsondata["kodeBooking"] = $ls_kode_pengajuan_siapkerja;
                echo json_encode($jsondata);
              }else{
                $ls_mess = $p_mess; 

                $jsondata["ret"] = "-1";
                $jsondata["msg"] = $ls_mess;
                $jsondata["kodeBooking"] = $ls_kode_pengajuan_siapkerja;
                echo json_encode($jsondata);
              }

            }else{
              $ls_mess = "Terjadi kesalahan pada server, silahkan coba beberapa saat lagi.";
              $jsondata["ret"] = "-3";
              $jsondata["msg"] = $ls_mess;
              echo json_encode($jsondata);
            }

          }
          // ========================================= end update reset antrian lapalasik 23022022 ===================================

          //-------------------------------------- START UPDATE KOREKSI VERIFIKASI DATA KLAIM PP03 26/07/2022 -------------------------------------------------

          $sql_pp03 = "SELECT KODE_AGENDA, KODE_KLAIM
                            FROM PN.PN_AGENDA_VERIFIKASI_JHT_TUKEP
                          WHERE KODE_KLAIM = '$ls_kode_klaim'
                        GROUP BY KODE_AGENDA, KODE_KLAIM ";											 	
          $DB->parse($sql_pp03);
          $DB->execute();
          $row_pp03 = $DB->nextrow();		
          $ls_kd_agenda_pp03  = $row_pp03['KODE_AGENDA'];
          $ls_kd_klaim_pp03   = $row_pp03['KODE_KLAIM'];


          if($ls_kd_klaim_pp03 !== ""){
          $qry_pp03 = "
          BEGIN 
          PN.P_PN_PN60010210.X_BATAL_KLAIM ('$ls_kd_agenda_pp03',
                '$ls_kd_klaim_pp03',
                '$ls_user',
                :P_MESS,
                :P_SUKSES);
          END;";

          $proc = $DB->parse($qry_pp03);       
          oci_bind_by_name($proc, ":P_SUKSES", $p_sukses,2);
          oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);
          $DB->execute();
          }

          $sql_rtw = "select count(*) cnt_rtw
                      from PN.PN_KLAIM a
                      where a.KODE_TIPE_KLAIM = 'JKK01'
                      and a.KODE_SEGMEN = 'PU'
                      and nvl(a.STATUS_BATAL,'X') = 'T'
                      and nvl(a.FLAG_RTW,'X') = 'Y'
                      and a.KODE_KLAIM = '$ls_kode_klaim'";

          $DB->parse($sql);
          $DB->execute();
          $row = $DB->nextrow();
          $ls_cnt_rtw                  = $row['CNT_RTW'];
          
          if($ls_cnt_rtw > 0){
            $qry_rtw = "BEGIN
                            PN.P_PN_PN5031.X_POST_BATAL_AGENDA_RTW ('$ls_kode_klaim',
                                                                    '$ls_user',
                                                                    :P_SUKSES,
                                                                    :P_MESS);
                        END;";

            $proc = $DB->parse($qry_rtw);       
            oci_bind_by_name($proc, ":P_SUKSES", $p_sukses,2);
            oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);
            $DB->execute();
          }
          

          //-------------------------------------- END UPDATE KOREKSI VERIFIKASI DATA KLAIM PP03 26/07/2022 -------------------------------------------------                       

          
          echo "<script language=\"JavaScript\" type=\"text/javascript\">";
          echo "window.opener.fl_js_reload_post_submit();";
          echo "window.close();";
          echo "window.opener.alert('$ls_mess')";
          echo "</script>";	 	  	 
        }else
        {
          if ($resultArray->P_SUKSES =="-1" || $resultArray->P_SUKSES =="-2")
          {
            $ls_mess = "PEMBATALAN KLAIM GAGAL, ".$resultArray->P_MESS;
          }else
          {
            $ls_mess = "PEMBATALAN KLAIM GAGAL, ".$resultArray->P_MESS." </br>".$resultArray->msg;	 
          }
          echo "<script language=\"JavaScript\" type=\"text/javascript\">";
          echo "window.opener.fl_js_reloadFormEntry();";
          echo "window.close();";
          echo "window.opener.alert('$ls_mess')";
          echo "</script>";		
        }		
      }  	
    }
    else
		{
      $ls_mess = "PEMBATALAN KLAIM GAGAL, KODE KLAIM ATAU ALASAN PEMBATALAN KOSONG, HARAP DICOBA KEMBALI..";
			echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      echo "window.opener.fl_js_reloadFormEntry();";
			echo "window.close();";
			echo "window.opener.alert('$ls_mess')";
			echo "</script>";			
		}  	
  	$ls_status_submit = "Y";
	}
  ?>
	<img alt="warning" src="../../images/warning.gif" align="left" hspace="10" vspace="0"> <span style="color:#ff0000;">harap sabar menunggu sampe proses selesai..!!!</span>		
	
  <form name="adminForm" id="adminForm" method="post" action="<?=$PHP_SELF;?>">
    <div id="formframe">
      <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
      <input type="hidden" id="ket_batal" name="ket_batal" value="<?=$ls_ket_batal;?>">	
      <input type="hidden" id="status_submit" name="status_submit" value="<?=$ls_status_submit;?>">					
      <input type="submit" id="btnsubmit" name="btnsubmit" value="" style="color:#fbf7c8;"/>
			<input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">	

			<input type="submit" id="btnsubmit" name="btnsubmit" value="" style="color:#fbf7c8;"/>
    </div>													 										
  </form>	

  <script language="javascript">
			if ($('#status_submit').val()=="")
			{		
  			document.getElementById("btnsubmit").click();
			}
  </script>

</body>
</html>						
	