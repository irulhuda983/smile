<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$USER = $_SESSION["USER"];

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

$TYPE           = $_POST["TYPE"];
$SUBTYPE        = $_REQUEST["SUBTYPE"];
$kode_user      = $_SESSION["USER"];
$kode_kantor    = $_SESSION['kdkantorrole'];
$ses_reg_role   = $_SESSION['regrole'];
$AKSI           = $_POST["aksi"];



  if($AKSI=='simpan'){

    $kode_jenis_agenda        = $_POST['tb_kode_perihal'];
    $kode_jenis_agenda_detil  = $_POST['tb_kode_perihal_detil'];
    $keterangan               = $_POST['tb_keterangan'];
    $tgl_kejadian             = $_POST['tgl_kejadian'];
    $kode_tk                  = $_POST['kode_tk'];
    $kpj                      = $_POST['kpj'];
    $nomor_identitas          = $_POST['nomor_identitas'];
    $nama_tk                  = $_POST['nama_tk'];
   
    $path_url                 = $_POST['path_url'];
    $mime_type                = $_POST['mime_type'];
    $flag_mandatory           = $_POST['flag_mandatory'];
    $flag_draft               = $_POST['flag_draft'];
 
    $keterangan_tindaklanjut  = $_POST['keterangan_tindaklanjut'];
    $tgl_lahir                = $_POST['tgl_lahir'];

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
                           KODE_KELOMPOK_AGENDA,
                           STATUS_AGENDA,
                           DETIL_STATUS,
                           REFERENSI
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
                           'I',
                           'TERBUKA',
                           'TERBUKA',
                           '".$kode_tk."'
                      )";
      $DB->parse($sql);
      if($DB->execute()){
                    $qry = "
													BEGIN 
                          pn.P_PN_PN60010401.x_insert_tdl_klaim_jkm
                          (
                            '$kd_agenda',
                            '$flag_draft',
                             TO_DATE('$tgl_kejadian','DD/MM/RRRR'),
                            '$nomor_identitas',
                            '$nama_tk',
                            TO_DATE('$tgl_lahir','DD/MM/RRRR'),
                            '$keterangan_tindaklanjut',
                            '$kode_kantor',
                            '$kode_user', 
                            :p_sukses, 
                            :p_mess
                          );
													END;";

                         
												  
													$proc = $DB->parse($qry);
                           oci_bind_by_name($proc, ":P_SUKSES", $p_sukses, 2);
													 oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);       
												
													if ($DB->execute()) {
														$ls_sukses = $p_sukses;
														$ls_mess = $p_mess; 

                                if($ls_sukses=="1"){
                                    $query="";
                                    if(isset($flag_mandatory)){
                                    $str=json_decode($flag_mandatory);
                                    $i=0;
                                    for($i=0;$i<ExtendedFunction::count($str);$i++){
                                      $str_array =$str[$i];
                                      $query .="UPDATE PN.PN_AGENDA_KOREKSI_KLAIM_JKMDOK SET FLAG_STATUS ='Y', PETUGAS_UBAH='{$USER}', TGL_UBAH=SYSDATE WHERE KODE_DOKUMEN = '{$str_array}' AND KODE_AGENDA='{$kd_agenda}';";
                                    }
                                    
                                    }

                                    if(isset($path_url)){
                                      if($path_url!=""){
                                      $query2="UPDATE PN.PN_AGENDA_KOREKSI_KLAIM_JKMDOK SET PATH_URL='{$path_url}', MIME_TYPE='{$mime_type}' WHERE KODE_AGENDA='{$kd_agenda}' AND KODE_DOKUMEN='D040';";
                                      }
                                    }
                                    
                                    
                                    $sql_dokumen = "BEGIN
                                    
                                            $query
                                            $query2
                                            COMMIT;
                                          END;
                                    ";
                                    $DB->parse($sql_dokumen);
                                    $DB->execute();
                                    $jsondata["ret"] = "0";
                                    $jsondata["msg"] = 	$ls_mess;
                                    $jsondata["kd_agenda"] = 	$kd_agenda;
                                    echo json_encode($jsondata);
                              }else{
                                $jsondata["ret"] = "-1";
                                $jsondata["msg"] = 	$ls_mess;
                                echo json_encode($jsondata);
                              }


													}else{
                            $jsondata["ret"] = "-1";
                            $jsondata["msg"] = "Terjadi kesalahan pada server";
                            echo json_encode($jsondata);
                          }
      
        
        }else{
          $jsondata["ret"] = "-1";
          $jsondata["msg"] = "Terjadi kesalahan pada server";
          echo json_encode($jsondata);
        }
      }
  }else if($AKSI=='submit'){

    $kode_jenis_agenda        = $_POST['tb_kode_perihal'];
    $kode_jenis_agenda_detil  = $_POST['tb_kode_perihal_detil'];
    $keterangan               = $_POST['tb_keterangan'];
    $tgl_kejadian             = $_POST['tgl_kejadian'];
    $kode_tk                  = $_POST['kode_tk'];
    $kpj                      = $_POST['kpj'];
    $nomor_identitas          = $_POST['nomor_identitas'];
    $nama_tk                  = $_POST['nama_tk'];
   
    $path_url                 = $_POST['path_url'];
    $mime_type                = $_POST['mime_type'];
    $flag_mandatory           = $_POST['flag_mandatory'];
    $flag_draft               = $_POST['flag_draft'];

    $keterangan_tindaklanjut  = $_POST['keterangan_tindaklanjut'];
    $tgl_lahir                = $_POST['tgl_lahir'];
    $kd_agenda                = $_POST['kd_agenda'];

    $ls_path_url_exists = $_POST["path_url_exists"];
    $str_array = explode("/", $ls_path_url_exists);
    $namaBucket = $str_array[1];
    $namaFolder = $str_array[2]."/".$str_array[3]."/".$str_array[4];
    $namaFile =  $str_array[6];

    
    $data_doc = array(
      'namaBucket'      =>  $namaBucket,
      'namaFolder'     =>  $namaFolder,
      'file'      => $namaFile
    );

    $headers = array(
      'Content-Type: application/json',
       'X-Forwarded-For: ' . $ipfwd
       );
  
                  $qry = "
                  BEGIN 
                  pn.P_PN_PN60010401.x_submit_tdl_klaim_jkm
                  (
                    '$kd_agenda',
                    '$keterangan_tindaklanjut',
                    '$kode_kantor',
                    '$kode_user',
                    :p_kode_agenda_sebab_na, 
                    :p_sukses, 
                    :p_mess
                  );
                  END;";
  
  
                
                  
                  $proc = $DB->parse($qry);
                  oci_bind_by_name($proc, ":P_SUKSES", $p_sukses, 2);
                  oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);
                  oci_bind_by_name($proc, ":p_kode_agenda_sebab_na", $p_kode_agenda_sebab_na,100);       
                
                  if ($DB->execute()) {
                    $ls_sukses = $p_sukses;
                    $ls_mess = $p_mess;
                    $ls_kode_agenda_sebab_na = $p_kode_agenda_sebab_na; 
  
                        if($ls_sukses=="1"){
                            $query="";
                            if(isset($flag_mandatory)){
                            $str=json_decode($flag_mandatory);
                            $i=0;
                            for($i=0;$i<ExtendedFunction::count($str);$i++){
                              $str_array =$str[$i];
                              $query .="UPDATE PN.PN_AGENDA_KOREKSI_KLAIM_JKMDOK SET FLAG_STATUS ='Y', PETUGAS_UBAH='{$USER}', TGL_UBAH=SYSDATE WHERE KODE_DOKUMEN = '{$str_array}' AND KODE_AGENDA='{$kd_agenda}';";
                            }
                            
                            }
  
                            if(isset($path_url)){
                              if($path_url!=""){
                                $result_doc = api_json_call($wsIpStorage."/object/delete", $headers, $data_doc);
                                $query2="UPDATE PN.PN_AGENDA_KOREKSI_KLAIM_JKMDOK SET PATH_URL='{$path_url}', MIME_TYPE='{$mime_type}', FLAG_STATUS ='Y', FLAG_UPLOAD='Y', PETUGAS_UBAH='{$USER}', TGL_UBAH=SYSDATE,  TGL_UPLOAD=SYSDATE  WHERE KODE_AGENDA='{$kd_agenda}' AND KODE_DOKUMEN='D040';";
                              }
                            }
                            
                            
                            $sql_dokumen = "BEGIN
                            
                                    $query
                                    $query2
                                    COMMIT;
                                  END;
                            ";
                            $DB->parse($sql_dokumen);
                            $DB->execute();
                            if($ls_kode_agenda_sebab_na !=null){

                              $sql_koreksi_sebab_na_tk="select a.*,
                              (select blth_na_akhir from pn.pn_agenda_koreksi_klaim_jkm b where b.kode_tk=a.kode_tk and status_batal='T') blth_na_akhir,
                              (select nomor_identitas from kn.kn_tk b where b.kode_tk=a.kode_tk and rownum=1) nomor_identitas
                               from KN.KN_AGENDA_KOREKSI_SEBAB_NA_TK a where a.kode_agenda='$ls_kode_agenda_sebab_na'";
                              $DB->parse($sql_koreksi_sebab_na_tk);
                              $DB->execute();
                              $row = $DB->nextrow();
                              $ls_nomor_identitas     = $row["NOMOR_IDENTITAS"];
                              $ls_kode_sebab_na       = $row["KODE_SEBAB_NA"];
                              $ls_kode_sebab_na_baru  = $row["KODE_SEBAB_NA_BARU"];
                              $ls_blth_na             = $row["BLTH_NA_AKHIR"];
                              $ls_petugas_rekam       = $row["PETUGAS_REKAM"];
                              $ls_kode_kantor_keps    = $row["KODE_KANTOR"];    
  
                              $data_koreksi_sebab_na_tk = array(
                                "chId"  => "SMILE",
                                "reqId" => $USER,
                                "nik" => $ls_nomor_identitas,
                                "statusAwal"  => $ls_kode_sebab_na,
                                "statusAkhir" => $ls_kode_sebab_na_baru,
                                "blth" => $ls_blth_na,
                                "kodeUser"  => $ls_petugas_rekam,
                                "kodeKantor" => $ls_kode_kantor_keps,
                                "idPointerAsal" => $ls_kode_agenda_sebab_na
                                );
  
                                $result_koreksi_sebab_na_tk = api_json_call( $wsIp."/JSKlaimJKP/KoreksiTKEligibleToSiapKerja", $headers, $data_koreksi_sebab_na_tk);
                              }  
                            $jsondata["ret"] = "0";
                            $jsondata["msg"] = 	$ls_mess;
                            $jsondata["kd_agenda"] = 	$kd_agenda;
                            $jsondata["kode_agenda_sebab_na"] = $ls_kode_agenda_sebab_na;
                            echo json_encode($jsondata);
                      }else{
                        $jsondata["ret"] = "-1";
                        $jsondata["msg"] = 	$ls_mess;
                        echo json_encode($jsondata);
                      }
  
  
                  }else{
                    $jsondata["ret"] = "-1";
                    $jsondata["msg"] = "Terjadi kesalahan pada server";
                    echo json_encode($jsondata);
                  }
   

  }else if($AKSI=="downloadFileSmile"){

    $ls_path_url=$_POST['path_url'];
    $ls_path_url_encrypt = encrypt_decrypt('encrypt',$ls_path_url);
  
    $jsondata["pathUrlEncrypt"] = $ls_path_url_encrypt;
  
    echo json_encode($jsondata);
  
  }else if($AKSI=="batal"){

    $keterangan_tindaklanjut  = $_POST['keterangan_tindaklanjut'];
    $kd_agenda                = $_POST['kd_agenda'];

          $qry = "
          BEGIN 
          pn.P_PN_PN60010401.x_batal_tdl_klaim_jkm
          (
            '$kd_agenda',
            '$keterangan_tindaklanjut',
            '$kode_user', 
            :p_sukses, 
            :p_mess
          );
          END;";



          
          $proc = $DB->parse($qry);
          oci_bind_by_name($proc, ":P_SUKSES", $p_sukses, 2);
          oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);       

          if ($DB->execute()) {
            $ls_sukses = $p_sukses;
            $ls_mess = $p_mess; 

                if($ls_sukses=="1"){
                  $jsondata["ret"] = "0";
                  $jsondata["msg"] = 	$ls_mess;
                  $jsondata["kd_agenda"] = 	$kd_agenda;
                  echo json_encode($jsondata);
                }else{
                  $jsondata["ret"] = "-1";
                  $jsondata["msg"] = 	$ls_mess;
                  echo json_encode($jsondata);
                }
          }else{
            $jsondata["ret"] = "-1";
            $jsondata["msg"] = "Terjadi kesalahan pada server";
            echo json_encode($jsondata);
          }
  }else if($AKSI=="simpan_draft"){

    $kode_jenis_agenda        = $_POST['tb_kode_perihal'];
    $kode_jenis_agenda_detil  = $_POST['tb_kode_perihal_detil'];
    $keterangan               = $_POST['tb_keterangan'];
    $tgl_kejadian             = $_POST['tgl_kejadian'];
    $kode_tk                  = $_POST['kode_tk'];
    $kpj                      = $_POST['kpj'];
    $nomor_identitas          = $_POST['nomor_identitas'];
    $nama_tk                  = $_POST['nama_tk'];
    $tgl_aktivitas            = $_POST['tgl_aktivitas'];
    $kode_aktivitas           = $_POST['kode_aktivitas'];
    $nama_aktivitas           = $_POST['nama_aktivitas'];
    $narasumber               = $_POST['narasumber'];
    $profesi                  = $_POST['profesi'];
    $alamat                   = $_POST['alamat'];
    $kode_pos                 = $_POST['kode_pos'];
    $kode_kelurahan           = $_POST['kode_kelurahan'];
    $kode_kecamatan           = $_POST['kode_kecamatan'];
    $kode_kabupaten           = $_POST['kode_kabupaten'];
    $no_hp                    = $_POST['no_hp'];
    $path_url                 = $_POST['path_url'];
    $mime_type                = $_POST['mime_type'];
    $flag_mandatory           = $_POST['flag_mandatory'];
    $flag_draft               = $_POST['flag_draft'];
    $keterangan_aktivitas     = $_POST['keterangan_aktivitas'];
    $keterangan_tindaklanjut  = $_POST['keterangan_tindaklanjut'];
    $tgl_lahir                = $_POST['tgl_lahir'];
    $kd_agenda                = $_POST['kd_agenda'];

    $ls_path_url_exists = $_POST["path_url_exists"];
    $str_array = explode("/", $ls_path_url_exists);
    $namaBucket = $str_array[1];
    $namaFolder = $str_array[2]."/".$str_array[3]."/".$str_array[4];
    $namaFile =  $str_array[6];

    
    $data_doc = array(
      'namaBucket'      =>  $namaBucket,
      'namaFolder'     =>  $namaFolder,
      'file'      => $namaFile
    );

    $headers = array(
      'Content-Type: application/json',
       'X-Forwarded-For: ' . $ipfwd
       );


    $qry = "
    BEGIN 
    pn.P_PN_PN60010401.x_insert_tdl_klaim_jkm
    (
      '$kd_agenda',
      '$flag_draft',
       TO_DATE('$tgl_kejadian','DD/MM/RRRR'),
      '$nomor_identitas',
      '$nama_tk',
      TO_DATE('$tgl_lahir','DD/MM/RRRR'),
      TO_DATE('$tgl_aktivitas','DD/MM/RRRR'),
      '$kode_aktivitas',
      '$narasumber',
      '$profesi',
      '$alamat',
      '$kode_kelurahan',
      '$kode_kecamatan',
      '$kode_kabupaten',
      '$kode_pos',
      '$no_hp',
      '$keterangan_aktivitas',
      '$keterangan_tindaklanjut',
      '$kode_kantor',
      '$kode_user', 
      :p_sukses, 
      :p_mess
    );
    END;";

   
    
    $proc = $DB->parse($qry);
     oci_bind_by_name($proc, ":P_SUKSES", $p_sukses, 2);
     oci_bind_by_name($proc, ":P_MESS", $p_mess,1000);       
  
    if ($DB->execute()) {
      $ls_sukses = $p_sukses;
      $ls_mess = $p_mess; 

          if($ls_sukses=="1"){
            //update doc
                $query="";
                if(isset($flag_mandatory)){
                $str=json_decode($flag_mandatory);
                $i=0;
                for($i=0;$i<ExtendedFunction::count($str);$i++){
                  $str_array =$str[$i];
                  $query .="UPDATE PN.PN_AGENDA_KOREKSI_KLAIM_JKMDOK SET FLAG_STATUS ='Y', PETUGAS_UBAH='{$USER}', TGL_UBAH=SYSDATE WHERE KODE_DOKUMEN = '{$str_array}' AND KODE_AGENDA='{$kd_agenda}';";
                }
                
                }

                if(isset($path_url)){
                  if($path_url!=""){
                   $result_doc = api_json_call($wsIpStorage."/object/delete", $headers, $data_doc);                    
                  $query2="UPDATE PN.PN_AGENDA_KOREKSI_KLAIM_JKMDOK SET PATH_URL='{$path_url}', MIME_TYPE='{$mime_type}', FLAG_STATUS ='Y', FLAG_UPLOAD='Y', PETUGAS_UBAH='{$USER}', TGL_UBAH=SYSDATE,  TGL_UPLOAD=SYSDATE  WHERE KODE_AGENDA='{$kd_agenda}' AND KODE_DOKUMEN='D040';";
                  }
                }
                
                
                $sql_dokumen = "BEGIN
                
                        $query
                        $query2
                        COMMIT;
                      END;
                ";
                $DB->parse($sql_dokumen);
                $DB->execute();
              $jsondata["ret"] = "0";
              $jsondata["msg"] = 	$ls_mess;
              $jsondata["kd_agenda"] = 	$kd_agenda;
              echo json_encode($jsondata);
        }else{
          $jsondata["ret"] = "-1";
          $jsondata["msg"] = 	$ls_mess;
          echo json_encode($jsondata);
        }


    }else{
      $jsondata["ret"] = "-1";
      $jsondata["msg"] = "Terjadi kesalahan pada server";
      echo json_encode($jsondata);
    }

  }else if($AKSI=="tambah_aktivitas"){
      $tgl_aktivitas            = $_POST['tgl_aktivitas'];
      $kode_aktivitas           = $_POST['kode_aktivitas'];
      $nama_aktivitas           = $_POST['nama_aktivitas'];
      $narasumber               = $_POST['narasumber'];
      $profesi                  = $_POST['profesi'];
      $alamat                   = $_POST['alamat'];
      $kode_pos                 = $_POST['kode_pos'];
      $kode_kelurahan           = $_POST['kode_kelurahan'];
      $kode_kecamatan           = $_POST['kode_kecamatan'];
      $kode_kabupaten           = $_POST['kode_kabupaten'];
      $kode_propinsi            = $_POST['kode_propinsi'];
      $no_hp                    = $_POST['no_hp'];
      $email                    = $_POST['email'];
      $keterangan_aktivitas     = $_POST['keterangan_aktivitas'];
      $kd_agenda                = $_POST['kd_agenda'];
      $no_urut                  = $_POST['no_urut'];
      
      $qry=" insert into PN.PN_AGENDA_KOREKSI_KLAIM_JKMAKT
      (
        KODE_AGENDA,
        NO_URUT,
        TGL_AKTIVITAS,
        KODE_AKTIVITAS,
        NAMA_SUMBER,
        PROFESI_SUMBER,
        ALAMAT,
        KODE_KELURAHAN,
        KODE_KECAMATAN,
        KODE_KABUPATEN,
        KODE_PROVINSI,
        KODE_POS,
        HANDPHONE,
        EMAIL,
        KETERANGAN,
        TGL_REKAM,
        PETUGAS_REKAM
      )
      values
      (
        '$kd_agenda',
        '$no_urut',
        TO_DATE('$tgl_aktivitas','DD/MM/RRRR'),
        '$kode_aktivitas',
        '$narasumber',
        '$profesi',
        '$alamat',
        '$kode_kelurahan',
        '$kode_kecamatan',
        '$kode_kabupaten',
        '$kode_propinsi',
        '$kode_pos',
        '$no_hp',
        '$email',
      '$keterangan_aktivitas',
        sysdate,
        '$USER'
      )";
     
      $DB->parse($qry);
      if ($DB->execute()){
        $jsondata["ret"] = "0";
        $jsondata["msg"] = "sukses";
        $jsondata["kd_agenda"] = 	$kd_agenda;
        echo json_encode($jsondata);
      }else{
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = "Terjadi kesalahan pada server";
        $jsondata["qry"] = $qry;
        echo json_encode($jsondata);
      }
  }else if($AKSI=="delete_aktivitas"){
    
    $kode_aktivitas           = $_POST['kode_aktivitas'];
    $kd_agenda                = $_POST['kd_agenda'];
    $no_urut                  = $_POST['no_urut'];
    
    $qry="delete from PN.PN_AGENDA_KOREKSI_KLAIM_JKMAKT where kode_agenda='$kd_agenda' and kode_aktivitas='$kode_aktivitas' and no_urut='$no_urut'";
   
    $DB->parse($qry);
    if ($DB->execute()){
      $jsondata["ret"] = "0";
      $jsondata["msg"] = "Data berhasil di hapus.";
      $jsondata["kd_agenda"] = 	$kd_agenda;
      echo json_encode($jsondata);
    }else{
      $jsondata["ret"] = "-1";
      $jsondata["msg"] = "Terjadi kesalahan pada server";
      echo json_encode($jsondata);
    }
}else if($AKSI=="edit_aktivitas"){
  $tgl_aktivitas            = $_POST['tgl_aktivitas'];
  $kode_aktivitas           = $_POST['kode_aktivitas'];
  $nama_aktivitas           = $_POST['nama_aktivitas'];
  $narasumber               = $_POST['narasumber'];
  $profesi                  = $_POST['profesi'];
  $alamat                   = $_POST['alamat'];
  $kode_pos                 = $_POST['kode_pos'];
  $kode_kelurahan           = $_POST['kode_kelurahan'];
  $kode_kecamatan           = $_POST['kode_kecamatan'];
  $kode_kabupaten           = $_POST['kode_kabupaten'];
  $kode_propinsi            = $_POST['kode_propinsi'];
  $no_hp                    = $_POST['no_hp'];
  $email                    = $_POST['email'];
  $keterangan_aktivitas     = $_POST['keterangan_aktivitas'];
  $kd_agenda                = $_POST['kd_agenda'];
  $no_urut                  = $_POST['no_urut'];

  if($kode_aktivitas=="AKTV03"){

    $qry="UPDATE PN.PN_AGENDA_KOREKSI_KLAIM_JKMAKT SET 
    TGL_AKTIVITAS = TO_DATE('$tgl_aktivitas','DD/MM/RRRR'),
    KODE_AKTIVITAS = '$kode_aktivitas',
    NAMA_SUMBER =  '$narasumber',
    PROFESI_SUMBER = '$profesi',
    ALAMAT = '',
    KODE_KELURAHAN = '',
    KODE_KECAMATAN = '',
    KODE_KABUPATEN = '',
    KODE_PROVINSI =  '',
    KODE_POS = '',
    HANDPHONE = '$no_hp',
    EMAIL = '$email',
    KETERANGAN = '$keterangan_aktivitas',
    TGL_UBAH = sysdate,
    PETUGAS_UBAH =  '$USER'
    WHERE  KODE_AGENDA = '$kd_agenda' AND NO_URUT='$no_urut'";  

  }else{
  
  $qry="UPDATE PN.PN_AGENDA_KOREKSI_KLAIM_JKMAKT SET 
    TGL_AKTIVITAS = TO_DATE('$tgl_aktivitas','DD/MM/RRRR'),
    KODE_AKTIVITAS = '$kode_aktivitas',
    NAMA_SUMBER =  '$narasumber',
    PROFESI_SUMBER = '$profesi',
    ALAMAT = '$alamat',
    KODE_KELURAHAN = '$kode_kelurahan',
    KODE_KECAMATAN = '$kode_kecamatan',
    KODE_KABUPATEN = '$kode_kabupaten',
    KODE_PROVINSI =  '$kode_propinsi',
    KODE_POS = '$kode_pos',
    HANDPHONE = '$no_hp',
    EMAIL = '$email',
    KETERANGAN = '$keterangan_aktivitas',
    TGL_UBAH = sysdate,
    PETUGAS_UBAH =  '$USER'
    WHERE  KODE_AGENDA = '$kd_agenda' AND NO_URUT='$no_urut'";
  }

 
  $DB->parse($qry);
  if ($DB->execute()){
    $jsondata["ret"] = "0";
    $jsondata["msg"] = "sukses";
    $jsondata["kd_agenda"] = 	$kd_agenda;
    echo json_encode($jsondata);
  }else{
    $jsondata["ret"] = "-1";
    $jsondata["msg"] = "Terjadi kesalahan pada server";
    $jsondata["qry"] = $qry;
    echo json_encode($jsondata);
  }
}
      
       



 


?>
