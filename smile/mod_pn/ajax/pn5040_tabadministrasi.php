<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Update Status Kelengkapan Administrasi
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/	
$tempdir  = "../../temp_qrcode/"; //<-- Nama Folder file QR Code kita nantinya akan disimpan
//kalau folder belum ada, maka buat.
// if (!file_exists($tempdir)){ 
//     mkdir($tempdir);
// }
//parameter inputan
$kode_klaim  = $_GET['kode_klaim'];
$isi_teks = $kode_klaim;
$namafile = $kode_klaim.".png";
$quality  = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
$ukuran   = 3; //batasan 1 paling kecil, 10 paling besar
$padding  = 0;

QRcode::png($isi_teks,$tempdir.$namafile,$quality,$ukuran,$padding); 

?>

<?php 
  $sql_data_qrcode="select nama_tk, nomor_identitas, kpj, kode_klaim, status_submit_agenda from pn.pn_klaim where kode_klaim=:p_kode_klaim";
  $proc = $DB->parse($sql_data_qrcode);
  oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
  $DB->execute();	
  $row = $DB->nextrow();
  $nama_tk=$row['NAMA_TK'];
  $nomor_identitas=$row['NOMOR_IDENTITAS'];
  $kpj=$row['KPJ'];
  $kode_klaim= $row['KODE_KLAIM'];
  $status_submit_agenda= $row['STATUS_SUBMIT_AGENDA'];
  ?>
  
<script language="javascript">

   function fl_js_set_status_diserahkan(v_i)
    {
      var form = document.formreg;
      var n_d_adm_status_diserahkan = 'd_adm_status_diserahkan'+v_i;      
      var n_d_adm_tgl_diserahkan = 'd_adm_tgl_diserahkan'+v_i;            
      
      //set sysdate utk tgl penyerahan dokumen --------------------------------     
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth()+1; //January is 0!
      
      var yyyy = today.getFullYear();
      if(dd<10){
          dd='0'+dd;
      } 
      if(mm<10){
          mm='0'+mm;
      } 
      var today = dd+'/'+mm+'/'+yyyy;
      //end set sysdate utk tgl penyerahan dokumen -----------------------------
      
      if (document.getElementById(n_d_adm_status_diserahkan).checked)
      {
        document.getElementById(n_d_adm_status_diserahkan).value = 'Y';
        document.getElementById(n_d_adm_tgl_diserahkan).value = today;
      }
      else
      {
        document.getElementById(n_d_adm_status_diserahkan).value = 'T';
        document.getElementById(n_d_adm_tgl_diserahkan).value = '';
      }                 
    }
 
    window.fl_js_set_status_diserahkan = function(v_i){
        var form = document.formreg;
        var n_d_adm_status_diserahkan = 'd_adm_status_diserahkan'+v_i;      
        var n_d_adm_tgl_diserahkan    = 'd_adm_tgl_diserahkan'+v_i;            
        
        //set sysdate utk tgl penyerahan dokumen --------------------------------     
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        
        var yyyy = today.getFullYear();
        if(dd<10){
            dd='0'+dd;
        } 
        if(mm<10){
            mm='0'+mm;
        } 
        var today = dd+'/'+mm+'/'+yyyy;
        //end set sysdate utk tgl penyerahan dokumen -----------------------------
        
        if (document.getElementById(n_d_adm_status_diserahkan).checked)
        {
          document.getElementById(n_d_adm_status_diserahkan).value = 'Y';
          document.getElementById(n_d_adm_tgl_diserahkan).value = today;
        }
        else
        {
          document.getElementById(n_d_adm_status_diserahkan).value = 'T';
          document.getElementById(n_d_adm_tgl_diserahkan).value = '';
        }             
    }
    

    function show_dokumen(path_dokumen) {


      let wsLinkDownload  = "<?php echo $wsIpDokumenAntrian ?>";

      NewWindow( wsLinkDownload+path_dokumen,'',900,700,1);

    }


    function refresh_tab(){
     
      window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/form/pn5040.php?task=Edit&tab=3&kode_klaim=<?= $ls_kode_klaim ?>&dataid=<?= $ls_kode_klaim ?>&activetab=11&mid='+Math.random());
      $('#t1').removeClass('active');	
      $('#t11').addClass('active');
    }

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
          window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/form/pn5040.php?task=Edit&kode_klaim=<?= $ls_kode_klaim ?>&dataid=<?= $ls_kode_klaim ?>&activetab=11&mid='+Math.random());
				},
					destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
	}

	function confirmation(title, msg, fnYes, fnNo) {
		window.parent.Ext.Msg.show({
			title: title,
			msg: msg,
			buttons: window.parent.Ext.Msg.YESNO,
			icon: window.parent.Ext.Msg.QUESTION,
			fn: function(btn) {
				if (btn === 'yes') {
					fnYes();
				} else {
					fnNo();
				}
			}
		});
	}

  	function directDownload(id,id2,id3,id4,id5,id6){


		var wsLinkDownload  = "<?php echo $wsIpDokumenAntrian ?>";

		namaFile=id+'_'+id2+'_'+id3+'_'+id4;
		
		var mime_type = id6;
 
		switch (mime_type){
			case "image/png":
				ctype = ".png";
				break;
			case "image/jpg":
				ctype = ".jpg";
				break;
			case "image/jpeg":
				ctype = ".jpeg";
				break;
			case "image/bmp":
				ctype = ".bmp";
				break;
			case "application/pdf":
				ctype = ".pdf";
				break;			
			
		}	

		downloadFile(wsLinkDownload+id5);
		function downloadFile(urlToSend) {
			 var req = new XMLHttpRequest();
			 req.open("GET", urlToSend, true);
			 req.responseType = "blob";
			 req.onload = function (event) {
				var blob = req.response;
				
				var fileName = namaFile+ctype;
				var link=document.createElement('a');
				link.href=window.URL.createObjectURL(blob);
				link.download=fileName;
				link.click();
			};

			req.send();
		}

	}  

