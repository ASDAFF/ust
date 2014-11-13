<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="catalog-filter">
	<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
		<?foreach($arResult["HIDDEN"] as $arItem):?>
			<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
		<?endforeach;?>
		<div class="field">
			<label>Тип техники:</label>
			<select name="type">
				<?foreach($arResult["TYPE"] as $arType):?>
					<option value="<?=$arType["CODE"]?>"<?if($arType["ID"] == $arResult["SELECTED_TYPE"]):?> selected="selected"<?endif;?>><?=$arType["NAME"]?></option>
				<?endforeach;?>
			</select>
		</div>
		<div class="field last">
			<label>Вид техники:</label>
			<select name="type">
				<option value="0">Все</option>
				<?foreach($arResult["VIEW"] as $arView):?>
					<?if($arView["IBLOCK_SECTION_ID"] == $arResult["SELECTED_TYPE"]):?>
						<option value="<?=$arView["CODE"]?>"<?if($arView["ID"] == $arResult["SELECTED_VIEW"]):?> selected="selected"<?endif;?>><?=$arView["NAME"]?></option>
					<?endif;?>
				<?endforeach;?>
			</select>
		</div>
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?if($arItem["CODE"] == "BRAND"):?>
				<?if(!empty($arItem["VALUES"])):?>
					<?$count = 0;?>
					<?foreach($arItem["VALUES"] as $val => $ar):?>					
						<div class="field checkbox<?if(($count+1)%4 == 0):?> last<?endif;?>">
							<label for="<?echo $ar["CONTROL_ID"]?>">
								<input type="checkbox" value="<?echo $ar["HTML_VALUE"]?>" name="<?echo $ar["CONTROL_NAME"]?>" id="<?echo $ar["CONTROL_ID"]?>" <?echo $ar["CHECKED"]? 'checked="checked"': ''?> onclick="smartFilter.click(this)" />
								<?echo $ar["VALUE"];?>
							</label>
						</div>
						<?$count++;?>
					<?endforeach;?>
				<?endif;?>
			<?endif;?>
				<?/*if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"])):?>
				<a href="#" onclick="BX.toggle(BX('ul_<?echo $arItem["ID"]?>')); return false;" class="showchild"><?=$arItem["NAME"]?></a>
					<ul id="ul_<?echo $arItem["ID"]?>">
						<?
							//$arItem["VALUES"]["MIN"]["VALUE"];
							//$arItem["VALUES"]["MAX"]["VALUE"];
						?>
						<li class="lvl2">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<span class="min-price"><?echo GetMessage("CT_BCSF_FILTER_FROM")?></span>
									</td>
									<td>
										<span class="max-price"><?echo GetMessage("CT_BCSF_FILTER_TO")?></span>
									</td>
								</tr>
								<tr>
									<td><input
										class="min-price"
										type="text"
										name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
										id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
										value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
										size="5"
										onkeyup="smartFilter.keyup(this)"
									/></td>
									<td><input
										class="max-price"
										type="text"
										name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
										id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
										value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
										size="5"
										onkeyup="smartFilter.keyup(this)"
									/></td>
								</tr>
							</table>
						</li>
					</ul>
				<?elseif(!empty($arItem["VALUES"])):;?>
				<a href="#" onclick="BX.toggle(BX('ul_<?echo $arItem["ID"]?>')); return false;" class="showchild"><?=$arItem["NAME"]?></a>
					<ul id="ul_<?echo $arItem["ID"]?>">
						<?foreach($arItem["VALUES"] as $val => $ar):?>
						<li class="lvl2<?echo $ar["DISABLED"]? ' lvl2_disabled': ''?>"><input
							type="checkbox"
							value="<?echo $ar["HTML_VALUE"]?>"
							name="<?echo $ar["CONTROL_NAME"]?>"
							id="<?echo $ar["CONTROL_ID"]?>"
							<?echo $ar["CHECKED"]? 'checked="checked"': ''?>
							onclick="smartFilter.click(this)"
						/><label for="<?echo $ar["CONTROL_ID"]?>"><?echo $ar["VALUE"];?></label></li>
						<?endforeach;?>
					</ul>
				<?endif;*/?>
		<?endforeach;?>
		<div class="clear"></div>
		<input type="hidden" name="set_filter" value="y" />
		<button type="submit" id="set_filter" name="set_filter" value="y"><?=GetMessage("CT_BCSF_SET_FILTER")?></button>
		<div class="modef" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?>>
			<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
			<a href="<?echo $arResult["FILTER_URL"]?>" class="showchild"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
		</div>
		<div class="clear"></div>
	</form>
</div>
<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>');
</script>