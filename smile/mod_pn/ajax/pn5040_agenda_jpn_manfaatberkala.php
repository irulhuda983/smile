<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Manfaat JP Berkala
Hist: - 18/09/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
?>
<script language="javascript">
function NewWindow4(mypage,myname,w,h,scroll){
		var openwin = window.parent.Ext.create('Ext.window.Window', {
		title: myname,
		collapsible: true,
		animCollapse: true,

		maximizable: true,
		width: w,
		height: h,
		minWidth: 600,
		minHeight: 400,
		layout: 'fit',
		html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
		dockedItems: [{
  			xtype: 'toolbar',
  			dock: 'bottom',
  			ui: 'footer',
  			items: [
  				{ 
  					xtype: 'button',
  					text: 'Tutup',
  					handler : function(){
  						openwin.close();
  					}
  				}
  			]
  		}]
	});
	openwin.show();
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
          window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5041_penetapan.php?task=edit&kode_klaim=<?= $ls_kode_klaim; ?>&task=Edit&no_level=&dataid=<?= $ls_kode_klaim; ?>&id_pointer_asal=&kode_pointer_asal=&kode_realisasi=&root_sender=pn5041.php&sender=pn5041_penetapan.php&activetab=2&mid=');
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
showFormReload('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5041_upload_dokumen_tambahan.php?' + params, "FORM UPLOAD DOKUMEN TAMBAHAN", 500, 300, scroll);
}

