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
    $ls_tgl_awal_pra        = $_POST["tgl_awal_pra"];
    $ls_tgl_akhir_pra       = $_POST["tgl_akhir_pra"];
    $ls_tgl_awal_onsite     = $_POST["tgl_awal_onsite"];
    $ls_tgl_akhir_onsite    = $_POST["tgl_akhir_onsite"];
    $ls_tgl_awal_paska      = $_POST["tgl_awal_paska"];
    $ls_tgl_akhir_paska     = $_POST["tgl_akhir_paska"];   
    $ls_tgl_awal_pra_baru       = $_POST["tgl_awal_pra_baru"];
    $ls_tgl_akhir_pra_baru      = $_POST["tgl_akhir_pra_baru"];
    $ls_tgl_awal_onsite_baru    = $_POST["tgl_awal_onsite_baru"];
    $ls_tgl_akhir_onsite_baru   = $_POST["tgl_akhir_onsite_baru"];
    $ls_tgl_awal_paska_baru     = $_POST["tgl_awal_paska_baru"];
    $ls_tgl_akhir_paska_baru    = $_POST["tgl_akhir_paska_baru"];
    $ls_jenis_koreksi           = $_POST["jenis_koreksi"];
    
    $ls_keterangan_kor      = str_replace("'","' || '''' ||'", $_POST["keterangan_koreksi"]);
    
    $qry_koreksi_pmi = "
              UPDATE PN.PN_AGENDA_KOREKSI_KLAIM_PMI
                    SET   KODE_PERUSAHAAN       = '$ls_kode_perusahaan',
                          KODE_DIVISI           = '$ls_kode_divisi',
                          KODE_KEPESERTAAN      = '$ls_kode_kepesertaan',
                          KODE_TK               = '$ls_kode_tk',
                          NIK                   = '$ls_nik',
                          NAMA_TK               = '$ls_nama_tk',
                          KODE_KLAIM            = '$ls_kode_klaim',
                          TGL_AWAL_PRA          = TO_DATE('$ls_tgl_awal_pra','DD/MM/YYYY'),
                          TGL_AKHIR_PRA         = TO_DATE('$ls_tgl_akhir_pra','DD/MM/YYYY'),
                          TGL_AWAL_ONSITE       = TO_DATE('$ls_tgl_awal_onsite','DD/MM/YYYY'),
                          TGL_AKHIR_ONSITE      = TO_DATE('$ls_tgl_akhir_onsite','DD/MM/YYYY'),
                          TGL_AWAL_PASKA        = TO_DATE('$ls_tgl_awal_paska','DD/MM/YYYY'),
                          TGL_AKHIR_PASKA       = TO_DATE('$ls_tgl_akhir_paska','DD/MM/YYYY'),
                          TGL_AWAL_PRA_BARU         = TO_DATE('$ls_tgl_awal_pra_baru','DD/MM/YYYY'),
                          TGL_AKHIR_PRA_BARU        = TO_DATE('$ls_tgl_akhir_pra_baru','DD/MM/YYYY'),
                          TGL_AWAL_ONSITE_BARU      = TO_DATE('$ls_tgl_awal_onsite_baru','DD/MM/YYYY'),
                          TGL_AKHIR_ONSITE_BARU     = TO_DATE('$ls_tgl_akhir_onsite_baru','DD/MM/YYYY'),
                          TGL_AWAL_PASKA_BARU       = TO_DATE('$ls_tgl_awal_paska_baru','DD/MM/YYYY'),
                          TGL_AKHIR_PASKA_BARU      = TO_DATE('$ls_tgl_akhir_paska_baru','DD/MM/YYYY'),
                          JENIS_KOREKSI             = '$ls_jenis_koreksi',
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
            $qry_koreksi_pmi
            $qry_agenda
        EXCEPTION
        WHEN OTHERS THEN
        ROLLBACK;
        RAISE;
        END;";
    $DB->parse($sql);
    if($DB->execute()){
        echo '{"ret":0,"msg":"Sukses, Data berhasil disimpan!"}'; 
    } else {
        $sql = "
        BEGIN
            DELETE FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI
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
              AND EXISTS (SELECT 1 FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI
                            WHERE KODE_AGENDA = A.KODE_AGENDA)";
    // print_r($sql);
    // die();
    $recordsTotal = $DB->get_data($sql);
    if ($recordsTotal <= 0) {
        echo '{"ret":-1, "msg":"Kode agenda tidak ditemukan !"}';
        die();
    }else{
        $sql = "
		DECLARE
			v_jenis_koreksi varchar(150) := '';
			v_tgl_kejadian date;
			v_awal_perlindungan date;
			v_akhir_perlindungan date;
			v_ada number := 0;
			v_ada_purna number :=0;
			
        BEGIN
            UPDATE  PN.PN_AGENDA_KOREKSI_KLAIM_PMI
                        SET     STATUS_APPROVAL = 'Y',
                                PETUGAS_APPROVAL= '$USER',
                                TGL_APPROVAL     = SYSDATE
                        WHERE   KODE_AGENDA     = '$ls_kode_agenda';
            UPDATE  PN.PN_AGENDA_KOREKSI
                        SET     STATUS_AGENDA   = 'DITUTUP',
                                DETIL_STATUS    = 'APPROVAL',
                                TGL_SELESAI     = SYSDATE
                        WHERE   KODE_AGENDA     = '$ls_kode_agenda';     

			---- update 06052019
			SELECT JENIS_KOREKSI INTO v_jenis_koreksi FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI WHERE KODE_AGENDA = '$ls_kode_agenda';   
			
			IF v_jenis_koreksi = 'PRA' THEN
				SELECT TGL_AWAL_PRA_BARU,TGL_AKHIR_PRA_BARU INTO v_awal_perlindungan,v_akhir_perlindungan  FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI WHERE KODE_AGENDA = '$ls_kode_agenda';
				
				UPDATE PN.PN_KLAIM
					SET TGL_AWAL_PERLINDUNGAN = v_awal_perlindungan,
					TGL_AKHIR_PERLINDUNGAN = v_akhir_perlindungan,
					KODE_PERLINDUNGAN = v_jenis_koreksi
				WHERE KODE_KLAIM IN 
				 (SELECT KODE_KLAIM FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI
				  WHERE KODE_AGENDA = '$ls_kode_agenda'
				);
			ELSE
				SELECT TGL_AWAL_ONSITE_BARU,TGL_AKHIR_ONSITE_BARU INTO v_awal_perlindungan,v_akhir_perlindungan  FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI WHERE KODE_AGENDA = '$ls_kode_agenda';
				
				SELECT TGL_KEJADIAN INTO v_tgl_kejadian FROM PN.PN_KLAIM 
				WHERE KODE_KLAIM IN 
				 (SELECT KODE_KLAIM FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI
				  WHERE KODE_AGENDA = '$ls_kode_agenda'
				);
				
				SELECT COUNT(*) INTO v_ada_purna FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI
				WHERE KODE_KLAIM IN 
				 (SELECT KODE_KLAIM FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI
				  WHERE KODE_AGENDA = '$ls_kode_agenda'
				)
				AND v_tgl_kejadian BETWEEN TGL_AWAL_PASKA_BARU AND TGL_AKHIR_PASKA_BARU
				; 
				
				IF v_ada_purna > 0 THEN
					v_jenis_koreksi := 'PURNA';
				END IF;
				
				UPDATE PN.PN_KLAIM
				SET TGL_AWAL_PERLINDUNGAN = v_awal_perlindungan,
				TGL_AKHIR_PERLINDUNGAN = v_akhir_perlindungan,
				KODE_PERLINDUNGAN = v_jenis_koreksi
				WHERE KODE_KLAIM IN 
				 (SELECT KODE_KLAIM FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI
				  WHERE KODE_AGENDA = '$ls_kode_agenda'
				);
			END IF;
		COMMIT;	
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
              AND EXISTS (SELECT 1 FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI
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
            UPDATE  PN.PN_AGENDA_KOREKSI_KLAIM_PMI
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
             (SELECT KODE_KLAIM FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI
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
              AND EXISTS (SELECT 1 FROM PN.PN_AGENDA_KOREKSI_KLAIM_PMI
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
            UPDATE  PN.PN_AGENDA_KOREKSI_KLAIM_PMI
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

else {
    echo '{"ret":-1,"msg":"Fungsi Belum Tersedia!"}';
}
?>