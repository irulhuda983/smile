
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
$ls_max_tertanggung=0;
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
$sql = "select kode_kantor, nama_kantor from sijstk.ms_kantor ".
"where kode_tipe not in ('0','1') ".
"start with kode_kantor = '$gs_kantor_aktif' ".
"connect by prior kode_kantor = kode_kantor_induk";
$DB->parse($sql);
$DB->execute();
$kode_kantor="";
while($row = $DB->nextrow())
    $kode_kantor .= "<option ". ($row["KODE_KANTOR"]==$ls_kode_kantor?" selected ":"") . "value=\"{$row["KODE_KANTOR"]}\">{$row["KODE_KANTOR"]} - {$row["NAMA_KANTOR"]}</option>";
// echo $kode_kantor; die;
$sql1 = "select kode_kantor, nama_kantor from sijstk.ms_kantor ".
"where kode_kantor = '$gs_kantor_aktif' ";
$DB->parse($sql1);
$DB->execute();
$kode_nama_kantor_se="";
while($row = $DB->nextrow()) 
    $kode_nama_kantor_se .= $row["KODE_KANTOR"] . ' - ' . $row["NAMA_KANTOR"];
    
$f_daftar=$task_code=="New"?true:false;

$v_faskes=$task_code=="View"?true:false;
$v_iks=$task_code=="View"?true:false;
$v_lamp=$task_code=="View"?true:false;


