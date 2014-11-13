<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(defined("PRINT")):?>
	<?$APPLICATION->IncludeComponent("areal:filialy.detail", "print", array("TYPE" => $arParams["TYPE"], "ID" => $arParams["ID"]));?>
<?else:?>
	<?$APPLICATION->IncludeComponent("areal:filialy.detail", ".default", array("TYPE" => $arParams["TYPE"], "ID" => $arParams["ID"]));?>
	<div class="filialy">
		<form name="filials" method="get" action="/filialy/">
			<div class="line autocomplete small">
				<input type="text" class="autocomplete town required" name="TOWN" value="<?=$arResult["TOWN_NAME"]?>" />
				<button type="submit" name="change_town">Выбрать</button>
				<div class="b-places"></div>
				<div class="clear"></div>
			</div>
		</form>
		<?$APPLICATION->IncludeComponent("areal:filialy.map", "list", array("TYPE" => $arParams["TYPE"], "VIEW" => "list", "ID" => $arParams["ID"]));?>
	</div>
<?endif;?>