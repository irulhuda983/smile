<?php
$pagetype = "form";
$gs_pagetitle = "Setup Batas Bayar";
require_once "../../includes/header_app_nosql.php";	
// $_REQUEST["mid"]="108030101010000";

?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>

<!--<script type="text/javascript" src="../../javascript/bootbox.min.js"></script>-->

<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../../assets/fonts/font-awesome-4.7.0/css/font-awesome.css">
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

	

</script>
<style>
.errorField{
	border: solid #fe8951 1px !important;
    background: rgba(254, 145, 81, 0.24);
}
.dataValid{
    background: #09b546;
    padding: 2px;
    color: #FFF;
    border-radius: 5px;
}

#formKiri button.btn {
    border: 1px solid #5492D6;
    cursor: pointer;
    padding: 2px 5px;
    font-size: 10px;
    font-family: verdana, arial, tahoma, sans-serif;
    border-radius: 2px;
    background: -webkit-linear-gradient(top,#5DBBF6 0%,#2788E0 100%);
    background: -o-linear-gradient(#5DBBF6, #2788E0);
    background: -moz-linear-gradient(#5DBBF6, #2788E0);
    background: linear-gradient(#5DBBF6, #2788E0);
}
#formKiri button.btn.hapus {
    border: 1px solid #ff0404;
    cursor: pointer;
    padding: 2px 5px;
    font-size: 10px;
    font-family: verdana, arial, tahoma, sans-serif;
    border-radius: 2px;
    background: -webkit-linear-gradient(top,#5DBBF6 0%,#2788E0 100%);
    background: -o-linear-gradient(#5DBBF6, #2788E0);
    background: -moz-linear-gradient(#5DBBF6, #2788E0);
    background: linear-gradient(#fd3749, #f51b1b);
}


fieldset {
    border: 1px solid #d4d4d4;
    padding: 10px;
    width: 78%;
}

</style>

<div id="formframe">
	<div id="dispError1" style="color:red;line-height: 19px;text-align: center;margin-top: 5px;display: none;"></div>
    
<div id="formKiri"> 

	<fieldset id="fieldset_form_sebab_dokumen" style="display: none"><legend>Form Sebab Dokumen </legend>
		<form name="form_sebab_dokumen" id="form_sebab_dokumen" role="form" method="post">
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Sebab Dokumen</label>
				<input type="text" id="v_kode_sebab_dokumen" name="v_kode_sebab_dokumen">
				<a title="Cari Dokumen terdaftar" href="#" id="v_lov_sebab_dokumen">
				<img src="../../images/help.png" alt="Cari Dokumen" border="0" align="absmiddle">
				</a>
				<input type="hidden" name="type">
				<input type="hidden" id="c_kode_sebab_klaim" name="c_kode_sebab_klaim" value="<?=$_REQUEST["kode_sebab_klaim"];?>">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Sebab Perlindungan</label>
				<select name="v_kode_sebab_perlindungan" id="v_kode_sebab_perlindungan">
					<option value="PU">Penerima Upah</option>
					<option value="PURNA">Purna</option>
					<option value="PRA">Pra</option>
				</select>
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Syarat Tahap Ke</label>
				<input type="text" id="v_syarat_tahap_ke_sebab_dokumen" class="number_value" name="v_syarat_tahap_ke_sebab_dokumen" size="30" maxlength="100" tabindex="3">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Flag Mandatory</label>
				<input type="checkbox" id="v_flag_mandatory_sebab_dokumen" name="v_flag_mandatory_sebab_dokumen" size="30" maxlength="100" tabindex="3">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>No Urut</label>
				<input type="text" id="v_no_urut_sebab_dokumen" class="number_value" name="v_no_urut_sebab_dokumen" size="30" maxlength="100" tabindex="3">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Tgl Nonaktif</label>
				<input type="text" id="v_tgl_nonaktif_sebab_dokumen" name="v_tgl_nonaktif_sebab_dokumen" value="" placeholder="dd/mm/yyyy" size="12">  
				<input type="image" align="top" onclick="return showCalendar('v_tgl_nonaktif_sebab_dokumen', 'dd/mm/yyyy');" src="../../images/dynCalendar.gif" style="height: 11px !important;"/>
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Status NonAktif</label>
				<input type="checkbox" id="v_status_nonaktif_sebab_dokumen" name="v_status_nonaktif_sebab_dokumen" size="30" maxlength="100" tabindex="3">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Keterangan</label>
				<textarea id="v_keterangan_sebab_dokumen" name="v_keterangan_sebab_dokumen" style="margin: 0px; width: 442px; height: 41px;"></textarea>
			</div>
			<div class="clear"></div>
		</form>
		<input type="button" class="btn green" id="btn_kembali" name="btn_kembali" value="KEMBALI" title="Klik Untuk kembali data">
		<input type="button" class="btn green" id="btn_simpan_sebab_dokumen" name="btn_simpan_sebab_dokumen" value="SIMPAN" title="Klik Untuk Simpan data">
	</fieldset>

	<!--Fieldset Table Sebab Dokumen -->
	<fieldset id="fieldset_sebab_dokumen" style="display: block"><legend>Data Setup Sebab Dokumen <?=$_REQUEST['kode_sebab_klaim'];?></legend>
		<div class="form-row_kiri">
			<button class="btn green" id="btn_tambah_sebab_dokumen" name="btn_tambah_sebab_dokumen"  title="Klik Untuk kembali ke table sebab klaim"><i class="fa fa-plus" aria-hidden="true"></i> TAMBAH</button>
		</div>
		<div class="form-row_kanan">
			Search By&nbsp;
			<select name="f_pilihan" id="f_pilihan">
				<option value="">----------Pilih----------</option>
				<option value="kode_sebab_dokumen">Kode Tipe Klaim</option>
			</select>
			<input type="text" name="f_keyword" id="f_keyword" placeholder="keyword">
			<input type="button" class="btn green" id="btncaridokumen" name="btncaridokumen" value="TAMPILKAN" title="Klik Untuk Tampilkan data">
		</div>
		
		<div class="clear5"></div>
		<table class="table table-striped table-bordered row-border hover" id="table_sebab_dokumen" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th scope="col" width="5%" class="align-left">Kode Dokumen</th>
					<th scope="col" width="5%" class="align-left">Kode Perlindungan</th>
					<th scope="col" width="10%" class="align-left">Syarat Tahap Ke</th>
					<th scope="col" width="5%" class="align-left">Flag Mandatory</th>
					<th scope="col" width="10%" class="align-center">Tgl Nonaktif</th>
					<th scope="col" width="5%" class="align-center">Status Non aktif</th>
					<th scope="col" width="15%" class="align-center">Action</th>
					<!-- <th scope="col" width="20%" class="align-center">Tambah</th> -->
				</tr>
			</thead>
		</table>
		<div class="clear"></div>
	</fieldset>

	
<!-- 	<br>
	<fieldset style="background: #FF0;"><legend style="background: #FF0; border: 1px solid #CCC;">KETERANGAN</legend>
    	<li>Pilih Jenis Pencarian</li>	
        <li>Input Kata Kunci (Keyword)</li>	
         <li>Klik Tombol CARI DATA untuk memulai pencarian data</li>	
		<li>Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
	</fieldset> -->
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		$('input').keyup(function() {
			this.value = this.value.toUpperCase();
		});
		
		$(".number_value" ).keydown(function (e) {
			// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
				 // Allow: Ctrl+A, Command+A
				(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
				 // Allow: home, end, left, right, down, up
				(e.keyCode >= 35 && e.keyCode <= 40)) {
					 // let it happen, don't do anything
					 return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});

		window.f_pilihan = "";
		window.f_keyword = "";
		window.c_kode_sebab_klaim = '<?=$_REQUEST["kode_sebab_klaim"];?>';
		loadTableDokumen(window.c_kode_sebab_klaim);

		//==========================action for sebab dokumen ================================

		$('#btn_tambah_sebab_dokumen').click(function(){
			resetForm();
			$('input[name*="btn_simpan"]').show();
			$('input[name="type"]').val('new');
			$('fieldset[id*="fieldset_"]').css("display","none");
			$('#fieldset_form_sebab_dokumen').css("display","block");
			$('#c_kode_sebab_klaim').val(window.c_kode_sebab_klaim);
			$('#v_lov_sebab_dokumen').attr("onclick","NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10012_lov_dokumen.php?b=v_kode_sebab_dokumen','',900,500,1)");
			$('#v_kode_sebab_dokumen').attr('readonly','readonly');
		})

		$('#btn_kembali').click(function(){
			$('fieldset[id*="fieldset_"]').css("display","none");
			$('#fieldset_sebab_dokumen').css("display","block");
		});

		$("#btncaridokumen").click(function() {
			loadTableDokumen();
		});

		//ajax save for sebab klaim
		$('#btn_simpan_sebab_dokumen').click(function() {
			// if(validasiForm()){
				preload(true);
				$.ajax({
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1001b_action.php?'+Math.random(),
					data: $('#form_sebab_dokumen').serialize(),
					success: function(data) {
						preload(false);
						jdata = JSON.parse(data);
						if(jdata.ret == '0'){
							window.parent.Ext.notify.msg('Berhasil', jdata.msg);
							$('fieldset[id*="fieldset_"').css("display","none");
							$('#fieldset_sebab_dokumen').css("display","");
							window.table_sebab_dokumen.columns.adjust().draw();
						} else {
							alertError(jdata.msg);
						}
					}
				});
			// }
		});

		//==========================end of action sebab dokumen


	});//end of document javascript






//===========================Function for Sebab Dokumen ======================================= 
function loadTableDokumen(kode_sebab_klaim){
	window.kode_sebab_klaim = '<?=$_REQUEST["kode_sebab_klaim"];?>';
	window.table_sebab_dokumen = $('#table_sebab_dokumen').DataTable({
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
        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1001b_action.php",
        	"type": "POST",
        	"data" : function(e) { 
        		e.type = 'query';
        		e.pilihan = window.f_pilihan;
        		e.keyword = window.f_keyword;
        		e.c_kode_sebab_klaim = kode_sebab_klaim;
        		
        	},complete : function(){
        		preload(false);
        	},error: function (){
            	alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
            	preload(false);
            }
        },
        "columns": [
        	{ "data": "KODE_DOKUMEN" },
        	{ "data": "KODE_PERLINDUNGAN" },
        	{ "data": "SYARAT_TAHAP_KE" },
        	{ "data": "FLAG_MANDATORY" },
            { "data": "TGL_NONAKTIF" },
            { "data": "STATUS_NONAKTIF" },
            { "data": "ACTION" },
        ],
        'aoColumnDefs': [
			{"className": "dt-center", "targets": [0,1,2,3]}
		]
        
    });
    window.table_sebab_dokumen.columns.adjust().draw();
	window.table_sebab_dokumen.on( 'draw.dt', function () {
		$('button[name*="edit_sebab_dokumen"]').click(function() {
			window.kode_sebab_dokumen = $(this).attr('kode_sebab_dokumen');
			getDataRowSebabDokumen('edit',window.kode_sebab_dokumen);
		});
		$('button[name*="view_sebab_dokumen"]').click(function() {
			window.kode_sebab_dokumen = $(this).attr('kode_sebab_dokumen');
			getDataRowSebabDokumen('view',window.kode_sebab_dokumen);
		});
		$('button[name*="hapus_sebab_dokumen"]').click(function() {
			window.kode_sebab_dokumen = $(this).attr('kode_sebab_dokumen');
			deleteDataSebabDokumen(window.c_kode_sebab_klaim, window.kode_sebab_dokumen);
		});
	});
}

function getDataRowSebabDokumen(type, kode_sebab_dokumen){
	preload(true);
	window.type = type;
	$.ajax({
		type: 'POST',
		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1001b_action.php?'+Math.random(),
		data: { 
				type : "view",
				keyword : kode_sebab_dokumen
		},success: function(data) {
			preload(false);
			// console.log(data);
			jdata = JSON.parse(data);
			//set field value 
			if(window.type=='view'){
				//set readonly for view
				$("#form_sebab_dokumen :input").attr('disabled', 'disabled');
				$('#v_kode_sebab_dokumen').css('background', '');
				$('input[name*="btn_simpan_sebab_dokumen"]').hide();
			}else{
				$("#form_sebab_dokumen :input").removeAttr('disabled', 'disabled');
				$('#v_kode_sebab_dokumen').attr('readonly','readonly');
				$('#v_kode_sebab_dokumen').attr('title', 'Field ini adalah kunci jadi tidak bisa diupdate');
				$('#v_kode_sebab_dokumen').css('background', '#dcdada');
				$('input[name*="btn_simpan"]').show();
			}
			$('#v_kode_sebab_dokumen').val(jdata.KODE_DOKUMEN);
			$('#v_no_urut_sebab_dokumen').val(jdata.NO_URUT);
			$('#v_syarat_tahap_ke_sebab_dokumen').val(jdata.SYARAT_TAHAP_KE);
			if(jdata.STATUS_NONAKTIF=="Y"){
				$('#v_status_nonaktif_sebab_dokumen').prop('checked', true);	
			}else{
				$('#v_status_nonaktif_sebab_dokumen').prop('checked', false);	
			}
			if(jdata.FLAG_MANDATORY=="Y"){
				$('#v_flag_mandatory_sebab_dokumen').prop('checked', true);	
			}else{
				$('#v_flag_mandatory_sebab_dokumen').prop('checked', false);	
			}
			$('#v_tgl_nonaktif_sebab_dokumen').val(jdata.TGL_NONAKTIF);
			$('#v_keterangan_sebab_dokumen').html(jdata.KETERANGAN);
			$('input[name="type"]').val('edit');
			//aktif form show 
			$('fieldset[id*="fieldset_"]').css("display","none");
			$('#fieldset_form_sebab_dokumen').css("display","block");

		},error:function(){
			preload(false);
			alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
		}
	});

}

function deleteDataSebabDokumen(kode_sebab_klaim, kode_sebab_dokumen){
	if(confirm("Anda yakin ingin menghapus data ini ? ")){
		preload(true);
		$.ajax({
			type: 'POST',
			url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1001b_action.php?'+Math.random(),
			data: {
				type : "delete",
				v_kode_sebab_dokumen : kode_sebab_dokumen,
				c_kode_sebab_klaim : kode_sebab_klaim
			},
			success: function(data) {
				preload(false);
				jdata = JSON.parse(data);
				if(jdata.ret == '0'){
					window.parent.Ext.notify.msg('Berhasil', jdata.msg);
					window.table_sebab_dokumen.columns.adjust().draw();
				} else {
					alertError(jdata.msg);
				}
			}
		});
	}
}

//=========================End of function sebab dokumen======================================


function resetForm(){
	$(':input').removeAttr('disabled', 'disabled');
	$(':input').removeAttr('readonly', 'readonly');
	$('input[type="text"]').css('background','');
	$('input[type="text"]').val('');
	$('textarea').removeAttr("disabled","disabled");
	$('textarea').val('');
	$('input[type="checkbox"]').prop("checked",false);
	
}


function validasiForm(){
	tgl_efektif = $('#TGL_EFEKTIF').val().split('/');
	tgl_akhir = $('#TGL_AKHIR').val().split('/');
	int_tgl_efektif = parseInt(tgl_efektif[2]+tgl_efektif[1]+tgl_efektif[0]);	
	int_tgl_akhir = parseInt(tgl_akhir[2]+tgl_akhir[1]+tgl_akhir[0]);
	
	if($('#TGL_EFEKTIF').val()=='')
	{
		alert("Tanggal Efektif tidak boleh kosong");
		return false;		
	}else if($('#TGL_AKHIR').val()=='')
	{
		alert("Tanggal Akhir tidak boleh kosong");
		return false;		
	}else if(int_tgl_akhir<=int_tgl_efektif)
	{
		alert("Tanggal Efektif tidak bisa lebih kecil dari tanggal akhir");
		return false;
	}else if($('#TGL_BATAS').val()=='' || parseInt($('#TGL_BATAS').val())==0)
	{
		alert("Tanggal batas tidak boleh kosong");
		return false;
	}else if(parseInt($('#TGL_BATAS').val())>=31)
	{
		alert("Tanggal batas tidak boleh melebihi dari tanggal 31");
		return false;
	}else if($('#JML_HARI_REKON').val()=='')
	{
		alert("Jumlah hari rekon tidak boleh kosong");
		return false;
	}else if($('#PERIODE_TOLERANSI').val()=='')
	{
		alert("Periode toleransi tidak boleh kosong");
		return false;
	}else{
		return true;
	}
	
}

</script>
<?
include "../../includes/footer_app_nosql.php";
?>