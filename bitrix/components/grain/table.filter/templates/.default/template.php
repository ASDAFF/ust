<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
$arResult["JS_TEMPLATE_ID"] = md5($arParams["NAME"]);

foreach($arResult["COLUMNS"] as $index=>$arColumn) {

	if($arColumn["TYPE"]=="link" && !GPropertyTable::IsLinksInstalled()) {
		$arResult["COLUMNS"][$index] = Array("TYPE" => "text");
		continue;
	}

	if($arColumn["TYPE"]=="link" && is_array($arColumn["LINK"]) && $arColumn["LINK"]["DATA_SOURCE"]) {

		$arResult["COLUMNS"][$index]["TEMPLATE_IDENTIFIER"] = "GRAIN_LINKS_EDIT_DEFAULT";
	
		$arParameters = $arColumn["LINK"];
	
		$arParameters["INPUT_NAME"] = "";
		$arParameters["USE_SEARCH"] = in_array($arColumn["INTERFACE"],Array("search","selectsearch"))?"Y":"N";
		$arParameters["USE_SEARCH_COUNT"] = "";
		$arParameters["EMPTY_SHOW_ALL"] = in_array($arColumn["INTERFACE"],Array("select","selectsearch"))?"Y":"N";
		$arParameters["NAME_TRUNCATE_LEN"] = "";
		$arParameters["USE_AJAX"] = $arColumn["INTERFACE"]=="ajax"?"Y":"N";
		$arParameters["VALUE"] = "";
		$arParameters["MULTIPLE"] = "N";
		$arParameters["ADMIN_SECTION"] = "N";
		$arParameters["LEAVE_EMPTY_INPUTS"] = "N";
		$arParameters["USE_VALUE_ID"] = "N";

		$arParameters["SCRIPTS_ONLY"] = "Y";

		$arResult["COLUMNS"][$index]["INSTANCE_IDENTIFIER"] = $GLOBALS["APPLICATION"]->IncludeComponent(
			"grain:links.edit",
			"",
			$arParameters,
			null,
			array('HIDE_ICONS' => 'Y')
		);
			
	}
	
}

?>

<script type="text/javascript">

	grain_tables_filter_<?=$arResult["JS_TEMPLATE_ID"]?> = <?=CUtil::PhpToJsObject($arResult["COLUMNS"])?>;

	function grain_tables_filter_change_type_<?=$arResult["JS_TEMPLATE_ID"]?>() {
		
		cur_col = this.options[this.selectedIndex].value;
		
		obCurInput = document.getElementById("grain_tables_cur_input_<?=$arResult["JS_TEMPLATE_ID"]?>");
		obEqualCheckbox = document.getElementById("grain_tables_equal_<?=$arResult["JS_TEMPLATE_ID"]?>");

		var cur_type = "";
		if(cur_col) cur_type = grain_tables_filter_<?=$arResult["JS_TEMPLATE_ID"]?>[cur_col].TYPE;
		
		if(obCurInput) {
		
			switch(cur_type) {
			
				case 'select':

					var newInput = document.createElement("select");

				    newInput.options[newInput.options.length] = new Option("<?=GetMessage("GRAIN_TABLES_TF_TEMPLATE_NOT_SELECT")?>", "");

					for(var index in grain_tables_filter_<?=$arResult["JS_TEMPLATE_ID"]?>[cur_col].VALUES) {
					    newInput.options[newInput.options.length] = new Option(grain_tables_filter_<?=$arResult["JS_TEMPLATE_ID"]?>[cur_col].VALUES[index].LANG.<?=LANGUAGE_ID?>, grain_tables_filter_<?=$arResult["JS_TEMPLATE_ID"]?>[cur_col].VALUES[index].VALUE);
					}
					
				
				break;

				case 'checkbox':

					var newInput = document.createElement("select");
				    newInput.options[newInput.options.length] = new Option("<?=GetMessage("GRAIN_TABLES_TF_TEMPLATE_NOT_SELECT")?>", "");
				    newInput.options[newInput.options.length] = new Option("<?=GetMessage("GRAIN_TABLES_TF_TEMPLATE_YES")?>", "Y");
				    newInput.options[newInput.options.length] = new Option("<?=GetMessage("GRAIN_TABLES_TF_TEMPLATE_NO")?>", "N");

				break;

				case 'link':
				
					if(grain_tables_filter_<?=$arResult["JS_TEMPLATE_ID"]?>[cur_col].INSTANCE_IDENTIFIER) {
				
						var newInput = document.createElement("table");
						newInput.setAttribute("style","display: inline-block !important; vertical-align: middle !important; border-spacing: 0px !important;");
						newInput.setAttribute("cellspacing","0");
						var newInputTr = document.createElement("tr");
						var newInputTd = document.createElement("td");
						newInputTd.setAttribute("style","padding: 0 !important");
						var newInputLink = document.createElement("input");
						newInputLink.setAttribute("type","text");
						newInputLink.setAttribute("style","width: auto !important");
						newInputLink.setAttribute("id","grain_tables_cur_input_link_<?=$arResult["JS_TEMPLATE_ID"]?>");
						newInputTd.appendChild(newInputLink);
						newInputTr.appendChild(newInputTd);
						var newInputTd = document.createElement("td");					
						var newInputLinkValues = document.createElement("span");
						newInputLinkValues.setAttribute("id","grain_tables_cur_input_link_values_<?=$arResult["JS_TEMPLATE_ID"]?>");
						newInputLinkValues.className = grain_tables_filter_<?=$arResult["JS_TEMPLATE_ID"]?>[cur_col].TEMPLATE_IDENTIFIER + "-values";
						newInputTd.appendChild(newInputLinkValues);
						newInputTr.appendChild(newInputTd);
						newInput.appendChild(newInputTr);
					
					} else {
					
						var newInput = document.createElement("span");
						newInput.innerHTML = '<?=GetMessage("GRAIN_TABLES_TF_TEMPLATE_LINKS_DS_NOT_SET")?>';
					
					}
				
				break;
				
				default:

					var newInput = document.createElement("input");
					newInput.setAttribute("type","text");

				break;
			
			}

			if(obEqualCheckbox) {

				if(cur_type=='select' || cur_type=='checkbox' || cur_type=='link') {
					obEqualCheckbox.checked = true;
				} else {
					obEqualCheckbox.checked = false;
				}

				if(cur_type=='select' || cur_type=='checkbox' || cur_type=='link' || cur_type=='') {
					obEqualCheckbox.style.display = "none";
				} else {
					obEqualCheckbox.style.display = "";
				}

			}
		
			if(cur_type!='link')
				newInput.setAttribute("name","<?=$arParams["NAME"]?>");
			newInput.setAttribute("id","grain_tables_cur_input_<?=$arResult["JS_TEMPLATE_ID"]?>");
			
			obCurInput.parentNode.replaceChild(newInput, obCurInput);
		
			if(cur_type=='link' && grain_tables_filter_<?=$arResult["JS_TEMPLATE_ID"]?>[cur_col].INSTANCE_IDENTIFIER) {

				GRAIN_LINKS_EDIT_DEFAULT.ibind(
					grain_tables_filter_<?=$arResult["JS_TEMPLATE_ID"]?>[cur_col].INSTANCE_IDENTIFIER,
					"grain_tables_cur_input_link_<?=$arResult["JS_TEMPLATE_ID"]?>",
					{
						values_id: "grain_tables_cur_input_link_values_<?=$arResult["JS_TEMPLATE_ID"]?>",
						input_name: "<?=$arParams["NAME"]?>"
					},
					{}
				);
			
			}
		
		}
	
	}

