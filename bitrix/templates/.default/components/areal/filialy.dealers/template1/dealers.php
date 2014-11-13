<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["DEALERS"])):?>
	<div class="filialy">
		<div class="left filialy">
			<div class="tabs-plate">
				<div class="tabs" id="tab_link">
					<ul>
						<li><a href="/filialy/" title="Филиалы УСТ">Филиалы УСТ</a></li>
						<li class="active_li"><a href="/dilery-skladskoj-tehniki/" title="Дилеры по складской технике">Дилеры по складской технике</a></li>
						<a id="selected" href=""></a>
						<div class="clear"></div>
					</ul>
				</div>
			</div>	
			<div id="map">
				<ul class="dealers-map" data-attr="map-holder">
					<li class="regions-list"><ul></ul></li>
					<li class="cities-list"><ul></ul></li>
					<li class="dots-list"><ul></ul></li>
					<li class="areas-list">
						<img class="overlay" src="/design/map/images/img-overlay.gif" usemap="#dealers-map" width="726" height="434" *>
						<map id="dealers-map" name="dealers-map"></map>
					</li>
				</ul>
			</div>
			<form name="dealers" method="post" action="<?=$APPLICATION->GetCurPage(false)?>">
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
			<h2>Диллерские представительства</h2>
			<div id="pane" class="scroll-pane">
				<?reset($arResult["DEALERS"]); $first_key = key($arResult["DEALERS"]);?>
				<?foreach($arResult["DEALERS"] as $key => $Dealer):?>
					<?if(isset($arResult["ACTIVE_TOWN_CODE"]) && isset($arResult["DEALERS"][$arResult["ACTIVE_TOWN_CODE"]]) && $key == $arResult["ACTIVE_TOWN_CODE"]):?>
						<?$class="active";?>
					<?elseif(!isset($arResult["DEALERS"][$arResult["ACTIVE_TOWN_CODE"]]) && $key == $first_key):?>
						<?$class="active";?>
					<?else:?>
						<?$class="";?>
					<?endif;?>
					<a href="#" class="open <?=$class?>" id="element_<?=$key?>" title="<?=trim($Dealer[0]["TOWN_NAME"])?>"><span class="name"><?=$Dealer[0]["TOWN_NAME"]?></span></a>
					<div class="element element_<?=$key?> <?=$class?>">
						<?foreach($Dealer as $arDealer):?>
							<p><b><?=$arDealer["NAME"]?></b></p>
							<?if(!empty($arDealer["ADDRESS"])):?>
								<p>Адрес: <br /><?=$arDealer["ADDRESS"]?></p>
							<?endif;?>
							<?if(!empty($arDealer["PHONE"])):?>
								<p>Тел: <br /><?=$arDealer["PHONE"]?></p>
							<?endif;?>
							<?if(!empty($arDealer["EMAIL"])):?>
								<p>Email: <br /><a class="e-mail" title="<?=$arDealer["EMAIL"][0]?>" href="#<?=$arDealer["EMAIL"][1]?>"></a></p>
							<?endif;?>
							<?if(!empty($arDealer["WEB_SITE"])):?>
								<p><a href="http://<?=$arDealer["WEB_SITE"]?>" target="_blank" title="<?=$arDealer["NAME"]?>"><?=$arDealer["WEB_SITE"]?></a></p>
							<?endif;?>
							<?if(!empty($arDealer["WORK_SHEDULE"]["TEXT"])):?>
								<p>Режим работы:<br /><?=$arDealer["WORK_SHEDULE"]["TEXT"]?></p>
							<?endif;?>
							<?if(count($Dealer) > 1):?><br /><?endif;?>
						<?endforeach;?>
					</div>
				<?endforeach;?>
			</div>
		</div>
		<div class="clear"></div>	
	</div>
<?endif;?>
<div class="text">
	<a class="show-more" href="#" title="Подробнее">Диллеры складской техники в России <span></span></a>  
	<div class="more"><?$APPLICATION->IncludeComponent("bitrix:main.include", "catalog", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/dealers.php"), false);?></div>
</div>

<script type="text/javascript">
	<?if(!empty($arResult["POINTS"])):?>
		var dealersCities = new Array();
		<?foreach($arResult["POINTS"] as $key => $point):?>
			dealersCities[dealersCities.length] = {
				"key": "<?=$key?>",
				"name": "<?=$point["NAME"]?>",
				"area": "<?=$point["POINT"]?>"
			};
		<?endforeach;?>
	<?endif;?>
</script>