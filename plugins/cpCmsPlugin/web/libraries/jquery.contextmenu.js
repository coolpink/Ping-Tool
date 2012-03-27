/**
 * Builds a context menu, either from the DOM or from an array
 *
 * $('.items-to-show-menu-on').contextMenu();
 * $('.items-to-show-menu-on').contextMenu(options);
 * $('.items-to-show-menu-on').contextMenu(options, menu);
 *
 * See defaultoptions for available options.
 *
 * If building the context menu from the DOM, the context menu nodes need the following style:
 *
 * <ul class="is-context-menu"> // this class should be whatever selector attribute you pass into the options
 *  <li class="lorem">ipsum</li>
 *  <li class="dolor">
 *    <a href="blah.html" title="whatever">sit</a>
 *    <ul>
 *      <li class="amet">consectetur</li>
 *      <li class="adipiscing">elit
 *        <ul>
 *          <li class="hendrerit">feugiat</li>
 *          <li class="purus">egestas
 *            <ul>
 *              <li class="rhoncus">magna</li>
 *              <li class="elementum">sodales
 *            </ul>
 *          </li>
 *        </ul>
 *      </li>
 *    </ul>
 *  </li>
 * </ul>
 *
 * Use the following structure to pass the menu directly.
 * $('.items-to-show-menu-on').contextMenu({},
 * [
 *   {
 *     'label'		: 'blah blah',
 *     'icon' 		: '/img/blah.png',
 *     'position' : 'left',
 *     'callback'	: function (e){},
 *     'href'		  : function (e){},
 *     'children'	: [
 *                   {
 *                     'label'		: 'blah blah',
 *                     'icon' 		: null,
 *                     'position' : 'left',
 *                     'callback'	: function (e){},
 *                     'href'		  : function (e){},
 *                     'children'	: [
 *                                  ]
 *                   },
 *                   {
 *                   'label'		: 'blah blah',
 *                   'icon' 		: '/img/blah.png',
 *                   'position' : 'left',
 *                   'callback'	: function (e){},
 *                   'href'		  : function (e){},
 *                   'children'	: [
 *                                ]
 *                   }
 *                  ]
 *   },
 *   {
 *     'label'		: 'blah blah',
 *     'icon' 		: '/img/blah.png',
 *     'position' : 'left',
 *     'callback'	: function (e){},
 *     'href'		  : function (e){},
 *     'children'	: [
 *                  ]
 *   }
 * ]
 * );
 */
