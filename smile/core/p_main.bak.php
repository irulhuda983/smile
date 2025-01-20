<?PHP
session_start();
include('includes/connsql.php');

$username	 		= $_SESSION["USER"];
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
<title><?=$appname;?></title>

<link rel="stylesheet" type="text/css" href="portal.css" />

<script type="text/javascript" src="shared/include-ext.js"></script>
<script type="text/javascript" src="shared/options-toolbar.js"></script>
<script type="text/javascript">
    Ext.require(['*']);

    Ext.onReady(function() {

        Ext.QuickTips.init();
		
		new Ext.util.KeyMap(document.body, {
			key  : 's',
			shift : true,
			fn   : function(keycode, e) {
				e.stopEvent();		
				console.log('shift + s was pressed');
			}
		});
		new Ext.util.KeyMap(document.body, {
			key  : 'f',
			shift : true,
			fn   : function(keycode, e) {
				Ext.MessageBox.prompt('Buka Form', 'Silahkan masukan kode Form/Report:', bukaForm);
			}
		});
		
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
				iconCls: 'nav',
				tools: [
                    { type:'search',
					tooltip: 'Cari Form / Report',
					handler: function(s) {
							Ext.MessageBox.prompt('Buka Form', 'Silahkan masukan kode Form/Report:', bukaForm);
						}
					}
                ],
				listeners: {
					itemclick: function(s,r) {						
						bukaForm(r.data.leaf,r.data.id,r.data.text);
					}
				}			
				
		})
		var itemsPerPage = 10,
        nama = 'MPPA';
        //total = 0; // records to return

		var listmppastore = Ext.create('Ext.data.Store', {
			fields: ['iddokumen', 'kantor', 'prog','tglajuan', 'jenis', 'mataanggaran','jmlajuan', 'keterangan'],
	
			autoLoad: true,
			pageSize: itemsPerPage,
	
			proxy: {
				type: 'ajax',
				url: 'act/listmppa.bpjs',
				reader: {
					type: 'json',
					root: 'rows',
					totalProperty: 'total'
				},
				extraParams: {
					nama: nama
					//total: total
				}
			}
		});
		function bukaWindow(judul, myurl){
			var winreport = Ext.create('Ext.window.Window', {
				title: judul,
				collapsible: true,
				animCollapse: true,
				maximizable: true,
				width: 1125,
				height: 500,
				minWidth: 450,
				minHeight: 200,
				layout: 'fit',
				loader: {
					url: myurl,
					contentType: 'html',
					autoLoad: true,
				}
			});
			winreport.show();
		}
		function bukaForm(formleaf, formid, formtext){
			var checkTab = tabcenter.getComponent(formid);
			if (checkTab) {
                tabcenter.setActiveTab(checkTab);
            } else {
				if (formleaf) {
					if(formid == "report_iuran_bpu"){
						tabcenter.add({
							closable: true,
							style: 'width: 100%; height:100%;',
							iconCls: 'tabs',
							id:formid,
							title: formtext,
							items:[
								Ext.create('Ext.form.Panel', {
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
										anyMatch: true,
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
										type: 'date',
										dateFormat: 'Y-m-d',
										submitFormat: 'Y-m-d',
										allowBlank: false
									}, {
										fieldLabel: 'End Date',
										name: 'enddt',
										id: 'enddt',
										type: 'date',
										dateFormat: 'Y-m-d',
										submitFormat: 'Y-m-d',
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
													url: 'report/penerimaan_iuran.bpjs?kantor='+Ext.getCmp('combokdkantor').getValue()+'&tgl_from='+Ext.getCmp('startdt').getSubmitValue()+'&tgl_to='+Ext.getCmp('enddt').getSubmitValue()+'&jenis='+Ext.ComponentQuery.query('[name=jenis_channel]')[0].getGroupValue(),
													contentType: 'html',
													autoLoad: true,
												}
											});
											winreport.show();
											
										}
									}]
								})
							]
						}).show();
					} else if(formid == "report_iuran_jakon"){
						tabcenter.add({
							closable: true,
							style: 'width: 100%; height:100%;',
							iconCls: 'tabs',
							id:formid,
							title: formtext,
							items:[
								Ext.create('Ext.form.Panel', {
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
										id:'combokdkantor2',
										fieldLabel: 'Pilih',
										displayField: 'NmKantor',
										valueField: 'KdKantor',
										name:'rule',
										width: 450,
										labelWidth: 125,
										store: kdkantorstore,
										allowBlank: false,
										queryMode: 'local',
										typeAhead:true,
										anyMatch: true,
										listConfig: {
											getInnerTpl: function() {
												return '<div data-qtip="{KdKantor}">{NmKantor}</div>';
											}
										}
									}),
									{
										fieldLabel: 'Start Date',
										name: 'startdt',
										id: 'startdt2',
										vtype: 'date',
										dateFormat: 'd-m-Y',
										altFormats: 'd-m-Y',
										allowBlank: false
									}, {
										fieldLabel: 'End Date',
										name: 'enddt',
										id: 'enddt2',
										vtype: 'date',
										dateFormat: 'd-m-Y',
										altFormats: 'd-m-Y',
										allowBlank: false
									},{
										xtype: 'radiogroup',
										fieldLabel: 'Jenis Channel',
										columns: 2,
										itemId: 'jnsChannel',
										id:'jnsChannel2',
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
													url: 'act/report.php?nama=penerimaan_iuran&param='+Ext.getCmp('combokdkantor2').getValue()+'&tgl_from='+Ext.getCmp('startdt2').getSubmitValue()+'&tgl_to='+Ext.getCmp('enddt2').getSubmitValue()+'&jenis='+Ext.ComponentQuery.query('[name=jenis_channel2]')[0].getGroupValue(),
													contentType: 'html',
													autoLoad: true,
												}
											});
											winreport.show();
											
										}
									}]
								})
							]
						}).show();
					} else if(formid == "approval_mppa"){
						tabcenter.add({
							closable: true,
							style: 'width: 100%; height:100%;',
							iconCls: 'tabs',
							id:formid,
							title: formtext,
							items:[
							
							
								Ext.create('Ext.form.Panel', {
								frame: true,
								title: 'Approval Memo Pengajuan Pencairan Anggaran',
								bodyPadding: '5 5 0',
								fieldDefaults: {
									labelWidth: 125,
									msgTarget: 'side',
									autoFitErrors: false
								},
								defaultType: 'datefield',
								items: [{
									xtype: 'fieldset',
									title: 'Parameter Data Approval MPPA',
									defaultType: 'datefield', 
									style: 'padding-bottom:10px;',
									layout: 'anchor',
									items: [																
											Ext.create('Ext.form.field.ComboBox', {
												id:'combokdkantor_approval_mppa',
												fieldLabel: 'Pilih',
												displayField: 'NmKantor',
												valueField: 'KdKantor',
												name:'rule',
												width: 450,
												labelWidth: 125,
												store: kdkantorstore,
												allowBlank: false,
												typeAhead:true,
												anyMatch: true,
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
												id: 'startdt_approval_mppa',
												vtype: 'date',
												dateFormat: 'd-m-Y',
												altFormats: 'd-m-Y',
												allowBlank: false
											}, {
												fieldLabel: 'End Date',
												name: 'enddt',
												id: 'enddt_approval_mppa',
												vtype: 'date',
												dateFormat: 'd-m-Y',
												altFormats: 'd-m-Y',
												allowBlank: false
											}, {
												xtype: 'button',
												text: 'TAMPILKAN DATA',
												style: 'padding:5px;',
												handler: function(r){
													listmppastore.proxy.extraParams = {
														nama:Ext.getCmp('combokdkantor_approval_mppa').getRawValue(),
														kodekantor:Ext.getCmp('combokdkantor_approval_mppa').getValue(),
														startdate:Ext.getCmp('startdt_approval_mppa').getSubmitValue(),
														enddate:Ext.getCmp('enddt_approval_mppa').getSubmitValue(),
														//total:200
													};
													listmppastore.load();
													Ext.getCmp('listmppagrid').getStore().load();
												}
											}]
									},{
									xtype: 'fieldset',
									title: 'Daftar Data Approval MPPA',
									defaultType: 'datefield', 
									layout: 'anchor',
									items: [										
										Ext.create('Ext.grid.Panel', {
											style: 'margin-top:5px; width: 100%; height:100%;',
											id:'listmppagrid',
											store: listmppastore,
											columns: [
											{
												header: 'Id Dokumen',
												dataIndex: 'iddokumen',
												flex: 1
											}, {
												header: 'Kantor',
												dataIndex: 'kantor',
												flex: 1
											}, {
												header: 'Prog',
												dataIndex: 'prog',
												flex: 1
											}, {
												header: 'Tgl Ajuan',
												dataIndex: 'tglajuan',
												flex: 1
											}, {
												header: 'Jenis',
												dataIndex: 'jenis',
												flex: 1
											}, {
												header: 'Mata Anggaran',
												dataIndex: 'mataanggaran',
												flex: 1
											}, {
												header: 'Jml Ajuan',
												dataIndex: 'jmlajuan',
												flex: 1
											}, {
												header: 'Keterangan',
												dataIndex: 'keterangan',
												flex: 1
											}],
											height: 300,
											dockedItems: [{
												xtype: 'pagingtoolbar',
												store: listmppastore,
												dock: 'bottom',
												displayInfo: true
											}]
										})
									]
								}],
									buttons: [{
										text: 'APPROVE DATA',
										style: 'padding:5px;',
										handler: function() {
																						
										}
									}]
								}),						
							]
						}).show();
					} else {
						tabcenter.add({
							closable: true,
							id:formid,
							style: 'width: 100%; height:100%;',
							iconCls: 'tabs',
							title: formtext,
							html:'<iframe src="<?=$coreform;?>'+formid+'.bpjstk"  height="100%" width="100%" frameborder="0"  style="border:0; height:100%; width:100%;"></iframe>',
							/*
							loader: {
								url: 'form/form.php?form=/data/jms/SIPT/KP/FORM/KP7710&p_user_id=RO158820&p_kode_kantor=0',
								contentType: 'html',
								autoLoad: true,
								params: ''
							}*/
						}).show();
					}	
					
				}
			}
		}
		
		
		var tabcenter = Ext.create('Ext.tab.Panel', {
                region: 'center', 
				resizeTabs: true,
        		enableTabScroll: true,
                activeTab: 0,     
				defaults: {
					bodyPadding: 10,
					autoScroll: true
				},
                items: [{
                    title: 'Dashboard',
					id:'dashboard',
                    autoScroll: true,
					iconCls:'tabsdashboard',
					items : [{
						xtype : "component",
						contentEl: 'welcome',
					}],
                }],
				plugins: Ext.create('Ext.ux.TabCloseMenu', {
					extraItemsTail: [
						'-',
						{
							text: 'Closable',
							checked: true,
							hideOnClick: true,
							handler: function (item) {
								currentItem.tab.setClosable(item.checked);
							}
						},
						'-',
						{
							text: 'Enabled',
							checked: true,
							hideOnClick: true,
							handler: function(item) {
								currentItem.tab.setDisabled(!item.checked);
							}
						}
					],
					listeners: {
						beforemenu: function (menu, item) {
							var enabled = menu.child('[text="Enabled"]'); 
							menu.child('[text="Closable"]').setChecked(item.closable);
							if (item.tab.active) {
								enabled.disable();
							} else {
								enabled.enable();
								enabled.setChecked(!item.tab.isDisabled());
							}
		
							currentItem = item;
						}
					}
				})
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
                html: '<a href="logout.bpjs" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;"><img src="resources/images/logout.png" alt="Logout Aplikasi"></a>',
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
                width: 300,
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
				{name:'kodeFungsi', type:'int'},
				{name:'namaFungsi', type:'string'},
				{name:'kodeKantor', type:'string'}
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
				url: 'act/pilihrole.bpjs',
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
				url: 'act/kdkantorlist.bpjs',
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
				typeAhead:true,
				anyMatch: true,
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
				handler: function(btn) {
					Ext.create('Ext.window.Window', {
						title: 'View Report',
						collapsible: true,
						animCollapse: true,
						maximizable: true,
						width: 1125,
						height: 500,
						minWidth: 450,
						minHeight: 200,
						stateful: true,
						layout: 'fit',
						loader: {
							url: 'act/report.php?nama=penerimaan_iuran&param='+Ext.getCmp('combokdkantor').getValue()+'&tgl_from='+Ext.getCmp('startdt').getSubmitValue()+'&tgl_to='+Ext.getCmp('enddt').getSubmitValue()+'&jenis='+Ext.ComponentQuery.query('[name=jenis_channel]')[0].getGroupValue(),
							contentType: 'html',
							autoLoad: true,
						},
						listeners: {
							destroy: function(){
								btn.enable();
							}
						}
					}).show();
					btn.disable();					
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
							displayField: 'namaFungsi',
							valueField: 'kodeFungsi',
							value:'Pilih...',
							name:'rule',
							width: 420,
							labelWidth: 60,
							store: rolestore,
							margin:10,
							queryMode: 'local',
							anyMatch: true,
							listConfig: {
								getInnerTpl: function() {
									return '<div data-qtip="{kodeFungsi}">{namaFungsi}</div>';
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
						  url: 'act/setrule.bpjs',
						  success:function(data){ 
							  Ext.notify.msg('Success', 'Rule yang dipilih adalah <strong>'+Ext.getCmp('combox').getRawValue()+'</strong>');
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
							  treepanel.reconfigure(menustore);
							  win.close();          
						  },
						  failure:function(form, result){
							  Ext.notify.msg('Gagal', 'Gagal memilih rule.');	
						  }
							
						});
						
							
						if (form.isValid()) {
							var out = [];
							Ext.Object.each(form.getValues(), function(key, value){
								out.push(key + '=' + value);
							});
						}
					}
				},
				{ text:'Batal',
				  handler: function() {
					  window.location='logout.bpjs';
				  }
				}
			]
        })
    });
	win.show();
    function showResultText(btn, text){
		Ext.notify.msg('Notifikasi', 'Rule yang dipilih adalah <strong>"{1}"</strong>.', btn, text);
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