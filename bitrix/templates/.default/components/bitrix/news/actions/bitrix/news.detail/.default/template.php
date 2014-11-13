<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<pre class="test123" style="display:none;"><?print_r($arResult)?></pre>
<style>
</style>
<div class="action-top">
	<?if(!empty($arResult["PHOTOS"])):?>
		<div class="border_gray carousel">
			<ul id="action_banner">
				<?foreach($arResult["PHOTOS"] as $key => $photo):?>
					<li>
						<a href="<?=$photo["NATURE"]?>" rel="group_actions" class="fancybox image">
							<table><tr><td>
								<img src="<?=$photo["IMAGE"]["src"]?>" width="<?=$photo["IMAGE"]["width"]?>" height="<?=$photo["IMAGE"]["height"]?>" alt="<?=$photo["DESCRIPTION"]?>" title="<?=$photo["DESCRIPTION"]?>">
							</td></tr></table>
						</a>						
						<div class="description"><span><?=$photo["DESCRIPTION"]?></span></div>
					</li>
				<?endforeach;?>
			</ul>
			<?if(count($arResult["PHOTOS"]) > 1):?>
				<div class="nav">
					<a href="#" class="prev"></a> 
					<a href="#" class="next"></a>
					<ul class="pagging"></ul>
				</div>
			<?else:?>
				<input type="hidden" name="count_banner" value="<?=count($arResult["PHOTOS"]);?>" />
			<?endif;?>
		</div>
	<?else:?>
		<div class="actions border_gray image carousel">
			<table><tr><td>
				<img src="<?=getImageNoPhoto(520, 254)?>" alt="Нет изображения" title="Нет изображения" />
			</td></tr></table>
		</div>
	<?endif;?>
	<div class="action-timer">
		<div class="title">До окончания акции осталось</div>
		<div class="time-left">
			<div class="icon"></div>
<? if(empty($arResult["TIME_LEFT"]) || $arResult["TIME_LEFT"] == '' || $arResult["TIME_LEFT"] == 0) 
	$arResult["TIME_LEFT"] = '0 дней'; 
?>
	<?=$arResult["TIME_LEFT"]?>

		</div>
		<div class="date">Акция действительна <span><?=getDateWithTimeText($arResult["ACTIVE_FROM"], $arResult["ACTIVE_TO"], false);?></span></div>
		<input type="hidden" name="action_name" value="<?=$arResult["NAME"]?>" />
		<?//if($USER->IsAdmin()):?>
			<a href="<?=$arResult["PROPERTIES"]["PROP_LINK"]["VALUE"]?>" class="to_section">Перейти в раздел</a>
		<?//endif;?>
		<button class="silver order price_btn" id="action_<?=$arResult["ID"]?>">Заказать</button>
		
		<?//if($USER->IsAdmin()):?>
		<div class="" style="display:none"><pre><?print_r($arResult["PROPERTIES"]["PRICE_LIST"]["VALUE"])?></pre></div>
		<?if ($arResult["PROPERTIES"]["PRICE_LIST"]["VALUE"] != ""):?>
		<a href="<?=CFile::GetPath($arResult["PROPERTIES"]["PRICE_LIST"]["VALUE"])?>" class="silver order file_popup price_btn" id="file_popup_link">Прайс</a>
		<?endif;?>
		<?foreach($arResult["PROPERTIES"]["ADD_FILES"]["VALUE"] as $key => $file):?>
				<a class="add_files" id="" href="<?=(CFile::GetFileArray($file)["SRC"])?>"><?=(CFile::GetFileArray($file)["DESCRIPTION"])?></a>
			<?endforeach;?>
			<!--<a href="/image.jpeg" class="file_popup" id="file_popup_link">скачать</a>-->
		<?//endif;?>
	</div>
	<div class="clear"></div>
</div>
<div class="detailaction-text">
<?if(!empty($arResult["DETAIL_TEXT"])):?>
	<?if($arResult["DETAIL_TEXT_TYPE"] == "text"):?>
		<p><?=$arResult["DETAIL_TEXT"]?></p>
	<?elseif($arResult["DETAIL_TEXT_TYPE"] == "html"):?>
		<?=$arResult["DETAIL_TEXT"]?>
	<?endif;?>
<?endif;?>
</div>