
(function($) {

   var wizards = {};
   var wizardZIndex = 10000;

   $.wizard = function(settings) {

   if (wizards[settings.id] == null){

     var new_wizard = $("<div></div>").css({
       'position': 'absolute',
       'z-index': wizardZIndex++,
       'visibility' : 'hidden'
     }).attr("id", settings.id).toggleClass("wizard");
     var actions = $("<div></div>").addClass("actions");
     if (settings.buttons) {
      $(settings.buttons).each(function() {
        var button = $('<button></button>').addClass(this.style).text(this.text).click(this.callback).appendTo(actions);
      });
     }
     new_wizard.html(actions).appendTo($(document.body)).wizard(settings);

     wizards[settings.id] = new_wizard;

   }else {

     wizards[settings.id].wizard(settings);

   }

   return new_wizard;

   };

   $.fn.destroywizard = function (){

     this.each(function() {

      var self = $(this);
      wizards[self.attr("id")] = null;
      self.remove();

    });

   }

   $.fn.wizard = function (settings){

    var config = {'url': null};

    if (settings) $.extend(config, settings);

    this.each(function() {

    var self = $(this);

    self.append('<h2><img src="' + settings.icon + '" /> ' + settings.title + '</h2>');

    if (settings.url == null)
    {
      self.append('<div class="frame"></div>');
    }else {
      var _name = settings.name ? ' name="' + settings.name + '"' : '';
      self.append('<iframe class="frame" src="' + settings.url + '" ' + _name +'/>');
    }

    if (settings.elm != null){

     $(settings.elm).show().appendTo(self.find(".frame"));

    }
    self.draggable({
      handle  :  'h2',
      start   : function (){
        $(this).css("z-index", wizardZIndex++);
      }
    }).click(function (){
      $(this).css("z-index", wizardZIndex++);
    });

    var height = $(window).height();
    var width = $(document).width();

    self.css({
      'left' : width/2 - (self.width() / 2),
      'top' : height/2 - (self.height() / 2)

    });
    self.css("visibility", "visible");

   });


   return this;

   }

 })(jQuery);
