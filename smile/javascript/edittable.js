//khulafa-2009

var theTable, theTableBody, celltype, cell_id, row

function init(table_id, arr_celltype, cellid, defaultrow) {
    theTable = document.getElementById(table_id); //
    theTableBody = theTable.tBodies[0];
    celltype= arr_celltype;// new Array('1','2','3','1','5');
    cell_id = cellid ;
    row=defaultrow;  
}

function insertTableRow(where, arr_value, view_only) {
    clearBGColors();
    if (arr_value==undefined) arr_value=new Array('');
    if (view_only==undefined) view_only=false;
    
    var newCell;
    var newRow = theTableBody.insertRow(where);
    row +=1;
    
    for (var i = 0; i < celltype.length; i++) {
        newCell = newRow.insertCell(i);
        newCell.innerHTML = f_celltype(celltype[i], row, i+1, (arr_value[i]==undefined) ? '' : arr_value[i]);
        newCell.style.backgroundColor = "salmon";
        if (view_only){ 
          f_view_only(row, i); 
        }else{
          f_btn(true,row)
        }
    }
    
}

function removeRow(row_data) {
    theTableBody.deleteRow(row_data-1);
}

function f_view_only(row_data, col_data){
  var element = document.getElementById('cell'+row_data+col_data);
  
  if (element !=undefined){
      //alert(element.type);
      if (element.type=="checkbox") element.onclick = function(){return false};
      
      element.readOnly=true;
      element.style.border='none';
      var rows = theTable.getElementsByTagName("tr");  
          if(row_data % 2 == 0){
            rows[row_data].style.backgroundColor = "#ffffff";
            element.style.backgroundColor='#ffffff';
          }else{
            rows[row_data].style.backgroundColor = "#f3f3f3";
            element.style.backgroundColor='#f3f3f3';
          }  
      f_btn(false,row_data)
   }
}

function f_edit(rows){
  for (var i = 1; i < celltype.length; i++) {
      var element=document.getElementById(cell_id+rows+i);
      if (element.className!='disabled'){
        element.style.border='';
        element.readOnly=false;
        element.style.backgroundColor="white";
        if (element.type=="checkbox") element.onclick = function(){return true};
      }
      f_btn(true, rows);
  }
}

function f_btn(edit, rows){
   var elementedit=document.getElementById('btn_edit'+rows);
   var elementsave=document.getElementById('btn_save'+rows);
   if ( elementedit !=undefined && elementsave !=undefined ){
      elementedit.disabled=edit;
      elementsave.disabled=!edit;
   }
}

function f_save(rows){
  for (var i = 0; i < celltype.length; i++) {
      f_view_only(rows, i);
  }
}



function clearBGColors() {
    for (var i = 0; i < theTableBody.rows.length; i++) {
        for (var j = 0; j < theTableBody.rows[i].cells.length; j++) {
            theTableBody.rows[i].cells[j].style.backgroundColor = "";     
        }
    }
}



/*function f_celltype(type, rows, cols, value){
  switch(type){
       case '1': type = "<input id='"+cell_id+rows+cols+"' type='text' onblur='f_ajax_val_kode(this.value, "+rows+");' style='width:50px' value='"+value+"'/>"+
                        "<a href='#' onclick=\"NewWindow('lov_inv_kegiatan.php?idkode="+cell_id+rows+cols+"&idnama="+cell_id+rows+"2','',700,400,1)\"> " +
                        "<img src='../../images/help.png' alt='Cari Kegiatan' border=0 align='absmiddle'> " +
                        "</a><input id='editid"+rows+"' type='hidden' value='"+value+"' />";
                        break;
       case '2': type = "<input id='"+cell_id+rows+cols+"' type='text' style='width:100%' readonly class='disabled' value='"+value+"'/>";break;
       case '3': type = "<input id='"+cell_id+rows+cols+"' type='text' style='width:100%' value='"+value+"' />";break;
       case '4': type = "<input id='btn_edit"+rows+"' type=button onclick=\"f_edit("+rows+");\" value='Edit' />&nbsp"+
                        "<input id='btn_save"+rows+"' type=button onclick=\"f_ajax_simpan(true, "+rows+");\" value='Save' />&nbsp"+
                        "<input id='btn_del"+rows+"' type=button onclick=\"f_ajax_simpan(false, "+rows+");\" value='Delete' />&nbsp";
                        break;
  }
  return type;
}
*/
