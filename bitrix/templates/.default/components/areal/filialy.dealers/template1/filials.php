<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["FILIAL"])):?>
	<div class="filialy">
		<div class="left">
			<div class="tabs-plate">
				<div class="tabs" id="tab_link">
					<ul>
						<li class="active_li"><a href="/filialy/" title="Филиалы УСТ">Филиалы УСТ</a></li>
						<li><a href="/dilery-skladskoj-tehniki/" title="Дилеры по складской технике">Дилеры по складской технике</a></li>
						<a id="selected" href=""></a>
						<div class="clear"></div>
					</ul>
				</div>
			</div>	
			<div id="map">
				<ul class="offices-map" data-attr="map-holder">
					<li class="regions-list"><ul></ul></li>
					<li class="cities-list"><ul></ul></li>
					<li class="dots-list"><ul></ul></li>
					<li class="areas-list">
						<img class="overlay" src="/design/map/images/img-overlay.gif" usemap="#offices-map" width="693" height="479">
						<map id="offices-map" name="offices-map">
						</map>
					</li>
				</ul>
			</div>
			<form name="filials" method="post" action="<?=$APPLICATION->GetCurPage(false)?>">
				<div class="line autocomplete small">
					<input type="text" class="autocomplete town required" name="TOWN" value="<?=$arResult["ACTIVE_TOWN_NAME"]?>" />
					<button type="submit" name="change_town">Выбрать</button>
					<div class="b-places"></div>
					<div class="clear"></div>
					<p class="errortext"></p>
				</div>
			</form>
		</div>
		<div class="right scrollBox">
			<h2>Филиалы УСТ</h2>
			<div id="pane" class="scroll-pane">
				<?reset($arResult["FILIAL"]); $first_key = key($arResult["FILIAL"]);?>
				<?foreach($arResult["FILIAL"] as $key => $arFilial):?>	
					<?if(isset($arResult["ACTIVE_TOWN_CODE"]) && isset($arResult["FILIAL"][$arResult["ACTIVE_TOWN_CODE"]]) && $key == $arResult["ACTIVE_TOWN_CODE"]):?>
						<?$class="active";?>
					<?elseif(!isset($arResult["FILIAL"][$arResult["ACTIVE_TOWN_CODE"]]) && $key == $first_key):?>
						<?$class="active";?>
					<?else:?>
						<?$class="";?>
					<?endif;?>
					<a href="#" class="open <?=$class?>" id="element_<?=$key?>" title="<?=$arFilial["TOWN_NAME"]?>"><span class="name"><?=$arFilial["TOWN_NAME"]?></span></a>
					<div class="element element_<?=$key?> <?=$class?>">
						<?if(!empty($arFilial["SERVICES"])):?>
							<div class="services">
                                                            <?
                                                            $k=0;
                                                            
                                                            ?>
								<?foreach($arFilial["SERVICES"] as $service):?>
                                                            <?$k++;?>
									<a href="<?=$service["URL"]?>" class="image service-icon filials ico<?=$k?>">
										<table><tr><td>
											<img detail-pic="<?=$service["DETAIL"]["src"]?>" src="<?=$service["PICTURE"]["src"]?>" width="<?=$service["PICTURE"]["width"]?>" height="<?=$service["PICTURE"]["height"]?>" />
										</td></tr></table>
										<span><?=$service["NAME"]?></span>
									</a>
								<?endforeach;?>
								<div class="clear"></div>
							</div>
						<?endif;?>
						<?if(!empty($arFilial["ADDRESS"])):?>
							<p>Адрес:<br /><a href="<?=$arFilial["LINK"]?>" title="Подробная информация"><?=$arFilial["ADDRESS"]?> (Подробная информация)</a></p>
						<?endif;?>
						<?if(!empty($arFilial["PHONE"])):?>
							<p>Тел:<br /><?=$arFilial["PHONE"]?></p>
						<?endif;?>
						<?if(!empty($arFilial["WORK_SHEDULE"]["TEXT"])):?>
							<p>График работы:<br /><?=$arFilial["WORK_SHEDULE"]["TEXT"]?></p>
						<?endif;?>
						<?if(!empty($arFilial["EMAIL"])):?>
							<p>Email:<br /><a class="e-mail" title="<?=$arFilial["EMAIL"][0]?>" href="#<?=$arFilial["EMAIL"][1]?>"></a></p>
						<?endif;?>
						<?if(!empty($arFilial["PREVIEW_TEXT"])):?>
							<?if($arFilial["PREVIEW_TEXT_TYPE"] == "text"):?>
								<p><?=$arFilial["PREVIEW_TEXT"]?></p>
							<?else:?>
								<?=$arFilial["PREVIEW_TEXT"]?>
							<?endif;?>
						<?endif;?>
					</div>
				<?endforeach;?>				
			</div>
			<span class="description"></span>
		</div>
		<div class="clear"></div>
		<div class="text">
			<a class="show-more" href="#" title="Подробнее">Филиалы компании Универсал-Спецтехника в России <span></span></a>  
			<div class="more"><?$APPLICATION->IncludeComponent("bitrix:main.include", "catalog", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/filialy.php"), false);?></div>
		</div>
	</div>
	<script type="text/javascript">
		<?if(!empty($arResult["POINTS"])):?>
			var officesCities = new Array();
			<?foreach($arResult["POINTS"] as $key => $point):?>
				officesCities[officesCities.length] = {
					"key": "<?=$key?>",
					"name": "<?=$point["NAME"]?>",
					"area": "<?=$point["POINT"]?>"
				};
			<?endforeach;?>
		<?endif;?>
	</script>
<?else:?>
	<p>К сожалению, нет доступной информации о филиалах.</p>
<?endif;?>