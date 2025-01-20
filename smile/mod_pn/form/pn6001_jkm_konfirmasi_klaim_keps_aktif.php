<?php
$php_file_name='pn6001_jkm_konfirmasi_klaim_keps_aktif';

$p_task = $_REQUEST["task"];
$p_kode_agenda = $_REQUEST["kd_agenda"];

    if ($p_kode_agenda != "" || $p_task == "View") { 
        $sql = "SELECT a.kode_agenda,
        a.kode_jenis_agenda,
        a.kode_jenis_agenda_detil,
        a.status_agenda,
        a.keterangan,
        a.referensi,
        b.*,
        TO_CHAR (b.tgl_kejadian, 'DD/MM/RRRR')
            tgl_kejadian_display,
        TO_CHAR (b.tgl_lahir, 'DD/MM/RRRR')
            tgl_lahir_display,
        (SELECT npp
           FROM kn.kn_perusahaan z
          WHERE z.kode_perusahaan = b.kode_perusahaan AND ROWNUM = 1)
            npp,
        (SELECT nama_perusahaan
           FROM kn.kn_perusahaan z
          WHERE z.kode_perusahaan = b.kode_perusahaan AND ROWNUM = 1)
            nama_perusahaan,
        (SELECT nama_divisi
           FROM kn.kn_divisi z
          WHERE     z.kode_divisi = b.kode_divisi
                AND z.kode_perusahaan = b.kode_perusahaan
                AND ROWNUM = 1)
            nama_divisi,
        (SELECT kpj
           FROM kn.kn_tk z
          WHERE z.kode_tk = b.kode_tk AND ROWNUM = 1)
            kpj,
        (SELECT status
           FROM kn.kn_kepesertaan_tk z
          WHERE z.kode_tk = b.kode_tk AND ROWNUM = 1)
            status,
        DECODE ((SELECT aktif
                   FROM kn.kn_kepesertaan_tk z
                  WHERE z.kode_tk = b.kode_tk AND ROWNUM = 1),
                'Y', 'AKTIF',
                'T', 'NON AKTIF')
            aktif,
        b.keterangan
            AS keterangan_tindaklanjut,
        (SELECT nama_kantor from ms.ms_kantor z where z.kode_kantor=b.kode_kantor) nama_kantor,
        TO_CHAR (nvl((
                  pn.p_pn_pn60010401.f_get_blth_iuran_terakhir_tk
                  (
                    KODE_SEGMEN,--p_kode_segmen,
                    KODE_PERUSAHAAN,--p_kode_perusahaan,
                    KODE_DIVISI,--p_kode_divisi,
                    KODE_TK --p_kode_tk
                  )
                ),'31-DEC-3000'),'DD/MM/RRRR') BLTH_IURAN_TERAKHIR
   FROM pn.pn_agenda_koreksi               a,
        pn.pn_agenda_koreksi_klaim_jkm     b
  WHERE     a.kode_agenda = b.kode_agenda
        AND a.kode_agenda = '$p_kode_agenda'";
          

    }

  
            
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_tgl_kejadian        = $row["TGL_KEJADIAN_DISPLAY"];
    $ls_tgl_lahir           = $row["TGL_LAHIR_DISPLAY"];
    $ls_kode_tk             = $row["KODE_TK"] ;
    $ls_kpj                 = $row["KPJ"] ;       
    $ls_no_nik              = $row["NIK"];
    $ls_nama_tk             = $row["NAMA_TK"];
    $ls_kode_perusahaan     = $row["KODE_PERUSAHAAN"];
    $ls_npp                 = $row["NPP"];
    $ls_nama_perusahaan     = $row["NAMA_PERUSAHAAN"];
    $ls_kode_divisi         = $row["KODE_DIVISI"];
    $ls_nama_divisi         = $row["NAMA_DIVISI"];
    $ls_kode_kepesertaan    = $row["KODE_KEPESERTAAN"];
    $ls_status              = $row["STATUS"];
    $ls_aktif               = $row["AKTIF"];
    $ls_kode_kantor         = $row["KODE_KANTOR"];
    $ls_nama_kantor         = $row["NAMA_KANTOR"];
    $ls_kode_agenda         = $row["KODE_AGENDA"];
    $ls_kode_jenis_agenda   = $row["KODE_JENIS_AGENDA"];
    $ls_kode_jenis_agenda_detil = $row["KODE_JENIS_AGENDA_DETIL"];


    $ls_keterangan_tindaklanjut        = $row["KETERANGAN_TINDAKLANJUT"];
    $ls_flag_draft                     = $row["FLAG_DRAFT"];
    $ls_blth_iuran_terakhir            = $row["BLTH_IURAN_TERAKHIR"]; 


    $sql_dokumen_pendukung ="select a.kode_dokumen, (select b.nama_dokumen
          from pn.pn_kode_dokumen b
         where b.kode_dokumen = a.kode_dokumen)
           nama_dokumen, path_url, mime_type, to_char(a.tgl_upload,'DD/MM/RRRR') tgl_upload from pn.pn_agenda_koreksi_klaim_jkmdok a where kode_agenda = '$p_kode_agenda' and a.kode_dokumen='D040'";

    $DB->parse($sql_dokumen_pendukung);
    $DB->execute();
    $rowdoc = $DB->nextrow();
    $ls_path_url=$rowdoc["PATH_URL"];
    $ls_tgl_upload=$rowdoc["TGL_UPLOAD"];              


  
                       

