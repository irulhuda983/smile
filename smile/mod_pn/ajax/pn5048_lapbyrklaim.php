<?php
$pagetype = "report";
$gs_pagetitle = "Laporan Pembayaran Jaminan";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_newrpt.php';
	
/*--------------------- Form History -----------------------------------------
File: inv5501_laporanharian_saham.php

Deskripsi:
-----------
File ini dipergunakan untuk Pencetakan Laporan Listing Transaksi

Author:
--------
Pitra

Histori Perubahan:
--------------------
25/02/2009 - Pitra
Pembuatan Form

-------------------- End Form History --------------------------------------*/

$ls_kode_kantor		= $_POST["kode_kantor"];
$ls_kode_segmen		= $_POST["kode_segmen"];
$ls_kd_prg				= $_POST["kd_prg"];
$ls_jenis_laporan	 	= $_POST["jenis_laporan"];
$ld_tgl1						= $_POST["tgl1"];
$ld_tgl2						= $_POST["tgl2"];
$ld_tgllap					= $_POST["tgllap"];
$ls_metode_byr			= $_POST["metode_byr"];
$ls_st_csv					= $_POST["st_csv"];
$ls_kode_buku				= $_POST["kode_buku"];
$username   = $_SESSION['USER'];
$noref      = $_POST["noref"];
$ls_kd_bank      = $_POST["kd_bank"];

if ($ls_st_csv=="on" || $ls_st_csv=="ON" || $ls_st_csv=="Y")
{
	$ls_st_csv = "Y";
}else
{
	$ls_st_csv = "T";
}

