<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

if($arParams["~arUserField"]["MULTIPLE"]=="Y") {

	echo GUserPropertyTable::GetAdminListViewHTMLMulty(
		array(
			"SETTINGS" => $arParams['~arUserField']["SETTINGS"],
		),
		array(
			"NAME" => $arParams['~arUserField']["FIELD_NAME"],
			"VALUE" => $arParams['~arUserField']["VALUE"],
		)
	);

} else {

	echo GUserPropertyTable::GetAdminListViewHTML(
		array(
			"SETTINGS" => $arParams['~arUserField']["SETTINGS"],
		),
		array(
			"NAME" => $arParams['~arUserField']["FIELD_NAME"],
			"VALUE" => $arParams['~arUserField']["VALUE"],
		)
	);

}

?>
