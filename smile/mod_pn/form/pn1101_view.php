<style>
</style>
    		  <div style="float:left;">
            Pilih Kategori Setup :
            <select id="idSetup">
                <option value="Akibat"  <?php echo $task_no=='Akibat'?"Selected":"";?>>Akibat Diderita</option>
                <option value="Diagnosa"  <?php echo $task_no=='Diagnosa'?"Selected":"";?>>Diagnosa</option>
                <option value="DiagnosaDetil"  <?php echo $task_no=='DiagnosaDetil'?"Selected":"";?>>Diagnosa Detil</option>
                <option value="Dokumen"  <?php echo $task_no=='Dokumen'?"Selected":"";?>>Dokumen</option>
                <option value="GroupICD" <?php echo $task_no=='GroupICD'?"Selected":"";?>>Group ICD</option>
                <option value="JenisKasus"  <?php echo $task_no=='JenisKasus'?"Selected":"";?>>Jenis Kasus</option>
                <option value="KondisiTerakhir"  <?php echo $task_no=='KondisiTerakhir'?"Selected":"";?>>Kondisi Terakhir</option>
                <option value="LokasiKecelakaan"  <?php echo $task_no=='LokasiKecelakaan'?"Selected":"";?>>Lokasi Kecelakaan</option>
                <option value="SebabKlaim"  <?php echo $task_no=='SebabKlaim'?"Selected":"";?>>Sebab Klaim</option>
                <option value="SebabSegmen"  <?php echo $task_no=='SebabSegmen'?"Selected":"";?>>Sebab Segmen</option>
                <option value="TipeKlaim"  <?php echo $task_no=='TipeKlaim'?"Selected":"";?>>Tipe Klaim</option>
                <option value="TipePenerima"  <?php echo $task_no=='TipePenerima'?"Selected":"";?>>Tipe Penerima</option>
                <option value="Manfaat"  <?php echo $task_no=='Manfaat'?"Selected":"";?>>Manfaat</option>
            </select>
          </div>
          <div style="float:right">
            <?php if($task_no=='Diagnosa' || $task_no=='DiagnosaDetil'|| $task_no=='Dokumen'|| $task_no=='SebabKlaim'|| $task_no=='SebabSegmen'|| $task_no=='Manfaat'){?>
            <input type="text" style="width:250px;" id="txtsearch" name="txtsearch" value="<?=$_GET['s'];?>" />
            <?php } ?>
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
          <li>Klik Tombol View/Edit untuk melihat/ubah detail salah satu data pada Tabel hasil Pencarian yang dipilih</li>
        </fieldset>

