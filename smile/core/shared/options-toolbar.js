(function() {
	Ext.Loader.setConfig({enabled: true});

	//Ext.Loader.setPath('Ext.ux', 'ux/');
	
    Ext.require([
		'Ext.tip.QuickTipManager',
		'Ext.menu.*',
		'Ext.layout.container.Table',
		'Ext.container.ButtonGroup',
		'Ext.tree.*',
        'Ext.data.*',
		'Ext.window.MessageBox',
    	'Ext.tip.*',
		'Ext.form.*',
		'Ext.form.field.ComboBox',
		'Ext.form.FieldContainer',
		'Ext.ux.TabCloseMenu'
	]);

    Ext.onReady(function() {
		var store = Ext.create('Ext.data.TreeStore', {
			root: {
				expanded: true
			},
			proxy: {
				type: 'ajax',
				url: 'resources/data/tree/tree-data.json'
			}
		});
    });
})();

Ext.notify = function(){
    var msgCt;

    function createBox(t, s){
       return '<div class="msg"><h3>' + t + '</h3><p>' + s + '</p></div>';
    }
    return {
        msg : function(title, format){
            if(!msgCt){
                msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
            }
            var s = Ext.String.format.apply(String, Array.prototype.slice.call(arguments, 1));
            var m = Ext.DomHelper.append(msgCt, createBox(title, s), true);
            m.hide();
            m.slideIn('t').ghost("t", { delay: 1000, remove: true});
        },

        init : function(){
            if(!msgCt){
                msgCt = Ext.DomHelper.insertFirst(document.body, {id:'msg-div'}, true);
            }
        }
    };
}();


Ext.onReady(Ext.notify.init, Ext.notify);