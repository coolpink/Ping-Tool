/**
 * Style switcher - jQuery plugin
 * Requires: jquery.cookie.js (http://plugins.jquery.com/project/cookie)
 *
 * Call it on the element/s that you want the styles applied to.
 *   $('ul').styleSwitcher({selector: '#styleSwitcher'});
 *
 * #styleSwitcher is the element receiving the click event which will show the first sibling UL as the options list
 *   <a id="styleSwitcher">Change style</a>
 *   <ul>
 *     <li class="style1"><a title="Swap to Style1">Swap to Style1</a></li>
 *     <li class="style2"><a title="Swap to Style2">Swap to Style2</a></li>
 *   </ul>
 */
(function($){
  $.fn.styleSwitcher = function(options) {

    var self = this;
    
    var opts = $.extend({}, $.fn.styleSwitcher.defaults, options);
    if (!opts.selector) return this;
    opts.data.choices = $(opts.selector).next('ul');
    opts.data.choicesWidth = 0;

    /**
     * Main selector
     */
    $(opts.selector).click(function(e) {
      e.preventDefault();
      e.stopPropagation();
      if (opts.data.choices.is(':hidden')) {
        // Update the choices width if need be
        if (opts.data.choicesWidth < 1) {
          // Can't directly get the width of a hidden element
          $.swap(opts.data.choices[0], { position: "absolute", visibility: "hidden", display: "block" }, function() {
            if (opts.data.choices.width() < $(opts.selector).width()) {
              opts.data.choicesWidth = $(opts.selector).width();
              opts.data.choices.width($(opts.selector).width());
            }
          });
        }

        $.fn.styleSwitcher.show(opts.data.choices);
        opts.showSelector(e);
      } else {
        $.fn.styleSwitcher.hide(opts.data.choices);
        opts.hideSelector(e);
      }
    });

    /**
     * Option selector
     */
    $(opts.data.choices).click(function(e) {
      e.preventDefault();
      if ($.inArray($(e.target).closest('li').attr('class'), opts.styles) > -1) {
        opts.data.currentStyle = $(e.target).closest('li').attr('class');
        $.each(opts.styles, function(i, v) {
          // Remove existing styles
          self.removeClass(v);
        });
        self.addClass(opts.data.currentStyle);

        if (opts.cookieName) {
          $.cookie(opts.cookieName, opts.data.currentStyle, opts.cookie);
        }
      }
      $.fn.styleSwitcher.hide(opts.data.choices);
      opts.hideSelector(e);
    });

    /**
     * Close selector
     */
    if (opts.cancelOnBody) {
      $(document.body).click(function (e) {
          $.fn.styleSwitcher.hide(opts.data.choices);
          opts.hideSelector(e);
      });
    }

    /**
     * Apply style to element/s
     */
    return this.each(function(e) {
      var o = $.meta ? $.extend({}, opts, $(this).data()) : opts;

      o.data.currentStyle = o.styles[0];
      if (o.cookieName && $.cookie(o.cookieName)) {
        o.data.currentStyle = $.cookie(o.cookieName);
      }

      $(this).addClass(o.data.currentStyle);

      if (o.cookieName) {
        $.cookie(o.cookieName, o.data.currentStyle, o.cookie);
      }
    });
  };

  $.fn.styleSwitcher.show = function(el) {
    el.show();
  };

  $.fn.styleSwitcher.hide = function(el) {
    el.hide();
  };
  
  $.fn.styleSwitcher.defaults = {
    data: {},
    showSelector: function(e) {},        // Called when we show the options list
    hideSelector: function(e) {},        // Called when we hide the options list
    chooseSelector: function(e) {},      // Called when we select an option in the list
    cancelOnBody: true,                  // Whether to close the option list when clicking on the rest of the page
    cookieName: 'styleswitcher_cookie',  // What name to use for storing cookies (null to disable)
    cookie: { expires: 31, path: '/' },  // Options to pass to jquery.cookie
    styles: [ 'list', 'grid' ],          // List of valid styles (default style first)
    selector: ''                         // The UL containing the options to choose from
  };
})(jQuery);