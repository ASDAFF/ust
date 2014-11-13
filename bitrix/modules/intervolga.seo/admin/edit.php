<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intervolga.seo/include.php");

IncludeModuleLangFile(__FILE__);

//$POST_RIGHT="W"; //workaround
$POST_RIGHT = $APPLICATION->GetGroupRight("intervolga.seo");
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("ivseo_tab_element"), "ICON"=>"main_user_edit", "TITLE"=>GetMessage("ivseo_tab_rubric_title")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$ID = intval($ID);		// Id of the edited record
$message = null;
$bVarsFromForm = false;
if($REQUEST_METHOD == "POST" && ($save!="" || $apply!="") && $POST_RIGHT=="W" && check_bitrix_sessid())
{
	$rubric = new CIvSeo;
	$arFields = Array(
		"ACTIVE"	=> ($ACTIVE <> "Y"? "N":"Y"),

        "CANONICAL"	=> ($CANONICAL <> "Y"? "N":"Y"),

        "LID" => $LID,

        "URL" => $URL,
        "H1"	=> $H1,

        "TITLE"	=> $TITLE,
        "KEYWORDS"	=> $KEYWORDS,
        "DESCRIPTION"	=> $DESCRIPTION,

        "TEXT1"	=> $TEXT1,
        "TEXT2"	=> $TEXT2,
        "TEXT3"	=> $TEXT3,
	);

	if($ID > 0)
	{
		$res = $rubric->Update($ID, $arFields);
	}
	else
	{
		$ID = $rubric->Add($arFields);
		$res = ($ID>0);
	}

	if($res)
	{
		if($apply!="")
			LocalRedirect("/bitrix/admin/intervolga.seo_edit.php?ID=".$ID."&mess=ok&lang=".LANG."&".$tabControl->ActiveTabParam());
		else
			LocalRedirect("/bitrix/admin/intervolga.seo_list.php?lang=".LANG);
	}
	else
	{
		if($e = $APPLICATION->GetException())
			$message = new CAdminMessage(GetMessage("ivseo_save_error"), $e);
		$bVarsFromForm = true;
	}

}

//Edit/Add part
ClearVars();
$str_ACTIVE = "Y";
if($page)
    $str_URL = $page;//TODO: ѕолучение из url при переходе из публички
if($SITE_ID)
    $str_LID = $SITE_ID;//TODO: ѕолучение SITE_ID при переходе из публички

if($ID>0)
{
	$seo = CIvSeo::GetByID($ID);
	if(!$seo->ExtractFields("str_"))
		$ID=0;
}

if($bVarsFromForm)
	$DB->InitTableVarsForEdit("iv_seo", "", "str_");

$APPLICATION->SetTitle(($ID>0? GetMessage("ivseo_title_edit").$ID : GetMessage("ivseo_title_add")));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$aMenu = array(
	array(
		"TEXT"=>GetMessage("ivseo_list"),
		"TITLE"=>GetMessage("ivseo_list_title"),
		"LINK"=>"intervolga.seo_list.php?lang=".LANG,
		"ICON"=>"btn_list",
	)
);

if($ID > 0 && !$bCopy)
{
    $aMenu[] = array("SEPARATOR"=>"Y");
    $aMenu[] = array(
        "TEXT"=>GetMessage("MAIN_ADD"),
        "LINK"=>"intervolga.seo_edit.php?lang=".LANG,
        "ICON"=>"btn_new",
        "TITLE"=>GetMessage("POST_ADD_TITLE"),
    );


    if ($POST_RIGHT>="W")
        $aMenu[] = array(
            "ICON"=>"delete",
            "TEXT"=>GetMessage("ivseo_delete"),
            "LINK"=>"javascript:if(confirm('".GetMessageJS('ivseo_del_conf')."'))window.location='".CUtil::JSEscape('intervolga.seo_list.php?action=delete&ID[]='.$ID.'&lang='.LANG.'&'.bitrix_sessid_get())."';",
        );

}

$context = new CAdminContextMenu($aMenu);
$context->Show();
?>

<?
if($_REQUEST["mess"] == "ok" && $ID>0)
	CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("ivseo_saved"), "TYPE"=>"OK"));

if($message)
	echo $message->Show();
elseif($rubric->LAST_ERROR!="")
	CAdminMessage::ShowMessage($rubric->LAST_ERROR);
?>