if(isset($_POST["btncetak"]))
{	
	/*---------Kirim Parameter----------------------------------------------------*/

	//set parameter kantor
	if ($ls_kode_kantor==""){$ls_lap_kode_kantor = "";}else{$ls_lap_kode_kantor = $ls_kode_kantor;}
	$ls_user_param .= " QKTR='$ls_lap_kode_kantor'";	

	if ($ls_kd_prg==""){$ls_lap_kd_prg = "";}else{$ls_lap_kd_prg = $ls_kd_prg;}
	$ls_user_param .= " QPRG='$ls_lap_kd_prg'";	
		
	if ($ls_metode_byr==""){$ls_lap_metode_byr = "";}else{$ls_lap_metode_byr = $ls_metode_byr;}
	$ls_user_param .= " QCARABYR='$ls_lap_metode_byr'";	
			
	//set parameter kode_buku ----------------------------------------------------
  if ($ls_kode_buku==""){$ls_lap_kode_buku = "";}else{$ls_lap_kode_buku = $ls_kode_buku;}
  $ls_user_param .= " qbuku='$ls_lap_kode_buku'";	
				
  $sql = "select to_char(to_date('$ld_tgl1','dd/mm/yyyy'),'yyyymmdd') tgl1, ".
			 	 "			 to_char(to_date('$ld_tgl2','dd/mm/yyyy'),'yyyymmdd') tgl2, ".
				 "			 to_char(to_date('$ld_tgllap','dd/mm/yyyy'),'yyyymmdd') tgllap from dual";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_laptgl1 		= $row["TGL1"];						
  $ls_laptgl2 		= $row["TGL2"];
	$ls_laptgllap 	= $row["TGLLAP"];
	
  $ls_user_param .= " QTGL1='$ls_laptgl1'";	
  $ls_user_param .= " QTGL2='$ls_laptgl2'";
	$ls_user_param .= " QTGLLAP='$ls_laptgllap'";

	//CETAK REPORT ---------------------------------------------------------------
  $tipe     = "PDF";
  $ls_modul = "pn"; 

  
	if ($ls_jenis_laporan=="lap2") //Pembayaran Jaminan
	{ 
		if ($ls_st_csv=="Y")
		{
  		$sql = "select
                kd_cbg, kd_klaim, no_penetapan, tgl_penetapan, no_klaim, 
								replace(replace(replace(replace(replace(replace(sebab_klaim,chr(10),''),chr(13),''),chr(14),''),chr(34),''),chr(39),''),';','') sebab_klaim, 
								tgl_klaim, tipe_klaim, 
                kd_tk, nm_tk, kpjtk, kd_cbg_tk, kd_prs, 
								replace(replace(replace(replace(replace(replace(nm_prs,chr(10),''),chr(13),''),chr(14),''),chr(34),''),chr(39),''),';','') nm_prs, 
								npp, kd_pry, 
								replace(replace(replace(replace(replace(replace(nama_proyek,chr(10),''),chr(13),''),chr(14),''),chr(34),''),chr(39),''),';','') nama_proyek,         
                saldo_iuran_jht,
                hasil_pengembangan_jht, 
                pph_21, 
                jml_pokok_bayar, 
                jml_pembulatan,
                jml_transfer, 
                tgl_bayar, tgl_batal,
                vouc_saldo_iuran_jht,
                vouc_hslpengembangan_jht,
                vouc_pph_21,
                case when abs(nvl(vouc_saldo_iuran_jht,0))+abs(nvl(vouc_hslpengembangan_jht,0)) = 0 then
                    nvl(vouc_pokok_bayar,0)
                else
                    nvl(vouc_pokok_bayar,0)-nvl(vouc_pph_21,0)
                end vouc_pokok_bayar,                                
                vouc_pembulatan,
                vouc_transfer,
                vouc_count                                         
            from
            (
                select 
                    kd_cbg, kd_klaim, no_penetapan, tgl_penetapan, no_klaim, sebab_klaim, tgl_klaim, tipe_klaim, 
                    kd_tk, nm_tk, kpjtk, kd_cbg_tk, kd_prs, nm_prs, npp, kd_pry, nama_proyek,         
                    sum(nvl(nilai_bbnjaminan_jht,0)) saldo_iuran_jht,
                    sum(nvl(nilai_hslpengembangan_jht,0)) hasil_pengembangan_jht, 
                    sum(nvl(pph_21,0)) pph_21, 
                    sum(nvl(jml_pokok_bayar,0)) jml_pokok_bayar, 
                    sum(nvl(
                    decode(jns_jht,'JHT_FULL',abs(nvl(nilai_trans,0)-(nvl(nilai_bbnjaminan_jht,0)+nvl(nilai_hslpengembangan_jht,0)-nvl(pph_21,0))), 
                                   'JHT_PARTIAL', abs(nvl(nilai_trans,0)-(nvl(jml_pokok_bayar,0)-nvl(pph_21,0))), 
                                   0),0)) jml_pembulatan,                     
                    sum(nvl(nilai_trans,0)) jml_transfer, 
                    max(tgl_bayar) tgl_bayar, max(tgl_batal) tgl_batal,
                    ( select sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and x.akun in ('5101010101')
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_saldo_iuran_jht,
                    ( select sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and x.akun = '5101020101'
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_hslpengembangan_jht,
                    ( select -sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and x.akun = '2601011100'
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_pph_21,
                    ( select sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and x.akun in ('5101010101','5101020101','5101010102','5102010100','5102010300')
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_pokok_bayar,                                
                    ( select sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and x.akun = '5804080100'
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_pembulatan,
                    ( select -sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and substr(x.akun,1,4) in ('1101','1201')
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_transfer,
                    ( select count(*) from sijstk.gl_voucher y
                      where y.id_dokumen_induk = to_char(tt.kd_klaim) and y.kode_pointer_induk like 'JM%' 
                      and to_char(y.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_count         
                from 
                (    
                     select 
                        c.kd_cbg, a.kd_klaim, c.no_penetapan, c.tgl_penetapan, c.no_klaim, c.sebab_klaim, c.tgl_klaim, c.tipe_klaim, 
                        c.kd_tk,c.nm_tk,c.kpjtk, c.kd_cbg_tk, c.kd_prs, replace(replace(replace(c.nm_prs,chr(10),''),chr(13),''),chr(14),'') nm_prs, 
                                                c.npp, c.kd_pry, replace(replace(replace(c.nama_proyek,chr(10),''),chr(13),''),chr(14),'') nama_proyek,         
                        a.kd_prg,(select nm_prg from sijstk.ms_prg where kd_prg = a.kd_prg) nm_prg, 
                        a.tipe_penerima,a.tgl_bayar,a.tgl_batal, 
                        b.nm_penerima, 
                        case when a.kd_prg in ('1','4') then 
                            case when  
                                (                     
                                    select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)+nvl(x.nilai6_disetujui,0)) 
                                    from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                    where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                    and x.kd_klaim = a.kd_klaim 
                                    and x.tipe_penerima = a.tipe_penerima 
                                    and y.kd_prg = a.kd_prg                                         
                                ) = 0 then 'JHT_PARTIAL'  
                            else 
                                'JHT_FULL'                             
                            end 
                        else 
                            'NON_JHT' 
                        end jns_jht,  
                        case when a.kd_prg in ('1','4') then 
                            ( 
                                select sum(nvl(x.nilai4_diajukan,0)) from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                and x.kd_klaim = a.kd_klaim 
                                and x.tipe_penerima = a.tipe_penerima 
                                and y.kd_prg = a.kd_prg                 
                            ) 
                        else 
                            0 
                        end klaim_jht_sebelumnya,                             
                        case when a.kd_prg in ('1','4') then 
                            (             
                                select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)) 
                                from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                and x.kd_klaim = a.kd_klaim 
                                and x.tipe_penerima = a.tipe_penerima 
                                and y.kd_prg = a.kd_prg                 
                            ) 
                        else 
                            0 
                        end nilai_bbnjaminan_jht,     
                        case when a.kd_prg in ('1','4') then 
                            (                 
                                select sum(nvl(x.nilai6_disetujui,0)) 
                                from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                and x.kd_klaim = a.kd_klaim 
                                and x.tipe_penerima = a.tipe_penerima 
                                and y.kd_prg = a.kd_prg                 
                            ) 
                        else 
                            0 
                        end nilai_hslpengembangan_jht,     
                        case when a.kd_prg in ('1','4') then 
                            (             
                                select sum(nvl(x.nilai4_disetujui,0)) 
                                from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                and x.kd_klaim = a.kd_klaim 
                                and x.tipe_penerima = a.tipe_penerima 
                                and y.kd_prg = a.kd_prg             
                            ) 
                        else 
                            0 
                        end pph_21, 
                        case when a.kd_prg in ('1','4') then 
                            case when  
                            ( 
                                select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)+nvl(x.nilai6_disetujui,0)) 
                                from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                and x.kd_klaim = a.kd_klaim 
                                and x.tipe_penerima = a.tipe_penerima 
                                and y.kd_prg = a.kd_prg                 
                            ) = 0  
                            then  
                                ( 
                                    select sum(nvl(x.nilai1_disetujui,0)+nvl(x.nilai4_disetujui,0)) 
                                    from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                    where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                    and x.kd_klaim = a.kd_klaim 
                                    and x.tipe_penerima = a.tipe_penerima 
                                    and y.kd_prg = a.kd_prg                     
                                )                  
                            else 
                                (                     
                                    select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)+nvl(x.nilai6_disetujui,0)-nvl(x.nilai4_disetujui,0)) 
                                    from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                    where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                    and x.kd_klaim = a.kd_klaim 
                                    and x.tipe_penerima = a.tipe_penerima 
                                    and y.kd_prg = a.kd_prg                     
                                )             
                            end 
                        else 
                            a.jml_bayar 
                        end jml_pokok_bayar, 
                        a.jml_bayar nilai_trans                
                    from sijstk.tx_klaim_tk_byr_prg a, sijstk.tx_klaim_tk_byr b, sijstk.tx_klaim_tk c 
                    where a.kd_klaim = b.kd_klaim(+) and a.tipe_penerima = b.tipe_penerima(+) and a.kd_klaim = c.kd_klaim 
                    and to_char(a.tgl_bayar,'yyyymmdd') between '$ls_laptgl1' and '$ls_laptgl2'
                    and nvl(to_char(a.tgl_batal,'yyyymmdd'),'30000101') > '$ls_laptgllap'
                    and to_char(a.tgl_bayar,'yyyymmdd') <= '$ls_laptgllap'
                    and a.no_pointer is not null 
                    and a.kd_prg like nvl('$ls_kd_prg','%') 
                    and c.kd_cbg in (select kode_kantor from sijstk.ms_kantor start with kode_kantor = '$ls_lap_kode_kantor' connect by prior kode_kantor=kode_kantor_induk)       
                )tt
                group by kd_cbg, kd_klaim, no_penetapan, tgl_penetapan, no_klaim, sebab_klaim, tgl_klaim, tipe_klaim, 
                    kd_tk, nm_tk, kpjtk, kd_cbg_tk, kd_prs, nm_prs, npp, kd_pry, nama_proyek  
            ) tt2 ";	
    	//echo $sql;
  		$DB->parse($sql); 
    	$DB->execute();
  		$somecontent = "KD_CBG;KD_KLAIM;NO_PENETAPAN;TGL_PENETAPAN;NO_KLAIM;SEBAB_KLAIM;TGL_KLAIM;TIPE_KLAIM;KD_TK;NM_TK;KPJTK;KD_CBG_TK;KD_PRS;NM_PRS;NPP;KD_PRY;NAMA_PROYEK;TGL_BAYAR;TGL_BATAL;SALDO_IURAN_JHT;HASIL_PENGEMBANGAN_JHT;PPH_21;JML_POKOK_BAYAR;JML_PEMBULATAN;JML_TRANSFER;VOUC_SALDO_IURAN_JHT;VOUC_HSLPENGEMBANGAN_JHT;VOUC_PPH_21;VOUC_POKOK_BAYAR;VOUC_PEMBULATAN;VOUC_TRANSFER;VOUC_COUNT".chr(13).chr(10);							
    	while ($row = $DB->nextrow())
    	{	
        $lscsv_kd_cbg							 = $row["KD_CBG"];
        $lscsv_kd_klaim						 = $row["KD_KLAIM"];			 
        $lscsv_no_penetapan				 = $row["NO_PENETAPAN"];
        $lscsv_tgl_penetapan			 = $row["TGL_PENETAPAN"];
        $lscsv_no_klaim						 = $row["NO_KLAIM"];
        $lscsv_sebab_klaim				 = $row["SEBAB_KLAIM"];
        $lscsv_tgl_klaim					 = $row["TGL_KLAIM"];
        $lscsv_tipe_klaim					 = $row["TIPE_KLAIM"];
        $lscsv_kd_tk							 = $row["KD_TK"];
        $lscsv_nm_tk							 = $row["NM_TK"];
        $lscsv_kpjtk							 = $row["KPJTK"];
        $lscsv_kd_cbg_tk					 = $row["KD_CBG_TK"];
        $lscsv_kd_prs							 = $row["KD_PRS"];
        $lscsv_nm_prs							 = $row["NM_PRS"];
        $lscsv_npp								 = $row["NPP"];
        $lscsv_kd_pry							 = $row["KD_PRY"];
        $lscsv_nama_proyek				 = $row["NAMA_PROYEK"];
        $lscsv_saldo_iuran_jht		 	 = $row["SALDO_IURAN_JHT"];
        $lscsv_hasil_pengembangan_jht	 = $row["HASIL_PENGEMBANGAN_JHT"];
        $lscsv_pph_21									 = $row["PPH_21"];
        $lscsv_jml_pokok_bayar				 = $row["JML_POKOK_BAYAR"];
        $lscsv_jml_pembulatan					 = $row["JML_PEMBULATAN"];
        $lscsv_jml_transfer						 = $row["JML_TRANSFER"];
        $lscsv_tgl_bayar							 = $row["TGL_BAYAR"];
        $lscsv_tgl_batal							 = $row["TGL_BATAL"];
        $lscsv_saldo_iuran_jht		 		 = $row["VOUC_SALDO_IURAN_JHT"];
        $lscsv_vouc_hslpengembangan_jht	 = $row["VOUC_HSLPENGEMBANGAN_JHT"];
        $lscsv_vouc_pph_21							 = $row["VOUC_PPH_21"];
				$lscsv_vouc_pokok_bayar					 = $row["VOUC_POKOK_BAYAR"];
        $lscsv_vouc_pembulatan					 = $row["VOUC_PEMBULATAN"];
        $lscsv_vouc_transfer						 = $row["VOUC_TRANSFER"];
				$lscsv_vouc_count						 		 = $row["VOUC_COUNT"];

				$somecontent .= $lscsv_kd_cbg.";".$lscsv_kd_klaim.";".$lscsv_no_penetapan.";".$lscsv_tgl_penetapan.";".$lscsv_no_klaim.";".$lscsv_sebab_klaim.";".$lscsv_tgl_klaim.";".$lscsv_tipe_klaim.";".$lscsv_kd_tk.";".$lscsv_nm_tk.";".$lscsv_kpjtk.";".$lscsv_kd_cbg_tk.";".$lscsv_kd_prs.";".$lscsv_nm_prs.";".$lscsv_npp.";".$lscsv_kd_pry.";".$lscsv_nama_proyek.";".$lscsv_tgl_bayar.";".$lscsv_tgl_batal.";".$lscsv_saldo_iuran_jht.";".$lscsv_hasil_pengembangan_jht.";".$lscsv_pph_21.";".$lscsv_jml_pokok_bayar.";".$lscsv_jml_pembulatan.";".$lscsv_jml_transfer.";".$lscsv_saldo_iuran_jht.";".$lscsv_vouc_hslpengembangan_jht.";".$lscsv_vouc_pph_21.";".$lscsv_vouc_pokok_bayar.";".$lscsv_vouc_pembulatan.";".$lscsv_vouc_transfer.";".$lscsv_vouc_count."".chr(13).chr(10);
  		}  	
    	$ls_tgl		  = $DB->get_data("select to_char(sysdate,'YYYYMMDDHH24MISS') from dual");
    	$filename 	= "KLAIM_BYR".$ls_tgl.".csv";
    	$loc_server	= "../../teks/";
    	$upload 	= $loc_server.$filename;
  
    	if (file_exists($upload)){ 
    			unlink($upload);
    	}									
    	$fp = fopen($upload,"a");
    	$ok = fputs($fp,$somecontent);
    	fclose($fp);
  		
    	if ($ok)
    	{ 
    		echo "<script type=\"text/javascript\">".
    		"window.open('dl.php?act=dl&file=$filename&loc_server=$loc_server');".
    		"</script>";
    	}
  		$ls_error="1";//agar tidak memanggil fungsi report 				
		}else
		{
		 	$ls_nama_rpt 	.= "PNR603002.rdf";
		}	
	}
     else if ($ls_jenis_laporan=="lap4") //Pembayaran Jaminan JMO
    { 
        if ($ls_st_csv=="Y")
        {
        $sql = "select
*
from
(
select
                kd_cbg, kd_klaim, no_penetapan, tgl_penetapan, no_klaim, 
                                replace(replace(replace(replace(replace(replace(sebab_klaim,chr(10),''),chr(13),''),chr(14),''),chr(34),''),chr(39),''),';','') sebab_klaim, 
                                tgl_klaim, tipe_klaim, 
                kd_tk, nm_tk, kpjtk, kd_cbg_tk, kd_prs, 
                                replace(replace(replace(replace(replace(replace(nm_prs,chr(10),''),chr(13),''),chr(14),''),chr(34),''),chr(39),''),';','') nm_prs, 
                                npp, kd_pry, 
                                replace(replace(replace(replace(replace(replace(nama_proyek,chr(10),''),chr(13),''),chr(14),''),chr(34),''),chr(39),''),';','') nama_proyek,         
                saldo_iuran_jht,
                hasil_pengembangan_jht, 
                pph_21, 
                jml_pokok_bayar, 
                jml_pembulatan,
                jml_transfer, 
                tgl_bayar, tgl_batal,
                vouc_saldo_iuran_jht,
                vouc_hslpengembangan_jht,
                vouc_pph_21,
                case when abs(nvl(vouc_saldo_iuran_jht,0))+abs(nvl(vouc_hslpengembangan_jht,0)) = 0 then
                    nvl(vouc_pokok_bayar,0)
                else
                    nvl(vouc_pokok_bayar,0)-nvl(vouc_pph_21,0)
                end vouc_pokok_bayar,                                
                vouc_pembulatan,
                vouc_transfer,
                vouc_count,
                 (select kanal_pelayanan from pn.pn_klaim where kode_klaim = tt2.kd_klaim and rownum = 1)  kanal_pelayanan                                         
            from
            (
                select 
                    kd_cbg, kd_klaim, no_penetapan, tgl_penetapan, no_klaim, sebab_klaim, tgl_klaim, tipe_klaim, 
                    kd_tk, nm_tk, kpjtk, kd_cbg_tk, kd_prs, nm_prs, npp, kd_pry, nama_proyek,         
                    sum(nvl(nilai_bbnjaminan_jht,0)) saldo_iuran_jht,
                    sum(nvl(nilai_hslpengembangan_jht,0)) hasil_pengembangan_jht, 
                    sum(nvl(pph_21,0)) pph_21, 
                    sum(nvl(jml_pokok_bayar,0)) jml_pokok_bayar, 
                    sum(nvl(
                    decode(jns_jht,'JHT_FULL',abs(nvl(nilai_trans,0)-(nvl(nilai_bbnjaminan_jht,0)+nvl(nilai_hslpengembangan_jht,0)-nvl(pph_21,0))), 
                                   'JHT_PARTIAL', abs(nvl(nilai_trans,0)-(nvl(jml_pokok_bayar,0)-nvl(pph_21,0))), 
                                   0),0)) jml_pembulatan,                     
                    sum(nvl(nilai_trans,0)) jml_transfer, 
                    max(tgl_bayar) tgl_bayar, max(tgl_batal) tgl_batal,
                    ( select sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and x.akun in ('5101010101')
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_saldo_iuran_jht,
                    ( select sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and x.akun = '5101020101'
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_hslpengembangan_jht,
                    ( select -sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and x.akun = '2601011100'
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_pph_21,
                    ( select sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and x.akun in ('5101010101','5101020101','5101010102','5102010100','5102010300')
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_pokok_bayar,                                
                    ( select sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and x.akun = '5804080100'
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_pembulatan,
                    ( select -sum(nvl(x.cdebet,0)-nvl(x.ckredit,0)) from sijstk.gl_voucher_jurnal x, sijstk.gl_voucher y
                      where x.id_dokumen = y.id_dokumen and y.id_dokumen_induk = to_char(tt.kd_klaim) and substr(x.akun,1,4) in ('1101','1201')
                      and to_char(x.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_transfer,
                    ( select count(*) from sijstk.gl_voucher y
                      where y.id_dokumen_induk = to_char(tt.kd_klaim) and y.kode_pointer_induk like 'JM%' 
                      and to_char(y.tgl_trans,'yyyymmdd')<= '$ls_laptgllap'
                    ) vouc_count         
                from 
                (    
                     select 
                        c.kd_cbg, a.kd_klaim, c.no_penetapan, c.tgl_penetapan, c.no_klaim, c.sebab_klaim, c.tgl_klaim, c.tipe_klaim, 
                        c.kd_tk,c.nm_tk,c.kpjtk, c.kd_cbg_tk, c.kd_prs, replace(replace(replace(c.nm_prs,chr(10),''),chr(13),''),chr(14),'') nm_prs, 
                                                c.npp, c.kd_pry, replace(replace(replace(c.nama_proyek,chr(10),''),chr(13),''),chr(14),'') nama_proyek,         
                        a.kd_prg,(select nm_prg from sijstk.ms_prg where kd_prg = a.kd_prg) nm_prg, 
                        a.tipe_penerima,a.tgl_bayar,a.tgl_batal, 
                        b.nm_penerima, 
                        case when a.kd_prg in ('1','4') then 
                            case when  
                                (                     
                                    select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)+nvl(x.nilai6_disetujui,0)) 
                                    from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                    where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                    and x.kd_klaim = a.kd_klaim 
                                    and x.tipe_penerima = a.tipe_penerima 
                                    and y.kd_prg = a.kd_prg                                         
                                ) = 0 then 'JHT_PARTIAL'  
                            else 
                                'JHT_FULL'                             
                            end 
                        else 
                            'NON_JHT' 
                        end jns_jht,  
                        case when a.kd_prg in ('1','4') then 
                            ( 
                                select sum(nvl(x.nilai4_diajukan,0)) from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                and x.kd_klaim = a.kd_klaim 
                                and x.tipe_penerima = a.tipe_penerima 
                                and y.kd_prg = a.kd_prg                 
                            ) 
                        else 
                            0 
                        end klaim_jht_sebelumnya,                             
                        case when a.kd_prg in ('1','4') then 
                            (             
                                select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)) 
                                from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                and x.kd_klaim = a.kd_klaim 
                                and x.tipe_penerima = a.tipe_penerima 
                                and y.kd_prg = a.kd_prg                 
                            ) 
                        else 
                            0 
                        end nilai_bbnjaminan_jht,     
                        case when a.kd_prg in ('1','4') then 
                            (                 
                                select sum(nvl(x.nilai6_disetujui,0)) 
                                from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                and x.kd_klaim = a.kd_klaim 
                                and x.tipe_penerima = a.tipe_penerima 
                                and y.kd_prg = a.kd_prg                 
                            ) 
                        else 
                            0 
                        end nilai_hslpengembangan_jht,     
                        case when a.kd_prg in ('1','4') then 
                            (             
                                select sum(nvl(x.nilai4_disetujui,0)) 
                                from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                and x.kd_klaim = a.kd_klaim 
                                and x.tipe_penerima = a.tipe_penerima 
                                and y.kd_prg = a.kd_prg             
                            ) 
                        else 
                            0 
                        end pph_21, 
                        case when a.kd_prg in ('1','4') then 
                            case when  
                            ( 
                                select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)+nvl(x.nilai6_disetujui,0)) 
                                from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                and x.kd_klaim = a.kd_klaim 
                                and x.tipe_penerima = a.tipe_penerima 
                                and y.kd_prg = a.kd_prg                 
                            ) = 0  
                            then  
                                ( 
                                    select sum(nvl(x.nilai1_disetujui,0)+nvl(x.nilai4_disetujui,0)) 
                                    from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                    where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                    and x.kd_klaim = a.kd_klaim 
                                    and x.tipe_penerima = a.tipe_penerima 
                                    and y.kd_prg = a.kd_prg                     
                                )                  
                            else 
                                (                     
                                    select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)+nvl(x.nilai6_disetujui,0)-nvl(x.nilai4_disetujui,0)) 
                                    from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y 
                                    where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat 
                                    and x.kd_klaim = a.kd_klaim 
                                    and x.tipe_penerima = a.tipe_penerima 
                                    and y.kd_prg = a.kd_prg                     
                                )             
                            end 
                        else 
                            a.jml_bayar 
                        end jml_pokok_bayar, 
                        a.jml_bayar nilai_trans                                     
                    from sijstk.tx_klaim_tk_byr_prg a, sijstk.tx_klaim_tk_byr b, sijstk.tx_klaim_tk c 
                    where a.kd_klaim = b.kd_klaim(+) and a.tipe_penerima = b.tipe_penerima(+) and a.kd_klaim = c.kd_klaim 
                    and to_char(a.tgl_bayar,'yyyymmdd') between '$ls_laptgl1' and '$ls_laptgl2'
                    and nvl(to_char(a.tgl_batal,'yyyymmdd'),'30000101') > '$ls_laptgllap'
                    and to_char(a.tgl_bayar,'yyyymmdd') <= '$ls_laptgllap'
                    and a.no_pointer is not null 
                    and a.kd_prg like nvl('$ls_kd_prg','%') 
                    and c.kd_cbg in (select kode_kantor from sijstk.ms_kantor start with kode_kantor = '$ls_lap_kode_kantor' connect by prior kode_kantor=kode_kantor_induk)       
                )tt
                group by kd_cbg, kd_klaim, no_penetapan, tgl_penetapan, no_klaim, sebab_klaim, tgl_klaim, tipe_klaim, 
                    kd_tk, nm_tk, kpjtk, kd_cbg_tk, kd_prs, nm_prs, npp, kd_pry, nama_proyek  
            ) tt2 
)tt3
where nvl(tt3.kanal_pelayanan,'XXX') <> '40'";    
        //echo $sql;
        $DB->parse($sql); 
        $DB->execute();
        $somecontent = "KD_CBG;KD_KLAIM;NO_PENETAPAN;TGL_PENETAPAN;NO_KLAIM;SEBAB_KLAIM;TGL_KLAIM;TIPE_KLAIM;KD_TK;NM_TK;KPJTK;KD_CBG_TK;KD_PRS;NM_PRS;NPP;KD_PRY;NAMA_PROYEK;TGL_BAYAR;TGL_BATAL;SALDO_IURAN_JHT;HASIL_PENGEMBANGAN_JHT;PPH_21;JML_POKOK_BAYAR;JML_PEMBULATAN;JML_TRANSFER;VOUC_SALDO_IURAN_JHT;VOUC_HSLPENGEMBANGAN_JHT;VOUC_PPH_21;VOUC_POKOK_BAYAR;VOUC_PEMBULATAN;VOUC_TRANSFER;VOUC_COUNT".chr(13).chr(10);                           
        while ($row = $DB->nextrow())
        {   
        $lscsv_kd_cbg                            = $row["KD_CBG"];
        $lscsv_kd_klaim                      = $row["KD_KLAIM"];             
        $lscsv_no_penetapan              = $row["NO_PENETAPAN"];
        $lscsv_tgl_penetapan             = $row["TGL_PENETAPAN"];
        $lscsv_no_klaim                      = $row["NO_KLAIM"];
        $lscsv_sebab_klaim               = $row["SEBAB_KLAIM"];
        $lscsv_tgl_klaim                     = $row["TGL_KLAIM"];
        $lscsv_tipe_klaim                    = $row["TIPE_KLAIM"];
        $lscsv_kd_tk                             = $row["KD_TK"];
        $lscsv_nm_tk                             = $row["NM_TK"];
        $lscsv_kpjtk                             = $row["KPJTK"];
        $lscsv_kd_cbg_tk                     = $row["KD_CBG_TK"];
        $lscsv_kd_prs                            = $row["KD_PRS"];
        $lscsv_nm_prs                            = $row["NM_PRS"];
        $lscsv_npp                               = $row["NPP"];
        $lscsv_kd_pry                            = $row["KD_PRY"];
        $lscsv_nama_proyek               = $row["NAMA_PROYEK"];
        $lscsv_saldo_iuran_jht           = $row["SALDO_IURAN_JHT"];
        $lscsv_hasil_pengembangan_jht    = $row["HASIL_PENGEMBANGAN_JHT"];
        $lscsv_pph_21                                    = $row["PPH_21"];
        $lscsv_jml_pokok_bayar               = $row["JML_POKOK_BAYAR"];
        $lscsv_jml_pembulatan                    = $row["JML_PEMBULATAN"];
        $lscsv_jml_transfer                      = $row["JML_TRANSFER"];
        $lscsv_tgl_bayar                             = $row["TGL_BAYAR"];
        $lscsv_tgl_batal                             = $row["TGL_BATAL"];
        $lscsv_saldo_iuran_jht               = $row["VOUC_SALDO_IURAN_JHT"];
        $lscsv_vouc_hslpengembangan_jht  = $row["VOUC_HSLPENGEMBANGAN_JHT"];
        $lscsv_vouc_pph_21                           = $row["VOUC_PPH_21"];
                $lscsv_vouc_pokok_bayar                  = $row["VOUC_POKOK_BAYAR"];
        $lscsv_vouc_pembulatan                   = $row["VOUC_PEMBULATAN"];
        $lscsv_vouc_transfer                         = $row["VOUC_TRANSFER"];
                $lscsv_vouc_count                                = $row["VOUC_COUNT"];

                $somecontent .= $lscsv_kd_cbg.";".$lscsv_kd_klaim.";".$lscsv_no_penetapan.";".$lscsv_tgl_penetapan.";".$lscsv_no_klaim.";".$lscsv_sebab_klaim.";".$lscsv_tgl_klaim.";".$lscsv_tipe_klaim.";".$lscsv_kd_tk.";".$lscsv_nm_tk.";".$lscsv_kpjtk.";".$lscsv_kd_cbg_tk.";".$lscsv_kd_prs.";".$lscsv_nm_prs.";".$lscsv_npp.";".$lscsv_kd_pry.";".$lscsv_nama_proyek.";".$lscsv_tgl_bayar.";".$lscsv_tgl_batal.";".$lscsv_saldo_iuran_jht.";".$lscsv_hasil_pengembangan_jht.";".$lscsv_pph_21.";".$lscsv_jml_pokok_bayar.";".$lscsv_jml_pembulatan.";".$lscsv_jml_transfer.";".$lscsv_saldo_iuran_jht.";".$lscsv_vouc_hslpengembangan_jht.";".$lscsv_vouc_pph_21.";".$lscsv_vouc_pokok_bayar.";".$lscsv_vouc_pembulatan.";".$lscsv_vouc_transfer.";".$lscsv_vouc_count."".chr(13).chr(10);
        }   
        $ls_tgl       = $DB->get_data("select to_char(sysdate,'YYYYMMDDHH24MISS') from dual");
        $filename   = "KLAIM_BYR_JMO".$ls_tgl.".csv";
        $loc_server = "../../teks/";
        $upload     = $loc_server.$filename;
  
        if (file_exists($upload)){ 
                unlink($upload);
        }                                   
        $fp = fopen($upload,"a");
        $ok = fputs($fp,$somecontent);
        fclose($fp);
        
        if ($ok)
        { 
            echo "<script type=\"text/javascript\">".
            "window.open('dl.php?act=dl&file=$filename&loc_server=$loc_server');".
            "</script>";
        }
        $ls_error="1";//agar tidak memanggil fungsi report              
        }else
        {
            $ls_nama_rpt    .= "PNR603002JMO.rdf";
        }   
    }
	else if ($ls_jenis_laporan=="lap1") //Penetapan Siap Bayar
	{ 
		$sql = "select ".
           "     kd_cbg, kd_klaim, no_penetapan, tgl_penetapan, no_klaim, ".
					 "		 replace(replace(replace(replace(replace(replace(sebab_klaim,chr(10),''),chr(13),''),chr(14),''),chr(34),''),chr(39),''),';','') sebab_klaim, ".
					 "		 tgl_klaim, tipe_klaim, kd_tk, ".
					 "		 replace(replace(replace(replace(replace(replace(nm_tk,chr(10),''),chr(13),''),chr(14),''),chr(34),''),chr(39),''),';','') nm_tk, ".
					 "		 kpjtk, kd_cbg_tk, kd_prs, ".
					 "		 replace(replace(replace(replace(replace(replace(nm_prs,chr(10),''),chr(13),''),chr(14),''),chr(34),''),chr(39),''),';','') nm_prs, ".
					 "		 npp, kd_pry, ".
					 "		 replace(replace(replace(replace(replace(replace(nama_proyek,chr(10),''),chr(13),''),chr(14),''),chr(34),''),chr(39),''),';','') nama_proyek, ".        
           "     kd_prg, nm_prg, tipe_penerima, ".
					 "		 replace(replace(replace(replace(replace(replace(nm_penerima,chr(10),''),chr(13),''),chr(14),''),chr(34),''),chr(39),''),';','') nm_penerima, ".
           "     jns_jht, klaim_jht_sebelumnya, nilai_bbnjaminan_jht saldo_iuran_jht,nilai_hslpengembangan_jht hasil_pengembangan_jht, ".
           "     pph_21, jml_pokok_bayar, ".
           "     decode(jns_jht,'JHT_FULL',abs(nvl(nilai_trans,0)-(nvl(nilai_bbnjaminan_jht,0)+nvl(nilai_hslpengembangan_jht,0)-nvl(pph_21,0))), ".
           "                    'JHT_PARTIAL', abs(nvl(nilai_trans,0)-(nvl(jml_pokok_bayar,0)-nvl(pph_21,0))), ".
           "                    0) jml_pembulatan,  ".                   
           "     nilai_trans jml_transfer, ".
           "     tgl_bayar ".
           "from ".
           "(  ".  
           "      select ".
           "         c.kd_cbg, a.kd_klaim, c.no_penetapan, c.tgl_penetapan, c.no_klaim, c.sebab_klaim, c.tgl_klaim, c.tipe_klaim, ".
           "         c.kd_tk,c.nm_tk,c.kpjtk, c.kd_cbg_tk, c.kd_prs,c.nm_prs, c.npp, c.kd_pry, c.nama_proyek, ".        
           "         a.kd_prg,(select nm_prg from sijstk.ms_prg where kd_prg = a.kd_prg) nm_prg, ".
           "         a.tipe_penerima,a.tgl_bayar, ".
           "         b.nm_penerima, ".
           "         case when a.kd_prg in ('1','4') then ".
           "             case when  ".
           "                 (  ".                   
           "                     select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)+nvl(x.nilai6_disetujui,0)) ".
           "                     from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y ".
           "                     where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat ".
           "                     and x.kd_klaim = a.kd_klaim ".
           "                     and x.tipe_penerima = a.tipe_penerima ".
           "                     and y.kd_prg = a.kd_prg  ".                                       
           "                 ) = 0 then 'JHT_PARTIAL' ". 
           "             else ".
           "                 'JHT_FULL' ".                            
           "             end ".
           "         else ".
           "             'NON_JHT' ".
           "         end jns_jht,  ".
           "         case when a.kd_prg in ('1','4') then ".
           "             ( ".
           "                 select sum(nvl(x.nilai4_diajukan,0)) from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y ".
           "                 where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat ".
           "                 and x.kd_klaim = a.kd_klaim ".
           "                 and x.tipe_penerima = a.tipe_penerima ".
           "                 and y.kd_prg = a.kd_prg  ".               
           "             ) ".
           "         else ".
           "             0 ".
           "         end klaim_jht_sebelumnya, ".                            
           "         case when a.kd_prg in ('1','4') then ".
           "             ( ".            
           "                 select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)) ".
           "                 from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y ".
           "                 where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat ".
           "                 and x.kd_klaim = a.kd_klaim ".
           "                 and x.tipe_penerima = a.tipe_penerima ".
           "                 and y.kd_prg = a.kd_prg ".                
           "             ) ".
           "         else ".
           "             0 ".
           "         end nilai_bbnjaminan_jht, ".    
           "         case when a.kd_prg in ('1','4') then ".
           "             (  ".               
           "                 select sum(nvl(x.nilai6_disetujui,0)) ".
           "                 from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y ".
           "                 where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat ".
           "                 and x.kd_klaim = a.kd_klaim ".
           "                 and x.tipe_penerima = a.tipe_penerima ".
           "                 and y.kd_prg = a.kd_prg ".                
           "             ) ".
           "         else ".
           "             0 ".
           "         end nilai_hslpengembangan_jht, ".    
           "         case when a.kd_prg in ('1','4') then ".
           "             ( ".            
           "                 select sum(nvl(x.nilai4_disetujui,0)) ".
           "                 from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y ".
           "                 where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat ".
           "                 and x.kd_klaim = a.kd_klaim ".
           "                 and x.tipe_penerima = a.tipe_penerima ".
           "                 and y.kd_prg = a.kd_prg ".            
           "             ) ".
           "         else ".
           "             0 ".
           "         end pph_21, ".
           "         case when a.kd_prg in ('1','4') then ".
           "             case when  ".
           "             ( ".
           "                 select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)+nvl(x.nilai6_disetujui,0)) ".
           "                 from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y ".
           "                 where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat ".
           "                 and x.kd_klaim = a.kd_klaim ".
           "                 and x.tipe_penerima = a.tipe_penerima ".
           "                 and y.kd_prg = a.kd_prg ".                
           "             ) = 0  ".
           "             then  ".
           "                 ( ".
           "                     select sum(nvl(x.nilai1_disetujui,0)+nvl(x.nilai4_disetujui,0)) ".
           "                     from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y ".
           "                     where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat ".
           "                     and x.kd_klaim = a.kd_klaim ".
           "                     and x.tipe_penerima = a.tipe_penerima ".
           "                     and y.kd_prg = a.kd_prg ".                    
           "                 ) ".                 
           "             else ".
           "                 ( ".                    
           "                     select sum(nvl(x.nilai2_disetujui,0)+nvl(x.nilai3_disetujui,0)+nvl(x.nilai6_disetujui,0)-nvl(x.nilai4_disetujui,0)) ".
           "                     from sijstk.tx_klaim_tk_mnf_dtl x, sijstk.tx_klaim_tk_mnf y ".
           "                     where x.kd_klaim = y.kd_klaim and x.kd_manfaat = y.kd_manfaat ".
           "                     and x.kd_klaim = a.kd_klaim ".
           "                     and x.tipe_penerima = a.tipe_penerima ".
           "                     and y.kd_prg = a.kd_prg ".                    
           "                 ) ".            
           "             end ".
           "         else ".
           "             a.jml_bayar ".
           "         end jml_pokok_bayar, ".
           "         a.jml_bayar nilai_trans ".
           "     from sijstk.tx_klaim_tk_byr_prg a, sijstk.tx_klaim_tk_byr b, sijstk.tx_klaim_tk c ".
           "     where a.kd_klaim = b.kd_klaim(+) and a.tipe_penerima = b.tipe_penerima(+) and a.kd_klaim = c.kd_klaim ".
           "     and to_char(c.tgl_penetapan,'yyyymmdd') between '$ls_laptgl1' and '$ls_laptgl2' ".
           "     and nvl(to_char(a.tgl_bayar,'yyyymmdd'),'30000101') > '$ls_laptgllap' and a.no_pointer is null ".
					 "		 and a.kd_prg like nvl('$ls_kd_prg','%') ".
           "     and c.kd_cbg in (select kode_kantor from sijstk.ms_kantor start with kode_kantor = '$ls_lap_kode_kantor' connect by prior kode_kantor=kode_kantor_induk)  ".     
           ") ";	
  	//echo $sql;
		$DB->parse($sql); 
  	$DB->execute();
		$somecontent = "KD_CBG;TIPE_BYR;KD_KLAIM;NO_PENETAPAN;TGL_PENETAPAN;NO_KLAIM;SEBAB_KLAIM;TGL_KLAIM;TIPE_KLAIM;KD_TK;NM_TK;KPJTK;KD_CBG_TK;KD_PRS;NM_PRS;NPP;KD_PRY;NAMA_PROYEK;KD_PRG;NM_PRG;TIPE_PENERIMA;NM_PENERIMA;JNS_JHT;KLAIM_JHT_SEBELUMNYA;SALDO_IURAN_JHT;HASIL_PENGEMBANGAN_JHT;PPH_21;JML_POKOK_BAYAR;JML_PEMBULATAN;JML_TRANSFER;TGL_BAYAR".chr(13).chr(10);							
  	while ($row = $DB->nextrow())
  	{	
      $lscsv_kd_cbg										 = $row["KD_CBG"];
      $lscsv_tipe_byr									 = $row["TIPE_BYR"];
      $lscsv_kd_klaim									 = $row["KD_KLAIM"];
      $lscsv_no_penetapan							 = $row["NO_PENETAPAN"];
      $lscsv_tgl_penetapan						 = $row["TGL_PENETAPAN"];
      $lscsv_no_klaim									 = $row["NO_KLAIM"];
      $lscsv_sebab_klaim							 = $row["SEBAB_KLAIM"];
      $lscsv_tgl_klaim								 = $row["TGL_KLAIM"];
      $lscsv_tipe_klaim 							 = $row["TIPE_KLAIM"];
      $lscsv_kd_tk										 = $row["KD_TK"];
      $lscsv_nm_tk										 = $row["NM_TK"];
      $lscsv_kpjtk										 = $row["KPJTK"];
      $lscsv_kd_cbg_tk								 = $row["KD_CBG_TK"];
      $lscsv_kd_prs										 = $row["KD_PRS"];
      $lscsv_nm_prs										 = $row["NM_PRS"];
      $lscsv_npp											 = $row["NPP"];
      $lscsv_kd_pry										 = $row["KD_PRY"];
      $lscsv_nama_proyek							 = $row["NAMA_PROYEK"];
      $lscsv_kd_prg										 = $row["KD_PRG"];
      $lscsv_nm_prg										 = $row["NM_PRG"];
      $lscsv_tipe_penerima						 = $row["TIPE_PENERIMA"];
      $lscsv_nm_penerima							 = $row["NM_PENERIMA"];
      $lscsv_jns_jht									 = $row["JNS_JHT"];
      $lscsv_klaim_jht_sebelumnya			 = $row["KLAIM_JHT_SEBELUMNYA"];
      $lscsv_saldo_iuran_jht					 = $row["SALDO_IURAN_JHT"];
      $lscsv_hasil_pengembangan_jht		 = $row["HASIL_PENGEMBANGAN_JHT"];
      $lscsv_pph_21										 = $row["PPH_21"];
      $lscsv_jml_pokok_bayar					 = $row["JML_POKOK_BAYAR"];
      $lscsv_jml_pembulatan						 = $row["JML_PEMBULATAN"];
      $lscsv_jml_transfer							 = $row["JML_TRANSFER"];
			$lscsv_tgl_bayar							 	 = $row["TGL_BAYAR"];

			$somecontent .= $lscsv_kd_cbg.";".$lscsv_tipe_byr.";".$lscsv_kd_klaim.";".$lscsv_no_penetapan.";".$lscsv_tgl_penetapan.";".$lscsv_no_klaim.";".$lscsv_sebab_klaim.";".$lscsv_tgl_klaim.";".$lscsv_tipe_klaim.";".$lscsv_kd_tk.";".$lscsv_nm_tk.";".$lscsv_kpjtk.";".$lscsv_kd_cbg_tk.";".$lscsv_kd_prs.";".$lscsv_nm_prs.";".$lscsv_npp.";".$lscsv_kd_pry.";".$lscsv_nama_proyek.";".$lscsv_kd_prg.";".$lscsv_nm_prg.";".$lscsv_tipe_penerima.";".$lscsv_nm_penerima.";".$lscsv_jns_jht.";".$lscsv_klaim_jht_sebelumnya.";".$lscsv_saldo_iuran_jht.";".$lscsv_hasil_pengembangan_jht.";".$lscsv_pph_21.";".$lscsv_jml_pokok_bayar.";".$lscsv_jml_pembulatan.";".$lscsv_jml_transfer.";".$lscsv_tgl_bayar."".chr(13).chr(10);
		}  	
  	$ls_tgl		  = $DB->get_data("select to_char(sysdate,'YYYYMMDDHH24MISS') from dual");
  	$filename 	= "KLAIM_SIAPBYR".$ls_tgl.".csv";
  	$loc_server	= "../../teks/";
  	$upload 	= $loc_server.$filename;

  	if (file_exists($upload)){ 
  			unlink($upload);
  	}									
  	$fp = fopen($upload,"a");
  	$ok = fputs($fp,$somecontent);
  	fclose($fp);
		
  	if ($ok)
  	{ 
  		echo "<script type=\"text/javascript\">".
  		"window.open('dl.php?act=dl&file=$filename&loc_server=$loc_server');".
  		"</script>";
  	}
		$ls_error="1";//agar tidak memanggil fungsi report 					
	}	
  else if ($ls_jenis_laporan=="lap6") //Pembayaran Manfaat
  {
    $ls_nama_rpt 	.= "HUR202302002.rdf";
    $ls_modul = "lk";  

    $sql = "select to_char(to_date('$ld_tgl1','dd/mm/yyyy'),'yyyymmdd') tgl1, ".
      "			 to_char(to_date('$ld_tgl2','dd/mm/yyyy'),'yyyymmdd') tgl2 from dual";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_laptgl1 		= $row["TGL1"];						
    $ls_laptgl2 		= $row["TGL2"];

    if ($ls_kd_prg=='998') {
      $ls_kd_prg = '';
    }

    $ls_user_param = " P_KODE_KANTOR=$ls_kode_kantor"; 
    $ls_user_param .= " P_PERIODE1=$ls_laptgl1"; 
    $ls_user_param .= " P_PERIODE2=$ls_laptgl2"; 
    $ls_user_param .= " P_KODE_USER=$username"; 
    $ls_user_param .= " P_KD_PRG=$ls_kd_prg"; 
    $ls_user_param .= " P_KODE_SEGMEN=$ls_kode_segmen";     
    $ls_user_param .= " P_KODE_BANK=$ls_kd_bank";     
  } 
  else if ($ls_jenis_laporan=="lap7") //MONITORING PEMBAYARAN KLAIM SENTRALISASI
  {
    $ls_nama_rpt 	.= "HUR202302003.rdf";
    $ls_modul = "lk";  

    $sql = "select to_char(to_date('$ld_tgl1','dd/mm/yyyy'),'dd-mm-yyyy') tgl1, ".
      "			 to_char(to_date('$ld_tgl2','dd/mm/yyyy'),'dd-mm-yyyy') tgl2 from dual";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_laptgl1 		= $row["TGL1"];						
    $ls_laptgl2 		= $row["TGL2"];

    if ($ls_kd_prg=='998') {
      $ls_kd_prg = '';
    }

    $ls_user_param = " P_KODE_KANTOR=$ls_kode_kantor"; 
    $ls_user_param .= " P_TGL_AWAL=$ls_laptgl1"; 
    $ls_user_param .= " P_TGL_AKHIR=$ls_laptgl2"; 
    $ls_user_param .= " P_KODE_USER=$username"; 
    $ls_user_param .= " P_KD_PRG=$ls_kd_prg"; 
    $ls_user_param .= " P_KODE_SEGMEN=$ls_kode_segmen";     
  }
  else if ($ls_jenis_laporan=="lap8") //PEMBAYARAN KLAIM PLKK
  {
    $ls_nama_rpt 	.= "HUR202302004.rdf";
    $ls_modul = "lk"; 
    $ls_user_param = "P_NO_REF_TRF_PLKK=$noref"; 
    $ls_user_param .= " P_KODE_USER=$username"; 
  } 
  else if ($ls_jenis_laporan=="lap9") //Laporan Estimasi Pembayaran Jaminan dan Biaya Operasional Nasional
  {
    $ls_nama_rpt 	.= "HUR202302005.rdf";
    $ls_modul = "lk"; 
    $sql = "select to_char(to_date('$ld_tgl1','dd/mm/yyyy'),'yyyymmdd') tgl1, ".
    "			 to_char(to_date('$ld_tgl2','dd/mm/yyyy'),'yyyymmdd') tgl2 from dual";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_laptgl1 		= $row["TGL1"];						
    $ls_laptgl2 		= $row["TGL2"];
    $ls_user_param = "P_TGLDATA1=$ls_laptgl1"; 
    $ls_user_param .= " P_TGLDATA2=$ls_laptgl2"; 
    $ls_user_param .= " P_KODE_USER=$username"; 
  }
						
	/*END SET LAPORAN YANG AKAN DIPANGGIL ----------------------------------*/
	if ($ls_error=="1")
	{}
	else
	{							
		//echo $ls_user_param;
		 
    exec_rpt_enc_new(1,$ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);			
	}
}	
//--------------------- end button action ------------------------------------
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript"></script>		
<?	
//--------------------- end fungsi lokal javascript --------------------------	
?>

