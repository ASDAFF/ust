<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!--meta property="og:image" content="<?="http://ust-co.ru/".$arResult["PREVIEW_PICTURE"]["SRC"]?>" />
<meta property="og:title" content="" />
<meta property="og:description" content="Результат теста: Дракон почти Ваш «конек»! Вы пока не можете преподавать Драконоведение, но на верном пути!" /-->
<?
/*
//seo code
//убирайте за собой мусор=)
print "<pre news_detail style='display:none'>"; 
print_r($arResult);
print "</pre>"; 
*/
// GLOBAL $APPLICATION;
// // $APPLICATION->SetPageProperty("description", $arResult["PREVIEW_TEXT"]);
// $APPLICATION->AddHeadString("<meta property=\"og:description\" content=\"".trim(htmlspecialchars_decode(strip_tags($arResult["PREVIEW_TEXT"])))."\" />");
// $APPLICATION->AddHeadString("<meta property=\"og:image\" content=\"http://ust-co.ru/".$arResult["PREVIEW_PICTURE"]["SRC"]."\" />");
// $APPLICATION->AddHeadString("<meta property=\"og:title\" content=\"\" />");
// $APPLICATION->AddHeadString("<meta property=\"og:url\" content=\"".$arResult["DETAIL_PAGE_URL"]."\" />");
?>
<div class="news-preview" style="display:none;"><?=$arResult["PREVIEW_TEXT"]?></div>
<div class="news-detail">
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	<div class="clear"></div>
</div>
