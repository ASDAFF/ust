<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if(!empty($arResult["SECTION"]["~UF_SHORT_SEO_TEXT"]) || !empty($arResult["SECTION"]["~UF_LONG_SEO_TEXT"])):?>
	<div class="catalog-section-descr">
		<p><?=$arResult["SECTION"]["~UF_SHORT_SEO_TEXT"]?></p>
		<?if(!empty($arResult["SECTION"]["~UF_LONG_SEO_TEXT"])):?>
			<p class="more_description"><?=$arResult["SECTION"]["~UF_LONG_SEO_TEXT"]?></p>
			<p><a href="#" class="catalog_more">Подробнее &raquo;</a></p>
		<?endif;?>
	</div>
<?endif;?>