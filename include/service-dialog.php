<div class="dialog" id="service"> 	
	<input type="hidden" name="count_service_up" value="<?=$_SESSION["SERVICE_WINDOW"]?>" />
	<div class="title">Служба сервиса<br />&laquo;Универсал-Спецтехника&raquo;</div>
	<p class="graphic">Круглосуточно и без выходных</p>
	<div class="images">
		<img src="/images/service_2.jpg" width="240" height="240" />
		<img src="/images/service_1.jpg" width="240" height="240" />
		<img src="/images/service_3.jpg" width="240" height="240" class="last" /> 		 
		<div class="clear"></div>
	</div>
	<p>Мы готовы решить Ваши проблемы с обслуживанием техники в любой день, в том числе в выходные и праздничные дни, КРУГЛОСУТОЧНО!</p>
	<div class="red_contacts">
		<a class="e-mail" title="service" href="#ust-co.ru" ></a>&nbsp;&nbsp;&nbsp;
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/phone.php"));?>
	</div>
	<p class="center">
		<a href="#" id="phone_support" title="Телефоны круглосуточной службы поддержки" ><u>Телефоны круглосуточной службы поддержки</u></a>
	</p>
</div>