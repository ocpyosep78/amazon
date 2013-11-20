Ext.Loader.setConfig({ enabled: true });
Ext.Loader.setPath('Ext.ux', URLS.ext + '/examples/ux');
Ext.require([ 'Ext.grid.*', 'Ext.data.*', 'Ext.ux.grid.FiltersFeature', 'Ext.toolbar.Paging' ]);

Ext.onReady(function() {
	Ext.QuickTips.init();
	
	var main_store = Ext.create('Ext.data.Store', {
		autoLoad: true, pageSize: 25, remoteSort: true,
        sorters: [{ property: 'name', direction: 'ASC' }],
		fields: [
			'id', 'alias', 'name', 'code', 'desc', 'price_show', 'date_update', 'brand_id', 'brand_name', 'category_id', 'category_name', 'category_sub_id', 'category_sub_name',
			'scrape_id', 'item_status_name'
		],
		proxy: {
			type: 'ajax',
			url : URLS.base + 'panel/product/item/grid', actionMethods: { read: 'POST' },
			reader: { type: 'json', root: 'rows', totalProperty: 'count' }
		}
	});
	
	var main_grid = new Ext.grid.GridPanel({
		viewConfig: { forceFit: true }, store: main_store, height: 335, renderTo: 'grid-member',
		features: [{ ftype: 'filters', encode: true, local: false }],
		columns: [ {
					header: 'Name', dataIndex: 'name', sortable: true, filter: true, width: 200, flex: 1
			}, {	header: 'Category', dataIndex: 'category_name', sortable: true, filter: true, width: 100
			}, {	header: 'Sub Category', dataIndex: 'category_sub_name', sortable: true, filter: true, width: 100
			}, {	header: 'Code', dataIndex: 'code', sortable: true, filter: true, width: 80
			}, {	header: 'Brand', dataIndex: 'brand_name', sortable: true, filter: true, width: 80
			}, {	header: 'Price', dataIndex: 'price_show', sortable: true, filter: true, width: 80
			}, {	header: 'Status', dataIndex: 'item_status_name', sortable: true, filter: true, width: 80
			}, {	header: 'Tanggal Update', dataIndex: 'date_update', sortable: true, filter: true, width: 125
			}, {	header: 'Action', xtype: 'actioncolumn', width: 75, align: 'center',
					items: [ {
							iconCls: 'refreshIcon', tooltip: 'Re Scrape', handler: function(grid, rowIndex, colIndex) {
								var row = grid.store.getAt(rowIndex).data;
								var link_scrape = URLS.base + 'panel/product/item/do_scrape?scrape_id=' + row.scrape_id + '&item_request_rescrape=' + row.id;
								window.open(link_scrape);
							}
					} ]
		} ],
		tbar: [ {
				text: 'Scrape :', margin: '0 5 0 5', xtype: 'label'
			},	Combo.Class.Scrape({ width: 225, id: 'scrape' }), {
				id: 'scrape_page', xtype: 'textfield', tooltip: 'Scrape Page', emptyText: 'Scrape Page'
			}, {
				text: 'Start Scrape', iconCls: 'addIcon', tooltip: 'Start Scrape', handler: function() {
					var scrape_id = Ext.getCmp('scrape').getValue();
					var scrape_page = Ext.getCmp('scrape_page').getValue();
					if (scrape_id == null || scrape_page == '') {
						return false;
					}
					
					var link_scrape = URLS.base + 'panel/product/item/do_scrape?scrape_id=' + scrape_id + '&scrape_page=' + escape(scrape_page);
					window.open(link_scrape);
				}
			}, '-', {
				text: 'Tambah', iconCls: 'addIcon', tooltip: 'Tambah', handler: function() { main_win({ id: 0 }); }
			}, '-', {
				text: 'Ubah', iconCls: 'editIcon', tooltip: 'Ubah', handler: function() { main_grid.update({ }); }
			}, '-', {
				xtype: 'splitbutton', text: 'Multi Title', iconCls: 'editIcon', tooltip: 'Multi Title',
				handler: function() { main_grid.multi_title({ }); },
				menu: Ext.create('Ext.menu.Menu', { items: [
					{	 text: 'Compare', iconCls: 'editIcon', tooltip: 'Tambah', handler: function() { main_grid.compare({ });; }
					}
				] } )
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
				url: URLS.base + 'panel/product/item/action',
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
				url: URLS.base + 'panel/product/item/action',
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
		multi_title: function() {
			var row = main_grid.getSelectionModel().getSelection();
			if (row.length == 0) {
				Ext.Msg.alert('Informasi', 'Silahkan memilih data.');
				return false;
			}
			
			multi_title_win(row[0].data);
		},
		compare: function() {
			var row = main_grid.getSelectionModel().getSelection();
			if (row.length == 0) {
				Ext.Msg.alert('Informasi', 'Silahkan memilih data.');
				return false;
			}
			
			compare_win(row[0].data);
		}
	});
	
	function main_win(param) {
		var win = new Ext.Window({
			layout: 'fit', width: 1070, height: 535,
			closeAction: 'hide', plain: true, modal: true,
			buttons: [ {
						text: 'Save', handler: function() { win.save(); }
				}, {	text: 'Close', handler: function() {
						win.hide();
				}
			}],
			listeners: {
				show: function(w) {
					var Title = (param.id == 0) ? 'Entry Item - [New]' : 'Entry Item - [Edit]';
					w.setTitle(Title);
					
					Ext.Ajax.request({
						url: URLS.base + 'panel/product/item/view',
						success: function(Result) {
							w.body.dom.innerHTML = Result.responseText;
							
							win.id = param.id;
							win.name = new Ext.form.TextField({
								renderTo: 'nameED', width: 575, allowBlank: false, blankText: 'Masukkan Judul',
								enableKeyEvents: true, listeners: {
									keyup: function(me) {
										var alias = Func.GetName(me.getValue());
										win.alias.setValue(alias);
									}
								}
							});
							win.alias = new Ext.form.TextField({ renderTo: 'aliasED', width: 575, readOnly: true });
							win.desc = new Ext.form.HtmlEditor({ renderTo: 'descED', width: 575, height: 345, enableFont: false });
							win.link_source = new Ext.form.TextField({ renderTo: 'link_sourceED', width: 575 });
							win.status_stock = new Ext.form.TextField({ renderTo: 'status_stockED', width: 575 });
							win.brand = Combo.Class.Brand({ renderTo: 'brandED', width: 225, allowBlank: false, blankText: 'Masukkan Brand' });
							win.category = Combo.Class.Category({
								renderTo: 'categoryED', width: 225, listeners: {
									select: function(cb, record) {
										win.category_sub.reset();
									}
								}
							});
							win.category_sub = Combo.Class.CategorySub({
								renderTo: 'category_subED', width: 225, allowBlank: false, blankText: 'Masukkan Sub Category', listeners: {
									beforequery: function(queryEvent, eOpts) {
										queryEvent.combo.store.proxy.extraParams.category_id = win.category.getValue();
										queryEvent.combo.store.load();
									}
								}
							});
							win.code = new Ext.form.TextField({ renderTo: 'codeED', width: 225 });
							win.store = new Ext.form.TextField({ renderTo: 'storeED', width: 225 });
							win.tag = new Ext.form.TextField({ renderTo: 'tagED', width: 225 });
							win.item_status = Combo.Class.ItemStatus({ renderTo: 'item_statusED', width: 225 });
							win.price_old = new Ext.form.TextField({ renderTo: 'price_oldED', width: 225 });
							win.price_show = new Ext.form.TextField({ renderTo: 'price_showED', width: 225 });
							win.price_range = new Ext.form.TextField({ renderTo: 'price_rangeED', width: 225 });
							win.image = new Ext.form.TextField({ renderTo: 'imageED', width: 225, readOnly: true });
							win.image_button = new Ext.Button({ renderTo: 'btn_imageED', text: 'Browse', width: 75, handler: function(btn) {
								window.iframe_image.browse();
							} });
							item_image = function(p) { win.image.setValue(p.file_name); }
							
							// Populate Record
							if (param.id > 0) {
								win.name.setValue(param.name);
								win.alias.setValue(param.alias);
								win.desc.setValue(param.desc);
								win.link_source.setValue(param.link_source);
								win.status_stock.setValue(param.status_stock);
								win.code.setValue(param.code);
								win.store.setValue(param.store);
								win.price_old.setValue(param.price_old);
								win.price_show.setValue(param.price_show);
								win.price_range.setValue(param.price_range);
								win.image.setValue(param.image);
								win.item_status.setValue(param.item_status_id);
								
								win.brand.setValue(param.brand_id);
								win.category.setValue(param.category_id);
								Func.SetValue({ action : 'category_sub', ForceID: param.category_sub_id, Combo: win.category_sub });
								
								// tag
								if (param.array_tag != null) {
									var string_tag = '';
									for (var i = 0; i < param.array_tag.length; i++) {
										string_tag += (string_tag == '') ? param.array_tag[i].tag_name : ',' + param.array_tag[i].tag_name;
									}
									win.tag.setValue(string_tag);
								}
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
				ajax.alias = win.alias.getValue();
				ajax.desc = win.desc.getValue();
				ajax.link_source = win.link_source.getValue();
				ajax.status_stock = win.status_stock.getValue();
				ajax.code = win.code.getValue();
				ajax.store = win.store.getValue();
				ajax.tag = win.tag.getValue();
				ajax.price_old = win.price_old.getValue();
				ajax.price_show = win.price_show.getValue();
				ajax.price_range = win.price_range.getValue();
				ajax.image = win.image.getValue();
				ajax.item_status_id = win.item_status.getValue();
				ajax.brand_id = win.brand.getValue();
				ajax.category_id = win.category.getValue();
				ajax.category_sub_id = win.category_sub.getValue();
				
				// Validation
				var is_valid = true;
				if (! win.name.validate()) {
					is_valid = false;
				}
				if (! win.brand.validate()) {
					is_valid = false;
				}
				if (! win.category_sub.validate()) {
					is_valid = false;
				}
				if (! is_valid) {
					return;
				}
				
				Func.ajax({ param: ajax, url: URLS.base + 'panel/product/item/action', callback: function(result) {
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
	
	function multi_title_win(param) {
		var win = new Ext.Window({
			layout: 'fit', width: 1070, height: 480,
			closeAction: 'hide', plain: true, modal: true, title: 'Entry Multi Title',
			buttons: [ { text: 'Close', handler: function() { win.hide(); } }],
			listeners: {
				show: function(w) {
					Ext.Ajax.request({
						params: { form_name: 'multi_title' },
						url: URLS.base + 'panel/product/item/view',
						success: function(Result) {
							w.body.dom.innerHTML = Result.responseText;
							
							win.store = Ext.create('Ext.data.Store', {
								autoLoad: true, pageSize: 25, remoteSort: true,
								sorters: [{ property: 'name', direction: 'ASC' }],
								fields: [ 'id', 'name', 'desc' ],
								proxy: {
									type: 'ajax', extraParams: { item_id: param.id },
									url : URLS.base + 'panel/product/item_multi_title/grid', actionMethods: { read: 'POST' },
									reader: { type: 'json', root: 'rows', totalProperty: 'count' }
								}
							});
							
							win.grid = new Ext.grid.GridPanel({
								viewConfig: { forceFit: true }, store: win.store, height: 400, renderTo: 'grid-multiEM',
								columns: [ {
											header: 'Name', dataIndex: 'name', sortable: true, filter: true, width: 200, flex: 1
								} ],
								tbar: [ {
										text: 'Tambah', iconCls: 'addIcon', tooltip: 'Tambah', handler: function() {
											win.grid.edit_mode();
											
											// set new record
											win.record = { id: 0 };
											win.name.reset();
											win.desc.reset();
											win.name.focus();
										}
									}, '-', {
										text: 'Ubah', iconCls: 'editIcon', tooltip: 'Ubah', handler: function() {
											// set data
											var row = win.grid.getSelectionModel().getSelection();
											if (row.length == 0) {
												Ext.Msg.alert('Informasi', 'Silahkan memilih data.');
												return false;
											}
											
											win.grid.edit_mode();
											var record = row[0].data;
											win.record = { id: record.id };
											win.name.setValue(record.name);
											win.desc.setValue(record.desc);
										}
									}, '-', {
										text: 'Hapus', iconCls: 'delIcon', tooltip: 'Hapus', handler: function() { console.log('Hapus'); }
								} ],
								listeners: {
									'itemclick': function(grid, record, item) {
										win.name.setValue(record.data.name);
										win.desc.setValue(record.data.desc);
									}
								},
								edit_mode: function() {
									win.name.setReadOnly(false);
									win.desc.setReadOnly(false);
									win.save.setDisabled(false);
									win.cancel.setDisabled(false);
								},
								read_mode: function() {
									win.name.setReadOnly(true);
									win.desc.setReadOnly(true);
									win.save.setDisabled(true);
									win.cancel.setDisabled(true);
									
									win.name.reset();
									win.desc.reset();
								}
							});
							
							win.name = new Ext.form.TextField({ renderTo: 'nameEM', width: 575, allowBlank: false, blankText: 'Masukkan Judul', readOnly: true });
							win.desc = new Ext.form.HtmlEditor({ renderTo: 'descEM', width: 575, height: 345, enableFont: false, readOnly: true });
							win.save = new Ext.Button({ renderTo: 'save_btnEM', text: 'Save', width: 100, disabled: true, handler: function(btn) {
								var ajax = new Object();
								ajax.action = 'update';
								ajax.id = win.record.id;
								ajax.item_id = param.id;
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
								
								Func.ajax({ param: ajax, url: URLS.base + 'panel/product/item_multi_title/action', callback: function(result) {
									Ext.Msg.alert('Informasi', result.message);
									if (result.status) {
										win.store.load();
										win.grid.read_mode();
									}
								} });
							} });
							win.cancel = new Ext.Button({ renderTo: 'cancel_btnEM', text: 'Cancel', width: 100, disabled: true, handler: function(btn) {
								win.grid.read_mode();
							} });
						}
					});
				},
				hide: function(w) {
					w.destroy();
					w = win = null;
				}
			}
		});
		win.show();
	}
	
	function compare_win(param) {
		var win = new Ext.Window({
			layout: 'fit', width: 1070, height: 480,
			closeAction: 'hide', plain: true, modal: true, title: 'Entry Compare',
			buttons: [ { text: 'Close', handler: function() { win.hide(); } }],
			listeners: {
				show: function(w) {
					Ext.Ajax.request({
						params: { form_name: 'item_compare' },
						url: URLS.base + 'panel/product/item/view',
						success: function(Result) {
							w.body.dom.innerHTML = Result.responseText;
							
							win.store = Ext.create('Ext.data.Store', {
								autoLoad: true, pageSize: 25, remoteSort: true,
								sorters: [{ property: 'name', direction: 'ASC' }],
								fields: [ 'id', 'name', 'desc', 'price', 'url' ],
								proxy: {
									type: 'ajax', extraParams: { item_id: param.id },
									url : URLS.base + 'panel/product/item_compare/grid', actionMethods: { read: 'POST' },
									reader: { type: 'json', root: 'rows', totalProperty: 'count' }
								}
							});
							
							win.grid = new Ext.grid.GridPanel({
								viewConfig: { forceFit: true }, store: win.store, height: 400, renderTo: 'grid-compareEM',
								columns: [ {
											header: 'Name', dataIndex: 'name', sortable: true, filter: true, width: 200, flex: 1
								} ],
								tbar: [ {
										text: 'Tambah', iconCls: 'addIcon', tooltip: 'Tambah', handler: function() {
											win.grid.edit_mode();
											
											// set new record
											win.record = { id: 0 };
											win.name.reset();
											win.desc.reset();
											win.price.reset();
											win.url.reset();
											win.name.focus();
										}
									}, '-', {
										text: 'Ubah', iconCls: 'editIcon', tooltip: 'Ubah', handler: function() {
											// set data
											var row = win.grid.getSelectionModel().getSelection();
											if (row.length == 0) {
												Ext.Msg.alert('Informasi', 'Silahkan memilih data.');
												return false;
											}
											
											win.grid.edit_mode();
											var record = row[0].data;
											win.record = { id: record.id };
											win.name.setValue(record.name);
											win.desc.setValue(record.desc);
											win.price.setValue(record.price);
											win.url.setValue(record.url);
										}
									}, '-', {
										text: 'Hapus', iconCls: 'delIcon', tooltip: 'Hapus', handler: function() { console.log('Hapus'); }
								} ],
								listeners: {
									'itemclick': function(grid, record, item) {
										win.name.setValue(record.data.name);
										win.desc.setValue(record.data.desc);
										win.price.setValue(record.data.price);
										win.url.setValue(record.data.url);
									}
								},
								edit_mode: function() {
									win.name.setReadOnly(false);
									win.desc.setReadOnly(false);
									win.price.setReadOnly(false);
									win.url.setReadOnly(false);
									win.save.setDisabled(false);
									win.cancel.setDisabled(false);
								},
								read_mode: function() {
									win.name.setReadOnly(true);
									win.desc.setReadOnly(true);
									win.price.setReadOnly(true);
									win.url.setReadOnly(true);
									win.save.setDisabled(true);
									win.cancel.setDisabled(true);
									
									win.name.reset();
									win.desc.reset();
									win.price.reset();
									win.url.reset();
								}
							});
							
							win.name = new Ext.form.TextField({ renderTo: 'nameEM', width: 575, allowBlank: false, blankText: 'Masukkan Judul', readOnly: true });
							win.desc = new Ext.form.HtmlEditor({ renderTo: 'descEM', width: 575, height: 285, enableFont: false, readOnly: true });
							win.url = new Ext.form.TextField({ renderTo: 'urlEM', width: 575, readOnly: true });
							win.price = new Ext.form.TextField({ renderTo: 'priceEM', width: 200, readOnly: true });
							win.save = new Ext.Button({ renderTo: 'save_btnEM', text: 'Save', width: 100, disabled: true, handler: function(btn) {
								var ajax = new Object();
								ajax.action = 'update';
								ajax.id = win.record.id;
								ajax.item_id = param.id;
								ajax.name = win.name.getValue();
								ajax.desc = win.desc.getValue();
								ajax.price = win.price.getValue();
								ajax.url = win.url.getValue();
								
								// Validation
								var is_valid = true;
								if (! win.name.validate()) {
									is_valid = false;
								}
								if (! is_valid) {
									return;
								}
								
								Func.ajax({ param: ajax, url: URLS.base + 'panel/product/item_compare/action', callback: function(result) {
									Ext.Msg.alert('Informasi', result.message);
									if (result.status) {
										win.store.load();
										win.grid.read_mode();
									}
								} });
							} });
							win.cancel = new Ext.Button({ renderTo: 'cancel_btnEM', text: 'Cancel', width: 100, disabled: true, handler: function(btn) {
								win.grid.read_mode();
							} });
						}
					});
				},
				hide: function(w) {
					w.destroy();
					w = win = null;
				}
			}
		});
		win.show();
	}
	
	Renderer.InitWindowSize({ Panel: -1, Grid: main_grid, Toolbar: 70 });
	Ext.EventManager.onWindowResize(function() {
		Renderer.InitWindowSize({ Panel: -1, Grid: main_grid, Toolbar: 70 });
    }, main_grid);
});