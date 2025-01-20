function preload(com){
	if(com == true){
		$("#loading-mask").show();
		$("#loading").show();
		$('#dispError1').hide();
	} else {
		$("#loading-mask").hide();
		$("#loading").hide();
	}
}
function NewWindow(mypage,myname,w,h,scroll){
  var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
  var settings  ='height='+h+',';
      settings +='width='+w+',';
      settings +='top='+wint+',';
      settings +='left='+winl+',';
      settings +='scrollbars='+scroll+',';
      settings +='resizable=yes';
  win=window.open(mypage,myname,settings);
  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}

function convert_date(field1) {
	var fLength = field1.value.length; // Length of supplied field in characters.
	var divider_values = new Array ('-','.','/',' ',':','_',','); // Array to hold permitted date seperators.  Add in '\' value
	var array_elements = 7; // Number of elements in the array - divider_values.
	var day1 = new String(null); // day value holder
	var month1 = new String(null); // month value holder
	var year1 = new String(null); // year value holder
	var divider1 = null; // divider holder
	var outdate1 = null; // formatted date to send back to calling field holder
	var counter1 = 0; // counter for divider looping 
	var divider_holder = new Array ('0','0','0'); // array to hold positions of dividers in dates
	var s=String(field1.value); // supplied date value variable

  //If field is empty do nothing
  if (fLength==0 ) {
  	return true;
  }

  // Deal with today or now
  if (field1.value.toUpperCase()=='NOW' || field1.value.toUpperCase()=='TODAY') {
  	var newDate1=new Date();
    	if (navigator.appName == "Netscape") {
      	var myYear1 = newDate1.getYear() + 1900;
    	}	else {
    		var myYear1 =newDate1.getYear();
    	}
  	var myMonth1 = newDate1.getMonth()+1;  
  	var myDay1 = newDate1.getDate();
  	field1.value = myDay1 + "/" + myMonth1 + "/" + myYear1;
  	fLength = field1.value.length;//re-evaluate string length.
  	s = String(field1.value)//re-evaluate the string value.
  }
  
  //Check the date is the required length
  if (fLength!=0 && (fLength<6 || fLength>11)) {
  	invalid_date(field1);
  	return false;   
  }

  // Find position and type of divider in the date
  for (var i=0; i<3; i++) {
  	for (var x=0; x<array_elements; x++) {
  		if (s.indexOf(divider_values[x], counter1)!=-1) {
  			divider1=divider_values[x];
  			divider_holder[i]=s.indexOf(divider_values[x], counter1);
  			//alert(i + " divider1 = " + divider_holder[i]);
  			counter1 = divider_holder[i] + 1;
  			//alert(i + " counter1 = " + counter1);
  			break;
  		}
  	}
  }
  
  // if element 2 is not 0 then more than 2 dividers have been found so date is invalid.
  if (divider_holder[2]!=0) {
  	invalid_date(field1);
  	return false;   
  }
  
  // See if no dividers are present in the date string.
  if (divider_holder[0]==0 && divider_holder[1]==0) { 
     
  	//continue processing
  	if (fLength==6) {//ddmmyy
  		day1=field1.value.substring(0,2);
  		month1 = field1.value.substring(2,4);
  		year1 = field1.value.substring(4,6);
  		if ((year1=validate_year(year1))==false) {
  			invalid_date(field1);
  			return false; 
  		}
  	}	else if (fLength==7) { //ddmmmy
  		day1=field1.value.substring(0,2);
  		month1 = field1.value.substring(2,5);
  		year1 = field1.value.substring(5,7);
    	if ((month1 = convert_month(month1))==false) {
  			invalid_date(field1);
  			return false;
  		}
  		if ((year1=validate_year(year1))==false) {
  			invalid_date(field1);
  			return false; 
  		}
  	}	else if (fLength==8) { //ddmmyyyy
  		day1=field1.value.substring(0,2);
  		month1 = field1.value.substring(2,4);
  		year1 = field1.value.substring(4,8);
  	}	else if (fLength==9) { //ddmmmyyyy
  		day1 = field1.value.substring(0,2);
  		month1 = field1.value.substring(2,5);
  		year1 = field1.value.substring(5,9);
  		if ((month1=convert_month(month1))==false) {
  			invalid_date(field1);
  			return false; 
  		}
  	}
  	if ((outdate1=validate_date(day1,month1,year1))==false) {
  		alert("Nilai yang anda masukkan, " + field1.value + " bukan tanggal yang benar.\n\r" +  
  		"Masukkan tanggal dengan  format dd/mm/yyyy");
  		field1.focus();
  		field1.select();			
  		return false;
  	}
  	field1.value = outdate1;
  	return true; // All OK
  }
  		
  // 2 dividers are present so continue to process	
  if (divider_holder[0]!=0 && divider_holder[1]!=0) { 	
  	day1 = field1.value.substring(0, divider_holder[0]);
  	month1 = field1.value.substring(divider_holder[0] + 1, divider_holder[1]);
  	//alert(month1);
  	year1 = field1.value.substring(divider_holder[1] + 1, field1.value.length);
  }
  if (isNaN(day1) && isNaN(year1)) { // Check day and year are numeric
  	invalid_date(field1);
  	return false;  
     }
  
  if ( day1.length == 1 ) { //Make d day dd
     day1 = '0' + day1;  
  }
  
  if ( month1.length == 1 ) {//Make m month mm
  	month1 = '0' + month1;   
  }
  
  if ( year1.length == 2 ) {//Make yy year yyyy
     if ( (year1 = validate_year(year1)) == false ) {
     	invalid_date(field1);
  		return false;  
  		}
  }
  
  if ( month1.length == 3 || month1.length == 4 ) {//Make mmm month mm
     if ( (month1 = convert_month(month1)) == false) {
     	alert("month1" + month1);
     	invalid_date(field1);
     	return false;  
     }
  }
  
  // Date components are OK
  if ( (day1.length == 2 || month1.length == 2 || year1.length == 4) == false) {
     invalid_date(field1);
     return false;
  }
  
  //Validate the date
  if ( (outdate1 = validate_date(day1, month1, year1)) == false ) {
		 alert("Nilai  " + field1.value + " tidak valid.\n\r" +  
  	"Masukkan tanggal yang benar dd/mm/yyyy");
  	//field1.focus(); 
  	//field1.select();
		return false;		
  }
  
  // Redisplay the date in dd/mm/yyyy format
  field1.value = outdate1;
  return true;//All is well

}


