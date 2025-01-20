<?php
	$source = $_REQUEST['source'];
	$parentName ='pn5043K.php';
	require_once "../../includes/header_app_nosql.php";
    include "../../style/smile_gridstyle.css";

	$KD_KANTOR 	= $_SESSION['kdkantorrole'];
	$KODE_ROLE 	= $_SESSION['regrole'];
	$KODE_USER 	= $_SESSION['USER'];
?>
<form name="formreg" id="formreg" role="form" method="post" style="display:none">
 <div id="actmenu">
    <div id="actbutton">
      <div style="float:left;">
      <?php
      if(isset($_REQUEST["task"]))
      {

        $source = $_REQUEST['source'];

        ?>
        <div style="float:left;"><div class="icon">
          <a id="btn_submit" href="#" tabindex="4" onclick="submit()"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Submit</a>
        </div></div>
          
        <div style="float:left;"><div class="icon">
          <a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/<?=$parentName;?>?mid=<?=$mid;?>&tgl_trans1_display=<?=$ld_tgl_trans1_display;?>&tgl_trans2_display=<?=$ld_tgl_trans2_display;?>"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a>
        </div></div>
        <?php
      }
      ?>
      </div>
    </div>
  </div>
   <table class="captionentry">
    <tr>
      <td align="left"><b><?=$_REQUEST["task"] == "View" ? "View Data" : ($_REQUEST["task"] == "Edit" ? "Koreksi Data Penerima" : "Entry Data Penerima") ?></b>
          <div id="dispError1" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></div>
          <input type="hidden" id="st_errval1" name="st_errval1">
      </td>
    </tr>
  </table>

  <?php 
  if( $_REQUEST["task"] == "View" || $_REQUEST["task"] == "New"){
    ?>
   

      <?//set tgl retur = sysdate ---------------------------------------
      if ($_REQUEST["task"] == "New")
      {
         echo "<script type=\"text/javascript\">fl_js_set_tgl_sysdate('tgl_retur');</script>";
         echo "<script type=\"text/javascript\">fl_js_set_tgl_sysdate('tgl_transaksi');</script>";
      }
    }else{ ?>

 
 <div id="formframe" style="width:1170px">
        <div id="formKiri" style="width:1050px">
          <input type="hidden" id="TYPE" name="TYPE" value="<?=$_REQUEST["task"];?>">
          <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
          <input type="hidden" id="level" name="level" value="<?=$_REQUEST["level"];?>">
          <input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">
          <input type="hidden" id="btn_task" name="btn_task" value="">
          <input type="hidden" name="trigersubmit" value="0">
          <!-- <input type="hidden" id="kode_segmen" name="kode_segmen" value="<?=$ls_kode_segmen;?>"> -->
          <input type="hidden" id="kode_agenda" name="kode_agenda" value="<?=$ls_kode_agenda;?>">
          <input type="hidden" id="tgl_trans1_display" name="tgl_trans1_display" value="<?=$ld_tgl_trans1_display;?>">
          <input type="hidden" id="tgl_trans2_display" name="tgl_trans2_display" value="<?=$ld_tgl_trans2_display;?>">
		  <input type="hidden" name="KD_PRG" value="">
		  <input type="hidden" name="KODE_TIPE_PENERIMA" value="">
			
          <table width="1050px" border="0">
            <tr>
			  <td width="50%" valign="top" align="center">
                <fieldset style="height:260px;width:500px;"><legend><font color="#009999"><i>Informasi Entry </i></font></legend>
					<div class="form-row_kiri">
						<label style="text-align:right">Kode Transfer</label>
						<input type="text" readonly name="KODE_TRANSFER" class="disabled">
					</div>
					<div class="clear"></div>

					<div class="form-row_kiri">
						<label style="text-align:right">No. Ref. Payment</label>
						<input type="text" readonly name="NO_REF_PAYMENT" class="disabled">
					</div>
					<div class="clear"></div>

					<div class="form-row_kiri">
						<label style="text-align:right">No. Ref. Transfer PLKK</label>
						<input type="text" readonly name="NO_REF_TRF_PLKK" class="disabled">
					</div>
					<div class="clear"></div>

					<div class="form-row_kiri" style="margin-top:10px">
						<label style="text-align:right">Nominal Netto</label>
						<input type="text" readonly name="NOM_NETTO" class="disabled">
					</div>
					<div class="clear"></div>

					<div class="form-row_kiri">
						<label style="text-align:right">Nominal Sudah Dibayar</label>
						<input type="text" readonly name="NOM_SUDAH_DIBAYAR" class="disabled">
					</div>
					<div class="clear"></div>

					<div class="form-row_kiri">
						<label style="text-align:right">Nominal Sisa</label>
						<input type="text" readonly name="NOM_SISA" class="disabled">
					</div>
					<div class="clear"></div>

					<div class="form-row_kiri" style="margin-top:10px">
						<label style="text-align:right">Jumlah Kode Klaim</label>
						<input type="text" readonly name="JML_KODE_KLAIM" class="disabled">
					</div>
					<div class="clear"></div>

					<div class="form-row_kiri">
						<label style="text-align:right">Info Status Transfer</label>
						<input type="text" readonly name="INFO_STATUS_TRANSFER" class="disabled">
					</div>
					<div class="clear"></div>
                </fieldset>
              </td>

              <td width="50%" valign="top" align="center" >
                <table>
					<tr>
						<td>
							<fieldset style="height:100px;"  ><legend><i><font color="#009999">Informasi Penerima Sebelum </font></i></legend>
							<div class="form-row_kiri">
								<label style="text-align:right">Nama Penerima</label>
								<input type="text" readonly name="NAMA_PENERIMA_PREV" class="disabled">
							<div>
							<div class="clear"></div>
								<div class="form-row_kiri">
								<label style="text-align:right">Nama Rekening Penerima</label>
								<input type="text" readonly name="NAMA_REKENING_PENERIMA_PREV" class="disabled">
							<div>
							<div class="clear"></div>
								<div class="form-row_kiri">
								<label style="text-align:right">Bank Penerima</label>
								<input type="text" readonly name="BANK_PENERIMA_PREV" class="disabled">
							<div>
							<div class="clear"></div>
								<div class="form-row_kiri">
								<label style="text-align:right">No Rekening</label>
								<input type="text" readonly name="NO_REK_PENERIMA_PREV" class="disabled">
							<div>
							<div class="clear"></div>		
							</fieldset>
						</td>
					</tr>


                  <!-- ================================================================================================== -->

                  <tr>
						<td>
						<fieldset style="height:130px;"><legend><i><font color="#009999">Informasi Penerima Update </font></i></legend>
                        </br>

                        <div class="form-row_kiri">
                        <label style = "text-align:right;">Nama Penerima</label>
                          <input type="text" id="nama_penerima" name="nama_penerima" value="" tabindex="1" maxlength="100"style="width:274px;background-color:#ffff99">
                        </div>
                        <div class="clear"></div>

							<div class="form-row_kiri">
                        <label style = "text-align:right;">Bank Penerima</label>
                          
						<input type="text" id="bank_penerima" name="bank_penerima" value="" style="width:175px;background-color:#ffff99">
						<input type="text" id="kode_bank_penerima" name="kode_bank_penerima" value="" style="width:30px;">
						<input type="text" id="id_bank_penerima" name="id_bank_penerima" value="" style="width:50px;">
						<a id="btn_lov_bank_penerima" href="#" tabomdex="2" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5043K_lov_bankpenerima.php?p=pn5043_edit.php&a=formreg&b=bank_penerima&c=kode_bank_penerima&d=id_bank_penerima','',800,500,1);">
						<img src="../../images/help.png" alt="Cari Bank" border="0" align="absmiddle"></a>
												
                        </div>
                        <div class="clear"></div>

                        <div class="form-row_kiri">
                        <label style = "text-align:right;">No Rekening</label>
                          <input type="text" id="no_rek_penerima" name="no_rek_penerima" onblur="fl_js_cek_validasi_rekening();" value="<?=$ls_no_rek_penerima;?>" tabindex="4" maxlength="30" style="width:120px;background-color:#ffff99">
                          <!-- <input type="text" id="nama_rekening_penerima_ws" name="nama_rekening_penerima_ws" value="<?=$ls_nama_rek_penerima;?>" maxlength="100" style="width:165px;" readonly class="disabled" placeholder="-- validasi rekening bank --" onblur="this.value=this.value.toUpperCase();"> -->
                          <input type="checkbox" id="cb_valid_rekening" name="cb_valid_rekening" class="cebox" disabled <?=$ls_status_valid_rekening_penerima=="Y" ||$ls_status_valid_rekening_penerima=="ON" ||$ls_status_valid_rekening_penerima=="on" ? "checked" : "";?>><i><font color="#009999">Valid</font></i>
                        	<input type="hidden" id="nama_rek_penerima" name="nama_rek_penerima" value="<?=$ls_nama_rek_penerima;?>" tabindex="5" maxlength="100" readonly class="disabled" style="width:250px;">
						</div>
                        <div class="clear"></div>

                        </br>

                        <div class="form-row_kiri">
                        <label style = "text-align:right;">&nbsp;</label>
                          <i><font color="#ff0000"><img src="../../images/warning.gif" border="0" alt="Tambah" align="absmiddle" style="height:16px;"/> &nbsp; Note: Rekening harus valid...! </font></i>
                          <input type="hidden" id="status_valid_rekening_penerima" name="status_valid_rekening_penerima" value="<?=$ls_status_valid_rekening_penerima;?>">
                          <? $ls_status_valid_rekening_penerima = isset($ls_status_valid_rekening_penerima) ? $ls_status_valid_rekening_penerima : "T"; ?>
                        	<input type="hidden" id="metode_transfer" name="metode_transfer" value="<?=$ls_metode_transfer;?>">
												</div>
                        <div class="clear"></div>
											</fieldset>
										</td>
									</tr>



                </table>
              </td>
            </tr>
          </table>
          <?php
            if(!is_null($ls_keterangan_error)){
          ?>
          <div style="background: #F2F2F2;margin-top:2px;margin-left:5px;padding:10px 20px;border:1px solid #ececec; width:1010px;">
            <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
            <li style="margin-left:15px;">Data di form merupakan data hasil koreksi dari monitoring</li>
            <li style="margin-left:15px;"><font color="#ff0000">KETERANGAN ERROR</font> pada form di atas merupakan penjelasan data tersebut perlu dikoreksi</li>
          </div>

          <?php
            }
          ?>
          <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;">
          <span><i><b>Keterangan:</b></i></span>
          <li style="margin-left:15px;">Form Hanya Digunakan di jam SKN  <font color="#ff0000">(di hari yang sama sd pukul 13.30 WIB)</font></li>
        </div>
        </div><!-- end div id="formKiri"-->
        
      </div><!-- end div id="formframe"-->

<?php } ?>


