<style>
</style>
    		  <div style="float:left;">
            Pilih Kategori Setup :
            <select id="idSetup">
                <option value="Kegiatan"  <?php echo $task_no=='Kegiatan'?"Selected":"";?>>Jenis Kegiatan</option>
                <option value="SubKegiatan"  <?php echo $task_no=='SubKegiatan'?"Selected":"";?>>Sub Kegiatan</option>
            </select>
          </div>
          <div style="float:right">
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
          </div>
          <div class="clear"></div>
              <table class="table table-striped table-bordered row-border hover responsive-table" id="mydata" cellspacing="0" width="100%">
                <thead>
                </thead>
                <tbody id="listdata">
                </tbody>
              </table>
		    <fieldset style="background: #FF0;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
          <li>Klik Tombol TAMPILKAN DATA untuk refresh data</li>	
          <li>Klik Tombol View/Edit untuk melihat/ubah detail  salah satu data pada Tabel hasil Pencarian yang dipilih</li>
        </fieldset>

<script type="text/javascript">
$(document).ready(function(){
  loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>);
  $('#idSetup').change(function() {
    window.location ="<?=$php_file_name;?>.php?task=<?=$task_code;?>&taskno="+this.value;
  });
  $("#btncari").click(function(){
    loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>);
  });
});
var dataid=0;
var datastatus='PROSES';
var windowid=1;
<?php if($task_no=='Kegiatan') {?>
var tblcolKegiatan = [
        	  { "title"     : "",
            "data"      : "NO_URUT1",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_KEGIATAN']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT1",
             },
            { "title"     : "Kode",
            "data"      : "KODE_KEGIATAN" },
            { "title"     : "Nama",
            "data"      : "NAMA_KEGIATAN" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
            {  "title"     : "NO URUT",
            "data"      : "NO_URUT" },
            {  "title"     : "Keterangan",
            "data"      : "KETERANGAN" },
        ];
<?php }else if($task_no=='SubKegiatan') {?>
var tblcolSubKegiatan = [
        	  { "title"     : "",
            "data"      : "KODE_SUB_KEGIATAN",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_SUB_KEGIATAN']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT1",
             },
            { "title"     : "Kode Detil",
            "data"      : "KODE_SUB_KEGIATAN" },
            { "title"     : "Nama Detil",
            "data"      : "NAMA_SUB_KEGIATAN" },
            { "title"     : "Bentuk Kegiatan",
            "data"      : "BENTUK_KEGIATAN" },
            { "title"     : "Regulasi",
            "data"      : "REGULASI" },
            {  "title"     : "Keterangan",
            "data"      : "KETERANGAN" },
        ];
<?php }?>
function set_dataid(p_id)
{
  window.dataid=p_id;
}
function loadData(p_cat,p_columns)
{
  preload(true);
	window.mydatatable = $('#mydata').DataTable({
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
        	"url"	: "../ajax/<?=$php_file_name;?>_query.php",
        	"type": "POST",
        	"data" : function(e) { 
        		e.TYPE = p_cat;
        		//e.pilihan = window.f_pilihan;
        	//	e.keyword = window.f_keyword;
        		
        	},complete : function(){

        		preload(false);
        	},error: function (){
            	alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
            	preload(false);
            }
        },
        "columns": p_columns
    });
	// window.table_jenis_kasus.columns.adjust().draw();
	/*window.table_jenis_kasus.on( 'draw.dt', function () {
		$('button[name*="edit_jenis_kasus"]').click(function() {
			window.kode_jenis_kasus = $(this).attr('kode_jenis_kasus');
			getDataRowJenisKasus('edit',window.kode_jenis_kasus);
		});
		$('button[name*="view_jenis_kasus"]').click(function() {
			window.kode_jenis_kasus = $(this).attr('kode_jenis_kasus');
			getDataRowJenisKasus('view',window.kode_jenis_kasus);
		});
		$('button[name*="hapus_jenis_kasus"]').click(function() {
			window.kode_jenis_kasus = $(this).attr('kode_jenis_kasus');
			deleteDataJenisKasus(window.kode_jenis_kasus);
		});
	});*/
}
</script>