<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
?>
			</div>
		</div>
		<footer class="footer">
			<div class="left">
				<hr />
				<div class="copy">
					&copy; 2005 - <?=date("Y")?> ООО &laquo;Универсал-Спецтехника&raquo;<br />
					Все права защищены.
				</div>
				<div class="mail">Эл. почта: <a class="e-mail" title="info" href="#ust-co.ru" ></a></div>
				<div class="phone">
					<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/phone.php"), false);?><br /><?=GetPhoneFromTown()?>
				</div>
			</div>
			<div class="print"></div>
			<div class="clear"></div>
		</footer>
	</body>
</html>