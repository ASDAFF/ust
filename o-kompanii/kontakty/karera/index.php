<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?> 
<script type="text/javascript" src="/design/js/jquery.swfobject.1-1-1.min.js"></script> 
<script type="text/javascript" src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&amp;lang=ru-RU"></script>
<script type="text/javascript">
	ymaps.ready(init);
	var myMap, myPlacemark;
	function init(){
		myMap = new ymaps.Map ("map", {
			center: [55.840990,37.448617],
			zoom: 15
		}); 
		myMap.controls.add(new ymaps.control.ZoomControl());
		myMap.controls.add(new ymaps.control.MapTools());
		myMap.controls.add('typeSelector');

		myCollection = new ymaps.GeoObjectCollection({});
		myMap.geoObjects.add(myCollection);
		ymaps.option.presetStorage.add('my#preset', {
			iconLayout:"default#imageWithContent",
			iconImageHref: '/design/images/css/map-buble.png',
			iconImageSize: [140, 81],
			iconImageOffset: [-13, -74],
			iconContentOffset:[0, 0]
		});
		myCollection.removeAll();
		myPlacemark = new ymaps.Placemark(
			[55.840990,37.448617], 
			{},
			{preset: 'my#preset'}
		);
		myCollection.add(myPlacemark);
	}
</script> 
<div class="contacts-page vcard"> 	 
	<div class="contacts-left-col"> 		 
		<div class="phones tel"> 			
			 		
			<span><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"PATH" => SITE_DIR."include/phone.php"
				)
				);?>
			</span> 
			 
		 
			<span><?=GetPhoneFromTown()?>/1555</span>
		</div>
		<div class="mail"><a class="e-mail" title="Написать письмо" href="mailto:hr@ust-co.ru">hr@ust-co.ru</a></div>

		<div class="work-time"> 			 
			<div class="title">График работы:</div>
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"PATH" => SITE_DIR."include/contacts/shedule_work.php"
				)
			);?> 
		</div>
		<?$APPLICATION->IncludeComponent("areal:form.feedback", ".default");?> 	
	</div>

	<div class="contacts-right-col"> 		 
		<div class="col-title">Центральный офис</div>
		<div class="address adr"> 			
			<img src="/images/contacts-photo.jpg" alt="Центральный офис" title="Центральный офис" class="photo" /> 			 
			<div class="sub-title"><strong><font size="2">Адрес:</font></strong></div>
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"PATH" => SITE_DIR."include/contacts/address.php",
					"EDIT_TEMPLATE" => ""
				)
			);?>
			 
			<div class="hint">	
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"PATH" => SITE_DIR."include/contacts/address_hint.php",
						"EDIT_TEMPLATE" => ""
					)
				);?> 
				<a href="#" class="contact-detailed" >Подробнее <span></span></a>
			</div>
		</div>
		<div class="office-details"> 			 
			<p><b>Центральный офис</b></p>
			<p>
				Адрес: 
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"PATH" => SITE_DIR."include/contacts/address.php",
						"EDIT_TEMPLATE" => ""
					)
				);?>
			</p>
			<p><i>Общественным транспортом:</i></p>
			<p><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"PATH" => SITE_DIR."include/contacts/how_to_get_public_transport.php"
				)
			);?></p>
			<p><i>На легковом автомобиле:</i></p>
			<p><i>С МКАД:</i></p>
			<p><?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/contacts/how_to_get_auto_from_MKAD.php"
			)
			);?></p>
			<p><i>Из центра:</i></p>
			<p><?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/contacts/how_to_get_auto_from_center.php"
			)
			);?></p>
			<p><i>На грузовом автомобиле (от 5 тонн):</i></p>
			<p><?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
			"AREA_FILE_SHOW" => "file",
			"PATH" => SITE_DIR."include/contacts/how_to_get_truck.php"
			)
			);?></p>
			<a href="#" class="contact-short opened" >Свернуть<span></span></a> 		
		</div>

		<div class="warning">
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
					"AREA_FILE_SHOW" => "file",
					"PATH" => SITE_DIR."include/contacts/warning.php"
				)
			);?>
		</div>

		<div class="links">
			<?if(COption::GetOptionInt("ust", "ust_print_link") == 1):?>
				<a href="print.php" title="Распечатать схему проезда" target="_blank" ><b>Распечатать схему проезда</b></a>
			<?endif;?>
			<a href="#" class="contacts-map-link" ><b>Как добраться</b></a> 			 
			<div class="dialog" id="contacts-map"> 				 
				<div class="office"> 					 
				<div class="tit">Центральный офис:</div>

				<div class="address"><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/contacts/address.php"
				)
				);?></div>
				<hr /> 					 
				<div class="work-time"> 						 
				<div class="tit">Режим работы:</div>
				<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/contacts/shedule_work.php"
				)
				);?> 					</div>

				<div class="phone"> 						 
				<div class="tit">Телефон:</div>
				<span><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/phone.php"
				)
				);?></span> 						<span><?=GetPhoneFromTown()?></span> 					</div>

				<div class="clear"></div>
				</div>

				<div class="flashmap"> 					 
				<div class="print-sheme"><a href="#" >Распечатать схему проезда</a></div>

				<div class="map">
					<img src="/images/map.gif" width="770" height="520" alt="Схема проезда" title="Схема проезда">
				</div>
				</div>

				<div class="warning"><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/contacts/warning.php"
				)
				);?></div>

				<div class="show-directs"> 					 
				<div class="toggler">Подробное текстовое описание<span></span></div>
				</div>

				<div class="directs"> 					 
				<div class="public how-to-get"> 						 
				<p class="title">Общественным транспортом:</p>

				<p><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/contacts/how_to_get_public_transport.php"
				)
				);?></p>
				</div>

				<div class="auto how-to-get"> 						 
				<p class="title">На легковом автомобиле:</p>

				<p><b>С МКАД:</b></p>

				<p><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/contacts/how_to_get_auto_from_MKAD.php"
				)
				);?></p>

				<p><b>Из центра:</b></p>

				<p><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/contacts/how_to_get_auto_from_center.php"
				)
				);?></p>
				</div>

				<div class="truck how-to-get"> 						 
				<p class="title">На грузовом автомобиле (от 5 тонн):</p>

				<p><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/contacts/how_to_get_truck.php"
				)
				);?></p>
				</div>

				<div class="gps how-to-get"> 						 
				<p class="title">С помощью GPS-навигатора:</p>

				<p><?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				"",
				Array(
				"AREA_FILE_SHOW" => "file",
				"PATH" => SITE_DIR."include/contacts/how_to_get_gps.php"
				)
				);?></p>
				</div>
				<hr /> 					 
				<div class="hide-directs"> 						 
				<div class="toggler opened">Свернуть<span></span></div>
				</div>
				</div>
			</div>
		</div>
		<div class="map" id="map" style="height:250px;"></div>
		<div class="links"><a href="/filialy/" ><b>Филиалы и дилеров на карте</b></a></div>
	</div>
	<div class="clear"></div>
</div>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>