// jQuery Alert Dialogs Plugin
//
// Version 1.1
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 14 May 2009
//
// Visit http://abeautifulsite.net/notebook/87 for more information
//
// Usage:
//		jAlert( message, [title, callback] )
//		jConfirm( message, [title, callback] )
//		jPrompt( message, [value, title, callback] )
// 
// History:
//
//		1.00 - Released (29 December 2008)
//
//		1.01 - Fixed bug where unbinding would destroy all resize events
//
// License:
// 
// This plugin is dual-licensed under the GNU General Public License and the MIT License and
// is copyright 2008 A Beautiful Site, LLC. 
//
(function($) {
	
	$.alerts = {
		
		// These properties can be read/written by accessing $.alerts.propertyName from your scripts at any time
		
		verticalOffset: -75,                // vertical offset of the dialog from center screen, in pixels
		horizontalOffset: 0,                // horizontal offset of the dialog from center screen, in pixels/
		repositionOnResize: true,           // re-centers the dialog on window resize
		overlayOpacity: .25,                // transparency level of overlay
		overlayColor: '#eee',               // base color of overlay
		draggable: false,                    // make the dialogs draggable (requires UI Draggables plugin)
		okButton: '&nbsp;OK&nbsp;',         // text for the OK button
		cancelButton: '&nbsp;Cancel&nbsp;', // text for the Cancel button
		dialogClass: null,                  // if specified, this class will be applied to all dialogs
		
		// Public methods
		
		alert: function(message, title, callback) {
			if( title == null ) title = 'Alert';
			$.alerts._show(title, message, null, 'alert', function(result) {
				if( callback ) callback(result);
			},'','','');
		},
		
		confirm: function(message, title, callback) {
			if( title == null ) title = 'Confirm';
			$.alerts._show(title, message, null, 'confirm', function(result) {
				if( callback ) callback(result);
			},'','','');
		},
			
		prompt: function(message, value, title, callback) {
			if( title == null ) title = 'Prompt';
			$.alerts._show(title, message, value, 'prompt', function(result) {
				if( callback ) callback(result);
			},'','','');
		},
		
		print: function(message, title, callback) {
			if( title == null ) title = 'Print';
			$.alerts._show(title, message, null, 'print', function(result) {
				if( callback ) callback(result);
			},'','','');
		},
		
		choise: function(message, title, callback) {
			if( title == null ) title = 'Print';
			$.alerts._show(title, message, null, 'choise', function(result) {
				if( callback ) callback(result);
			},'','','');
		},
		
		
		
		loading: function(message, title, callback) {
			if( title == null ) title = 'Loading';
			$.alerts._showLoading(title, message, null, 'loading', function(result) {
				if( callback ) callback(result);
			},'','','');
		},
		
		popup: function(message, title, callback, data, width, height){
			if( title == null ) title = 'POP UP';
			$.alerts._showPopup(title, message, null, 'popup', function(result) {
				if( callback ) callback(result);
			}, data, width, height);
		},
		
		popupfrmsearch: function(message, title, callback, data, width, height){
			if( title == null ) title = 'POP UP FORM SEARCH';
			$.alerts._showPopupFrmSearch(title, message, null, 'popupfrmsearch', function(result) {
				if( callback ) callback(result);
			}, data, width, height);
		},
		
		closepopup: function() {
			$.alerts._hidePopup();
		},
		
		
		
		// Private methods
		
		_showPopupFrmSearch: function(title, msg, value, type, callback, data, width, height) {
			var add = "loading";
			$.alerts._hidePopupFrmSearch();
			$.alerts._overlayPopupFrmSearch('show');
			
			
			$.alerts.horizontalOffset = 0;
			$.alerts.verticalOffset = -205;
			$.alerts.draggable = true;//popup_container : 400px | popup_content_popup : 311px				
			var heightContent = parseInt(height) - 89;
			var body_append = '';
			body_append = '<div id="popup_container_popup_frmsearch" style="width:'+width+'px;display:none;">';
			body_append+= '		<div id="popup_title_popup_title_frmsearch" style="background: #eee;border-bottom: solid 1px #999;">';
			body_append+= '			<div style="float:right;padding:5px;line-height: 1.75em;color: #666;cursor: default;margin: 0em;">';
			body_append+= '				<img id="popup_close_frmsearch" src="'+base_url+'assets/images/cross.png" alt="close" align="absmiddle" style="cursor:pointer;" />';
			body_append+= '			</div><br style="clear:both;" />';
			body_append+= '		 </div>';
			body_append+= '		 <div id="popup_content_popup_popup_frmsearch"></div>';
			body_append+= '	</div>';
			$("BODY").append(body_append);
			
			$('#popup_container_popup_frmsearch').fadeIn('fast');
			
			$.ajax({
				url: msg,
				type: 'POST',
				data: data,
				success: function(html){
					$("#popup_content_popup_popup_frmsearch").html(html);
				},
				beforeSend:function(){							
					$("#popup_content_popup_popup_frmsearch").html('<div style="text-align:center;"><img src="'+base_url+'assets/images/loading.gif" alt="loading" /><br><b>Silahkan Tunggu...</b></div>');
				}												
			});
			
			if( $.alerts.dialogClass ) $("#popup_container_popup_frmsearch").addClass($.alerts.dialogClass);			
			// IE6 Fix
			var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed'; 
						
			$("#popup_container_popup_frmsearch").css({
				position: pos,
				zIndex: 9999,
				padding: 0,
				margin: 0
			});
			
			$("#popup_title_popup_frmsearch").text(title);
			$("#popup_content_popup_frmsearch").addClass(type);
			$("#popup_message_popup_frmsearch").text(msg);
			$("#popup_message_popup_frmsearch").html( $("#popup_message_popup_frmsearch").text().replace(/\n/g, '<br />') );
			
			$("#popup_container_popup_frmsearch").css({
				minWidth: $("#popup_container_popup_frmsearch").outerWidth(),
				maxWidth: $("#popup_container_popup_frmsearch").outerWidth()
			});
			
			$.alerts._repositionPopupFrmSearch();
			$.alerts._maintainPosition(true);
			
			$("#popup_close_frmsearch").click( function() {				
				$('#popup_container_popup_frmsearch').fadeOut('fast',function(){
																$.alerts._hidePopupFrmSearch();
															});
				callback(true);
			});
			
			// Make draggable
			if( $.alerts.draggable ) {
				try {
					$("#popup_container_popup_frmsearch").draggable({ handle: $("#popup_title_popup_title_frmsearch") });
					$("#popup_title_popup_title_frmsearch").css({ cursor: 'move' });
				} catch(e) { /* requires jQuery UI draggables */ }
			}
		},
		
		_showPopup: function(title, msg, value, type, callback, data, width, height) {
			var add = "loading";
			$.alerts._hidePopup();
			$.alerts._overlayPopup('show');
			
			
			$.alerts.horizontalOffset = 0;
			$.alerts.verticalOffset = -205;
			$.alerts.draggable = true;//popup_container : 400px | popup_content_popup : 311px				
			var heightContent = parseInt(height) - 89;
			var body_append = '';
			body_append = '<div id="popup_container_popup" style="width:'+width+'px;display:none;">';
			body_append+= '		<div id="popup_title_popup_title" style="background: #eee;border-bottom: solid 1px #999;">';
			body_append+= '			<div style="float:right;padding:5px;line-height: 1.75em;color: #666;cursor: default;margin: 0em;">';
			body_append+= '				<img id="popup_close" src="'+base_url+'assets/images/cross.png" alt="close" align="absmiddle" style="cursor:pointer;" />';
			body_append+= '			</div><br style="clear:both;" />';
			body_append+= '		 </div>';
			body_append+= '		 <div id="popup_content_popup_popup"></div>';
			body_append+= '	</div>';
			$("BODY").append(body_append);
			
			$('#popup_container_popup').fadeIn('fast');
			
			$.ajax({
				url: msg,
				type: 'POST',
				data: data,
				success: function(html){
					$("#popup_content_popup_popup").html(html);
				},
				beforeSend:function(){							
					$("#popup_content_popup_popup").html('<div style="text-align:center;"><img src="'+base_url+'assets/images/loading.gif" alt="loading" /><br><b>PLEASE WAIT ...</b></div>');
				}												
			});
			
			if( $.alerts.dialogClass ) $("#popup_container_popup").addClass($.alerts.dialogClass);			
			// IE6 Fix
			var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed'; 
						
			$("#popup_container_popup").css({
				position: pos,
				zIndex: 999,
				padding: 0,
				margin: 0
			});
			
			$("#popup_title_popup").text(title);
			$("#popup_content_popup").addClass(type);
			$("#popup_message_popup").text(msg);
			$("#popup_message_popup").html( $("#popup_message_popup").text().replace(/\n/g, '<br />') );
			
			$("#popup_container_popup").css({
				minWidth: $("#popup_container_popup").outerWidth(),
				maxWidth: $("#popup_container_popup").outerWidth()
			});
			
			$.alerts._repositionPopup();
			$.alerts._maintainPosition(true);
						
			$("#popup_close").click( function() {				
				$('#popup_container_popup').fadeOut('fast',function(){
																$.alerts._hidePopup();
															});
				//$.alerts._hidePopup();
				callback(true);
			});
			
			// Make draggable
			if( $.alerts.draggable ) {
				try {
					$("#popup_container_popup").draggable({ handle: $("#popup_title_popup_title") });
					$("#popup_title_popup_title").css({ cursor: 'move' });
				} catch(e) { /* requires jQuery UI draggables */ }
			}
		},
		
		
		_showLoading: function(title, msg, value, type, callback, data, width, height) {
			var add = "loading";
			$.alerts._hideLoading();
			$.alerts._overlayLoading('show');
			
			$.alerts.horizontalOffset = 0;
			$.alerts.verticalOffset = -75;
			$.alerts.draggable = false;
			$("BODY").append(
			  '<div id="popup_container_loading" class="'+add+'">' +
				'<h1 id="popup_title_loading"></h1>' +
				'<div id="popup_content_loading">' +
				  '<div id="popup_message_loading"></div>' +
				'</div>' +
			  '</div>');
			
			
			if( $.alerts.dialogClass ) $("#popup_container_loading").addClass($.alerts.dialogClass);			
			// IE6 Fix
			var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed'; 
						
			$("#popup_container_loading").css({
				position: pos,
				zIndex: 99999,
				padding: 0,
				margin: 0
			});
			
			$("#popup_title_loading").text(title);
			$("#popup_content_loading").addClass(type);
			$("#popup_message_loading").text(msg);
			$("#popup_message_loading").html( $("#popup_message_loading").text().replace(/\n/g, '<br />') );
			
			$("#popup_container_loading").css({
				minWidth: $("#popup_container_loading").outerWidth(),
				maxWidth: $("#popup_container_loading").outerWidth()
			});
			
			$.alerts._repositionLoading();
			$.alerts._maintainPosition(true);
			
			switch( type ) {
				case 'loading':
					$("#popup_message_loading").remove();
					$("#popup_content_loading").after('<div style="text-align:center;"><img src="'+base_url+'assets/images/loading.gif" alt="loading"  /><br><b>PLEASE WAIT ...</b></div><div id="popup_panel">&nbsp;</div>');
				break;
			}
			
			// Make draggable
			if( $.alerts.draggable ) {
				try {
					$("#popup_container_loading").draggable({ handle: $("#popup_title_loading") });
					$("#popup_title_loading").css({ cursor: 'move' });
				} catch(e) { /* requires jQuery UI draggables */ }
			}
			
		},
		
		
		_show: function(title, msg, value, type, callback, data, width, height) {
			if(type=="loading"){
				var add = "loading";
				$.alerts._hideLoading();
				$.alerts._overlayLoading('show');
			}
			else{
				
				var add = "";
				$.alerts._hide();
				$.alerts._overlay('show');
			}
			
			if(type!="popup"){
				
				var min_width = "";
				if(type=="choise"){min_width = "min-width: 440px;";}
				if(type=="print"){min_width = "min-width: 455px;";}
				
				$.alerts.horizontalOffset = 0;
				$.alerts.verticalOffset = -75;
				$.alerts.draggable = false;
				$("BODY").append(
				  '<div id="popup_container" class="'+add+'" style="'+min_width+'">' +
					'<h1 id="popup_title"></h1>' +
					'<div id="popup_content">' +
					  '<div id="popup_message"></div>' +
					'</div>' +
				  '</div>');
			}
			else{				
				$.alerts.horizontalOffset = 0;
				$.alerts.verticalOffset = -205;
				$.alerts.draggable = true;//popup_container : 400px | popup_content_popup : 311px				
				var heightContent = parseInt(height) - 89;
				$("BODY").append('<div id="popup_container" style="width:'+width+'px;"><h1 id="popup_title"></h1><div id="popup_content_popup" style="height:auto;"></div></div>');
				$.ajax({
					url: msg,
					type: 'GET',
					data: data,
					success: function(html){
						$("#popup_content_popup").html(html);
					},
					beforeSend:function(){							
						$("#popup_content_popup").html('<div style="text-align:center;"><img src="'+base_url+'assets/images/loading.gif" alt="loading" /><br><b>Please wait ...</b></div>');
					}									
				});
				
			}
			
			
					
			if( $.alerts.dialogClass ) $("#popup_container").addClass($.alerts.dialogClass);			
			// IE6 Fix
			var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed'; 
						
			$("#popup_container").css({
				position: pos,
				zIndex: 9999,
				padding: 0,
				margin: 0
			});
			
			$("#popup_title").text(title);
			$("#popup_content").addClass(type);
			$("#popup_message").text(msg);
			$("#popup_message").html( $("#popup_message").text().replace(/\n/g, '<br />') );
			
			$("#popup_container").css({
				minWidth: $("#popup_container").outerWidth(),
				maxWidth: $("#popup_container").outerWidth()
			});
			
			$.alerts._reposition();
			$.alerts._maintainPosition(true);
				
			
			switch( type ) {
				case 'alert':
					$("#popup_message").after('<div id="popup_panel"><button id="popup_ok" class="btn btn-small btn-success"><i class="icon-ok"></i>' + $.alerts.okButton + '</button></div>');
					
					
					$("#popup_ok").click( function() {
						$.alerts._hide();
						callback(true);
					});
					$("#popup_ok").focus().keypress( function(e) {
						if( e.keyCode == 13 || e.keyCode == 27 ) $("#popup_ok").trigger('click');
					});
				break;
				case 'confirm':
					$("#popup_message").after('<div id="popup_panel"><button id="popup_ok" class="btn btn-small btn-success"><i class="icon-ok"></i>' + $.alerts.okButton + '</button> <button id="popup_cancel" class="btn btn-small btn-danger"><i class="icon-remove"></i>' + $.alerts.cancelButton + '</button></div>');
					
					$("#popup_ok").click( function() {
						$.alerts._hide();
						if( callback ) callback(true);
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback(false);
					});
					$("#popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
				break;
				case 'prompt':
					$("#popup_message").append('<br /><input type="text" size="30" id="popup_prompt" />').after('<div id="popup_panel"><input type="button" value="' + $.alerts.okButton + '" id="popup_ok" /> <input type="button" value="' + $.alerts.cancelButton + '" id="popup_cancel" /></div>');
					$("#popup_prompt").width( $("#popup_message").width() );
					$("#popup_ok").click( function() {
						var val = $("#popup_prompt").val();
						$.alerts._hide();
						if( callback ) callback( val );
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback( null );
					});
					$("#popup_prompt, #popup_ok, #popup_cancel").keypress( function(e) {
						if( e.keyCode == 13 ) $("#popup_ok").trigger('click');
						if( e.keyCode == 27 ) $("#popup_cancel").trigger('click');
					});
					if( value ) $("#popup_prompt").val(value);
					$("#popup_prompt").focus().select();
				break;
				case 'print':
					$("#popup_message").after('<div id="popup_panel"><button type="button" class="xls" id="popup_xls">Excel</button>&nbsp;<button type="button" class="pdf" id="popup_pdf2">ORIGINAL PO (PDF)</button>&nbsp;<button type="button" class="pdf" id="popup_pdf">E.D.I PO (PDF)</button>&nbsp;<button type="button" class="cancel" id="popup_cancel">Cancel</button></div>');
					$("#popup_pdf").click( function() {
						$.alerts._hide();
						if( callback ) callback('pdf');
					});
					$("#popup_pdf2").click( function() {
						$.alerts._hide();
						if( callback ) callback('pdf2');
					});
					$("#popup_xls").click( function() {
						$.alerts._hide();
						if( callback ) callback('xls');
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback('cancel');
					});
					//$("#popup_xls").focus();
				break;
				case 'choise':
					/*$("#popup_message").after('<div id="popup_panel"><button type="button" class="btn add" id="popup_stay">Tambah Persyaratan Baru</button>&nbsp;<button type="button" class="btn next" id="popup_next">Lanjutkan pengisian</button>&nbsp;<button type="button" class="cancel" id="popup_cancel">Kembali</button></div>');*/
					$("#popup_message").after('<div id="popup_panel"><button type="button" class="cancel" id="popup_cancel">Kembali</button>&nbsp;<button type="button" class="btn add" id="popup_stay">Tambah Persyaratan Baru</button>&nbsp;<button type="button" class="btn next" id="popup_next">Lanjutkan pengisian</button></div>');
					$("#popup_stay").click( function() {
						$.alerts._hide();
						if( callback ) callback('stay');
					});
					$("#popup_next").click( function() {
						$.alerts._hide();
						if( callback ) callback('next');
					});
					$("#popup_cancel").click( function() {
						$.alerts._hide();
						if( callback ) callback('cancel');
					});
					$("#popup_pdf").focus();
				break;
				case 'loading':
					$("#popup_message").remove();
					$("#popup_content").after('<div style="text-align:center;"><img src="'+base_url+'assets/images/loading.gif" alt="loading" /><br><b>Please wait ...</b></div><div id="popup_panel">&nbsp;</div>');
				break;
				case 'popup':
					$("#popup_content_popup").after('<div id="popup_panel_popup"><button type="button" class="button" id="popup_close"><span class="cross icon"></span>Close</button></div>');
					$("#popup_close").click( function() {
						$.alerts._hide();
						callback(true);
					});
				break;
				
				
			}
			
			// Make draggable
			if( $.alerts.draggable ) {
				try {
					$("#popup_container").draggable({ handle: $("#popup_title") });
					$("#popup_title").css({ cursor: 'move' });
				} catch(e) { /* requires jQuery UI draggables */ }
			}
		},
		
		_hide: function() {
			$("#popup_container").remove();
			$.alerts._overlay('hide');
			$.alerts._maintainPosition(false);
		},
		
		_hidePopup: function() {
			$("#popup_container_popup").remove();
			$.alerts._overlayPopup('hide');
			$.alerts._maintainPosition(false);
		},
		
		_hidePopupFrmSearch: function() {
			$("#popup_container_popup_frmsearch").remove();
			$.alerts._overlayPopupFrmSearch('hide');
			$.alerts._maintainPosition(false);
		},
		
		_hideLoading: function() {
			$("#popup_container_loading.loading").remove();
			$.alerts._overlayLoading('hide');
			$.alerts._maintainPosition(false);
		},
		
		_overlayLoading: function(status) {//alert('test_overlayLoading');
			switch( status ) {
				case 'show':
					$.alerts._overlayLoading('hide');
					$("BODY").append('<div id="popup_overlay_loading" class="loading"></div>');
					$("#popup_overlay_loading.loading").css({
						position: 'absolute',
						zIndex: 9999,
						top: '0px',
						left: '0px',
						width: '100%',
						height: $(document).height(),
						background: $.alerts.overlayColor,
						opacity: $.alerts.overlayOpacity
					});
				break;
				case 'hide':
					$("#popup_overlay_loading.loading").remove();
				break;
			}
		},
		
		_overlay: function(status) {
			switch( status ) {
				case 'show':
					$.alerts._overlay('hide');
					$("BODY").append('<div id="popup_overlay"></div>');
					$("#popup_overlay").css({
						position: 'absolute',
						zIndex: 9999,
						top: '0px',
						left: '0px',
						width: '100%',
						height: $(document).height(),
						background: $.alerts.overlayColor,
						opacity: $.alerts.overlayOpacity
					});
				break;
				case 'hide':
					$("#popup_overlay").remove();
				break;
			}
		},
		
		_overlayPopup: function(status) {
			switch( status ) {
				case 'show':
					$.alerts._overlay('hide');
					$("BODY").append('<div id="popup_overlay_popup"></div>');
					$("#popup_overlay_popup").css({
						position: 'absolute',
						zIndex: 999,
						top: '0px',
						left: '0px',
						width: '100%',
						height: $(document).height(),
						background: $.alerts.overlayColor,
						opacity: $.alerts.overlayOpacity
					});
				break;
				case 'hide':
					$("#popup_overlay_popup").remove();
				break;
			}
		},
		
		_overlayPopupFrmSearch: function(status) {
			switch( status ) {
				case 'show':
					$.alerts._overlay('hide');
					$("BODY").append('<div id="popup_overlay_popup_frmsearch"></div>');
					$("#popup_overlay_popup_frmsearch").css({
						position: 'absolute',
						zIndex: 9999,
						top: '0px',
						left: '0px',
						width: '100%',
						height: $(document).height(),
						background: $.alerts.overlayColor,
						opacity: $.alerts.overlayOpacity
					});
				break;
				case 'hide':
					$("#popup_overlay_popup_frmsearch").remove();
				break;
			}
		},
		
		_reposition: function() {
			var top = (($(window).height() / 2) - ($("#popup_container").outerHeight() / 2)) + $.alerts.verticalOffset;
			var left = (($(window).width() / 2) - ($("#popup_container").outerWidth() / 2)) + $.alerts.horizontalOffset;
			if( top < 0 ) top = 0;
			if( left < 0 ) left = 0;
			
			// IE6 fix
			if( $.browser.msie && parseInt($.browser.version) <= 6 ) top = top + $(window).scrollTop();
			
			$("#popup_container").css({
				top: top + 'px',
				left: left + 'px'
			});
			$("#popup_overlay").height( $(document).height() );
		},
		
		_repositionLoading: function() {
			var top = (($(window).height() / 2) - ($("#popup_container_loading").outerHeight() / 2)) + $.alerts.verticalOffset;
			var left = (($(window).width() / 2) - ($("#popup_container_loading").outerWidth() / 2)) + $.alerts.horizontalOffset;
			if( top < 0 ) top = 0;
			if( left < 0 ) left = 0;
			
			// IE6 fix
			if( $.browser.msie && parseInt($.browser.version) <= 6 ) top = top + $(window).scrollTop();
			
			$("#popup_container_loading").css({
				top: top + 'px',
				left: left + 'px'
			});
			$("#popup_overlay_loading").height( $(document).height() );
		},
		
		_repositionPopup: function() {
			var top = (($(window).height() / 2) - ($("#popup_container_popup").outerHeight() / 2)) + $.alerts.verticalOffset;
			var left = (($(window).width() / 2) - ($("#popup_container_popup").outerWidth() / 2)) + $.alerts.horizontalOffset;
			if( top < 0 ) top = 0;
			if( left < 0 ) left = 0;
			
			// IE6 fix
			if( $.browser.msie && parseInt($.browser.version) <= 6 ) top = top + $(window).scrollTop();
			
			$("#popup_container_popup").css({
				top: top + 'px',
				left: left + 'px'
			});
			$("#popup_overlay_popup").height( $(document).height() );
		},
		
		_repositionPopupFrmSearch: function() {
			var top = (($(window).height() / 2) - ($("#popup_container_popup_frmsearch").outerHeight() / 2)) + $.alerts.verticalOffset;
			var left = (($(window).width() / 2) - ($("#popup_container_popup_frmsearch").outerWidth() / 2)) + $.alerts.horizontalOffset;
			if( top < 0 ) top = 0;
			if( left < 0 ) left = 0;
			
			// IE6 fix
			if( $.browser.msie && parseInt($.browser.version) <= 6 ) top = top + $(window).scrollTop();
			
			$("#popup_container_popup_frmsearch").css({
				top: top + 'px',
				left: left + 'px'
			});
			$("#popup_overlay_popup_frmsearch").height( $(document).height() );
		},
		
		
		
		_maintainPosition: function(status) {
			if( $.alerts.repositionOnResize ) {
				switch(status) {
					case true:
						$(window).bind('resize', $.alerts._reposition);
					break;
					case false:
						$(window).unbind('resize', $.alerts._reposition);
					break;
				}
			}
		}
		
	}
	
	// Shortuct functions
	jAlert = function(message, title, callback) {
		$.alerts.alert(message, title, callback);
	}
	
	jConfirm = function(message, title, callback) {
		$.alerts.confirm(message, title, callback);
	};
		
	jPrompt = function(message, value, title, callback) {
		$.alerts.prompt(message, value, title, callback);
	};
	
	jPrint = function(message, title, callback) {
		$.alerts.print(message, title, callback);
	};
	
	jChoise = function(message, title, callback) {
		$.alerts.choise(message, title, callback);
	};
	
	jLoadingOpen = function(message, title, callback) {
		$.alerts.loading(message, title, callback);
	};
	
	jLoadingClose = function(message, title, callback) {
		$.alerts._hideLoading();
		/*$('#popup_overlay_loading').fadeOut('slow',function(){
													$.alerts._hideLoading();
												});*/
	};
	
	jpopup = function(url, title, data, width, height) {
		var callback = '';
		$.alerts.popup(url, title, callback, data, width, height);
	};
	
	jpopup_frmsearch = function(url, title, data, width, height) {
		var callback = '';
		$.alerts.popupfrmsearch(url, title, callback, data, width, height);
	};
	
	jpopup_close = function() {
		$.alerts.closepopup();
	};
	
	
})(jQuery);