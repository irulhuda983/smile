<?PHP
// session_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
 }
include_once ('includes/connsql.php');
include_once "includes/conf_global.php";
include_once "includes/class_database.php";
$DB = new Database($gs_DBUser, $gs_DBPass, $gs_DBName);

$username 			= $_SESSION["USER"];
$login["user"] 		= str_replace("'", "`", $_SESSION['NAMA']);
$login["kantor"] 	= $_SESSION['KANTOR'];
$login["kdkantor"] 	= $_SESSION['KDKANTOR'];
$login["npk"] 		= $_SESSION['NPK'];
$login["email"] 	= $_SESSION['EMAIL'];
$login["ip"] 		= $_SESSION['IP'];
$login["tgl_expired_passwd"] 	= $_SESSION["TGL_EXPIRED_PASSWD"];
$login["change_passwd_in_days"]	= $_SESSION["CHANGE_PASSWD_IN_DAYS"];

// define PARAMETER BLINK WARNA MERAH INFORMASI EXPIRED PASSWORD DALAM HARI, default 3
$ls_blink_expired_pwd = 0;
$sql = "SELECT NVL(MAX(KATEGORI),0) JML_KODE_TIPE FROM MS.MS_LOOKUP WHERE TIPE = 'LGNSESSION' AND KODE = 'LGNSESSION_BLINK_EXPIRED_PWD' AND ROWNUM = 1 ";
$DB->parse($sql);
if($DB->execute()){
	$row = $DB->nextrow();
	$ls_blink_expired_pwd = $row['JML_KODE_TIPE'];
	if($ls_blink_expired_pwd == 0)
	{
		$ls_blink_expired_pwd = 3;
	}
}else{
	$ls_blink_expired_pwd = 3;
}

// define PARAMETER MASA TUNGGU SESSION JIKA IDLE TIME DALAM DETIK, default 7200
$ls_session_idle_time_out = 0;
$sql = "SELECT NVL(MAX(KATEGORI),0) JML_KODE_TIPE FROM MS.MS_LOOKUP WHERE TIPE = 'LGNSESSION' AND KODE = 'LGNSESSION_IDLE_TIME_OUT' AND ROWNUM = 1 ";
$DB->parse($sql);
if($DB->execute()){
	$row = $DB->nextrow();
	$ls_session_idle_time_out = $row['JML_KODE_TIPE'];
	if($ls_session_idle_time_out == 0)
	{
		$ls_session_idle_time_out = 7200;
	}
}else{
	$ls_session_idle_time_out = 7200;
}

if($login["change_passwd_in_days"] <= $ls_blink_expired_pwd)
{
	$login["info_login"] = "<blink><font color=red>Password expired dalam <b>".$login["change_passwd_in_days"]."</b> hari, <br>tanggal ". $login["tgl_expired_passwd"]. "</font></blink>";
}
else
{
	$login["info_login"] = "Password expired dalam <b>".$login["change_passwd_in_days"]."</b> hari, <br>tanggal ". $login["tgl_expired_passwd"];
}


//$login["foto"] = "http://172.28.201.81/hcis/sunfish5upload/ehrm/photo/" . $login["npk"] . ".jpg";
$login["foto"] = "http://hcis.bpjsketenagakerjaan.go.id/hcis/sunfish5upload/ehrm/photo/" . $login["npk"] . ".jpg";

if (( time() - $_SESSION['LOGIN_AT'] > $ls_session_idle_time_out) || ($_SESSION["STATUS"] != "LOGIN")) {
    echo "<script>window.location='logout.bpjs?error=Silahkan Login ulang';</script>";
}

$notifikasi = 5;
//echo time() - $_SESSION['LOGIN_AT'];
//add @aryaduta 25 agustus 2017 by zimmy---utk blasting informasi via sijstk
// get informasi

$sql = "select replace(ket_info,'''','') ket_info,warna_font from sijstk.ms_info where status_info='Y' and rownum=1";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_info = $row["KET_INFO"];
$ls_warna_font = $row["WARNA_FONT"];

//$ls_info = '';
//$ls_warna_font = '';

