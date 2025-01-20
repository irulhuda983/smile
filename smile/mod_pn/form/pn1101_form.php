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

if($task_code=="View" || $task_code=="Edit")
{
    if($task_no=='TipeKlaim')
        $sql = "SELECT rownum NO_URUT,KODE_TIPE_KLAIM KODE,NAMA_TIPE_KLAIM NAMA,STATUS_NONAKTIF,TO_CHAR(TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM,NVL(TO_CHAR(TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(PETUGAS_UBAH,' ') PETUGAS_UBAH
        FROM {$schema}.PN_KODE_TIPE_KLAIM
        WHERE KODE_TIPE_KLAIM='{$dataid}'"; // echo $sql;    
    else if($task_no=="TipePenerima")
        $sql = "SELECT rownum NO_URUT,KODE_TIPE_PENERIMA KODE,NAMA_TIPE_PENERIMA NAMA,STATUS_NONAKTIF,TO_CHAR(TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM,NVL(TO_CHAR(TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(PETUGAS_UBAH,' ') PETUGAS_UBAH
        FROM {$schema}.PN_KODE_TIPE_PENERIMA
        WHERE KODE_TIPE_PENERIMA='{$dataid}'"; // echo $sql;
    else if($task_no=="GroupICD")
        $sql = "SELECT rownum NO_URUT,KODE_GROUP_ICD KODE,NAMA_GROUP_ICD NAMA,STATUS_NONAKTIF,TO_CHAR(TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM,NVL(TO_CHAR(TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(PETUGAS_UBAH,' ') PETUGAS_UBAH
        FROM {$schema}.PN_KODE_GROUP_ICD
        WHERE KODE_GROUP_ICD='{$dataid}'"; // echo $sql;
    else if($task_no=="LokasiKecelakaan")
        $sql = "SELECT rownum NO_URUT,KODE_LOKASI_KECELAKAAN KODE,NAMA_LOKASI_KECELAKAAN NAMA,STATUS_NONAKTIF,TO_CHAR(TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM,NVL(TO_CHAR(TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(PETUGAS_UBAH,' ') PETUGAS_UBAH
        FROM {$schema}.PN_KODE_LOKASI_KECELAKAAN
        WHERE KODE_LOKASI_KECELAKAAN='{$dataid}'"; // echo $sql;
    else if($task_no=="KondisiTerakhir")
        $sql = "SELECT rownum NO_URUT,KODE_KONDISI_TERAKHIR KODE,NAMA_KONDISI_TERAKHIR NAMA,STATUS_NONAKTIF,TO_CHAR(TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM,NVL(TO_CHAR(TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(PETUGAS_UBAH,' ') PETUGAS_UBAH,KODE_TIPE_KLAIM KODE_PARENT
        FROM {$schema}.PN_KODE_KONDISI_TERAKHIR
        WHERE KODE_KONDISI_TERAKHIR='{$dataid}'"; // echo $sql;
    else if($task_no=="JenisKasus")
        $sql = "SELECT rownum NO_URUT,KODE_JENIS_KASUS KODE,NAMA_JENIS_KASUS NAMA,KODE_TIPE_KLAIM KODE_PARENT, STATUS_NONAKTIF,TO_CHAR(TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM,NVL(TO_CHAR(TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(PETUGAS_UBAH,' ') PETUGAS_UBAH
        FROM {$schema}.PN_KODE_JENIS_KASUS
        WHERE KODE_JENIS_KASUS='{$dataid}'"; // echo $sql;
    else if($task_no=="Akibat")
        $sql = "SELECT rownum NO_URUT,KODE_AKIBAT_DIDERITA KODE,NAMA_AKIBAT_DIDERITA NAMA,STATUS_NONAKTIF,TO_CHAR(TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM,NVL(TO_CHAR(TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(PETUGAS_UBAH,' ') PETUGAS_UBAH
        FROM {$schema}.PN_KODE_AKIBAT_DIDERITA
        WHERE KODE_AKIBAT_DIDERITA='{$dataid}'"; // echo $sql;
    else if($task_no=="Diagnosa")
        $sql = "SELECT rownum NO_URUT,KODE_DIAGNOSA KODE,NAMA_DIAGNOSA NAMA,STATUS_NONAKTIF,TO_CHAR(TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM,NVL(TO_CHAR(TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(PETUGAS_UBAH,' ') PETUGAS_UBAH,KODE_GROUP_ICD KODE_PARENT
        FROM {$schema}.PN_KODE_DIAGNOSA
        WHERE KODE_DIAGNOSA='{$dataid}'"; // echo $sql;
    else if($task_no=="DiagnosaDetil")
        $sql = "SELECT rownum NO_URUT,KODE_DIAGNOSA_DETIL KODE,NAMA_DIAGNOSA_DETIL NAMA,A.STATUS_NONAKTIF,TO_CHAR(A.TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(A.TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, A.PETUGAS_REKAM,NVL(TO_CHAR(A.TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(A.PETUGAS_UBAH,' ') PETUGAS_UBAH,A.KODE_DIAGNOSA KODE_PARENT, B.KODE_GROUP_ICD KODE_GRAND_PARENT
        FROM {$schema}.PN_KODE_DIAGNOSA_DETIL A left outer join {$schema}.PN_KODE_DIAGNOSA B
        on A.KODE_DIAGNOSA=B.KODE_DIAGNOSA
        WHERE KODE_DIAGNOSA_DETIL='{$dataid}'";  //echo $sql;
    else if($task_no=="Dokumen")
        $sql = "SELECT rownum NO_URUT,KODE_DOKUMEN KODE,NAMA_DOKUMEN NAMA,A.STATUS_NONAKTIF,TO_CHAR(A.TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(A.TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, A.PETUGAS_REKAM,NVL(TO_CHAR(A.TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(A.PETUGAS_UBAH,' ') PETUGAS_UBAH
        FROM {$schema}.PN_KODE_DOKUMEN A
        WHERE KODE_DOKUMEN='{$dataid}'";  //echo $sql;
    else if($task_no=="SebabKlaim")
        $sql = "SELECT rownum NO_URUT,KODE_SEBAB_KLAIM KODE,NAMA_SEBAB_KLAIM NAMA,A.STATUS_NONAKTIF,TO_CHAR(A.TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(A.TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, A.PETUGAS_REKAM,NVL(TO_CHAR(A.TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(A.PETUGAS_UBAH,' ') PETUGAS_UBAH,KODE_TIPE_KLAIM KODE_PARENT,FLAG_MENINGGAL,FLAG_PARTIAL,PERSEN_PENGAMBILAN_MAKSIMUM MAKS,KEYWORD
        FROM {$schema}.PN_KODE_SEBAB_KLAIM A
        WHERE KODE_SEBAB_KLAIM='{$dataid}'";  //echo $sql;
    else if($task_no=="SebabSegmen")
    {
        list($dataid1,$dataid2)=explode("_",$dataid);
        $sql = "SELECT rownum NO_URUT,KETERANGAN NAMA,A.STATUS_NONAKTIF,TO_CHAR(A.TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(A.TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, A.PETUGAS_REKAM,NVL(TO_CHAR(A.TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(A.PETUGAS_UBAH,' ') PETUGAS_UBAH,KODE_SEBAB_KLAIM KODE_PARENT,KODE_SEGMEN KODE_PARENT1
        FROM {$schema}.PN_KODE_SEBAB_SEGMEN A
        WHERE KODE_SEBAB_KLAIM='{$dataid1}' and KODE_SEGMEN='{$dataid2}'";  //echo $sql;
    }else if($task_no=="Manfaat")
        $sql = "SELECT rownum NO_URUT,KODE_MANFAAT KODE,NAMA_MANFAAT NAMA,A.STATUS_NONAKTIF,TO_CHAR(A.TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(A.TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, A.PETUGAS_REKAM,NVL(TO_CHAR(A.TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(A.PETUGAS_UBAH,' ') PETUGAS_UBAH,KATEGORI_MANFAAT KATEGORI,JENIS_MANFAAT JENIS,TIPE_MANFAAT TIPE,FLAG_BERKALA,KETERANGAN
        FROM {$schema}.PN_KODE_MANFAAT A
        WHERE KODE_MANFAAT='{$dataid}'";  //echo $sql;
    //echo $sql;
    $DB->parse($sql);
    $DB->execute();
    if($row = $DB->nextrow())
    {
        $ls_no                  = $row['NO_URUT'];
        $ls_kode                = $row['KODE'];
        $ls_kode_parent         = $row['KODE_PARENT'];
        $ls_kode_parent1        = $row['KODE_PARENT1'];
        $ls_kode_grand_parent   = $row['KODE_GRAND_PARENT'];
        $ls_nama                = $row['NAMA'];
        $ls_status_nonaktif     = $row['STATUS_NONAKTIF'];
        $ls_tgl_nonaktif        = $row['TGL_NONAKTIF'];
        $ls_tgl_rekam           = $row['TGL_REKAM'];
        $ls_petugas_rekam       = $row['PETUGAS_REKAM'];
        $ls_tgl_ubah            = $row['TGL_UBAH'];
        $ls_petugas_ubah        = $row['PETUGAS_UBAH'];

        $ls_f_meninggal         = $row['FLAG_MENINGGAL'];
        $ls_f_partial           = $row['FLAG_PARTIAL'];
        $ls_maks                = $row['MAKS'];
        $ls_keyword             = $row['KEYWORD'];

        $ls_kategori            = $row['KATEGORI'];
        $ls_jenis               = $row['JENIS'];
        $ls_tipe                = $row['TIPE'];
        $ls_f_berkala        = $row['FLAG_BERKALA'];
        $ls_keterangan          = $row['KETERANGAN'];
    }
}
$i_kode_readonly = ($task_code=='New')?'':'readonly';
$i_kode_color = ($task_code=='New')?'#ffff99;':'#e9e9e9;';
$i_nama_readonly = ($task_code=='New' || $task_code=='Edit')?'':'readonly';
$i_nama_color = ($task_code=='New' || $task_code=='Edit')?'#ffff99;':'#e9e9e9;';
$i_status_nonaktif = ($task_code=='View')?"disabled=\"disabled\"":"";