</form>

<script type="text/javascript">
	loadData();
	function loadData() {	
		preload(true);
		$.ajax({
			type: 'POST',
			url: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5043K_query.php?"+Math.random(),
			data: {
				tipe: 'act_geterror',
				kode_kantor:'<?php echo $KD_KANTOR;?>',
				kode_user:'<?php echo $KODE_USER;?>',
				tglawaldisplay: '<?php echo $_GET["tglawaldisplay"];?>',
				tglakhirdisplay: '<?php echo $_GET["tglakhirdisplay"];?>',	
				page: <?php echo $_GET["page"];?>,
				page_item: <?php echo $_GET["page_item"];?>,	
				norekpenerima: '<?php echo $_GET["norekpenerima"];?>',
				kodetransfer: '<?php echo $_GET["kodetransfer"];?>'	
			},
			success: function(data){ 
				var jdata = JSON.parse(data);
				if (jdata.ret == "-1") alert('no data')
				var jdt = jdata.data[0]
				$('input[name="KODE_TRANSFER"').val(jdt.KODE_TRANSFER)
				$('input[name="NAMA_TIPE_PENERIMA"').val(jdt.NAMA_TIPE_PENERIMA)
				$('input[name="KODE_TIPE_PENERIMA"').val(jdt.KODE_TIPE_PENERIMA)
				$('input[name="KD_PRG"').val(jdt.KD_PRG)
				$('input[name="NO_REF_PAYMENT"').val(jdt.NO_REF_PAYMENT)
				$('input[name="NO_REF_TRF_PLKK"').val(jdt.NO_REF_TRF_PLKK)
				$('input[name="BANK_PENERIMA_PREV"').val(jdt.BANK_PENERIMA)
				$('input[name="NO_REK_PENERIMA_PREV"').val(jdt.NO_REKENING_PENERIMA)
				$('input[name="NAMA_REKENING_PENERIMA_PREV"').val(jdt.NAMA_REKENING_PENERIMA)
				$('input[name="NAMA_PENERIMA_PREV"').val(jdt.NAMA_PENERIMA)
				$('input[name="NOM_NETTO"').val(format_uang(jdt.NOM_NETTO))
				$('input[name="NOM_SUDAH_DIBAYAR"').val(format_uang(jdt.NOM_SUDAH_DIBAYAR))
				$('input[name="NOM_SISA"').val(format_uang(jdt.NOM_SISA))
				$('input[name="JML_KODE_KLAIM"').val(jdt.JML_KODE_KLAIM)
				$('input[name="INFO_STATUS_TRANSFER"').val(jdt.INFO_STATUS_TRANSFER)

				$('input[name="bank_penerima"').val(jdt.BANK_PENERIMA)
				$('input[name="no_rek_penerima"').val(jdt.NO_REKENING_PENERIMA)
				$('input[name="nama_rekening_penerima"').val(jdt.NAMA_REKENING_PENERIMA)
				$('input[name="nama_penerima"').val(jdt.NAMA_REKENING_PENERIMA)
				$('input[name="id_bank_penerima"').val(jdt.ID_BANK_PENERIMA)
				$('input[name="kode_bank_penerima"').val(jdt.KODE_BANK_PENERIMA)

			},
			complete: function(){
				$('#formreg').show()
				preload(false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				preload(false);
			}
		})
	}

	function submit() {
		let kodetransfer = $('input[name="KODE_TRANSFER"').val()
		let norekprev = $('input[name="NO_REK_PENERIMA_PREV"').val()
		let namapenerima = $('input[name="NAMA_PENERIMA_PREV"').val()
		let namarekpenerima = $('input[name="nama_penerima"').val()
		let norekpenerima = $('input[name="no_rek_penerima"').val()
		let bankpenerima = $('input[name="bank_penerima"').val()
		let kodebankpenerima =  $('input[name="kode_bank_penerima"').val()
		let idbankpenerima = $('input[name="id_bank_penerima"').val()
		let kodetipepenerima = $('input[name="KODE_TIPE_PENERIMA"').val()
		let kdprg = $('input[name="KD_PRG"').val()
		let noreftrfplkk = $('input[name="NO_REF_TRF_PLKK"]').val()

		if (!norekpenerima) {
			alert('No Rekening penerima tidak boleh kosong');
			return;
		}
		if (!namarekpenerima) {
			alert('No Penerima tidak boleh kosong');
			return;
		}
		if (!bankpenerima || !kodebankpenerima || !idbankpenerima) {
			alert('Bank Penerima tidak boleh kosong');
			return;
		}
		if ($('#cb_valid_rekening').is(':checked') != true){
			alert('No rekening penerima belum valid');
			return;
		}

		preload(true);
		$.ajax({
			type: 'POST',
			url: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5043K_query.php?"+Math.random(),
			data: {
				tipe: 'act_submitkoreksi',
				kodetransfer,
				norekprev,
				namapenerima,
				bankpenerima,
				norekpenerima,
				namarekpenerima,
				kodebankpenerima,
				idbankpenerima,
				kodetipepenerima,
				kdprg,
				noreftrfplkk
			},
			success: function(data){ 
				console.log(data)
				var jdata = JSON.parse(data);
				if (jdata.ret == '0')
				{
					window.parent.Ext.notify.msg('Penyimpanan data berhasil, session dilanjutkan...', jdata.msg);
					window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/form/<?=$parentName;?>?mid=<?=$mid;?>&tgl_trans1_display=<?=$ld_tgl_trans1_display;?>&tgl_trans2_display=<?=$ld_tgl_trans2_display;?>');
				}else
				{
					alert(jdata.msg);
				}
				preload(false);

			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				preload(false);
			}
		})
	}

	//validasi rekening penerima, pada saat menginput nomor rekening penerima --
	function fl_js_cek_validasi_rekening()
	{
		var v_kode_bank_tujuan = $('#kode_bank_penerima').val();
		var v_no_rek_tujuan = $('#no_rek_penerima').val();
		var v_nama_bank_tujuan = $('#bank_penerima').val();

		if (v_kode_bank_tujuan=="")
		{
			$('#nama_rekening_penerima_ws').val('');
			window.document.getElementById('cb_valid_rekening').checked = false;
			$('#status_valid_rekening_penerima').val('T');
			$('#nama_rek_penerima').val('');
			alert('Isikan Bank Penerima..!!!');
		} else if (v_no_rek_tujuan=="")
		{
				$('#nama_rekening_penerima_ws').val('');
				window.document.getElementById('cb_valid_rekening').checked = false;
				$('#status_valid_rekening_penerima').val('T');
				$('#nama_rek_penerima').val('');
				alert('Isikan No Rekening Penerima..!!!');
		}else
		{
			preload(true);
			$.ajax(
			{
				type: 'POST',
				url: 'http://<?=$HTTP_HOST;?>/mod_hu/ajax/hu_file_entry_retur_validasi.php?'+Math.random(),
				data: {
					TYPE:'validate_rekening_penerima',
					NO_REK_TUJUAN:v_no_rek_tujuan,
					KODE_BANK_ATB_TUJUAN:v_kode_bank_tujuan,
					NAMA_BANK_TUJUAN:v_nama_bank_tujuan
				},
				success: function(data)
				{
					preload(false);
					jdata = JSON.parse(data);
					if(jdata.ret=="0")
					{
						window.document.getElementById('cb_valid_rekening').checked = true;
						$('#status_valid_rekening_penerima').val('Y');
						$('#nama_penerima').val(jdata.data['NAMA_REK_TUJUAN']);
						document.getElementById('nama_penerima').readOnly = true;
						document.getElementById('nama_penerima').style.backgroundColor='#F5F5F5';
					}else{
						window.parent.Ext.notify.msg('Gagal validasi rekening...', jdata.msg);
						$('#status_valid_rekening_penerima').val('T');
						$('#nama_rek_penerima').val('');
						$('#no_rek_penerima').val('');	//no rekening penerima harus valid
						document.getElementById('no_rek_penerima').placeholder = "-- rekening valid --";
						window.document.getElementById('cb_valid_rekening').checked = false;
					}
				}
				});
			}
	}
	//end validasi rekening penerima, pada saat menginput no_rekening penerima -
</script>
<?php
include "../../includes/footer_app_nosql.php";
?>