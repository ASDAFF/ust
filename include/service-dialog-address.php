<div class="dialog service" id="service_centers">
	<div class="title">Адреса сервисных центров</div>
	<p>Укажите город</p>
	<div class="input_autocomplete autocomplete">
		<form name="town" method="post" class="ajax" action="<?=$APPLICATION->GetCurPage()?>">
			<input type="text" class="autocomplete town" name="select_town" value="" />
			<button type="submit" name="send">Выбрать</button>
			<div class="b-places"></div>
		</form>
	</div>
	<div class="scrollBox">
		<div id="pane" class="scroll-pane">	
			<?$APPLICATION->IncludeComponent("areal:service.address", "template1", Array(
	
	),
	false
);?>
		</div>
	</div>
</div>