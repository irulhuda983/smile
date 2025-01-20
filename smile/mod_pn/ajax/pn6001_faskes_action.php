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
            $ls_kode_tipe           = $_POST["kode_tipe"] ;     
            $ls_nama_faskes         = $_POST["nama_faskes"];
            $ls_alamat_ntr              = $_POST["alamat"];
            $ls_alamat = str_replace(array("\r\n", "\r", "\n"), " ", $ls_alamat_ntr);
            $ls_rt                  = $_POST["rt"] ;       
            $ls_rw                  = $_POST["rw"];
            $ls_kode_kelurahan      = $_POST["kode_kelurahan"];
            $ls_kode_kecamatan      = $_POST["kode_kecamatan"];
            $ls_kode_kabupaten      = $_POST["kode_kabupaten"];
            $ls_kode_pos            = $_POST["kode_pos"];
            $ls_nama_pic            = $_POST["nama_pic"] ;       
            $ls_handphone_pic       = $_POST["handphone_pic"];
            $ls_kode_jenis          = $_POST["kode_jenis"] ;       
            $ls_kode_jenis_detil    = $_POST["kode_jenis_detil"];
            $ls_no_ijin_praktek     = $_POST["no_ijin_praktek"];
            $ls_npwp                = $_POST["npwp"];       
            $ls_nama_pemilik        = $_POST["nama_pemilik"];
            $ls_alamat_pemilik_ntr      = $_POST["alamat_pemilik"];
            $ls_alamat_pemilik = str_replace(array("\r\n", "\r", "\n"), " ", $ls_alamat_pemilik_ntr);
            $ls_rt_pemilik          = $_POST["rt_pemilik"];
            $ls_rw_pemilik          = $_POST["rw_pemilik"];
            $ls_kode_kelurahan_pemilik = $_POST["kode_kelurahan_pemilik"] ;       
            $ls_kode_kecamatan_pemilik = $_POST["kode_kecamatan_pemilik"];
            $ls_kode_kabupaten_pemilik = $_POST["kode_kabupaten_pemilik"];
            $ls_kode_pos_pemilik       = $_POST["kode_pos_pemilik"];
            $ls_telepon_area_pemilik   = $_POST["telp_area_pemilik"];
            $ls_telepon_pemilik        = $_POST["telp_pemilik"] ;       
            $ls_telepon_ext_pemilik    = $_POST["telepon_ext_pemilik"];
            $ls_fax_area_pemilik       = $_POST["fax_area_pemilik"];
            $ls_fax_pemilik            = $_POST["fax_pemilik"];
            $ls_email_pemilik          = $_POST["email_pemilik"];
            $ls_kode_kepemilikan       = $_POST["kode_kepemilikan"];
            $ls_kode_metode_byr        = $_POST["paymethod"] ;       
            $ls_kode_bank_byr          = $_POST["bankcode"];
            $ls_nama_rekening_pembayaran = $_POST["namarek"];
            $ls_no_rekening_pembayaran   = $_POST["norek"];
            $ls_telepon_area_pic       = $_POST["telp_area_pic"] ;       
            $ls_telepon_pic            = $_POST["telp_pic"];
            $ls_telepon_ext_pic        = $_POST["telp_ext_pic"];
            $ls_no_faskes              = $_POST["no_faskes"];
            $ls_kelas_perawatan        = $_POST["kelas_perawatan"];
            $ls_bagian_pic             = $_POST["bagian_pic"] ;       
            $ls_tgl_submit             = $_POST["createddate"];
            $ls_petugas_submit         = $_POST["createdby"];
            $ls_tgl_rekam              = $_POST["rekamdate"];
            $ls_petugas_rekam          = $_POST["rekamby"];
            $ls_latitude               = $_POST["latitude"];
            $ls_longitude              = $_POST["longitude"];
            $ls_kode_pic_cabang             = $_POST["kode_pic_cabang"];
            $ls_handphone_pic_kacab       = $_POST["handphone_pic_kacab"];
  
            if ($ls_kode_tipe == 'B' ||  $ls_kode_jenis != 'J02'){
                $ls_kelas_perawatan  = "";
            }  
           

   $qry_faskes_history = "
              INSERT INTO TC.TC_FASKES_HIST
                  (   KODE_AGENDA,
                      KODE_FASKES,
                      KODE_TIPE,
                      KODE_KANTOR,
                      KODE_PEMBINA,
                      NAMA_FASKES,
                      ALAMAT,
                      RT,
                      RW,
                      KODE_KELURAHAN,
                      KODE_KECAMATAN,
                      KODE_KABUPATEN,
                      KODE_POS,
                      NAMA_PIC,
                      HANDPHONE_PIC,
                      TGL_NONAKTIF,
                      TGL_AKTIF,
                      KODE_STATUS,
                      KODE_JENIS,
                      KODE_JENIS_DETIL,
                      NO_IJIN_PRAKTEK,
                      NPWP,
                      MAX_TERTANGGUNG,
                      FLAG_UMUM,
                      FLAG_GIGI,
                      FLAG_SALIN,
                      FLAG_REG_WEBSITE,
                      KETERANGAN,
                      NAMA_PEMILIK,
                      ALAMAT_PEMILIK,
                      RT_PEMILIK,
                      RW_PEMILIK,
                      KODE_KELURAHAN_PEMILIK,
                      KODE_KECAMATAN_PEMILIK,
                      KODE_KABUPATEN_PEMILIK,
                      KODE_POS_PEMILIK,
                      TELEPON_AREA_PEMILIK,
                      TELEPON_PEMILIK,
                      TELEPON_EXT_PEMILIK,
                      FAX_AREA_PEMILIK,
                      FAX_PEMILIK,
                      EMAIL_PEMILIK,
                      KODE_KEPEMILIKAN,
                      KODE_METODE_PEMBAYARAN,
                      KODE_BANK_PEMBAYARAN,
                      NAMA_REKENING_PEMBAYARAN,
                      NO_REKENING_PEMBAYARAN,
                      TELEPON_AREA_PIC,
                      TELEPON_PIC,
                      TELEPON_EXT_PIC,
                      NO_FASKES,
                      KELAS_PERAWATAN,
                      BAGIAN_PIC,
                      TGL_REQUEST,
                      PETUGAS_REQUEST,
                      TGL_APPROVE_REQUEST,
                      PETUGAS_APPROVE_REQUEST,
                      STATUS_REQUEST,
                      STATUS_APPROVE_REQUEST,
                      TGL_APPROVE_REQUEST1,
                      PETUGAS_APPROVE_REQUEST1,
                      STATUS_APPROVE_REQUEST1,
                      TGL_SUBMIT,
                      PETUGAS_SUBMIT,
                      TGL_REKAM,
                      PETUGAS_REKAM,
                      TGL_UBAH,
                      PETUGAS_UBAH,
                      LATITUDE,
                      LONGITUDE,
                      KODE_PIC_CABANG,
                      KONTAK_PIC_CABANG
                  )
              (SELECT '$ls_kode_agenda',
                      KODE_FASKES,
                      KODE_TIPE,
                      KODE_KANTOR,
                      KODE_PEMBINA,
                      NAMA_FASKES,
                      ALAMAT,
                      RT,
                      RW,
                      KODE_KELURAHAN,
                      KODE_KECAMATAN,
                      KODE_KABUPATEN,
                      KODE_POS,
                      NAMA_PIC,
                      HANDPHONE_PIC,
                      TGL_NONAKTIF,
                      TGL_AKTIF,
                      KODE_STATUS,
                      KODE_JENIS,
                      KODE_JENIS_DETIL,
                      NO_IJIN_PRAKTEK,
                      NPWP,
                      MAX_TERTANGGUNG,
                      FLAG_UMUM,
                      FLAG_GIGI,
                      FLAG_SALIN,
                      FLAG_REG_WEBSITE,
                      KETERANGAN,
                      NAMA_PEMILIK,
                      ALAMAT_PEMILIK,
                      RT_PEMILIK,
                      RW_PEMILIK,
                      KODE_KELURAHAN_PEMILIK,
                      KODE_KECAMATAN_PEMILIK,
                      KODE_KABUPATEN_PEMILIK,
                      KODE_POS_PEMILIK,
                      TELEPON_AREA_PEMILIK,
                      TELEPON_PEMILIK,
                      TELEPON_EXT_PEMILIK,
                      FAX_AREA_PEMILIK,
                      FAX_PEMILIK,
                      EMAIL_PEMILIK,
                      KODE_KEPEMILIKAN,
                      KODE_METODE_PEMBAYARAN,
                      KODE_BANK_PEMBAYARAN,
                      NAMA_REKENING_PEMBAYARAN,
                      NO_REKENING_PEMBAYARAN,
                      TELEPON_AREA_PIC,
                      TELEPON_PIC,
                      TELEPON_EXT_PIC,
                      NO_FASKES,
                      KELAS_PERAWATAN,
                      BAGIAN_PIC,
                      TGL_REQUEST,
                      PETUGAS_REQUEST,
                      TGL_APPROVE_REQUEST,
                      PETUGAS_APPROVE_REQUEST,
                      STATUS_REQUEST,
                      STATUS_APPROVE_REQUEST,
                      NULL,--TGL_APPROVE_REQUEST1,
                      NULL,--PETUGAS_APPROVE_REQUEST1,
                      NULL,--STATUS_APPROVE_REQUEST1,
                      TGL_SUBMIT,
                      PETUGAS_SUBMIT,
                      TGL_REKAM,
                      PETUGAS_REKAM,
                      TGL_UBAH,
                      PETUGAS_UBAH,
                      LATITUDE,
                      LONGITUDE,
                      KODE_PIC_CABANG,
                      KONTAK_PIC_CABANG
                FROM TC.TC_FASKES
                WHERE KODE_FASKES = '$ls_kode_faskes'   
                  );
      ";
    

    $qry_faskes= "UPDATE  TC.TC_FASKES
                  SET     KODE_TIPE         = '$ls_kode_tipe',
                          NAMA_FASKES       = '$ls_nama_faskes',
                          ALAMAT            = '$ls_alamat',
                          RT                = '$ls_rt',
                          RW                = '$ls_rw',
                          KODE_KELURAHAN    = '$ls_kode_kelurahan',
                          KODE_KECAMATAN    = '$ls_kode_kecamatan',
                          KODE_KABUPATEN    = '$ls_kode_kabupaten',
                          KODE_POS          = '$ls_kode_pos',
                          NAMA_PIC          = '$ls_nama_pic',
                          HANDPHONE_PIC     = '$ls_handphone_pic',
                          KODE_JENIS        = '$ls_kode_jenis',
                          KODE_JENIS_DETIL  = '$ls_kode_jenis_detil',
                          NO_IJIN_PRAKTEK   = '$ls_no_ijin_praktek',
                          NPWP              = '$ls_npwp',
                          NAMA_PEMILIK      = '$ls_nama_pemilik',
                          ALAMAT_PEMILIK    = '$ls_alamat_pemilik',
                          RT_PEMILIK        = '$ls_rt_pemilik',
                          RW_PEMILIK        = '$ls_rw_pemilik',
                          KODE_KELURAHAN_PEMILIK = '$ls_kode_kelurahan_pemilik',
                          KODE_KECAMATAN_PEMILIK = '$ls_kode_kecamatan_pemilik',
                          KODE_KABUPATEN_PEMILIK = '$ls_kode_kabupaten_pemilik',
                          KODE_POS_PEMILIK       = '$ls_kode_pos_pemilik',
                          TELEPON_AREA_PEMILIK   = '$ls_telepon_area_pemilik',
                          TELEPON_PEMILIK        = '$ls_telepon_pemilik',
                          TELEPON_EXT_PEMILIK    = '$ls_telepon_ext_pemilik',
                          FAX_AREA_PEMILIK       = '$ls_fax_area_pemilik',
                          FAX_PEMILIK            = '$ls_fax_pemilik',
                          EMAIL_PEMILIK          = '$ls_email_pemilik',
                          KODE_KEPEMILIKAN       = '$ls_kode_kepemilikan',
                          KODE_METODE_PEMBAYARAN = '$ls_kode_metode_byr',
                          KODE_BANK_PEMBAYARAN   = '$ls_kode_bank_byr',
                          NAMA_REKENING_PEMBAYARAN = '$ls_nama_rekening_pembayaran',
                          NO_REKENING_PEMBAYARAN = '$ls_no_rekening_pembayaran',
                          TELEPON_AREA_PIC       = '$ls_telepon_area_pic' ,
                          TELEPON_PIC            = '$ls_telepon_pic',
                          TELEPON_EXT_PIC        = '$ls_telepon_ext_pic',
                          --NO_FASKES              = '$ls_no_faskes',
                          KELAS_PERAWATAN        = '$ls_kelas_perawatan',
                          BAGIAN_PIC             = '$ls_bagian_pic',
                          TGL_UBAH               = SYSDATE,
                          PETUGAS_UBAH           = '$USER',
                          LATITUDE           = '$ls_latitude',
                          LONGITUDE           = '$ls_longitude',
                          KODE_PIC_CABANG           = '$ls_kode_pic_cabang',
                          KONTAK_PIC_CABANG           = '$ls_handphone_pic_kacab'
                  WHERE   KODE_FASKES = '$ls_kode_faskes';";

  $qry_agenda = "UPDATE  PN.PN_AGENDA_KOREKSI
                  SET     REFERENSI       = '$ls_kode_faskes',
                          STATUS_AGENDA   = 'DITUTUP',
                          DETIL_STATUS    = 'BERHASIL',
                          TGL_SELESAI     = SYSDATE
                  WHERE   KODE_AGENDA     = '$ls_kode_agenda';";

   $sql = "
        BEGIN
            $qry_faskes_history
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