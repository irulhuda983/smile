<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Update Status Kelengkapan Administrasi
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/	
$p_kode_agenda = $_REQUEST["kd_agenda"];
$p_kd_perihal_detil = $_REQUEST["kd_perihal_detil"];
// $sql = "SELECT NAMA_FILE FROM";
// $DB->parse($sql);
// $DB->execute();
// $row = $DB->nextrow();
// $ls_kode_klaim          = $row["KODE_KLAIM"];
// $ls_kpj                 = $row["KPJ"] ;
?>
<script language="javascript">


</script>			
<div id="formKiri" style="width: 100%">					
  <fieldset style="width: 1150px"><legend>Upload File :</legend>
  	<table border='0' width="100%">
    <tr>
        <td width="37%" valign="top">
            <div class="l_frm" ><label for="datafile">Berita Acara<span style="color:#ff0000;">&nbsp;*</span> :</label></div>   
            <div class="r_frm">
                <?PHP if(($_REQUEST['task']=="Edit")||($_REQUEST['task']=="View")||($_REQUEST['task']=="Submit")) { ?>
                    <input type="button" class="btn green" id="btn_download" name="btn_download" value="Download" style="width:180px;" onclick="window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_koreksi_pelayanan_download_file.php?&kode_agenda=<?=$p_kode_agenda;?>&kode_perihal_detil=<?=$p_kd_perihal_detil;?>&TYPE=download')">
                <?
                }else{
                ?>
                    <input type="file" id="datafile" name="datafile" size="40" style="width:265px;background-color:#ffff99" accept=".pdf, .png, .jpg, .jpeg" value="<?=$ls_nama_file;?>" />
                    
                <?
                }
                ?>
                
            </div>


        </td>
        <td valign="top">
        <? if($p_kode_agenda=="" && $p_kd_perihal_detil == 'PP0203'){?>
            <div class="l_frm" >
            <span style="color:#009999;">&nbsp;(.pdf, .png, .jpg, .jpeg)</span>&nbsp;&nbsp;&nbsp;&nbsp;
            </div>
            <div class="r_frm" >
            <span style="color:#ff0000;">&nbsp;*  max 500KB </span>
            </div>
            <?}?>
        </td>
    </tr>
</table>
        <?if ($p_kd_perihal_detil == 'PP0203'){?>
            <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;">
                      <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Disclaimer :</b></i></span>
                        <li style="margin-left:15px;">Untuk Jenis Koreksi Tambah ahli waris:        <b>Lampirkan Form 7</b></li>    
                        <li style="margin-left:15px;">Untuk Jenis Koreksi Tambah anak Usia <= 300 hari : <b>Lampirkan Akta kelahiran anak</b></li>
            </div>
        <?}?>
  </fieldset>
	</br>
	</br>
</div>
<script type="text/javascript">
// function isValidDok() {
//         var file = $("#datafile")[0].files[0] == undefined ? { name: "", size: "" } : $("#datafile")[0].files[0];
//         var fileName = file.name;
//         var fileSize = file.size;
        
//         var maxSize = 5;

//         if (fileSize > maxSize)  {
//             return { val : false, msg : "Maksimal ukuran file adalah ' + maxSize + ' KB !" };
//         } 
//         return { val : true, msg : "Valid"} ;
//     }

</script>


