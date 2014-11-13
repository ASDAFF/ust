<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["ITEMS"]) > 0):?>
	<?if($arParams["DISPLAY_TOP_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>
	<div class="clear"></div>
	<div class="bu-products">
		<input type="hidden" name="section_name" value="<?=($arResult["NAME"] ? $arResult["NAME"] : "Строительная техника")?>" />
		<?foreach($arResult["ITEMS"] as $key => $arItem):?>
			<div class="item">
				<div class="top-line">
					<div class="name">
						<?if(!empty($arItem["PROPERTIES"]["ENABLE_DETAILED_PAGE"]["VALUE"])):?>
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>"><?=$arItem["NAME"]?></a>
						<?else:?>
							<?=$arItem["NAME"]?>
						<?endif;?>
					</div>
					<?if(!empty($arItem["PROPERTIES"]["LOT"]["VALUE"])):?>
						<div class="sku">(лот <?=$arItem["PROPERTIES"]["LOT"]["VALUE"]?>)</div>
					<?endif;?>
					<div class="clear"></div>
				</div>
				<div class="item-body">
					<div class="left-col">
						<div class="image">
							<a href="<?=$arItem["BIG_PICTURE"]?>" class="fancybox image">
								<table><tr><td>
									<?if(!empty($arItem["PREVIEW_PICTURE"]["src"])):?>
										<img src="<?=$arItem["PREVIEW_PICTURE"]["src"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["width"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["height"]?>" title="<?=$arItem["NAME"]?>" alt="<?=$arItem["NAME"]?>">
									<?else:?>
										<img src="<?=getImageNoPhoto(380, 245)?>" title="<?=$arItem["NAME"]?>" alt="<?=$arItem["NAME"]?>">
									<?endif;?>
								</td></tr></table>
								<span class="full"></span>
							</a>
						</div>
						<div class="descr">
							<?if(!empty($arItem["PREVIEW_TEXT"])):?>
								<?=strip_tags($arItem["PREVIEW_TEXT"]);?>
								<?if(!empty($arItem["DETAIL_TEXT"])):?>
									<span class="dots">...</span>
									<span class="full-text"><?=$arItem["DETAIL_TEXT"]?></span>
									<a class="more" href="#">Подробнее</a>								
								<?endif;?>
							<?endif;?>
						</div>
					</div>
					<div class="details">
						<div class="specs">
							<div class="title">Характеристики</div>
							<div class="list">
								<?foreach($arItem["PROPERTIES"] as $props):?>
									<?if(!in_array($props["CODE"], $arParams["GENERAL_PROPERTIES_LIST"])):?>
										<?if(!empty($props["VALUE"])):?>
											<div class="row">
												<div class="name <?if(is_array($props["VALUE"])):?> bold <?endif;?>"><?=$props["NAME"]?></div>
												<div class="value <?if(!is_array($props["VALUE"])):?> bold <?endif;?>">
													<?if(is_array($props["VALUE"])):?>
														<?if(isset($props["VALUE"]["TEXT"])):?>
															<?=$props["~VALUE"]["TEXT"]?>
														<?endif;?>
													<?else:?>
														<?=$props["VALUE"]?>
													<?endif;?>
												</div>
												<div class="clear"></div>
											</div>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>							
							</div>
						</div>
						<div class="info">
							<div class="title">Цена</div>
							<div class="price">
								<?if(!empty($arItem["CATALOG_PRICE_".$arResult["PRICE_ID"]])):?>
									<?=CurrencyFormat($arItem["CATALOG_PRICE_".$arResult["PRICE_ID"]], $arItem["CATALOG_CURRENCY_".$arResult["PRICE_ID"]]);?>
								<?else:?>
									Не указана
								<?endif;?>
							</div>
							<div class="other">
								<div class="list">
									<?$property = array("YEAR_OF_RELEASE", "MTBF", "LOCATION", "CONTACTS");?>
									<?foreach($property as $prop):?>
										<?if(isset($arItem["PROPERTIES"][$prop]["VALUE"])):?>
											<div class="item">
												<div class="name"><?=$arItem["PROPERTIES"][$prop]["NAME"]?></div>
												<div class="value">
													<?if(is_array($arItem["PROPERTIES"][$prop]["VALUE"])):?>
														<?if(isset($arItem["PROPERTIES"][$prop]["VALUE"]["TEXT"])):?>
															<?=$arItem["PROPERTIES"][$prop]["~VALUE"]["TEXT"]?>
														<?endif;?>
													<?else:?>
														<?=$arItem["PROPERTIES"][$prop]["VALUE"]?>
													<?endif;?>
												</div>
											</div>
										<?endif;?>
									<?endforeach;?>
								</div>
							</div>
							<?if(COption::GetOptionInt("ust", "USED_show_order") == 1):?>
								<div class="order-btn">
									<input type="hidden" name="lot" value="<?=$arItem["PROPERTIES"]["LOT"]["VALUE"]?>" />
									<button class="silver_button" href="#" id="catalog_<?=$arItem["ID"]?>">Заказать</button>
								</div>
							<?endif;?>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		<?endforeach;?>
	</div>
	<div class="hr"></div>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>
<?else:?>
	<div class="clear"></div>
	<p class="errortext">К сожалению, в данном разделе нет доступных товаров.</p>
	<div class="clear"></div>
<?endif;?>