<?php
//--------------------- validasi ajax ----------------------------------------
?>	
<script type="text/javascript" src="../../javascript/validator.js"></script>
<script type="text/javascript" src="../../javascript/ajax.js"></script>

<script type="text/javascript">
//Create validator object
var validator = new formValidator();
var ajax = new sack();
//ambil nilai previous, dibandingkan dg nilai current, apabila berbeda maka ajax akan dijalankan
var curr_kode_kantor	=<?php echo ($ls_kode_kantor=='') ? 'false' : "'".$ls_kode_kantor."'"; ?>;
var curr_kd_prg	=<?php echo ($ls_kd_prg=='') ? 'false' : "'".$ls_kd_prg."'"; ?>;
var curr_kode_periode	=<?php echo ($ls_kode_periode=='') ? 'false' : "'".$ls_kode_periode."'"; ?>;
var curr_tgl1	=<?php echo ($ld_tgl1=='') ? 'false' : "'".$ld_tgl1."'"; ?>;
var curr_tgl2	=<?php echo ($ld_tgl2=='') ? 'false' : "'".$ld_tgl2."'"; ?>;

function downloadLaporan() {
  let kd_prg = document.getElementById('kd_prg').value;
  let tipe_token = document.querySelector('input[name="rad_tipe_token"]:checked')
  tipe_token = tipe_token != null ? tipe_token.value : "";
  let bg_ul = document.getElementById('bg_ul').value;

  let params = '';
  if (tipe_token == 'NON_TOKEN') {
    params += '&tipe_download=' + "JHT_NON_TOKEN_EXCEL";
    params += '&bg_ul=' + window.document.getElementById('bg_ul').value;
  } else {
    params += '&tipe_download=' + "EXCEL";
  }
  params += '&tgl1=' + window.document.getElementById('tgl1').value;
  params += '&tgl2=' + window.document.getElementById('tgl2').value;
  params += '&tgllap=' + window.document.getElementById('tgllap').value;
  params += '&kode_kantor=' + window.document.getElementById('kode_kantor').value;
  params += '&kd_prg=' + window.document.getElementById('kd_prg').value;
  params += '&metode_byr=' + window.document.getElementById('metode_byr').value;
  params += '&kode_buku=' + window.document.getElementById('kode_buku').value;

  location.href = '../ajax/pn5048_lapbyrklaim_download.php?' + params;
  }

