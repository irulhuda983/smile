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
// print_r($ACTION);
// die();
$ls_kode_agenda         = $_POST["kd_agenda"];
$ls_kode_klaim          = $_POST["kode_klaim"];
$ls_kode_kor_awaris     = $_POST["kode_koreksi_awaris2"];
$ls_status_cerai        = $_POST["status_cerai"];
$ls_tgl_cerai           = $_POST["tgl_cerai"];
// print_r($_POST);
//  die();

if ($TYPE == "New") {
    $sql = "SELECT  COUNT(1)
              FROM    PN.PN_AGENDA_KOREKSI
              WHERE   KODE_AGENDA = '$ls_kode_agenda'";
    $recordsTotal = $DB->get_data($sql);
    if ($recordsTotal <= 0) {
        echo '{"ret":-1, "msg":"Kode agenda tidak ditemukan !"}';
        die();
    }

  $ls_keterangan_kor      = str_replace("'","' || '''' ||'", $_POST["keterangan_koreksi"]);
  
	$qry = "BEGIN 
				PN.P_PN_PN60010203.X_INSERT_AGENDA
				(
					'$ls_kode_agenda',
					'$ls_kode_klaim',
					'$ls_kode_kor_awaris',
					'$KD_KANTOR',
					'$ls_status_cerai',
					to_date('$ls_tgl_cerai', 'dd/mm/yyyy'),
					'$USER',
					:p_sukses,
					:p_mess
				);
			END;";		
	// echo $qry;
	$proc = $DB->parse($qry);				
	oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
	oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
	$DB->execute();				
	$ls_sukses = $p_sukses;
	$ls_mess = $p_mess;	

    if($ls_sukses == 1){
        echo '{"ret":0,"msg":"Sukses, Data berhasil disimpan!"}'; 
    }else{
		echo '{"ret":0,"msg":"Gagal, Data tidak berhasil disimpan. Error: '.$ls_mess.'"}'; 
	}

   //$DB->parse($sql);
    
   // if($DB->execute()){
 
//   else {

//      $sql = "
//      BEGIN
//           DELETE FROM PN.PN_AGENDA_KOREKSI_AWARIS
//                 WHERE KODE_AGENDA = '$ls_kode_agenda';
//           DELETE FROM PN.PN_AGENDA_KOREKSI
//                 WHERE KODE_AGENDA = '$ls_kode_agenda';
//      END;";
//      $DB->parse($sql);
//      $DB->execute();

//      echo '{"ret":-1,"msg":"Proses gagal, data gagal disimpan!"}';
//   }
}

else if ($ACTION == "Submitawaris") {
    //$ls_kode_agenda       = $_POST["kd_agenda"];
  
    $sql = "SELECT  COUNT(1)
              FROM    PN.PN_AGENDA_KOREKSI_AWARIS_DETIL
              WHERE   KODE_AGENDA = '$ls_kode_agenda'
          ";
    // print_r($sql);
    // die();
    $recordsTotal = $DB->get_data($sql);
    if ($recordsTotal <= 0) {
        echo '{"ret":-1, "msg":"Belum ada data ahliwaris yang dikoreksi. Silahkan koreksi daftar ahliwaris terlebih dahulu !"}';
        die();
    }else{
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

        $sql = "BEGIN PN.P_PN_PN60010203.X_SET_AHLIWARIS_JP
                    ( '$ls_kode_agenda', 
                      '$ls_kode_klaim', 
                      '$ls_diajukan_ke_fungsi', 
                      '$USER',
                      :p_sukses,
                      :p_mess); 
                END;";
        $proc = $DB->parse($sql);       
        oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
        oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
        if($DB->execute()){
            $ls_mess = $p_mess;
            $ls_sukses = $p_sukses;
            if($ls_sukses == '1'){
                echo '{"ret":0,"msg":"Sukses, Data berhasil disubmit!"}';
                //echo '{"ret":0,"msg":"'.$ls_mess.'"}';
            }else{
                echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
            }
        }else{
            echo '{"ret":-1,"msg":"Gagal, Gagal Eksekusi Prosedur PN.P_PN_PN60010203.X_SET_AHLIWARIS_JP!"}';
        }          
        

        // if($DB->execute()){
        //   $sql = "
        //   BEGIN
        //       UPDATE  PN.PN_AGENDA_KOREKSI_AWARIS
        //                   SET     STATUS_SUBMIT_KOREKSI = 'Y',
        //                           TGL_SUBMIT_KOREKSI    = SYSDATE,
        //                           PETUGAS_SUBMIT_KOREKSI= '$USER',
        //                           TGL_UBAH              = SYSDATE,
        //                           PETUGAS_UBAH          = '$USER'
        //                   WHERE   KODE_AGENDA  = '$ls_kode_agenda';
        //       UPDATE  PN.PN_AGENDA_KOREKSI
        //             SET     DETIL_STATUS    = 'SUBMIT',
        //                     DIAJUKAN_KE_FUNGSI = '$ls_diajukan_ke_fungsi'
        //             WHERE   KODE_AGENDA     = '$ls_kode_agenda';           
        //   EXCEPTION
        //   WHEN OTHERS THEN
        //   ROLLBACK;
        //   END;
        //   ";

        //   $DB->parse($sql);
        //   if($DB->execute()){
        //     echo '{"ret":0,"msg":"Sukses, Data berhasil disubmit!"}';
        //   }else{
        //     echo '{"ret":-1,"msg":"Gagal, Data gagal disubmit!"}';
        //   }
        // }else{
        //     echo '{"ret":-1,"msg":"Gagal, Gagal Eksekusi Prosedur PN.P_PN_PN60010203.X_SET_AHLIWARIS_JP!"}';
        // }   

    }
}