$l_name = ($task_no=="TipeKlaim")?"Tipe Klaim":"";
$l_name = $task_no=="TipePenerima"?"Tipe Penerima":$l_name;
$l_name = $task_no=="GroupICD"?"Group ICD":$l_name;
$l_name = $task_no=="LokasiKecelakaan"?"Lokasi":$l_name;
$l_name = $task_no=="KondisiTerakhir"?"Kondisi Trkhir":$l_name;
$l_name = $task_no=="JenisKasus"?"Jenis Kasus":$l_name;
$l_name = $task_no=="Akibat"?"Akibat Diderita":$l_name;
$l_name = $task_no=="Diagnosa"?"Diagnosa":$l_name;
$l_name = $task_no=="DiagnosaDetil"?"Diagnosa Detil":$l_name;
$l_name = $task_no=="Dokumen"?"Dokumen":$l_name;
$l_name = $task_no=="SebabKlaim"?"Sebab Klaim":$l_name;
$l_name = $task_no=="SebabSegmen"?"Sebab Segmen":$l_name;
$l_name = $task_no=="Manfaat"?"Manfaat":$l_name;

$l_name_p = ($task_no=="KondisiTerakhir"||$task_no=="JenisKasus")?"Tipe Klaim":"";
$l_name_p = $task_no=="Diagnosa"?"Group ICD":$l_name_p;
$l_name_p = $task_no=="DiagnosaDetil"?"Diagnosa":$l_name_p;
$l_name_p = $task_no=="SebabSegmen"?"Sebab Klaim":$l_name_p;

