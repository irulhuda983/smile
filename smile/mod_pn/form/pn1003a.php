<div id="formframe">
	<div id="dispError1" style="color:red;line-height: 19px;text-align: center;margin-top: 5px;display: none;"></div>
    
<div id="formKiri"> 
	<fieldset id="fieldset_form_tipe_penerima" style="display: none"><legend>Form Tipe Penerima </legend>
		<form name="form_tipe_penerima" id="form_tipe_penerima" role="form" method="post">
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Tipe Penerima</label>
				<input type="text" id="v_kode_tipe_penerima" name="v_kode_tipe_penerima" size="30" maxlength="100" tabindex="3">
				<input type="hidden" name="type">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Nama Tipe Penerima</label>
				<input type="text" id="v_nama_tipe_penerima" name="v_nama_tipe_penerima" size="30" maxlength="100" tabindex="3">
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
		</form>
		<input type="button" class="btn green" id="btn_kembali_sebab_klaim" name="btn_kembali_sebab_klaim" value="KEMBALI" title="Klik Untuk kembali data">
		<input type="button" class="btn green" id="btn_simpan_tipe_penerima" name="btn_simpan_tipe_penerima" value="SIMPAN" title="Klik Untuk Simpan data">
	</fieldset>

	<!--Fieldset Table Sebab Klaim -->
	<fieldset id="fieldset_tipe_penerima" style="display: block"><legend>Data Tipe Penerima</legend>
		<div class="form-row_kiri">
			<button class="btn green" id="btn_tambah_tipe_penerima" name="btn_tambah_tipe_penerima"  title="Klik Untuk Menambahkan data"><i class="fa fa-plus" aria-hidden="true"></i> TAMBAH</button>
		</div>
		<div class="form-row_kanan">
			Search By&nbsp;
			<select name="f_pilihan" id="f_pilihan">
				<option value="">----------Pilih----------</option>
				<option value="kode_tipe_penerima">Kode Tipe Penerima</option>
				<option value="nama_tipe_penerima">Nama Tipe Pnerima</option>
				
			</select>
			<input type="text" name="f_keyword" id="f_keyword" placeholder="keyword">
			<input type="button" class="btn green" id="btncari" name="btncari" value="TAMPILKAN" title="Klik Untuk Tampilkan data">
		</div>
		
		<div class="clear5"></div>
		<table class="table table-striped table-bordered row-border hover" id="table_tipe_penerima" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th scope="col" width="5%" class="align-left">Kode Tipe Penerima</th>
					<th scope="col" width="25%" class="align-left">Nama Tipe Penerima</th>
					<th scope="col" width="5%" class="align-center">Tgl Nonaktif</th>
					<th scope="col" width="5%" class="align-center">Status Non aktif</th>
					<th scope="col" width="10%" class="align-center">Action</th>
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


		//=================================action for sebab klaim============================
		loadTable();
		
		$("#btncari").click(function() {
			loadTable();
		});
		
		$('input[name*="btn_kembali"]').click(function() {
			$('fieldset[id*="fieldset_"]').css("display","none");
			$('#fieldset_tipe_penerima').css("display","block");
		});
		$('#btn_tambah_tipe_penerima').click(function(){
			resetForm();
			$('input[name*="btn_simpan"]').show();
			$('input[name="type"]').val('new');
			$('fieldset[id*="fieldset_"]').css("display","none");
			$('#fieldset_form_tipe_penerima').css("display","block");
		});

		//ajax save for sebab klaim
		$('#btn_simpan_tipe_penerima').click(function() {
			// if(validasiForm()){
				
				preload(true);
				$.ajax({
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003a_action.php?'+Math.random(),
					data: $('#form_tipe_penerima').serialize(),
					success: function(data) {
						preload(false);
						jdata = JSON.parse(data);
						if(jdata.ret == '0'){
							window.parent.Ext.notify.msg('Berhasil', jdata.msg);
							$('fieldset[id*="fieldset_"').css("display","none");
							$('#fieldset_tipe_penerima').css("display","");
							window.table_tipe_penerima.columns.adjust().draw();
						} else {
							alertError(jdata.msg);
						}
					}
				});
			// }
		});

		
		//==========================end of action sebab klaim ===============================

	});