function f_ajax_val_kode_kantor()
{
  var c_kode_kantor		= window.document.getElementById('kode_kantor').value;
  var c_kd_prg				= window.document.getElementById('kd_prg').value;
			
  if (c_kode_kantor!="" && c_kode_kantor != curr_kode_kantor){	
		document.getElementById('kode_buku').options.length = 0; 	
  	ajax.requestFile = '../ajax/pn5048_lapbyrklaim_ajax.php?getClientId=f_ajax_val_kode_kantor&c_kode_kantor='+c_kode_kantor+'&c_kd_prg='+c_kd_prg;
  	ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
  	ajax.runAJAX();	// Execute AJAX function
  	curr_kode_kantor = c_kode_kantor;
  }
}
function f_ajax_val_kd_prg()
{
  var c_kode_kantor		= window.document.getElementById('kode_kantor').value;
  var c_kd_prg				= window.document.getElementById('kd_prg').value;
			
  if (c_kd_prg != curr_kd_prg){		
  	document.getElementById('kode_buku').options.length = 0;
		ajax.requestFile = '../ajax/pn5048_lapbyrklaim_ajax.php?getClientId=f_ajax_val_kd_prg&c_kode_kantor='+c_kode_kantor+'&c_kd_prg='+c_kd_prg;
  	ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
  	ajax.runAJAX();	// Execute AJAX function
  	curr_kd_prg = c_kd_prg;
  }

  if (c_kd_prg == "1") { // JHT
    document.getElementById('div_tipe_token').style.display = 'block';
    document.getElementById('div_bg_ul').style.display = 'none';
  } else {
    document.getElementById('div_tipe_token').style.display = 'none';
    document.getElementById('div_bg_ul').style.display = 'none';
  }

  var arr_temp_rad_tipe_token = document.getElementsByName("rad_tipe_token");
  arr_temp_rad_tipe_token.forEach(function (rad, index) {
    rad.checked = false;
  });
  
  document.getElementById('div_tgl2').style.display = 'inline-block';
}
function tipe_token_change(){
  let c_tipe_token = document.querySelector('input[name="rad_tipe_token"]:checked')
  c_tipe_token = c_tipe_token != null ? c_tipe_token.value : "";
  if (c_tipe_token == "NON_TOKEN") {
    document.getElementById('div_bg_ul').style.display = 'block';
    document.getElementById('div_tgl2').style.display = 'none';
  } else {
    document.getElementById('div_bg_ul').style.display = 'none';
    document.getElementById('div_tgl2').style.display = 'inline-block';
  }
  document.getElementById('bg_ul').value = '';
}

