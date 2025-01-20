<?php /*****LOAD DATA*************************************/
/*** */
$schema="sijstk";
$dataid= isset($_GET['dataid'])?$_GET['dataid']:'';
$ls_no                  ='1';
$ls_kode                ='';
$ls_nama                ='';
$ls_status_nonaktif     ='';
$ls_tgl_nonaktif        ='';
$ls_tgl_rekam           ='';
$ls_petugas_rekam       ='';
$ls_tgl_ubah            ='';
$ls_petugas_ubah        ='';

//Kantor -------------------------------------------------------------------
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif; 
if($ls_kode_kantor=="")
{
     $ls_kode_kantor =  $gs_kantor_aktif;
}	
//Sumber Data : sesuai kantor login ----------------------------------------
$sql = "select kode_tipe from sijstk.ms_kantor where kode_kantor = '$ls_kode_kantor' ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_tipe_kantor = $row["KODE_TIPE"];

    if ($ls_tipe_kantor=="0")
{
     $ls_kode_sumber_data = "1";
}else if ($ls_tipe_kantor=="1")
{
     $ls_kode_sumber_data = "2";
}
else if ($ls_tipe_kantor=="3" || $ls_tipe_kantor=="4" || $ls_tipe_kantor=="5")
{
     $ls_kode_sumber_data = "3";	 
}

    if ($ls_kode_sumber_data !="")
{
  $sql = "select nama_sumber_data from sijstk.kn_kode_sumber_data ".
                "where kode_sumber_data = '$ls_kode_sumber_data' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_nama_sumber_data = $row["NAMA_SUMBER_DATA"];
}	

$v_faskes=$task_code==true;
$v_iks=$task_code==true;
$v_lamp=$task_code==true;


