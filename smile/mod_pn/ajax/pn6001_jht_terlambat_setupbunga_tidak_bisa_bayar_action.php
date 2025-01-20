<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE       = $_POST["TYPE"];
$ACTION     = $_POST["ACTION_TYPE"];
$USER       = $_SESSION["USER"];
$KD_KANTOR  = $_SESSION['kdkantorrole'];
$ses_reg_role = $_SESSION['regrole'];
$KODEAGENDA 	= $_REQUEST["KODE_AGENDA"];

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
      $sql = "BEGIN PN.P_PN_PN60010207.X_INSERT_KOREKSI_KLAIM_JHT
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
            echo '{"ret":-1,"msg":"Gagal, Gagal Eksekusi Prosedur PN.P_PN_PN60010207.X_INSERT_KOREKSI_KLAIM_JHT!"}';
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
   $ls_kode_agenda              = $_POST["kd_agenda"];
   $ls_path_url                 = $_POST["path_url"];
   $ls_mime_type                = $_POST["mime_type"];
   $ls_kode_dokumen_kurang_bayar=$_POST["kode_dokumen_kurang_bayar"];
   $ls_kode_bank                =$_POST["kode_bank"];
   $ls_kode_sebab_kurang_bayar  =$_POST["kode_sebab_kurang_bayar"];
   $ls_keterangan               =$_POST["keterangan"];
  
   
   $sql_cek_dokumen="SELECT COUNT(1) CEK_DOKUMEN, PATH_URL FROM PN.PN_KURANG_BAYAR_TDL_DOK WHERE KODE_AGENDA='$ls_kode_agenda' GROUP BY PATH_URL";
   $DB->parse($sql_cek_dokumen);
   $DB->execute();
   $row = $DB->nextrow();
   $ls_cek_dokumen = $row["CEK_DOKUMEN"];
   $ls_path_url_exist = $row["PATH_URL"];

   if(strlen($ls_path_url)>0){

       if($ls_cek_dokumen=="1"){
             
           $str_array = explode("/", $ls_path_url_exist);
           $namaBucket = $str_array[1];
           $namaFolder = $str_array[2]."/".$str_array[3];
           $namaFile =  $str_array[5];
       
           $headers = array(
             'Content-Type: application/json',
             'X-Forwarded-For: ' . $ipfwd
           );
       
             $data = array(
             'namaBucket'      =>  $namaBucket,
             'namaFolder'     =>  $namaFolder,
             'file'      => $namaFile
           );
   
           $result = api_json_call($wsIpStorage."/object/delete", $headers, $data);
       
          
           $sql_dokumen_delete="DELETE PN.PN_KURANG_BAYAR_TDL_DOK WHERE KODE_AGENDA='$ls_kode_agenda' AND PATH_URL='$ls_path_url_exist' ";
           $DB->parse($sql_dokumen_delete);
           $DB->execute();
   
         }

           $sql_doc="INSERT INTO PN.PN_KURANG_BAYAR_TDL_DOK
           (KODE_AGENDA, KODE_DOKUMEN, MIME_TYPE, PATH_URL, TGL_REKAM, PETUGAS_REKAM, FLAG_UPLOAD, TGL_UPLOAD) VALUES 
           ('$ls_kode_agenda','$ls_kode_dokumen_kurang_bayar','$ls_mime_type', '$ls_path_url', SYSDATE, '$USER', 'Y', SYSDATE)";
           $DB->parse($sql_doc);
           $DB->execute();

   }



  
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
        $sql = "BEGIN PN.P_PN_PN60010207.X_SUBMIT_TDL_KLAIM_JHT
				(
					'$ls_kode_agenda',
                    '$ls_kode_bank',
                    '$ls_kode_sebab_kurang_bayar',
                    '$ls_keterangan',
                    '$KD_KANTOR',
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
               echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
            }
        }else{
            echo '{"ret":-1,"msg":"Gagal, Gagal Eksekusi Prosedur PN.P_PN_PN60010207.X_SUBMIT_TDL_KLAIM_JHT!"}';
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
        $sql = "BEGIN PN.P_PN_PN60010207.x_approve_tdl_klaim_jht
				(
					'$ls_kode_agenda',
                    '$KD_KANTOR',
					'$USER',
                    :p_approval_selesai,
                    :p_kode_agenda_out,
                    :p_kode_klaim_out,
					:p_sukses,
					:p_mess
                ); 
                END;";
        // print_r($sql);
        // die();   
        $proc = $DB->parse($sql);       
        oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
        oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
        oci_bind_by_name($proc, ":p_approval_selesai", $p_approval_selesai,1000);
        oci_bind_by_name($proc, ":p_kode_agenda_out", $p_kode_agenda_out,1000);
        oci_bind_by_name($proc, ":p_kode_klaim_out", $p_kode_klaim_out,1000);
		
		if($DB->execute()){
            $ls_mess = $p_mess;
            $ls_sukses = $p_sukses;
            $ls_approval_selesai = $p_approval_selesai;
            $ls_kode_klaim = $p_kode_klaim_out;
            if($ls_sukses == '1'){

                if($ls_approval_selesai=="Y"){
                $sqlbucket="select to_char(sysdate, 'yyyymm') blth,
                (select kode_kantor from pn.pn_klaim where kode_klaim = '$ls_kode_klaim') kode_kantor
                from dual";

                $DB->parse($sqlbucket);
                $DB->execute();
                $row = $DB->nextrow();
                $ls_blth = $row["BLTH"];
                $ls_kode_kantor_bucket = $row["KODE_KANTOR"];


                $ls_nama_bucket_storage = "arsip";
                $ls_nama_folder_storage = "$ls_kode_kantor_bucket/$ls_blth/klaim";

                $sql_cek_kcp = "select count(1) is_kcp from sijstk.ms_kantor 
                where kode_kantor = '$KD_KANTOR' 
                and kode_tipe in ('4','5')";
                $DB->parse($sql_cek_kcp);
                $DB->execute();
                $row_cek_kcp = $DB->nextrow();
                $ls_is_kcp = $row_cek_kcp["IS_KCP"];

                $headers = array(
                    'Content-Type: application/json',
                    'X-Forwarded-For: ' . $ipfwd
                );
                if($ls_is_kcp>0){

                    $data = array(
                        "chId" => "SMILE",
                        "reqId" => $USER,
                        "idDokumen" => $ls_kode_klaim,
                        "kodeJenisDokumen" => "JD106",
                        "kodeDokumen" => "JD106-D1001",
                        "urlDokumen" => $ipReportServer."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR310002.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26P_KODE_KLAIM%3D%27".$ls_kode_klaim."%27%26P_KODE_AGENDA%3D%27".$ls_kode_agenda."%27%26P_USER%3D%27".$USER."%27",
                        "namaBucketTujuan" => $ls_nama_bucket_storage,
                        "namaFolderTujuan" => $ls_nama_folder_storage
                      );
                    
                }else{
                $data = array(
                    "chId" => "SMILE",
                    "reqId" => $USER,
                    "idDokumen" => $ls_kode_klaim,
                    "kodeJenisDokumen" => "JD106",
                    "kodeDokumen" => "JD106-D1001",
                    "urlDokumen" => $ipReportServer."/reports/rwservlet/setauth?button=Submit&username=".$nc_rpt_user_arsip."&password=".$nc_rpt_pass_arsip."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$nc_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR310001.rdf%26userid%3D%2Fdata%2Freports%2Fpn%26%26P_KODE_KLAIM%3D%27".$ls_kode_klaim."%27%26P_KODE_AGENDA%3D%27".$ls_kode_agenda."%27%26P_USER%3D%27".$USER."%27",
                    "namaBucketTujuan" => $ls_nama_bucket_storage,
                    "namaFolderTujuan" => $ls_nama_folder_storage
                  );
                }

                $result = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers, $data);
                
                if($result->ret == "0") {

                    $id_arsip=$result->idArsip;

                    $sql_sign_kbl="select a.*,
                    (select nama_jabatan from ms.ms_jabatan b where b.kode_jabatan=a.kode_jabatan) nama_jabatan,
                    (select nik from ms.sc_user b where b.kode_user='$USER') npk
                    from pn.pn_kurang_bayar_tdl_approval a where kode_agenda='$ls_kode_agenda' and kode_jabatan='47'";

                    $DB->parse($sql_sign_kbl);
                    $DB->execute();
                    $row_kbl = $DB->nextrow();
                    $ls_nama_jabatan_kbl = $row_kbl["NAMA_JABATAN"];
                    $ls_npk_kbl = $row_kbl["NPK"];
                    $ls_kode_kantor_kbl = $row_kbl["KODE_KANTOR"];

                    $sql_sign_kcb="select a.*,
                    (select nama_jabatan from ms.ms_jabatan b where b.kode_jabatan=a.kode_jabatan) nama_jabatan,
                    (select nik from ms.sc_user b where b.kode_user='$USER') npk
                    from pn.pn_kurang_bayar_tdl_approval a where kode_agenda='$ls_kode_agenda' and kode_jabatan='70'";

                    $DB->parse($sql_sign_kcb);
                    $DB->execute();
                    $row_kcb = $DB->nextrow();
                    $ls_nama_jabatan_kcb = $row_kcb["NAMA_JABATAN"];
                    $ls_npk_kcb = $row_kcb["NPK"];
                    $ls_kode_kantor_kcb = $row_kcb["KODE_KANTOR"];

                    $sql_sign_kcp="select a.*,
                    'KEPALA KCP' nama_jabatan,
                    (select nik from ms.sc_user b where b.kode_user='$USER') npk
                    from pn.pn_kurang_bayar_tdl_approval a where kode_agenda='$ls_kode_agenda' and kode_jabatan IN ('704','705')";

                    $DB->parse($sql_sign_kcp);
                    $DB->execute();
                    $row_kcp = $DB->nextrow();
                    $ls_nama_jabatan_kcp = $row_kcp["NAMA_JABATAN"];
                    $ls_npk_kcp = $row_kcp["NPK"];
                    $ls_kode_kantor_kcp = $row_kcp["KODE_KANTOR"];


                    if($ls_is_kcp > 0){

                        $dataSign = array(
                        "chId" => "SMILE", 
                        "reqId" => $USER, 
                        "idArsip" => $id_arsip, 
                        "docSigns" => array(
                            array(
                                "kodeDokumen" => "JD106-D1001", 
                                "kodeDokumenSign" => "JD106-D1001-0003", 
                                "dataUserSign" => array(
                                    "kodeKantor" => $ls_kode_kantor_kcp, 
                                    "npk" => $ls_npk_kcp, 
                                    "namaJabatan" => $ls_nama_jabatan_kcp, 
                                    "petugas" => $USER 
                                ), 
                                "keterangan" => "TTD KAKCP", 
                                "action" => "sign" 
                            )
                        ) 
                        );
                    }else{
                        $dataSign = array(
                            "chId" => "SMILE", 
                            "reqId" => $USER, 
                            "idArsip" => $id_arsip, 
                            "docSigns" => array(
                                array(
                                    "kodeDokumen" => "JD106-D1001", 
                                    "kodeDokumenSign" => "JD106-D1001-0001", 
                                    "dataUserSign" => array(
                                        "kodeKantor" => $ls_kode_kantor_kcb, 
                                        "npk" => $ls_npk_kcb, 
                                        "namaJabatan" => $ls_nama_jabatan_kcb, 
                                        "petugas" => $USER 
                                    ), 
                                    "keterangan" => "TTD KAKACAB/KAKCP", 
                                    "action" => "sign" 
                                ), 
                                array(
                                        "kodeDokumen" => "JD106-D1001", 
                                        "kodeDokumenSign" => "JD106-D1001-0002", 
                                        "dataUserSign" => array(
                                            "kodeKantor" => $ls_kode_kantor_kbl, 
                                            "npk" => $ls_npk_kbl, 
                                            "namaJabatan" => $ls_nama_jabatan_kbl, 
                                            "petugas" => $USER 
                                        ), 
                                        "keterangan" => "TTD KEPALA BIDANG PELAYANAN", 
                                        "action" => "sign" 
                                ) 
                            ) 
                            );

                    }

                        $result = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $dataSign);
                }
            }




                echo '{"ret":0,"msg":"Sukses, Prosedur Sukses...Data koreksi berhasil diapprove!"}';
            }else{
               echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
            }
        }else{
            echo '{"ret":-1,"msg":"Gagal, Gagal Eksekusi Prosedur PN.P_PN_PN60010207.x_approve_tdl_klaim_jht!"}';
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
		$sql = "BEGIN PN.P_PN_PN60010207.X_BATAL_TDL_KLAIM_JHT
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
               echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
            }
        }else{
            echo '{"ret":-1,"msg":"Gagal, Gagal Eksekusi Prosedur PN.P_PN_PN60010207.X_BATAL_TDL_KLAIM_JHT!"}';
        }
    }
}

else {
    echo '{"ret":-1,"msg":"Fungsi Belum Tersedia!"}';
}
?>