<?
class CASDcropphoto {

	public static function CropImageFile($sourceFile, &$destinationFile, $arSize, $arWaterMark = array(), $jpgQuality = false, $arFilters = false) {

		if (CModule::IncludeModuleEx('asd.cropphoto') === MODULE_DEMO_EXPIRED) {
			return false;
		}

		$io = CBXVirtualIo::GetInstance();

		$imageInput = false;
		$bNeedCreatePicture = false;
		$picture = false;

		if (!is_array($arSize)) {
			$arSize = array();
		}
		if (!array_key_exists('width', $arSize) || IntVal($arSize['width']) <= 0) {
			$arSize['width'] = 0;
		}
		if (!array_key_exists('height', $arSize) || IntVal($arSize['height']) <= 0) {
			$arSize['height'] = 0;
		}
		$arSize['width'] = IntVal($arSize['width']);
		$arSize['height'] = IntVal($arSize['height']);

		$arSourceSize = array('x' => $arSize['x'], 'y' => $arSize['y'], 'width' => $arSize['width_orig'], 'height' => $arSize['height_orig']);
		$arDestinationSize = array('x' => 0, 'y' => 0, 'width' => $arSize['width'], 'height' => $arSize['height']);


		$arSourceFileSizeTmp = CFile::GetImageSize($sourceFile);
		if (!in_array($arSourceFileSizeTmp[2], array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_BMP))) {
			return false;
		}

		if (!$io->FileExists($sourceFile)) {
			return false;
		}

