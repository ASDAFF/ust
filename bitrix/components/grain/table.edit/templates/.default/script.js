function grain_TableAddRow(field_name,user_fields)
{
	var table=document.getElementById('grain_table_' + field_name);
	var template=document.getElementById('grain_table_row_template_' + field_name);
	
	var name_escaped = field_name.replace(/[-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
	
	if(user_fields=="Y") {
		var index_regex = new RegExp ("^"+name_escaped+"\\\[(\\d+)\\\]","i");
		var name_template = field_name + "[--INDEX--]";
	} else {
		var index_regex = new RegExp ("^"+name_escaped+"\\\[n(\\d+)\\\]","i");
		var name_template = field_name + "[n--INDEX--]";
	}
	
	if (table && template)
	{
		var parentElement = table.tBodies[0]; // parent
		var new_row = template.cloneNode(true);
		new_row.style.display = "";
		new_row.removeAttribute("id");

		var new_index = 0;

		var code = table.innerHTML.match(/name\=[\"\']?[^\"\' \>]+/ig);
		
		if(code)
		{
			for(var i in code) if(!isNaN(i)) // leave IE 'input' element
			{
				var param = code[i].match(/name\=[\"\']?([^\"\' \>]+)/i);
				param = param[1];

				var cur_index = param.match(index_regex);
				if(cur_index) {
					cur_index = parseInt(cur_index[1]);
					if(cur_index>=new_index) new_index=cur_index+1;
				}
			}
		}
		
		var new_name = name_template.replace(/--INDEX--/g,new_index);
		
		parentElement.appendChild(new_row); // append before because of IE 'cells' not work otherwise
		
		for (i=0;i<new_row.cells.length;i++) {

			var rand_min = 1000;
			var rand_max = 1000000000;
			var rand_num = rand_min + Math.floor(Math.random()*(rand_max-rand_min+1));

			new_row.cells[i].innerHTML = new_row.cells[i].innerHTML.replace(/--NAME--/g,new_name);
			new_row.cells[i].innerHTML = new_row.cells[i].innerHTML.replace(/--ID--/g,rand_num);

			if(
				new_row.cells[i].hasAttribute("data-grain-links-instance-id") 
				&& new_row.cells[i].hasAttribute("data-grain-links-field-id") 
				&& new_row.cells[i].hasAttribute("data-grain-links-name")
			) {

				new_row.cells[i].setAttribute("data-grain-links-name",new_row.cells[i].getAttribute("data-grain-links-name").replace(/--NAME--/g,new_name));
				new_row.cells[i].setAttribute("data-grain-links-field-id",new_row.cells[i].getAttribute("data-grain-links-field-id").replace(/--ID--/g,rand_num));

				GRAIN_LINKS_EDIT_DEFAULT.ibind(
					new_row.cells[i].getAttribute("data-grain-links-instance-id"),
					'grain_table_link_field_'+rand_num,
					{
						values_id: 'grain_table_link_field_'+rand_num+'_values',
						input_name: new_row.cells[i].getAttribute("data-grain-links-name")
					},
					{}
				);

			}

		}

		//for (var i=0;i<new_row.cells.length;i++) alert(new_row.cells[i].innerHTML);

		parentElement.style.display = "";

	}
	
	grain_TableInitSort(field_name,user_fields);
	
	return;
}


function grain_TableRemoveRow(obLink)
{
	var obTbody = obLink.parentNode.parentNode.parentNode;
	if(obTbody) {
		obTbody.removeChild(obLink.parentNode.parentNode);
		if(obTbody.getElementsByTagName("tr").length<=0) obTbody.style.display = "none";
	}
}


function grain_TableInitSort(field_name,user_fields) {

	grain_TableBindMouseEvents();

	var obTable = document.getElementById('grain_table_' + field_name);
	if(obTable) {
		obTable.setAttribute("grain_table_input_name",field_name);
		obTable.setAttribute("grain_table_user_fields",user_fields);
		var tableDnD = new grain_tableDnD();
		tableDnD.init(obTable);
	}
	
}


function grain_TableReSort(table) {

	var grIE = /*@cc_on!@*/false;

	var input_name = table.getAttribute("grain_table_input_name");
	if (input_name == null || input_name == "undefined") return; 
	var user_fields = table.getAttribute("grain_table_user_fields");
	if (user_fields == null || user_fields == "undefined") return;

	var input_name_escaped = input_name.replace(/[-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");

	if(grIE) {
		// original regex: /name\=([\"\']?)(PROP\[20\])(\[[^\]]*\])([^\"\' \>]+)([\"\']?)/g
		var input_name_regex = new RegExp ("name\\=([\\\"\\']?)(" + input_name_escaped + ")(\\[[^\\]]*\\])([^\\\"\\' \\>]+)([\\\"\\']?)","g");
	} else {
		var input_name_regex = new RegExp ("(" + input_name_escaped + ")\\[[^\\]]*\\]([^\\\"\\' \\>]+)","g");
	}

	for (var i=0;i<table.tBodies[0].rows.length;i++) {

		if(user_fields=="Y")
			var new_count = i;
		else
			var new_count = 'n' + i;

		var elements = table.tBodies[0].rows[i].getElementsByTagName('input');

		for (var a = 0; a < elements.length; a++) {
			if(grIE) 
				elements[a].outerHTML = elements[a].outerHTML.replace(input_name_regex,'name=$1$2['+new_count+']$4$5');
			else
				elements[a].setAttribute("name",elements[a].name.replace(input_name_regex,'$1['+new_count+']$2'));
		}

		var elements = table.tBodies[0].rows[i].getElementsByTagName('textarea');

		for (var a = 0; a < elements.length; a++) {
			if(grIE) 
				elements[a].outerHTML = elements[a].outerHTML.replace(input_name_regex,'name=$1$2['+new_count+']$4$5');
			else
				elements[a].setAttribute("name",elements[a].name.replace(input_name_regex,'$1['+new_count+']$2'));
		}

		var elements = table.tBodies[0].rows[i].getElementsByTagName('select');

		for (var a = 0; a < elements.length; a++) {
			if(grIE) 
				elements[a].outerHTML = elements[a].outerHTML.replace(input_name_regex,'name=$1$2['+new_count+']$4$5');
			else
				elements[a].setAttribute("name",elements[a].name.replace(input_name_regex,'$1['+new_count+']$2'));
		}

		var cells = table.tBodies[0].rows[i].getElementsByTagName('td');

		for (a=0;a<cells.length;a++) {

			if(
				cells[a].hasAttribute("data-grain-links-instance-id") 
				&& cells[a].hasAttribute("data-grain-links-field-id") 
				&& cells[a].hasAttribute("data-grain-links-name")
			) {

				cells[a].setAttribute("data-grain-links-name",cells[a].getAttribute("data-grain-links-name").replace(input_name_regex,'$1['+new_count+']$2'));

				GRAIN_LINKS_EDIT_DEFAULT.field_params[cells[a].getAttribute("data-grain-links-instance-id")][cells[a].getAttribute("data-grain-links-field-id")]["input_name"] = cells[a].getAttribute("data-grain-links-name");

			}

		}

		//alert(table.tBodies[0].rows[i].innerHTML);

	}

}


// ===================================================================
// Author: Denis Howlett <feedback@isocra.com>
// WWW: http://www.isocra.com/
//
// NOTICE: You may use this code for any purpose, commercial or
// private, without any further permission from the author. You may
// remove this notice from your final code if you wish, however we
// would appreciate it if at least the web site address is kept.
//
// You may *NOT* re-distribute this code in any way except through its
// use. That means, you can include it in your product, or your web
// site, or any other form where the code is actually being used. You
// may not put the plain javascript up on your site for download or
// include it in your javascript libraries for download.
// If you wish to share this code with others, please just point them
// to the URL instead.
//
// Please DO NOT link directly to this .js files from your site. Copy
// the files to your server and use them there. Thank you.
// ===================================================================

/** Keep hold of the current table being dragged */
var grain_table_sort_curtable = null;

/** Capture the onmousemove so that we can see if a row from the current
 *  table if any is being dragged.
 * @param ev the event (for Firefox and Safari, otherwise we use window.event for IE)
 */

function grain_TableBindMouseEvents() {

	if (typeof grain_table_sort_mouse_events_binded === 'undefined') {

		document.onmousemove = function(ev){
		    if (grain_table_sort_curtable && grain_table_sort_curtable.dragObject) {
		        ev   = ev || window.event;
		        var mousePos = grain_table_sort_curtable.mouseCoords(ev);
		        var y = mousePos.y - grain_table_sort_curtable.mouseOffset.y;
		        if (y != grain_table_sort_curtable.oldY) {
		            // work out if we're going up or down...
		            var movingDown = y > grain_table_sort_curtable.oldY;
		            // update the old value
		            grain_table_sort_curtable.oldY = y;
		            // update the style to show we're dragging
		            grain_table_sort_curtable.dragObject.className = "grain-tables-table-edit-row-drag";
		            // If we're over a row then move the dragged row to there so that the user sees the
		            // effect dynamically
		            var currentRow = grain_table_sort_curtable.findDropTargetRow(y);
		            if (currentRow) {
		                if (movingDown && grain_table_sort_curtable.dragObject != currentRow) {
		                    grain_table_sort_curtable.dragObject.parentNode.insertBefore(grain_table_sort_curtable.dragObject, currentRow.nextSibling);
		                } else if (! movingDown && grain_table_sort_curtable.dragObject != currentRow) {
		                    grain_table_sort_curtable.dragObject.parentNode.insertBefore(grain_table_sort_curtable.dragObject, currentRow);
		                }
		            }
		        }
		
		        return false;
		    }
		}
		
		// Similarly for the mouseup
		document.onmouseup   = function(ev){
		    if (grain_table_sort_curtable && grain_table_sort_curtable.dragObject) {
		        var droppedRow = grain_table_sort_curtable.dragObject;
		        // If we have a dragObject, then we need to release it,
		        // The row will already have been moved to the right place so we just reset stuff
		        droppedRow.className = "";
		        grain_table_sort_curtable.dragObject   = null;
		        // And then call the onDrop method in case anyone wants to do any post processing
		        grain_table_sort_curtable.onDrop(grain_table_sort_curtable.table, droppedRow);
		        grain_table_sort_curtable = null; // let go of the table too
		    }
		}

		grain_table_sort_mouse_events_binded = true;

	}

}

/** get the source element from an event in a way that works for IE and Firefox and Safari
 * @param evt the source event for Firefox (but not IE--IE uses window.event) */
function grain_getEventSource(evt) {
    if (window.event) {
        evt = window.event; // For IE
        return evt.srcElement;
    } else {
        return evt.target; // For Firefox
    }
}

/**
 * Encapsulate table Drag and Drop in a class. We'll have this as a Singleton
 * so we don't get scoping problems.
 */
function grain_tableDnD() {
    /** Keep hold of the current drag object if any */
    this.dragObject = null;
    /** The current mouse offset */
    this.mouseOffset = null;
    /** The current table */
    this.table = null;
    /** Remember the old value of Y so that we don't do too much processing */
    this.oldY = 0;

    /** Initialise the drag and drop by capturing mouse move events */
    this.init = function(table) {
        this.table = table;
        var rows = table.tBodies[0].rows; //getElementsByTagName("tr")
        for (var i=0; i<rows.length; i++) {
			// John Tarr: added to ignore rows that I've added the NoDnD attribute to (Category and Header rows)
			var nodrag = rows[i].getAttribute("NoDrag")
			if (nodrag == null || nodrag == "undefined") { //There is no NoDnD attribute on rows I want to drag
				this.makeDraggable(rows[i]);
			}
        }
    }

    /** This function is called when you drop a row, so redefine it in your code
        to do whatever you want, for example use Ajax to update the server */
    this.onDrop = function(table, droppedRow) {
		grain_TableReSort(table);
    }

	/** Get the position of an element by going up the DOM tree and adding up all the offsets */
    this.getPosition = function(e){
        var left = 0;
        var top  = 0;
		/** Safari fix -- thanks to Luis Chato for this! */
		if (e.offsetHeight == 0) {
			/** Safari 2 doesn't correctly grab the offsetTop of a table row
			    this is detailed here:
			    http://jacob.peargrove.com/blog/2006/technical/table-row-offsettop-bug-in-safari/
			    the solution is likewise noted there, grab the offset of a table cell in the row - the firstChild.
			    note that firefox will return a text node as a first child, so designing a more thorough
			    solution may need to take that into account, for now this seems to work in firefox, safari, ie */
			e = e.firstChild; // a table cell
		}

        while (e.offsetParent){
            left += e.offsetLeft;
            top  += e.offsetTop;
            e     = e.offsetParent;
        }

        left += e.offsetLeft;
        top  += e.offsetTop;

        return {x:left, y:top};
    }

	/** Get the mouse coordinates from the event (allowing for browser differences) */
    this.mouseCoords = function(ev){
        if(ev.pageX || ev.pageY){
            return {x:ev.pageX, y:ev.pageY};
        }
        return {
            x:ev.clientX + document.body.scrollLeft - document.body.clientLeft,
            y:ev.clientY + document.body.scrollTop  - document.body.clientTop
        };
    }

	/** Given a target element and a mouse event, get the mouse offset from that element.
		To do this we need the element's position and the mouse position */
    this.getMouseOffset = function(target, ev){
        ev = ev || window.event;

        var docPos    = this.getPosition(target);
        var mousePos  = this.mouseCoords(ev);
        return {x:mousePos.x - docPos.x, y:mousePos.y - docPos.y};
    }

	/** Take an item and add an onmousedown method so that we can make it draggable */
    this.makeDraggable = function(item) {
        if(!item || !item.cells || item.cells.length<2) return;
        var self = this; // Keep the context of the grain_tableDnD inside the function
        var target_cell = item.cells[item.cells.length-2];
        if(!target_cell) return;
        target_cell.onmousedown = function(ev) {
            // Need to check to see if we are an input or not, if we are an input, then
            // return true to allow normal processing
            var target = grain_getEventSource(ev);
            if (target.tagName == 'INPUT' || target.tagName == 'SELECT') return true;
            grain_table_sort_curtable = self;
            self.dragObject  = this.parentNode;
            self.mouseOffset = self.getMouseOffset(this.parentNode, ev);
            return false;
        }
        target_cell.style.cursor = "move";
    }

    /** We're only worried about the y position really, because we can only move rows up and down */
    this.findDropTargetRow = function(y) {
        var rows = this.table.tBodies[0].rows;
		for (var i=0; i<rows.length; i++) {
			var row = rows[i];
			// John Tarr added to ignore rows that I've added the NoDnD attribute to (Header rows)
			var nodrop = row.getAttribute("NoDrop");
			if (nodrop == null || nodrop == "undefined") {  //There is no NoDnD attribute on rows I want to drag
				var rowY    = this.getPosition(row).y;
				var rowHeight = parseInt(row.offsetHeight)/2;
				if (row.offsetHeight == 0) {
					rowY = this.getPosition(row.firstChild).y;
					rowHeight = parseInt(row.firstChild.offsetHeight)/2;
				}
				// Because we always have to insert before, we need to offset the height a bit
				if ((y > rowY - rowHeight) && (y < (rowY + rowHeight))) {
					// that's the row we're over
					return row;
				}
			}
		}
		return null;
	}
}