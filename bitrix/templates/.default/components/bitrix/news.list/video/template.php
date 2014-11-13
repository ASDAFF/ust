<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["ITEMS"])):?>
	<div class="icon-title"><span></span>Видео</div>
	<div class="list">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<div class="item">
				<div class="video">
					<div class="frame"></div>
					<a href="/player.php?id=<?=$arItem["ID"]?>" onclick="window.open(this.href, this.target,'width=<?=(($arItem["PROPERTIES"]["FILE"]["VALUE"]["width"] > 0) ? $arItem["PROPERTIES"]["FILE"]["VALUE"]["width"] : 420)?>, height=<?=(($arItem["PROPERTIES"]["FILE"]["VALUE"]["height"] > 0) ? $arItem["PROPERTIES"]["FILE"]["VALUE"]["height"]+20 : 320)?>,scrollbars=0');return false;" title="Посмотреть">
						<?if(!empty($arItem["PREVIEW_PICTURE_RESIZE"])):?>
							<img src="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["src"]?>" width="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["width"]?>" height="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
						<?else:?>
							<img src="<?=getImageNoPhoto(233, 149)?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />						
						<?endif;?>
						<span class="icon"></span>
						<span class="video-overlay"></span>
					</a>
				</div>
				<div class="name"><a href="/player.php?id=<?=$arItem["ID"]?>" onclick="window.open(this.href, this.target,'width=<?=(($arItem["PROPERTIES"]["FILE"]["VALUE"]["width"] > 0) ? $arItem["PROPERTIES"]["FILE"]["VALUE"]["width"] : 420)?>, height=<?=(($arItem["PROPERTIES"]["FILE"]["VALUE"]["height"] > 0) ? $arItem["PROPERTIES"]["FILE"]["VALUE"]["height"]+20 : 320)?>,scrollbars=0');return false;" title="Посмотреть"><?=$arItem["NAME"]?></a></div>
			</div>
		<?endforeach;?>
	</div>
<?endif;?>