		if ($io->Copy($sourceFile, $destinationFile)) {
			$sourceImage = false;
			switch ($arSourceFileSizeTmp[2]) {
				case IMAGETYPE_GIF:
					$sourceImage = imagecreatefromgif($io->GetPhysicalName($sourceFile));
					$bHasAlpha = true;
					break;
				case IMAGETYPE_PNG:
					$sourceImage = imagecreatefrompng($io->GetPhysicalName($sourceFile));
					$bHasAlpha = true;
					break;
				case IMAGETYPE_BMP:
					$sourceImage = CFile::ImageCreateFromBMP($sourceFile);
					$bHasAlpha = false;
					break;
				default:
					$sourceImage = imagecreatefromjpeg($io->GetPhysicalName($sourceFile));
					$bHasAlpha = false;
					break;
			}

			$sourceImageWidth = IntVal(imagesx($sourceImage));
			$sourceImageHeight = IntVal(imagesy($sourceImage));

			if ($sourceImageWidth > 0 && $sourceImageHeight > 0) {
				if ($arSize['width'] <= 0 || $arSize['height'] <= 0) {
					$arSize['width'] = $sourceImageWidth;
					$arSize['height'] = $sourceImageHeight;
				}
				if (CFile::IsGD2()) {
					$picture = ImageCreateTrueColor($arDestinationSize['width'], $arDestinationSize['height']);
					if ($arSourceFileSizeTmp[2] == IMAGETYPE_PNG) {
						$transparentcolor = imagecolorallocatealpha($picture, 0, 0, 0, 127);
						imagefilledrectangle($picture, 0, 0, $arDestinationSize['width'], $arDestinationSize['height'], $transparentcolor);
						$transparentcolor = imagecolortransparent($picture, $transparentcolor);
						imagealphablending($picture, false);
						imagecopyresampled($picture, $sourceImage, 0, 0, $arSize['x'], $arSize['y'], $arSize['width'], $arSize['height'], $arSize['width'], $arSize['height']);
						imagealphablending($picture, true);
					} elseif ($arSourceFileSizeTmp[2] == IMAGETYPE_GIF) {
						imagepalettecopy($picture, $sourceImage);
						//Save transparency for GIFs
						$transparentcolor = imagecolortransparent($sourceImage);
						if ($transparentcolor >= 0 && $transparentcolor < imagecolorstotal($sourceImage)) {
							$RGB = imagecolorsforindex($sourceImage, $transparentcolor);
							$transparentcolor = imagecolorallocate($picture, $RGB['red'], $RGB['green'], $RGB['blue']);
							imagecolortransparent($picture, $transparentcolor);
							imagefilledrectangle($picture, 0, 0, $arDestinationSize['width'], $arDestinationSize['height'], $transparentcolor);
						}
						imagecopyresampled($picture, $sourceImage, 0, 0, $arSize['x'], $arSize['y'], $arSize['width'], $arSize['height'], $arSize['width'], $arSize['height']);
					} else {
						imagecopyresampled($picture, $sourceImage, 0, 0, $arSize['x'], $arSize['y'], $arSize['width'], $arSize['height'], $arSize['width'], $arSize['height']);
					}
				} else {
					$picture = ImageCreate($arDestinationSize['width'], $arDestinationSize['height']);
					imagecopyresized($picture, $sourceImage, 0, 0, $arSize['x'], $arSize['y'], $arSize['width'], $arSize['height'], $arSize['width'], $arSize['height']);
				}

				if (is_array($arFilters)) {
					foreach ($arFilters as $arFilter) {
						CFile::ApplyImageFilter($picture, $arFilter, $bHasAlpha);
					}
				}

				if (is_array($arWaterMark)) {
					$arWaterMark['name'] = 'watermark';
					CFile::ApplyImageFilter($picture, $arWaterMark, $bHasAlpha);
				}

				if ($io->FileExists($destinationFile)) {
					$io->Delete($destinationFile);
				}
				switch ($arSourceFileSizeTmp[2]) {
					case IMAGETYPE_GIF:
						imagegif($picture, $io->GetPhysicalName($destinationFile));
						break;
					case IMAGETYPE_PNG:
						imagealphablending($picture, false);
						imagesavealpha($picture, true);
						imagepng($picture, $io->GetPhysicalName($destinationFile));
						break;
					default:
						if ($arSourceFileSizeTmp[2] == IMAGETYPE_BMP)
							$destinationFile .= '.jpg';
						if ($jpgQuality === false)
							$jpgQuality = intval(COption::GetOptionString('main', 'image_resize_quality', '95'));
						if ($jpgQuality <= 0 || $jpgQuality > 100)
							$jpgQuality = 95;
						imagejpeg($picture, $io->GetPhysicalName($destinationFile), $jpgQuality);
						break;
				}
				imagedestroy($picture);
			}
			return true;
		}
		return false;
	}

	public static function OnEndBufferContent(&$content) {
		if (defined('ADMIN_SECTION') && true == ADMIN_SECTION) {
			if (($GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/iblock_element_edit.php' ||
					$GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/cat_product_edit.php' ||
					$GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/iblock_section_edit.php' ||
					$GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/cat_section_edit.php'
					) &&
					$_REQUEST['ID'] > 0 && $_REQUEST['IBLOCK_ID'] > 0 &&
					!strlen($_REQUEST['savebtn'])
			) {
				include_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/asd.cropphoto/classes/general/helper.php';
				if (strpos($content, '/bitrix/js/fileman/core_file_input.js') !== false) {
					$content = preg_replace('#(<script type="text/javascript" src="/bitrix/js/fileman/core_file_input\.js\?[\d]+"></script>)#is', '\\1' . CASDcropphotoHelper::GetJSIB(), $content);
				}
			}
		}
	}

}

$arJSAsdCropConfig = array(
	'asd_crop_jcrop' => array(
		'js' => '/bitrix/js/asd.cropphoto/jquery.Jcrop.min.js',
		'css' => '/bitrix/js/asd.cropphoto/css/jquery.Jcrop.min.css',
		'rel' => array('jquery'),
	),
	'asd_crop_core' => array(
		'js' => '/bitrix/js/asd.cropphoto/script.js',
		'rel' => array('asd_crop_jcrop', 'jquery')
	),
);
foreach ($arJSAsdCropConfig as $ext => $arExt) {
	CJSCore::RegisterExt($ext, $arExt);
}
?>