$l_name_p1 = $task_no=="SebabSegmen"?"Segmen":'';

$l_name_g = ($task_no=="DiagnosaDetil")?"Group ICD":"";
$l_name_g = ($task_no=="SebabSegmen")?"Segmen":"";
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
.f_2{width:310px;text-align:left;margin-left:2px;float:left;margin-bottom:2px;  }
.f_readonly{background-color:#e9e9e9;}
.f_mandatory{background-color:#ffff99;}
</style>
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">

</script>
<?php /*****end LOCAL JAVASCRIPT*********************************/?>

<form name="formreg" id="formreg" role="form" method="post">
<input type="hidden" id="formregact" name="formregact" value="<?=$task_code;?>" />
<input type="hidden" id="taskno" name="taskno" value="<?=$task_no;?>" />
<table border='0' width="100%">
    <tr>
        <td width="50%" valign="top">
            <?php if($task_no!='SebabSegmen'){?>
            <div class="f_1" ><label for="kode">Kode <?=$l_name;?>* :</label></div>
            <div class="f_2"><input type="text" id="kode" name="kode" style="width:80px;background-color:<?=$i_kode_color;?>" tabindex="1" value="<?=$ls_kode;?>" <?=$i_kode_readonly;?>  /></div>
            <?php }?>
            <div class="f_1" ><label for="nama">Nama <?=$l_name;?>* :</label></div>
            <div class="f_2"><input type="text" id="nama" name="nama" style="width:260px;background-color:<?=$i_nama_color;?>" tabindex="2"   value="<?=$ls_nama;?>" <?=$i_nama_readonly;?>/></div>
            <?php if($task_no=='DiagnosaDetil'){?>
            <div class="f_1" ><label for="kode_grand_parent">Kode <?=$l_name_g;?>* :</label></div>
            <div class="f_2"><select id="kode_grand_parent" name="kode_grand_parent" style="width:270px"></select></div>
            <?php }?>
            <?php if($task_no=='SebabSegmen'){?>
            <div class="f_1" ><label for="kode_parent1">Kode <?=$ls_kode_parent1;?>* :</label></div>
            <div class="f_2"><select id="kode_parent1" name="kode_parent1" style="width:270px"></select></div>
            <input type="hidden" id="kode_parent1_old" name="kode_parent1_old" value="<?=$ls_kode_parent1;?>" />
            <?php }?>
            <?php if($task_no=='KuisEvaluasi' || $task_no=='KondisiTerakhir' || $task_no=='JenisKasus'|| $task_no=='Diagnosa'|| $task_no=='DiagnosaDetil' || $task_no=='SebabKlaim'|| $task_no=='SebabSegmen'){?>
            <div class="f_1" ><label for="kode_parent">Kode <?=$l_name_p;?>*:</label></div>
            <div class="f_2"><select id="kode_parent" name="kode_parent" style="width:270px"></select></div>
            <input type="hidden" id="kode_parent_old" name="kode_parent_old" value="<?=$ls_kode_parent;?>" />
            <?php }?>
            <?php if($task_no=="APenAwal"||$task_no=="APenLink"||$task_no=="ApenTempat"){?>
            <div class="f_1" ><label for="bobot">Bobot* :</label></div>
            <div class="f_2"><input type="text" id="bobot" name="bobot" style="text-align:right;width:60px;background-color:<?=$i_nama_color;?>" tabindex="3"   value="<?=$ls_no;?>" <?=$i_nama_readonly;?>/></div>
            <?php }?>
            <?php if($task_no=="SebabKlaim"){?>
            <div class="f_1">Flagging :</div>
            <div class="f_2">
                <input type="checkbox" id="fmeninggal" name="fmeninggal" value="Y"  <?php echo $ls_f_meninggal=='Y'?"checked":"";?> /> <label for="fmeninggal">Meninggal</label>
                <input type="checkbox" id="fpartial" name="fpartial" value="Y"  <?php echo $ls_f_partial=='Y'?"checked":"";?> /> <label for="fpartial">Partial</label>
            </div>
            <div class="f_1" ><label for="persen">Persen Max Pengambilan :</label></div>
            <div class="f_2"><input type="text" id="persen" name="persen" style="text-align:right;width:60px;background-color:<?=$i_nama_color;?>" tabindex="3"   value="<?=$ls_maks;?>" <?=$i_nama_readonly;?>/> %</div>
            <div class="f_1" ><label for="keyword">Keyword* :</label></div>
            <div class="f_2"><input type="text" id="keyword" name="keyword" style="width:120px;background-color:<?=$i_nama_color;?>" tabindex="2"   value="<?=$ls_keyword;?>" <?=$i_nama_readonly;?>/></div>
            <?php }?>
            <?php if($task_no=='Manfaat'){?>
            <div class="f_1" ><label for="kategori">Kategori :</label></div>
            <div class="f_2"><select id="kategori" name="kategori" style="width:270px">
                <option value="UTAMA" <?php echo $ls_kategori=='MANFAAT'?"selected":"";?>>Utama</option>
                <option value="TAMBAHAN" <?php echo $ls_kategori=='TAMBAHAN'?"selected":"";?>>Tambahan</option>
            </select></div>
            <div class="f_1" ><label for="jenis">Jenis :</label></div>
            <div class="f_2"><select id="jenis" name="jenis" style="width:270px">
                <option value="PASTI" <?php echo $ls_jenis=='PASTI'?"selected":"";?>>Pasti</option>
                <option value="DIAJUKAN" <?php echo $ls_jenis=='DIAJUKAN'?"selected":"";?>>Diajukan</option>
            </select></div>
            <div class="f_1" ><label for="tipe">Tipe :</label></div>
            <div class="f_2"><select id="tipe" name="tipe" style="width:270px">
                <option value="JAMINAN" <?php echo $ls_tipe=='JAMINAN'?"selected":"";?>>Jaminan</option>
                <option value="SANTUNAN" <?php echo $ls_tipe=='SANTUNAN'?"selected":"";?>>Santunan</option>
                <option value="BIAYA" <?php echo $ls_tipe=='BIAYA'?"selected":"";?>>Biaya</option>
            </select></div>
            <div class="f_1" ></div>
            <div class="f_2"><input type="checkbox" id="fberkala" name="fberkala" value="Y"  <?php echo $ls_f_berkala=='Y'?"checked":"";?> /> <label for="fberkala">Flag Berkala</label></div>
            <div class="f_1" ><label for="keterangan">Keterangan :</label></div>
            <div class="f_2"><input type="text" id="keterangan" name="keterangan" style="width:260px;"    value="<?=$ls_keterangan;?>" <?=$i_nama_readonly;?>/></div>
            <?php }?>
            <div class="f_1" ><label for="status_nonaktif">Non Aktif* :</label></div>
            <div class="f_2"><select id="status_nonaktif" name="status_nonaktif" tabindex="3" <?=$i_status_nonaktif;?>>
                <option value="T"<?php echo $ls_status_nonaktif=='T'?'selected':'';?>>Tidak</option>
                <option value="Y" <?php echo $ls_status_nonaktif=='Y'?'selected':'';?>>Ya</option>
            </select><input type="hidden" id="status_nonaktif_old" name="status_nonaktif_old" value="<?=$ls_status_nonaktif;?>" />
            </div>
            <div class="f_1" ><label for="tgl_nonaktif">Tanggal Non Aktif :</label></div>
            <div class="f_2"><input type="text" id="tgl_nonaktif" name="tgl_nonaktif" style="100px;background-color:#e9e9e9;" tabindex="4" value="<?=$ls_tgl_nonaktif;?>" readonly /></div>
        </td>
        <td valign="top">
            <div class="f_1" ><label for="tgl_rekam">Tanggal Rekam :</label></div>
            <div class="f_2"><input type="text" id="tgl_rekam" name="tgl_rekam" style="100px;background-color:#e9e9e9;"  value="<?=$ls_tgl_rekam;?>" readonly /></div>
            <div class="f_1" ><label for="petugas_rekam">Petugas Rekam :</label></div>
            <div class="f_2"><input type="text" id="petugas_rekam" name="petugas_rekam" style="width:275px;background-color:#e9e9e9;"  value="<?=$ls_petugas_rekam;?>"/></div>
            <div class="f_1" ><label for="tgl_ubah">Tanggal Ubah :</label></div>
            <div class="f_2"><input type="text" id="tgl_ubah" name="tgl_ubah" style="100px;background-color:#e9e9e9;"  value="<?=$ls_tgl_ubah;?>" readonly /></div>
            <div class="f_1" ><label for="petugas_ubah">Petugas ubah :</label></div>
            <div class="f_2"><input type="text" id="petugas_ubah" name="petugas_ubah" style="width:275px;background-color:#e9e9e9;"  value="<?=$ls_petugas_ubah;?>"/></div>
        </td>
    </tr>
</table>
</form>
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">
var actionState=0;
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
function cek_dataNew()
{ 
    if($("#kode").val()==''||$("#nama").val()=='')
        return false;
    else
        return true;
}
function lov_subData(par_callFunc,par_ajaxFunc,par_ajaxKey1,par_ajaxKey2,p_g)
{
    preload(true);
    var ajax_Key1 = (par_ajaxKey1 == undefined) ? "":par_ajaxKey1;
    var ajax_Key2 = (par_ajaxKey2 == undefined) ? "":par_ajaxKey2; 
    $.ajax({
        type: 'GET',
        url: "../ajax/<?=$php_file_name;?>_lov.php?"+Math.random(),
        data: {f:par_ajaxFunc,key1:ajax_Key1,key2:ajax_Key2},
        success: function(ajaxdata){ console.log(ajaxdata);
            preload(false);
            if(p_g==0 || p_g==undefined) setParentKode(ajaxdata);
            else if(p_g==1) setGrandParentKode(ajaxdata);
            else if(p_g==2) setParent1Kode(ajaxdata);
        },
        error:function(){
            preload(false);
        },
    });
}

function setParentKode(par_data){$("#formreg select[name=kode_parent]").html(par_data);}
function setGrandParentKode(par_data){$("#formreg select[name=kode_grand_parent]").html(par_data);}
function setParent1Kode(par_data){$("#formreg select[name=kode_parent1]").html(par_data);}

$(document).ready(function(){ 
    $("#btn_save").click(function(){
        if(!cek_dataNew())
            alert("Lengkapi isian mandatory field terlebih dulu!");
        else if(confirm('Save new data?')){
            saveData();
        }
    });
    <?php if($task_no == "KondisiTerakhir"||$task_no == "JenisKasus"||$task_no == "SebabKlaim"){?>
        lov_subData('getTipeKlaim','getTipeKlaim','<?=$ls_kode_parent;?>','');
    <?php }?>
    <?php if($task_no == "Diagnosa"){?>
        lov_subData('getGroupICD','getGroupICD','<?=$ls_kode_parent;?>','');
    <?php }?>
    <?php if($task_no == "DiagnosaDetil"){?>
        lov_subData('getDiagnosa','getDiagnosa','<?=$ls_kode_parent;?>','');
        lov_subData('getGroupICD','getGroupICD','<?=$ls_kode_grand_parent;?>','',1);
        $("#kode_grand_parent").change(function(){
            lov_subData('getDiagnosa','getDiagnosa','',this.value);
        });
    <?php }?>
    <?php if($task_no == "SebabSegmen"){?>
        lov_subData('getSebabKlaim','getSebabKlaim','<?=$ls_kode_parent;?>','');
        lov_subData('getSegmen','getSegmen','<?=$ls_kode_parent1;?>','',2);
    <?php }?>
});
</script>
<?php /*****end LOCAL JAVASCRIPT*********************************/?>