?>
<form name="formreg" id="formreg" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
<style>
    .l_frm{width: 200px; height: : 30px; clear: left; float: left;margin-bottom: 2px;text-align:right;}
    .r_frm{float: left;margin-bottom: 2px;}
    .r_frm input,.r_frm select {
        border-radius: 2px; 
        -moz-border-radius: 2px; 
        -webkit-border-radius: 2px; 
        border: 1px solid #585858;
    }
</style>
<div style="width: 100%;">
<br>
<fieldset><legend><b>Pilih Nomor Kartu Kepesertaan</b></legend>
<table border='0' width="100%">
    <tr>
     <td width="50%" valign="top">
     <div class="l_frm" ><label for="tgl_kejadian">Tgl Kejadian <span style="color:#ff0000;">&nbsp;*</span> :</label></div>
    <div class="r_frm">
        <input type="text" id="tgl_kejadian" name="tgl_kejadian" maxlength="100"  value="<?=$ls_tgl_kejadian;?>" style="width:150px;background-color:#ffff99; text-align: ;"  <?PHP if(($p_task=="Edit" ) || $p_task=="View") {echo "readonly";}?> />
        <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl_kejadian', 'dd-mm-y');" src="../../images/calendar.gif" / <?PHP if(($p_task=="Edit" ) || $p_task=="View") {echo "disabled";}?>> 
        <input type="hidden" id="agenda_exists" name="agenda_exists" maxlength="100"  value="<?=$ls_agenda_exists;?>" style="width:150px;background-color:#ffff99; text-align: ;"readonly /> 
    </div>     
    <div class="l_frm" ><label for="no_referensi">No Referensi <span style="color:#ff0000;">&nbsp;*</span> :</label></div>
    <div class="r_frm">
        <input type="text" id="kpj" name="kpj" maxlength="100"  value="<?=$ls_kpj;?>" style="width:150px;background-color:#ffff99; text-align: ;"readonly />
        <a href="#"  onclick="fl_js_get_lov_by('KPJ')" tabindex="8" <?PHP if($_REQUEST["task"]!="New"){echo "style='pointer-events:none'";}?>>                            
        <img src="../../images/help.png" alt="Cari No Referensi" border="0" align="absmiddle"></a>   
    </div>
    </td>
   </tr>
</table>
</fieldset>
<br>
<fieldset><legend><b>Informasi Peserta</b></legend>
<table border='0' width="100%">
    <tr>
        <td width="50%" valign="top">
            <div class="l_frm" ><label for="no_nik">NIK :</label></div>
            <div class="r_frm">
                <input type="hidden" id="kode_tk" name="kode_tk" maxlength="100"  value="<?=$ls_kode_tk;?>" style="width:150px;background-color:#ffff99" readonly class="disabled" />
                <input type="text" id="nomor_identitas" name="nomor_identitas" maxlength="100"  value="<?=$ls_no_nik;?>" style="width:150px;background-color:#e9e9e9" readonly  />
            </div>
            <div class="l_frm" ><label for="nama_tk">Nama Peserta :</label></div>
            <div class="r_frm">
                <input type="text" id="nama_tk" name="nama_tk" maxlength="100"  value="<?=$ls_nama_tk;?>" style="width:265px;background-color:#e9e9e9" readonly  />
            </div>
            <div class="l_frm" ><label for="tgl_lahir">Tanggal Lahir :</label></div>
            <div class="r_frm">
                <input type="text" id="tgl_lahir" name="tgl_lahir" maxlength="100"  value="<?=$ls_tgl_lahir;?>" style="width:150px;background-color:#e9e9e9" readonly  />
            </div>
            <div class="l_frm" ><label for="blth_iuran_terakhir">BLTH Iuran Terakhir :</label></div>
            <div class="r_frm">
                <input type="text" id="blth_iuran_terakhir" name="blth_iuran_terakhir" maxlength="100"  value="<?=$ls_blth_iuran_terakhir;?>" style="width:150px;background-color:#e9e9e9" readonly  />
            </div>
        </td>
        <td valign="top">
            <div class="l_frm" ><label for="npp">NPP<span style="color:#ff0000;">&nbsp;*</span> :</label></div>
            <div class="r_frm">
                <input type="hidden" id="kode_perusahaan" name="kode_perusahaan" maxlength="100"  value="<?=$ls_kode_perusahaan;?>" style="width:200px;background-color:#ffff99" readonly class="disabled" />
                <input type="hidden" id="kode_kepesertaan" name="kode_kepesertaan" maxlength="100"  value="<?=$ls_kode_kepesertaan;?>" style="width:200px;background-color:#ffff99" readonly class="disabled" />
                <input type="text" id="npp" name="npp" maxlength="100"  value="<?=$ls_npp;?>" style="width:100px; text-align: center; background-color:#e9e9e9" readonly class="disabled" /> - 
                <input type="text" id="nama_perusahaan" name="nama_perusahaan" maxlength="100"  value="<?=$ls_nama_perusahaan;?>" style="width:245px;background-color:#e9e9e9" readonly />
            </div>
            <div class="l_frm" ><label for="kode_divisi">Unit Kerja :</label></div>
            <div class="r_frm">
                <input type="text" id="kode_divisi" name="kode_divisi" maxlength="100"  value="<?=$ls_kode_divisi;?>" style="width:100px;background-color:#e9e9e9; text-align: center;" readonly /> - 
                <input type="text" id="nama_divisi" name="nama_divisi" maxlength="100"  value="<?=$ls_nama_divisi;?>" style="width:245px;background-color:#e9e9e9" readonly />
            </div>
            <div class="l_frm" ><label for="status_keps">Status Kepesertaan :</label></div>
            <div class="r_frm">
                <input type="text" id="status_tk" name="status_tk" maxlength="100"  value="<?=$ls_status;?>" style="width:100px;background-color:#e9e9e9;  text-align: center;"  readonly /> - 
                <input type="text" id="aktif_tk" name="aktif_tk" maxlength="100"  value="<?=$ls_aktif;?>" style="width:245px;background-color:#e9e9e9;" readonly />
            </div>
            <div class="l_frm" ><label for="kantor">Kantor :</label></div>
            <div class="r_frm">
                <input type="text" id="kode_kantor_tk" name="kode_kantor_tk" maxlength="100"  value="<?=$ls_kode_kantor;?>" style="width:100px;background-color:#e9e9e9; text-align: center;" readonly /> - 
                <input type="text" id="nama_kantor" name="nama_kantor" maxlength="100"  value="<?=$ls_nama_kantor;?>" style="width:245px;background-color:#e9e9e9" readonly />
            </div>
        </td>
    </tr>
