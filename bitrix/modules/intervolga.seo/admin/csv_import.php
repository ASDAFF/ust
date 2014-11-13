<?
require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/prolog.php");

IncludeModuleLangFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("intervolga.seo");
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

// Реализовано на основе стандартного импорта для инфоблоков
$STEP = intval($STEP);
if ($STEP <= 0)
	$STEP = 1;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["backButton"]) && strlen($_POST["backButton"]) > 0)
	$STEP = $STEP - 2;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["backButton2"]) && strlen($_POST["backButton2"]) > 0)
	$STEP = 1;

$max_execution_time	=	intval($max_execution_time);
if ($max_execution_time <= 0)
	$max_execution_time = 0;

if (isset($_REQUEST["CUR_LOAD_SESS_ID"]) && strlen($_REQUEST["CUR_LOAD_SESS_ID"]) > 0)
	$CUR_LOAD_SESS_ID	=	$_REQUEST["CUR_LOAD_SESS_ID"];
else
	$CUR_LOAD_SESS_ID	=	"CL".time();

$bAllLinesLoaded	=	true;
$CUR_FILE_POS		=	isset($_REQUEST["CUR_FILE_POS"]) ? intval($_REQUEST["CUR_FILE_POS"]) : 0;
$strError			=	"";
$line_num			=	0;
$correct_lines		=	0;
$error_lines		=	0;
$killed_lines		=	0;
$io					=	CBXVirtualIo::GetInstance();

$arSeoAvailFields = array(
	"ID" => array(
		"field"		=>	"ID",
		"important"	=>	"Y",
		"name"		=>	GetMessage("ivseo_ID"),
	) ,
	"H1" => array(
		"field"		=>	"H1",
		"important"	=>	"N",
		"name"		=>	GetMessage("ivseo_h1"),
	) ,	
	"URL" => array(
		"field"		=>	"URL",
		"important"	=>	"Y",
		"name"		=>	GetMessage("ivseo_url"),
	) ,
	"TITLE" => array(
		"field"		=>	"TITLE",
		"important"	=>	"N",
		"name"		=>	GetMessage("ivseo_title"),
	) ,
	"DESCRIPTION" => array(
		"field"		=>	"DESCRIPTION",
		"important"	=>	"N",
		"name"		=>	GetMessage("ivseo_description"),
	) ,
	"KEYWORDS" => array(
		"field"		=>	"KEYWORDS",
		"important"	=>	"N",
		"name"		=>	GetMessage("ivseo_keywords"),
	) ,
	"ACTIVE" => array(
		"field"		=>	"ACTIVE",
		"important"	=>	"N",
		"name"		=>	GetMessage("ivseo_act"),
	) ,
	"CANONICAL" => array(
		"field"		=>	"CANONICAL",
		"important"	=>	"N",
		"name"		=>	GetMessage("ivseo_CANONICAL"),
	) ,
	"TEXT1" => array(
		"field"		=>	"TEXT1",
		"important"	=>	"N",
		"name"		=>	GetMessage("ivseo_text1"),
	) ,
	"TEXT2" => array(
		"field"		=>	"TEXT2",
		"important"	=>	"N",
		"name"		=>	GetMessage("ivseo_text2"),
	) ,
	"TEXT3" => array(
		"field"		=>	"TEXT3",
		"important"	=>	"N",
		"name"		=>	GetMessage("ivseo_text3"),
	) ,
	"LID" => array(
		"field"		=>	"LID",
		"important"	=>	"Y",
		"name"		=>	GetMessage("ivseo_site"),
	) ,
);

/////////////////////////////////////////////////////////////////////

class CAssocData extends CCSVData
{
	var $__rows = array();
	var $__pos = array();
	var $__last_pos = 0;
	var $NUM_FIELDS = 0;
	var $tmpid = "";
	var $PK = array();
	var $GROUP_REGEX = "";

	function __construct($fields_type = "R", $first_header = false, $NUM_FIELDS = 0)
	{
		parent::__construct($fields_type, $first_header);
		$this->NUM_FIELDS = intval($NUM_FIELDS);
	}

	function GetPos()
	{
		if(empty($this->__pos))
			return parent::GetPos();
		else
			return $this->__pos[count($this->__pos) - 1];
	}

