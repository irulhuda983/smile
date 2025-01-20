<?PHP
session_start();
include_once "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
require_once __DIR__ . "../../logs.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);


$logs = new Logs();
$logs->setFunctionName('mod_pn/ajax/pn6001_verifikasi_kpj_dengan_satu_nomor_keps_action.php');
$logs->setEventName('PN6001 - Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan');
$logs->start();
$comment_if = null;

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
    $comment_if .= '[if ($TYPE == "INSERT_KARTU")]';
  if($SUBTYPE=='PP0301' || $SUBTYPE=='PP0305'){
    $comment_if .= '[if ($SUBTYPE=="PP0301" || $SUBTYPE=="PP0305")]';
    if($tipe_submit == 'TAMBAH_KARTU'){
      $comment_if .= '[if ($tipe_submit == "TAMBAH_KARTU")]';
      $kode_tk      = $_POST['kode_tk'];
      $kode_agenda  = $_POST['kode_agenda'];
      $kode_dok     = '';

      if($SUBTYPE=='PP0301'){
        $comment_if .= '[if ($SUBTYPE == "PP0301")]';
        $kode_dok     = 'D436';
      }else if($SUBTYPE=='PP0305'){
        $comment_if .= '[if ($SUBTYPE == "PP0305")]';
        $kode_dok     = 'D438';
      }

      $sql =  "BEGIN
                  PN.P_PN_PN60010305.X_INSERT_VERIFIKASI_JHT_TUKEP ('$kode_agenda',
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
        $comment_if .= '[if ($p_sukses == "1")]';
        $logs->info(json_encode($sql), json_encode($p_mess), "Insert Kartu Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan menggunakan prosedure : $comment_if");
        echo '{"ret":0,"msg":"Agenda berhasil dibuat!","dataid":"'.$p_kode_agenda_adj.'"}';
      } else {
        $logs->error(json_encode($sql), json_encode($p_mess), "Gagal Insert Kartu Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan menggunakan prosedure : $comment_if");
        echo '{"ret":-1,"msg":"'.$p_mess.'"}';
      } 
    } 

    

  }

} else if($TYPE=='SUBMIT'){
    $comment_if .= '[if ($TYPE == "SUBMIT")]';
    
    $kode_agenda    = $_POST['kode_agenda'];
    $kode_tk        = $_POST['kode_tk_dok'];
    $mime_tipe      = $_POST['i_mime_tipe'];
    $path_url       = $_POST['i_path_url'];
    $tipe_submit    = $_POST['tipe_submit'];
    $tb_keterangan  = $_POST['tb_keterangan'];

    $list_kode_tk   = explode('###', $kode_tk);
    $list_mime_tipe = explode('###', $mime_tipe);
    $list_path_url  = explode('###', $path_url);

    $i_kode_tk      = count($list_kode_tk);
    $i_mime_tipe    = count($list_mime_tipe);
    $i_path_url     = count($list_path_url);

    for($i=0;$i<$i_kode_tk-1;$i++){
        $sql = " UPDATE PN.PN_AGENDA_VERIFIKASI_JHT_TUDOK
                    SET MIME_TYPE = '$list_mime_tipe[$i]',
                        PATH_URL = '$list_path_url[$i]',
                        FLAG_UPLOAD = 'Y',
                        TGL_UPLOAD = SYSDATE,
                        PETUGAS_REKAM = '$kode_user'
                WHERE KODE_AGENDA = '$kode_agenda' AND KODE_TK = '$list_kode_tk[$i]'";

        $DB->parse($sql);
        $DB->execute();
    }

    

    
        
    $sql =  "BEGIN
                PN.P_PN_PN60010305.X_SUBMIT_TINDAK_LANJUT ('$kode_agenda',
                                            '$kode_user',
                                            '$tb_keterangan',
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
        $comment_if .= '[if ($p_sukses == "1")]';
        $logs->info(json_encode($sql), json_encode($p_mess), "Submit tindak lanjut Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan menggunakan prosedure : $comment_if");
        $sql = " UPDATE PN.PN_AGENDA_KOREKSI SET STATUS_AGENDA = 'TERBUKA', DETIL_STATUS = 'SUBMIT', DIAJUKAN_KE_FUNGSI = '6', DIAJUKAN_KE_KANTOR = '$kode_kantor' WHERE KODE_AGENDA = '$kode_agenda'";
        $DB->parse($sql);

        if($DB->execute()){
            $comment_if .= '[if ($DB->execute())]';
            $logs->info(json_encode($sql), json_encode($p_mess), "Update Status agenda Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan : $comment_if");
            echo '{"ret":0,"msg":"Agenda berhasil disubmit.","dataid":"'.$kode_agenda.'"}';
        } else {
            $logs->error(json_encode($sql), json_encode($p_mess), "Gagal Update Status agenda Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan : $comment_if");
            echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal dibuat!"}';
        } 
    } else {
        $logs->error(json_encode($sql), json_encode($p_mess), "Gagal Submit tindak lanjut Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan menggunakan prosedure : $comment_if");
        echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal dibuat!"}';
    } 


} else if($TYPE=='BATAL'){
    $comment_if .= '[if ($TYPE == "BATAL")]';
    
    $kode_agenda    = $_POST['kode_agenda'];
    $tipe_submit    = $_POST['tipe_submit'];
    $keterangan     = $_POST['keterangan'];
        
    $sql =  "BEGIN
                PN.P_PN_PN60010305.X_BATAL_TINDAK_LANJUT ('$kode_agenda',
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
            $comment_if .= '[if ($p_sukses == "1")]';
            $logs->info(json_encode($sql), json_encode($p_mess), "Membatalkan Agenda Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan menggunakan prosedure : $comment_if");
            if($tipe_submit == 'BATAL_SUBMIT'){
                $comment_if .= '[if ($tipe_submit == "BATAL_SUBMIT")]';
                $sql = " UPDATE PN.PN_AGENDA_KOREKSI SET STATUS_AGENDA = 'BATAL', DETIL_STATUS = 'BATAL', DIAJUKAN_KE_KANTOR = '$kode_kantor' WHERE KODE_AGENDA = '$kode_agenda'";
            } else {
                $sql = " UPDATE PN.PN_AGENDA_KOREKSI SET STATUS_AGENDA = 'TERBUKA', DETIL_STATUS = 'AGENDA' WHERE KODE_AGENDA = '$kode_agenda'";
                
            }
            $DB->parse($sql);

            if($DB->execute()){
                $comment_if .= '[if ($DB->execute())]';
                $logs->info(json_encode($sql), json_encode($p_mess), "Update Status Agenda Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan : $comment_if");
                echo '{"ret":0,"msg":"Agenda berhasil ditolak.","dataid":"'.$kode_agenda.'"}';
            } else {
                $logs->error(json_encode($sql), json_encode($p_mess), "Gagal Update Status Agenda Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan : $comment_if");
                echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal dibatalkan!"}';
            } 
        } else {
            $logs->error(json_encode($sql), json_encode($p_mess), "Gagal Membatalkan Agenda Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan menggunakan prosedure : $comment_if");
            echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal dibatalkan!"}';
        } 


} else if($TYPE=='APPROVE'){
    $comment_if .= '[if ($TYPE == "APPROVE")]';
    $kode_agenda            = $_POST['kode_agenda'];
    $tipe_submit            = $_POST['tipe_submit'];
    $keterangan             = $_POST['keterangan'];
    $tb_kode_perihal_detil  = $_POST['tb_kode_perihal_detil'];
        
        $sql =  "BEGIN
                PN.P_PN_PN60010305.X_APPROVE_TINDAK_LANJUT ('$kode_agenda',
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
            $comment_if .= '[if ($p_sukses == "1")]';
            $logs->info(json_encode($sql), json_encode($p_mess), "Approve Tindak Lanjut Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan menggunakan prosedure : $comment_if");
            $sql = " UPDATE PN.PN_AGENDA_KOREKSI SET STATUS_AGENDA = 'DITUTUP', DETIL_STATUS = 'APPROVED', DIAJUKAN_KE_KANTOR = '$kode_kantor' WHERE KODE_AGENDA = '$kode_agenda'";
            $DB->parse($sql);

            if($DB->execute()){
                $comment_if .= '[if ($DB->execute())]';
                $logs->info(json_encode($sql), json_encode($p_mess), "Update Status Agenda Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan : $comment_if");
                echo '{"ret":0,"msg":"Agenda berhasil diapprove.","dataid":"'.$kode_agenda.'"}';
            } else {
                $logs->error(json_encode($sql), json_encode($p_mess), "Gagal Update Status Agenda Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan : $comment_if");
                echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal diapprove!"}';
            } 
        } else {
            $logs->error(json_encode($sql), json_encode($p_mess), "Gagal Approve Tindak Lanjut Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan menggunakan prosedure : $comment_if");
            echo '{"ret":-1,"msg":"Proses gagal, agendaa gagal diapprove!"}';
        } 
    


} else if($TYPE=='HAPUS_KARTU'){
    $comment_if .= '[if ($TYPE == "HAPUS_KARTU")]';
    
    $kode_agenda    = $_POST['kode_agenda'];
    $SUBTYPE        = $_POST['SUBTYPE'];
    $kode_tk        = $_POST['kode_tk'];
        
    $sql =  "BEGIN
                PN.P_PN_PN60010305.X_HAPUS_KARTU ('$kode_agenda',
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
            $comment_if .= '[if ($p_sukses == "1")]';
            $logs->info(json_encode($sql), json_encode($p_mess), "Hapus Kartu Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan menggunakan prosedure : $comment_if");
            echo '{"ret":0,"msg":"Data berhasil dihapus.","dataid":"'.$kode_tk.'"}'; 
        } else {
            $logs->error(json_encode($sql), json_encode($p_mess), "Gagal Hapus Kartu Lanjut Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan menggunakan prosedure : $comment_if");
            echo '{"ret":-1,"msg":"Proses gagal, data gagal dihapus!"}';
        } 


} else if($TYPE=="downloadFileSmile"){
    $comment_if .= '[if ($TYPE == "downloadFileSmile")]';

    $ls_path_url=$_POST['path_url'];
    $ls_path_url_encrypt = encrypt_decrypt('encrypt',$ls_path_url);
  
    $jsondata["pathUrlEncrypt"] = $ls_path_url_encrypt;
  
    $logs->info(json_encode($ls_path_url), json_encode($jsondata), "Hapus Kartu Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan menggunakan prosedure : $comment_if");
    echo json_encode($jsondata);
  
}else{
    echo '{"ret":-1,"msg":"Tidak ada tipe yang dipilih"}';
}


$logs->stop('-', '-', 'PN6001 - Perihal Peserta Terverifikasi Tidak Memiliki KPJ Dengan 1 (Satu) Nomor Kepesertaan');
?>