</table>
</fieldset>
<br>
<fieldset><legend><b>Daftar Checklist Dokumen</b></legend>
    <br>
    <table id="tbldoc" width="100%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <tbody>	
        <tr>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Dokumen</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Mandatory</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Status</th>						    							
        </tr>
        <tr>
		    <th colspan="7"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
		</tr>
        <?php

        if ($p_kode_agenda != "") {
          $sql="select rownum no_urut,
          kode_dokumen,
          (select b.nama_dokumen
             from pn.pn_kode_dokumen b
            where b.kode_dokumen = a.kode_dokumen)
              nama_dokumen, flag_status
     from pn.pn_agenda_koreksi_klaim_jkmdok a
    where     a.kode_agenda = '$p_kode_agenda'
          and a.kode_dokumen in ('D001',
                                 'D022',
                                 'D037',
                                 'D014',
                                 'D032',
                                 'D008')";          
        }else{
        $sql = "select 
        (
          select b.nama_dokumen from PN.PN_KODE_DOKUMEN b
          where b.kode_dokumen = a.kode_dokumen
        ) nama_dokumen, 'T' flag_status,
        a.* from PN_KODE_SEBAB_DOKUMEN a 
        where KODE_SEBAB_KLAIM = 'SKM08' 
        and nvl(a.STATUS_NONAKTIF,'T') = 'T'
        and a.KODE_DOKUMEN in ('D001','D022','D037','D014','D032','D008')
        order by a.NO_URUT asc";
        }

     
		  $DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;										
          while ($row = $DB->nextrow())
          {
        ?>
         <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">	
								<?=$row['NO_URUT'];?>											
              	<input type="hidden" id="no_urut<?=$i;?>" name="no_urut<?=$i;?>" size="2" style="border-width: 1;text-align:center" value="<?=$row['NO_URUT'];?>" readonly class="disabled">    									 
              </td> 																
         <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">
								<?=$row['NAMA_DOKUMEN'];?>											
				<input type="hidden" id="kode_dokumen<?=$i;?>" name="kode_dokumen<?=$i;?>" size="2" style="border-width: 1;text-align:center" value="<?=$row['KODE_DOKUMEN'];?>" readonly class="disabled">											
              	<input type="hidden" id="nama_dokumen<?=$i;?>" name="nama_dokumen<?=$i;?>" size="50" style="border-width: 1;text-align:left" value="<?=$row['NAMA_DOKUMEN'];?>" readonly class="disabled">    									 
        </td>
        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
        <img src=../../images/file_apply.gif>
        </td>
        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
        <input type="checkbox" class="cebox" id="flag_mandatory[]" name="flag_mandatory[]" value="<?=$row['KODE_DOKUMEN'];?>" <?php if($row['FLAG_STATUS']=="Y") {echo 'checked';}  ?>>
        </td>
        </tr>
        <?								    							
            $i++;//iterasi i
          }	//end while
          $ln_dtl=$i;						
        ?> 								
        </tbody>
    </table>
