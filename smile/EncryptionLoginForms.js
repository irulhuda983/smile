/**
 *
 * @author Thorsten Stanitzok
 * @copyright © 2011 by Thorsten Stanitzok
 * @class Ext.ux.EncryptionLoginForm
 * @extends Ext.form.FormPanel
 *
 * Implements a simple Ext.form.FormPanel as a Loginform with encryption
 * capabilities. This is the form only, so it can be embedded into a window or
 * static panel, or whatever (i hope ;-)).
 * Can be configured as any other FormPanel, with some exceptions:
 *      - fixed anchor layout (anchor 100%)
 *      - fixed items (logo-container, login/password-field)
 *      - fixed buttons
 *
 * Some new config options added:
 *      - Simple Encryption support:
 *          expects a function which performs the encryption when activated
 *          Example:
 *          [...]
 *          encryption: {
 *                  active:true,
 *                  fn:yourFn
 *              }
 *          One parameter will be passed to the specified function, expecting the value
 *          to be encoded. If encryption is activated but no fn specified the
 *          Ext.ux.Encryption class is required.
 *
 *      - Submit configuration
 *          Since you want to perform some actions after pressing the login button you
 *          have to specify at least the success and failure callbacks. You are
 *          allowed to specify any other config avaible in Ext.form.Basic#doAction
 *          Example:
 *          submitConfig: {
 *              waitMsg:'Performing login...', //optional
 *              waitMsgTarget: true, // show msg direct in form.
 *              success: function(form, action) {
 *                  alert('success')
 *              },
 *              failure: function(form, action) {
 *                  alert('falure')
 *              }
 *          }
 *
 *      - loginLabel
 *          Labeltext for the login field. Defaults to 'Login'.
 *
 *      - passwordLabel
 *          Labeltext for the password field. Defaults to 'Password'.
 *
 *      - loginButtonText
 *          Text for the login button. Defaults to 'Login'.
 *
 *      - resetButtonText
 *          Text for the reset button. Defaults to 'Reset'.
 *
 *      - hideResetButton
 *          True to hide the reset button, false to show. Defaults to false.
 *
 *
 * Within the form is a logo-container specified. This Ext.container.Container
 * has a 60px height and an additional CSS-class called encryptedloginform-logo.
 *
 * Complete example:
 * Ext.onReady( function() {
 *      Ext.QuickTips.init();
 *      var loginWindow = Ext.create('Ext.Window', {
 *          title:'Login',
 *          modal:true,
 *          closable:false,
 *          resizable:false,
 *          items: [{
 *              xtype:'encryptionloginform',
 *              preventHeader:true,
 *              border:false,
 *              width: 500,
 *              url:'login.php',
 *              defaults: {
 *              labelWidth:100
 *          },
 *
 *          hideResetButton:false,
 *          resetButtonText:'My Reset Text',
 *          loginButtonText:'My Login Text',
 *          loginLabel:'Custom Login',
 *          passwordLabel:'Custom Password',
 *
 *          encryption: {
 *              fn: Ext.ux.Encryption.MD5,
 *              active: true
 *          },
 *
 *          submitConfig: {
 *              waitMsg:'Performing login...',
 *              waitMsgTarget: true,
 *              success: function(form, action) {
 *                  alert('success')
 *              },
 *              failure: function(form, action) {
 *                  alert('falure')
 *              }
 *          }
 *      }]
 * });
 * loginWindow.show();
 *});
 */