/******************************************************************
   convert_month()
   
   Function to convert mmm month to mm month 
   
   Called by convert_date()    
   
   Author: Simon Kneafsey 
   Date Created: 4/9/00
   Email: simonkneafsey@hotmail.com
   WebSite: www.simonkneafsey.co.uk
   
   Notes:P lease feel free to use/edit this script.  If you do please keep my comments and details 
   intact and notify me via a quick Email to the address above.  Enjoy!
*******************************************************************/
function convert_month(monthIn) {
var month_values = new Array ("JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC");
monthIn = monthIn.toUpperCase(); 
if (monthIn.length==3) {
	for (var i=0; i<12; i++) {
		if (monthIn==month_values[i])	{
			monthIn=i+1;
			if (i!=10 && i!=11 && i!=12) {
				monthIn='0'+monthIn;
			}
			return monthIn;
		}
	}
} else if ( monthIn.length == 4 && monthIn == 'SEPT') {
	monthIn = '09';
	return monthIn;
} else {
	return false;
} 
}
/******************************************************************
   invalid_date()
   
   If an entered date is deemed to be invalid, invali
   d_date() is called to display a warning message to
   the user.  Also returns focus to the date  in que
   stion and selects the date for edit.
        
   Called by convert_date()
   
   Author: Simon Kneafsey
   Date Created: 4/9/00
   Email: simonkneafsey@hotmail.com
   WebSite: www.simonkneafsey.co.uk
   
   Notes: Please feel free to use/edit this script.  If you do please keep my comments and details 
   intact and notify me via a quick Email to the address above.  Enjoy!
*******************************************************************/
function invalid_date(inField) {
alert("Nilai yang anda masukkan " + inField.value + " tidak tepat.\n\r" + 
        "Masukkan tanggal dengan  format dd/mm/yyyy");
//inField.focus();
//inField.select();
return true   
}
/******************************************************************
   validate_date()
   
   Validates date output from convert_date().  Checks
   day is valid for month, leap years, month !> 12,.
   
   Author: Simon Kneafsey
   Date Created: 4/9/00
   Email: simonkneafsey@hotmail.com
   WebSite: www.simonkneafsey.co.uk
   
   Notes: Please feel free to use/edit this script.  If you do please keep my comments and details 
   intact and notify me via a quick Email to the address above.  Enjoy!
*******************************************************************/
function validate_date(day2, month2, year2) {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
var DayArray = new Array(31,28,31,30,31,30,31,31,30,31,30,31);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
var MonthArray = new Array("01","02","03","04","05","06","07","08","09","10","11","12");                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
var inpDate = day2 + month2 + year2;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
var filter=/^[0-9]{2}[0-9]{2}[0-9]{4}$/;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          

//Check ddmmyyyy date supplied
if (! filter.test(inpDate)) {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
  return false;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
  }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
/* Check Valid Month */                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
filter=/01|02|03|04|05|06|07|08|09|10|11|12/ ;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
if (! filter.test(month2)) {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
  return false;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
  }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
/* Check For Leap Year */                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
var N = Number(year2);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
if ( ( N%4==0 && N%100 !=0 ) || ( N%400==0 ) ) {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
   DayArray[1]=29;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
  	}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
/* Check for valid days for month */                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
for(var ctr=0; ctr<=11; ctr++) {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
   if (MonthArray[ctr]==month2)	{                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
      if (day2<= DayArray[ctr] && day2 >0 ) {
        inpDate = day2 + '/' + month2 + '/' + year2;       
        return inpDate;
        } else {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
        return false;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
        }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
   	}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
   }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
}
/******************************************************************
   validate_year()
   
   converts yy years to yyyy
   Uses a hinge date of 10
        < 10 = 20yy 
        => 10 = 19yy.
         
   Called by convert_date() before validate_date().
      
   Author: Simon Kneafsey 
   Date Created: 4/9/00
   Email: simonkneafsey@hotmail.com
   WebSite: www.simonkneafsey.co.uk
   
   Notes: Please feel free to use/edit this script.  If you do please keep my comments and details 
   intact and notify me via a quick Email to the address above.  Enjoy!
*******************************************************************/
function validate_year(inYear) {
	if (inYear<50) {
		inYear="20"+inYear;
		return inYear;
	} else if (inYear>=50) {
		inYear="19"+inYear;
		return inYear;
	} else	{
		return false;
	}   
}

