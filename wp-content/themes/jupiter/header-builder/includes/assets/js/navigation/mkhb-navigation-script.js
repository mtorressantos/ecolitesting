/**
 * HB App
 */
// v5 namespace 
window.MK = window.MK || {};
MK.HB = MK.HB || {};

/* hb-menu.js */
(function ($, window, document, undefined) {

  var pluginName = "HbMegaMenu",
    defaults = {
      propertyName: "value"
    };

  // Set delay time for mouseout
  var delayOut = 400; // Default delay time is 200

  // the list of menus
  var menus = [];

  function HbCustomMenu(element, options) {
    this.element = element;

    this.options = $.extend({}, defaults, options);

    this._defaults = defaults;
    this._name = pluginName;

    this.init();
  }

  HbCustomMenu.prototype = {
    isOpen: false,
    timeout: null,
    init: function () {

      var that = this;

      $(this).each(function(index, menu) {
        that.node = menu.element; 
        that.addListeners(menu.element);

        var $menu = $(menu.element);
        $menu.addClass("dropdownJavascript");
        menus.push(menu.element);

        $menu.find('ul > li').each(function(index, submenu) {
          if ($(submenu).find('ul').length > 0 ) {
            $(submenu).addClass('with-menu');
          }
        });
      });
    },
    addListeners: function(menu) {
      var that = this;
      $(menu).mouseover(function(e) {
        that.handleMouseOver.call(that, e);
      }).mouseout(function(e) {
          that.handleMouseOut.call(that, e);
        });
    },
    handleMouseOver: function (e) {
      var that = this;
      // clear the timeout
      this.clearTimeout();

      // find the parent list item
      //var item = ('target' in e ? e.target : e.srcElement);
      var item = e.target || e.srcElement;
      while (item.nodeName != 'LI' && item != this.node) {
        item = item.parentNode;
      }

      // if the target is within a list item, set the timeout
      if (item.nodeName == 'LI') {
        this.toOpen = item;
        this.timeout = setTimeout(function() {
          that.open.call(that);
        }, this.options.delay);
      }

    },
    handleMouseOut: function () {
      var that = this;
      // clear the timeout
      this.clearTimeout();

      // Check mouseout delay overriding
      var _delayOut = this.options.delay;
      if ( delayOut ) {
        _delayOut = delayOut;
      }

      this.timeout = setTimeout(function() {
        that.close.call(that);
      }, _delayOut );

    },
    clearTimeout: function () {

      // clear the timeout
      if (this.timeout) {
        clearTimeout(this.timeout);
        this.timeout = null;
      }

    },
    open: function () {

      var that = this;
      // store that the menu is open
      this.isOpen = true;

      // loop over the list items with the same parent
      var items = $(this.toOpen).parent().children('li');
      $(items).each(function(index, item) {
        $(item).find("ul").each(function(index, submenu) {
          if (item != that.toOpen) {
            // close the submenu
            $(item).removeClass("dropdownOpen");
            that.close(item);

          } else if (!$(item).hasClass('dropdownOpen')) {

            // open the submenu
            //if ( !$(item).parents('li').hasClass('has-mega-menu') ) {
              $(item).addClass("dropdownOpen");
            //}


            // determine the location of the edges of the submenu
            var left = 0;
            var node = submenu;
            while (node) {
              //abs is because when you make menus right to left
              //the offsetLeft would be negative
              left += Math.abs(node.offsetLeft);
              node = node.offsetParent;
            }
            var right = left + submenu.offsetWidth;


            //We should refactor this code to execute only when menu is vertical
            var menuHeight = $(submenu).outerHeight();
            var parentTop = $(submenu).offset().top - $(window).scrollTop();
            /*var totalHeight = menuHeight + parentTop;
            var windowHeight = window.innerHeight;*/

           /* if (totalHeight > windowHeight) {
              var bestTop = (windowHeight - totalHeight) - 20;
              $(submenu).css('margin-top', bestTop + "px");
            }*/

            //remove any previous classes
            $(item).removeClass('dropdownRightToLeft');

            // move the submenu to the right of the item if appropriate
            if (left < 0) $(item).addClass('dropdownLeftToRight');

            // move the submenu to the left of the item if appropriate
            if (right > document.body.clientWidth) {
              $(item).addClass('dropdownRightToLeft');
            }

          }
        });
      });

    },


    close: function (node) {

      // if no node was specified, close all menus
      if (!node) {
        this.isOpen = false;
        node = this.node;
      }

      // loop over the items, closing their submenus
      $(node).find('li').each(function(index, item) {
        $(item).removeClass('dropdownOpen');
      });

    }
  };

  $.fn[pluginName] = function (options) {
    return this.each(function () {
      if (!$.data(this, "plugin_" + pluginName)) {
        $.data(this, "plugin_" + pluginName,
          new HbCustomMenu(this, options));
      }
    });
  };

})(jQuery, window, document);



