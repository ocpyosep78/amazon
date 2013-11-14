Ext.Loader.setConfig({ enabled: true });
Ext.Loader.setPath('Ext.ux', URLS.ext + '/examples/ux');
Ext.require([ 'Ext.grid.*', 'Ext.data.*', 'Ext.ux.grid.FiltersFeature', 'Ext.toolbar.Paging' ]);

Ext.onReady(function() {
	Ext.QuickTips.init();
	
	var main_store = Ext.create('Ext.data.Store', {
		autoLoad: true, pageSize: 25, remoteSort: true,
        sorters: [{ property: 'name', direction: 'ASC' }],
		fields: [ 'id', 'alias', 'name', 'code', 'desc', 'price_show', 'date_update', 'brand_id', 'brand_name', 'category_id', 'category_name', 'category_sub_id', 'category_sub_name', 'scrape_id' ],
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
					header: 'Name', dataIndex: 'name', sortable: true, filter: true, width: 200
			}, {	header: 'Category', dataIndex: 'category_name', sortable: true, filter: true, width: 200
			}, {	header: 'Sub Category', dataIndex: 'category_sub_name', sortable: true, filter: true, width: 200, flex: 1
			}, {	header: 'Code', dataIndex: 'code', sortable: true, filter: true, width: 200
			}, {	header: 'Brand', dataIndex: 'brand_name', sortable: true, filter: true, width: 200
			}, {	header: 'Price', dataIndex: 'price_show', sortable: true, filter: true, width: 200
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
							win.is_publish = new Ext.form.Checkbox({ renderTo: 'is_publishED' });
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
								win.tag.setValue(param.tag_implode);
								win.price_old.setValue(param.price_old);
								win.price_show.setValue(param.price_show);
								win.price_range.setValue(param.price_range);
								win.image.setValue(param.image);
								win.is_publish.setValue((param.is_publish == 1));
								
								win.brand.setValue(param.brand_id);
								win.category.setValue(param.category_id);
								Func.SetValue({ action : 'category_sub', ForceID: param.category_sub_id, Combo: win.category_sub });
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
				ajax.is_publish = (win.is_publish.getValue()) ? 1 : 0;
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
	
	Renderer.InitWindowSize({ Panel: -1, Grid: main_grid, Toolbar: 70 });
	Ext.EventManager.onWindowResize(function() {
		Renderer.InitWindowSize({ Panel: -1, Grid: main_grid, Toolbar: 70 });
    }, main_grid);
});