</script>


<span style="white-space: nowrap" class="grain-tables-filter">

	<?
	$cur_name = $arParams["VALUE"]["COL"];
	$cur_type = "";
	if($cur_name) $cur_type = $arResult["COLUMNS"][$cur_name]["TYPE"];
	
	switch($cur_type):
	
		case "select":
			?>
			<select type="text" name="<?=$arParams["NAME"]?>" id="grain_tables_cur_input_<?=$arResult["JS_TEMPLATE_ID"]?>" />
				<option value=""><?=GetMessage("GRAIN_TABLES_TF_TEMPLATE_NOT_SELECT")?></option>
				<?foreach($arResult["COLUMNS"][$cur_name]["VALUES"] as $arValue):?>
					<option value="<?=$arValue["VALUE"]?>"<?if($arParams["VALUE"]["VALUE"]==$arValue["VALUE"]):?> selected="selected"<?endif?>><?=$arValue["LANG"][LANGUAGE_ID]?></option>
				<?endforeach?>
			</select>
			<?
		break;

		case "checkbox":
			?>
			<select type="text" name="<?=$arParams["NAME"]?>" id="grain_tables_cur_input_<?=$arResult["JS_TEMPLATE_ID"]?>" />
				<option value=""><?=GetMessage("GRAIN_TABLES_TF_TEMPLATE_NOT_SELECT")?></option>
				<option<?if($arParams["VALUE"]["VALUE"]=="Y"):?> selected='selected'<?endif?> value="Y"><?=GetMessage("GRAIN_TABLES_TF_TEMPLATE_YES")?></option>
				<option<?if($arParams["VALUE"]["VALUE"]=="N"):?> selected='selected'<?endif?> value="N"><?=GetMessage("GRAIN_TABLES_TF_TEMPLATE_NO")?></option>
			</select>
			<?
		break;

		case "link":

			if(CModule::IncludeModule("grain.links")):
			
			$arColumn = $arResult["COLUMNS"][$cur_name];
			$value = $arParams["VALUE"]["VALUE"];
			$name = $arParams["NAME"];
			
			if($arColumn["INSTANCE_IDENTIFIER"]):
			
			?>
			<table id="grain_tables_cur_input_<?=$arResult["JS_TEMPLATE_ID"]?>" cellspacing="0" style="display: inline-block !important; vertical-align: middle !important; border-spacing: 0px !important;"><tr><td style="padding: 0 !important">
			<input 
				type="text" 
				id="grain_tables_cur_input_link_<?=$arResult["JS_TEMPLATE_ID"]?>"
				value=""
				<?if($arColumn["INTERFACE"]=="select"):?> readonly="readonly"<?endif?> 
			/>
			<?
			$SELECTED=Array();
			$JS_SELECTED=Array();
			
			$arParameters = $arColumn["LINK"];
			
			$arParameters["SHOW_URL"] = $arColumn["SHOW_URL"]=="Y";
			$arParameters["MULTIPLE"] = $arColumn["MULTIPLE"]=="Y";
			$arParameters["ADMIN_SECTION"] = false;
			
			$DATA = CGrain_LinksTools::GetSelected($arParameters,$value);
			
			if(is_array($value)) foreach($value as $value_id=>$val) {
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
			
			?>
			</td><td>
			<span class="<?=$arColumn["TEMPLATE_IDENTIFIER"]?>-values" id="grain_tables_cur_input_link_values_<?=$arResult["JS_TEMPLATE_ID"]?>">
			    <?$i=0;foreach($SELECTED as $val=>$arItem):?>
			    	<div class="<?=$arColumn["TEMPLATE_IDENTIFIER"]?>-values-value" id="<?=$arColumn["INSTANCE_IDENTIFIER"]?>_grain_tables_cur_input_link_<?=$arResult["JS_TEMPLATE_ID"]?>_value_<?=$val?>">
			    		<?if($arColumn["SHOW_URL"]=="Y" && array_key_exists("URL",$arItem)):?><a class="<?=$arColumn["TEMPLATE_IDENTIFIER"]?>-values-value-link" href="<?=$arItem["URL"]?>"><?endif?><?=$arItem["NAME"]?><?if($arColumn["SHOW_URL"]=="Y" && array_key_exists("URL",$arItem)):?></a><?endif?>
			    		<a class="<?=$arColumn["TEMPLATE_IDENTIFIER"]?>-values-value-delete" href="#" onclick="<?=$arColumn["TEMPLATE_IDENTIFIER"]?>.deleteitem('<?=$arColumn["INSTANCE_IDENTIFIER"]?>','grain_tables_cur_input_link_<?=$arResult["JS_TEMPLATE_ID"]?>',this); return false;" rel="<?=$val?>">x</a>
			    		<input type="hidden" id="<?=$arColumn["INSTANCE_IDENTIFIER"]?>_hidden_<?=$val?>" name="<?=$name?>" value="<?=$val?>" />
			    	</div>
			    <?$i++;endforeach?>
			</span>
			</td></tr></table>
			<script type="text/javascript">
			setTimeout( function() { 
			<?=$arColumn["TEMPLATE_IDENTIFIER"]?>.ibind(
				'<?=$arColumn["INSTANCE_IDENTIFIER"]?>',
				'grain_tables_cur_input_link_<?=$arResult["JS_TEMPLATE_ID"]?>',
				{
					values_id: 'grain_tables_cur_input_link_values_<?=$arResult["JS_TEMPLATE_ID"]?>',
					input_name: '<?=$name?>'
				},
				<?=CUtil::PhpToJsObject($JS_SELECTED)?>
			);
			},500);
			</script>
			<?
			
			else: // if data source not set
			
			echo GetMessage("GRAIN_TABLES_TF_TEMPLATE_LINKS_DS_NOT_SET");
			
			endif;
			
			endif;


		break;

		default:
			?>
			<input type="text" name="<?=$arParams["NAME"]?>" value="<?=$arParams["VALUE"]["VALUE"]?>" id="grain_tables_cur_input_<?=$arResult["JS_TEMPLATE_ID"]?>" />
			<?
		break;
	
	endswitch;
	
	?>


	<select name="<?=$arParams["NAME"]?>_col" id="grain_tables_cur_input_chtype_<?=$arResult["JS_TEMPLATE_ID"]?>">
		<option value=""><?=GetMessage("GRAIN_TABLES_TF_TEMPLATE_ALL_COLUMNS")?></option>
		<?foreach($arParams["SETTINGS"]["COLUMNS"] as $arColumn):?>
			<option<?if($arParams["VALUE"]["COL"]==$arColumn["NAME"]):?> selected='selected'<?endif?> value="<?=$arColumn["NAME"]?>"><?=$arColumn["LANG"][LANGUAGE_ID]["NAME"]?></option>
		<?endforeach?>
	</select>

	<script type="text/javascript">
	setTimeout( function() { 
		document.getElementById("grain_tables_cur_input_chtype_<?=$arResult["JS_TEMPLATE_ID"]?>").onchange = grain_tables_filter_change_type_<?=$arResult["JS_TEMPLATE_ID"]?>;
	},500);
	</script>
	
	
	<input type="checkbox"<?if(in_array($cur_type,Array("select","checkbox","link"))|| !$cur_type):?> style="display:none"<?endif?> title="<?=GetMessage("GRAIN_TABLES_TF_TEMPLATE_EQUAL")?>" name="<?=$arParams["NAME"]?>_equal"<?if($arParams["VALUE"]["EQUAL"]=="Y" || in_array($cur_type,Array("select","checkbox","link"))):?> checked='checked'<?endif?> value="Y" id="grain_tables_equal_<?=$arResult["JS_TEMPLATE_ID"]?>" />

</span>