else if ($ACTION == "Approve") {
    //if($ls_kode_kor_awaris == 'KP01' || $ls_kode_kor_awaris == 'KP03'){
        $sql = "BEGIN PN.P_PN_PN60010203.X_APPROVE_AGENDA
                    ( '$ls_kode_agenda',
                      '$USER', 
                      :p_sukses,
                      :p_mess); 
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
                echo '{"ret":0,"msg":"Sukses, Data berhasil diapprove!"}';
            }else{
                echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
            }
        }else{
            echo '{"ret":-1,"msg":"Gagal, Gagal Eksekusi Prosedur PN.P_PN_PN60010203.X_APPROVE_AGENDA!"}';
        }
    // }else{
    //   echo '{"ret":-1,"msg":"Fungsi Approval untuk Koreksi ini belum tersedia"}';
    // }
    // // $ls_kode_agenda       = $_POST["kd_agenda"];
    // // $ls_kode_klaim          = $_POST["kode_klaim"];
    // //$ls_kode_kor_awaris     = $_POST["kode_koreksi_awaris"];
  
    // $sql = "SELECT  COUNT(1)
    //           FROM    PN.PN_AGENDA_KOREKSI A
    //           WHERE   KODE_AGENDA = '$ls_kode_agenda'
    //           AND EXISTS (SELECT 1 FROM PN.PN_AGENDA_KOREKSI_AWARIS_DETIL
    //                         WHERE KODE_AGENDA = A.KODE_AGENDA)";
    // // print_r($sql);
    // // die();
    // $recordsTotal = $DB->get_data($sql);
    // if ($recordsTotal <= 0) {
    //     echo '{"ret":-1, "msg":"Kode agenda tidak ditemukan !"}';
    //     die();
    // }else{
    //     if($ls_kode_kor_awaris == 'KP01' || $ls_kode_kor_awaris == 'KP03'){
    //       $app_koreksi_ahliwaris = "
    //         INSERT INTO PN.PN_KLAIM_PENERIMA_BERKALA(
    //               SELECT  KODE_KLAIM,
    //                       NO_URUT_KELUARGA,
    //                       KODE_HUBUNGAN,
    //                       NAMA_LENGKAP,
    //                       NO_KARTU_KELUARGA,
    //                       NOMOR_IDENTITAS,
    //                       TEMPAT_LAHIR,
    //                       TGL_LAHIR,
    //                       JENIS_KELAMIN,
    //                       GOLONGAN_DARAH,
    //                       STATUS_KAWIN,
    //                       ALAMAT,
    //                       RT,
    //                       RW,
    //                       KODE_KELURAHAN,
    //                       KODE_KECAMATAN,
    //                       KODE_KABUPATEN,
    //                       KODE_POS,
    //                       TELEPON_AREA,
    //                       TELEPON,
    //                       TELEPON_EXT,
    //                       FAX_AREA,
    //                       FAX,
    //                       HANDPHONE,
    //                       EMAIL,
    //                       NPWP,
    //                       NAMA_PENERIMA,
    //                       BANK_PENERIMA,
    //                       NO_REKENING_PENERIMA,
    //                       NAMA_REKENING_PENERIMA,
    //                       KODE_BANK_PEMBAYAR,
    //                       KPJ_TERTANGGUNG,
    //                       PEKERJAAN,
    //                       KODE_KONDISI_TERAKHIR,
    //                       TGL_KONDISI_TERAKHIR,
    //                       STATUS_LAYAK,
    //                       KODE_PENERIMA_BERKALA,
    //                       KETERANGAN,
    //                       TGL_REKAM,
    //                       PETUGAS_REKAM,
    //                       TGL_UBAH,
    //                       PETUGAS_UBAH,
    //                       JENIS_IDENTITAS,
    //                       STATUS_VALID_IDENTITAS,
    //                       KODE_BANK_PENERIMA,
    //                       ID_BANK_PENERIMA,
    //                       STATUS_VALID_REKENING_PENERIMA,
    //                       STATUS_REKENING_SENTRAL,
    //                       KANTOR_REKENING_SENTRAL,
    //                       METODE_TRANSFER,
    //                       KODE_NEGARA
    //               FROM PN.PN_AGENDA_KOREKSI_AWARIS_DETIL
    //               WHERE KODE_AGENDA = '$ls_kode_agenda'
    //           );"; 
    //     }
    //     else if($ls_kode_kor_awaris == 'KP02'){
    //       $app_koreksi_ahliwaris = 
    //             "UPDATE PN.PN_KLAIM_PENERIMA_BERKALA
    //               SET    KODE_PENERIMA_BERKALA = '',
    //                      TGL_UBAH = SYSDATE,
    //                      PETUGAS_UBAH = '$USER'
    //               WHERE  KODE_KLAIM = '$ls_kode_klaim'
    //               AND KODE_PENERIMA_BERKALA IN ('A1', 'A2');

    //               UPDATE PN.PN_KLAIM_PENERIMA_BERKALA
    //               SET    KODE_PENERIMA_BERKALA = 'A1',
    //                      TGL_UBAH = SYSDATE,
    //                      PETUGAS_UBAH = '$USER'
    //               WHERE  KODE_KLAIM = '$ls_kode_klaim'
    //               AND NO_URUT_KELUARGA IN 
    //               (
    //                   SELECT NO_URUT_KELUARGA FROM PN.PN_AGENDA_KOREKSI_AWARIS_DETIL 
    //                   WHERE KODE_AGENDA = '$ls_kode_agenda'
    //                   AND KODE_PENERIMA_BERKALA_BARU = 'A1'
    //               )
    //               AND NVL(STATUS_LAYAK,'T') = 'Y' ;

    //               UPDATE PN.PN_KLAIM_PENERIMA_BERKALA
    //               SET    KODE_PENERIMA_BERKALA = 'A2',
    //                      TGL_UBAH = SYSDATE,
    //                      PETUGAS_UBAH = '$USER'
    //               WHERE  KODE_KLAIM = '$ls_kode_klaim'
    //               AND NO_URUT_KELUARGA IN 
    //               (
    //                   SELECT NO_URUT_KELUARGA FROM PN.PN_AGENDA_KOREKSI_AWARIS_DETIL 
    //                   WHERE KODE_AGENDA = '$ls_kode_agenda'
    //                   AND KODE_PENERIMA_BERKALA_BARU = 'A2'
    //               )
    //               AND NVL(STATUS_LAYAK,'T') = 'Y' ;
    //             ";
    //      }
    //     else{
    //       $app_koreksi_ahliwaris = "";
    //     }

    //     $sql = "
    //     BEGIN
    //         $app_koreksi_ahliwaris
    //     EXCEPTION
    //     WHEN OTHERS THEN
    //     ROLLBACK;
    //     RAISE;
    //     END;";

    //     // print_r($sql);
    //     // die;
    //     $DB->parse($sql);
    //         if($DB->execute()){
    //             $qry_agenda = "
    //                 UPDATE  PN.PN_AGENDA_KOREKSI_AWARIS_DETIL
    //                             SET     FLAG_KOREKSI    = 'Y'
    //                             WHERE   KODE_AGENDA     = '$ls_kode_agenda';
    //                 UPDATE  PN.PN_AGENDA_KOREKSI_AWARIS
    //                             SET     STATUS_APPROVAL = 'Y',
    //                                     PETUGAS_APPROVAL= '$USER',
    //                                     TGL_APPROVAL     = SYSDATE,
    //                                     TGL_UBAH         = SYSDATE,
    //                                     PETUGAS_UBAH     = '$USER'
    //                             WHERE   KODE_AGENDA     = '$ls_kode_agenda';
    //                 UPDATE  PN.PN_AGENDA_KOREKSI
    //                             SET     STATUS_AGENDA   = 'DITUTUP',
    //                                     DETIL_STATUS    = 'APPROVED',
    //                                     TGL_SELESAI     = SYSDATE
    //                             WHERE   KODE_AGENDA     = '$ls_kode_agenda';  
    //             ";

    //             $sql = "
    //             BEGIN
    //                 $qry_agenda
    //             EXCEPTION
    //             WHEN OTHERS THEN
    //             ROLLBACK;
    //             RAISE;
    //             END;";

    //             $DB->parse($sql);
    //             if($DB->execute()){
                  
    //               echo '{"ret":0,"msg":"Sukses, Data berhasil diapprove!"}';
    //             }else{
    //               echo '{"ret":-1,"msg":"Gagal, Data gagal diapprove!"}';
    //             }
    //         }else{
    //           echo '{"ret":-1,"msg":"Gagal, Gagal Insert ke Tabel Penerima Berkala!"}';
    //         }       

    // }
}

else if ($ACTION == "Batal") {
    $ls_kode_agenda       = $_POST["kd_agenda"];
  
    $sql = "SELECT  COUNT(1)
              FROM    PN.PN_AGENDA_KOREKSI A
              WHERE   KODE_AGENDA = '$ls_kode_agenda'
              AND EXISTS (SELECT 1 FROM PN.PN_AGENDA_KOREKSI_AWARIS
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
            UPDATE  PN.PN_AGENDA_KOREKSI_AWARIS
                        SET     STATUS_BATAL = 'Y',
                                PETUGAS_BATAL= '$USER',
                                TGL_BATAL    = SYSDATE,
                                TGL_UBAH     = SYSDATE,
                                PETUGAS_UBAH = '$USER'
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
}

else {
    echo '{"ret":-1,"msg":"Fungsi Belum Tersedia!"}';
}
?>