</fieldset>
<br>
<fieldset id="fieldset_aktivitas"><legend><b>Aktivitas Pelaporan/Cek Kasus</b></legend>
<table id="tbldoc" width="100%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <tbody>	
        <tr>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Aktivitas</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal Aktivitas</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Narasumber</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Profesi</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No Telepon</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Email</th>
            <th colspan="2" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>															    							
        </tr>
        <tr>
          <th colspan="7"></th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;"></th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;"></th>
        </tr>
        <tr>
		    <th colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
		    </tr>

        <?php

          if ($p_kode_agenda != "") {
          $sql="select (select z.keterangan
                      from sijstk.ms_lookup z
                    where     tipe = 'KLMAKTLJKM'
                          and nvl (aktif, 'T') = 'Y'
                          and z.kode = c.kode_aktivitas)
                      nama_aktivitas,
                  (select z.nama_kelurahan
                      from sijstk.ms_kelurahan z
                    where z.kode_kelurahan = c.kode_kelurahan)
                      nama_kelurahan,
                  (select z.nama_kecamatan
                      from sijstk.ms_kecamatan z
                    where z.kode_kecamatan = c.kode_kecamatan)
                      nama_kecamatan,
                  (select z.nama_kabupaten
                      from sijstk.ms_kabupaten z
                    where z.kode_kabupaten = c.kode_kabupaten)
                      nama_kabupaten,
                  c.keterangan
                      as keterangan_aktivitas,
                  TO_CHAR(c.tgl_aktivitas,'DD/MM/RRRR') tgl_aktivitas_display,
                  (select max(no_urut) from pn.pn_agenda_koreksi_klaim_jkmakt b where b.kode_agenda = '$p_kode_agenda')+1 max_no_urut,   
                  c.*
                from pn.pn_agenda_koreksi_klaim_jkmakt c
                where kode_agenda = '$p_kode_agenda' order by NO_URUT ASC";
                $DB->parse($sql);
                $DB->execute();							              					
                $i=0;		
                $ln_dtl =0;										
                while ($row = $DB->nextrow())
                {          
            

         
          ?>



         <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
            <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">	
            <?=$row['NO_URUT'];?>											
            <input type="hidden" id="no_urut_aktivitas<?=$i?>" name="no_urut_akitivtas<?=$i?>" value="<?=$row['NO_URUT'];?>" readonly class="disabled">
            <input type="hidden" id="max_no_urut_aktivitas<?=$i?>" name="_max_no_urut_akitivtas<?=$i?>" value="<?=$row['MAX_NO_URUT'];?>" readonly class="disabled">     									 
            </td> 																
            <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <?=$row['NAMA_AKTIVITAS'];?>															 
            </td>
            <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <?=$row['TGL_AKTIVITAS_DISPLAY'];?>	
            </td>
            <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <?=$row['NAMA_SUMBER'];?>	
            </td>
            <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <?=$row['PROFESI_SUMBER'];?>	
            </td>
            <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <?=$row['HANDPHONE'];?>	
            </td>
            <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <?=$row['EMAIL'];?>	
            </td>
            <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <?php if($ls_detil_status=="SUBMIT" || $ls_detil_status=="BATAL") { ?>
            <a href="#" onclick="tambahAktivitas('view_aktivitas','<?=$row['KODE_AKTIVITAS'];?>','<?=$row['NO_URUT'];?>')" style="padding-right: 10px;">
                     <img src="../../images/ico_tampilkan_data.jpg" border="0" alt="Edit" align="absmiddle" style="height:18px;"> VIEW
            </a>
            <?php } ?>
            <?php
            if($ls_detil_status!="SUBMIT") {
              if($ls_detil_status!="BATAL") {
              ?>
            <a href="#" onclick="tambahAktivitas('edit_aktivitas','<?=$row['KODE_AKTIVITAS'];?>','<?=$row['NO_URUT'];?>')" style="padding-right: 10px;">
                     <img src="../../images/ico_edit.jpg" border="0" alt="Edit" align="absmiddle" style="height:18px;"> EDIT
            </a>
            </td>
            <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
            <?php if($row['NO_URUT']>"3"){ ?>
              <a href="#" onclick="deleteAktivitas('<?=$row['KODE_AGENDA']; ?>','<?=$row['KODE_AKTIVITAS']; ?>','<?=$row['NO_URUT']; ?>')" style="padding-right: 10px;">
                     <img src="../../images/ico_close.jpg" border="0" alt="Delete" align="absmiddle" style="height:18px;"> DELETE
             </a>
            <?php }}} ?>
            </td>
        </tr>
        <?								    							
            $i++;//iterasi i
          }	//end while
        }
          $ln_dtl=$i;						
        ?> 							
        <tr>
          <tr>
            <td>
              &nbsp;
            </td>
          </tr>
          <td colspan="7">
          </td>
          <td colspan="2" style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
          <input type="button" name="btntambahaktivitas" style="width: 120px;" class="btn green" id="btntambahaktivitas" value="TAMBAH AKTIVITAS" onclick="tambahAktivitas('tambah_aktivitas','','')"/>
          </td>
        </tr>						
        </tbody>
