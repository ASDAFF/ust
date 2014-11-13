<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/***************** Resize image for main.file.input ****************/

if (!function_exists("__MPF_ImageResizeHandlerCal"))
{
	function __MPF_ImageResizeHandlerCal(&$arCustomFile, $arParams = null)
	{
		static $arResizeParams = array();

		if ($arParams !== null)
		{
			if (is_array($arParams) && array_key_exists("width", $arParams) && array_key_exists("height", $arParams))
			{
				$arResizeParams = $arParams;
			}
			elseif(intVal($arParams) > 0)
			{
				$arResizeParams = array("width" => intVal($arParams), "height" => intVal($arParams));
			}
		}

		if ((!is_array($arCustomFile)) || !isset($arCustomFile['fileID']))
			return false;

		$fileID = $arCustomFile['fileID'];

		$arFile = CFile::MakeFileArray($fileID);
		if (CFile::CheckImageFile($arFile) === null)
		{
			$aImgThumb = CFile::ResizeImageGet(
				$fileID,
				array("width" => 90, "height" => 90),
				BX_RESIZE_IMAGE_EXACT,
				true
			);
			$arCustomFile['img_thumb_src'] = $aImgThumb['src'];

			if (!empty($arResizeParams))
			{
				$aImgSource = CFile::ResizeImageGet(
					$fileID,
					array("width" => $arResizeParams["width"], "height" => $arResizeParams["height"]),
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true
				);
				$arCustomFile['img_source_src'] = $aImgSource['src'];
				$arCustomFile['img_source_width'] = $aImgSource['width'];
				$arCustomFile['img_source_height'] = $aImgSource['height'];
			}
		}
	}
}

if (!empty($arParams["UPLOAD_FILE_PARAMS"]))
{
	$bNull = null;
	__MPF_ImageResizeHandlerCal($bNull, $arParams["UPLOAD_FILE_PARAMS"]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['mfi_mode']) && ($_REQUEST['mfi_mode'] == "upload"))
{
	AddEventHandler('main',  "main.file.input.upload", '__MPF_ImageResizeHandlerCal');
}

/***************** Trap for CID from webdav.user.field *************/
if (!empty($arParams["UPLOAD_WEBDAV_ELEMENT"]))
{
	if (!function_exists("__main_post_form_get_cid_webdav_cal"))
	{
		function __main_post_form_get_cid_webdav_cal($arResult = false, $arParams = false)
		{
			static $CID = false;

			if ($arResult === false && $arParams === false)
				return $CID;
			if ($arParams['EDIT'] == 'Y')
				$CID = $arResult['UID'];
			return true;
		}
	}
	ob_start();
	$eventHandlerID = AddEventHandler("webdav", "webdav.user.field", "__main_post_form_get_cid_webdav_cal");
	$APPLICATION->IncludeComponent(
		"bitrix:system.field.edit",
		"webdav_element",
		array("arUserField" => $arResult['USER_FIELDS']['UF_WEBDAV_CAL_EVENT']),
		null,
		array("HIDE_ICONS" => "Y")
	);
	RemoveEventHandler("webdav", "webdav.user.field", $eventHandlerID);
	$arParams["UPLOAD_WEBDAV_ELEMENT_HTML"] = ob_get_clean();
	$arParams["UPLOAD_WEBDAV_ELEMENT_CID"] = __main_post_form_get_cid_webdav_cal();
}
?>