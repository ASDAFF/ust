<?

switch($arColumn["TYPE"]):


case "text":

?>
<input type="text" name="<?=$name?>" value="<?=htmlspecialchars($value)?>" size="<?=$arColumn["SIZE"]?>" />
<?

break;


case "textarea":

?>
<textarea name="<?=$name?>" cols="<?=$arColumn["COLS"]?>" rows="<?=$arColumn["ROWS"]?>"><?=htmlspecialchars($value)?></textarea>
<?

break;


case "checkbox":

?>
<input type="checkbox" name="<?=$name?>" value="Y"<?if($value=="Y"):?> checked="checked"<?endif?> />
<?

break;


case "select":

?>
<select<?if($arColumn["MULTIPLE"]=="Y"):?> name="<?=$name?>[]" multiple="multiple"<?else:?> name="<?=$name?>"<?endif?>>
	<?foreach($arColumn["VALUES"] as $option):?>
		<?
			if($arColumn["MULTIPLE"]=="Y" && is_array($value)) $sel=in_array($option["VALUE"],$value);
			else $sel = $value==$option["VALUE"];
		?>
		<option value="<?=htmlspecialchars($option["VALUE"])?>"<?if($sel):?> selected="selected"<?endif?>><?=$option["LANG"][LANGUAGE_ID]?></option>
	<?endforeach?>
</select>
<?

break;


case "date":

?>
<input type="text" id="grain_table_date_field_<?=$id?>" name="<?=$name?>" value="<?=htmlspecialchars($value)?>" size="<?=$arColumn["SIZE"]?>" />
<?

$APPLICATION->IncludeComponent(
    'bitrix:main.calendar', 
    '.default', 
    array(
    	'SHOW_INPUT' => 'N',
    	'INPUT_NAME' => "grain_table_date_field_".$id,
    	'INPUT_VALUE' => $value,
    	'SHOW_TIME' => 'N',
    ), 
    null, 
    array('HIDE_ICONS' => 'Y')
);

break;


case "filepath":

?>
<input type="text" name="<?=$name?>" value="<?=htmlspecialchars($value)?>" />
<?if(strlen($value)>0):?><?if($arColumn["SHOW_AS"]=="image"):?><?if(CFile::IsImage($value)):$imgsize=getimagesize($_SERVER["DOCUMENT_ROOT"]."/".$value)?><br /><?=CFile::Show2Images($value,$value,100,100,"border=\"0\" class=\"grain-tables-table-edit-image-preview\"",GetMessage("GRAIN_TABLES_TE_TEMPLATE_IMAGE_TITLE",Array("#WIDTH#"=>$imgsize[0],"#HEIGHT#"=>$imgsize[1])))?><?endif?><?elseif($arColumn["SHOW_AS"]=="link"):?><br /><a href="<?=$value?>"><?=GetMessage("GRAIN_TABLES_TE_TEMPLATE_FILE_DOWNLOAD")?></a><?endif?><?endif?>
<?

break;

case "link":

if(GPropertyTable::IsLinksInstalled()):

if($arColumn["INSTANCE_IDENTIFIER"]):

?>
<input 
	type="text" 
	class="<?=$arColumn["TEMPLATE_IDENTIFIER"]?>-text <?=$arColumn["TEMPLATE_IDENTIFIER"]?>-text-placeholded"
	id="grain_table_link_field_<?=$id?>"
	value="<?=GetMessage($arColumn["INTERFACE"]=="select"?"GRAIN_LINKS_EDIT_T_DEFAULT_PLACEHOLDER_SELECT":"GRAIN_LINKS_EDIT_T_DEFAULT_PLACEHOLDER_SEARCH")?>"
	<?if($arColumn["INTERFACE"]=="select"):?> readonly="readonly"<?endif?> 
/>
<?
$SELECTED=Array();
$JS_SELECTED=Array();
if(!$bTemplate) {

	$arParameters = $arColumn["LINK"];
	
	$arParameters["SHOW_URL"] = $arColumn["SHOW_URL"]=="Y";
	$arParameters["MULTIPLE"] = $arColumn["MULTIPLE"]=="Y";
	$arParameters["ADMIN_SECTION"] = false;

	$DATA = CGrain_LinksTools::GetSelected($arParameters,$value);

	if(is_array($value)) foreach($value as $val) {
		if(!array_key_exists($val,$DATA))
			continue;
		$arItem=$DATA[$val];
	    $SELECTED[$val] = $arItem;
	    $JS_SELECTED[$val] = true;
	} else {
	    if(array_key_exists($value,$DATA)) {
	    	$SELECTED=Array($value => $DATA[$value]);
	    	$JS_SELECTED=Array($value => true);
	    }
	}

}
?>

<span class="<?=$arColumn["TEMPLATE_IDENTIFIER"]?>-values<?if($arColumn["MULTIPLE"]=="Y"):?>-multiple<?endif?>" id="grain_table_link_field_<?=$id?>_values">
    <?$i=0;foreach($SELECTED as $val=>$arItem):?>
    	<div class="<?=$arColumn["TEMPLATE_IDENTIFIER"]?>-values<?if($arColumn["MULTIPLE"]=="Y"):?>-multiple<?endif?>-value" id="<?=$arColumn["INSTANCE_IDENTIFIER"]?>_grain_table_link_field_<?=$id?>_value_<?=$val?>">
    		<?if($arColumn["SHOW_URL"]=="Y" && array_key_exists("URL",$arItem)):?><a class="<?=$arColumn["TEMPLATE_IDENTIFIER"]?>-values<?if($arColumn["MULTIPLE"]=="Y"):?>-multiple<?endif?>-value-link" href="<?=$arItem["URL"]?>"><?endif?><?=$arItem["NAME"]?><?if($arColumn["SHOW_URL"]=="Y" && array_key_exists("URL",$arItem)):?></a><?endif?>
    		<a class="<?=$arColumn["TEMPLATE_IDENTIFIER"]?>-values<?if($arColumn["MULTIPLE"]=="Y"):?>-multiple<?endif?>-value-delete" href="#" onclick="<?=$arColumn["TEMPLATE_IDENTIFIER"]?>.deleteitem('<?=$arColumn["INSTANCE_IDENTIFIER"]?>','grain_table_link_field_<?=$id?>',this); return false;" rel="<?=$val?>">x</a>
    		<input type="hidden" id="<?=$arColumn["INSTANCE_IDENTIFIER"]?>_hidden_<?=$val?>" name="<?=$name?><?if($arColumn["MULTIPLE"]=="Y"):?>[]<?endif?>" value="<?=$val?>" />
    	</div>
    <?$i++;endforeach?>
</span>


<?if(!$bTemplate):?>
<script type="text/javascript">
<?=$arColumn["TEMPLATE_IDENTIFIER"]?>.ibind(
	'<?=$arColumn["INSTANCE_IDENTIFIER"]?>',
	'grain_table_link_field_<?=$id?>',
	{
		values_id: 'grain_table_link_field_<?=$id?>_values',
		input_name: '<?=$name?>'
	},
	<?=CUtil::PhpToJsObject($JS_SELECTED)?>
);
</script>
<?endif?>
<?

else: // if data source not set

echo GetMessage("GRAIN_TABLES_TE_TEMPLATE_LINKS_DS_NOT_SET");

endif;

else: // if links not installed

if(is_array($value)):
foreach($value as $val):
?><input type="text" name="<?=$name?>[]" value="<?=htmlspecialchars($val)?>" /><?
endforeach;
else:
?><input type="text" name="<?=$name?>" value="<?=htmlspecialchars($value)?>" /><?
endif;

endif;

break;

endswitch;

?>