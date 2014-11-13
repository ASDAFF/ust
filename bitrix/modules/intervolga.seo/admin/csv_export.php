<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intervolga.seo/include.php");

IncludeModuleLangFile(__FILE__);

$POST_RIGHT = $APPLICATION->GetGroupRight("intervolga.seo");
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

// Реализовано на основе стандартного экспорта для инфоблоков
if ($STEP <= 0)
	$STEP = 1;
if ($_SERVER["REQUEST_METHOD"] == "POST" && strlen($backButton) > 0)
	$STEP = $STEP - 2;

$strError = "";
$DATA_FILE_NAME = "";
if ($STEP > 1) {
	if ($fields_type != "F" && $fields_type != "R")
		$strError .= GetMessage("ivseo_error_file_info");

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
		$strError .= GetMessage("ivseo_error_set_delim");
	
	$csvFile = new CCSVData();
	$csvFile->SetFieldsType($fields_type);
	if (strlen($strError) <= 0)
		$csvFile->SetDelimiter($delimiter_r_char);

	if (strlen($_REQUEST["DATA_FILE_NAME"]) <= 0) {
		$strError .= GetMessage("ivseo_error_file_result");
	} elseif (
		preg_match('/[^a-zA-Z0-9\s!#\$%&\(\)\[\]\{\}+\.;=@\^_\~\/\\\\\-]/i', $_REQUEST["DATA_FILE_NAME"])
		|| preg_match('/^[a-z]+:\\/\\//i', $_REQUEST["DATA_FILE_NAME"])
		|| HasScriptExtension($_REQUEST["DATA_FILE_NAME"])
		) {
		$strError .= GetMessage("ivseo_error_file_result");
	} else {
		$DATA_FILE_NAME = Rel2Abs("/", $_REQUEST["DATA_FILE_NAME"]);
		if (strtolower(substr($DATA_FILE_NAME, strlen($DATA_FILE_NAME)-4)) != ".csv")
			$DATA_FILE_NAME .= ".csv";
	}

	if (strlen($strError) <= 0) {
		$fp = fopen($_SERVER["DOCUMENT_ROOT"].$DATA_FILE_NAME, "w");
		if(!is_resource($fp)) {
			$strError .= GetMessage("ivseo_error_file_create");
			$DATA_FILE_NAME = "";
		} else {
			fclose($fp);
		}
	}

	$num_rows_writed = 0;
	if (strlen($strError) <= 0) {
		if($first_line_names == "Y") {
			$arSeoFields = array(
					"ID"
				,	"H1"
				,	"URL"
				,	"TITLE"
				,	"DESCRIPTION"
				,	"KEYWORDS"
				,	"ACTIVE"
				,	"CANONICAL"
				,	"TEXT1"
				,	"TEXT2"
				,	"TEXT3"
				,	"LID"
			);
			$csvFile->SaveFile($_SERVER["DOCUMENT_ROOT"].$DATA_FILE_NAME, $arSeoFields);
		}

		$rsSeo = CIvSeo::GetList();
		while($arSeoRow = $rsSeo->Fetch()) {
			$arSeoFields = array();
			$arSeoFields = array(
					$arSeoRow["ID"]
				,	$arSeoRow["H1"]
				,	$arSeoRow["URL"]
				,	$arSeoRow["TITLE"]
				,	$arSeoRow["DESCRIPTION"]
				,	$arSeoRow["KEYWORDS"]
				,	$arSeoRow["ACTIVE"]
				,	$arSeoRow["CANONICAL"]
				,	$arSeoRow["TEXT1"]
				,	$arSeoRow["TEXT2"]
				,	$arSeoRow["TEXT3"]
				,	$arSeoRow["LID"]
			);

			$csvFile->SaveFile($_SERVER["DOCUMENT_ROOT"].$DATA_FILE_NAME, $arSeoFields);
			$num_rows_writed++;
		}
	}
}

if (strlen($strError) > 0) $STEP = 1;

$APPLICATION->SetTitle(GetMessage("ivseo_page_title"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

CAdminMessage::ShowMessage($strError);
?>
<!-- WORK_AREA -->
<form method="POST" action="<?echo $sDocPath?>?lang=<?echo LANG ?>" ENCTYPE="multipart/form-data" name="dataload">

<input type="hidden" name="STEP" value="<?=$STEP+1?>">
<?=bitrix_sessid_post()?>
<?
$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("ivseo_table_tab1"), "ICON" => "iblock", "TITLE" => GetMessage("ivseo_table_tab1")),
	array("DIV" => "edit2", "TAB" => GetMessage("ivseo_table_tab2"), "ICON" => "iblock", "TITLE" => GetMessage("ivseo_table_tab2")),
);


