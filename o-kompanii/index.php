<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О компании");
?>
<div class="about-page">
	<div class="mission">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/about_company/mision.php"), false);?>	
	</div>
	<div class="company-plate">
		<?$APPLICATION->IncludeComponent("areal:about.company.banner", ".default", array("LOCATION" => "TOP"));?>
		<div class="counters">
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/about_company/counters.php"), false);?>		
		</div>
	</div>
	<div class="clear"></div>
	<div class="why-us">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/about_company/why-us.php"), false);?>
	</div>
	<?$APPLICATION->IncludeComponent("areal:about.company.activities", ".default");?>
	<hr class="about_company" />
	<?$APPLICATION->IncludeComponent("areal:about.company.banner", ".default", array("LOCATION" => "BOTTOM"));?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>