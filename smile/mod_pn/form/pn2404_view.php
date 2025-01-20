<style>
</style>
          <div style="float:right">
            <select id="kdkantor" name="kdkantor" style="width:200px;">
                <option value="">--Pilih Kantor--</option>
            <?php
            $sql = "select kode_kantor, nama_kantor from sijstk.ms_kantor ".
                    "where kode_tipe not in ('0','1') ".                                                                           
                    "start with kode_kantor = '{$gs_kantor_aktif}' ".
                    "connect by prior kode_kantor = kode_kantor_induk";
                    $DB->parse($sql);
                    $DB->execute();
                    $kode_kantor="";
                    while($row = $DB->nextrow())
                        echo "<option ". ($row["KODE_KANTOR"]==$gs_kantor_aktif ?" selected ":"") . "value=\"{$row["KODE_KANTOR"]}\">{$row["KODE_KANTOR"]} - {$row["NAMA_KANTOR"]}</option>";
            ?>
            </select>
            <input type="hidden" style="width:150px;" id="txtsearch" name="txtsearch" value="<?=$_GET['s'];?>" />
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
        </fieldset>

<script type="text/javascript">
$(document).ready(function(){
    loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'<?=$_GET['s1'];?>','<?=$_GET['s2'];?>');
    $("#btncari").click(function(){
    loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,$("#kdkantor").val(),$("#txtsearch").val(),'');
    });
});
var dataid=0;
var tblcolIKS = [
            { "title"     : "Kode Kantor",
            "data"      : "KODE_KANTOR" },
            { "title"     : "No Faskes",
             "data"      : "KODE_FASKES" },             
            { "title"     : "Nama Faskes",
            "data"      : "NAMA_FASKES" },
            { "title"     : "No IKS",
             "data"      : "NO_IKS" },
             { "title"     : "Tgl Awal",
             "data": "TGL_AWAL_IKS" },
             { "title"     : "Tgl Akhir",
             "data": "TGL_AKHIR_IKS" },
             { "title"     : "Tgl Rekam",
             "data": "TGL_REKAM" },
             { "title"     : "Petugas Rekam",
             "data": "PETUGAS_REKAM" }
        ];

function loadData(p_cat,p_columns,p_search1,p_search2,p_search3)
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
                e.SEARCHB = p_search2;
                e.SEARCHC = p_search3;
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