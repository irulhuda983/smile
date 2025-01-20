<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['formregact'];
$noform                                 = $_POST['noform'];

$ls_kode_rtw 					        = $_POST["kode_rtw"];
$ls_pen_no   					        = $_POST["pen_no"];


//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $ls_kode_rtw != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="New" || $TYPE=="Edit")
{		
    $sql = 	"select count(kode_RTW_KLAIM) as JML from sijstk.pn_RTW_penilaian where kode_penilaian='NIL{$ls_kode_rtw}01'";
    $DB->parse($sql);// echo $sql;
    $DB->execute(); 
    $ls_count = 0;
    if($row = $DB->nextrow())
        $ls_count=$row['JML']; 
    if($ls_count==0)
    {
        $sql = "INSERT ALL
            into sijstk.pn_rtw_penilaian(KODE_RTW_KLAIM,KODE_KLAIM,KODE_TEMPLATE_PENILAIAN,KODE_PENILAIAN,TGL_REKAM,PETUGAS_REKAM)
            VALUES ('{$ls_kode_rtw}',(select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),'RTWTEMP01','NIL{$ls_kode_rtw}01',sysdate,'{$_SESSION['USER']}')
            into sijstk.pn_rtw_penilaian(KODE_RTW_KLAIM,KODE_KLAIM,KODE_TEMPLATE_PENILAIAN,KODE_PENILAIAN,TGL_REKAM,PETUGAS_REKAM)
            VALUES ('{$ls_kode_rtw}',(select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),'RTWTEMP02','NIL{$ls_kode_rtw}02',sysdate,'{$_SESSION['USER']}')
            into sijstk.pn_rtw_penilaian(KODE_RTW_KLAIM,KODE_KLAIM,KODE_TEMPLATE_PENILAIAN,KODE_PENILAIAN,TGL_REKAM,PETUGAS_REKAM)
            VALUES ('{$ls_kode_rtw}',(select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),'RTWTEMP03','NIL{$ls_kode_rtw}03',sysdate,'{$_SESSION['USER']}')
        SELECT * FROM dual"; //echo $sql;
        $DB->parse($sql);
        $DB->execute();

        $sql = "INSERT into sijstk.pn_rtw_penilaian_detil(KODE_PENILAIAN,KODE_TEMPLATE_PENILAIAN,KODE_ASSESMENT,BOBOT,TGL_REKAM,PETUGAS_REKAM,NO_URUT)
            select A.*,rownum + {$ls_count}  as NO_URUT from
            (
                select 'NIL{$ls_kode_rtw}01' K, KODE_TEMPLATE_PENILAIAN,KODE_ASSESMENT,BOBOT,sysdate,'{$_SESSION['USER']}' U from sijstk.PN_RTW_KODE_PENILAIAN_DETIL where KODE_TEMPLATE_PENILAIAN='RTWTEMP01'
                union all
                select 'NIL{$ls_kode_rtw}02' K, KODE_TEMPLATE_PENILAIAN,KODE_ASSESMENT,BOBOT,sysdate,'{$_SESSION['USER']}' U from sijstk.PN_RTW_KODE_PENILAIAN_DETIL where KODE_TEMPLATE_PENILAIAN='RTWTEMP02'
                union all
                select 'NIL{$ls_kode_rtw}03' K, KODE_TEMPLATE_PENILAIAN,KODE_ASSESMENT,BOBOT,sysdate,'{$_SESSION['USER']}' U from sijstk.PN_RTW_KODE_PENILAIAN_DETIL where KODE_TEMPLATE_PENILAIAN='RTWTEMP03'
            ) A";
        $DB->parse($sql); //echo $sql;
        $DB->execute();
    }
    if($ls_pen_no==1)
    {
        $ls_tgl_penilaian		        = strtoupper($_POST["pen_a_tgl_penilaian"]);
        $ls_nama_penilai			    = strtoupper($_POST["pen_a_nama_penilai"]);
        $ls_jbt_penilai		            = strtoupper($_POST["pen_a_jbt_penilai"]);
        $ls_tek_darah	                = strtoupper($_POST["pen_a_tek_darah"]);
        $ls_tg_badan		            = strtoupper($_POST["pen_a_tg_bdn"]);
        $ls_brt_badan		            = strtoupper($_POST["pen_a_brt_bdn"]);
        $ls_rwy_medis		            = strtoupper($_POST["pen_a_rwyt_medis"]);
        $ls_ref_tk  		            = strtoupper($_POST["pen_a_ref_tk"]);
        $ls_ref_prs 		            = strtoupper($_POST["pen_a_ref_prs"]);
        $ls_keterangan		            = strtoupper($_POST["pen_a_ket"]);
        $ls_job_desc		            = strtoupper($_POST["pen_a_job_desc"]);
        $ls_bbn_kerja		            = strtoupper($_POST["pen_a_beban_kerja"]);
        $ls_proc_kerja		            = strtoupper($_POST["pen_a_proc_kerja"]);
        $ls_pak		                    = strtoupper($_POST["pen_a_pak"]);
        $sql = 	"select count(kode_PENILAIAN) as JML from sijstk.pn_RTW_penilaian_info_awal where KODE_PENILAIAN='NIL{$ls_kode_rtw}01'";
        $DB->parse($sql);
        $DB->execute();
        $ls_count = 0;
        if($row = $DB->nextrow())
            $ls_count=$row['JML']; //echo $ls_count ;

        if($ls_count<=0)
            $sql = "insert into sijstk.pn_RTW_penilaian_info_awal(KODE_PENILAIAN,
            NO_URUT,
            TGL_PENILAIAN_AWAL,
            NAMA_PENILAI_AWAL,
            JABATAN_PENILAI_AWAL,
            TEKANAN_DARAH_AWAL,
            TINGGI_BADAN_AWAL,
            BERAT_BADAN_AWAL,
            RIWAYAT_MEDIS_AWAL,
            REF_TK_AWAL,
            REF_PERUSAHAAN_AWAL,
            KETERANGAN_AWAL,
            DESKRIPSI_TUGAS_AWAL,
            PROSES_KERJA_AWAL,
            KODE_BEBAN_KERJA_AWAL,
            KODE_PAK_AWAL,
            TGL_REKAM,
            PETUGAS_REKAM)
            values(
            'NIL{$ls_kode_rtw}01',
            0,
            to_date('{$ls_tgl_penilaian}','DD/MM/YYYY') ,
            '{$ls_nama_penilai}' ,
            '{$ls_jbt_penilai}',
            '{$ls_tek_darah}' ,
            '{$ls_tg_badan}' ,
            '{$ls_brt_badan}',
            '{$ls_rwy_medis}' ,
            '{$ls_ref_tk}' ,
            '{$ls_ref_prs}' ,   
            '{$ls_keterangan}' ,
            '{$ls_job_desc}' ,
            '{$ls_proc_kerja}',
            '{$ls_bbn_kerja}' ,
            '{$ls_pak}',
            sysdate,
            '{$_SESSION['USER']}' )";
        else
            $sql = 	"update sijstk.pn_RTW_penilaian_info_awal set     
            TGL_PENILAIAN_AWAL=to_date('{$ls_tgl_penilaian}','DD/MM/YYYY') ,
            NAMA_PENILAI_AWAL='{$ls_nama_penilai}' ,
            JABATAN_PENILAI_AWAL='{$ls_jbt_penilai}',
            TEKANAN_DARAH_AWAL='{$ls_tek_darah}' ,
            TINGGI_BADAN_AWAL='{$ls_tg_badan}' ,
            BERAT_BADAN_AWAL='{$ls_brt_badan}',
            RIWAYAT_MEDIS_AWAL='{$ls_rwy_medis}' ,
            REF_TK_AWAL='{$ls_ref_tk}' ,
            REF_PERUSAHAAN_AWAL='{$ls_ref_prs}' , 
            KETERANGAN_AWAL='{$ls_keterangan}' ,
            DESKRIPSI_TUGAS_AWAL='{$ls_job_desc}' ,
            PROSES_KERJA_AWAL='{$ls_proc_kerja}',
            KODE_BEBAN_KERJA_AWAL='{$ls_bbn_kerja}' ,
            KODE_PAK_AWAL='{$ls_pak}',      
            TGL_UBAH =sysdate,
            PETUGAS_UBAH=    '{$_SESSION['USER']}'
            where KODE_PENILAIAN='NIL{$ls_kode_rtw}01'";
        $DB->parse($sql); //echo $sql;
        if($DB->execute())
        {
            $sql = 	"update sijstk.pn_rtw_klaim set status_rtw_klaim='DIPROSES' where  kode_rtw_klaim='{$ls_kode_rtw}' and status_rtw_klaim='BARU'";
            $DB->parse($sql);
            $DB->execute();
            echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_rtw.'"}';		
        }
        else 
            echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
    }   
    else if($ls_pen_no==2)
    {
        $ls_tgl_penilaian              = strtoupper($_POST["pen_lk_tgl_penilaian"]);
        $ls_nama_penilai             = strtoupper($_POST["pen_lk_nama_penilai"]);
        $ls_jbt_penilai                  = strtoupper($_POST["pen_lk_jbt_penilai"]);
        $ls_tek_darah                   = strtoupper($_POST["pen_lk_tek_darah"]);
        $ls_tg_badan                  = strtoupper($_POST["pen_lk_tg_bdn"]);
        $ls_brt_badan                  = strtoupper($_POST["pen_lk_brt_bdn"]);
        $ls_rwy_medis                  = strtoupper($_POST["pen_lk_rwyt_medis"]);
        $ls_eval_fisik                    = strtoupper($_POST["pen_lk_eval_fisik"]);
        $ls_eval_mental                   = strtoupper($_POST["pen_lk_eval_mental"]);
        $ls_eval_kapasitas                 = strtoupper($_POST["pen_lk_eval_kapasitas"]);
        $ls_analisa                  = strtoupper($_POST["pen_lk_ket_analisa"]);
        $ls_rekomendasi                  = strtoupper($_POST["pen_lk_ket_rekomendasi"]);
        $ls_intervensi                 = strtoupper($_POST["pen_lk_ket_intervensi"]);
        $sql = 	"select count(kode_PENILAIAN) as JML from sijstk.pn_RTW_penilaian_info_link where KODE_PENILAIAN='NIL{$ls_kode_rtw}02'";
        $DB->parse($sql);
        $DB->execute();
        $ls_count = 0;
        if($row = $DB->nextrow())
            $ls_count=$row['JML']; //echo $ls_count ;

        if($ls_count<=0)
            $sql = "insert into sijstk.pn_RTW_penilaian_info_link(KODE_PENILAIAN,
            NO_URUT,
            TGL_PENILAIAN_LINGKUNGAN,
            NAMA_PENILAI_LINGKUNGAN,
            JABATAN_PENILAI_LINGKUNGAN,
            TEKANAN_DARAH_LINGKUNGAN,
            TINGGI_BADAN_LINGKUNGAN,
            BERAT_BADAN_LINGKUNGAN,
            RIWAYAT_MEDIS_LINGKUNGAN,
            EVALUASI_FISIK,
            EVALUASI_MENTAL,
            EVALUASI_KAPASITAS,
            KETERANGAN_ANALISA,
            KETERANGAN_REKOMENDASI,
            KETERANGAN_INTERVENSI,
            TGL_REKAM,
            PETUGAS_REKAM)
            values(
            'NIL{$ls_kode_rtw}02',
            0,
            to_date('{$ls_tgl_penilaian}','DD/MM/YYYY') ,
            '{$ls_nama_penilai}' ,
            '{$ls_jbt_penilai}',
            '{$ls_tek_darah}' ,
            '{$ls_tg_badan}' ,
            '{$ls_brt_badan}',
            '{$ls_rwy_medis}' ,
            '{$ls_eval_fisik}' ,
            '{$ls_eval_mental}' ,   
            '{$ls_eval_kapasitas}' ,
            '{$ls_analisa}' ,
            '{$ls_rekomendasi}',
            '{$ls_intervensi}' ,
            sysdate,
            '{$_SESSION['USER']}' )";
        else
            $sql = 	"update sijstk.pn_RTW_penilaian_info_link set     
            TGL_PENILAIAN_LINGKUNGAN=to_date('{$ls_tgl_penilaian}','DD/MM/YYYY') ,
            NAMA_PENILAI_LINGKUNGAN='{$ls_nama_penilai}' ,
            JABATAN_PENILAI_LINGKUNGAN='{$ls_jbt_penilai}',
            TEKANAN_DARAH_LINGKUNGAN='{$ls_tek_darah}' ,
            TINGGI_BADAN_LINGKUNGAN='{$ls_tg_badan}' ,
            BERAT_BADAN_LINGKUNGAN='{$ls_brt_badan}',
            RIWAYAT_MEDIS_LINGKUNGAN='{$ls_rwy_medis}' ,
            EVALUASI_FISIK='{$ls_eval_fisik}' ,
            EVALUASI_MENTAL='{$ls_eval_mental}' , 
            EVALUASI_KAPASITAS='{$ls_eval_kapasitas}' ,
            KETERANGAN_ANALISA='{$ls_analisa}' ,
            KETERANGAN_REKOMENDASI='{$ls_rekomendasi}',
            KETERANGAN_INTERVENSI='{$ls_intervensi}' ,                  
            TGL_UBAH =sysdate,
            PETUGAS_UBAH=    '{$_SESSION['USER']}'
            where KODE_PENILAIAN='NIL{$ls_kode_rtw}01'";
        $DB->parse($sql); //echo $sql;
        if($DB->execute())
        {
            $sql = 	"update sijstk.pn_rtw_klaim set status_rtw_klaim='DIPROSES' where  kode_rtw_klaim='{$ls_kode_rtw}' and status_rtw_klaim='BARU'";
            $DB->parse($sql);
            $DB->execute();
            echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_rtw.'"}';		
        }
        else 
            echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
    }
    else if($ls_pen_no==3)
    {
        $ls_tgl_penilaian              =strtoupper( $_POST["pen_pk_tgl_penilaian"]);
        $ls_nama_penilai             = strtoupper($_POST["pen_pk_nama_penilai"]);
        $ls_jbt_penilai                  = strtoupper($_POST["pen_pk_jbt_penilai"]);
        $ls_ref_prs                   = strtoupper($_POST["pen_pk_ref_prs"]);
        $ls_job_desc                  =strtoupper($_POST["pen_pk_job_desc"]);
        $ls_beban_kerja                 = strtoupper($_POST["pen_pk_beban_kerja"]);
        $ls_proc_kerja                  = strtoupper($_POST["pen_pk_proc_kerja"]);
        $ls_pak                   = strtoupper($_POST["pen_pk_pak"]);
        $ls_analisa                   = strtoupper($_POST["pen_pk_analisa"]);
        $ls_prosen                 = strtoupper($_POST["pen_pk_prosen_cacat"]);
        $ls_kesimpulan                  = strtoupper($_POST["pen_pk_kesimpulan"]);
        $ls_keterangan                 = strtoupper($_POST["pen_pk_keterangan"]);
        $ls_biaya                =strtoupper( $_POST["pen_pk_biaya_alat"]);
        $sql = 	"select count(kode_PENILAIAN) as JML from sijstk.pn_RTW_penilaian_info_kerja where KODE_PENILAIAN='NIL{$ls_kode_rtw}03'";
        $DB->parse($sql);
        $DB->execute();
        $ls_count = 0;
        if($row = $DB->nextrow())
            $ls_count=$row['JML']; //echo $ls_count ;

        if($ls_count<=0)
            $sql = "insert into sijstk.pn_RTW_penilaian_info_kerja(KODE_PENILAIAN,
            NO_URUT,
            TGL_PENEMPATAN,
            NAMA_PENILAI_PENEMPATAN,
            JABATAN_PENILAI_PENEMPATAN,
            DESKRIPSI_TUGAS_PENEMPATAN,
            PROSES_KERJA_PENEMPATAN,
            KODE_BEBAN_KERJA_PENEMPATAN,
            KODE_PAK_PENEMPATAN,
            KETERANGAN_HASIL_ANALISA,
            PERSENTASE_CACAT,
            KESIMPULAN,
            REF_PERUSAHAAN_PENEMPATAN,
            KETERANGAN_PENEMPATAN,
            BIAYA_ALAT,
            TGL_REKAM,
            PETUGAS_REKAM)
            values(
            'NIL{$ls_kode_rtw}03',
            0,
            to_date('{$ls_tgl_penilaian}','DD/MM/YYYY') ,
            '{$ls_nama_penilai}' ,
            '{$ls_jbt_penilai}',
            '{$ls_job_desc}' ,
            '{$ls_proc_kerja}' ,
            '{$ls_beban_kerja}',
            '{$ls_pak}' ,
            '{$ls_analisa}' ,
            '{$ls_prosen}' ,   
            '{$ls_kesimpulan}' ,
            '{$ls_ref_prs}' ,
            '{$ls_keterangan}',
            '{$ls_biaya}' ,
            sysdate,
            '{$_SESSION['USER']}' )";
        else
            $sql = 	"update sijstk.pn_RTW_penilaian_info_kerja set     
            TGL_PENEMPATAN=to_date('{$ls_tgl_penilaian}','DD/MM/YYYY') ,
            NAMA_PENILAI_PENEMPATAN='{$ls_nama_penilai}' ,
            JABATAN_PENILAI_PENEMPATAN='{$ls_jbt_penilai}',
            DESKRIPSI_TUGAS_PENEMPATAN='{$ls_job_desc}' ,
            PROSES_KERJA_PENEMPATAN='{$ls_proc_kerja}' ,
            KODE_BEBAN_KERJA_PENEMPATAN='{$ls_beban_kerja}',
            KODE_PAK_PENEMPATAN='{$ls_pak}' ,
            KETERANGAN_HASIL_ANALISA='{$ls_analisa}' ,
            PERSENTASE_CACAT='{$ls_prosen}' , 
            KESIMPULAN='{$ls_kesimpulan}' ,
            REF_PERUSAHAAN_PENEMPATAN='{$ls_ref_prs}' ,
            KETERANGAN_PENEMPATAN='{$ls_keterangan}',
            BIAYA_ALAT='{$ls_biaya}' ,            
            PETUGAS_UBAH=    '{$_SESSION['USER']}'
            where KODE_PENILAIAN='NIL{$ls_kode_rtw}03'";
        $DB->parse($sql);// echo $sql;
        if($DB->execute())
        {
            echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_rtw.'"}';		
        }
        else 
            echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
    }     
}

?>