$ready = function(){
	var disableSetSelect = false;
	showPreview = function(coords) {
		if (!disableSetSelect) {
			$('#crop_select_width').val(parseInt(coords.w*ratio));
			$('#crop_select_height').val(parseInt(coords.h*ratio));
		} else {
			disableSetSelect = false;
		}
		$('#crop_set_coords').val(
			coords.x*ratio + ',' +
			coords.y*ratio + ',' +
			coords.w*ratio + ',' +
			coords.h*ratio
		);
		$('#crop_preview').css({
			width: coords.w,
			height: coords.h
		});
		$('#crop_preview img').css({
			marginLeft: '-' + coords.x + 'px',
			marginTop: '-' + coords.y + 'px'
		});
	}
	getRatio = function() {
		return $('#crop_ratio_x').val() / $('#crop_ratio_y').val();
	}
	$('#jcrop_target').Jcrop({
		allowMove: true,
		onChange:   showPreview,
		onSelect:   showPreview,
		aspectRatio: aspectRatio ? getRatio() : 0
	}, function(){
		jcrop_api = this;
	});
	$('#crop_select_width,#crop_select_height').keyup(function(){
		disableSetSelect = true;
		jcrop_api.setSelect([0,0,$('#crop_select_width').val()/ratio,$('#crop_select_height').val()/ratio]);
	});
	$('#aspectRatio').change(function(){
		jcrop_api.setOptions(this.checked? { aspectRatio: getRatio() }: { aspectRatio: 0 });
		if (this.checked) {
			$('#aspectRatio_set').show();
		} else {
			$('#aspectRatio_set').hide();
		}
	});
	$('#crop_ratio_x,#crop_ratio_y').keyup(function(){
		jcrop_api.setOptions({ aspectRatio: getRatio() });
	});
	$('#ratioResize').change(function(){
		if (this.checked) {
			$('#ratioResize_set').show();
		} else {
			$('#ratioResize_set').hide();
		}
	});
}