<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["FILIAL"]["POINT_ON_MAP"])):?>
	<script type="text/javascript">
		//alert(window.navigator);
		ymaps.ready(init);
		var myMap, myPlacemark;
		function init(){
			myMap = new ymaps.Map ("map", {
				center: [<?=$arResult["FILIAL"]["POINT_ON_MAP"][1]?>, <?=$arResult["FILIAL"]["POINT_ON_MAP"][0]?>],
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
				[<?=$arResult["FILIAL"]["POINT_ON_MAP"][1]?>, <?=$arResult["FILIAL"]["POINT_ON_MAP"][0]?>], 
				{},
				{preset: 'my#preset'}
			);
			myCollection.add(myPlacemark);
		}
	</script>
<?endif;?>
<div class="filial-detail">
	<div class="left">
		<div class="office">
			<p class="address"><?=$arResult["FILIAL"]["ADDRESS"]?></p>
			<?if(!empty($arResult["FILIAL"]["WORK_SHEDULE"]["TEXT"])):?>
				<div class="work-time"> 						 
					<div class="tit">Режим работы:</div>
					<?=$arResult["FILIAL"]["WORK_SHEDULE"]["TEXT"]?>
				</div>
			<?endif;?>
			<?if(!empty($arResult["FILIAL"]["PHONE"])):?>
				<div class="phone"> 						 
					<div class="tit">Контакты:</div>
					<span><?=$arResult["FILIAL"]["PHONE"]?></span>
					<?if(!empty($arResult["FILIAL"]["EMAIL"])):?>
						<a class="e-mail" title="<?=$arResult["FILIAL"]["EMAIL"][0]?>" href="#<?=$arResult["FILIAL"]["EMAIL"][1]?>" ></a>
					<?endif;?>
				</div>
			<?endif;?>
			<div class="clear"></div>
			
			<?if(!empty($arResult["FILIAL"]["HOW_TO_GET"]["TEXT"])):?>
				<div class="tit_small">Как добраться:</div>						 
				<?if($arResult["FILIAL"]["HOW_TO_GET"]["TYPE"] == "html"):?>
					<?=$arResult["FILIAL"]["HOW_TO_GET"]["TEXT"]?>
				<?else:?>
					<p><?=$arResult["FILIAL"]["HOW_TO_GET"]["TEXT"]?></p>
				<?endif;?>
			<?endif;?>
			
			<?/*if(!empty($arResult["FILIAL"]["CONSULTANT"])):?>
				<div class="tit_small">Консультант:</div>
				<p><?=$arResult["FILIAL"]["CONSULTANT"]["NAME"]?></p>
				<p><?=$arResult["FILIAL"]["CONSULTANT"]["PHONE"]?></p>
				<p><?=$arResult["FILIAL"]["CONSULTANT"]["EMAIL"]?></p>
			<?endif;*/?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="right">
		<div class="map" id="map" style="height:318px;"></div>
	</div>
	<div class="clear"></div>
	<?if(!empty($arResult["FILIAL"]["DETAIL_TEXT"])):?>
		<?if($arResult["FILIAL"]["DETAIL_TEXT_TYPE"] == "html"):?>
			<?=$arResult["FILIAL"]["DETAIL_TEXT"]?>
		<?else:?>
			<p><?=$arResult["FILIAL"]["DETAIL_TEXT"]?></p>
		<?endif;?>
	<?endif;?>
</div>