window.addEventListener('load', function() {
  jenis_laporan_change(); 
}, false);

function jenis_laporan_change(){
  var c_jenis_laporan			= window.document.getElementById('jenis_laporan').value;	
  if (c_jenis_laporan=="lap6") { // Pembayaran Manfaat 
    document.getElementById('fr_noref').style.display = 'none';
    document.getElementById('fr_kkantor').style.display = 'block';
    document.getElementById('fr_ksegmen').style.display = 'block';
    document.getElementById('fr_kprogram').style.display = 'block';
    document.getElementById('fr_tglbayar').style.display = 'block';
    document.getElementById('fr_kbuku').style.display = 'none';
    document.getElementById('fr_pos').style.display = 'none';
    document.getElementById('fr_metodebayar').style.display = 'none';
    document.getElementById('fr_kbank').style.display = 'block';
    document.getElementById('div_tipe_token').style.display = 'none';
    document.getElementById('div_bg_ul').style.display = 'none';
    document.getElementById('btncetak_excel').style.display = 'none';
  } else if (c_jenis_laporan=="lap7") { // Klaim sentralisasi
    document.getElementById('fr_noref').style.display = 'none';
    document.getElementById('fr_kkantor').style.display = 'block';
    document.getElementById('fr_ksegmen').style.display = 'none';
    document.getElementById('fr_kprogram').style.display = 'none';
    document.getElementById('fr_tglbayar').style.display = 'block';
    document.getElementById('fr_kbuku').style.display = 'none';
    document.getElementById('fr_pos').style.display = 'none';
    document.getElementById('fr_metodebayar').style.display = 'none';
    document.getElementById('fr_kbank').style.display = 'none';
    document.getElementById('div_tipe_token').style.display = 'none';
    document.getElementById('div_bg_ul').style.display = 'none';
    document.getElementById('btncetak_excel').style.display = 'none';
  } else if (c_jenis_laporan=="lap8") { // Lampiran PLKK
    document.getElementById('fr_noref').style.display = 'block';
    document.getElementById('fr_kkantor').style.display = 'none';
    document.getElementById('fr_ksegmen').style.display = 'none';
    document.getElementById('fr_kprogram').style.display = 'none';
    document.getElementById('fr_kbuku').style.display = 'none';
    document.getElementById('fr_tglbayar').style.display = 'none';
    document.getElementById('fr_pos').style.display = 'none';
    document.getElementById('fr_metodebayar').style.display = 'none';
    document.getElementById('fr_kbank').style.display = 'none';
    document.getElementById('div_tipe_token').style.display = 'none';
    document.getElementById('div_bg_ul').style.display = 'none';
    document.getElementById('btncetak_excel').style.display = 'none';
  } else if (c_jenis_laporan=="lap9") { // Laporan Estimasi Pembayaran Jaminan dan Biaya Operasional Nasional
    document.getElementById('fr_noref').style.display = 'none';
    document.getElementById('fr_kkantor').style.display = 'none';
    document.getElementById('fr_ksegmen').style.display = 'none';
    document.getElementById('fr_kprogram').style.display = 'none';
    document.getElementById('fr_kbuku').style.display = 'none';
    document.getElementById('fr_tglbayar').style.display = 'block';
    document.getElementById('fr_pos').style.display = 'none';
    document.getElementById('fr_metodebayar').style.display = 'none';
    document.getElementById('fr_kbank').style.display = 'none';
    document.getElementById('div_tipe_token').style.display = 'none';
    document.getElementById('div_bg_ul').style.display = 'none';
    document.getElementById('btncetak_excel').style.display = 'none';
  } else {
    document.getElementById('fr_noref').style.display = 'none';
    document.getElementById('fr_kkantor').style.display = 'block';
    document.getElementById('fr_ksegmen').style.display = 'none';
    document.getElementById('fr_kprogram').style.display = 'block';
    document.getElementById('fr_kbuku').style.display = 'block';
    document.getElementById('fr_tglbayar').style.display = 'block';
    document.getElementById('fr_pos').style.display = 'block';
    document.getElementById('fr_metodebayar').style.display = 'block';
    document.getElementById('fr_kbank').style.display = 'none';
    document.getElementById('btncetak_excel').style.display = 'inline-block';
  }
}

