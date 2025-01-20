<?PHP
$formserver = "http://172.26.0.18:8888";
?>
<HTML>
<HEAD><TITLE>Core System BPJS Ketenagakerjaan | Migration Test | Server Development</TITLE></HEAD>
<BODY >

<COMMENT id="forms_plugin_info" 
         serverURL="<?=$formserver;?>/forms/lservlet?ifcfs=/forms/frmservlet?form=<?=$_REQUEST["form"];?>&#38;config=siptonline-fusion&#38;p_user_id=<?=$_REQUEST["p_user_id"];?>&#38;p_kode_kantor=<?=$_REQUEST["p_kode_kantor"];?>&#38;p_sender=UI&#38;ifsessid=WLS_FORMS.formsapp.666&#38;acceptLanguage=en-US,en;q=0.8,id;q=0.6,ms;q=0.4,pl;q=0.2"
         plug_ver="clsid:CAFEEFAC-0016-0000-0012-ABCDEFFEDCBA" 
         appheight="600"
         appwidth="1200"
         appcodebase="http://java.sun.com/update/1.6.0/jinstall-6-windows-i586.cab#Version=1,6,0,12"
         appname="">
</COMMENT>
<!-- Forms applet definition (start) -->
<NOSCRIPT>
<OBJECT classid="clsid:CAFEEFAC-0016-0000-0012-ABCDEFFEDCBA"
        codebase="http://java.sun.com/update/1.6.0/jinstall-6-windows-i586.cab#Version=1,6,0,12"
        WIDTH="100%"
        HEIGHT="97%"
        HSPACE="0"
        VSPACE="0"
        ID="">
</NOSCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="<?=$formserver;?>/forms/frmjscript/forms_ie.js"></SCRIPT> 
<PARAM NAME="TYPE"       VALUE="application/x-java-applet">
<PARAM NAME="CODEBASE"   VALUE="<?=$formserver;?>/forms/java">
<PARAM NAME="CODE"       VALUE="oracle.forms.engine.Main" >
<PARAM NAME="ARCHIVE"    VALUE="icons.jar, jacob.jar, frmwebutil.jar, frmall.jar,banner.jar,SIPT.jar,frmgeneric_laf.jar,frmoracle_laf.jar,frmresources.jar,oraclebarcode.jar,webutil.jar,jacob.jar, frmwebutil.jar, frmall.jar,banner.jar,frmgeneric_laf.jar,frmoracle_laf.jar,frmresources.jar,oraclebarcode.jar" > 
<PARAM NAME="cache_archive_ex" VALUE="jacob.jar;preload,frmwebutil.jar;preload">
<PARAM NAME="serverURL" VALUE="<?=$formserver;?>/forms/lservlet?ifcfs=/forms/frmservlet?form=<?=$_REQUEST["form"];?>&#38;config=siptonline-fusion&#38;p_user_id=<?=$_REQUEST["p_user_id"];?>&#38;p_kode_kantor=<?=$_REQUEST["p_kode_kantor"];?>&#38;p_sender=UI&#38;ifsessid=WLS_FORMS.formsapp.666&#38;acceptLanguage=en-US,en;q=0.8,id;q=0.6,ms;q=0.4,pl;q=0.2">
<PARAM NAME="networkRetries" VALUE="0">
<PARAM NAME="serverArgs" 
       VALUE="escapeParams=true module=<?=$_REQUEST["form"];?> userid=  debug=no host= port= p_user_id=<?=$_REQUEST["p_user_id"];?> p_kode_kantor=<?=$_REQUEST["p_kode_kantor"];?>">
