<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["ITEMS"]) > 0):?>
	<?foreach($arResult["ARENDA"] as $key => $arenda):?>		
		<?foreach($arenda as $k => $v):?>
			<?unset($props);
			foreach($v as $item) {
				foreach($item["PROPERTIES"] as $key_prop => $arProp) {
					if(!empty($arProp["VALUE"]) && !in_array($key_prop, $props) && $key_prop != "SERIES" && $key_prop != "NOTE" && $key_prop != "PRICE_BY")
						$props[$key_prop] = $arProp["NAME"];
				}
			}?>
			<div class="rent-variants-list">
				<div class="rent-item">
					<table class="no-hover">
						<thead>
							<tr>
								<th rowspan="2" colspan="2" class="image">
									<?if(isset($arResult["SECTIONS"][$key]["PICTURE"]["src"])):?>
										<img src="<?=$arResult["SECTIONS"][$key]["PICTURE"]["src"]?>" width="<?=$arResult["SECTIONS"][$key]["PICTURE"]["width"]?>" height="<?=$arResult["SECTIONS"][$key]["PICTURE"]["height"]?>" alt="<?=$arResult["SECTIONS"][$key]["NAME"]?>" title="<?=$arResult["SECTIONS"][$key]["NAME"]?>" />
									<?else:?>
										<img src="<?=getImageNoPhoto(201, 105)?>" alt="<?=$arResult["SECTIONS"][$key]["NAME"]?>" title="<?=$arResult["SECTIONS"][$key]["NAME"]?>" />
									<?endif;?>
								</th>
								<th colspan="<?=(count($props)+1)?>" class="head-row"><?=$arResult["SECTIONS"][$key]["NAME"]?></th>
							</tr>
							<tr>
								<?foreach($props as $key => $value):?>
									<th><div class="inner"><?=$value?></div></th>
								<?endforeach;?>
								<th><div class="inner">Стоимость</div></th>
							</tr>
						</thead>
						<tbody>
							<?foreach($v as $item):?>
								<tr class="odd">
									<td class="line-cell"></td>
									<td><div class="name"><?=$item["NAME"]?></div></td>
									<?foreach($props as $key => $value):?>
										<td><?=$item["PROPERTIES"][$key]["VALUE"]?></td>
									<?endforeach;?>
									<td>
										<?if($item["CATALOG_PRICE_1"] > 0):?>
											от <?=CurrencyFormat($item["CATALOG_PRICE_1"], $item["CATALOG_CURRENCY_1"])?>/<?=$item["PROPERTIES"]["PRICE_BY"]["VALUE"]?>
										<?else:?>
											<?="Цена не указана"?>
										<?endif;?>
									</td>
								</tr>
								<tr>
									<td class="line-cell"></td>
									<td class="button-cell">
										<input type="hidden" name="name" value="<?=$item["NAME"]?>" />
										<button class="order silver_button silver">Заказать</button>
									</td>
									<td colspan="<?=(count($props)+1)?>" class="additional">
										<?if(!empty($item["PROPERTIES"]["NOTE"]["VALUE"])):?>
											<?if($item["PROPERTIES"]["NOTE"]["VALUE"]["TYPE"] == "text"):?>
												<?=$item["PROPERTIES"]["NOTE"]["NAME"]?>: <?=$item["PROPERTIES"]["NOTE"]["VALUE"]["TEXT"]?></div>
											<?else:?>
												<?=$item["PROPERTIES"]["NOTE"]["NAME"]?>: <?=strip_tags($item["PROPERTIES"]["NOTE"]["VALUE"]["TEXT"])?>
											<?endif;?>
										<?endif;?>
									</td>
								</tr>
								<tr>
									<td colspan="<?=(count($props)+3)?>" class="sep-cell"></td>
								</tr>
							<?endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
		<?endforeach;?>
	<?endforeach;?>
<?else:?>
	<p class="errortext">К сожалению, в данном разделе нет доступных наименований.</p>
<?endif;?>