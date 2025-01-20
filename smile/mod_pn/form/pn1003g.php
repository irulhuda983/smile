<div id="formframe">
	<div id="dispError1" style="color:red;line-height: 19px;text-align: center;margin-top: 5px;display: none;"></div>
    
<div id="formKiri"> 
	<fieldset id="fieldset_form_pelaporan" style="display: none"><legend>Form Pelaporan </legend>
		<form name="form_pelaporan" id="form_pelaporan" role="form" method="post">
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Pelaporan</label>
				<input type="text" id="v_kode_pelaporan" name="v_kode_pelaporan" size="30" maxlength="100" tabindex="3">
				<input type="hidden" name="type">
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Sebab Klaim</label>
				<select id="v_kode_sebab_klaim" name="v_kode_sebab_klaim">
				</select>  
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Segmen</label>
				<select id="v_kode_segmen" name="v_kode_segmen">
					<option value="">------pilih------</option>
					<option value="PU">PENERIMA UPAH</option>
					<option value="BPU">BUKAN PENERIMA UPAH</option>
					<option value="TKI">TENAGA KERJA INDONESIA</option>
					<option value="CTKI">CALON TENAGA KERJA INDOENESIA</option>
				</select>  
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Jenis Kasus</label>
				<select id="v_kode_jenis_kasus" name="v_kode_jenis_kasus">
				</select>  
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Lokasi Kecelakaan</label>
				<select id="v_kode_lokasi_kecelakaan" name="v_kode_lokasi_kecelakaan">
				</select>  
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Akibat Diderita</label>
				<select id="v_kode_akibat_diderita" name="v_kode_akibat_diderita">
				</select>  
			</div>
			<div class="clear"></div>
			<div class="form-row_kiri">
				<label>Kode Kondisi Terakhir</label>
				<select id="v_kode_kondisi_terakhir" name="v_kode_kondisi_terakhir">
				</select>  
			</div>
			<!-- <div class="clear"></div>
			<div class="form-row_kiri">
				<label>No Urut</label>
				<input type="text" id="v_no_urut" class="number_value" name="v_no_urut" size="30" maxlength="100" tabindex="3">
			</div> -->
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
		<input type="button" class="btn green" id="btn_simpan_pelaporan" name="btn_simpan_pelaporan" value="SIMPAN" title="Klik Untuk Simpan data">
	</fieldset>

	<!--Fieldset Table Sebab Klaim -->
	<fieldset id="fieldset_pelaporan" style="display: block"><legend>Data Pelaporan</legend>
		<div class="form-row_kiri">
			<button class="btn green" id="btn_tambah_pelaporan" name="btn_tambah_pelaporan"  title="Klik Untuk Menambahkan data"><i class="fa fa-plus" aria-hidden="true"></i> TAMBAH</button>
		</div>
		<div class="form-row_kanan">
			Search By&nbsp;
			<select name="f_pilihan" id="f_pilihan">
				<option value="">----------Pilih----------</option>
				<option value="kode_pelaporan">Kode Pelaporan</option>
				<option value="kode_sebab_klaim">Kode Sebab Klaim</option>
				<option value="kode_segmen">Kode Segmen</option>
				<option value="kode_jenis_kasus">Kode Jenis Kasus</option>
				<option value="kode_lokasi_kecelakaan">Kode Lokasi Kecelakaan</option>
				<option value="kode_akibat_diderita">Kode Akibat Diderita</option>
				<option value="kode_kondisi_terakhir">Kode Kondisi Terakhir</option>
			</select>
			<input type="text" name="f_keyword" id="f_keyword" placeholder="keyword">
			<input type="button" class="btn green" id="btncari" name="btncari" value="TAMPILKAN" title="Klik Untuk Tampilkan data">
		</div>
		
		<div class="clear5"></div>
		<table class="table table-striped table-bordered row-border hover" id="table_pelaporan" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th scope="col" width="5%" class="align-left">Kode Pelaporan</th>
					<th scope="col" width="10%" class="align-left">Kode Sebab Klaim</th>
					<th scope="col" width="10%" class="align-center">Kode Segmen</th>
					<th scope="col" width="10%" class="align-center">Kode Jenis Kasus</th>
					<th scope="col" width="10%" class="align-center">Kode Lokasi Kecelakaan</th>
					<th scope="col" width="10%" class="align-center">Kode Akibat diderita</th>
					<th scope="col" width="10%" class="align-center">Kode Kondisi Terakhir</th>
					<th scope="col" width="10%" class="align-center">Action</th>
				</tr>
			</thead>
		</table>
		<div class="clear"></div>
	</fieldset>
	
	<br>
	<fieldset style="background: #FF0;"><legend style="background: #FF0; border: 1px solid #CCC;">KETERANGAN</legend>
    	<li>Pilih Jenis Pencarian</li>	
        <li>Input Kata Kunci (Keyword)</li>	
         <li>Klik Tombol CARI DATA untuk memulai pencarian data</li>	
		<li>Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
	</fieldset>
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
		loadLov();

		$("#btncari").click(function() {
			loadTable();
		});
		
		$('input[name*="btn_kembali"]').click(function() {
			$('fieldset[id*="fieldset_"]').css("display","none");
			$('#fieldset_pelaporan').css("display","block");
		});
		$('#btn_tambah_pelaporan').click(function(){
			resetForm();
			$('input[name*="btn_simpan"]').show();
			$('input[name="type"]').val('new');
			$('fieldset[id*="fieldset_"]').css("display","none");
			$('#fieldset_form_pelaporan').css("display","block");
		});

		//ajax save for sebab klaim
		$('#btn_simpan_pelaporan').click(function() {
			// if(validasiForm()){
				
				preload(true);
				$.ajax({
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003g_action.php?'+Math.random(),
					data: $('#form_pelaporan').serialize(),
					success: function(data) {
						preload(false);
						jdata = JSON.parse(data);
						if(jdata.ret == '0'){
							window.parent.Ext.notify.msg('Berhasil', jdata.msg);
							$('fieldset[id*="fieldset_"').css("display","none");
							$('#fieldset_pelaporan').css("display","");
							window.table_pelaporan.columns.adjust().draw();
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
	window.table_pelaporan = $('#table_pelaporan').DataTable({
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
        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003g_action.php",
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
        	{ "data": "KODE_PELAPORAN" },
            { "data": "KODE_SEBAB_KLAIM" },
            { "data": "KODE_SEGMEN" },
            { "data": "KODE_JENIS_KASUS" },
            { "data": "KODE_LOKASI_KECELAKAAN" },
            { "data": "KODE_AKIBAT_DIDERITA" },
            { "data": "KODE_KONDISI_TERAKHIR" },
            { "data": "ACTION" },
        ],
        'aoColumnDefs': [
			{"className": "dt-center", "targets": [0,1,2,3,7]}
		]
        
    });
	// window.table_pelaporan.columns.adjust().draw();
	window.table_pelaporan.on( 'draw.dt', function () {
		$('button[name*="edit_pelaporan"]').click(function() {
			window.kode_pelaporan = $(this).attr('kode_pelaporan');
			getDataRowPelaporan('edit',window.kode_pelaporan);
		});
		$('button[name*="view_pelaporan"]').click(function() {
			window.kode_pelaporan = $(this).attr('kode_pelaporan');
			getDataRowPelaporan('view',window.kode_pelaporan);
		});
		$('button[name*="hapus_pelaporan"]').click(function() {
			window.kode_pelaporan = $(this).attr('kode_pelaporan');
			deleteDataPelaporan(window.kode_pelaporan);
		});
	});
}


function deleteDataPelaporan(kode_pelaporan){
	if(confirm("Anda yakin ingin menghapus data ini ? ")){
		preload(true);
		$.ajax({
			type: 'POST',
			url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003g_action.php?'+Math.random(),
			data: {
				type : "delete",
				v_kode_pelaporan : kode_pelaporan
			},
			success: function(data) {
				preload(false);
				jdata = JSON.parse(data);
				if(jdata.ret == '0'){
					window.parent.Ext.notify.msg('Berhasil', jdata.msg);
					window.table_pelaporan.columns.adjust().draw();
				} else {
					alertError(jdata.msg);
				}
			}
		});
	}
}

function getDataRowPelaporan(type, kode_pelaporan){
	preload(true);
	window.type = type;
	$.ajax({
		type: 'POST',
		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003g_action.php?'+Math.random(),
		data: { 
				type : "view",
				keyword : kode_pelaporan
		},
		async: false,
		success: function(data) {
			// preload(false);
			// console.log(data);
			jdata = JSON.parse(data);
			//set field value 
			if(window.type=='view'){
				//set readonly for view
				$("#form_pelaporan :input").attr('disabled', 'disabled');
				$('#v_kode_pelaporan').css('background', '');
				$('input[name*="btn_simpan"]').hide();
			}else{
				$("#form_pelaporan :input").removeAttr('disabled', 'disabled');
				$('#v_kode_pelaporan').attr('readonly','readonly');
				$('#v_kode_pelaporan').attr('title', 'Field ini adalah kunci jadi tidak bisa diupdate');
				$('#v_kode_pelaporan').css('background', '#dcdada');
				$('input[name*="btn_simpan"]').show();
			}
			$('#v_kode_pelaporan').val(jdata.KODE_PELAPORAN);
			$('#v_kode_sebab_klaim option[value="'+jdata.KODE_SEBAB_KLAIM+'"]').prop('selected', true);
			$('#v_kode_segmen option[value="'+jdata.KODE_SEGMEN+'"]').prop('selected', true);
			$('#v_kode_jenis_kasus option[value="'+jdata.KODE_JENIS_KASUS+'"]').prop('selected', true);
			$('#v_kode_lokasi_kecelakaan option[value="'+jdata.KODE_LOKASI_KECELAKAAN+'"]').prop('selected', true);
			$('#v_kode_akibat_diderita option[value="'+jdata.KODE_AKIBAT_DIDERITA+'"]').prop('selected', true);
			$('#v_kode_kondisi_terakhir option[value="'+jdata.KODE_KONDISI_TERAKHIR+'"]').prop('selected', true);
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
			$('#fieldset_form_pelaporan').css("display","");

		},error:function(){
			preload(false);
			alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
		},complete:function(){
			preload(false);
		}
	});

}


function loadLov(){
	getLovKodeSebabKlaim();
	getLovKodeJenisKasus();
	getLovKodeLokasiKecelakaan();
	getLovKodeAkibatDiderita();
	getLovKodeKondisiTerakhir();
}


function getLovKodeSebabKlaim(){
	$.ajax({
		type: 'POST',
		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003g_action.php?'+Math.random(),
		data: {
			type : "lov_kode_sebab_klaim"
		},
		async: false,
		success: function(data) {
			jdata = JSON.parse(data);
			if(jdata.ret == '0'){
				var htmlJenisParameter = "<option value=''>-------pilih-------</option>";
				for(i=0;i<jdata.data.length;i++){
					htmlJenisParameter += "<option value='"+jdata.data[i].KODE_SEBAB_KLAIM+"'>"+jdata.data[i].KODE_SEBAB_KLAIM+"-"+jdata.data[i].NAMA_SEBAB_KLAIM+"</option>";
				}
				$('#v_kode_sebab_klaim').html(htmlJenisParameter);
			} else {
				alert(jdata.msg);
			}
		}
	});
}

function getLovKodeJenisKasus(){
	$.ajax({
		type: 'POST',
		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003g_action.php?'+Math.random(),
		data: {
			type : "lov_kode_jenis_kasus"
		},
		async: false,
		success: function(data) {
			jdata = JSON.parse(data);
			if(jdata.ret == '0'){
				var htmlJenisParameter = "<option value=''>-------pilih-------</option>";
				for(i=0;i<jdata.data.length;i++){
					htmlJenisParameter += "<option value='"+jdata.data[i].KODE_JENIS_KASUS+"'>"+jdata.data[i].KODE_JENIS_KASUS+"-"+jdata.data[i].NAMA_JENIS_KASUS+"</option>";
				}
				$('#v_kode_jenis_kasus').html(htmlJenisParameter);
			} else {
				alert(jdata.msg);
			}
		}
	});
}

function getLovKodeLokasiKecelakaan(){
	$.ajax({
		type: 'POST',
		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003g_action.php?'+Math.random(),
		data: {
			type : "lov_kode_lokasi_kecelakaan"
		},
		async: false,
		success: function(data) {
			jdata = JSON.parse(data);
			if(jdata.ret == '0'){
				var htmlJenisParameter = "<option value=''>-------pilih-------</option>";
				for(i=0;i<jdata.data.length;i++){
					htmlJenisParameter += "<option value='"+jdata.data[i].KODE_LOKASI_KECELAKAAN+"'>"+jdata.data[i].KODE_LOKASI_KECELAKAAN+"-"+jdata.data[i].NAMA_LOKASI_KECELAKAAN+"</option>";
				}
				$('#v_kode_lokasi_kecelakaan').html(htmlJenisParameter);
			} else {
				alert(jdata.msg);
			}
		}
	});
}

function getLovKodeAkibatDiderita(){
	$.ajax({
		type: 'POST',
		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003g_action.php?'+Math.random(),
		data: {
			type : "lov_kode_akibat_diderita"
		},
		async: false,
		success: function(data) {
			jdata = JSON.parse(data);
			if(jdata.ret == '0'){
				var htmlJenisParameter = "<option value=''>-------pilih-------</option>";
				for(i=0;i<jdata.data.length;i++){
					htmlJenisParameter += "<option value='"+jdata.data[i].KODE_AKIBAT_DIDERITA+"'>"+jdata.data[i].KODE_AKIBAT_DIDERITA+"-"+jdata.data[i].NAMA_AKIBAT_DIDERITA+"</option>";
				}
				$('#v_kode_akibat_diderita').html(htmlJenisParameter);
			} else {
				alert(jdata.msg);
			}
		}
	});
}

function getLovKodeKondisiTerakhir(){
	$.ajax({
		type: 'POST',
		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1003g_action.php?'+Math.random(),
		data: {
			type : "lov_kode_kondisi_terakhir"
		},
		async: false,
		success: function(data) {
			jdata = JSON.parse(data);
			if(jdata.ret == '0'){
				var htmlJenisParameter = "<option value=''>-------pilih-------</option>";
				for(i=0;i<jdata.data.length;i++){
					htmlJenisParameter += "<option value='"+jdata.data[i].KODE_KONDISI_TERAKHIR+"'>"+jdata.data[i].KODE_KONDISI_TERAKHIR+"-"+jdata.data[i].NAMA_KONDISI_TERAKHIR+"</option>";
				}
				$('#v_kode_kondisi_terakhir').html(htmlJenisParameter);
			} else {
				alert(jdata.msg);
			}
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
