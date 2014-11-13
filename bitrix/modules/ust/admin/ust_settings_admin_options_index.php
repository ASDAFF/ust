<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle(GetMessage("UST_TITLE"));
$APPLICATION->AddHeadString('<link rel="stylesheet" type="text/css" href="/bitrix/modules/ust/ust_settings_options.css" />');
$options = array(
    "ust_banners_speed", 
    "ust_banners_timeout", 
    "ust_print_link", 
    "show_contacts_print",
    "CATALOG_order", 
    "CATALOG_question", 
    "CATALOG_kredit", 
    "CATALOG_arenda", 
    "CATALOG_used", 
    "CATALOG_where_buy", 
    "CATALOG_service_centers", 
    "catalog_filter_count_brand", 
    "catalog_detail_count_chars",
    "catalog_detail_you_interested",
    "catalog_detail_spare_parts", 
    "catalog_detail_you_looked"
);

//если опции не установлены, устанавливаем их первоначально все в 1
foreach($options as $option)
    if(COption::GetOptionInt("ust", $option) === false)
        COption::SetOptionInt("ust", $option, 1);
        
//если пользователь сохранил форму    
if(!empty($_POST)) {
    foreach($options as $opt) {
        if(isset($_POST[$opt]) && $_POST[$opt] > 0)
            COption::SetOptionInt("ust", $opt, $_POST[$opt]);
        else
            COption::SetOptionInt("ust", $opt, 0);
    }
    
    if(!empty($_POST["INTRODUCTION"]) && !empty($_POST["INTRODUCTION_TYPE"])) {
        COption::SetOptionString("ust", "SURVEY_INTRODUCTION", $_POST["INTRODUCTION"]);
        COption::SetOptionString("ust", "SURVEY_INTRODUCTION_TYPE", $_POST["INTRODUCTION_TYPE"]);
    }
    if(!empty($_POST["GRATITUDE"]) && !empty($_POST["GRATITUDE_TYPE"])) {
        COption::SetOptionString("ust", "SURVEY_GRATITUDE", $_POST["GRATITUDE"]);
        COption::SetOptionString("ust", "SURVEY_GRATITUDE_TYPE", $_POST["GRATITUDE_TYPE"]);
    }
    if(!empty($_POST["CATALOG_DETAIL_SPARE_PARTS_CONTENT"]) && !empty($_POST["CATALOG_DETAIL_SPARE_PARTS_CONTENT_TYPE"])) {
        COption::SetOptionString("ust", "CATALOG_DETAIL_SPARE_PARTS_CONTENT", $_POST["CATALOG_DETAIL_SPARE_PARTS_CONTENT"]);
        COption::SetOptionString("ust", "CATALOG_DETAIL_SPARE_PARTS_CONTENT_TYPE", $_POST["CATALOG_DETAIL_SPARE_PARTS_CONTENT_TYPE"]);
    }
}
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");?>
<form method="POST" name="administrator_settings" class="ust_administrator_settings" enctype="multipart/form-data">
    <?=bitrix_sessid_post()?>
    <input type="hidden" name="lang" value="<?=LANG?>">
    <?if($error):?>
        <div style="color: red; font-weight: bold; margin-bottom: 20px;">Ошибка! Не все поля заполнены. Новые данные не сохранены.</div>
    <?elseif($_POST):?>
        <div style="color: green; font-weight: bold; margin-bottom: 20px;">Сохранение прошло успешно.</div>
    <?endif?>
    <table width="100%">
        <tr><td colspan="2" class="heading"><?=GetMessage("BANNERS")?></td></tr>
        <tr>
            <td class="cell-r"><?=GetMessage("BANNERS_SPEED")?> </td>
            <td><input type="text" name="ust_banners_speed" value="<?=COption::GetOptionInt("ust", "ust_banners_speed");?>" /></td>
        </tr>
        <tr>
            <td class="cell-r"><?=GetMessage("BANNERS_TIMEOUT")?> </td>
            <td><input type="text" name="ust_banners_timeout" value="<?=COption::GetOptionInt("ust", "ust_banners_timeout");?>" /></td>
        </tr>
        <tr><td colspan="2" class="heading"><?=GetMessage("VERSION_PRINT")?></td></tr>
        <tr>
            <td class="cell-r"><?=GetMessage("SHOW_PRINT_CONTACTS")?></td>
            <td><input type="checkbox" name="ust_print_link" <?if(COption::GetOptionInt("ust", "ust_print_link") == 1):?> checked="checked" <?endif;?> value="1" /></td>
        </tr>
        <tr><td colspan="2" class="heading"><?=GetMessage("CATALOG_SETTINGS")?></td></tr>
        <tr>
            <td class="cell-r"><?=GetMessage("CATALOG_order")?> </td>
            <td><input type="checkbox" name="CATALOG_order" <?if(COption::GetOptionInt("ust", "CATALOG_order") == 1):?> checked="checked" <?endif;?> value="1" /></td>
        </tr>
        <tr>
            <td class="cell-r"><?=GetMessage("CATALOG_question")?> </td>
            <td><input type="checkbox" name="CATALOG_question" <?if(COption::GetOptionInt("ust", "CATALOG_question") == 1):?> checked="checked" <?endif;?> value="1" /></td>
        </tr>
        <tr>
            <td class="cell-r"><?=GetMessage("CATALOG_kredit")?> </td>
            <td><input type="checkbox" name="CATALOG_kredit" <?if(COption::GetOptionInt("ust", "CATALOG_kredit") == 1):?> checked="checked" <?endif;?> value="1" /></td>
        </tr>
        <tr>
            <td class="cell-r"><?=GetMessage("CATALOG_arenda")?> </td>
            <td><input type="checkbox" name="CATALOG_arenda" <?if(COption::GetOptionInt("ust", "CATALOG_arenda") == 1):?> checked="checked" <?endif;?> value="1" /></td>
        </tr>
        <tr>
            <td class="cell-r"><?=GetMessage("CATALOG_used")?> </td>
            <td><input type="checkbox" name="CATALOG_used" <?if(COption::GetOptionInt("ust", "CATALOG_used") == 1):?> checked="checked" <?endif;?> value="1" /></td>
        </tr>
        <tr>
            <td class="cell-r"><?=GetMessage("CATALOG_where_buy")?> </td>
            <td><input type="checkbox" name="CATALOG_where_buy" <?if(COption::GetOptionInt("ust", "CATALOG_where_buy") == 1):?> checked="checked" <?endif;?> value="1" /></td>
        </tr>
        <tr>
            <td class="cell-r"><?=GetMessage("CATALOG_service_centers")?> </td>
            <td><input type="checkbox" name="CATALOG_service_centers" <?if(COption::GetOptionInt("ust", "CATALOG_service_centers") == 1):?> checked="checked" <?endif;?> value="1" /></td>
        </tr>
        <tr><td colspan="2" class="heading"><?=GetMessage("SURVEY")?>: <?=GetMessage("EDIT_VOTE_SETTINGS")?></td></tr>
        <tr><td colspan="2">
            <?CFileMan::AddHTMLEditorFrame(
                "INTRODUCTION",
                COption::GetOptionString("ust", "SURVEY_INTRODUCTION"),
                "INTRODUCTION_TYPE",
                COption::GetOptionString("ust", "SURVEY_INTRODUCTION_TYPE"),
                array(
                    'height' => '450',
                    'width' => '100%'
                ),
                "N", 0, "", "", false, false, array("BXPropertiesTaskbar"), array()
            );?>
        </td></tr>
        <tr><td colspan="2" class="heading"><?=GetMessage("SURVEY")?>: <?=GetMessage("EDIT_TEXT_OF_GRATITYDE")?></td></tr>
        <tr><td colspan="2">
            <?CFileMan::AddHTMLEditorFrame(
                "GRATITUDE",
                COption::GetOptionString("ust", "SURVEY_GRATITUDE"),
                "GRATITUDE_TYPE",
                COption::GetOptionString("ust", "SURVEY_GRATITUDE_TYPE"),
                array(
                    'height' => '450',
                    'width' => '100%'
                ),
                "N", 0, "", "", false, false, array("BXPropertiesTaskbar"), array()
            );?>
            
        </td></tr>
        <tr><td colspan="2" class="heading"><?=GetMessage("FILTER")?></td></tr>
        <tr>
            <td class="cell-r"><?=GetMessage("FILTER_BRAND_COUNT")?> </td>
            <td><input type="text" name="catalog_filter_count_brand" value="<?=COption::GetOptionInt("ust", "catalog_filter_count_brand");?>" /></td>
        </tr>
        <tr><td colspan="2" class="heading"><?=GetMessage("CATALOG_DETAIL")?></td></tr>
        <tr>
            <td class="cell-r"><?=GetMessage("CATALOG_DETAIL_COUNT_CHARS")?> </td>
            <td><input type="text" name="catalog_detail_count_chars" value="<?=COption::GetOptionInt("ust", "catalog_detail_count_chars");?>" /></td>
        </tr>
        <tr>
            <td class="cell-r"><?=GetMessage("CATALOG_DETAIL_YOU_LOOKED")?> </td>
            <td><input type="checkbox" name="catalog_detail_you_looked" <?if(COption::GetOptionInt("ust", "catalog_detail_you_looked") == 1):?> checked="checked" <?endif;?> value="1" /></td>
        </tr>
        <tr>
            <td class="cell-r"><?=GetMessage("CATALOG_DETAIL_SPARE_PARTS")?> </td>
            <td><input type="checkbox" name="catalog_detail_spare_parts" <?if(COption::GetOptionInt("ust", "catalog_detail_spare_parts") == 1):?> checked="checked" <?endif;?> value="1" /></td>
        </tr>
        <tr>
            <td class="cell-r"><?=GetMessage("CATALOG_DETAIL_YOU_INTERESTED")?> </td>
            <td><input type="checkbox" name="catalog_detail_you_interested" <?if(COption::GetOptionInt("ust", "catalog_detail_you_interested") == 1):?> checked="checked" <?endif;?> value="1" /></td>
        </tr>
        <tr><td colspan="2" class="heading"><?=GetMessage("CATALOG_DETAIL_SPARE_PARTS_CONTENT")?></td></tr>
        <tr><td colspan="2">
            <?CFileMan::AddHTMLEditorFrame(
                "CATALOG_DETAIL_SPARE_PARTS_CONTENT",
                COption::GetOptionString("ust", "CATALOG_DETAIL_SPARE_PARTS_CONTENT"),
                "CATALOG_DETAIL_SPARE_PARTS_CONTENT_TYPE",
                COption::GetOptionString("ust", "CATALOG_DETAIL_SPARE_PARTS_CONTENT_TYPE"),
                array(
                    'height' => '450',
                    'width' => '100%'
                ),
                "N", 0, "", "", false, false, array("BXPropertiesTaskbar"), array()
            );?>
            
        </td></tr>
        <tr><td colspan="2"><button type="submit">Сохранить</button></td></tr>
    </table>
</form>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>