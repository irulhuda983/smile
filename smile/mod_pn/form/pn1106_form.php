<?php /*****LOAD DATA*************************************/
/*** */
$schema="PN";
$dataid= isset($_GET['dataid'])?$_GET['dataid']:'';
$ls_no                         = '0';
$ls_kode_bhp                   = '';
$ls_nama_bhp                   = '';
$ls_alamat_bhp                 = '';
$ls_nama_pimpinan              = '';
$ls_nama_penerima_bhp          = '';
$ls_bank_penerima_bhp          = '';
$ls_no_rekening_penerima_bhp   = '';
$ls_nama_rekening_penerima_bhp = '';
$ls_telepon_area               = '';
$ls_telepon                    = '';
$ls_fax_area                   = '';
$ls_fax                        = '';
$ls_email                      = '';
$ls_nama_kontak                = '';
$ls_telepon_area_kontak        = '';
$ls_telepon_kontak             = '';
$ls_handphone_kontak           = '';

if($task_code=="View" || $task_code=="Edit")
{
  $sql = "
    SELECT  ROWNUM NO_URUT,
        KODE_BHP,
        NAMA_BHP,
        ALAMAT_BHP,
        NAMA_PIMPINAN,
        NAMA_PENERIMA_BHP,
        BANK_PENERIMA_BHP,
        NO_REKENING_PENERIMA_BHP,
        NAMA_REKENING_PENERIMA_BHP,
        TELEPON_AREA,
        TELEPON,
        FAX_AREA,
        FAX,
        EMAIL,
        NAMA_KONTAK,
        TELEPON_AREA_KONTAK,
        TELEPON_KONTAK,
        HANDPHONE_KONTAK,
        TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, 
        PETUGAS_REKAM,
        NVL(TO_CHAR(TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(PETUGAS_UBAH,' ') PETUGAS_UBAH
    FROM  {$schema}.PN_KODE_BHP
    WHERE   KODE_BHP = '{$dataid}'";
  $DB->parse($sql);
  $DB->execute();
  if($row = $DB->nextrow())
  {
    $ls_no                         = $row['NO_URUT'];
    $ls_kode_bhp                   = $row["KODE_BHP"];
    $ls_nama_bhp                   = $row["NAMA_BHP"];
    $ls_alamat_bhp                 = $row["ALAMAT_BHP"];
    $ls_nama_pimpinan              = $row["NAMA_PIMPINAN"];
    $ls_nama_penerima_bhp          = $row["NAMA_PENERIMA_BHP"];
    $ls_bank_penerima_bhp          = $row["BANK_PENERIMA_BHP"];
    $ls_no_rekening_penerima_bhp   = $row["NO_REKENING_PENERIMA_BHP"];
    $ls_nama_rekening_penerima_bhp = $row["NAMA_REKENING_PENERIMA_BHP"];
    $ls_telepon_area               = $row["TELEPON_AREA"];
    $ls_telepon                    = $row["TELEPON"];
    $ls_fax_area                   = $row["FAX_AREA"];
    $ls_fax                        = $row["FAX"];
    $ls_email                      = $row["EMAIL"];
    $ls_nama_kontak                = $row["NAMA_KONTAK"];
    $ls_telepon_area_kontak        = $row["TELEPON_AREA_KONTAK"];
    $ls_telepon_kontak             = $row["TELEPON_KONTAK"];
    $ls_handphone_kontak           = $row["HANDPHONE_KONTAK"];
    $ls_tgl_rekam                  = $row["TGL_REKAM"];
    $ls_petugas_rekam              = $row["PETUGAS_REKAM"];
    $ls_tgl_ubah                   = $row["TGL_UBAH"];
    $ls_petugas_ubah               = $row["PETUGAS_UBAH"];
  }  
}
$i_kode_readonly = ($task_code=='New')?'':'readonly';
$i_kode_color = ($task_code=='New')?'#ffff99;':'#e9e9e9;';
$i_nama_readonly = ($task_code=='New' || $task_code=='Edit')?'':'readonly';
$i_nama_color = ($task_code=='New' || $task_code=='Edit')?'#ffff99;':'#e9e9e9;';
/*****end LOAD DATA*********************************/ ?>
<style>
.f_0 {}

.f_0 form fieldset legend {
  font-size: 100%;
  font-weight: bold;
  color: #157fcc;
  font-family: verdana, arial, tahoma, sans-serif;
}

.f_0 input,
textarea,
select {
  border: 1px solid #dddddd;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.056);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.056);
  padding: 2px;
  font-size: 10px;
  font-family: verdana, arial, tahoma, sans-serif;
}

.f_0 input:disabled,
textarea:disabled,
select:disabled {
  color: #C5C4C4;
  background: #F5F5F5;
  border: 1px solid #dddddd;
  -webkit-box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.556);
  box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.556);
  padding: 2px;
  font-size: 10px;
  font-family: verdana, arial, tahoma, sans-serif;
}

