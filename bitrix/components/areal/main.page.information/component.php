<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(CModule::IncludeModule("iblock")) {
	$cache = new CPHPCache();
	$cache_time = 3600;
	$arResult = array();
	$cache_dir_id = 'main_info_iblocks';
	$cache_dir_path = '/main_info_iblocks/';
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_dir_id, $cache_dir_path)) {
	   $res = $cache->GetVars();
	   if (is_array($res["main_info_iblocks"]) && (count($res["main_info_iblocks"]) > 0))
		  $arResult = array_merge($arResult, $res["main_info_iblocks"]);
	}
	else {
		/* Новости */
		$news = CIBlockElement::GetList(array("DATE_ACTIVE_FROM" => "DESC", "SORT" => "ASC", "CREATED" => "ASC"), array("IBLOCK_ID" => NEWS, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y"), false, array("nTopCount" => 4), array("ID", "DETAIL_PICTURE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "NAME", "DETAIL_PAGE_URL", "DATE_CREATE", "DATE_ACTIVE_FROM"));
		while($new = $news->GetNext())
			$arResult["NEWS"][] = array(
				"ID" => $new["ID"],
				"NAME" => $new["NAME"],
				"PREVIEW_PICTURE" =>  CFile::ResizeImageGet( 
					$new["DETAIL_PICTURE"], 
					array("width" => 111, "height" => 98), 
					BX_RESIZE_IMAGE_EXACT,
					true 
				),
				"DATE_CREATE" => (!empty($new["DATE_ACTIVE_FROM"])) ? CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($new["DATE_ACTIVE_FROM"], CSite::GetDateFormat())) : CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($new["DATE_CREATE"], CSite::GetDateFormat())),
				"PREVIEW_TEXT" => $new["PREVIEW_TEXT"],
				"PREVIEW_TEXT_TYPE" => $new["PREVIEW_TEXT_TYPE"],
				"DETAIL_PAGE_URL" => $new["DETAIL_PAGE_URL"]
			);
		
		/* Отзывы */	
		$reviews = CIBlockElement::GetList(array("DATE_ACTIVE_FROM" => "DESC", "SORT" => "ASC", "CREATED" => "ASC"), array("IBLOCK_ID" => REVIEWS, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y"), false, array("nTopCount" => 4), array("ID", "NAME", "DATE_ACTIVE_FROM", "DATE_CREATE", "DETAIL_PICTURE", "PREVIEW_PICTURE"));
		while($review = $reviews->GetNext())
			$arResult["REVIEWS"][] = array(
				"ID" => $review["ID"],
				"NAME" => $review["NAME"],
				"SMALL_PICTURE" =>  CFile::ResizeImageGet( 
					$review["DETAIL_PICTURE"], 
					array("width" => 111, "height" => 98), 
					BX_RESIZE_IMAGE_EXACT
				),
				"BIG_PICTURE" =>  CFile::GetPath($review["PREVIEW_PICTURE"]),
				"DATE_CREATE" => (!empty($review["DATE_ACTIVE_FROM"])) ? CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($review["DATE_ACTIVE_FROM"], CSite::GetDateFormat())) : CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($review["DATE_CREATE"], CSite::GetDateFormat())),
			);
		/* Статьи */
		$articles = CIBlockElement::GetList(array("DATE_ACTIVE_FROM" => "DESC", "SORT" => "ASC", "CREATED" => "ASC"), array("IBLOCK_ID" => ARTICLES, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y"), false, array("nTopCount" => 4), array("ID", "DETAIL_PICTURE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "NAME", "DETAIL_PAGE_URL", "DATE_ACTIVE_FROM", "DATE_CREATE"));
		while($article = $articles->GetNext())
			$arResult["ARTICLES"][] = array(
				"ID" => $article["ID"],
				"NAME" => $article["NAME"],
				"PREVIEW_PICTURE" =>  CFile::ResizeImageGet( 
					$article["DETAIL_PICTURE"], 
					array("width" => 111, "height" => 98), 
					BX_RESIZE_IMAGE_EXACT,
					true 
				),
				"PREVIEW_TEXT" => $article["PREVIEW_TEXT"],
				"PREVIEW_TEXT_TYPE" => $article["PREVIEW_TEXT_TYPE"],
				"DETAIL_PAGE_URL" => $article["DETAIL_PAGE_URL"],
				"DATE_CREATE" => (!empty($article["DATE_ACTIVE_FROM"])) ? CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($article["DATE_ACTIVE_FROM"], CSite::GetDateFormat())) : CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($article["DATE_CREATE"], CSite::GetDateFormat())),
			);
		
		/* Сертификаты */
		$certificates = CIBlockElement::GetList(array("SORT" => "ASC", "ID" => "ASC"), array("IBLOCK_ID" => CERTIFICATES, "ACTIVE" => "Y", "ACTIVE_DATE" => "Y"), false, array("nTopCount" => 4), array("ID", "DETAIL_PICTURE", "NAME", "DATE_CREATE", "PREVIEW_PICTURE"));
		while($certificate = $certificates->GetNext())
			$arResult["CERTIFICATES"][] = array(
				"NAME" => $certificate["NAME"],
				"DATE_CREATE" => CIBlockFormatProperties::DateFormat("j F Y", MakeTimeStamp($certificate["DATE_CREATE"], CSite::GetDateFormat())),
				"BIG_PICTURE" =>  CFile::GetPath($certificate["PREVIEW_PICTURE"]),
				"SMALL_PICTURE" =>  CFile::ResizeImageGet( 
					$certificate["DETAIL_PICTURE"], 
					array("width" => 111, "height" => 98), 
					BX_RESIZE_IMAGE_EXACT,
					true 
				)
			);
		if ($cache_time > 0) {
			$cache->StartDataCache($cache_time, $cache_dir_id, $cache_dir_path);
			$cache->EndDataCache(array("main_info_iblocks" => $arResult));
		}
	}
	
	$this->IncludeComponentTemplate();
}
?>