$tabControl = new CAdminTabControl("tabControl", $aTabs, false, true);
$tabControl->Begin();

$tabControl->BeginNextTab();

if ($STEP < 2) {
?>
	<tr class="heading">
		<td colspan="2">
			<?echo GetMessage("ivseo_tab1_file_opt")?>
			<input type="hidden" name="fields_type" value="R">
		</td>
	</tr>
	<tr>
		<td width="40%" class="adm-detail-valign-top"><?echo GetMessage("ivseo_tab1_f_delim")?></td>
		<td width="60%">
			<input type="radio" name="delimiter_r" id="delimiter_TZP" value="TZP" checked><label for="delimiter_TZP"><?echo GetMessage("ivseo_tab1_f_delim_tzp")?></label><br>
			<input type="radio" name="delimiter_r" id="delimiter_ZPT" value="ZPT"><label for="delimiter_ZPT"><?echo GetMessage("ivseo_tab1_f_delim_zpt")?></label><br>
			<input type="radio" name="delimiter_r" id="delimiter_TAB" value="TAB"><label for="delimiter_TAB"><?echo GetMessage("ivseo_tab1_f_delim_tab")?></label><br>
			<input type="radio" name="delimiter_r" id="delimiter_SPS" value="SPS"><label for="delimiter_SPS"><?echo GetMessage("ivseo_tab1_f_delim_sp")?></label><br>
			<input type="radio" name="delimiter_r" id="delimiter_OTR" value="OTR"><label for="delimiter_OTR"><?echo GetMessage("ivseo_tab1_f_delim_other")?></label>
			<input type="text" name="delimiter_other_r" size="3" value="">
		</td>
	</tr>
	<tr>
		<td><?echo GetMessage("ivseo_tab1_f_first_row_cont_names")?></td>
		<td>
			<input type="checkbox" name="first_line_names" value="Y" checked>
		</td>
	</tr>

	<tr class="heading">
		<td colspan="2"><?echo GetMessage("ivseo_tab1_file_save_as")?></td>
	</tr>
	<tr>
		<td><?echo GetMessage("ivseo_tab1_file_name")?></td>
		<td>
			<input type="text" name="DATA_FILE_NAME" size="40" value="<?echo htmlspecialcharsbx(strlen($DATA_FILE_NAME) > 0? $DATA_FILE_NAME: "/".COption::GetOptionString("main", "upload_dir", "upload")."/iv_seo_".time().".csv")?>"><br>
			<?echo GetMessage("ivseo_tab1_file_name_note")?>
		</td>
	</tr>
	<?
}

$tabControl->EndTab();

$tabControl->BeginNextTab();

if ($STEP == 2) {
?>
	<tr>
		<td>
		<?echo CAdminMessage::ShowMessage(array(
			"TYPE" => "PROGRESS",
			"MESSAGE" => GetMessage("ivseo_save_complete"),
			"DETAILS" => GetMessage("ivseo_save_lines_sum").' '.$num_rows_writed.'<br>'.GetMessage("ivseo_save_file1").' <a href="'.htmlspecialcharsbx($DATA_FILE_NAME).'" target="_blank">'.htmlspecialcharsex($DATA_FILE_NAME).'</a> '.GetMessage("ivseo_save_file2"),
			"HTML" => true,
		))?>
		</td>
	</tr>
	<?
}
$tabControl->EndTab();

$tabControl->Buttons();
if ($STEP > 1):
?>
	<input type="submit" name="backButton" value="&lt;&lt; <?echo GetMessage("ivseo_btn_to_first")?>" class="adm-btn-save">
<?
else:
?>
	<input type="submit" value="<?echo GetMessage("ivseo_btn_start")?> &gt;&gt;" name="submit_btn" class="adm-btn-save">
<?
endif;

$tabControl->End();
?>
<script type="text/javaScript">
BX.ready(function() {
<?if ($STEP < 2):?>
	tabControl.SelectTab("edit1");
	tabControl.DisableTab("edit2");
<?elseif ($STEP == 2):?>
	tabControl.SelectTab("edit2");
	tabControl.DisableTab("edit1");
<?endif;?>
});
</script>

</form>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>