<style>
.gw_selected {background-color:#FFC0FF;}
.gw_cursor_hand tr:hover {cursor:pointer important!;}
#listdata tr:hover {background-color:#e0e0FF;cursor:pointer;}
</style>
    <div id="formframe">
      <div id="formKiri" style="width:1000px"><form id="formreg" name="formreg">	 
      <input type="hidden" id="formregact" name="formregact" value="delegate" />
        <fieldset><legend><b>Perusahaan Pendukung RTW</b></legend>								 									 
          <div class="form-row_kanan">
            <span style="margin-right:2px;">Search by:</span>
            <input  type="text" name="keyword" id="keyword" style="width:150px;" placeholder="Keyword">            							       
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
					
          </div>
          <div class="clear"></div>
          
          <div id="formsplit">
            <div class="clear"></div>
              <table class="table table-striped table-bordered row-border hover responsive-table" id="mydata" cellspacing="0" width="100%">
                <thead>
                </thead>
                <tbody id="listdata">
                </tbody>
              </table>
            <div class="clear"></div>
          </div>																																																					
        </fieldset>
        <br>
		<fieldset style="background: #F2F2F2;"><legend style="background: #FF0; border: 1px solid #CCC;color:#000000;">Keterangan:</legend>
          <li>Klik Tombol TAMPILKAN DATA untuk memulai pencarian data</li>
          <li>Klik Tombol New untuk menambah data</li>	
          <li>Klik Tombol Edit untuk melihat detail dan merubah data</li>
        </fieldset>
  		</form></div>
  	</div>
<script type="text/javascript">
$(document).ready(function(){               
  loadData();
  $('#btncari').click(function() {
    loadData();
  });
});
var dataid=0;
var datastatus='PROSES';
function set_dataid(p_id)
{
  dataid=p_id;
}
function loadData()
{ preload(true);
  window.table = $('#mydata').DataTable({
    "scrollCollapse"  : true,
    "paging"          : true,
    'sPaginationType' : 'full_numbers',
    scrollY           : true,
    scrollX           : true,
    "processing"      : true,
    "serverSide"      : true,
    "search"          : {
          "regex": true
    },
    select            : true,
    "searching"       : false,
    "destroy"         : true,
        "ajax"        : {
          "url"  : "../ajax/<?=$php_file_name;?>_query.php",
          "type" : "POST",
          "dataSrc": "data",					
          "data" : function(e) { 
            e.TYPE           = 'query';
            e.keyword        = $('#keyword').val();            
          },complete : function(data){
            preload(false);
            console.log(data);
          },error: function (data){
              console.log(data);
              alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
              preload(false);
            }
        },
        "columns": [
          { "title"     : "#",
            "data"      : "NO_URUT",
            "width"     : "1%",
            "className" : "dt-body-center dt-head-center",
            "render"    : function(d,t,r){
                return '<input type="checkbox" name="kd[]" value="'+r['KODE_PERUSAHAAN']+'" onClick="set_dataid(\''+r['KODE_PERUSAHAAN']+'\')">';
            }
          },
          { "title"     : "Kode Perusahaan",
            "data"      : "KODE_PERUSAHAAN",
            "width"     : "10%",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Status Aktif",
            "data"      : "STATUS_AKTIF",
            "width"     : "10%",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Nama Perusahaan",
            "data"      : "NAMA_PERUSAHAAN",
            "width"     : "10%"  },
          { "title"     : "Alamat Perusahaan",
            "data"      : "ALAMAT_PERUSAHAAN",
            "width"     : "10%"  },
          { "title"     : "Tanggal Rekam",
            "data"      : "TGL_REKAM",
            "width"     : "10%",
            "className" : "dt-body-left dt-head-center"  },
          { "title"     : "Petugas Rekam",
            "data"      : "PETUGAS_REKAM",
            "width"     : "10%"  },
        ]
      });
}	
function lov_subData(par_callFunc,par_ajaxFunc,par_ajaxKey1,par_ajaxKey2)
{
    var ajax_Key1 = (par_ajaxKey1 == undefined) ? "":par_ajaxKey1;
    var ajax_Key2 = (par_ajaxKey2 == undefined) ? "":par_ajaxKey2;
    $.ajax({
        type: 'GET',
        url: "http://<?=$HTTP_HOST;?>/mod_tc/ajax/tc5001_lov.php?"+Math.random(),
        data: {f:par_ajaxFunc,key1:ajax_Key1,key2:ajax_Key2},
        success: function(ajaxdata){
            if(par_callFunc=='getStatus') getStatus(ajaxdata);								
        }
    });
}
<?php if($_SESSION['regrole']=='1' || $_SESSION['regrole']=='6') {  ?>
function check_checkedid()
{ 
  var ilength=0;
  var checks = document.getElementsByName("kd[]");
  for (var i=0; i < checks.length; i++) {
    if(checks[i].checked == true)
      ilength++;
  }
  return ilength;
}
function delegasikan()
{
  var irow=check_checkedid();
  if($("#kode_dst_kantor").val()=="")
    alert('Silahkan isikan kantor tujuan!');
  else if(irow==0)
    alert('Silahkan untuk memilih agenda RTW yang akan didelegasikan');
  else if(confirm('Yakin mendelegasikan '+irow+' agenda RTW?'))
  {
    preload(true);
    $.ajax({
        type: 'POST',
        url: "../ajax/pn5032_action.php?"+Math.random(),
        data: $("#formreg").serialize(),
        success: function(ajaxdata){ 
            preload(false); 
            console.log(ajaxdata);	
            jdata = JSON.parse(ajaxdata);
            if(jdata.ret == '0')
            {						 		 						 						 
                window.parent.Ext.notify.msg('Pendelegasian RTW berhasil, session dilanjutkan...','');
                loadData();
                $("#kode_dst_kantor").val('');
                $("#nama_dst_kantor").val('');
            }else 
            {
               alert('Pendelegasian RTW gagal, ulangi beberapa saat lagi!');
            }
        }
    });
  }
}
<?php }?>
</script>