/* hb-menu-one-pager.js */

(function($) {
    'use strict';

    /**
     * One pager menu hash update. 
     * Smooth scroll is appended globally whenever the click element has corresponding #el
     */    
    var onePageNavItem = function onePageNavItem() {
        var $this = $( this ),
            link = $this.find( 'a' ),
            anchor = MK.HB.utils.detectAnchor( link ); // anchor on current page

        if( !anchor.length ) return;

        $this.removeClass( 'current-menu-item current-menu-ancestor current-menu-parent' );

        var activeNav = function( state ) {
            return function() {
                $this[ state ? 'addClass' : 'removeClass' ]( 'current-menu-item' );
                window.history.replaceState( undefined, undefined, [ state ? anchor : ' ' ] );
            };
        };

        MK.HB.utils.scrollSpy( $( anchor )[0], {
            before : activeNav( false ),
            active : activeNav( true ),
            after  : activeNav( false ),
        });
    };

    var $navItems = $('.mkhb-js-nav').find( 'li' );
    
    $(window).on('load', function() {
        // Wait with spying anchors so we do not assign anchor that is browser scroll to after page load
        // Especially when there are anchors on top - refreshing page can cause grabbing one of them into url
        // and force unwanted scroll
        setTimeout(function() {
            $navItems.each( onePageNavItem );
        }, 1000);
    });
    
}(jQuery));