.f_0 input:readonly,
textarea:readonly,
select:readonly {
  color: #3e3724;
  background: #F5F5F5;
  border: 1px solid #dddddd;
  -webkit-box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.556);
  box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.556);
  padding: 2px;
  font-size: 10px;
  font-family: verdana, arial, tahoma, sans-serif;
}

.f_1 {
  width: 160px;
  text-align: right;
  float: left;
  clear: left;
  margin-bottom: 2px;
}

.f_2 {
  width: 310px;
  text-align: left;
  margin-left: 2px;
  float: left;
  margin-bottom: 2px;
}

.f_readonly {
  background-color: #e9e9e9;
}

.f_mandatory {
  background-color: #ffff99;
}

#table_data_bhp tbody{
  display:block;
  overflow:auto;
  height:178px;
  width:100%;
}
#table_data_bhp thead tr{
  display:block;
}
</style>
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">

</script>
<?php /*****end LOCAL JAVASCRIPT*********************************/?>

<form name="formreg" id="formreg" role="form" method="post">
<input type="hidden" id="formregact" name="formregact" value="<?=$task_code;?>" />
<table border='0' width="100%">
  <tr>
    <!-- $i_kode_readonly = ($task_code=='New')?'':'readonly';
    $i_kode_color = ($task_code=='New')?'#ffff99;':'#e9e9e9;';
    $i_nama_readonly = ($task_code=='New' || $task_code=='Edit')?'':'readonly';
    $i_nama_color = ($task_code=='New' || $task_code=='Edit')?'#ffff99;':'#e9e9e9;'; -->
    <td width="50%" valign="top">
    <?php if ($task_code == 'New' || $task_code == 'Edit') { ?>
      <div class="f_1"><label for="kode_bhp">Kode BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="kode_bhp" name="kode_bhp" style="width:200px;background-color:#e9e9e9" value="<?=$ls_kode_bhp;?>" readonly/>
      </div>
      <div class="f_1"><label for="nama_bhp">Nama BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="nama_bhp" name="nama_bhp" style="width:260px;background-color:#ffff99" value="<?=$ls_nama_bhp;?>"/>
      </div>
      <div class="f_1"><label for="alamat_bhp">Alamat* :</label></div>
      <div class="f_2">
        <textarea id="alamat_bhp" name="alamat_bhp" style="width:260px;background-color:#ffff99"><?=$ls_alamat_bhp?></textarea>
      </div>
      <div class="f_1"><label for="nama_pimpinan">Nama Pimpinan* :</label></div>
      <div class="f_2">
        <input type="text" id="nama_pimpinan" name="nama_pimpinan" style="width:200px;background-color:#ffff99" value="<?=$ls_nama_pimpinan;?>"/>
      </div>
      <div class="f_1"><label for="nama_penerima_bhp">Nama Penerima BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="nama_penerima_bhp" name="nama_penerima_bhp" style="width:200px;background-color:#ffff99" value="<?=$ls_nama_penerima_bhp;?>"/>
      </div>
      <div class="f_1"><label for="bank_penerima_bhp">Bank Penerima BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="bank_penerima_bhp" name="bank_penerima_bhp" style="width:200px;background-color:#ffff99" value="<?=$ls_bank_penerima_bhp;?>"/>
      </div>
      <div class="f_1"><label for="no_rekening_penerima_bhp">No Rek Penerima BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="no_rekening_penerima_bhp" name="no_rekening_penerima_bhp" style="width:200px;background-color:#ffff99" value="<?=$ls_no_rekening_penerima_bhp;?>"/>
      </div>
      <div class="f_1"><label for="nama_rekening_penerima_bhp">Nama Rek Penerima BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="nama_rekening_penerima_bhp" name="nama_rekening_penerima_bhp" style="width:200px;background-color:#ffff99" value="<?=$ls_nama_rekening_penerima_bhp;?>"/>
      </div>
      <div class="f_1"><label for="telepon_area">Telepon :</label></div>
      <div class="f_2">
        <input type="text" id="telepon_area" name="telepon_area" style="width:60px;background-color:#ffffff" value="<?=$ls_telepon_area;?>" maxlength="5"/>
        <input type="text" id="telepon" name="telepon" style="width:200px;background-color:#ffffff" value="<?=$ls_telepon;?>"/>
      </div>
      <div class="f_1"><label for="fax_area">Fax :</label></div>
      <div class="f_2">
        <input type="text" id="fax_area" name="fax_area" style="width:60px;background-color:#ffffff" value="<?=$ls_fax_area;?>"  maxlength="5"/>
        <input type="text" id="fax" name="fax" style="width:200px;background-color:#ffffff" value="<?=$ls_fax;?>"/>
      </div>
      <div class="f_1"><label for="email">Email* :</label></div>
      <div class="f_2">
        <input type="text" id="email" name="email" style="width:200px;background-color:#ffff99" value="<?=$ls_email;?>"/>
      </div>
      <div class="f_1"><label for="nama_kontak">Nama Kontak* :</label></div>
      <div class="f_2">
        <input type="text" id="nama_kontak" name="nama_kontak" style="width:200px;background-color:#ffff99" value="<?=$ls_nama_kontak;?>"/>
      </div>
      <div class="f_1"><label for="telepon_area_kontak">Telepon Kontak* :</label></div>
      <div class="f_2">
        <input type="text" id="telepon_area_kontak" name="telepon_area_kontak" style="width:60px;background-color:#ffff99" value="<?=$ls_telepon_area_kontak;?>" maxlength="5"/>
        <input type="text" id="telepon_kontak" name="telepon_kontak" style="width:200px;background-color:#ffff99" value="<?=$ls_telepon_kontak;?>"/>
      </div>
      <div class="f_1"><label for="handphone_kontak">Handphone Kontak* :</label></div>
      <div class="f_2"><input type="text" id="handphone_kontak" name="handphone_kontak" style="width:200px;background-color:#ffff99" value="<?=$ls_handphone_kontak;?>"/></div>
    <?php } else if ($task_code == 'View') { ?>
      <div class="f_1"><label for="kode_bhp">Kode BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="kode_bhp" name="kode_bhp" style="width:200px;background-color:#e9e9e9" value="<?=$ls_kode_bhp;?>" readonly/>
      </div>
      <div class="f_1"><label for="nama_bhp">Nama BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="nama_bhp" name="nama_bhp" style="width:260px;background-color:#e9e9e9" value="<?=$ls_nama_bhp;?>" readonly/>
      </div>
      <div class="f_1"><label for="alamat_bhp">Alamat* :</label></div>
      <div class="f_2">
        <textarea id="alamat_bhp" name="alamat_bhp" style="width:260px;background-color:#e9e9e9"><?=$ls_alamat_bhp?></textarea>
      </div>
      <div class="f_1"><label for="nama_pimpinan">Nama Pimpinan* :</label></div>
      <div class="f_2">
        <input type="text" id="nama_pimpinan" name="nama_pimpinan" style="width:200px;background-color:#e9e9e9" value="<?=$ls_nama_pimpinan;?>" readonly/>
      </div>
      <div class="f_1"><label for="nama_penerima_bhp">Nama Penerima BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="nama_penerima_bhp" name="nama_penerima_bhp" style="width:200px;background-color:#e9e9e9" value="<?=$ls_nama_penerima_bhp;?>" readonly/>
      </div>
      <div class="f_1"><label for="bank_penerima_bhp">Bank Penerima BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="bank_penerima_bhp" name="bank_penerima_bhp" style="width:200px;background-color:#e9e9e9" value="<?=$ls_bank_penerima_bhp;?>" readonly/>
      </div>
      <div class="f_1"><label for="no_rekening_penerima_bhp">No Rek Penerima BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="no_rekening_penerima_bhp" name="no_rekening_penerima_bhp" style="width:200px;background-color:#e9e9e9" value="<?=$ls_no_rekening_penerima_bhp;?>" readonly/>
      </div>
      <div class="f_1"><label for="nama_rekening_penerima_bhp">Nama Rek Penerima BHP* :</label></div>
      <div class="f_2">
        <input type="text" id="nama_rekening_penerima_bhp" name="nama_rekening_penerima_bhp" style="width:200px;background-color:#e9e9e9" value="<?=$ls_nama_rekening_penerima_bhp;?>" readonly/>
      </div>
      <div class="f_1"><label for="telepon_area">Telepon :</label></div>
      <div class="f_2">
        <input type="text" id="telepon_area" name="telepon_area" style="width:60px;background-color:#e9e9e9" value="<?=$ls_telepon_area;?>" maxlength="5" readonly/>
        <input type="text" id="telepon" name="telepon" style="width:200px;background-color:#e9e9e9" value="<?=$ls_telepon;?>" readonly/>
      </div>
      <div class="f_1"><label for="fax_area">Fax :</label></div>
      <div class="f_2">
        <input type="text" id="fax_area" name="fax_area" style="width:60px;background-color:#e9e9e9" value="<?=$ls_fax_area;?>"  maxlength="5" readonly/>
        <input type="text" id="fax" name="fax" style="width:200px;background-color:#e9e9e9" value="<?=$ls_fax;?>" readonly/>
      </div>
      <div class="f_1"><label for="email">Email* :</label></div>
      <div class="f_2">
        <input type="text" id="email" name="email" style="width:200px;background-color:#e9e9e9" value="<?=$ls_email;?>" readonly/>
      </div>
      <div class="f_1"><label for="nama_kontak">Nama Kontak* :</label></div>
      <div class="f_2">
        <input type="text" id="nama_kontak" name="nama_kontak" style="width:200px;background-color:#e9e9e9" value="<?=$ls_nama_kontak;?>" readonly/>
      </div>
      <div class="f_1"><label for="telepon_area_kontak">Telepon Kontak* :</label></div>
      <div class="f_2">
        <input type="text" id="telepon_area_kontak" name="telepon_area_kontak" style="width:60px;background-color:#e9e9e9" value="<?=$ls_telepon_area_kontak;?>" maxlength="5" readonly/>
        <input type="text" id="telepon_kontak" name="telepon_kontak" style="width:200px;background-color:#e9e9e9" value="<?=$ls_telepon_kontak;?>" readonly/>
      </div>
      <div class="f_1"><label for="handphone_kontak">Handphone Kontak* :</label></div>
      <div class="f_2"><input type="text" id="handphone_kontak" name="handphone_kontak" style="width:200px;background-color:#e9e9e9" value="<?=$ls_handphone_kontak;?>" readonly/></div>
      <?php } ?>
    </td>
    <td valign="top">
      <?php if ($task_code == 'Edit') { ?>
      <div class="f_1"><label>Daftar Kantor Aktif *:</label></div>
      <div class="f_2" style="padding-bottom: 12px!important;">
        <table id="table_data_bhp" style="border-collapse: collapse;">
          <thead>
            <tr>
              <td style="padding:0; margin:0;"><input type="text" id="kodeKantor" name="kodeKantor" style="width:60px;background-color:#ffff99;text-transform: uppercase;" value="" placeholder="Kode" onblur="getKantor(this);" maxlength="5"/></td>
              <td style="padding:0; margin:0;"><input type="text" id="namaKantor" name="namaKantor" style="width:205px;background-color:#ffff99;" value="" placeholder="Nama Kantor" readonly /></td>
              <td style="padding-left: 6px; min-width: 80px!important;">
                <a href="#" onclick="addKantor()">
                  <img src="http://<?=$HTTP_HOST;?>/images/app_form_add.png" align="absmiddle" border="0"> Tambah
                </a>
              </td>
            </tr>
          </thead>
          <tbody id="bodyListKantor">
          </tbody>
        </table>
      </div>
      <?php } else if ($task_code == 'View') { ?>
      <div class="f_1"><label>Daftar Kantor Aktif *:</label></div>
      <div class="f_2" style="padding-bottom: 12px!important;">
        <div id="div_container_tbl">
          <table style="border-collapse: collapse;">
            <tbody id="bodyListKantor">
            </tbody>
          </table>
        </div>
      </div>
      <?php } ?>
      <div class="f_1"><label for="tgl_rekam">Tanggal Rekam :</label></div>
      <div class="f_2">
        <input type="text" id="tgl_rekam" name="tgl_rekam" style="width:100px;background-color:#e9e9e9;" value="<?=$ls_tgl_rekam;?>" readonly />
      </div>
      <div class="f_1"><label for="petugas_rekam">Petugas Rekam :</label></div>
      <div class="f_2">
        <input type="text" id="petugas_rekam" name="petugas_rekam" style="width:275px;background-color:#e9e9e9;" value="<?=$ls_petugas_rekam;?>" readonly/>
      </div>
      <div class="f_1"><label for="tgl_ubah">Tanggal Ubah :</label></div>
      <div class="f_2">
        <input type="text" id="tgl_ubah" name="tgl_ubah" style="100px;background-color:#e9e9e9;" value="<?=$ls_tgl_ubah;?>" readonly />
      </div>
      <div class="f_1"><label for="petugas_ubah">Petugas ubah :</label></div>
      <div class="f_2">
        <input type="text" id="petugas_ubah" name="petugas_ubah" style="width:275px;background-color:#e9e9e9;" value="<?=$ls_petugas_ubah;?>" readonly/>
      </div>
    </td>
  </tr>
