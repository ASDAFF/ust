<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["ITEMS"]) > 0):?>
	<div class="archive-actions">
		<div class="title"><a href="#" class="archive-link">Архив акций<span></span></a></div>
		<div class="actions-list">
			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<div class="action bordershadow <?if(($key+1)%3 == 0):?> last <?endif;?>">
					<?if(!empty($arItem["PROPERTIES"]["PERCENT"]["VALUE"])):?>
						<span class="sale_percent gray"><?=$arItem["PROPERTIES"]["PERCENT"]["VALUE"]?>%</span>
					<?endif;?>
					<div class="frame"></div>
					<div class="image">
						<table><tr><td>
						<?if(!empty($arItem["PREVIEW_PICTURE_RESIZE"]["src"])):?>
							<img src="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["src"]?>" width="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["width"]?>" height="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
						<?else:?>
							<img src="<?=getImageNoPhoto(278, 139)?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
						<?endif;?>
						</td></tr></table>
						<div class="shadow"></div>
					</div>
					<div class="action-bot">
						<div class="text"><?=$arItem["NAME"]?></div>
					</div>
				</div>
			<?endforeach;?>
			<div class="clear"></div>
		</div>
	</div>
<?endif;?> 