<PARAM NAME="separateFrame" VALUE="true">
<PARAM NAME="splashScreen"  VALUE="false">
<PARAM NAME="background"  VALUE="">
<PARAM NAME="lookAndFeel"  VALUE="Oracle">
<PARAM NAME="colorScheme"  VALUE="teal">
<PARAM NAME="serverApp" VALUE="default">
<PARAM NAME="logo" VALUE="">
<PARAM NAME="imageBase" VALUE="codebase">
<PARAM NAME="formsMessageListener" VALUE="">
<PARAM NAME="recordFileName" VALUE="">
<PARAM NAME="EndUserMonitoringEnabled" VALUE="false">
<PARAM NAME="EndUserMonitoringURL" VALUE="">
<PARAM NAME="heartBeat" VALUE="">
<PARAM NAME="MaxEventWait" VALUE="">
<PARAM NAME="allowAlertClipboard" VALUE="true">
<PARAM NAME="disableValidateClipboard" VALUE="false">
<PARAM NAME="enableJavascriptEvent" VALUE="true">
<PARAM NAME="MAYSCRIPT" VALUE="true">
<PARAM NAME="digitSubstitution" VALUE="context">
<PARAM NAME="legacy_lifecycle" VALUE="false">
<PARAM NAME="JavaScriptBlocksHeartBeat" VALUE="false">
<PARAM NAME="highContrast" VALUE="false">
<PARAM NAME="disableMDIScrollbars" VALUE="">
<PARAM NAME="clientDPI" VALUE="">
<PARAM name="applet_stop_timeout" value="800">
<PARAM name="guiMode" value="0">
<COMMENT> 
<EMBED SRC="" PLUGINSPAGE="http://java.sun.com/products/archive/j2se/6u12/index.html" 
        TYPE="application/x-java-applet" 
        java_codebase="<?=$formserver;?>/forms/java" 
        java_code="oracle.forms.engine.Main" 
        java_archive="icons.jar, jacob.jar, frmwebutil.jar, frmall.jar,banner.jar,SIPT.jar,frmgeneric_laf.jar,frmoracle_laf.jar,frmresources.jar,oraclebarcode.jar,webutil.jar,jacob.jar, frmwebutil.jar, frmall.jar,banner.jar,frmgeneric_laf.jar,frmoracle_laf.jar,frmresources.jar,oraclebarcode.jar" 
        cache_archive_ex="jacob.jar;preload,frmwebutil.jar;preload"
        WIDTH="100%"
        HEIGHT="97%" 
        HSPACE="0"
        VSPACE="0"
        serverURL="<?=$formserver;?>/forms/lservlet?ifcfs=/forms/frmservlet?form=<?=$_REQUEST["form"];?>&#38;config=siptonline-fusion&#38;p_user_id=<?=$_REQUEST["p_user_id"];?>&#38;p_kode_kantor=<?=$_REQUEST["p_kode_kantor"];?>&#38;p_sender=UI&#38;ifsessid=WLS_FORMS.formsapp.666&#38;acceptLanguage=en-US,en;q=0.8,id;q=0.6,ms;q=0.4,pl;q=0.2"
        networkRetries="0"
        serverArgs="escapeParams=true module=<?=$_REQUEST["form"];?> userid=  debug=no host= port= p_user_id=<?=$_REQUEST["p_user_id"];?> p_kode_kantor=<?=$_REQUEST["p_kode_kantor"];?>"
        separateFrame="false"
        splashScreen=""
        background=""
        lookAndFeel="Oracle"
        colorScheme="teal"
        serverApp="default"
        logo=""
        imageBase="codebase"
        recordFileName=""
        EndUserMonitoringEnabled="false"
        EndUserMonitoringURL=""
        heartBeat=""
        MaxEventWait=""
        disableValidateClipboard="false"
        allowAlertClipboard="true"
        enableJavascriptEvent="true"
        MAYSCRIPT="true"
        digitSubstitution="context"
        legacy_lifecycle="false"
        JavaScriptBlocksHeartBeat="false"
        highContrast="false"
        disableMDIScrollbars=""
        clientDPI=""
        applet_stop_timeout="800"
        guiMode="0"
>
<NOEMBED> 
</COMMENT> 
</NOEMBED></EMBED> 
</OBJECT>
</BODY>
</HTML>