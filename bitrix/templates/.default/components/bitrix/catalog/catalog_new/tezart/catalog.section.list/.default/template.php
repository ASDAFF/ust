<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$arViewModeList = array('LINE', 'TEXT', 'TILE');
$arViewStyles = array(
    'LINE' => array(
        'CONT' => 'bx_catalog_line',
        'TITLE' => 'bx_catalog_line_category_title',
        'LIST' => 'bx_catalog_line_ul',
        'EMPTY_IMG' => $this->GetFolder() . '/images/line-empty.png'
    ),
    'TEXT' => array(
        'CONT' => 'bx_catalog_text',
        'TITLE' => 'bx_catalog_text_category_title',
        'LIST' => 'bx_catalog_text_ul'
    ),
    'TILE' => array(
        'CONT' => 'bx_catalog_tile',
        'TITLE' => 'bx_catalog_tile_category_title',
        'LIST' => 'bx_catalog_tile_ul',
        'EMPTY_IMG' => $this->GetFolder() . '/images/tile-empty.png'
    )
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$boolShowDepth = (1 < $arParams['TOP_DEPTH']);
$strDepthSym = '>';



//print "<pre>"; print_r($_SERVER); print "</pre>";


echo '<div class="clear"></div>';
if ($arParams["SECTION_CODE"] == "navesnoe-oborudovanie"):
// шаблон для разделов навеного оборудования
    ?>
<div class="tabs-plate for-catalog">
    <div class="<? echo $arCurView['CONT']; ?> tabs" id="tab_link"><?
        if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID'])
        {
            $this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], CIBlock::GetArrayByID($arResult['SECTION']["IBLOCK_ID"], "SECTION_EDIT"));
            $this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], CIBlock::GetArrayByID($arResult['SECTION']["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
            ?>

            <?
        }
        if (0 < $arResult["SECTIONS_COUNT"])
        {
            ?><ul class="<? echo $arCurView['LIST']; ?> submenu navesnoe-menu"><?
            foreach ($arResult['SECTIONS'] as $arSection)
            {
                $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
                $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));

                //print "<pre>"; print_r($arSection); print "</pre>";

                $arFilterNameGroup = Array('IBLOCK_ID' => $arSection['IBLOCK_ID'], 'GLOBAL_ACTIVE' => 'Y', "ID" => $arSection['ID']);
                $db_listNameGroup = CIBlockSection::GetList(Array(), $arFilterNameGroup, true, Array('UF_NAME_GROUP'));
                if ($ar_resultNameGroup = $db_listNameGroup->GetNext())
                {
                    // print '<div class="text_top">'.$ar_resultSeo["~UF_SEOSEACH"].'</div>';
                    if ($ar_resultNameGroup["~UF_NAME_GROUP"] != "")
                        $arSection['NAME'] = $ar_resultNameGroup["~UF_NAME_GROUP"];
                }
                if (isset($name_group))
                    if (false === $arSection['PICTURE'])
                        $arSection['PICTURE'] = array(
                            'SRC' => $arCurView['EMPTY_IMG']
                        );
                ?><li <? if (strpos($_SERVER["REQUEST_URI"],$arSection['SECTION_PAGE_URL'])!==false): ?>class="active_li"<? endif; ?>  id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
                        <h2 class="bx_catalog_line_title"><? echo str_repeat($strDepthSym, $arSection['RELATIVE_DEPTH']);
                ?><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
                            if ($arParams["COUNT_ELEMENTS"])
                            {
                                ?>(<? echo $arSection['ELEMENT_CNT']; ?>)<?
                            }
                            ?></h2><?
                        if ('' != $arSection['DESCRIPTION'])
                        {
                            ?><p class="bx_catalog_line_description"><? echo $arSection['DESCRIPTION']; ?></p><?
                            }
                            ?><div style="clear: both;"></div>
                    </li><?
                }
                unset($arSection);
                ?>
                <a id="selected" href=""></a>
                </ul><div class="clear"></div><?
                echo ('LINE' != $arParams['VIEW_MODE'] ? '<div style="clear: both;"></div>' : '');
            }
            ?></div></div>
            <? else: ?>
            <?php
            // шаблон для разделов строительной техники
            
           
            ?>

    <div class="<? echo $arCurView['CONT']; ?> elements "><?
        if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID'])
        {
            $this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], CIBlock::GetArrayByID($arResult['SECTION']["IBLOCK_ID"], "SECTION_EDIT"));
            $this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], CIBlock::GetArrayByID($arResult['SECTION']["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
                ?>
            <?
        }




        if (0 < $arResult["SECTIONS_COUNT"])
        {
             
            ?><ul class="<? echo $arCurView['LIST']; ?> submenu_list"><?
            $k = 0;
            foreach ($arResult['SECTIONS'] as $arSection)
            {
                $k++;
                if ($k % 5 == 0)
                    $last_col = 'class="last_col"';
                else $last_col="";
                
                $arFilter1 = Array('IBLOCK_ID' => $arSection['IBLOCK_ID'], 'ID' => $arSection["ID"], 'GLOBAL_ACTIVE' => 'Y');
                $db_list = CIBlockSection::GetList(Array("timestamp_x" => "DESC"), $arFilter1, false, Array("UF_IMG_SECTION","UF_SECT_SERIYA"));
                $seriya="";
                if ($uf_value = $db_list->GetNext())
                {
                    $link = $uf_value["UF_IMG_SECTION"];
                    $seriya=$uf_value["UF_SECT_SERIYA"];
                }
                if($seriya!="")
                {
                    $arSection['SECTION_PAGE_URL']=$arSection["LIST_PAGE_URL"].$arParams["SECTION_CODE"]."/".$seriya."/";
                }
                //print $arSection['SECTION_PAGE_URL'];  
               // print "<pre>" ; print_r($arSection); print "</pre>" ; 
                
                $pathImg = CFile::GetPath($link);
                if ($pathImg != NULL)
                {
                    $arSection['PICTURE']['SRC'] = $pathImg;
                }

                $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
                $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));

                if (false === $arSection['PICTURE'])
                    $arSection['PICTURE'] = array(
                        'SRC' => $arCurView['EMPTY_IMG']
                    );
                ?><li <?=$last_col?> id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
                        <a href="<? echo $arSection['SECTION_PAGE_URL']; ?>" class="bx_catalog_line_img" style="background-image: url(<? echo $arSection['PICTURE']['SRC']; ?>);"></a>
                        <h4 class="bx_catalog_line_title "><? echo str_repeat($strDepthSym, $arSection['RELATIVE_DEPTH']);
            ?><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
                            if ($arParams["COUNT_ELEMENTS"])
                            {
                                ?>(<? echo $arSection['ELEMENT_CNT']; ?>)<?
                            }
                            ?></h4><?
                            if ('' != $arSection['DESCRIPTION'])
                            {
                                ?><p class="bx_catalog_line_description"><? echo $arSection['DESCRIPTION']; ?></p><?
                            }
                            ?><div style="clear: both;"></div>
                    </li><?
            }
                        ?></ul><?
                echo ('LINE' != $arParams['VIEW_MODE'] ? '<div style="clear: both;"></div>' : '');
            }
            ?></div>


<? endif; ?>