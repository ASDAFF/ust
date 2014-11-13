<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["SECTIONS"]) > 1 || (count($arResult["SECTIONS"]) == 1 && !empty($arResult["SECTIONS"]["DESCRIPTION"]))):?>
	<div class="useful-info">
		<div class="icon-title">Полезная информация<span></span></div>
		<div class="tags">
			<?foreach($arResult["SECTIONS"] as $arSection):?>
				<a href="#" id="tooltip_<?=$arSection["ID"]?>" <?if($arSection["SELECTED"] == 1):?>class="active"<?endif;?>><?=$arSection["NAME"]?></a>
			<?endforeach;?>
		</div>
		<?foreach($arResult["SECTIONS"] as $arSection):?>
			<div class="text descr_<?=$arSection["ID"]?>">
				<?if(!empty($arSection["DESCRIPTION"])):?>
					<?if($arSection["DESCRIPTION_TYPE"] == "text"):?>
						<p><?=$arSection["DESCRIPTION"]?></p>
					<?else:?>
						<?=$arSection["DESCRIPTION"]?>
					<?endif;?>
				<?else:?>
					<p>Полезной информации не найдено.</p>
				<?endif;?>				
			</div>
		<?endforeach;?>
	</div>
<?endif;?>