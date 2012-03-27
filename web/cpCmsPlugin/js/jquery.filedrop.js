/*
 * Default text - jQuery plugin for html5 dragging files from desktop to browser
 *
 * Author: Weixi Yen
 *
 * Email: [Firstname][Lastname]@gmail.com
 *
 * Copyright (c) 2010 Resopollution
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.github.com/weixiyen/jquery-filedrop
 *
 * Version:  0.1.0
 *
 * Features:
 *      Allows sending of extra parameters with file.
 *      Works with Firefox 3.6+
 *      Future-compliant with HTML5 spec (will work with Webkit browsers and IE9)
 * Usage:
 * 	See README at project homepage
 *
 */
(function($){

	var opts = {},
		default_opts = {
			url: '',
			refresh: 1000,
			paramname: 'userfile',
			maxfiles: 25,
			maxfilesize: 1, // MBs
      fallback: null, // jQuery selector to standard form
      targets: null, // jQuery selector to targets
      usingWorkaround: false,
			data: {},
			drop: empty,
			dragEnter: empty,
			dragOver: empty,
			dragLeave: empty,
			docEnter: empty,
			docOver: empty,
			docLeave: empty,
			error: function(err, file){alert(err);},
			uploadStarted: empty,
			uploadFinished: empty,
			progressUpdated: empty,
			speedUpdated: empty
		},
		errors = ["BrowserNotSupported", "TooManyFiles", "FileTooLarge"],
		doc_leave_timer,
		stop_loop = false;

	$.fn.filedrop = function(options) {
		opts = $.extend( {}, default_opts, options );

    if (this.get(0).addEventListener) {
		  this.get(0).addEventListener("drop", drop, true);
    } else {
      // Support for IE
      this.get(0).attachEvent("drop", drop);
    }
		this.bind('dragenter', dragEnter).bind('dragover', dragOver).bind('dragleave', dragLeave);

    if (document.addEventListener) {
		  document.addEventListener("drop", docDrop, true);
    } else {
      // Support for IE
      document.attachEvent("drop", docDrop);
    }
		$(document).bind('dragenter', docEnter).bind('dragover', docOver).bind('dragleave', docLeave);
	};

	function drop(e) {
		opts.drop(e);
		upload(e.dataTransfer.files);
		e.preventDefault();
		return false;
	}

  function checkBrowserSupport(e) {
    if (typeof DataTransfer != 'undefined' && 'files' in DataTransfer.prototype) {
      // HTML5 support
      return true;
    } else {
      // Try to build a workaround
      if ($('#jquery_filedrop').length > 0 || $(opts.fallback).length < 1) {
        // Already running the workaround or we don't have a fallback form...
        return false;
      }

      // Work out what support we have
      var fi = document.createElement('input');
      fi.type = 'file';
      if(fi.multiple === false || fi.multiple === true) {
        opts.usingWorkaround = 'webkit';
      }
      else if(fi.min === '' && fi.max === '') {
        // If/when it gets support...
        opts.usingWorkaround = 'opera';
      }
      else {
        // No workaround available
        opts.error(errors[0]);
        return false;
      }

      // Build the workaround!
      var dropBox = $('<div />').attr('id', 'jquery_filedrop').css({
        'position': 'absolute',
        'width': '100%',
        'height': '100%',
        'top': 0,
        'left': 0
      });
      // Clone the fallback form, removing IDs and hiding elements
      var form = $(opts.fallback).clone().find('*').removeAttr('id').hide().end();
      var inputSelect = form.find('input[type=file]');
      inputSelect.each(function (){
         $(this).attr({
          'name': $(this).attr('name') + '[]'
        }).css({
          'width': '100%',
          'height': '100%',
          'position' : 'absolute',
          'opacity': 0,
          'z-index': 100
        });
      }).show().parentsUntil('form').show();
      
      // Build the appropriate workaround
      if (opts.usingWorkaround == 'webkit') {
        inputSelect.attr('multiple', 'multiple');
      } else if (opts.usingWorkaround == 'opera') {
        inputSelect.attr('min', 1);
        inputSelect.attr('max', opts.maxfiles);
      }
      $(e.currentTarget).prepend(dropBox.append(form));

      // Event handler
      inputSelect.change(function(e) {
        opts.drop(e);

        if (opts.data.currentTarget) {
          // Update the target directory
          var _dir = getDirFromUrl(opts.data.currentTarget.find('div.action span.link a').attr('href')); // assets_functions.js
          form.find('input[name="upload[directory]"]').val(_dir);
        }
        opts.uploadStarted(0, e.target.files[0], e.target.files.length);
        form.submit();
        e.preventDefault();

        return false;
      });

      if (opts.targets) {
        opts.data.targets = [];
        if ($(opts.targets).length > 0) {
          $(opts.targets).each(function () {
            var _offset = $(this).offset();
            var _target = {
              'el': $(this),
              'l': _offset.left,
              'r': _offset.left + $(this).outerWidth(),
              't': _offset.top,
              'b': _offset.top + $(this).outerHeight()
            };
            opts.data.targets.push(_target);
          });
        } else {
          opts.data.currentTarget = null;
        }
      }
    }
  }

  function updateTarget(e) {
    if (opts.targets) {
      opts.data.pageX = e.pageX;
      opts.data.pageY = e.pageY;
      opts.data.currentTarget = null;
      $(opts.data.targets).each(function () {
        if (e.pageX >= this.l && e.pageX <= this.r && e.pageY >= this.t && e.pageY <= this.b) {
          opts.data.currentTarget = this.el;
          $(this.el).addClass('highlight');
        } else {
          $(this.el).removeClass('highlight');
        }
      });
    }
  }

	function getBuilder(filename, filetype, filedata, boundary) {
		var dashdash = '--',
			crlf = '\r\n',
			builder = '';
    if (!filetype) filetype = 'application/octet-stream';
    
		builder += 'Content-Type: multipart/form-data; ';
		builder += 'boundary=';
    builder += dashdash;
    builder += boundary;
    builder += crlf;
    builder += crlf;

		builder += dashdash;
		builder += boundary;
		builder += crlf;
		builder += 'Content-Disposition: form-data; name="'+opts.paramname+'"';
		builder += '; filename="' + filename + '"';
		builder += crlf;

		builder += 'Content-Type: '+filetype;
		builder += crlf;
		builder += crlf;

		builder += filedata;
		builder += crlf;

		builder += dashdash;
		builder += boundary;

		$.each(opts.data, function(i, val) {
	    	if (typeof val === 'function') val = val();
		  builder += crlf;
			builder += 'Content-Disposition: form-data; name="'+i+'"';
			builder += crlf;
			builder += crlf;
			builder += val;
			builder += crlf;
			builder += dashdash;
			builder += boundary;
		});

		builder += dashdash;
    builder += crlf;
    
		return builder;
	}

	function progress(e) {
		if (e.lengthComputable) {
			var percentage = Math.round((e.loaded * 100) / e.total);
			if (this.currentProgress != percentage) {

				this.currentProgress = percentage;
				opts.progressUpdated(this.index, this.file, this.currentProgress);

				var elapsed = new Date().getTime();
				var diffTime = elapsed - this.currentStart;
				if (diffTime >= opts.refresh) {
					var diffData = e.loaded - this.startData;
					var speed = diffData / diffTime; // KB per second
					opts.speedUpdated(this.index, this.file, speed);
					this.startData = e.loaded;
					this.currentStart = elapsed;
				}
			}
		}
	}



	function upload(files) {
		stop_loop = false;
		if (!files) {
			opts.error(errors[0]);
			return false;
		}
		var len = files.length;

		if (len > opts.maxfiles) {
		    opts.error(errors[1]);
		    return false;
		}

		for (var i=0; i<len; i++) {
			if (stop_loop) return false;
			try {
				if (i === len) return;
				var reader = new FileReader(),
					max_file_size = 1048576 * opts.maxfilesize;

				reader.index = i;
				reader.file = files[i];
				reader.len = len;
				if (reader.file.size > max_file_size) {
					opts.error(errors[2], reader.file);
					return false;
				}

				reader.onload = send;
				reader.readAsBinaryString(files[i]);
			} catch(err) {
				opts.error(errors[0]);
				return false;
			}
		}

		function send(e) {
      var xhr = new XMLHttpRequest(),
        upload = xhr.upload,
        file = e.target.file,
        index = e.target.index,
        start_time = new Date().getTime(),
        boundary = '------multipartformboundary' + (new Date).getTime();
      
      if (typeof file != 'undefined') {
        var builder = getBuilder(file.name, file.type, e.target.result, boundary);

        upload.index = index;
        upload.file = file;
        upload.downloadStartTime = start_time;
        upload.currentStart = start_time;
        upload.currentProgress = 0;
        upload.startData = 0;
        upload.addEventListener("progress", progress, false);
        upload.addEventListener("load", progress, false);

        xhr.open("POST", opts.url, true);
        xhr.setRequestHeader("X_REQUESTED_WITH", "XMLHttpRequest");
        xhr.setRequestHeader('content-type', 'multipart/form-data; boundary='
            + boundary);

        xhr.sendAsBinary(builder);

        opts.uploadStarted(index, file, e.target.len);

        xhr.onload = function() {
            if (xhr.responseText) {
          var now = new Date().getTime(),
              timeDiff = now - start_time,
              result = opts.uploadFinished(index, file, eval( '[' + xhr.responseText + ']' ), timeDiff);
            if (result === false) stop_loop = true;
            }
        };
      }
    }
	}

	function dragEnter(e) {
		clearTimeout(doc_leave_timer);
		e.preventDefault();
    checkBrowserSupport(e);
		opts.dragEnter(e);
	}

	function dragOver(e) {
		clearTimeout(doc_leave_timer);
		if (!opts.usingWorkaround) e.preventDefault(); // Prevent the image opening in the browser window
		opts.docOver(e);
		opts.dragOver(e);
	}

	function dragLeave(e) {
		clearTimeout(doc_leave_timer);
		opts.dragLeave(e);
		docLeave(e);
		e.stopPropagation();
	}

	function docDrop(e) {
		e.preventDefault();
		opts.docLeave(e);
		return false;
	}

	function docEnter(e) {
		clearTimeout(doc_leave_timer);
		e.preventDefault();
		opts.docEnter(e);
		return false;
	}

	function docOver(e) {
		clearTimeout(doc_leave_timer);
		if (!opts.usingWorkaround) e.preventDefault(); // Prevent the image opening in the browser window
    if (opts.usingWorkaround) updateTarget(e); // Keep track of the drop target
		opts.docOver(e);
		if (!opts.usingWorkaround) return false; // Prevent the image opening in the browser window
	}

	function docLeave(e) {
		doc_leave_timer = setTimeout(function(){
			opts.docLeave(e);
      if (opts.usingWorkaround) $('#jquery_filedrop').remove();
		}, 200);
	}

	function empty(){}

})(jQuery);