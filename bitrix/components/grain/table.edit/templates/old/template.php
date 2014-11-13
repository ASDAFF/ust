<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<table class="grain-tables-table-edit" id="grain_table_<?=$arParams["NAME"]?>" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<?foreach($arParams["SETTINGS"]["COLUMNS"] as $arColumn):?>
				<td>
					<?=$arColumn["LANG"][LANGUAGE_ID]["NAME"]?><?if($arColumn["REQUIRED"]=="Y"):?><span class="grain-tables-table-edit-star-required">*</span><?endif?>
					<?if($arColumn["LANG"][LANGUAGE_ID]["TOOLTIP"]):?>
						<div class="grain-tables-table-edit-tooltip"><?=$arColumn["LANG"][LANGUAGE_ID]["TOOLTIP"]?></div>
					<?endif?>
				</td>
			<?endforeach?>
			<?if($arParams["MULTIPLE"]=="Y"):?><td>&nbsp;</td><?endif?>
		</tr>
	</thead>
	<tbody>
		<?if($arParams["MULTIPLE"]=="Y"):?>
			<tr style="display: none" id="grain_table_row_template_<?=$arParams["NAME"]?>">
				<?foreach($arParams["SETTINGS"]["COLUMNS"] as $arColumn):?>
					<?$name="--NAME--"."[".$arColumn["NAME"]."]";$value=$arColumn["DEFAULT_VALUE"];?>
					<td>
						<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/row_template.php")?>
					</td>
				<?endforeach?>
				<td><a href="#" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); return false;" title="<?=GetMessage("GRAIN_TABLES_TE_TEMPLATE_DELETE_ROW")?>"><img src="/bitrix/images/grain.tables/gtables_icon_remove_tablerow.gif" width="16" height="16" border="0" /></a></td>
			</tr>
		<?endif?>
		<?if($arParams["MULTIPLE"]=="Y"):?>
			<?foreach($arParams["VALUE"] as $value_id=>$arValues):?>
			<tr>
				<?foreach($arParams["SETTINGS"]["COLUMNS"] as $arColumn):?>
					<?$name=$arParams["NAME"]."[".$value_id."][".$arColumn["NAME"]."]";$value=$arValues[$arColumn["NAME"]];?>
					<td>
						<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/row_template.php")?>
					</td>
				<?endforeach?>
				<td><a href="#" onclick="this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); return false;" title="<?=GetMessage("GRAIN_TABLES_TE_TEMPLATE_DELETE_ROW")?>"><img src="/bitrix/images/grain.tables/gtables_icon_remove_tablerow.gif" width="16" height="16" border="0" /></a></td>
			</tr>
			<?endforeach?>
		<?else:?>
			<tr>
				<?foreach($arParams["SETTINGS"]["COLUMNS"] as $arColumn):?>
					<?$name=$arParams["NAME"]."[".$arColumn["NAME"]."]";$value=$arParams["VALUE"][$arColumn["NAME"]];?>
	    			<td>
	    				<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/row_template.php")?>
	    			</td>
	    		<?endforeach?>
			</tr>
		<?endif?>
	</tbody>
	<?if($arParams["MULTIPLE"]=="Y"):?>
	<tfoot>
		<tr>
			<td colspan="<?=count($arParams["SETTINGS"]["COLUMNS"])+1?>"><a onclick="grainTableAddRow('<?=$arParams["NAME"]?>',<?if($arParams["USER_FIELDS"]=="Y"):?>true<?else:?>false<?endif?>); return false;" href="#"><img width="16" height="16" border="0" src="/bitrix/images/grain.tables/gtables_icon_add_tablerow.gif">&nbsp;&nbsp;<span><?=GetMessage("GRAIN_TABLES_TE_TEMPLATE_ADD_ROW")?></span></a>
</td>
		</tr>	
	</tfoot>
	<?endif?>
</table>