</table>
</form>
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">
var actionState=0;

function getKantor(e){
  var val = $(e).val();
  if (val != '') {
    preload(true);
    $.ajax({
      type: 'POST',
      url: "../ajax/<?=$php_file_name;?>_query.php?"+Math.random(),
      data: {
        'TYPE': 'getKantor',
        'kode_kantor': val
      },
      success: function(ajaxdata){ 
        preload(false);
        var jdata = JSON.parse(ajaxdata);
        if (jdata.ret == 0) {
          $('#namaKantor').val(jdata.namaKantor);
        } else {
          alert (jdata.msg);
        }
      },
      error:function(){
        alert("Gagal mendaftarkan data kantor!");
        preload(false);
      }
    });
  }
}

function loadListKantorBhp(hasAction){
  var kodeBhp = $('#kode_bhp').val();

  $('#bodyListKantor').html('');
  preload(true);
  $.ajax({
    type: 'POST',
    url: "../ajax/<?=$php_file_name;?>_query.php?"+Math.random(),
    data: {
      'TYPE': 'getListKantorBhp',
      'kode_bhp': kodeBhp
    },
    success: function(ajaxdata){ 
      preload(false);
      var jdata = JSON.parse(ajaxdata);
      if (jdata.ret == 0) {
        for (let i = 0; i < jdata.data.length; i++) {
          var d             = new Date(jdata.data[i].TGL_REKAM);
          var dd            = new Date("2022-07-18");
          var cek_btn_hapus = jdata.data_kantor.includes(jdata.data[i].KODE_KANTOR);
          var html = '';
          html += '';
          html +='<tr>';
          html +='  <td style="padding:0; margin:0;"><input type="text" style="width:60px;background-color:#e9e9e9;" value="' + jdata.data[i].KODE_KANTOR + '" placeholder="Kode" readonly/></td>';
          html +='  <td style="padding:0; margin:0;"><input type="text" style="width:205px;background-color:#e9e9e9;" value="' + jdata.data[i].NAMA_KANTOR + '" placeholder="Nama Kantor" readonly /></td>';
          if (hasAction == true) {
            html +='  <td style="padding-left: 6px; min-width: 80px!important;">';
            html +='    <a href="#" onclick="if(confirm('+"'"+'Hapus data?'+"'"+')) deleteKantor(\'' + jdata.data[i].KODE_KANTOR + '\')">';

            if(jdata.tipe_kantor == 'P' || jdata.tipe_kantor == null){
              html +='      <img src="http://<?=$HTTP_HOST;?>/images/app_form_delete.png" align="absmiddle" border="0"> Hapus';
            } else {
              if(d >= dd && cek_btn_hapus == true){
                html +='      <img src="http://<?=$HTTP_HOST;?>/images/app_form_delete.png" align="absmiddle" border="0"> Hapus';
              }
            }

            html +='    </a>';
            html +='  </td>';
          }
          html +='</tr>';
          $('#bodyListKantor').append(html);
        }
      } else {
        alert (jdata.msg);
      }
    },
    error:function(){
      alert("Gagal mendaftarkan data kantor!");
      preload(false);
    }
  });
}