	function Fetch()
	{
		if (empty($this->__rows))
		{
			$this->__last_pos = $this->GetPos();
			return parent::Fetch();
		}
		else
		{
			$this->__last_pos = array_pop($this->__pos);
			return array_pop($this->__rows);
		}
	}

	function PutBack($row)
	{
		$this->__rows[] = $row;
		$this->__pos[] = $this->__last_pos;
	}

	function AddPrimaryKey($field_name, $field_ind)
	{
		$this->PK[$field_name] = $field_ind;
	}

	function FetchAssoc()
	{
		global $line_num;
		$result = array();
		while ($ar = $this->Fetch()) {			
			$line_num++;

			return $ar;
		}
		//eof

		if (empty($result))
			return $ar;
		else
			return $result;
	}
}
/////////////////////////////////////////////////////////////////////

if (($REQUEST_METHOD == "POST" || $CUR_FILE_POS > 0) && $STEP > 1 && check_bitrix_sessid()) {
	if ($STEP > 1) {
		//*****************************************************************//
		$DATA_FILE_NAME = "";
		if (isset($_FILES["DATA_FILE"]) && is_uploaded_file($_FILES["DATA_FILE"]["tmp_name"])) {
			if (strtolower(GetFileExtension($_FILES["DATA_FILE"]["name"])) != "csv") {
				$strError.= GetMessage("ivseo_error_not_csv");
			} else {
				$DATA_FILE_NAME = "/".COption::GetOptionString("main", "upload_dir", "upload")."/".basename($_FILES["DATA_FILE"]["name"]);
				if ($APPLICATION->GetFileAccessPermission($DATA_FILE_NAME) >= "W")
					copy($_FILES["DATA_FILE"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"].$DATA_FILE_NAME);
				else
					$DATA_FILE_NAME = "";
			}
		}

		if (strlen($strError) <= 0) {
			if (strlen($DATA_FILE_NAME) <= 0) {
				if (strlen($URL_DATA_FILE) > 0) {
					$URL_DATA_FILE = trim(str_replace("\\", "/", trim($URL_DATA_FILE)) , "/");
					$FILE_NAME = rel2abs($_SERVER["DOCUMENT_ROOT"], "/".$URL_DATA_FILE);
					if (
						(strlen($FILE_NAME) > 1)
						&& ($FILE_NAME === "/".$URL_DATA_FILE)
						&& $io->FileExists($_SERVER["DOCUMENT_ROOT"].$FILE_NAME)
						&& ($APPLICATION->GetFileAccessPermission($FILE_NAME) >= "W")
					) {
						$DATA_FILE_NAME = $FILE_NAME;
					}
				}
			}

			if (strlen($DATA_FILE_NAME) <= 0)
				$strError.= GetMessage("ivseo_error_file_not_selected");
		}

		if (strlen($strError) <= 0) {
			if ($CUR_FILE_POS > 0 && is_set($_SESSION, $CUR_LOAD_SESS_ID) && is_set($_SESSION[$CUR_LOAD_SESS_ID], "LOAD_SCHEME")) {
				parse_str($_SESSION[$CUR_LOAD_SESS_ID]["LOAD_SCHEME"]);
				$STEP = 4;
			}
		}

		if (strlen($strError) > 0)
			$STEP = 1;
	}
	if ($STEP > 2) {
		//*****************************************************************//
		$csvFile = new CAssocData;
		$csvFile->LoadFile($io->GetPhysicalName($_SERVER["DOCUMENT_ROOT"].$DATA_FILE_NAME));
		if ($fields_type != "F" && $fields_type != "R")
			$strError.= GetMessage("ivseo_error_file_info");

		$arDataFileFields = array();
		if (strlen($strError) <= 0) {
			$fields_type = (($fields_type == "F") ? "F" : "R");
			$csvFile->SetFieldsType($fields_type);
			if ($fields_type == "R") {
				$first_names_r = (($first_names_r == "Y") ? "Y" : "N");
				$csvFile->SetFirstHeader(($first_names_r == "Y") ? true : false);
				$delimiter_r_char = "";
				switch ($delimiter_r) {
					case "TAB":
						$delimiter_r_char = "\t";
						break;

					case "ZPT":
						$delimiter_r_char = ",";
						break;

					case "SPS":
						$delimiter_r_char = " ";
						break;

					case "OTR":
						$delimiter_r_char = substr($delimiter_other_r, 0, 1);
						break;

					case "TZP":
						$delimiter_r_char = ";";
						break;
				}
				if (strlen($delimiter_r_char) != 1)
					$strError.= GetMessage("ivseo_error_set_delim");

				if (strlen($strError) <= 0) {
					$csvFile->SetDelimiter($delimiter_r_char);
				}
			} else {
				$first_names_f = (($first_names_f == "Y") ? "Y" : "N");
				$csvFile->SetFirstHeader(($first_names_f == "Y") ? true : false);
				if (strlen($metki_f) <= 0)
					$strError.= GetMessage("ivseo_error_set_delim");

				if (strlen($strError) <= 0)
				{
					$arMetki = array();
					foreach (preg_split("/[\D]/i", $metki_f) as $metka)
					{
						$metka = intval($metka);
						if ($metka > 0)
							$arMetki[] = $metka;
					}

					if (!is_array($arMetki) || count($arMetki) < 1)
						$strError.= GetMessage("ivseo_error_set_delim");

					if (strlen($strError) <= 0) {
						$csvFile->SetWidthMap($arMetki);
					}
				}
			}

			if (strlen($strError) <= 0) {
				$bFirstHeaderTmp = $csvFile->GetFirstHeader();
				$csvFile->SetFirstHeader(false);
				if ($arRes = $csvFile->Fetch()) {
					foreach ($arRes as $i => $ar) {
						$arDataFileFields[$i] = $ar;
					}
				} else {
					$strError.= GetMessage("ivseo_error_empty_file");
				}
				$NUM_FIELDS = count($arDataFileFields);
			}
		}

		if (strlen($strError) > 0)
			$STEP = 2;
		//*****************************************************************//
	}
	if ($STEP > 3) {
		//*****************************************************************//

		if (strlen($strError) <= 0) {
			$csvFile->SetPos($CUR_FILE_POS);
			if ($CUR_FILE_POS <= 0 && $bFirstHeaderTmp) {
				$arRes = $csvFile->Fetch();
			}
			$io = CBXVirtualIo::GetInstance();
			$el = new CIvSeo;

			if ($CUR_FILE_POS > 0 && is_set($_SESSION, $CUR_LOAD_SESS_ID)) {

				if (is_set($_SESSION[$CUR_LOAD_SESS_ID], "line_num"))
					$line_num = intval($_SESSION[$CUR_LOAD_SESS_ID]["line_num"]);

				if (is_set($_SESSION[$CUR_LOAD_SESS_ID], "correct_lines"))
					$correct_lines = intval($_SESSION[$CUR_LOAD_SESS_ID]["correct_lines"]);

				if (is_set($_SESSION[$CUR_LOAD_SESS_ID], "error_lines"))
					$error_lines = intval($_SESSION[$CUR_LOAD_SESS_ID]["error_lines"]);

				if (is_set($_SESSION[$CUR_LOAD_SESS_ID], "killed_lines"))
					$killed_lines = intval($_SESSION[$CUR_LOAD_SESS_ID]["killed_lines"]);
			}
			foreach ($arSeoAvailFields as $key => $arField) {
				if ($arField["field"] === "ID") {
					for ($i = 0; $i < $NUM_FIELDS; $i++)
						if ($key === $GLOBALS["field_".$i])
							$csvFile->AddPrimaryKey($key, $i);
				} elseif ($arField["field"] === "NAME") {
					for ($i = 0; $i < $NUM_FIELDS; $i++)
						if ($key === $GLOBALS["field_".$i])
							$csvFile->AddPrimaryKey($key, $i);
				}
			}
			$csvFile->NUM_FIELDS = $NUM_FIELDS;
			
			$arSeoFileProperty = array();

			while ($arRes = $csvFile->FetchAssoc()) {
				$strErrorR = "";
				$arFilter = array();

				$i = 0;
				foreach ($arSeoAvailFields as $key => $arField) {
					if (isset($arRes[$i]))
						$arLoadProductArray[$arField["field"]] = $arRes[$i];
					$i++;
				}

				//if (strlen($arLoadProductArray["ID"])) $arFilter["ID"] = $arLoadProductArray["ID"];
				if (strlen($arLoadProductArray["LID"]))
					$arFilter["LID"] = $arLoadProductArray["LID"];
				if (strlen($arLoadProductArray["URL"]))
					$arFilter["URL"] = $arLoadProductArray["URL"];
				if(count($arFilter) < 1)
					$strErrorR.= GetMessage("ivseo_error_line_num")." ".$line_num.". ".GetMessage("ivseo_error_not_ident");

				if (strlen($strErrorR) <= 0) {					
					$res = CIvSeo::GetList(array(),$arFilter);
					if ($arr = $res->Fetch()) {
						$PRODUCT_ID = $arr["ID"];
						$res = $el->Update($PRODUCT_ID, $arLoadProductArray);
					} else {
						if ($arLoadProductArray["ACTIVE"] != "N") $arLoadProductArray["ACTIVE"] = "Y";						
						unset($arLoadProductArray['ID']);
						$PRODUCT_ID = $el->Add($arLoadProductArray);
						$res = ($PRODUCT_ID > 0);
					}

					if (!$res) {
						$strErrorR.= GetMessage("ivseo_error_line_num")." ".$line_num.". ".GetMessage("ivseo_error_el_load")." ".$el->LAST_ERROR."<br>";
					}
				}

				if (strlen($strErrorR) <= 0) {
					$correct_lines++;
				} else {
					$error_lines++;
					$strError.= $strErrorR;
				}

				if (intval($max_execution_time) > 0 && (getmicrotime() - START_EXEC_TIME) > intval($max_execution_time)) {
					$bAllLinesLoaded = false;
					break;
				}
			}

			if ($bAllLinesLoaded) {
				if (is_set($_SESSION, $CUR_LOAD_SESS_ID))
					unset($_SESSION[$CUR_LOAD_SESS_ID]);

				if ($inFileAction == "A") {
					$res = CIBlockElement::GetList(array() , array(
						"IBLOCK_ID" => $IBLOCK_ID,
						"CHECK_PERMISSIONS" => "N",
						"TMP_ID" => $tmpid,
						"ACTIVE" => "N",
					) , false, false, array(
						"ID",
						"IBLOCK_ID",
					));
					while ($arr = $res->Fetch()) {
						$el->Update($arr["ID"], array(
							"ACTIVE" => "Y",
						));
					}
				}
			} else {
				if (strlen($CUR_LOAD_SESS_ID) <= 0)
					$CUR_LOAD_SESS_ID = "CL".time();

				$_SESSION[$CUR_LOAD_SESS_ID]["line_num"] = $line_num;
				$_SESSION[$CUR_LOAD_SESS_ID]["correct_lines"] = $correct_lines;
				$_SESSION[$CUR_LOAD_SESS_ID]["error_lines"] = $error_lines;
				$_SESSION[$CUR_LOAD_SESS_ID]["killed_lines"] = $killed_lines;
				$paramsStr = "fields_type=".urlencode($fields_type);
				$paramsStr.= "&first_names_r=".urlencode($first_names_r);
				$paramsStr.= "&delimiter_r=".urlencode($delimiter_r);
				$paramsStr.= "&delimiter_other_r=".urlencode($delimiter_other_r);
				$paramsStr.= "&first_names_f=".urlencode($first_names_f);
				$paramsStr.= "&metki_f=".urlencode($metki_f);
				for ($i = 0; $i < $NUM_FIELDS; $i++)
				{
					$paramsStr.= "&field_".$i."=".urlencode(${"field_".$i});
				}
				$paramsStr.= "&outFileAction=".urlencode($outFileAction);
				$paramsStr.= "&max_execution_time=".urlencode($max_execution_time);
				$_SESSION[$CUR_LOAD_SESS_ID]["LOAD_SCHEME"] = $paramsStr;
				$curFilePos = $csvFile->GetPos();
			}
		}
		if (strlen($strError) > 0) {
			$strError.= GetMessage("ivseo_error_el_total")." ".$error_lines.".<br>";
			$strError.= GetMessage("ivseo_el_cor1").$correct_lines." ".GetMessage("ivseo_el_cor2")."<br>";
			$STEP = 3;
		}
		//*****************************************************************//

	}
}
/////////////////////////////////////////////////////////////////////
$APPLICATION->SetTitle(GetMessage("ivseo_page_title"));
require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
/*********************************************************************/
/********************  BODY  *****************************************/
/*********************************************************************/
CAdminMessage::ShowMessage($strError);
$strParams = '';
?>

<form method="POST" action="<?echo $sDocPath ?>?lang=<?echo LANG ?>" ENCTYPE="multipart/form-data" name="dataload" id="dataload">

<?$aTabs = array(
	array(
		"DIV"	=>	"edit1",
		"TAB"	=>	GetMessage("ivseo_table_tab1"),
		"ICON"	=>	"iblock",
		"TITLE"	=>	GetMessage("ivseo_table_tab1"),
	) ,
	array(
		"DIV"	=>	"edit2",
		"TAB"	=>	GetMessage("ivseo_table_tab2"),
		"ICON"	=>	"iblock",
		"TITLE"	=>	GetMessage("ivseo_table_tab2"),
	) ,
	array(
		"DIV"	=>	"edit3",
		"TAB"	=>	GetMessage("ivseo_table_tab3"),
		"ICON"	=>	"iblock",
		"TITLE"	=>	GetMessage("ivseo_table_tab3"),
	) ,
	array(
		"DIV"	=>	"edit4",
		"TAB"	=>	GetMessage("ivseo_table_tab4"),
		"ICON"	=>	"iblock",
		"TITLE"	=>	GetMessage("ivseo_table_tab4"),
	) ,
);
$tabControl = new CAdminTabControl("tabControl", $aTabs, false, true);
$tabControl->Begin();
?>

<?$tabControl->BeginNextTab();
if ($STEP == 1)
{
?>
	<tr>
		<td width="40%"><?echo GetMessage("ivseo_tab1_file_select")?></td>
		<td width="60%">
			<input type="text" name="URL_DATA_FILE" value="<?echo htmlspecialcharsbx($URL_DATA_FILE); ?>" size="30">
			<input type="button" value="Открыть" OnClick="BtnClick()">
			<?CAdminFileDialog::ShowScript(array(
				"event" => "BtnClick",
				"arResultDest" => array(
					"FORM_NAME" => "dataload",
					"FORM_ELEMENT_NAME" => "URL_DATA_FILE",
				) ,
				"arPath" => array(
					"SITE" => SITE_ID,
					"PATH" => "/".COption::GetOptionString("main", "upload_dir", "upload"),
				) ,
				"select" => 'F', // F - file only, D - folder only
				"operation" => 'O', // O - open, S - save
				"showUploadTab" => true,
				"showAddToMenuTab" => false,
				"fileFilter" => 'csv',
				"allowAllFiles" => true,
				"SaveConfig" => true,
			));
			?>
		</td>
	</tr>
	<?
}
$tabControl->EndTab();
?>

<?$tabControl->BeginNextTab();
if ($STEP == 2)
{
?>
	<input type="hidden" name="fields_type" id="fields_type_R" value="R">
	<tr id="table_r" class="heading">
		<td colspan="2"><?echo GetMessage("ivseo_tab2_file_format")?></td>
	</tr>
	<tr id="table_r1">
		<td class="adm-detail-valign-top"><?echo GetMessage("ivseo_tab2_f_delim")?></td>
		<td>
			<input type="radio" name="delimiter_r" id="delimiter_r_TZP" value="TZP" <?
	if ($delimiter_r == "TZP" || strlen($delimiter_r) <= 0)
		echo "checked" ?>><label for="delimiter_r_TZP"><?echo GetMessage("ivseo_tab2_f_delim_tzp")?></label><br>
			<input type="radio" name="delimiter_r" id="delimiter_r_ZPT" value="ZPT" <?
	if ($delimiter_r == "ZPT")
		echo "checked" ?>><label for="delimiter_r_ZPT"><?echo GetMessage("ivseo_tab2_f_delim_zpt")?></label><br>
			<input type="radio" name="delimiter_r" id="delimiter_r_TAB" value="TAB" <?
	if ($delimiter_r == "TAB")
		echo "checked" ?>><label for="delimiter_r_TAB"><?echo GetMessage("ivseo_tab2_f_delim_tab")?></label><br>
			<input type="radio" name="delimiter_r" id="delimiter_r_SPS" value="SPS" <?
	if ($delimiter_r == "SPS")
		echo "checked" ?>><label for="delimiter_r_SPS"><?echo GetMessage("ivseo_tab2_f_delim_sp")?></label><br>
			<input type="radio" name="delimiter_r" id="delimiter_r_OTR" value="OTR" <?
	if ($delimiter_r == "OTR")
		echo "checked" ?>><label for="delimiter_r_OTR"><?echo GetMessage("ivseo_tab2_f_delim_other")?></label>
			<input type="text" name="delimiter_other_r" size="3" value="<?echo htmlspecialcharsbx($delimiter_other_r); ?>">
		</td>
	</tr>
	<tr id="table_r2">
		<td><?echo GetMessage("ivseo_tab2_f_first_row_cont_names")?></td>
		<td>
			<input type="hidden" name="first_names_r" id="first_names_r_N" value="N">
			<input type="checkbox" name="first_names_r" id="first_names_r_Y" value="Y" checked>
		</td>
	</tr>

	<tr class="heading">
		<td colspan="2"><?echo GetMessage("ivseo_tab_data_sample")?></td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			<?$sContent = "";
	if (strlen($DATA_FILE_NAME) > 0)
	{
		$DATA_FILE_NAME = trim(str_replace("\\", "/", trim($DATA_FILE_NAME)) , "/");
		$FILE_NAME = rel2abs($_SERVER["DOCUMENT_ROOT"], "/".$DATA_FILE_NAME);
		if (
			(strlen($FILE_NAME) > 1)
			&& ($FILE_NAME == "/".$DATA_FILE_NAME)
			&& $APPLICATION->GetFileAccessPermission($FILE_NAME) >= "W"
		)
		{
			$f = $io->GetFile($_SERVER["DOCUMENT_ROOT"].$FILE_NAME);
			$file_id = $f->open("rb");
			$sContent = fread($file_id, 10000);
			fclose($file_id);
		}
	}
?>
			<textarea name="data" wrap="OFF" rows="10" cols="80" style="width:100%"><?echo htmlspecialcharsbx($sContent); ?></textarea>
		</td>
	</tr>
	<?
}
$tabControl->EndTab();
?>

<?$tabControl->BeginNextTab();
if ($STEP == 3)
{
?>
	<tr class="heading">
		<td colspan="2"><?echo GetMessage("ivseo_tab3_conf")?></td>
	</tr>
	<input type="hidden" id="outFileAction_F" name="outFileAction" value="F">
	<input type="hidden" id="inFileAction_F" name="inFileAction" value="F">
	
	<tr>
		<td class="adm-detail-valign-top"><?echo GetMessage("ivseo_tab3_exex_time")?></td>
		<td align="left">
			<input type="text" name="max_execution_time" size="6" value="<?echo htmlspecialcharsbx($max_execution_time); ?>"><br>
			<?echo GetMessage("ivseo_tab3_exex_time_note")?>
		</td>
	</tr>

	<tr class="heading">
		<td colspan="2"><?echo GetMessage("ivseo_tab_data_sample")?></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<?$sContent = "";
	if (strlen($DATA_FILE_NAME) > 0)
	{
		$DATA_FILE_NAME = trim(str_replace("\\", "/", trim($DATA_FILE_NAME)) , "/");
		$FILE_NAME = rel2abs($_SERVER["DOCUMENT_ROOT"], "/".$DATA_FILE_NAME);
		if (
			(strlen($FILE_NAME) > 1)
			&& ($FILE_NAME == "/".$DATA_FILE_NAME)
			&& $APPLICATION->GetFileAccessPermission($FILE_NAME) >= "W"
		)
		{
			$f = $io->GetFile($_SERVER["DOCUMENT_ROOT"].$FILE_NAME);
			$file_id = $f->open("rb");
			$sContent = fread($file_id, 10000);
			fclose($file_id);
		}
	}
?>
			<textarea name="data" wrap="OFF" rows="10" cols="80" style="width:100%"><?echo htmlspecialcharsbx($sContent); ?></textarea>
		</td>
	</tr>
	<?
}
$tabControl->EndTab();
?>

<?$tabControl->BeginNextTab();
if ($STEP == 4)
{
?>
	<tr>
		<td>
		<?echo CAdminMessage::ShowMessage(array(
			"TYPE" => "PROGRESS",
			"MESSAGE" => !$bAllLinesLoaded? GetMessage("ivseo_tab4_load_proc") : GetMessage("ivseo_tab4_load_complete"),
			"DETAILS" =>

			GetMessage("ivseo_tab4_lines_sum").' <b>'.$line_num.'</b><br>'
			.GetMessage("ivseo_tab4_lines_cor").' <b>'.$correct_lines.'</b><br>'
			.GetMessage("ivseo_tab4_lines_err").' <b>'.$error_lines.'</b><br>'
			.($outFileAction == "D"
				? GetMessage("ivseo_tab4_lines_del")." <b>".$killed_lines."</b>"
				:($outFileAction == "F"
					? ""
					: GetMessage("ivseo_tab4_lines_deact")." <b>".$killed_lines."</b>"
				)
			),
			"HTML" => true,
		))?>
		</td>
	</tr>
<?
}
$tabControl->EndTab();
?>

<?$tabControl->Buttons();
?>

<?
if ($STEP < 4): ?>
	<input type="hidden" name="STEP" value="<?echo $STEP + 1; ?>">
	<?echo bitrix_sessid_post(); ?>
	<?
	if ($STEP > 1): ?>
		<input type="hidden" name="URL_DATA_FILE" value="<?echo htmlspecialcharsbx($DATA_FILE_NAME); ?>">
	<?
	endif; ?>

	<?
	if ($STEP <> 2): ?>
		<input type="hidden" name="fields_type" value="<?echo htmlspecialcharsbx($fields_type); ?>">
		<input type="hidden" name="delimiter_r" value="<?echo htmlspecialcharsbx($delimiter_r); ?>">
		<input type="hidden" name="delimiter_other_r" value="<?echo htmlspecialcharsbx($delimiter_other_r); ?>">
		<input type="hidden" name="first_names_r" value="<?echo htmlspecialcharsbx($first_names_r); ?>">
		<input type="hidden" name="metki_f" value="<?echo htmlspecialcharsbx($metki_f); ?>">
		<input type="hidden" name="first_names_f" value="<?echo htmlspecialcharsbx($first_names_f); ?>">
	<?
	endif; ?>

	<?
	if ($STEP <> 3): ?>
		<?
		foreach ($_POST as $name => $value): ?>
			<?
			if (preg_match("/^field_(\\d+)$/", $name)): ?>
				<input type="hidden" name="<?echo $name ?>" value="<?echo htmlspecialcharsbx($value); ?>">
			<?
			endif
?>
		<?
		endforeach
		?>
		<input type="hidden" name="outFileAction" value="<?echo htmlspecialcharsbx($outFileAction); ?>">
		<input type="hidden" name="inFileAction" value="<?echo htmlspecialcharsbx($inFileAction); ?>">
		<input type="hidden" name="max_execution_time" value="<?echo htmlspecialcharsbx($max_execution_time); ?>">
	<?
	endif; ?>

	<?
	if ($STEP > 1): ?>
	<input type="submit" name="backButton" value="&lt;&lt; <?echo GetMessage("ivseo_btn_prev")?>">
	<?
	endif
?>
	<input type="submit" value="<?echo GetMessage("ivseo_btn_next")?> &gt;&gt;" name="submit_btn" class="adm-btn-save">
<?
	else: ?>
	<input type="submit" name="backButton2" value="&lt;&lt; <?echo GetMessage("ivseo_btn_to_first")?>" class="adm-btn-save">
<?
	endif; ?>

<?$tabControl->End();
?>

</form>

<script language="JavaScript">
<!--
<?if ($STEP < 2): ?>
tabControl.SelectTab("edit1");
tabControl.DisableTab("edit2");
tabControl.DisableTab("edit3");
tabControl.DisableTab("edit4");
<?elseif ($STEP == 2): ?>
tabControl.SelectTab("edit2");
tabControl.DisableTab("edit1");
tabControl.DisableTab("edit3");
tabControl.DisableTab("edit4");
<?elseif ($STEP == 3): ?>
tabControl.SelectTab("edit3");
tabControl.DisableTab("edit1");
tabControl.DisableTab("edit2");
tabControl.DisableTab("edit4");
<?elseif ($STEP > 3): ?>
tabControl.SelectTab("edit4");
tabControl.DisableTab("edit1");
tabControl.DisableTab("edit2");
tabControl.DisableTab("edit3");
<?endif; ?>
//-->
</script>

<?require ($DOCUMENT_ROOT."/bitrix/modules/main/include/epilog_admin.php");
?>