function uploadDokumenTambahan(){
let kode_klaim = "<?php echo $ls_kode_klaim; ?>";
var params = "&kode_klaim=" + kode_klaim;
showFormReload('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5040_upload_dokumen_tambahan.php?' + params, "FORM UPLOAD DOKUMEN TAMBAHAN", 500, 300, scroll);
}

function deleteDokumenLain(kode_klaimx,nama_dokumen_lainnyax,no_urutx){

let kode_klaim = kode_klaimx;
let nama_dokumen_lainnya = nama_dokumen_lainnyax;
let no_urut = no_urutx;

confirmation("Konfirmasi", "Apakah anda yakin akan menghapus data ini?",
  //setTimeout(
    function () {
    
    preload(true);
    $.ajax({
             type: 'POST',
             url: "../ajax/pn5040_upload_dokumen_tambahan_action.php?"+Math.random(),
             data: {
                 tipe: 'delete_data_dokumen',
                 kode_klaim : kode_klaim,
                 nama_dokumen_lainnya : nama_dokumen_lainnya,
                 no_urut : no_urut
               },
            success: function(data){
               
                var jdata = JSON.parse(data);
                  if (jdata.ret == 0){
                    alert("Sukses! Data berhasil di hapus");
                    window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/form/pn5040.php?task=Edit&kode_klaim=<?= $ls_kode_klaim ?>&dataid=<?= $ls_kode_klaim ?>&activetab=11&mid='+Math.random());
                  }
                  else {
                      alert("Gagal!  "+jdata.msg);
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
  }, //100));
  setTimeout(function(){}, 1000));
//function (){});
}

function simpanCatatan(){

let kode_klaim = "<?php echo $ls_kode_klaim ?>";
let keterangan = $('#keterangan_catatan').val();


confirmation("Konfirmasi", "Apakah anda yakin akan menyimpan catatan ini?",
  //setTimeout(
    function () {
    
    preload(true);
    $.ajax({
             type: 'POST',
             url: "../ajax/pn5040_upload_dokumen_tambahan_action.php?"+Math.random(),
             data: {
                 tipe: 'simpan_catatan',
                 kode_klaim : kode_klaim,
                 keterangan : keterangan
               },
            success: function(data){
              //console.log(data);
                var jdata = JSON.parse(data);
                  if (jdata.ret == 0){
                    alert("Sukses! Data berhasil di simpan");
                    window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/form/pn5040.php?task=Edit&kode_klaim=<?= $ls_kode_klaim ?>&dataid=<?= $ls_kode_klaim ?>&activetab=11&mid='+Math.random());
                  }
                  else {
                      alert("Gagal!  "+jdata.msg);
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
  }, //100));
  setTimeout(function(){}, 1000));
//function (){});
}


</script>			
<div id="formKiri">					
  <fieldset style="width:935px;"><legend><b><i><font color="#009999">Update Status Kelengkapan Administrasi :</font></i></b></legend>
  	</br>
    <table id="tblrincian1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>	
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">&nbsp;</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="4">Penyerahan Dokumen</th>					    							
        </tr>	
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3"></th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="4"><hr/></th>					    							
        </tr>												
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:40px;">No</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:350px;">Nama Dokumen</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Mandatory</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Status</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama File</th>					    							
        </tr>
				<tr>
					<th colspan="7"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>
        <?						
        if ($ls_kode_klaim!="")
        {			
          $sql = "select a.kode_klaim, a.kode_dokumen, a.no_urut, b.nama_dokumen, to_char(a.tgl_diserahkan,'dd/mm/yyyy') tgl_diserahkan, ".
                 "    a.ringkasan, a.url, a.keterangan, nvl(a.status_diserahkan,'T') status_diserahkan, ".
								 "		nvl(a.flag_mandatory,'T') flag_mandatory, a.syarat_tahap_ke, a.nama_file ".
                 "from sijstk.pn_klaim_dokumen a, sijstk.pn_kode_dokumen b ".
                 "where a.kode_dokumen = b.kode_dokumen(+) ".
                 "and a.kode_klaim = :p_kode_klaim ".
                 "order by a.no_urut ";          
          $proc = $DB->parse($sql);
          oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;										
          while ($row = $DB->nextrow())
          {
             // begin enkripsi  

              $encrypt_method = "AES-256-CBC";
              $secret_key = 'WS-SERVICE-KEY';
              $secret_iv = 'WS-SERVICE-VALUE';
              // hash
              $key = hash('sha256', $secret_key);
              // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
              $iv = substr(hash('sha256', $secret_iv), 0, 16);  
              
              // end enkripsi

             $url_encrypt = base64_encode(openssl_encrypt($row['URL'], $encrypt_method, $key, 0, $iv));
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">	
								<?=$row['NO_URUT'];?>											
              	<input type="hidden" id="d_adm_no_urut<?=$i;?>" name="d_adm_no_urut<?=$i;?>" size="2" style="border-width: 1;text-align:center" value="<?=$row['NO_URUT'];?>" readonly class="disabled">    									 
              </td> 																
              <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">
								<?=$row['NAMA_DOKUMEN'];?>											
								<input type="hidden" id="d_adm_kode_dokumen<?=$i;?>" name="d_adm_kode_dokumen<?=$i;?>" size="2" style="border-width: 1;text-align:center" value="<?=$row['KODE_DOKUMEN'];?>" readonly class="disabled">											
              	<input type="hidden" id="d_adm_nama_dokumen<?=$i;?>" name="d_adm_nama_dokumen<?=$i;?>" size="50" style="border-width: 1;text-align:left" value="<?=$row['NAMA_DOKUMEN'];?>" readonly class="disabled">    									 
              </td> 
              <td align="center">
              	<?=($row["FLAG_MANDATORY"]=="Y" ? "<img src=../../images/file_apply.gif>" : "")?>
								<input type="hidden" id="d_adm_flag_mandatory<?=$i;?>" name="d_adm_flag_mandatory<?=$i;?>" size="50" style="border-width: 1;text-align:left" value="<?=$row['FLAG_MANDATORY'];?>" readonly class="disabled">																																																													
              </td>
              <td align="center">
                <?
								if ($_REQUEST["task"] == "Edit"  && $status_submit_agenda!="Y")
								{
								?>
  								<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_uploadlampirandokumen.php?&kode_klaim=<?=$ls_kode_klaim;?>&kode_dokumen=<?=$row["KODE_DOKUMEN"];?>&sender=pn5040.php','upload_tk',550,400,'yes')" href="javascript:void(0);"> 
  								<img src="../../images/uploadx.png" border="0" alt="Tambah" align="absmiddle" style="height:15px;"/>
  								Upload Dokumen</a>
								<?
								}
								?>
							</td>								
              <td align="center">
								<?
                if ($ls_status_submit_agenda=="Y")
                {
								 	?>
  								<input type="checkbox" disabled class="cebox" id="dcb_adm_status_diserahkan<?=$i;?>" name="dcb_adm_status_diserahkan<?=$i;?>" value="<?=$row['STATUS_DISERAHKAN'];?>" <?=$row['STATUS_DISERAHKAN']=="Y" || $row['STATUS_DISERAHKAN']=="ON" || $row['STATUS_DISERAHKAN']=="on" ? "checked" : "";?>>
                  <input type="hidden" id="d_adm_status_diserahkan<?=$i;?>" name="d_adm_status_diserahkan<?=$i;?>" value="<?=$row['STATUS_DISERAHKAN'];?>">
									<?
								}else
								{
								 	?>	 
									<input type="checkbox" class="cebox" id="d_adm_status_diserahkan<?=$i;?>" name="d_adm_status_diserahkan<?=$i;?>" value="<?=$row['STATUS_DISERAHKAN'];?>" onclick="fl_js_set_status_diserahkan(<?=$i;?>)" <?=$row['STATUS_DISERAHKAN']=="Y" || $row['STATUS_DISERAHKAN']=="ON" || $row['STATUS_DISERAHKAN']=="on" ? "checked" : "";?>>
									<?		 
								}
								?>
							</td>
							<td align="center">
								<!-- <?=$row['TGL_DISERAHKAN'];?>	 -->
              	<input type="text" id="d_adm_tgl_diserahkan<?=$i;?>" name="d_adm_tgl_diserahkan<?=$i;?>" size="10" style="border-width: 0;text-align:center" value="<?=$row['TGL_DISERAHKAN'];?>" readonly>    									 
              </td> 	
              <!-- <td align="center">
                <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_download_dok.php?&kode_klaim=<?=$ls_kode_klaim;?>&kode_dokumen=<?=$row["KODE_DOKUMEN"];?>&TASK=DOWNLOAD_DOK','upload_tk',970,600,'yes')" href="javascript:void(0);">
								<?=$row['NAMA_FILE'];?></a>
								<input type="hidden" id="d_adm_nama_file<?=$i;?>" name="d_adm_nama_file<?=$i;?>" size="10" style="border-width: 1;text-align:left" value="<?=$row['NAMA_FILE'];?>">
              </td> -->
              <td align="center">
                <a href="#" onClick="show_dokumen('<?=$url_encrypt;?>');">
								<?=$row['NAMA_FILE'];?></a>
								<input type="hidden" id="d_adm_nama_file<?=$i;?>" name="d_adm_nama_file<?=$i;?>" size="10" style="border-width: 1;text-align:left" value="<?=$row['NAMA_FILE'];?>">
              </td>																							       																			        											
            </tr>
            <?								    							
            $i++;//iterasi i
          }	//end while
          $ln_dtl=$i;
        }						
        ?>
         										             																
      </tbody>
      <tr>
        <td style="text-align:center" colspan="7">
					<hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/>
					<input type="hidden" id="d_adm_kounter_dtl" name="d_adm_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_adm_count_dtl" name="d_adm_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_adm_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
        </td>
      </tr>
      <? 
        if ($ls_jenis_klaim=="JPN"){
          ?>
      <tr>
        <th colspan="7">
            <legend style="background: #ccffff; border: 1px solid #CCC; text-align:left;font: 11px Arial, Verdana, Helvetica, sans-serif; color:#ff0000">
              <ul>
                <li>Pastikan jenis peristiwa sesuai dengan data kepesertaan tk.</li>
                <li>Pastikan status menikah sesuai dengan data ahli waris.</li>
                <li>Pastikan kode pos pada data alamat ahli waris sudah terisi. </li>
              </ul>
            </legend>
        </th> 
      </tr>  
      <? } ?> 																					
    </table>
    <br>
  <input type="button" name="btnrefresh" style="width: 150px; margin-left:780px" class="btn green" id="btnrefresh" value="Refresh" onclick="refresh_tab()"/>
    </br>
  </fieldset>
 

  <br>
  <fieldset style="width:935px;" id="fieldsetDokumenLain3">
          <legend><b><i><font color="#009999">Dokumen Tambahan</font></i></b></legend>
          <div class="row">
            <div>
              <table width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
                <thead>
                <tr>
				      	<th colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				        </tr>
                  <tr>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Dokumen</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Keterangan</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Download</th>
                    <?php
                    if ($_REQUEST["task"] == "Edit" && $status_submit_agenda!="Y")
                    {
                    ?>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Aksi</th>
                    <?php } ?>
                  </tr>
                  <tr>
					      <th colspan="5"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				        </tr> 
                </thead>
                <tbody id="tbDataDokumenLain3">
                <?							
        if ($ls_kode_klaim!="")
        {
          if (!function_exists('encrypt_decrypt'))   {
          function encrypt_decrypt($action, $string)
          {
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
        }			
          $sql = " SELECT * FROM PN.PN_KLAIM_DOKUMEN_TAMBAHAN WHERE KODE_KLAIM= :p_kode_klaim AND SYARAT_TAHAP_KE= :p_syarat ORDER BY NO_URUT ASC";
          //echo $sql;
          $proc = $DB->parse($sql);
          $param_bv = [':p_kode_klaim' => $ls_kode_klaim,':p_syarat' => '1'];
          foreach ($param_bv as $key => $value) {
            oci_bind_by_name($proc, $key, $param_bv[$key]);
          }
          $DB->execute();							              					
          $i=1;		
          $ln_dtl =1;							
          while ($row = $DB->nextrow())
          {
            $path_encrypt= encrypt_decrypt('encrypt',$row['PATH_URL']);
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$i?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_DOKUMEN_TAMBAHAN'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KETERANGAN_DOKUMEN_TAMBAHAN'];?></td>
              <td align="center">
             		<a href="#"  onclick="directDownload('<?= $ls_kode_klaim; ?>','<?=$row['KODE_DOKUMEN']?>','','<?=$row['NAMA_DOKUMEN_TAMBAHAN']?>','<?=$path_encrypt?>','<?=$row['MIME_TYPE']?>')"><img src="../../images/downloadx.png" border="0" width="20px" alt="Download data" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DOWNLOAD </font></a>																																																		
              </td>
              <?php
								if ($_REQUEST["task"] == "Edit" && $status_submit_agenda!="Y")
								{
              ?>
              <td align="center">
             		<a href="#"  onclick="deleteDokumenLain('<?= $ls_kode_klaim; ?>','<?=$row['NAMA_DOKUMEN_TAMBAHAN']?>','<?=$row['NO_URUT']?>')"><img src="../../../smile/images/app_form_delete.png" border="0" alt="Delete data" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DELETE DATA </font></a>																																																		
              </td>
              <?php } ?>
						
            </tr>
            <?								    							
            $i++;//iterasi i
					
          }	//end while
          $ln_dtl=$i;
        }						
        ?>								
                </tbody>
              </table>
            </div>
      <br>
      <?php
								if ($status_submit_agenda != "Y")
								{
              ?>
      <input type="button" name="btnfilter" style="width: 150px; margin-left:780px" class="btn green" id="btnfilter" value="Tambah Dokumen" onclick="uploadDokumenTambahan()"/>
        <?php } ?>
        </div>
  </fieldset>
  <br>
  <fieldset style="width:935px;" id="fieldsetDokumenLain4">
          <legend><b><i><font color="#009999">Catatan</font></i></b></legend>
          <div class="row">
            <div>
              <table width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
                <thead>
                </thead>
                <tbody id="tbDataDokumenLain4">
                  <?
                    if ($ls_kode_klaim!="")
                    {
                      $sql = " SELECT * FROM PN.PN_KLAIM_CATATAN WHERE KODE_KLAIM= :p_kode_klaim AND KODE_CATATAN=:p_kode_catatan ORDER BY NO_URUT ASC";
                      $proc = $DB->parse($sql);
                      $param_bv = [':p_kode_catatan' =>'1',':p_kode_klaim' => $ls_kode_klaim];
                      foreach ($param_bv as $key => $value) {
                        oci_bind_by_name($proc, $key, $param_bv[$key]);
                      }
                      $DB->execute();	
                      $row = $DB->nextrow();
                      $keterangan_catatan=$row['KETERANGAN'];
                       
                    }
                  ?>
                  <tr>
     							  <td>
                    <textarea name="keterangan_catatan" id="keterangan_catatan" cols="140" rows="2" maxlength="4000"><?=$keterangan_catatan;?></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td>
                    *Silahkan klik tombol Simpan Catatan untuk menyimpan catatan (maksimal 4000 karakter).
                    </td>
                  </tr>   
                </tbody>
              </table>
            </div>
            <?php
								if ($status_submit_agenda != "Y")
								{
              ?>
        <input type="button" name="btnfilter2" style="width: 150px; margin-left:780px" class="btn green" id="btnfilter2" value="Simpan Catatan" onclick="simpanCatatan()"/>
        <?php } ?>
        </div>
  </fieldset>
 
	</br>
	</br>
</div>
<div id="formKanan">

  <img style="align-content: center; padding-left: 50px" width="150" src="../../temp_qrcode/<?=$namafile?>" styltitle="Qrcode" />
  <div style="padding-left: 50px;">
  <h4><?= "Nama : <br>".$nama_tk; ?></h4>
  <h4><?= "NIK : <br>".$nomor_identitas; ?></h4>
  <h4><?= "KPJ : <br>".$kpj; ?></h4>
  <h4><?="Kode Klaim : <br>" .$kode_klaim; ?></h4>
  </div>
  
 
  

</div>
