<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intervolga.seo/include.php");

IncludeModuleLangFile(__FILE__);

//$POST_RIGHT = "W";
$POST_RIGHT = $APPLICATION->GetGroupRight("intervolga.seo");
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$sTableID = "iv_seo";
$oSort = new CAdminSorting($sTableID, "ID", "desc");
$lAdmin = new CAdminList($sTableID, $oSort);

function CheckFilter()
{
    //Пока проверять нечего
	return true;
}

$FilterArr = Array(
    "find_id",
    "find_url",
    "find_lurl",
    "find_active",
    "find_CANONICAL",
    "find_h1",
    "find_description",
    "find_keywords",
    "find_text1",
    "find_text2",
    "find_text3",
    "find_type",
    "find_lid"
);

$lAdmin->InitFilter($FilterArr);

if (CheckFilter())
{
	$arFilter = Array(
		"ID" => ($find!="" && $find_type == "id"? $find:$find_id),
		"H1" => ($find!="" && $find_type == "h1"? $find:$find_h1),
        "URL" => $find_url,
        "LURL" => $find_lurl,
        "TITLE" => $find_title,
        "DESCRIPTION" => $find_description,
        "KEYWORDS" => $find_keywords,
        "ACTIVE" => $find_active,
        "CANONICAL" => $find_CANONICAL,
        "TEXT1" => $find_text1,
        "TEXT2" => $find_text2,
        "TEXT3" => $find_text3,
        "LID" => $find_lid
	);
}

if($lAdmin->EditAction() && $POST_RIGHT=="W")
{
	foreach($FIELDS as $ID=>$arFields)
	{
		if(!$lAdmin->IsUpdated($ID))
			continue;
		$DB->StartTransaction();
		$ID = IntVal($ID);
		$cData = new CIvSeo;
		if(($rsData = $cData->GetByID($ID)) && ($arData = $rsData->Fetch()))
		{
			foreach($arFields as $key=>$value)
				$arData[$key]=$value;
			if(!$cData->Update($ID, $arData))
			{
				$lAdmin->AddGroupError(GetMessage("rub_save_error")." ".$cData->LAST_ERROR, $ID);
				$DB->Rollback();
			}
		}
		else
		{
			$lAdmin->AddGroupError(GetMessage("rub_save_error")." ".GetMessage("rub_no_rubric"), $ID);
			$DB->Rollback();
		}
		$DB->Commit();
	}
}

if(($arID = $lAdmin->GroupAction()) && $POST_RIGHT=="W")
{
	if($_REQUEST['action_target']=='selected')
	{
		$cData = new CRubric;
		$rsData = $cData->GetList(array($by=>$order), $arFilter);
		while($arRes = $rsData->Fetch())
			$arID[] = $arRes['ID'];
	}

	foreach($arID as $ID)
	{
		if(strlen($ID)<=0)
			continue;
		$ID = IntVal($ID);
		switch($_REQUEST['action'])
		{
		case "delete":
			@set_time_limit(0);
			$DB->StartTransaction();
			if(!CIvSeo::Delete($ID))
			{
				$DB->Rollback();
				$lAdmin->AddGroupError(GetMessage("ivseo_del_err"), $ID);
			}
			$DB->Commit();

            if($_REQUEST['backurl'])
                LocalRedirect($_REQUEST['backurl']);

			break;
		case "activate":
		case "deactivate":
			$cData = new CIvSeo();
			if(($rsData = $cData->GetByID($ID)) && ($arFields = $rsData->Fetch()))
			{
				$arFields["ACTIVE"]=($_REQUEST['action']=="activate"?"Y":"N");
				if(!$cData->Update($ID, $arFields))
					$lAdmin->AddGroupError(GetMessage("ivseo_save_error").$cData->LAST_ERROR, $ID);
			}
			else
				$lAdmin->AddGroupError(GetMessage("ivseo_save_error")." ".GetMessage("ivseo_no_rubric"), $ID);
			break;
		}

	}
}

