<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
//print_r($_POST);
$TYPE       = $_POST["TYPE"];
$ACTION     = $_POST["ACTION_TYPE"];
$USER       = $_SESSION["USER"];
$KD_KANTOR  = $_SESSION['kdkantorrole'];
$ses_reg_role = $_SESSION['regrole'];
$KODEAGENDA 	= $_REQUEST["KODE_AGENDA"];

 
if ($TYPE == "New") {
    $ls_kode_agenda              = $_POST["kd_agenda"];
  
    $sql = "SELECT  COUNT(1)
              FROM    PN.PN_AGENDA_KOREKSI
              WHERE   KODE_AGENDA = '$ls_kode_agenda'";
    $recordsTotal = $DB->get_data($sql);
    if ($recordsTotal <= 0) {
        echo '{"ret":-1, "msg":"Kode agenda tidak ditemukan !"}';
        die();
    }

    $sql = "select count(1) is_kcp from sijstk.ms_kantor 
            where kode_kantor = '$KD_KANTOR' 
            and kode_tipe in ('4','5')";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_is_kcp = $row["IS_KCP"];
    if ($ls_is_kcp > 0){
        $ls_diajukan_ke_fungsi = '25';
    } else {
        $ls_diajukan_ke_fungsi = '6';
    }

    $ls_kode_klaim          = $_POST["kd_klaim"];
    $ls_kode_kepesertaan    = $_POST["kode_kepesertaan"] ;
    $ls_kode_perusahaan     = $_POST["kode_perusahaan"] ;
    $ls_kode_divisi         = $_POST["kode_divisi"] ;
    $ls_kode_tk             = $_POST["kode_tk"] ;
    $ls_nik                 = $_POST["no_nik"] ; 
    $ls_nama_tk             = str_replace("'","' || '''' ||'", $_POST["nama_tk"]);    
    $ls_tgl_dok_dftr        = $_POST["tgl_dok_pendaftaran"];
    $ls_tgl_dok_upah        = $_POST["tgl_dok_upah"];
    $ls_time_dok_dftr       = $_POST["time_dok_dftr"];
    $ls_time_dok_upah       = $_POST["time_dok_upah"];
    $ls_date_dok_dftr       = $ls_tgl_dok_dftr.' '.$ls_time_dok_dftr;
    $ls_date_dok_upah       = $ls_tgl_dok_upah.' '.$ls_time_dok_upah;
    $ls_kode_sumber_data    = $_POST["sumber_data"] ;       
    $ls_bulan               = $_POST["bulan"];
    $ls_tahun               = $_POST["tahun"];
    $ls_blth                = '01/'.$ls_bulan.'/'.$ls_tahun;
    $ls_nom_upah            = $_POST["upah_terakhir"];
    $ls_nom_upah            = ExtendedFunction::str_replace_number(",","",$ls_nom_upah);
    $ls_nom_tingkat_pengembangan = $_POST["nom_tingkat_pengembangan"];
    $ls_nom_tingkat_pengembangan_kor = $_POST["nom_tingkat_pengembangan_kor"];
    $ls_keterangan_kor      = str_replace("'","' || '''' ||'", $_POST["keterangan_koreksi"]);

  $qry_agenda = "UPDATE  PN.PN_AGENDA_KOREKSI
                  SET     REFERENSI       = '$ls_kode_klaim',
                          STATUS_AGENDA   = 'TERBUKA',
                          DETIL_STATUS    = 'TERBUKA',
                          DIAJUKAN_KE_KANTOR = '$KD_KANTOR',
                          DIAJUKAN_KE_FUNGSI = '$ls_diajukan_ke_fungsi'
                  WHERE   KODE_AGENDA     = '$ls_kode_agenda';";

   $sql = "
        BEGIN
            $qry_agenda
        EXCEPTION
        WHEN OTHERS THEN
        ROLLBACK;
        RAISE;
        END;";

    // print_r($sql);
    // exit();
    $DB->parse($sql);
    if($DB->execute()){
      $sql = "BEGIN PN.P_PN_PN60010205.X_INSERT_KOREKSI_KLAIM_JHT
                    (   '$ls_kode_agenda',
              '$ls_kode_klaim',
              '$ls_nom_tingkat_pengembangan',
              '$ls_nom_tingkat_pengembangan_kor',
              '$USER',
                        :p_sukses,
                        :p_mess
                    ); 
                END;";
        // print_r($sql);
        // die();   
        $proc = $DB->parse($sql);       
        oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
        oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
        if($DB->execute()){
            $ls_mess = $p_mess;
            $ls_sukses = $p_sukses;
            if($ls_sukses == '1'){
                echo '{"ret":0,"msg":"Sukses, Prosedur Sukses...Data berhasil disimpan!"}';
            }else{
               $sql = "
                 BEGIN
                      DELETE FROM PN.PN_AGENDA_KOREKSI
                            WHERE KODE_AGENDA = '$ls_kode_agenda';
                 END;";
                $DB->parse($sql);
                $DB->execute();
                echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
            }
        }else{
            echo '{"ret":-1,"msg":"Gagal, Gagal Eksekusi Prosedur PN.P_PN_PN60010205.X_INSERT_KOREKSI_KLAIM_JHT!"}';
        }         
    }

   //$DB->parse($sql);
    
   // if($DB->execute()){
 
  else {

     $sql = "
     BEGIN
          DELETE FROM PN.PN_AGENDA_KOREKSI
                WHERE KODE_AGENDA = '$ls_kode_agenda';
     END;";
     $DB->parse($sql);
     $DB->execute();

     echo '{"ret":-1,"msg":"Proses gagal, data gagal disimpan!"}';
  }
}

