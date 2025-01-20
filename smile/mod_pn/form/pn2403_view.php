<style>
</style>
          <div style="float:right">
            <select id="type" name="type" style="width:150px;" >
                <option value="faskes">Nama Faskes/BLK</option>
                <option value="alamat">Alamat</option>              
            </select>
            <input type="text" style="width:250px;" id="txtsearch" name="txtsearch" value="<?=$_GET['s'];?>" />
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
          <li>Klik Tombol Approve untuk melakukan approval</li>
        </fieldset>

<script type="text/javascript">
$(document).ready(function(){
    loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'<?=$_GET['s1'];?>','<?=$_GET['s2'];?>','<?=$_GET['s3'];?>');
    $("#btncari").click(function(){
    loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,$("#type").val(),$("#txtsearch").val(),'');
    });
});
var dataid=0;
var tblcolFaskes = [
            { "title"     : "Action",
            "data"      : "NO_URUT",
            "render"    : function(d,t,r){
                //return '<a href="javascript:approve_batal_nonaktif(\''+r['KODE_FASKES']+'\');" style="display:block;color:#0080FF;">Approve</a>';
                return '<div style="float:left;width:80px;" ><a href="<?=$php_file_name;?>.php?task=View&taskno=Faskes&dataid='+r['KODE_FASKES']+'" ><img src="http://<?=$HTTP_HOST;?>/images/accept.png" align="absmiddle" border="0"> Approval</a></div>';
            }
             },
             { "title"     : "Request",
             "data"      : "STATUS_REQUEST" ,
             "render"    : function(d,t,r){
                if(r['STATUS_REQUEST']=='B')
                    return 'Batal NonAktif';
                else if(r['STATUS_REQUEST']=='N')
                    return 'NonAktif';
                else return '';
                }
             },
             { "title"     : "No Faskes",
             "data"      : "KODE_FASKES" },
            { "title"     : "Kode Kantor",
            "data"      : "KODE_KANTOR" },
            { "title"     : "Tipe",
            "data"      : "KODE_TIPE" },
            { "title"     : "Nama Faskes",
            "data"      : "NAMA_FASKES" },
            { "title"     : "Alamat",
            "data"      : "ALAMAT" }    
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