/* hb-scroll.js */
(function($) {
	'use strict';

  var MK = window.MK || {};
  MK.HB = window.MK.HB || {};
  MK.HB.utils = window.MK.HB.utils || {};
  MK.HB.val = window.MK.HB.val || window.MK.val || {};

	/**
	 * Scrolls page to static pixel offset
	 * @param  {Number}
	 */
	MK.HB.utils.scrollTo = function( offset ) {
		$('html, body').stop().animate({
			scrollTop: offset
			}, {
	  		duration: 1200,
	  		easing: "easeInOutExpo"
		});
	};

	/**
	 * Scrolls to element passed in as object or DOM reference
	 * @param  {String|Object}
	 */
	MK.HB.utils.scrollToAnchor = function( hash ) {
		var $target = $( hash );
		// console.log( hash );

		if( ! $target.length ) return;

		var offset  = $target.offset().top;
		offset = offset - MK.HB.val.offsetHeaderHeight( offset );

		if( hash === '#top-of-page' ) window.history.replaceState( undefined, undefined, ' ' );
		else window.history.replaceState( undefined, undefined, hash );

		MK.HB.utils.scrollTo( offset );
	};

	/**
	 * Controls native scroll behaviour
	 * @return {Object} => {disable, enable}
	 */
	MK.HB.utils.scroll = (function() {
        // 37 - left arror, 38 - up arrow, 39 right arrow, 40 down arrow
	    var keys = [38, 40];

        function preventDefault(e) {
          e = e || window.event;
          e.preventDefault();
          e.returnValue = false;  
        }

        function wheel(e) {
          preventDefault(e);
        }

        function keydown(e) {
            for (var i = keys.length; i--;) {
                if (e.keyCode === keys[i]) {
                    preventDefault(e);
                    return;
                }
            }
        }

        function disableScroll() {
            if (window.addEventListener) {
                window.addEventListener('DOMMouseScroll', wheel, false);
            }
          	window.onmousewheel = document.onmousewheel = wheel;
          	document.onkeydown = keydown;
        }

        function enableScroll() {            
          	if (window.removeEventListener) {
                window.removeEventListener('DOMMouseScroll', wheel, false);
            }
            window.onmousewheel = document.onmousewheel = document.onkeydown = null; 
        }	

        return {
        	disable : disableScroll,
        	enable  : enableScroll
        };

	})();

	/**
	 * Checks if passed link element has anchor inside current page. Returns string like '#anchor' if so or false
	 * @param  {String|Object}
	 * @return {String|Boolean}
	 */
	MK.HB.utils.detectAnchor = function( el ) {
		var $this = $( el ),
			href = $this.attr( 'href' ),
			linkSplit = (href) ? href.split( '#' ) : '',
			hrefHash  = linkSplit[1] ? linkSplit[1] : '';

		if( typeof hrefHash !== 'undefined' && hrefHash !== '' ) {
			return '#' + hrefHash;
		} else {
			return false;
		}
	};

	/**
	 * This should be invoked only on page load. 
	 * Scrolls to anchor from  address bar
	 */
	MK.HB.utils.scrollToURLHash = function() {
		var loc = window.location,
			hash = loc.hash;

		if ( hash.length && hash.substring(1).length ) {
			// !loading is added early after DOM is ready to prevent native jump to anchor
			hash = hash.replace( '!loading', '' );

			// Wait for one second before animating 
			// Most of UI animations should be done by then and async operations complited
			setTimeout( function() {
				MK.HB.utils.scrollToAnchor( hash );
			}, 1000 ); 

			// Right after reset back address bar
			setTimeout( function() {
				window.history.replaceState(undefined, undefined, hash);
			}, 1001);
		}
	};

	/**
	 * Scroll Spy implementation. Spy dynamic offsets of elements or static pixel offset
	 * @param  {Number|Element}
	 * @param  {Object} => callback object {before, active, after}
	 */
	MK.HB.utils.scrollSpy = function( toSpy, config ) {
		var $window   = $( window ),
	        container = document.getElementById( 'mk-theme-container' ),
	        isObj     = ( typeof toSpy === 'object' ),
	        offset    = (isObj) ? MK.HB.val.dynamicOffset( toSpy, config.position, config.threshold ) : function() { return toSpy; },
	        height    = (isObj) ? MK.HB.val.dynamicHeight( toSpy ) : function() { return 0; },
	        cacheVals = {},
	        _p 		  = 'before'; // current position

		var checkPosition = function() {
	    	var s = MK.HB.val.scroll(), 
	    		o = offset(),
	    		h = height();

	        if( s < o && _p !== 'before' ) {
	        	// console.log( toSpy, 'before' );
	        	if( config.before ) config.before();
	        	_p = 'before';
	        } 
	        else if( s >= o && s <= o + h && _p !== 'active' ) {
	        	// console.log( toSpy, 'active' );
	        	if( config.active ) config.active( o );
	        	_p = 'active';
	        }
	        else if( s > o + h && _p !== 'after' ) {
	        	// console.log( toSpy, 'after' );
	        	if( config.after) config.after( o + h );
	        	_p = 'after';
	        }
		};

		var rAF = function() {
			window.requestAnimationFrame( checkPosition );
		};

		var exportVals = function() {
			return cacheVals;    
		};

		var updateCache = function() {
	    	var o = offset(),
	    		h = height();
	    		
	        cacheVals = {
	        	before : o - $window.height(),
	        	active : o,
	        	after : o + h
	        };
		};

		if( config.cache ) {
			config.cache( exportVals );
		}

	    checkPosition();
	    $window.on( 'load', checkPosition );
	    $window.on( 'resize', checkPosition );
	    $window.on( 'mouseup', checkPosition );
   		window.addResizeListener( container, checkPosition );

	    $window.on( 'scroll', rAF ); 

   		updateCache();
	    $window.on( 'load', updateCache );
	    $window.on( 'resize', updateCache );
   		window.addResizeListener( container, updateCache );
	};

}(jQuery));




// hb-touch-menu.js
(function($){
  'use strict';

  // Do not assign at all if no touch events
  var hasTouchscreen = ('ontouchstart' in document.documentElement);
  if(!hasTouchscreen) return;

  $('.mkhb-navigation .menu-item-has-children').each( normalizeClick );

  function normalizeClick() {
    $(this).on('click', handleClick);
  }

  // Most reasonable way to normalize click and let hover state on first touch yet don't break experience on multiple input devices is to actually check against desired effect.
  // Better way would be to check against expected state but JS doesn't detect :hover so it would be nice to have it normalized globally like expecting .hover class in css
  function handleClick(e) {
    var $this = $(e.currentTarget);
    var $child = $this.find('> ul');
    var isVisible = $child.css('display') !== 'none';

    if(!isVisible) {
      e.preventDefault();
      e.stopPropagation();
    }
  }

}(jQuery));