function deleteDokumenLain(kode_klaimx,nama_dokumen_lainnyax){

let kode_klaim = kode_klaimx;
let nama_dokumen_lainnya = nama_dokumen_lainnyax;


confirmation("Konfirmasi", "Apakah anda yakin akan menghapus data ini?",
  //setTimeout(
    function () {
    
    preload(true);
    $.ajax({
             type: 'POST',
             url: "../ajax/pn5041_upload_dokumen_tambahan_action.php?"+Math.random(),
             data: {
                 tipe: 'delete_data_dokumen',
                 kode_klaim : kode_klaim,
                 nama_dokumen_lainnya : nama_dokumen_lainnya
               },
            success: function(data){
               
                var jdata = JSON.parse(data);
                  if (jdata.ret == 0){
                    alert("Sukses! Data berhasil di hapus");
                    
                    window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5041_penetapan.php?task=edit&kode_klaim=<?= $ls_kode_klaim; ?>&task=Edit&no_level=&dataid=<?= $ls_kode_klaim; ?>&id_pointer_asal=&kode_pointer_asal=&kode_realisasi=&root_sender=pn5041.php&sender=pn5041_penetapan.php&activetab=2&mid=');
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
             url: "../ajax/pn5041_upload_dokumen_tambahan_action.php?"+Math.random(),
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
                    
                    window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5041_penetapan.php?task=edit&kode_klaim=<?= $ls_kode_klaim; ?>&task=Edit&no_level=&dataid=<?= $ls_kode_klaim; ?>&id_pointer_asal=&kode_pointer_asal=&kode_realisasi=&root_sender=pn5041.php&sender=pn5041_penetapan.php&activetab=2&mid=');
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
  <fieldset style="width:930px;"><legend>Manfaat Pensiun Berkala</legend>
    <table id="tblrincian1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>	
				<tr>
					<th colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>									
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Program</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan ke-</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Berjalan</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Rapel</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kompensasi</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Jumlah Berkala</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:150px;">Action</th>
        </tr>
				<tr>
					<th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>				
        <?							
        if ($ls_kode_klaim!="")
        {			
          $sql = "select a.kode_klaim, a.no_konfirmasi, a.kd_prg, b.nm_prg, a.no_proses, to_char(a.blth_proses,'mm/yyyy') blth_proses, ".
							 	 "			 a.nom_kompensasi, a.nom_rapel, a.nom_berjalan, a.nom_berkala ".
                 "from sijstk.pn_klaim_berkala_rekap a, sijstk.ms_prg b ".
                 "where a.kd_prg = b.kd_prg  ".
                 "and a.kode_klaim = :P_KODE_KLAIM ".
                 "and a.no_konfirmasi = '0' ".
                 "order by a.no_proses";
          //echo $sql;
					$proc = $DB->parse($sql);
          oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;	
          $ln_tot_d_nom_kompensasi  = 0;
          $ln_tot_d_nom_rapel = 0;
          $ln_tot_d_nom_berjalan = 0;
          $ln_tot_d_nom_berkala = 0;						
          while ($row = $DB->nextrow())
          {
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NM_PRG'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_PROSES'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BLTH_PROSES'];?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_BERJALAN'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_RAPEL'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_KOMPENSASI'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_BERKALA'],2,".",",");?></td>																		       																			        											
              <td align="center">
              	<a href="#" onClick="NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_agenda_jpn_manfaatberkalarinci.php?&kode_klaim=<?=$row["KODE_KLAIM"];?>&no_konfirmasi=<?=$row["NO_KONFIRMASI"];?>&no_proses=<?=$row["NO_PROSES"];?>&kd_prg=<?=$row["KD_PRG"];?>&sender=pn5040_agenda_jpn_manfaatberkala.php&sender_activetab=4&sender_mid=<?=$mid;?>','Rincian Manfaat Pensiun Berkala',1100,700,'no')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>
  						</td>
            </tr>
            <?								    							
            $i++;//iterasi i
						$ln_tot_d_nom_kompensasi  += $row["NOM_KOMPENSASI"];
						$ln_tot_d_nom_rapel  += $row["NOM_RAPEL"];
						$ln_tot_d_nom_berjalan  += $row["NOM_BERJALAN"];
						$ln_tot_d_nom_berkala  += $row["NOM_BERKALA"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
      <tr>
        <td style="text-align:right;" colspan="3"><i>Total Keseluruhan :<i>
          <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
				</td>	  		
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_berjalan,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_rapel,2,".",",");?></td>							
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_kompensasi,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_berkala,2,".",",");?></td>
				<td></td>										        
      </tr>																
    </table>
  </fieldset>
	
	</br>
	
  <fieldset style="width:930px;"><legend>Penerima Manfaat Berkala</legend>
    <table id="tblrincian1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>
				<tr>
					<th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>									
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tipe</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Hubungan</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">NPWP</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bank</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No.Rek</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">A/N</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nominal</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:150px;">Action</th>
        </tr>
				<tr>
					<th colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>
        <?							
        if ($ls_kode_klaim!="")
        {			
          $sql = "select ".
                 "     a.kode_klaim, a.no_konfirmasi, ". 
                 "     a.kode_tipe_penerima, (select nama_tipe_penerima from sijstk.pn_kode_tipe_penerima where kode_tipe_penerima = a.kode_tipe_penerima) nama_tipe_penerima, ".
                 "     a.kode_penerima_berkala, decode(a.kode_penerima_berkala,'TK','TENAGA KERJA','JD','JANDA/DUDA','A1', 'ANAK I','A2', 'ANAK II','OT', 'ORANG TUA', a.kode_penerima_berkala) nama_kode_penerima_berkala, ".
								 "		 b.nama_lengkap, b.npwp, ". 
                 "     b.bank_penerima, b.no_rekening_penerima, b.nama_rekening_penerima, ".
                 "     a.nom_berkala ". 
                 "from sijstk.pn_klaim_berkala a, sijstk.pn_klaim_penerima_berkala b ".
                 "where a.kode_klaim = b.kode_klaim (+) ". 
                 "and a.kode_penerima_berkala = b.kode_penerima_berkala(+) ".
                 "and a.kode_klaim = :P_KODE_KLAIM ".
                 "and a.no_konfirmasi = '0' ". 								 
                 "order by b.no_urut_keluarga";
          //echo $sql;
					$proc = $DB->parse($sql);
          oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;	
					$ln_tot_d_jpnbkala_nom_berkala =0;								
          while ($row = $DB->nextrow())
          {
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_TIPE_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_KODE_PENERIMA_BERKALA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_LENGKAP'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NPWP'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BANK_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_REKENING_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_REKENING_PENERIMA'];?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_BERKALA'],2,".",",");?></td>																		       																			        																												       																			        											
              <td align="center">
              <!-- <?
								if ($ls_status_klaim == "AGENDA" || $ls_status_klaim == "AGENDA_TAHAP_I" || $ls_status_klaim == "AGENDA_TAHAP_II" || $ls_status_klaim == "PENETAPAN")
								{
								 	$ls_task_pnrm = "edit"; 
								}else
								{
								 	$ls_task_pnrm = "view";
								}
								?>										
             		<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_agenda_jpn_manfaatberkalapenerima.php?&task=<?=$ls_task_pnrm;?>&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&kode_penerima_berkala=<?=$row["KODE_PENERIMA_BERKALA"];?>&root_sender=pn5040.php&sender=pn5041_penetapan.php&sender_activetab=5&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',860,600,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a> -->
              
              <!-- validasi rekening JP new -->
                <?
                  if ($ls_root_form == "pn5041.php")
                  {
                  ?>	
                  <!-- <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_penerima.php?&task=Edit&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&root_sender=pn5041.php&sender=pn5041_penetapan.php&sender_activetab=6&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',860,600,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a> -->
                  <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_agenda_jpn_manfaatberkalapenerima.php?&task=<?=$ls_task_pnrm;?>&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&kode_penerima_berkala=<?=$row["KODE_PENERIMA_BERKALA"];?>&root_sender=pn5040.php&sender=pn5041_penetapan.php&sender_activetab=5&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',860,600,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>
                  <?								
                  }else  
                  {
                  ?>	
                  <!-- <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_penerima.php?&task=Edit&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&root_sender=pn5040.php&sender=pn5040.php&sender_activetab=6&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',860,600,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>																																																				 -->
                  <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_agenda_jpn_manfaatberkalapenerima.php?&task=<?=$ls_task_pnrm;?>&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&kode_penerima_berkala=<?=$row["KODE_PENERIMA_BERKALA"];?>&root_sender=pn5040.php&sender=pn5041_penetapan.php&sender_activetab=5&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',860,600,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>
                  <?
                  }
                ?>	          
             
             
              </td>
            </tr>
            <?								    							
            $i++;//iterasi i
						$ln_tot_d_jpnbkala_nom_berkala  += $row["NOM_BERKALA"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
      <tr>
        <td style="text-align:right" colspan="7"><i>Total Diterima :<i>
          <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
        </td>
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_jpnbkala_nom_berkala,2,".",",");?></td>
				<td>				
				</td>				
      </tr>																		
    </table>
  </fieldset>

  <?
                    if ($ls_kode_klaim!="")
                    {
                      $sql = "SELECT STATUS_KLAIM FROM PN.PN_KLAIM WHERE KODE_KLAIM= :P_KODE_KLAIM";
                      $proc = $DB->parse($sql);
                      oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);
                      $DB->execute();	
                      $row = $DB->nextrow();
                      $status_klaim=$row['STATUS_KLAIM'];
                       
                    }
                    if($status_klaim=='PENETAPAN'){
?>

  <fieldset style="width:930px;" id="fieldsetDokumenLain3">
          <legend><b><i><font color="#009999">Dokumen Tambahan</font></i></b></legend>
          <div class="row">
            <div>
              <table width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
                <thead>
                <tr>
				      	<th colspan="6"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				        </tr>
                  <tr>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Dokumen</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Keterangan</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Sumber Dokumen</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Download</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Aksi</th>
                  </tr>
                  <tr>
					      <th colspan="6"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
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
          $sql = "SELECT A.*,
          DECODE(SYARAT_TAHAP_KE,'1','CSO','PMP') SUMBER_DOKUMEN 
           FROM PN.PN_KLAIM_DOKUMEN_TAMBAHAN A WHERE KODE_KLAIM= :P_KODE_KLAIM ORDER BY NO_URUT ASC";
          //echo $sql;
					$proc = $DB->parse($sql);
          oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);
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
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['SUMBER_DOKUMEN'];?></td>
              <td align="center">
             		<a href="#"  onclick="directDownload('<?= $ls_kode_klaim; ?>','<?=$row['KODE_DOKUMEN']?>','','<?=$row['NAMA_DOKUMEN_TAMBAHAN']?>','<?=$path_encrypt?>','<?=$row['MIME_TYPE']?>')"><img src="../../images/downloadx.png" border="0" width="20px" alt="Download data" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DOWNLOAD </font></a>																																																		
              </td>
              <td align="center">
             		<a href="#"  onclick="deleteDokumenLain('<?= $ls_kode_klaim; ?>','<?=$row['NAMA_DOKUMEN_TAMBAHAN']?>','<?=$row['NO_URUT']?>')"><img src="../../../smile/images/app_form_delete.png" border="0" alt="Delete data" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DELETE DATA </font></a>																																																		
              </td>
						
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
      <input type="button" name="btnfilter" style="width: 150px; margin-left:780px" class="btn green" id="btnfilter" value="Tambah Dokumen" onclick="uploadDokumenTambahan()"/>
        </div>
  </fieldset>

  <fieldset style="width:930px;" id="fieldsetDokumenLain4">
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
                      $sql = " SELECT * FROM PN.PN_KLAIM_CATATAN WHERE KODE_KLAIM= :P_KODE_KLAIM AND KODE_CATATAN='2' ORDER BY NO_URUT ASC";
                      $proc = $DB->parse($sql);
                      oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);
                      $DB->execute();	
                      $row = $DB->nextrow();
                      $keterangan_catatan=$row['KETERANGAN'];
                       
                    }
                  ?>
                  <tr>
     							  <td>
                    <textarea name="keterangan_catatan" id="keterangan_catatan" cols="120" rows="2" maxlength="4000"><?=$keterangan_catatan;?></textarea>
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
        <input type="button" name="btnfilter2" style="width: 150px; margin-left:780px" class="btn green" id="btnfilter2" value="Simpan Catatan" onclick="simpanCatatan()"/>
        </div>
  </fieldset>
  <?
                    }
  ?>
</div>	