//if($task_no=='Faskes'){
    if($task_code=="View" || $task_code=="Edit")
    {
        $sql = "SELECT KODE_FASKES, KODE_TIPE,KODE_KANTOR,KODE_PEMBINA,NAMA_FASKES,ALAMAT,RT,RW,KODE_KELURAHAN,KODE_KECAMATAN,
        KODE_KABUPATEN,KODE_POS,NAMA_PIC,HANDPHONE_PIC,TO_CHAR(TGL_NONAKTIF,'DD/MM/YYYY') TGL_NONAKTIF,TO_CHAR(TGL_AKTIF,'DD/MM/YYYY') TGL_AKTIF,
        KODE_STATUS,KODE_JENIS,KODE_JENIS_DETIL,NO_IJIN_PRAKTEK,NPWP,MAX_TERTANGGUNG,FLAG_UMUM,FLAG_GIGI,FLAG_SALIN,FLAG_REG_WEBSITE,KETERANGAN,
        NAMA_PEMILIK,ALAMAT_PEMILIK,RT_PEMILIK,RW_PEMILIK,KODE_KELURAHAN_PEMILIK,KODE_KECAMATAN_PEMILIK,KODE_KABUPATEN_PEMILIK,KODE_POS_PEMILIK,
        TELEPON_AREA_PEMILIK,TELEPON_PEMILIK,TELEPON_EXT_PEMILIK,FAX_AREA_PEMILIK,FAX_PEMILIK,EMAIL_PEMILIK,KODE_KEPEMILIKAN,KODE_METODE_PEMBAYARAN,
        KODE_BANK_PEMBAYARAN,NAMA_REKENING_PEMBAYARAN,NO_REKENING_PEMBAYARAN,TO_CHAR(TGL_SUBMIT,'DD/MM/YYYY') TGL_SUBMIT,
        PETUGAS_SUBMIT,TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM,PETUGAS_REKAM,TO_CHAR(TGL_UBAH,'DD/MM/YYYY') TGL_UBAH,PETUGAS_UBAH,
        TELEPON_AREA_PIC,TELEPON_PIC,TELEPON_EXT_PIC,KELAS_PERAWATAN,BAGIAN_PIC,LONGITUDE,LATITUDE,
        KODE_KANTOR || ' - ' || (
        SELECT
            NAMA_KANTOR
        FROM
            MS.MS_KANTOR
        WHERE 
            KODE_KANTOR = '{$_SESSION['gs_kantor_aktif']}') AS KODE_NAMA_KACAB,
		KODE_PIC_CABANG,
		KODE_PIC_CABANG || ' - ' || (SELECT NAMA_USER FROM MS.SC_USER A WHERE KODE_PIC_CABANG = A.KODE_USER) AS KODE_USER_NAMA,
        KONTAK_PIC_CABANG
        FROM {$schema}.TC_FASKES
        WHERE KODE_FASKES='{$dataid}' AND kode_kantor in (select x.kode_kantor from sijstk.ms_kantor x
                                where x.kode_tipe not in ('0','1')
                                start with x.kode_kantor = '{$_SESSION['gs_kantor_aktif']}'
                                connect by prior x.kode_kantor = x.kode_kantor_induk)"; // echo $sql;
        //echo $sql;
        $sql_edit=$sql;
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
            $ls_longitude           =$row['LONGITUDE'];
            $ls_latitude            =$row['LATITUDE'];
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
    $sql = "select KODE_TIPE,NAMA_TIPE from {$schema}.TC_kode_TIPE where status_nonaktif='T' or KODE_TIPE='{$ls_kode_tipe}'";
    $DB->parse($sql);
    $DB->execute();
    $kode_tipe= "";
    $irow=0;
    while($row = $DB->nextrow()){
        if($irow++==0 && $ls_kode_tipe=='')
            $ls_kode_tipe=$row['KODE_TIPE'];
        $kode_tipe .= "<option ". ($row["KODE_TIPE"]==$ls_kode_tipe?" selected ":"") . "value=\"{$row["KODE_TIPE"]}\">{$row["NAMA_TIPE"]}</option>";
    }

    $sql = "select KODE_KEPEMILIKAN,NAMA_KEPEMILIKAN from {$schema}.tc_kode_kepemilikan where status_nonaktif='T'";
    $DB->parse($sql);
    $DB->execute();
    $kode_kepemilikan="";
    while($row = $DB->nextrow())
        $kode_kepemilikan .= "<option ". ($row["KODE_KEPEMILIKAN"]==$ls_kode_kepemilikan?" selected ":"") . "value=\"{$row["KODE_KEPEMILIKAN"]}\">{$row["NAMA_KEPEMILIKAN"]}</option>";
    // get kode status
    //$ls_kode_status='';
    $sql = "select KODE_STATUS,NAMA_STATUS from {$schema}.tc_kode_status where status_nonaktif='T'";
    $DB->parse($sql);
    $DB->execute();
    $kode_status="";
    while($row = $DB->nextrow())
        $kode_status .= "<option ". ($row["KODE_STATUS"]==$ls_kode_status?" selected ":"") . "value=\"".$row["KODE_STATUS"]."\">".$row["NAMA_STATUS"]."</option>";

        // get kode metode bayar
    //$ls_kode_metode_pembayaran='';
    $sql = "select KODE_METODE_PEMBAYARAN,NAMA_METODE_PEMBAYARAN from {$schema}.tc_kode_metode_pembayaran where status_nonaktif='T'";
    $DB->parse($sql);
    $DB->execute();
    $kode_metode_pembayaran="";
    while($row = $DB->nextrow())
        $kode_metode_pembayaran .= "<option ". ($row["KODE_METODE_PEMBAYARAN"]==$ls_kode_metode_pembayaran?" selected ":"") . "value=\"".$row["KODE_METODE_PEMBAYARAN"]."\">".$row["NAMA_METODE_PEMBAYARAN"]."</option>";

    // get kode bank bayar
    //$ls_kode_metode_pembayaran='';
    $sql = "select KODE_BANK  KODE_BANK_PEMBAYARAN,NAMA_BANK NAMA_BANK_PEMBAYARAN from {$schema}.ms_bank where AKTIF='Y' order by NAMA_BANK";
    $DB->parse($sql);
    $DB->execute();
    $kode_bank_pembayaran="";
    while($row = $DB->nextrow())
        $kode_bank_pembayaran .= "<option ". ($row["KODE_BANK_PEMBAYARAN"]==$ls_kode_bank_pembayaran?" selected ":"") . "value=\"".$row["KODE_BANK_PEMBAYARAN"]."\">".$row["NAMA_BANK_PEMBAYARAN"]."</option>";

$v_faskes=($task_code=='Edit' && ($ls_kode_status=='ST3' || $ls_kode_status=='ST5'|| $ls_kode_status=='ST6'))?true:$v_faskes;
$v_iks=($task_code=="Edit" && $ls_kode_status=='ST6')?true:$v_iks;
$v_lamp=($task_code=="Edit" && $ls_kode_status=='ST6')?true:$v_lamp;
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
    const maks_file_size = 5; // dalam MB
    const maks_file_name_length = 75;
</script>
<?php /*****end LOCAL JAVASCRIPT*********************************/?>

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
                <div class="f_2"><select id="kode_tipe" name="kode_tipe" style="background-color:<?=$i_mcolor;?>" <?=$i_readonly;?> ><?=$kode_tipe;?></select></div>
                <div class="f_1" ><label for="kode_kantor">Kantor :</label></div>
                <div class="f_2"><select id="kode_kantor" name="kode_kantor" style="background-color:<?=$i_mcolor;?>" <?=$i_readonly;?>><?=$kode_kantor;?></select></div>
                <div class="f_1" ><label for="alamat">Alamat Lengkap* :</label></div>
                <div class="f_2"><textarea id="alamat" name="alamat" maxlength="300" style="width:265px;background-color:<?=$i_mcolor;?>" rows="2" <?=$i_readonly;?>><?=$ls_alamat;?></textarea></div>
                <div class="f_1" ><label for="rt">RT/ RW :</label></div>
                <div class="f_2"><input type="text" id="rt" name="rt" maxlength="5" style="width:30px;background-color:<?=$i_ocolor;?>" value="<?=$ls_rt;?>" <?=$i_readonly;?> />/ <input type="text" id="rw" name="rw" maxlength="5" style="width:30px;background-color:<?=$i_ocolor;?>"  value="<?=$ls_rw;?>"  <?=$i_readonly;?> /></div>
                <div class="f_1" ><label for="kode_pos">Kode Pos* :</label></div>
                <div class="f_2"><input type="text" id="kode_pos" name="kode_pos" style="width:90px;background-color: <?=$i_mcolor;?>" readonly   value="<?=$ls_kode_pos;?>"/>
                <?php if(!$v_faskes){?>
                <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2401_lov_pos.php?p=tc5001.php&a=formreg&b=kode_kelurahan&c=nama_kelurahan&d=kode_kecamatan&e=nama_kecamatan&f=kode_kabupaten&g=nama_kabupaten&h=kode_propinsi&j=nama_propinsi&k=kode_pos','_faskeslovprop',800,500,1)" tabindex="8">
                <img src="../../images/help.png" alt="Cari Kabupaten" border="0" align="absmiddle"></a>
                <?php }?>
                </div>
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
                <div class="f_2"><input type="text" id="latitude" name="latitude" style="width:140px;background-color:<?=$i_mcolor;?>"  value="<?=$ls_latitude;?>"/></div>
                <div class="f_1" ><label for="longitude">Longitude* :</label></div>
                <div class="f_2"><input type="text" id="longitude" name="longitude" style="width:140px;background-color:<?=$i_mcolor;?>"  value="<?=$ls_longitude;?>"/></div>
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
                <div class="f_2"><input type="text" id="nama_pic" name="nama_pic" maxlength="100" style="width:265px;background-color:<?=$i_mcolor;?>" value="<?=$ls_nama_pic;?>" <?=$i_readonly;?>/></div>

                <div class="f_1" ><label for="bagian_pic">Bagian PIC :</label></div>
                <div class="f_2"><input type="text" id="bagian_pic" name="bagian_pic" style="width:265px;background-color:<?=$i_mcolor;?>" maxlength="20" <?=$i_readonly;?> value="<?=$ls_bagian_pic;?>" /></div>
                <div class="f_1" ><label for="handphone_pic">Handphone PIC :</label></div>
                <div class="f_2"><input type="text" id="handphone_pic" name="handphone_pic" maxlength="20" style="width:140px;background-color:<?=$i_mcolor;?>" value="<?=$ls_handphone_pic;?>" <?=$i_readonly;?>/></div>
                <div class="f_1" ><label for="no_ijin_praktek">No. Ijin Praktek* :</label></div>
                <div class="f_2"><input type="text" id="no_ijin_praktek" name="no_ijin_praktek" maxlength="100" style="width:265px;background-color:<?=$i_mcolor;?>" value="<?=$ls_no_ijin_praktek;?>" <?=$i_readonly;?>/></div>
                <div class="f_1" ><label for="npwp">NPWP Faskes/BLK* :</label></div>
                <div class="f_2"><input type="text" id="npwp" name="npwp" maxlength="15" style="width:140px;background-color:<?=$i_mcolor;?>" <?=$i_readonly;?>  value="<?=$ls_npwp;?>"/></div>
                <!--
                <div class="f_1" ><label for="max_tertanggung">Max Tertanggung :</label></div>
                <div class="f_2"><input type="text" id="max_tertanggung" name="max_tertanggung" style="width:125px;background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>  value="<?=$ls_max_tertanggung;?>"/></div> -->
                <div class="f_1" ><label for="kode_jenis">Jenis Faskes/BLK* :</label></div>
                <div class="f_2"><select id="kode_jenis" name="kode_jenis" tabindex="15" style="background-color:<?=$i_mcolor;?>" <?=$i_readonly;?> ><?=$kode_jenis;?></select></div>
                <div class="f_1"><label for="kode_jenis_detil">Sub Jenis Faskes/BLK* :</label></div>
                <div class="f_2"><select id="kode_jenis_detil" name="kode_jenis_detil" <?=$i_readonly;?> style="background-color:<?=$i_ocolor;?>"></select></div>
                <div id="div_kelas_perawatan" style="display:none">
                <div class="f_1"><label for="kelas_perawatan">Kelas Perawatan* :</label></div>
                <div class="f_2">
                <select id="kelas_perawatan" name="kelas_perawatan" tabindex="15" style="background-color:<?=$i_ocolor;?>" <?=$i_readonly;?> >
                    <option value="KELAS 1" <?php echo $ls_kelas_perawatan=='KELAS 1'?'selected':'';?>>KELAS 1</option>
                    <option value="KELAS 2" <?php echo $ls_kelas_perawatan=='KELAS 2'?'selected':'';?>>KELAS 2</option>
                    <option value="KELAS 3" <?php echo $ls_kelas_perawatan=='KELAS 3'?'selected':'';?>>KELAS 3</option>
                </select>
                </div>
                </div>
                <!--
                <div class="f_1">&nbsp;</div>
                <div class="f_2"><input type="checkbox" id="umum" name="umum" value="Y" <?php echo ($ls_flag_umum=='Y')?"checked":"";?> tabindex="17"/> <label for="umum">Umum</label> &nbsp; &nbsp;
                                <input type="checkbox" id="salin" name="salin" value="Y"  <?php echo ($ls_flag_salin=='Y')?"checked":"";?> tabindex="18"/> <label for="salin">Salin</label></div>
                <div class="f_1" >&nbsp;</div>
                <div class="f_2"><input type="checkbox" id="gigi" name="gigi" value="Y"  <?php echo ($ls_flag_gigi=='Y')?"checked":"";?> tabindex="19"/> <label for="gigi">Gigi</label> &nbsp; &nbsp; &nbsp; &nbsp;
                                <input type="checkbox" id="regweb" name="regweb" value="Y"  <?php echo ($ls_flag_reg_website=='Y')?"checked":"";?> tabindex="20"/> <label for="regweb">Reg Website</label></div>-->
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
                <div class="f_2"><select id="kode_kepemilikan" name="kode_kepemilikan"  style="background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>><?=$kode_kepemilikan;?></select></div>
            </td>
        </tr>
    </table>
</fieldset>
<fieldset><legend><b>Informasi Pembayaran</b></legend>
    <table border='0' width="100%">
        <tr>
            <td width="50%" valign="top">
                <div class="f_1" ><label for="paymethod">Metode Pembayaran :</label></div>
                <div class="f_2"><select id="paymethod" name="paymethod"  style="background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>><?=$kode_metode_pembayaran;?></select></div>
                <div class="f_1" ><label for="bankcode">Bank Penerima :</label></div>
                <div class="f_2"><select id="bankcode" name="bankcode" style="background-color:<?=$i_ocolor;?>" <?=$i_readonly;?>><?=$kode_bank_pembayaran;?></select></div>
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
    <table border='0' width="100%">
        <tr>
            <td width="50%" valign="top">
                <!-- <div class="f_1" ><label for="kacab">Kantor Cabang* :</label></div>
                <div class="f_2">
                    <? if($task_code=='New') {?>
                        <input type="text" id="kode_nama_kantor" name="kode_nama_kantor" style="width:200px; background-color:<?=$i_mcolor;?>" readonly  value="<?=$kode_nama_kantor_se;?>"/>
	                    <input type="text" name="kode_kacab" id="kode_kacab"  style="width:50px; background-color:#ffff99;" tabindex="1" value="<?=$ls_kode_kantor;?>" readonly/> 
                        <label for="kacab"> - </label>
	                    <input type="text" name="nama_kacab" id="nama_kacab"  style="width:150px; background-color:#ffff99;" tabindex="1" value="<?=$ls_kode_kantor;?>" readonly/> 
                    <?} else if($task_code=='Edit') {?>
                        <input type="text" id="kode_nama_kantor" name="kode_nama_kantor" style="width:200px; background-color:<?=$i_mcolor;?>" readonly  value="<?=$kode_nama_kantor;?>"/>
	                    <input type="hidden" name="kode_kacab" id="kode_kacab"  style="background-color:#ffff99;" tabindex="1" value="<?=$ls_kode_kantor;?>" readonly/> 
                    <?} else{?>
                        <input type="text" id="kode_nama_kantor" name="kode_nama_kantor" style="width:200px;background-color:#e9e9e9" readonly  value="<?=$kode_nama_kantor;?>"/>
	                    <input type="hidden" name="kode_kacab" id="kode_kacab"  style="background-color:#ffff99;" tabindex="1" value="<?=$ls_kode_kantor;?>" readonly/>
                    <?}?>
                    <?if($task_code!='View') {?>
	                <a href="#" id="btn_kantor" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2401_lov_kantor.php?p=pn2401.php&a=formreg&b=kode_kacab&c=kode_nama_kantor','',800,500,1)"> 
                    <img src="../../images/help.png" alt="Cari Kabupaten" border="0" align="absmiddle"></a>
                    <?}?>
                </div> -->
                <?if($task_code=='New' || $task_code=='Edit') {?>
                <div class="f_1" ><label for="nm_pic_kacab">Nama PIC Kantor Cabang* :</label></div>
                <div class="f_2">
                    <input type="text" id="nm_pic_kacab" name="nm_pic_kacab" style="width: 265px; background-color:#ffff99" value="<?=$kode_user_nama;?>" size="20" readonly />
                    <input type="hidden" name="kode_pic_cabang" id="kode_pic_cabang" style="background-color:#ffff99;" tabindex="1"  value="<?=$kode_pic_cabang;?>" readonly/>
                    <input type="hidden" name="kode_kacab" id="kode_kacab" style="background-color:#ffff99;" tabindex="1"  value="<?=$ls_kode_kantor;?>" readonly/>
                    <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2401_lov_user.php?p=pn2401.php&a=formreg&b=kode_kacab&c=nm_pic_kacab&d=kode_pic_cabang&e='+$('#kode_kantor').val()+'','',800,500,1)">
                    <img src="../../images/help.png" alt="Cari User Kantor Cabang" border="0" align="absmiddle"></a>
                </div>
                <div class="f_1" ><label for="handphone_pic_kacab">Handphone PIC Cabang* :</label></div>
                <div class="f_2"><input type="text" id="handphone_pic_kacab" name="handphone_pic_kacab" maxlength="20" style="width:140px;background-color:#ffff99" value="<?=$ls_handphone_pic_kacab;?>" <?=$i_readonly;?>/></div>
                <?}else{?>
                <div class="f_1" ><label for="nm_pic_kacab">Nama PIC Kantor Cabang* :</label></div>
                <div class="f_2">
                    <input type="text" id="nm_pic_kacab" name="nm_pic_kacab" style="width: 265px; background-color:#e9e9e9" value="<?=$kode_user_nama;?>" size="20" readonly />
                    <input type="hidden" name="kode_pic_cabang" id="kode_pic_cabang" style="background-color:#e9e9e9;" tabindex="1"  value="<?=$kode_pic_cabang;?>" readonly/>
                    <input type="hidden" name="kode_kacab" id="kode_kacab" style="background-color:#e9e9e9;" tabindex="1"  value="<?=$ls_kode_kantor;?>" readonly/>
                    <a href="#" style="display: none;" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2401_lov_user.php?p=pn2401.php&a=formreg&b=kode_kacab&c=nm_pic_kacab&d=kode_pic_cabang&e='+$('#kode_kantor').val()+'','',800,500,1)">
                    <img src="../../images/help.png" alt="Cari User Kantor Cabang" border="0" align="absmiddle"></a>
                </div>
                <div class="f_1" ><label for="handphone_pic_kacab">Handphone PIC Cabang* :</label></div>
                <div class="f_2"><input type="text" id="handphone_pic_kacab" name="handphone_pic_kacab" maxlength="20" style="width:140px;background-color:#e9e9e9" value="<?=$ls_handphone_pic_kacab;?>" <?=$i_readonly;?>/></div>
                <?}?>
            </td>
        </tr>
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
<br>
<fieldset style="background: #F2F2F2;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
    <ol>
        <span><b>langkah-langkah pencarian longitude dan latitude:</b></span>
        <li>Silahkan memastikan komputer Anda terhubung dengan internet.</li>
        <li>Silahkan mengakses google maps atau w3schools untuk mendapatkan titik lokasi Faskes.</li>
        <li>Silahkan menyalin atau memasukkan Latitude dan Longitude pada inputan.</li>
    </ol>
</fieldset>
</form>
<?php }else if($task_no=="IKS"){?>
<?php if(!$v_iks){?>
<div>
    <input type="button" name="btntbiks" class="btn green" id="btntbiks" value=" Tambah IKS " style="cursor:pointer">
    <input type="button" name="btneditiks" class="btn green" id="btneditiks" value=" Edit IKS " style="cursor:pointer">
    <input type="button" name="btnadendumiks" class="btn green" id="btnadendumiks" value=" Addendum IKS " style="cursor:pointer">
</div>
<?php }?>
<table class="table table-striped table-bordered row-border hover responsive-table" id="mydata" cellspacing="0" width="100%">
    <thead>
    </thead>
    <tbody id="listdata">
    </tbody>
    </table>
<fieldset style="background: #FF0;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
<li>Klik Tombol Tambah IKS untuk menambah data IKS Baru/Perpanjangan</li>
<li>Dokumen baru atau perpanjangan otomatis ter submit ke approval iks jika sudah ada lampiran dokumen iks yang sudah di upload</li>
</fieldset>
<?php }else if($task_no=="Lamp"){?>
<span style="color:#FF0000" id="lamp_error"></span>
<iframe name="iframe_ulamp" id="iframe_ulamp"  src="#" style="postion:fixed;left:-9999;top:-9999;display:none"></iframe>
<form id="form_lampiran" name="form_lampiran" target="iframe_ulamp"  action="../ajax/<?=$php_file_name;?>_action_lampiran.php" method="POST" enctype="multipart/form-data">
<input type="hidden" id="h_kode_faskes" name="h_kode_faskes" value="<?=$dataid;?>" />
<input type="hidden" id="formregact" name="formregact" value="uplamp" />
<input type="hidden" id="h_kode_iks" name="h_kode_iks" />
<div style="float:right">
    <table width="100%">
        <tr>
            <td></td>
            <td>No IKS</td>
            <td>Tgl Awal IKS</td>
            <td>Tgl Akhir IKS</td>
            <?php if(!$v_lamp){?>
            <td>File Name</td>
            <td></td>
            <?php }?>
        </tr>
        <tr>
            <td>
                <a href="#" onclick="NewWindow('../ajax/<?=$php_file_name;?>_lov_iks.php?aid=<?=$dataid;?>&frm=form_lampiran&a=h_kode_iks&b=i_no_iks&c=i_tgl_awal&d=i_tgl_akhir','_faskesloviks',800,500,1)" >
                <img src="../../images/help.png" alt="Cari Kabupaten" border="0" align="absmiddle" /></a>
            </td>
            <td><input type="text" style="width:125px" id="i_no_iks" name="i_no_iks" readonly /></td>
            <td><input type="text" style="width:75px" id="i_tgl_awal" name="i_tgl_awal" readonly /></td>
            <td><input type="text" style="width:75px" id="i_tgl_akhir" name="i_tgl_akhir" readonly /></td>
            <?php if(!$v_lamp){?>
            <td><input type="file" style="width:250px;" id="fname" name="fname" /></td>
            <td><input type="button" name="btnupload" class="btn green" id="btnupload" value=" Tambah Lampiran IKS "></td>
            <?php }?>
        </tr>
    </table>
</div>
</form>
<div class="clear"></div>
<table class="table table-striped table-bordered row-border hover responsive-table" id="mydata" cellspacing="0" width="100%">
    <thead>
    </thead>
    <tbody id="listdata">
    </tbody>
    </table>
<fieldset style="background: #FF0;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
<li>Pilih IKS dan Klik Tombol Tambah Lampiran IKS untuk menambah data lampiran IKS</li>
<li>Maksimal ukuran file yang di upload adalah <span id="span_file_size">5</span>MB</li>
<li>Maksimal panjang nama file adalah <span id="span_file_name_length">75</span> karakter</li>
</fieldset>

<?php }?>
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">
var actionState=0;
var curBtn=1;
var kodeIKS='';
var noIKS='';
var statusIKS='';
var statusIKS1='';
var adendumIKS='T';
var arrField= [
        ['nama_faskes','Nama Faskes',false,'',0,100],
        ['alamat','Alamat Faskes',false,'',0,300],
        ['kode_pos','Kode Pos Faskes',false,'',0,10],
        ['latitude','Latitude',false,'',0,50],
        ['longitude','Longitude',false,'',0,50],
        ['nama_pic','Nama PIC',false,'',0,100],
        ['handphone_pic','No Handphone PIC',false,'N',0,20],
        ['no_ijin_praktek','No Ijin Praktek',false,'',0,100],
        ['npwp','NPWP',false,'N',0,15],
        ['nama_pemilik','Nama Pemilik',false,'',0,100],
        ['alamat_pemilik','Alamat Pemilik',false,'',0,300],
        ['kode_pos_pemilik','Kode Pos Pemilik',false,'',0,10],
        ['kode_jenis','Kode Jenis',false,'',0,50],
        ['bagian_pic','Bagian PIC',false,'',0,100],
        ['bagian_pic','Bagian PIC',false,'',0,100],
        ['nm_pic_kacab','Nama PIC Kantor Cabang',false,'',0,100],
        ['handphone_pic_kacab','Handphone PIC Kantor Cabang',false,'N',0,20]
    ];

