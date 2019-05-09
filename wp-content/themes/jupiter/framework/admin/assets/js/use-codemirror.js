(function($){
	var ideFactory = function( textareaID, mode ) {
		return {
			init: function() {
				this.$textarea = $( textareaID );

				if ( ! this.$textarea.length ) {
					return false;
				}

				this.editor = window.CodeMirror.fromTextArea( this.$textarea.get( 0 ),{
					autofocus: true,
					mode: mode,
					lineNumbers: true,
					tabSize: 2,
					indentWithTabs: true,
					lineWrapping: true,
					styleActiveLine: true,
					matchBrackets: true,
					theme: 'dracula',
					viewportMargin: Infinity,
					direction: "ltr",
					extraKeys: {"Ctrl-Space": "autocomplete"}
				});

				this.addListeners();
			},

			addListeners: function() {
				this.$textarea.on( 'change', _.bind( function( editor ){
					this.editor.focus();
					this.editor.getDoc().setValue( this.$textarea.val() );
				}, this ) );
			}
		};
	};

	var cssEditor = ideFactory( '#custom_css', 'text/css' );
	var jsEditor = ideFactory( '#custom_js', 'text/javascript' );

	_.bind( jsEditor.init, jsEditor )();
	_.bind( cssEditor.init, cssEditor )();


	// Hide Code Mirror By Single Click
	$('.CodeMirror-scroll').on('click', function (e) {
	  if ( e.target != this ) return;
	  $('.CodeMirror-hints').hide();
	});

})(jQuery);
