Ext.Loader.setConfig({ enabled: true });
Ext.Loader.setPath('Ext.ux', URLS.ext + '/examples/ux');
Ext.require([ 'Ext.grid.*', 'Ext.data.*', 'Ext.ux.grid.FiltersFeature', 'Ext.toolbar.Paging' ]);

Ext.onReady(function() {
	Ext.QuickTips.init();
	
	var main_store = Ext.create('Ext.data.Store', {
		autoLoad: true, pageSize: 25, remoteSort: true,
        sorters: [{ property: 'email', direction: 'ASC' }],
		fields: [ 'id', 'user_type_id', 'user_type_name', 'email', 'fullname', 'passwd', 'address', 'register_date', 'login_last_date', 'is_active' ],
		proxy: {
			type: 'ajax',
			url : URLS.base + 'panel/user/user/grid', actionMethods: { read: 'POST' },
			reader: { type: 'json', root: 'rows', totalProperty: 'count' }
		}
	});
	
	var main_grid = new Ext.grid.GridPanel({
		viewConfig: { forceFit: true }, store: main_store, height: 335, renderTo: 'grid-member',
		features: [{ ftype: 'filters', encode: true, local: false }],
		columns: [ {
					header: 'Email', dataIndex: 'email', sortable: true, filter: true, width: 200
			}, {	header: 'Fullname', dataIndex: 'fullname', sortable: true, filter: true, width: 200, flex: 1
			}, {	header: 'User Type', dataIndex: 'user_type_name', sortable: true, filter: true, width: 100
			}, {	header: 'Register Date', dataIndex: 'register_date', sortable: true, filter: true, width: 200
			}, {	header: 'Active', xtype: 'actioncolumn', width: 75, align: 'center',
					items: [ {
						getClass: function(v, meta, rec) {
							if (rec.get('is_active') == 0) {
								this.items[0].tooltip = 'Active';
								return 'delIcon';
							} else {
								this.items[0].tooltip = 'Inactive';
								return 'acceptIcon';
							}
						},
						handler: function(grid, rowIndex, colIndex) {
							var rec = grid.store.getAt(rowIndex);
							var param = { action: 'update', id: rec.data.id, is_active: (rec.data.is_active == 0) ? 1 : 0 }
							Func.ajax({ param: param, url: URLS.base + 'panel/user/user/action', callback: function(result) {
								if (result.status) {
									grid.store.load();
								}
							} });
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
				url: URLS.base + 'panel/user/user/action',
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
				url: URLS.base + 'panel/user/user/action',
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
			layout: 'fit', width: 390, height: 220,
			closeAction: 'hide', plain: true, modal: true,
			buttons: [ {
						text: 'Save', handler: function() { win.save(); }
				}, {	text: 'Close', handler: function() {
						win.hide();
				}
			}],
			listeners: {
				show: function(w) {
					var Title = (param.id == 0) ? 'Entry Category - [New]' : 'Entry Category - [Edit]';
					w.setTitle(Title);
					
					Ext.Ajax.request({
						url: URLS.base + 'panel/user/user/view',
						success: function(Result) {
							w.body.dom.innerHTML = Result.responseText;
							
							win.id = param.id;
							win.email = new Ext.form.TextField({ renderTo: 'emailED', width: 225, allowBlank: false, blankText: 'Masukkan Email' });
							win.fullname = new Ext.form.TextField({ renderTo: 'fullnameED', width: 225, allowBlank: false, blankText: 'Masukkan Nama Lengkap' });
							win.address = new Ext.form.TextField({ renderTo: 'addressED', width: 225 });
							win.passwd = new Ext.form.TextField({ renderTo: 'passwdED', width: 225 });
							win.user_type = Combo.Class.UserType({ renderTo: 'user_typeED', width: 225, allowBlank: false, blankText: 'Masukkan Jenis User' });
							
							// Populate Record
							if (param.id > 0) {
								win.email.setValue(param.email);
								win.fullname.setValue(param.fullname);
								win.address.setValue(param.address);
								win.user_type.setValue(param.user_type_id);
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
				ajax.email = win.email.getValue();
				ajax.fullname = win.fullname.getValue();
				ajax.address = win.address.getValue();
				ajax.passwd = win.passwd.getValue();
				ajax.user_type_id = win.user_type.getValue();
				
				// Validation
				var is_valid = true;
				if (! win.email.validate()) {
					is_valid = false;
				}
				if (! win.fullname.validate()) {
					is_valid = false;
				}
				if (! win.user_type.validate()) {
					is_valid = false;
				}
				if (! is_valid) {
					return;
				}
				
				Func.ajax({ param: ajax, url: URLS.base + 'panel/user/user/action', callback: function(result) {
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