jQuery.expr[':'].data = function(elem, index, m) {

      m[0] = m[0].replace(/:data\(|\)$/g, '');

    var val,key;

      var regex = new RegExp('([\'"]?)((?:\\\\\\1|.)+?)\\1(,|$)', 'g'),
        key1 = regex.exec( m[0] ),
        val1 = regex.exec( m[0] );
        if (key1 != null && key1.length > 2) key = key1[2];
        if (val1 != null && val1.length > 2) val = val1[2];


    return val ? jQuery(elem).data(key) == val : !!jQuery(elem).data(key);

};

(function($) {

  $.fn.cpSelectable = function (settings, param){


    var config = {
      multiselect   : true,
      dragover    : this,
      elements    : 'li',
      change        : function (){ },
      mouseenter    : function (){ },
      mouseleave    : function (){ }
    }

    // extend the config
    if (settings && typeof(settings) != "string"){
    // create the dragger
        var dragging = null;
        var dragger = $('<div />').css({
          'position'      : 'absolute',
          'background-color'  : '#b7d3ef',
          'border'      : '1px solid #3399ff',
          'opacity'     : 0.5,
          'z-index'     : 10000
          }).hide().appendTo("body");
        
      $.extend(config,settings);
      this.data("config", config);

    }


    function doChange(selected, unselected){

      selected.find("input[type=checkbox]").attr("checked", true);
      unselected.find("input[type=checkbox]").attr("checked", false);

      config.change(selected, unselected);
    }


    var selected = $([]);


    if (typeof(settings) == 'string'){

      var command = settings;

      switch(command){

        case "getSelected":
          return this.find(this.data("config").elements).filter(":data(selected,yes)");
          break;

      }

    }



    // for each selectable item...
    this.each(function (){

      if (config.dragover != null && config.multiselect){

        config.dragover.mousedown(function (e){

          if (e.pageX > $(window).width() - 30){
              return;
          }
          if (!e.shiftKey && selected.length > 0){

            selected = $([]);

            selectable.data("selected", "no");

            doChange(selected, selectable.filter(":data(selected,no)"));

          }



          e.preventDefault();

          if (e.shiftKey){

            e.stopPropagation();

          }

          dragging = {
            x : e.pageX,
            y : e.pageY
            };

          $(document).bind("mousemove.cpselectable", function (e){
      
            var dragovercoords = config.dragover.offset();
            dragovercoords.right = dragovercoords.left + config.dragover.width();
            dragovercoords.bot = dragovercoords.top + config.dragover.height();
            var lowx  = Math.min(dragging.x, e.pageX);
            var lowy  = Math.min(dragging.y, e.pageY);
            var highx   = Math.max(dragging.x, e.pageX);
            var highy   = Math.max(dragging.y, e.pageY);

            /**
             * Prevent box being drawn outside of the dragover area
             */
            if(dragovercoords.left > lowx) lowx = dragovercoords.left;
            if(dragovercoords.right < highx) highx = dragovercoords.right;
            if(dragovercoords.bot < highy) highy = dragovercoords.bot;
            if(dragovercoords.top > lowy) lowy = dragovercoords.top;

            dragger.show().css({

              left  : lowx,
              top   : lowy,
              width : highx - lowx,
              height  : highy - lowy

            });

            var newselection = $([]);


            selectable.each(function (){

              var offset = $(this).offset();
              offset.right = offset.left + $(this).outerWidth();
              offset.bottom = offset.top + $(this).outerHeight();

              if (!(  offset.left   >= highx ||
                  offset.right  <= lowx  ||
                  offset.top    >= highy ||
                  offset.bottom   <= lowy  )) {

                if (jQuery.inArray(this, newselection) < 0){

                  newselection.push(this);

                }

              }


            });


            if (e.shiftKey) {

              var change = false;

              newselection.each(function (){

                if (jQuery.inArray(this, selected) < 0){

                  selected.push(this);
                  change = true;

                }

              });

              if (change){


                selectable.data("selected", "no");

                selected.data("selected", "yes");

                doChange(selected, selectable.filter(":data(selected,no)"));

              }

            }else {

              if (newselection != selected){

                selectable.data("selected", "no");

                selected = newselection;

                selected.data("selected", "yes");

                doChange(selected, selectable.filter(":data(selected,no)"));

              }
            }


          }).bind("mouseup.cpselectable", function (e){

            dragger.hide();

            $(document).unbind(".cpselectable");

            dragging = null;

          });

        });

      }

      var self = $(this);

      var selectable = self.find(config.elements)

      .mouseenter(function (){

        if (dragging != null) return false;

        config.mouseenter(this);

      }).mouseleave(function (){

        config.mouseleave(this);

      }).mouseup(function (e){

        if (e.ctrlKey) {

            selectable.data("selected", "no");

            // Ctrl-click - add/remove individual element
            var loc = jQuery.inArray(this, selected);
            if (loc == -1) {
            // Element not in the array, add it
            selected.push(this);
            }
            else {
            // Element already exists, remove it
            selected.eq(loc);
            selected.splice(loc, 1);
            }

            selected.data("selected", "yes");

            doChange(selected, selectable.filter(":data(selected,no)"));

        }else if (e.shiftKey) {

          // do nothing (yet)

        }else {

          if (jQuery.inArray(this, selected) > -1){

          selectable.data("selected", "no");

            selected = $([this]);

            selected.data("selected", "yes");

            doChange(selected, selectable.filter(":data(selected,no)"));

          }


        }

      }).mousedown(function (e){

        e.preventDefault();

        e.stopPropagation();

        if (config.multiselect){


          if (e.ctrlKey) {

            return true;

          }
          else if (e.shiftKey) {

            // Shift-click - add all elements between cp_selected
            var second_el_index = $(this).prevAll(config.elements).length;
            var first_el = selected.last();
            var first_el_index = selected.prevAll(config.elements).length;
            if (first_el_index == second_el_index) {
            // cp_selected the same element
            selected = $([this]);
            }
            else if (first_el_index < second_el_index) {
            // cp_selected forwards
            selected = first_el.nextUntil(":nth-child(" + (second_el_index + 1) + ")");
            }
            else if (first_el_index > second_el_index) {
            // cp_selected backwards
            selected = first_el.prevUntil(":nth-child(" + (second_el_index + 1) + ")");
            }
            selected.splice(0, 0, first_el[0]); // Push the first element onto the start of the array
            selected.push(this); // Push the second element onto the end of the array
          }
          else {

            // Regular click - select individual element
            if (jQuery.inArray(this, selected) > -1){

              return true;

            }

            selected = $([this]);
          }
        }else {

            selected = $([this]);

        }

        selectable.data("selected", "no");

        selected.data("selected", "yes");

        doChange(selected, selectable.filter(":data(selected,no)"));


      });

      $(this).find("input[type=checkbox]").each(function (){

        if (this.checked){

          selected.push($(this).parents(config.elements)[0]);

        }

      }).mousedown(function(e){

        var parentElm = $(this).parents(config.elements)[0];

        if (!this.checked){

          if (config.multiselect){

            selected.push(parentElm);

          }else {

            selected.find("input[type=checkbox]").attr("checked", false);

            selected = $([parentElm]);

          }

          selectable.data("selected", "no");

          selected.data("selected", "yes");

          config.change(selected, selectable.filter(":data(selected,no)"));


        }else {

          var loc = jQuery.inArray(parentElm, selected);

          if (loc > -1){

            selected.splice(loc, 1);

            selectable.data("selected", "no");

            selected.data("selected", "yes");

            config.change(selected, selectable.filter(":data(selected,no)"));

          }

        }

        e.stopPropagation();

      }).mouseup(function (e){

        e.stopPropagation();

      });

      selected.data("selected", "yes");

      doChange(selected, selectable.filter(":data(selected,no)"));

    });
    return this;

  }

})(jQuery);