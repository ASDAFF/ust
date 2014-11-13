<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["BANNER"])):?>
	<div class="banner-left">
		<?if(!empty($arResult["BANNER"]["FLASH"])):?>
			<object  width="<?=$arResult["BANNER"]["PICTURE"]["width"]?>" height="<?=$arResult["BANNER"]["PICTURE"]["height"]?>">
				<param name="movie" value="<?=$arResult["BANNER"]["FLASH"]?>" />
                                <param name="wmode" value="opaque"/>
				<embed wmode="opaque">
				<!--[if !IE]>--><object type="application/x-shockwave-flash" data="<?=$arResult["BANNER"]["FLASH"]?>" width="<?=$arResult["BANNER"]["PICTURE"]["width"]?>" height="<?=$arResult["BANNER"]["PICTURE"]["height"]?>"><!--<![endif]-->
				<!--[if !IE]>--><param name="wmode" value="opaque"/><!--<![endif]-->
				<!--[if !IE]>--><embed wmode="opaque"><!--<![endif]-->
				<img src="<?=$arResult["BANNER"]["PICTURE"]["src"]?>" width="<?=$arResult["BANNER"]["PICTURE"]["width"]?>" height="<?=$arResult["BANNER"]["PICTURE"]["height"]?>" alt="<?=$arResult["BANNER"]["NAME"]?>" />
				<!--[if !IE]>--></object><!--<![endif]-->
			</object>
		<?else:?>
			<img src="<?=$arResult["BANNER"]["PICTURE"]["src"]?>" width="<?=$arResult["BANNER"]["PICTURE"]["width"]?>" height="<?=$arResult["BANNER"]["PICTURE"]["height"]?>" alt="<?=$arResult["BANNER"]["NAME"]?>" title="<?=$arResult["BANNER"]["NAME"]?>" />
		<?endif;?>
	</div>
<?endif;?>