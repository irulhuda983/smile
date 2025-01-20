<?php
    //print_r($_SESSION);
?>          <div style="float:right">
            <select id="fsstatus" name="fsstatus"  ></select>
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
                        echo "<option ". ($row["KODE_KANTOR"]===$gs_kantor_aktif ?" selected ":"") . "value=\"{$row["KODE_KANTOR"]}\">{$row["KODE_KANTOR"]} - {$row["NAMA_KANTOR"]}</option>";
            ?>
            </select>
            <select id="type" name="type" >
                <option value="nofaskes">No Faskes</option>
                <option value="kodefaskes">Kode Faskes</option>
                <option value="faskes">Nama Faskes/BLK</option>
                <option value="tipefaskes">Tipe Faskes</option> 
                <option value="alamat">Alamat</option>
            </select>
            <input type="text" style="width:120px;" id="txtsearch" name="txtsearch" value="<?=$_GET['s'];?>" />
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
          </div>
          <div class="clear"></div>
              <table class="table table-striped table-bordered row-border hover responsive-table" id="mydata" cellspacing="0" width="100%">
                <thead>
                </thead>
                <tbody id="listdata">
                </tbody>
              </table>
		    <fieldset style="background: #f2f2f2;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
          <li>Klik Tombol TAMPILKAN DATA untuk refresh data</li>	
          <li>Klik Tombol View/Edit untuk melihat/ubah detail salah satu data pada Tabel hasil Pencarian yang dipilih</li>
          <li>Status Faskes <b>AKTIF</b> dengan tanda (<span style='color:#ff0000;'><b>*</b></span>) menandakan sedang dalam pengajuan  NON AKTIF  Approval ke-1</li>
          <li>Status Faskes <b>AKTIF</b> dengan tanda (<span style='color:#ff0000;'><b>**</b></span>) menandakan sedang dalam pengajuan  NON AKTIF Approval ke-2</li>
        </fieldset>

<script type="text/javascript">
$(document).ready(function(){
    lov_subData('getStatus','getStatus','<?=$_GET['s1'];?>',''); 
    loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,'<?=$_GET['s1'];?>','<?=$_GET['s2'];?>','<?=$_GET['s3'];?>','<?=$_GET['s4'];?>');
    $("#btncari").click(function(){
    loadData('query<?=$task_no;?>',tblcol<?=$task_no;?>,$("#fsstatus").val(),$("#type").val(),$("#txtsearch").val(),$("#kdkantor").val());
    });
});
var dataid=0;
var status='';
var datastatus='PROSES';
var windowid=1;
var tblcolFaskes = [
        	  { "title"     : "",
            "data"      : "NO_URUT",
            "width" : "10",
            "render"    : function(d,t,r){
                return '<input type="radio" name="vehicle" value="Bike" onClick="set_dataid(\''+r['KODE_FASKES']+'\',\''+r['KODE_STATUS']+'\');" />';
            }
            },
            { "title"     : "Action",
            "data"      : "NO_URUT",
            "render"    : function(d,t,r){
                if(r['KODE_STATUS']=='ST3' && r['STATUS_REQUEST']!='N')
                    return '<a href="javascript:nonaktif(\''+r['KODE_FASKES']+'\');" style="display:block;color:#0080FF;">NONAKTIFKAN</a>';
                else
                    return '';
            }
             },
             { "title"     : "Kode Faskes",
             "data"      : "KODE_FASKES" },
             { "title"     : "No Faskes",
             "data"      : "NO_FASKES" },
            { "title"     : "Kode Kantor",
            "data"      : "KODE_KANTOR" },
            { "title"     : "Tipe",
            "data"      : "KODE_TIPE" },
            { "title"     : "Nama Faskes",
            "data"      : "NAMA_FASKES" },
            { "title"     : "Status",
            "data"      : "NAMA_STATUS" ,
            "render"    : function(d,t,r){
                    if( (r['KODE_STATUS']=='ST5' && r['STATUS_REQUEST']=='B') || (r['KODE_STATUS']=='ST3' && r['STATUS_REQUEST']=='N') && r['STATUS_APPROVE_REQUEST']=='Y')
                        return r['NAMA_STATUS']+'<span style="color:#ff0000;">**</span>';
                    if( (r['KODE_STATUS']=='ST5' && r['STATUS_REQUEST']=='B') || (r['KODE_STATUS']=='ST3' && r['STATUS_REQUEST']=='N'))
                        return r['NAMA_STATUS']+'<span style="color:#ff0000;">*</span>';
                    else
                        return r['NAMA_STATUS'];
                }
            },
            { "title"     : "Alamat",
            "data"      : "ALAMAT" },
            { "title"     : "REQUEST",
            "data"      : "NAMA_STATUS" ,
            "render"    : function(d,t,r){
                    if(r['STATUS_REQUEST']=='B')
                        return 'BATAL NONAKTIF';
                    else if(r['STATUS_REQUEST']=='N')
                        return 'NONAKTIF';
                    else
                        return '';
                }
            },    
        ];

function set_dataid(p_id,p_status)
{
  window.dataid=p_id;
  window.status=p_status;
}
function loadData(p_cat,p_columns,p_search1,p_search2,p_search3,p_search4)
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
                e.SEARCHD = p_search4;
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
function setViewSearch(par_data){
        $("#fsstatus").html(par_data);
    }
</script>