jQuery(function($) {

  'use strict';

  // Get All Related Layers
  var init = function init() {
    var $portfolio = $('.portfolio-grid');
    var $imgs = $portfolio.find('img[data-mk-image-src-set]');

    if ( $portfolio.hasClass('portfolio-grid-lazyload') && $imgs.length ) {

      // Load Images if the user scrolls to them
      $(window).on('scroll.mk_portfolio_lazyload', MK.utils.throttle(500, function(){
        $imgs.each(function(index, elem) {
          if ( MK.utils.isElementInViewport(elem) ) {
            MK.component.ResponsiveImageSetter.init( $(elem) );
            $imgs = $imgs.not( $(elem) );  // Remove element from the list when loaded to reduce the amount of iteration in each()
          }
        });
      }));

      $(window).trigger('scroll.mk_portfolio_lazyload');

      // Handle the resize
      MK.component.ResponsiveImageSetter.onResize($imgs);

    } else {

      MK.component.ResponsiveImageSetter.init($imgs);
      MK.component.ResponsiveImageSetter.onResize($imgs);

    }
  }

  init();
  $(window).on('vc_reload', function(){
    init();

    $('.mk-portfolio-container').each(function(){
      var id = $(this).attr('id');
      var el = '#' + id + '.mk-portfolio-container.js-el';

      if($(this).data('mk-component') == 'Grid') {
        // Disable VC frontend component init for portfolio.
        $(el).data('init-Grid', true);

        // Init Grid component on shortcode render.
        var component = new MK.component[ 'Grid' ]( el );
        component.init();
      }
    });

  });

});