//if($task_no=='Faskes'){
    if($task_code=="View")
    {
        $sql = "SELECT A.KODE_FASKES, A.KODE_TIPE,A.KODE_KANTOR,A.KODE_PEMBINA,A.NAMA_FASKES,A.ALAMAT,A.RT,A.RW,A.KODE_KELURAHAN,A.KODE_KECAMATAN,
        A.KODE_KABUPATEN,A.KODE_POS,A.NAMA_PIC,A.HANDPHONE_PIC,TO_CHAR(A.TGL_NONAKTIF,'DD/MM/YYYY') TGL_NONAKTIF,TO_CHAR(A.TGL_AKTIF,'DD/MM/YYYY') TGL_AKTIF,
        A.KODE_STATUS,A.KODE_JENIS,A.KODE_JENIS_DETIL,A.NO_IJIN_PRAKTEK,A.NPWP,A.MAX_TERTANGGUNG,A.FLAG_UMUM,A.FLAG_GIGI,A.FLAG_SALIN,A.FLAG_REG_WEBSITE,A.KETERANGAN,
        A.NAMA_PEMILIK,A.ALAMAT_PEMILIK,A.RT_PEMILIK,A.RW_PEMILIK,A.KODE_KELURAHAN_PEMILIK,A.KODE_KECAMATAN_PEMILIK,A.KODE_KABUPATEN_PEMILIK,A.KODE_POS_PEMILIK,
        A.TELEPON_AREA_PEMILIK,A.TELEPON_PEMILIK,A.TELEPON_EXT_PEMILIK,A.FAX_AREA_PEMILIK,A.FAX_PEMILIK,A.EMAIL_PEMILIK,A.KODE_KEPEMILIKAN,A.KODE_METODE_PEMBAYARAN,
        A.KODE_BANK_PEMBAYARAN,A.NAMA_REKENING_PEMBAYARAN,A.NO_REKENING_PEMBAYARAN,TO_CHAR(A.TGL_SUBMIT,'DD/MM/YYYY') TGL_SUBMIT,
        A.PETUGAS_SUBMIT,TO_CHAR(A.TGL_REKAM,'DD/MM/YYYY') TGL_REKAM,A.PETUGAS_REKAM,TO_CHAR(A.TGL_UBAH,'DD/MM/YYYY') TGL_UBAH,A.PETUGAS_UBAH,
        A.TELEPON_AREA_PIC,A.TELEPON_PIC,A.TELEPON_EXT_PIC,A.KELAS_PERAWATAN,A.BAGIAN_PIC,STATUS_REQUEST,LATITUDE,LONGITUDE,
        (SELECT KODE_KANTOR FROM TC.TC_FASKES WHERE KODE_FASKES = A.KODE_FASKES) || ' - ' || (SELECT NAMA_KANTOR FROM MS.MS_KANTOR WHERE KODE_KANTOR = (SELECT KODE_KANTOR FROM TC.TC_FASKES WHERE KODE_FASKES = A.KODE_FASKES)) AS KODE_NAMA_KACAB,
        A.KODE_PIC_CABANG,
		A.KODE_PIC_CABANG || ' - ' || (SELECT NAMA_USER FROM MS.SC_USER A WHERE KODE_PIC_CABANG = A.KODE_USER) AS KODE_USER_NAMA,
        A.KONTAK_PIC_CABANG
        FROM {$schema}.TC_FASKES A
        WHERE A.KODE_FASKES='{$dataid}' AND A.KODE_KANTOR='{$gs_kantor_aktif}'"; // echo $sql;    
        // echo $sql;
        $DB->parse($sql);
        $DB->execute();
        if($row = $DB->nextrow())
        {
            $ls_kode_faskes         =$row['KODE_FASKES'];
            $ls_kode_tipe           =$row['KODE_TIPE'];
            $ls_kode_kantor         =$row['KODE_KANTOR'];
            $ls_kode_pembina        =$row['KODE_PEMBINA'];
            $ls_nama_faskes         =$row['NAMA_FASKES'];
            $ls_alamat              =$row['ALAMAT'];
            $ls_rt                  =$row['RT'];
            $ls_rw                  =$row['RW'];
            $ls_kode_kelurahan      =$row['KODE_KELURAHAN'];
            $ls_kode_kecamatan      =$row['KODE_KECAMATAN'];
            $ls_kode_kabupaten      =$row['KODE_KABUPATEN'];
            $ls_kode_pos            =$row['KODE_POS'];
            $ls_nama_pic            =$row['NAMA_PIC'];
            $ls_handphone_pic       =$row['HANDPHONE_PIC'];
            $ls_tgl_nonaktif        =$row['TGL_NONAKTIF'];
            $ls_tgl_aktif           =$row['TGL_AKTIF'];
            $ls_kode_status         =$row['KODE_STATUS'];
            $ls_kode_jenis          =$row['KODE_JENIS'];
            $ls_kode_jenis_detil    =$row['KODE_JENIS_DETIL'];
            $ls_no_ijin_praktek     =$row['NO_IJIN_PRAKTEK'];
            $ls_npwp                =$row['NPWP'];
            $ls_max_tertanggung     =$row['MAX_TERTANGGUNG'];
            $ls_flag_umum           =$row['FLAG_UMUM'];
            $ls_flag_gigi           =$row['FLAG_GIGI'];
            $ls_flag_salin          =$row['FLAG_SALIN'];
            $ls_flag_reg_website    =$row['FLAG_REG_WEBSITE'];
            $ls_keterangan          =$row['KETERANGAN'];
            $ls_nama_pemilik        =$row['NAMA_PEMILIK'];
            $ls_alamat_pemilik      =$row['ALAMAT_PEMILIK'];
            $ls_rt_pemilik          =$row['RT_PEMILIK'];
            $ls_rw_pemilik          =$row['RW_PEMILIK'];
            $ls_kode_kelurahan_pemilik=$row['KODE_KELURAHAN_PEMILIK'];
            $ls_kode_kecamatan_pemilik=$row['KODE_KECAMATAN_PEMILIK'];
            $ls_kode_kabupaten_pemilik=$row['KODE_KABUPATEN_PEMILIK'];
            $ls_kode_pos_pemilik    =$row['KODE_POS_PEMILIK'];
            $ls_telepon_area_pemilik=$row['TELEPON_AREA_PEMILIK'];
            $ls_telepon_pemilik     =$row['TELEPON_PEMILIK'];
            $ls_telepon_ext_pemilik =$row['TELEPON_EXT_PEMILIK'];
            $ls_fax_area_pemilik    =$row['FAX_AREA_PEMILIK'];
            $ls_fax_pemilik         =$row['FAX_PEMILIK'];
            $ls_email_pemilik       =$row['EMAIL_PEMILIK'];
            $ls_kode_kepemilikan    =$row['KODE_KEPEMILIKAN'];
            $ls_kode_metode_pembayaran  =$row['KODE_METODE_PEMBAYARAN'];
            $ls_kode_bank_pembayaran    =$row['KODE_BANK_PEMBAYARAN'];
            //$ls_nama_bank_pembayaran    =$row['NAMA_BANK_PEMBAYARAN'];
            $ls_nama_rekening_pembayaran=$row['NAMA_REKENING_PEMBAYARAN'];
            $ls_no_rekening_pembayaran  =$row['NO_REKENING_PEMBAYARAN'];
             $ls_tgl_submit          =$row['TGL_SUBMIT'];
            $ls_petugas_submit      =$row['PETUGAS_SUBMIT'];
            $ls_tgl_rekam           =$row['TGL_REKAM'];
            $ls_petugas_rekam       =$row['PETUGAS_REKAM'];
            $ls_tgl_ubah            =$row['TGL_UBAH'];
            $ls_petugas_ubah        =$row['PETUGAS_UBAH'];

            $ls_telepon_area_pic    =$row['TELEPON_AREA_PIC'];
            $ls_telepon_pic         =$row['TELEPON_PIC'];
            $ls_telepon_ext_pic     =$row['TELEPON_EXT_PIC'];
            if($row['KODE_STATUS']=='ST1' && $task_code=='Edit')
                $f_daftar=true;
            $ls_kelas_perawatan     =$row['KELAS_PERAWATAN'];
            $ls_bagian_pic          =$row['BAGIAN_PIC'];
            $ls_latitude            =$row['LATITUDE'];
            $ls_longitude           =$row['LONGITUDE'];
            $ls_kode_request    = $row['STATUS_REQUEST'];
            $kode_nama_kantor       =$row['KODE_NAMA_KACAB'];
            $kode_user_nama         =$row['KODE_USER_NAMA'];
            $kode_pic_cabang        =$row['KODE_PIC_CABANG'];
            $ls_handphone_pic_kacab =$row['KONTAK_PIC_CABANG'];
        }
    }
    if(trim($ls_kode_pos)!='' || trim($ls_kode_pos_pemilik)!='')
    {
        $sql = "SELECT KODE_KELURAHAN,NAMA_KELURAHAN 
        FROM {$schema}.MS_KELURAHAN
        WHERE KODE_KELURAHAN in ('{$ls_kode_kelurahan}','{$ls_kode_kelurahan_pemilik}')"; //  echo $sql;
        $DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
            if($row['KODE_KELURAHAN']==$ls_kode_kelurahan)
                $ls_nama_kelurahan = $row['NAMA_KELURAHAN'];
            if($row['KODE_KELURAHAN']==$ls_kode_kelurahan_pemilik)
                $ls_nama_kelurahan_pemilik = $row['NAMA_KELURAHAN'];
        }
        $sql = "SELECT KODE_KECAMATAN,NAMA_KECAMATAN
        FROM {$schema}.MS_KECAMATAN
        WHERE KODE_KECAMATAN in ('{$ls_kode_kecamatan}','{$ls_kode_kecamatan_pemilik}')";
        $DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
            if($row['KODE_KECAMATAN']==$ls_kode_kecamatan)
                $ls_nama_kecamatan = $row['NAMA_KECAMATAN'];
            if($row['KODE_KECAMATAN']==$ls_kode_kecamatan_pemilik)
                $ls_nama_kecamatan_pemilik = $row['NAMA_KECAMATAN'];
        }
        $sql = "SELECT KODE_KABUPATEN,NAMA_KABUPATEN
        FROM {$schema}.MS_KABUPATEN
        WHERE KODE_KABUPATEN in ('{$ls_kode_kabupaten}','{$ls_kode_kabupaten_pemilik}')";
        $DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
            if($row['KODE_KABUPATEN']==$ls_kode_kabupaten)
                $ls_nama_kabupaten = $row['NAMA_KABUPATEN'];
            if($row['KODE_KABUPATEN']==$ls_kode_kabupaten_pemilik)
                $ls_nama_kabupaten_pemilik = $row['NAMA_KABUPATEN'];
        }
    }
    $ls_kode_tipe = isset($ls_kode_tipe) ? $ls_kode_tipe: "";
    $sql = "select KODE_TIPE,NAMA_TIPE from {$schema}.TC_kode_TIPE where KODE_TIPE='{$ls_kode_tipe}'";
    $DB->parse($sql);
    $DB->execute();
    $nama_tipe= "";
    if($row = $DB->nextrow()){
        $kode_tipe .= $row["NAMA_TIPE"];
    }

    $sql = "select KODE_KEPEMILIKAN,NAMA_KEPEMILIKAN from {$schema}.tc_kode_kepemilikan where kode_kepemilikan='{$ls_kode_kepemilikan}'";
    $DB->parse($sql);
    $DB->execute();
    $nama_kepemilikan="";
    if($row = $DB->nextrow())
        $nama_kepemilikan .=$row["NAMA_KEPEMILIKAN"];
    // get kode status
    //$ls_kode_status='';
    $sql = "select KODE_STATUS,NAMA_STATUS from {$schema}.tc_kode_status where KODE_STATUS='{$ls_kode_status}'";
    $DB->parse($sql);
    $DB->execute();
    $nama_status="";
    if($row = $DB->nextrow())
        $nama_status .= $row["NAMA_STATUS"];

        // get kode metode bayar
    //$ls_kode_metode_pembayaran='';
    $sql = "select KODE_METODE_PEMBAYARAN,NAMA_METODE_PEMBAYARAN from {$schema}.tc_kode_metode_pembayaran where KODE_METODE_PEMBAYARAN='{$ls_kode_metode_pembayaran}'";
    $DB->parse($sql);
    $DB->execute();
    $nama_metode_pembayaran="";
    if($row = $DB->nextrow())
        $nama_metode_pembayaran .= $row["NAMA_METODE_PEMBAYARAN"];

    // get kode bank bayar
    //$ls_kode_metode_pembayaran='';
    $sql = "select KODE_BANK_PEMBAYARAN,NAMA_BANK_PEMBAYARAN from {$schema}.ms_bank where kode_bank='{$ls_kode_bank_pembayaran}'";
    $DB->parse($sql);
    $DB->execute();
    $kode_bank_pembayaran="";
    if($row = $DB->nextrow())
        $kode_bank_pembayaran .= $row["NAMA_BANK"];
    
    $sql = "select KODE_JENIS,NAMA_JENIS from {$schema}.TC_KODE_JENIS where KODE_JENIS='{$ls_kode_jenis}'";
    $DB->parse($sql);
    $DB->execute();
    $nama_jenis="";
    if($row = $DB->nextrow())
        $nama_jenis .= $row["NAMA_JENIS"];
    $sql = "select KODE_JENIS_DETIL,NAMA_JENIS_DETIL from {$schema}.TC_KODE_JENIS_DETIL where KODE_JENIS_DETIL='{$ls_kode_jenis_detil}'";
    $DB->parse($sql);
    $DB->execute();
    $nama_jenis_detil="";
    if($row = $DB->nextrow())
            $nama_jenis_detil .= $row["NAMA_JENIS_DETIl"];


