<title>SITEMAP</title>
<?
// костыли
//1. в качестве файла указываю sitemap.xml.
//  только это ни на что не влияет, потому что в классе sitemap.php название файла принудительно присваивается sitemap
//  названия остальных файлов собираются по виду $имя_файла.$номер_файла.".xml";
//  индексный файл - $имя_файла.".xml"
//2. при перечислении всех разелов получает имена в виде about/delivery.
//  поэтому я дописываю "/" трижды
//  - при выводе
//  - при добавлении в массив
//  - при проверке, стоит ли галка

// настройки на размер файла задаются в sitemap.php. можно вывести в аргументы, если нужно




IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/bedrosova.sitemap/export_setup_templ.php");

global $APPLICATION;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/fileman/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/fileman/include.php");



function GetPathAndMenuInPath($site,$path,$depth)
{
    global $result;

    $DOC_ROOT = CSite::GetSiteDocRoot($site);
    $arParsedPath = CFileMan::ParsePath($path);
    $arPath = Array($site, $path);

    if($type == "flash")
        $ext = "swf,fla";
    elseif($type == "image")
        $ext = "gif,jpg,jpeg,bmp,png";
    CFileMan::GetDirList(Array($site, $path), $arDirs, $arFiles, Array("EXTENSIONS"=>$ext, "MIN_PERMISSION"=>"R"), Array("name"=>"asc"));

    $arDirContent = array_merge($arDirs, $arFiles);
    $db_DirContent = new CDBResult;
    $db_DirContent->InitFromArray($arDirContent);
    $db_DirContent->sSessInitAdd = $path;



    while($arDirElement = $db_DirContent->Fetch())
    {

        if($arDirElement['NAME']!='bitrix' && $arDirElement['NAME']!='upload')
        {

            if (strpos($arDirElement['NAME'],'.menu.php')) $result['mainmenu'][]=$arDirElement['PATH'];
            if ($arDirElement['TYPE']=='D')
            {

                $result['maindir'][]=$arDirElement['ABS_PATH'];
                if ($depth<3)
                {
                    $depth++;
                    $res=GetPathAndMenuInPath($site,$arDirElement['ABS_PATH'],$depth);

                }
            }
        }

    }

    return $result;
}


