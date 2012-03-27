function file_details(path){
	if (/\.\w+$/.test(path)){
		if (path.match(/([^\/\\]+)\.(\w+)$/) )
			return {filename: RegExp.$1, ext: RegExp.$2};
	}else {
		if (path.match(/([^\/\\]+)$/) )
			return {filename: RegExp.$1, ext: null};
	}
}
function launchCropper(from){
	var p = $(from).parents(".form-row");
	var imagetag = p.find("div.selected_image img");
	var inputtag = p.find("input.sfWidgetFormInputImage");
	
	$.cropper({
		url: inputtag.val(),
		onCrop : function (c){
			var url = crop_url;
			$.post(url, c, function (data){
				try {
					$.cropper.destroy();
				}catch(e){
				}
				imagetag.attr("src", data.new_url+'/max_width/450/max_height/450?'+new Date().getTime());
				inputtag.val(data.new_url);
				
			},'json');
		}
	});
}
(function($) {

	var cropper = null;

	var crop_api = null;

	$.cropper = function (settings){
		

		if (cropper == null){

			cropper = $.wizard({
					id: 'cropper',
					icon : '/cpAdminGeneratorPlugin/wizard/images/package_graphics.png',
					title : 'Image Cropper',
          buttons:  [{
                      text: 'Cancel',
                      style: 'grey',
                      callback  : function (e){
                        e.preventDefault();
                        $.cropper.destroy();
                      }
                    }]
				}).cropper(settings);

		}
		return cropper;
	}

	$.cropper.load = function(url){

		cropper.cropper("load", url);

	}

	$.fn.cropper = function (settings, param){

		if (param == null){

			var config = {

				onCrop : function () {}

			}

			if (settings) $.extend(config,settings);


			this.each(function() {

				var self = $(this);

				var frame = self.find("div.frame");

				var content = $('<div class="wizard-content" />').appendTo(frame);

				var buttons = self.find("div.actions");

				var saveButton = $('<button class="green">Save</button>').click(function (){

					var dimensions = crop_api.tellSelect();

					if (dimensions.w == 0 || dimensions.h == 0){
						return $.cropper.destroy();
					}

					image_url = self.find("img.croppable").attr("src");
					var image_details = file_details(image_url);
					var suggested_filename = image_details.filename + "_crop." + image_details.ext;
					var new_file = null;
					var valid = false;

					while (!valid){
						var user_filename = prompt('Please enter the new name for this file', suggested_filename);
						new_file = file_details(user_filename);
						if (new_file == null){
							alert("Please enter a file name");
						}else if (new_file.ext.toLowerCase() != image_details.ext.toLowerCase()){
							alert("You have entered an invalid file name. Please make sure your file extension is ."+image_details.ext);
						}else if (new_file.filename == ""){
							alert("Please enter a file name");
						}else {
							valid = true;
						}
					}

					self.find("button").attr("disabled", "true");
					var details = $.extend(dimensions, {
						image_url : image_url,
						new_url : new_file.filename + '.' + new_file.ext.toLowerCase(),
						resized_width: $('img.croppable').width(),
						resized_height: $('img.croppable').height()
					});

					settings.onCrop(details);

					$.cropper.destroy();

				});

				saveButton.appendTo(buttons);

				content.append("<p>Drag over area you wish to crop, then click Save.</p>");

				var showUrl = settings.url == null ? "/cpAdminGeneratorPlugin/cropper/images/none.gif" : "/cpAdminGeneratorPlugin/cropper/images/loading.gif";

				$('<div><img src="' + showUrl + '" width="560" class="croppable" /></div>').appendTo(content);

				if (settings.url != null){
					self.cropper("load", settings.url);
				}

				return this;
			});

		}else {


			var command = settings;

			var self = $(this);
			var img = self.find("img.croppable");

			switch (command){

				case "load":

					$("<img />").load(function (){

						var me = $(this);

 						me.removeAttr("width")
           				.removeAttr("height")
           				.css({ width: "", height: "" });

           				var width_orig = this.width;
           				var height_orig = this.height;

						var width = max_width = 560;
						var height = max_height = 390;

						var marginLeft = 0;

						if (width_orig > width || height_orig > height){

							var max_ratio = width/height;

							var ratio_orig = width_orig/height_orig;

							if (max_ratio > ratio_orig) {
							  width = height*ratio_orig;
							  marginLeft = (max_width - width)/2;
							} else {
							  height = width/ratio_orig;
							}

						}

						img.parent().css("margin-left", marginLeft);

						img.css({
							width : width,
							height : height
							}).attr("width", width).attr("height", height).attr("src", me.attr("src"));

						crop_api = $.Jcrop(img, { trueSize: [width_orig,height_orig] });

					}).attr("src", param);

					break;

			}

		}

	    return this;

	 }

	 $.cropper.destroy = function (){
		if (crop_api != null)
		{
			crop_api.destroy();
		}
		cropper.destroywizard();
		cropper = null;
		crop_api = null;

	 }

})(jQuery);