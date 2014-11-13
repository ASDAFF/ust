<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="catalog-section-descr">
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "catalog", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/arenda_default_seo.php"), false);?>
</div>
<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/arenda_advantages.php"), false);?>
