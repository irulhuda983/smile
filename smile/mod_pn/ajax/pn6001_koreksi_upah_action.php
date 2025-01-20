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

    $ls_kode_klaim          = $_POST["kode_klaim"];
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
    // $ls_nama_file           = $_FILES['datafile']['name'];
    // $ls_nama_file           = stripslashes($ls_nama_file);
    // $ls_nama_file           = str_replace("'","",$ls_nama_file);
    // $ext                    = end(explode(".", $_FILES['datafile']['name']));
    // $jml_ext                = strlen($ext)+1;
    // $FILENAME               = substr(pathinfo($_FILES['datafile']['name'], PATHINFO_FILENAME),0,(50-$jml_ext)).'.'.$ext; 
    // // print_r($ls_nama_file);
    // die();
    // echo '{"ret":-1,"msg":"Sukses, '.$ls_nama_file.'"}';
    // die();
    // if(!empty($_FILES['datafile']['tmp_name']) 
    //  && file_exists($_FILES['datafile']['tmp_name'])) {
    //       $DOC_FILE= addslashes(file_get_contents($_FILES['datafile']['tmp_name']));
    // }  
    // $ls_mime_type           = $_FILES['datafile']['type'];
    $ls_keterangan_kor      = str_replace("'","' || '''' ||'", $_POST["keterangan_koreksi"]);
           
   $qry_koreksi_upah = "
              UPDATE PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
                    SET   KODE_PERUSAHAAN       = '$ls_kode_perusahaan',
                          KODE_DIVISI           = '$ls_kode_divisi',
                          KODE_KEPESERTAAN      = '$ls_kode_kepesertaan',
                          KODE_TK               = '$ls_kode_tk',
                          NIK                   = '$ls_nik',
                          NAMA_TK               = '$ls_nama_tk',
                          KODE_KLAIM            = '$ls_kode_klaim',
                          TGL_TERIMA_DOK_PENDAFTARAN = TO_DATE('$ls_date_dok_dftr','DD/MM/RRRR hh24:mi'),
                          TGL_TERIMA_DOK_UPAH        = TO_DATE('$ls_date_dok_upah','DD/MM/RRRR hh24:mi'),
                          KODE_SUMBER_DATA      = '$ls_kode_sumber_data',
                          BLTH_UPAH_KECELAKAAN  = TO_DATE('$ls_blth','DD/MM/RRRR'),
                          NOM_UPAH_KECELAKAAN   = '$ls_nom_upah',
                          STATUS_SUBMIT_KOREKSI = 'Y',
                          PETUGAS_SUBMIT_KOREKSI= '$USER',
                          TGL_SUBMIT_KOREKSI    = SYSDATE,
                          STATUS_APPROVAL       = 'T',
                          STATUS_BATAL          = 'T',
                          KETERANGAN            = '$ls_keterangan_kor',
                          TGL_REKAM             = SYSDATE,
                          PETUGAS_REKAM         = '$USER'
                      WHERE KODE_AGENDA = '$ls_kode_agenda';
      ";

  $qry_agenda = "UPDATE  PN.PN_AGENDA_KOREKSI
                  SET     REFERENSI       = '$ls_kode_klaim',
                          STATUS_AGENDA   = 'TERBUKA',
                          DETIL_STATUS    = 'SUBMIT',
                          DIAJUKAN_KE_KANTOR = '$KD_KANTOR',
                          DIAJUKAN_KE_FUNGSI = '$ls_diajukan_ke_fungsi'
                  WHERE   KODE_AGENDA     = '$ls_kode_agenda';";

   $sql = "
        BEGIN
            $qry_koreksi_upah
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
        //  $sql_upload = 
        //        "UPDATE
        //            PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
        //         SET
        //             NAMA_FILE='$FILENAME',
        //             DOC_FILE = EMPTY_BLOB()
        //         WHERE KODE_AGENDA = '$ls_kode_agenda'  
        //         RETURNING
        //             DOC_FILE INTO :LOB_A
        //       ";
        // $stmt   = oci_parse($DB->conn, $sql_upload);
        // $myLOB  = oci_new_descriptor($DB->conn, OCI_D_LOB);
        // oci_bind_by_name($stmt, ":LOB_A", $myLOB, -1, OCI_B_BLOB);
        // oci_execute($stmt, OCI_DEFAULT)
        // or die ("Unable to execute query\n");
        // if ( !$myLOB->save($DOC_FILE.date('H:i:s',time())) ) {
        //     $STATUS_UPLOAD = false;
        //     oci_rollback($DB->conn);
        // } else {
        //     $STATUS_UPLOAD=oci_commit($DB->conn);
        // }
        //               // Free resources
        // oci_free_statement($stmt);
        // $myLOB->free();
         
        // if($STATUS_UPLOAD){
        //     echo '{"ret":0,"msg":"Sukses, Data berhasil disimpan!"}';   
        // } else{
        //   $sql = "
        //        BEGIN
        //             DELETE FROM PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
        //                   WHERE KODE_AGENDA = '$ls_kode_agenda';
        //             DELETE FROM PN.PN_AGENDA_KOREKSI
        //                   WHERE KODE_AGENDA = '$ls_kode_agenda';
        //        END;";
        //   $DB->parse($sql);
        //   $DB->execute();
        //   echo '{"ret":-1,"msg":"Proses gagal, gagal upload dokumen!"}';
        // }
        echo '{"ret":0,"msg":"Sukses, Data berhasil disimpan!"}'; 
    }

   //$DB->parse($sql);
    
   // if($DB->execute()){
 
  else {

     $sql = "
     BEGIN
          DELETE FROM PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
                WHERE KODE_AGENDA = '$ls_kode_agenda';
          DELETE FROM PN.PN_AGENDA_KOREKSI
                WHERE KODE_AGENDA = '$ls_kode_agenda';
     END;";
     $DB->parse($sql);
     $DB->execute();

     echo '{"ret":-1,"msg":"Proses gagal, data gagal disimpan!"}';
  }
}

