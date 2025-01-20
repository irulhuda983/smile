  <div class="clear"></div>
    <table class="table table-striped table-bordered row-border hover responsive-table" id="mydata" style="width: 100%;">
      <tbody id="listdata">
      </tbody>
    </table>
    <fieldset style="background: #FF0;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
      <li>Klik Tombol TAMPILKAN DATA untuk refresh data</li>	
      <li>Klik Tombol View/Edit untuk melihat/ubah detail salah satu data pada Tabel hasil Pencarianyang dipilih</li>
    </fieldset>
  </div>
<script type="text/javascript">
$(document).ready(function(){
  loadData('query', tblcol);
  $('#idSetup').change(function() {
    window.location ="<?=$php_file_name;?>.php?task=<?=$task_code;?>";
  });
  $("#btncari").click(function(){
    loadData('query', tblcol);
  });
});
var dataid=0;
var datastatus='PROSES';
var windowid=1;

var tblcol = [
  {
    "title": "Act",
    "data": "NO_URUT",
    "render": function(d, t, r) {
        return '<input type="radio" name="no_urut" value="no_urut" onClick="set_dataid(\'' + r['KODE_BHP'] + '\');" />';
    },
    "searchable": false,
    "sortable": false
  },
  {
    "title": "No.",
    "data": "NO_URUT",
    "searchable": false,
    "sortable": false
  },
  {
    "title": "Kode",
    "data": "KODE_BHP",
    "searchable": true,
    "sortable": true
  },
  {
    "title": "Nama",
    "data": "NAMA_BHP",
    "searchable": true,
    "sortable": true
  },
  {
    "title": "Nama Pimpinan",
    "data": "NAMA_PIMPINAN",
    "searchable": false,
    "sortable": false
  },
  {
    "title": "Nama Penerima",
    "data": "NAMA_PENERIMA_BHP",
    "searchable": false,
    "sortable": false
  },
  {
    "title": "Bank Penerima",
    "data": "BANK_PENERIMA_BHP",
    "searchable": false,
    "sortable": false
  },
  {
    "title": "Norek Penerima",
    "data": "NO_REKENING_PENERIMA_BHP",
    "searchable": false,
    "sortable": false
  },
  {
    "title": "Nama Rekening Penerima",
    "data": "NAMA_REKENING_PENERIMA_BHP",
    "searchable": false,
    "sortable": false
  }
];

function set_dataid(p_id)
{
  window.dataid=p_id;
}
function loadData(p_cat, p_columns)
{
  preload(true);
	window.mydatatable = $('#mydata').DataTable({
    "scrollCollapse"	: true,
    "paging"			    : true,
    'sPaginationType'	: 'full_numbers',
    scrollY				    : "300px",
    scrollX				    : true,
    "processing"		  : true,
    "serverSide"		  : true,
    "search"			    : {
      "regex" : true
    },
    select			  : true,
    "searching"		: true,
    "destroy"			: true,
    "ajax"				: {
      "url"	  : "../ajax/<?=$php_file_name;?>_query.php",
      "type"  : "POST",
      "data"  : function(e) { 
        e.TYPE = p_cat;
      },complete : function(){
        preload(false);
      },error: function (e){
        console.log(e);
        alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
        preload(false);
      }
    },
    "columns": p_columns,
    initComplete : function() {
      var input = $('.dataTables_filter input').unbind(),
          self = this.api(),
          $searchButton = $('<button>')
                      .text('search')
                      .click(function() {
                        self.search(input.val()).draw();
                      }) 
      $('.dataTables_filter').append($searchButton);
    }
  });

  window.mydatatable.columns.adjust().draw();
}
</script>