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
    		minHeight: 200,
    		y: 15,
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

</style>

<div id="actmenu">
	<div id="actbutton">
		<?
		if(isset($_REQUEST["task"])){
		?>
		<div style="float:left;">
			<div class="icon">
				<a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
			</div>
		</div>
		<div style="float:left;">
			<div class="icon">
				<a id="btn_close" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a>
			</div>
		</div>
        <?
		} else {
		?>
		<div style="float:left;">
			<div class="icon">
				<a href="javascript:void(0)" id="btn_view">
				<img src="http://<?=$HTTP_HOST;?>/images/application_get.png" align="absmiddle" border="0"> View</a>
			</div>
		</div>
		<div style="float:left;">
			<div class="icon">
				<a id="btn_edit" href="javascript:void(0)" ><img src="http://<?=$HTTP_HOST;?>/images/app_form_edit.png" align="absmiddle" border="0"> Edit</a>
			</div>
		</div>
		<div style="float:left;">
			<div class="icon">
				<a id="btn_delete" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/app_form_delete.png" align="absmiddle" border="0"> Delete</a>
			</div>
		</div>
		<div style="float:left;">
			<div class="icon"><a id="btn_new" href="javascript:void(0)">
			<img src="http://<?=$HTTP_HOST;?>/images/app_form_add.png" align="absmiddle" border="0"> New</a></div>
		</div>
		
        <?
		}
		?>
    </div>
</div>


<div id="formframe">
	<div id="dispError1" style="color:red;line-height: 19px;text-align: center;margin-top: 5px;display: none;"></div>
    