$rsData = CIvSeo::GetList(array($by=>$order), $arFilter);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("ivseo_nav")));

$lAdmin->AddHeaders(array(
	array(	"id"		=>"ID",
		"content"	=>"ID",
		"sort"		=>"ID",
		"align"		=>"right",
		"default"	=>true,
	),
    array(	"id"		=>"LID",
              "content"	=>GetMessage("ivseo_site"),
              "sort"		=>"lid",
              "default"	=>true,
    ),
	array(	"id"		=>"URL",
		"content"	=>GetMessage("ivseo_url"),
		"sort"		=>"URL",
		"default"	=>true,
	),
	array(	"id"		=>"H1",
		"content"	=>GetMessage("ivseo_h1"),
		"sort"		=>"H1",
		"default"	=>true,
	),
	array(	"id"		=>"TITLE",
		"content"	=>GetMessage("ivseo_ctitle"),
		"sort"		=>"TITLE",
		"default"	=>true,
	),
	array(	"id"		=>"DESCRIPTION",
		"content"	=>GetMessage("ivseo_description"),
		"sort"		=>"DESCRIPTION",
		"default"	=>true,
	),
    array(	"id"		=>"KEYWORDS",
        "content"	=>GetMessage("ivseo_keywords"),
        "sort"		=>"KEYWORDS",
        "default"	=>true,
    ),
    array(	"id"		=>"TEXT1",
              "content"	=>GetMessage("ivseo_text1"),
              "sort"		=>"TEXT1",
              "default"	=>false,
    ),
    array(	"id"		=>"TEXT2",
        "content"	=>GetMessage("ivseo_text2"),
        "sort"		=>"TEXT2",
        "default"	=>false,
    ),
    array(	"id"		=>"TEXT3",
        "content"	=>GetMessage("ivseo_text3"),
        "sort"		=>"TEXT3",
        "default"	=>false,
    ),
	array(	"id"		=>"ACTIVE",
		"content"	=>GetMessage("ivseo_act"),
		"sort"		=>"ACTIVE",
		"default"	=>true,
	),
    array(	"id"		=>"CANONICAL",
        "content"	=>GetMessage("ivseo_CANONICAL"),
        "sort"		=>"CANONICAL",
        "default"	=>true,
    ),
));


$dbSites = CSite::GetList($b="NAME", $o="asc");
$arSites = array();
while ($arSite = $dbSites->Fetch())
{
    $arSites[$arSite["ID"]] = $arSite["NAME"];
}

while($arRes = $rsData->NavNext(true, "f_")):
	$row =& $lAdmin->AddRow($f_ID, $arRes);

    //TODO: Возможность редактировать url
    $row->AddInputField("URL", array("size"=>40));
	$row->AddViewField("URL", '<a href="intervolga.seo_edit.php?ID='.$f_ID.'&amp;lang='.LANG.'">'.$f_URL.'</a>');
    $row->AddCheckField("ACTIVE");
    $row->AddCheckField("CANONICAL");

    $row->AddEditField("LID", CLang::SelectBox("FIELDS[".$f_ID."][LID]", $f_LID));

    $row->AddInputField("H1");
    $row->AddInputField("TITLE");
    $row->AddInputField("DESCRIPTION");
    $row->AddInputField("KEYWORDS");

    $maxTextLength = 128;
    $display = (strlen($f_TEXT)<=$maxTextLength)?$f_TEXT:substr($f_TEXT, 0, $maxTextLength)."...";
    $row->AddViewField("TEXT", $display);

	$arActions = Array();

	$arActions[] = array(
		"ICON"=>"edit",
		"DEFAULT"=>true,
		"TEXT"=>GetMessage("ivseo_edit"),
		"ACTION"=>$lAdmin->ActionRedirect("intervolga.seo_edit.php?ID=".$f_ID)
	);

    //TODO: Добавить действие "Показать в публичке"
    /*$arActions[] = array(
        "ICON"=>"edit",
        "DEFAULT"=>true,
        "TEXT"=> 'Показать в публичке '.GetMessage("ivseo_edit"),
        "ACTION"=>$lAdmin->ActionRedirect("http://".SERVER_NAME.$f_URL)
    );*/

	if ($POST_RIGHT>="W")
		$arActions[] = array(
			"ICON"=>"delete",
			"TEXT"=>GetMessage("ivseo_del"),
			"ACTION"=>"if(confirm('".GetMessage('ivseo_del_conf')."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
		);

	if(is_set($arActions[count($arActions)-1], "SEPARATOR"))
		unset($arActions[count($arActions)-1]);
	$row->AddActions($arActions);