function setJenis(par_data){$("#kode_jenis").html(par_data);}
function setJenisDetil(par_data){$("#kode_jenis_detil").html(par_data);}


function saveData()
{
    preload(true);
    $.ajax({
        type: 'POST',
        url: "../ajax/<?=$php_file_name;?>_action.php?"+Math.random(),
        data: $("#formreg").serialize(),
        success: function(ajaxdata){
            preload(false);
            //console.log($('#formreg').serialize());
            if($.trim(ajaxdata)!="") alert(ajaxdata);
            else{
                window.parent.Ext.notify.msg('Penyimpanan data sukses!','');
                window.location='<?=$php_file_name;?>.php?taskno=<?=$task_no;?>&mid=<?=$mid;?>&s=<?=$_GET['s'];?>';
            }
        },
        error:function(){
            alert("error saving data!");
            preload(false);
        }
    });
}
function enableInput(obj_jquery,ena)
{
    if(ena) obj_jquery.prop('disabled', false);
    else obj_jquery.prop('disabled', true);
}
function set_dataiks(p_kode,p_no,p_status,p_status1,p_adendum)
{
    kodeIKS=p_kode;
    noIKS=p_no;
    statusIKS=p_status;
    statusIKS1=p_status1;
    adendumIKS=p_adendum;
}
function showKelasPerawatan(p_val)
{
    if(p_val=="J02") $("#div_kelas_perawatan").show();
    else $("#div_kelas_perawatan").hide();
}
$(document).ready(function(){
    <?php if($task_no=="Faskes"){?>
    lov_subData('getJenis','getJenis','<?=$ls_kode_tipe;?>','<?=$ls_kode_jenis;?>');
    lov_subData('getJenisDetil','getJenisDetil','<?=$ls_kode_jenis;?>','<?=$ls_kode_jenis_detil;?>');
    showKelasPerawatan("<?=$ls_kode_jenis;?>");

    $("#kode_tipe").change(function(){
        lov_subData('getJenis','getJenis',this.value,'');
        setJenisDetil('');
    });
    $("#kode_jenis").change(function(){
        lov_subData('getJenisDetil','getJenisDetil',this.value,'');
        showKelasPerawatan(this.value);
    });
    <?php if($v_faskes){?>
    $("#dbtn_save").hide();
    <?php }?>
    $("#btn_save").click(function(){
        if(confirm('Save new data?'))
            if(checkFieldArray(arrField)==0)
                saveData();
    });
    <?php } ?>
    <?php if($task_no=="IKS"||$task_no=="Lamp"){?>
        loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'<?=$dataid;?>');
        <?php if($task_no=="IKS"){?>
            $("#btntbiks").click(function(){
            NewWindowIKS('<?=$php_file_name;?>_form_iks.php?task=New&dataid=<?=$dataid;?>','_fiks',800,600,1);
            });
            $("#btneditiks").click(function(){ //confirm(statusIKS);
                if(kodeIKS=='' || statusIKS=='1' || (statusIKS=='4' && statusIKS=='4'))
                    NewWindowIKS('<?=$php_file_name;?>_form_iks.php?task=Edit&dataid=<?=$dataid;?>&iks='+kodeIKS+'&noIKS='+noIKS,'_fiks',800,600,1);
                else if( statusIKS1=='4'&& statusIKS=='3')
                    alert('Hanya IKS  yang di tolak oleh 2 approval yang bisa diubah !');
                else
                    alert('Hanya IKS  berstatus baru, meminta persetujuan atau di tolak yang bisa diubah!');
            });
            $("#btnadendumiks").click(function(){// confirm(adendumIKS);
                if(adendumIKS!='Y')
                    alert('Hanya IKS  yg telah disetujui bisa di adendum!');
                else if(kodeIKS!='')
                    NewWindowIKS('<?=$php_file_name;?>_form_iks.php?task=Adendum&dataid=<?=$dataid;?>&iks='+kodeIKS+'&noIKS='+noIKS,'_fiks',800,600,1);
            });
        <?php }?>
        <?php if($task_no=="Lamp"){?>
        initiateUploadLampiran();
        $("#span_file_size").html(maks_file_size);
        $("#span_file_name_length").html(maks_file_name_length);
        <?php }?>
    <?php }?>

    // Attach change event to the dropdown with id "kode_kantor"
    $("#kode_kantor").on("change", function() {
        var selectedValue = $(this).val();
        $("#kode_kacab").val(selectedValue);
    });
});
<?php if($task_no=="IKS"){?>
var tblcolIKS = [
            { "title"     : "Action",
        "data"      : "NO_URUT",
        "width" : "10",
        "render"    : function(d,t,r){
            return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataiks(\''+r['KODE_IKS']+'\',\''+r['NO_IKS']+'\',\''+r['KODE_STATUS_IKS']+'\',\''+r['KODE_STATUS_IKS1']+'\',\''+r['CAN_ADENDUM']+'\');" />';
        }
        },
        { "title"     : "NO IKS",
        "data"      : "NO_IKS" },
        { "title"     : "Tgl Awal",
        "data"      : "TGL_AWAL_IKS" },
        { "title"     : "Tgl Akhir",
        "data"      : "TGL_AKHIR_IKS" },
        { "title"     : "Approval 1",
        "data"      : "STATUS_IKS1" },
        { "title"     : "Keterangan 1",
        "data"      : "ALASAN_APPROVAL" },
        { "title"     : "Approval 2",
        "data"      : "STATUS_IKS2" },
        { "title"     : "Keterangan 2",
        "data"      : "ALASAN_APPROVAL1" },
        { "title"     : "Non Aktif",
        "data"      : "STATUS_NA" },
        { "title"     : "Alasan NA",
        "data"      : "ALASAN_NA" },
        { "title"     : "No Ref Adendum",
        "data"      : "NO_ADDENDUM" }
    ];
window.RefreshIKS=function(){
    loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'<?=$dataid;?>');
    kodeIKS='';
    noIKS='';
    statusIKS='';
    statusIKS1='';

}
<?php }else if($task_no=="Lamp"){?>
    var tblcolLamp = [
        { "title"     : "No",
        "data"      : "NO_URUT"
        },
        { "title"     : "NO IKS",
        "data"      : "NO_IKS" ,
        "render"    : function(d,t,r){
            var strDel=r['NO_IKS']+' <a href="javascript:delLampiran('+r['KODE_IKS']+','+r['KODE_LAMPIRAN']+');"><img src="../../images/file_cancel.gif" /></a>';
            if(r['EDITABLE']=='0')
                return r['NO_IKS'];
            else
                return strDel;
            }
        },
        { "title"     : "Nama File",
        "data"      : "NAMA_FILE",
        "render"    : function(d,t,r){
            return '<a href="../ajax/<?=$php_file_name;?>_download_lampiran.php?kd='+r['KODE_LAMPIRAN']+'&f='+r['KODE_FASKES']+'&i='+r['KODE_IKS']+'" target="_lamp">'+r['NAMA_FILE']+'</a>';
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
                e.SEARCHB = p_cat=='queryLamp'?document.form_lampiran.h_kode_iks.value:'';
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
function NewWindowIKS(mypage,myname,w,h,scroll){
    var winl = (screen.width-w)/2;
    var wint = (screen.height-h)/2;
    var settings  ='height='+h+',';
        settings +='width='+w+',';
        settings +='top='+wint+',';
        settings +='left='+winl+',';
        settings +='scrollbars='+scroll+',';
        settings +='resizable=1';
        settings +='location=0';
        settings +='menubar=0';
    win=window.open(mypage,myname,settings);
    if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
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

function initiateUploadLampiran()
{
    $('#form_lampiran').submit(function(){
        $("#lamp_error").html('');
        var response;
        preload(true);
        var frame_lampiran=$("#iframe_ulamp").load(function(){
            preload(false);
            response=frame_lampiran.contents().find('body');
            if(response.html()!='')
                $("#lamp_error").html(response.html());
            else
            {
                window.parent.Ext.notify.msg('Sukses Upload dokumen!','');
                loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'<?=$dataid;?>');
            }
            frame_lampiran.unbind("load");
            setTimeout(function(){ response.html(''); },1);
        });
    });
    $("#btnupload").click(function(){
        var filename = $('#fname').val().split('\\').pop();
        if($("#i_no_iks").val()=='' )
            alert('Silahkan memilih data IKS nya!');
        else if($("#fname").val()=='' )
            alert('Silahkan pilih file yang akan di unggah!');
        else if( !(Right(String($("#fname").val()),3).toUpperCase()=='PDF' || Right(String($("#fname").val()),3).toUpperCase()=='JPG' || Right(String($("#fname").val()),4).toUpperCase()=='JPEG' ) && $("#fname").val()!='')
            alert('Hanya file bertipe pdf,jpg,jpeg yang bisa di unggah!');
        else if(filename.length>maks_file_name_length)
            alert('Panjang nama file maksimal '+maks_file_name_length+' karakter.');
        else{
            const file_size = ($("#fname")[0].files[0].size / 1024 / 1024).toFixed(2);
            if( file_size > maks_file_size)
                alert('Ukuran file '+ file_size +'MB melebihi batas maksimal ' + maks_file_size + 'MB');
            else {
                if(!confirm('Upload file?')) return 0;
                else $('#form_lampiran').submit();
            }
        }
    });
}
function setIKSlamp(kodeiks,noiks,tglawal,tglakhir)
{
    document.form_lampiran.h_kode_iks.value=kodeiks;
    document.form_lampiran.i_no_iks.value=noiks;
    document.form_lampiran.i_tgl_awal.value=tglawal;
    document.form_lampiran.i_tgl_akhir.value=tglakhir;
    setTimeout(function(){
        loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'<?=$dataid;?>');
    }, 200);
}
function delLampiran(kodeiks,kodelampiran)
{
    if(!confirm('Yakin menghapus lampiran IKS?'))
    preload(true);
    $.ajax({
        type: 'POST',
        url: "../ajax/<?=$php_file_name;?>_action_lampiran.php?"+Math.random(),
        data: { formregact:'delLamp',h_kode_faskes:'<?=$dataid;?>',h_kode_iks:kodeiks,h_kode_iks_lamp:kodelampiran},
        success: function(ajaxdata){
            preload(false);
            alert("sukses menghapus lampiran IKS!");
            loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'<?=$dataid;?>');
        },
        error:function(){
            alert("error menghapus lampiran IKS!");
            preload(false);
        }
    });
}
<?php }?>
</script>
<?php /*****end LOCAL JAVASCRIPT*********************************/?>
