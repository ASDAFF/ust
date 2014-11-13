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

$APPLICATION->IncludeComponent(
    'bitrix:main.calendar', 
    '.default', 
    array(
    	'SHOW_INPUT' => 'Y',
    	'INPUT_NAME' => $name,
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


endswitch;

?>