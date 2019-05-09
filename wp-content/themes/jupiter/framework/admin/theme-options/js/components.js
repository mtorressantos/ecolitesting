jQuery( document ).ready(function( $ ) {

    var DefaultValueMixin = {
        mounted: function () {
            // Sets default value if value is undefined.
            if ( typeof this.value === 'undefined' ) {
                this.value = this.schema.default;
            }
        },
    }

    Vue.component("field-mk-list-control", {
        template: '<div class="mka-wrap mka-clist-wrap" @click="updateItems">\
            <div class="mka-clist">\
                <div class="mka-clist-list" v-bind:ref="name" :class="[!itemCount ? emptyListClass : \'\']">\
                    <div class="mka-clist-item" v-for="item in listItems" v-once>\
                        <div class="mka-clist-item-inner">\
                            <div class="mka-clist-item-content" >\
                                <span class="mka-clist-social-icon" v-if="nameSelect" v-html="item.select.icon"></span>\
                                <span class="mka-clist-social-title" v-if="nameSelect" :data-key="item.select.key">{{item.select.value}}</span>\
                                <span class="mka-clist-social-url" v-if="nameInput">{{item.input}}</span>\
                            </div>\
                            <div class="mka-clist-buttons">\
                                <a href="#" class="mka-clist-edit">\
                                    <span class="mka-clist-edit-icon"></span>\
                                    <span class="mka-clist-edit-ripple"></span>\
                                </a>\
                                <a href="#" class="mka-clist-remove">\
                                    <span class="mka-clist-remove-icon"></span>\
                                    <span class="mka-clist-remove-ripple"></span>\
                                </a>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
                <a href="#" class="mka-clist-add">\
                    <span class="mka-clist-add-icon"></span>\
                    <span class="mka-clist-add-text">{{ repeaterOptions.label || "Add New Item" }}</span>\
                </a>\
                <div class="mka-clist-addbox mka-clist-addbox-clone">\
                    <div class="mka-clist-item-add-content">\
                        <div class="mka-clist-social-list-wrap" v-if="nameSelect">\
                            <span class="mka-clist-social-list-title">{{ selectOptions.label  || "Network Name" }}</span>\
                            <div class="mka-wrap mka-select-wrap">\
                                <div class="mka-select">\
                                    <input type="hidden" class="mka-select-box-value" value="">\
                                    <div class="mka-select-box">{{ selectOptions.noneSelectedText || "Nothing selected" }}</div>\
                                    <div class="mka-select-box-list-wrap mka-select-list-wrap">\
                                        <div class="mka-select-list">\
                                            <span :data-value="dropDownItem.key" :data-icon="dropDownItem.icon" class="mka-select-list-item" v-for="dropDownItem in dropDownItems">{{dropDownItem.value}}</span>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="mka-clist-social-url-wrap" v-if="nameInput">\
                            <span class="mka-clist-social-list-title">{{ inputOptions.label || "Add text"}}</span>\
                            <input class="mka-textfield mka-clist-addbox-social-url" type="text">\
                        </div>\
                    </div>\
                    <div class="mka-clist-item-add-buttons">\
                        <a href="#" class="mka-clist-item-cancel-btn mka-button mka-button--darkgray mka-button--small mka-button--float">\
                            <svg width="10" height="10" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">\
                                <line style="fill:none;stroke:#ffffff;stroke-width:3;stroke-miterlimit:10;" x1="0.8" y1="0.8" x2="15.2" y2="15.3"/>\
                                <line style="fill:none;stroke:#ffffff;stroke-width:3;stroke-miterlimit:10;" x1="15.2" y1="0.8" x2="0.8" y2="15.3"/>\
                            </svg>\
                        </a>\
                        <button class="mka-clist-item-apply-btn mka-button mka-button--green mka-button--small mka-button--float">\
                            <svg width="18" height="18" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">\
                                <polyline style="fill:none;stroke:#ffffff;stroke-width:2;stroke-miterlimit:10;" points="2.5,7.3 5.7,10.6 12.7,3.6 "/>\
                            </svg>\
                        </button>\
                    </div>\
                </div>\
                <div class="mka-clist-item mka-clist-item-clone">\
                    <div class="mka-clist-item-inner">\
                        <div class="mka-clist-item-content">\
                            <span class="mka-clist-social-icon"></span>\
                            <span class="mka-clist-social-title"></span>\
                            <span class="mka-clist-social-url"></span>\
                        </div>\
                        <div class="mka-clist-buttons">\
                            <a href="#" class="mka-clist-edit">\
                                <span class="mka-clist-edit-icon"></span>\
                                <span class="mka-clist-edit-ripple"></span>\
                            </a>\
                            <a href="#" class="mka-clist-remove">\
                                <span class="mka-clist-remove-icon"></span>\
                                <span class="mka-clist-remove-ripple"></span>\
                            </a>\
                        </div>\
                    </div>\
                </div>\
            </div>\
            <input v-if="nameSelect" type="hidden" :name="nameSelect" v-model="selectValue">\
            <input v-if="nameInput" type="hidden" :name="nameInput" v-model="inputValue">\
        </div>',
       mixins: [ VueFormGenerator.abstractField ],
	   data: function() {
		  return {
	      	emptyListClass: 'mka-clist-list--empty',
		  }
	   },
	   mounted: function() {
			this.setValue( 'select', this.selectValue );
			this.setValue( 'input', this.inputValue );
	   },
       computed: {
	     itemCount: function() {
			return this.listItems.length;
		 },
         selectOptions: function() {
            return this.schema.selectOptions || {};
         },
         inputOptions: function() {
            return this.schema.inputOptions || {};
         },
         repeaterOptions: function() {
            return this.schema.repeaterOptions || {};
         },
         nameSelect: function() {
            if ( ! this.schema.model ) {
               return ''
            }

            return this.schema.model.select || '';
         },
         selectValue: function() {
            var val = '';

            if ( this.model && this.schema.model && this.schema.model.select ) {
               val =  this.model[ this.schema.model.select ];
            }

            return val;

         },
         nameInput: function() {
            if ( ! this.schema.model ) {
               return ''
            }

            return this.schema.model.input || '';
         },
         inputValue: function() {
            var val = '';

            if ( this.model && this.schema.model && this.schema.model.input ) {
               val =  this.model[ this.schema.model.input ];
            }

            return val;
         },
         name: function() {
            return this.nameInput + '_' + this.nameSelect;
         },
         options: function() {
            var options = this.schema.options;
            if ( 'functions' === typeof( options ) ) {
               return values.apply( this, [ this.model, this.schema ] );
            } else {
               return options;
            }
         },
         dropDownItems: function() {
            return _.map(this.options, function(option, key) {
               return {
                  'key': key,
                  'icon': _.isArray(option) ? option[1] : '',
                  'value': _.isArray(option) ? option[0] : option
               };
            });
         },
         listItems: function() {
            var select = this.selectValue ? this.selectValue.split( ',' ) : [];
            var input = this.inputValue ? this.inputValue.split( ',' ) : [];

            var l = _.min( _.filter( [ select.length, input.length ], function( num ) {
               return num > 0;
            } ) );

            var list = [];
            if ( !_.isFinite(l) || !_.isNumber(l) ) {
               return list;
            }

            for ( var i = 0; i < l; i = i + 1 ) {
               list.push( ( function( sel, inp, that ) {
                  var option = that.options[ sel[ i ] ];
                  var icon = _.isArray( option ) ? option[ 1 ] : '';
                  var text = _.isArray( option ) ? option[ 0 ] : option;

                  return {
                     'select': {
                        'key': sel[ i ],
                        'icon': icon,
                        'value': text
                     },
                     'input': inp[ i ]
                  };
               } )( select, input, this ) );
            }

            return list;
         }
       },
       methods: {
         setValue: function( field, newValue ) {
            if ( this.schema.model && this.schema.model[ field ] ) {
               this.setModelValueByPath( this.schema.model[ field  ], newValue );
            }

            this.$emit( "model-updated", newValue, this.schema.model[ field ] );
         },
         updateItems: function() {
            var that = this;

            setTimeout( function() {
               var $items = $( that.$refs[ that.name ] ).find( '.mka-clist-item' );

               var selectValues = $items.find( '.mka-clist-social-title' ).map( function() {
                  return $( this ).data( 'key' );
               } ).get().join( ',' );

               var inputValues = $items.find( '.mka-clist-social-url' ).map( function() {
                  return $( this ).text();
               } ).get().join( ',' );

               that.setValue( 'select', selectValues );
               that.setValue( 'input', inputValues );
            }, 501 );
         }
       }
    });

    Vue.component("field-mk-select", {
        template:  '<div class="mka-wrap mka-select-wrap">\
            <div class="mka-select">\
                <input type="hidden" class="mka-select-box-value" v-model="value" :name="name">\
                <div class="mka-select-box" v-bind:ref="name">{{ currentItemLabel || defaultItem }}</div>\
                <div class="mka-select-box-list-wrap mka-select-list-wrap">\
                    <div class="mka-select-list">\
                        <span :data-value="item[0]" class="mka-select-list-item" v-for="item in items" @click="selectItem">\
                            {{item[1]}}\
                        </span>\
                    </div>\
                </div>\
            </div>\
        </div>',

        mixins: [ VueFormGenerator.abstractField, DefaultValueMixin ],

        computed: {
            items: function() {
                return this.getItems();
            },

            name: function() {
                return this.schema.model || '';
            },
            currentItemLabel: function() {
                return this.getCurrentItemLabel();
            },
            defaultItem: function() {
                return this.items[0][1];
            }
        },

        methods: {
            selectItem: function( event ) {
                if ( ! event.target ) {
                    return;
                }
                this.value = $(event.target).data().value;
            },

            getItems: function() {
                return this.schema.options;
            },

            getItemByKey: function( key  ) {
                return _.find( this.getItems(), function(pair) {
                    return String(pair[0]) === String(key);
                });

            },

            getCurrentItemLabel: function() {
                var item = this.getItemByKey( this.value );

                if ( _.isArray(item) && 2 === item.length ) {
                    return item[1];
                }

                return this.value;
            }
        }
    });

    Vue.component("field-mk-input", {
        template: '<div class="mka-wrap">\
            <input v-if="inputType == \'hidden\'" class="mka-textfield" type="hidden" v-model="value">\
            <input v-if="inputType != \'hidden\'" class="mka-textfield" type="text" v-model="value">\
        </div>',

        mixins: [ VueFormGenerator.abstractField, DefaultValueMixin ],

        computed: {
            inputType: function() {
                return this.schema.inputType;
            }
        }
    });

    Vue.component("field-mk-textarea", {
        template: '<div class="mka-wrap">\
			<textarea class="mka-textarea" :rows="schema.rows" ref="ide" :readonly="schema.readonly" v-model="value" :placeholder="schema.placeholder"></textarea>\
		</div>',

        mixins: [ VueFormGenerator.abstractField, DefaultValueMixin ],

        mounted: function(){
    		if ( ! _.contains( ['text/css', 'text/javascript'], this.schema.mode ) ) {
    			return false;
    		}

            this.codeMirrorInit( this.$refs['ide'] , this.schema.mode );
        },

        methods: {
            codeMirrorInit: function( textareaObj, mode ) {
                var vm = this;
                var editor = window.CodeMirror.fromTextArea( textareaObj,{
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
                    extraKeys: {"Shift-Space": "autocomplete"}
                });

                // Hide Code Mirror By Single Click
                $('.CodeMirror-scroll').on('click', function (e) {
                    if ( e.target != this ) return;
                    $('.CodeMirror-hints').hide();
                });

                editor.on( 'change', function( cm ) {
                    vm.value = cm.getValue();
                } );
            }
        }
    });

    Vue.component("field-mk-toggle", {
        template: '<div :id="\'mk-opt-\' + schema.model" class="mka-toggle" :alt="schema.label" @click="toggle">\
            <span class="mka-toggle-bullet">\
                <span class="mka-toggle-bullet-inner"></span>\
                <span class="mka-toggle-bullet-ripple"></span>\
            </span>\
            <input class="mka-toggle-input" type="hidden" v-model="value">\
        </div>',

        mixins: [ VueFormGenerator.abstractField, DefaultValueMixin ],

        mounted:  function(){
            this.mkToggle           = this.$el;
            this.mkToggle['input']  = this.$el.querySelector('.mka-toggle-input');
            this.mkToggle['bullet'] = this.$el.querySelector('.mka-toggle-bullet');
            this.mkToggle['ripple'] = this.$el.querySelector('.mka-toggle-bullet-ripple');

            if( this.value == 'true' ) {
                this.toggleOn();
                return;
            }
            this.toggleOff();
	    },

        methods: {
            toggle: function() {
                if( this.value == 'true' ) {
                    this.toggleOff();
                    return;
                }
                this.toggleOn();
            },
            toggleOn: function() {
                TweenLite.to( this.mkToggle['bullet'], 0.4, { css: { x: '99.5%' }, ease: Power4.easeOut, delay: 0 });
    			TweenLite.to( this.mkToggle['ripple'], 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
    			TweenLite.to( this.mkToggle['ripple'], 0.2, { css: { scale: 0.8, opacity: 1 }, ease: Power4.easeOut, delay: 0 });
    			TweenLite.to( this.mkToggle['ripple'], 0.7, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });
    			this.mkToggle.className += ' mka-toggle--active';
    			this.mkToggle['input'].value = true;

                this.value = this.mkToggle['input'].value;
            },
            toggleOff: function() {
                TweenLite.to( this.mkToggle['bullet'], 0.4, { css: { x: '0%' }, ease: Power4.easeOut, delay: 0 });
    			TweenLite.to( this.mkToggle['ripple'], 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
    			TweenLite.to( this.mkToggle['ripple'], 0.2, { css: { scale: 0.8, opacity: 1 }, ease: Power4.easeOut, delay: 0 });
    			TweenLite.to( this.mkToggle['ripple'], 0.7, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.1 });
    			this.mkToggle.classList.remove('mka-toggle--active');
    			this.mkToggle['input'].value = false;

                this.value = this.mkToggle['input'].value;
            }
        }
    });

    Vue.component("field-mk-range", {
        template: '<div class="mka-wrap mka-range-wrap">\
            <div class="mka-range-bg" ref="bg">\
                <input type="text" class="mka-range-val" ref="text" v-model="value">\
                <span class="mka-range-overlay-btn"></span>\
                <span class="mka-range-btn"></span>\
                <span class="mka-range-unit">{{ schema.unit }}</span>\
            </div>\
            <div class="mka-range">\
                <input type="range" ref="range" class="mka-range-input" :min="schema.min" :max="schema.max" :step="schema.step" :value="value">\
            </div>\
        </div>',

        mixins: [ VueFormGenerator.abstractField, DefaultValueMixin ],

        mounted:  function(){
            var vm = this;
			var $bg = $(this.$refs.bg);

            $( this.$el ).find( '.mka-range-input' ).rangeslider({

                polyfill: false,

                // Default CSS classes
                rangeClass: 'rangeslider',
                disabledClass: 'rangeslider--disabled',
                horizontalClass: 'rangeslider--horizontal',
                verticalClass: 'rangeslider--vertical',
                fillClass: 'rangeslider__fill',
                handleClass: 'rangeslider__handle',

                // Callback function
                onSlide: function(position, value) {
                    this.$element.closest('.mka-wrap').find('.mka-range-val').val(value);
                },

                // Callback function
                onSlideEnd: function(position, value) {
                    vm.value = value;
                }

            });
	    },
    });

    Vue.component("field-mk-color", {
        template: '<div class="color-picker-holder">\
            <input type="text" v-model="value" :data-rgba="schema.rgba" :data-default-color="schema.default" class="wp-color-picker-field" />\
        </div>',

        mixins: [ VueFormGenerator.abstractField, DefaultValueMixin ],

        mounted:  function(){
            var vm = this;

            $( this.$el ).find( 'input' ).alphaColorPicker().on( 'irischange', function( event, ui ) {
                vm.value = ui.color.toString();
            });

            // Default clear function is not working porperly.
            $( this.$el ).find( '.wp-picker-clear' ).on( 'click', function() {
                vm.value = '';
            } );

	    }

    });

	Vue.component("field-mk-upload", {
		template: '<div class="mka-wrap mka-image-upload-wrap">\
		<div class="mka-upload">\
				<div class="mk-field-wrap">\
					<input class="mka-textfield" type="text" v-model="value" >\
					<span v-if="value" class="mka-image-upload-view-btn"></span>\
				</div>\
				<a href="#" class="mka-upload-btn" @click.prevent="openModal">\
					<span class="mka-upload-btn-icon"></span>\
				</a>\
				<div v-if="schema.modelWidth" class="mka-range-bg">\
					<label>Width\
						<div v-if="schema.helpWidth" class="mka-wrap mka-tip-wrap">\
							<a href="#" class="mka-tip">\
								<span class="mka-tip-icon">\
									<span class="mka-tip-arrow"></span>\
								</span>\
								<span class="mka-tip-ripple"></span>\
							</a>\
							<div class="mka-tip-content" v-html="schema.helpWidth"></div>\
						</div>\
					</label>\
					<input type="text" class="mka-range-val" v-model="width">\
					<span class="mka-range-overlay-btn"></span>\
					<span class="mka-range-unit">px</span>\
				</div>\
			</div>\
			<div class="mka-image-upload-view">\
				<svg class="mka-spinner mka-image-upload-view-spinner" width="25px" height="25px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">\
				<circle class="mka-spinner-path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>\
				</svg>\
			</div>\
		</div>',

		mixins: [ VueFormGenerator.abstractField, DefaultValueMixin ],

		computed: {
			width: {
				get: function () {
					return this.model[ this.schema.modelWidth ]
				},
				set: function (newValue) {
					this.$set( this.model, this.schema.modelWidth, newValue );
				}
			}
		},

		methods: {
			openModal: function() {
				var vm = this;

				var frame;

				if ( frame ) {
					frame.open();
					return;
				}

				frame = wp.media({
					title: 'Insert Media',
					button: {
						text: 'Insert into field'
					},
					multiple: false  // Set to true to allow multiple files to be selected
				});

				// When an image is selected in the media frame...
				frame.on( 'select', function() {
					var attachment = frame.state().get('selection').first().toJSON();

					vm.$el.querySelector('.mka-textfield').value = attachment.url;

					vm.value = attachment.url;

				});

				frame.open();
			}
		}
	});

    Vue.component("field-mk-visual-selector", {
        template: '<div class="mk-visual-selector">\
            <a href="#" v-for="(value, key) in schema.options" :rel="key" @click.prevent="currentify( key )" :class="{ current: currentItem == key }" v-html="value">\
            </a>\
            <input type="hidden" v-model="value">\
        </div>',

        mixins: [ VueFormGenerator.abstractField, DefaultValueMixin ],

        data: function () {
            return {
                currentItem: this.value
            }
        },

        methods: {
            currentify: function( key ) {
                this.currentItem = key;
                this.value = key;
            }
        }

    });

    Vue.component("field-mk-select-box", {
        template: '<div class="mka-options">\
            <div class="mka-options-item-wrap" v-for="( value, key ) in schema.options" @click="currentify( $event, key )" :class="{ current: currentItem == key }" :title="key">\
                <span class="mka-options-item" v-html="value"></span>\
                <span class="mka-options-item-ripple"></span>\
            </div>\
            <input type="hidden" v-model="value">\
        </div>',

        mixins: [ VueFormGenerator.abstractField, DefaultValueMixin ],

        data: function () {
            return {
                currentItem: this.value
            }
        },

        mounted: function () {
            this.currentItem = this.value;
        },

        methods: {
            currentify: function( event, key ) {

                this.currentItem = key;

        		var $ripple = $( event.target ).parents('.mka-options-item-wrap').find('.mka-options-item-ripple');

                console.log(  );

        		TweenLite.to( $ripple, 0, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
        		TweenLite.to( $ripple, 0.3, { css: { scaleX: 1.3, scaleY: 1.3, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
        		TweenLite.to( $ripple, 0.2, { css: { scaleX: 1.6, scaleY: 1.6, opacity: 0 }, ease: Power1.easeOut, delay: 0.2 });

                $( this.$el ).find('input').val( key );

                this.value = key;
            }
        }

    });

    Vue.component("field-mk-header-switcher", {
        template: '<div id="mk-header-switcher" :class="[alignClass, toolbarClass]">\
            <div class="header-style-unit">\
                <div class="mk-header-preview">\
                    <div></div>\
                </div>\
                <div class="mk-header-styles-number" @click="toggleStyle">\
                    <span rel="4" :class="{ active : 4 == this.value }">4</span>\
                    <span rel="3" :class="{ active : 3 == this.value }">3</span>\
                    <span rel="2" :class="{ active : 2 == this.value }">2</span>\
                    <span rel="1" :class="{ active : 1 == this.value }">1</span>\
                </div>\
                <div class="mk-header-style-tools">\
                    <div class="mk-header-align" @click="toggleAlignment">\
                        <div class="label">Align</div>\
                        <span rel="left" class="header-align-left" :class=\'{ active : this.align == "left" }\'></span>\
                        <span rel="center" class="header-align-center" v-if="this.value != 4" :class=\'{ active : this.align == "center" }\'></span>\
                        <span rel="right" class="header-align-right" :class=\'{ active : this.align == "right" }\'></span>\
                    </div>\
                    <div class="mk-header-toolbar-toggle" @click="toggleToolbar">\
                        <div class="label">Toolbar</div>\
                        <span class="header-toolbar-toggle-button active" :class="[toolbarIcon]"></span>\
                    </div>\
                </div>\
            </div>\
            <input class="hidden-input-theme_header_align" type="hidden" v-model="this.align">\
            <input class="hidden-input-theme_toolbar_toggle" type="hidden" v-model="this.toolbar">\
            <input type="hidden" v-model="value" name="theme_header_style" id="theme_header_style">\
        </div>',
        mixins: [ VueFormGenerator.abstractField ],

        data: function () {
            return {
                align: this.model.theme_header_align ? this.model.theme_header_align : 'left',
                alignClass: '',
                toolbar: this.model.theme_toolbar_toggle ? this.model.theme_toolbar_toggle : 'true',
                toolbarClass: '',
                toolbarIcon: '',
                style: this.value
            }
        },

        mounted: function() {
            this.alignClass = 'style-' + this.style + '-align-' + this.align;
            this.toolbarClass = this.toolbar == 'true' ? 'toolbar-true' : 'toolbar-false' ;
            this.toolbarIcon = this.toolbar == 'true' ? 'enabled' : 'disabled';

            this.$set( this.model, 'theme_header_align', this.align );
            this.$set( this.model, 'theme_toolbar_toggle', this.toolbar );
        },

        watch: {
            align: function() {
                this.$set( this.model, 'theme_header_align', this.align );
            },
            toolbar: function() {
                this.$set( this.model, 'theme_toolbar_toggle', this.toolbar );
            },
            style: function() {
                this.value = this.style;
            }
        },

        methods: {
            toggleToolbar: function() {
                if ( this.toolbar == 'true' ) {
                    this.toolbarClass = 'toolbar-false';
                    this.toolbarIcon = 'disabled';
                    this.toolbar = 'false';
                    return;
                }

                this.toolbarClass = 'toolbar-true';
                this.toolbarIcon = 'enabled';
                this.toolbar = 'true';
            },
            toggleAlignment: function( event ) {
                var target = $( event.target );

                if ( ! target.is( "span" ) ) {
                    return;
                }

                var targetRel = target.attr( 'rel' );
                this.align = targetRel;
                this.alignClass = 'style-' + this.style + '-align-' + targetRel;
            },
            toggleStyle: function( event ) {
                var target = $( event.target );

                if ( ! target.is( "span" ) ) {
                    return;
                }

                this.style = target.attr( 'rel' );
                this.alignClass = 'style-' + this.style + '-align-' + this.align;
            }
        }

    });

    Vue.component("field-mk-radio", {
        template:
        '<div class="mka-radio">\
        	<div v-for="(value, key) in schema.options" class="mka-radio-item">\
		        <div class="mka-wrap mka-radio-wrap">\
			        <input class="mka-radio" type="radio" :checked="{ checked: currentItem == key }" @click="currentify(key)">\
			        <div class="mka-radio-skin">\
			            <div class="mka-radio-bg"></div>\
			            <div class="mka-radio-bullet"></div>\
			            <div class="mka-radio-bullet-inactive"></div>\
			            <div class="mka-radio-ripple"></div>\
			        </div>\
			    </div>\
			    <input type="hidden" v-model="value">\
			    <span class="mka-radio-label">{{ value }}</span>\
	    	</div>\
	    </div>',
        mixins: [ VueFormGenerator.abstractField ],

        data: function () {
            return {
                currentItem: this.value
            }
        },

        methods: {
            currentify: function( key ) {
                this.currentItem = key;

        		var $ripple = $( event.target ).siblings('.mka-radio-skin').find('.mka-radio-ripple');
				var $bullet =  $( event.target ).siblings('.mka-radio-skin').find('.mka-radio-bullet');
				var $bullet_inactive =  $( event.target ).siblings('.mka-radio-skin').find('.mka-radio-bullet-inactive');

				// Bullet Animate
				TweenLite.to( $bullet, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
				TweenLite.to( $bullet, 0.3, { css: { scale: 1, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
				TweenLite.to( $bullet_inactive, 0.3, { css: {  opacity: 0 }, ease: Power1.easeOut, delay: 0 });

				TweenLite.to( $( event.target ).closest('.mka-radio-item').siblings().find('.mka-radio-bullet'), 0.3, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
				TweenLite.to( $( event.target ).closest('.mka-radio-item').siblings().find('.mka-radio-bullet-inactive'), 0.3, { css: {  opacity: 1 }, ease: Power1.easeOut, delay: 0 });

				// Background Animate
				TweenLite.to( $ripple, 0, { css: { scale: 0.1, opacity: 0 }, ease: Power1.easeOut, delay: 0 });
				TweenLite.to( $ripple, 0.3, { css: { scale: 1, opacity: 1 }, ease: Power1.easeOut, delay: 0 });
				TweenLite.to( $ripple, 0.3, { css: { scale: 1, opacity: 0 }, ease: Power1.easeOut, delay: 0.2 });

                $( this.$el ).children('input').val( key );

                this.value = key;
            }
        }

    });

    Vue.component("field-mk-background-selector", {
        template: '<div class="mk-general-bg-selector" :class="layout" v-click-outside="outside">\
            <div class="outer-wrapper">\
                <div rel="body" @click.stop="editSection( \'body\' )" class="body-section" :class="activeSectionClass( \'body\' )">\
                    <span class="section-indicator">Body</span>\
                    <span class="hover-state-body mka-wrap">\
                        <a href="#" @click.prevent class="mka-button mka-button--blue mka-button--small">Edit</a>\
                    </span>\
                    <div class="mk-bg-preview-layer" :style="bodyStyles"></div>\
                    <div class="mk-transparent-bg-indicator"></div>\
                </div>\
                <div class="main-sections-wrapper">\
                    <div rel="header" @click.stop="editSection( \'header\' )" class="header-section" :class="activeSectionClass( \'header\' )">\
                        <span class="section-indicator">Header</span>\
                        <span class="hover-state mka-wrap">\
                            <a href="#" @click.prevent class="mka-button mka-button--blue mka-button--small">Edit</a>\
                        </span>\
                        <div class="mk-bg-preview-layer" :style="headerStyles"></div>\
                        <div class="mk-transparent-bg-indicator"></div>\
                    </div>\
                    <div rel="banner" @click.stop="editSection( \'banner\' )" class="banner-section" :class="activeSectionClass( \'banner\' )">\
                        <span class="section-indicator">Page Title</span>\
                        <span class="hover-state mka-wrap">\
                            <a href="#" @click.prevent class="mka-button mka-button--blue mka-button--small">Edit</a>\
                        </span>\
                        <div class="mk-bg-preview-layer" :style="bannerStyles"></div>\
                        <div class="mk-transparent-bg-indicator"></div>\
                    </div>\
                    <div rel="page" @click.stop="editSection( \'page\' )" class="page-section" :class="activeSectionClass( \'page\' )">\
                        <span class="section-indicator">Page</span>\
                        <span class="hover-state mka-wrap">\
                            <a href="#" @click.prevent class="mka-button mka-button--blue mka-button--small">Edit</a>\
                        </span>\
                        <div class="mk-bg-preview-layer" :style="pageStyles"></div>\
                        <div class="mk-transparent-bg-indicator"></div>\
                    </div>\
                    <div rel="footer" @click.stop="editSection( \'footer\' )" class="footer-section" :class="activeSectionClass( \'footer\' )">\
                        <span class="section-indicator">Footer</span>\
                        <span class="hover-state mka-wrap">\
                            <a href="#" @click.prevent class="mka-button mka-button--blue mka-button--small">Edit</a>\
                        </span>\
                        <div class="mk-bg-preview-layer" :style="footerStyles"></div>\
                        <div class="mk-transparent-bg-indicator"></div>\
                    </div>\
                </div>\
            </div>\
            <div class="mk-bg-panel">\
                <span class="mk-bg-panel-text">Select a section on the left to open its settings.</span>\
            </div>\
        </div>',
        mixins: [ VueFormGenerator.abstractField ],

        data: function () {
            return {
                activeSection: '',
                bodyStyles: {},
                headerStyles: {},
                bannerStyles: {},
                pageStyles: {},
                footerStyles: {}
            }
        },

        mounted: function() {
            var vm = this;

            vm.hidePanel();
            vm.applyAllStyles();

            vm.$set(vm.model, 'bg_panel_color_style', '');
            vm.$set(vm.model, 'bg_panel_image_style', '');
            vm.$set(vm.model, 'bg_panel_repeat', '');
            vm.$set(vm.model, 'bg_panel_position', '');
            vm.$set(vm.model, 'bg_panel_size', '');
            vm.$set(vm.model, 'bg_panel_attachment', '');
            vm.$set(vm.model, 'bg_panel_upload', '');
            vm.$set(vm.model, 'bg_panel_color', '');
            vm.$set(vm.model, 'bg_panel_color_2', '');
            vm.$set(vm.model, 'grandient_color_style', '');
            vm.$set(vm.model, 'grandient_color_angle', '');

            $( window ).on( 'resize', function() {
                if ( $( '[data-tab="layout_backgrounds"]' ).is( ':visible' ) === true ) {
                    vm.setPanelPosition();
                }
            } );
        },

        watch: {
            bg_panel: function( data ) {
                var activeSection = this.activeSection;
                this.model[activeSection + '_source'] = data[0];
                this.model[activeSection + '_image'] = data[1];
                if ( data[0] != 'custom' ) {
                    this.model[activeSection + '_image'] = '';
                }
                this.model[activeSection + '_color_gradient'] = data[2];
                this.model[activeSection + '_color'] = data[3];
                this.model[activeSection + '_color_2'] = data[4];
                this.model[activeSection + '_color_gradient_style'] = data[5];
                this.model[activeSection + '_color_gradient_angle'] = data[6];
                this.model[activeSection + '_size'] = data[7];
                this.model[activeSection + '_position'] = data[8];
                this.model[activeSection + '_attachment'] = data[9];
                this.model[activeSection + '_repeat'] = data[10];
                this.applyStyle();
            }
        },

        computed: {
            layout: function() {
                return this.model.background_selector_orientation;
            },

            bg_panel: function() {
                return [
                    this.model.bg_panel_image_style,
                    this.model.bg_panel_upload,
                    this.model.bg_panel_color_style,
                    this.model.bg_panel_color,
                    this.model.bg_panel_color_2,
                    this.model.grandient_color_style,
                    this.model.grandient_color_angle,
                    this.model.bg_panel_size,
                    this.model.bg_panel_position,
                    this.model.bg_panel_attachment,
                    this.model.bg_panel_repeat
                ];
            }

        },
        methods: {
            editSection: function( section ) {
                var vm = this;

				if ( section === this.activeSection ) {
					return;
				}

                if ( this.activeSection ) {
                    this.removePanel( section );
                    return;
                }

                vm.activeSection = section;
                $( '.mk-bg-panel-text' ).text( 'Loading settings ...' );
                vm.$set( vm.model, 'general_backgounds', 'true' );
                vm.activeSectionOptions( vm.activeSection );

                vm.$nextTick( function(){
                    vm.setPanelPosition();
                });

            },

            hidePanel: function() {
                var panelOptions = $( '[data-section="background-selector-options"]' );

                panelOptions.fadeOut();
            },

            outside: function() {
                this.$set(this.model, 'general_backgounds', 'false');
                this.activeSection = '';
                $( '.mk-bg-panel-text' ).text( 'Select a section on the left to open its settings.' );
                this.hidePanel();
            },

            activeSectionOptions: function( activeSection ){
                this.model.bg_panel_image_style  = this.model[activeSection + '_source'];
                this.model.bg_panel_upload       = this.model[activeSection + '_image'];
                this.model.bg_panel_color_style  = this.model[activeSection + '_color_gradient'];
                this.model.bg_panel_color        = this.model[activeSection + '_color'];
                this.model.bg_panel_color_2      = this.model[activeSection + '_color_2'];
                this.model.grandient_color_style = this.model[activeSection + '_color_gradient_style'];
                this.model.grandient_color_angle = this.model[activeSection + '_color_gradient_angle'];
                this.model.bg_panel_size         = this.model[activeSection + '_size'];
                this.model.bg_panel_position     = this.model[activeSection + '_position'];
                this.model.bg_panel_attachment   = this.model[activeSection + '_attachment'];
                this.model.bg_panel_repeat       = this.model[activeSection + '_repeat'];
            },

            removePanel: function( section ) {
                var vm = this;

                vm.$set(this.model, 'general_backgounds', 'false');
                vm.activeSection = '';

                this.$nextTick(function(){
                    vm.editSection( section );
                });
            },

            getPanelPosition: function() {
                var bodyTop         = $('body').offset().top;
                var ThemeOptionsTop = $('#mk-theme-options').offset().top;
                var wpTopMenu       = $( '#wpadminbar' ).height();
                var wpLeftMenu      = $( '#adminmenu' ).width();
                var panelTop        = $( '.mk-bg-panel' ).offset().top;
                var panelLeft       = $( '.mk-bg-panel' ).offset().left;


                // Difference between body and theme options prevents issue when
                // There are some notices in top of the theme options.
                var top = panelTop - bodyTop - ThemeOptionsTop - wpTopMenu + 7;
                var left = panelLeft - wpLeftMenu - 261;

                if ( window.matchMedia( '(max-width: 782px)' ).matches ) {
                    var top = panelTop - bodyTop - ThemeOptionsTop - wpTopMenu + 35;
                    var left = panelLeft - 251;
                }

                return { top: top, left: left };
            },

            setPanelPosition: function() {
                if ( ! this.activeSection ) {
                    return;
                }

                $( '[data-section="background-selector-options"]' ).css({
                    top: this.getPanelPosition().top,
                    left: this.getPanelPosition().left
                }).fadeIn();
            },

            activeSectionClass: function( section ) {
                if ( this.activeSection == section) {
                    return 'active';
                }
            },

            applyAllStyles: function() {
                var vm = this;
                var sections = ['body', 'header', 'banner', 'page', 'footer'];

                sections.forEach( function( section ) {
                    var bgImageStyle = vm.model[section + '_source'];
                    var bgColor = vm.model[section + '_color'];
                    var bgColorGradient = vm.model[section + '_color_gradient'];
                    var bgColor2 = vm.model[section + '_color_2'];
                    var bgColorGradientStyle = vm.model[section + '_color_gradient_style'];
                    var bgColorGradientAngle = vm.model[section + '_color_gradient_angle'];
                    var bgImage = 'url(' + vm.model[section + '_image'] + ')';
                    var bgSize = vm.model[section + '_size'] == 'true' ? 'cover' : 'contain';
                    var bgPosition = vm.model[section + '_position'];
                    var bgAttachment = vm.model[section + '_attachment'];
                    var bgRepeat = vm.model[section + '_repeat'];
                    var bgSource = vm.model[section + '_source'];

                    if ( bgColorGradient == 'gradient' ) {

                        switch( bgColorGradientAngle ) {
                            case 'horizontal':
                                bgColorGradientAngle = 'to right,';
                                break;
                            case 'diagonal_left_bottom':
                                bgColorGradientAngle = 'to right bottom,';
                                break;
                            case 'diagonal_left_top':
                                bgColorGradientAngle = 'to right top,';
                                break;
                            default:
                                bgColorGradientAngle = '';
                        }

                        if ( bgColorGradientStyle == 'radial' ) {
                            bgColorGradientAngle = '';
                        }

                        vm[section + 'Styles'] = {
                            background: bgColorGradientStyle + '-gradient(' + bgColorGradientAngle + ' ' + bgColor + ' 0%, ' + bgColor2 + ' 100%)'
                        }
                        return;
                    }

                    if ( bgImageStyle == 'no-image' ) {
                        vm[section + 'Styles'] = {
                            backgroundColor: bgColor
                        }
                        return;
                    }

                    vm[section + 'Styles'] = {
                        backgroundColor: bgColor,
                        backgroundImage: bgImage,
                        backgroundRepeat: bgRepeat,
                        backgroundAttachment: bgAttachment,
                        backgroundPosition: bgPosition,
                        backgroundSize: bgSize
                    }

                } );
            },

            applyStyle: function() {
                var vm = this;
                var section = this.activeSection;

                var bgImageStyle = vm.model[section + '_source'];
                var bgColor = vm.model[section + '_color'] ? vm.model[section + '_color'] : '#FFF';
                var bgColorGradient = vm.model[section + '_color_gradient'];
                var bgColor2 = vm.model[section + '_color_2'] ? vm.model[section + '_color_2'] : '#FFF';
                var bgColorGradientStyle = vm.model[section + '_color_gradient_style'];
                var bgColorGradientAngle = vm.model[section + '_color_gradient_angle'];
                var bgImage = 'url(' + vm.model[section + '_image'] + ')';
                var bgSize = vm.model[section + '_size'] == 'true' ? 'cover' : 'contain';
                var bgPosition = vm.model[section + '_position'];
                var bgAttachment = vm.model[section + '_attachment'];
                var bgRepeat = vm.model[section + '_repeat'];
                var bgSource = vm.model[section + '_source'];

                if ( bgColorGradient == 'gradient' ) {

                    switch( bgColorGradientAngle ) {
                        case 'horizontal':
                            bgColorGradientAngle = 'to right,';
                            break;
                        case 'diagonal_left_bottom':
                            bgColorGradientAngle = 'to right bottom,';
                            break;
                        case 'diagonal_left_top':
                            bgColorGradientAngle = 'to right top,';
                            break;
                        default:
                            bgColorGradientAngle = '';
                    }

                    if ( bgColorGradientStyle == 'radial' ) {
                        bgColorGradientAngle = '';
                    }

                    vm[section + 'Styles'] = {
                        background: bgColorGradientStyle + '-gradient(' + bgColorGradientAngle + ' ' + bgColor + ' 0%, ' + bgColor2 + ' 100%)'
                    }
                    return;
                }

                if ( bgImageStyle == 'no-image' ) {
                    vm[section + 'Styles'] = {
                        backgroundColor: bgColor
                    }
                    //vm.model[section + '_source'] = '';
                    return;
                }

                vm[section + 'Styles'] = {
                    backgroundColor: bgColor,
                    backgroundImage: bgImage,
                    backgroundRepeat: bgRepeat,
                    backgroundAttachment: bgAttachment,
                    backgroundPosition: bgPosition,
                    backgroundSize: bgSize
                }

            }
        },


        /**
         * Directive Name : click-outside
         * Directive to handle event when clicked outside the target element
         */

        directives: {
            'click-outside': {
                bind: function(el, binding, vNode) {
                    var compName;
                    var warn;
                    var handler;
                    var elem = $('#wpwrap')[0];
                    var evnt = 'click';
                    var r;

                    // Provided expression must evaluate to a function.
                    if (typeof binding.value !== 'function') {
                        compName = vNode.context.name
                        warn = "[Vue-click-outside:] provided expression '${binding.expression}' is not a function, but has to be"

                        if (compName) { warn += "Found in component '${compName}'" }
                        console.warn(warn)
                    }
                    // check if target element
                    handler = function (e) {
                        if (!el.contains(e.target) && el !== e.target && $(e.target).closest('[data-section="background-selector-options"]').length === 0 ) {
                            binding.value(e);
                        }
                    };
                    el.__vueClickOutside__ = handler

                    // Add click event listener
                    if (elem.addEventListener) {
                        elem.addEventListener(evnt, handler);
                    } else if (elem.attachEvent) {
                        r = elem.attachEvent("on" + evnt, handler);
                        return r;
                    } else {
                        console.log('Can not attache ' + evnt + ' to document');
                    }
                },

                unbind: function(el, binding) {
                    // Remove click event listener
                    var elem = $('#wpwrap')[0];
                    var evnt = 'click';
                    var r;
                    if (elem.removeEventListener) {
                        elem.removeEventListener(evnt, el.__vueClickOutside__);
                    } else if (elem.detachEvent) {
                        r = elem.detachEvent("on" + evnt, el.__vueClickOutside__);
                        return r;
                    } else {
                        console.log('Can not attache ' + evnt + ' to document');
                    }
                    el.__vueClickOutside__ = null
                }

            }
        }

    });

    Vue.component("field-mk-font", {
       template: '<div class="mka-wrap mka-font-wrap" style="display: inline-block;">\
            <div class="mka-font">\
                <input-text @changedText="changeSuggestion" v-model="value" :options="suggestions" />\
                <input-filter :type="type" :filters="filters" @changeFilter="changeDropdown" />\
            </div>\
            <div class="mka-font-list mka-select-list-wrap" ref="dropdown">\
                <input-select :items="matches" @selectedItem="changeSelection" />\
            </div>\
        </div>',
        mixins: [ VueFormGenerator.abstractField ],
        data: function() {
           return {
                type: this.schema.initialType ? this.schema.initialType : 'all',
                selection: ''
           };
        },
		mounted: function() {
			this.changeSelection(this.value);
		},
        methods: {
           changeDropdown: function(value){
                this.type = value;

				if ( this.schema.items[ value ] && this.schema.items[ value ].model2 ) {
					this.fontType = this.schema.items[ value ].model2;
				}
           },
           changeSuggestion: function(value) {
                this.value = value;

                if ( 'all' === this.type || 0 !== this.filters[ this.type ].list.length ) {
                    this.selection = value.trim();
                    $(this.$refs.dropdown).show();
                }
           },
           changeSelection: function(value) {
                this.value = value;

				var type = this.searchFontByType(value);
				type = type ? type : this.schema.initialType;
				this.type = type;

				var fontType = this.filters[ type ].model2;
				this.fontType = fontType ? fontType : '';

                $( this.$refs.dropdown ).hide();
           },
		   searchFontByType: function(value) {
				var vm = this;
				var type = [];

			    if ( 'all' !== this.type ) {
					return this.type;
				}

				_.each(this.filters, function(item,key) {
					if ( 'all' === key ) {
						return;
					}

					if ( !item.list.length ) {
						return;
					}

					var len = item.list.length;
					for ( var i = 0; i < len; i = i + 1 ) {
						if ( String(item.list[i][0]) === String(value) ) {
							type.push(key);
							break;
						}
					}
				});

				// Return only first match in case of duplicate grouping.
				return type.length ? type[0] : '';
		   }
        },
        computed: {
		   fontType: {
			 get: function() {
					if ( this.model && this.schema.model2 ) {
					   return this.model[ this.schema.model2 ];
					}

					return '';
			 },
	         set: function( newValue ) {
   		         	if ( this.model && this.schema.model2 ) {
       		     		this.setModelValueByPath( this.schema.model2, newValue );
           	 		}

           	 		this.$emit( "model-updated", newValue, this.schema.model2 );
         	 }
		   },
           filters: function() {
                return this.schema.items;
           },
           suggestions: function() {
                if ( !this.type ) {
                   return [];
                }

                if ( 'all' !== this.type ) {
                    return _.sortBy( this.filters[this.type].list, function(pair) {
                        return pair[1];
                    } );
                }

                return _.sortBy( _.flatten( _.map( this.filters, function( item, key ) {
                    return item.list;
                } ), true ), function(pair) {
                    return pair[1];
                } );
           },
           matches: function() {
                var vm = this;
                return _.filter(this.suggestions, function(pair){
                    if (!vm.selection) return true;
                    return pair[1].toLowerCase().indexOf(vm.selection.toLowerCase()) >= 0;
                });
           }
        },
        components: {
           'input-filter': {
              template: '\
                  <div class="mka-font-filter">\
                       <span :data-value="type" class="mka-font-filter-selected" :key="type">{{typeText}}</span>\
                       <div class="mka-font-filter-list">\
                           <span @click="selectFilter(key)" :key="filter.id"  :data-value="key" class="mka-font-filter-item" :class="[selected(key), filter.textClass]" v-for="(filter, key) in filters">{{filter.text}}</span>\
                       </div>\
                   </div>\
              ',
              props: ['type', 'filters'],
              methods: {
                 selected: function(key) {
                    return key === this.type ? 'mka-font-filter-item--selected' : '';
                 },
                 selectFilter: function(key) {
                    this.$emit('changeFilter', key);
                 }
              },
              computed: {
                 typeText: function(){
                    return this.filters[ this.type ] ? this.filters[ this.type ].text : '';
                 }
              }
           },
           'input-select': {
              template: '\
                   <div class="mka-select-list">\
                       <span :data-name="item[0]" class="mka-select-list-item" v-for="item in items" :key="item.id" @click="selectItem($event.target)">{{item[1]}}</span>\
                   </div>\
              ',
              props: ['items'],
              methods: {
                 selectItem: function(value) {
                     this.$emit('selectedItem', $(value).data('name'), $(value).text());
                 }
              }
           },
           'input-text': {
                template: '\
                   <span>\
                        <input type="text" :value="text" class="mka-font-field" @input="changeText($event.target.value)" @focus="changeText($event.target.value)">\
                        <input type="hidden" :value="value">\
                        <span class="mka-font-icon-wrap">\
                            <span class="mka-font-icon"></span>\
                            <div class="mka-bubbling">\
                                <span class="mka-bubbling-1"></span>\
                                <span class="mka-bubbling-2"></span>\
                                <span class="mka-bubbling-3"></span>\
                            </div>\
                        </span>\
                    </span>\
                ',
                props: ['value', 'options'],
                computed: {
                    text: function() {
                        return this.getDisplayText( this.value, this.options );
                    }
                },
                methods: {
                    changeText: function( value ) {
                        this.$emit( 'changedText', value );
                    },
                    getDisplayText: _.memoize( function( value, map ) {
                        var items = _.filter( map, function( pair ) {
                            return pair[0] === value;
                        } );

                        if ( !items.length || items[0].length < 2 ) {
                            return value;
                        }

                        return items[0][1];
                    } )
                }
            }
        }
    });

    Vue.component("field-mk-select-multi", {
        template: '<div class="mka-wrap">\
            <select class="mka-multiselect--entry-adder-no-ajax" name="" multiple="multiple" v-model="selections" ref="select">\
                <option v-for="( option, optionKey ) in schema.options" :value="optionKey">{{ option }}</option>\
            </select>\
        </div>',

        mixins: [ VueFormGenerator.abstractField, DefaultValueMixin ],

        data: function() {
            return {
                selections: []
            }
        },

        mounted: function() {
            var vm = this;

            vm.selection = _.isArray( this.value ) ? this.value : [];

            var select = $( this.$refs.select );

            select.val( vm.selection );

            select.mk_multiselect({
    	        keyword_length: 1
    	    });

            // This is dirty.
            $( this.$el ).on( 'change', function() {
                vm.value = select.val();
            });
        }
    });

    Vue.component("field-mk-button-small", {
        template: '<div class="mka-wrap">\
        <input type="button" class="mka-button mka-button--gray mka-button--small" :value="schema.val" :name="schema.name" :id="schema.id" :data-nonce="schema.nonce">\
        </div>',
        mixins: [ VueFormGenerator.abstractField ]
    });

    Vue.component("field-mk-div", {
        template: '<div></div>',
        mixins: [ VueFormGenerator.abstractField ]
    });

    Vue.component("field-mk-alert", {
        template: '<div :class="[\'mk-field-alert\', \'mk-field-alert-\' + type]">\
            <span class="mk-icon" v-html="icon"></span>\
            <span class="mk-message" v-html="message"></span>\
        </div>',

        props: ['alertTypeColor', 'alertType', 'alertMessage'],

        data: function() {
            return {
                color: this.alertTypeColor || this.schema.alertTypeColor || '',
                type: this.alertType || this.schema.alertType || 'warning',
                message: this.alertMessage || this.schema.alertMessage || ''
            }
        },

        computed: {
            icon: function() {
                switch ( this.type ) {
                    case 'warning':
                        return '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"\
                        	width="16px" height="16px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">\
                        	<path fill="#' + this.color + '" d="M8,0C3.6,0,0,3.6,0,8s3.6,8,8,8s8-3.6,8-8S12.4,0,8,0z M7,3h2v6H7V3z M9,13H7v-2h2V13z"/>\
                        </svg>'
                        break;
                    default:
                        return '';
                }
            }
        },

        mixins: [ VueFormGenerator.abstractField ]
    });

    Vue.component("field-mk-fonts", {
        template: '<div class="mka-wrap mk-fonts">\
            <font-alert class="mk-migrate-notice" v-if=" typeof this.value === \'undefined\' " alertTypeColor="ccc" alertType="warning" alertMessage=\'It seems you are visiting new Typography options for the first time after theme update or template installation, therefore visit your website front-end first then come back here.\'></font-alert>\
            <div v-for="(font, index) in fonts" :class="[\'mk-font\', \'mk-font-\' + index, { \'mka-disabled\': editDisabled }]">\
                <div class="mk-font-family">\
                    <h3 class="mk-font-family-title">Font Family\
                        <small> - {{ font.fontFamily }}</small>\
                        <span v-show="font.default" class="mk-badge">Default</span>\
                    </h3>\
                    <div class="mk-font-actions">\
                        <a href="#" class="mka-clist-edit" v-show="font.currentField != \'font-edit-family\'" @click="editFont( font, index )" title="Edit font family">\
                            <span class="mka-clist-edit-icon"></span>\
                            <span class="mka-clist-edit-ripple"></span>\
                        </a>\
                        <a href="#" class="mka-clist-remove" v-show="hideRemove( font )" @click="removeFont( font, index )" title="Delete the font set">\
                            <span class="mka-clist-remove-icon"></span>\
                            <span class="mka-clist-remove-ripple"></span>\
                        </a>\
                    </div>\
                    <div class="mk-font-preview">\
                        <p class="mk-preview-label"><span>Fetching&nbsp;</span>Preview</p>\
                        <h2\
                            class="mk-preview-title"\
                            :style="{ fontFamily: font.fontFamily }"\
                            contenteditable="true"\
                            title="Preview text is editable.">The spectacle before us was indeed sublime.\
                        </h2>\
                    </div>\
                </div>\
                <div class="mk-font-fields mka-clearfix">\
                    <transition name="slide-fade" mode="out-in">\
                        <component\
                            :schema="schema"\
                            :index="index"\
                            :font="font"\
                            :is="font.currentField"\
                            :alertTypeColor="alertTypeColor"\
                            :alertType="alertType"\
                            :alertMessage="alertMessage"\
                            :fontFamilies="fontFamilies"\
                            :fontTypes="fontTypes"\
                            :elements="elements"\
                            :typekitId="typekitId"\
                            :subsets="subsets">\
                        </component>\
                    </transition>\
                </div>\
            </div>\
            <a href="#" class="mka-clist-add mka-add-font" :class="{ \'mka-disabled\' : newFont  }" @click.stop.prevent="addFont()" :title="newFontTitle">\
                <span class="mka-clist-add-icon"></span>\
            </a>\
        </div>',

        mixins: [ VueFormGenerator.abstractField ],

        data: function () {
            return {
                currentField: 'font-select-elements',
                newFontShow: false,
                alertMessage: this.schema.alert,
                alertType: 'warning',
                alertTypeColor: 'cccccc',
                typekitId: this.model.typekit_id || ''
            }
        },

        mounted: function(){
            this.loadFonts();
        },

        computed: {
            fonts: function() {
                return this.model.fonts;
            },
            fontFamilies: function() {
                return this.schema.fontFamilies;
            },
            fontTypes: function() {
                return this.schema.fontTypes;
            },
            subsets: function() {
                return this.schema.subsets;
            },
            elements: function() {
                return this.schema.elements;
            },
			newFont: function() {
				if ( _.findKey( this.model.fonts, { 'new': 'true' } ) ) {
					return true;
				}

				return false;
			},
			newFontTitle: function() {
				if ( _.findKey( this.model.fonts, { 'new': 'true' } ) ) {
					return 'Save your newly added font before adding a new font.';
				}

				return 'Add a new font.';
			},
			editDisabled: function() {
				if ( _.findKey( this.model.fonts, { 'new': 'true' } ) ) {
					$( '.mk-font' ).addClass( 'mka-disabled' );
					return;
				}

				$( '.mk-font' ).removeClass( 'mka-disabled' );
				return false;
			},

        },

        methods: {
            addFont: function() {

				if ( this.newFont ) {
					return;
				}

                this.value.push({
                    'type': 'all',
                    'fontFamily': 'Arial, Helvetica, sans-serif',
                    'elements': [],
                    'subset': '',
                    'currentField': 'font-edit-family',
					'new': 'true'
                });
            },
            editFont: function( font, index ) {
				$( '.mk-font, .mka-add-font' ).not( '.mk-font-' + index ).addClass( 'mka-disabled' );
                font.currentField = 'font-edit-family';
            },
            removeFont: function( font, index ) {
                var vm = this;

				if ( font.new === 'true' ) {
					$( '.mk-font, .mka-add-font' ).removeClass( 'mka-disabled' );
					return vm.fonts.splice( index, 1 );
				}

                mk_modal({
                    title: 'Remove this font family?',
                    text: 'The elements that are affected by this font family will be rendered by your default font family.',
                    type: false,
                    showCancelButton: true,
                    showConfirmButton: true,
                    showCloseButton: true,
                    showLearnmoreButton: false,
                    showProgress: false,
                    confirmButtonText: 'REMOVE',
                    cancelButtonText: 'DISCARD',
                    closeOnConfirm: true,
                    onConfirm: function() {
                        vm.fonts.splice( index, 1 );
						$( '.mk-font, .mka-add-font' ).removeClass( 'mka-disabled' );
                    }
                });
            },
            loadFonts: function() {
                var vm = this;
                var fonts = [];
				var WebFontConfig = {
					typekit: { id: this.typekitId }
				};

                _.each( vm.fonts, function( font ) {
                    if ( ! font['fontFamily'] || font['type'] != 'google' ) {
                        return;
                    }
                   fonts.push( font['fontFamily'] );
                } );

                if ( fonts.length > 0 ) {
					WebFontConfig['google'] = {
                        families: fonts
                    }
                }

                WebFont.load( WebFontConfig );

				if ( this.typekitId.length > 1 ) {

					$.ajax({
						url: 'https://typekit.com/api/v1/json/kits/' + this.typekitId + '/published',
						dataType: 'jsonp'
					})
					.done( function( data ) {
						if ( data.hasOwnProperty( 'errors' ) ) {
							console.log( 'Typekit ID is ' + data.errors[0] + '. Check Theme Options > API Integrations and verify if your Typkit ID is valid.' );
							return;
						}

						data.kit.families.forEach(function( element, index, array ) {
							vm.fontFamilies.typekit.push( element['css_stack'] );
							vm.fontFamilies.all.push( element['css_stack'] );
						});
					});
				}

            },
			hideRemove: function( font ) {
				if( font.default || font.new === 'true' ) {
					return false;
				}

				return true;
			}
        },

        components: {
            'font-edit-family': {
                template: '<div>\
                    <div :class="firstColumn">\
                        <span class="mk-ui-lib-lbl">\
                            Select a Font Family\
                            <div class="mka-wrap mka-tip-wrap">\
                                <a href="#" class="mka-tip">\
                                    <span class="mka-tip-icon">\
                                        <span class="mka-tip-arrow"></span>\
                                    </span>\
                                    <span class="mka-tip-ripple"></span>\
                                </a>\
                                <div class="mka-tip-content">\
                                    Type to select a font from dropdown list. Consider that only a font from the dropdown should be selected otherwise you can not save it. The typekit fonts load automatically from Typekit website when you add your kit ID in <strong>Theme options > API Integrations</strong>.\
                                </div>\
                            </div>\
                        </span>\
                        <div class="mka-wrap mka-font-wrap">\
                            <div class="mka-font">\
                                <input type="text"\
                                    :placeholder="placeholder"\
                                    ref="fontFamiliesInput"\
                                    class="mka-font-field"\
                                    v-model="font.fontFamily"\
                                    @keyup="keyupFontFamily"\
                                    @focus="focusFontFamily">\
                                <span class="mka-font-icon-wrap">\
                                    <span class="mka-font-icon" v-show="!searching"></span>\
                                    <div class="mka-bubbling" v-show="searching">\
                                        <span class="mka-bubbling-1"></span>\
                                        <span class="mka-bubbling-2"></span>\
                                        <span class="mka-bubbling-3"></span>\
                                    </div>\
                                </span>\
                                <div class="mka-font-filter">\
                                    <span data-value="all" class="mka-font-filter-selected">{{fontTypeLabel}}</span>\
                                    <div class="mka-font-filter-list" @click="changeFontType( $event )">\
                                        <span\
                                            v-for="( fontType, fontTypeKey ) in fontTypes"\
                                            :data-value="fontTypeKey"\
                                            :class="[\'mka-font-filter-item\', \'mka-font-filter-item--\' + fontTypeKey]">{{ fontType }}\
                                        </span>\
                                    </div>\
                                </div>\
                            </div>\
                            <div class="mka-font-list mka-select-list-wrap" ref="fontFamiliesDropdown">\
                                <div class="mka-select-list">\
                                    <span\
                                        class="mka-select-list-item"\
                                        @click="changeFontFamily( $event, fontFamily )"\
                                        @mouseenter="previewFontFamily( $event, fontFamily )"\ v-for="fontFamily in filteredFontFamilies">{{ fontFamily }}\
                                    </span>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                    <div :class="[secondColumn, fontSubsetsVisibility]">\
                        <div class="mka-wrap mka-select-wrap">\
                            <span class="mk-ui-lib-lbl">\
                                Google Font Character Sets\
                                <div class="mka-wrap mka-tip-wrap">\
                                    <a href="#" class="mka-tip">\
                                        <span class="mka-tip-icon">\
                                            <span class="mka-tip-arrow"></span>\
                                        </span>\
                                        <span class="mka-tip-ripple"></span>\
                                    </a>\
                                    <div class="mka-tip-content">\
                                        Select an appropriate font subset. You need to lookup your font in <a href="https://fonts.google.com/" target="_blank">Google Fonts</a> website to check the supported subset. Each font may support different subsets.\
                                    </div>\
                                </div>\
                            </span>\
                            <div class="mka-select">\
                                <input type="hidden" class="mka-select-box-value">\
                                <div class="mka-select-box">{{ subsetLabel }}</div>\
                                <div class="mka-select-box-list-wrap mka-select-list-wrap">\
                                    <div class="mka-select-list" v-for="( subset, subsetKey ) in subsets">\
                                        <span data-value="some-value" class="mka-select-list-item" @click="changeSubset( subsetKey )">{{ subset }}</span>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                        <div class="mka-font-edit-family-actions">\
                            <a href="#" class="mka-clist-item-cancel-btn mka-button mka-button--darkgray mka-button--small mka-button--float" @click="ignoreFontEdit( font, index )" :title="ignoreEditTitle">\
                                <svg width="10" height="10" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">\
                                    <line style="fill:none;stroke:#ffffff;stroke-width:3;stroke-miterlimit:10;" x1="0.8" y1="0.8" x2="15.2" y2="15.3"/>\
                                    <line style="fill:none;stroke:#ffffff;stroke-width:3;stroke-miterlimit:10;" x1="15.2" y1="0.8" x2="0.8" y2="15.3"/>\
                                </svg>\
                            </a>\
                            <a href="#" class="mka-clist-item-apply-btn mka-button mka-button--green mka-button--small mka-button--float" @click="saveFontEdit( font )" title="Save the font and switch to elements selection.">\
                                <svg width="18" height="18" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"\
                                     viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve">\
                                    <polyline style="fill:none;stroke:#ffffff;stroke-width:2;stroke-miterlimit:10;" points="2.5,7.3 5.7,10.6 12.7,3.6 "/>\
                                </svg>\
                            </a>\
                        </div>\
                    </div>\
                    <span class="mka-textfield-error-msg" v-html="errorMessage"></span>\
                </div>',

                props: ['schema', 'index', 'font', 'subsets', 'fontFamilies', 'fontTypes', 'typekitId'],

                data: function() {
                    return {
                        placeholder: this.schema.placeholder,
                        saved: {
                            type: this.font.type,
                            fontFamily: this.font.fontFamily,
                            subset: this.font.subset
                        },
                        errorMessage: '',
                        searching: false
                    }
                },

                mounted: function() {
                    if ( this.font.type == 'typekit' && this.$parent.typekitId.length < 1 ) {
                        this.errorMessage = 'Add your Typekit Kit ID in General Settings > API Integrations.';
                    }
                },

                computed: {
                    firstColumn: function() {
                        var sizeLg = 7;
                        var sizeMd = 6;

                        if ( this.fontSubsetsVisibility ) {
                            sizeLg = 9;
                            sizeMd = 8;
                        }

                        return 'col-md-' + sizeMd + ' col-lg-' + sizeLg + ' mka-font-edit-column';
                    },
                    secondColumn: function() {
                        var sizeLg = 5;
                        var sizeMd = 6;

                        if ( this.fontSubsetsVisibility ) {
                            sizeLg = 3;
                            sizeMd = 4;
                        }

                        return 'col-md-' + sizeMd + ' col-lg-' + sizeLg;
                    },
                    subsetLabel: function() {
                        var subsets = this.subsets;

                        for ( var subsetKey in subsets ) {
                            if ( this.font.subset == subsetKey ) {
                                return subsets[subsetKey];
                            }
                        }
                    },
                    fontTypeLabel: function() {
                        var fontTypes = this.fontTypes;

                        for ( var fontTypeKey in fontTypes ) {
                            if ( this.font.type == fontTypeKey ) {
                                return fontTypes[fontTypeKey];
                            }
                        }
                    },
                    filteredFontFamilies: function() {
                        var vm = this;
                        var fontType = vm.font.type;
                        var fontFamilies = vm.fontFamilies[fontType];

                        if ( ! _.isArray( fontFamilies ) ) {
                            fontFamilies = [];
                        }

                        this.searching = false;

                        var filteredFontFamilies = fontFamilies.filter(function ( fontFamily ) {
                            return fontFamily.toLowerCase().indexOf( vm.font.fontFamily.toLowerCase() ) !== -1;
                        });

                        return filteredFontFamilies;

                    },
                    fontSubsetsVisibility: function() {
                        var fontType = this.font.type;

                        if ( fontType == 'google' || fontType == 'all' ) {
                            return '';
                        }

                        return 'mka-subsets-hidden';
                    },
					ignoreEditTitle: function( font ) {

						if( this.font.new === 'true' ) {
							return 'Delete the font set.'
						}

						return 'Ignore the changes and switch to elements selection. The previous saved font will be set.';
					}
                },

                methods: {
                    changeSubset: function( subsetKey ) {
                        this.font.subset = subsetKey;
                    },
                    changeFontType: function( event ) {
                        if ( event.target.dataset.value != 'all' ) {
                            this.font.fontFamily = '';
                        }

                        this.font.type = event.target.dataset.value;

                        if ( event.target.dataset.value == 'typekit' && this.typekitId.length < 1 ) {
                            this.errorMessage = 'Add your Typekit Kit ID in General Settings > API Integrations.';
                        } else if ( event.target.dataset.value == 'typekit' && this.$parent.fontFamilies.typekit.length == 0 ) {
                            this.errorMessage = 'No fonts found in your font kit. Verify your Typekit ID in Theme Options > API Integrations then check your font kit in Typekit website.';
                        } else {
                            this.errorMessage = '';
                        }
                    },
                    previewFontFamily: function( event, fontFamily ) {
                        this.$nextTick(function () {
                            var fontElement = $( '.mk-font-' + this.index )
                            var fontPreviewTitleElement = fontElement.find( '.mk-preview-title' );

                            $('.mk-font').removeClass( 'mka-previewing' );
                            fontElement.addClass( 'mka-previewing' );

                            if ( _.includes( this.schema.fontFamilies.google, fontFamily ) ) {
                                WebFont.load({
                                    google: {
                                        families: [fontFamily]
                                    }
                                });
                            }

                            fontPreviewTitleElement.css({ fontFamily: fontFamily });
                        });
                    },
                    changeFontFamily: function( event, fontFamily ) {
                        this.font.fontFamily = fontFamily;
                        this.$refs.fontFamiliesDropdown.style.display = 'none';
                        $( this.$refs.fontFamiliesInput ).removeClass( 'mka-textfield--error' );
                        this.errorMessage = '';

                        if ( _.includes( this.schema.fontFamilies.google, fontFamily ) ) {
                            this.font.type = 'google';
                        }
                    },
                    focusFontFamily: function( event ) {
                        this.$refs.fontFamiliesDropdown.style.display = 'block';
                        $( this.$refs.fontFamiliesInput ).removeClass( 'mka-textfield--error' );
                        $( this.$refs.fontFamiliesInput ).select();
                    },
                    keyupFontFamily: function() {
                        var vm = this;

                        if ( vm.filteredFontFamilies.length === 0 ) {
                            vm.errorMessage = 'No font could be found. Try a different keyword.';
                        } else {
                            vm.errorMessage = '';
                        }

                        vm.searching = true;

                        setTimeout(
                            function(){
                                vm.searching = false;
                            }, 500
                        );
                    },
                    saveFontEdit: function( font ) {
                        if ( ! _.includes( this.schema.fontFamilies.all, font.fontFamily ) ) {
                            $( this.$refs.fontFamiliesInput ).addClass( 'mka-textfield--error' );
                            this.errorMessage = 'The font family is not valid. Select another font family from the dropdown.';
                            return;
                        }

						$( '.mk-font, .mka-add-font' ).removeClass( 'mka-disabled' );

                        this.$nextTick(function () {
                            if ( font.default ) {
                                return font.currentField = 'font-alert';
                            }

							if ( font.new ) {
								font.new = false;
							}

                            font.currentField = 'font-select-elements';

                        });
                    },
                    ignoreFontEdit: function( font, index ) {

						if( font.new === 'true' ) {
							return this.$parent.removeFont( font, index );
						}

                        font.type = this.saved['type'];
                        font.fontFamily = this.saved['fontFamily'];
                        font.subset = this.saved['subset'];

						$( '.mk-font, .mka-add-font' ).removeClass( 'mka-disabled' );

                        this.$nextTick(function () {
                            if ( font.default ) {
                                return font.currentField = 'font-alert';
                            }

                            font.currentField = 'font-select-elements';
                        });
                    }
                }
            },
            'font-select-elements': {
                template: '<div>\
                    <div class="col-sm-12">\
                        <label>This font family will be assigned to these elements:</label>\
                        <div class="mka-wrap">\
                            <select class="mka-multiselect--entry-adder-no-ajax" name="" multiple="multiple" v-model="selections" ref="select">\
                                <option v-for="( element, elementKey ) in elements" :value="elementKey">{{ element }}</option>\
                            </select>\
                        </div>\
                    </div>\
                </div>',

                props: ['font', 'elements'],

                data: function() {
                    return {
                        selections: []
                    }
                },

                mounted: function() {
                    var vm = this;

                    vm.selection = _.isArray( vm.font.elements ) ? vm.font.elements : [];

                    var select = $( this.$refs.select );

                    select.val( vm.selection );

                    select.mk_multiselect({
            	        keyword_length: 1
            	    });

                    // This is dirty.
                    $( this.$el ).on( 'change', function() {
                        vm.font.elements = select.val();
                    });

                    vm.$nextTick( function() {

                        var searchInput = $( vm.$el ).find( '.mka-multiselect-search--input' );

                        searchInput.on( 'keyup', function() {
                            $( this ).parents().addClass( 'searching' );

                            setTimeout(
                                function(){
                                    searchInput.parents().removeClass( 'searching' );
                                }, 500
                            );
                        } );

                    } );
                }
            },
            'font-alert': Vue.component( 'field-mk-alert' )
        }
    });

});
