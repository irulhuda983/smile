<?PHP
session_start();
include_once "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE           = $_POST["TYPE"];
$SUBTYPE        = $_REQUEST["SUBTYPE"];
$kode_user      = $_SESSION["USER"];
$kode_kantor    = $_SESSION['kdkantorrole'];
$ses_reg_role   = $_SESSION['regrole'];


if($TYPE=='Edit'){

}else if($TYPE=='Delete'){

}else if($TYPE=='New'){
    $kode_jenis_agenda  = $_POST['tb_kode_perihal'];
    $kode_jenis_agenda_detil = $_POST['tb_kode_perihal_detil'];
    $keterangan         = $_POST['tb_keterangan'];

    $get_id = "SELECT P_PN_GENID.F_GEN_KODEPP FROM DUAL";

    $DB->parse($get_id);
    if($DB->execute()){
      $kode_agenda = $DB->nextrow();
      $kd_agenda   = $kode_agenda['F_GEN_KODEPP'];
      $sql = " INSERT INTO PN.PN_AGENDA_KOREKSI (
                           KODE_AGENDA,
                           KODE_JENIS_AGENDA,
                           KODE_JENIS_AGENDA_DETIL,
                           KODE_KANTOR,
                           TGL_AGENDA,
                           KETERANGAN,
                           PEMILIK_DATA,
                           TGL_REKAM,
                           PETUGAS_REKAM,
                           KODE_KELOMPOK_AGENDA
                           )
               VALUES (    '".$kd_agenda."',
                           '".$kode_jenis_agenda."',
                           '".$kode_jenis_agenda_detil."',
                           '".$kode_kantor."',
                           TO_DATE('".date('d-m-Y')."','dd-mm-rrrr'),
                           '".$keterangan."',
                           '".$kode_user."',
                           SYSDATE,
                           '".$kode_user."',
                           'I'
                      )";

      // print_r($sql);
      // die;
      $DB->parse($sql);
      if($DB->execute()){
        if($kode_jenis_agenda_detil=="PP0202"){
            $ls_nama_file_PP0202           = $_FILES['datafile']['name'];
            $ls_nama_file_PP0202           = stripslashes($ls_nama_file_PP0202);
            $ls_nama_file_PP0202           = str_replace("'","",$ls_nama_file_PP0202);
            $ls_mime_type           = $_FILES['datafile']['type'];
            $ext                    = end(explode(".", $_FILES['datafile']['name']));
            $jml_ext                = strlen($ext)+1;
            $FILENAME               = substr(pathinfo($_FILES['datafile']['name'], PATHINFO_FILENAME),0,(50-$jml_ext)).'.'.$ext;
            if(!empty($_FILES['datafile']['tmp_name']) 
              && file_exists($_FILES['datafile']['tmp_name'])) {
             // if($ls_nama_file_PP0202 !=""){
                $DOC_FILE= file_get_contents($_FILES['datafile']['tmp_name']);
                $sql_upload = "INSERT INTO PN.PN_AGENDA_KOREKSI_KLAIM_UPAH(
                                          KODE_AGENDA,
                                          NAMA_FILE,
                                          MIME_TYPE,
                                          TGL_REKAM,
                                          PETUGAS_REKAM,
                                          DOC_FILE
                                      )
                              VALUES  (
                                          '".$kd_agenda."',
                                          '".$ls_nama_file_PP0202."',
                                          '".$ls_mime_typex."',
                                          SYSDATE,
                                          '".$kode_user."',
                                          EMPTY_BLOB()
                              ) RETURNING
                          DOC_FILE INTO :LOB_A";
                $stmt   = oci_parse($DB->conn, $sql_upload);
                $myLOB  = oci_new_descriptor($DB->conn, OCI_D_LOB);
                oci_bind_by_name($stmt, ":LOB_A", $myLOB, -1, OCI_B_BLOB);
                oci_execute($stmt, OCI_DEFAULT)
                or die ("Unable to execute query\n");
                if ( !$myLOB->save($DOC_FILE)) {
                    $STATUS_UPLOAD = false;
                    oci_rollback($DB->conn);
                } else {
                    $STATUS_UPLOAD=oci_commit($DB->conn);
                }
                              // Free resources
                oci_free_statement($stmt);
                $myLOB->free();
                 
                if($STATUS_UPLOAD){
                    echo '{"ret":0,"msg":"Agenda berhasil dibuat!","dataid":"'.$kode_agenda['F_GEN_KODEPP'].'"}';   
                } else{
                  $sql = "
                       BEGIN
                            DELETE FROM PN.PN_AGENDA_KOREKSI_KLAIM_UPAH
                                  WHERE KODE_AGENDA = '$kd_agenda';
                            DELETE FROM PN.PN_AGENDA_KOREKSI
                                  WHERE KODE_AGENDA = '$kd_agenda';
                       END;";
                  $DB->parse($sql);
                  $DB->execute();
                  echo '{"ret":-1,"msg":"Proses gagal, gagal upload dokumen!"}';
                }
            } else{
                $sql = "
                            DELETE FROM PN.PN_AGENDA_KOREKSI
                                  WHERE KODE_AGENDA = '$kd_agenda'";
                  $DB->parse($sql);
                  $DB->execute();
                  echo '{"ret":-1,"msg":"Proses gagal,"'.$ls_nama_file_PP0202.'" Dokumen kosong!"}';
            }  
        }
        else if($kode_jenis_agenda_detil=="PP0203"){
          if(!empty($_FILES['datafile']['tmp_name'])) {
            $filename = $kd_agenda . "-" . $_FILES["datafile"]["name"];
            $mime_type = $_FILES['datafile']['type'];

            $upload_dir_full = $smile_upd_dir_full . "/mod_pn/pn60010203/";
            $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png", "pdf" => "application/pdf");
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $maxsize = 0.5 * 1024 * 1024; // 500KB

            $path_fullname = $upload_dir_full . $filename;

            $qry = "
            INSERT INTO PN.PN_AGENDA_KOREKSI_AWARIS(
                KODE_AGENDA,
                TGL_TERIMA_DOK_KOREKSI,
                NAMA_FILE,
                MIME_TYPE,
                TGL_REKAM,
                PETUGAS_REKAM,
                PATH_DOKUMEN)
              VALUES  (
                :p_kode_agenda,
                SYSDATE,
                :p_nama_file,
                :p_mime_type,
                SYSDATE,
                :p_kode_user,
                :p_path_dokumen
            )";
            $proc = $DB->parse($qry);				
            oci_bind_by_name($proc, ":p_kode_agenda", $kd_agenda, 100);
            oci_bind_by_name($proc, ":p_nama_file", $filename, 100);
            oci_bind_by_name($proc, ":p_mime_type", $mime_type, 100);
            oci_bind_by_name($proc, ":p_kode_user", $kode_user, 100);
            oci_bind_by_name($proc, ":p_path_dokumen", $path_fullname, 500);

            $uploaded = true;
            if ($DB->execute()) {
              if (!is_dir($smile_upd_dir)) {
                echo '{"ret":-1,"msg":"Direktori Upload tidak ditemukan."}';
                $uploaded = false;
              } else if (!is_dir($upload_dir_full)) {
                mkdir($upload_dir_full, 0777, true);
              }
              
              if($_FILES['datafile']['error'] > 0){
                echo '{"ret":-1,"msg":"An error ocurred when uploading."}';
                $uploaded = false;
              } else if (!array_key_exists($ext, $allowed)) {
                echo '{"ret":-1,"msg":"Error: Please select a valid file format."}';
                $uploaded = false;
              } else if($_FILES['datafile']['size'] > $maxsize){
                echo '{"ret":-1,"msg":"File uploaded exceeds maximum upload size."}';
                $uploaded = false;
              } else if(file_exists($path_fullname)){
                echo '{"ret":-1,"msg":"File with that name already exists."}';
                $uploaded = false;
              } else if(!move_uploaded_file($_FILES['datafile']['tmp_name'], $path_fullname)){
                echo '{"ret":-1,"msg":"Proses gagal, gagal upload dokumen!"}';
                $uploaded = false;
              }
            } else {
              $uploaded = false;
            }

            // rollback jika upload gagal
            if ($uploaded) {
              echo '{"ret":0,"msg":"Agenda berhasil dibuat!","dataid":"'.$kode_agenda['F_GEN_KODEPP'].'"}';   
            } else {
              $sql = "
                BEGIN
                    DELETE FROM PN.PN_AGENDA_KOREKSI_AWARIS
                          WHERE KODE_AGENDA = '$kd_agenda';
                    DELETE FROM PN.PN_AGENDA_KOREKSI
                          WHERE KODE_AGENDA = '$kd_agenda';
                END;";
              $DB->parse($sql);
              $DB->execute();
            }
          } else {
            $sql = "
              DELETE FROM PN.PN_AGENDA_KOREKSI WHERE KODE_AGENDA = '$kd_agenda'";
            $DB->parse($sql);
            $DB->execute();
            echo '{"ret":-1,"msg":"Proses gagal,"Dokumen kosong!"}';  
          }
            // $ls_nama_file           = $_FILES['datafile']['name'];
            // $ls_nama_file           = stripslashes($ls_nama_file);
            // $ls_nama_file           = str_replace("'","",$ls_nama_file);
            // $ls_mime_type           = $_FILES['datafile']['type'];
            // $ext                    = end(explode(".", $_FILES['datafile']['name']));
            // $jml_ext                = strlen($ext)+1;
            // $FILENAME               = substr(pathinfo($_FILES['datafile']['name'], PATHINFO_FILENAME),0,(50-$jml_ext)).'.'.$ext;
            // if(!empty($_FILES['datafile']['tmp_name']) 
            //   && file_exists($_FILES['datafile']['tmp_name'])) {
            //  // if($ls_nama_file !=""){
            //     $DOC_FILE= file_get_contents($_FILES['datafile']['tmp_name']);
            //     $sql_upload = "INSERT INTO PN.PN_AGENDA_KOREKSI_AWARIS(
            //                               KODE_AGENDA,
            //                               TGL_TERIMA_DOK_KOREKSI,
            //                               NAMA_FILE,
            //                               MIME_TYPE,
            //                               TGL_REKAM,
            //                               PETUGAS_REKAM,
            //                               DOC_FILE
            //                           )
            //                   VALUES  (
            //                               '".$kd_agenda."',
            //                               SYSDATE,
            //                               '".$ls_nama_file."',
            //                               '".$ls_mime_type."',
            //                               SYSDATE,
            //                               '".$kode_user."',
            //                               EMPTY_BLOB()
            //                   ) RETURNING
            //               DOC_FILE INTO :LOB_A";
            //     $stmt   = oci_parse($DB->conn, $sql_upload);
            //     $myLOB  = oci_new_descriptor($DB->conn, OCI_D_LOB);
            //     oci_bind_by_name($stmt, ":LOB_A", $myLOB, -1, OCI_B_BLOB);
            //     oci_execute($stmt, OCI_DEFAULT)
            //     or die ("Unable to execute query\n");
            //     if ( !$myLOB->save($DOC_FILE)) {
            //         $STATUS_UPLOAD = false;
            //         oci_rollback($DB->conn);
            //     } else {
            //         $STATUS_UPLOAD=oci_commit($DB->conn);
            //     }
            //                   // Free resources
            //     oci_free_statement($stmt);
            //     $myLOB->free();
                 
            //     if($STATUS_UPLOAD){
            //         echo '{"ret":0,"msg":"Agenda berhasil dibuat!","dataid":"'.$kode_agenda['F_GEN_KODEPP'].'"}';   
            //     } else{
            //       $sql = "
            //            BEGIN
            //                 DELETE FROM PN.PN_AGENDA_KOREKSI_AWARIS
            //                       WHERE KODE_AGENDA = '$kd_agenda';
            //                 DELETE FROM PN.PN_AGENDA_KOREKSI
            //                       WHERE KODE_AGENDA = '$kd_agenda';
            //            END;";
            //       $DB->parse($sql);
            //       $DB->execute();
            //       echo '{"ret":-1,"msg":"Proses gagal, gagal upload dokumen!"}';
            //     }
            // } else{
            //     $sql = "
            //                 DELETE FROM PN.PN_AGENDA_KOREKSI
            //                       WHERE KODE_AGENDA = '$kd_agenda'";
            //       $DB->parse($sql);
            //       $DB->execute();
            //       echo '{"ret":-1,"msg":"Proses gagal,"'.$ls_nama_file.'" Dokumen kosong!"}';
            // }  
        }
        else if($kode_jenis_agenda_detil=="PP0204"){
            $ls_nama_file           = $_FILES['datafile']['name'];
            $ls_nama_file           = stripslashes($ls_nama_file);
            $ls_nama_file           = str_replace("'","",$ls_nama_file);
            $ls_mime_type           = $_FILES['datafile']['type'];
            $ext                    = end(explode(".", $_FILES['datafile']['name']));
            $jml_ext                = strlen($ext)+1;
            $FILENAME               = substr(pathinfo($_FILES['datafile']['name'], PATHINFO_FILENAME),0,(50-$jml_ext)).'.'.$ext;
            if(!empty($_FILES['datafile']['tmp_name']) 
              && file_exists($_FILES['datafile']['tmp_name'])) {
             // if($ls_nama_file !=""){
                $DOC_FILE= file_get_contents($_FILES['datafile']['tmp_name']);
                $sql_upload = "INSERT INTO PN.PN_AGENDA_KOREKSI_KLAIM_PMI(
                                          KODE_AGENDA,
                                          NAMA_FILE,
                                          MIME_TYPE,
                                          TGL_REKAM,
                                          PETUGAS_REKAM,
                                          DOC_FILE
                                      )
                              VALUES  (
                                          '".$kd_agenda."',
                                          '".$ls_nama_file."',
                                          '".$ls_mime_type."',
                                          SYSDATE,
                                          '".$kode_user."',
                                          EMPTY_BLOB()
                              ) RETURNING
                          DOC_FILE INTO :LOB_A";
                $stmt   = oci_parse($DB->conn, $sql_upload);
                $myLOB  = oci_new_descriptor($DB->conn, OCI_D_LOB);
                oci_bind_by_name($stmt, ":LOB_A", $myLOB, -1, OCI_B_BLOB);
                oci_execute($stmt, OCI_DEFAULT)
                or die ("Unable to execute query\n");
                if ( !$myLOB->save($DOC_FILE)) {
                    $STATUS_UPLOAD = false;
                    oci_rollback($DB->conn);
                } else {
                    $STATUS_UPLOAD=oci_commit($DB->conn);
                }
                              // Free resources
                oci_free_statement($stmt);
                $myLOB->free();
                 
                if($STATUS_UPLOAD){
                    echo '{"ret":0,"msg":"Agenda berhasil dibuat!","dataid":"'.$kode_agenda['F_GEN_KODEPP'].'"}';   
                } else{
                  $sql = "
                       BEGIN
                            DELETE FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI
                                  WHERE KODE_AGENDA = '$kd_agenda';
                            DELETE FROM PN.PN_AGENDA_KOREKSI
                                  WHERE KODE_AGENDA = '$kd_agenda';
                       END;";
                  $DB->parse($sql);
                  $DB->execute();
                  echo '{"ret":-1,"msg":"Proses gagal, gagal upload dokumen!"}';
                }
            } else{
                $sql = "
                            DELETE FROM PN.PN_AGENDA_KOREKSI
                                  WHERE KODE_AGENDA = '$kd_agenda'";
                  $DB->parse($sql);
                  $DB->execute();
                  echo '{"ret":-1,"msg":"Proses gagal,"'.$ls_nama_file.'" Dokumen kosong!"}';
            }  
        }else if($kode_jenis_agenda_detil=="PP0301" || $kode_jenis_agenda_detil=="PP0302" || $kode_jenis_agenda_detil=="PP0303" || $kode_jenis_agenda_detil=="PP0304" || $kode_jenis_agenda_detil=="PP0305" || $kode_jenis_agenda_detil=="PP0306" || $kode_jenis_agenda_detil=="PP0501"){
          
          $no_identitas = $_POST['no_identitas'];
          $tipe_submit  = $_POST['tipe_submit'];
          $keterangan   = $_POST['keterangan'];
          $kpj          = $_POST['kpj'];
          $kode_tk      = $_POST['kode_tk'];
	
          if($tipe_submit == 'SIMPAN_PROFIL'){
            $sql = '';
            if($kode_jenis_agenda_detil=="PP0301" || $kode_jenis_agenda_detil=="PP0302"){
              $sql =  "BEGIN
                      PN.P_PN_PN60010210.X_SIMPAN_PROFIL ('$kd_agenda',
                                                  '$kode_jenis_agenda',
                                                  '$kode_jenis_agenda_detil',
                                                  '$kode_kantor',
                                                  '$no_identitas',
                                                  '$kode_user',
                                                  '',
                                                  :P_KODE_AGENDA_ADJ,
                                                  :P_MESS,
                                                  :P_SUKSES);
                    END;";
            } else if ($kode_jenis_agenda_detil=="PP0303" || $kode_jenis_agenda_detil=="PP0304"){
              $sql =  "BEGIN
                      PN.P_PN_PN60010211.X_SIMPAN_PROFIL ('$kd_agenda',
                                                  '$kode_jenis_agenda',
                                                  '$kode_jenis_agenda_detil',
                                                  '$kode_kantor',
                                                  '$no_identitas',
                                                  '$kpj',
                                                  '$kode_tk',
                                                  '$kode_user',
                                                  '',
                                                  :P_KODE_AGENDA_ADJ,
                                                  :P_MESS,
                                                  :P_SUKSES);
                    END;";
            } else if ($kode_jenis_agenda_detil=="PP0305") {
              // 05-01-2024 Penamabahan validasi untuk kode_jenis_agenda_detil PP0305 yang mengikuti PP0302
              $sql =  "BEGIN
                      PN.P_PN_PN60010305.X_SIMPAN_PROFIL ('$kd_agenda',
                                                  '$kode_jenis_agenda',
                                                  '$kode_jenis_agenda_detil',
                                                  '$kode_kantor',
                                                  '$no_identitas',
                                                  '$kode_user',
                                                  '',
                                                  :P_KODE_AGENDA_ADJ,
                                                  :P_MESS,
                                                  :P_SUKSES);
                    END;";
            } else if ($kode_jenis_agenda_detil=="PP0306") {
              // 06-02-2024 Penamabahan validasi untuk kode_jenis_agenda_detil PP0306 yang mengikuti PP0303
              $sql =  "BEGIN
                      PN.P_PN_PN60010211.X_SIMPAN_PROFIL ('$kd_agenda',
                                                  '$kode_jenis_agenda',
                                                  '$kode_jenis_agenda_detil',
                                                  '$kode_kantor',
                                                  '$no_identitas',
                                                  '$kpj',
                                                  '$kode_tk',
                                                  '$kode_user',
                                                  '',
                                                  :P_KODE_AGENDA_ADJ,
                                                  :P_MESS,
                                                  :P_SUKSES);
                    END;";
            }
            else if ($kode_jenis_agenda_detil=="PP0501"){
              $sql =  "BEGIN
                      PN.P_PN_PN60010212.X_SIMPAN_PROFIL ('$kd_agenda',
                                                  '$kode_jenis_agenda',
                                                  '$kode_jenis_agenda_detil',
                                                  '$kode_kantor',
                                                  '$no_identitas',
                                                  '$kpj',
                                                  '$kode_tk',
                                                  '$kode_user',
                                                  '',
                                                  :P_KODE_AGENDA_ADJ,
                                                  :P_MESS,
                                                  :P_SUKSES);
                    END;";
            }
                  // var_dump($sql);die();
            $proc = $DB->parse($sql);       
            oci_bind_by_name($proc, ":P_KODE_AGENDA_ADJ", $p_kode_agenda_adj,32);
            oci_bind_by_name($proc, ":P_SUKSES", $p_sukses,32);
            oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);
            
            $DB->execute();
            
            if($p_sukses == '1'){
              $sql = " UPDATE PN.PN_AGENDA_KOREKSI SET 
                          STATUS_AGENDA = 'TERBUKA', 
                          DETIL_STATUS = 'AGENDA', 
                          KETERANGAN = '$keterangan', 
                          DIAJUKAN_KE_KANTOR = '$kode_kantor',
                          REFERENSI = '$no_identitas'
                       WHERE KODE_AGENDA = '$kd_agenda'";
              $DB->parse($sql);
              $DB->execute();

              if ($kode_jenis_agenda_detil=="PP0305") {
                // 02-02-2024 kondisi terbaru untuk PP0305 agar KPJ langsung tersubmit
                $kode_dok     = 'D438';            
                $sql =  "BEGIN
                            PN.P_PN_PN60010305.X_INSERT_VERIFIKASI_JHT_TUKEP ('$kd_agenda',
                                                        '$kode_tk',
                                                        '$kode_dok',
                                                        '$kode_user',
                                                        :P_KODE_AGENDA_ADJ,
                                                        :P_MESS,
                                                        :P_SUKSES);
                        END;";
                $proc = $DB->parse($sql);       
                oci_bind_by_name($proc, ":P_KODE_AGENDA_ADJ", $p_kode_agenda_adj,32);
                oci_bind_by_name($proc, ":P_SUKSES", $p_sukses,32);
                oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);
                
                $DB->execute();
                
                if($p_sukses == '1'){
                  echo '{"ret":0,"msg":"Agenda berhasil dibuat!","dataid":"'.$kode_agenda['F_GEN_KODEPP'].'"}';
                  // echo '{"ret":0,"msg":"Agenda berhasil dibuat!","dataid":"'.$p_kode_agenda_adj.'"}';
                } else {
                  echo '{"ret":-1,"msg":"'.$p_mess.'"}';
                } 
              } else {
                echo '{"ret":0,"msg":"Agenda berhasil dibuat!","dataid":"'.$kode_agenda['F_GEN_KODEPP'].'"}';
              }

            } else {
              echo '{"ret":-1,"msg":"'.$p_mess.'"}';
            } 
          }

        }
        else{
            echo '{"ret":0,"msg":"Agenda berhasil dibuat!","dataid":"'.$kode_agenda['F_GEN_KODEPP'].'"}';
        }
      }else{
        echo '{"ret":-1,"msg":"Proses gagal, agenda gagal dibuat!"}';
      }
    }else{
      echo '{"ret":-1,"msg":"Proses gagal, kode agenda gagal dibuat!"}';
    }
}else if($TYPE=='QUERY'){
  if($SUBTYPE=='PP0201')
  {
    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.NAMA_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY B.KODE_KLAIM ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY c.KPJ ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY c.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="select rownum as no_urut,a.kode_agenda,a.kode_jenis_agenda_detil,d.nama_jenis_agenda_detil,d.url_path,
            to_char(a.tgl_agenda,'DD/MM/YYYY') tgl_agenda, a.detil_status,c.kpj,c.nama_tk,b.kode_klaim,
            a.tgl_rekam,a.petugas_rekam,a.status_agenda,a.kode_kantor
          from pn.pn_agenda_koreksi a inner join
            pn.pn_agenda_koreksi_klaim b on a.kode_agenda=b.kode_agenda left outer join
            pn.pn_klaim c on b.kode_klaim=c.kode_klaim left outer join 
            pn.pn_kode_jenis_agenda_detil  d on a.kode_jenis_agenda_detil=d.kode_jenis_agenda_detil
          where d.kode_jenis_agenda_detil='PP0201'
          --AND EXISTS (SELECT 1 FROM PN.PN_KODE_JENIS_AGENDA_KOR_ROLE 
          --                              WHERE KODE_JENIS_AGENDA_DETIL = A.KODE_JENIS_AGENDA_DETIL
          --                              AND KODE_FUNGSI = '$ses_reg_role')  
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
  }
  else if($SUBTYPE=='PP0401')
  {
    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      if($ls_search_pilihan=="C.KPJ"){
        $condition .= " AND C.KODE_TK = (SELECT KODE_TK FROM KN.KN_TK WHERE KPJ='$ls_search_keyword')";
      }else{
        $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
      }
      
    } 

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.NAMA_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY B.KODE_KLAIM ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY c.KPJ ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY c.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="select rownum as no_urut,a.kode_agenda,a.kode_jenis_agenda_detil,d.nama_jenis_agenda_detil,d.url_path,
    to_char(a.tgl_agenda,'DD/MM/YYYY') tgl_agenda, a.detil_status, (select kpj from kn.kn_tk z where z.kode_tk=c.kode_tk) kpj, c.nama_tk, '' kode_klaim,
    a.tgl_rekam,a.petugas_rekam,a.status_agenda,a.kode_kantor
  from pn.pn_agenda_koreksi a inner join
    PN.PN_AGENDA_KOREKSI_KLAIM_JKM c on a.kode_agenda=c.kode_agenda left outer join 
    pn.pn_kode_jenis_agenda_kor_detil  d on a.kode_jenis_agenda_detil=d.kode_jenis_agenda_detil
  where d.kode_jenis_agenda_detil='PP0401'
  and a.pemilik_data = '$kode_user'
  and a.kode_kantor =  '$kode_kantor'
  --AND EXISTS (SELECT 1 FROM PN.PN_KODE_JENIS_AGENDA_KOR_ROLE 
  --                              WHERE KODE_JENIS_AGENDA_DETIL = A.KODE_JENIS_AGENDA_DETIL
  --                              AND KODE_FUNGSI = '$ses_reg_role')  
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
  }
  else if($SUBTYPE=='PP0202')
  {
    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY B.KODE_KLAIM ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY C.KPJ ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="select rownum as no_urut,a.kode_agenda,a.kode_jenis_agenda_detil,d.nama_jenis_agenda_detil,d.url_path,
            to_char(a.tgl_agenda,'DD/MM/YYYY') tgl_agenda, a.detil_status,c.kpj,c.nama_tk,b.kode_klaim,
            a.tgl_rekam,a.petugas_rekam,a.status_agenda,a.kode_kantor
          from pn.pn_agenda_koreksi a inner join
            pn.pn_agenda_koreksi_klaim_upah b on a.kode_agenda=b.kode_agenda left outer join
            pn.pn_klaim c on b.kode_klaim=c.kode_klaim left outer join 
            pn.pn_kode_jenis_agenda_kor_detil  d on a.kode_jenis_agenda_detil=d.kode_jenis_agenda_detil
          where d.kode_jenis_agenda_detil='PP0202'
          and a.pemilik_data = '$kode_user'
          and a.kode_kantor =  '$kode_kantor'
          --AND EXISTS (SELECT 1 FROM PN.PN_KODE_JENIS_AGENDA_KOR_ROLE 
          --                              WHERE KODE_JENIS_AGENDA_DETIL = A.KODE_JENIS_AGENDA_DETIL
          --                              AND KODE_FUNGSI = '$ses_reg_role')  
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    //echo $sql;
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
  }

  else if($SUBTYPE=='PP0203')
  {
    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
    $ls_selected_kategori = $_POST['reg_kategori'];
    // print_r($ls_selected_kategori) ;
    // die();

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 

    $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_koreksi = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      and b.status_submit_koreksi = 'Y'
                      and b.status_batal = 'T'";
    }


    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY B.KODE_KLAIM ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY C.KPJ ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="select rownum as no_urut,a.kode_agenda,a.kode_jenis_agenda_detil,d.nama_jenis_agenda_detil,d.url_path,
            to_char(a.tgl_agenda,'DD/MM/YYYY') tgl_agenda, a.detil_status,c.kpj,c.nama_tk,b.kode_klaim,
            a.tgl_rekam,a.petugas_rekam,a.status_agenda,a.kode_kantor
          from pn.pn_agenda_koreksi a 
          inner join
            pn.pn_agenda_koreksi_awaris b 
               on a.kode_agenda=b.kode_agenda
               $rb_selected 
          left outer join
            pn.pn_klaim c 
              on b.kode_klaim=c.kode_klaim 
          left outer join 
            pn.pn_kode_jenis_agenda_kor_detil  d 
               on a.kode_jenis_agenda_detil=d.kode_jenis_agenda_detil
          where d.kode_jenis_agenda_detil='PP0203'
          --and a.pemilik_data = '$kode_user'
          and a.kode_kantor =  '$kode_kantor'
          --AND EXISTS (SELECT 1 FROM PN.PN_KODE_JENIS_AGENDA_KOR_ROLE 
          --                              WHERE KODE_JENIS_AGENDA_DETIL = A.KODE_JENIS_AGENDA_DETIL
          --                              AND KODE_FUNGSI = '$ses_reg_role')  
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    // print_r($sql) ;
    // die();
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
  }
  else if($SUBTYPE=='PP0204')
  {
    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY B.KODE_KLAIM ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY C.KPJ ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="select rownum as no_urut,a.kode_agenda,a.kode_jenis_agenda_detil,d.nama_jenis_agenda_detil,d.url_path,
            to_char(a.tgl_agenda,'DD/MM/YYYY') tgl_agenda, a.detil_status,c.kpj,c.nama_tk,b.kode_klaim,
            a.tgl_rekam,a.petugas_rekam,a.status_agenda,a.kode_kantor
          from pn.pn_agenda_koreksi a inner join
            pn.pn_agenda_koreksi_klaim_pmi b on a.kode_agenda=b.kode_agenda left outer join
            pn.pn_klaim c on b.kode_klaim=c.kode_klaim left outer join 
            pn.pn_kode_jenis_agenda_kor_detil  d on a.kode_jenis_agenda_detil=d.kode_jenis_agenda_detil
          where d.kode_jenis_agenda_detil='PP0204'
          and a.pemilik_data = '$kode_user'
          and a.kode_kantor =  '$kode_kantor'
          --AND EXISTS (SELECT 1 FROM PN.PN_KODE_JENIS_AGENDA_KOR_ROLE 
          --                              WHERE KODE_JENIS_AGENDA_DETIL = A.KODE_JENIS_AGENDA_DETIL
          --                              AND KODE_FUNGSI = '$ses_reg_role')  
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    //echo $sql;
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
  }
  else if($SUBTYPE=='PP0205')
  {
	// update status data terlebih dahulu sesuai data klaim sebelum data ditampilkan
	$sql = "BEGIN PN.P_PN_PN60010205.X_UPDATE_KOREKSI_KLAIM_JHT_ALL
			(
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
	
	$DB->execute();
	//
	
    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
	 $ls_selected_kategori = $_POST['reg_kategori'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
	
	  $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_koreksi = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      and b.status_submit_koreksi = 'Y'
                      and b.status_batal = 'T'";
    }

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY B.KODE_KLAIM ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY C.KPJ ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="select rownum as no_urut,a.kode_agenda,a.kode_jenis_agenda_detil,d.nama_jenis_agenda_detil,d.url_path,
            to_char(a.tgl_agenda,'DD/MM/YYYY') tgl_agenda, a.detil_status,c.kpj,c.nama_tk,b.kode_klaim,
            a.tgl_rekam,a.petugas_rekam,a.status_agenda,a.kode_kantor
          from pn.pn_agenda_koreksi a inner join
            pn.pn_agenda_koreksi_klaim b on a.kode_agenda=b.kode_agenda 
			$rb_selected 
			left outer join
            pn.pn_klaim c on b.kode_klaim=c.kode_klaim left outer join 
            pn.pn_kode_jenis_agenda_kor_detil  d on a.kode_jenis_agenda_detil=d.kode_jenis_agenda_detil
          where d.kode_jenis_agenda_detil='PP0205'
          and a.pemilik_data = '$kode_user'
          and a.kode_kantor =  '$kode_kantor' 
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    //echo $sql;
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
  }
  else if($SUBTYPE=='PP0206')
  {
	// update status data terlebih dahulu sesuai data klaim sebelum data ditampilkan
	$sql = "BEGIN PN.P_PN_PN60010206.X_UPDATE_KOREKSI_KLAIM_JPN_ALL
			(
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
	
	$DB->execute();
	//
	
    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
	 $ls_selected_kategori = $_POST['reg_kategori'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
	
	  $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_koreksi = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      and b.status_submit_koreksi = 'Y'
                      and b.status_batal = 'T'";
    }

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY B.KODE_KLAIM ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY C.KPJ ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="select rownum as no_urut,a.kode_agenda,a.kode_jenis_agenda_detil,d.nama_jenis_agenda_detil,d.url_path,
            to_char(a.tgl_agenda,'DD/MM/YYYY') tgl_agenda, a.detil_status,c.kpj,c.nama_tk,b.kode_klaim,
            a.tgl_rekam,a.petugas_rekam,a.status_agenda,a.kode_kantor
          from pn.pn_agenda_koreksi a inner join
            pn.pn_agenda_koreksi_klaim b on a.kode_agenda=b.kode_agenda 
			$rb_selected 
			left outer join
            pn.pn_klaim c on b.kode_klaim=c.kode_klaim left outer join 
            pn.pn_kode_jenis_agenda_kor_detil  d on a.kode_jenis_agenda_detil=d.kode_jenis_agenda_detil
          where d.kode_jenis_agenda_detil='PP0206'
          and a.pemilik_data = '$kode_user'
          and a.kode_kantor =  '$kode_kantor' 
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    //echo $sql;
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
  }else if($SUBTYPE=='PP0207')
  {
	// update status data terlebih dahulu sesuai data klaim sebelum data ditampilkan
	// $sql = "BEGIN PN.P_PN_PN60010205.X_UPDATE_KOREKSI_KLAIM_JHT_ALL
	// 		(
	// 			'$USER',
	// 			:p_sukses,
	// 			:p_mess
	// 		); 
	// 		END;";
	// print_r($sql);
	// die();   
  
	// $proc = $DB->parse($sql);       
	// oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
	// oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
	
	// $DB->execute();

	//
	
    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
	 $ls_selected_kategori = $_POST['reg_kategori'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
	
	  $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_koreksi = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      and b.status_submit_koreksi = 'Y'
                      and b.status_batal = 'T'";
    }

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY B.KODE_KLAIM ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY C.KPJ ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="select rownum as no_urut,a.kode_agenda,a.kode_jenis_agenda_detil,d.nama_jenis_agenda_detil,d.url_path,
            to_char(a.tgl_agenda,'DD/MM/YYYY') tgl_agenda, a.detil_status,c.kpj,c.nama_tk,b.kode_klaim,
            a.tgl_rekam,a.petugas_rekam,a.status_agenda,a.kode_kantor
          from pn.pn_agenda_koreksi a inner join
            pn.pn_agenda_koreksi_klaim b on a.kode_agenda=b.kode_agenda 
			$rb_selected 
			left outer join
            pn.pn_klaim c on b.kode_klaim=c.kode_klaim left outer join 
            pn.pn_kode_jenis_agenda_kor_detil  d on a.kode_jenis_agenda_detil=d.kode_jenis_agenda_detil
          where d.kode_jenis_agenda_detil='PP0207'
          and a.pemilik_data = '$kode_user'
          and a.kode_kantor =  '$kode_kantor' 
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    //echo $sql;
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
  }
  else if($SUBTYPE=='PP0208')
  {
	// update status data terlebih dahulu sesuai data klaim sebelum data ditampilkan
	// $sql = "BEGIN PN.P_PN_PN60010206.X_UPDATE_KOREKSI_KLAIM_JPN_ALL
	// 		(
	// 			'$USER',
	// 			:p_sukses,
	// 			:p_mess
	// 		); 
	// 		END;";
	// // print_r($sql);
	// // die();   
	// $proc = $DB->parse($sql);       
	// oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
	// oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
	
	// $DB->execute();
	//
	
    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
	 $ls_selected_kategori = $_POST['reg_kategori'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
	
	  $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_koreksi = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      and b.status_submit_koreksi = 'Y'
                      and b.status_batal = 'T'";
    }

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY B.KODE_KLAIM ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY C.KPJ ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="select rownum as no_urut,a.kode_agenda,a.kode_jenis_agenda_detil,d.nama_jenis_agenda_detil,d.url_path,
            to_char(a.tgl_agenda,'DD/MM/YYYY') tgl_agenda, a.detil_status,c.kpj,c.nama_tk,b.kode_klaim,
            a.tgl_rekam,a.petugas_rekam,a.status_agenda,a.kode_kantor
          from pn.pn_agenda_koreksi a inner join
            pn.pn_agenda_koreksi_klaim b on a.kode_agenda=b.kode_agenda 
			$rb_selected 
			left outer join
            pn.pn_klaim c on b.kode_klaim=c.kode_klaim left outer join 
            pn.pn_kode_jenis_agenda_kor_detil  d on a.kode_jenis_agenda_detil=d.kode_jenis_agenda_detil
          where d.kode_jenis_agenda_detil='PP0208'
          and a.pemilik_data = '$kode_user'
          and a.kode_kantor =  '$kode_kantor' 
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    //echo $sql;
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
  } else if($SUBTYPE=='PP0301'){

    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
	  $ls_selected_kategori = $_POST['reg_kategori'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
	
	  $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_tindak_lanjut = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      and b.status_submit_tindak_lanjut = 'Y'
                      and b.status_batal = 'T'";
    }

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="SELECT ROWNUM AS no_urut,
                    a.kode_agenda,
                    a.kode_jenis_agenda_detil,
                    d.nama_jenis_agenda_detil,
                    b.nomor_identitas,
                    b.nama_lengkap nama_tk,
                    TO_CHAR (a.tgl_agenda, 'DD/MM/YYYY') tgl_agenda,
                    a.detil_status,
                    b.nama_lengkap,
                    a.tgl_rekam,
                    a.petugas_rekam,
                    a.status_agenda,
                    a.kode_kantor,
                    d.url_path,
                    '' kode_klaim,
                    '' kpj
                FROM pn.pn_agenda_koreksi a
                    INNER JOIN pn.PN_AGENDA_VERIFIKASI_JHT b
                      ON a.kode_agenda = b.kode_agenda $rb_selected 
                    LEFT JOIN pn.pn_kode_jenis_agenda_kor_detil d
                        ON d.kode_jenis_agenda_detil = a.kode_jenis_agenda_detil
              WHERE     b.kode_jenis_agenda_detil = 'PP0301'
                        AND a.pemilik_data = '$kode_user'
                        AND a.kode_kantor =  '$kode_kantor' 
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    // var_dump($sql); die();
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }

  } else if($SUBTYPE=='PP0302'){

    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
	  $ls_selected_kategori = $_POST['reg_kategori'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
	
	  $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_tindak_lanjut = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      -- and b.status_submit_tindak_lanjut = 'T'
                      and b.status_batal = 'T'";
      // 26-01-2024 komen query  b.status_submit_tindak_lanjut = 'Y' agar memunculkan di filter data status agenda dan submit
    }

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="SELECT ROWNUM AS no_urut,
                    a.kode_agenda,
                    a.kode_jenis_agenda_detil,
                    d.nama_jenis_agenda_detil,
                    b.nomor_identitas,
                    b.nama_lengkap nama_tk,
                    TO_CHAR (a.tgl_agenda, 'DD/MM/YYYY') tgl_agenda,
                    a.detil_status,
                    b.nama_lengkap,
                    a.tgl_rekam,
                    a.petugas_rekam,
                    a.status_agenda,
                    a.kode_kantor,
                    d.url_path,
                    '' kode_klaim,
                    '' kpj
                FROM pn.pn_agenda_koreksi a
                    INNER JOIN pn.PN_AGENDA_VERIFIKASI_JHT b
                      ON a.kode_agenda = b.kode_agenda $rb_selected 
                    LEFT JOIN pn.pn_kode_jenis_agenda_kor_detil d
                        ON d.kode_jenis_agenda_detil = a.kode_jenis_agenda_detil
              WHERE     b.kode_jenis_agenda_detil = 'PP0302'
                        AND a.pemilik_data = '$kode_user'
                        AND a.kode_kantor =  '$kode_kantor' 
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    // var_dump($sql); die();
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }

  } else if($SUBTYPE=='PP0305'){
    // 05-01-2024 Penamabahan validasi untuk kode_jenis_agenda_detil PP0305 yang mengikuti PP0302

    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
	  $ls_selected_kategori = $_POST['reg_kategori'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
	
	  $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_tindak_lanjut = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      and b.status_batal = 'T'";
    }

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="SELECT ROWNUM AS no_urut,
                    a.kode_agenda,
                    a.kode_jenis_agenda_detil,
                    d.nama_jenis_agenda_detil,
                    b.nomor_identitas,
                    b.nama_lengkap nama_tk,
                    TO_CHAR (a.tgl_agenda, 'DD/MM/YYYY') tgl_agenda,
                    a.detil_status,
                    b.nama_lengkap,
                    a.tgl_rekam,
                    a.petugas_rekam,
                    a.status_agenda,
                    a.kode_kantor,
                    d.url_path,
                    '' kode_klaim,
                    '' kpj
                FROM pn.pn_agenda_koreksi a
                    INNER JOIN pn.PN_AGENDA_VERIFIKASI_JHT b
                      ON a.kode_agenda = b.kode_agenda $rb_selected 
                    LEFT JOIN pn.pn_kode_jenis_agenda_kor_detil d
                        ON d.kode_jenis_agenda_detil = a.kode_jenis_agenda_detil
              WHERE     b.kode_jenis_agenda_detil = 'PP0305'
                        AND a.pemilik_data = '$kode_user'
                        AND a.kode_kantor =  '$kode_kantor' 
          ";
    $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
            where no_urut between {$start} and {$end}";
    // var_dump($sql); die();
    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }

  } else if($SUBTYPE=='PP0303'){

    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
	  $ls_selected_kategori = $_POST['reg_kategori'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
	
	  $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_tindak_lanjut = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      and b.status_submit_tindak_lanjut = 'Y'
                      and b.status_batal = 'T'";
    }

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="SELECT 
                    a.kode_agenda,
                    a.kode_jenis_agenda_detil,
                    d.nama_jenis_agenda_detil,
                    b.nomor_identitas,
                    b.nama_lengkap nama_tk,
                    TO_CHAR (a.tgl_agenda, 'DD/MM/YYYY') tgl_agenda,
                    a.detil_status,
                    b.nama_lengkap,
                    a.tgl_rekam,
                    a.petugas_rekam,
                    a.status_agenda,
                    a.kode_kantor,
                    d.url_path,
                    '' kode_klaim,
                    b.kpj
                FROM pn.pn_agenda_koreksi a
                    INNER JOIN pn.PN_AGENDA_FLAG_DIAKUI b
                      ON a.kode_agenda = b.kode_agenda $rb_selected 
                    LEFT JOIN pn.pn_kode_jenis_agenda_kor_detil d
                        ON d.kode_jenis_agenda_detil = a.kode_jenis_agenda_detil
              WHERE     b.kode_jenis_agenda_detil = 'PP0303'
                        AND a.pemilik_data = '$kode_user'
                        AND a.kode_kantor =  '$kode_kantor' 
          ";
    $sql = "SELECT * FROM (SELECT ROWNUM AS no_urut, C.* FROM ({$sql_utama} {$order} ) C WHERE 1=1 {$condition} )
            where no_urut between {$start} and {$end}";

    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} ) C WHERE 1=1 {$condition} ";
    
    // var_dump($queryTotal); die();
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }

  } else if($SUBTYPE=='PP0306'){
    // 06-02-2024 Penamabahan validasi untuk kode_jenis_agenda_detil PP0306 yang mengikuti PP0303

    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
	  $ls_selected_kategori = $_POST['reg_kategori'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
	
	  $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_tindak_lanjut = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      and b.status_batal = 'T'";
    }

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="SELECT 
                    a.kode_agenda,
                    a.kode_jenis_agenda_detil,
                    d.nama_jenis_agenda_detil,
                    b.nomor_identitas,
                    b.nama_lengkap nama_tk,
                    TO_CHAR (a.tgl_agenda, 'DD/MM/YYYY') tgl_agenda,
                    a.detil_status,
                    b.nama_lengkap,
                    a.tgl_rekam,
                    a.petugas_rekam,
                    a.status_agenda,
                    a.kode_kantor,
                    d.url_path,
                    '' kode_klaim,
                    b.kpj
                FROM pn.pn_agenda_koreksi a
                    INNER JOIN pn.PN_AGENDA_FLAG_DIAKUI b
                      ON a.kode_agenda = b.kode_agenda $rb_selected 
                    LEFT JOIN pn.pn_kode_jenis_agenda_kor_detil d
                        ON d.kode_jenis_agenda_detil = a.kode_jenis_agenda_detil
              WHERE     b.kode_jenis_agenda_detil = 'PP0306'
                        AND a.pemilik_data = '$kode_user'
                        AND a.kode_kantor =  '$kode_kantor' 
          ";
    $sql = "SELECT * FROM (SELECT ROWNUM AS no_urut, C.* FROM ({$sql_utama} {$order} ) C WHERE 1=1 {$condition} )
            where no_urut between {$start} and {$end}";

    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} ) C WHERE 1=1 {$condition} ";
    
    // var_dump($queryTotal); die();
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }

  } else if($SUBTYPE=='PP0304'){

    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
	  $ls_selected_kategori = $_POST['reg_kategori'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
	
	  $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_tindak_lanjut = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      and b.status_submit_tindak_lanjut = 'Y'
                      and b.status_batal = 'T'";
    }

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="SELECT 
                    a.kode_agenda,
                    a.kode_jenis_agenda_detil,
                    d.nama_jenis_agenda_detil,
                    b.nomor_identitas,
                    b.nama_lengkap nama_tk,
                    TO_CHAR (a.tgl_agenda, 'DD/MM/YYYY') tgl_agenda,
                    a.detil_status,
                    b.nama_lengkap,
                    a.tgl_rekam,
                    a.petugas_rekam,
                    a.status_agenda,
                    a.kode_kantor,
                    d.url_path,
                    '' kode_klaim,
                    b.kpj
                FROM pn.pn_agenda_koreksi a
                    INNER JOIN pn.PN_AGENDA_FLAG_DIAKUI b
                      ON a.kode_agenda = b.kode_agenda $rb_selected 
                    LEFT JOIN pn.pn_kode_jenis_agenda_kor_detil d
                        ON d.kode_jenis_agenda_detil = a.kode_jenis_agenda_detil
              WHERE     b.kode_jenis_agenda_detil = 'PP0304'
                        AND a.pemilik_data = '$kode_user'
                        AND a.kode_kantor =  '$kode_kantor' 
          ";
    $sql = "SELECT * FROM (SELECT ROWNUM AS no_urut, C.* FROM ({$sql_utama} {$order} ) C WHERE 1=1 {$condition} )
            where no_urut between {$start} and {$end}";

    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} ) C WHERE 1=1 {$condition} ";
    
    // var_dump($sql); die();
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }

  }else if($SUBTYPE=='PP0501'){

    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];
	  $ls_selected_kategori = $_POST['reg_kategori'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
	
	  $rb_selected ="";
    //add query condition for keyword
    if($ls_selected_kategori=="APPROVED"){
      $rb_selected = "and b.status_approval = 'Y'
                      and b.status_submit_tindak_lanjut = 'Y'
                      and b.status_batal = 'T'";
    }
    if($ls_selected_kategori=="SUBMIT"){
      $rb_selected = "and b.status_approval = 'T'
                      and b.status_submit_tindak_lanjut = 'Y'
                      and b.status_batal = 'T'";
    }

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.KODE_KANTOR ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY B.NAMA_TK ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.detil_status ";
    }else
      $order = "ORDER BY A.TGL_REKAM DESC";

    $sql_utama="SELECT 
                    a.kode_agenda,
                    a.kode_jenis_agenda_detil,
                    d.nama_jenis_agenda_detil,
                    b.nomor_identitas,
                    b.nama_lengkap nama_tk,
                    TO_CHAR (a.tgl_agenda, 'DD/MM/YYYY') tgl_agenda,
                    a.detil_status,
                    b.nama_lengkap,
                    a.tgl_rekam,
                    a.petugas_rekam,
                    a.status_agenda,
                    a.kode_kantor,
                    d.url_path,
                    '' kode_klaim,
                    b.kpj
                FROM pn.pn_agenda_koreksi a
                    INNER JOIN pn.pn_agenda_lepas_fiktif b
                      ON a.kode_agenda = b.kode_agenda $rb_selected 
                    LEFT JOIN pn.pn_kode_jenis_agenda_kor_detil d
                        ON d.kode_jenis_agenda_detil = a.kode_jenis_agenda_detil
              WHERE     b.kode_jenis_agenda_detil = 'PP0501'
                        AND a.pemilik_data = '$kode_user'
                        AND a.kode_kantor =  '$kode_kantor' 
          ";
    $sql = "SELECT * FROM (SELECT ROWNUM AS no_urut, A.* FROM ({$sql_utama} {$order} ) A WHERE 1=1 {$condition} )
            where no_urut between {$start} and {$end}";       

    $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} ) A WHERE 1=1 {$condition} ";
    
    $recordsTotal = $DB->get_data($queryTotal);


    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }

  }
  // else if($SUBTYPE=='PP0207')
  // {
  //   $ls_search_pilihan    = $_POST['search_pilihan'];
  //   $ls_search_keyword    = $_POST['keyword'];
  //   $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
  //   $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];

  //   $draw = 1;
  //   if(isset($_POST['draw'])){
  //       $draw = $_POST['draw']; 
  //   }else{
  //       $draw = 1;       
  //   }

  //   $start = $_POST['start']+1;
  //   $length = $_POST['length'];
  //   $end = ($start-1) + $length;

  //   $condition ="";
  //   //add query condition for keyword
  //   if($ls_search_pilihan!=""){
  //     $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
  //   } 

  //   $order      = $_POST["order"];
  //   $by = $order[0]['dir'];

  //   if($order[0]['column']=='0'){
  //       $order = "ORDER BY A.KODE_AGENDA DESC";
  //   }else if($order[0]['column']=='1'){
  //       $order = "ORDER BY A.KODE_JENIS_AGENDA_DETIL ";
  //   }else if($order[0]['column']=='2'){
  //       $order = "ORDER BY A.KODE_KANTOR ";
  //   }else if($order[0]['column']=='3'){
  //       $order = "ORDER BY B.KODE_KLAIM ";
  //   }else if($order[0]['column']=='4'){
  //       $order = "ORDER BY C.KPJ ";
  //   }else if($order[0]['column']=='5'){
  //       $order = "ORDER BY B.NAMA_TK ";
  //   }else if($order[0]['column']=='6'){
  //       $order = "ORDER BY A.TGL_AGENDA ";
  //   }else if($order[0]['column']=='7'){
  //       $order = "ORDER BY A.detil_status ";
  //   }else
  //     $order = "ORDER BY A.TGL_REKAM DESC";

  //   $sql_utama="
  //   SELECT ROWNUM                               AS no_urut,
  //         a.kode_agenda,
  //         a.kode_jenis_agenda_detil,
  //         d.nama_jenis_agenda_detil,
  //         d.url_path,
  //         TO_CHAR (a.tgl_agenda, 'DD/MM/YYYY') tgl_agenda,
  //         a.detil_status,
  //         b.kpj,
  //         b.nama_tk,
  //         b.kode_klaim,
  //         a.tgl_rekam,
  //         a.petugas_rekam,
  //         a.status_agenda,
  //         a.kode_kantor
  //     FROM pn.pn_agenda_koreksi a
  //         INNER JOIN pn.pn_agenda_mutasi_sal b ON a.kode_agenda = b.kode_agenda
  //         LEFT OUTER JOIN pn.pn_kode_jenis_agenda_kor_detil d
  //             ON a.kode_jenis_agenda_detil = d.kode_jenis_agenda_detil
  //   WHERE     d.kode_jenis_agenda_detil = 'PP0207'
  //         and a.pemilik_data = '$kode_user'
  //         and a.kode_kantor =  '$kode_kantor'";
  //   $sql = "SELECT * FROM ({$sql_utama} {$condition} {$order} ) 
  //           where no_urut between {$start} and {$end}";
  //   //echo $sql;
  //   $queryTotal = " SELECT COUNT(1) FROM ({$sql_utama} {$condition} )";
  //   $recordsTotal = $DB->get_data($queryTotal);      

  //   $DB->parse($sql);
  //   if($DB->execute()){ 
  //     $i = 0;
  //     while($data = $DB->nextrow())
  //     {
  //       $data['ACTION'] = "";
  //         $jsondata .= json_encode($data);
  //         $jsondata .= ',';
  //         $i++;
  //     }
  //     $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
  //     $jsondata .= ']}';
  //     $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
  //     echo $jsondata;
  //   } else {
  //     echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
  //   }
  // }
  else
  {

    $ls_search_pilihan    = $_POST['search_pilihan'];
    $ls_search_keyword    = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_pilihan!=""){
      $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_AGENDA DESC";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY B.NAMA_JENIS_AGENDA ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY C.NAMA_JENIS_AGENDA_DETIL ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY E.KODE_FASKES ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY E.NO_FASKES ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY E.NAMA_FASKES ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY A.TGL_AGENDA ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY A.DETIL_STATUS ";
    }
    //$order .= $by;
    //$order .= 'DESC';
     $order = "ORDER BY A.TGL_REKAM DESC ";

    $sql = "SELECT * FROM (SELECT  ROWNUM AS NO_URUT,
									'' KODE_KLAIM,
									'' KPJ,
									'' NAMA_TK,
                                    A.KODE_AGENDA,
                                    A.KODE_JENIS_AGENDA, 
                                    B.NAMA_JENIS_AGENDA,
                                    B.KODE_TIPE_AGENDA,
                                    D.NAMA_TIPE_AGENDA,
                                    A.KODE_JENIS_AGENDA_DETIL,
                                    C.NAMA_JENIS_AGENDA_DETIL,
                                    A.KODE_KANTOR,
                                    E.KODE_FASKES,
                                    E.NO_FASKES,
                                    -- (SELECT NAMA_FASKES FROM TC.TC_FASKES 
                                    --     WHERE KODE_FASKES = E.KODE_FASKES
                                    -- ) NAMA_FASKES,
                                    E.NAMA_FASKES,
                                    TO_CHAR(A.TGL_AGENDA,'DD/MM/RRRR') TGL_AGENDA,
                                    A.KETERANGAN,
                                    A.PEMILIK_DATA,
                                    A.TGL_SELESAI,
                                    A.STATUS_AGENDA,
                                    A.DETIL_STATUS,
                                    A.KODE_KELOMPOK_AGENDA,
                                    C.URL_PATH
                      FROM PN.PN_AGENDA_KOREKSI A, PN.PN_KODE_JENIS_AGENDA_KOREKSI B, PN.PN_KODE_JENIS_AGENDA_KOR_DETIL C, PN.PN_KODE_TIPE_AGENDA_KOREKSI D, TC.TC_FASKES E
                            WHERE A.KODE_JENIS_AGENDA = B.KODE_JENIS_AGENDA
                            AND A.KODE_JENIS_AGENDA_DETIL = C.KODE_JENIS_AGENDA_DETIL
                            AND B.KODE_TIPE_AGENDA = D.KODE_TIPE_AGENDA
                            AND A.REFERENSI = E.KODE_FASKES
                            --AND A.KODE_AGENDA = E.KODE_AGENDA
                            AND A.KODE_KELOMPOK_AGENDA = 'I'  
                            --AND A.PEMILIK_DATA = '$USER' 
                            AND A.KODE_KANTOR IN (SELECT KODE_KANTOR FROM SIJSTK.MS_KANTOR START WITH KODE_KANTOR = '$kode_kantor' CONNECT BY PRIOR KODE_KANTOR = KODE_KANTOR_INDUK)
                            AND EXISTS (SELECT 1 FROM PN.PN_KODE_JENIS_AGENDA_KOR_ROLE 
                                        WHERE KODE_JENIS_AGENDA_DETIL = A.KODE_JENIS_AGENDA_DETIL
                                        AND KODE_FUNGSI = '$ses_reg_role')  
                            ".$condition." ".$order." ) 
            where no_urut between ".$start." and ".$end;

    //echo $sql;

    $queryTotal = " SELECT COUNT(1) FROM (SELECT  ROWNUM AS NO_URUT,
                                    A.KODE_AGENDA,
                                    A.KODE_JENIS_AGENDA, 
                                    B.NAMA_JENIS_AGENDA,
                                    B.KODE_TIPE_AGENDA,
                                    D.NAMA_TIPE_AGENDA,
                                    A.KODE_JENIS_AGENDA_DETIL,
                                    C.NAMA_JENIS_AGENDA_DETIL,
                                    A.KODE_KANTOR,
                                    E.KODE_FASKES,
                                    E.NO_FASKES,
                                    E.NAMA_FASKES,
                                    A.TGL_AGENDA,
                                    A.KETERANGAN,
                                    A.PEMILIK_DATA,
                                    A.TGL_SELESAI,
                                    A.STATUS_AGENDA,
                                    A.DETIL_STATUS,
                                    A.KODE_KELOMPOK_AGENDA,
                                    C.URL_PATH
                      FROM PN.PN_AGENDA_KOREKSI A, PN.PN_KODE_JENIS_AGENDA_KOREKSI B, PN.PN_KODE_JENIS_AGENDA_KOR_DETIL C, PN.PN_KODE_TIPE_AGENDA_KOREKSI D, TC.TC_FASKES E
                            WHERE A.KODE_JENIS_AGENDA = B.KODE_JENIS_AGENDA
                            AND A.KODE_JENIS_AGENDA_DETIL = C.KODE_JENIS_AGENDA_DETIL
                            AND B.KODE_TIPE_AGENDA = D.KODE_TIPE_AGENDA
                            AND A.REFERENSI = E.KODE_FASKES
                            --AND A.KODE_AGENDA = E.KODE_AGENDA
                            AND A.KODE_KELOMPOK_AGENDA = 'I' 
                            --AND A.PEMILIK_DATA = '$USER' 
                            AND A.KODE_KANTOR IN (SELECT KODE_KANTOR FROM SIJSTK.MS_KANTOR START WITH KODE_KANTOR = '$kode_kantor' CONNECT BY PRIOR KODE_KANTOR = KODE_KANTOR_INDUK)
                            AND EXISTS (SELECT 1 FROM PN.PN_KODE_JENIS_AGENDA_KOR_ROLE 
                                        WHERE KODE_JENIS_AGENDA_DETIL = A.KODE_JENIS_AGENDA_DETIL
                                        AND KODE_FUNGSI = '$ses_reg_role') 
                            ".$condition." ".$order." )"
              ;
     // print_r($sql);
     // die;
    $recordsTotal = $DB->get_data($queryTotal);      

    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
        $data['ACTION'] = "";
          $jsondata .= json_encode($data);
          $jsondata .= ',';
          $i++;
      }
      $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
      $jsondata .= ']}';
      $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
      echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
  }

}

else if($TYPE=='PP0102'){
    

}

else{
    echo '{"ret":-1,"msg":"Tidak ada tipe yang dipilih"}';
}
?>
