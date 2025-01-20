function isDecimal(value) {
  return /^-?(?:0|0\.\d*|[1-9]\d*\.?\d*)$/.test(value)
}

function unmask(value, ds = '.') {
  if (!value) return value
  let val = value.toString()
  if(val[val.length-1]==='.') return value
  val=val.replaceAll('.', ds)
  return isNaN(val) === 'NaN' ? value : Number(val)
}

function mask(value, dp = -1, editing = false, ds = ',', gs = '.') {
  if (!value) return value
  if (editing) {
    return value
  }

  const parts = value.toString().split(/\./)

  let dec = parts[1] || ''

  if (dp >= 0) {
    dec = dec.length < dp ? dec.padEnd(dp, '0') : dec.substr(0, dp)
  }

  if (dec) {
    dec = ds + dec
  }
  return !value ? null : parts[0].replace(/(\d)(?=(?:\d{3})+$)/g, '$1' + gs) + dec
}

// function formatDecimal(value, dec = 0) {
//   value = !value ? '0' : value.toString()
//   return mask(value, dec)
// }

function formatDecimal(value, dec = 0) {
  try{
    let n =Number(value).toFixed(dec)
    
    n = !n ? '0' : n.toString()
    return mask(n, dec)
  }catch(e){
    return value
  }
}

const lowercaseKeys = (obj) =>
  Object.keys(obj).reduce((acc, key) => {
    acc[key.toLowerCase()] = obj[key]
    return acc
  }, {})
  
const arrNamaBulan =['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November']

function getNamaHari(val){
  try{
    const [bln,tgl,thn] = val.split('/')
    return Number(tgl)+' '+arrNamaBulan[Number(bln)-1]+' '+Number(thn)
  }catch(e){
    return ''
  }
}

function getNamaBulanFromTanggal(val){
  try{
    const [bln,tgl,thn] = val.split('/')
    return arrNamaBulan[Number(bln)-1]+' '+Number(thn)
  }catch(e){
    return ''
  }
}

function getNamaBulan(val){
  try{
    const thn = val.slice(0,4)
    const bln = val.slice(-2)
    return arrNamaBulan[Number(bln)-1]+' '+Number(thn)
  }catch(e){
    return ''
  }
}
function terbilang(angka){

  var bilne=["","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas"];

  if(angka < 12){

    return bilne[angka];

  }else if(angka < 20){

    return terbilang(angka-10)+" belas";

  }else if(angka < 100){

    return terbilang(Math.floor(parseInt(angka)/10))+" puluh "+terbilang(parseInt(angka)%10);

  }else if(angka < 200){

    return "seratus "+terbilang(parseInt(angka)-100);

  }else if(angka < 1000){

    return terbilang(Math.floor(parseInt(angka)/100))+" ratus "+terbilang(parseInt(angka)%100);

  }else if(angka < 2000){

    return "seribu "+terbilang(parseInt(angka)-1000);

  }else if(angka < 1000000){

    return terbilang(Math.floor(parseInt(angka)/1000))+" ribu "+terbilang(parseInt(angka)%1000);

  }else if(angka < 1000000000){

    return terbilang(Math.floor(parseInt(angka)/1000000))+" juta "+terbilang(parseInt(angka)%1000000);

  }else if(angka < 1000000000000){

    return terbilang(Math.floor(parseInt(angka)/1000000000))+" milyar "+terbilang(parseInt(angka)%1000000000);

  }else if(angka < 1000000000000000){

    return terbilang(Math.floor(parseInt(angka)/1000000000000))+" trilyun "+terbilang(parseInt(angka)%1000000000000);

  }

}


function readCsvToArray(e, headers,delimiter=';') {
  return new Promise((resolve) => {
    var fileUpload = e.target
    //Validate whether File is valid Excel file.
    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv)$/
    if (regex.test(fileUpload.value.toLowerCase())) {
      const readSheetToArray = this.getArrayOfObject
      if (typeof FileReader != 'undefined') {
        var reader = new FileReader()
        //For Browsers other than IE.
        if (reader.readAsBinaryString) {
          reader.onload = function (e) {
            resolve({ ret: 0, data: csvStringToArray(e.target.result, headers,delimiter) })
          }
          reader.readAsBinaryString(fileUpload.files[0])
        } else {
          //For IE Browser.
          reader.onload = function (e) {
            var data = ''
            var bytes = new Uint8Array(e.target.result)
            for (var i = 0; i < bytes.byteLength; i++) {
              data += String.fromCharCode(bytes[i])
            }
            resolve({ ret: 0, data: readSheetToArray(data, headers,delimiter) })
          }
          reader.readAsArrayBuffer(fileUpload.files[0])
        }
      } else {
        alert('This browser does not support HTML5.')
        resolve({ ret: -1, msg: 'This browser does not support HTML5.' })
      }
    } else {
      alert('Please upload a valid csv file.')
      resolve({ ret: -1, msg: 'Please upload a valid csv file.' })
    }
  })
}

function csvStringToArray(str,headers,delimiter=';'){
  return str.split("\n").map(x=>{
    return x.split(delimiter).reduce((acc,y,i)=>{
      const obj={}
      obj[headers[i]]=y.replace(/\r?\n|\r/g, "")
      return ({...acc,...obj})
    },{})
  })
}
// class serviceExcelReader {
//   isRead = false
//   data = []

//   readExcelToJson(e, oXLSX) {
//     return new Promise((resolve) => {
//       this.isRead = true
//       //Reference the FileUpload element.
//       var fileUpload = e.target
//       //Validate whether File is valid Excel file.
//       var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/
//       if (regex.test(fileUpload.value.toLowerCase())) {
//         const readSheetToArray = this.getArrayOfObject
//         if (typeof FileReader != 'undefined') {
//           var reader = new FileReader()

//           //For Browsers other than IE.
//           if (reader.readAsBinaryString) {
//             reader.onload = function (e) {
//               resolve({ ret: 0, data: readSheetToArray(e.target.result, oXLSX) })
//             }
//             reader.readAsBinaryString(fileUpload.files[0])
//           } else {
//             //For IE Browser.
//             reader.onload = function (e) {
//               var data = ''
//               var bytes = new Uint8Array(e.target.result)
//               for (var i = 0; i < bytes.byteLength; i++) {
//                 data += String.fromCharCode(bytes[i])
//               }
//               resolve({ ret: 0, data: readSheetToArray(data, oXLSX) })
//             }
//             reader.readAsArrayBuffer(fileUpload.files[0])
//           }
//         } else {
//           alert('This browser does not support HTML5.')
//           resolve({ ret: -1, msg: 'This browser does not support HTML5.' })
//         }
//       } else {
//         alert('Please upload a valid Excel file.')
//         resolve({ ret: -1, msg: 'Please upload a valid Excel file.' })
//       }
//     })
//   }
//   getArrayOfObject(data, oXLSX) {
//     //Read the Excel File data in binary
//     var workbook = oXLSX.read(data, {
//       type: 'binary'
//     })

//     //get the name of First Sheet.
//     var Sheet = workbook.SheetNames[0]

//     //Read all rows from First Sheet into an JSON array.
//     return oXLSX.utils.sheet_to_row_object_array(workbook.Sheets[Sheet])
//     // this.isRead = false
//   }
// }