</table>
</fieldset>
<br>
<fieldset><legend><b>Dokumen Pendukung</b></legend>
    <br>
    <table id="tbldoc" width="100%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <tbody>	
        <tr>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Dokumen</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal diserahkan</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Upload File</th>
            <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Download File</th>											    							
        </tr>
        <tr>
		    <th colspan="7"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
		</tr>
         <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">	
								1											
              	<input type="hidden" id="no" name="no" size="2" style="border-width: 1;text-align:center" value="1" readonly class="disabled">    									 
              </td> 																
         <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">
                                Dokumen Pendukung Konfirmasi Agenda Klaim JKM Kepesertaan Aktif 										
				<input type="hidden" id="kode_dokumen_pendukung" name="kode_dokumen_pendukung" size="2" style="border-width: 1;text-align:center" value="D040" readonly class="disabled">											
              	<input type="hidden" id="nama_dokumen_pendukung" name="nama_dokumen_pendukung" size="50" style="border-width: 1;text-align:left" value="Dokumen Pendukung Konfirmasi Agenda Klaim JKM Kepesertaan Aktif " readonly class="disabled">    									 
        </td>
        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
        <?=$ls_tgl_upload; ?>    
        </td>
        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
        <input name="file_dokumen" type="file" id="file_dokumen" style="width:225px;background-color:#ffff99;" accept="image/*, application/pdf" />
        </td>
        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
        <input type="hidden" id="path_url_exists" name="path_url_exists" value="<?=$ls_path_url; ?>" readonly>  
        <?php if($ls_path_url !="") { ?>
          <a href="#"  onclick="downloadFileSmile('<?=$ls_path_url; ?>')" style="padding-right: 10px;">
              <img src="../../images/ico_cetak.jpg" border="0" alt="Delete" align="absmiddle" style="height:18px;"> DOWNLOAD
          </a>
        <?php } ?>
        </td>
        </tr>						
        </tbody>
    </table>
    <br>
    <div class="l_frm" ><label for="keterangan">Keterangan Tindak Lanjut:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>   
            <div class="r_frm">
            <textarea id="keterangan_tindaklanjut" maxlength="300" name="keterangan_tindaklanjut" style="background-color:#ffff99;width:265px;" rows="2" <?=$i_readonly;?>><?=$ls_keterangan_tindaklanjut;?></textarea> 
            </div>
    <div class="l_frm" ><label for=""></label></div> 
    <div class="r_frm" id="div_flag_disclaimer">
    <br>   
    <input type="checkbox" id="flag_disclaimer" name="flag_disclaimer">
    <b style="font-size: 12px;">Dengan mencentang kotak ini, saya telah memeriksa dan meneliti kebenaran serta keabsahan data yang diinput / upload</b>          
    </div>
    
    <div class="l_frm" ><label for=""></label></div>
    <div class="r_frm">
        <br>
    <input type="button" name="btnsimpan" style="width: 100px" id="btnsimpan" value="SIMPAN" onclick="submitData()" class="btn green"/>
    <input type="button" name="btnsubmit" style="width: 100px" id="btnsubmit" value="SUBMIT" onclick="submitDataFinal()" disabled/>
    <input type="button" name="btnbatal" style="width: 100px" id="btnbatal" value="BATAL" onclick="batalData()" class="btn green"/>
    <input type="button" name="btntutup" style="width: 100px" class="btn green" id="btntutup" value="TUTUP" onclick="tutupForm()"/>
    </div>
 </fieldset>
   
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){ 

  $('#loading-mask').height($(document).height()+2000);

   $("#btn_save").hide();
   $("#btn_close").hide();
   $("#btn_tolak").hide();

   $("#flag_disclaimer").change(function() {
    if(this.checked) {
      $('#btnsubmit').prop("disabled", false);
      $("#btnsubmit").addClass('btn green');
    }
    else{
      $('#btnsubmit').prop("disabled", true);
      $("#btnsubmit").removeClass('btn green');
     
    }
    });

    let detil_status =  $("#detil_status").val();
  
    if(detil_status=="SUBMIT" || detil_status=="BATAL" ){
      $("#btnsimpan").hide();
      $("#btnsubmit").hide();
      $("#btnbatal").hide();
      $("#file_dokumen").hide();
      $("#btntambahaktivitas").hide();

      $("#flag_disclaimer").prop('checked', true);
      $("#flag_disclaimer").attr("disabled", true);

      $("#keterangan_tindaklanjut").prop('readonly',true);
      $("#keterangan_tindaklanjut").css("background-color", "#F5F5F5");
      
    }else if(detil_status==""){
      $("#btnsubmit").hide();
      $("#btnbatal").hide();
      $("#div_flag_disclaimer").hide();
      $("#fieldset_aktivitas").hide();
    }else if(detil_status=="TERBUKA"){
      $("#btnsimpan").hide();
    }

    
    $("#tgl_kejadian").blur(function(){
      var tglkejadian  = $("#tgl_kejadian").val();
      var datePartsawal = tglkejadian.split("/");	
      var dateObjectawal = new Date(+datePartsawal[2], datePartsawal[1] - 1, +datePartsawal[0]);
      var ToDate = new Date(); 
      if(dateObjectawal > ToDate){
        $("#tgl_kejadian").val('');
      alert("Tanggal Kejadian tidak boleh lebih dari hari ini");
      }
    });

});

