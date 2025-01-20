<?PHP
session_start();
include('includes/connsql.php');

$username	 		= $_SESSION['NAMA'];
$login["user"] 		= $_SESSION['NAMA'];
$login["kantor"] 	= $_SESSION['KANTOR'];
$login["npk"] 		= $_SESSION['NPK'];
$login["email"] 	= $_SESSION['EMAIL'];
$login["ip"] 		= $_SESSION['IP'];

if($_SESSION["STATUS"] != "LOGIN"){
	echo "<script>window.location='index.php?error=Silahkan Login ulang';</script>";
}

?>
<html>
<head>
<title><?=$appname.'|'.$_SERVER['SERVER_NAME'];?></title>

<link rel="stylesheet" type="text/css" href="portal.css" />

<style type="text/css">
p {
    margin:5px;
}
.settings {
    background-image:url(shared/icons/fam/folder_wrench.png);
}
.nav {
    background-image:url(shared/icons/fam/folder_go.png);
}
.info {
    background-image:url(shared/icons/fam/information.png);
}
</style>
<script type="text/javascript" src="shared/include-ext.js"></script>
<script type="text/javascript" src="shared/options-toolbar.js"></script>
<script type="text/javascript">
    Ext.require(['*']);

    Ext.onReady(function() {

        Ext.QuickTips.init();

        var menustore = Ext.create('Ext.data.TreeStore', {
			root: {
				expanded: true
			},
			proxy: {
				type: 'ajax',
				url: 'resources/data/tree/tree-data.json'
			}
		});
		var treepanel = Ext.create('Ext.tree.Panel', {
				id: 'tree-panel',
				title: 'Pilihan Form',
				region:'north',
				split: true,
				height: 380,
				minSize: 150,
				rootVisible: false,
				autoScroll: true,
				store: menustore,
				iconCls: 'nav'				
				
		})
		var tabcenter = Ext.create('Ext.tab.Panel', {
                region: 'center', // a center region is ALWAYS required for border layout
                /*deferredRender: false,*/
				resizeTabs: true,
        		enableTabScroll: true,
                activeTab: 0,     // first tab initially active
				defaults: {
					bodyPadding: 10,
					autoScroll: true
				},
                items: [{
                    title: 'Dashboard',
                    autoScroll: true,
					items : [{
						xtype : "component",						
						/*style: 'width: 100%; height:100%;',*/
						contentEl: 'welcome',
						/*html: 'Welcome Greeting..!',
						
						loader: {
							url: 'http://172.26.0.18:8888/forms/frmservlet',
							contentType: 'html',
							autoLoad: true,
							params: 'form=/data/jms/SIPT/KP/FORM/KP7710&config=siptonline-fusion&p_user_id=RO158820&p_kode_kantor=0&p_sender=UI'
						}*/
						/*
						autoEl : {
							tag : "iframe",
							src : "http://172.26.0.18:8888/forms/frmservlet?form=/data/jms/SIPT/KP/FORM/KP7710&config=siptonline-fusion&p_user_id=RO158820&p_kode_kantor=0&p_sender=UI",
						}*/
					}],
                }]
            });
        Ext.state.Manager.setProvider(Ext.create('Ext.state.CookieProvider'));

        var viewport = Ext.create('Ext.Viewport', {
            id: 'app-viewport',
            layout: {
                type: 'border',
                padding: '5 0 0 0',
				align:"middle"
            },			
            items: [
            // create instance immediately
            Ext.create('Ext.Component', {
				id: 'app-header',
				xtype: 'hbox',
                region: 'north',
                height: 65, // give north and south regions a height
                /*html: '<a href="http://ercy.com/SIJSTK-FUSION/logout.bpjstk" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;"><img src="http://ercy.com/SIJSTK-FUSION/assets/img/logout.png" alt="BPJS Ketenagakerjaan"></a>',*/
				items: [
					Ext.create('Ext.menu.Menu', {
						id: 'mainMenu',
						style: {
							overflow: 'visible'     // For the Combo popup
						}
					}),
					Ext.create('Ext.toolbar.Toolbar')
				], 
				
            }), {
                // lazily created panel (xtype:'panel' is default)
                region: 'south',
                split: false,
                height: 35,
                minSize: 100,
                maxSize: 200,
                collapsible: false,
                collapsed: false,
                title: '<?=$copy;?>',
                margins: '0 0 0 0',
				tools: [
                    { type:'refresh' }
                ],
            }, {
                region: 'west',
                stateId: 'navigation-panel',
                id: 'west-panel', // see Ext.getCmp() below
                title: 'Navigation',
                split: true,
                width: 250,
                minWidth: 175,
                collapsible: true,
                animCollapse: true,
                margins: '0 0 0 5',
                items: [
					{
						id:'profil',
						html:'<div style="margin:10px; width:100%;"><div style="float:left; width:86px; text-align:center;"><img src="resources/images/photo.jpg" style="border:solid 2px #157FCC;"><strong><?=$login["npk"];?></strong></div><div style="float:left; width:154px; "><p><strong>Login Sebagai:</strong><br><?=$login["user"];?></p><p><strong>Role:</strong><br>{role}</p></div></div>',
						height:140,
					},
					treepanel
				]
            },
            tabcenter
            ]
        });
		
		Ext.define('RoleList', {
			extend: 'Ext.data.Model',
			fields: [
				{name:'KODE', type:'int'},
				{name:'NAMA', type:'string'},
				{name:'DETAIL', type:'string'}
			]
		});
	
		var rolestore = Ext.create('Ext.data.Store', {
			model: 'RoleList',
			autoLoad: true,
			proxy: {
				limitParam: undefined,
				startParam: undefined,
				paramName: undefined,
				pageParam: undefined,
				noCache:false,
				type: 'ajax',
				url: 'act/pilihrole.php?username=<?=$username;?>',
				reader: {
					root: 'myrole'
				}
			}
		});
		
		Ext.define('KdKantorList', {
			extend: 'Ext.data.Model',
			fields: [
				{name:'KdKantor', type:'string'},
				{name:'NmKantor', type:'string'}
			]
		});
		
		var kdkantorstore = Ext.create('Ext.data.Store', {
			model: 'KdKantorList',
			autoLoad: true,
			proxy: {
				limitParam: undefined,
				startParam: undefined,
				paramName: undefined,
				pageParam: undefined,
				noCache:false,
				type: 'ajax',
				url: 'act/kdkantorlist.php?username=<?=$username;?>',
				reader: {
					root: 'mycombo'
				}
			}
		});
		
		var formReport = Ext.create('Ext.form.Panel', {
			frame: true,
			title: 'Report Parameter',
			bodyPadding: '5 5 0',
			fieldDefaults: {
				labelWidth: 125,
				msgTarget: 'side',
				autoFitErrors: false
			},
			defaultType: 'datefield',
			items: [
			Ext.create('Ext.form.field.ComboBox', {
				id:'combokdkantor',
				fieldLabel: 'Pilih',
				displayField: 'NmKantor',
				valueField: 'KdKantor',
				name:'rule',
				width: 450,
				labelWidth: 125,
				store: kdkantorstore,
				allowBlank: false,
				queryMode: 'local',
				listConfig: {
					getInnerTpl: function() {
						return '<div data-qtip="{KdKantor}">{NmKantor}</div>';
					}
				}
			}),
			{
				fieldLabel: 'Start Date',
				name: 'startdt',
				id: 'startdt',
				vtype: 'date',
				dateFormat: 'd-m-Y',
				altFormats: 'd-m-Y',
				allowBlank: false
			}, {
				fieldLabel: 'End Date',
				name: 'enddt',
				id: 'enddt',
				vtype: 'date',
				dateFormat: 'd-m-Y',
				altFormats: 'd-m-Y',
				allowBlank: false
			},{
				xtype: 'radiogroup',
				fieldLabel: 'Jenis Channel',
				columns: 2,
				itemId: 'jnsChannel',
				id:'jnsChannel',
				width: 350,
				items: [
					{
						xtype: 'radiofield',
						boxLabel: 'VA',
						name: 'jenis_channel',
						checked: true,
						inputValue: 'VA'
					},
					{
						xtype: 'radiofield',
						boxLabel: 'EPS',
						name: 'jenis_channel',
						inputValue: 'EPS'
					}
				]
			}],
			buttons: [{
				text: 'CETAK REPORT',
				handler: function() {
					var winreport = Ext.create('Ext.window.Window', {
						title: 'View Report',
						collapsible: true,
						animCollapse: true,
						maximizable: true,
						width: 1125,
						height: 500,
						minWidth: 450,
						minHeight: 200,
						layout: 'fit',
						loader: {
							url: 'act/report.php?nama=penerimaan_iuran&param='+Ext.getCmp('combokdkantor').getValue()+'&tgl_from='+Ext.getCmp('startdt').getSubmitValue()+'&tgl_to='+Ext.getCmp('enddt').getSubmitValue()+'&jenis='+Ext.ComponentQuery.query('[name=jenis_channel]')[0].getGroupValue(),
							contentType: 'html',
							autoLoad: true,
						}
					});
					winreport.show();
					
				}
			}]
		})
		
        win = new Ext.Window(
    {
        layout: 'fit',
		title:'Pilih Rule Aplikasi',
        width: 500,
        height: 200,
        modal: true,
        closable: false,
        items: new Ext.create('Ext.form.Panel', 
        {
			margin:10,
          	items: [
			   {
					xtype: 'fieldset',
					title: 'Memilih Role',
					defaultType: 'textfield',
					defaults: {
						anchor: '100%'
					},
					items: [
						{
						xtype: 'fieldcontainer',
						id:'pilihrole',
						fieldLabel: 'Selamat datang user: <?=$_SESSION['NAMA'];?>, Anda melakukan koneksi ke Aplikasi SIJSTK dari <?=$login["ip"];?>',
						labelStyle: 'font-weight:bold;padding:0',
						labelWidth: 400,
						layout: 'hbox',
						},
						Ext.create('Ext.form.field.ComboBox', {
							id:'combox',
							fieldLabel: 'Pilih',
							displayField: 'NAMA',
							valueField: 'KODE',
							value:'Pilih...',
							name:'rule',
							width: 420,
							labelWidth: 60,
							store: rolestore,
							margin:10,
							queryMode: 'local',
							listConfig: {
								getInnerTpl: function() {
									return '<div data-qtip="{DETAIL}">{NAMA}</div>';
								}
							}
						})
					]
				}
		   ],			
			buttons: [
				{
					text:'Pilih',
					fn: showResultText,
					handler: function() {
						var form = this.up('form').getForm();
						var out = [];
						Ext.Object.each(form.getValues(), function(key, value){
							out.push(value);
						});
						
						form.submit({
						  method:'POST', 
						  headers: {
							  'Content-Type': 'application/json;charset=utf-8'
						  },
						  //params: Ext.util.JSON.encode(form.getValues()),
						  waitTitle:'Connecting', 
						  waitMsg:'Creating...',
						  url: 'act/setrule.php',
						  success:function(data){ 
							  Ext.example.msg('Success', 'Rule yang dipilih adalah <strong>'+Ext.getCmp('combox').getRawValue()+'</strong>');
							  var el = Ext.get("profil");
							  var prp = '<div style="margin:10px; width:100%;"><div style="float:left; width:86px; text-align:center;"><img src="resources/images/photo.jpg" style="border:solid 2px #157FCC;"><strong><?=$login["npk"];?></strong></div><div style="float:left; width:154px; "><p><strong>Login Sebagai:</strong><br><?=$login["user"];?></p><p><strong>Role:</strong><br>'+Ext.getCmp('combox').getRawValue()+'</p></div></div>';
							  el.setHTML(prp);
							  
							  var menustore = Ext.create('Ext.data.TreeStore', {
									root: {
										expanded: true
									},
									proxy: {
										type: 'ajax',
										url: 'resources/data/tree/menu-role.php'
									}
							  });
							  treepanel.getSelectionModel().on('select', function(selModel, record) {
									if (record.get('leaf')) {
										//addTab(true);
										if(record.getId() == "report_iuran_bpu"){
											tabcenter.add({
												closable: true,
												style: 'width: 100%; height:100%;',
												iconCls: 'tabs',
												title: record.get('text'),
												items:[
													formReport
												]
											}).show();
										} else {
											tabcenter.add({
												closable: true,
												/*html: 'Test Create Tab',*/
												style: 'width: 100%; height:100%;',
												iconCls: 'tabs',
												title: record.getId(),
												loader: {
													url: 'form/form.php?form=/data/jms/SIPT/KP/FORM/KP7710&p_user_id=RO158820&p_kode_kantor=0',
													contentType: 'html',
													autoLoad: true,
													params: ''
												}
											}).show();
										}
										/*
										function addTab (closable) {
											++index;
											tabcenter.add({
												closable: !!closable,
												html: 'Tab Body ' + index + '<br/><br/>' + Ext.example.bogusMarkup,
												iconCls: 'tabs',
												title: 'New Tab ' + index
											}).show();
										}*/
										/*
										Ext.getCmp('content-panel').layout.setActiveItem(record.getId() + '-panel');
										 if (!detailEl) {
											var bd = Ext.getCmp('details-panel').body;
											bd.update('').setStyle('background','#fff');
											detailEl = bd.createChild(); //create default empty div
										}
										detailEl.hide().update(Ext.getDom(record.getId() + '-details').innerHTML).slideIn('l', {stopAnimation:true,duration: 200});
										*/
										//Ext.example.msg('Gagal', record.getId());	
										
									}
								});
								treepanel.reconfigure(menustore);
								//myNewStore.setRootNode(myNewStore.getRootNode());
							  //treepanel.setRootNode(menustore);
							  //menurl.setRootNode(menustore);
							  //treepanel.setStore(menustore);
							  win.close();          
						  },
						  failure:function(form, result){
							  Ext.example.msg('Gagal', 'Gagal memilih rule.');	
						  }
							
						});
						
							
						if (form.isValid()) {
							var out = [];
							Ext.Object.each(form.getValues(), function(key, value){
								out.push(key + '=' + value);
							});
							//Ext.Msg.alert('Submitted Values', out.join('<br />'));
						}
					}
				},
				{ text:'Batal' }
			]
        })
    });
	win.show();
    function showResultText(btn, text){
		Ext.example.msg('Notifikasi', 'Rule yang dipilih adalah <strong>"{1}"</strong>.', btn, text);
	}
	getComboDisplay = function(combo) {
		var value = combo.getValue();
		var valueField = combo.valueField;
		var record;
		combo.getStore().each(function(r){
			if(r.data[valueField] == value){
				record = r;
				return false;
			}
		});	
		return record ? record.get(combo.displayField) : null;
	}
    });
    </script>
</head>
<body>
    <div id="welcome" class="x-hide-display">
        <img src="resources/images/home.png" class="welcome-img">
    </div>
</body>
</html>
