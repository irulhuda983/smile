<?PHP
session_start();
header("Access-Control-Allow-Origin: *");
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>SIJSTK Core System - Server 101</title>
		<meta charset="UTF-8" />

		<link rel="stylesheet" type="text/css" href="shared/resources/css/ext-all-neptune.css" />

		<script type="text/javascript" src="shared/ext-all.js"></script> 
		<script type="text/javascript" src="Encryption.js"></script>
		<script type="text/javascript" src="EncryptionLoginForms.js"></script>
		<style type="text/css">
			body{
				background: #CBD8F1 url(resources/images/bgrn.jpg) fixed !important;
			}
			.x-mask{
				opacity: 0.4 !important;
    			background: #9FB8DC !important;
			}
			.encryptedloginform-logo {
				background:transparent url(resources/images/logo-login.png) center center no-repeat;
			}
			.x-message-box .ext-mb-loader {
				background: url(resources/images/loading.gif) no-repeat scroll 6px 0px transparent;
				height: 52px!important;
			}

		</style>
		<script type="text/javascript">
   		 Ext.onReady( function() {
        	Ext.QuickTips.init();
		Ext.override(Ext.form.TextField, {
			enableKeyEvents:true,
			onKeyUp: function (e,o){
				if(this.getId == 'userlogin'){
					var value = this.getValue().toUpperCase();
					this.setValue(value);
					this.fireEvent('keyup', this, e);
				}
			}
		});
        var loginWindow = Ext.create('Ext.Window', {
            title:'Login SIJSTK Core System',
            modal:true,
            closable:false,
            resizable:true,
            items: [{
                xtype:'encryptionloginform',
                preventHeader:true,
                border:false,
                width: 350,

                url:'act/login.bpjs',

                defaults: {
                    labelWidth:80,
					style:'text-align:right'
                },

                hideResetButton:true,
                resetButtonText:'Reset',
                loginButtonText:'Login',
                loginLabel:'Username',
                passwordLabel:'Password',

                encryption: {
                    fn: Ext.ux.Encryption.MD5,
                    active: false
                },
				
                submitConfig: {
                    waitMsg:'Mencoba untuk login...',
                    waitMsgTarget: true,
					method:'POST', 
                    success: function(form, action) {
						Ext.Msg.alert('ALERT',action.result.logininfo.redirect);
						Ext.MessageBox.show({
						   msg: 'Membuka Halaman Aplikasi, silahkan tunggu...',
						   progressText: 'Info Login',
						   width:300,
						   wait:true,
						   icon:'ext-mb-loader',
						   iconHeight: 50,
						   animateTarget: 'mb7'
					   });
					   window.parent.location=action.result.logininfo.redirect;
						
                    },
                    failure: function(form, action) {
                        Ext.Msg.alert('ALERT',action.result.errors.msg);
                    }
                }
            }]
        });
        loginWindow.show();
		Ext.getCmp('userlogin').focus(false, 200);

    });
		</script>
	</head>
	<body>
	</body>
</html>
</html>