(function($){
  $.fn.contextmenu = function(options, menu) {
    if (!$.isArray(menu)) menu = [];
    options = $.extend({}, $.fn.contextmenu.defaults, options);

    return this.each(function() {
      var opts = $.meta ? $.extend({}, options, $(this).data()) : options;
      
      menu = (menu.length == 0) ? iterateNodes($(this).find('ul.'+opts.selector+' > li'), opts) : menu;

      $(this).data('contextmenu', menu);

      document.oncontextmenu = function(e) {
        e.preventDefault();
      }

      // Bind the context menu
      $(this).mousedown(function(e) {
        e.preventDefault();
        $(this).mouseup(function(e) {
          if(e.which == 3) {
            var contextMenu = ($('#'+opts.id).length > 0) ?
                                          $('#'+opts.id) :
                                          $('<div />').attr('id', opts.id).hide().appendTo('body');
            contextMenu.html(iterateMenu($(this).data('contextmenu')));

            $(document).unbind('click.contextmenu'); // So we can right click again

            contextMenu.toggleClass('contextmenu', true);
            contextMenu.css({
              top: e.pageY,
              left: e.pageX
            });

            $.swap(contextMenu[0], { position: "absolute", visibility: "hidden", display: "block" }, function() {
              if ($(window).width() < contextMenu.offset().left + contextMenu.outerWidth()) {
                contextMenu.css('left', $(window).width() - (contextMenu.outerWidth() + 3))
              }
              if ($(window).height() < contextMenu.offset().top + contextMenu.outerHeight()) {
                contextMenu.css('top', $(window).height() - (contextMenu.outerHeight() + 3))
              }
            });

            contextMenu.show();
            
            // Remove the browser default context menu
            contextMenu.bind('contextmenu', function(e){
              e.preventDefault();
            });

            // Binding to hide the menu
            setTimeout(function() {
              $(document).bind('click.contextmenu', function() {
                $(document).unbind('click.contextmenu');
                contextMenu.hide();
              });
            }, 50);
          }
        });
      });
    });



    function iterateMenu(children) {
      var menu = $('<ul />')
      $(children).each(function() {
        var menuItem = createMenu(this);
        if (this.children.length > 0) {
          menuItem.append($('<div />').addClass('hasChildren'));
          var subMenu = iterateMenu(this.children);
          menuItem.append(subMenu);
          menuItem.hover(
            function() {
              $.fn.contextmenu.position(subMenu);
            },
            function() {
              $.fn.contextmenu.hideSubMenu(subMenu);
            }
          );
        }
        menu.append(menuItem);
      });

      return menu;
    }

    function createMenu(data) {
      var item = $('<li />');
      var label = data.label;
      if (data.href) {
        data.hrefLabel = data.hrefLabel ? data.hrefLabel : data.label;
        item.attr('title', data.hrefLabel);
        label = $('<a />').attr('href', data.href).text(label);
      }else {
         label = $("<span />").addClass("aslink").html(label);
      }
      if (data.icon) {
        label.css('background', 'url(' + data.icon + ') no-repeat 3px 5px');
      }
      item.append(label);
      // Fancy test to see if there is a callback - by default it's an empty function
      if (/[^{\s]\s*\}$/.test(String(data.callback))) {
        item.click(function(e){
          e.preventDefault();
          $(this).children('a, span').click(data.callback).click();
        });
      }
      item.hover(
        function () {
          $(this).toggleClass('hovered', true);
        },
        function () {
          $(this).toggleClass('hovered', false);
        }
      );

      return item;
    }

    function iterateNodes(children, opts) {
      var arr = [];
      if (typeof children == "undefined" || children.length === 0) {
        return arr;
      }
      var o = $.extend({}, opts);
      delete o.id;
      delete o.selector;

      children.each(function(){
        var node = createNode($(this), o);
        if ($(this).children('ul').length > 0) {
          node.children = iterateNodes($(this).children('ul').children('li'), o);
        }
        arr.push(node);
      });
      return arr;
    }

    function createNode(el, o) {
      if (typeof el == "undefined") {
        return null;
      }
      var node = $.extend({}, o);
      node.label = $.trim(el.clone().children().not('a').remove().end().end().text());
      node.icon = el.children('img').length > 0 ? el.children('img').eq(0) : o.icon;
      node.href = el.children('a').length > 0 ? el.children('a').eq(0).attr('href') : null;
      node.hrefLabel = el.children('a').length > 0 ? el.children('a').eq(0).attr('title') : null;

      return node;
    }
  };

  /**
   * Displays the context menu element.
   * Checks that it will show on the screen and adjusts it if need be
   * @param el the element to show
   */
  $.fn.contextmenu.position = function(el) {
    el.css({
      top: 0,
      left: el.parent().outerWidth() - 3
    });
    var offset = el.offset();

    if ($(window).width() < offset.left + el.outerWidth()) {
      el.css('left', $(window).width() - (offset.left + el.outerWidth() + ($(window).width() - offset.left)))
    }
    if ($(window).height() < offset.top + el.outerHeight()) {
      el.css('top', $(window).height() - (offset.top + el.outerHeight() + 3))
    }

    el.show();
  };

  /**
   * Hides the context menu by moving it away so that the offset can be calculated later
   * @param el the element to hide
   */
  $.fn.contextmenu.hideSubMenu = function(el) {
    el.css({
      left: '-9999px',
      top: '-9999px'
    });
  }

  /**
   * Default values
   */
  $.fn.contextmenu.defaults = {
    label     : null,              // Text to display
    icon      : null,              // Image icon [URL 12px x 12px/null]
    callback  : function (e){},    // Called instead of following href if not empty
    href      : null,              // Link to follow
    hrefLabel : null,              // Label for link (defaults to label)
    children  : [],                // Array of children
    selector  : 'is-context-menu', // The class of the elements context menu
    id        : 'contextmenu'      // The ID that the context menu will use (must not exist already)
  };
})(jQuery);