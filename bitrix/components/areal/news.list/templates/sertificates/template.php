<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["ITEMS"])):?>
	<h2>Наши сертификаты</h2>
	<div class="clients-page">
		<?foreach($arResult["ITEMS"] as $key => $arItem):?>	
			<?if($key >= 0 && $key < $arParams["NEWS_COUNT"]):?>
				<div class="image clients <?if(($key+1)%5 == 0):?>last<?endif;?>">
					<?if(!empty($arItem["PREVIEW_PICTURE"]["src"])):?>
						<a href="<?=$arItem["DETAIL_PICTURE"]?>" rel="group_rewiews" class="fancybox">
					<?endif;?> 
							<table><tr><td>
								<?if(!empty($arItem["PREVIEW_PICTURE"]["src"])):?>
									<img src="<?=$arItem["PREVIEW_PICTURE"]["src"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["width"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
								<?else:?>
									<img src="<?=getImageNoPhoto(140, 110)?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
								<?endif;?>
							</td></tr></table>
							<span class="date"><?=$arItem["DATE_CREATE_STRING"]?></span>
							<span><?=$arItem["NAME"]?></span>
					<?if(!empty($arItem["PREVIEW_PICTURE"]["src"])):?>
						</a>
					<?endif;?> 
				</div>
			<?endif;?>
		<?endforeach;?>
		<div class="clear"></div>
		<?if($arParams["NEWS_COUNT"] < count($arResult["ITEMS"])):?>
			<div class="more">
				<?foreach($arResult["ITEMS"] as $key => $arItem):?>	
					<?if($key >= $arParams["NEWS_COUNT"]):?>
						<div class="image clients <?if(($key+1)%5 == 0):?>last<?endif;?>">
							<?if(!empty($arItem["PREVIEW_PICTURE"]["src"])):?>
								<a href="<?=$arItem["DETAIL_PICTURE"]?>" rel="group_rewiews" class="fancybox" title="<?=$arItem["NAME"]?>">
							<?endif;?> 
									<table><tr><td>
										<?if(!empty($arItem["PREVIEW_PICTURE"]["src"])):?>
											<img src="<?=$arItem["PREVIEW_PICTURE"]["src"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["width"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
										<?else:?>
											<img src="<?=getImageNoPhoto(140, 110)?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
										<?endif;?>
									</td></tr></table>
									<span class="date"><?=$arItem["DATE_CREATE_STRING"]?></span>
									<span><?=$arItem["NAME"]?></span>
							<?if(!empty($arItem["PREVIEW_PICTURE"]["src"])):?>
								</a>
							<?endif;?> 
						</div>
					<?endif;?>
				<?endforeach;?>
				<div class="clear"></div>
			</div>
			<a href="#" class="show-more" title="Показать все">Показать все<span></span></a>
			<div class="clear"></div>
		<?endif;?>
	</div>
<?else:?>
	 
<?endif;?>