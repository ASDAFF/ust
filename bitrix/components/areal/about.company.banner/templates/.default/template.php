<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["BANNER"])):?>
	<?if($arParams["LOCATION"] == "TOP"):?>
		<div class="carousel border_gray big">
			<ul id="carousel">
				<?foreach($arResult["BANNER"] as $arBanner):?>
					<li class="image">
						<table><tr><td>			
							<img src="<?=$arBanner["PREVIEW_PICTURE"]["src"]?>" width="<?=$arBanner["PREVIEW_PICTURE"]["width"]?>" height="<?=$arBanner["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arBanner["NAME"]?>" title="<?=$arBanner["NAME"]?>" />
						</td></tr></table>
						<div class="description"><span><?=$arBanner["NAME"]?></span></div>
					</li>
				<?endforeach;?>
			</ul>
			<div class="nav">
				<a href="#" class="prev"></a> 
				<a href="#" class="next"></a>
				<ul class="pagging"></ul>
			</div>
			<div class="clear"></div>
		</div>
	<?else:?>
		<div class="jcarousel" id="about_company">
			<ul>
				<?foreach($arResult["BANNER"] as $key => $arBanner):?>
					<li class="border_gray image">
						<table><tr><td>
							<img src="<?=$arBanner["PREVIEW_PICTURE"]["src"]?>" width="<?=$arBanner["PREVIEW_PICTURE"]["width"]?>" height="<?=$arBanner["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arBanner["NAME"]?>" title="<?=$arBanner["NAME"]?>" />
						</td></tr></table>
						<div class="description"><span><?=$arBanner["NAME"]?></span></div>
					</li>
				<?endforeach;?>
			</ul>
			<button class="jcarousel-prev"></button>
			<button class="jcarousel-next"></button>
		</div>
	<?endif;?>
<?endif;?>