function showFormReload(mypage, myname, w, h, scroll) {
		var openwin = window.parent.Ext.create('Ext.window.Window', {
			title: myname,
			collapsible: true,
			animCollapse: true,
			maximizable: true,
			closable: true,
			width: w,
			height: h,
			minWidth: w,
			minHeight: h,
			layout: 'fit',
			modal: true,
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
			listeners: {
				close: function () {
					reLoad();
				},
					destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
	}

  function reLoad(){
		location.reload();
	}

function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

  function fl_js_val_numeric(v_field_id)
  {
    var c_val = window.document.getElementById(v_field_id).value;
    var number=/^[0-9]+$/;
    
    if ((c_val!='') && (!c_val.match(number)))
    {
      document.getElementById(v_field_id).value = '';	
      window.document.getElementById(v_field_id).focus();
      alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! "); 			 
      return false; 				 
    }		
  }

function tutupForm(){
        window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/form/pn6001.php?mid=<?=$mid;?>');
}


function reload(kd_agenda){
      window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/form/pn6001.php?task=View&kd_perihal_detil=PP0401&path=pn6001_jkm_konfirmasi_klaim_keps_aktif.php&kd_agenda='+kd_agenda);
  }


 function tambahAktivitas(aksi,kode_aktivitas,no_urut){

let kd_agenda = "<?php echo $kd_agenda; ?>";
let max_no_urut_aktivitas = $('#max_no_urut_aktivitas0').val();
var params = "&kd_agenda=" + kd_agenda;
    params += "&kode_aktivitas=" + kode_aktivitas;
    params += "&max_no_urut_aktivitas=" + max_no_urut_aktivitas;
    params += "&aksi=" + aksi;
    params += "&no_urut=" + no_urut;
showFormReload('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn6001_jkm_tambah_aktivitas_konfirmasi.php?' + params, "FORM AKTIVITAS", 550, 400, scroll);

 }

 function deleteAktivitas(kd_agenda,kode_aktivitas,no_urut){


confirmation("Konfirmasi", "Apakah anda yakin akan menghapus data ini?",
  
    function () {
    
    preload(true);
    $.ajax({
             type: 'POST',
             url: "../ajax/pn6001_jkm_konfirmasi_klaim_keps_aktif_action.php?"+Math.random(),
             data: {
                 aksi: 'delete_aktivitas',
                 kd_agenda : kd_agenda,
                 kode_aktivitas : kode_aktivitas,
                 no_urut : no_urut
               },
            success: function(data){
                 
                var jdata = JSON.parse(data);
                if (jdata.ret == 0){
                    alert(jdata.msg);
                    reload(jdata.kd_agenda);
                  }
                  else {
                      alert(jdata.msg);
                  }
                  preload(false);
            },
            complete: function(){
              preload(false);
            },
            error: function(){
              alert("Terjadi kesalahan, coba beberapa saat lagi!");
              preload(false);
            }
          });
  }, 
  setTimeout(function(){}, 1000));

}

function downloadFileSmile(path_url){

let endPoint= "<?php echo $wsFileSmile ?>";
preload(true);
$.ajax({
    type: 'POST',
    url: "../ajax/pn6001_jkm_konfirmasi_klaim_keps_aktif_action.php?"+Math.random(),
    data: {
        aksi: 'downloadFileSmile',
        path_url : path_url
      },     
    success: function(data){     
          let jdata = JSON.parse(data);
          NewWindow(endPoint+"/krgBayar/downloadFileKB?data="+jdata.pathUrlEncrypt);
          preload(false);
    },
    complete: function(){
      preload(false);
    },
    error: function(){
      alert("Terjadi kesalahan, coba beberapa saat lagi!");
      preload(false);
    }
  });

}

function fl_js_get_lov_by(lov) { 
        if (lov == "KPJ") {
            let tgl_kejadian = $("#tgl_kejadian").val();
            if(tgl_kejadian==""){
                return alert ('Tanggal Kejadian tidak boleh kosong');
            }else{
                NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_jkm_konfirmasi_klaim_keps_aktif_lov_kpj.php?p=pn6001.php&o=blth_iuran_terakhir&u=nama_kantor&y=status_tk&z=aktif_tk&a=formreg&b=kode_tk&c=kpj&d=nama_tk&e=kode_perusahaan&f=nama_perusahaan&g=kode_divisi&h=nama_divisi&j='+'PU'+'&k=npp&l=nomor_identitas&m=tgl_lahir&n=kode_kantor_tk&q='+'JKM01'+'&r='+formreg.tgl_kejadian.value+'&s='+'SKM08'+'','',1000,500,1);
            }      
        } 
    }

function getBase64(file) {
    return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
});
}

async function uploadFile(file){

    let image_file = await getBase64(file);
    
    let data = "<?php 

    function encrypt_decrypt2($action, $string)
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

      $json_spec = array(
        "kodeDokumen" => "",
        "bucketName" => "smile",
        "folderName" => "konfirmasiklaimjkm/".$gs_kantor_aktif.'/'.date("Ym"),
        "idDokumen" => ""
        );
    
      echo encrypt_decrypt2('encrypt',str_replace("\/", "/", json_encode($json_spec))) 		

    ?>";

let spec = 
  {
    "data" : data,
    "fileBase64" : image_file	
};


return await doUploadDokumen(spec);

}

async function doUploadDokumen(spec){
    let end_point= "<?php echo $wsFileSmile ?>";
    const response = await fetch(end_point+"/krgBayar/uploadFileKB", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                                data : spec
                                            }) 
                                    });
    const data = await response.json();
    return data;
}

