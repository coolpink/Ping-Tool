/*
 FCBKcomplete 2.5 MOD 0.2 for symfony.

 MODED :
 * Optimised for dynamic json response:
 - serverside limit and filtration of results could be wired
 - better cache handling
 * Key combinations/not editing content keys detection (Ctr+A, Ctr, Shift, Alt, Num lock, Caps Lock, Print screen...)
 * Far less unneccessary requests
 * Ctr + Space triggers immediate autocompletition/query
 * Quick fade animations / Could be turned off
 * New options available
 * Auto select first element

 /* Coded by: emposha <admin@emposha.com> */
/* Moded by: antitoxic <engineering@thesimplecomplex.co.uk> || antioxic@gmail.com*/
/* Copyright: Emposha.com <http://www.emposha.com/> - Distributed under MIT - Keep this message! */
/*
 * json_url        					 - url to fetch json object
 * json_cache      			 		- use cache for json
 * serverside_maxshownitems			- are the results limited by the json_url script
 * serverside_filter				- are the results filtered by the keyword on the serverside
 * height           				- maximum number of element shown before scroll will apear
 * newel            				- show typed text like a element
 * filter_case      				- case sensitive filter
 * culture      					- keep culture of the autocompletition persistant if it is changed in another connection/ browseer window/tab
 * filter_selected  				- filter selected items from list
 * auto_select  					- automatically select first match on the list
 * add_style_selected_on_selected 	- adds class="selected" on initially selected DOM <option> nodes
 * nothingfound_text 				- text for the case of no results/matches
 * complete_text    				- text shown on empty input
 * maxshownitems					- maximum numbers that will be shown at dropdown list (less better performance)
 * timeout							- timeout to wait after a key is typed => if typing quickly server requests are saved by this, edit for you typing confort
 * onselect							- fire event on item select
 * animation_time					- miliseconds or jquery time keywords in which any animation will complete./ if set to 0 no animations for transitions will occur
 * onselect							- fire event on item select
 * onremove							- fire event on item remove
 */