$i_readonly = $v_faskes?'readonly':'';
$i_mcolor = $v_faskes?'#e9e9e9;':'#ffff99;';
$i_ocolor = $v_faskes?'#e9e9e9;':'#ffffff;';
/*****end LOAD DATA*********************************/ ?>
<style>
.f_0{}
.f_0 form fieldset legend {
    font-size: 100%;
    font-weight: bold;
    color : #157fcc;
	font-family: verdana, arial, tahoma, sans-serif; 		
  }
.f_0 input,textarea, select  {
  		border: 1px solid #dddddd;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.056);
		box-shadow: inset 0 1px 1px rgba(0,0,0,0.056);
		padding:2px;
		font-size:10px;
		font-family: verdana, arial, tahoma, sans-serif;		
  }
  .f_0 input:disabled,textarea:disabled, select:disabled  {
	  	color: #C5C4C4;
    	background: #F5F5F5;
  		border: 1px solid #dddddd;
		-webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
		box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
		padding:2px;
		font-size:10px;
		font-family: verdana, arial, tahoma, sans-serif;		
  }
  .f_0 input:readonly,textarea:readonly, select:readonly  {
	  	color: #3e3724;
    	background: #F5F5F5;
  		border: 1px solid #dddddd;
		-webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
		box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
		padding:2px;
		font-size:10px;
		font-family: verdana, arial, tahoma, sans-serif;		
  }
