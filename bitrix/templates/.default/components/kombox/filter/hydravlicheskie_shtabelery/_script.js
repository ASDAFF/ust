function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

$(document).ready(function(){	
	var komboxHint = null;
	$('.kombox-filter-property-hint').on('click', function(){
		var $this = $(this);
		var hint = $this.next();
		if(hint.length)
		{
			var hintHtml = hint.html();
			if(hintHtml.length)
			{
				if(komboxHint == null) {
					komboxHint = new BX.PopupWindow("kombox-hint", BX(this), {
						content: '',
						lightShadow : true,
						autoHide: true,
						closeByEsc: true,
						bindOptions: {position: "bottom"},
						closeIcon : { top : "5px", right : "10px"},
						offsetLeft : 0,
						offsetTop : 2,
						angle : { offset : 14 }
					});
				}
				komboxHint.setContent('<div class="kombox-filter-hint">' + hintHtml + '</div>');
				komboxHint.setBindElement(BX(this));
				komboxHint.show();
			}
		}
		return false;
	});
	
	var listAutoResizeWidth = function(list) {
		var change = true;
		while(change)
		{
			change = false;
			var i = 0,
				currentRow = -1,
				currentRowStart = 0,
				rowDivs = new Array();
				
			list.each(function() {
				var $el = $(this);
				var topPosition = $el.position().top;
		
				if (currentRowStart != topPosition) {
					currentRow++;
					i = 0;
					currentRowStart = topPosition;
				}
				
				if(typeof rowDivs[i] == 'undefined')
					rowDivs[i] = new Array();
				
				rowDivs[i][currentRow] = $el;
				
				i++;
			});
			
			$.each(rowDivs, function(key, value ){
				if(value.length > 1){
					var width = 0;
					$.each(value, function(key, el ){
						if(typeof el != 'undefined')
							if(el.width() > width)
								width = el.width();
					});
					
					$.each(value, function(key, el ){
						if(typeof el != 'undefined')
							if(el.width() != width)
							{
								change = true;
							}
							el.width(width);
					});
					
				}
			});
		}
		
		$.each(rowDivs, function(key, value ){
			if(value.length > 1){
				var width = 0;
				$.each(value, function(key, el ){
					if(typeof el != 'undefined'){
						el.width('auto');
						if(el.width() > width)
							width = el.width();
						}
				});
				
				$.each(value, function(key, el ){
					if(typeof el != 'undefined')
						el.width(width);
				});
				
			}
		});
	};
	
	$(window).resize(function() {
		$('.kombox-filter .kombox-combo').each(function(){
			listAutoResizeWidth($(this).find('li'));
		});
	});
	
	$('.kombox-filter .kombox-combo').each(function(){
		listAutoResizeWidth($(this).find('li'));
	});
	
	$('.kombox-filter-show-properties a').on('click', function(){
		var contaner = $('.kombox-filter-show-properties');
		if(contaner.hasClass('kombox-show')){
			$('.kombox-filter .closed').show();
			contaner.addClass('kombox-hide').removeClass('kombox-show');
			$.cookie('kombox-filter-closed', false, { path: '/' });
		}
		else
		{
			$('.kombox-filter .closed').hide();
			contaner.addClass('kombox-show').removeClass('kombox-hide');
			$.cookie('kombox-filter-closed', true, { path: '/' });
		}
		return false;
	});
	
	$('.kombox-filter .kombox-num table').each(function(){
		var $this = $(this);
		$this.width($this.width());
	});
	
	$(".kombox-range div").each(function(){
		var slider = $(this);
		var min = parseFloat(slider.data('min'));
		var max = parseFloat(slider.data('max'));
		var diaposonNumbers = max - min;
		var parent = slider.parents('.kombox-num');
		var step = 1;
		if(diaposonNumbers / 20 < 1)
			step = Math.round(diaposonNumbers / 20 * 10) / 10;
			
		var inputFrom = $('.kombox-num-from', parent);
		var inputTo = $('.kombox-num-to', parent);
		
		slider.ionRangeSlider({
			type: "double",
			hasGrid: true,
			step: step,
			hideMinMax: true,
			hideFromTo: true,
			prettify: false,
			onFinish: function(obj, slider){
				if(obj.fromNumber > min)
					inputFrom.val(obj.fromNumber);
				else
					inputFrom.val('');
					
				if(obj.toNumber < max)
					inputTo.val(obj.toNumber);
				else
					inputTo.val('');
					
				komboxSmartFilter.keyup(BX(inputFrom[0]));
			}
		});
		
		
		inputFrom.on('change', function(){
			var from = parseFloat(inputFrom.val()) || min;
			var to = parseFloat(inputTo.val()) || max;
			
			if(from > to){
				from = to;
				inputFrom.val(from);
			}
			else if(from<=min){
				from = min;
				inputFrom.val('');
			}
			
			slider.ionRangeSlider("update", {                       
				from: from,                      
				to: to                       
			});
			
			komboxSmartFilter.keyup(BX(inputFrom[0]));
		});
		
		inputTo.on('change', function(){
			var from = parseFloat(inputFrom.val()) || min;
			var to = parseFloat(inputTo.val()) || max;
			
			if(from > to){
				to = from;
				inputTo.val(to);
			}
			else if(to >= max){
				to = max;
				inputTo.val('');
			}
			
			slider.ionRangeSlider("update", {                       
				from: from,                      
				to: to                       
			});
			
			komboxSmartFilter.keyup(BX(inputTo[0]));
		});
	});

	if($.cookie('kombox-filter-closed') != 'false'){
		$('.kombox-filter .closed').hide();
		$('.kombox-filter-show-properties').addClass('kombox-show').removeClass('kombox-hide');
	}
	else
		$('.kombox-filter-show-properties').addClass('kombox-hide').removeClass('kombox-show');
	
	$('.kombox-filter form').on('submit', function() {
		$(':input', this).filter(
			function() {
				return $(this).val().length == 0;
			}
		).prop("disabled", true);
		
		if($(this).data('sef') == 'yes')
		{
			var url = $(this).attr('action');
			url += 'filter/';
			
			$('li.lvl1 div.kombox-num, li.lvl1 div.kombox-combo', this).each(function(){
				var $this = $(this);
				var name = $this.data('name');
			
				if(name.length)
				{
					var values = '';
					
					if($this.hasClass('kombox-num'))
					{
						var from = $('input.kombox-num-from', $this).val();
						if(parseFloat(from))
							values += '-from-'+from;
						
						var to = $('input.kombox-num-to', $this).val();
						if(parseFloat(to))
							values += '-to-'+to;
					}
					else if($this.hasClass('kombox-combo'))
					{
						var arValues = $('input:checked', $this).map(function() {
											return $(this).data('value');
										}).get();
						if(arValues.length)
							values = '-' + arValues.join('-or-');
					}
					
					if(values.length){
						url += name + values + '/';
					}
				}
			});
			window.location = url;
			return false;
		}
	});
});


