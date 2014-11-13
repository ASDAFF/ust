function grainTableAddRow(field_name,bUserFields)
{
	var table=document.getElementById('grain_table_' + field_name);
	var template=document.getElementById('grain_table_row_template_' + field_name);
	
	var name_escaped = field_name.replace(/[-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
	
	if(bUserFields) {
		var index_regex = new RegExp ("^"+name_escaped+"\\\[(\\d+)\\\]","i");
		var name_template = field_name + "[--INDEX--]";
	} else {
		var index_regex = new RegExp ("^"+name_escaped+"\\\[n(\\d+)\\\]","i");
		var name_template = field_name + "[n--INDEX--]";
	}
	
	if (table && template)
	{
		var parentElement = template.parentNode; // parent
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
		
		for (i=0;i<new_row.cells.length;i++) 
			new_row.cells[i].innerHTML = new_row.cells[i].innerHTML.replace(/--NAME--/g,new_name);

		//for (var i=0;i<new_row.cells.length;i++) alert(new_row.cells[i].innerHTML);

	}
	return;
}

grain_table_tmp_input_id = "";

function grainTableMedialibReturn(return_path) {

	if(grain_table_tmp_input_id.length>0) {
		
		obField = document.getElementById(grain_table_tmp_input_id);
		
		if(obField) obField.value=return_path.src;
	
	}

}

function grainTableFilemanReturn(filename,foldername,site_id) {

	if(grain_table_tmp_input_id.length>0) {
		
		obField = document.getElementById(grain_table_tmp_input_id);
		
		if(obField) obField.value=foldername+"/"+filename;
	
	}

}
