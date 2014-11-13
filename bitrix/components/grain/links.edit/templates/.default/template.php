<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$APPLICATION->AddHeadScript($templateFolder."/select.js");

$arResult["TEMPLATE_IDENTIFIER"] = "GRAIN_LINKS_EDIT_DEFAULT";

if($arResult["AJAX_RETURN"]) {

	$APPLICATION->RestartBuffer();
	while(ob_end_clean()) {}
	
	echo "/*".$arResult["INSTANCE_IDENTIFIER"]."*/".$arResult["JS_DATA"]."/*".$arResult["INSTANCE_IDENTIFIER"]."*/";

	die();
	
}

$arResult["FIELD_IDENTIFIER"] = $arResult["INSTANCE_IDENTIFIER"]."_text";

?>

<?if(!$arParams["SCRIPTS_ONLY"]):?>

<input type="text" class="<?if($arParams["ADMIN_SECTION"]):?>adm-input<?else:?><?=$arResult["TEMPLATE_IDENTIFIER"]?>-text <?=$arResult["TEMPLATE_IDENTIFIER"]?>-text-placeholded<?endif?>" id="<?=$arResult["FIELD_IDENTIFIER"]?>" value="<?=$arParams["MESSAGE_PLACEHOLDER"]?$arParams["MESSAGE_PLACEHOLDER"]:GetMessage($arParams["USE_SEARCH"] || $arParams["USE_AJAX"]?"GRAIN_LINKS_EDIT_T_DEFAULT_PLACEHOLDER_SEARCH":"GRAIN_LINKS_EDIT_T_DEFAULT_PLACEHOLDER_SELECT")?>"<?if(!($arParams["USE_SEARCH"] || $arParams["USE_AJAX"])):?> readonly="readonly"<?endif?> />

<?if($arParams["LEAVE_EMPTY_INPUTS"]):?>
	<?if($arParams["MULTIPLE"]):?>
		<?if($arParams["USE_VALUE_ID"]):?>
			<?foreach($arParams["VALUE"] as $value_id=>$value):?>
				<input type="hidden" name="<?=$arParams["INPUT_NAME"]?>[<?=$value_id?>]" value="" />
			<?endforeach?>
		<?else:?>
			<input type="hidden" name="<?=$arParams["INPUT_NAME"]?>[]" value="" />
		<?endif?>
	<?else:?>
		<input type="hidden" name="<?=$arParams["INPUT_NAME"]?>" value="" />
	<?endif?>
<?endif?>

<span class="<?=$arResult["TEMPLATE_IDENTIFIER"]?>-values<?if($arParams["MULTIPLE"]):?>-multiple<?endif;if($arParams["ADMIN_SECTION"]):?> <?=$arResult["TEMPLATE_IDENTIFIER"]?>-values<?if($arParams["MULTIPLE"]):?>-multiple<?endif?>-admin<?endif?>" id="<?=$arResult["INSTANCE_IDENTIFIER"]?>_values">
    <?foreach($arResult["SELECTED"] as $value=>$arItem):?>
    	<div class="<?=$arResult["TEMPLATE_IDENTIFIER"]?>-values<?if($arParams["MULTIPLE"]):?>-multiple<?endif?>-value" id="<?=$arResult["INSTANCE_IDENTIFIER"]?>_<?=$arResult["FIELD_IDENTIFIER"]?>_value_<?=$value?>">
    		<?if($arParams["SHOW_URL"] && array_key_exists("URL",$arItem)):?><a class="<?=$arResult["TEMPLATE_IDENTIFIER"]?>-values<?if($arParams["MULTIPLE"]):?>-multiple<?endif?>-value-link" href="<?=$arItem["URL"]?>"><?endif?><?=$arItem["NAME"]?><?if($arParams["SHOW_URL"] && array_key_exists("URL",$arItem)):?></a><?endif?>
    		<a class="<?=$arResult["TEMPLATE_IDENTIFIER"]?>-values<?if($arParams["MULTIPLE"]):?>-multiple<?endif?>-value-delete" href="#" onclick="<?=$arResult["TEMPLATE_IDENTIFIER"]?>.deleteitem('<?=$arResult["INSTANCE_IDENTIFIER"]?>','<?=$arResult["FIELD_IDENTIFIER"]?>',this); return false;" rel="<?=$value?>">x</a>
    		<input type="hidden" id="<?=$arResult["INSTANCE_IDENTIFIER"]?>_hidden_<?=$value?>" name="<?=$arParams["INPUT_NAME"]?><?if($arParams["MULTIPLE"]):?>[<?if($arParams["USE_VALUE_ID"]) echo $arItem["VALUE_ID"];?>]<?endif?>" value="<?=$value?>" />
    	</div>
    <?endforeach?>
</span>

<?endif?>

<script type="text/javascript">

<?if(!$arParams["USE_AJAX"]):?>