function KomboxSmartFilter(ajaxURL, align, time)
{
	this.ajaxURL = ajaxURL;
	this.form = null;
	this.timer = null;
	this.reload_cnt = 0;
	this.align = align;
	this.modeftimeout = time;
}

KomboxSmartFilter.prototype.keyup = function(input)
{
	var _this = this;
	_this.reload(input);
}

KomboxSmartFilter.prototype.click = function(checkbox)
{
	$checkbox = $(checkbox);
	var parent = $checkbox.parent();
	
	if($checkbox.prop('checked'))
		parent.addClass('kombox-checked');
	else
		parent.removeClass('kombox-checked');
		
	var _this = this;
	_this.reload(checkbox);
}

KomboxSmartFilter.prototype.loading = function(set)
{
	var filter = this.form.parent();
	if(filter.length)
	{
		var loading = filter.find('.kombox-loading');
		
		if(loading.length)
		{
			loading.width(filter.outerWidth());
			loading.height(filter.outerHeight());
			
			if(set)
				loading.show();
			else
				loading.hide();
		}
	}
}

KomboxSmartFilter.prototype.reload = function(input)
{
	this.input = $(input);
	this.form = this.input.parents('div.kombox-filter').find('form');
	if(this.form.length)
	{
		//this.loading(true);
		var values = new Array;
		values[0] = {name: 'ajax', value: 'y'};
		
		this.gatherInputsValues(values, this.form.find('input'));
		
		BX.ajax.loadJSON(
			this.ajaxURL,
			this.values2post(values),
			BX.delegate(this.postHandler, this)
		);
	}
}

