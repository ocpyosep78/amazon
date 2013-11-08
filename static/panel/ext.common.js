var DATE_FORMAT = 'd-m-Y';
var TIME_FORMAT = 'H:i';

var Func = {
	ArrayToJson: function(Data) {
		var Temp = '';
		for (var i = 0; i < Data.length; i++) {
			Temp = (Temp.length == 0) ? Func.ObjectToJson(Data[i]) : Temp + ',' + Func.ObjectToJson(Data[i]);
		}
		return '[' + Temp + ']';
	},
	GetName: function(value) {
		var result = value.trim().replace(new RegExp(/[^0-9a-z]+/gi), '-').toLowerCase();
		result = result.replace(new RegExp(/^\-/gi), '').toLowerCase();
		result = result.replace(new RegExp(/\-$/gi), '').toLowerCase();
		return result;
	},
	InArray: function(Value, Array) {
		var Result = false;
		for (var i = 0; i < Array.length; i++) {
			if (Value == Array[i]) {
				Result = true;
				break
			}
		}
		return Result;
	},
	IsEmpty: function(value) {
		var Result = false;
		if (value == null || value == 0) {
			Result = true;
		} else if (typeof(value) == 'string') {
			value = Helper.Trim(value);
			if (value.length == 0) {
				Result = true;
			}
		}

		return Result;
	},
	ObjectToJson: function(obj) {
		var str = '';
		for (var p in obj) {
			if (obj.hasOwnProperty(p)) {
				if (obj[p] != null) {
					str += (str.length == 0) ? str : ',';
					str += '"' + p + '":"' + obj[p] + '"';
				}
			}
		}
		str = '{' + str + '}';
		return str;
	},
	SetValue: function(Param) {
		// Func.SetValue({ Action : 'City', ForceID: Param.city_id, Combo: WinGateway.city });

		Ext.Ajax.request({
			url: Web.HOST + '/index.php/combo',
			params: { Action : Param.Action, ForceID: Param.ForceID },
			success: function(Result) {
				Param.Combo.store.loadData(eval(Result.responseText));
				Param.Combo.setValue(Param.ForceID);
			}
		});
	},
	SyncComboParam: function(c, Param) {
		var ArrayConfig = ['renderTo', 'name', 'fieldLabel', 'anchor', 'id', 'allowBlank', 'blankText', 'tooltip', 'iconCls', 'width', 'listeners', 'value'];
		for (var i = 0; i < ArrayConfig.length; i++) {
			if (Param[ArrayConfig[i]] != null) {
				c[ArrayConfig[i]] = Param[ArrayConfig[i]];
			}
		}
		return c;
	},
	Trim: function(value) {
		return value.replace(/^\s+|\s+$/g,'');
	},
	
	ajax: function(p) {
		p.json = (p.json == null) ? true : p.json;
		
		Ext.Ajax.request({
			params: p.param,
			url: p.url,
			success: function(raw_result) {
				if (p.json) {
					eval('var result = ' + raw_result.responseText)
				} else {
					var result = raw_result;
				}
				
				if (p.callback != null) {
					p.callback(result);
				}
			}
		});
	}
}

var Renderer = {
	GetDateFromString: {
		Date: function(Value) {
			if (Value.length < 10) {
				return '';
			}

			var RawValue = Value.substr(0, 10);
			if (RawValue == '0000-00-00') {
				return '';
			}

			return RawValue;
		},
		Time: function(Value) {
			if (Value.length < 19) {
				return '';
			}

			var RawValue = Value.substr(11, 5);
			if (RawValue == '00:00') {
				return '';
			}

			return RawValue;
		}
	},
	ShowFormat: {
		Date: function(Value) {
			if (Value == null) {
				return '';
			} else if (typeof(Value) == 'string') {
				return Value;
			}

			var Day = Value.getDate();
			var DayText = (Day.toString().length == 1) ? '0' + Day : Day;
			var Month = Value.getMonth() + 1;
			var MonthText = (Month.toString().length == 1) ? '0' + Month : Month;
			var Date = Value.getFullYear() + '-' + MonthText + '-' + DayText;
			return Date;
		},
		Time: function(Value) {
			if (typeof(Value) == 'string' && Value == '') {
				return '00:00';
			}

			var Hour = Value.getHours();
			var HourText = (Hour.toString().length == 1) ? '0' + Hour : Hour;
			var Minute = Value.getMinutes();
			var MinuteText = (Minute.toString().length == 1) ? '0' + Minute : Minute;
			var Time = HourText + ':' + MinuteText;
			return Time;
		}
	},
	InitWindowSize: function(Param) {
		Renderer.AutoWindowSize(Param);
	},
	AutoWindowSize: function(Param) {
		Param.IsTabPanel = (Param.IsTabPanel == null) ? 0 : Param.IsTabPanel;

		if (typeof window.innerWidth != 'undefined') {
			WindowWidth = window.innerWidth;
			WindowHeight = window.innerHeight;
		} else if (typeof document.documentElement != 'undefined' && typeof document.documentElement.clientWidth != 'undefined' && document.documentElement.clientWidth != 0) {
			WindowWidth = document.documentElement.clientWidth,
			WindowHeight = document.documentElement.clientHeight
		} else {
			WindowWidth = document.getElementsByTagName('body')[0].clientWidth;
			WindowHeight = document.getElementsByTagName('body')[0].clientHeight;
		}

		if (Param.Panel == -1) {
			if (Param.IsTabPanel == 0) {
				Param.Grid.setHeight(WindowHeight);
			} else {
				var FreeSpace = WindowHeight - Param.Toolbar;
				FreeSpace = (FreeSpace < 400) ? 400 : FreeSpace;
				var TabActiveID = Param.Grid.getActiveTab().id;

				Param.Grid.setHeight(FreeSpace);
				for (var grid in Param.Grid.grid) {
					if (Param.Grid.grid[grid].TabOwner != null && Param.Grid.grid[grid].TabOwner == TabActiveID) {
						Param.Grid.grid[grid].setHeight(FreeSpace - 24);
					}
				}
			}
		} else {
			Param.Panel.setHeight(WindowHeight);
			Param.Grid.setHeight(WindowHeight - Param.Toolbar);
		}
	}
}