function showClientData()
{
  var formObj = document.adminForm;
  eval(ajax.response);
  var st_errorval  = window.document.getElementById("st_errval").value;
  var st_errorval1 = window.document.getElementById("st_errval1").value;
  var st_errorval2 = window.document.getElementById("st_errval2").value;
  var st_errorval3 = window.document.getElementById("st_errval3").value;
  
  //tampilan error jika kode jurnal tidak valid
  if (st_errorval == 1)
  {  
  	window.document.getElementById("dispError").innerHTML = "(* Kode kantor tidak sesuai ..!!!";
  	window.document.getElementById("dispError").style.display = 'block';
  	window.document.getElementById('kode_kantor').focus();
  }else{
  	window.document.getElementById("dispError").style.display = 'none';
  }																											
}
</script>			
<?php
//--------------------- end validasi ajax ------------------------------------
?>

<table class="captionfoxrm">
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
  </style>		
  <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?> <?=$ls_subtitle;?></h3></td></tr>	
</table>

<?	
//Nilai Default --------------------------------------------------------------
//1. Kode Kantor, isikan dengan global.kantor_aktif		
	$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;
	if($ls_kode_kantor=="")
	{
		$ls_kode_kantor =  $gs_kantor_aktif;
	}

  if($ls_jenis_laporan=="")
  {
  	$ls_jns_lap = "lapl";
  }	
	
	if ($ld_tgl1 == "")
	{
    $sql = 	"select to_char(trunc(sysdate,'mm'),'dd/mm/yyyy') as tgl1, to_char(sysdate,'dd/mm/yyyy') as tgl2, ".
    		 		"to_char(sysdate,'dd/mm/yyyy') as tgllap from dual ";		
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ld_tgl1 = $row["TGL1"];	
		$ld_tgl2 = $row["TGL2"];
		$ld_tgllap = $row["TGLLAP"];
		//$ls_kode_periode = $row["PERIODE"];
	}																
