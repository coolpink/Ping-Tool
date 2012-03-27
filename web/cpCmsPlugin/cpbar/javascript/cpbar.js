(function ($){

	$.cpbar = function (settings){
		var config = {
			message  : null,
			html	 : null,
			type	 : "error",
			duration : 4000,
			id		 : null
		}
     	if (settings) $.extend(config, settings);
     	if (config.message == null && config.html == null)
     	{
			return false;
		}

		function setupBarItem (item){
			item.each(function (){
				var self = $(this);
				$(document).mousemove(function (){
					self.data("mousemoved", true);
					if (self.data("expired")){
						self.fadeOut("slow", function (){
							self.remove();
						});
					}
				});
				self.data("timeout", setTimeout(function (){
					self.data("expired", true)
				}, config.duration));
			 }).data("mousemoved", false).data("expired", false);
			 return item;
		}

		bar = $('#cpbar');
		if (!bar.length){
			bar = $('<div id="cpbar" />').css({
				'position' 	: 'absolute',
				'bottom' 	: 0,
				'width' 	: '100%',
				'z-index' 	: 10000,
				'opacity' 	: 0.88,
				'cursor'  	: 'default'
				}).appendTo("body");
		}
		if (config.id != null){
			var relevantElm = $('#'+config.id);
			if (relevantElm.length){
				clearTimeout(relevantElm.data("timeout"));
				relevantElm
					.stop(true, true)
					.fadeIn()
					.removeClass()
					.addClass('cpbar_item')
					.addClass('cpbar_' + config.type)
					.html(config.message == null ? config.html : '<div class="cpbar_msg">' + config.message + '</div>');
				setupBarItem(relevantElm);
			}else {
				var newblock = $('<div class="cpbar_item cpbar_' + config.type + '" />')
									.hide()
									.attr("id", config.id)
									.html(config.message == null ? config.html : '<div class="cpbar_msg">' + config.message + '</div>')
									.appendTo(bar)
									.slideDown().click(function (){
										clearTimeout($(this).data("timeout"));
										$(this).fadeOut("slow", function (){
											$(this).remove();
										});
									});

				setupBarItem(newblock);
			}
		}else {
			var newblock = $('<div class="cpbar_item cpbar_' + config.type + '" />')
								.hide()
								.html(config.message == null ? config.html : '<div class="cpbar_msg">' + config.message + '</div>')
								.appendTo(bar)
								.slideDown().click(function (){
										clearTimeout($(this).data("timeout"));
										$(this).fadeOut("slow", function (){
											$(this).remove();
										});
									});

			setupBarItem(newblock);
		}
	}
})(jQuery);