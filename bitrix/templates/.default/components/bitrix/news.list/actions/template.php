<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["ITEMS"]) > 0):?>
	<div class="current-actions">
		<div class="actions-list">
			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<div class="action bordershadow <?if(($key+1)%3 == 0):?> last <?endif;?>">
					<?if(!empty($arItem["PROPERTIES"]["PERCENT"]["VALUE"])):?>
						<span class="sale_percent"><?=$arItem["PROPERTIES"]["PERCENT"]["VALUE"]?>%</span>
					<?endif;?>
					<div class="image">
						<?if(!empty($arItem["PREVIEW_PICTURE_RESIZE"]["src"])):?>
							<img src="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["src"]?>" width="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["width"]?>" height="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
						<?else:?>
							<img src="<?=getImageNoPhoto(278, 143)?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
						<?endif;?>
					</div>
					<div class="action-bot">
						<div class="text"><?=$arItem["NAME"]?></div>
					</div>
					<div class="counter">
						<div class="ico"></div>
						<?if($arItem["TIME_LEFT"]):?>
							<div class="timer"><?=$arItem["TIME_LEFT"]?></div>
						<?endif;?>
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="details-link">Подробнее &raquo;</a>
					</div>
				</div>
			<?endforeach;?>
			<div class="clear"></div>		
		</div>
	</div>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>
<?else:?>
	<p class="errortext">К сожалению, в данном разделе нет доступной информации.</p>
<?endif;?>