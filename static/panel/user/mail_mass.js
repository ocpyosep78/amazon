Ext.Loader.setConfig({ enabled: true });
Ext.Loader.setPath('Ext.ux', URLS.ext + '/examples/ux');
Ext.require([ 'Ext.grid.*', 'Ext.data.*', 'Ext.ux.grid.FiltersFeature', 'Ext.toolbar.Paging' ]);

Ext.onReady(function() {
	Ext.QuickTips.init();
	
	var main_store = Ext.create('Ext.data.Store', {
		autoLoad: true, pageSize: 25, remoteSort: true,
        sorters: [{ property: 'name', direction: 'ASC' }],
		fields: [ 'id', 'name', 'desc', 'queue_no', 'queue_max', 'date_update', 'is_finish' ],
		proxy: {
			type: 'ajax',
			url : URLS.base + 'panel/user/mail_mass/grid', actionMethods: { read: 'POST' },
			reader: { type: 'json', root: 'rows', totalProperty: 'count' }
		}
	});
	
	var main_grid = new Ext.grid.GridPanel({
		viewConfig: { forceFit: true }, store: main_store, height: 335, renderTo: 'grid-member',
		features: [{ ftype: 'filters', encode: true, local: false }],
		columns: [ {
					header: 'Title', dataIndex: 'name', sortable: true, filter: true, width: 200, flex: 1
			}, {	header: 'Current Queue', dataIndex: 'queue_no', sortable: true, filter: true, width: 150
			}, {	header: 'Max Queue', dataIndex: 'queue_max', sortable: true, filter: true, width: 150
			}, {	header: 'Tanggal Update', dataIndex: 'date_update', sortable: true, filter: true, width: 125
			}, {	header: 'Finish', xtype: 'actioncolumn', width: 75, align: 'center',
					items: [ {
						getClass: function(v, meta, rec) {
							if (rec.get('is_finish') == false) {
								this.items[0].tooltip = 'No';
								return 'delIcon';
							} else {
								this.items[0].tooltip = 'Yes';
								return 'acceptIcon';
							}
						}
					} ]
			}, {	header: 'Action', xtype: 'actioncolumn', width: 75, align: 'center',
					items: [ {
							iconCls: 'mailIcon', tooltip: 'Sent Mail', handler: function(grid, rowIndex, colIndex) {
								var row = grid.store.getAt(rowIndex).data;
								
								if (! row.is_finish) {
									main_grid.send_mail(row);
								}
							}
					} ]
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
				url: URLS.base + 'panel/user/mail_mass/action',
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
				url: URLS.base + 'panel/user/mail_mass/action',
				params: { action: 'delete', id: main_grid.getSelectionModel().getSelection()[0].data.id },
				success: function(TempResult) {
					eval('var Result = ' + TempResult.responseText)
					
					Ext.Msg.alert('Informasi', Result.message);
					if (Result.status == '1') {
						main_store.load();
					}
				}
			});
		},
		send_mail: function(row) {
			var param = row;
			param.action = 'send_mail';
			Func.ajax({ param: param, url: URLS.base + 'panel/user/mail_mass/action', callback: function(result) {
				Ext.Msg.alert('Informasi', result.message);
				if (result.status) {
					main_store.load();
					
					// continue sent next mail
					if (result.is_finish == true) {
						Ext.Msg.alert('Informasi', 'Email sudah terkirim untuk semua subscriber.');
					} else {
						main_grid.send_mail(row);
					}
				}
			} });
		}
	});
	
	function main_win(param) {
		var win = new Ext.Window({
			layout: 'fit', width: 710, height: 451,
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
						url: URLS.base + 'panel/user/mail_mass/view',
						success: function(Result) {
							w.body.dom.innerHTML = Result.responseText;
							
							win.id = param.id;
							win.name = new Ext.form.TextField({ renderTo: 'nameED', width: 585, allowBlank: false, blankText: 'Masukkan Judul' });
							win.desc = new Ext.form.HtmlEditor({ renderTo: 'descED', width: 575, height: 345, enableFont: false });
							
							// Populate Record
							if (param.id > 0) {
								win.name.setValue(param.name);
								win.desc.setValue(param.desc);
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
				ajax.name = win.name.getValue();
				ajax.desc = win.desc.getValue();
				
				// Validation
				var is_valid = true;
				if (! win.name.validate()) {
					is_valid = false;
				}
				if (! is_valid) {
					return;
				}
				
				Func.ajax({ param: ajax, url: URLS.base + 'panel/user/mail_mass/action', callback: function(result) {
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