.f_1{width:160px;text-align:right;float:left;clear:left;margin-bottom:2px;}
.f_2{width:300px;text-align:left;margin-left:2px;float:left;margin-bottom:2px;  }
.f_readonly{background-color:#e9e9e9;}
.f_mandatory{background-color:#ffff99;}
</style>
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">

</script>
<?php /*****end LOCAL JAVASCRIPT*********************************/?>
<form>
<fieldset><legend><b>Approval <?php echo $ls_kode_request=='B'?'Pembatalan Status Non Aktif':($ls_kode_request=='N'?'Pengajuan Status Non Aktif':'');?> Faskes/ BLK</b></legend>
    <select id="statusapprove" name="statusapprove" style="width:150px;" >
        <option value="Y">Disetujui</option>
        <option value="T">Ditolak</option>              
    </select>
    <input type="button" name="btnapprove" class="btn green" id="btnapprove" value=" APPROVE <?php echo $ls_kode_request=='B'?'BATAL NONAKTIF':'NONAKTIF';?> FASKES/BLK ">
</fieldset>
</form>
<br />
<?php if($task_no=="Faskes"){?>
<form name="formreg" id="formreg" role="form" method="post">
    <input type="hidden" id="formregact" name="formregact" value="<?=$task_code;?>" />
    <fieldset><legend><b>Informasi Fasilitas</b></legend>
    <table border='0' width="100%">
        <tr>
            <td width="50%" valign="top">
                <?php if ($f_daftar) {?>
                <div class="f_1">&nbsp;</div>
                <div class="f_2"><input type="checkbox" id="daftar" name="daftar" value="Y" checked /> <label for="daftar">Mendaftarkan Faskes/ BLK</label></div>
                <?php } else {?>
                <input type="hidden" id="daftar" name="daftar" value="T" />
                <?php }?>
                <div class="f_1" ><label for="nama_faskes">Nama Faskes/BLK* :</label></div>
                <div class="f_2"><input type="text" id="nama_faskes" name="nama_faskes" maxlength="100"  value="<?=$ls_nama_faskes;?>" style="width:265px;background-color:<?=$i_mcolor;?>" <?=$i_readonly;?> /></div>
                <div class="f_1" ><label for="kode_faskes">No. Faskes/BLK :</label></div>
                <div class="f_2"><input type="text" id="kode_faskes" name="kode_faskes" style="width:160px;background-color:#e9e9e9;" readonly  value="<?=$ls_kode_faskes;?>"/></div>
                <div class="f_1" ><label for="kode_tipe">Tipe Faskes/BLK* :</label></div>
                <div class="f_2"><input type="text" id="kode_tipe" name="kode_tipe" style="width:160px;background-color:#e9e9e9;" readonly  value="<?=$nama_tipe;?>"/></div>
                <div class="f_1" ><label for="kode_kantor">Kantor :</label></div>
                <div class="f_2"><input type="text" id="kode_kantor" name="kode_kantor" style="width:160px;background-color:#e9e9e9;" readonly  value="<?=$ls_kode_kantor;?>"/></div>
                <div class="f_1" ><label for="alamat">Alamat Lengkap* :</label></div>
                <div class="f_2"><textarea id="alamat" name="alamat" maxlength="300" style="width:265px;background-color:<?=$i_mcolor;?>" rows="2" <?=$i_readonly;?>><?=$ls_alamat;?></textarea></div>
                <div class="f_1" ><label for="rt">RT/ RW :</label></div>
                <div class="f_2"><input type="text" id="rt" name="rt" maxlength="5" style="width:30px;background-color:<?=$i_ocolor;?>" value="<?=$ls_rt;?>" <?=$i_readonly;?> />/ <input type="text" id="rw" name="rw" maxlength="5" style="width:30px;background-color:<?=$i_ocolor;?>"  value="<?=$ls_rw;?>"  <?=$i_readonly;?> /></div>
                <div class="f_1" ><label for="kode_pos">Kode Pos* :</label></div>
                <div class="f_2"><input type="text" id="kode_pos" name="kode_pos" style="width:90px;background-color: <?=$i_mcolor;?>" readonly   value="<?=$ls_kode_pos;?>"/></div>
                <input type="hidden" id="kode_kelurahan" name="kode_kelurahan" value="<?=$ls_kode_kelurahan;?>" />
                <input type="hidden" id="kode_kecamatan" name="kode_kecamatan" value="<?=$ls_kode_kecamatan;?>"/>
                <input type="hidden" id="kode_kabupaten" name="kode_kabupaten" value="<?=$ls_kode_kabupaten;?>"/>
                <input type="hidden" id="kode_propinsi" name="kode_propinsi" />
                <input type="hidden" id="nama_propinsi" name="nama_propinsi" />
                <div class="f_1" ><label for="nama_kelurahan">Kelurahan :</label></div>
                <div class="f_2"><input type="text" id="nama_kelurahan" name="nama_kelurahan" style="width:200px;background-color:#e9e9e9;" readonly  value="<?=$ls_nama_kelurahan;?>"/></div>
                <div class="f_1" ><label for="nama_kecamatan">Kecamatan :</label></div>
                <div class="f_2"><input type="text" id="nama_kecamatan" name="nama_kecamatan" style="width:200px;background-color:#e9e9e9;" readonly  value="<?=$ls_nama_kecamatan;?>"/></div>
                <div class="f_1" ><label for="nama_kabupaten">Kabupaten :</label></div>
                <div class="f_2"><input type="text" id="nama_kabupaten" name="nama_kabupaten" style="width:200px;background-color:#e9e9e9;" readonly  value="<?=$ls_nama_kabupaten;?>"/></div>
                <div class="f_1" ><label for="latitude">Latitude* :</label></div>
                <div class="f_2"><input type="text" id="latitude" name="latitude" style="width:200px;background-color:#e9e9e9;" readonly  value="<?=$ls_latitude;?>"/></div>
                <div class="f_1" ><label for="longitude">Longitude* :</label></div>
                <div class="f_2"><input type="text" id="longitude" name="longitude" style="width:200px;background-color:#e9e9e9;" readonly  value="<?=$ls_longitude;?>"/></div>
                <!--
                <div>&nbsp;</div>
                <div class="f_1" ><label for="kode_status">Status :</label></div>
                <div class="f_2"><select id="kode_status" name="kode_status" tabindex="9" disabled="disabled"><?=$kode_status;?></select></div>         
                -->
                
            </td>
            <td valign="top">
                <?php if ($task=='New') {?>
                    <div class="f_1">&nbsp;</div>
                    <div class="f_2"></div>
                <?php }?>
                <div class="f_1" ><label for="telp_area_pic">No. Telepon :</label></div>
                <div class="f_2"><input type="text" id="telp_area_pic" name="telp_area_pic" style="width:40px;background-color: <?=$i_ocolor;?>" maxlength="5"   value="<?=$ls_telepon_area_pic;?>"  <?=$i_readonly;?>/> <input type="text" id="telp_pic" name="telp_pic" style="width:170px;background-color: <?=$i_ocolor;?>" maxlength="20"  value="<?=$ls_telepon_pic;?>"  <?=$i_readonly;?>/></div>
                <div class="f_1" ><label for="telp_ext_pic">Telepon Ext. :</label></div>
                <div class="f_2"><input type="text" id="telp_ext_pic" name="telp_ext_pic" style="width:100px;background-color: <?=$i_ocolor;?>" value="<?=$ls_telepon_ext_pic;?>" maxlength="5"  <?=$i_readonly;?>/> </div>
                <div class="f_1" ><label for="nama_pic">Nama PIC :</label></div>
                <div class="f_2"><input type="text" id="nama_pic" name="nama_pic" maxlength="100" style="width:265px;background-color:<?=$i_ocolor;?>" value="<?=$ls_nama_pic;?>" <?=$i_readonly;?>/></div>
                <div class="f_1" ><label for="handphone_pic">Handphone PIC :</label></div>
                <div class="f_2"><input type="text" id="handphone_pic" name="handphone_pic" maxlength="20" style="width:140px;background-color:<?=$i_ocolor;?>" value="<?=$ls_handphone_pic;?>" <?=$i_readonly;?>/></div>
                <div class="f_1" ><label for="no_ijin_praktek">No. Ijin Praktek* :</label></div>
                <div class="f_2"><input type="text" id="no_ijin_praktek" name="no_ijin_praktek" maxlength="100" style="width:265px;background-color:<?=$i_mcolor;?>" value="<?=$ls_no_ijin_praktek;?>" <?=$i_readonly;?>/></div>
                <div class="f_1" ><label for="npwp">NPWP Faskes/BLK* :</label></div>
                <div class="f_2"><input type="text" id="npwp" name="npwp" maxlength="30" style="width:265px;background-color:<?=$i_mcolor;?>" <?=$i_readonly;?>  value="<?=$ls_npwp;?>"/></div>
                <div class="f_1" ><label for="max_tertanggung">Max Tertanggung :</label></div>
                <div class="f_2"><input type="text" id="max_tertanggung" name="max_tertanggung" style="width:125px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$ls_max_tertanggung;?>"/></div>
                <div class="f_1" ><label for="kode_jenis">Jenis Faskes/BLK* :</label></div>
                <div class="f_2"><input type="text" id="kode_jenis" name="kode_jenis" maxlength="100" style="width:265px;background-color:<?=$i_mcolor;?>" value="<?=$nama_jenis;?>" <?=$i_readonly;?>/></div>
                <div class="f_1"><label for="kode_jenis_detil">Sub Jenis Faskes/BLK* :</label></div>
                <div class="f_2"><input type="text" id="kode_jenis_detil" name="kode_jenis_detil" maxlength="100" style="width:265px;background-color:<?=$i_mcolor;?>" value="<?=$nama_jenis_detil;?>" <?=$i_readonly;?>/></div>
                <?php if($ls_kode_jenis=='J02'){?>
                <div class="f_1"><label for="kelas_perawatan">Kelas Perawatan* :</label></div>
                <div class="f_2"><input type="text" id="kelas_perawatan" name="kelas_perawatan" maxlength="100" style="width:265px;background-color:<?=$i_mcolor;?>" value="<?=$ls_kelas_perawatan;?>" <?=$i_readonly;?>/></div>
                <?php }?>
                </div>
                <div class="f_1">&nbsp;</div>
                <div class="f_2"><input type="checkbox" id="umum" name="umum" value="Y" <?php echo ($ls_flag_umum=='Y')?"checked":"";?> tabindex="17"/> <label for="umum">Umum</label> &nbsp; &nbsp;
                                <input type="checkbox" id="salin" name="salin" value="Y"  <?php echo ($ls_flag_salin=='Y')?"checked":"";?> tabindex="18"/> <label for="salin">Salin</label></div>
                <div class="f_1" >&nbsp;</div>
                <div class="f_2"><input type="checkbox" id="gigi" name="gigi" value="Y"  <?php echo ($ls_flag_gigi=='Y')?"checked":"";?> tabindex="19"/> <label for="gigi">Gigi</label> &nbsp; &nbsp; &nbsp; &nbsp;
                                <input type="checkbox" id="regweb" name="regweb" value="Y"  <?php echo ($ls_flag_reg_website=='Y')?"checked":"";?> tabindex="20"/> <label for="regweb">Reg Website</label></div>
                <div class="f_1" ><label for="keterangan">Keterangan :</label></div>
                <div class="f_2"><textarea id="keterangan" name="keterangan" rows="2" maxlength="300" style="width:265px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?> ><?=$ls_alamat_pemilik;?></textarea></div>
                <!--
                <div class="f_1" ><label for="tgl_aktif">Tanggal Aktif :</label></div>
                <div class="f_2"><input type="text" id="tgl_aktif" name="tgl_aktif" style="100px;background-color:#e9e9e9;" tabindex="22" value="<?=$ls_tgl_aktif;?>" readonly />
                <input type="image" align="top" onclick="return showCalendar('tgl_aktif', 'dd-mm-y');" src="../../images/calendar.gif" disabled="disabled"/>
                </div>
                <div class="f_1" ><label for="tgl_non_aktif">Tanggal Non Aktif :</label></div>
                <div class="f_2"><input type="text" id="tgl_non_aktif" name="tgl_non_aktif" style="100px;background-color:#e9e9e9;" tabindex="23" value="<?=$ls_tgl_nonaktif;?>" readonly/>
                <input type="image" align="top" onclick="return showCalendar('tgl_non_aktif', 'dd-mm-y');" src="../../images/calendar.gif" disabled="disabled"/>
                </div>
                -->
            </td>
        </tr>
    </table>
</fieldset>
<fieldset><legend><b>Informasi Pemilik</b></legend>
    <table border='0' width="100%">
        <tr>
            <td width="50%" valign="top">
                <div class="f_1" ><label for="nama_pemilik">Nama* :</label></div>
                <div class="f_2"><input type="text" id="nama_pemilik" maxlength="100" name="nama_pemilik" style="width:265px;background-color:<?=$i_mcolor;?>" <?=$i_readonly;?> value="<?=$ls_nama_pemilik;?>" /></div>
                <div class="f_1" ><label for="alamat_pemilik">Alamat Lengkap* :</label></div>   
                <div class="f_2"><textarea id="alamat_pemilik" maxlength="300" name="alamat_pemilik" style="width:265px;background-color:<?=$i_mcolor;?>" rows="2" <?=$i_readonly;?>><?=$ls_alamat_pemilik;?></textarea></div>
                <div class="f_1" ><label for="rt_pemilik">RT/ RW :</label></div>
                <div class="f_2"><input type="text" id="rt_pemilik" maxlength="5" name="rt_pemilik" style="width:30px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$ls_rt_pemilik;?>" />/ <input type="text" id="rw_pemilik" maxlength="5" name="rw_pemilik" style="width:30px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$ls_rw_pemilik;?>" /></div>
                <div class="f_1" ><label for="kode_pos_pemilik">Kode Pos* :</label></div>
                <div class="f_2"><input type="text" id="kode_pos_pemilik" name="kode_pos_pemilik" style="width:90px;background-color:<?=$i_mcolor;?>" readonly  value="<?=$ls_kode_pos_pemilik;?>"/>
                <?php if(!$v_faskes){?>
                <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2401_lov_pos.php?p=tc5001.php&a=formreg&b=kode_kelurahan_pemilik&c=nama_kelurahan_pemilik&d=kode_kecamatan_pemilik&e=nama_kecamatan_pemilik&f=kode_kabupaten_pemilik&g=nama_kabupaten_pemilik&h=kode_propinsi_pemilik&j=nama_propinsi_pemilik&k=kode_pos_pemilik','_faskeslovprop',800,500,1)"  >							
                <img src="../../images/help.png" alt="Cari Kabupaten" border="0" align="absmiddle"></a>	
                <?php }?>
                </div>
                <input type="hidden" id="kode_kelurahan_pemilik" name="kode_kelurahan_pemilik" value="<?=$ls_kode_kelurahan_pemilik;?>" />
                <input type="hidden" id="kode_kecamatan_pemilik" name="kode_kecamatan_pemilik" value="<?=$ls_kode_kecamatan_pemilik;?>" />
                <input type="hidden" id="kode_kabupaten_pemilik" name="kode_kabupaten_pemilik" value="<?=$ls_kode_kabupaten_pemilik;?>" />
                <input type="hidden" id="kode_propinsi_pemilik" name="kode_propinsi_pemilik" />
                <input type="hidden" id="nama_propinsi_pemilik" name="nama_propinsi_pemilik" />
                <div class="f_1" ><label for="nama_kelurahan_pemilik">Kelurahan :</label></div>
                <div class="f_2"><input type="text" id="nama_kelurahan_pemilik" name="nama_kelurahan_pemilik" style="width:220px;background-color:#e9e9e9;" readonly value="<?=$ls_nama_kelurahan_pemilik;?>"/></div>
                <div class="f_1" ><label for="nama_kecamatan_pemilik">Kecamatan :</label></div>
                <div class="f_2"><input type="text" id="nama_kecamatan_pemilik" name="nama_kecamatan_pemilik" style="width:220px;background-color:#e9e9e9;" readonly value="<?=$ls_nama_kecamatan_pemilik;?>"/></div>
                <div class="f_1" ><label for="nama_kabupaten_pemilik">Kabupaten :</label></div>
                <div class="f_2"><input type="text" id="nama_kabupaten_pemilik" name="nama_kabupaten_pemilik" style="width:220px;background-color:#e9e9e9;" readonly value="<?=$ls_nama_kabupaten_pemilik;?>"/></div>
            </td>
            <td valign="top">
                <div class="f_1" ><label for="bagian_pic">Bagian PIC :</label></div>
                <div class="f_2"><input type="text" id="bagian_pic" name="bagian_pic" style="width:265px;background-color:<?=$i_mcolor;?>" maxlength="20" <?=$i_readonly;?> value="<?=$ls_bagian_pic;?>" /></div>
                <div class="f_1" ><label for="telp_area_pemilik">No. Telepon :</label></div>
                <div class="f_2"><input type="text" id="telp_area_pemilik" name="telp_area_pemilik" style="width:40px;background-color:<?=$i_ocolor;?>" maxlength="5" <?=$i_readonly;?>  value="<?=$ls_telepon_area_pemilik;?>" /> <input type="text" id="telp_pemilik" name="telp_pemilik" style="width:170px;background-color:<?=$i_ocolor;?>" maxlength="20" <?=$i_readonly;?> value="<?=$ls_telepon_pemilik;?>" /></div>
                <div class="f_1" ><label for="telepon_ext_pemilik">Telepon Ext. :</label></div>
                <div class="f_2"><input type="text" id="telepon_ext_pemilik" name="telepon_ext_pemilik" style="width:100px;background-color:<?=$i_ocolor;?>" value="<?=$ls_telepon_ext_pemilik;?>" <?=$i_ocolor;?> maxlength="5" /> </div>
                <div class="f_1" ><label for="fax_area_pemilik">Fax :</label></div>
                <div class="f_2"><input type="text" id="fax_area_pemilik" name="fax_area_pemilik" style="width:40px;background-color:<?=$i_ocolor;?>" value="<?=$ls_fax_area_pemilik;?>" maxlength="5" <?=$i_readonly;?>/> <input type="text" id="fax_pemilik" name="fax_pemilik" style="width:170px;background-color:<?=$i_ocolor;?>" value="<?=$ls_fax_pemilik;?>" maxlength="20"  <?=$i_readonly;?>/></div>
                <div class="f_1" ><label for="email_pemilik">Alamat Email :</label></div>
                <div class="f_2"><input type="text" id="email_pemilik" name="email_pemilik" style="width:265px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$ls_email_pemilik;?>" maxlength="200" /></div>
                <div>&nbsp;</div>
                <div class="f_1" ><label for="kode_kepemilikan">Tipe Pemilik :</label></div>
                <div class="f_2"><input type="text" id="kode_pemilik" name="kode_pemilik" style="width:265px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$nama_kepemilikan;?>" maxlength="200" /></div>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset><legend><b>Informasi Pembayaran</b></legend>
    <table border='0' width="100%">
        <tr>
            <td width="50%" valign="top">
                <div class="f_1" ><label for="paymethod">Metode Pembayaran :</label></div>
                <div class="f_2"><input type="text" id="paymethod" name="paymethod" style="width:265px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$nama_metode_pembayaran;?>" maxlength="200" /></div>
                <div class="f_1" ><label for="bankcode">Bank Penerima :</label></div>
                <div class="f_2"><input type="text" id="bankcode" name="bankcode" style="width:265px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$ls_nama_bank_pembayaran;?>" maxlength="200" /></div>
            </td>
            <td width="50%" valign="top">
                <div class="f_1" ><label for="norek">No. Rekening :</label></div>
                <div class="f_2"><input type="text" id="norek" name="norek" maxlength="100" style="width:160px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$ls_no_rekening_pembayaran;?>" /></div>                        
                <div class="f_1" ><label for="namarek">Nama Rekening :</label></div>
                <div class="f_2"><input type="text" id="namarek" name="namarek" maxlength="100" style="width:265px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?> value="<?=$ls_nama_rekening_pembayaran;?>"  /></div>                        
            </td>
        </tr>
    </table>
</fieldset>
<fieldset><legend><b>PIC Kantor Cabang</b></legend>
    <table border="0" width="100%">
        <td width="50%" valign="top">
            <!-- <div class="f_1"><label for="kacab">Kantor Cabang* :</label></div>
            <div class="f_2">
                <input type="text" id="kode_nama_kantor" name="kode_nama_kantor" style="width:200px; background-color:<?=$i_mcolor;?>" readonly  value="<?=$kode_nama_kantor;?>"/>
            </div> -->
            <div class="f_1" ><label for="nm_pic_kacab">Nama PIC Kantor Cabang :</label></div>
            <div class="f_2">
                <input type="text" id="nm_pic_kacab" name="nm_pic_kacab" style="width: 200px; background-color:<?=$i_mcolor;?>" value="<?=$kode_user_nama;?>" size="20" readonly />
            </div>
            <div class="f_1" ><label for="handphone_pic_kacab">No Handphone PIC Cabang:</label></div>
            <div class="f_2"><input type="text" id="handphone_pic_kacab" name="handphone_pic_kacab" maxlength="20" style="width:140px;background-color:<?=$i_mcolor;?>" value="<?=$ls_handphone_pic_kacab;?>" <?=$i_readonly;?>/></div>
        </td>
    </table>
</fieldset>
<fieldset><legend><b>Petugas</b></legend>
    <table border='0' width="100%">
        <tr>
            <td width="50%" valign="top">
                <div class="f_1" ><label for="createdby">Petugas Submit :</label></div>
                <div class="f_2"><input type="text" id="createdby" name="createdby" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_petugas_submit;?>" /></div>
                <div class="f_1" ><label for="modifiedby">Petugas Ubah :</label></div>
                <div class="f_2"><input type="text" id="modifiedby" name="modifiedby" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_petugas_ubah;?>" /></div>
                <div class="f_1" ><label for="rekamby">Petugas Rekam :</label></div>
                <div class="f_2"><input type="text" id="rekamby" name="rekamby" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_petugas_rekam;?>" /></div>
            </td>
            <td width="50%" valign="top">
                <div class="f_1" ><label for="createddate">Tanggal Submit :</label></div>
                <div class="f_2"><input type="text" id="createddate" name="createddate" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_tgl_submit;?>" /></div>
                <div class="f_1" ><label for="modifiedate">Tanggal Ubah :</label></div>
                <div class="f_2"><input type="text" id="modifiedate" name="modifiedate" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_tgl_ubah;?>" /></div>
                <div class="f_1" ><label for="rekamdate">Tanggal Rekam :</label></div>
                <div class="f_2"><input type="text" id="rekamdate" name="rekamdate" style="width:140px;background-color:#e9e9e9;" readonly value="<?=$ls_tgl_rekam;?>" /></div>
            </td>
        </tr>
    </table>
</fieldset>
</form>
<?php }else if($task_no=="IKS"){?>
<table class="table table-striped table-bordered row-border hover responsive-table" id="mydata" cellspacing="0" width="100%">
    <thead>
    </thead>
    <tbody id="listdata">
    </tbody>
    </table>
<?php }else if($task_no=="Lamp"){?>
<span style="color:#FF0000" id="lamp_error"></span>
<div class="clear"></div>
<table class="table table-striped table-bordered row-border hover responsive-table" id="mydata" cellspacing="0" width="100%">
    <thead>
    </thead>
    <tbody id="listdata">
    </tbody>
    </table>

<?php }?>
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">
var actionState=0;
var curBtn=1;
var kodeIKS='';
var noIKS='';
var statusIKS='';
var adendumIKS='T';
$(document).ready(function(){ 
    <?php if($task_no=="IKS"||$task_no=="Lamp"){?>
        loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'<?=$dataid;?>');
    <?php }?>
    $("#btnapprove").click(function(){
        approve_status('<?=$dataid;?>',$("#statusapprove").val());
    });
});
<?php if($task_no=="IKS"){?>
var tblcolIKS = [
            { "title"     : "Action",
        "data"      : "NO_URUT",
        "width" : "10",
        "render"    : function(d,t,r){
            return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataiks(\''+r['KODE_IKS']+'\',\''+r['NO_IKS']+'\',\''+r['KODE_STATUS_IKS']+'\',\''+r['CAN_ADENDUM']+'\');" />';
        }
        },
        { "title"     : "NO IKS",
        "data"      : "NO_IKS" },
        { "title"     : "Tgl Awal",
        "data"      : "TGL_AWAL_IKS" },
        { "title"     : "Tgl Akhir",
        "data"      : "TGL_AKHIR_IKS" },
        { "title"     : "Status",
        "data"      : "STATUS_IKS" },
        { "title"     : "Masa Addendum",
        "data"      : "CAN_ADENDUM" },
        { "title"     : "Non Aktif",
        "data"      : "STATUS_NA" },
        { "title"     : "Alasan NA",
        "data"      : "ALASAN_NA" },
        { "title"     : "No Adendum",
        "data"      : "NO_ADDENDUM" }
    ];
window.RefreshIKS=function(){
    loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'<?=$dataid;?>');
    kodeIKS='';
    noIKS='';
    statusIKS='';

}
<?php }else if($task_no=="Lamp"){?>
    var tblcolLamp = [
        { "title"     : "Action",
        "data"      : "NO_URUT"
        },
        { "title"     : "NO IKS",
        "data"      : "NO_IKS" },
        { "title"     : "Nama File",
        "data"      : "NAMA_FILE",
        "render"    : function(d,t,r){
            return '<a href="../ajax/<?=$php_file_name;?>_download_lampiran.php?kd='+r['KODE_LAMPIRAN']+'" target="_lamp">'+r['NAMA_FILE']+'</a>';
            }
        },
        { "title"     : "Tgl Awal",
        "data"      : "TGL_AWAL_IKS" },
        { "title"     : "Tgl Akhir",
        "data"      : "TGL_AKHIR_IKS" },
        { "title"     : "Tgl Rekam",
        "data"      : "TGL_REKAM" },
        { "title"     : "Petugas Rekam",
        "data"      : "PETUGAS_REKAM" }
    ];


<?php }?>

<?php if($task_no=="Lamp" || $task_no=="IKS"){?>
function loadData(p_cat,p_columns,p_search1)
{
    preload(true);
	window.mydatatable = $('#mydata').DataTable({
		"scrollCollapse"	: true,
		"paging"			: true,
		'sPaginationType'	: 'full_numbers',
		scrollY				: "300px",
        scrollX				: true,
  		"processing"		: true,
		"serverSide"		: true,
		"search"			: {
		    "regex": true
		},
		select			: true,
		"searching"			: false,
		"destroy"			: true,
        "ajax"				: {
        	"url"	: "../ajax/<?=$php_file_name;?>_query.php",
        	"type": "POST",
        	"data" : function(e) { 
        		e.TYPE = p_cat;
        		e.SEARCHA = p_search1;
        	//	e.keyword = window.f_keyword;
        		
        	},complete : function(){

        		preload(false);
        	},error: function (){
            	alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
            	preload(false);
            }
        },
        "columns": p_columns
    });
}
function Right(str, n){
    if (n <= 0)
       return "";
    else if (n > String(str).length)
       return str;
    else {
       var iLen = String(str).length;
       return String(str).substring(iLen, iLen - n);
    }
}
<?php }?>
function approve_status(p_faskes,p_status)
{
    if(confirm('Approve <?php echo $ls_kode_request=='B'?'Pembatalan Status Non Aktif':($ls_kode_request=='N'?'Pengajuan Status Non Aktif':'');?> Faskes/ BLK?'))
    {
        preload(true);
        $.ajax({
            type: 'POST',
            url: "../ajax/<?=$php_file_name;?>_action.php?"+Math.random(),
            data: {formregact:'ApproveNonAktif',key1:p_faskes,key2:p_status},
            success: function(ajaxdata){ 
                preload(false);
                //console.log($('#formreg').serialize());    
                if($.trim(ajaxdata)!="") alert(ajaxdata);
                else{
                    window.parent.Ext.notify.msg('Approval status NonAktif sukses!','');
                    window.location="<?=$php_file_name;?>.php";
                }
            },
            error:function(){
                alert("error saving data!");
                preload(false);
            }
        });
    }
}
</script>   
<?php /*****end LOCAL JAVASCRIPT*********************************/?>