else if ($ACTION == "SUBMIT_KOREKSI") {
   $ls_kode_agenda       = $_POST["kd_agenda"];
  
    $sql = "SELECT  COUNT(1)
              FROM    PN.PN_AGENDA_KOREKSI A
              WHERE   KODE_AGENDA = '$ls_kode_agenda'
              AND EXISTS (SELECT 1 FROM PN.PN_AGENDA_KOREKSI_KLAIM
                            WHERE KODE_AGENDA = A.KODE_AGENDA)";
    // print_r($sql);
    // die();
    $recordsTotal = $DB->get_data($sql);
    if ($recordsTotal <= 0) {
        echo '{"ret":-1, "msg":"Kode agenda tidak ditemukan !"}';
        die();
    }else{
        $sql = "BEGIN PN.P_PN_PN60010205.X_SUBMIT_KOREKSI_KLAIM_JHT
				(
					'$ls_kode_agenda',
					'$USER',
					:p_sukses,
					:p_mess
                ); 
                END;";
        // print_r($sql);
        // die();   
        $proc = $DB->parse($sql);       
        oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
        oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
		
		if($DB->execute()){
            $ls_mess = $p_mess;
            $ls_sukses = $p_sukses;
            if($ls_sukses == '1'){
                echo '{"ret":0,"msg":"Sukses, Prosedur Sukses...Data koreksi berhasil disubmit!"}';
            }else{
               echo '{"ret":-1,"msg":"Gagal, Data gagal disubmit! "'.$ls_mess.'"}';
            }
        }else{
            echo '{"ret":-1,"msg":"Gagal, Gagal Eksekusi Prosedur PN.P_PN_PN60010205.X_SUBMIT_KOREKSI_KLAIM_JHT!"}';
        }
    }
}

else if ($ACTION == "Approve" || $ACTION == "APPROVE_KOREKSI") {
    $ls_kode_agenda       = $_POST["kd_agenda"];
  
    $sql = "SELECT  COUNT(1)
              FROM    PN.PN_AGENDA_KOREKSI A
              WHERE   KODE_AGENDA = '$ls_kode_agenda'
              AND EXISTS (SELECT 1 FROM PN.PN_AGENDA_KOREKSI_KLAIM
                            WHERE KODE_AGENDA = A.KODE_AGENDA)";
    // print_r($sql);
    // die();
    $recordsTotal = $DB->get_data($sql);
    if ($recordsTotal <= 0) {
        echo '{"ret":-1, "msg":"Kode agenda tidak ditemukan !"}';
        die();
    }else{
        $sql = "BEGIN PN.P_PN_PN60010205.X_APPROVE_KOREKSI_KLAIM_JHT
				(
					'$ls_kode_agenda',
					'$USER',
					:p_sukses,
					:p_mess
                ); 
                END;";
        // print_r($sql);
        // die();   
        $proc = $DB->parse($sql);       
        oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
        oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
		
		if($DB->execute()){
            $ls_mess = $p_mess;
            $ls_sukses = $p_sukses;
            if($ls_sukses == '1'){
                echo '{"ret":0,"msg":"Sukses, Prosedur Sukses...Data koreksi berhasil diapprove!"}';
            }else{
               echo '{"ret":-1,"msg":"Gagal, Data gagal diapprove! "'.$ls_mess.'"}';
            }
        }else{
            echo '{"ret":-1,"msg":"Gagal, Gagal Eksekusi Prosedur PN.P_PN_PN60010205.X_APPROVE_KOREKSI_KLAIM_JHT!"}';
        }
    }
}
else if ($ACTION == "CETAK_KOREKSI") {
   // cetak penetapan koreksi
}

else if ($ACTION == "Batal" || $ACTION == "BATAL_KOREKSI") {
    $ls_kode_agenda       = $_POST["kd_agenda"];
  
    $sql = "SELECT  COUNT(1)
              FROM    PN.PN_AGENDA_KOREKSI A
              WHERE   KODE_AGENDA = '$ls_kode_agenda'
              AND EXISTS (SELECT 1 FROM PN.PN_AGENDA_KOREKSI_KLAIM
                            WHERE KODE_AGENDA = A.KODE_AGENDA)";
    // print_r($sql);
    // die();
    $recordsTotal = $DB->get_data($sql);
    if ($recordsTotal <= 0) {
        echo '{"ret":-1, "msg":"Kode agenda tidak ditemukan !"}';
        die();
    }else{
		$sql = "BEGIN PN.P_PN_PN60010205.X_BATAL_KOREKSI_KLAIM_JHT
				(
					'$ls_kode_agenda',
					'$USER',
					:p_sukses,
					:p_mess
                ); 
                END;";
        // print_r($sql);
        // die();   
        $proc = $DB->parse($sql);       
        oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
        oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
		
		if($DB->execute()){
            $ls_mess = $p_mess;
            $ls_sukses = $p_sukses;
            if($ls_sukses == '1'){
                echo '{"ret":0,"msg":"Sukses, Prosedur Sukses...Data koreksi berhasil dibatalkan!"}';
            }else{
               echo '{"ret":-1,"msg":"Gagal, Data gagal dibatalkan! "'.$ls_mess.'"}';
            }
        }else{
            echo '{"ret":-1,"msg":"Gagal, Gagal Eksekusi Prosedur PN.P_PN_PN60010205.X_BATAL_KOREKSI_KLAIM_JHT!"}';
        }
    }
}

else {
    echo '{"ret":-1,"msg":"Fungsi Belum Tersedia!"}';
}
?>