<?=$arResult["TEMPLATE_IDENTIFIER"]?>.lists['<?=$arResult["INSTANCE_IDENTIFIER"]?>'] = <?=$arResult["JS_DATA"]?>;

<?endif?>

<?=$arResult["TEMPLATE_IDENTIFIER"]?>.setparams({
	text_classname: '<?=$arParams["ADMIN_SECTION"]?"adm-input":$arResult["TEMPLATE_IDENTIFIER"]."-text"?>',
	text_placeholded_classname: '<?=$arParams["ADMIN_SECTION"]?"adm-input":$arResult["TEMPLATE_IDENTIFIER"]."-text-placeholded"?>',
	dropdown_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]."-dropdown".($arParams["ADMIN_SECTION"]?" ".$arResult["TEMPLATE_IDENTIFIER"]."-dropdown-admin":"")?>',
	dropdown_scroll_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-dropdown-scroll',
	dropdown_container_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-dropdown-container',
	item_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-item',
	item_selected_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-item-selected',
	item_current_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-item-current',
	item_link_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-item-link',
	highlight_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-highlight',
	message_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-message',
	item_multiple_value_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-values-multiple-value',
	item_multiple_link_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-values-multiple-value-link',
	item_multiple_delete_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-values-multiple-value-delete',
	item_value_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-values-value',
	item_link_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-values-value-link',
	item_delete_classname: '<?=$arResult["TEMPLATE_IDENTIFIER"]?>-values-value-delete'
});

<?=$arResult["TEMPLATE_IDENTIFIER"]?>.setinstanceparams('<?=$arResult["INSTANCE_IDENTIFIER"]?>',{
	multiple: <?=$arParams["MULTIPLE"]?"true":"false"?>,
	use_ajax: <?=$arParams["USE_AJAX"]?"true":"false"?>,
	use_value_id: <?=$arParams["USE_VALUE_ID"]?"true":"false"?>,
	show_ajax_error: true,
	show_ajax_empty: true,
	<?if($arParams["~ON_AFTER_SELECT"]):?>on_after_select: function(strValue,strName,strUrl,obSelect,instance_id){<?=$arParams["~ON_AFTER_SELECT"]?>},<?endif?>
	<?if($arParams["~ON_AFTER_REMOVE"]):?>on_after_remove: function(strValue,strName,strUrl,obSelect,instance_id){<?=$arParams["~ON_AFTER_REMOVE"]?>},<?endif?>
	use_search: <?=$arParams["USE_SEARCH"]?"true":"false"?>,
	show_url: <?=$arParams["SHOW_URL"]?"true":"false"?>,
	empty_show_all: <?=$arParams["EMPTY_SHOW_ALL_URL"]?"true":"false"?>,
	cur_page: '<?=$APPLICATION->GetCurPageParam()?>',
	MESSAGE_PLACEHOLDER: '<?=$arParams["MESSAGE_PLACEHOLDER"]?$arParams["MESSAGE_PLACEHOLDER"]:GetMessage($arParams["USE_SEARCH"] || $arParams["USE_AJAX"]?"GRAIN_LINKS_EDIT_T_DEFAULT_PLACEHOLDER_SEARCH":"GRAIN_LINKS_EDIT_T_DEFAULT_PLACEHOLDER_SELECT")?>',
	MESSAGE_LIST_EMPTY: '<?=$arParams["MESSAGE_LIST_EMPTY"]?$arParams["MESSAGE_LIST_EMPTY"]:GetMessage("GRAIN_LINKS_EDIT_T_DEFAULT_LIST_EMPTY")?>',
	MESSAGE_NOT_FOUND: '<?=$arParams["MESSAGE_NOT_FOUND"]?$arParams["MESSAGE_NOT_FOUND"]:GetMessage("GRAIN_LINKS_EDIT_T_DEFAULT_NOT_FOUND")?>',
	MESSAGE_AJAX_ERROR: '<?=$arParams["MESSAGE_AJAX_ERROR"]?$arParams["MESSAGE_AJAX_ERROR"]:GetMessage("GRAIN_LINKS_EDIT_T_DEFAULT_AJAX_ERROR")?>',
	MESSAGE_DELETE_MULTIPLE: 'x',
	MESSAGE_DELETE: 'x'
});

<?if(!$arParams["SCRIPTS_ONLY"]):?>

<?=$arResult["TEMPLATE_IDENTIFIER"]?>.ibind(
	'<?=$arResult["INSTANCE_IDENTIFIER"]?>',
	'<?=$arResult["FIELD_IDENTIFIER"]?>',
	{
		values_id: '<?=$arResult["INSTANCE_IDENTIFIER"]?>_values',
		input_name: '<?=$arParams["INPUT_NAME"]?>'
	},
	<?=$arResult["JS_SELECTED"]?>
);

<?endif?>

</script>