var Template = {
	Agama: new Ext.XTemplate(
		'<ul>' +
		'<li style="padding: 2px; font-weight: bold;">' +
			'<div style="float: left; width: 150px;">Agama ID</div>' +
			'<div style="float: left; width: 150px;">Agama</div>' +
			'<div class="clear"></div>' +
		'</li>' +
		'<tpl for="."><li class="x-boundlist-item">' +
			'<div style="float: left; width: 150px;">{AgamaID}</div>' +
			'<div style="float: left; width: 150px;">{Agama}</div>' +
			'<div class="clear"></div>' +
		'</li></tpl></ul>'
	)
}

var Store = {
	AdsenseOwner: function() {
		var Store = new Ext.create('Ext.data.Store', {
			fields: ['id', 'name', 'priority'],
			autoLoad: true, proxy: {
				type: 'ajax', extraParams: { action: 'adsense_owner' },
				url: URLS.base + 'panel/combo',
				reader: { type: 'json', root: 'res' },
				actionMethods: { read: 'POST' }
			}
		});
		return Store;
	},
	AdsenseType: function() {
		var Store = new Ext.create('Ext.data.Store', {
			fields: ['id', 'name', 'alias'],
			autoLoad: true, proxy: {
				type: 'ajax', extraParams: { action: 'adsense_type' },
				url: URLS.base + 'panel/combo',
				reader: { type: 'json', root: 'res' },
				actionMethods: { read: 'POST' }
			}
		});
		return Store;
	},
	Category: function() {
		var Store = new Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			autoLoad: true, proxy: {
				type: 'ajax', extraParams: { action: 'category' },
				url: URLS.base + 'panel/combo',
				reader: { type: 'json', root: 'res' },
				actionMethods: { read: 'POST' }
			}
		});
		return Store;
	},
	CommentStatus: function() {
		var Store = ['Cancel', 'Ongoing', 'Done'];
		return Store;
	},
	LinkShort: function() {
		var Store = new Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			autoLoad: true, proxy: {
				type: 'ajax', extraParams: { action: 'link_short' },
				url: URLS.base + 'panel/combo',
				reader: { type: 'json', root: 'res' },
				actionMethods: { read: 'POST' }
			}
		});
		return Store;
	},
	PostType: function() {
		var Store = new Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			autoLoad: true, proxy: {
				type: 'ajax', extraParams: { action: 'post_type' },
				url: URLS.base + 'panel/combo',
				reader: { type: 'json', root: 'res' },
				actionMethods: { read: 'POST' }
			}
		});
		return Store;
	},
	UserType: function() {
		var Store = new Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			autoLoad: true, proxy: {
				type: 'ajax', extraParams: { action: 'user_type' },
				url: URLS.base + 'panel/combo',
				reader: { type: 'json', root: 'res' },
				actionMethods: { read: 'POST' }
			}
		});
		return Store;
	}
}

var Combo = {
	Param: {
		Category: function(Param) {
			var p = {
				xtype: 'combo', store: Store.Category(), minChars: 1, selectOnFocus: true,
				valueField: 'id', displayField: 'name', readonly: true, editable: false
			}
			p = Func.SyncComboParam(p, Param);
			
			return p;
		},
		PostType: function(Param) {
			var p = {
				xtype: 'combo', store: Store.PostType(), minChars: 1, selectOnFocus: true,
				valueField: 'id', displayField: 'name', readonly: true, editable: false
			}
			p = Func.SyncComboParam(p, Param);
			
			return p;
		},
		FreeText: function(Param) {
			var p = {
				xtype: 'combo', store: Store.Agama(), minChars: 1, selectOnFocus: false,
				triggerAction: 'all', lazyRender: true, typeAhead: true,
				valueField: 'AgamaID', displayField: 'Agama',
				readonly: false, editable: true
			}
			p = Func.SyncComboParam(p, Param);

			return p;
		},
		Time: function(Param) {
			var p = { xtype: 'timefield', format: TIME_FORMAT, increment: 30 }
			p = Func.SyncComboParam(p, Param);

			return p;
		},
		UserType: function(Param) {
			var p = {
				xtype: 'combo', store: Store.UserType(), minChars: 1, selectOnFocus: true,
				valueField: 'id', displayField: 'name', readonly: true, editable: false
			}
			p = Func.SyncComboParam(p, Param);
			
			return p;
		}
	}
}

Combo.Class = {
	Category: function(Param) {
		var c = new Ext.form.ComboBox(Combo.Param.Category(Param));
		return c;
	},
	CommentStatus: function(Param) {
		var c = new Ext.form.ComboBox(Combo.Param.CommentStatus(Param));
		return c;
	},
	LinkShort: function(Param) {
		var c = new Ext.form.ComboBox(Combo.Param.LinkShort(Param));
		return c;
	},
	PostType: function(Param) {
		var c = new Ext.form.ComboBox(Combo.Param.PostType(Param));
		return c;
	},
	Time: function(Param) {
		var c = new Ext.form.field.Time(Combo.Param.Time(Param));
		return c;
	},
	UserType: function(Param) {
		var c = new Ext.form.ComboBox(Combo.Param.UserType(Param));
		return c;
	}
}