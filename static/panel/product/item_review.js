Ext.Loader.setConfig({ enabled: true });
Ext.Loader.setPath('Ext.ux', URLS.ext + '/examples/ux');
Ext.require([ 'Ext.grid.*', 'Ext.data.*', 'Ext.ux.grid.FiltersFeature', 'Ext.toolbar.Paging' ]);

Ext.onReady(function() {
	Ext.QuickTips.init();
	
	var main_store = Ext.create('Ext.data.Store', {
		autoLoad: true, pageSize: 25, remoteSort: true,
        sorters: [{ property: 'name', direction: 'ASC' }],
		fields: [ 'id', 'alias', 'name', 'rating', 'user', 'date_update', 'item_name' ],
		proxy: {
			type: 'ajax',
			url : URLS.base + 'panel/product/item_review/grid', actionMethods: { read: 'POST' },
			reader: { type: 'json', root: 'rows', totalProperty: 'count' }
		}
	});
	
	var main_grid = new Ext.grid.GridPanel({
		viewConfig: { forceFit: true }, store: main_store, height: 335, renderTo: 'grid-member',
		features: [{ ftype: 'filters', encode: true, local: false }],
		columns: [ {
					header: 'Item Barang', dataIndex: 'item_name', sortable: true, filter: true, width: 200
			}, {	header: 'Title', dataIndex: 'name', sortable: true, filter: true, width: 200, flex: 1
			}, {	header: 'Rating', dataIndex: 'rating', sortable: true, filter: true, width: 100
			}, {	header: 'Writer', dataIndex: 'user', sortable: true, filter: true, width: 100
			}, {	header: 'Tanggal Update', dataIndex: 'date_update', sortable: true, filter: true, width: 150
		} ],
		tbar: [ {
				text: 'Tambah', iconCls: 'addIcon', tooltip: 'Tambah', handler: function() { main_win({ id: 0 }); }
			}, '-', {
				text: 'Ubah', iconCls: 'editIcon', tooltip: 'Ubah', handler: function() { main_grid.update({ }); }
			}, '-', {
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
		listeners: {
			'itemdblclick': function(model, records) {
				main_grid.update({ });
            }
        },
		load_grid: function(Param) {
			main_store.proxy.extraParams = Param;
			main_store.load();
		},
		update: function(Param) {
			var row = main_grid.getSelectionModel().getSelection();
			if (row.length == 0) {
				Ext.Msg.alert('Informasi', 'Silahkan memilih data.');
				return false;
			}
			
			Ext.Ajax.request({
				url: URLS.base + 'panel/product/item_review/action',
				params: { action: 'get_by_id', id: row[0].data.id },
				success: function(Result) {
					eval('var record = ' + Result.responseText)
					record.id = record.id;
					main_win(record);
				}
			});
		},
		delete: function(Value) {
			if (Value == 'no') {
				return;
			}
			
			Ext.Ajax.request({
				url: URLS.base + 'panel/product/item_review/action',
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
	
	function main_win(param) {
		var win = new Ext.Window({
			layout: 'fit', width: 710, height: 325,
			closeAction: 'hide', plain: true, modal: true,
			buttons: [ {
						text: 'Save', handler: function() { win.save(); }
				}, {	text: 'Close', handler: function() {
						win.hide();
				}
			}],
			listeners: {
				show: function(w) {
					var Title = (param.id == 0) ? 'Entry Item Review - [New]' : 'Entry Item Review - [Edit]';
					w.setTitle(Title);
					
					Ext.Ajax.request({
						url: URLS.base + 'panel/product/item_review/view',
						success: function(Result) {
							w.body.dom.innerHTML = Result.responseText;
							
							win.id = param.id;
							win.item = Combo.Class.Item({ renderTo: 'itemED', width: 585, allowBlank: false, blankText: 'Masukkan Item' });
							win.name = new Ext.form.TextField({
								renderTo: 'nameED', width: 585, allowBlank: false, blankText: 'Masukkan Judul',
								enableKeyEvents: true, listeners: {
									keyup: function(me) {
										var alias = Func.GetName(me.getValue());
										win.alias.setValue(alias);
									}
								}
							});
							win.alias = new Ext.form.TextField({ renderTo: 'aliasED', width: 585, readOnly: true });
							win.desc = new Ext.form.TextArea({ renderTo: 'descED', width: 585, height: 100 });
							win.user = new Ext.form.TextField({ renderTo: 'userED', width: 200, allowBlank: false, blankText: 'Masukkan Penulis' });
							win.rating = Combo.Class.Rating({ renderTo: 'ratingED', width: 80, allowBlank: false, blankText: 'Masukkan Rating' });
							
							// Populate Record
							if (param.id > 0) {
								win.name.setValue(param.name);
								win.alias.setValue(param.alias);
								win.desc.setValue(param.desc);
								win.rating.setValue(param.rating);
								win.user.setValue(param.user);
								Func.SetValue({ action : 'item', ForceID: param.item_id, Combo: win.item });
							}
						}
					});
				},
				hide: function(w) {
					w.destroy();
					w = win = null;
				}
			},
			save: function() {
				var ajax = new Object();
				ajax.action = 'update';
				ajax.id = win.id;
				ajax.item_id = win.item.getValue();
				ajax.name = win.name.getValue();
				ajax.alias = win.alias.getValue();
				ajax.desc = win.desc.getValue();
				ajax.rating = win.rating.getValue();
				ajax.user = win.user.getValue();
				
				// Validation
				var is_valid = true;
				if (! win.item.validate()) {
					is_valid = false;
				}
				if (! win.name.validate()) {
					is_valid = false;
				}
				if (! win.user.validate()) {
					is_valid = false;
				}
				if (! win.rating.validate()) {
					is_valid = false;
				}
				if (! is_valid) {
					return;
				}
				
				Func.ajax({ param: ajax, url: URLS.base + 'panel/product/item_review/action', callback: function(result) {
					Ext.Msg.alert('Informasi', result.message);
					if (result.status) {
						main_store.load();
						win.hide();
					}
				} });
			}
		});
		win.show();
	}
	
	Renderer.InitWindowSize({ Panel: -1, Grid: main_grid, Toolbar: 70 });
	Ext.EventManager.onWindowResize(function() {
		Renderer.InitWindowSize({ Panel: -1, Grid: main_grid, Toolbar: 70 });
    }, main_grid);
});