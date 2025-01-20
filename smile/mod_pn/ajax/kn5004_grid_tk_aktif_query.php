<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE            = $_POST["TYPE"] . $_GET['TYPE'];    //       echo $TYPE;
$PTYPE           = $_POST["PTIPE"] . $_GET['PTIPE'];    //       echo $TYPE;
$SEARCHA        = trim(isset($_REQUEST['SEARCHA'])?strtoupper($_REQUEST['SEARCHA']):''); //echo $SEARCHA;
$SEARCHB        = trim(isset($_REQUEST['SEARCHB'])?strtoupper($_REQUEST['SEARCHB']):''); //echo $SEARCHA;
$SEARCHC        = trim(isset($_REQUEST['SEARCHC'])?strtoupper($_REQUEST['SEARCHC']):''); //echo $SEARCHA;
$SEARCHD        = trim(isset($_REQUEST['SEARCHD'])?strtoupper($_REQUEST['SEARCHD']):''); //echo $SEARCHA;
$SEARCHE        = trim(isset($_REQUEST['SEARCHE'])?strtoupper($_REQUEST['SEARCHE']):''); //echo $SEARCHA;
$SEARCHF        = trim(isset($_REQUEST['SEARCHF'])?strtoupper($_REQUEST['SEARCHF']):''); //echo $SEARCHA;
$SEARCHG        = trim(isset($_REQUEST['SEARCHG'])?strtoupper($_REQUEST['SEARCHG']):''); //echo $SEARCHA;
$PAGE           = isset($_REQUEST['PAGE'])?strtoupper($_REQUEST['PAGE']):''; //echo $SEARCHA;
$USER           = $_SESSION["USER"];
$KODE_KANTOR  = $_SESSION['kdkantorrole'];
 //print_r($_REQUEST);
$schema="sijstk";      //echo $PAGE;print_r($_REQUEST);
$PAGE=$PAGE==''?1:$PAGE;       
$ROWPERPAGE = 15;
$startpage=($PAGE-1)*$ROWPERPAGE+1;
$endpage = $PAGE*$ROWPERPAGE;
$pagecount=0;