function submitData(){

   
var form = $('#formreg');
var formdata = false;
if (window.FormData){
    formdata = new FormData(form[0]);
}

let tipe = $('#TYPE').val();
let tb_kode_perihal = $('#tb_kode_perihal').val();
let tb_kode_perihal_detil = $('#tb_kode_perihal_detil').val();
let tb_path_perihal = $('#tb_path_perihal').val();
let tb_keterangan = $('#tb_keterangan').val();
let lov_sumber_informasi = $('#lov_sumber_informasi').val();
let tb_kode_sumber_data = $('#tb_kode_sumber_data').val();

let tgl_kejadian = $('#tgl_kejadian').val();
let kode_tk = $('#kode_tk').val();
let kpj = $('#kpj').val();
let nomor_identitas = $('#nomor_identitas').val();
let tgl_lahir = $('#tgl_lahir').val();
let nama_tk = $('#nama_tk').val();

let tgl_aktivitas = $('#tgl_aktivitas').val();
let kode_aktivitas = $('#kode_aktivitas').val();
let nama_aktivitas = $('#nama_aktivitas').val();
let narasumber = $('#narasumber').val();
let profesi = $('#profesi').val();
let alamat = $('#alamat').val();
let kode_pos = $('#kode_pos').val();
let kode_kelurahan = $('#kode_kelurahan').val();
let kode_kecamatan = $('#kode_kecamatan').val();
let kode_kabupaten = $('#kode_kabupaten').val();
let no_hp = $('#no_hp').val();
let keterangan_aktivitas = $('#keterangan_aktivitas').val();
let keterangan_tindaklanjut = $('#keterangan_tindaklanjut').val();
let flag_draft = 'Y';
let kd_agenda = $('#kd_agenda').val();
let path_url_exists = $('#path_url_exists').val();
let aksi="";
if(kd_agenda==""){
  aksi = 'simpan';
}else{
  aksi = 'simpan_draft';
}
  



let file_dokumen = $('#file_dokumen').val();
let file = document.getElementById("file_dokumen").files[0];

let path_url="";
let mime_type="";
let upload="";

let check_flag_mandatory_all=$('input[name="flag_mandatory[]"]').length;
let check_flag_mandatory=$('input[name="flag_mandatory[]"]:checked').length;
let flag_mandatory0 = $('input[name="flag_mandatory[]"]:checked').map(function(){    
  return $(this).val();
  });
let flag_mandatory1=flag_mandatory0.get();
let flag_mandatory2 = flag_mandatory1.map(function(kode){    
    // return {
    //   kodeDokumen : kode,
    //   flagmandatory : 'T'
    // }
    return kode;
      
  });
let flag_mandatory=JSON.stringify(flag_mandatory2);



let konfirmasi="Apakah anda yakin akan menyimpan agenda ini?";

confirmation("Konfirmasi", konfirmasi,
    async function () {
    if(check_flag_mandatory_all != check_flag_mandatory){
      return alert('Harap melakukan pemeriksaan pada seluruh dokumen dengan memberikan tanda centang pada kolom mandatory');
    }

    if(file){
    let filesize = file.size;
      let maxsize = 6097152;
    if(filesize > maxsize){
      return alert('Maximal file upload 6 MB!');
        }else{
              preload(true);
              upload=await uploadFile(file);
              preload(false);
              if(upload.ret=="0"){
                path_url =upload.data.path;
                mime_type=upload.data.mimeType;
              } 	
            }  
    }

    if(parseInt(upload.ret) == -1 ){
        return alert('Terjadi kesalahan, gagal mengupload file.');
      }       
    preload(true);
    $.ajax({
            type: 'POST',
            url: "../ajax/pn6001_jkm_konfirmasi_klaim_keps_aktif_action.php?"+Math.random(),
            data: {
                TYPE : tipe,
                tb_kode_perihal : tb_kode_perihal,
                tb_kode_perihal_detil : tb_kode_perihal_detil,
                tb_path_perihal : tb_path_perihal,
                tb_keterangan : tb_keterangan ,
                lov_sumber_informasi : lov_sumber_informasi,
                tb_kode_sumber_data : tb_kode_sumber_data,
                tgl_kejadian : tgl_kejadian,
                kode_tk : kode_tk,
                kpj : kpj,
                nomor_identitas : nomor_identitas,
                tgl_lahir : tgl_lahir,
                nama_tk : nama_tk,
                tgl_aktivitas : tgl_aktivitas,
                kode_aktivitas : kode_aktivitas,
                nama_aktivitas : nama_aktivitas,
                narasumber : narasumber,
                profesi : profesi,
                alamat : alamat,
                kode_pos : kode_pos,
                kode_kelurahan : kode_kelurahan,
                kode_kecamatan : kode_kecamatan,
                kode_kabupaten : kode_kabupaten,
                no_hp : no_hp,
                keterangan_aktivitas : keterangan_aktivitas,
                keterangan_tindaklanjut : keterangan_tindaklanjut,
                path_url : path_url,
                mime_type : mime_type,
                flag_mandatory : flag_mandatory,
                flag_draft : flag_draft,
                aksi : aksi,
                kd_agenda : kd_agenda,
                path_url_exists : path_url_exists
              },     
            success: function(data){
                
                  let jdata = JSON.parse(data);
                  if (jdata.ret == 0){
                    alert(jdata.msg);
                    reload(jdata.kd_agenda);
                  }
                  else {
                      alert(jdata.msg);
                  }
                  preload(false);
            },
            complete: function(){
              preload(false);
            },
            error: function(){
              alert("Terjadi kesalahan, coba beberapa saat lagi!");
              preload(false);
            }
          });
  }, 
  setTimeout(function(){}, 1000));

}

