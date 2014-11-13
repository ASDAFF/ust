<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["FILIAL"])):?>
	<?foreach($arResult["FILIAL"] as $arFilial):?>
		<a href="#" class="open <?if($arFilial["TOWN_ID"] == $arResult["ACTIVE_TOWN"]):?>active<?endif;?>" id="filial_<?=$arFilial["TOWN_ID"]?>">
			<h2><?=$arFilial["TOWN_NAME"]?></h2>
		</a>
		<div class="filial filial_<?=$arFilial["TOWN_ID"]?><?if($arFilial["TOWN_ID"] == $arResult["ACTIVE_TOWN"]):?> active<?endif;?>">
			<?if(!empty($arFilial["SERVICES"])):?>
				<div class="services">
					<?foreach($arFilial["SERVICES"] as $service):?>
						<a href="<?=$service["URL"]?>" class="image service-icon">
							<table><tr><td>
								<img detail-pic="<?=$service["DETAIL"]["src"]?>" src="<?=$service["PICTURE"]["src"]?>" width="<?=$service["PICTURE"]["width"]?>" height="<?=$service["PICTURE"]["height"]?>" />
							</td></tr></table>
							<span><i></i><?=$service["NAME"]?></span>
						</a>
					<?endforeach;?>
					<div class="clear"></div>
				</div>
			<?endif;?>
			<?if(!empty($arFilial["ADDRESS"])):?>
				<p>Адрес:<br /><a href="<?=$arFilial["LINK"]?>" class="link_red" title="Подробная информация"><?=$arFilial["ADDRESS"]?> (Подробная информация)</a></p>
			<?endif;?>
			<?if(!empty($arFilial["PHONE"])):?>
				<p>Тел:<br /><?=$arFilial["PHONE"]?></p>
			<?endif;?>
			<?if(!empty($arFilial["WORK_SHEDULE"]["TEXT"])):?>
				<p>График работы:<br /><?=$arFilial["WORK_SHEDULE"]["TEXT"]?></p>
			<?endif;?>
			<?if(!empty($arFilial["EMAIL"])):?>
				<p>Email:<br /><a class="e-mail" title="<?=$arFilial["EMAIL"][0]?>" href="#<?=$arFilial["EMAIL"][1]?>"></a></p>
			<?endif;?>
			<?if(!empty($arFilial["PREVIEW_TEXT"])):?>
				<?if($arFilial["PREVIEW_TEXT_TYPE"] == "text"):?>
					<p><?=$arFilial["PREVIEW_TEXT"]?></p>
				<?else:?>
					<?=$arFilial["PREVIEW_TEXT"]?>
				<?endif;?>
			<?endif;?>
		</div>
	<?endforeach;?>
<?else:?>
	<p>К сожалению, в выбранном городе еще нет филиалов.</p>
<?endif;?>