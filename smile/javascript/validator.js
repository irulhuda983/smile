/*
Function for checking field inputan berdasarkan Array field ID
    array(p_FieldId,p_FieldName,p_Blank,p_AlphaNumeric,p_Min,p_Max)
example :
    var arrField= [
            ['FieldID1','FieldName 1',true,'AN',9,9],
            ['FieldID2','FieldName 2',true,'N',0,100]
        ];
    checkFieldArray(arrField);
    
function checkFieldArray(p_FieldIdArray)
p_FieldId => ID Field
p_FieldName => Deskripsi/ Nama field
p_Blank => Blank Option 
            true -> boleh kosong
            false -> harus ada isinya
p_AlphaNumeric => Alpha Numeric option    
            'AN'    -> Harus ada Huruf dan Angka
            'A'     -> Hanya Huruf
            'N'     -> Hanya Angka
            'AX'    -> Harus Di awali huruf dan boleh di ikuti Huruf atau angka
            'NX'    -> Harus di awali angka dan boleh di iku ti Huruf atau angka
 p_Min => Panjang Minimal option
            0   => Tidak ada minimal
            N   => Minimal N Huruf/ Angka
p_Max  => Panjang Maksimal option
            0   => Tidak ada Maximal
            N   => Maximal N Huruf/ Angka
           
*/
function checkFieldArray(p_FieldIdArray)
{
    if(p_FieldIdArray.length>0)
    {
        for(var i=0;i<p_FieldIdArray.length;i++)
        {
            var errno = checkField(p_FieldIdArray[i][0],p_FieldIdArray[i][1],p_FieldIdArray[i][2],p_FieldIdArray[i][3],p_FieldIdArray[i][4],p_FieldIdArray[i][5],true)
            if(errno>0) return errno;
        }
    }
    return 0;
}

/*
Function for checking field inputan
Function checkField(p_FieldId,p_FieldName,p_Blank,p_AlphaNumeric,p_Min,p_Max)
p_FieldId => ID Field
p_FieldName => Deskripsi/ Nama field
p_Blank => Blank Option 
            true -> boleh kosong
            false -> harus ada isinya
p_AlphaNumeric => Alpha Numeric option   
            'AN-_'  -> Boleh Huruf atau Angka - _ . 
            'AN'    -> Boleh Huruf atau Angka
            'A'     -> Hanya Huruf
            'N'     -> Hanya Angka
            'AX'    -> Harus Di awali huruf dan boleh di ikuti Huruf atau angka
            'NX'    -> Harus di awali angka dan boleh di iku ti Huruf atau angka
 p_Min => Panjang Minimal option              
            N   => Minimal N Huruf/ Angka
p_Max  => Panjang Maksimal option        
            N   => Maximal N Huruf/ Angka
p_ShowMsg => Alert message option
            true -> alert the error message
            false -> no alert the error message
*/
function checkField(p_FieldId,p_FieldName,p_Blank,p_AlphaNumeric,p_Min,p_Max,p_ShowMsg)
{ 
    /*  ErrNo:
        1 -> p_Blank=False & blank
    */
    var xObj = document.getElementById(p_FieldId);
    var errNo = 0;
    var xStr = Trim(String(xObj.value).toUpperCase());
    //Check if id blank or not
    if(p_Blank && xStr.length==0) errNo = 0;
    else if(!p_Blank && xStr.length<=0) errNo = 1;
    else if(p_AlphaNumeric=='X' && !xStr.match(/^[0-9a-zA-Z-_ \.]*$/)) errNo=2;
    else if(p_AlphaNumeric=='AN' && !xStr.match(/^[0-9a-zA-Z]*$/)) errNo=3;
    else if(p_AlphaNumeric=='A' && !xStr.match(/^[a-zA-Z]*$/)) errNo=4;
    else if(p_AlphaNumeric=='N' && !xStr.match(/^[0-9]*$/)) errNo=5;
    else if(p_AlphaNumeric=='AX' && !xStr.match(/^[a-zA-Z]+[0-9a-zA-Z]*$/)) errNo=6;
    else if(p_AlphaNumeric=='NX' && !xStr.match(/^[0-9]+[0-9a-zA-Z]*$/)) errNo=7;
    else if(p_Min>0 && p_Min==p_Max && xStr.length!=p_Min)  errNo=8;
    else if(p_Min>0 && xStr.length<p_Min)  errNo=9;
    else if(p_Max>0 && xStr.length>p_Max)  errNo=10;
    //alert(errNo);
    if(errNo>0 && p_ShowMsg)
        switch (errNo)
        {
            case 1: alert('Field: ('+p_FieldName+') tidak boleh kosong!');break;
            case 2: alert('Field: ('+p_FieldName+') harus huruf atau angka - _ !');break;
            case 3: alert('Field: ('+p_FieldName+') harus huruf atau angka!');break;
            case 4: alert('Field: ('+p_FieldName+') harus huruf !');break;
            case 5: alert('Field: ('+p_FieldName+') harus angka!');break;
            case 6: alert('Field: ('+p_FieldName+') harus diawali dengan huruf!');break;
            case 7: alert('Field: ('+p_FieldName+') harus diawali dengan angka!');break;
            case 8: alert('Panjang inputan Field: ('+p_FieldName+') harus'+p_Min+'!');break;
            case 9: alert('Panjang Minimum inputan Field: ('+p_FieldName+') adalah:'+p_Min+'!');break;
            case 10: alert('Panjang Maximum inputan Field: ('+p_FieldName+') adalah:'+p_Max+'!');break;
            default: 
                alert("Error pada field: "+p_FieldName);
        }
    return errNo;
}
/*
method removes whitespace from both sides of a string.
function Trim(x)                                      
*/
function Trim(x) {
    return x.replace(/^\s+|\s+$/gm,'');
}

// JavaScript Document

function formValidator () {
	//Using associative Array saving only Not Valid Item
	this.validationList = null;
	
	this.init = function() {
		this.validationList = new Array();
		this.validationText = "";
		this.isvalid=false;
	}
	
	
	this.findItem = function(name) {
		if (this.validationList[name].length > 0) {
			return true;	
		}
		
		return false;
	}

	this.addValidation = function(name, isvalid, message) {
		/* 	Get validator item 
			This is the parameter mean:
				name 	: 	Name of elements
				isvalue	: 	true/false
				message	:	Any message that describing the validation condition
		*/
		
		name = name.toUpperCase();
		
		if (isvalid) {
			//if valid we set the message to valid
			this.validationList[name] = "valid"; 
		} else {
			this.validationList[name] = message; 
		}
	}
	
	this.getValidationText = function() {
		var validationString = "";
		
		for (var i in this.validationList) {
			if (this.validationList[i] != "valid") {
				//If item i is not valid
				if (validationString.length > 0) {
					validationString += "\n";
				}
			
				validationString += "Item : (" + i + ") tidak valid : " + this.validationList[i];
			}
		}
		
		return validationString;
	}
	
	this.init();
}