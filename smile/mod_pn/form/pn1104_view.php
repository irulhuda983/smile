<style>
</style>
    		  <div style="float:left;">
            Pilih Kategori Setup :
            <select id="idSetup">
                <option value="Tipe"  <?php echo $task_no=='Tipe'?"Selected":"";?>>Tipe Faskes</option>
                <option value="Jenis" <?php echo $task_no=='Jenis'?"Selected":"";?>>Jenis Faskes</option>
                <option value="JenisDetil"  <?php echo $task_no=='JenisDetil'?"Selected":"";?>>Jenis Detil Faskes</option>
                <option value="Kepemilikan"  <?php echo $task_no=='Kepemilikan'?"Selected":"";?>>Kepemilikan Faskes</option>
                <option value="Pembayaran"  <?php echo $task_no=='Pembayaran'?"Selected":"";?>>Metode Pembayaran</option>
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
          <li>Klik Tombol View/Edit untuk melihat/ubah detail salah satu data pada Tabel hasil Pencarianyang dipilih</li>
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
<?php if($task_no=='Jenis') {?>
var tblcolJenis = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_JENIS']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode",
            "data"      : "KODE_JENIS" },
            { "title"     : "Nama",
            "data"      : "NAMA_JENIS" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='JenisDetil') {?>
var tblcolJenisDetil = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_JENIS_DETIL']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Detil",
            "data"      : "KODE_JENIS_DETIL" },
            { "title"     : "Nama Detil",
            "data"      : "NAMA_JENIS_DETIL" },
            { "title"     : "Nama Jenis",
            "data"      : "NAMA_JENIS" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='Tipe') {?>
var tblcolTipe = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_TIPE']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Tipe",
            "data"      : "KODE_TIPE" },
            { "title"     : "Nama Tipe",
            "data"      : "NAMA_TIPE" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='Kepemilikan') {?>
var tblcolKepemilikan = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_KEPEMILIKAN']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Kepemilikan",
            "data"      : "KODE_KEPEMILIKAN" },
            { "title"     : "Nama Kepemilikan",
            "data"      : "NAMA_KEPEMILIKAN" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='Pembayaran') {?>
var tblcolPembayaran = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_METODE_PEMBAYARAN']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Kepemilikan",
            "data"      : "KODE_METODE_PEMBAYARAN" },
            { "title"     : "Nama Kepemilikan",
            "data"      : "NAMA_METODE_PEMBAYARAN" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='Bank') {?>
var tblcolBank = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_BANK_PEMBAYARAN']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Kepemilikan",
            "data"      : "KODE_BANK_PEMBAYARAN" },
            { "title"     : "Nama Kepemilikan",
            "data"      : "NAMA_BANK_PEMBAYARAN" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
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