KomboxSmartFilter.prototype.postHandler = function (result)
{
	if(result.ITEMS)
	{
		for(var PID in result.ITEMS)
		{
			var arItem = result.ITEMS[PID];
			if(arItem.PROPERTY_TYPE == 'N' || arItem.PRICE)
			{
				var control = $('#' + arItem.VALUES.MAX.CONTROL_ID);
				var slider = control.parents('.lvl1').find('.kombox-range div');
				
				slider.data('range-from', parseFloat(arItem.VALUES.MIN.RANGE_VALUE));
				slider.data('range-to', parseFloat(arItem.VALUES.MAX.RANGE_VALUE));	

				slider.ionRangeSlider("updateRange");
			}
			else if(arItem.VALUES)
			{
				for(var i in arItem.VALUES)
				{
					var ar = arItem.VALUES[i];
					var control =$('#' + ar.CONTROL_ID);
					if(control.length)
					{
						var parent = control.parent();
						
						if(ar.DISABLED)
							parent.addClass('kombox-disabled');
						else
							parent.removeClass('kombox-disabled');
						
						if(ar.CHECKED)
							parent.addClass('kombox-checked');
						else
							parent.removeClass('kombox-checked');
						
						if(ar.CNT)
							parent.find('span').text('(' + ar.CNT + ')');
						else
							parent.find('span').text('');
					}
				}
			}
		}
		var modef = $('#modef');
		var modef_num = $('#modef_num');
		if(modef.length && modef_num.length)
		{
			modef_num.html(result.ELEMENT_COUNT);
			var href = modef.find('a');
			if(result.FILTER_URL && href.length)
				href.attr('href', BX.util.htmlspecialcharsback(result.FILTER_URL));

			var curProp = $(this.input).parents('.lvl1').find('.for_modef');
			if(curProp.length)
			{
				modef.show();
				
				if(this.align == 'LEFT')
				{
					modef.css({'left': '-' + modef.outerWidth() + 'px'});
				}
				else
				{
					modef.addClass('modef-right');
					modef.css({'right': '-' + modef.outerWidth() + 'px'});
				}
				
				curProp.append(modef);
				
				if(this.modeftimeout > 0)
				{
					if(this.modeftimer)
						clearTimeout(this.modeftimer);
						
					this.modeftimer = setTimeout(function(){
						modef.hide();
					}, this.modeftimeout*1000);
				}
			}
		}
	}
	
	//this.loading(false);
}

KomboxSmartFilter.prototype.gatherInputsValues = function (values, elements)
{
	if(elements)
	{
		for(var i = 0; i < elements.length; i++)
		{
			var el = elements[i];
			if (el.disabled || !el.type)
				continue;

			switch(el.type.toLowerCase())
			{
				case 'text':
				case 'textarea':
				case 'password':
				case 'hidden':
				case 'select-one':
					if(el.value.length)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'radio':
				case 'checkbox':
					if(el.checked)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'select-multiple':
					for (var j = 0; j < el.options.length; j++)
					{
						if (el.options[j].selected)
							values[values.length] = {name : el.name, value : el.options[j].value};
					}
					break;
				default:
					break;
			}
		}
	}
}

KomboxSmartFilter.prototype.values2post = function (values)
{
	var post = new Array;
	var current = post;
	var i = 0;
	while(i < values.length)
	{
		var p = values[i].name.indexOf('[');
		if(p == -1)
		{
			current[values[i].name] = values[i].value;
			current = post;
			i++;
		}
		else
		{
			var name = values[i].name.substring(0, p);
			var rest = values[i].name.substring(p+1);
			if(!current[name])
				current[name] = new Array;

			var pp = rest.indexOf(']');
			if(pp == -1)
			{
				//Error - not balanced brackets
				current = post;
				i++;
			}
			else if(pp == 0)
			{
				//No index specified - so take the next integer
				current = current[name];
				values[i].name = '' + current.length;
			}
			else
			{
				//Now index name becomes and name and we go deeper into the array
				current = current[name];
				values[i].name = rest.substring(0, pp) + rest.substring(pp+1);
			}
		}
	}
	return post;
}
