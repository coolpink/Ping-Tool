(function($) {

   $.fn.adminMenu = function(settings) {
     var config = {}

     if (settings) $.extend(config, settings);

     this.each(function() {

		var tabLinks = $(this).find("ul.tabs a");
		var toolBars = $(this).find('div.toolbar');

		tabLinks.mousedown(function (){

			tabLinks.toggleClass("selected", false);
			$(this).toggleClass("selected", true);

			toolBars.removeClass("selected");

        	$($(this).attr("rel")).addClass("selected");

		});


     });

     return this;

   };

 })(jQuery);
var maps = [];
function collapseFieldsets(){
	var counter = $(document).find("div.has-error").length;
	$('fieldset:has(label)').each(function (){
		var self = $(this);
		if (self.find("fieldset").length < 1){
			var fieldset_fields = self.find('div.fieldset-fields');
			if (counter++ > 0 && fieldset_fields.find("div.has-error").length == 0){
				fieldset_fields.hide();
			}
			self.find("h2").click(function (){
				fieldset_fields.slideToggle("slow", function (){
          for (i=0; i<maps.length; i++){
            if (google != null){
              google.maps.event.trigger(maps[i], 'resize');
              if (maps[i].savedPosition != undefined && maps[i].savedPosition != null){
                maps[i].setCenter(maps[i].savedPosition);
              }
            }
          }
        });


			}).mouseenter(function (){
				$(this).toggleClass("hover", true);
			}).mouseleave(function (){
				$(this).toggleClass("hover", false);
			});
		}
	});

}