$p_info = '<div class="info_bpjs"><h3>' . $ls_info . ' <span id="text_notif"> </span> </h3></div>';
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title><?= $appname; ?></title>
		<link href="images/favicon.png" rel="shortcut icon" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="portal.css" />
        <!-- //add @aryaduta 25 agustus 2017 by zimmy---utk blasting informasi via sijstk -->
        <!-- Styles -->
        <style style="text/css">
            .info_bpjs {
                height: 35px;
                width: 4000px;
                overflow: hidden;
                position: top;
            }
            .info_bpjs h3 {
                font-size: 1.5em;
                color: <?= $ls_warna_font; ?>;
                position: top;
                width: 100%;
                height: 100%;
                margin: 0;
                line-height: 20px;
                text-align: top;
                /* posisi start */
                -moz-transform:translateX(100%);
                -webkit-transform:translateX(100%);
                transform:translateX(100%);
                /* Apply animasi ke elemen pesan */
                -moz-animation: info_bpjs 30s linear infinite;
                -webkit-animation: info_bpjs 30s linear infinite;
                animation: info_bpjs 30s linear infinite;
            }
            /* pergerakan (sesuai animasi pesan) */
            @-moz-keyframes info_bpjs {
                0%   { -moz-transform: translateX(100%); }
                100% { -moz-transform: translateX(-100%); }
            }
            @-webkit-keyframes info_bpjs {
                0%   { -webkit-transform: translateX(100%); }
                100% { -webkit-transform: translateX(-100%); }
            }
            @keyframes info_bpjs {
                0%   {
                    -moz-transform: translateX(100%);
                    -webkit-transform: translateX(100%);
                    transform: translateX(100%);
                }
                100% {
                    -moz-transform: translateX(-100%);
                    -webkit-transform: translateX(-100%);
                    transform: translateX(-100%);
                }
            }

			blink {
				-webkit-animation: 0.6s linear infinite condemned_blink_effect; // for android
				animation: 0.6s linear infinite condemned_blink_effect;
			}
			@-webkit-keyframes condemned_blink_effect { // for android
				0% {
					visibility: hidden;
				}
				50% {
					visibility: hidden;
				}
				100% {
					visibility: visible;
				}
			}
			@keyframes condemned_blink_effect {
				0% {
					visibility: hidden;
				}
				50% {
					visibility: hidden;
				}
				100% {
					visibility: visible;
				}
			}
        </style>

        <script type="text/javascript" src="shared/include-ext.js"></script>
        <script type="text/javascript" src="shared/options-toolbar.js"></script>
        <script type="text/javascript">
            Ext.require(['*']);

            Ext.onReady(function () {

                Ext.QuickTips.init();

                new Ext.util.KeyMap(document.body, {
                    key: 's',
                    shift: true,
                    fn: function (keycode, e) {
                        e.stopEvent();
                        console.log('shift + s was pressed');
                    }
                });
                new Ext.util.KeyMap(document.body, {
                    key: 'f',
                    shift: true,
                    fn: function (keycode, e) {
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
                    region: 'north',
                    split: true,
                    height: 380,
                    minSize: 150,
                    animate: true,
                    loadingText: 'Loading',
                    rootVisible: false,
                    autoScroll: true,
                    store: menustore,
                    iconCls: 'nav',
                    tools: [
                        {type: 'search',
                            tooltip: 'Cari Form / Report',
                            handler: function (s) {
                                Ext.MessageBox.prompt('Buka Form', 'Silahkan masukan kode Form/Report:', bukaForm);
                            }
                        }
                    ],
                    listeners: {
                        itemclick: function (s, r) {
                            bukaForm(r.data.leaf, r.data.id, r.data.text, window.kodeFungsi);
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
                            beforedrop: function (s, r, c, m, n)
                            {
                                if ((c.internalId == '00000') || (c.internalId == 'F00000')) {
                                    Ext.Ajax.request({
                                        url: 'act/addfav.bpjs?menuid=' + r.records[0].internalId + '&target=' + c.internalId + '&com=Y&role=' + window.kodeFungsi,
                                        success: function (data) {
                                            console.log('Success add ' + c.data.text + ' as Favorite Menu');
                                            Ext.notify.msg('Success', 'Success add <strong>' + c.data.text + '</strong> as Favorite Menu');
                                            treepanel.getStore().reload({
                                                callback: function () {
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
                                    window.menuname = c.data.text;
                                    function yaHapus(btn) {
                                        if (btn[0] == 'y') {
                                            Ext.Ajax.request({
                                                url: 'act/addfav.bpjs?menuid=' + window.idmenudel + '&target=' + window.idmenutrg + '&com=T&role=' + window.kodeFungsi,
                                                success: function () {
                                                    console.log('Success add ' + window.menuname + ' as Favorite Menu');
                                                    treepanel.getStore().reload({
                                                        callback: function () {
                                                            var me = this,
                                                                    node = me.getNodeById('00000'); //favorite id
                                                            console.log(node.getPath());
                                                            treepanel.expandPath(node.getPath());
                                                        }
                                                    });
                                                }
                                            });
                                        } else {
                                            console.log('Cancel remove menu ' + window.menuname + ' as Favorite Menu');
                                            treepanel.getStore().reload({
                                                callback: function () {
                                                    var me = this,
                                                            node = me.getNodeById('00000'); //favorite id
                                                    console.log(node.getPath());
                                                    treepanel.expandPath(node.getPath());
                                                }
                                            });
                                        }
                                    }
                                    Ext.MessageBox.confirm('Confirm', 'Apakah anda ingin menghapus menu <strong>' + window.menuname + '</strong> dari Favorite?', yaHapus);


                                    //return false;
                                }
                            }
                        }
                    }

                })
                var itemsPerPage = 10,
                        nama = 'MPPA';
                //total = 0; // records to return


                function bukaWindow(judul, myurl) {
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

                function bukaForm(formleaf, formid, formtext, formfungsi) {
                    var checkTab = tabcenter.getComponent(formid);
                    if (checkTab) {
                        tabcenter.setActiveTab(checkTab);
                    } else {
                        if (formleaf) {
                            if (formid != "F00000") { //default node fav menu
                                /*
                                 if ( formid.indexOf('mod_sipa/alur_penyusunan_anggaran') > -1 ) {
                                 fullpop('f.php?kodeFungsi='+formfungsi+'&kodeMenu='+formid, 'Alur Penyusunan Anggaran');
                                 }
                                 else*/
                                if (formid.indexOf('http') > -1) {
                                    openwindow('f.php?kodeFungsi=' + formfungsi + '&kodeMenu=' + formid);
                                } else {
                                    tabcenter.add({
                                        closable: true,
                                        id: formid,
                                        style: 'width: 100%; height:100%;',
                                        iconCls: 'tabs',
                                        title: formtext,
                                        loader: {
                                            url: 'f.php?kodeFungsi=' + formfungsi + '&kodeMenu=' + formid,
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
                    id: 'panel-tab-content',
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
                            id: 'dashboard',
                            autoScroll: true,
                            iconCls: 'tabsdashboard',
							loader: {
								url: 'dashboard.php',
								contentType: 'html',
								autoLoad: true,
								params: ''
							}
							/*
                            items: [{
                                    xtype: "component",
                                    contentEl: 'welcome',
                                }],
								*/
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
                                handler: function (item) {
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
				var activeTab = tabcenter.getActiveTab();
				var test = activeTab.id;
				var activeTabIndex = tabcenter.items.findIndex('id', activeTab.id);

				console.log(activeTab.id);
				//tabcenter.update(test);
				/*
				tabcenter.getUpdater().update({
					url: 'dashboard.php',
					contentType: 'html',
					scripts: true
				});
				*/

				//tabcenter.getActiveTab().getLoader().load();
                Ext.state.Manager.setProvider(Ext.create('Ext.state.CookieProvider'));
                var topmenu = Ext.create('Ext.menu.Menu', {
                    width: 100,
                    margin: '0 0 10 0',
                    floating: false, // usually you want this set to True (default)
                    items: [{
                            text: 'regular item 1'
                        }, {
                            text: 'regular item 2'
                        }, {
                            text: 'regular item 3'
                        }]
                });
                var viewport = Ext.create('Ext.Viewport', {
                    id: 'app-viewport',
                    layout: {
                        type: 'border',
                        padding: '5 0 0 0',
                        align: "middle"
                    },
                    items: [
                        // create instance immediately
                        Ext.create('Ext.Component', {
                            id: 'app-header',
                            xtype: 'hbox',
                            region: 'north',
                            height: 65, // give north and south regions a height
                            html: '<a href="logout.bpjs" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;"><img src="resources/images/logout.png" title="Logout Aplikasi"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:location.reload();" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;"><img src="resources/images/pilihmenu.png" title="Pilih Role"></a>&nbsp;&nbsp;&nbsp;<a href="downloads/help.pdf" target="_blank" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;" onclick="javascript:void(0);"><img src="resources/images/help.png" title="Bantuan"></a>&nbsp;&nbsp;&nbsp;<a href="#" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;" onClick="notifikasi(\'core123/mod_setup/form/setup_kantor.php\');"><img src="resources/images/notifikasi.png" title="Notifikasi"></a>',
                        }), {
                            // lazily created panel (xtype:'panel' is default)
                            region: 'south',
                            split: false,
                            height: 35,
                            minSize: 100,
                            maxSize: 200,
                            collapsible: false,
                            collapsed: false,
                            title: '<?= $p_info; ?>',
                            margins: '0 0 0 0',
                            tools: [
                                {type: 'refresh',
                                    handler: function (s) {
                                        var activeTab = tabcenter.getActiveTab();
                                        var activeTabIndex = tabcenter.items.findIndex('id', activeTab.id);
                                        //window.alert(activeTab);
                                        Ext.getCmp(activeTabIndex).getUpdater().refresh();
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
                                    id: 'profil',
                                    html: '<div style="margin:10px; width:100%;"><div style="float:left; width:74px; text-align:center;"><img src="<?= $login["foto"]; ?>" style="border:solid 2px #157FCC; width:70px;"><strong><?= $login["npk"]; ?></strong></div><div style="float:left; width: 180px; margin-top: -7px;"><p><strong>Login Sebagai:</strong><br><?= $login["user"]; ?></p><p><strong>Role:</strong><br>{role}</p></div></div>',
                                    height: 200,
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
                        {name: 'kodeFungsi', type: 'int'},
                        {name: 'kodeFungsiKantor', type: 'string'},
                        {name: 'namaFungsi', type: 'string'},
                        {name: 'kodeKantor', type: 'string'}
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
                        noCache: false,
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
                            title: 'Pilih Role Aplikasi',
                            width: 500,
                            height: 200,
                            modal: true,
                            closable: false,
                            items: new Ext.create('Ext.form.Panel',
                                    {
                                        margin: 10,
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
                                                        id: 'pilihrole',
                                                        fieldLabel: 'Selamat datang user: <?= $login["user"]; ?>, Anda melakukan koneksi ke Aplikasi SMILE dari <?= $login["ip"]; ?>',
                                                        labelStyle: 'font-weight:bold;padding:0',
                                                        labelWidth: 400,
                                                        layout: 'hbox',
                                                    },
                                                    Ext.create('Ext.form.field.ComboBox', {
                                                        id: 'combox',
                                                        fieldLabel: 'Pilih',
                                                        displayField: 'namaFungsi',
                                                        valueField: 'kodeFungsiKantor',
                                                        value: 'Pilih...',
                                                        name: 'rule',
                                                        width: 420,
                                                        labelWidth: 60,
                                                        store: rolestore,
                                                        margin: 10,
                                                        queryMode: 'local',
                                                        anyMatch: true,
                                                        listConfig: {
                                                            getInnerTpl: function () {
                                                                return '<div data-qtip="{kodeFungsi}">{namaFungsi}</div>';
                                                            }
                                                        }
                                                    })
                                                ]
                                            }
                                        ],
                                        buttons: [
                                            {
                                                text: 'Pilih',
                                                fn: showResultText,
                                                handler: function () {
                                                    var form = this.up('form').getForm();
                                                    var out = [];
                                                    Ext.Object.each(form.getValues(), function (key, value) {
                                                        out.push(value);
                                                    });

                                                    form.submit({
                                                        method: 'POST',
                                                        waitTitle: 'Connecting',
                                                        waitMsg: 'Creating menu...',
                                                        url: 'act/setrule.bpjs?role=' + Ext.getCmp('combox').getValue() + '&rolename=' + Ext.getCmp('combox').getRawValue(),
                                                        success: async function (form, action) {
                                                            var kdrole = Ext.getCmp('combox').getValue();
                                                            var kdrolevar = kdrole.split(":");
                                                            window.kodeFungsi = kdrolevar[0];
                                                            window.kodeKantor = kdrolevar[1];
                                                            window.totalnotif = action.result.notiftotal;
                                                            window.linknotif = action.result.notifurl;
                                                            var apphe = Ext.get("app-header");

                                                            var apphel = '<a href="logout.bpjs" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;"><img src="resources/images/logout.png" title="Logout Aplikasi"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:location.reload();" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;"><img src="resources/images/pilihmenu.png" title="Pilih Role"></a>&nbsp;&nbsp;&nbsp;<a href="downloads/user_manual.pdf" target="_blank" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;" onclick="javascript:void(0);"><img src="resources/images/help.png" title="Bantuan"></a>&nbsp;&nbsp;&nbsp;<a href="#" style="margin-top:10px;margin-right:10px;float:right;cursor:pointer;" onClick="showNotification()"><img src="resources/images/notifikasi.png" title="Notifikasi"><div id="notification-flag"></div></a>';
                                                            Ext.notify.msg('Success', 'Role yang dipilih adalah <strong>' + Ext.getCmp('combox').getRawValue() + 'dan kodenya adalah ' + window.kodeFungsi + '</strong>');
                                                            var el = Ext.get("profil");
                                                            var prp = '<div style="height:50%"><div style="margin:10px; width:100%;"><div style="float:left; width:74px; text-align:center;"><img src="<?= $login["foto"]; ?>" style="border:solid 2px #157FCC; width:70px;"><strong><?= $login["npk"]; ?></strong></div><div style="float:left; width:180px; margin-top: -7px;"><p><strong>Login Sebagai:</strong><br><?= $login["user"]; ?></p><p><strong>Role:</strong><br>' + Ext.getCmp('combox').getRawValue() + '</p></div></div></div><div style="height:50%;margin-top:-10px;"><p><strong>Email:</strong><br><?= $login["email"]; ?></p><p><strong>Informasi Login:</strong><br><?= $login["info_login"]; ?></p></div>';
                                                            el.setHTML(prp);
                                                            apphe.setHTML(apphel);

                                                            var menustore = Ext.create('Ext.data.TreeStore', {
                                                                root: {
                                                                    expanded: true
                                                                },
                                                                proxy: {
                                                                    type: 'ajax',
                                                                    url: 'act/menutree.bpjs?role=' + action.result.rolenum
                                                                }
                                                            });
                                                            treepanel.reconfigure(menustore);
															tabcenter.getActiveTab().getLoader().load();
                                                            win.close();
                                                            var valKodeFungsi = kdrole.split("|")[0];
                                                            var valKodeKantor = kdrole.split("|")[1];

                                                            if(valKodeFungsi=="401" || valKodeFungsi=="402" || valKodeFungsi=="404" || valKodeFungsi=="405" || valKodeFungsi=="406" || valKodeFungsi=="408" || valKodeFungsi=="409" || valKodeFungsi=="411"){
                                                                popUpNotifikasiEproc(valKodeFungsi);
                                                            }else if(valKodeFungsi == "3"){
                                                                await getNotification('cso',valKodeKantor);
                                                                let interval = await getInterval();

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                            getNotification('cso',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }else if(valKodeFungsi == "6"){
                                                                await getNotification('kbl',valKodeKantor);
                                                                let interval = await getInterval();

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                            getNotification('kbl',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }else if(valKodeFungsi == "4"){
                                                                await getNotification('pmp',valKodeKantor);
                                                                let interval = await getInterval();

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                            getNotification('pmp',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }else if(valKodeFungsi == "25"){
                                                                await getNotification('kakcp',valKodeKantor);
                                                                let interval = await getInterval();

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                            getNotification('kakcp',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }else if(valKodeFungsi == "27"){
                                                                await getNotification('pmpu',valKodeKantor);
                                                                let interval = await getInterval();

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                            getNotification('pmpu',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }else if(valKodeFungsi == "8"){
                                                                await getNotification('ro',valKodeKantor);
                                                                let interval = await getInterval();

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                            getNotification('ro',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }else if(valKodeFungsi == "7"){
                                                                await getNotification('mo',valKodeKantor);
                                                                let interval = await getInterval();

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                            getNotification('mo',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }else if(valKodeFungsi == "10"){
                                                                await getNotification('kbp',valKodeKantor);
                                                                let interval = await getInterval();

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                            getNotification('kbp',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }else if(valKodeFungsi == "79"){
                                                                await getNotification('kbpbpu',valKodeKantor);
                                                                let interval = await getInterval();

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                            getNotification('kbpbpu',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }else if(valKodeFungsi == "15"){
                                                                await getNotification('kakacab',valKodeKantor);
                                                                let interval = await getInterval();

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                            getNotification('kakacab',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }else if(valKodeFungsi == "13"){
                                                                await getNotification('kbkeu',valKodeKantor);
                                                                let interval = await getInterval();

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                            getNotification('kbkeu',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }

                                                            if(await checkRoleKeuangan(valKodeFungsi)){
                                                                await getNotificationKeuangan('pmk',valKodeKantor);
                                                                console.log(1)
                                                                let interval = await getIntervalNotificationKeuangan('INTERVALNOTIFRETUR');

                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                            setInterval(() => {
                                                                                console.log(2)
                                                                            getNotificationKeuangan('pmk',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }

                                                            if(valKodeFungsi == "337"){
                                                                await getNotification('ppma',valKodeKantor);
                                                                let interval = await getIntervalNotificationKeuangan('INTERVALNOTIFMPPA');
                                                                if(interval.ret == "1"){
                                                                    if(interval.aktif == 'Y'){
                                                                        let cycleTime =  interval.waktu * 60  * 1000;
                                                                        // let cycleTime =  1 * 60  * 1000;
                                                                            setInterval(() => {
                                                                                getNotificationKeuangan('ppma',valKodeKantor);
                                                                        }, cycleTime);
                                                                    }
                                                                }else{
                                                                    alert('Sistem gagal mendapatkan interval time notifikasi');
                                                                }
                                                            }
                                                        },
                                                        failure: function (form, action) {
                                                            Ext.Msg.alert('ALERT', action.result.errors);
                                                        }

                                                    });


                                                    if (form.isValid()) {
                                                        var out = [];
                                                        Ext.Object.each(form.getValues(), function (key, value) {
                                                            out.push(key + '=' + value);
                                                        });
                                                    }
                                                }
                                            },
                                            {text: 'Batal',
                                                handler: function () {
                                                    window.location = 'logout.bpjs';
                                                }
                                            }
                                        ]
                                    })
                        });
                win.show();
                function showResultText(btn, text) {
                    Ext.notify.msg('Notifikasi', 'Role yang dipilih adalah <strong>"{1}"</strong>.', btn, text);
                }
                getComboDisplay = function (combo) {
                    var value = combo.getValue();
                    var valueField = combo.valueField;
                    var record;
                    combo.getStore().each(function (r) {
                        if (r.data[valueField] == value) {
                            record = r;
                            return false;
                        }
                    });
                    return record ? record.get(combo.displayField) : null;
                }
            });
            function notifikasi(alamat) {
                if (alamat != '') {
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
                        html: '<iframe src="<?= $coreform; ?>' + alamat + '"  height="100%" width="100%" frameborder="0"  style="border:0; height:100%; width:100%;"></iframe>',
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
            function fullpop(alamat, title) {
                if (alamat != '') {
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
                        html: '<iframe src="' + alamat + '"  height="100%" width="100%" frameborder="0"  style="border:0; height:100%; width:100%;"></iframe>',
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
            function openwindow(alamat) {
                window.open(alamat);
            }

            function showFormPopup(mypage, myname, w, h, scroll) {
              var openwin = window.parent.Ext.create('Ext.window.Window', {
                title: myname,
                collapsible: true,
                animCollapse: true,
                maximizable: true,
                closable: true,
                width: w,
                height: h,
                minWidth: w,
                minHeight: h,
                layout: 'fit',
                modal: true,
                html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
                listeners: {
                  close: function () {
                  },
                    destroy: function (wnd, eOpts) {
                  }
                }
              });
              openwin.show();
              return openwin;
            }

            function popUpNotifikasiEproc(koderole){
                var myWindow = showFormPopup('http://<?= $HTTP_HOST; ?>/mod_nep/form/nep_notifikasi.php?x='+koderole+'', "NOTIFIKASI", 500, 350, scroll);
            }


            let list_notifikasi = ``;
            let getNotification = (role, kode_kantor) => {
                list_notifikasi = ``;
                if(role == 'cso'){
                    const data = {
                        tipe: 'get_notification_cso',
                        kode_kantor: kode_kantor
                    }

                    fetch('./get_notification.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let count = 0;
                        const siap_kerja = JSON.parse(data.siap_kerja);
                        const jht_kolektif = JSON.parse(data.jht_kolektif);
                        const epmi = JSON.parse(data.epmi);

                        if(siap_kerja.ret == '1' && siap_kerja.total > 0)
                            count += parseInt(siap_kerja.total);
                        if(jht_kolektif.ret == '1' && jht_kolektif.totalCso > 0)
                            count += parseInt(jht_kolektif.totalCso);
                        if(epmi.ret == '1' && epmi.total > 0)
                            count += parseInt(epmi.total);
                        // kalau ada baru silakan tambahkan baris ini lagi, sound digunakan untuk menandakan ada berapa list notifikasi yang ada
                        //list notifikasi dapat berupa notifikasi siap kerja, notifikasi plkk, notifikasi proses klaim
                        //jadi list notifikasi itu ada 3, menampilkan icon 3
                        //jika ada 1 maka menampilkan 1

                        // jika ada notifikasi > 0, maka tampilkan

                        let info_text = '<?php echo $ls_info; ?>';

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;
                            if(siap_kerja.ret == '1' && siap_kerja.total > 0){
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${parseInt(siap_kerja.total)} pengajuan klaim JKP dari Siap Kerja. `;
                            }
                            if(jht_kolektif.ret == '1' && jht_kolektif.totalCso > 0){
                                document.getElementById('text_notif').innerHTML += `${info_text == '' ? '' : ' | ' } Anda memiliki ${parseInt(jht_kolektif.totalCso)} pengajuan klaim JHT Kolektif dari SIPP`;
                            }
                            if(epmi.ret == '1' && epmi.total > 0){
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${parseInt(epmi.total)} pengajuan klaim E-PMI belum diproses. `;
                            }
                        }

                        // simpan apa yang mau di tampilkan di pop up list notifikasi disini
                        // jika tidak ada error tampilkan sesuai dengan msg nya, jika ada error juga tampilkan, biar orang tw
                        // jgn sampai dianggap tidak ada notifikasi padahal ret nya -1
                        // list baru tambahkan disini menggunakan p

                        if(siap_kerja.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Siap Kerja : </strong> ${siap_kerja.msg}, <a href="#" onClick="toPage(true,'/mod_ec/form/sk1003.php?mid=627030000000000','SK1003-Tindak Lanjut Klaim Manfaat Tunai dari SIAP KERJA', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Siap Kerja : </strong> ${siap_kerja.msg}
                                </p>
                            `;
                        }
                        if(jht_kolektif.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JHT Kolektif : </strong> ${jht_kolektif.msg_cso}, <a href="#" onClick="toPage(true,'/mod_ec/form/ec5013.php?mid=627030000000000','EC5017 - Monitoring Pengajuan Klaim JHT Kolektif', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JHT Kolektif : </strong> ${jht_kolektif.msg}
                                </p>
                            `;
                        }
                        if(epmi.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> E-PMI : </strong> ${epmi.msg}, <a href="#" onClick="toPage(true,'/mod_ec/form/ek1001.php?mid=627030000000000','EK1001 - Verifikasi Pengajuan E-KLAIM PMI', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> E-PMI : </strong> ${epmi.msg}
                                </p>
                            `;
                        }

                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }else if(role == 'kbl'){
                    const data = {
                        tipe: 'get_notification_kbl',
                        kode_kantor: kode_kantor
                    }

                    fetch('./get_notification.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let count = 0;
                        let count_jht_kolektif = 0;
                        let count_informasi_pengaduan = 0;
                        let count_jp_koreksi_norek = 0;
                        const siap_kerja = JSON.parse(data.siap_kerja);
                        const jht_kolektif = JSON.parse(data.jht_kolektif);
                        const informasi_pengaduan = JSON.parse(data.informasi_pengaduan);
                        const jp_koreksi_norek = JSON.parse(data.jp_koreksi_norek);

                        if(jht_kolektif.ret == '1' && jht_kolektif.totalKbl > 0)
                            count_jht_kolektif += parseInt(jht_kolektif.totalKbl);

                        if(informasi_pengaduan.ret == '1' && informasi_pengaduan.total > 0)
                            count_informasi_pengaduan += parseInt(informasi_pengaduan.total);

                        if(jp_koreksi_norek.ret == '1' && parseInt(jp_koreksi_norek.total_kbl) > 0)
                            count_jp_koreksi_norek += parseInt(jp_koreksi_norek.total_kbl);

                        let info_text = '<?php echo $ls_info; ?>';

                        count = count_jht_kolektif + count_informasi_pengaduan + count_jp_koreksi_norek;

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;

                            if(jht_kolektif.ret == '1' && jht_kolektif.totalKbl > 0){
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${jht_kolektif.totalKbl} approval pengajuan klaim JHT Kolektif dari SIPP. | `;
                            }
                            if(jht_kolektif.ret == '1' && jht_kolektif.totalCso > 0){
                                document.getElementById('text_notif').innerHTML += `${info_text == '' ? '' : ' | ' }  Anda memiliki ${parseInt(jht_kolektif.totalCso)} pengajuan klaim JHT Kolektif dari SIPP di CSO. | `;
                            }
                            if(informasi_pengaduan.ret == '1' && informasi_pengaduan.total > 0){
                                document.getElementById('text_notif').innerHTML += `${info_text == '' ? '' : ' | ' }  Anda memiliki ${parseInt(informasi_pengaduan.total)} Agenda Informasi dan Pengaduan yang Belum ditindaklanjuti. | `;
                            }
                            if(jp_koreksi_norek.ret == '1' && count_jp_koreksi_norek > 0){
                                document.getElementById('text_notif').innerHTML += `${info_text == '' ? '' : ' | ' }  Anda memiliki ${parseInt(count_jp_koreksi_norek)} pengajuan approval koreksi rekening pembayaran klaim return belum diproses. | `;
                            }

                        }

                        if(jht_kolektif.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JHT Kolektif : </strong> ${jht_kolektif.msg_kbl}, <a href="#" onClick="toPage(true,'/mod_ec/form/ec5014.php?mid=627030000000000','EC5014 - Monitoring Approval Pengajuan Klaim JHT Kolektif', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JHT Kolektif : </strong> ${jht_kolektif.msg}
                                </p>
                            `;
                        }

                        if(informasi_pengaduan.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Informasi Pengaduan : </strong> ${informasi_pengaduan.msg}, <a href="#" onClick="toPage(true,'/mod_ld/form/ld5003.php?mid=627030000000000','LD5003 - Tindak Lanjut Informasi dan Pengaduan', '${window.kodeFungsi}')"> Lihat Agenda </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Informasi Pengaduan : </strong> ${informasi_pengaduan.msg}
                                </p>
                            `;
                        }

                        if(jp_koreksi_norek.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JP Berkala: </strong> ${jp_koreksi_norek.msg_jpnorek_kbl}, <a href="#" onClick="toPage(true,'/mod_pn/form/pn5076.php?mid=311500000000000','PN5076 - Daftar Koreksi Rekening Pembayaran Klaim Return', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Informasi Pengaduan : </strong> ${jp_koreksi_norek.msg_jpnorek_kbl}
                                </p>
                            `;
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }else if(role == 'pmp'){
                    const data = {
                        tipe: 'get_notification_pmp',
                        kode_kantor: kode_kantor
                    }

                    fetch('./get_notification.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let count = 0;
                        let count_siap_kerja = 0;
                        let count_jp_koreksi_norek = 0;
                        const siap_kerja = JSON.parse(data.siap_kerja);
                        const jp_koreksi_norek = JSON.parse(data.jp_koreksi_norek);

                        if(siap_kerja.ret == '1' && siap_kerja.total > 0)
                            count_siap_kerja += parseInt(siap_kerja.total);

                        if(jp_koreksi_norek.ret == '1' && jp_koreksi_norek.total_pmp > 0)
                            count_jp_koreksi_norek += parseInt(jp_koreksi_norek.total_pmp);

                        let info_text = '<?php echo $ls_info; ?>';

                        count += parseInt(count_jp_koreksi_norek); // + parseInt(count_siap_kerja) -- pada role pmp notif kerja hanya berupa info running text

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;

                            if(count_siap_kerja > 0){
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_siap_kerja} pengajuan klaim JKP dari Siap Kerja | `;
                            }
                            if(count_jp_koreksi_norek > 0){
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_jp_koreksi_norek} pengajuan koreksi rekening pembayaran klaim return belum diproses | `;
                            }

                        }

                        if(jp_koreksi_norek.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JP Berkala: </strong> ${jp_koreksi_norek.msg_jpnorek_pmp}, <a href="#" onClick="toPage(true,'/mod_pn/form/pn5075.php?mid=311500000000000','PN5075 - Monitoring Koreksi Pembayaran Klaim Return', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JP Berkala: </strong> ${jp_koreksi_norek.msg_jpnorek_pmp}
                                </p>
                            `;
                        }


                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }else if(role == 'kakcp'){
                    const data = {
                        tipe: 'get_notification_kakcp',
                        kode_kantor: kode_kantor
                    }

                    fetch('./get_notification.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let count = 0;
                        let count_siap_kerja = 0;
                        let count_mlt_pembina = 0;
                        let count_mlt_pembina_reset = 0;
                        let count_jp_koreksi_norek = 0;

                        const siap_kerja = JSON.parse(data.siap_kerja);
                        const mlt_pembina = JSON.parse(data.mlt_pembina);
                        const jp_koreksi_norek = JSON.parse(data.jp_koreksi_norek);

                        if(siap_kerja.ret == '1' && siap_kerja.total > 0)
                            count_siap_kerja += parseInt(siap_kerja.total);

                        if(mlt_pembina.ret == '1' && mlt_pembina.total_kbp > 0)
                            count_mlt_pembina += parseInt(mlt_pembina.total_kbp);

                        if(jp_koreksi_norek.ret == '1' && jp_koreksi_norek.total_kbl > 0)
                            count_jp_koreksi_norek += parseInt(jp_koreksi_norek.total_kbl);


                        if(mlt_pembina.ret == '1' && mlt_pembina.total_kbp_reset > 0)
                            count_mlt_pembina_reset += parseInt(mlt_pembina.total_kbp_reset);

                        let info_text = '<?php echo $ls_info; ?>';

                        count =  count_mlt_pembina + count_jp_koreksi_norek + count_mlt_pembina_reset; // + count_siap_kerja tidak ditambahkan karna notif siap_kerja hanya untuk running text

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;
                            if(count_siap_kerja > 0){
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_siap_kerja} pengajuan klaim JKP dari Siap Kerja. `;
                            }
                            if(count_mlt_pembina > 0){
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_mlt_pembina} pengajuan MLT belum diapprove. `;
                            }
                            if(count_jp_koreksi_norek > 0){
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_jp_koreksi_norek} pengajuan approval koreksi rekening pembayaran klaim return belum diproses. `;
                            }
                            if (count_mlt_pembina_reset > 0) {
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_mlt_pembina_reset} pengajuan Reset MLT belum diapprove. `;
                            }

                        }

                        if(mlt_pembina.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg_mlt_kbp}, <a href="#" onClick="toPage(true,'/mod_ec/form/ec5113.php?mid=627030000000000','EC5100-Approval Pengajuan MLT', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan Reset MLT : </strong> ${mlt_pembina.msg_mlt_kbp_reset}, <a href="#" onClick="toPage(true,'/mod_ec/form/ec5115.php?mid=627030000000000','EC5115 - APPROVAL RESET PENGAJUAN MLT', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg_mlt_kbp}
                                </p>
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan Reset MLT : </strong> ${mlt_pembina.msg_mlt_kbp_reset}
                                </p>
                            `;
                        }

                        if(jp_koreksi_norek.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JP Berkala: </strong> ${jp_koreksi_norek.msg_jpnorek_kbl}, <a href="#" onClick="toPage(true,'/mod_pn/form/pn5076.php?mid=311500000000000','PN5076 - Daftar Koreksi Rekening Pembayaran Klaim Return', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JP Berkala: </strong> ${jp_koreksi_norek.msg_jpnorek_kbl}
                                </p>
                            `;
                        }

                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }else if(role == 'pmpu'){
                    const data = {
                        tipe: 'get_notification_pmpu',
                        kode_kantor: kode_kantor
                    }

                    fetch('./get_notification.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let count = 0;
                        let count_siap_kerja = 0;
                        let count_jp_koreksi_norek = 0;
                        const siap_kerja = JSON.parse(data.siap_kerja);
                        const jp_koreksi_norek = JSON.parse(data.jp_koreksi_norek);

                        if(siap_kerja.ret == '1' && siap_kerja.total > 0)
                            count_siap_kerja += parseInt(siap_kerja.total);

                        if(jp_koreksi_norek.ret == '1' && jp_koreksi_norek.total > 0)
                            count_jp_koreksi_norek += parseInt(jp_koreksi_norek.totalPmp);

                        let info_text = '<?php echo $ls_info; ?>';

                        count += parseInt(count_siap_kerja); // + parseInt(count_siap_kerja) -- pada role pmpu notif kerja hanya berupa info running text

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;

                            if(count_siap_kerja > 0){
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_siap_kerja} pengajuan klaim JKP dari Siap Kerja`;
                            }
                            if(count_jp_koreksi_norek > 0){
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_jp_koreksi_norek} pengajuan koreksi rekening pembayaran klaim return belum diproses`;
                            }

                        }

                        if(jp_koreksi_norek.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JP Berkala: </strong> ${jp_koreksi_norek.msg_jpnorek_pmp}, <a href="#" onClick="toPage(true,'/mod_pn/form/pn5075.php?mid=311500000000000','PN5075 - Monitoring Koreksi Pembayaran Klaim Return', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JP Berkala: </strong> ${jp_koreksi_norek.msg_jpnorek_pmp}
                                </p>
                            `;
                        }

                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }else if(role == 'mo'){
                    const data = {
                        tipe: 'get_notification_mo',
                        kode_kantor: kode_kantor
                    }

                    fetch('./get_notification.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let count = 0;
                        const mlt_pembina = JSON.parse(data.mlt_pembina);

                        if(mlt_pembina.ret == '1' && mlt_pembina.total_ark > 0)
                            count += parseInt(mlt_pembina.total_ark);

                        let info_text = '<?php echo $ls_info; ?>';

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;
                            document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count} pengajuan MLT belum diproses`;
                        }



                        if(mlt_pembina.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg_mlt_ark}, <a href="#" onClick="toPage(true,'/mod_ec/form/ec5112.php?mid=627030000000000','EC5112 - Verifikasi Pengajuan MLT', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg}
                                </p>
                            `;
                        }

                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }else if(role == 'ro'){
                    const data = {
                        tipe: 'get_notification_ro',
                        kode_kantor: kode_kantor
                    }

                    fetch('./get_notification.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let count = 0;
                        const mlt_pembina = JSON.parse(data.mlt_pembina);

                        if(mlt_pembina.ret == '1' && mlt_pembina.total_ark > 0)
                            count += parseInt(mlt_pembina.total_ark);

                        let info_text = '<?php echo $ls_info; ?>';

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;
                            document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count} pengajuan MLT belum diproses`;
                        }



                        if(mlt_pembina.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg_mlt_ark}, <a href="#" onClick="toPage(true,'/mod_ec/form/ec5112.php?mid=627030000000000','EC5112 - Verifikasi Pengajuan MLT', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg}
                                </p>
                            `;
                        }

                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }else if(role == 'kbp'){
                    const data = {
                        tipe: 'get_notification_kbp',
                        kode_kantor: kode_kantor
                    }

                    fetch('./get_notification.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let count = 0;
                        let count_mlt_pembina = 0;
                        let count_mlt_pembina_reset = 0;
                        const mlt_pembina = JSON.parse(data.mlt_pembina);

                        if(mlt_pembina.ret == '1' && mlt_pembina.total_kbp > 0)
                            count_mlt_pembina += parseInt(mlt_pembina.total_kbp);

                        if(mlt_pembina.ret == '1' && mlt_pembina.total_kbp_reset > 0)
                            count_mlt_pembina_reset += parseInt(mlt_pembina.total_kbp_reset);

                        let info_text = '<?php echo $ls_info; ?>';

                        count = count_mlt_pembina + count_mlt_pembina_reset;

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;
                            if (count_mlt_pembina > 0) {
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_mlt_pembina} pengajuan MLT belum diapprove. `;
                            }
                            if (count_mlt_pembina_reset > 0) {
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_mlt_pembina_reset} pengajuan Reset MLT belum diapprove. `;
                            }

                        }



                        if(mlt_pembina.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg_mlt_kbp}, <a href="#" onClick="toPage(true,'/mod_ec/form/ec5113.php?mid=627030000000000','EC5113-Approval Pengajuan MLT', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan Reset MLT : </strong> ${mlt_pembina.msg_mlt_kbp_reset}, <a href="#" onClick="toPage(true,'/mod_ec/form/ec5115.php?mid=627030000000000','EC5115 - APPROVAL RESET PENGAJUAN MLT', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg_mlt_kbp}
                                </p>
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan Reset MLT : </strong> ${mlt_pembina.msg_mlt_kbp_reset}
                                </p>
                            `;
                        }

                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }else if(role == 'kbpbpu'){
                    const data = {
                        tipe: 'get_notification_kbpbpu',
                        kode_kantor: kode_kantor
                    }

                    fetch('./get_notification.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let count = 0;
                        let count_mlt_pembina = 0;
                        let count_mlt_pembina_reset = 0;
                        const mlt_pembina = JSON.parse(data.mlt_pembina);

                        if(mlt_pembina.ret == '1' && mlt_pembina.total_kbp > 0)
                            count_mlt_pembina += parseInt(mlt_pembina.total_kbp);

                        if(mlt_pembina.ret == '1' && mlt_pembina.total_kbp_reset > 0)
                            count_mlt_pembina_reset += parseInt(mlt_pembina.total_kbp_reset);

                        let info_text = '<?php echo $ls_info; ?>';

                        count = count_mlt_pembina + count_mlt_pembina_reset;

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;
                            if (count_mlt_pembina > 0) {
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_mlt_pembina} pengajuan MLT belum diapprove. `;
                            }
                            if (count_mlt_pembina_reset > 0) {
                                document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_mlt_pembina_reset} pengajuan Reset MLT belum diapprove. `;
                            }
                        }



                        if(mlt_pembina.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg_mlt_kbp}, <a href="#" onClick="toPage(true,'/mod_ec/form/ec5113.php?mid=627030000000000','EC5100-Approval Pengajuan MLT', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan Reset MLT : </strong> ${mlt_pembina.msg_mlt_kbp_reset}, <a href="#" onClick="toPage(true,'/mod_ec/form/ec5115.php?mid=627030000000000','EC5115 - APPROVAL RESET PENGAJUAN MLT', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg_mlt_kbp}
                                </p>
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan Reset MLT : </strong> ${mlt_pembina.msg_mlt_kbp_reset}
                                </p>
                            `;
                        }

                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }else if(role == 'kakacab'){
                    const data = {
                        tipe: 'get_notification_kakacab',
                        kode_kantor: kode_kantor
                    }

                    fetch('./get_notification.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let count                   = 0;
                        let count_mlt_pembina       = 0;
                        let count_jp_koreksi_norek  = 0;
                        const mlt_pembina           = JSON.parse(data.mlt_pembina);
                        const jp_koreksi_norek      = JSON.parse(data.jp_koreksi_norek);

                        if(mlt_pembina.ret == '1' && mlt_pembina.total_kakacab > 0)
                            count_mlt_pembina += parseInt(mlt_pembina.total_kakacab);

                        if(jp_koreksi_norek.ret == '1' && jp_koreksi_norek.total_kakacab > 0)
                            count_jp_koreksi_norek += parseInt(jp_koreksi_norek.total_kakacab);

                        let info_text = '<?php echo $ls_info; ?>';


                        count = count_mlt_pembina + count_jp_koreksi_norek;

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;
                            if (count_mlt_pembina > 0) {
                                document.getElementById('text_notif').innerHTML += `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_mlt_pembina} pengajuan MLT belum diapprove. `;
                            }
                            if (count_jp_koreksi_norek > 0){
                                document.getElementById('text_notif').innerHTML += `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_jp_koreksi_norek} pengajuan approval koreksi rekening pembayaran klaim return belum diproses. `;
                            }

                        }



                        if(mlt_pembina.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg_mlt_kakacab}, <a href="#" onClick="toPage(true,'/mod_ec/form/ec5113.php?mid=627030000000000','EC5100-Approval Pengajuan MLT', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Pengajuan MLT : </strong> ${mlt_pembina.msg}
                                </p>
                            `;
                        }

                        if(jp_koreksi_norek.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JP Berkala: </strong> ${jp_koreksi_norek.msg_jpnorek_kakacab}, <a href="#" onClick="toPage(true,'/mod_pn/form/pn5076.php?mid=311500000000000','PN5076 - Daftar Koreksi Rekening Pembayaran Klaim Return', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JP Berkala: </strong> ${jp_koreksi_norek.msg_jpnorek_kakacab}
                                </p>
                            `;
                        }

                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }else if(role == 'kbkeu'){
                    const data = {
                        tipe: 'get_notification_kbkeu',
                        kode_kantor: kode_kantor
                    }

                    fetch('./get_notification.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {

                        let count                   = 0;
                        let count_jp_koreksi_norek  = 0;
                        const jp_koreksi_norek      = JSON.parse(data.jp_koreksi_norek);

                        if(jp_koreksi_norek.ret == '1' && jp_koreksi_norek.total_kbkeu > 0)
                            count_jp_koreksi_norek += parseInt(jp_koreksi_norek.total_kbkeu);

                        let info_text = '<?php echo $ls_info; ?>';

                        count = count_jp_koreksi_norek; // jika ada notifikasi baru bisa ditambahkan (+) di variable count

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;
                            document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count_jp_koreksi_norek} pengajuan approval koreksi rekening pembayaran klaim return belum diproses`;
                        }

                        if(jp_koreksi_norek.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JP Berkala: </strong> ${jp_koreksi_norek.msg_jpnorek_kbkeu}, <a href="#" onClick="toPage(true,'/mod_pn/form/pn5076.php?mid=311500000000000','PN5076 - Daftar Koreksi Rekening Pembayaran Klaim Return', '${window.kodeFungsi}')"> Lihat Pengajuan </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> JP Berkala: </strong> ${jp_koreksi_norek.msg_jpnorek_kbkeu}
                                </p>
                            `;
                        }

                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }

            }

            let getNotificationKeuangan = (role, kode_kantor) => {
                list_notifikasi = ``;
                if(role == 'pmk'){
                    const data = {
                        tipe: 'get_notification_pmk',
                        kode_kantor: kode_kantor
                    }

                    fetch('./mod_hu/get_notification_keuangan.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        console.log(data);

                        let count = 0;
                        const retur = JSON.parse(data.retur);


                        if(retur.ret == '1' && retur.jumlah_retur > 0)
                            count += parseInt(retur.jumlah_retur);
                            tgl_awal_form = retur.tgl_awal_form;
                        // kalau ada baru silakan tambahkan baris ini lagi, sound digunakan untuk menandakan ada berapa list notifikasi yang ada
                        //list notifikasi dapat berupa notifikasi siap kerja, notifikasi plkk, notifikasi proses klaim
                        //jadi list notifikasi itu ada 3, menampilkan icon 3
                        //jika ada 1 maka menampilkan 1

                        // jika ada notifikasi > 0, maka tampilkan

                        let info_text = '<?php echo $ls_info; ?>';

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;
                            document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count} retur transaksi`;
                        }

                        // simpan apa yang mau di tampilkan di pop up list notifikasi disini
                        // jika tidak ada error tampilkan sesuai dengan msg nya, jika ada error juga tampilkan, biar orang tw
                        // jgn sampai dianggap tidak ada notifikasi padahal ret nya -1
                        // list baru tambahkan disini menggunakan p

                        if(retur.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Transaksi Retur : </strong> ${retur.msg}, <a href="#" onClick="toPage(true,'/mod_hu/form/hu_file_entry_retur.php?mid=803040100000915&former_tglawaldisplay=${retur.tgl_awal_form}','Entry Retur', '${window.kodeFungsi}')"> Lihat Data Retur </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Transaksi Retur : </strong> ${retur.msg}
                                </p>
                            `;
                        }


                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }

                console.log('masuk getNotificationKeuangan')
                console.log(role);
                if(role == 'ppma'){
                    const data = {
                        tipe: 'get_notification_ppma',
                        kode_kantor: kode_kantor
                    }

                    fetch('./mod_hu/get_notification_keuangan.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        console.log(data);

                        let count = 0;
                        const kembalidokblmagenda = JSON.parse(data.kembalidokblmagenda);


                        if(kembalidokblmagenda.ret == '1' && kembalidokblmagenda.jumlah_id_dokumen > 0)
                            count += parseInt(kembalidokblmagenda.jumlah_id_dokumen);
                        // kalau ada baru silakan tambahkan baris ini lagi, sound digunakan untuk menandakan ada berapa list notifikasi yang ada
                        //list notifikasi dapat berupa notifikasi siap kerja, notifikasi plkk, notifikasi proses klaim
                        //jadi list notifikasi itu ada 3, menampilkan icon 3
                        //jika ada 1 maka menampilkan 1

                        // jika ada notifikasi > 0, maka tampilkan

                        let info_text = '<?php echo $ls_info; ?>';

                        if(count > 0){
                            document.getElementById('notification-flag').innerHTML = `<span class="notification-bubble" title="Notifications" style="display: inline; background-color: rgb(254, 145, 81);">${count}</span>`;
                            document.getElementById('text_notif').innerHTML = `${info_text == '' ? '' : ' | ' } Anda memiliki ${count} ID Dokumen MPPA yang dikembalikan`;
                        }

                        // simpan apa yang mau di tampilkan di pop up list notifikasi disini
                        // jika tidak ada error tampilkan sesuai dengan msg nya, jika ada error juga tampilkan, biar orang tw
                        // jgn sampai dianggap tidak ada notifikasi padahal ret nya -1
                        // list baru tambahkan disini menggunakan p

                        if(kembalidokblmagenda.ret == '1'){
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Transaksi MPPA : </strong> ${kembalidokblmagenda.msg}, <a href="#" onClick="toPage(true,'/mod_hu/form/keu_pencairan_anggaran.php?mid=803020201010000','Entry Memo Pencairan Anggaran', '${window.kodeFungsi}')"> Lihat Data MPPA </a>
                                </p>
                            `;
                        }else{
                            list_notifikasi += `
                                <p style="margin-bottom: 12px;">
                                    <strong> Transaksi MPPA : </strong> ${kembalidokblmagenda.msg}
                                </p>
                            `;
                        }


                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }
            }

            let getIntervalNotificationKeuangan = (tipeInterval) => {
                const data = {
                    tipe: 'get_interval',
                    tipeInterval : tipeInterval
                }

                return fetch('./mod_hu/get_notification_keuangan.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                })
                .then((response) => response.json())
                .then((data) => {
                    return JSON.parse(data);
                })
            }

            let getRoleNotifRetur = () => {
                const data = {
                    tipe: 'get_role_keuangan_retur',
                }

                return fetch('./mod_hu/get_notification_keuangan.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                })
                .then((response) => response.json())
                .then((data) => {
                    return JSON.parse(data);
                })
            }

            let checkRoleKeuangan = async function(role) {
                const listRole = await getRoleNotifRetur();
                const dataRoleKeuangan = listRole['data'];
                return (dataRoleKeuangan.indexOf(role) >= 0);
            }

            let getInterval = () => {
                const data = {
                    tipe: 'get_interval',
                }

                return fetch('./get_notification.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                })
                .then((response) => response.json())
                .then((data) => {
                    return JSON.parse(data);
                })
            }

            let showNotification = () => {
                var openwin = window.parent.Ext.create('Ext.window.Window', {
                    title: "Notifikasi",
                    collapsible: true,
                    animCollapse: true,
                    maximizable: true,
                    closable: true,
                    width: 550,
                    height:300,
                    autoScroll: true,
                    minWidth: 500,
                    minHeight: 300,
                    layout: 'fit',
                    modal: true,
                    html: `
                        <div style="margin: 32px 24px;">
                            ${list_notifikasi ? list_notifikasi : 'Anda tidak memiliki notifikasi'}
                        </div>
                    `,
                    listeners: {
                    close: function () {
                    },
                        destroy: function (wnd, eOpts) {
                    }
                    }
                });

                openwin.show();
                return openwin;
            }

            function close() {
                var win = window.parent.parent.Ext.WindowManager.getActive();
                    if (win) {
                win.close();
                }
            }

            let toPage = (formleaf, formid, formtext, formfungsi) => {
                var tp = Ext.getCmp('panel-tab-content');
                var tabCheck = Ext.getCmp(formid);

                if (tabCheck) {
                    tp.setActiveTab(tabCheck);
                    close();
                }else{
                    if (formleaf) {
                        tp.add({
                            closable: true,
                            id: formid,
                            style: 'width: 100%; height:100%;',
                            iconCls: 'tabs',
                            title: formtext,
                            loader: {
                                url: 'f.php?kodeFungsi=' + formfungsi + '&kodeMenu=' + formid,
                                contentType: 'html',
                                autoLoad: true,
                                params: ''
                            }
                        }).show();
                        close();
                    }
                }
            }



        </script>
    </head>
    <body >
        <div id="welcome" class="x-show-display" style="background-color: #f8f8f8;">
        </div>
    </body>
</html>