<?
if(isset($_REQUEST["task"]))
{
	if($_REQUEST["task"] == "edit" || $_REQUEST["task"] == "new" || $_REQUEST['task']=="view")
	{
?>
<div id="formKiri"> 
	<form name="form_1" id="form_1" role="form" method="post">
		<fieldset>
			<legend>Form Tipe Klaim </legend>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Tipe Klaim</label>
				<input type="text" id="v_kode_tipe_klaim" name="v_kode_tipe_klaim" size="30" maxlength="100" tabindex="3">
				<input type="hidden" id="type" name="type" value="<?=$_REQUEST['task']?>">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Nama Tipe Klaim</label>
				<input type="text" id="v_nama_tipe_klaim" name="v_nama_tipe_klaim" size="30" maxlength="100" tabindex="3">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>No Urut</label>
				<input type="text" id="v_no_urut" class="number_value" name="v_no_urut" size="30" maxlength="100" tabindex="3">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Tgl Nonaktif</label>
				<input type="text" id="v_tgl_nonaktif" name="v_tgl_nonaktif" value="" placeholder="dd/mm/yyyy" size="12">  
				<input type="image" align="top" onclick="return showCalendar('v_tgl_nonaktif', 'dd/mm/yyyy');" src="../../images/dynCalendar.gif" style="height: 11px !important;"/>
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Status NonAktif</label>
				<input type="checkbox" id="v_status_nonaktif" name="v_status_nonaktif" size="30" maxlength="100" tabindex="3">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Keterangan</label>
				<textarea id="v_keterangan" name="v_keterangan" style="margin: 0px; width: 442px; height: 41px;"></textarea>
			</div>
			<div class="clear"></div>
		</fieldset>
	</form>
</div>
<?
	} 
		
} else {
?>
	<div id="formKiri">
		<fieldset><legend>Data Setup Jenis Klaim</legend>
			
			<div class="form-row_kanan">
				Search By&nbsp;
				<select name="f_pilihan" id="f_pilihan">
					<option value="">----------Pilih----------</option>
					<option value="kode_tipe_klaim">Kode Tipe Klaim</option>
					<option value="nama_tipe_klaim">Nama Tipe Klaim</option>
				</select>
				<input type="text" name="f_keyword" id="f_keyword" placeholder="keyword">
				<input type="button" class="btn green" id="btncari" name="btncari" value="TAMPILKAN" title="Klik Untuk Tampilkan data">
			</div>
			
			<div class="clear5"></div>
			<table class="table table-striped table-bordered row-border hover" id="table_1" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th scope="col" width="5%" class="align-center">Action</th>
						<th scope="col" width="5%" class="align-left">Kode Tipe Klaim</th>
						<th scope="col" width="5%" class="align-left">Nama Tipe Klaim</th>
						<th scope="col" width="10%" class="align-center">Tgl Nonaktif</th>
						<th scope="col" width="10%" class="align-center">Status Non aktif</th>
					</tr>
				</thead>
			</table>
			<div class="clear"></div>
		</fieldset>
	
    <br>
    <fieldset style="background: #ebebeb;">
    	<div class="form-row_kanan">
			<button type="button" class="btn green" id="btn_tambah_sebab_klaim" name="btn_tambah_sebab_klaim" title="Klik Untuk Tambah Sebab Klaim"><i class="fa fa-plus" aria-hidden="true"></i> Add Sebab Klaim</button>
		</div>
	</fieldset>
	<br>
	<fieldset style="background: #FF0;"><legend style="background: #FF0; border: 1px solid #CCC;">KETERANGAN</legend>
    	<li>Pilih Jenis Pencarian</li>	
        <li>Input Kata Kunci (Keyword)</li>	
         <li>Klik Tombol TAMPILKAN untuk memulai pencarian data</li>	
		<li>Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
	</fieldset>
</div>

<? } ?>
<script type="text/javascript">
	$(document).ready(function(){
		<?
		if($_REQUEST["task"] == "edit" || $_REQUEST["task"] == "view"){
		?>	
			window.kode_tipe_klaim = '<?=$_REQUEST["id"];?>';
			window.type = '<?=$_REQUEST["task"];?>';
			$.ajax({
				type: 'POST',
				url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1001_action.php?'+Math.random(),
				data: { 
						type : "view",
						keyword : window.kode_tipe_klaim
				},success: function(data) {
					console.log(data);
					jdata = JSON.parse(data);
					if(window.type=='view'){
						$("#form_1 :input").attr('disabled', 'disabled');
						$('#btn_save').hide();
					}
					//set field value 
					$('#v_kode_tipe_klaim').val(jdata.KODE_TIPE_KLAIM);
					$('#v_kode_tipe_klaim').attr('readonly','readonly');
					// $('#v_kode_tipe_klaim').attr('title', 'Field ini adalah kunci jadi tidak bisa diupdate');
					$('#v_kode_tipe_klaim').css('background', '#dcdada');
					$('#v_nama_tipe_klaim').val(jdata.NAMA_TIPE_KLAIM);
					$('#v_no_urut').val(jdata.NO_URUT);
					if(jdata.STATUS_NONAKTIF=="Y"){
						$('#v_status_nonaktif').prop('checked', true);	
					}else{
						$('#v_status_nonaktif').prop('checked', false);	
					}
					$('#v_tgl_nonaktif').val(jdata.TGL_NONAKTIF);
					$('#v_keterangan').html(jdata.KETERANGAN);
					//set readonly for view
					
				}
			});

		<? }else{ ?>// else untuk tidak ada kondisi first page load 

		window.f_pilihan = $('#f_pilihan').val();
		window.f_keyword = $('#f_keyword').val();
		loadTable();
		
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
			
		
		
		$('#btn_view').click(function() {
			if($('input[name^="CHECK"][type=checkbox]:checked').length>0)
			{
				if($('input[name^="CHECK"][type=checkbox]:checked').length<2){
					window.kode_tipe_klaim = $('input[name^="CHECK"][type=checkbox]:checked').attr("kode_tipe_klaim");
					window.location='pn1001.php?task=view&id='+window.kode_tipe_klaim;
				}else{
					alert('Untuk melakukan view hanya bisa dipilih satu record');
				}
			} else {
				alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
			}
				
		});
		$('#btn_edit').click(function() {
			if($('input[name^="CHECK"][type=checkbox]:checked').length>0)
			{
				if($('input[name^="CHECK"][type=checkbox]:checked').length<2){	
					window.kode_tipe_klaim = $('input[name^="CHECK"][type=checkbox]:checked').attr("kode_tipe_klaim");
						window.location='pn1001.php?task=edit&id='+window.kode_tipe_klaim;
				}else{
					alert('Untuk melakukan edit hanya bisa dipilih satu record');
				}
			} else {
				alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
			}
		});

		$("#btn_delete").click(function() {
			if($('input[name^="CHECK"][type=checkbox]:checked').length>0)
			{
				if($('input[name^="CHECK"][type=checkbox]:checked').length<2){	
					window.kode_tipe_klaim = $('input[name^="CHECK"][type=checkbox]:checked').attr("kode_tipe_klaim");
					deleteData(window.kode_tipe_klaim);
				}else{
					alert('Untuk melakukan edit hanya bisa dipilih satu record');
				}
			} else {
				alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
			}
		});

		$("#btn_tambah_sebab_klaim").click(function() {
			if($('input[name^="CHECK"][type=checkbox]:checked').length>0)
			{
				if($('input[name^="CHECK"][type=checkbox]:checked').length<2){	
					window.kode_tipe_klaim = $('input[name^="CHECK"][type=checkbox]:checked').attr("kode_tipe_klaim");
					NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/form/pn1001a.php?kode_tipe_klaim='+window.kode_tipe_klaim,'Detail Sebab Klaim Tipe '+window.kode_tipe_klaim,'75%','93%','no');
				}else{
					alert('Untuk melakukan edit hanya bisa dipilih satu record');
				}
			} else {
				alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
			}
		});

		$('#btn_new').click(function() {
			window.location='pn1001.php?task=new';
		});
		$("#btncari").click(function() {
			loadTable();
		});


	<? 
		}//end of kondisi first page load 
	?>

		//javascript untuk dafault attribute yang digunakan 
		$('#btn_close').click(function() {
			window.location='pn1001.php';
		});
		$('#btn_save').click(function() {
			// if(validasiForm()){
				preload(true);
				$.ajax({
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1001_action.php?'+Math.random(),
					data: $('#form_1').serialize(),
					success: function(data) {
						preload(false);
						jdata = JSON.parse(data);
						if(jdata.ret == '0'){
							window.parent.Ext.notify.msg('Berhasil', jdata.msg);
							window.location='pn1001.php?task=view&id='+$('#v_kode_tipe_klaim').val();
						} else {
							alertError(jdata.msg);
						}
					}
				});
			// }
		});



			

	});//end of document javascript