<form method="POST" Action="<?echo $APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="post_form">
<?
$tabControl->Begin();
?>
<?
//********************
//Rubric
//********************
$tabControl->BeginNextTab();
?>
	<tr>
		<td width="40%"><?echo GetMessage("ivseo_act")?></td>
		<td width="60%"><input type="checkbox" name="ACTIVE" value="Y"<?if($str_ACTIVE == "Y") echo " checked"?>></td>
	</tr>
    <tr>
        <td width="40%"><?echo GetMessage("ivseo_CANONICAL")?></td>
        <td width="60%"><input type="checkbox" name="CANONICAL" value="Y"<?if($str_CANONICAL == "Y") echo " checked"?>></td>
    </tr>
	<tr class="adm-detail-required-field">
		<td><?echo GetMessage("ivseo_site")?></td>
		<td><?echo CLang::SelectBox("LID", $str_LID);?></td>
	</tr>
	<tr class="adm-detail-required-field">
		<td><?echo GetMessage("ivseo_url")?></td>
		<td><input type="text" name="URL" value="<?echo $str_URL;?>" size="45" maxlength="100"></td>
	</tr>
	<tr>
		<td><?echo GetMessage("ivseo_h1")?></td>
		<td><input type="text" name="H1" value="<?echo $str_H1;?>" size="45"></td>
	</tr>
    <tr>
        <td><?echo GetMessage("ivseo_title")?></td>
        <td><input type="text" name="TITLE" value="<?echo $str_TITLE;?>" size="45"></td>
    </tr>
    <tr>
        <td><?echo GetMessage("ivseo_keywords")?></td>
        <td><input type="text" name="KEYWORDS" value="<?echo $str_KEYWORDS;?>" size="45"></td>
    </tr>
    <tr>
        <td><?echo GetMessage("ivseo_description")?></td>
        <td><input type="text" name="DESCRIPTION" value="<?echo $str_DESCRIPTION;?>" size="45"></td>
    </tr>
    <tr>
        <td colspan="2">
            <div align="center">
                <strong><?echo GetMessage("ivseo_text1")?><br><small>&lt;!--seo_text1--&gt;</small></strong>
            </div>
        </td>
    </tr>
    <tr id="tr_PREVIEW_TEXT_EDITOR">
        <td colspan="2" align="center">
            <?if(CModule::IncludeModule("fileman")):?>
            <?CFileMan::AddHTMLEditorFrame(
                "TEXT1",
                $str_TEXT1,
                false,
                false,
                array(
                    'height' => '200',
                    'width' => '100%'
                ),
                "N",
                0,
                "",
                "",
                $str_LID ? $str_LID: SITE_ID,
                true,
                false,
                array(
                )
            );?>
            <?else:?>
                <textarea class="typearea" name="TEXT1" cols="45" rows="5" wrap="VIRTUAL"><?echo $str_TEXT1; ?></textarea>
            <?endif;?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div align="center">
                <strong><?echo GetMessage("ivseo_text2")?><br><small>&lt;!--seo_text2--&gt;</small></strong>
            </div>
        </td>
    </tr>
    <tr id="tr_PREVIEW_TEXT_EDITOR">
        <td colspan="2" align="center">
            <?if(CModule::IncludeModule("fileman")):?>
                <?CFileMan::AddHTMLEditorFrame(
                    "TEXT2",
                    $str_TEXT2,
                    false,
                    false,
                    array(
                        'height' => '200',
                        'width' => '100%'
                    ),
                    "N",
                    0,
                    "",
                    "",
                    $str_LID ? $str_LID: SITE_ID,
                    true,
                    false,
                    array(
                    )
                );?>
            <?else:?>
                <textarea class="typearea" name="TEXT2" cols="45" rows="5" wrap="VIRTUAL"><?echo $str_TEXT2; ?></textarea>
            <?endif;?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div align="center">
                <strong><?echo GetMessage("ivseo_text3")?><br><small>&lt;!--seo_text3--&gt;</small></strong>
            </div>
        </td>
    </tr>
    <tr id="tr_PREVIEW_TEXT_EDITOR">
        <td colspan="2" align="center">
            <?if(CModule::IncludeModule("fileman")):?>
                <?CFileMan::AddHTMLEditorFrame(
                    "TEXT3",
                    $str_TEXT3,
                    false,
                    false,
                    array(
                        'height' => '200',
                        'width' => '100%'
                    ),
                    "N",
                    0,
                    "",
                    "",
                    $str_LID ? $str_LID: SITE_ID,
                    true,
                    false,
                    array(
                    )
                );?>
            <?else:?>
                <textarea class="typearea" name="TEXT3" cols="45" rows="5" wrap="VIRTUAL"><?echo $str_TEXT3; ?></textarea>
            <?endif;?>
        </td>
    </tr>
<?

$tabControl->Buttons(
	array(
		"disabled"=>($POST_RIGHT<"W"),
		"back_url"=>"intervolga.seo_list.php?lang=".LANG,
        "btnSaveAndAdd" => true
	)
);
?>
<?echo bitrix_sessid_post();?>
<input type="hidden" name="lang" value="<?=LANG?>">
<?if($ID>0 && !$bCopy):?>
	<input type="hidden" name="ID" value="<?=$ID?>">
<?endif;?>
<?
$tabControl->End();
?>

<?
$tabControl->ShowWarnings("post_form", $message);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>