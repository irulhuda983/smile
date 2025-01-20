<?PHP
// session_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
 }
include('includes/connsql.php');
header("Access-Control-Allow-Origin: *");

global $toggle_captcha;
global $g_site_key;

?>
<!DOCTYPE HTML>
<html>
    <head>
        <title><?=$appname;?></title>
        <meta charset="UTF-8" />
        <link href="images/favicon.png" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="shared/resources/css/ext-all-neptune.css" />
        <?php
            if ($toggle_captcha == "on") { 
        ?>
        <script src="https://www.recaptcha.net/recaptcha/api.js?render=<?php echo $g_site_key;?>"></script>
        <?php } ?>
        <script type="text/javascript" src="shared/ext-all.js"></script> 
        <script type="text/javascript" src="Encryption.js"></script>
        <script type="text/javascript" src="EncryptionLoginForms.js"></script>
        
        <style type="text/css">
            body{
                background: #CBD8F1 url(resources/images/bgrn-core.png) no-repeat center center fixed !important;
                -webkit-background-size: cover !important;
                -moz-background-size: cover !important;
                -o-background-size: cover !important;
                background-size: cover !important;
            }
            .x-mask {
              opacity: 0.4 !important;
              background: linear-gradient(45deg, #0322ff, #0099a0, #004aff, #00b371);
              background-size: 450% 100%;
              background-position: 0% 50%;
                /*
              -webkit-animation-name: animate_gradient;
              animation-name: animate_gradient;
              -webkit-animation-duration: 35s;
              animation-duration: 35s;
              -webkit-animation-timing-function: linear;
              animation-timing-function: linear;
              -webkit-animation-iteration-count: infinite;
              animation-iteration-count: infinite;
                */
            }
            @-webkit-keyframes animate_gradient {
                0% {
                    background-position: 0% 50%; }
                50% {
                    background-position: 100% 50%; }
                100% {
                    background-position: 0% 50%; } }
            @keyframes animate_gradient {
                0% {
                    background-position: 0% 50%; }
                50% {
                    background-position: 100% 50%; }
                100% {
                    background-position: 0% 50%; } }

            .encryptedloginform-logo {
                background:transparent url(resources/images/smile-login.png) center center no-repeat;
            }
            .x-message-box .ext-mb-loader {
                background: url(resources/images/loading.gif) no-repeat scroll 6px 0px transparent;
                height: 52px!important;
            }

        </style>
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
        
        <script type="text/javascript">
            const isActiveCaptcha = '<?php echo $toggle_captcha;?>' 
            const site_key = '<?php echo $g_site_key;?>'
            
            Ext.onReady(function () {
                Ext.QuickTips.init();
                Ext.override(Ext.form.TextField, {
                    enableKeyEvents: true,
                    onKeyUp: function (e, o) {
                        if (this.getId == 'userlogin') {
                            var value = this.getValue().toUpperCase();
                            this.setValue(value);
                            this.fireEvent('keyup', this, e);
                        }
                    }
                });

                var loginWindow = Ext.create('Ext.Window', {
                    title: 'Login SMILE System',
                    modal: true,
                    closable: false,
                    resizable: true,
                    items: [{
                            xtype: 'encryptionloginform',
                            preventHeader: true,
                            border: false,
                            width: 350,
                            url: 'act/login.bpjs',
                            defaults: {
                                labelWidth: 80,
                                style: 'text-align:right'
                            },
                            //hideResetButton: true,
                            resetButtonText: 'Lupa Password',
                            loginButtonText: 'Login',
                            loginLabel: 'Username',
                            passwordLabel: 'Password',
                            encryption: {
                                fn: Ext.ux.Encryption.MD5,
                                active: false
                            },
                            submitConfig: {
                                waitMsg: 'Mencoba untuk login...',
                                waitMsgTarget: true,
                                method: 'POST',
                                success: function (form, action) {
                                    Ext.Msg.alert('ALERT', action.result.logininfo.redirect);
                                    Ext.MessageBox.show({
                                        msg: 'Membuka Halaman Aplikasi, silahkan tunggu...',
                                        progressText: 'Login Sukses',
                                        width: 300,
                                        wait: true,
                                        icon: 'ext-mb-loader',
                                        iconHeight: 50,
                                        animateTarget: 'mb7'
                                    });
                                    window.parent.location = action.result.logininfo.redirect;

                                },
                                failure: function (form, action) {
                                    console.log(action.response.responseText);
                                    if(action.result.ret=="-3"){
                                        title = "Konfirmasi"
                                        msg = action.result.errors.msg
                                        window.parent.Ext.Msg.show({
                                            title: title,
                                            msg: msg,
                                            buttons: window.parent.Ext.Msg.YESNO,
                                            icon: window.parent.Ext.Msg.QUESTION,
                                            fn: function(btn) {
                                                if (btn === 'yes') {
                                                    forceLogout();
                                                } else {
                                                    console.log("no")
                                                }
                                            }
                                        });
                                    }else{
                                        Ext.Msg.alert('ALERT', action.result.errors.msg);
                                    }
                                    
                                }
                            }
                        }]
                });
                loginWindow.show();
                Ext.getCmp('userlogin').focus(false, 200);
                if (Ext.getCmp('userlogin').data('autocomplete')) {
                    Ext.getCmp('userlogin').autocomplete("destroy");
                    Ext.getCmp('userlogin').autocomplete("off");
                    Ext.getCmp('userlogin').autocomplete("false");
                    Ext.getCmp('userlogin').removeData('autocomplete');
                }

            });

            function doSubmitLupaPass(kodeUser, email){
                confirmation("Konfirmasi", "Password Akun Anda Akan Direset, Apakah Anda Yakin?",
                  //setTimeout(
                    function () {
                    Ext.MessageBox.show({
                        msg: 'Processing......',
                        progressText: 'Sukses',
                        width: 150,
                        height: 30,
                        wait: true,
                        icon: 'ext-mb-loader',
                        iconHeight: 10,
                        animateTarget: 'mb7'
                    });
                    $.ajax({
                            type: 'POST',
                            url: "act/login.bpjs",
                            data: {
                              tipe: 'LUPA_PASSWORD',
                              kodeUser: kodeUser,
                              email : email
                            },
                            success: function(data){
                                // console.log(data);
                                jdata = JSON.parse(data);
                                if (jdata.ret == '0') {
                                //window.parent.Ext.notify.msg('Berhasil', jdata.msg);
                                // console.log(data);
                                    Ext.MessageBox.alert('Konfirmasi!', 'Permintaan reset password berhasil, silahkan cek email Anda!');
                                }else {
                                    Ext.MessageBox.alert('Konfirmasi!', jdata.msg);
                                }
                            },
                            complete: function(){
                              //preload(false);
                            },
                            error: function(){
                              alert("Terjadi kesalahan, coba beberapa saat lagi!");
                              //preload(false);
                            }
                          });
                  }, //100));
                  setTimeout(function(){}, 1000));

            }

            function confirmation(title, msg, fnYes, fnNo) {
                window.parent.Ext.Msg.show({
                    title: title,
                    msg: msg,
                    buttons: window.parent.Ext.Msg.YESNO,
                    icon: window.parent.Ext.Msg.QUESTION,
                    fn: function(btn) {
                        if (btn === 'yes') {
                            fnYes();
                        } else {
                            fnNo();
                        }
                    }
                });
            }
            
            
            function forceLogout(){
                Ext.MessageBox.show({
                        msg: 'Processing......',
                        progressText: 'Sukses',
                        width: 150,
                        height: 30,
                        wait: true,
                        icon: 'ext-mb-loader',
                        iconHeight: 10,
                        animateTarget: 'mb7'
                    });
                    $.ajax({
                            type: 'POST',
                            url: "act/login.bpjs",
                            data: {
                              tipe: 'FORCE_LOGOUT'
                            },
                            success: function(data){
                                
                                jdata = JSON.parse(data);
                                if (jdata.ret == '0') {
                                    Ext.MessageBox.alert('Konfirmasi!', "Silakan klik tombol login kembali.");
                                }else {
                                    // console.log(jdata);
                                    Ext.MessageBox.alert('Alert!', jdata.msg);
                                }
                            },
                            complete: function(){
                              //preload(false);
                            },
                            error: function(){
                              alert("Terjadi kesalahan, coba beberapa saat lagi!");
                              //preload(false);
                            }
                          });
                  
            }

        </script>
    </head>
    <body>
    </body>
</html>
</html>
