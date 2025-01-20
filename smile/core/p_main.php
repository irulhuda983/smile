<?PHP
session_start();
include('includes/connsql.php');

$username	 	= $_SESSION["USER"];
$login["user"] 		= str_replace("'", "`", $_SESSION['NAMA']);
$login["kantor"] 	= $_SESSION['KANTOR'];
$login["kdkantor"]	= $_SESSION['KDKANTOR'];
$login["npk"] 		= $_SESSION['NPK'];
$login["email"] 	= $_SESSION['EMAIL'];
$login["ip"] 		= $_SESSION['IP'];
//$login["foto"] = "http://172.28.201.81/hcis/sunfish5upload/ehrm/photo/".$login["npk"].".jpg";
$login["foto"] = "http://hcis.bpjsketenagakerjaan.go.id/hcis/sunfish5upload/ehrm/photo/".$login["npk"].".jpg";
if(( time() - $_SESSION['LOGIN_AT'] > 120*60) || ($_SESSION["STATUS"] != "LOGIN")){
	echo "<script>window.location='logout.bpjs?error=Silahkan Login ulang';</script>";
}
$notifikasi = 5;
//echo time() - $_SESSION['LOGIN_AT'];
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
				animate:true,
				loadingText:'Loading',
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
						bukaForm(r.data.leaf,r.data.id,r.data.text,window.kodeFungsi);
					}
				},
				viewConfig: {
					plugins: {
						ptype: 'treeviewdragdrop'
					},
					listeners: {
						viewready: function (tree) {
							var view = treepanel.getView(),
								dd = view.findPlugin('treeviewdragdrop');
							
							dd.dragZone.onBeforeDrag = function (data, e) {
								var rec = view.getRecord(e.getTarget(view.itemSelector));
								return rec.isLeaf();
							};
						},
						beforedrop: function(s,r,c,m,n)
						{
							if((c.internalId == '00000') || (c.internalId == 'F00000')){
								Ext.Ajax.request({
									url: 'act/addfav.bpjs?menuid=' + r.records[0].internalId+'&target='+c.internalId+'&com=Y&role='+window.kodeFungsi,
									success: function(data) {
										console.log('Success add '+c.data.text+' as Favorite Menu');
										Ext.notify.msg('Success', 'Success add <strong>'+c.data.text+'</strong> as Favorite Menu');
										treepanel.getStore().reload({
											callback:function(){
												var me = this,
													node = me.getNodeById('00000'); //favorite id
												console.log(node.getPath());
												treepanel.expandPath(node.getPath());
											}
										});
									}
								});
							} else {
								window.idmenudel = r.records[0].internalId;
								window.idmenutrg = c.internalId;
								window.menuname  = c.data.text;
								function yaHapus(btn){
									if(btn[0] == 'y'){
										Ext.Ajax.request({
											url: 'act/addfav.bpjs?menuid=' + window.idmenudel+'&target='+window.idmenutrg+'&com=T&role='+window.kodeFungsi,
											success: function() {
												console.log('Success add '+window.menuname+' as Favorite Menu');
												treepanel.getStore().reload({
													callback:function(){
														var me = this,
															node = me.getNodeById('00000'); //favorite id
														console.log(node.getPath());
														treepanel.expandPath(node.getPath());
													}
												});
											}
										});
									} else {
										console.log('Cancel remove menu '+window.menuname+' as Favorite Menu');
										treepanel.getStore().reload({
											callback:function(){
												var me = this,
													node = me.getNodeById('00000'); //favorite id
												console.log(node.getPath());
												treepanel.expandPath(node.getPath());
											}
										});
									}
								}
								Ext.MessageBox.confirm('Confirm', 'Apakah anda ingin menghapus menu <strong>'+window.menuname+'</strong> dari Favorite?', yaHapus);
								
								
								//return false;
							}
						}
					}
				}		
				
		})
		var itemsPerPage = 10,
        nama = 'MPPA';
        //total = 0; // records to return
		
		
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
		
		function bukaForm(formleaf, formid, formtext, formfungsi){
			var checkTab = tabcenter.getComponent(formid);
			if (checkTab) {
                tabcenter.setActiveTab(checkTab);
            } else {
				if (formleaf) {
					if(formid != "F00000"){ //default node fav menu
						/*
						if ( formid.indexOf('mod_sipa/alur_penyusunan_anggaran') > -1 ) {
							fullpop('f.php?kodeFungsi='+formfungsi+'&kodeMenu='+formid, 'Alur Penyusunan Anggaran');
						}
						else*/
						if ( formid.indexOf('http') > -1 ) {
							openwindow('f.php?kodeFungsi='+formfungsi+'&kodeMenu='+formid);
						} else {
							tabcenter.add({
								closable: true,
								id:formid,
								style: 'width: 100%; height:100%;',
								iconCls: 'tabs',
								title: formtext,
								loader: {
									url: 'f.php?kodeFungsi='+formfungsi+'&kodeMenu='+formid,
									contentType: 'html',
									autoLoad: true,
									params: ''
								}
							}).show();
						}
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
		var topmenu = Ext.create('Ext.menu.Menu', {
			width: 100,
			margin: '0 0 10 0',
			floating: false,  // usually you want this set to True (default)
			items: [{
				text: 'regular item 1'
			},{
				text: 'regular item 2'
			},{
				text: 'regular item 3'
			}]
		});
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
                html: '<a href="logout.bpjs" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;"><img src="resources/images/logout.png" title="Logout Aplikasi"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:location.reload();" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;"><img src="resources/images/pilihmenu.png" title="Pilih Role"></a>&nbsp;&nbsp;&nbsp;<a href="#" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;" onClick="notifikasi(\'core123/mod_setup/form/setup_kantor.php\');"><img src="resources/images/notifikasi.png" title="Notifikasi"><span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">0</span></a>',
				
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
                    { type:'refresh',
					handler: function(s) {
							var activeTab = tabcenter.getActiveTab();
							var activeTabIndex = tabcenter.items.findIndex('id', activeTab.id);
							//window.alert(activeTab);
							//Ext.getCmp(activeTabIndex).getUpdater().refresh();
							//tabPanel.items.findIndex('id', activeTab.id).getUpdater().refresh();
						}
					}
                ],
            }, {
                region: 'west',
                stateId: 'navigation-panel',
                id: 'west-panel', // see Ext.getCmp() below
                title: 'Navigation',
                split: true,
                width: 270,
                minWidth: 175,
                collapsible: true,
                animCollapse: true,
                margins: '0 0 0 5',
                items: [
					{
						id:'profil',
						html:'<div style="margin:10px; width:100%;"><div style="float:left; width:74px; text-align:center;"><img src="<?=$login["foto"];?>" style="border:solid 2px #157FCC; width:70px;"><strong><?=$login["npk"];?></strong></div><div style="float:left; width: 180px; margin-top: -7px;"><p><strong>Login Sebagai:</strong><br><?=$login["user"];?></p><p><strong>Role:</strong><br>{role}</p></div></div>',
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
				{name:'kodeFungsiKantor', type:'string'},
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
		
        win = new Ext.Window(
    {
        layout: 'fit',
		title:'Pilih Role Aplikasi',
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
						fieldLabel: 'Selamat datang user: <?=$login["user"];?>, Anda melakukan koneksi ke Aplikasi SIJSTK dari <?=$login["ip"];?>',
						labelStyle: 'font-weight:bold;padding:0',
						labelWidth: 400,
						layout: 'hbox',
						},
						Ext.create('Ext.form.field.ComboBox', {
							id:'combox',
							fieldLabel: 'Pilih',
							displayField: 'namaFungsi',
							valueField: 'kodeFungsiKantor',
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
						  waitTitle:'Connecting', 
						  waitMsg:'Creating menu...',
						  url: 'act/setrule.bpjs?role='+Ext.getCmp('combox').getValue()+'&rolename='+Ext.getCmp('combox').getRawValue(),
						  success:function(form, action) {
							  var kdrole	= Ext.getCmp('combox').getValue(); 
						  	  var kdrolevar = kdrole.split(":");
							  window.kodeFungsi = kdrolevar[0];
							  window.kodeKantor = kdrolevar[1];
							  window.totalnotif = action.result.notiftotal;
							  window.linknotif = action.result.notifurl;
							  var apphe = Ext.get("app-header");
							  var apphel = '<a href="logout.bpjs" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;"><img src="resources/images/logout.png" title="Logout Aplikasi"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:location.reload();" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;"><img src="resources/images/pilihmenu.png" title="Pilih Role"></a>&nbsp;&nbsp;&nbsp;<a href="#" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;" onClick="notifikasi(\''+window.linknotif+'\');"><img src="resources/images/notifikasi.png" title="Notifikasi"><span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">'+window.totalnotif+'</span></a>';
							  Ext.notify.msg('Success', 'Role yang dipilih adalah <strong>'+Ext.getCmp('combox').getRawValue()+'dan kodenya adalah '+window.kodeFungsi+'</strong>');
							  var el = Ext.get("profil");
							  var prp = '<div style="margin:10px; width:100%;"><div style="float:left; width:74px; text-align:center;"><img src="<?=$login["foto"];?>" style="border:solid 2px #157FCC; width:70px;"><strong><?=$login["npk"];?></strong></div><div style="float:left; width:180px; margin-top: -7px;"><p><strong>Login Sebagai:</strong><br><?=$login["user"];?></p><p><strong>Role:</strong><br>'+Ext.getCmp('combox').getRawValue()+'</p></div></div>';
							  el.setHTML(prp);
							  apphe.setHTML(apphel);
							  
							  var menustore = Ext.create('Ext.data.TreeStore', {
									root: {
										expanded: true
									},
									proxy: {
										type: 'ajax',
										url: 'act/menutree.bpjs?role='+action.result.rolenum
									}
							  });
							  treepanel.reconfigure(menustore);
							  win.close(); 
						  },
						  failure:function(form, action){
							  Ext.Msg.alert('ALERT',action.result.errors);
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
		Ext.notify.msg('Notifikasi', 'Role yang dipilih adalah <strong>"{1}"</strong>.', btn, text);
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
	function notifikasi(alamat){
		if(alamat != ''){
			var openwin = Ext.create('Ext.window.Window', {
				title: 'Notifikasi',
				collapsible: true,
				animCollapse: true,

				maximizable: true,
				width: 800,
				height: 500,
				minWidth: 450,
				minHeight: 200,
				layout: 'fit',
				html:'<iframe src="<?=$coreform;?>'+alamat+'"  height="100%" width="100%" frameborder="0"  style="border:0; height:100%; width:100%;"></iframe>',
				/*
				loader: {
					url: alamat,
					contentType: 'html',
					autoLoad: true,
				}*/
			});
			openwin.show();
		}
	}
	function fullpop(alamat,title){
		if(alamat != ''){
			var openwin = Ext.create('Ext.window.Window', {
				/*title: title,*/
				collapsible: false,
				animCollapse: true,
				maximizable: false,
				height: document.documentElement.clientHeight,    
    			width: document.documentElement.clientWidth,
				minWidth: 450,
				minHeight: 200,
				layout: 'fit',
				modal: true,
				html:'<iframe src="'+alamat+'"  height="100%" width="100%" frameborder="0"  style="border:0; height:100%; width:100%;"></iframe>',
				/*
				loader: {
					url: alamat,
					contentType: 'html',
					autoLoad: true,
				}*/
			});
			openwin.show();
		}
	}
	function openwindow(alamat){
		window.open(alamat);
	}
	
    </script>
</head>
<body>
    <div id="welcome" class="x-hide-display">
       <img src="resources/images/home.jpg" class="welcome-img">
    </div>
</body>
</html>
