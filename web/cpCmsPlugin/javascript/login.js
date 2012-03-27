(function ($){

	$.fn.loginForm = function (bgs){

		var backgrounds = bgs;

		function rejig(elm) {

			var height = $(window).height();
			var width = $(document).width();

			elm.css({
				'left' : width/2 - (342 / 2),
				'top' : height/2 - (316 / 2)
			});

		}

		function postForm(form){

			var self = form;

			$.ajax({
			  type: 'POST',
			  url: self.attr("action"),
			  data: self.serialize(),
			  dataType: 'application/json',
			  success: function (data){

				if (typeof(data) == "string"){

					data = jQuery.parseJSON(data);
				}

				if (data.success){

						location.href = data.redirect;

				}else {
					$.cpbar({
						message  : "You have entered an invalid username or password",
						type	 : "error"
					});

					self.find("div.textfield").addClass("error");
				}

			  },
			  error : function (){

				$.cpbar({
					message  : "There has been a problem logging in. Our tech team have been notified",
					type	 : "error"
				});

			  }

			});


			return false;
		}

		this.each(function (){

			var bg = backgrounds[Math.floor(Math.random() * backgrounds.length)];

			$("body").css({
				'background-image' : 'url('+bg.image+')',
				'background-color' : bg.color
			});

			var self = $(this);

			$(window).resize(function (){
				rejig(self);
			});

			rejig(self);

			self.find("div.textfield input").keydown(function (){

				$(this).parents("div.textfield").removeClass("error");

			}).focus(function (){

				$(this).parents("div.textfield").toggleClass("highlight", true);

			}).blur(function (){

				$(this).parents("div.textfield").toggleClass("highlight", false);

			});

			if (!$.browser.msie) {

				self.find("fieldset").hide();

				self.delay(100).show("scale",{},600, function (){



					$(this).css({

						"-webkit-transition" : "all .15s ease-out",
						"-moz-transition" : "all .15s ease-out"


						}).find("fieldset").fadeIn("slow").end().find('input:first').focus();

				});

			}else {

				self.show().find('input:first').focus();

			}

			self.submit(function (e){
				e.preventDefault();
				postForm(self);
			});
			self.find("button").click(function (e){
				e.preventDefault();
				postForm(self);
			});

			return this;

		});

		return this;

	}

})(jQuery);