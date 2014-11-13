<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//pr($arResult);?>
<?if(!empty($arResult["BANNERS"])):?>
	<div class="banner">
		<ul id="secondary_banner">
				<?foreach($arResult["BANNERS"] as $key => $arBanner):?>
					<li>
						<img src="<?=$arBanner["PREVIEW_PICTURE"]["src"]?>" width="<?=$arBanner["PREVIEW_PICTURE"]["width"]?>" height="<?=$arBanner["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arBanner["NAME"]?>" title="<?=$arBanner["NAME"]?>" />
						<?if(!empty($arBanner["PREVIEW_TEXT"])):?>
							<div class="frame-description"></div>
							<div class="description<?if(count($arResult["BANNERS"]) == 1):?> only<?endif;?>">
								<?if($arBanner["PREVIEW_TEXT_TYPE"] == "text"):?>
									<?=$arBanner["PREVIEW_TEXT"]?>
								<?else:?>
									<?=strip_tags($arBanner["PREVIEW_TEXT"])?>
								<?endif;?>
							</div>
						<?endif;?>
					</li>
				<?endforeach;?>
		</ul>
		<?if(count($arResult["BANNERS"]) > 1):?>
			<div class="nav">
				<a href="#" class="prev_secondary_banner"></a> 
				<a href="#" class="next_secondary_banner"></a>
				<ul id="nav_secondary_banner"></ul>
			</div>
		<?else:?>
			<input type="hidden" name="count_banner" value="<?=count($arResult["BANNERS"]);?>" />
		<?endif;?>
		<div class="clear"></div>
	</div>
<?endif;?>