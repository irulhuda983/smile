<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Biaya dan Santunan
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
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
	
<div id="formKiri" style="width:1050px;">					
  <fieldset style="width:1000px;"><legend><b><i><font color="#009999">Biaya dan Santunan</font></i></b></legend>
    <table id="tblrincian1" width="85%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>	
				<tr>
					<th colspan="6"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>									
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Manfaat</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Program</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tgl Diajukan</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nilai Manfaat (Netto)</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Penerima Manfaat</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:170px;">Action</th>
        </tr>
				<tr>
					<th colspan="6"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>				
        <?							
        if ($ls_kode_klaim!="")
        {
          
           //control data JKK JKM
           $sql_control_data_jkk_jkm=" select nomor_identitas, to_char(tgl_lahir,'DD/MM/RRRR') tgl_lahir, to_char(tgl_kejadian,'DD/MM/RRRR') tgl_kejadian, nama_tk, kode_tipe_klaim from pn.pn_klaim where kode_klaim='$ls_kode_klaim'";
           $DB->parse($sql_control_data_jkk_jkm);
           $DB->execute();
           $row = $DB->nextrow();		
           $ls_nomor_identitas_cd = $row['NOMOR_IDENTITAS'];
           $ls_tgl_lahir_cd = $row['TGL_LAHIR'];
           $ls_tgl_kejadian_cd = $row['TGL_KEJADIAN'];
           $ls_nama_tk_cd = $row['NAMA_TK']; 
           $ls_kode_tipe_klaim_cd = $row['KODE_TIPE_KLAIM'];

           $ls_cek_klaim_jkm_cd="";
           
           if($ls_kode_tipe_klaim_cd == 'JKK01'){
 
             $sql_control_data_jkk_jkm="select count(*) cek_klaim_jkm from pn.pn_klaim a
             where nomor_identitas = '$ls_nomor_identitas_cd'
             and utl_match.edit_distance_similarity (nama_tk, '$ls_nama_tk_cd') >= 75
             and tgl_lahir = TO_DATE('$ls_tgl_lahir_cd','DD/MM/RRRR')
             and tgl_kejadian = TO_DATE('$ls_tgl_kejadian_cd','DD/MM/RRRR')
             and substr(kode_tipe_klaim,1,3) = 'JKM'
             and nvl(status_batal,'T')='T'
             and kode_klaim <> nvl('$ls_kode_klaim','XXX')";
 
             $DB->parse($sql_control_data_jkk_jkm);
             $DB->execute();
             $row = $DB->nextrow();		
             $ls_cek_klaim_jkm_cd = $row['CEK_KLAIM_JKM'];

            
 
 
             $sql_control_data_jkk_jkm="select kode_klaim from pn.pn_klaim a
             where nomor_identitas = '$ls_nomor_identitas_cd'
             and utl_match.edit_distance_similarity (nama_tk, '$ls_nama_tk_cd') >= 75
             and tgl_lahir = TO_DATE('$ls_tgl_lahir_cd','DD/MM/RRRR')
             and tgl_kejadian = TO_DATE('$ls_tgl_kejadian_cd','DD/MM/RRRR')
             and substr(kode_tipe_klaim,1,3) = 'JKM'
             and nvl(status_batal,'T')='T'
             and kode_klaim <> nvl('$ls_kode_klaim','XXX')
             and rownum=1";
 
             $DB->parse($sql_control_data_jkk_jkm);
             $DB->execute();
             $row = $DB->nextrow();		
             $ls_kode_klaim_jkm = $row['KODE_KLAIM'];
           
 
           }

         


           $sql = "select ".
                  "    a.kode_klaim, a.kode_manfaat, b.nama_manfaat, a.kd_prg, c.nm_prg, ". 
					 			 "		to_char(a.tgl_diajukan,'dd/mm/yyyy') tgl_diajukan, ". 
                  "    a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, ".
					 			 "		a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.keterangan, replace(b.url_path,'pn5002','pn5041') url_path, ".
					 			 "		nvl(a.status_lunas,'T') status_lunas, to_char(a.tgl_lunas,'dd/mm/yyyy') tgl_lunas, ".
					 			 "		( ".
                  // 03012022, penyesuaian untuk kebutuhan syariah
				  //"     select listagg(y.nama_tipe_penerima,', ') within group (order by y.nama_tipe_penerima) tipe_penerima_manfaat ".
				  //	 			 "		 from pn.pn_klaim_manfaat_detil x, pn.pn_kode_tipe_penerima y ".
                  //"     where x.kode_tipe_penerima = y.kode_tipe_penerima ".
                  //"     and x.kode_klaim = a.kode_klaim ".
                  //"     and x.kode_manfaat = a.kode_manfaat ".
                  //"     group by x.kode_manfaat ".
                  //"		) tipe_penerima_manfaat ".
				  "	  select
						  (listagg(nama_tipe_penerima,', ') within group (order by nama_tipe_penerima)) tipe_penerima_manfaat
						 from
						 (
						   select x.kode_manfaat, y.nama_tipe_penerima
						   from pn.pn_klaim_manfaat_detil x, pn.pn_kode_tipe_penerima y
						   where x.kode_tipe_penerima = y.kode_tipe_penerima
						   and x.kode_klaim = '$ls_kode_klaim'
						   --and x.kode_manfaat = a.kode_manfaat
						   group by x.kode_manfaat, y.nama_tipe_penerima
						 ) where kode_klaim = a.kode_klaim
						   and kode_manfaat = a.kode_manfaat
						 group by kode_manfaat
							) tipe_penerima_manfaat ".
                  "from sijstk.pn_klaim_manfaat a, sijstk.pn_kode_manfaat b, sijstk.ms_prg c ".
                  "where a.kode_manfaat = b.kode_manfaat(+) and a.kd_prg = c.kd_prg ".
					 			 "and a.kode_klaim='$ls_kode_klaim' ".
                  "order by a.kode_manfaat";
          //echo $sql;
					$DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;	
					$ln_tot_d_mnf_nom_manfaat_netto = 0;									
          while ($row = $DB->nextrow())
          {
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_MANFAAT'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NM_PRG'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TGL_DIAJUKAN'];?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_MANFAAT_NETTO'],2,".",",");?></td>																		       																			        											
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TIPE_PENERIMA_MANFAAT'];?></td>
							<td align="center">
								<?
								if ($row["URL_PATH"]!="")
								{
								?>
              		<!--<a href="#" onClick="NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$row["URL_PATH"];?>?&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_manfaat=<?=$row["KODE_MANFAAT"];?>&sender=pn5001_tabmanfaat.php&sender_activetab=4&sender_mid=<?=$mid;?>','Rincian Biaya dan Santunan',800,580,'no')"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>-->
									
                  <? if ($ls_cek_klaim_jkm_cd > 0 && $ls_kode_tipe_klaim_cd =="JKK01" && ($row['KODE_MANFAAT']=="7" || $row['KODE_MANFAAT']=="8" || $row['KODE_MANFAAT']=="6" || $row['KODE_MANFAAT']=="2")) { ?>

                  <a href="#" onclick="alert('Peserta dengan NIK <?= $ls_nomor_identitas_cd ?> telah dilakukan klaim JKM-nya dengan kode klaim <?= $ls_kode_klaim_jkm ?>. Silakan lakukan verifikasi/pengecekan kasus kembali terkait penyebab meninggal dunia peserta karena kecelakaan kerja atau bukan')">
                  <img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>

                  <? } else { ?>
                  <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$row["URL_PATH"];?>?&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_manfaat=<?=$row["KODE_MANFAAT"];?>&root_sender=<?=$ls_root_sender;?>&form_penetapan=<?=$ls_form_penetapan;?>&sender_activetab=2&sender_mid=<?=$mid;?>','Rincian Biaya dan Santunan',800,580,'no')"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>
                  <? } ?>
                 
                  <!--<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$row["URL_PATH"];?>?&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_manfaat=<?=$row["KODE_MANFAAT"];?>&root_sender=<?=$ls_root_sender;?>&form_penetapan=<?=$ls_form_penetapan;?>&sender_activetab=2&sender_mid=<?=$mid;?>','Rincian Biaya dan Santunan',1000,580,'no')"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>-->
								<?
								}else
								{
								?>
									<a href="#"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>
								<?
								}
								?>																																																												
              </td>
            </tr>
            <?								    							
            $i++;//iterasi i
						$ln_tot_d_mnf_nom_manfaat_netto  += $row["NOM_MANFAAT_NETTO"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="6"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
      <tr>
        <td style="text-align:right;" colspan="3"><i>Total Biaya dan Santunan :<i>
          <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
				</td>	  									
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_mnf_nom_manfaat_netto,2,".",",");?></td>
				<td></td>	
				<td></td>										        
      </tr>																
    </table>
  </fieldset>
	
	</br>
	
  <fieldset style="width:1000px;"><legend><b><i><font color="#009999">Penerima Manfaat Biaya dan Santunan</font></i></b></legend>
    <table id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>
				<tr>
					<th colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>									
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tipe</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">NPWP</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bank</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No.Rek</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">A/N</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nominal</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:170px;">Action</th>
        </tr>
				<tr>
					<th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>
        <?							
        if ($ls_kode_klaim!="")
        {	
         

          $sql = "select ".
                 "     a.kode_klaim, a.kode_tipe_penerima, b.nama_tipe_penerima, a.kode_hubungan, ". 
                 "     a.ket_hubungan_lainnya, a.nomor_identitas, a.nama_pemohon, ". 
                 "     a.tempat_lahir, a.tgl_lahir, a.jenis_kelamin, ". 
                 "     a.alamat, a.rt, a.rw, ". 
                 "     a.kode_kelurahan, a.kode_kecamatan, a.kode_kabupaten, ". 
                 "     a.kode_pos, a.telepon_area, a.telepon, ". 
                 "     a.telepon_ext, a.handphone, a.email, ". 
                 "     a.npwp, a.nama_penerima, a.bank_penerima, ". 
                 "     a.no_rekening_penerima, a.nama_rekening_penerima, ".
								 "		 a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, ".
								 "		 a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, ". 
                 "     a.keterangan, a.status_lunas, a.tgl_lunas, ". 
                 "     a.petugas_lunas, decode(nvl(a.status_lunas,'T'),'Y',' (* sudah dibayar','') ket_byr ".
                 "from sijstk.pn_klaim_penerima_manfaat a, sijstk.pn_kode_tipe_penerima b ".
                 "where a.kode_tipe_penerima = b.kode_tipe_penerima(+) ".
                 "and a.kode_klaim = '$ls_kode_klaim' ".								 
                 "order by b.no_urut";
          //echo $sql;
					$DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;	
					$ln_tot_d_mnftipepenerima_nom_manfaat_netto =0;								
          while ($row = $DB->nextrow())
          {
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_TIPE_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NPWP'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BANK_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_REKENING_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_REKENING_PENERIMA'];?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_MANFAAT_NETTO'],2,".",",");?><i><?=$row['KET_BYR'];?></i></td>																		       																			        																												       																			        											
              <td align="center">
             		<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_penerima.php?&task=Edit&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&root_sender=<?=$ls_root_sender;?>&sender=<?=$ls_form_penetapan;?>&sender_activetab=2&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',950,520,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>																																																		
              </td>
            </tr>
            <?								    							
            $i++;//iterasi i
						$ln_tot_d_mnftipepenerima_nom_manfaat_netto  += $row["NOM_MANFAAT_NETTO"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
      <tr>
        <td style="text-align:right" colspan="6"><i>Total Diterima :<i>
          <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
        </td>
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_mnftipepenerima_nom_manfaat_netto,2,".",",");?></td>
				<td>				
				</td>				
      </tr>																		
    </table>
  </fieldset>

  <fieldset style="width:1000px;" id="fieldsetDokumenLain3">
          <legend><b><i><font color="#009999">Dokumen Tambahan</font></i></b></legend>
          <div class="row">
            <div>
              <table width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
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
          $sql = "SELECT A.*,
          DECODE(SYARAT_TAHAP_KE,'1','CSO','PMP') SUMBER_DOKUMEN 
           FROM PN.PN_KLAIM_DOKUMEN_TAMBAHAN A WHERE KODE_KLAIM= '$ls_kode_klaim' ORDER BY NO_URUT ASC";
          //echo $sql;
					$DB->parse($sql);
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
      <input type="button" name="btnfilter" style="width: 150px; margin-left:810px" class="btn green" id="btnfilter" value="Tambah Dokumen" onclick="uploadDokumenTambahan()"/>
        </div>
  </fieldset>

  <fieldset style="width:1000px;" id="fieldsetDokumenLain4">
          <legend><b><i><font color="#009999">Catatan</font></i></b></legend>
          <div class="row">
            <div>
              <table width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
                <thead>
                </thead>
                <tbody id="tbDataDokumenLain4">
                  <?
                    if ($ls_kode_klaim!="")
                    {
                      $sql = " SELECT * FROM PN.PN_KLAIM_CATATAN WHERE KODE_KLAIM= '$ls_kode_klaim' AND KODE_CATATAN='2' ORDER BY NO_URUT ASC";
                      $DB->parse($sql);
                      $DB->execute();	
                      $row = $DB->nextrow();
                      $keterangan_catatan=$row['KETERANGAN'];
                       
                    }
                  ?>
                  <tr>
     							  <td>
                    <textarea name="keterangan_catatan" id="keterangan_catatan" cols="150" rows="2" maxlength="4000"><?=$keterangan_catatan;?></textarea>
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
        <input type="button" name="btnfilter2" style="width: 150px; margin-left:810px" class="btn green" id="btnfilter2" value="Simpan Catatan" onclick="simpanCatatan()"/>
        </div>
  </fieldset>
		
</div>	