//End Nilai Default ----------------------------------------------------------
?>				
<div id="formframe" style="width:650px;">
	<span id="dispError" style="display:none;color:red"></span>
  <input type="hidden" id="st_errval" name="st_errval">
	<span id="dispError1" style="display:none;color:red"></span>
  <input type="hidden" id="st_errval1" name="st_errval1">	
	<span id="dispError2" style="display:none;color:red"></span>
  <input type="hidden" id="st_errval2" name="st_errval2">	
	<span id="dispError3" style="display:none;color:red"></span>
  <input type="hidden" id="st_errval3" name="st_errval3">	
	
	<div id="formKiri" style="width:650px;">
		<fieldset style="width:650px;"><legend>Parameter Laporan</legend>
			</br>
																					
			<div class="form-row_kiri" id="fr_kkantor">
			  <label style = "text-align:right;">Kantor &nbsp;&nbsp;</label>
				<select size="1" id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>" tabindex="1" class="select_format" onchange="f_ajax_val_kd_prg();" style="width:350px;">
				<option value="">-- Pilih Kantor --</option>
				<? 
				$sql = "select kode_kantor, nama_kantor from sijstk.ms_kantor ".    									 	 
				"start with kode_kantor = '$gs_kantor_aktif' ".
				"connect by prior kode_kantor = kode_kantor_induk";
				$DB->parse($sql);
				$DB->execute();
				while($row = $DB->nextrow())
				{
				echo "<option ";
				if ($row["KODE_KANTOR"]==$ls_kode_kantor && strlen($ls_kode_kantor)==strlen($row["KODE_KANTOR"])){ echo " selected"; }
				echo " value=\"".$row["KODE_KANTOR"]."\">".$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"]."</option>";
				}
				?>
				</select>															 								
			</div>
			<div class="clear"></div>
			<div class="clear"></div>

			<div class="form-row_kiri">
  		  <label style = "text-align:right;">Jenis Laporan &nbsp;&nbsp;&nbsp;</label>
        <select size="1" id="jenis_laporan" name="jenis_laporan" class="select_format" tabindex="2"  style="width:350px;"  onchange="jenis_laporan_change()">
						<? 
							switch($ls_jenis_laporan)
							{
								case 'lap1' : $ls_jenis_laporan1="selected"; break;
								case 'lap2' : $ls_jenis_laporan2="selected"; break;
                case 'lap4' : $ls_jenis_laporan4="selected"; break;
                case 'lap6' : $ls_jenis_laporan6="selected"; break;
                case 'lap7' : $ls_jenis_laporan7="selected"; break;
                case 'lap8' : $ls_jenis_laporan8="selected"; break;
                case 'lap9' : $ls_jenis_laporan9="selected"; break;
							}
						?>
						<!--<option value="lap1" <?=$ls_jenis_laporan1;?>>Penetapan Siap Bayar</option>-->
						<option value="lap2" <?=$ls_jenis_laporan2;?>>Pembayaran Jaminan</option>
            <option value="lap4" <?=$ls_jenis_laporan4;?>>Pembayaran Jaminan JMO</option>   
            <option value="lap6" <?=$ls_jenis_laporan6;?>>Laporan Pembayaran Manfaat</option>
            <option value="lap7" <?=$ls_jenis_laporan7;?>>Monitoring Pembayaran Klaim Sentralisasi</option>
            <option value="lap8" <?=$ls_jenis_laporan8;?>>Lampiran Pembayaran Klaim PLKK</option>
            <option value="lap9" <?=$ls_jenis_laporan9;?>>Laporan Estimasi Pembayaran Jaminan dan Biaya Operasional Nasional</option>
				</select>
  		</div>
			<div class="clear"></div>

			<div class="form-row_kiri" id="fr_noref" style="display:none">
  		  <label style = "text-align:right;">No Ref. Transfer PLKK &nbsp;&nbsp;&nbsp;</label>
  				<input id="noref" name="noref" value="<?=$noref;?>" style="width:340px;" />
  		</div>
			<div class="clear"></div>


			<div class="form-row_kiri" id="fr_ksegmen" style="display:none">
  		<label style = "text-align:right;">Kode Segmen &nbsp;&nbsp;&nbsp;</label>
  				<select size="1" id="kode_segmen" name="kode_segmen" class="select_format" tabindex="2"  style="width:350px;"  onchange="jenis_laporan_change()">
            <? 
							switch($_POST["kode_segmen"])
							{
								case 'PU' : $ls_kode_segmen_pu="selected"; break;
								case 'BPU' : $ls_kode_segmen_bpu="selected"; break;
                case 'EJAKON' : $ls_kode_segmen_ejakon="selected"; break;
                case 'TKI' : $ls_kode_segmen_pmi="selected"; break;
							}
						?>
            <option value="">-- Pilih Segmen --</option>
            <option value="PU" <?=$ls_kode_segmen_pu;?>>PU</option>
            <option value="BPU" <?=$ls_kode_segmen_bpu;?>>BPU</option>   
            <option value="EJAKON" <?=$ls_kode_segmen_ejakon;?>>EJAKON</option>   
						<option value="TKI" <?=$ls_kode_segmen_pmi;?>>PMI</option>            																						
				</select>
  		</div>
			<div class="clear"></div>
						
			<div class="form-row_kiri" id="fr_kprogram">
			<label style = "text-align:right;">Program &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
				<select size="1" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>" tabindex="3" class="select_format" onchange="f_ajax_val_kd_prg();" style="width:350px;">
				<option value="">-- Pilih Program --</option>
				<? 
				$sql = "select kd_prg, nm_prg from sijstk.ms_prg where kd_prg <> 999 order by kd_prg";
				$DB->parse($sql);
				$DB->execute();
				while($row = $DB->nextrow())
				{
				echo "<option ";
				if ($row["KD_PRG"]==$ls_kd_prg && strlen($ls_kd_prg)==strlen($row["KD_PRG"])){ echo " selected"; }
				echo " value=\"".$row["KD_PRG"]."\">".$row["KD_PRG"]." - ".$row["NM_PRG"]."</option>";
				}
				?>
				</select>															 								
			</div>
			<div class="clear"></div>	

			<div class="form-row_kiri" id="fr_kbank" style="display: none">
			<label style = "text-align:right;">Bank &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
				<select size="1" id="kd_bank" name="kd_bank" value="<?=$ls_kd_bank;?>" tabindex="3" class="select_format" style="width:350px;">
          <? 
							switch($_POST["kd_bank"])
							{
								case '010' : $ls_kd_bank_mdr="selected"; break;
								case '020' : $ls_kd_bank_bri="selected"; break;
                case '030' : $ls_kd_bank_bni="selected"; break;
                case '050' : $ls_kd_bank_bca="selected"; break;
							}
          ?>
          <option value="">-- Pilih Bank --</option>
          <option value="010" <?=$ls_kd_bank_mdr;?>>Mandiri</option>
          <option value="020" <?=$ls_kd_bank_bri;?>>BRI</option>
          <option value="030" <?=$ls_kd_bank_bni;?>>BNI</option>
          <option value="050" <?=$ls_kd_bank_bca;?>>BCA</option>      
				</select>															 								
			</div>
			<div class="clear"></div>	


      <div class="form-row_kiri" id="div_tipe_token" style="display: none">
			  <label style="text-align:right;">&nbsp;</label>
        <input type="radio" name="rad_tipe_token" value="TOKEN" onchange="tipe_token_change()"><span>Token</span>
        <input type="radio" name="rad_tipe_token" value="NON_TOKEN" onchange="tipe_token_change()"><span>Non Token</span>
			</div>
			<div class="clear"></div>	

      <div class="form-row_kiri" id="div_bg_ul" style="display: none;">	
        <label style = "text-align:right;">Bilyet Giro (BG) UL &nbsp;&nbsp;&nbsp;&nbsp; </label>
					<input type="text" name="bg_ul" id="bg_ul" size="16" value=""> 
		  </div>
    	<div class="clear"></div>	

			<div class="form-row_kiri" id="fr_metodebayar">
  		<label style = "text-align:right;">Metode Bayar &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>			
        <select size="1" id="metode_byr" name="metode_byr" value="<?=$ls_metode_byr;?>" class="select_format" tabindex="4"  style="width:350px;">
        <option value="">-- Pilih Metode Bayar --</option>
        <? 
        $sql = "select kode,keterangan from sijstk.ms_lookup where tipe='KLMCRBYR' and nvl(aktif,'T')='Y' order by seq";
        $DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
        echo "<option ";
				if ($row["KODE"]==$ls_metode_byr && strlen($ls_metode_byr)==strlen($row["KODE"])){ echo " selected"; }
        echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
        }
        ?>
        </select>
  		</div>
			<div class="clear"></div>
			
      <div class="form-row_kiri" id="fr_kbuku">
      <label style = "text-align:right;">Kode Buku &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <select size="1" id="kode_buku" name="kode_buku" value="<?=$ls_kode_buku;?>" tabindex="5" class="select_format"  style="width:320px;">
        <option value="">-- Pilih Kode Buku --</option>
        <? 
        $sql = "select distinct kode_buku, (select nama_rekening from sijstk.ms_rekening where kode_kantor = a.kode_kantor and kode_buku = a.kode_buku and rownum = 1) nama_rekening ". 
               "from sijstk.ms_rekening a ".
               "where kode_kantor in ".
               "( ".
               "    select kode_kantor from sijstk.ms_kantor ".
               "    start with kode_kantor = '$ls_kode_kantor' ".
               "    connect by prior kode_kantor = kode_kantor_induk ".
               ") ".
               "union all ".
               "select '00000' kode_buku, null nama_rekening from dual ".
               "order by kode_buku";
        $DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
        echo "<option ";
        if ($row["KODE_BUKU"]==$ls_kode_buku && strlen($ls_kode_buku)==strlen($row["KODE_BUKU"])){ echo " selected"; }
        echo " value=\"".$row["KODE_BUKU"]."\">".$row["KODE_BUKU"]." - ".$row["NAMA_REKENING"]."</option>";
        }
        ?>
        </select>															 								
      </div>
      <div class="clear"></div>			
																																									
			<div class="form-row_kiri" id="fr_tglbayar">	
      	<label style = "text-align:right;">Tgl Pembayaran &nbsp;&nbsp;</label>
					<input type="text" name="tgl1" id="tgl1" size="16" onblur="convert_date(tgl1);"  value="<?=$ld_tgl1;?>" tabindex="6"> 
					<input id="btn_tgl1" type="image" align="top" onclick="return showCalendar('tgl1', 'dd-mm-y');" src="../../images/calendar.gif" />
					<div id="div_tgl2" style="display: inline-block">
          &nbsp; s/d &nbsp;
					<input type="text" name="tgl2" id="tgl2" onblur="convert_date(tgl2);" size="16" value="<?=$ld_tgl2;?>" tabindex="5">
					<input id="btn_tgl2" type="image" align="top" onclick="return showCalendar('tgl2', 'dd-mm-y');" src="../../images/calendar.gif" />
          </div>
		  </div> 			
    	<div class="clear"></div>	

			<div class="form-row_kiri" id="fr_pos">	
        <label style = "text-align:right;">Posisi Per Tgl</label>
					<input type="text" name="tgllap" id="tgllap" size="16" onblur="convert_date(tgllap);"  value="<?=$ld_tgllap;?>" tabindex="7"> 
					<input id="btn_tgllap" type="image" align="top" onclick="return showCalendar('tgllap', 'dd-mm-y');" src="../../images/calendar.gif" />					
		  </div>
    	<div class="clear"></div>	
			
			</br></br>
			
			<!--		 
			<div class="form-row_kiri">
				<label>&nbsp;</label>	 			
				<? $ls_st_csv = isset($ls_st_csv) ? $ls_st_csv : "T"; ?>
				<input type="checkbox" id="st_csv" name="st_csv" class="cebox" onclick="fl_js_set_st_csv();" <?=$ls_st_csv=="Y" ||$ls_st_csv=="ON" ||$ls_st_csv=="on" ? "checked" : "";?>> <font  color="#009999">Format *.CSV</font> <br/>
		  </div> 			
    	<div class="clear"></div>		
			-->																																																																			
		</fieldset>

		<div id="buttonbox" style="width:650px; text-align:center;">
      <input type="submit" class="btn green" id="btncetak" name="btncetak" value="       CETAK LAPORAN       " title="Klik Untuk Cetak Laporan">	
      <input type="button" class="btn green" id="btncetak_excel" name="btncetak_excel" value="   CETAK LAPORAN EXCEL   " title="Klik Untuk Cetak Laporan" onclick="downloadLaporan()">		 
		</div>

		<?
		if (isset($msg))		
		{
		?>
		<fieldset style="width:600px;">
      <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
      <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
		</fieldset>		
		<?
		}
		?>
								
	</div>
</div>			
<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>