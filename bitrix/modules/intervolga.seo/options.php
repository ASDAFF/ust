<?
$module_id = "intervolga.seo";
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT>="R"):

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "subscribe_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
	array("DIV" => "edit2", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "subscribe_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$arAllOptions = array(//Набор параметров
    GetMessage("opt_www_redirect_title"),
	array("www_redirect",  GetMessage("opt_www_redirect"), array("checkbox", "Y", 'onclick="this.form.www_redirect_opt.disabled = !this.checked;"')),
	array("www_redirect_opt", GetMessage("opt_www_redirect_opt"),
        array("selectbox",
            array( "w" => GetMessage("opt_www_redirect_opt_w"), "wo" => GetMessage("opt_www_redirect_opt_wo"))
        )
	),
    GetMessage("opt_smart_pagenav_title"),
	array("smart_pagenav",  GetMessage("opt_smart_pagenav"), array("checkbox", "Y", 'onclick="this.form.smart_pagenav_template.disabled = this.form.smart_pagenav_all_template.disabled = this.form.smart_pagenav_excludes.disabled = !this.checked;"')),
	array("smart_pagenav_template", GetMessage("opt_smart_pagenav_template"), array("text", "30")),
	array("smart_pagenav_all_template", GetMessage("opt_smart_pagenav_all_template"), array("text", "30")),
	array("smart_pagenav_excludes", GetMessage("opt_smart_pagenav_excludes"), array("textarea", 5, 30)),
);

if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && $POST_RIGHT=="W" && check_bitrix_sessid())
{
	if(strlen($RestoreDefaults)>0)
	{
		COption::RemoveOption($module_id);
		$z = CGroup::GetList($v1="id",$v2="asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
		while($zr = $z->Fetch())
			$APPLICATION->DelGroupRight($module_id, array($zr["ID"]));
	}
	else
	{
		foreach($arAllOptions as $arOption)
		{
            if(is_array($arOption))
            {
                $val=false;
                $name = $arOption[0];
                if($$name)
                {
                    if($arOption[2][0]=="text-list")
                    {
                        $val = "";
                        for($j=0; $j<count($$name); $j++)
                        {
                            if(strlen(trim(${$name}[$j])) > 0)
                                $val .= ($val <> ""? ",":"").trim(${$name}[$j]);
                        }
                    }
                    elseif($arOption[2][0]=="textarea" && $name == "smart_pagenav_excludes")
                    {
                        //Очистить от пустых строк
                        $excludes = explode("\n", $$name);
                        $clearExcludes = array();
                        foreach($excludes as $exclude)
                        {
                            if(trim($exclude))
                                $clearExcludes[] = trim($exclude);
                        }
                        $val = implode("\n",$clearExcludes);
                    }
                    else
                        $val=$$name;
                }
                if($arOption[2][0] == "checkbox")
                {
                    if($$name <> "Y")
                        $val="N";
                    else
                        $val="Y";
                }

                if($val)
                    COption::SetOptionString($module_id, $name, $val);
            }
		}
	}

	$Update = $Update.$Apply;
	ob_start();
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
	ob_end_clean();

	if(strlen($_REQUEST["back_url_settings"]) > 0)
	{
		if((strlen($Apply) > 0) || (strlen($RestoreDefaults) > 0))
			LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
		else
			LocalRedirect($_REQUEST["back_url_settings"]);
	}
	else
	{
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&".$tabControl->ActiveTabParam());
	}
}

?>
<form name="main_options" method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
<?
$tabControl->Begin();
$tabControl->BeginNextTab();

foreach($arAllOptions as $Option):
    if(is_array($Option))
    {
$type = $Option[2];
$val = COption::GetOptionString($module_id, $Option[0]);
?>
    <tr>
        <td width="40%" <?if($type[0]=="textarea" || $type[0]=="text-list") echo 'class="adm-detail-valign-top"'?>>
            <label for="<?echo htmlspecialcharsbx($Option[0])?>"><?echo $Option[1]?></label>
        <td width="60%">
            <?
            if($type[0]=="checkbox"):
                ?><input type="checkbox" name="<?echo htmlspecialcharsbx($Option[0])?>" id="<?echo htmlspecialcharsbx($Option[0])?>" value="Y"<?if($val=="Y")echo" checked";?><?if($type[2]):?> <?=$type[2];?><?endif;?>><?
            elseif($type[0]=="text"):
                ?><input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($Option[0])?>"><?
            elseif($type[0]=="textarea"):
                ?><textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($Option[0])?>"><?echo htmlspecialcharsbx($val)?></textarea><?
            elseif($type[0]=="text-list"):
                $aVal = explode(",", $val);
                for($j=0; $j<count($aVal); $j++):
                    ?><input type="text" size="<?echo $type[2]?>" value="<?echo htmlspecialcharsbx($aVal[$j])?>" name="<?echo htmlspecialcharsbx($Option[0])."[]"?>"><br><?
                endfor;
                for($j=0; $j<$type[1]; $j++):
                    ?><input type="text" size="<?echo $type[2]?>" value="" name="<?echo htmlspecialcharsbx($Option[0])."[]"?>"><br><?
                endfor;
            elseif($type[0]=="selectbox"):
                $arr = $type[1];
                $arr_keys = array_keys($arr);
                ?><select name="<?echo htmlspecialcharsbx($Option[0])?>"><?
                for($j=0; $j<count($arr_keys); $j++):
                    ?><option value="<?echo $arr_keys[$j]?>"<?if($val==$arr_keys[$j])echo" selected"?>><?echo htmlspecialcharsbx($arr[$arr_keys[$j]])?></option><?
                endfor;
                ?></select><?
            endif;
            ?></td>
    </tr>
    <?
    }
    else
    {
    ?>
        <tr class="heading">
            <td colspan="2"><b><?=$Option?></b></td>
        </tr>
<?
    }
endforeach;?>

<?$tabControl->BeginNextTab();?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>

<?$tabControl->Buttons();?>
    <script type="text/javascript">

        BX.ready(function(){
            var f = document.forms['main_options'];
            if(f.www_redirect)
                f.www_redirect_opt.disabled = !f.www_redirect.checked;

            if(f.smart_pagenav)
                f.smart_pagenav_template.disabled = f.smart_pagenav_all_template.disabled = f.smart_pagenav_excludes.disabled = !f.smart_pagenav.checked;
        });

    </script>

	<input <?if ($POST_RIGHT<"W") echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
	<input <?if ($POST_RIGHT<"W") echo "disabled" ?> type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
	<?if(strlen($_REQUEST["back_url_settings"])>0):?>
		<input <?if ($POST_RIGHT<"W") echo "disabled" ?> type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
	<?endif?>
	<input <?if ($POST_RIGHT<"W") echo "disabled" ?> type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="return confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
	<?=bitrix_sessid_post();?>
<?$tabControl->End();?>
</form>

<?=BeginNote();?>
	<?=GetMessage("opt_note_www_redirect_opt")?>
	<br/>
	<?=GetMessage("opt_note_smart_pagenav_template")?>
<?=EndNote();?>

<?endif;?>
