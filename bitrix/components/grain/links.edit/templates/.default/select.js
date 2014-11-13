
GRAIN_LINKS_EDIT_DEFAULT = {

	lists: [],

	instance_params: [],

	selected: [],

	field_params: [],

	opened_instance_id: false,

	opened_field_id: false,
	
	last_key_press: 0,

	current_value: false,

	params: {
		zindex: 5100,
		ajax_delay: 500,
		text_classname: 'extselect-text',
		text_placeholded_classname: 'extselect-text-placeholded',
		dropdown_classname: 'extselect-dropdown',
		dropdown_scroll_classname: 'extselect-dropdown-scroll',
		dropdown_container_classname: 'extselect-dropdown-container',
		item_classname: 'extselect-item',
		item_selected_classname: 'extselect-item-selected',
		item_current_classname: 'extselect-item-current',
		item_link_classname: 'extselect-item-link',
		highlight_classname: 'extselect-highlight',
		message_classname: 'extselect-message',
		item_multiple_value_classname: 'extselect-values-multiple-value',
		item_multiple_link_classname: 'extselect-values-multiple-value-link',
		item_multiple_delete_classname: 'extselect-values-multiple-value-delete',
		item_value_classname: 'extselect-values-value',
		item_link_classname: 'extselect-values-value-link',
		item_delete_classname: 'extselect-values-value-delete'
	},
    
	instance_default_params: {
		multiple: false,
		show_url: false,
		use_ajax: false,
		use_value_id: false,
		show_ajax_error: true,
		show_ajax_empty: true,
		use_search: true,
		empty_show_all: true,
		cur_page: "",
    	MESSAGE_LIST_EMPTY: "List is empty",
    	MESSAGE_NOT_FOUND: "Nothing was found",
    	MESSAGE_AJAX_ERROR: "Server communication error",
    	MESSAGE_PLACEHOLDER: "Select...",
    	MESSAGE_DELETE_MULTIPLE: "x",
    	MESSAGE_DELETE: "x"
	},

    show: function(instance_id,field_id) {
    
    	if(this.opened_instance_id) {
    		if(this.opened_instance_id==instance_id && this.opened_field_id==field_id) return;
    		else this.destroy(this.opened_instance_id);
    	} 
    
		if(!document.getElementById(instance_id+"_dropdown")) {
    
			var dropdown = document.createElement("div");
			dropdown.id = instance_id+"_dropdown";
			dropdown.className = this.params.dropdown_classname;
			dropdown.style.display = "none";
			dropdown.style.position = "absolute";
			dropdown.style.left = "-10000px";
			dropdown.style.top = "-1000px";
			
			dropdown.style.zIndex = this.params.zindex;
			
			var input = document.getElementById(field_id);
	    	input.value = "";
			input.className = this.params.text_classname;

			var input_pos = this.real_pos(input);
			
			dropdown.style.top = input_pos.bottom +  "px";
			dropdown.style.left = input_pos.left + "px";
			//dropdown.style.minWidth = input.offsetWidth + "px";
			
			dropdown.innerHTML = "";

			var dropdown_scroll = document.createElement("div");
			dropdown_scroll.id = instance_id+"_dropdown_scroll";
			dropdown_scroll.className = this.params.dropdown_scroll_classname;

			dropdown.appendChild(dropdown_scroll);

			var dropdown_container = document.createElement("div");
			dropdown_container.id = instance_id+"_dropdown_container";
			dropdown_container.className = this.params.dropdown_container_classname;
			
			dropdown_scroll.appendChild(dropdown_container);
			
			document.body.appendChild(dropdown);
			dropdown = null;
			
			this.opened_instance_id = instance_id;
			this.opened_field_id = field_id;

			//this.addEventHandler(document, "keydown", this.destroy);
			this.addEventHandler(document, "click", this.click_handler);

			if(!this.instance_params[instance_id].use_ajax) this.search(instance_id,input.value);

 		}
 	

    },


	ajax_return: function(instance_id,data,search_query) {

		//document.getElementById("test").innerHTML += data + "<br /><br />"+instance_id+"<br /><br />";

		thisobj = GRAIN_LINKS_EDIT_DEFAULT;

		thisobj.lists[instance_id] = [];

		var data_error = false;

		if (!data || data.length<(instance_id.length*2+10)) data_error = true;
		if (data.substr(0,instance_id.length+4)!="/*"+instance_id+"*/") data_error = true;
		if (data.substr(-(instance_id.length+4),instance_id.length+4)!="/*"+instance_id+"*/") data_error = true;
		
		if(data_error && thisobj.instance_params[instance_id].show_ajax_error) 
			thisobj.show_items(instance_id,search_query,thisobj.instance_params[instance_id].MESSAGE_AJAX_ERROR);		
		if(data_error) return;

		thisobj.lists[instance_id] = eval('(' + data + ')');
		if(thisobj.lists[instance_id].length>0 || thisobj.instance_params[instance_id].show_ajax_empty) 
			thisobj.show_items(instance_id,search_query);

	},


    search: function(instance_id,search_query,error_message) {

		if(this.instance_params[instance_id].use_ajax) {
	
			var thisobj = this;
			
			if(search_query.length>0) {
			
				var ajax_search_variable = "ajax_search_" + instance_id;
			
				var arData = {};
				arData[ajax_search_variable] = search_query;
			
				var TID = CPHttpRequest.InitThread();
				CPHttpRequest.SetAction(TID,function(data){ thisobj.ajax_return(instance_id,data,search_query); });
				CPHttpRequest.Send(TID, this.instance_params[instance_id].cur_page, arData);
	
			} else {
			
				var dropdown = document.getElementById(instance_id+"_dropdown");
				if(dropdown) dropdown.style.display = "none";
			
			}	
			
		} else {
		
			this.show_items(instance_id,search_query);
		
		}

    },


	show_items: function(instance_id,search_query,error_message) {

		var field_id = this.opened_field_id;

		var dropdown = document.getElementById(instance_id+"_dropdown");
   		var dropdown_container = document.getElementById(instance_id+"_dropdown_container");
   		
   		if(!dropdown || !dropdown_container) return;

		if(this.instance_params[instance_id].use_ajax && (search_query.length<=0 || !this.lists[instance_id])) {
		
			dropdown.style.display = "none";
			return;
		
		}

		dropdown.style.display = "";
   		
		dropdown_container.innerHTML="";


		var highlight_replace = '<span class="'+this.params.highlight_classname+'">$&</span>';
		
		var rg = new RegExp(search_query.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&"), "gi");
	
		var bShowAll = !this.instance_params[instance_id].use_search
			|| (this.instance_params[instance_id].use_search && this.instance_params[instance_id].empty_show_all && (search_query.length<=0));
		
		var thisobj = this;
		
		var items_count = 0;
		
		for(var vvalue in this.lists[instance_id]) {
			
			var value = vvalue.slice(1);
			
			var item_name = this.lists[instance_id][vvalue].NAME;
			
			if(bShowAll || this.instance_params[instance_id].use_ajax || item_name.search(rg) != -1) {
			
				var item = document.createElement("div");
				item.className = this.params.item_classname;
				if(this.selected[instance_id][field_id][value]) item.className += " " + this.params.item_selected_classname;

				var a = document.createElement("a");
				a.className = this.params.item_link_classname;
				a.href="#";
				a.rel=value;
				a.onclick = function() { thisobj.selectitem(instance_id,this); return false; };

				if(!bShowAll || this.instance_params[instance_id].use_ajax) item_name = item_name.replace(rg,highlight_replace);
				
				a.innerHTML=item_name;
				
				item.appendChild(a);
				
				dropdown_container.appendChild(item);
				
				items_count++;
			
			}
			
		
		}
		
		if(items_count<=0) {
			
			var item = document.createElement("div");
			item.className = this.params.message_classname;
			
			if(error_message) {
				
				item.innerHTML = error_message;
			
			} else {

				if(!this.is_empty_object(this.lists[instance_id]) && !this.instance_params[instance_id].use_ajax) {
					item.innerHTML = this.instance_params[instance_id].MESSAGE_NOT_FOUND;
				} else if(this.is_empty_object(this.lists[instance_id]) && !this.instance_params[instance_id].use_ajax) {
					item.innerHTML = this.instance_params[instance_id].MESSAGE_LIST_EMPTY;
				} else if(this.instance_params[instance_id].use_ajax && this.instance_params[instance_id].show_ajax_empty) {
					item.innerHTML = this.instance_params[instance_id].MESSAGE_NOT_FOUND;
				} 
	
			}
			
			if(item.innerHTML) dropdown_container.appendChild(item);
		
		}

	
	},


    selectitem: function(instance_id,a) {

		//alert(a.rel);
		
		var field_id = this.opened_field_id;
		
		if(this.instance_params[instance_id].multiple && this.selected[instance_id][field_id][a.rel]) {
			this.destroy(instance_id);
			return;
		}
		
		var container = document.getElementById(this.field_params[instance_id][field_id].values_id);
		if(container) {

		    var item = document.createElement("div");
		    item.className = this.instance_params[instance_id].multiple?this.params.item_multiple_value_classname:this.params.item_value_classname;
		    item.id = instance_id + "_" + field_id + "_value_" + a.rel;
		    
		    if(this.instance_params[instance_id].show_url && typeof(this.lists[instance_id]['v'+a.rel].URL)!=="undefined") {

		    	var a_item = document.createElement("a");
		    	a_item.className = this.instance_params[instance_id].multiple?this.params.item_multiple_link_classname:this.params.item_link_classname;
		    	a_item.href=this.lists[instance_id]['v'+a.rel].URL;
		    	a_item.target="_blank";
		    	a_item.innerHTML = this.lists[instance_id]['v'+a.rel].NAME;
		    	item.appendChild(a_item);
		    
		    } else {

		    	item.innerHTML = this.lists[instance_id]['v'+a.rel].NAME;
		    
		    }
		
		    item.innerHTML += " ";

		    var a_delete = document.createElement("a");
		    a_delete.className = this.instance_params[instance_id].multiple?this.params.item_multiple_delete_classname:this.params.item_delete_classname;
		    a_delete.href="";
		    a_delete.rel=a.rel;
		    a_delete.innerHTML = this.instance_params[instance_id].multiple?this.instance_params[instance_id].MESSAGE_DELETE_MULTIPLE:this.instance_params[instance_id].MESSAGE_DELETE;
		    var thisobj = this;
		    a_delete.onclick = function() { thisobj.deleteitem(instance_id,field_id,this); return false; };
		    item.appendChild(a_delete);

		    var input_h = document.createElement("input");
		    input_h.setAttribute("type","hidden");
		    input_h.setAttribute("id",instance_id+"_hidden_"+a.rel);

		    if(this.instance_params[instance_id].multiple) {
		    	if(this.instance_params[instance_id].use_value_id) {

					var name_escaped = this.field_params[instance_id][field_id].input_name.replace(/[-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
					var index_regex = new RegExp ("^"+name_escaped+"\\\[n(\\d+)\\\]","i");
					var name_template = this.field_params[instance_id][field_id].input_name + "[n--INDEX--]";
					var new_index = 0;
					var code = container.innerHTML.match(/name\=[\"\']?[^\"\' \>]+/ig);
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
					input_h.setAttribute("name",new_name);
				} else {
					input_h.setAttribute("name",this.field_params[instance_id][field_id].input_name+"[]");
				}
			} else {
				input_h.setAttribute("name",this.field_params[instance_id][field_id].input_name);
			}
				
			input_h.value = a.rel;
		
			item.appendChild(input_h);
		
			if(!this.instance_params[instance_id].multiple) container.innerHTML = "";
		
			container.appendChild(item);
		    
		    if(!this.instance_params[instance_id].multiple)	this.selected[instance_id][field_id] = [];
			this.selected[instance_id][field_id][a.rel] = true;
		
		}
		
		if(typeof(this.instance_params[instance_id].on_after_select)=="function") 
			this.instance_params[instance_id].on_after_select(a.rel,this.lists[instance_id]['v'+a.rel].NAME,this.lists[instance_id]['v'+a.rel].URL,this,instance_id);

		this.destroy(instance_id);
		
    },


    deleteitem: function(instance_id,field_id,a) {

		var container = document.getElementById(this.field_params[instance_id][field_id].values_id);

		var value = a.rel;

		delete this.selected[instance_id][field_id][value];

		if(container) {

			if(this.instance_params[instance_id].multiple) {

				var item = document.getElementById(instance_id+"_"+field_id+"_value_"+value);
				if(item) container.removeChild(item);
		
			} else {
		
				container.innerHTML = "";
		
			}
			
			if(typeof(this.instance_params[instance_id].on_after_remove)=="function") 
				this.instance_params[instance_id].on_after_remove(value,this.lists[instance_id]['v'+value].NAME,this.lists[instance_id]['v'+value].URL,this,instance_id);
			
		}

    },


	key_action: function(instance_id,key_code) {
	
		//alert(key_code);
	
		var bNext = false;
		var bPrevious = false;
		var bPgUp = false;
		var bPgDn = false;
		var bEnter = false;
		var obPrevious = false;
		var obPreviousParent = false;
		var obSelectedLink = false;
		var previous_value = false;
		var bDone = false;
	
		switch(key_code) {
		
			case 33: // PgUp

				bPgUp = true;
		
			break;

			case 34: // PgDn

				bPgDn = true;
		
			break;

			case 38: // Up

				bPrevious = true;
			
			break;

			case 40: // Down

				bNext = true;
			
			break;

			case 13: // Enter
			
				bEnter = true;
			
			break;
		
		}
		
   		var dropdown_scroll = document.getElementById(instance_id+"_dropdown_scroll");
   		var dropdown_container = document.getElementById(instance_id+"_dropdown_container");
   		
   		if(!dropdown_container || !dropdown_scroll) return;

		if(bPgUp) {
		
			var newScrollTop = dropdown_scroll.scrollTop;
			newScrollTop -= dropdown_scroll.clientHeight;
			if(newScrollTop<0) newScrollTop=0;
			dropdown_scroll.scrollTop = newScrollTop;
		
		}

		if(bPgDn) {

			var newScrollTop = dropdown_scroll.scrollTop;
			newScrollTop += dropdown_scroll.clientHeight;
			//if(newScrollTop>(dropdown_container.clientHeight)) newScrollTop=0;
			dropdown_scroll.scrollTop = newScrollTop;
		
		}


		var elements = dropdown_container.getElementsByTagName('a');
		for (var e = 0; e < elements.length; e++) {
		
			var value = elements[e].getAttribute("rel");
			var obParent = elements[e].parentNode;

			if(typeof(value)=="string" && obParent.tagName=="DIV") {

		
				if(obParent.className.indexOf(this.params.item_current_classname)>0) {

					obParent.className = obParent.className.replace(" "+this.params.item_current_classname,"");

				} 
				
				if(bNext && !bDone) {
    			
    				if(
    					(this.current_value!==false && previous_value===this.current_value)
    					|| (this.current_value===false && e==0)
    				) {
    					obParent.className += " "+this.params.item_current_classname;						
    					this.current_value = value;
    					bDone = true;
    					//dropdown_scroll.scrollTop += 5;
    					//elements[e].innerHTML += " "+obParent.offsetTop+" "+obParent.offsetHeight+" "+dropdown_scroll.clientHeight;
    					this.scroll_to_element(dropdown_scroll,obParent,false);
    				}

   					if(e==(elements.length-1) && !bDone) {
   						elements[0].parentNode.className += " "+this.params.item_current_classname;
    					this.current_value = elements[0].getAttribute("rel");
    					bDone = true;
    					this.scroll_to_element(dropdown_scroll,elements[0].parentNode,false);
   					}

    				
    			} else if(bPrevious && !bDone) {
    			
    				if(this.current_value!==false && value===this.current_value) {
    					obPreviousParent.className += " "+this.params.item_current_classname;						
    					if(e==0) {
	    					this.current_value = false;
    					} else {
	    					this.current_value = previous_value;
	    					bDone = true;
    					}
    					this.scroll_to_element(dropdown_scroll,obPreviousParent,false);
    				}

    				if(this.current_value===false && e==(elements.length-1)) {
    					obParent.className += " "+this.params.item_current_classname;						
    					this.current_value = value;
    					bDone = true;
    					this.scroll_to_element(dropdown_scroll,obParent,false);
    				}

    			
				} else if(bPgUp && !bDone) {
    			

    				if(obPreviousParent) if(
    					obParent.offsetTop>=dropdown_scroll.scrollTop
    					&& obPreviousParent.offsetTop<dropdown_scroll.scrollTop
    				) {
    					obParent.className += " "+this.params.item_current_classname;						
    					this.current_value = value;
    					bDone = true;
    					this.scroll_to_element(dropdown_scroll,obParent,true);
    				}

   					if(e==(elements.length-1) && !bDone) {
   						elements[0].parentNode.className += " "+this.params.item_current_classname;
    					this.current_value = elements[0].getAttribute("rel");
    					bDone = true;
    					this.scroll_to_element(dropdown_scroll,elements[0].parentNode,true);
   					}

    				
    			} else if(bPgDn && !bDone) {
    			
    			
    				if(obPreviousParent) if(
    					(obParent.offsetTop+obParent.offsetHeight)>(dropdown_scroll.scrollTop+dropdown_scroll.clientHeight)
    					&& (obPreviousParent.offsetTop+obPreviousParent.offsetHeight)<=(dropdown_scroll.scrollTop+dropdown_scroll.clientHeight)    					
    				) {
    					obPreviousParent.className += " "+this.params.item_current_classname;						
    					this.current_value = previous_value;
    					bDone = true;
    					this.scroll_to_element(dropdown_scroll,obPreviousParent,true);
    				}

    				if(e==(elements.length-1) && !bDone) {
    					obParent.className += " "+this.params.item_current_classname;						
    					this.current_value = value;
    					bDone = true;
    					this.scroll_to_element(dropdown_scroll,obParent,true);
    				}

    			
    			} else if (bEnter && !bDone) {
    			
    				if(this.current_value!==false && value===this.current_value) {
						obSelectedLink = elements[e];
						bDone = true;    			
					}    			
    			
    			}
				
				obPrevious = elements[e];
				obPreviousParent = obParent;
				previous_value = value;
			
			}
			
		
		}

		if(!bDone) this.current_value = false;

		if(bEnter) {
		
			if(this.current_value) {
			
				this.selectitem(instance_id,obSelectedLink);
			
			}
	
		}

		
		 //alert(this.current_value);
	
	},
	
	
	scroll_to_element: function(obParent,obElement,bScrollWhenVisible) {
	
		//document.getElementById("test").innerHTML = "obElement.offsetTop: " + obElement.offsetTop + ", obParent.scrollTop: " +obParent.scrollTop;
	
		if((obElement.offsetTop+obElement.offsetHeight)>(obParent.scrollTop+obParent.clientHeight)) {
		
			obParent.scrollTop = obElement.offsetTop+obElement.offsetHeight-obParent.clientHeight;
		
		} else if(obElement.offsetTop<obParent.scrollTop) {
		
			obParent.scrollTop = obElement.offsetTop;
		
		} else if(bScrollWhenVisible) {
		
			if((obElement.offsetTop+obElement.offsetHeight/2)>(obParent.scrollTop+obParent.clientHeight/2)) {
			
				obParent.scrollTop = obElement.offsetTop+obElement.offsetHeight-obParent.clientHeight;
			
			} else if((obElement.offsetTop+obElement.offsetHeight/2)<=(obParent.scrollTop+obParent.clientHeight/2)) {
			
				obParent.scrollTop = obElement.offsetTop;			
			
			}
		
		}
	
	
	},


	click_handler: function(e) {
	
		e = e ? e : window.event;
		var target = e.target ? e.target : e.srcElement;
	
		//document.getElementById("test").innerHTML = (showObject(target));
	
		var instance_id = GRAIN_LINKS_EDIT_DEFAULT.opened_instance_id;
		var field_id = GRAIN_LINKS_EDIT_DEFAULT.opened_field_id;		

		if(!instance_id || instance_id=="undefined" || target.id==field_id) return;

		var bDestroy = true;
		var curElement = target;
		var count=0;
		
		do {
			if(curElement.id==(instance_id+"_dropdown")) bDestroy=false;
			curElement = curElement.parentNode;
			count++;
		} while(curElement && bDestroy && count<=10)

		if(bDestroy) {

			GRAIN_LINKS_EDIT_DEFAULT.removeEventHandler(document, "click", GRAIN_LINKS_EDIT_DEFAULT.click_handler);
			//GRAIN_LINKS_EDIT_DEFAULT.removeEventHandler(document, "keydown", this.destroy);

			GRAIN_LINKS_EDIT_DEFAULT.destroy(instance_id);

		}

		return;
		
	},


    key_handler: function(instance_id,obInput) {
    
   		var cur_time = new Date().getTime();
    		
   		if(cur_time>(this.last_key_press+this.params.ajax_delay)) {
    		
			this.last_key_press = 0;
    		
			this.search(instance_id,obInput.value);
    		
   		} else {
    		
    		var thisobj=this;
    		setTimeout(function() { thisobj.key_handler(instance_id,obInput); }, 100);
    		
		}
    	    
    },


    destroy: function(instance_id) {

		field_id = this.opened_field_id;

		this.opened_instance_id = false;
		this.opened_field_id = false;
		this.last_key_press = 0;
		this.current_value = false;
		
    	var dropdown = document.getElementById(instance_id+'_dropdown');
    	if(dropdown) document.body.removeChild(dropdown);

		if(this.instance_params[instance_id].use_ajax) this.lists[instance_id] = [];
	    	
    	var obInput = document.getElementById(field_id);
    	if(obInput) {
	    	obInput.value = this.instance_params[instance_id].MESSAGE_PLACEHOLDER;
			obInput.className = this.params.text_classname+" "+this.params.text_placeholded_classname;
			obInput.blur();
		}

    },
 
    
    setparams: function(new_params) {
    
    	for(var prop_name in new_params) this.params[prop_name] = new_params[prop_name];

    },


    setinstanceparams: function(instance_id,instance_params) {
    
    	if(typeof(this.instance_params[instance_id]) == 'undefined') {
	    	this.instance_params[instance_id] = {};
			for(var prop_name in this.instance_default_params) 
				this.instance_params[instance_id][prop_name] = this.instance_default_params[prop_name];
		}
    
    	for(var prop_name in instance_params) 
    		this.instance_params[instance_id][prop_name] = instance_params[prop_name];

    },

    setfieldparams: function(instance_id,field_id,field_params) {
    
    	if(typeof(this.field_params[instance_id]) == 'undefined')
	    	this.field_params[instance_id] = [];

   		if(field_id) 
   			this.field_params[instance_id][field_id] = field_params;  		

    },
    

	is_empty_object: function(obj) {
	
		for(var index in obj) {
			return false;
			break;
		}
		
		return true;
	},
	

	real_pos: function(obj) {
		var bodyRect = document.body.getBoundingClientRect();
		var elemRect = obj.getBoundingClientRect();
   		return {
			"left":elemRect.left-bodyRect.left,
			"top":elemRect.top-bodyRect.top,
			"right":elemRect.left-bodyRect.left+obj.offsetWidth,
			"bottom":elemRect.top-bodyRect.top+obj.offsetHeight,
			"width":obj.offsetWidth,
			"height":obj.offsetHeight			
		};
	},
    
    
	addEventHandler: function(element, type, handler) {
		if(element.attachEvent)
			element.attachEvent("on" + type, handler);
		else if(element.addEventListener)
			element.addEventListener(type, handler, false);
		else 
			element['on' + type] = handler;
	},

	removeEventHandler: function(element, type, handler) {
		if(element.detachEvent)
			element.detachEvent("on" + type, handler);
		else if(element.removeEventListener)
			element.removeEventListener(type, handler, false);
		else
			element['on' + type] = null;
			
	},

	ibind: function(instance_id,field_id,field_params,arSelected) {

		this.setfieldparams(instance_id,field_id,field_params);
	
		if(typeof(this.lists[instance_id])=='undefined') this.lists[instance_id] = [];

    	if(typeof(this.selected[instance_id]) == 'undefined')
	    	this.selected[instance_id] = [];
	    
	    this.selected[instance_id][field_id] = arSelected;

		var thisobj = this;

		var input=document.getElementById(field_id);
		input.value = this.instance_params[instance_id].MESSAGE_PLACEHOLDER;
		input.onfocus = function() { thisobj.show(instance_id,this.id); };
		input.autocomplete = 'off';
		input.onkeydown = function(e) {
		
			e = e ? e : window.event; 

			if((e.keyCode<=46 || e.keyCode==91) && e.keyCode!=8) { // control keys

				if(e.keyCode==33 || e.keyCode==34 || e.keyCode==38 || e.keyCode==40 || e.keyCode==13) {
	
					if(e.preventDefault) e.preventDefault();
					e.returnValue = false;
	
					thisobj.key_action(instance_id,e.keyCode);
					
				}
				
			}

		};
		input.onkeyup = function(e) {
			
			e = e ? e : window.event;

			if((e.keyCode<=46 || e.keyCode==91) && e.keyCode!=8) { // control keys

				if(e.keyCode==33 || e.keyCode==34 || e.keyCode==38 || e.keyCode==40 || e.keyCode==13) {
			
					if(e.preventDefault) e.preventDefault();
					e.returnValue = false;
				
				}

			} else if(thisobj.instance_params[instance_id].use_ajax) {

				if(thisobj.last_key_press>0) {
					thisobj.last_key_press = new Date().getTime();
				} else {
					thisobj.last_key_press = new Date().getTime();
					thisobj.key_handler(instance_id,this); 
				}
			
			} else {
			
				thisobj.search(instance_id,this.value);
			
			}

		};
		
	
	}

};
