<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["FILIAL"]["POINT_ON_MAP"])):?>
	<script type="text/javascript" src="http://api-maps.yandex.ru/2.0-stable/?load=package.standard&amp;lang=ru-RU"></script>
	<script type="text/javascript">
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
<?if($USER->GetID() == 8):?>
	<pre style="display:none;"><?print_r($arResult)?></pre>	
<?endif;?>
<div class="filial-detail">
	<h1>Филиал <?=$arResult["FILIAL"]["NAME"]?></h1>
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
			
			<?if(!empty($arResult["FILIAL"]["PHOTO"])):?>
				<div class="ppic detail">
					<div class="image-main image">
						<?if($arResult["FILIAL"]["PHOTO"][0]["SHOW_BIG"] == 1):?>
							<a class="fancybox" href="<?=$arResult["FILIAL"]["PHOTO"][0]["NATURE"]?>" >
						<?endif;?>
								<table><tr><td>
									<img src="<?=$arResult["FILIAL"]["PHOTO"][0]["STANDART"]["src"]?>" width="<?=$arResult["FILIAL"]["PHOTO"][0]["STANDART"]["width"]?>" height="<?=$arResult["FILIAL"]["PHOTO"][0]["STANDART"]["height"]?>" alt="<?=$arPhoto["NAME"]?>" title="<?=$arPhoto["NAME"]?>" />
								</td></tr></table>
						<?if($arResult["FILIAL"]["PHOTO"][0]["SHOW_BIG"] == 1):?>
							</a>
						<?endif;?>
					</div>
					<?if(count($arResult["FILIAL"]["PHOTO"])>1):?>
						<div class="jcarousel" id="carousel-detail">
							<ul>
								<?foreach($arResult["FILIAL"]["PHOTO"] as $key => $arPhoto):?>
									<?if($key > 0):?>
										<li class="image">
											<?if($arPhoto["SHOW_BIG"] == 1):?>
												<a href="<?=$arPhoto["NATURE"]?>" class="fancybox" rel="galery">
											<?endif;?>
												<table><tr><td>
														<img src="<?=$arPhoto["SMALL"]["src"]?>" width="<?=$arPhoto["SMALL"]["width"]?>" height="<?=$arPhoto["SMALL"]["height"]?>" alt="<?=$arPhoto["NAME"]?>" title="<?=$arPhoto["NAME"]?>" />
													</td></tr></table>
											<?if($arPhoto["SHOW_BIG"] == 1):?>
												</a>
											<?endif;?>
										</li>
									<?endif;?>
								<?endforeach;?>
							</ul>
							<?if(count($arResult["FILIAL"]["PHOTO"]) > 4):?>
								<button class="jcarousel-prev"></button>
								<button class="jcarousel-next"></button>
							<?endif;?>
						</div>
					<?endif;?>
					<div class="cl"></div>
				</div>
			<?endif;?>
			<?/*if(!empty($arResult["FILIAL"]["CONSULTANT"])):?>
				<div class="tit_small">Консультант:</div>
				<p><?=$arResult["FILIAL"]["CONSULTANT"]["NAME"]?></p>
				<p><?=$arResult["FILIAL"]["CONSULTANT"]["PHONE"]?></p>
				<p><?=$arResult["FILIAL"]["CONSULTANT"]["EMAIL"]?></p>
			<?endif;*/?>
			<a href="<?=$APPLICATION->GetCurPage(false)?>?print=y" target="_blank" class="print" title="Распечатать схему проезда">Распечатать схему проезда</a>
		</div>
		<?if(!empty($arResult["FILIAL"]["SERVICES"])):?>
			<div class="services">
				<?foreach($arResult["FILIAL"]["SERVICES"] as $arService):?>
					<a href="<?=$arService["URL"]?>" class="image service-icon">
						<img detail-pic="<?=$arService["DETAIL"]["src"]?>" src="<?=$arService["PICTURE"]["src"]?>" width="<?=$arService["PICTURE"]["width"]?>" height="<?=$arService["PICTURE"]["height"]?>" />
						<span><i></i><?=$arService["NAME"]?></span>
					</a>
				<?endforeach;?>
			</div>
		<?endif;?>
		<div class="clear"></div>
	</div>
	<div class="right">
		<div class="map" id="map" style="height:480px;"></div>
	</div>
	<div class="clear"></div>
	<?if(!empty($arResult["FILIAL"]["HOW_TO_GET"]["TEXT"])):?>
		<div class="tit_small">Как добраться:</div>						 
		<?if($arResult["FILIAL"]["HOW_TO_GET"]["TYPE"] == "html"):?>
			<?=$arResult["FILIAL"]["HOW_TO_GET"]["TEXT"]?>
		<?else:?>
			<p><?=$arResult["FILIAL"]["HOW_TO_GET"]["TEXT"]?></p>
		<?endif;?>
	<?endif;?>
	<div class="clear"></div>
	<!-- <pre style="display:none;"> -->
	
	<!-- </pre> -->
	<?if(!empty($arResult["FILIAL"]["DETAIL_TEXT"])):?>
	<?/*?>
	<div class="text">
		<a class="show-more" href="#" title="Подробнее"><?if(!empty($arResult["FILIAL"]["SHORT_DETAIL_LINK"])):?><?=$arResult["FILIAL"]["SHORT_DETAIL_LINK"]?><?else:?>Филиалы компании Универсал-Спецтехника в России<?endif;?> <span></span></a>  
		<div class="more">
			<?if($arResult["FILIAL"]["DETAIL_TEXT_TYPE"] == "html"):?>
				<?=$arResult["FILIAL"]["DETAIL_TEXT"]?>
			<?else:?>
				<p><?=$arResult["FILIAL"]["DETAIL_TEXT"]?></p>
			<?endif;?>
		</div>
	</div>
	<?*/?>
	<?endif;?>
</div>