else if ($ACTION == "Approve") {
    $ls_kode_agenda       = $_POST["kd_agenda"];
  
    $sql = "SELECT  COUNT(1)
              FROM    PN.PN_AGENDA_KOREKSI A
              WHERE   KODE_AGENDA = '$ls_kode_agenda'
              AND EXISTS (SELECT 1 FROM PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
                            WHERE KODE_AGENDA = A.KODE_AGENDA)";
    // print_r($sql);
    // die();
    $recordsTotal = $DB->get_data($sql);
    if ($recordsTotal <= 0) {
        echo '{"ret":-1, "msg":"Kode agenda tidak ditemukan !"}';
        die();
    }else{
        $sql = "
        BEGIN
            UPDATE  PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
                        SET     STATUS_APPROVAL = 'Y',
                                PETUGAS_APPROVAL= '$USER',
                                TGL_APPROVAL     = SYSDATE
                        WHERE   KODE_AGENDA     = '$ls_kode_agenda';
            UPDATE  PN.PN_AGENDA_KOREKSI
                        SET     STATUS_AGENDA   = 'DITUTUP',
                                DETIL_STATUS    = 'APPROVAL',
                                TGL_SELESAI     = SYSDATE
                        WHERE   KODE_AGENDA     = '$ls_kode_agenda';            
        EXCEPTION
        WHEN OTHERS THEN
        ROLLBACK;
        END;
        ";

        $DB->parse($sql);
        if($DB->execute()){
          echo '{"ret":0,"msg":"Sukses, Data berhasil diapprove!"}';
        }else{
          echo '{"ret":-1,"msg":"Gagal, Data gagal diapprove!"}';
        }

    }
}

else if ($ACTION == "Batal") {
    $ls_kode_agenda       = $_POST["kd_agenda"];
  
    $sql = "SELECT  COUNT(1)
              FROM    PN.PN_AGENDA_KOREKSI A
              WHERE   KODE_AGENDA = '$ls_kode_agenda'
              AND EXISTS (SELECT 1 FROM PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
                            WHERE KODE_AGENDA = A.KODE_AGENDA)";
    // print_r($sql);
    // die();
    $recordsTotal = $DB->get_data($sql);
    if ($recordsTotal <= 0) {
        echo '{"ret":-1, "msg":"Kode agenda tidak ditemukan !"}';
        die();
    }else{
        $sql = "
        BEGIN
            UPDATE  PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
                        SET     STATUS_BATAL = 'Y',
                                PETUGAS_BATAL= '$USER',
                                TGL_BATAL    = SYSDATE
                        WHERE   KODE_AGENDA  = '$ls_kode_agenda';
            UPDATE  PN.PN_AGENDA_KOREKSI
                        SET     STATUS_AGENDA   = 'DITUTUP',
                                DETIL_STATUS    = 'BATAL',
                                TGL_SELESAI     = SYSDATE
                        WHERE   KODE_AGENDA     = '$ls_kode_agenda';            
        EXCEPTION
        WHEN OTHERS THEN
        ROLLBACK;
        END;
        ";

        $DB->parse($sql);
        if($DB->execute()){
          echo '{"ret":0,"msg":"Sukses, Data berhasil dibatalkan!"}';
        }else{
          echo '{"ret":-1,"msg":"Gagal, Data gagal dibatalkan!"}';
        }

    }
}

