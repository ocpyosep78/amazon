var DATE_FORMAT = 'd-m-Y';
var TIME_FORMAT = 'H:i';

var Site = {
    Host: web.host,
    IsValidEmail: function (Email) {
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
        return emailPattern.test(Email);  
    },
    IsValidYear: function(Value) {
        var Result = true;
        
        Value = Value + '';
        Value = Value.replace(new RegExp('[^0-9]', 'gi'), '');
        
        if (Value.length != 4) {
            Result = false;
        }
        
        return Result;
    },
    IsValidPostalCode: function(Value) {
        var Result = true;
        
        Value = Value + '';
        Value = Value.replace(new RegExp('[^0-9]', 'gi'), '');
        
        if (Value.length != 5) {
            Result = false;
        }
        
        return Result;
    },
    GetTimeFromString: function(String) {
        String = $.trim(String);
        if (String == '') {
            return new Date();
        }
        
        var Data = new Date();
        var ArrayData = String.split('-');
        if (ArrayData[2] != null && ArrayData[2].length == 4) {
            Data = new Date(ArrayData[2] + '-' + ArrayData[1] + '-' + ArrayData[0]);
        }
        
        return Data;
    },
	SwapYearDay: function(String) {
		var Temp = Site.GetTimeFromString(String);
		var Result = Temp.getFullYear() + '-' + Temp.getMonth() + '-' + Temp.getDate();
		return Result;
	},
    Form: {
		InlineWarning: function(Input) {
			Input.parent('td').append('<div class="CntWarning">' + Input.attr('alt') + '</div>');
		},
        Start: function(Container) {
            var Input = jQuery('#' + Container + ' input');
            for (var i = 0; i < Input.length; i++) {
                if (Input.eq(i).hasClass('datepicker')) {
                    Input.eq(i).datepicker({ dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, yearRange: 'c-20:c+10' });
                }
                else if (Input.eq(i).hasClass('integer') || Input.eq(i).hasClass('postalcode')) {
                    Input.eq(i).keyup(function(Param) {
						var Value = jQuery(this).val();
                        Value = Value.replace(new RegExp('[^0-9\.]', 'gi'), '');
						
						if (Param.keyCode == 16 || Param.keyCode == 17 || Param.keyCode == 18 || Param.ctrlKey || Param.shiftKey) {
							return true;
						}
						
						jQuery(this).val(Value);
                    });
                }
				else if (Input.eq(i).hasClass('alphabet')) {
					Input.eq(i).keyup(function(Param) {
						var Value = jQuery(this).val();
						Value = Value.replace(new RegExp('[^a-z\ ]', 'gi'), '');
						
						if (Param.keyCode == 16 || Param.keyCode == 17 || Param.keyCode == 18 || Param.ctrlKey || Param.shiftKey) {
							return true;
						}
						
						jQuery(this).val(Value);
					});
				}
				else if (Input.eq(i).hasClass('float')) {
					Input.eq(i).keyup(function(Param) {
						var Value = jQuery(this).val();
						Value = Value.replace(new RegExp('[^0-9\.]', 'gi'), '');
						
						if (Param.keyCode == 16 || Param.keyCode == 17 || Param.keyCode == 18 || Param.ctrlKey || Param.shiftKey) {
							return true;
						}
						
						jQuery(this).val(Value);
					});
				}
            }
        },
        Validation: function(Container, Param) {
			Param.Inline = (Param.Inline == null) ? false : Param.Inline;
			
            var ArrayError = [];
			jQuery('.CntWarning').remove();
            
            var Input = jQuery('#' + Container + ' input');
            for (var i = 0; i < Input.length; i++) {
                Input.eq(i).removeClass('ui-state-highlight');
                
                if (Input.eq(i).hasClass('required')) {
                    var Value = jQuery.trim(Input.eq(i).val());
                    
                    if (Value == '') {
                        Input.eq(i).addClass('ui-state-highlight');
                        ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
						if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
                    }
                }
                if (Input.eq(i).hasClass('integer') || Input.eq(i).hasClass('datepicker')) {
                    var Value = jQuery.trim(Input.eq(i).val());
                    var ValueResult = Value.replace(new RegExp('[^0-9\-]', 'gi'), '');
                    
                    if (Value != ValueResult) {
                        Input.eq(i).addClass('ui-state-highlight');
                        ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
						if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
                    }
                }
                if (Input.eq(i).hasClass('datepicker')) {
                    var Result = true;
                    var Value = jQuery.trim(Input.eq(i).val());
                    var ArrayValue = Value.split('-');
                    
                    if (Value.length == 0) {
                        Result = true;
                    } else if (ArrayValue.length != 3) {
                        Result = false;
                    } else if (ArrayValue[0] == '' || ArrayValue[1] == '' || ArrayValue[2] == '') {
                        Result = false;
                    }
                    
                    if (!Result) {
                        Input.eq(i).addClass('ui-state-highlight');
						if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
                        ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
                    }
                }
                if (Input.eq(i).hasClass('email') && ! Site.IsValidEmail(Input.eq(i).val())) {
					if (Input.eq(i).val() != '') {
						Input.eq(i).addClass('ui-state-highlight');
						ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
						if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
					}
                }
                if (Input.eq(i).hasClass('postalcode') && (Input.eq(i).val().length != 0 && Input.eq(i).val().length != 5)) {
                    Input.eq(i).addClass('ui-state-highlight');
                    ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
					if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
                }
                if (Input.eq(i).hasClass('year') && (Input.eq(i).val().length != 0 && Input.eq(i).val().length != 4)) {
                    Input.eq(i).addClass('ui-state-highlight');
                    ArrayError[ArrayError.length] = Input.eq(i).attr('alt');
					if (Param.Inline) Site.Form.InlineWarning(Input.eq(i));
                }
            }
            
            var Select = jQuery('#' + Container +' select');
            for (var i = 0; i < Select.length; i++) {
                if (Select.eq(i).hasClass('required') && (Select.eq(i).val() == '' || Select.eq(i).val() == '-')) {
                    Select.eq(i).addClass('ui-state-highlight');
                    ArrayError[ArrayError.length] = Select.eq(i).attr('alt');
					if (Param.Inline) Site.Form.InlineWarning(Select.eq(i));
                } else {
                    Select.eq(i).removeClass('ui-state-highlight');
                }
            }
            
            var TextArea = jQuery('#' + Container +' textarea');
            for (var i = 0; i < TextArea.length; i++) {
                var Value = TextArea.eq(i).val();
                Value = jQuery.trim(Value);
                
                if (TextArea.eq(i).hasClass('required') && TextArea.eq(i).val() == '') {
                    TextArea.eq(i).addClass('ui-state-highlight');
                    ArrayError[ArrayError.length] = TextArea.eq(i).attr('alt');
                } else {
                    TextArea.eq(i).removeClass('ui-state-highlight');
                }
            }
            
            return ArrayError;
        },
        GetValue: function(Container) {
			var PrefixCheck = Container.substr(0, 1);
			if (! Func.InArray(PrefixCheck, ['.', '#'])) {
				Container = '#' + Container;
			}
			
            var Data = Object();
			var set_value = function(obj, name, value) {
				if (typeof(name) == 'undefined') {
					return obj;
				} else if (name.length < 3) {
					obj[name] = value;
					return obj;
				}
				
				var endfix = name.substr(name.length - 2, 2);
				if (endfix == '[]') {
					var name_valid = name.replace(endfix, '');
					if (obj[name_valid] == null) {
						obj[name_valid] = [];
					}
					obj[name_valid].push(value);
				} else {
					obj[name] = value;
				}
				
				return obj;
			}
            
            var Input = jQuery(Container + ' input, ' + Container + ' select, ' + Container + ' textarea');
            for (var i = 0; i < Input.length; i++) {
				var name = Input.eq(i).attr('name');
				var value = Input.eq(i).val();
				
				if (Input.eq(i).attr('type') == 'checkbox') {
					var Checked = Input.eq(i).attr('checked');
					if (typeof(Checked) == 'string' && Checked == 'checked') {
						Data = set_value(Data, name, value);
					} else {
						Data = set_value(Data, name, 0);
					}
				} else {
					Data = set_value(Data, name, value);
				}
            }
			
            return Data;
        }
    }
}

