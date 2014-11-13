<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["ITEMS"]) > 0):?>
	<div class="current-actions">
		<div class="actions-list">
			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="action bordershadow <?if(($key+1)%3 == 0):?> last <?endif;?>">
					<?if(!empty($arItem["PROPERTIES"]["PERCENT"]["VALUE"])):?>
						<?
							if($arItem["PROPERTIES"]["PERCENT"]["VALUE"] > 0 && $arItem["PROPERTIES"]["PERCENT"]["VALUE"] <= 10)
								$class = "yellow";
							elseif($arItem["PROPERTIES"]["PERCENT"]["VALUE"] > 10 && $arItem["PROPERTIES"]["PERCENT"]["VALUE"] <= 20)
								$class = "orange";
							elseif($arItem["PROPERTIES"]["PERCENT"]["VALUE"] > 20 && $arItem["PROPERTIES"]["PERCENT"]["VALUE"] <= 30)
								$class = "green";
							elseif($arItem["PROPERTIES"]["PERCENT"]["VALUE"] > 30)
								$class = "blue";
							else
								$class = "blue";
						
						?>
						<span class="sale_percent <?=$class?>"><?=$arItem["PROPERTIES"]["PERCENT"]["VALUE"]?>%</span>
					<?endif;?>
					<div class="image">
						<table><tr><td>
						<?if(!empty($arItem["PREVIEW_PICTURE_RESIZE"]["src"])):?>
							<img src="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["src"]?>" width="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["width"]?>" height="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
						<?else:?>
							<img src="<?=getImageNoPhoto(278, 139)?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
						<?endif;?>
						</td></tr></table>
					</div>
					<div class="action-bot">
						<div class="text"><?=$arItem["NAME"]?></div>
					</div>
					<div class="counter">
						<div class="ico"></div>
						<div class="timer"><?=$arItem["TIME_LEFT"]?></div>
						<span class="details-link">Подробнее &raquo;</span>
					</div>
				</a>
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