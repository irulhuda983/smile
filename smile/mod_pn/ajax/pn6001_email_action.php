<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
//print_r($_POST);
$TYPE       = $_POST["TYPE"];
$USER       = $_SESSION["USER"];
$KD_KANTOR  = $_SESSION['kdkantorrole'];


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

            $ls_kode_faskes         = $_POST["kode_faskes"];
            $ls_email               = strtolower($_POST["nama_email"]) ;     
            $ls_no_hp               = $_POST["no_hp"];
            $ls_aktif               = $_POST["cb_aktif"];
            if ($ls_aktif== "on" || $ls_aktif== "ON" || $ls_aktif== "1"){
                $ls_aktif = "Y";
            }else{
                $ls_aktif = "T";   
            }
           

   $qry_history = "
              INSERT INTO TC.TC_REG_FASKES_HIST
                  (   KODE_AGENDA,
                      KODE_FASKES,
                      EMAIL_FASKES,
                      PASSWD,
                      STATUS_AKTIF,
                      HANDPHONE,
                      TGL_REKAM,
                      PETUGAS_REKAM,
                      TGL_UBAH,
                      PETUGAS_UBAH
                  )
              (SELECT '$ls_kode_agenda',
                      KODE_FASKES,
                      EMAIL_FASKES,
                      PASSWD,
                      STATUS_AKTIF,
                      HANDPHONE,
                      TGL_REKAM,
                      PETUGAS_REKAM,
                      TGL_UBAH,
                      PETUGAS_UBAH
                FROM TC.TC_REG_FASKES
                WHERE KODE_FASKES = '$ls_kode_faskes'   
                  );
      ";
    

    $qry_faskes= "UPDATE  TC.TC_REG_FASKES
                  SET     EMAIL_FASKES      = '$ls_email',
                          HANDPHONE         = '$ls_no_hp',
                          STATUS_AKTIF      = '$ls_aktif',
                          TGL_UBAH          = SYSDATE,
                          PETUGAS_UBAH      = '$USER'
                  WHERE   KODE_FASKES       = '$ls_kode_faskes';";

  $qry_agenda = "UPDATE  PN.PN_AGENDA_KOREKSI
                  SET     REFERENSI       = '$ls_kode_faskes',
                          STATUS_AGENDA   = 'DITUTUP',
                          DETIL_STATUS    = 'BERHASIL',
                          TGL_SELESAI     = SYSDATE
                  WHERE   KODE_AGENDA     = '$ls_kode_agenda';";

   $sql = "
        BEGIN
            $qry_history
            $qry_faskes
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
      echo '{"ret":0,"msg":"Sukses, Data berhasil disimpan!"}';   
  } 
  else {

     $sql = "DELETE FROM PN.PN_AGENDA_KOREKSI
                WHERE KODE_AGENDA = '$ls_kode_agenda'";
     $DB->parse($sql);
     $DB->execute();

     echo '{"ret":-1,"msg":"Proses gagal, data gagal disimpan!"}';
  }
}

else {
    echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
}
?>