var Func = {
	ArrayToJson: function(Data) {
		var Temp = '';
		for (var i = 0; i < Data.length; i++) {
			Temp = (Temp.length == 0) ? Func.ObjectToJson(Data[i]) : Temp + ',' + Func.ObjectToJson(Data[i]);
		}
		return '[' + Temp + ']';
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
			value = Func.Trim(value);
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
	SwapDate: function(Value) {
		if (Value == null) {
			return '';
		}
		
		var ArrayValue = Value.split('-');
		if (ArrayValue.length != 3) {
			return '';
		}
		
		return ArrayValue[2] + '-' + ArrayValue[1] + '-' + ArrayValue[0];
	},
	Trim: function(value) {
		return value.replace(/^\s+|\s+$/g,'');
	},
	GetName: function(value) {
		var result = value.trim().replace(new RegExp(/[^0-9a-z]+/gi), '-').toLowerCase();
		result = result.replace(new RegExp(/^-/gi), '').toLowerCase();
		result = result.replace(new RegExp(/-$/gi), '').toLowerCase();
		return result;
	},
	GetStringFromDate: function(Value) {
		if (Value == null) {
			return '';
		} else if (typeof(Value) == 'string') {
			return Value;
		}
		
		var Day = Value.getDate();
		var DayText = (Day.toString().length == 1) ? '0' + Day : Day;
		var Month = Value.getMonth() + 1;
		var MonthText = (Month.toString().length == 1) ? '0' + Month : Month;
		var Date = DayText + '-' + MonthText + '-' + Value.getFullYear();
		return Date;
	},
	InitForm: function(p) {
		// Date Picker
		$(p.Container + ' .datepicker').datepicker({ format: DATE_FORMAT });
		
		$(p.Container + ' .tinymce').tinymce({
			// Location of TinyMCE script
			script_url : '../../../static/lib/tinymce/jscripts/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,sub,sup,|,charmap,iespell,media,advhr,|,fullscreen",
			theme_advanced_buttons2 : "cut,copy,paste,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,code,|,forecolor,backcolor",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			
//			plugins 							: "autoresize,style,table,advhr,advimage,advlink,emotions,inlinepopups,preview,media,contextmenu,paste,fullscreen,noneditable,xhtmlxtras,template,advlist",
			// Theme options
//			theme_advanced_buttons1 			: "undo,redo,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect",
//			theme_advanced_buttons2 			: "forecolor,backcolor,|,cut,copy,paste,pastetext,|,bullist,numlist,link,image,media,|,code,preview,fullscreen",

			// Example content CSS (should be your site CSS)
//			content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
		
		/*
		$(p.Container + ' .tinymce').tinymce({
			// Location of TinyMCE script
			script_url 							: '../../../static/lib/tiny_mce/tiny_mce.js',
			// General options
			theme 								: "advanced",
			plugins 							: "autoresize,style,table,advhr,advimage,advlink,emotions,inlinepopups,preview,media,contextmenu,paste,fullscreen,noneditable,xhtmlxtras,template,advlist",
			// Theme options
			theme_advanced_buttons1 			: "undo,redo,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,fontselect,fontsizeselect",
			theme_advanced_buttons2 			: "forecolor,backcolor,|,cut,copy,paste,pastetext,|,bullist,numlist,link,image,media,|,code,preview,fullscreen",
			theme_advanced_buttons3 			: "",
			theme_advanced_toolbar_location 	: "top",
			theme_advanced_toolbar_align 		: "left",
			theme_advanced_statusbar_location 	: "bottom",
			theme_advanced_resizing 			: false,
			font_size_style_values 				: "8pt,10px,12pt,14pt,18pt,24pt,36pt",
			init_instance_callback				: function(){
				function resizeWidth() {
					document.getElementById(tinyMCE.activeEditor.id+'_tbl').style.width='100%';
				}
				resizeWidth();
				$(window).resize(function() {
					resizeWidth();
				})
			}
		});
		/*	*/
		
		// Validation
		$(p.Container + ' form').validate({
			onkeyup: false, errorClass: 'error', validClass: 'valid',
			highlight: function(element) { $(element).closest('div').addClass("f_error"); },
			unhighlight: function(element) { $(element).closest('div').removeClass("f_error"); },
			errorPlacement: function(error, element) { $(element).closest('div').append(error); },
			rules: p.rule
		});
		
		// Twipsy
		$(p.Container + ' [rel=twipsy]').focus(function() {
			if ($(this).data('placement') == null) {
				$(this).attr('data-placement', 'right');
			}
			if ($(this).data('original-title') == null) {
				$(this).attr('data-original-title', $(this).attr('placeholder'));
			}
			
			$(this).twipsy('show');
		});
		$(p.Container + ' [rel=twipsy]').blur(function() { $(this).twipsy('hide'); });
	},
	ajax: function(p) {
		p.is_json = (p.is_json == null) ? 1 : p.is_json;
		
		$.ajax({ type: 'POST', url: p.url, data: p.param, success: function(data) {
			if (p.is_json == 1) {
				eval('var result = ' + data);
				p.callback(result);
			} else {
				p.callback(data);
			}
		} });
	},
	show_notice: function(p) {
		p.title = (p.title == null) ? 'Message' : p.title;
		p.text = (p.text == null) ? '-' : p.text;
		
		$('.gritter-close').click();
		$.gritter.add({ title: p.title, text: p.text, sticky: true, time: 3000 });
		
		// close glitter
		setTimeout(function() {
			var id = $('.gritter-item-wrapper').last().attr('id');
			$('#' + id).find('.gritter-close').click();
		}, 3000);
	},
	confirm_delete: function(p) {
		bootbox.confirm("Apa anda yakin ?", function(result) {
			if (! result) {
				return;
			}

			$.ajax({ type: "POST", url: p.url, data: p.data }).done(function( RawResult ) {
				eval('var result = ' + RawResult);
				Func.show_notice({ text: result.message });
				
				if (p.callback != null) {
					p.callback();
				}
			});
		});
	},
	init_datatable: function(p) {
		var cnt_id = '#' + p.id;
		
		var dt_param = {
			"aoColumns": p.column,
			"sAjaxSource": p.source,
			"bProcessing": true, "bServerSide": true, "sServerMethod": "POST", "sPaginationType": "full_numbers",
			"oLanguage": {
				"sSearch": "<span>Search:</span> ",
				"sInfo": "Showing <span>_START_</span> to <span>_END_</span> of <span>_TOTAL_</span> entries",
				"sLengthMenu": "_MENU_ <span>entries per page</span>"
			},
			"fnDrawCallback": function (oSettings) {
				if (p.callback != null) {
					p.callback();
				}
			}
		}
		if (p.fnServerParams != null) {
			dt_param.fnServerParams = p.fnServerParams;
		}
		if (p.aaSorting != null) {
			dt_param.aaSorting = p.aaSorting;
		}
		
		$(cnt_id).dataTable(dt_param);
		$(cnt_id + '_wrapper input').attr("placeholder", "Search here...");
		$(cnt_id + '_wrapper select').wrap("<div class='input-mini'></div>").chosen({ disable_search_threshold: 9999999 });
		
		// initiate
		if (p.init != null) {
			p.init();
		}
		
		var dt = {
			reload: function() {
				if ($(cnt_id + '_paginate .paginate_active').length > 0) {
					$(cnt_id + '_paginate .paginate_active').click();
				} else {
					$(cnt_id + '_length select').change();
				}
			}
		}
		
		return dt;
	},
	
	get_seeker: function() {
		var seeker_temp = $('.cnt-seeker').text();
		eval('var seeker = ' + seeker_temp);
		
		return seeker;
	},
	get_company: function() {
		var company_temp = $('.cnt-company').text();
		eval('var company = ' + company_temp);
		
		return company;
	},
	get_editor: function() {
		var editor_temp = $('.cnt-editor').text();
		eval('var editor = ' + editor_temp);
		
		return editor;
	},
	get_date_time: function(value, default_value) {
		if (value == null) {
			var t = new Date();
			var month = str_pad(t.getMonth() + 1, 2, '0', 'STR_PAD_LEFT');
			var tgl = str_pad(t.getDate(), 2, '0', 'STR_PAD_LEFT');
			var hour = str_pad(t.getHours(), 2, '0', 'STR_PAD_LEFT');
			var minute = str_pad(t.getMinutes(), 2, '0', 'STR_PAD_LEFT');
			var second = str_pad(t.getSeconds(), 2, '0', 'STR_PAD_LEFT');
			
			var date = (default_value == 1) ? tgl + '-' + month + '-' + t.getFullYear() : '';
			var time = (default_value == 1) ? hour + ':' + minute + ':' + second : '';
			return { date: date, time: time };
		}
		
		var array_value = value.split(' ');
		if (array_value.length == 2) {
			return { date: Func.SwapDate(array_value[0]), time: array_value[1] };
			
		}
	}
}
