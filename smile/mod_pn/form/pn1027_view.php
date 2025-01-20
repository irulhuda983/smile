<style>
.gw_selected {background-color:#FFC0FF;}
.gw_cursor_hand tr:hover {cursor:pointer important!;}
#listdata tr:hover {background-color:#e0e0FF;cursor:pointer;}
</style>
    <div id="formframe">
      <div id="formKiri" style="width:1000px"><form>	 
        <fieldset><legend><b>Setup - Kode Jenis Faskes</b></legend>			 									 
          <div class="form-row_kanan">         							       
            <input type="button" name="btncari" class="btn green" id="btncari" value=" Reload Data ">
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
		    <fieldset style="background: #FF0;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
          <li>Klik Reload Data Untuk Refresh</li>	
          <li>Klik Pada baris data untuk memilih data</li>	
          <li>Klik Tombol View/Edit untuk melihat detail/ Merubah data Klik salah satu data pada Tabel hasil Pencarian</li>
          <li>Klik Tombol New untuk menambah data</li>
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
          "url"  : "../ajax/pn1027_query.php",
          "type" : "POST",
          "dataSrc": "data",					
          "data" : function(e) { 
            e.TYPE           = 'query';
            //e.search_pilihan = $('#search_pilihan').val();
            //e.keyword        = $('#keyword').val();            
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
              "data"      : "ROWNUM",
              "width"     : "1%",
              "className" : "dt-body-center dt-head-center",
              "render"    : function(d,t,r){
                  return '<input type="radio" name="kode" value="'+r['KODE_JENIS']+'" onClick="set_dataid(\''+r['KODE_JENIS']+'\')">';
              }
            },
            { "title"     : "Kode Jenis",
              "data"      : "KODE_JENIS",
              "width"     : "10%",
              "className" : "dt-body-center dt-head-center"  },
            { "title"     : "Nama Jenis",
              "data"      : "NAMA_JENIS",
              "className" : "dt-body-center dt-head-center"  }
        ]
      });
}	
function set_dataid(p_id)
{
  dataid=p_id;
}
</script>