else if ($ACTION == "Batal_Approve") {
    $ls_kode_agenda       = $_POST["kd_agenda"];

    $sql = "SELECT STATUS_KLAIM FROM PN.PN_KLAIM 
            WHERE KODE_KLAIM IN 
             (SELECT KODE_KLAIM FROM PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
              WHERE KODE_AGENDA = '$ls_kode_agenda'
            )";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_status_klaim          = $row["STATUS_KLAIM"];

    if ($ls_status_klaim != "PENGAJUAN_TAHAP_I") {
        echo '{"ret":-1, "msg":"Agenda Tidak Dapat Dibatalkan Karena Status Tidak dalam PENGAJUAN TAHAP I !"}';
        die();
    }
  
    $sql = "SELECT  COUNT(1)
              FROM    PN.PN_AGENDA_KOREKSI A
              WHERE   KODE_AGENDA = '$ls_kode_agenda'
              AND EXISTS (SELECT 1 FROM PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
                            WHERE KODE_AGENDA = A.KODE_AGENDA)";
    // print_r($sql);
    // die();
    $recordsTotal = $DB->get_data($sql);
    if ($recordsTotal <= 0) {
        echo '{"ret":-1, "msg":"Kode agenda tidak ditemukan !"}';
        die();
    }else{
        $sql = "
        BEGIN
            UPDATE  PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
                        SET     STATUS_BATAL = 'Y',
                                PETUGAS_BATAL= '$USER',
                                TGL_BATAL    = SYSDATE,
                                KET_BATAL    = 'BATAL APPROVAL'
                        WHERE   KODE_AGENDA  = '$ls_kode_agenda';
            UPDATE  PN.PN_AGENDA_KOREKSI
                        SET     STATUS_AGENDA   = 'DITUTUP',
                                DETIL_STATUS    = 'BATAL',
                                TGL_SELESAI     = SYSDATE
                        WHERE   KODE_AGENDA     = '$ls_kode_agenda';            
        EXCEPTION
        WHEN OTHERS THEN
        ROLLBACK;
        END;
        ";

        $DB->parse($sql);
        if($DB->execute()){
          echo '{"ret":0,"msg":"Sukses, Data berhasil dibatalkan!"}';
        }else{
          echo '{"ret":-1,"msg":"Gagal, Data gagal dibatalkan!"}';
        }

    }
}

else if($TYPE=='GET_UPAH'){
  $ls_kode_kepesertaan  = $_POST["KODE_KEPESERTAAN"];
  $ls_kode_tk           = $_POST['KODE_TK'];
  $ls_blth              = $_POST['BLTH'];
  
  $sql =  "SELECT NVL(NOM_UPAH, '0') NOM_UPAH FROM KN.KN_IURAN_TK 
            WHERE KODE_KEPESERTAAN = '$ls_kode_kepesertaan'
            AND KODE_TK = '$ls_kode_tk'
            AND TRUNC(BLTH) = TO_DATE('$ls_blth','MMYYYY')
            AND ROWNUM = 1";       
  
  $DB->parse($sql);
  $DB->execute(); 
  $i=0;
  while($data = $DB->nextrow())
  {
    $jsondata .= json_encode($data);
    $jsondata .= ',';
    $i++;
  }
  $jsondataStart = '{"ret":0,"count":'.$i.',"data":[';
  $jsondata .= ']}';
  $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
  $jsondata = str_replace('},]}', '}]}', $jsondata);
  print_r($jsondata);
}

else {
    echo '{"ret":-1,"msg":"Fungsi Belum Tersedia!"}';
}
?>