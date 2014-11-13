<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>	
<div class="dialog" id="service_phone">
	<div class="title">Телефоны аварийной круглосуточной службы поддержки</div>
	<div class="scrollBox">
		<div id="pane_phone" class="scroll-pane phone_service">
			<?//pr($arResult["PHONE"]);?>
			<?if(!empty($arResult["PHONE"])):?>
				<?$flag = 0;?>
				<?foreach($arResult["PHONE"] as $town => $arPhone):?>
					<div class="phone<?if(($flag+1)%3 == 0):?> last<?endif;?>">
						<p><?=$town?></p>
<? /*$arPhone = str_replace(',',',<br />', $arPhone);*/?>

						<p class="tel"><?=$arPhone?></p>
					</div>
					<?if(($flag+1)%3 == 0):?><div class="clear"></div><?endif;?>
					<?$flag++;?>					
				<?endforeach;?>
			<?else:?>
				<p>Информации о сервисных центрах не найдено.</p>
			<?endif;?>
		</div>
	</div>
</div>