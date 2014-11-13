<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карьера в УСТ");?>
<div class="karera-page">
	<p><b><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/about_company/karera/slogan.php"), false);?></b></p>
	<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/about_company/karera/description.php"), false);?>
	<p class="slogan"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/about_company/karera/deviz.php"), false);?></p>
	<div class="video">
		<?$APPLICATION->IncludeComponent("bitrix:player", ".default", array(
			"PLAYER_TYPE" => "auto",
			"USE_PLAYLIST" => "N",
			"PATH" => "http://youtu.be/zK-Y5W4Vcv4",
			"PROVIDER" => "",
			"STREAMER" => "bitrix.swf",
			"WIDTH" => "573",
			"HEIGHT" => "356",
			"FILE_DURATION" => "",
			"FILE_AUTHOR" => "",
			"FILE_DATE" => "",
			"FILE_DESCRIPTION" => "",
			"SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
			"SKIN" => "",
			"CONTROLBAR" => "bottom",
			"WMODE" => "opaque",
			"LOGO" => "",
			"LOGO_LINK" => "",
			"LOGO_POSITION" => "none",
			"AUTOSTART" => "N",
			"REPEAT" => "none",
			"VOLUME" => "100",
			"MUTE" => "N",
			"PLUGINS" => array(
				0 => "",
				1 => "",
			),
			"ADDITIONAL_FLASHVARS" => "",
			"ADVANCED_MODE_SETTINGS" => "Y",
			"PLAYER_ID" => "",
			"BUFFER_LENGTH" => "10",
			"ALLOW_SWF" => "N"
			),
			false
		);?>
	</div>
	<div class="recomended border_gray image">
		<span class="title">Нас рекомендуют:</span>
		<table>
			<tr class="small-logo">
				<td><img src="/images/hh-logo.png" width="81" height="49" alt="" title="" /></td>
				<td><img src="/images/superjob-logo.png" width="120" height="49" alt="" title="" /></td>
			</tr>
			<tr>
				<td><a class="fancybox" rel="sertificates" href="/images/HeadHunter.jpg" title="Посмотреть"><img src="/images/hh-sertificat.png" width="118" height="167" alt="" title="" /></a></td>
				<td><a class="fancybox" rel="sertificates" href="/images/SuperJob.jpg" title="Посмотреть"><img src="/images/superjob-sertificat.png" width="120" height="167" alt="" title="" /></a></td>
			</tr>
		</table>
	</div>
	<div class="clear"></div>
	<?/*<div class="text">
		<a href="#" class="show-more" title="Подробнее">Полный текст обращения Президента компании &laquo;Универсал-Спецтехника&raquo;<span></span></a>
		<div class="more"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/about_company/karera/president_message.php"), false);?></div>
</div>*/?>
	<?$APPLICATION->IncludeComponent("areal:career", ".default")?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>