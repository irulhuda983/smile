<?php
    //print_r($_SESSION);
?>          <div style="float:right">                           
            <select id="kdkantor" name="kdkantor" style="width:200px;">
                <option value="0">Seluruh Wilayah Nasional</option>
                <optgroup label="Konsolidasi Wilayah">
            <?php
            $sql = "select kode_kantor, nama_kantor,lpad(kode_kantor,5,'0') a
                    from sijstk.ms_kantor 
                    where kode_tipe in ('1') 
                    order by a";
                    $DB->parse($sql);
                    $DB->execute();
                    $kode_kantor="";
                    while($row = $DB->nextrow())
                        echo "<option ". ($row["KODE_KANTOR"]==$gs_kantor_aktif ?" selected ":"") . "value=\"{$row["KODE_KANTOR"]}\">{$row["KODE_KANTOR"]} - {$row["NAMA_KANTOR"]}</option>";
            ?>
                </optgroup>
                <optgroup label="Kantor Cabang">
            <?php
            $sql = "select kode_kantor, nama_kantor,lpad(kode_kantor,5,'0') a
                    from sijstk.ms_kantor 
                    where kode_tipe in ('3','4','5') 
                    order by a";
                    $DB->parse($sql);
                    $DB->execute();
                    $kode_kantor="";
                    while($row = $DB->nextrow())
                        echo "<option ". ($row["KODE_KANTOR"]==$gs_kantor_aktif ?" selected ":"") . "value=\"{$row["KODE_KANTOR"]}\">{$row["KODE_KANTOR"]} - {$row["NAMA_KANTOR"]}</option>";
            ?>
                </optgroup>
            </select>
            <select id="type" name="type" >
                <option value="nofaskes">NOMOR FASKES</option>
                <option value="faskes">NAMA FASKES/BLK</option> 
                <option value="alamat">ALAMAT</option>
                <option value="kodefaskes">KODE FASKES</option>
            </select>
            <input type="text" style="width:120px;" id="txtsearch" name="txtsearch" value="<?=$_GET['s'];?>" alt="Searching..." />
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
          </div>
          <div>
            <input type="button" name="btnprint" class="btn green" id="btnprint" value=" Print Preview by Kantor">
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
          <li>Klik pada Kode Faskes untuk melihat info detil faskes/blk</li>
        </fieldset>                                                                                 
<script type="text/javascript">
$(document).ready(function(){
    loadData('queryFaskes','<?php echo substr('000'.$gs_kantor_aktif,-3); ?>','','');
    $("#btncari").click(function(){ 
    loadData('queryFaskes',$("#kdkantor").val(),$("#type").val(),$("#txtsearch").val());
    });
    $("#btnprint").click(function(){
      loadPrintDocument("pn2405_preview.php?kd_kantor="+$("#kdkantor").val());
    });
});
var tblcolFaskes = [
              { 
                  "title"     : "KODE KANTOR",
                  "data"      : "KODE_KANTOR" 
              },
              { 
                  "title"     : "NAMA KANTOR",
                  "data"      : "NAMA_KANTOR" 
              },
        	  { 
                  "title"     : "NAMA FASKES",
                  "data"      : "KODE_FASKES",
                  "render"    : function(d,t,r){
                    return '<a href="javascript:gw_newWindow(\'mod_pn/form/pn2405_form.php?kd='+r['KODE_FASKES']+'\',\'Informasi Faskes/BLK\',1000,600);" style="display:block;color:#0080FF;">'+r['NAMA_FASKES']+'</a>';
                  }
              },
              { 
                  "title"     : "KODE FASKES",
                  "data"      : "KODE_FASKES" 
              },
              { 
                  "title"     : "NO FASKES",
                  "data"      : "NO_FASKES" 
              }  
        ];

function loadData(p_cat,p_search1,p_search2,p_search3)
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
                //e.SEARCHD = p_search4;
        	//	e.keyword = window.f_keyword;
        		
        	},complete : function(){

        		preload(false);
        	},error: function (){
            	alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
            	preload(false);
            }
        },
        "columns": tblcolFaskes
    });  
    
}
</script>