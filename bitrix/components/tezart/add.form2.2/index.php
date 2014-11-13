<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Обратная связь Нефтехим");
$APPLICATION->SetPageProperty("keywords", "Обратная связь, Нефтехим, отраслевая группа, надежная компания, торговый партнер, мировой лидер, горюче-смазочные материалы, Mobil, Shell, Castrol,Esso,Teboil, Texaco, ЛукОйл, ТНК, Газпромнефть");
$APPLICATION->SetPageProperty("title", "Обратная связь");
$APPLICATION->SetTitle("Нефтехим. Обратная связь");
?> 
 
<?#форма обратной связи?>	
<?$APPLICATION->IncludeComponent("tezart:add.form2.0", "callback", array(
	"IBLOCK_TYPE" => "Inbox",						// Тип инфоблока
	"IBLOCK_ID" => "13",                           // ID инфоблока
	"STATUS_NEW" => "Y",                            // Статус новой заявки (активен/не активен)
	"CACHE_TYPE" => "A",                            // Кешировать
	"CACHE_TIME" => "36000000",                     // Время кеширования
	"PROPERTY_CODES" => array(                      // ID свойств которые необходимо вывести
		0 => "83",
		1 => "62",		
		2 => "63",
		3 => "84",
	),
	"PROPERTY_CODES_REQUIRED" => array(             // Обязательные свойства
		0 => "83",
		1 => "62",
	),
	"CUSTOM_NAME" => "#83#",
	"ID_PROPERTY_EMAIL" => "", 						// ID свойства в котором храниться е-mail
	"SEND_MAIL" => "Y",                             // Отправлять на почту поповещение, что добавлен новый элемент (заявка) в инфоблок
	"EVENT_TYPE" => "NEW_REQUEST_CALLBACK",
	"EMAIL_SUBJ" => "Новая заявка с формы перезвоните мне",					// Тема почтового сообщения
	"EMAIL" => array(                                                               // Список адресов куда требуется отправить оповещение
		0 => "avokires@yandex.ru",
		1 => "stqa.gm@gmail.com",
	),
	"USE_CAPTCHA" => "N",                                                           // Использовать каптчу
	"TITLE_FORM" => "",               // Заголовок формы
	"BUTTON_NAME" => "Отправить",                                                   // Название кнопки (разные бывают)
	"FORM_MESSAGES" => "Спасибо. Ваша заявка принята. Наш менеджер свяжется с вами в течение получаса",    // Текст выводимый после успешной отправки данных в форму
	"CUSTOM_TITLE_13" => "",                                                         // Кастомизированые названия полей формы
	"CUSTOM_TITLE_15" => "",
	"CUSTOM_TITLE_17" => "",
	"CUSTOM_TITLE_14" => "",
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>