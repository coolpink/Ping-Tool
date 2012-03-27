

var dragStates = {
    move : "move",
    sort : "sort"
}
var sortStates = {
    before : "before",
    after : "after"
}
var state = dragStates.move;
var currentHover = null;
var currentHoverY = 0;
var currentSelected = null;
var isDragging = false;
var elementHeight = 0;
var sortState = sortStates.before;
var isValidMove = false;

$(function (){
    $('table.listing>tbody>tr').each(function (){

        elementHeight = $(this).height();

    }).mouseenter(function (e){

        e.preventDefault();

        currentHover = $(this);

        currentHoverY = currentHover.offset().top;

        if (!isDragging){

            currentHover.toggleClass("hovered", true);

        }

    }).mouseleave(function (){

        $('table.listing>tbody>tr:visible').toggleClass("hovered", false);

    }).mousedown(function (e){

        
        e.preventDefault();
        e.stopPropagation();
        var dragSpecificallyDisabled = false;
        
        if ($(this).attr("cmsdraggable") == "false")
        {
          	dragSpecificallyDisabled = true;
        }

        if (e.button == 2) return;

        if ($(this).parents("table#tree").length > 0){

            $(document).mousemove(function (e){

                var previousState = state;

                var previousSortState = sortState;

                currentSelected = $('table.listing').cpSelectable("getSelected");

                e.preventDefault();

                isDragging = true;

                if (this.dragMessage == null){

                    this.dragMessage = createDragMessage();

                }
                this.dragMessage.css({

                    top : e.pageY+20,
                    left: e.pageX+20

                });
               
                if (e.pageY < (currentHoverY + 6)){

                    state = dragStates.sort;

                    sortState = sortStates.before;

                }else if (e.pageY > (currentHoverY + elementHeight - 6)){

                    state = dragStates.sort;

                    sortState = sortStates.after;

                }else {

                    if (currentHover[0] != currentSelected[0]){

                        currentHover.each(function (){

                            var x = $(this);

                            clearTimeout(x[0].timeout);

                            x[0].timeout = setTimeout(function (){

                                if (x[0] == currentHover[0]){

                                    x.expand();

                                }

                            }, 500);
                        });

                    }

                    state = dragStates.move;

                }

                if (sortState != previousSortState || state != previousState){

                    $("table#tree tr:visible").toggleClass("dragbefore", false).toggleClass("dragafter", false);

                    var myparents = $('table#tree').getParents(currentHover);

                    var hisparents = $('table#tree').getParents(currentSelected);

                    var safe = !dragSpecificallyDisabled;

                    for (var i=0; i<myparents.length; i++){

                        if (myparents[i][0] == currentSelected[0]){

                            safe = false;

                            break;

                        }

                    }
                    if (state == dragStates.sort && currentHover[0].className.indexOf("child-of") == -1){

                        safe = false;

                    }

                    if (state == dragStates.move && hisparents.length > 0 && hisparents[0][0] == currentHover[0]){

                        safe = false;

                    }
                    if (currentSelected[0] != null && currentSelected[0] != currentHover[0] && safe){


						var elmToCheck = state == dragStates.sort ? myparents[0] : currentHover[0];
						if (isAllowedAsChild(currentSelected[0], elmToCheck)){

							isValidMove = true;
		
							$('#move-position').html(state == dragStates.move ? "into" : sortState);

							$('span#move-where').html(currentHover.find("span.file-name").html());

							$('.dragger-moveto').show();

							$('.dragger-error').hide();

						}else {

							isValidMove = false;
							
							$('.dragger-moveto').hide();

							$('.dragger-error').show();
						}
 

                    }else {

                        isValidMove = false;

                        $('.dragger-moveto').hide();

                        $('.dragger-error').show();

                    }

                }
                
                if (state == dragStates.sort){

                    if (sortState != previousSortState || state != previousState){

                        if (sortState == sortStates.before){

                            var prev = currentHover.prevAll(":visible").eq(0);

                            if (prev.length > 0 && $("table#tree").getParentId($(prev[0])) == $("table#tree").getParentId(currentHover)){

                                prev.toggleClass("dragafter", true);

                                currentHover.toggleClass("dragbefore", false);

                            }else {

                                currentHover.toggleClass("dragbefore", true);

                            }

                        }else if (sortState == sortStates.after){

                            currentHover.toggleClass("dragafter", true);

                        }

                    }

                }

                currentHover.toggleClass("hovered", state == dragStates.move);


            }).mouseup(function (e){
                var command = {};

                if (isDragging && isValidMove){

                    if (state == dragStates.move){
                      
                        $(currentSelected).appendBranchTo(currentHover[0]);
                        command = {'elm' : currentSelected, 'to' : currentHover[0], 'method' : 'move' };

                    }else {

                        var myparents = $('table#tree').getParents(currentHover);

                        var hisparents = $('table#tree').getParents(currentSelected);

                        var targetParent = hisparents[0][0];

                        var myParent = myparents[0][0];


                        if (myparents[0][0] != targetParent){
                            currentSelected.appendBranchTo(myParent);
                            command = {'elm' : currentSelected, 'to' : myParent, 'method' : 'move' };
                        }


                        var myChildren = $($('table#tree').allChildren($(currentSelected)));

						if (sortState == sortStates.before){

							$(currentSelected).insertBefore(currentHover);
								$.each(myChildren, function (){
								$(this).insertBefore(currentHover);
							});

                            command = {'elm' : currentSelected, 'to' : currentHover, 'method' : 'insertBefore' };

                        }else {

                            var children = $('table#tree').getChildren(currentHover);

                            var last = null;

                            while (children.length > 0){

                                last = children.last();

                                children = $('table#tree').getChildren(last);

                            }

                            if (last != null && last.length > 0 && last[0] != currentSelected[0]){

								$.each(myChildren, function (){
									$(this).insertAfter(last);
								});
                                $(currentSelected).insertAfter(last);

                                command = {'elm' : currentSelected, 'to' : currentHover, 'method' : 'insertAfter' };

                            }else {

                                $.each(myChildren, function (){
									$(this).insertAfter(currentHover);
								});
                                $(currentSelected).insertAfter(currentHover);

                                command = {'elm' : currentSelected, 'to' : currentHover, 'method' : 'insertAfter' };

                            }

                        }

                    }

                }

                if (command.hasOwnProperty("method")){

                    if (!(command.elm instanceof jQuery)){
                        command.elm = $(command.elm);
                    }
                    if (!(command.to instanceof jQuery)){
                        command.to = $(command.to);
                    }
                    var elm  = $(command.elm).attr("rel");
                    var to  = $(command.to).attr("rel");

                    $.ajax({
                      type: 'POST',
                      url: window[command.method+'Url'],
                      data: {
                        elm : elm,
                        to : to
                      },
                      success: function (){
                          
                      },
                      dataType: 'json'
                    });
                }

                e.preventDefault();

                $("table#tree tr:visible").toggleClass("dragbefore", false);

                $("table#tree tr:visible").toggleClass("dragafter", false);

                $(this).unbind("mousemove").unbind("mouseup");

                isDragging = false;

                if (this.dragMessage != null){

                    this.dragMessage.remove();

                    this.dragMessage = null;

                }

            });

        }
    }).dblclick(function (e){
    
        e.preventDefault();
        e.stopPropagation();
 	      if ($(this).attr("cmseditable") == "false")
        {
          	return;
        }
        location.href = $(this).find("a:first").attr("href");

    }).each(function (){
    	if ($(this).attr("cmseditable") == "false")
    	{
    		$(this).find("td.sf_admin_list_td_meta_navigation_title a").attr("href", "javascript:void(0)").css("cursor","default");
    	}
    });

    $("table#tree tr span.expander").mouseenter(function (){
        $(this).toggleClass("hovered", true);
    }).mouseleave(function (){
        $(this).toggleClass("hovered", false);
    }).dblclick(function (e){
        e.preventDefault();
        e.stopPropagation();
    });

});

function isAllowedAsChild(child, parent){
    var childType = pageTypes[$(child).attr("rel")];
    var parentsChildren = childPageTypes[$(parent).attr("rel")];
    
    return $.inArray(childType, parentsChildren) > -1;
}
function createDragMessage(){

    var msg = $('<div style="position:absolute;z-index:100000;"><div class="filedragger"></div><div class="dragger-error"></div><div class="dragger-moveto"><div>Move <span id="move-position">to</span> <span id="move-where">Test</span></div></div></div>');

    $(document.body).append(msg);

    return msg;

}