<script type="text/javascript">
$(document).ready(function(){
  loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'<?=$_GET['s'];?>');
  $('#idSetup').change(function() {
    window.location ="<?=$php_file_name;?>.php?task=<?=$task_code;?>&taskno="+this.value;
  });
  $("#btncari").click(function(){
    <?php if($task_no=='Diagnosa' || $task_no=='DiagnosaDetil'|| $task_no=='Dokumen'|| $task_no=='SebabKlaim'|| $task_no=='SebabSegmen'|| $task_no=='Manfaat'){?>
    loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,$("#txtsearch").val());
    skey=$("#txtsearch").val();
    <?php }else{?>
    loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'');
    <?php }?>
  });
});
var dataid=0;
var datastatus='PROSES';
var windowid=1;
<?php if($task_no=='TipeKlaim') {?>
var tblcolTipeKlaim = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_TIPE_KLAIM']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode",
            "data"      : "KODE_TIPE_KLAIM" },
            { "title"     : "Nama",
            "data"      : "NAMA_TIPE_KLAIM" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='TipePenerima') {?>
var tblcolTipePenerima = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_TIPE_PENERIMA']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Tipe",
            "data"      : "KODE_TIPE_PENERIMA" },
            { "title"     : "Nama Tipe",
            "data"      : "NAMA_TIPE_PENERIMA" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='GroupICD') {?>
var tblcolGroupICD = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_GROUP_ICD']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode",
            "data"      : "KODE_GROUP_ICD" },
            { "title"     : "Nama",
            "data"      : "NAMA_GROUP_ICD" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='LokasiKecelakaan') {?>
var tblcolLokasiKecelakaan = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_LOKASI_KECELAKAAN']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Lokasi",
            "data"      : "KODE_LOKASI_KECELAKAAN" },
            { "title"     : "Nama Lokasi",
            "data"      : "NAMA_LOKASI_KECELAKAAN" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='KondisiTerakhir') {?>
var tblcolKondisiTerakhir = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_KONDISI_TERAKHIR']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Kondisi",
            "data"      : "KODE_KONDISI_TERAKHIR" },
            { "title"     : "Nama Kondisi",
            "data"      : "NAMA_KONDISI_TERAKHIR" },
            { "title"     : "Tipe Klaim",
            "data"      : "NAMA_TIPE_KLAIM" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='JenisKasus') {?>
var tblcolJenisKasus = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_JENIS_KASUS']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Kasus",
            "data"      : "KODE_JENIS_KASUS" },
            { "title"     : "Nama Kasus",
            "data"      : "NAMA_JENIS_KASUS" },
            { "title"     : "Tipe Klaim",
            "data"      : "NAMA_TIPE_KLAIM" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='Akibat') {?>
var tblcolAkibat = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_AKIBAT_DIDERITA']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Akibat",
            "data"      : "KODE_AKIBAT_DIDERITA" },
            { "title"     : "Nama Akibat",
            "data"      : "NAMA_AKIBAT_DIDERITA" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='Diagnosa') {?>
var tblcolDiagnosa = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_DIAGNOSA']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode",
            "data"      : "KODE_DIAGNOSA" },
            { "title"     : "Nama",
            "data"      : "NAMA_DIAGNOSA" },
            { "title"     : " Group ICD",
            "data"      : "NAMA_GROUP_ICD" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='DiagnosaDetil') {?>
var tblcolDiagnosaDetil = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_DIAGNOSA_DETIL']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode",
            "data"      : "KODE_DIAGNOSA_DETIL" },
            { "title"     : "Nama",
            "data"      : "NAMA_DIAGNOSA_DETIL" },
            { "title"     : "Group ICD",
            "data"      : "NAMA_GROUP_ICD" },
            { "title"     : "Group ICD",
            "data"      : "NAMA_DIAGNOSA" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='Dokumen') {?>
var tblcolDokumen    = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_DOKUMEN']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Dokumen",
            "data"      : "KODE_DOKUMEN" },
            { "title"     : "Nama Dokumen",
            "data"      : "NAMA_DOKUMEN" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='SebabKlaim') {?>
var tblcolSebabKlaim   = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_SEBAB_KLAIM']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Kode Sebab",
            "data"      : "KODE_SEBAB_KLAIM" },
            { "title"     : "Nama Sebab",
            "data"      : "NAMA_SEBAB_KLAIM" },
            { "title"     : "Nama Tipe Klaim",
            "data"      : "NAMA_TIPE_KLAIM" },
            { "title"     : "Flag Meninggal",
            "data"      : "FLAG_MENINGGAL" },
            { "title"     : "Flag Partial",
            "data"      : "FLAG_PARTIAL" },
            { "title"     : "Maks Ambil",
            "data"      : "MAKS" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='SebabSegmen') {?>
var tblcolSebabSegmen   = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_SEBAB_KLAIM']+'_'+r['KODE_SEGMEN']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Nama Sebab",
            "data"      : "NAMA_SEBAB_KLAIM" },
            { "title"     : "Nama Segmen",
            "data"      : "NAMA_SEGMEN" },
            { "title"     : "Keterangan",
            "data"      : "KETERANGAN" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];
<?php }else if($task_no=='Manfaat') {?>
var tblcolManfaat   = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_MANFAAT']+'\');" />';
            }
            },
            { "title"     : "No.",
            "data"      : "NO_URUT",
             },
            { "title"     : "Nama",
            "data"      : "NAMA_MANFAAT" },
            { "title"     : "Kategori",
            "data"      : "KATEGORI_MANFAAT" },
            { "title"     : "Jenis",
            "data"      : "JENIS_MANFAAT" },
            { "title"     : "Tipe",
            "data"      : "TIPE_MANFAAT" },
            { "title"     : "Flag Berkala",
            "data"      : "FLAG_BERKALA" },
            { "title"     : "Keterangan",
            "data"      : "KETERANGAN" },
            {  "title"     : "Status NonAktif",
            "data"      : "STATUS_NONAKTIF" },
        ];

<?php }?>
function set_dataid(p_id)
{
  window.dataid=p_id;
}
function loadData(p_cat,p_columns,p_search1)
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
        		e.SEARCHA = p_search1;
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