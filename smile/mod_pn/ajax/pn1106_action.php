<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE             = $_POST["TYPE"] . $_GET['TYPE'];
$USER             = $_SESSION["USER"];
$KODE_KANTOR      = $_SESSION['KDKANTOR'];
$gs_kantor_aktif  = $_SESSION['gs_kantor_aktif'];
$schema           = "pn";
$action           = isset($_POST['formregact'])?$_POST['formregact']:'';

/*****get parameter***********/
if($action=='New' || $action=='Edit' || $action=='Delete')
{
  $p_kode                       = strtoupper($_POST["kode"]);
  $p_kode_bhp                   = strtoupper($_POST["kode_bhp"]);
  $p_nama_bhp                   = strtoupper($_POST["nama_bhp"]);
  $p_alamat_bhp                 = strtoupper($_POST["alamat_bhp"]);
  $p_nama_pimpinan              = strtoupper($_POST["nama_pimpinan"]);
  $p_nama_penerima_bhp          = strtoupper($_POST["nama_penerima_bhp"]);
  $p_bank_penerima_bhp          = strtoupper($_POST["bank_penerima_bhp"]);
  $p_no_rekening_penerima_bhp   = strtoupper($_POST["no_rekening_penerima_bhp"]);
  $p_nama_rekening_penerima_bhp = strtoupper($_POST["nama_rekening_penerima_bhp"]);
  $p_telepon_area               = strtoupper($_POST["telepon_area"]);
  $p_telepon                    = strtoupper($_POST["telepon"]);
  $p_fax_area                   = strtoupper($_POST["fax_area"]);
  $p_fax                        = strtoupper($_POST["fax"]);
  $p_email                      = strtoupper($_POST["email"]);
  $p_nama_kontak                = strtoupper($_POST["nama_kontak"]);
  $p_telepon_area_kontak        = strtoupper($_POST["telepon_area_kontak"]);
  $p_telepon_kontak             = strtoupper($_POST["telepon_kontak"]);
  $p_handphone_kontak           = strtoupper($_POST["handphone_kontak"]);
  $p_tgl_rekam                  = strtoupper($_POST["tgl_rekam"]);
  $p_petugas_rekam              = strtoupper($_POST["petugas_rekam"]);
  $p_tgl_ubah                   = strtoupper($_POST["tgl_ubah"]);
  $p_petugas_ubah               = strtoupper($_POST["petugas_ubah"]);
}
if($action=='New')
{  
  $qry = "
  DECLARE
    V_KODE_BHP VARCHAR2(20);
  BEGIN
    SELECT  PN.P_PN_GENID.F_GEN_KODE_BHP 
    INTO    V_KODE_BHP
    FROM    DUAL;

    INSERT INTO PN.PN_KODE_BHP(
      KODE_BHP,
      NAMA_BHP,
      ALAMAT_BHP,
      NAMA_PIMPINAN,
      NAMA_PENERIMA_BHP,
      BANK_PENERIMA_BHP,
      NO_REKENING_PENERIMA_BHP,
      NAMA_REKENING_PENERIMA_BHP,
      TELEPON_AREA,
      TELEPON,
      FAX_AREA,
      FAX,
      EMAIL,
      NAMA_KONTAK,
      TELEPON_AREA_KONTAK,
      TELEPON_KONTAK,
      HANDPHONE_KONTAK,
      TGL_REKAM,
      PETUGAS_REKAM,
      KODE_KANTOR_REKAM
    ) VALUES (
      V_KODE_BHP,
      :P_NAMA_BHP,
      :P_ALAMAT_BHP,
      :P_NAMA_PIMPINAN,
      :P_NAMA_PENERIMA_BHP,
      :P_BANK_PENERIMA_BHP,
      :P_NO_REKENING_PENERIMA_BHP,
      :P_NAMA_REKENING_PENERIMA_BHP,
      :P_TELEPON_AREA,
      :P_TELEPON,
      :P_FAX_AREA,
      :P_FAX,
      :P_EMAIL,
      :P_NAMA_KONTAK,
      :P_TELEPON_AREA_KONTAK,
      :P_TELEPON_KONTAK,
      :P_HANDPHONE_KONTAK,
      SYSDATE,
      :P_PETUGAS_REKAM,
      :P_KODE_KANTOR_REKAM
    );

  EXCEPTION WHEN OTHERS THEN
    ROLLBACK;
    RAISE;
  END;";
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_nama_bhp", $p_nama_bhp, 100);
  oci_bind_by_name($proc, ":p_alamat_bhp", $p_alamat_bhp, 300);
  oci_bind_by_name($proc, ":p_nama_pimpinan", $p_nama_pimpinan, 100);
  oci_bind_by_name($proc, ":p_nama_penerima_bhp", $p_nama_penerima_bhp, 100);
  oci_bind_by_name($proc, ":p_bank_penerima_bhp", $p_bank_penerima_bhp, 100);
  oci_bind_by_name($proc, ":p_no_rekening_penerima_bhp", $p_no_rekening_penerima_bhp, 30);
  oci_bind_by_name($proc, ":p_nama_rekening_penerima_bhp", $p_nama_rekening_penerima_bhp, 100);
  oci_bind_by_name($proc, ":p_telepon_area", $p_telepon_area, 5);
  oci_bind_by_name($proc, ":p_telepon", $p_telepon, 20);
  oci_bind_by_name($proc, ":p_fax_area", $p_fax_area, 5);
  oci_bind_by_name($proc, ":p_fax", $p_fax, 20);
  oci_bind_by_name($proc, ":p_email", $p_email, 200);
  oci_bind_by_name($proc, ":p_nama_kontak", $p_nama_kontak, 50);
  oci_bind_by_name($proc, ":p_telepon_area_kontak", $p_telepon_area_kontak, 5);
  oci_bind_by_name($proc, ":p_telepon_kontak", $p_telepon_kontak, 20);
  oci_bind_by_name($proc, ":p_handphone_kontak", $p_handphone_kontak, 20);
  oci_bind_by_name($proc, ":p_petugas_rekam", $USER, 30);
  oci_bind_by_name($proc, ":p_kode_kantor_rekam", $gs_kantor_aktif, 30);
  if ($DB->execute()) {
    echo '{"ret":0,"msg":"Sukses, Data berhasil disimpan, session dilanjutkan.."}';
  } else {
    echo '{"ret":-1, "msg":"Gagal Menyimpan data!"}';
  }
}else if($action=='Edit')
{  
  $qry = "
  DECLARE
    V_NO_URUT NUMBER;
  BEGIN
    -- INSERT HIST
    SELECT  NVL(MAX(NO_URUT), 0) + 1
    INTO    V_NO_URUT
    FROM    PN.PN_KODE_BHP_HIST
    WHERE   KODE_BHP = :P_KODE_BHP;

    INSERT
    INTO    PN.PN_KODE_BHP_HIST(
      KODE_BHP,
      NAMA_BHP,
      ALAMAT_BHP,
      NAMA_PIMPINAN,
      NAMA_PENERIMA_BHP,
      BANK_PENERIMA_BHP,
      NO_REKENING_PENERIMA_BHP,
      NAMA_REKENING_PENERIMA_BHP,
      TELEPON_AREA,
      TELEPON,
      FAX_AREA,
      FAX,
      EMAIL,
      NAMA_KONTAK,
      TELEPON_AREA_KONTAK,
      TELEPON_KONTAK,
      HANDPHONE_KONTAK,
      TGL_REKAM,
      PETUGAS_REKAM,
      TGL_UBAH,
      PETUGAS_UBAH,
      NO_URUT,
      TGL_REKAM_HIST,
      PETUGAS_REKAM_HIST,
      KODE_KANTOR_HIST,
      KODE_KANTOR_REKAM
    )
    SELECT  KODE_BHP,
            NAMA_BHP,
            ALAMAT_BHP,
            NAMA_PIMPINAN,
            NAMA_PENERIMA_BHP,
            BANK_PENERIMA_BHP,
            NO_REKENING_PENERIMA_BHP,
            NAMA_REKENING_PENERIMA_BHP,
            TELEPON_AREA,
            TELEPON,
            FAX_AREA,
            FAX,
            EMAIL,
            NAMA_KONTAK,
            TELEPON_AREA_KONTAK,
            TELEPON_KONTAK,
            HANDPHONE_KONTAK,
            TGL_REKAM,
            PETUGAS_REKAM,
            TGL_UBAH,
            PETUGAS_UBAH,
            V_NO_URUT,
            SYSDATE TGL_REKAM_HIST,
            :P_PETUGAS_UBAH PETUGAS_REKAM_HIST,
            :P_KODE_KANTOR_UBAH KODE_KANTOR_HIST,
            KODE_KANTOR_REKAM
      FROM  PN.PN_KODE_BHP
      WHERE KODE_BHP = :P_KODE_BHP;
    
    UPDATE  PN.PN_KODE_BHP
    SET
            NAMA_BHP                    = :P_NAMA_BHP                  ,
            ALAMAT_BHP                  = :P_ALAMAT_BHP                ,
            NAMA_PIMPINAN               = :P_NAMA_PIMPINAN             ,
            NAMA_PENERIMA_BHP           = :P_NAMA_PENERIMA_BHP         ,
            BANK_PENERIMA_BHP           = :P_BANK_PENERIMA_BHP         ,
            NO_REKENING_PENERIMA_BHP    = :P_NO_REKENING_PENERIMA_BHP  ,
            NAMA_REKENING_PENERIMA_BHP  = :P_NAMA_REKENING_PENERIMA_BHP,
            TELEPON_AREA                = :P_TELEPON_AREA              ,
            TELEPON                     = :P_TELEPON                   ,
            FAX_AREA                    = :P_FAX_AREA                  ,
            FAX                         = :P_FAX                       ,
            EMAIL                       = :P_EMAIL                     ,
            NAMA_KONTAK                 = :P_NAMA_KONTAK               ,
            TELEPON_AREA_KONTAK         = :P_TELEPON_AREA_KONTAK       ,
            TELEPON_KONTAK              = :P_TELEPON_KONTAK            ,
            HANDPHONE_KONTAK            = :P_HANDPHONE_KONTAK          ,
            TGL_UBAH                    = SYSDATE                      ,
            PETUGAS_UBAH                = :P_PETUGAS_UBAH              
    WHERE   KODE_BHP = :P_KODE_BHP;
  EXCEPTION WHEN OTHERS THEN
    ROLLBACK;
    RAISE;
  END;";
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_kode_bhp", $p_kode_bhp, 10);
  oci_bind_by_name($proc, ":p_nama_bhp", $p_nama_bhp, 100);
  oci_bind_by_name($proc, ":p_alamat_bhp", $p_alamat_bhp, 300);
  oci_bind_by_name($proc, ":p_nama_pimpinan", $p_nama_pimpinan, 100);
  oci_bind_by_name($proc, ":p_nama_penerima_bhp", $p_nama_penerima_bhp, 100);
  oci_bind_by_name($proc, ":p_bank_penerima_bhp", $p_bank_penerima_bhp, 100);
  oci_bind_by_name($proc, ":p_no_rekening_penerima_bhp", $p_no_rekening_penerima_bhp, 30);
  oci_bind_by_name($proc, ":p_nama_rekening_penerima_bhp", $p_nama_rekening_penerima_bhp, 100);
  oci_bind_by_name($proc, ":p_telepon_area", $p_telepon_area, 5);
  oci_bind_by_name($proc, ":p_telepon", $p_telepon, 20);
  oci_bind_by_name($proc, ":p_fax_area", $p_fax_area, 5);
  oci_bind_by_name($proc, ":p_fax", $p_fax, 20);
  oci_bind_by_name($proc, ":p_email", $p_email, 200);
  oci_bind_by_name($proc, ":p_nama_kontak", $p_nama_kontak, 50);
  oci_bind_by_name($proc, ":p_telepon_area_kontak", $p_telepon_area_kontak, 5);
  oci_bind_by_name($proc, ":p_telepon_kontak", $p_telepon_kontak, 20);
  oci_bind_by_name($proc, ":p_handphone_kontak", $p_handphone_kontak, 20);
  oci_bind_by_name($proc, ":p_petugas_ubah", $USER, 30);
  oci_bind_by_name($proc, ":p_kode_kantor_ubah", $gs_kantor_aktif, 30);
  if ($DB->execute()) {
    echo '{"ret":0,"msg":"Sukses, Data berhasil diubah, session dilanjutkan.."}';
  } else {
    echo '{"ret":-1, "msg":"Gagal Menyimpan data!"}';
  }
}else if($action=='Delete')
{  
  $qry = "
  DECLARE
    V_NO_URUT NUMBER;
  BEGIN
    -- INSERT HIST
    SELECT  NVL(MAX(NO_URUT), 0) + 1
    INTO    V_NO_URUT
    FROM    PN.PN_KODE_BHP_HIST
    WHERE   KODE_BHP = :P_KODE_BHP;

    INSERT
    INTO    PN.PN_KODE_BHP_HIST(
      KODE_BHP,
      NAMA_BHP,
      ALAMAT_BHP,
      NAMA_PIMPINAN,
      NAMA_PENERIMA_BHP,
      BANK_PENERIMA_BHP,
      NO_REKENING_PENERIMA_BHP,
      NAMA_REKENING_PENERIMA_BHP,
      TELEPON_AREA,
      TELEPON,
      FAX_AREA,
      FAX,
      EMAIL,
      NAMA_KONTAK,
      TELEPON_AREA_KONTAK,
      TELEPON_KONTAK,
      HANDPHONE_KONTAK,
      TGL_REKAM,
      PETUGAS_REKAM,
      TGL_UBAH,
      PETUGAS_UBAH,
      NO_URUT,
      TGL_REKAM_HIST,
      PETUGAS_REKAM_HIST,
      KODE_KANTOR_HIST,
      KODE_KANTOR_REKAM
    )
    SELECT  KODE_BHP,
            NAMA_BHP,
            ALAMAT_BHP,
            NAMA_PIMPINAN,
            NAMA_PENERIMA_BHP,
            BANK_PENERIMA_BHP,
            NO_REKENING_PENERIMA_BHP,
            NAMA_REKENING_PENERIMA_BHP,
            TELEPON_AREA,
            TELEPON,
            FAX_AREA,
            FAX,
            EMAIL,
            NAMA_KONTAK,
            TELEPON_AREA_KONTAK,
            TELEPON_KONTAK,
            HANDPHONE_KONTAK,
            TGL_REKAM,
            PETUGAS_REKAM,
            TGL_UBAH,
            PETUGAS_UBAH,
            V_NO_URUT,
            SYSDATE TGL_REKAM_HIST,
            :P_PETUGAS_HAPUS PETUGAS_REKAM_HIST,
            :P_KODE_KANTOR_HAPUS KODE_KANTOR_REKAM_HIST,
            KODE_KANTOR_REKAM
      FROM  PN.PN_KODE_BHP
      WHERE KODE_BHP = :P_KODE_BHP;

    DELETE  PN.PN_KODE_BHP 
    WHERE   KODE_BHP = :P_KODE_BHP;

  EXCEPTION WHEN OTHERS THEN
    ROLLBACK;
    RAISE;
  END;";
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_kode_bhp", $p_kode, 10);
  oci_bind_by_name($proc, ":p_petugas_hapus", $USER, 30);
  oci_bind_by_name($proc, ":p_kode_kantor_hapus", $gs_kantor_aktif, 30);
  if ($DB->execute()) {
    echo '{"ret":0,"msg":"Sukses, Data berhasil dihapus, session dilanjutkan.."}';
  } else {
    echo '{"ret":-1, "msg":"Gagal Menghapus data!"}';
  }
}else if($action=='AddKantor')
{  
  $p_kode_bhp      = strtoupper($_POST["kode_bhp"]);
  $p_kode_kantor   = strtoupper($_POST["kode_kantor"]);
  $p_nama_kantor   = strtoupper($_POST["nama_kantor"]);
  $p_petugas_rekam = $USER;

  $qry = "
  DECLARE
    V_NAMA_BHP VARCHAR2(100);
    V_SUKSES VARCHAR2(2);
    V_MESS VARCHAR2(100);
    V_COUNT INTEGER;
  BEGIN
    SELECT  COUNT(1) INTO V_COUNT
    FROM    PN.PN_KODE_BHP_KANTOR
    WHERE   KODE_KANTOR = :P_KODE_KANTOR;

    IF V_COUNT = 0 THEN
      INSERT INTO PN.PN_KODE_BHP_KANTOR(
        KODE_BHP,
        KODE_KANTOR,
        TGL_REKAM,
        PETUGAS_REKAM)
      VALUES(
        :P_KODE_BHP,
        :P_KODE_KANTOR,
        SYSDATE,
        :P_PETUGAS_REKAM);
      V_SUKSES := '1';
      V_MESS := 'Sukses';
    ELSE 
      SELECT  NAMA_BHP INTO V_NAMA_BHP
      FROM    PN.PN_KODE_BHP
      WHERE   KODE_BHP IN (
        SELECT  KODE_BHP
        FROM    PN.PN_KODE_BHP_KANTOR
        WHERE   KODE_KANTOR = :P_KODE_KANTOR)
      AND ROWNUM = 1;

      V_SUKSES := '-1';
      V_MESS := 'Kode kantor ' || :P_KODE_KANTOR || ' sudah aktif di BHP ' || V_NAMA_BHP;
    END IF;
    SELECT  V_SUKSES INTO :P_SUKSES FROM DUAL;
    SELECT  V_MESS INTO :P_MESS FROM DUAL;
  END;";
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_kode_bhp", $p_kode_bhp, 30);
  oci_bind_by_name($proc, ":p_kode_kantor", $p_kode_kantor, 5);
  oci_bind_by_name($proc, ":p_sukses", $p_sukses, 2);
  oci_bind_by_name($proc, ":p_mess", $p_mess, 100);
  oci_bind_by_name($proc, ":p_petugas_rekam", $p_petugas_rekam, 30);
  if ($DB->execute()) {
    $ls_nama_bhp = $p_nama_bhp;
    $ls_sukses = $p_sukses;
    $ls_mess = $p_mess;
    if ($ls_sukses == '1') {
      $jdata["ret"] = "0";
      $jdata["msg"] = "Sukses, Data berhasil disimpan, session dilanjutkan..";
      $jdata["kodeKantor"] = $p_kode_kantor;
      $jdata["namaKantor"] = $p_nama_kantor;
    } else {
      $jdata["ret"] = "-1";
      $jdata["msg"] = $ls_mess;
      $jdata["kodeKantor"] = $p_kode_kantor;
      $jdata["namaKantor"] = $p_nama_kantor;
    }
    echo json_encode($jdata);
  } else {
    echo '{"ret":-1, "msg":"Gagal Menyimpan data!"}';
  }
}else if($action=='DeleteKantor')
{  
  $p_kode_bhp      = strtoupper($_POST["kode_bhp"]);
  $p_kode_kantor   = strtoupper($_POST["kode_kantor"]);
  
  $qry = "
  DECLARE
    V_NO_URUT NUMBER;
  BEGIN
    -- INSERT HIST
    SELECT  NVL(MAX(NO_URUT), 0) + 1
    INTO    V_NO_URUT
    FROM    PN.PN_KODE_BHP_KANTOR_HIST
    WHERE   KODE_BHP = :P_KODE_BHP
            AND KODE_KANTOR = :P_KODE_KANTOR;

    INSERT 
    INTO    PN.PN_KODE_BHP_KANTOR_HIST(
      KODE_BHP,
      KODE_KANTOR,
      STATUS_NONAKTIF,
      TGL_NONAKTIF,
      PETUGAS_NONAKTIF,
      TGL_REKAM,
      PETUGAS_REKAM,
      TGL_UBAH,
      PETUGAS_UBAH,
      NO_URUT,
      TGL_REKAM_HIST,
      PETUGAS_REKAM_HIST
    )
    SELECT  
            KODE_BHP,
            KODE_KANTOR,
            STATUS_NONAKTIF,
            TGL_NONAKTIF,
            PETUGAS_NONAKTIF,
            TGL_REKAM,
            PETUGAS_REKAM,
            TGL_UBAH,
            PETUGAS_UBAH,
            V_NO_URUT,
            SYSDATE TGL_REKAM_HIST,
            :P_PETUGAS_REKAM PETUGAS_REKAM_HIST
    FROM    PN.PN_KODE_BHP_KANTOR
    WHERE   KODE_BHP = :P_KODE_BHP
            AND KODE_KANTOR = :P_KODE_KANTOR;

    DELETE
    FROM    PN.PN_KODE_BHP_KANTOR
    WHERE   KODE_BHP = :P_KODE_BHP
            AND KODE_KANTOR = :P_KODE_KANTOR;

  EXCEPTION WHEN OTHERS THEN
    ROLLBACK;
    RAISE;
  END;";
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_kode_bhp", $p_kode_bhp, 30);
  oci_bind_by_name($proc, ":p_kode_kantor", $p_kode_kantor, 5);
  oci_bind_by_name($proc, ":p_petugas_rekam", $USER, 30);
  if ($DB->execute()) {
    $jdata["ret"] = "0";
    $jdata["msg"] = "Sukses, Data berhasil dihapus, session dilanjutkan..";
    echo json_encode($jdata);
  } else {
    echo '{"ret":-1, "msg":"Gagal Menghapus data!"}';
  }
}
else{
  echo 'Tidak action dilakukan!';
}
?>