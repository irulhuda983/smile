<style>
.gw_selected {background-color:#FFC0FF;}
.gw_cursor_hand tr:hover {cursor:pointer important!;}
#listdata tr:hover {background-color:#e0e0FF;cursor:pointer;}
</style>
    <div id="formframe">
      <div id="formKiri" style="width:1000px"><form>	 
        <fieldset><legend><b>AGENDA JKK RTW</b></legend>			 									 
          <div class="form-row_kanan">
            <span style="margin-right:2px;">Search by:</span>
            <select id="pfilter" name="pfilter" >
              <option value="PUTUS">Submit Putus</option>
              <option value="BATAL">Submit Batal</option>
              <option value="SEMUA">Semua Submit Status</option>               
            </select>
            <select id="search_pilihan" name="search_pilihan" >
              <option value="KODE_RTW_KLAIM">No Agenda</option>
              <option value="NPP">NPP</option>
              <option value="NOMOR_IDENTITAS">NIK</option>
              <option value="NAMA_TK">Nama TK</option>
              <option value="KETERANGAN">Keterangan</option>
            </select>
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
		    <fieldset style="background: #f2f2f2;"><legend style="background: #FF0; border: 1px solid #CCC;color:#000000;">Keterangan:</legend>
          <li>Pilih Jenis Pencarian</li>	
          <li>Klik Tombol TAMPILKAN DATA untuk memulai pencarian data</li>	
          <li>Klik Tombol APPROV untuk Approval dan REJECt untuk penolakan</li>
        </fieldset>
  		</form></div>
  	</div>
<script type="text/javascript">
$(document).ready(function(){
 //lov_subData('getStatus','getStatus','T',0);
  //loadData($('#fsstatus').val(),$('#type').val(),$('#keyword').val());
  loadData();
  //$('#mydata tbody').on('click', 'tr', function () {
        //var a= confirm(this.find());
  //});
  $('#btncari').click(function() {
    loadData();
  });
});
var dataid=0;
function loadData()
{
  preload(true);
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
            e.search_pilihan = $('#search_pilihan').val();
            e.keyword        = $('#keyword').val();  
            e.search_pfilter = $('#pfilter').val();
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
            { "title"     : "Action",
              "data"      : "NO_URUT",
              "width"     : "1%",
              "className" : "dt-body-center dt-head-center",
              "render"    : function(d,t,r){
                  return '<div style="width:100px;"><a href="javascript:approve_rtw(\''+r['KODE_RTW_KLAIM']+'\',\'Approve\')" style="color:#000080;">APPROVE</a> | <a href="javascript:approve_rtw(\''+r['KODE_RTW_KLAIM']+'\',\'Reject\')" style="color:#000080;">REJECT</a></div>';
              }
            },
            { "title"     : "No Agenda",
              "data"      : "KODE_RTW_KLAIM",
              "width"     : "10%",
              "className" : "dt-body-center dt-head-center"  },
            { "title"     : "Kode Klaim",
              "data"      : "KODE_KLAIM",
              "width"     : "10%",
              "className" : "dt-body-center dt-head-center"  },
            { "title"     : "Tanggal",
              "data"      : "TGL_REKAM",
              "width"     : "10%",
              "className" : "dt-body-left dt-head-center"  },
            { "title"     : "Submit Status",
              "data"      : "STATUS",
              "width"     : "10%",
              "className" : "dt-body-center dt-head-center"  },
            { "title"     : "Nama TK",
              "data"      : "NAMA_TK",
              "width"     : "10%",
              "className" : "dt-body-center dt-head-center"  },
            { "title"     : "Nama Perusahaan",
              "data"      : "NAMA_PERUSAHAAN",
              "width"     : "10%",
              "className" : "dt-body-center dt-head-center"  },
            { "title"     : "Tujuan",
              "data"      : "NAMA_KANTOR",
              "width"     : "10%",
              "className" : "dt-body-center dt-head-center"  }
        ]
      });
}	
function approve_rtw(p_id,p_act)
{ 
  if(p_act=="Approve")
  {
    if(!confirm('Approve submit RTW dengan No Agenda : ' + p_id +'?'))
      return(0);
  }
  else if(p_act=="Reject")
  {
    if(!confirm('Reject submit RTW dengan No Agenda : ' + p_id +'?'))
      return(0);
  }else
    return 0;
  preload(true);
  $.ajax({
      type: 'POST',
      url: "../ajax/<?=$php_file_name;?>_action.php?"+Math.random(),
      data: {TYPE:p_act,kode:p_id},
      success: function(ajaxdata){ 
          preload(false); 
          //console.log(ajaxdata);	
          jdata = JSON.parse(ajaxdata);
          if(jdata.ret == '0')
          {						 		 						 						 
              window.parent.Ext.notify.msg('Penyimpanan data berhasil, session dilanjutkan...','');
              loadData();
              
          }else 
          {
              alert(jdata.msg);
          }
      }
  });
}
</script>