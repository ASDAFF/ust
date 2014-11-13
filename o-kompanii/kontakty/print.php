<?
define("PRINT", "Y");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?>
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
	setTimeout(function() {
		window.print()
	}, 1000);
</script>
<div class="office"> 					 
	<div class="tit">Центральный офис:</div>
	<div class="address">
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/contacts/address.php"));?>
	</div>
	<div class="work-time"> 						 
		<div class="tit">Режим работы:</div>
		<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/contacts/shedule_work.php"));?>
	</div>
	<div class="phone"> 						 
		<div class="tit">Телефон:</div>
		<span><?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/phone.php"));?></span>
		<span><?=GetPhoneFromTown()?></span>
	</div>
	<div class="clear"></div>
</div>
<div class="map" id="map" style="height:480px;"></div>
<div class="warning"><?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/contacts/warning.php"));?></div>
<h2>Как добраться?</h2>
<div class="directs"> 					 
	<div class="public how-to-get"> 						 
		<p class="title">Общественным транспортом:</p>
		<p><?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/contacts/how_to_get_public_transport.php"
		));?></p>
	</div>
	<div class="auto how-to-get"> 						 
		<p class="title">На легковом автомобиле:</p>
		<p><b>С МКАД:</b></p>
		<p><?$APPLICATION->IncludeComponent("bitrix:main.include","",Array("AREA_FILE_SHOW" => "file","PATH" => SITE_DIR."include/contacts/how_to_get_auto_from_MKAD.php"));?></p>
		<p><b>Из центра:</b></p>
		<p><?$APPLICATION->IncludeComponent("bitrix:main.include","",Array("AREA_FILE_SHOW" => "file","PATH" => SITE_DIR."include/contacts/how_to_get_auto_from_center.php"));?></p>
	</div>
	<div class="truck how-to-get"> 						 
		<p class="title">На грузовом автомобиле (от 5 тонн):</p>
		<p><?$APPLICATION->IncludeComponent("bitrix:main.include","",Array("AREA_FILE_SHOW" => "file","PATH" => SITE_DIR."include/contacts/how_to_get_truck.php"	));?></p>
	</div>
	<div class="gps how-to-get"> 						 
		<p class="title">С помощью GPS-навигатора:</p>
		<p><?$APPLICATION->IncludeComponent("bitrix:main.include","",Array("AREA_FILE_SHOW" => "file","PATH" => SITE_DIR."include/contacts/how_to_get_gps.php"));?></p>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>