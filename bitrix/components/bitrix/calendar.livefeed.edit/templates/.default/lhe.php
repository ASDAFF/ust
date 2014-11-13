<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("fileman"))
	return;

if(!function_exists('CustomizeLightEditorForCalendar'))
{
	function CustomizeLightEditorForCalendar($id = false, $templateFolder = "")
	{
		static $bCalled = array();
		static $sTemplateFolder = "";
		if ($templateFolder != "")
			$sTemplateFolder = $templateFolder;

		if ($id && $bCalled[$id] !== true)
		{
?>
<script>
(function(window){
function checkEditorCustomized()
{
	if (!window.ModifyEditorForCalendar)
		return setTimeout(checkEditorCustomized, 20);

	window.ModifyEditorForCalendar('<?=$id?>', {
		path: '<?= CUtil::JSEscape($sTemplateFolder)?>',
		imageLinkText: '<?=GetMessageJS("MPF_IMAGE_LINK")?>',
		spoilerText: '<?=GetMessageJS("MPF_SPOILER")?>',
		videoText: '<?=GetMessageJS("FPF_VIDEO")?>',
		videoUploadText: '<?= GetMessageJS("BPC_VIDEO_P")?>',
		videoUploadText1: '<?= GetMessageJS("BPC_VIDEO_PATH_EXAMPLE")?>',
		videoUploadText2: '<?= GetMessageJS("FPF_VIDEO")?>',
		videoUploadText3: '<?=GetMessageJS("MPF_VIDEO_SIZE")?>'
	});
}
checkEditorCustomized();
})(window);
</script>
		<?
			$bCalled[$id] = true;
		}
	}
	CustomizeLightEditorForCalendar(false, $templateFolder);
}

AddEventHandler("fileman", "OnIncludeLightEditorScript", "CustomizeLightEditorForCalendar");
?>
<div>
	<?
	$LHE = new CLightHTMLEditor;
	$LHE->Show($arParams["LHE"]);
	?>
	<!-- Buttons-->
	<div class="feed-event-form-but-wrap">
		<span class="feed-event-form-but feed-add-file" id="bx_b_file_<?= $id?>" title="<?= GetMessage('ECLF_LHE_UPLOAD_FILE')?>"></span>
		<span class="feed-event-form-but feed-add-link" id="bx_b_link_<?= $id?>" title="<?= GetMessage('ECLF_LHE_CREATE_LINK')?>"></span>
		<span class="feed-event-form-but feed-add-video" id="bx_b_video_<?= $id?>" title="<?= GetMessage('ECLF_LHE_VIDEO')?>"></span>
		<div class="feed-event-form-but-more-open"><span title="<?= GetMessage('ECLF_LHE_SHOW_PANEL')?>" class="feed-event-form-editor-btn" id="cal-editor-show-panel-btn"></span></div>
	</div>
</div>