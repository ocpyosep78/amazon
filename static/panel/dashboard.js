Ext.Loader.setConfig({ enabled: true });
Ext.Loader.setPath('Ext.ux', URLS.ext + '/examples/ux');
Ext.require([ 'Ext.grid.*', 'Ext.data.*', 'Ext.ux.grid.FiltersFeature', 'Ext.toolbar.Paging' ]);

Ext.onReady(function() {
	Ext.QuickTips.init();
	Ext.get('loading_mask').remove();
	
	var table_comment = Ext.create('Ext.ux.grid.TransformGrid', "table-comment", { stripeRows: true, height: 325 });
	table_comment.render();
});