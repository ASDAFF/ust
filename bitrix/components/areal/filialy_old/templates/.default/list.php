<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
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
		<form name="filials" method="get" action="<?=$APPLICATION->GetCurPage(false)?>">
			<div class="line autocomplete small">
				<input type="text" class="autocomplete town required" name="TOWN" value="<?=$arResult["TOWN_NAME"]?>" />
				<button type="submit" name="change_town">Выбрать</button>
				<div class="b-places"></div>
				<div class="clear"></div>
			</div>
		</form>
	</div>
	<div class="right scrollBox">
		<h2>Филиалы УСТ</h2>
		<div id="pane" class="scroll-pane">
			<?$APPLICATION->IncludeComponent("areal:filialy.list", ".default", array("TYPE" => $arParams["TYPE"], "ID" => $arParams["ID"], "NAME" => $arParams["NAME"]));?>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="text">
	<a class="show-more" href="#" title="Подробнее">Филиалы компании Универсал-Спецтехника в России <span></span></a>  
	<div class="more"><?$APPLICATION->IncludeComponent("bitrix:main.include", "catalog", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/filialy.php"), false);?></div>
</div>