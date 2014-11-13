<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["BANNERS"]) > 0):?>
	<input type="hidden" name="speed" value="<?=$arResult["SPEED"]?>" />
	<div id="slider" class="nivoSlider">
		<?foreach($arResult["BANNERS"] as $key => $arBanner):?>
			<?if(!empty($arBanner["URL"])):?>
				<a href="<?=$arBanner["URL"]?>" target="_blank" title="<?=$arBanner["NAME"]?>">
			<?endif;?>
					<img src="<?=$arBanner["PREVIEW_PICTURE"]?>" alt="<?=$arBanner["NAME"]?>" title="#htmlcaption<?=($key+1)?>" />
			<?if(!empty($arBanner["URL"])):?>
				</a>
			<?endif;?>
		<?endforeach;?>
	</div>
<?endif;?>
<div class="text-plate">
	<?if(count($arResult["BANNERS"]) > 0):?>
		<?foreach($arResult["BANNERS"] as $key => $arBanner):?>
			<div id="htmlcaption<?=($key+1)?>" class="nivo-html-caption">
				<div class="action">
					<div class="title"><?=$arBanner["NAME"]?></div>
					<div class="text"><?=$arBanner["PREVIEW_TEXT"]?></div>
				</div>
			</div>
		<?endforeach;?>
	<?endif;?>