//===============================function for sebab klaim================================== 
function loadTable(){
	preload(true);
	window.f_pilihan = $('#f_pilihan').val();
	window.f_keyword = $('#f_keyword').val();
	window.table_tipe_penerima = $('#table_tipe_penerima').DataTable({
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
        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003a_action.php",
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
        	{ "data": "KODE_TIPE_PENERIMA" },
            { "data": "NAMA_TIPE_PENERIMA" },
            { "data": "TGL_NONAKTIF" },
            { "data": "STATUS_NONAKTIF" },
            { "data": "ACTION" },
        ],
        'aoColumnDefs': [
			{"className": "dt-center", "targets": [0,2,3,4]}
		]
        
    });
	// window.table_tipe_penerima.columns.adjust().draw();
	window.table_tipe_penerima.on( 'draw.dt', function () {
		$('button[name*="edit_tipe_penerima"]').click(function() {
			window.kode_tipe_penerima = $(this).attr('kode_tipe_penerima');
			getDataRowTipePenerima('edit',window.kode_tipe_penerima);
		});
		$('button[name*="view_tipe_penerima"]').click(function() {
			window.kode_tipe_penerima = $(this).attr('kode_tipe_penerima');
			getDataRowTipePenerima('view',window.kode_tipe_penerima);
		});
		$('button[name*="hapus_tipe_penerima"]').click(function() {
			window.kode_tipe_penerima = $(this).attr('kode_tipe_penerima');
			deleteDataTipePenerima(window.kode_tipe_penerima);
		});
	});
}


function deleteDataTipePenerima(kode_tipe_penerima){
	if(confirm("Anda yakin ingin menghapus data ini ? ")){
		preload(true);
		$.ajax({
			type: 'POST',
			url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003a_action.php?'+Math.random(),
			data: {
				type : "delete",
				v_kode_tipe_penerima : kode_tipe_penerima
			},
			success: function(data) {
				preload(false);
				jdata = JSON.parse(data);
				if(jdata.ret == '0'){
					window.parent.Ext.notify.msg('Berhasil', jdata.msg);
					window.table_tipe_penerima.columns.adjust().draw();
				} else {
					alertError(jdata.msg);
				}
			}
		});
	}
}

function getDataRowTipePenerima(type, kode_tipe_penerima){
	preload(true);
	window.type = type;

	$.ajax({
		type: 'POST',
		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003a_action.php?'+Math.random(),
		data: { 
				type : "view",
				keyword : kode_tipe_penerima
		},success: function(data) {
			preload(false);
			// console.log(data);
			jdata = JSON.parse(data);
			//set field value 
			if(window.type=='view'){
				//set readonly for view
				$("#form_tipe_penerima :input").attr('disabled', 'disabled');
				$('#v_kode_tipe_penerima').css('background', '');
				$('input[name*="btn_simpan"]').hide();
			}else{
				$("#form_tipe_penerima :input").removeAttr('disabled', 'disabled');
				$('#v_kode_tipe_penerima').attr('readonly','readonly');
				$('#v_kode_tipe_penerima').attr('title', 'Field ini adalah kunci jadi tidak bisa diupdate');
				$('#v_kode_tipe_penerima').css('background', '#dcdada');
				$('input[name*="btn_simpan"]').show();
			}
			$('#v_kode_tipe_penerima').val(jdata.KODE_TIPE_PENERIMA);
			$('#v_nama_tipe_penerima').val(jdata.NAMA_TIPE_PENERIMA);
			$('#v_no_urut').val(jdata.NO_URUT);
			if(jdata.STATUS_NONAKTIF=="Y"){
				$('#v_status_nonaktif').prop('checked', true);	
			}else{
				$('#v_status_nonaktif').prop('checked', false);	
			}
			$('#v_tgl_nonaktif').val(jdata.TGL_NONAKTIF);
			$('#v_keterangan').html(jdata.KETERANGAN);
			$('input[name="type"]').val('edit');
			//aktif form show 
			$('fieldset[id*="fieldset_"]').css("display","none");
			$('#fieldset_form_tipe_penerima').css("display","");

		},error:function(){
			preload(false);
			alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
		}
	});

}


//===========================end of function for sebab klaim=================================== 



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