endwhile;

$lAdmin->AddFooter(
	array(
		array("title"=>GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value"=>$rsData->SelectedRowsCount()),
		array("counter"=>true, "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value"=>"0"),
	)
);
$lAdmin->AddGroupActionTable(Array(
	"delete"=>GetMessage("MAIN_ADMIN_LIST_DELETE"),
	"activate"=>GetMessage("MAIN_ADMIN_LIST_ACTIVATE"),
	"deactivate"=>GetMessage("MAIN_ADMIN_LIST_DEACTIVATE"),
	));

$aContext = array(
	array(
		"TEXT"=>GetMessage("MAIN_ADD"),
		"LINK"=>"intervolga.seo_edit.php?lang=".LANG,
		"TITLE"=>GetMessage("POST_ADD_TITLE"),
		"ICON"=>"btn_new",
	),
    array(
            "TEXT"  => "Импорт"
        ,   "LINK"  => "intervolga.seo_csv_import.php?lang=".LANG
        ,   "TITLE" => "Импорт настроек из CSV-файла"
        ,   "ICON"  => "btn_new"
    ),
    array(
            "TEXT"  => "Экспорт"
        ,   "LINK"  => "intervolga.seo_csv_export.php?lang=".LANG
        ,   "TITLE" => "Экспорт настроек в CSV-файл"
        ,   "ICON"  => "btn_copy"
    )
);
$lAdmin->AddAdminContextMenu($aContext);
$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("ivseo_title"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$oFilter = new CAdminFilter(
	$sTableID."_filter",
	array(
		"ID",
		GetMessage("ivseo_f_url"),
        GetMessage("ivseo_f_lurl"),
		GetMessage("ivseo_f_active"),
        GetMessage("ivseo_f_CANONICAL"),

        GetMessage("ivseo_f_h1"),
		GetMessage("ivseo_f_title"),
        GetMessage("ivseo_f_keywords"),
        GetMessage("ivseo_f_description"),
        GetMessage("ivseo_f_text1"),
        GetMessage("ivseo_f_text2"),
        GetMessage("ivseo_f_text3"),
        GetMessage("ivseo_f_site"),
	)
);
?>
<form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
<?$oFilter->Begin();?>
    <tr>
        <td><b><?=GetMessage("ivseo_f_find")?>:</b></td>
        <td>
            <input type="text" size="25" name="find" value="<?echo htmlspecialcharsbx($find)?>" title="<?=GetMessage("ivseo_f_find_title")?>">
            <?
            $arr = array(
                "reference" => array(
                    "ID",
                    GetMessage("ivseo_f_h1"),
                ),
                "reference_id" => array(
                    "id",
                    "h1",
                )
            );
            echo SelectBoxFromArray("find_type", $arr, $find_type, "", "");
            ?>
        </td>
    </tr>
    <tr>
        <td><?="ID"?>:</td>
        <td>
            <input type="text" name="find_id" size="47" value="<?echo htmlspecialcharsbx($find_id)?>">
        </td>
    </tr>
<tr>
    <td><?=GetMessage("ivseo_f_url")?>:</td>
    <td>
        <input type="text" name="find_url" size="47" value="<?echo htmlspecialcharsbx($find_url)?>">
    </td>
</tr>
<tr>
    <td><?=GetMessage("ivseo_f_lurl")?>:</td>
    <td>
        <input type="text" name="find_lurl" size="47" value="<?echo htmlspecialcharsbx($find_lurl)?>">
    </td>
</tr>
<tr>
    <td><?=GetMessage("ivseo_f_active")?>:</td>
    <td>
        <?
        $arr = array(
            "reference" => array(
                GetMessage("MAIN_YES"),
                GetMessage("MAIN_NO"),
            ),
            "reference_id" => array(
                "Y",
                "N",
            )
        );
        echo SelectBoxFromArray("find_active", $arr, $find_active, GetMessage("MAIN_ALL"), "");
        ?>
    </td>
</tr>
    <tr>
        <td><?=GetMessage("ivseo_f_CANONICAL")?>:</td>
        <td>
            <?
            $arr = array(
                "reference" => array(
                    GetMessage("MAIN_YES"),
                    GetMessage("MAIN_NO"),
                ),
                "reference_id" => array(
                    "Y",
                    "N",
                )
            );
            echo SelectBoxFromArray("find_CANONICAL", $arr, $find_CANONICAL, GetMessage("MAIN_ALL"), "");
            ?>
        </td>
    </tr>

    <tr>
        <td><?=GetMessage("ivseo_f_h1")?>:</td>
        <td>
            <input type="text" name="find_h1" size="47" value="<?echo htmlspecialcharsbx($find_h1)?>">
        </td>
    </tr>
    <tr>
        <td><?=GetMessage("ivseo_f_title")?>:</td>
        <td>
            <input type="text" name="find_title" size="47" value="<?echo htmlspecialcharsbx($find_title)?>">
        </td>
    </tr>
    <tr>
        <td><?=GetMessage("ivseo_f_keywords")?>:</td>
        <td>
            <input type="text" name="find_keywords" size="47" value="<?echo htmlspecialcharsbx($find_keywords)?>">
        </td>
    </tr>
    <tr>
        <td><?=GetMessage("ivseo_f_description")?>:</td>
        <td>
            <input type="text" name="find_description" size="47" value="<?echo htmlspecialcharsbx($find_description)?>">
        </td>
    </tr>
    <tr>
        <td><?=GetMessage("ivseo_f_text1")?>:</td>
        <td>
            <input type="text" name="find_text1" size="47" value="<?echo htmlspecialcharsbx($find_text1)?>">
        </td>
    </tr>
    <tr>
        <td><?=GetMessage("ivseo_f_text2")?>:</td>
        <td>
            <input type="text" name="find_text2" size="47" value="<?echo htmlspecialcharsbx($find_text2)?>">
        </td>
    </tr>
    <tr>
        <td><?=GetMessage("ivseo_f_text3")?>:</td>
        <td>
            <input type="text" name="find_text3" size="47" value="<?echo htmlspecialcharsbx($find_text3)?>">
        </td>
    </tr>

    <?if(1){
        //Многосайтовость
        ?>
    <tr>
        <td><?=GetMessage("ivseo_f_site").":"?></td>
        <td><select name="find_lid">
            <option value=""<?echo ($find_lid == "" ? ' selected' : '') ?>><?echo GetMessage("MAIN_ALL")?></option>
            <?
            $dbSites = CSite::GetList($b="NAME", $o="asc");
            while ($arSites = $dbSites->Fetch())
            {
                var_dump($arSites["ID"]);
                ?><option value="<?echo htmlspecialcharsbx($arSites["ID"]) ?>"<?echo ($find_lid == $arSites["ID"] ? ' selected' : '') ?>>(<?echo htmlspecialcharsbx($arSites["ID"]) ?>) <?echo htmlspecialcharsbx($arSites["NAME"]) ?></option><?
            }
            ?>
        </select></td>
    </tr>
    <?}?>
<?
$oFilter->Buttons(array("table_id"=>$sTableID,"url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
$oFilter->End();
?>
</form>

<?$lAdmin->DisplayList();?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>