Ext.define('Ext.ux.EncryptionLoginForm', {
    extend:'Ext.form.FormPanel',
    alias:'widget.encryptionloginform',

    title:'Ext.ux.EncryptionLoginForm',

    /**
     * Labeltext for the login field. Defaults to 'Login'.
     */
    loginLabel:'Login',

    /**
     * Labeltext for the password field. Defaults to 'Password'.
     */
    passwordLabel:'Password',

    /**
     * Text for the login button. Defaults to 'Login'.
     */
    loginButtonText:'Login',

    /**
     * Text for the reset button. Defaults to 'Reset'.
     */
    resetButtonText:'Ganti Pasword',

    /**
     * True to hide the reset button, false to show. Defaults to false.
     */
    hideResetButton:false,

    bodyPadding:10,
    width:300,
    /**
     * Initializes the Ext.ux.EncryptionLoginForm and checks if this component is
     * configured correctly.
     */
    initComponent: function() {
        var callbackUsageMsg = "[...]\n\submitConfig:{\n\tsuccess:yourFunction\n\tfailure:yourFunction\n}\n[...]";

        //No submitConfig means no interactivity? Not allowed.
        if (!this.submitConfig) {
            throw "submitConfig have to be defined for Ext.ux.EncryptionLoginForm:\n" + callbackUsageMsg;
        }

        //No success fn. If login was successfull no reaction? Not allowed.
        if (!this.submitConfig.success) {
            throw "Success callback is missing for Ext.ux.EncryptionLoginForm:\n" + callbackUsageMsg;
        }

        //No failure fn. if login failed no msg to the user? Not allowed.
        if (!this.submitConfig.failure) {
            throw "Failure callback is missing for Ext.ux.EncryptionLoginForm:\n" + callbackUsageMsg;
        }

        //apply field defaults
        Ext.applyIf(this.defaults, {
            msgTarget:'side',
            labelWidth:100,
            allowBlank:false,

        });

        //fixed default values
        Ext.merge(this.defaults, {
            anchor:'100%',
            xtype:'textfield'
        });

        //Apply encryption defaults
        Ext.applyIf(this.encryption, {
            fn:undefined,
            active:false
        });

        //Add form fields.
        Ext.apply(this, {
            layout: {
                type:'anchor'
            },
            buttons: [{
                text:this.resetButtonText,
                hidden:this.hideResetButton,
				handler: showPassForm
				/*
                handler: function() {
                    //this.up('form').getForm().reset();
					//ubahpassword
					//showContactForm();
                }*/
            },{
                formBind:true,
                text:this.loginButtonText,
                handler: function() {
                    var form = this.up('form').getForm();
                    
                    if (form.isValid()) {
                        
                        if (isActiveCaptcha=="on"){
                            var submitCfg = this.up('form').submitConfig;
                            grecaptcha.ready(function() {
                                grecaptcha.execute(site_key, {action: 'submit'}).then(function(token) {
                                    // console.log(typeof token)
                                    if(token!==null){
                                        var formToken = form.findField('token');
                                        formToken.setValue(token);
                                        form.submit(submitCfg);

                                    }else{
                                        Ext.MessageBox.alert('ALERT', 'Koneksi Gagal ke Server. Pastikan internet anda lancar dan Refresh halaman ini, silakan dicoba kembali!');
                                    }
                                                                    
                                });
                            })
                        }else{//this will act if toggle captcha set off
                            form.submit(this.up('form').submitConfig);
                        }
                            
                    }
                }
            }],
            items: [{
                xtype:'container',
                cls:'encryptedloginform-logo',
                height:80,
                margin:'0 0 ' + this.bodyPadding + ' 0'
            },{
                fieldLabel:this.loginLabel,
                name:!this.encryption.active?'login':'loginDecrypted',
				id:'userlogin',
				maskRe: /\S/,
                submitValue:this.encryption.active === false,
				listeners: {
					change: function (obj, newValue) {
						// console.log(newValue);
						obj.setRawValue(newValue.toUpperCase());
					}
				}
            },{
                fieldLabel:this.passwordLabel,
				id:'userpass',
                type:'password',
                name:!this.encryption.active?'password':'passwordDecrypted',
                submitValue:this.encryption.active === false,
                inputType:'password',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							var form = this.up('form').getForm();
							if (form.isValid()) {
								if (isActiveCaptcha=="on"){
                                    var submitCfg = this.up('form').submitConfig;
                                    grecaptcha.ready(function() {
                                        grecaptcha.execute(site_key, {action: 'submit'}).then(function(token) {
                                            
                                            if(token!==null){
                                                var formToken = form.findField('token');
                                                formToken.setValue(token);
                                                form.submit(submitCfg);
        
                                            }else{
                                                Ext.MessageBox.alert('ALERT', 'Koneksi Gagal ke Server. Pastikan internet anda lancar dan Refresh halaman ini, silakan dicoba kembali!');
                                            }
                                                                            
                                        });
                                    })
                                }else{//this will act if toggle captcha set off
                                    form.submit(this.up('form').submitConfig);
                                }
							}
						}
					}
				},
            },{
                xtype:'hidden',
                name:'encrypted',
                value:this.encryption.active
            },{
                xtype:'hidden',
                name:'token',
                value:""
            }]
        })

        this.prepareEncryption(this.encryption.active);
        this.addEncryptionFields(this.encryption.active);

        this.callParent(arguments);
    },
    /**
     * Adds a 'beforeaction'-listener to the form to perform the encryption if
     * needed. Also the default encryption function will be added if not
     * specified.
     *
     * @param encryptionActive true, if the encryption is activated, otherwise
     * false
     */
    prepareEncryption: function(encryptionActive) {
        if (!encryptionActive) {
            return
        }

        if (!this.encryption.fn) {
            Ext.require('Ext.ux.Encryption');
            this.encryption.fn = Ext.ux.Encryption.MD5
        }

        this.on('beforeaction', this.encryptValues)
    },
    /**
     * Adds the hidden encryption fields. This fields will be submitted if
     * encryption is activated.
     *
     * @param encryptionActive true, if the encryption is activated, otherwise
     * false
     */
    addEncryptionFields: function(encryptionActive) {
        if (!encryptionActive) {
            return;
        }
        var hiddenLogin = {
            xtype:'hidden',
            name:'login',
        };
        var hiddenPassword = {
            xtype:'hidden',
            name:'password'
        }

        this.items.push(hiddenLogin);
        this.items.push(hiddenPassword);
    },
    /*
     * Encrypts fills the hidden fields with the encryption-method specified.
     */
    encryptValues: function(form, action) {
        //perform encryption only if action is 'instance of' submit
        if (action.type !== "directsubmit" && action.type !== "submit" && action.type !== "standardsubmit") {
            return;
        }
        var originalLogin = form.findField('loginDecrypted');
        var originalPassword = form.findField('passwordDecrypted');

        var hiddenLogin = form.findField('login');
        var hiddenPassword = form.findField('password');

        hiddenLogin.setValue(this.encryption.fn(originalLogin.getValue()));
        hiddenPassword.setValue(this.encryption.fn(originalPassword.getValue()));
    }
})
function showPassForm() {
	//if (!win) {
		var form = Ext.widget('form', {
			layout: {
				type: 'vbox',
				align: 'stretch'
			},
			border: false,
			bodyPadding: 10,

			fieldDefaults: {
				labelAlign: 'top',
				labelWidth: 100,
				labelStyle: 'font-weight:bold'
			},
			items: [{
				xtype: 'fieldcontainer',
				/*fieldLabel: 'Ubah Password untuk user: ',*/
				labelStyle: 'font-weight:bold;padding:0;',
				layout: 'hbox',
				defaultType: 'textfield',

				fieldDefaults: {
					labelAlign: 'top'
				},

				items: [{
					name: 'userName',
					itemId: 'userName',
					//inputType: 'password',
					fieldLabel: 'Username',
					allowBlank: false,
					margins: '0 0 0 7'
				}, {
					name: 'email',
					itemId: 'email',
					//inputType: 'password',
					fieldLabel: 'Email',
					allowBlank: false,
					margins: '0 0 0 7'
				}]
			}],

			buttons: [{
				text: 'Batal',
				handler: function() {
					this.up('form').getForm().reset();
					this.up('window').hide();
				}
			}, {
				text: 'Reset',
				handler: function() {
					if (this.up('form').getForm().isValid()) {
						// In a real application, this would submit the form to the configured url
						// this.up('form').getForm().submit();
						var form = this.up('form').getForm(),
                        values = form.getFieldValues();
						this.up('form').getForm().reset();
						this.up('window').hide();
						doSubmitLupaPass(values.userName, values.email.toLowerCase());
						//Ext.MessageBox.alert('Thank you!', 'Your inquiry has been sent. We will respond as soon as possible.');
					}
				}
			}]
		});

		win = Ext.widget('window', {
			title: 'Lupa Password',
			closeAction: 'hide',
			width: 420,
			height: 150,
			minWidth: 300,
			minHeight: 150,
			layout: 'fit',
			resizable: true,
			modal: true,
			items: form,
			defaultFocus: 'oldPass'
		});
	//}
	win.show();
}