function submitDataFinal(){

   
var form = $('#formreg');
var formdata = false;
if (window.FormData){
    formdata = new FormData(form[0]);
}

let tipe = $('#TYPE').val();
let tb_kode_perihal = $('#tb_kode_perihal').val();
let tb_kode_perihal_detil = $('#tb_kode_perihal_detil').val();
let tb_path_perihal = $('#tb_path_perihal').val();
let tb_keterangan = $('#tb_keterangan').val();
let lov_sumber_informasi = $('#lov_sumber_informasi').val();
let tb_kode_sumber_data = $('#tb_kode_sumber_data').val();

let kd_agenda = $('#kd_agenda').val();

let tgl_kejadian = $('#tgl_kejadian').val();
let kode_tk = $('#kode_tk').val();
let kpj = $('#kpj').val();
let nomor_identitas = $('#nomor_identitas').val();
let tgl_lahir = $('#tgl_lahir').val();
let nama_tk = $('#nama_tk').val();

let tgl_aktivitas = $('#tgl_aktivitas').val();
let kode_aktivitas = $('#kode_aktivitas').val();
let nama_aktivitas = $('#nama_aktivitas').val();
let narasumber = $('#narasumber').val();
let profesi = $('#profesi').val();
let alamat = $('#alamat').val();
let kode_pos = $('#kode_pos').val();
let kode_kelurahan = $('#kode_kelurahan').val();
let kode_kecamatan = $('#kode_kecamatan').val();
let kode_kabupaten = $('#kode_kabupaten').val();
let no_hp = $('#no_hp').val();
let keterangan_aktivitas = $('#keterangan_aktivitas').val();
let keterangan_tindaklanjut = $('#keterangan_tindaklanjut').val();
let flag_draft = 'T';
let path_url_exists = $('#path_url_exists').val();
let aksi = 'submit';



let file_dokumen = $('#file_dokumen').val();
let file = document.getElementById("file_dokumen").files[0];

let path_url="";
let mime_type="";
let upload="";

let check_flag_mandatory_all=$('input[name="flag_mandatory[]"]').length;
let check_flag_mandatory=$('input[name="flag_mandatory[]"]:checked').length;
let flag_mandatory0 = $('input[name="flag_mandatory[]"]:checked').map(function(){    
  return $(this).val();
  });
let flag_mandatory1=flag_mandatory0.get();
let flag_mandatory2 = flag_mandatory1.map(function(kode){    
    // return {
    //   kodeDokumen : kode,
    //   flagmandatory : 'T'
    // }
    return kode;
      
  });
let flag_mandatory=JSON.stringify(flag_mandatory2);



let konfirmasi="Apakah anda yakin akan mensubmit agenda ini?";

confirmation("Konfirmasi", konfirmasi,
    async function () {
    if(check_flag_mandatory_all != check_flag_mandatory){
      return alert('Harap melakukan pemeriksaan pada seluruh dokumen dengan memberikan tanda centang pada kolom mandatory');
    }

    if(file){
    let filesize = file.size;
      let maxsize = 6097152;
    if(filesize > maxsize){
      return alert('Maximal file upload 6 MB!');
        }else{
              preload(true);
              upload=await uploadFile(file);
              preload(false);
              if(upload.ret=="0"){
                path_url =upload.data.path;
                mime_type=upload.data.mimeType;
              } 	
            }  
    }

    if(parseInt(upload.ret) == -1 ){
        return alert('Terjadi kesalahan, gagal mengupload file.');
      }       
    preload(true);
    $.ajax({
            type: 'POST',
            url: "../ajax/pn6001_jkm_konfirmasi_klaim_keps_aktif_action.php?"+Math.random(),
            data: {
                TYPE : tipe,
                tb_kode_perihal : tb_kode_perihal,
                tb_kode_perihal_detil : tb_kode_perihal_detil,
                tb_path_perihal : tb_path_perihal,
                tb_keterangan : tb_keterangan ,
                lov_sumber_informasi : lov_sumber_informasi,
                tb_kode_sumber_data : tb_kode_sumber_data,
                tgl_kejadian : tgl_kejadian,
                kode_tk : kode_tk,
                kpj : kpj,
                nomor_identitas : nomor_identitas,
                tgl_lahir : tgl_lahir,
                nama_tk : nama_tk,
                tgl_aktivitas : tgl_aktivitas,
                kode_aktivitas : kode_aktivitas,
                nama_aktivitas : nama_aktivitas,
                narasumber : narasumber,
                profesi : profesi,
                alamat : alamat,
                kode_pos : kode_pos,
                kode_kelurahan : kode_kelurahan,
                kode_kecamatan : kode_kecamatan,
                kode_kabupaten : kode_kabupaten,
                no_hp : no_hp,
                keterangan_aktivitas : keterangan_aktivitas,
                keterangan_tindaklanjut : keterangan_tindaklanjut,
                path_url : path_url,
                mime_type : mime_type,
                flag_mandatory : flag_mandatory,
                flag_draft : flag_draft,
                aksi : aksi,
                kd_agenda : kd_agenda,
                path_url_exists : path_url_exists
              },     
            success: function(data){
                
                  let jdata = JSON.parse(data);
                  if (jdata.ret == 0){
                    alert(jdata.msg);
                    reload(jdata.kd_agenda);
                  }
                  else {
                      alert(jdata.msg);
                  }
                  preload(false);
            },
            complete: function(){
              preload(false);
            },
            error: function(){
              alert("Terjadi kesalahan, coba beberapa saat lagi!");
              preload(false);
            }
          });
  }, 
  setTimeout(function(){}, 1000));

}

function batalData(){

   


let kd_agenda = $('#kd_agenda').val();
let keterangan_tindaklanjut = $('#keterangan_tindaklanjut').val();
let aksi = 'batal';

let konfirmasi="Apakah anda yakin akan membatalkan agenda ini?";

confirmation("Konfirmasi", konfirmasi,
    async function () {
    if(keterangan_tindaklanjut==""){
      return alert('Keterangan Tindak Lanjut tidak boleh kosong');
    }

    preload(true);
    $.ajax({
            type: 'POST',
            url: "../ajax/pn6001_jkm_konfirmasi_klaim_keps_aktif_action.php?"+Math.random(),
            data: {
                keterangan_tindaklanjut : keterangan_tindaklanjut,
                aksi : aksi,
                kd_agenda : kd_agenda
              },     
            success: function(data){
                
                  let jdata = JSON.parse(data);
                  if (jdata.ret == 0){
                    alert(jdata.msg);
                    reload(jdata.kd_agenda);
                  }
                  else {
                      alert(jdata.msg);
                  }
                  preload(false);
            },
            complete: function(){
              preload(false);
            },
            error: function(){
              alert("Terjadi kesalahan, coba beberapa saat lagi!");
              preload(false);
            }
          });
  }, 
  setTimeout(function(){}, 1000));

}

 


</script>   