/**********************************
	replace all matches with empty strings
***********************************/
function removeCommas( strValue ) 
{
	var objRegExp = /,/g; //search for commas globally	
	return strValue.replace(objRegExp,'');
}
/**********************************
	format nilai uang    1,000,000.00
***********************************/
function format_uang(num, centDigit) 
{
    num = num.toString().replace(/\$|\,/g,'');
	if(centDigit == undefined){
		centDigit = parseInt(100);
	}else
	if(parseInt(centDigit) == 3){
		centDigit = parseInt(1000);
	}else
	if(parseInt(centDigit) == 4){
		centDigit = parseInt(10000);
	}
	
    if(isNaN(num))
    			num = "0";
    			sign = (num == (num = Math.abs(num)));
    			//num = Math.floor(num*100+0.50000000001);
				num = Math.floor(num*centDigit+0.50000000001);
					cents = num%centDigit;
    			//num = Math.floor(num/100).toString();
				num = Math.floor(num/centDigit).toString();
    			if(cents<10)
    					cents = "0" + cents;
    					for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    					num = num.substring(0,num.length-(4*i+3))+','+
    					num.substring(num.length-(4*i+3));
    return (((sign)?'':'-') + '' + num + '.' + cents);
}


/**********************************
	format nilai numeric tanpa decimal    1,000,000
***********************************/
function format_nondesimal(num) 
{
    num = num.toString().replace(/\$|\,/g,'');
    if(isNaN(num))
    			num = "0";
    			sign = (num == (num = Math.abs(num)));
    			num = Math.floor(num*100+0.50000000001);
    			cents = num%100;
    			num = Math.floor(num/100).toString();
					if(cents<10)
    					cents = "0" + cents;
    					for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    					num = num.substring(0,num.length-(4*i+3))+','+
    					num.substring(num.length-(4*i+3));
							
    return (((sign)?'':'-') + '' + num);
}


/********************************************
http://javascript.geniusbug.com/index.php?action=show&name=textLimit

fungsi untuk membatasi length input di Textarea
contoh :
  <textarea onkeypress="return canAddCharacter(this, 100)" onchange="trimLength(this, 100)"></textarea>
*********************************************/

function trimLength(textarea, maxChars)
  {
    if(textarea.value.length <= maxChars) return;
    textarea.value = textarea.value.substr(0, maxChars)
  } 
  
 function canAddCharacter(textarea, maxChars)
 {
    if(typeof(textarea.onkeypress.arguments[0]) != 'undefined')
      var keyCode = textarea.onkeypress.arguments[0].keyCode;
    else
    {
      if(document.selection.createRange().text.length != 0) return true;
      var keyCode = event.keyCode;
    }

    var allowedChars = new Array(8, 37, 38, 39, 40, 46);	//Backspace, delete and arrow keys
    for(var x=0; x<allowedChars.length; x++) if(allowedChars[x] == keyCode) return true;

    if(textarea.value.length < maxChars) return true;

    return false;
  }
/*------------------------------------------------------------------------------*/

/*untuk menghitung selisih hari dari 2 tanggal*/
function selisih_hari(ls_dari, ls_sampai){
	//if(ls_dari!=undefined && is_DMYformat(ls_dari) && ls_sampai!=undefined && is_DMYformat(ls_sampai)) {
		var one_day=1000*60*60*24;
		arr_dari	= ls_dari.split('/');
		arr_sampai	= ls_sampai.split('/');
		
		dt_dari		= new Date(arr_dari[2], arr_dari[1], arr_dari[0]);
		dt_sampai	= new Date(arr_sampai[2], arr_sampai[1], arr_sampai[0]);
		
		ln_selisih	= (dt_sampai.getTime()/parseInt(one_day)) - (dt_dari.getTime()/parseInt(one_day));
		return Math.ceil(ln_selisih);
	//}
}

function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function nvl(v_value, v_default){
    return (v_value==undefined || v_value=='') ? v_default : v_value;
}

function convert_tgl_jam(field1) {
	var fval		= field1;
	if(fval.indexOf('/') !== -1){
		tlg = fval.split('/'); 
		return field1.value = tlg[0]+'-'+tlg[1]+'-'+tlg[2]+' 00:00:00';
	} else {
		return null;	
	}
}