function loadTable(){
	preload(true);
	window.f_pilihan = $('#f_pilihan').val();
	window.f_keyword = $('#f_keyword').val();
	window.table_1 = $('#table_1').DataTable({
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
        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1001_action.php",
        	"type": "POST",
        	"data" : function(e) { 
        		e.type = 'query';
        		e.pilihan = window.f_pilihan;
        		e.keyword = window.f_keyword;
        		
        	},complete : function(){
        		preload(false);
        	},error: function (){
            	alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
            	preload(false);
            }
        },
        "columns": [
        	{ "data": "ACTION" },
            { "data": "KODE_TIPE_KLAIM" },
            { "data": "NAMA_TIPE_KLAIM" },
            { "data": "TGL_NONAKTIF" },
            { "data": "STATUS_NONAKTIF" },
            // { "data": "DETAIL" },
        ],
        'aoColumnDefs': [
			{"className": "dt-center", "targets": [0,1,2,3,4]}
		]
        
    });
	window.table_1.columns.adjust().draw();
}

function deleteData(kode_tipe_klaim){
	if(confirm("Anda yakin ingin menghapus data ini ? ")){
		preload(true);
		$.ajax({
			type: 'POST',
			url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1001_action.php?'+Math.random(),
			data: {
				type : "delete",
				v_kode_tipe_klaim : kode_tipe_klaim
			},
			success: function(data) {
				preload(false);
				jdata = JSON.parse(data);
				if(jdata.ret == '0'){
					window.parent.Ext.notify.msg('Berhasil', jdata.msg);
					window.table_1.columns.adjust().draw();
				} else {
					alert(jdata.msg);
				}
			}
		});
	}
}



function detailTipeKlaim(kode_tipe_klaim){
	win = new window.parent.Ext.window.Window({
          title: 'Detail Data',
          id: 'detaildata',
          layout: 'fit',
          autoScroll: true,
          y: 90,
          width: '80%',
          height: 500,
          modal: true,
          autoLoad: {
            url: 'http://<?=$HTTP_HOST;?>/mod_pn/form/pn1001a.php?id='+kode_tipe_klaim+"&"+Math.random(),
            scripts: true
          },
          dockedItems: [{
            xtype: 'toolbar',
            dock: 'bottom',
            ui: 'footer',
            items: [
              { 
                xtype: 'button',
                text: 'Tutup',
                handler : function(){
                  win.close();
                }
              }
            ]
          }]
          
        });
    win.show();
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