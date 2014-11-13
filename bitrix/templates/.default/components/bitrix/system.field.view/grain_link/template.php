<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?

if($arParams["~arUserField"]["MULTIPLE"]=="Y") {

	echo CGrain_UserPropertyLink::GetAdminListViewHTMLMulty(
		array(
			"SETTINGS" => $arParams['~arUserField']["SETTINGS"],
		),
		array(
			"NAME" => $arParams['~arUserField']["FIELD_NAME"],
			"VALUE" => $arParams['~arUserField']["VALUE"],
		)
	);

} else {

	echo CGrain_UserPropertyLink::GetAdminListViewHTML(
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
