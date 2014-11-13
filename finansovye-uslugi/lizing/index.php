<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Лизинг");
?>

	<?$APPLICATION->IncludeComponent("bitrix:main.include", "template1", Array(
	"AREA_FILE_SHOW" => "file",	// Показывать включаемую область
	"PATH" => SITE_DIR."include/finansovye-uslugi/lising.php",	// Путь к файлу области
	"EDIT_TEMPLATE" => "",	// Шаблон области по умолчанию
	),
	false
);?> 

<?/*$APPLICATION->IncludeComponent("areal:form.order", "template1", Array(
	
	),
	false
);*/?>
<div class="clear"></div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>