jQuery(
    function ($)
    {
      $.fn.fcbkcomplete = function (opt)
      {
        return this.each(function()
                         {
                           function init()
                           {
                             createFCBK();
                             preSet();
                             addInput(0);
                           }

                           function createFCBK()
                           {
                             element.hide();
                             if (element.attr("name").indexOf("[]") == -1)
                             {
                               element.attr("name",element.attr("name")+"[]");
                             }

                             holder = $(document.createElement("ul"));
                             holder.attr("class", "holder");
                             element.after(holder);

                             complete = $(document.createElement("div"));
                             complete.addClass("facebook-auto");
                             var starting = $(document.createElement("div"));
                             starting.addClass("default");
                             starting.addClass("starting");
                             starting.append(options.complete_text);
                             var nothing = $(document.createElement("div"));
                             nothing.addClass("default");
                             nothing.addClass("nothing");
                             nothing.append(options.nothingfound_text);

                             complete.append(starting);
                             complete.append(nothing);
                             nothing.hide();
                             starting.hide();
                             nothing.fadeOut(options.animation_time);
                             starting.fadeOut(options.animation_time);
                             feed = $(document.createElement("ul"));
                             feed.attr("id", "feed_" + elemid);
                             complete.prepend(feed);
                             holder.after(complete);
                             complete.show();
                           }

                           function preSet()
                           {
                             element.children("option").each(
                                 function(i,option)
                                 {
                                   option = $(option);
                                   if (option.hasClass("selected") && option.attr('selected')!='selected') {
                                     option.attr("selected","selected");
                                   }
                                   if (options.add_style_selected_on_selected && option.attr('selected')) {
                                     option.addClass("selected");
                                   }
                                   if (option.hasClass("selected"))
                                   {
                                     addItem(option.text(), option.val(), true, option.attr('selected'));
                                   }
                                   else
                                   {
                                     option.remove();
                                   }
                                   if (!options.serverside_filter) {
                                     var cacheKey = option.text();
                                     if (!options.filter_case) {
                                       cacheKey = cacheKey.toLowerCase();
                                     }
                                     cache[cacheKey] = {
                                       caption: option.text(),
                                       value: option.val()
                                     }
                                     search_string += "" + (cache.length - 1) + ":" + option.text() + ";";
                                   }
                                 }
                                 );
                           }

                           function getKeyCode(event) {
                             return window.event ? window.event.keyCode : event.which;
                           }

                           function isCtrlHold(event) {
                             var isCtrl = window.event ? window.event.ctrlKey : event.ctrlKey;
                             return isCtrl ? true : false;
                           }
                           function stripHTML(oldString) {
                             return oldString.replace(/(<([^>]+)>)/ig,"");
                           }


                           function addItem(title, value, preadded, selected)
                           {
                             feed.html('');
                             var li = document.createElement("li");
                             var txt = document.createTextNode(title);
                             var aclose = document.createElement("a");

                             $(li).attr({"class": "bit-box","rel": value});
                             $(li).prepend(txt);
                             $(aclose).attr({"class": "closebutton","href": "#"});

                             li.appendChild(aclose);
                             holder.append(li);

                             $(aclose).click(
                                 function(){
                                   $(this).parent("li").fadeOut(options.animation_time,
                                                                function(){
                                                                  removeItem($(this));
                                                                }
                                       );
                                   return false;
                                 }
                                 );

                             if (!preadded || selected)
                             {
                               $("#autocomplete_"+elemid).remove();
                               var _item;
                               if (!preadded) {
                                addInput(1);
                               }
                               if (element.children("option[value=" + value + "]").length)
                               {
                                 element.children("option[value=" + value + "]").remove();
                               }
                               var _item = $(document.createElement("option"));
                               _item.addClass("selected");
                               _item.get(0).setAttribute("selected", "selected");
                               _item.attr("value", value);
                               _item.text(title);
                               element.append(_item);
                               if (options.onselect.length)
                               {
                                 funCall(options.onselect,_item)
                               }
                             }
                             holder.children("li.bit-box.deleted").removeClass("deleted");
                           }

                           function removeItem(item)
                           {
                             element.children("option[value=" + item.attr("rel") + "]").remove();
                             item.remove();
                             if (options.onremove.length)
                             {
                               funCall(options.onremove,item)
                             }
                             $("#autocomplete_"+elemid ).children(".maininput").focus();
                           }

                           function addInput(focusme)
                           {
                             var li = $(document.createElement("li"));
                             var input = $(document.createElement("input"));
                             var isAutocompleteForced = false;
                             var stopCombination = false;

                             li.attr({"class": "bit-input","id": "autocomplete_" + elemid});
                             input.attr({"type": "text","class": "maininput","name" : "autocomplete_fake_input","autocomplete": "off"});
                             holder.append(li.append(input));
                             input.focus(
                                 function()
                                 {
                                   if (feed.html()!='' && input.val().length!=0) {
                                     feed.fadeIn(options.animation_time);
                                   } else if (input.val().length==0) {
                                     complete.children(".starting").fadeIn(options.animation_time);
                                   } else {
                                     complete.children(".nothing").fadeIn(options.animation_time);
                                   }
                                 }
                                 );

                             function dismiss() {
                               feed.fadeOut(options.animation_time);
                               complete.children(".starting").fadeOut(options.animation_time);
                               complete.children(".nothing").fadeOut(options.animation_time);
                             }
                             input.blur(dismiss);

                             holder.click(
                                 function()
                                 {
                                   input.focus();
                                 }
                                 );

                             input.keyup(
                                 function(event)
                                 {
                                   var key = getKeyCode(event);
                                   if (key==27) { // escape
                                     dismiss();
                                     return;
                                   }
                                   if (
                                       (key >= 36 && key <= 40) // home, arrows
                                           || (key >= 16 && key <= 18) //ctr key, shift, alt
                                           || key == 20  // caps lock
                                           || key == 144 // num key
                                           || key == 44 // print screen
                                           || key == 19 // pause/break
                                           || key == 93 // context menu
                                       )
                                   {
                                     return;
                                   }
                                   var etext = input.val();
                                   if (!options.filter_case) {
                                     etext = etext.toLowerCase()
                                   }
                                   function refresh() {
                                     function callback() {
                                       bindEvents();
                                       if (options.auto_select) {
                                         input.trigger('selectFirstMatch');
                                       }
                                     }
                                     if (options.json_url)
                                     {
                                       var existInCache=false;
                                       if (options.json_cache) {
                                         var cacheKey=null;
                                         if (etext.length == 0 ) {
                                           cacheKey = cache_blank_key; // key for the cache entry of empty query
                                           if (options.serverside_filter) {
                                             existInCache = (typeof cache[cache_blank_key] != 'undefined');
                                           }
                                         } else if (options.serverside_filter) {
                                           var directMatch = (typeof cache[etext] != 'undefined');
                                           // check for partial of this query returned less then maxium
                                           // results meaning that this one will have no more
                                           if (!directMatch) {
                                             var isSmallSubqueryFound = false;
                                             for (var arrayKey in cache) {

                                               if (cache[arrayKey].length < options.maxshownitems && arrayKey.length < etext.length) { // check if the subquery is small

                                                 if (etext.indexOf(arrayKey)>=0) {
                                                   cacheKey = arrayKey;
                                                   isSmallSubqueryFound = true;
                                                   break;
                                                 }
                                               }
                                             }
                                           }
                                           existInCache = directMatch ? true : isSmallSubqueryFound ;
                                         }
                                         if (!options.serverside_filter) {
                                           existInCache = json_loaded;
                                         }
                                       }
                                       if (options.json_cache && existInCache)
                                       {
                                         addMemebers(etext,null,callback,cacheKey);
                                       }
                                       else
                                       {
                                         var extraRequestVars='';
                                         if (options.serverside_maxshownitems) {
                                           extraRequestVars = "&l=" + options.maxshownitems;
                                         }
                                         if (options.culture) {
                                           extraRequestVars = extraRequestVars + "&c=" + options.culture;
                                         }
                                         $.getJSON(options.json_url + "?q=" + etext + extraRequestVars, null,
                                                   function(data)
                                                   {
                                                     addMemebers(etext, data,callback,cacheKey);
                                                     if (!options.serverside_filter) {
                                                       json_loaded=true;
                                                     }
                                                   }
                                             );
                                       }
                                     }
                                     else
                                     {
                                       addMemebers(etext,null,callback);
                                     }
                                   }
                                   if (isAutocompleteForced) {
                                     clearTimeout(timer);
                                     refresh();
                                     isAutocompleteForced=false;
                                     return;
                                   }
                                   if (stopCombination) {
                                     stopCombination=false;
                                     return;
                                   }
                                   if (key == 8 && etext.length == 0) // backspace
                                   {
                                     clearTimeout(timer);
                                     function completeStarting() {
                                       var fading;
                                       var visibleNothing = complete.children(".nothing:visible");
                                       fading = visibleNothing.length>0 ? visibleNothing : feed;
                                       fading.fadeOut(options.animation_time,function() {
                                         complete.children(".starting").fadeIn(options.animation_time);
                                       });
                                     }
                                     timer = setTimeout(completeStarting,options.timeout);
                                     if (holder.children("li.bit-box.deleted").length == 0)
                                     {
                                       holder.children("li.bit-box:last").addClass("deleted");
                                       return false;
                                     }
                                     else
                                     {
                                       holder.children("li.bit-box.deleted").fadeOut(options.animation_time, function()
                                       {
                                         removeItem($(this));
                                         return false;
                                       });
                                     }
                                   }
                                   if (etext.length != 0 ) // del, ctr+x
                                   {
                                     counter = 0;
                                     if (options.newel) {
                                       addTextItem(etext);
                                     }
                                     clearTimeout(timer);
                                     timer = setTimeout(refresh,options.timeout);
                                   }
                                 }
                                 );
                             function checkCombinations(event)
                             {
                               var key = getKeyCode(event);
                               var isCtrl = isCtrlHold(event);

                               if(isCtrl && key==32) // space
                               {
                                 isAutocompleteForced=true;
                                 event.preventDefault();
                                 return false;
                               }
                               if(isCtrl && (key==83) ) // S
                               {
                                 stopCombination=true;
                                 event.preventDefault();
                                 return false;
                               }
                               if(isCtrl && (key==65 || key==97) ) // A
                               {
                                 stopCombination=true;
                               }
                               return true;
                             }
                             input.keypress(checkCombinations);
                             if (focusme)
                             {
                               setTimeout(function(){
                                 input.focus();
                               },1);
                             }
                           }

                           function addMemebers(etext, data, callback, cacheKey)
                           {
                             var isDataNew = (data != null && data.length>0);
                             if (options.serverside_filter) {
                               var shouldFilter = false;
                               if (cacheKey==null) {
                                 cacheKey = etext;
                               } else if (cacheKey!=cache_blank_key) {
                                 shouldFilter = true;
                               }
                               // current suggestions for the feed
                               var suggestions;
                               if (isDataNew)
                               {
                                 //save in cache if enabled
                                 if (options.json_cache && cache.length < cache_max) {
                                   cache[cacheKey] = data;
                                 }
                                 suggestions=data;
                               } else if (typeof cache[cacheKey] == 'undefined') {
                                 suggestions = new Array();
                               } else {
                                 suggestions=cache[cacheKey];
                               }
                               var suggestionsLength = suggestions.length;
                               var n = options.maxshownitems < suggestionsLength ? options.maxshownitems : suggestionsLength;
                               var aSuggestion;
                               var suggestuionValueToCompareWith=null;
                               var content = '';
                               for ($i=0;$i<n;$i++) {
                                 aSuggestion = suggestions[$i];
                                 if (!options.filter_case) {
                                   suggestuionValueToCompareWith = aSuggestion.caption.toLowerCase();
                                 } else {
                                   suggestuionValueToCompareWith = aSuggestion.caption;
                                 }
                                 if (
                                     (!shouldFilter || (shouldFilter && suggestuionValueToCompareWith.indexOf(etext)>=0))
                                         && !(options.filter_selected && element.children("option[value=" + aSuggestion.value + "]").hasClass("selected"))
                                     )
                                 {
                                   content += '<li rel="' + aSuggestion.value + '">' + itemIllumination(aSuggestion.caption, etext) + '</li>';
                                   counter++;
                                 }
                               }
                             } else {
                               if (isDataNew)
                               {
                                 $.each(data,
                                        function(i, val)
                                        {
                                          if (cache.length >= cache_max) {
                                            return;
                                          }
                                          if ( typeof cache[val.caption] == 'undefined') {
                                            cache[val.caption] = {
                                              caption: val.caption,
                                              value: val.value
                                            }
                                            search_string += "" + (cache.length - 1) + ":" + val.caption + ";";
                                          }
                                        }
                                     );
                               }

                               var maximum = options.maxshownitems < cache.length ? options.maxshownitems : cache.length;
                               var filter='';
                               if (!options.filter_case)
                               {
                                 filter = "i";
                               }

                               var myregexp = eval('/(?:^|;)\\s*(\\d+)\\s*:[^;]*?' + etext + '[^;]*/g' + filter);
                               var match = myregexp.exec(search_string);
                               var content = '';
                               while (match != null && maximum > 0)
                               {
                                 var id = match[1];
                                 var object = cache[id];
                                 if (!(options.filter_selected && element.children("option[value=" + object.value + "]").hasClass("selected")))
                                 {
                                   content += '<li rel="' + object.value + '">' + itemIllumination(object.caption, etext) + '</li>'+"\n";
                                   counter++;
                                   maximum--;
                                 }
                                 match = myregexp.exec(search_string);
                               }
                             }
                             function refillFeeds() {
                               if (stripHTML($.trim(content.toLowerCase())) != stripHTML($.trim(feed.html().toLowerCase()))) {
                                 feed.fadeOut(options.animation_time,function() {
                                   feed.html('');
                                   feed.append(content);
                                   if (callback) {
                                     callback();
                                   }
                                   if (counter > options.height)
                                   {
                                     feed.css({"height": (options.height * 24) + "px","overflow": "auto"});
                                   }
                                   else
                                   {
                                     feed.css("height", "auto");
                                   }
                                   if (content!='') {
                                     feed.fadeIn(options.animation_time);
                                   } else if (etext.length!=0){
                                     complete.children(".nothing").fadeIn(options.animation_time);
                                   }
                                 });
                               } else {
                                 if (content=='') {
                                   complete.children(".nothing").fadeIn(options.animation_time);
                                 } else {
                                   feed.html(content);
                                   if (callback) {
                                     callback();
                                   }
                                   feed.fadeIn(options.animation_time);
                                 }
                               }
                             }
                             var visibleStarting = complete.children(".starting:visible");
                             var visibleNothing = complete.children(".nothing:visible");
                             var visibleDefault=null;
                             if (visibleStarting.length>0) {
                               visibleDefault = visibleStarting;
                             } else if (content!='' && etext.lenght!=0 && visibleNothing.length>0){
                               visibleDefault = visibleNothing;
                             }
                             if (visibleDefault) {
                               visibleDefault.fadeOut(options.animation_time,refillFeeds);
                             } else {
                               refillFeeds();
                             }
                           }

                           function itemIllumination(text, etext)
                           {

                             eval ("var text = text.replace(/(.*)(" + etext + ")(.*)/gi,'$1<em>$2</em>$3');");
                             return text;
                           }

                           function bindFeedEvent()
                           {
                             feed.children("li").mouseover(
                                 function()
                                 {
                                   feed.children("li").removeClass("auto-focus");
                                   $(this).addClass("auto-focus");
                                   focuson = $(this);
                                 }
                                 );

                             feed.children("li").mouseout(
                                 function()
                                 {
                                   $(this).removeClass("auto-focus");
                                   focuson = null;
                                   if (options.auto_select) {
                                     $("#autocomplete_" + elemid).children(".maininput").trigger("selectFirstMatch");
                                   }
                                 }
                                 );
                           }


                           function bindEvents()
                           {
                             var maininput = $("#autocomplete_"+elemid).children(".maininput");
                             bindFeedEvent();
                             feed.children("li").unbind("click");
                             feed.children("li").click(
                                 function()
                                 {
                                   var option = $(this);
                                   addItem(option.text(),option.attr("rel"));
                                   feed.fadeOut(options.animation_time);
                                 }
                                 );

                             maininput.bind("selectFirstMatch",
                                            function() {
                                              focuson = feed.children("li:first");
                                              focuson.addClass("auto-focus");
                                              feed.get(0).scrollTop = 0;
                                            }
                                 );
                             maininput.unbind("keydown");
                             maininput.keydown(
                                 function(event)
                                 {
                                   var key = getKeyCode(event);
                                   if (key != 8)
                                   {
                                     holder.children("li.bit-box.deleted").removeClass("deleted");
                                   }

                                   if (key == 13 && focuson != null) //enter with selection
                                   {
                                     var option = focuson;
                                     addItem(option.text(), option.attr("rel"));
                                     event.preventDefault();
                                     focuson = null;
                                     return false;
                                   }

                                   if (key == 13 && focuson == null) // enter with nothing selected
                                   {
                                     if (options.newel)
                                     {
                                       var value = $(this).val();
                                       addItem(value, value);
                                       event.preventDefault();
                                       focuson = null;
                                     }
                                     return false;
                                   }

                                   if (key == 40) // key down
                                   {
                                     if (focuson == null)
                                     {
                                       $(this).trigger('selectFirstMatch');
                                     }
                                     else
                                     {
                                       if (focuson.length == 0) {
                                         $(this).trigger('selectFirstMatch');
                                       } else {
                                         //focuson.removeClass("auto-focus");
                                         focuson = focuson.nextAll("li:visible:first");
                                         var prev = parseInt(focuson.prevAll("li:visible").length,10);
                                         var next = parseInt(focuson.nextAll("li:visible").length,10);
                                         if ((prev > Math.round(options.height /2) || next <= Math.round(options.height /2)) && typeof(focuson.get(0)) != "undefined")
                                         {
                                           feed.get(0).scrollTop = parseInt(focuson.get(0).scrollHeight,10) * (prev - Math.round(options.height /2));
                                         }
                                       }
                                     }
                                     feed.children("li").removeClass("auto-focus");
                                     focuson.addClass("auto-focus");
                                   }
                                   if (key == 38) // key up
                                   {
                                     function focusLast() {
                                       focuson = feed.children("li:visible:last");
                                       feed.get(0).scrollTop = parseInt(focuson.get(0).scrollHeight,10) * (parseInt(feed.children("li:visible").length,10) - Math.round(options.height /2));
                                     }
                                     if (focuson == null)
                                     {
                                       focusLast();
                                     }
                                     else
                                     {
                                       if (focuson.length == 0) {
                                         focusLast();
                                       } else {
                                         //focuson.removeClass("auto-focus");
                                         focuson = focuson.prevAll("li:visible:first");
                                         var prev = parseInt(focuson.prevAll("li:visible").length,10);
                                         var next = parseInt(focuson.nextAll("li:visible").length,10);
                                         if ((next > Math.round(options.height /2) || prev <= Math.round(options.height /2)) && typeof(focuson.get(0)) != "undefined")
                                         {
                                           feed.get(0).scrollTop = parseInt(focuson.get(0).scrollHeight,10) * (prev - Math.round(options.height /2));
                                         }
                                       }
                                     }
                                     feed.children("li").removeClass("auto-focus");
                                     focuson.addClass("auto-focus");
                                   }
                                 }
                                 );
                           }

                           function addTextItem(value)
                           {
                             feed.children("li[fckb=1]").remove();
                             if (value.length == 0)
                             {
                               return;
                             }
                             var li = $(document.createElement("li"));
                             li.attr({"rel": value,"fckb": "1"}).html(value);
                             feed.prepend(li);
                             counter++;
                           }

                           function funCall(func,item)
                           {
                             var _object = "";
                             for(i=0;i < item.get(0).attributes.length;i++)
                             {
                               if (item.get(0).attributes[i].nodeValue != null)
                               {
                                 _object += "\"_" + item.get(0).attributes[i].nodeName + "\": \"" + item.get(0).attributes[i].nodeValue + "\",";
                               }
                             }
                             _object = "{"+ _object + " notinuse: 0}";
                             eval(func+"("+_object+")");
                           }

                           var options = $.extend({
                                                    json_url: null,
                                                    height: "10",
                                                    newel: false,
                                                    filter_case: false,
                                                    filter_selected: true,
                                                    complete_text: "Start to type...",
                                                    nothingfound_text: "No more matches.",
                                                    maxshownitems:  30,
                                                    timeout: 280,
                                                    animation_time: 130,
                                                    auto_select: true,
                                                    add_style_selected_on_selected: true,
                                                    serverside_maxshownitems:  true,
                                                    serverside_filter:  true,
                                                    culture: null,
                                                    onselect: "",
                                                    onremove: ""
                                                  }, opt);

                           //system variables
                           var holder     		= null;
                           var feed       		= null;
                           var complete   		= null;
                           var counter    		= 0;
                           var cache      		= new Array();
                           var cache_blank_key = 'n3eJsk9ff1cHk4';
                           var cache_max      	= 122;
                           var json_loaded		= false;
                           var search_string	= "";
                           var focuson    		= null;
                           var timer    		= null;

                           var element = $(this);
                           var elemid = element.attr("id");
                           init();

                           return this;
                         });
      };
    }
    );