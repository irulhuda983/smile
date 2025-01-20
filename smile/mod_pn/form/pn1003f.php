<div id="formframe">
	<div id="dispError1" style="color:red;line-height: 19px;text-align: center;margin-top: 5px;display: none;"></div>
    
<div id="formKiri"> 
	<fieldset id="fieldset_form_diagnosa" style="display: none"><legend>Form Diagnosa </legend>
		<form name="form_diagnosa" id="form_diagnosa" role="form" method="post">
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Diagnosa</label>
				<input type="text" id="v_kode_diagnosa" name="v_kode_diagnosa" size="30" maxlength="100" tabindex="3">
				<input type="hidden" name="type">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Nama Diagnosa</label>
				<input type="text" id="v_nama_diagnosa" name="v_nama_diagnosa" size="30" maxlength="100" tabindex="3">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Group ICD</label>
				<select id="v_kode_group_icd" name="v_kode_group_icd">
				</select>  
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
		<input type="button" class="btn green" id="btn_kembali" name="btn_kembali" value="KEMBALI" title="Klik Untuk kembali data">
		<input type="button" class="btn green" id="btn_simpan_diagnosa" name="btn_simpan_diagnosa" value="SIMPAN" title="Klik Untuk Simpan data">
	</fieldset>

	<!--Fieldset Table Sebab Klaim -->
	<fieldset id="fieldset_diagnosa" style="display: block"><legend>Data Diagnosa</legend>
		<div class="form-row_kiri">
			<button class="btn green" id="btn_tambah_diagnosa" name="btn_tambah_diagnosa"  title="Klik Untuk Menambahkan data"><i class="fa fa-plus" aria-hidden="true"></i> TAMBAH</button>
		</div>
		<div class="form-row_kanan">
			Search By&nbsp;
			<select name="f_pilihan" id="f_pilihan">
				<option value="">----------Pilih----------</option>
				<option value="kode_diagnosa">Kode Diagnosa</option>
				<option value="nama_diagnosa">Nama Diagnosa</option>
				
			</select>
			<input type="text" name="f_keyword" id="f_keyword" placeholder="keyword">
			<input type="button" class="btn green" id="btncari" name="btncari" value="TAMPILKAN" title="Klik Untuk Tampilkan data">
		</div>
		
		<div class="clear5"></div>
		<table class="table table-striped table-bordered row-border hover" id="table_diagnosa" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th scope="col" width="5%" class="align-left">Kode Diagnosa</th>
					<th scope="col" width="25%" class="align-left">Nama Diagnosa</th>
					<th scope="col" width="10%" class="align-left">Kode Group ICD</th>
					<th scope="col" width="5%" class="align-center">Tgl Nonaktif</th>
					<th scope="col" width="5%" class="align-center">Status Non aktif</th>
					<th scope="col" width="20%" class="align-center">Action</th>
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
		getLovGroupICD();
		

		$("#btncari").click(function() {
			loadTable();
		});
		
		$('input[name*="btn_kembali"]').click(function() {
			$('fieldset[id*="fieldset_"]').css("display","none");
			$('#fieldset_diagnosa').css("display","block");
		});
		$('#btn_tambah_diagnosa').click(function(){
			resetForm();
			$('input[name*="btn_simpan"]').show();
			$('input[name="type"]').val('new');
			$('fieldset[id*="fieldset_"]').css("display","none");
			$('#fieldset_form_diagnosa').css("display","block");
		});

		//ajax save for sebab klaim
		$('#btn_simpan_diagnosa').click(function() {
			// if(validasiForm()){
				
				preload(true);
				$.ajax({
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003f_action.php?'+Math.random(),
					data: $('#form_diagnosa').serialize(),
					success: function(data) {
						preload(false);
						jdata = JSON.parse(data);
						if(jdata.ret == '0'){
							window.parent.Ext.notify.msg('Berhasil', jdata.msg);
							$('fieldset[id*="fieldset_"').css("display","none");
							$('#fieldset_diagnosa').css("display","");
							window.table_diagnosa.columns.adjust().draw();
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
	window.table_diagnosa = $('#table_diagnosa').DataTable({
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
        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003f_action.php",
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
        	{ "data": "KODE_DIAGNOSA" },
            { "data": "NAMA_DIAGNOSA" },
            { "data": "KODE_GROUP_ICD" },
            { "data": "TGL_NONAKTIF" },
            { "data": "STATUS_NONAKTIF" },
            { "data": "ACTION" },
        ],
        'aoColumnDefs': [
			{"className": "dt-center", "targets": [0,2,3,4,5]}
		]
        
    });
	// window.table_diagnosa.columns.adjust().draw();
	window.table_diagnosa.on( 'draw.dt', function () {
		$('button[name*="edit_diagnosa"]').click(function() {
			window.kode_diagnosa = $(this).attr('kode_diagnosa');
			getDataRowDiagnosa('edit',window.kode_diagnosa);
		});
		$('button[name*="view_diagnosa"]').click(function() {
			window.kode_diagnosa = $(this).attr('kode_diagnosa');
			getDataRowDiagnosa('view',window.kode_diagnosa);
		});
		$('button[name*="hapus_diagnosa"]').click(function() {
			window.kode_diagnosa = $(this).attr('kode_diagnosa');
			deleteDataDiagnosa(window.kode_diagnosa);
		});
		$('button[name*="detil"]').click(function() {
			window.kode_diagnosa = $(this).attr('kode_diagnosa');
			NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/form/pn1003f1.php?kode_diagnosa='+window.kode_diagnosa,'Detil Diagnosa  '+window.kode_diagnosa,'60%','83%','no');
		});
	});
}


function deleteDataDiagnosa(kode_diagnosa){
	if(confirm("Anda yakin ingin menghapus data ini ? ")){
		preload(true);
		$.ajax({
			type: 'POST',
			url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003f_action.php?'+Math.random(),
			data: {
				type : "delete",
				v_kode_diagnosa : kode_diagnosa
			},
			success: function(data) {
				preload(false);
				jdata = JSON.parse(data);
				if(jdata.ret == '0'){
					window.parent.Ext.notify.msg('Berhasil', jdata.msg);
					window.table_diagnosa.columns.adjust().draw();
				} else {
					alertError(jdata.msg);
				}
			}
		});
	}
}

function getDataRowDiagnosa(type, kode_diagnosa){
	preload(true);
	window.type = type;

	$.ajax({
		type: 'POST',
		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003f_action.php?'+Math.random(),
		data: { 
				type : "view",
				keyword : kode_diagnosa
		},success: function(data) {
			preload(false);
			// console.log(data);
			jdata = JSON.parse(data);
			//set field value 
			if(window.type=='view'){
				//set readonly for view
				$("#form_diagnosa :input").attr('disabled', 'disabled');
				$('#v_kode_diagnosa').css('background', '');
				$('input[name*="btn_simpan"]').hide();
			}else{
				$("#form_diagnosa :input").removeAttr('disabled', 'disabled');
				$('#v_kode_diagnosa').attr('readonly','readonly');
				$('#v_kode_diagnosa').attr('title', 'Field ini adalah kunci jadi tidak bisa diupdate');
				$('#v_kode_diagnosa').css('background', '#dcdada');
				$('input[name*="btn_simpan"]').show();
			}
			$('#v_kode_group_icd option[value="'+jdata.KODE_GROUP_ICD+'"]').prop('selected', true);
			$('#v_kode_diagnosa').val(jdata.KODE_DIAGNOSA);
			$('#v_nama_diagnosa').val(jdata.NAMA_DIAGNOSA);
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
			$('#fieldset_form_diagnosa').css("display","");

		},error:function(){
			preload(false);
			alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
		}
	});

}


//===========================end of function for sebab klaim=================================== 


function getLovGroupICD(){
	$.ajax({
		type: 'POST',
		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003f_action.php?'+Math.random(),
		data: {
			type : "lov_group_icd"
		},
		success: function(data) {
			preload(false);
			jdata = JSON.parse(data);
			if(jdata.ret == '0'){
				var htmlJenisParameter = "<option value=''>-------pilih-------</option>";
				for(i=0;i<jdata.data.length;i++){
					htmlJenisParameter += "<option value='"+jdata.data[i].KODE_GROUP_ICD+"'>"+jdata.data[i].KODE_GROUP_ICD+"-"+jdata.data[i].NAMA_GROUP_ICD+"</option>";
				}
				$('#v_kode_group_icd').html(htmlJenisParameter);
			} else {
				alert(jdata.msg);
			}
		}
	});
}

function resetForm(){
	$(':input').removeAttr('disabled', 'disabled');
	$(':input').removeAttr('readonly', 'readonly');
	$('input[type="text"]').css('background','');
	$('input[type="text"]').val('');
	$('textarea').removeAttr("disabled","disabled");
	$('textarea').val('');
	$('input[type="checkbox"]').prop("checked",false);
	$('select[name*="v"] option[value=""]').prop('selected', true);
	
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