$arSetupErrors = array();
if (($ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT_COPY') && $STEP == 1)
{
    if (isset($arOldSetupVars['SITEMAP_EXPORT']))
        $SITEMAP_EXPORT = $arOldSetupVars['SITEMAP_EXPORT'];
    if (isset($arOldSetupVars['SITEMAP2_EXPORT']))
        $SITEMAP2_EXPORT = $arOldSetupVars['SITEMAP2_EXPORT'];
    if (isset($arOldSetupVars['SITEMAP_PRIORI']))
        $SITEMAP_PRIORI = $arOldSetupVars['SITEMAP_PRIORI'];
    if (isset($arOldSetupVars['SITEMAP2_PRIORI']))
        $SITEMAP2_PRIORI = $arOldSetupVars['SITEMAP2_PRIORI'];
    if (isset($arOldSetupVars['SETUP_FILE_NAME']))
        $SETUP_FILE_NAME = $arOldSetupVars['SETUP_FILE_NAME'];
    if (isset($arOldSetupVars['SETUP_PROFILE_NAME']))
        $SETUP_PROFILE_NAME = $arOldSetupVars['SETUP_PROFILE_NAME'];
    if (isset($arOldSetupVars['SETUP_SERVER_NAME']))
        $SETUP_SERVER_NAME = $arOldSetupVars['SETUP_SERVER_NAME'];
}


if ($STEP>1)
{
    if (!is_array($SITEMAP_EXPORT) || count($SITEMAP_EXPORT)<=0)
    {
        $arSetupErrors[] = GetMessage("CET_ERROR_NO_IBLOCKS");
    }

    if (strlen($SETUP_FILE_NAME)<=0)
    {
        $arSetupErrors[] = GetMessage("CET_ERROR_NO_FILENAME");
    }
    elseif (preg_match(BX_CATALOG_FILENAME_REG,$SETUP_FILE_NAME))
    {
        $arSetupErrors[] = GetMessage("CES_ERROR_BAD_EXPORT_FILENAME");
    }
    elseif ($APPLICATION->GetFileAccessPermission($SETUP_FILE_NAME) < "W")
    {
        $arSetupErrors[] = str_replace("#FILE#", $SETUP_FILE_NAME, GetMessage('CET_YAND_RUN_ERR_SETUP_FILE_ACCESS_DENIED'));
    }

    if (($ACTION=="EXPORT_SETUP" || $ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT_COPY') && strlen($SETUP_PROFILE_NAME)<=0)
    {
        $arSetupErrors[] = GetMessage("CET_ERROR_NO_PROFILE_NAME");
    }

    if (!empty($arSetupErrors))
    {
        $STEP = 1;
    }
}

if (!empty($arSetupErrors))
    echo ShowError(implode('<br />', $arSetupErrors));

if ($STEP==1)
{
    if (CModule::IncludeModule("iblock"))
    {
        // Get IBlock list
        ?>
        <form method="POST" action="<? echo $APPLICATION->GetCurPage(); ?>" enctype="multipart/form-data" name="dataload">
        <? echo bitrix_sessid_post(); ?>
        <?if ($ACTION == 'EXPORT_EDIT' || $ACTION == 'EXPORT_COPY')
        {
            ?><input type="hidden" name="PROFILE_ID" value="<? echo intval($PROFILE_ID); ?>"><?
        }

        ?>

        <table width="100%">
        <tr>
            <td width="0%" valign="top">
                <font class="text" style="font-size: 20px;">1.&nbsp;&nbsp;&nbsp;</font>
            </td>
            <td valign="top">
                <font class="text"><?echo GetMessage("CET_EXPORT_CATALOGS");?><br><br></font>
                <?
                if (!isset($SITEMAP_EXPORT) || !is_array($SITEMAP_EXPORT))
                {
                    $SITEMAP_EXPORT = array();
                }
                $boolAll = false;
                $intCountChecked = 0;
                $intCountAvailIBlock = 0;
                $arIBlockList = array();
                $db_res = CIBlock::GetList(Array("IBLOCK_TYPE"=>"ASC", "NAME"=>"ASC"),array('CHECK_PERMISSIONS' => 'Y','MIN_PERMISSION' => 'W'));
                while ($res = $db_res->Fetch())
                {
                    //if ($ar_res1 = CCatalog::GetByID($res["ID"]))
                    //{
                    $arSiteList = array();
                    $rsSites = CIBlock::GetSite($res["ID"]);
                    while ($arSite = $rsSites->Fetch())
                    {
                        $arSiteList[] = $arSite["SITE_ID"];
                    }

                    //$boolYandex = (in_array($res['ID'],$SITEMAP_EXPORT));
                    $arIBlockList[] = array(
                        'ID' => $res['ID'],
                        'NAME' => $res['NAME'],
                        'IBLOCK_TYPE_ID' => $res['IBLOCK_TYPE_ID'],
                        //	'SITEMAP_EXPORT' => $boolYandex,
                        'SITE_LIST' => '('.implode(' ',$arSiteList).')',
                    );
                    //if ($boolYandex)
                    $intCountChecked++;
                    $intCountAvailIBlock++;
                    //}
                }
                if ($intCountChecked == $intCountAvailIBlock)
                    $boolAll = true;
                ?>


                <table width="100%" cellspacing="1" cellpadding="2" border="0">
                    <tr>
                        <td valign="top" class="tablehead1">
                            <font class="tableheadtext"><?echo GetMessage("CET_CATALOG");?></font>
                        </td>
                        <td valign="top" class="tablehead3">
                            <font class="tableheadtext"><?echo GetMessage("CET_ADD_TO_MAP");?></font>&nbsp;
                            <?/*
								<input style="vertical-align: middle;" type="checkbox" name="sitemap_export_all" id="sitemap_export_all" value="Y" onclick="checkAll(this,<? echo $intCountAvailIBlock; ?>);"<? echo ($boolAll ? ' checked' : ''); ?>>
								*/?>
                        </td>
                        <td valign="top" class="tablehead3">
                            <font class="tableheadtext"><?echo GetMessage("CET_INDEX_PRIORITY");?></font>
                        </td>
                    </tr>
                    <?

                    foreach ($arIBlockList as $key => $arIBlock)
                    {
                        ?><tr>
                        <td class="tablebody1">
                            <font class="tablebodytext"><? echo htmlspecialcharsex("[".$arIBlock["IBLOCK_TYPE_ID"]."] ".$arIBlock["NAME"]." ".$arIBlock['SITE_LIST']); ?></font>
                        </td>
                        <td class="tablebody3" align="center">
                            <font class="tablebodytext">
                                <input type="checkbox" name="SITEMAP_EXPORT[<? echo $key; ?>]" id="SITEMAP_EXPORT_<? echo $key; ?>" value="<? echo $arIBlock["ID"]; ?>" <?if (in_array($arIBlock["ID"],$SITEMAP_EXPORT)){?>checked<?}?> onclick="checkOne(this,<? echo $intCountAvailIBlock; ?>);">
                            </font>
                        </td>
                        <td>
                            <input type="text" name="SITEMAP_PRIORI[<? echo $key; ?>]" id="SITEMAP_PRIORI_<? echo $key; ?>" value="<?=$SITEMAP_PRIORI[$key]?$SITEMAP_PRIORI[$key]:"0.5";?>">
                        </td>
                        </tr><?
                    }
                    ?>


                    <tr>
                        <td valign="top" class="tablehead1">
                            <font class="tableheadtext"><?echo GetMessage("CET_SITE_WAYS_BASED_ON_MENU");?></font>
                        </td>
                        <td valign="top" class="tablehead3">
                            <font class="tableheadtext"><?echo GetMessage("CET_ADD_TO_MAP");?></font>&nbsp;
                        </td>
                        <td valign="top" class="tablehead3">
                            <font class="tableheadtext"><?echo GetMessage("CET_INDEX_PRIORITY");?></font>
                        </td>
                    </tr>


                    <?

                    $rsSites = CSite::GetList($by="sort", $order="desc", Array());
                    while ($arSite = $rsSites->Fetch())
                    {

                        $site = CFileMan::__CheckSite($arSite['LID']);
                        $path="";

                        $res=GetPathAndMenuInPath($site,$path,0);

                        $mainmenu=$res['mainmenu'];
                        $maindir=$res['maindir'];

                        foreach($mainmenu as $val)
                        {
                            require($val);
                        }

                            // Чертов эфир! После него вас развозит так, что вы похожи на пьяницу из старой ирландской новеллы: полная утрата двигательно-опорных навыков, галлюцинации, потеря равновесия, немеет язык, начинаются бояки и отказывает позвоночник.


                        foreach($aMenuLinks as $key_ml=>$ML)
                        {
                            ?>

                            <tr>
                                <td class="tablebody1"><?="/".$ML[1]?></td>
                                <td class="tablebody3" align="center">

                                    <input type="checkbox" name="SITEMAP2_EXPORT[<? echo $key_ml ?>]" id="SITEMAP2_EXPORT_<? echo $key_ml ?>" value="<?="/".$ML[1]?>" <?if (in_array("/".$ML[1],$SITEMAP2_EXPORT)){?>checked<?}?>>

                                </td>

                                <td class="tablebody3">
                                    <input type="text" name="SITEMAP2_PRIORI[<? echo $key_ml; ?>]" id="SITEMAP2_PRIORI_<? echo $key_ml; ?>" value="<?=$SITEMAP2_PRIORI[$key_ml]?$SITEMAP2_PRIORI[$key_ml]:"0.5";?>">
                                </td>
                            </tr>

                        <?
                        }




                    }

                    //*******************************************************************



                    ?>

                </table>



                <input type="hidden" name="count_checked" id="count_checked" value="<? echo $intCountChecked; ?>">
                <script type="text/javascript">
                    function checkAll(obj,cnt)
                    {
                        var boolCheck = obj.checked;
                        for (i = 0; i < cnt; i++)
                        {
                            BX('SITEMAP_EXPORT_'+i).checked = boolCheck;
                        }
                        BX('count_checked').value = (boolCheck ? cnt : 0);
                    }

                    function checkOne(obj,cnt)
                    {
                        var boolCheck = obj.checked;
                        var intCurrent = parseInt(BX('count_checked').value);
                        intCurrent += (boolCheck ? 1 : -1);
                        BX('sitemap_export_all').checked = (intCurrent < cnt ? false : true);
                        BX('count_checked').value = intCurrent;
                    }
                </script>
                <br>
            </td>
        </tr>

        <tr>
            <td width="0%" valign="top">
                <font class="text" style="font-size: 20px;">2.&nbsp;&nbsp;&nbsp;</font>
            </td>
            <td width="100%" valign="top">
                <font class="text">
                    <?echo GetMessage("CET_DOMAIN");?>: <input type="text" name="SETUP_SERVER_NAME" value="<?echo (strlen($SETUP_SERVER_NAME)>0) ? htmlspecialcharsbx($SETUP_SERVER_NAME) : '' ?>" size="50" /> <input type="button" onclick="this.form['SETUP_SERVER_NAME'].value = window.location.host;" value="<?echo GetMessage("CET_ADD_THIS");?>" />
                </font>
                <br><br>
            </td>
        </tr>

        <?
        if ($ACTION != "EXPORT")
        {
            ?>
        <tr>
            <td width="0%" valign="top">
                <font class="text" style="font-size: 20px;">3.&nbsp;&nbsp;&nbsp;</font>
            </td>
            <td width="100%" valign="top">
                <font class="text">
                    <?echo GetMessage("CET_PROFILE_NAME");?>: <input type="text" name="SETUP_PROFILE_NAME" value="<?echo htmlspecialcharsbx($SETUP_PROFILE_NAME)?>" size="30">
                </font>
                <br><br>
            </td>
        </tr>

        <? }?>
        <tr>
            <td width="0%" valign="top">
                &nbsp;
            </td>
            <td width="100%" valign="top">
                <input type="hidden" name="lang" value="<?echo LANGUAGE_ID ?>">
                <input type="hidden" name="ACT_FILE" value="<?echo htmlspecialcharsbx($_REQUEST["ACT_FILE"]) ?>">
                <input type="hidden" name="ACTION" value="<?echo htmlspecialcharsbx($ACTION) ?>">
                <input type="hidden" name="STEP" value="<?echo intval($STEP) + 1 ?>">
                <input type="hidden" name="SETUP_FILE_NAME" value="sitemap.xml">

                <input type="hidden" name="SETUP_FIELDS_LIST" value="SITEMAP_EXPORT,SITEMAP2_EXPORT,SITEMAP_PRIORI,SITEMAP2_PRIORI,SETUP_SERVER_NAME,SETUP_FILE_NAME">
                <input type="submit" value="<?echo ($ACTION=="EXPORT")?GetMessage("CET_GENERATE"):GetMessage("CET_SAVE");?>">
            </td>
        </tr>
        </table>

        </form>
    <?
    }
}
elseif ($STEP==2)
{
    $SETUP_SERVER_NAME = htmlspecialcharsbx($SETUP_SERVER_NAME);
    $_POST['SETUP_SERVER_NAME'] = htmlspecialcharsbx($_POST['SETUP_SERVER_NAME']);
    $_REQUEST['SETUP_SERVER_NAME'] = htmlspecialcharsbx($_REQUEST['SETUP_SERVER_NAME']);

    $FINITE = true;
}
?>