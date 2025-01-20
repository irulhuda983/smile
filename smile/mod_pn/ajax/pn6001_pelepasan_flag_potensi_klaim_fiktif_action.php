<?PHP
session_start();
include_once "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

require_once __DIR__ . "../../logs.php";

$logs = new Logs();
$logs->setEventName('PN6001 - Koreksi Data');
$logs->start();

function encrypt_decrypt($action, $string)
{
    /* =================================================
     * ENCRYPTION-DECRYPTION
     * =================================================
     * ENCRYPTION: encrypt_decrypt('encrypt', $string);
     * DECRYPTION: encrypt_decrypt('decrypt', $string) ;
     */
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'WS-SERVICE-KEY';
    $secret_iv = 'WS-SERVICE-VALUE';
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else {
        if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
    }
    return $output;
}

$TYPE           = $_POST["TYPE"];
$SUBTYPE        = $_REQUEST["SUBTYPE"];
$kode_user      = $_SESSION["USER"];
$kode_kantor    = $_SESSION['kdkantorrole'];
$ses_reg_role   = $_SESSION['regrole'];


if($TYPE=='INSERT_KARTU'){
  if($SUBTYPE=='PP0301' || $SUBTYPE=='PP0302'){
    if($tipe_submit == 'TAMBAH_KARTU'){
      $kode_tk      = $_POST['kode_tk'];
      $kode_agenda  = $_POST['kode_agenda'];
      $kode_dok     = '';

      if($SUBTYPE=='PP0301'){
        $kode_dok     = 'D436';
      }else if($SUBTYPE=='PP0302'){
        $kode_dok     = 'D437';
      }

      $sql =  "BEGIN
                  PN.P_PN_PN60010210.X_INSERT_VERIFIKASI_JHT_TUKEP ('$kode_agenda',
                                              '$kode_tk',
                                              '$kode_dok',
                                              '$kode_user',
                                              :P_KODE_AGENDA_ADJ,
                                              :P_MESS,
                                              :P_SUKSES);
              END;";
            // var_dump($sql);die();
      $proc = $DB->parse($sql);       
      oci_bind_by_name($proc, ":P_KODE_AGENDA_ADJ", $p_kode_agenda_adj,32);
      oci_bind_by_name($proc, ":P_SUKSES", $p_sukses,32);
      oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);
      
      $DB->execute();
      
      if($p_sukses == '1'){
        echo '{"ret":0,"msg":"Agenda berhasil dibuat!","dataid":"'.$p_kode_agenda_adj.'"}';
      } else {
        echo '{"ret":-1,"msg":"Proses gagal, tambah kartu gagal diproses!"}';
      } 
    } 

    

  }

} else if($TYPE=='SUBMIT'){
    
    $logs->setFunctionName('mod_pn/ajax/pn6001_pelepasan_flag_potensi_klaim_fiktif_action.php/submit');

    $kode_agenda    = $_POST['kode_agenda'];
    $kode_dok       = $_POST['kode_dok'];
    $mime_tipe      = $_POST['i_mime_tipe'];
    $path_url       = $_POST['i_path_url'];
    $tipe_submit    = $_POST['tipe_submit'];
    $keterangan     = $_POST['tb_keterangan'];

    $list_kode_dok  = explode('###', $kode_dok);
    $list_mime_tipe = explode('###', $mime_tipe);
    $list_path_url  = explode('###', $path_url);

    $i_kode_dok     = count($list_kode_dok);
    $i_mime_tipe    = count($list_mime_tipe);
    $i_path_url     = count($list_path_url);

    for($i=0;$i<$i_kode_dok-1;$i++){
        $sql = " UPDATE PN.PN_AGENDA_LEPAS_FIKTIF_DOK
                    SET MIME_TYPE = '$list_mime_tipe[$i]',
                        PATH_URL = '$list_path_url[$i]',
                        FLAG_UPLOAD = 'Y',
                        TGL_UPLOAD = SYSDATE,
                        TGL_UBAH = SYSDATE,
                        PETUGAS_UBAH = '$kode_user'
                WHERE KODE_AGENDA = '$kode_agenda' AND KODE_DOKUMEN = '$list_kode_dok[$i]'";

        $DB->parse($sql);
        $DB->execute();
    }
    
        
    $sql =  "BEGIN
                PN.P_PN_PN60010212.X_SUBMIT_TINDAK_LANJUT ('$kode_agenda',
                                            '$kode_user',
                                            '$keterangan',
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

        $ls_sukses = $p_sukses;
        $ls_mess = $p_mess;

        $logs->info( str_replace(' ', '', json_encode(str_replace(["\n", "\r"], '', $sql))), '{"ret" : "'.$ls_sukses.'","msg" : "'.$ls_mess.'"}', "Submit data pelepasan potensi klaim fiktif");
        $logs->stop('-', '-', 'PN6001 - Koreksi Data');

        $sql = " UPDATE PN.PN_AGENDA_KOREKSI SET STATUS_AGENDA = 'TERBUKA', DETIL_STATUS = 'SUBMIT', DIAJUKAN_KE_FUNGSI = '6', DIAJUKAN_KE_KANTOR = '$kode_kantor' WHERE KODE_AGENDA = '$kode_agenda'";
        $DB->parse($sql);

        if($DB->execute()){
            echo '{"ret":0,"msg":"Agenda berhasil disubnit.","dataid":"'.$kode_agenda.'"}';
        } else {
            echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal dibuat!"}';
        } 
    } else {
        echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal dibuat!"}';
    } 


} else if($TYPE=='BATAL'){

    $logs->setFunctionName('mod_pn/ajax/pn6001_pelepasan_flag_potensi_klaim_fiktif_action.php/batal');
    
    $kode_agenda    = $_POST['kode_agenda'];
    $tipe_submit    = $_POST['tipe_submit'];
    $keterangan     = $_POST['keterangan'];
        
    $sql =  "BEGIN
                PN.P_PN_PN60010212.X_BATAL_TINDAK_LANJUT ('$kode_agenda',
                                            '$kode_user',
                                            '$keterangan',
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

            $ls_sukses = $p_sukses;
            $ls_mess = $p_mess;
        
            $logs->info( str_replace(' ', '', json_encode(str_replace(["\n", "\r"], '', $sql))), '{"ret" : "'.$ls_sukses.'","msg" : "'.$ls_mess.'"}', "Batal data pelepasan potensi klaim fiktif");
            $logs->stop('-', '-', 'PN6001 - Koreksi Data');

            if($tipe_submit == 'BATAL_SUBMIT'){
                $sql = " UPDATE PN.PN_AGENDA_KOREKSI SET STATUS_AGENDA = 'BATAL', DETIL_STATUS = 'BATAL', DIAJUKAN_KE_KANTOR = '$kode_kantor' WHERE KODE_AGENDA = '$kode_agenda'";
            } else {
                $sql = " UPDATE PN.PN_AGENDA_KOREKSI SET STATUS_AGENDA = 'TERBUKA', DETIL_STATUS = 'AGENDA' WHERE KODE_AGENDA = '$kode_agenda'";
                
            }
            $DB->parse($sql);

            if($DB->execute()){
                echo '{"ret":0,"msg":"Agenda berhasil ditolak.","dataid":"'.$kode_agenda.'"}';
            } else {
                echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal dibatalkan!"}';
            } 
        } else {
            echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal dibatalkan!"}';
        } 


} else if($TYPE=='APPROVE'){

    $logs->setFunctionName('mod_pn/ajax/pn6001_pelepasan_flag_potensi_klaim_fiktif_action.php/approve');
    
    $kode_agenda            = $_POST['kode_agenda'];
    $tipe_submit            = $_POST['tipe_submit'];
    $keterangan             = $_POST['keterangan'];
    $tb_kode_perihal_detil  = $_POST['tb_kode_perihal_detil'];
        
        $sql =  "BEGIN
                PN.P_PN_PN60010212.X_APPROVE_TINDAK_LANJUT ('$kode_agenda',
                                            '$tb_kode_perihal_detil',
                                            '$kode_user',
                                            '$keterangan',
                                            :P_KODE_AGENDA_ADJ,
                                            :P_MESS,
                                            :P_SUKSES);
            END;";
            // var_dump($sql); die();

        $proc = $DB->parse($sql);       
        oci_bind_by_name($proc, ":P_KODE_AGENDA_ADJ", $p_kode_agenda_adj,32);
        oci_bind_by_name($proc, ":P_SUKSES", $p_sukses,32);
        oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);
        
        $DB->execute();

        if($p_sukses == '1'){

            $ls_sukses = $p_sukses;
            $ls_mess = $p_mess;
        
            $logs->info( str_replace(' ', '', json_encode(str_replace(["\n", "\r"], '', $sql))), '{"ret" : "'.$ls_sukses.'","msg" : "'.$ls_mess.'"}', "Approve data pelepasan potensi klaim fiktif");
            $logs->stop('-', '-', 'PN6001 - Koreksi Data');

            $sql = " UPDATE PN.PN_AGENDA_KOREKSI SET STATUS_AGENDA = 'DITUTUP', DETIL_STATUS = 'APPROVED', DIAJUKAN_KE_KANTOR = '$kode_kantor' WHERE KODE_AGENDA = '$kode_agenda'";
            $DB->parse($sql);

            if($DB->execute()){
                echo '{"ret":0,"msg":"Agenda berhasil diapprove.","dataid":"'.$kode_agenda.'"}';
            } else {
                echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal diapprove!"}';
            } 
        } else {
            echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal diapprove!"}';
        } 
    


} else if($TYPE=='HAPUS_KARTU'){
    
    $kode_agenda    = $_POST['kode_agenda'];
    $SUBTYPE        = $_POST['SUBTYPE'];
    $kode_tk        = $_POST['kode_tk'];
        
    $sql =  "BEGIN
                PN.P_PN_PN60010210.X_HAPUS_KARTU ('$kode_agenda',
                                            '$SUBTYPE',
                                            '$kode_tk',
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
            echo '{"ret":0,"msg":"Data berhasil dihapus.","dataid":"'.$kode_tk.'"}'; 
        } else {
            echo '{"ret":-1,"msg":"Proses gagal, data gagal dihapus!"}';
        } 


} else if($TYPE=="downloadFileSmile"){

    $ls_path_url=$_POST['path_url'];
    $ls_path_url_encrypt = encrypt_decrypt('encrypt',$ls_path_url);
  
    $jsondata["pathUrlEncrypt"] = $ls_path_url_encrypt;
  
    echo json_encode($jsondata);
  
}else{
    echo '{"ret":-1,"msg":"Tidak ada tipe yang dipilih"}';
}
?>