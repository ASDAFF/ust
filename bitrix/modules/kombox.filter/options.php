<?
IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle(GetMessage("KOMBOX_MODULE_FILTER_OPTIONS_TAB_1"));		
if ($USER->IsAdmin()):
	
	if ($_POST['Update'] && check_bitrix_sessid()) {
		$paths = $_POST['sef_paths'];
		COption::SetOptionString('kombox.filter', "sef_paths", $paths);
	}
	
	$paths = COption::GetOptionString('kombox.filter', "sef_paths");
	
	if(isset($_POST['sef_paths']))
		$paths = $_POST['sef_paths'];
		
	$aTabs = array();
	$aTabs[] = array("DIV" => "edit1", "TAB" => GetMessage("KOMBOX_MODULE_FILTER_OPTIONS_TAB_1"), "ICON" => "settings", "TITLE" => GetMessage("KOMBOX_MODULE_FILTER_OPTIONS_TAB_1_TITLE"));
	
	$tabControl = new CAdminTabControl("tabControl", $aTabs);
	$tabControl->Begin();?>
	<form name="kombox_filter_options" method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&amp;lang=<?echo LANG?>&amp;mid_menu=1">
		<?=bitrix_sessid_post();?>
		<?$tabControl->BeginNextTab();?>
			<tr>
				<td width="40%" class="adm-detail-valign-top adm-detail-content-cell-l"><?=GetMessage('KOMBOX_MODULE_FILTER_OPTIONS_PATHS');?>:</td>
				<td width="60%" class="adm-detail-content-cell-r">
					<textarea rows="5" name="sef_paths" style="width:100%"><?echo htmlspecialcharsbx($paths)?></textarea>
					<?
					echo BeginNote();
					echo GetMessage("KOMBOX_MODULE_FILTER_OPTIONS_PATHS_TIPS");
					echo EndNote();
					?>
				</td>
			</tr>
		<?$tabControl->Buttons();?>
		<input type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>">
		<?$tabControl->End();?>
	</form>
<?endif;?>