if($TYPE=='getTK' && trim($SEARCHB)!=''){
    $sqlfiltertambahan="";
    $sqlfiltertgllahir1="";
    $sqlfiltertmplahir1="";
    $sqlfiltertgllahir2="";
    $sqlfiltertmplahir2="";
    $sqlfilternama="";
    if(strtoupper(trim($SEARCHA))=='SC_NIK')
    {
        $sqlfiltertambahan=" and C.NOMOR_IDENTITAS='{$SEARCHB}'";      
    }
    if(strtoupper(trim($SEARCHA))=='SC_KPJ')
    {
        $sqlfiltertambahan=" and C.kpj='{$SEARCHB}'";      
    }
    if(strtoupper(trim($SEARCHA))=='SC_NAMA')
    {
        $sqlfilternama=" where nama_lengkap like '{$SEARCHB}%'";      
    }
    if(strtoupper(trim($SEARCHC))=='TEMPAT_LAHIR' && trim($SEARCHE)!='')
    {
        $sqlfiltertmplahir1.=" where tempat_lahir like '{$SEARCHE}%'";      
        $sqlfiltertmplahir2.=" and tempat_lahir like '{$SEARCHE}%'";      
    }
    if(strtoupper(trim($SEARCHC))=='TGL_LAHIR' && trim($SEARCHD)!='')
    {
        $sqlfiltertgllahir1.=" where to_char(tgl_lahir,'DD/MM/YYYY') = '".urldecode($SEARCHD)."'";      
        $sqlfiltertgllahir2.=" and to_char(tgl_lahir,'DD/MM/YYYY') = '".urldecode($SEARCHD)."'";      
    }
    $sqlfilterkpjtbh="";
    if(strtoupper(trim($SEARCHC))=='KPJTBH' && trim($SEARCHF)!='')
    {
        if(strtoupper(trim($SEARCHA))=='SC_KPJ')
        {
            $sqlfiltertambahan=" and (C.kpj='{$SEARCHB}' or c.kpj='{$SEARCHF}') ";
        }
        else
            $sqlfilterkpjtbh.=" and c.kpj='{$SEARCHF}'";
    }
    if(strtoupper(trim($SEARCHC))=='NIKTBH' && trim($SEARCHG)!='')
    {
        if(strtoupper(trim($SEARCHA))=='SC_NIK')
        {
            $sqlfiltertambahan=" and (C.NOMOR_IDENTITAS='{$SEARCHB}' or c.NOMOR_IDENTITAS='{$SEARCHG}') ";
        }
        else
            $sqlfilterkpjtbh.=" and c.NOMOR_IDENTITAS='{$SEARCHG}'";
    }
    if (strtoupper(trim($SEARCHA))!='SC_NAMA')
        $sqlutama="select kode_tk,kode_kantor,kpj,nama_lengkap,aktif,nomor_identitas,tgl_lahir,npp,kode_divisi,to_char(tgl_aktif,'DD-MM-YYYY')tgl_aktif,to_char(tgl_kepesertaan,'DD-MM-YYYY') tgl_kepesertaan,to_char(tgl_na,'DD-MM-YYYY')tgl_na,sijstk.F_KN_GET_NM_PRG_TK (kode_tk) NM_PRG,kode_segmen,
            namaprs,kode_perusahaan,kode_kepesertaan,kode_na,STATUS_VALID_IDENTITAS,
            (
                select case when kode_sebab_klaim in ('SKJ04','SKJ05') then 'KLAIM SEBAGIAN' else 'KLAIM PENUH' end STATUS_JHT  
                from pn.pn_klaim where (status_klaim='PEMBAYARAN' or status_klaim='SELESAI') and a.KODE_TK=KODE_TK and rownum=1
            ) STATUS_KLAIM_JHT
             from(
        select row_number() over(partition by b.kode_tk order by b.no_mutasi) N,b.no_mutasi, b.kode_kepesertaan, a.kode_kantor, a.kode_perusahaan, a.kode_divisi, a.kode_segmen, b.no_mutasi, b.aktif,b.tgl_aktif ,b.tgl_na,b.tgl_kepesertaan,
            c.kode_tk, c.kpj, c.nomor_identitas,      (select NPP from sijstk.kn_perusahaan where kode_perusahaan=a.kode_perusahaan) NPP,b.kode_na,
            (select NAMA_PERUSAHAAN from sijstk.kn_perusahaan where kode_perusahaan=a.kode_perusahaan) NAMAPRS,STATUS_VALID_IDENTITAS,
            case when c.jenis_identitas = 'KTP' and c.status_valid_identitas = 'Y' then
                (select nama_lengkap from sijstk.kn_penduduk where nik = c.nomor_identitas)
            else
                (select nama_lengkap from sijstk.kn_penduduk_um where kode_tk = c.kode_tk)
            end nama_lengkap, 
            case when c.jenis_identitas = 'KTP' and c.status_valid_identitas = 'Y' then
                (select tgl_lahir from sijstk.kn_penduduk where nik = c.nomor_identitas)
            else
                (select tgl_lahir from sijstk.kn_penduduk_um where kode_tk = c.kode_tk)
            end tgl_lahir,
            case when c.jenis_identitas = 'KTP' and c.status_valid_identitas = 'Y' then
                (select tempat_lahir from sijstk.kn_penduduk where nik = c.nomor_identitas)
            else
                (select tempat_lahir from sijstk.kn_penduduk_um where kode_tk = c.kode_tk)
            end tempat_lahir
        from sijstk.kn_kepesertaan_prs a, sijstk.kn_kepesertaan_tk b, sijstk.kn_tk c
        where a.kode_kepesertaan = b.kode_kepesertaan and b.kode_tk = c.kode_tk
        and a.tgl_transaksi<sysdate
        and a.status = 'PESERTA'
        and b.status IN ('PESERTA', 'CTKI', 'TKI', 'TKIE')
        and b.tgl_transaksi <= sysdate+1
        AND b.status_batal_trans = 'T'
        and b.no_mutasi =(select max(no_mutasi) from sijstk.kn_kepesertaan_tk where kode_tk=c.kode_tk and kode_kepesertaan=b.kode_kepesertaan)
        {$sqlfiltertambahan}  
        ) A {$sqlfiltertmplahir1} {$sqlfiltertgllahir1} order by nomor_identitas,kpj,nama_lengkap"; 
        //group by kode_tk,kode_kantor,kpj,nama_lengkap,aktif,nomor_identitas,tgl_lahir,npp,kode_divisi,namaprs,kode_perusahaan,kode_kepesertaan,kode_na,STATUS_VALID_IDENTITAS,to_char(tgl_aktif,'DD-MM-YYYY'),to_char(tgl_kepesertaan,'DD-MM-YYYY') ,to_char(tgl_na,'DD-MM-YYYY'),kode_segmen
        //order by nomor_identitas,kpj,nama_lengkap "; // echo $sql;
    else
        $sqlutama="SELECT a.KODE_TK,STATUS_VALID_IDENTITAS,
                 a.NOMOR_IDENTITAS,
                 a.KPJ,
                 a.nama_lengkap,
                 a.tgl_lahir,
                 a.tempat_lahir,
                 c.kode_kantor,
                 d.npp,
                 d.nama_perusahaan NAMAPRS,
                 c.kode_divisi,
                 to_char(b.tgl_na,'DD-MM-YYYY') tgl_na,
                 b.kode_na,
                 c.kode_segmen,
                 b.aktif,
                 to_char(b.tgl_aktif,'DD-MM-YYYY') tgl_aktif,
                 c.kode_perusahaan,
                 b.kode_kepesertaan,
                 to_char(b.tgl_kepesertaan,'DD-MM-YYYY') tgl_kepesertaan,
                 sijstk.F_KN_GET_NM_PRG_TK (a.kode_tk) NM_PRG,
                 (
                    select case when kode_sebab_klaim in ('SKJ04','SKJ05') then 'KLAIM SEBAGIAN' else 'KLAIM PENUH' end STATUS_JHT  
                    from pn.pn_klaim  where (status_klaim='PEMBAYARAN' or status_klaim='SELESAI') and KODE_TK=a.KODE_TK and rownum=1
                ) STATUS_KLAIM_JHT
            FROM (SELECT C.KODE_TK,
                         C.NOMOR_IDENTITAS,
                         C.KPJ,
                         a.nama_lengkap,
                         a.tgl_lahir,
                         a.tempat_lahir,
                         c.STATUS_VALID_IDENTITAS
                    FROM (SELECT 1 AS match,
                                 nik,
                                 CAST (' ' AS VARCHAR2 (30)) kode_tk,
                                 nama_lengkap,
                                 tgl_lahir,
                                 tempat_lahir
                            FROM SIJSTK.kn_penduduk {$sqlfilternama} {$sqlfiltertgllahir2} {$sqlfiltertmplahir2}  and rownum<500
                          UNION ALL
                          SELECT 1 AS match,
                                 CAST (' ' AS VARCHAR2 (16)) nik,
                                 kode_tk,
                                 nama_lengkap,
                                 tgl_lahir,
                                 tempat_lahir
                            FROM SIJSTK.kn_penduduk_um {$sqlfilternama}  {$sqlfiltertgllahir2} {$sqlfiltertmplahir2}   and rownum<500) a,
                         SIJSTK.KN_TK C
                   WHERE (   
                            (    
                                c.jenis_identitas = 'KTP'
                                AND c.status_valid_identitas = 'Y'
                                AND a.nik = nomor_identitas
                            )
                          OR (    
                            NOT (    
                                c.jenis_identitas = 'KTP'
                                AND c.status_valid_identitas = 'Y')
                                AND a.kode_tk = c.kode_tk
                            )
                          )
                          {$sqlfilterkpjtbh}
                   ) a
                 INNER JOIN sijstk.kn_kepesertaan_tk b ON a.kode_tk = b.kode_tk
                 INNER JOIN sijstk.kn_kepesertaan_prs c
                    ON b.kode_kepesertaan = c.kode_kepesertaan
                 INNER JOIN sijstk.kn_perusahaan d
                    ON c.kode_perusahaan = d.kode_perusahaan
                 where b.no_mutasi =(select max(no_mutasi) from sijstk.kn_kepesertaan_tk where kode_tk=a.kode_tk and kode_kepesertaan=b.kode_kepesertaan)
                 and c.tgl_transaksi<sysdate
                 and c.status = 'PESERTA'
                 and b.status IN ('PESERTA', 'CTKI', 'TKI', 'TKIE')
                 and b.tgl_transaksi <= sysdate+1
                 AND b.status_batal_trans = 'T'
                 order by nomor_identitas,kpj,nama_lengkap"; 
    $rcount =0; //echo $sqlutama;
    $irow=0;//echo $sql;
    $sql = "select count(*) jml from ({$sqlutama})";
    if($DB->parse($sql))
        if($DB->execute())
            if($row = $DB->nextrow())
                $rcount=$row['JML'];
    $pagecount = floor($rcount / $ROWPERPAGE)+1;
    $arrdata=array();
    $sql = "select * from (select rownum nourut,a.* from ({$sqlutama}) a order by NAMA_LENGKAP) where nourut between {$startpage} and {$endpage}"; 
    if($DB->parse($sql))
        if($DB->execute())
        {
            if($PTIPE=='1')
            {
                while($row = $DB->nextrow())
                {
                    echo "<tr class=\"gw_tr\"><td valign=\"top\"><a href=\"javascript:void(0)\" onclick=\"window.location.replace('kn5004.php?task=Edit&sender=kn5004&status_valid_identitas={$row['STATUS_VALID_IDENTITAS']}&kpj={$row['KPJ']}&kode_tk={$row['KODE_TK']}&nomor_identitas={$row['NOMOR_IDENTITAS']}&searchtxt={$SEARCHB}&pilihsearch={$SEARCHA}&type2={$SEARCHC}&keyword2a={$SEARCHD}&keyword2b=".urlencode($SEARCHE)."&keyword2c={$SEARCHF}&keyword2d={$SEARCHG}','kn5004 - Profile Tenaga Kerja')\">{$row['KODE_TK']}</a></td><td valign=\"top\">{$row['KPJ']}</td><td valign=\"top\">{$row['NOMOR_IDENTITAS']}</td><td valign=\"top\">{$row['NAMA_LENGKAP']}</td><td valign=\"top\" align=\"center\">{$row['TGL_LAHIR']}</td><td valign=\"top\" align=\"center\">{$row['KODE_KANTOR']}</td><td valign=\"top\">{$row['KODE_SEGMEN']}</td><td valign=\"top\">{$row['NPP']}</td><td valign=\"top\">{$row['NAMAPRS']}</td><td valign=\"top\" align=\"center\">{$row['KODE_DIVISI']}</td><td valign=\"top\" align=\"center\">{$row['TGL_KEPESERTAAN']}</td><td valign=\"top\" align=\"center\">{$row['TGL_AKTIF']}</td><td valign=\"top\" align=\"center\">{$row['TGL_NA']}</td><td valign=\"top\" align=\"center\">{$row['AKTIF']}</td><td valign=\"top\" align=\"center\">{$row['KODE_NA']}</td><td valign=\"top\">{$row['NM_PRG']}</td><td valign=\"top\" align=\"center\">{$row['STATUS_KLAIM_JHT']}</td><td alig\"center\"><a href=\"#\" onclick=\"NewWindow('../ajax/kn5004_cetak.php?kode_tk={$row['KODE_TK']}&kode_kepesertaan={$row['KODE_KEPESERTAAN']}&kode_perusahaan={$row['KODE_PERUSAHAAN']}&kpj={$row['KPJ']}','',1000,500,1)\"><img src=\"../../images/printer.png\" style=\"margin:0;\" /></a>
</td></tr>";                
                    $irow++;
                }
                if($irow>0)
                {
                    echo "<tr><th colspan=\"18\"><hr style=\"border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));\"/></th></tr>";
                    echo "<tr><td valign=\"top\" colspan=\"18\" align=\"center\">";
                    echo $PAGE==1?"First ":"<a href=\"javascript:void(0)\" onclick=\"getDataTK(1,1);\">First </a> ";echo $PAGE==1?"&lt;&lt;":"<a href=\"\">&lt;&lt;</a> ";
                    echo "  &nbsp;  ( ";
                    for ($i==1;$i<=$pagecount;$i++)
                    {
                        echo $PAGE==$i?"<b>{$i}</b>":" <a href=\"javascript:void(0)\" onclick=\"getDataTK(1,{$i});\">{$i}</a> ";
                    }
                    echo " )  &nbsp;  ";
                    echo $PAGE==$pagecount?"&gt;&gt;":"<a href=\"\">&gt;&gt;</a> ";echo $PAGE==$pagecount?" Last":" <a href=\"javascript:void(0)\" onclick=\"getDataTK(1,{$pagecount});\">Last</a> ";
                    echo "</td>";
                }
            }
            else
            {
                while($row = $DB->nextrow())
                {
                    $arrdata[]=$row;
                }
            }
        }
    if($irow==0 && $PTIPE=='1')
        echo "<tr><td></td><td ></td><td></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td ></td><td >&nbsp;</td></tr>";                
    if($PTIPE!='1')
        echo json_encode($arrdata);
} 
else if($PTYPE==2){
    echo '{"ret":-1,"msg":"Tidak ada tipe yang dipilih"}';
}
?>