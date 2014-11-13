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
					<?
					$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					if ($arFilial["TOWN_NAME"] == "Петрозаводск" && strpos($actual_link, "forestry.u-st.ru/filialy/") != false) continue;
					?>
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
                                                        <p><span class="town-num">Тел:<br /><?=$arFilial["PHONE"]?></span></p>
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
			<?/*?><div class="more"><?$APPLICATION->IncludeComponent("bitrix:main.include", "catalog", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/filialy.php"), false);?></div><?*/?>
			<div class="more">
				<?$APPLICATION->IncludeComponent("bitrix:news.list", "filialy_seo", Array(
	"DISPLAY_DATE" => "Y",	// Выводить дату элемента
	"DISPLAY_NAME" => "Y",	// Выводить название элемента
	"DISPLAY_PICTURE" => "Y",	// Выводить изображение для анонса
	"DISPLAY_PREVIEW_TEXT" => "Y",	// Выводить текст анонса
	"AJAX_MODE" => "N",	// Включить режим AJAX
	"IBLOCK_TYPE" => "branches_dealers",	// Тип информационного блока (используется только для проверки)
	"IBLOCK_ID" => "58",	// Код информационного блока
	"NEWS_COUNT" => "1",	// Количество новостей на странице
	"SORT_BY1" => "ACTIVE_FROM",	// Поле для первой сортировки новостей
	"SORT_ORDER1" => "DESC",	// Направление для первой сортировки новостей
	"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
	"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
	"FILTER_NAME" => "",	// Фильтр
	"FIELD_CODE" => "",	// Поля
	"PROPERTY_CODE" => array(	// Свойства
		0 => "SEO_TEXT",
	),
	"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
	"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
	"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
	"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
	"SET_TITLE" => "N",	// Устанавливать заголовок страницы
	"SET_STATUS_404" => "N",	// Устанавливать статус 404, если не найдены элемент или раздел
	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
	"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
	"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
	"PARENT_SECTION" => "",	// ID раздела
	"PARENT_SECTION_CODE" => "",	// Код раздела
	"INCLUDE_SUBSECTIONS" => "N",	// Показывать элементы подразделов раздела
	"CACHE_TYPE" => "A",	// Тип кеширования
	"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
	"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
	"CACHE_GROUPS" => "Y",	// Учитывать права доступа
	"PAGER_TEMPLATE" => ".default",	// Шаблон постраничной навигации
	"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
	"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
	"PAGER_TITLE" => "Новости",	// Название категорий
	"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
	"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
	"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
	"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
	"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
	"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
	),
	false
);?> 
			</div>
		</div>
	</div>
	<script type="text/javascript">
		<?if(!empty($arResult["POINTS"])):?>
			var officesCities = new Array();
			<?foreach($arResult["POINTS"] as $key => $point): 
			//Отказ вывода Петрозаводска
			$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			if ($point["NAME"] == "Петрозаводск" && $actual_link == "http://forestry.u-st.ru/filialy/") continue;
			//file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/areal/filialy.dealers/templates/.default/test.txt", $point[NAME]);?>
				officesCities[officesCities.length] = {
					"key": "<?=$key?>",
					"name": "<?=$point["NAME"]?>",
					"area": "<?=$point["POINT"]?>",
                                        "href": "#2"
				};
			<?endforeach;?>
		<?endif;?>
	</script>
<?else:?>
	<p>К сожалению, нет доступной информации о филиалах.</p>
<?endif;?>
<?$_SERVER["HTTP_HOST"]?>