function addKantor(){
  var kodeBhp = $('#kode_bhp').val();
  var kodeKantor = $('#kodeKantor').val();
  var namaKantor = $('#namaKantor').val();
  if (kodeBhp == '' || kodeKantor == '' || namaKantor == '') {
    return alert('Kode BHP, Kode dan nama kantor tidak boleh kosong!');
  } else {
    preload(true);
    $.ajax({
      type: 'POST',
      url: "../ajax/<?=$php_file_name;?>_action.php?"+Math.random(),
      data: {
        'formregact': 'AddKantor',
        'kode_bhp': kodeBhp,
        'kode_kantor': kodeKantor,
        'nama_kantor': namaKantor
      },
      success: function(ajaxdata){ 
        preload(false);
        var jdata = JSON.parse(ajaxdata);
        if (jdata.ret == 0) {
          loadListKantorBhp(true);
          $('#kodeKantor').val('');
          $('#namaKantor').val();
        } else {
          alert (jdata.msg);
        }
      },
      error:function(){
        alert("Gagal mendaftarkan data kantor!");
        preload(false);
      }
    });
  }
}

function deleteKantor(kodeKantor){
  var kodeBhp = $('#kode_bhp').val();
  if (kodeBhp == '' || kodeKantor == '' || namaKantor == '') {
    return alert('Kode BHP, Kode dan nama kantor tidak boleh kosong!');
  } else {
    preload(true);
    $.ajax({
      type: 'POST',
      url: "../ajax/<?=$php_file_name;?>_action.php?"+Math.random(),
      data: {
        'formregact': 'DeleteKantor',
        'kode_bhp': kodeBhp,
        'kode_kantor': kodeKantor
      },
      success: function(ajaxdata){ 
        preload(false);
        var jdata = JSON.parse(ajaxdata);
        if (jdata.ret == 0) {
          alert('Kode kantor berhasil dihapus');
          loadListKantorBhp(true);
        } else {
          alert (jdata.msg);
        }
      },
      error:function(){
        alert("Gagal mendaftarkan data kantor!");
        preload(false);
      }
    });
  }
}

