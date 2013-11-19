Ext.Loader.setConfig({ enabled: true });
Ext.Loader.setPath('Ext.ux', URLS.ext + '/examples/ux');
Ext.require([ 'Ext.grid.*', 'Ext.data.*', 'Ext.ux.grid.FiltersFeature', 'Ext.toolbar.Paging' ]);

Ext.onReady(function() {
	Ext.QuickTips.init();
	
	var main_store = Ext.create('Ext.data.Store', {
		autoLoad: true, pageSize: 25, remoteSort: true,
        sorters: [{ property: 'date_report', direction: 'DESC' }],
		fields: [ 'id', 'link', 'is_repair', 'date_report' ],
		proxy: {
			type: 'ajax',
			url : URLS.base + 'panel/report/broken_link/grid', actionMethods: { read: 'POST' },
			reader: { type: 'json', root: 'rows', totalProperty: 'count' }
		}
	});
	
	var main_grid = new Ext.grid.GridPanel({
		viewConfig: { forceFit: true }, store: main_store, height: 335, renderTo: 'grid-member',
		features: [{ ftype: 'filters', encode: true, local: false }],
		columns: [ {
					header: 'Link', dataIndex: 'link', sortable: true, filter: true, width: 200, flex: 1
			}, {	header: 'Report', dataIndex: 'date_report', sortable: true, filter: true, width: 200
			}, {	header: 'Repair', xtype: 'actioncolumn', width: 75, align: 'center',
					items: [ {
						getClass: function(v, meta, rec) {
							if (rec.get('is_repair') == 0) {
								this.items[0].tooltip = 'Error';
								return 'delIcon';
							} else {
								this.items[0].tooltip = 'Fixed';
								return 'acceptIcon';
							}
						},
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.store.getAt(rowIndex);
							var param = { action: 'update', id: rec.data.id, is_repair: (rec.data.is_repair == 0) ? 1 : 0 }
							Func.ajax({ param: param, url: URLS.base + 'panel/report/broken_link/action', callback: function(result) {
								if (result.status) {
									grid.store.load();
								}
							} });
						}
					} ]
			}, {	header: 'Action', xtype: 'actioncolumn', width: 75, align: 'center',
					items: [ {
							iconCls: 'linkIcon', tooltip: 'Link', handler: function(grid, rowIndex, colIndex) {
								var row = grid.store.getAt(rowIndex).data;
								var link = URLS.base + 'url?q=' + row.link;
								window.open(link);
							}
					} ]
		} ],
		tbar: [ {
				text: 'Hapus', iconCls: 'delIcon', tooltip: 'Hapus', handler: function() {
					if (main_grid.getSelectionModel().getSelection().length == 0) {
						Ext.Msg.alert('Informasi', 'Silahkan memilih data.');
						return false;
					}
					
					Ext.MessageBox.confirm('Konfirmasi', 'Apa anda yakin akan menghapus data ini ?', main_grid.delete);
				}
			}, '->', {
                id: 'SearchPM', xtype: 'textfield', tooltip: 'Cari', emptyText: 'Cari', listeners: {
                    'specialKey': function(field, el) {
                        if (el.getKey() == Ext.EventObject.ENTER) {
                            var value = Ext.getCmp('SearchPM').getValue();
                            if ( value ) {
								main_grid.load_grid({ namelike: value });
                            }
                        }
                    }
                }
            }, '-', {
				text: 'Reset', tooltip: 'Reset pencarian', iconCls: 'refreshIcon', handler: function() {
					main_grid.load_grid({ });
				}
		} ],
		bbar: new Ext.PagingToolbar( {
			store: main_store, displayInfo: true,
			displayMsg: 'Displaying topics {0} - {1} of {2}',
			emptyMsg: 'No topics to display'
		} ),
		load_grid: function(Param) {
			main_store.proxy.extraParams = Param;
			main_store.load();
		},
		delete: function(Value) {
			if (Value == 'no') {
				return;
			}
			
			Ext.Ajax.request({
				url: URLS.base + 'panel/report/broken_link/action',
				params: { action: 'delete', id: main_grid.getSelectionModel().getSelection()[0].data.id },
				success: function(TempResult) {
					eval('var Result = ' + TempResult.responseText)
					
					Ext.Msg.alert('Informasi', Result.message);
					if (Result.status == '1') {
						main_store.load();
					}
				}
			});
		}
	});
	
	Renderer.InitWindowSize({ Panel: -1, Grid: main_grid, Toolbar: 70 });
	Ext.EventManager.onWindowResize(function() {
		Renderer.InitWindowSize({ Panel: -1, Grid: main_grid, Toolbar: 70 });
    }, main_grid);
});