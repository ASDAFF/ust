<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

CModule::IncludeModule('iblock');
$obCache= new CPHPCache();

$cacheLifetime =0;
$cacheID = 'phonesReferer';
$cachePath = '/' . $cacheID;
if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath))
{
    $vars = $obCache->GetVars();
    $arResult = $vars['phonesReferer'];
}
elseif ($obCache->StartDataCache())
{
    $idblock = $arParams["ID_BLOCK"];
  
        $arSelect = Array("ID", "NAME", "DETAIL_TEXT", "PROPERTY_HTTP_REFERER", "PROPERTY_PHONE");
        $arFilter = Array("IBLOCK_ID" => $idblock, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 1000), $arSelect); // nPageSize - количество элементов на 1 странице
        while ($ob = $res->GetNextElement())
        {  
            $arResult[] = $ob->GetFields();   
        }
      $obCache->EndDataCache(array('phonesReferer' => $arResult));
}
 $this->IncludeComponentTemplate();/**/
?>