function saveData()
{
  preload(true);
  $.ajax({
    type: 'POST',
    url: "../ajax/<?=$php_file_name;?>_action.php?"+Math.random(),
    data: $("#formreg").serialize(),
    success: function(ajaxdata){ 
      preload(false);
      var jdata = JSON.parse(ajaxdata);
      if (jdata.ret == 0) {
        window.parent.Ext.notify.msg('Penyimpanan data sukses!','');
        window.location='<?=$php_file_name;?>.php?mid=<?=$mid;?>';
      } else {
        alert (jdata.msg);
      }
    },
    error:function(){
      alert("error saving data!");
      preload(false);
    }
  });
}
function enableInput(obj_jquery,ena)
{
  if(ena) obj_jquery.prop('disabled', false);
  else obj_jquery.prop('disabled', true);
}
function cek_dataNew()
{ 
  if($("#nama_bhp").val()=='')
    return 'Nama BHP mandatory!';
  else if($("#alamat_bhp").val()=='')
    return 'Alamat BHP mandatory!';
  else if($("#nama_pimpinan").val()=='')
    return 'Nama Pimpinan mandatory!';
  else if($("#nama_penerima_bhp").val()=='')
    return 'Nama Penerima mandatory!';
  else if($("#bank_penerima_bhp").val()=='')
    return 'Bank Penerima mandatory!';
  else if($("#no_rekening_penerima_bhp").val()=='')
    return 'No Rek Penerima mandatory!';
  else if($("#nama_rekening_penerima_bhp").val()=='')
    return 'Nama Rek Penerima mandatory!';
  else if($("#email").val()=='')
    return 'Email mandatory!';
  else if($("#nama_kontak").val()=='')
    return 'Nama Kontak mandatory!';
  else if($("#telepon_area_kontak").val()=='')
    return 'Telepon Area Kontak mandatory!';
  else if($("#telepon_kontak").val()=='')
    return 'Telepon Kontak mandatory!';
  else if($("#handphone_kontak").val()=='')
    return 'Handphone Kontak mandatory!';
  else 
    return null;
}
function lov_subData(par_callFunc,par_ajaxFunc,par_ajaxKey1,par_ajaxKey2)
{
  var ajax_Key1 = (par_ajaxKey1 == undefined) ? "":par_ajaxKey1;
  var ajax_Key2 = (par_ajaxKey2 == undefined) ? "":par_ajaxKey2; 
  $.ajax({
    type: 'GET',
    url: "../ajax/<?=$php_file_name;?>_lov.php?"+Math.random(),
    data: {f:par_ajaxFunc,key1:ajax_Key1,key2:ajax_Key2},
    success: function(ajaxdata){
      if(par_callFunc=='getJenis') get_Jenis(ajaxdata);	
    }
  });
}

function get_Jenis(par_data){$("#formreg select[name=kode_parent]").html(par_data);}

$(document).ready(function(){ 
  $("#btn_save").click(function(){
    var valMandatory = cek_dataNew(); 
    if(valMandatory != null)
      alert("Lengkapi isian mandatory field terlebih dulu! " + valMandatory);
    else if(confirm('Save new data?')){
      saveData();
    }
  });

  <?php if($task_code == "Edit") { ?>
    loadListKantorBhp(true);
  <?php } else if($task_code == "View") { ?>
    loadListKantorBhp(false);
  <? } ?>
});
</script>
<?php /*****end LOCAL JAVASCRIPT*********************************/?>