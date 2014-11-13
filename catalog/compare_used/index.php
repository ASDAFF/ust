<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
$APPLICATION->SetTitle("Сравнение");
$APPLICATION->SetPageProperty("HIDE_MENU", "Y");?> <?
       $APPLICATION->IncludeComponent("areal:catalog.compare.result", "compare_used", Array(
	"NAME" => "CATALOG_COMPARE_LIST",	// Уникальное имя для списка сравнения
	"IBLOCK_TYPE" => "used",	// Тип инфоблока
	"IBLOCK_ID" => "54",	// Инфоблок
	"FIELD_CODE" => array(	// Поля
		0 => "NAME",
		1 => "PREVIEW_PICTURE",
		2 => "DETAIL_PICTURE",
	),
	"PROPERTY_CODE" => array(	// Свойства
		0 => "MAX_WEIGHT",
		1 => "CITY",
		2 => "HEIGHT",
		3 => "BRAND",
		4 => "WORKED",
		5 => "MODEL",
		6 => "YEAR",
		7 => "KPP",
		8 => "COMPLECT",
		9 => "TOWER",
		10 => "",
	),
	"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
	"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
	"AJAX_MODE" => "N",	// Включить режим AJAX
	"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
	"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
	"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
	"DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
	"BASKET_URL" => "",	// URL, ведущий на страницу с корзиной покупателя
	"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
	"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
	"SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
	"PRICE_CODE" => array(	// Тип цены
		0 => "BASE",
	),
	"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
	"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
	"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
	"CONVERT_CURRENCY" => "N",	// Показывать цены в одной валюте
	"DISPLAY_ELEMENT_SELECT_BOX" => "N",	// Выводить список элементов инфоблока
	"ELEMENT_SORT_FIELD_BOX" => "name",	// По какому полю сортируем список элементов
	"ELEMENT_SORT_ORDER_BOX" => "asc",	// Порядок сортировки списка элементов
	"ELEMENT_SORT_FIELD_BOX2" => "id",	// Поле для второй сортировки списка элементов
	"ELEMENT_SORT_ORDER_BOX2" => "desc",	// Порядок второй сортировки списка элементов
	"HIDE_NOT_AVAILABLE" => "N",	// Не отображать в списке товары, которых нет на складах
	"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
	),
	false
); ?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>