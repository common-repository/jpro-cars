(function() {
	tinymce.PluginManager.add('jpro_cars', function( editor, url ) {
		var sh_tag = 'jpro_cars';

		//helper functions 
		function getAttr(s, n) {
			n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
			return n ?  window.decodeURIComponent(n[1]) : '';
		};

		function html( cls, data ,con) {
			var placeholder = url + '/img/' + getAttr(data,'type') + '.jpg';
			data = window.encodeURIComponent( data );
			content = window.encodeURIComponent( con );

			return '<img src="' + placeholder + '" class="mceItem ' + cls + '" ' + 'data-sh-attr="' + data + '" data-sh-content="'+ con+'" data-mce-resize="false" data-mce-placeholder="1" />';
		}

		function replaceShortcodes( content ) {
			return content.replace( /\[bs3_panel([^\]]*)\]([^\]]*)\[\/bs3_panel\]/g, function( all,attr,con) {
				return html( 'wp-jpro_cars', attr , con);
			});
		}

		function restoreShortcodes( content ) {
			return content.replace( /(?:<p(?: [^>]+)?>)*(<img [^>]+>)(?:<\/p>)*/g, function( match, image ) {
				var data = getAttr( image, 'data-sh-attr' );
				var con = getAttr( image, 'data-sh-content' );

				if ( data ) {
					return '<p>[' + sh_tag + data + ']' + con + '[/'+sh_tag+']</p>';
				}
				return match;
			});
		}

		//add popup
		editor.addCommand('jpro_cars_popup', function(ui, v) {
			//setup defaults
			var jp_title = '';
			if (v.jp_title)
				jp_title = v.jp_title;
			
			var jp_items = '';
			if (v.jp_items)
				jp_items = v.jp_items;
			
			var type = 'listing';
			if (v.type)
				type = v.type;
			
			var content = '';
			if (v.content)
				content = v.content;

			editor.windowManager.open( {
				title: 'JPro Shortcodes',
				body: [
					{	
						id: 'shortcode',
						type: 'listbox',
						name: 'type',
						label: 'Select Shortcode',
						value: type,
						'values': [
							{text: 'Cars Listing', value: 'listing'},
							{text: 'Cars Slider', value: 'slider'}
						],
						tooltip: 'Select Shortcode',
					}
					/*
					{
						type: 'textbox',
						name: 'jp_title',
						label: 'Custom Title',
						value: jp_title,
						tooltip: 'Leave blank for none'
					},
					{
						id: 'slideritems',
						type: 'textbox',
						name: 'jp_items',
						label: 'Slider Items',
						value: jp_items,
						tooltip: 'Leave blank for none'
					},
					{
						type: 'textbox',
						name: 'content',
						label: 'Panel Content',
						value: content,
						multiline: true,
						minWidth: 300,
						minHeight: 100
					}*/
				],
				onsubmit: function( e ) {
					
					if( e.data.type == 'listing' ) {
						var sh_tag = 'jpro_cars';
						var shortcode_str = '[' + sh_tag + ' title="" make="all" model="all" year="all" fuel="all" condition="all" mileage="all" price="all"';
					}
					
					if( e.data.type == 'slider' ) {
						var sh_tag = 'jpro_cars_slider';
						var shortcode_str = '[' + sh_tag + ' title="" make="" model="" equipment="" load_cars="9" items="3"';
					}
					
					if( e.data.type == 'search' ) {
						var sh_tag = 'jpro_cars_search';
						var shortcode_str = '[' + sh_tag + ' title=""';
					}
					
					//var shortcode_str = '[' + sh_tag + ' type="'+e.data.type+'"';
					
					//check for title
					if (typeof e.data.jp_title != 'undefined' && e.data.jp_title.length)
						shortcode_str += ' title="' + e.data.jp_title + '"';
					
					//check for slider items
					if (typeof e.data.jp_items != 'undefined' && e.data.jp_items.length)
						shortcode_str += ' items="' + e.data.jp_items + '"';

					//add panel content
					shortcode_str += ']' + '[/' + sh_tag + ']';
					//insert shortcode to tinymce
					editor.insertContent( shortcode_str );
				}
			});
	    });

		//add button
		editor.addButton('jpro_cars', {
			icon: 'jpro_cars',
			tooltip: 'JPro Cars',
			onclick: function() {
				editor.execCommand('jpro_cars_popup','',{
					header : '',
					footer : '',
					type   : 'listing',
					content: ''
				});
			}
		});

		//replace from shortcode to an image placeholder
		editor.on('BeforeSetcontent', function(event){ 
			event.content = replaceShortcodes( event.content );
		});

		//replace from image placeholder to shortcode
		editor.on('GetContent', function(event){
			event.content = restoreShortcodes(event.content);
		});
		
	});
})();