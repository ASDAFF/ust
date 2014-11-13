<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["HISTORY"])):?>
	<div class="history-page">
		<div class="years-tabs">
			<div class="tabs">
				<?foreach($arResult["HISTORY"] as $key => $arYears):?>
					<div data-tab="<?=($key+1)?>" class="year year-<?=($key+1)?> <?if($key == (count($arResult["HISTORY"])-1)):?>active<?endif;?>"><?=$arYears["NAME"]?><span class="<?if(($key+1)%2 == 0):?>rotate-f<?else:?>rotate-b<?endif;?>"></span></div>
				<?endforeach;?>
			</div>
			<div class="pages">
				<?foreach($arResult["HISTORY"] as $key => $arYears):?>
					<div class="page page-<?=($key+1)?> <?if($key == (count($arResult["HISTORY"])-1)):?>active<?endif;?>">
						<?if(!empty($arYears["PHOTO"]) && count($arYears["PHOTO"]) > 4):?>
							<div class="jcarousel history_carousel" id="history_<?=$arYears["ID"]?>">
								<ul>
									<?foreach($arYears["PHOTO"] as $key => $arPhoto):?>
										<li class="border_gray">
											<table><tr><td>
												<img src="<?=$arPhoto["PICTURE"]["src"]?>" width="<?=$arPhoto["PICTURE"]["width"]?>" height="<?=$arPhoto["PICTURE"]["height"]?>" alt="<?=$arPhoto["NAME"]?>" title="<?=$arPhoto["NAME"]?>" />
											</td></tr></table>
											<div class="description"><span><?=$arPhoto["NAME"]?></span></div>
										</li>
									<?endforeach;?>
								</ul>
								<button class="jcarousel-prev" id="prev_history_<?=$arYears["ID"]?>"></button>
								<button class="jcarousel-next" id="next_history_<?=$arYears["ID"]?>"></button>
							</div>
						<?else:?>
							<?foreach($arYears["PHOTO"] as $key => $arPhoto):?>
								<div class="border_gray only_photo count_<?=count($arYears["PHOTO"])?> <?if(($key+1) == count($arYears["PHOTO"])):?>last<?endif;?>">
									<table><tr><td>
										<img src="<?=$arPhoto["PICTURE"]["src"]?>" width="<?=$arPhoto["PICTURE"]["width"]?>" height="<?=$arPhoto["PICTURE"]["height"]?>" alt="<?=$arPhoto["NAME"]?>" title="<?=$arPhoto["NAME"]?>" />
									</td></tr></table>
									<div class="description"><span><?=$arPhoto["NAME"]?></span></div>
								</div>
							<?endforeach;?>
							<div class="clear"></div>
						<?endif;?>
						<div class="title"><?=$arYears["NAME"]?></div>
						<?if(!empty($arYears["DESCRIPTION"])):?>
							<?if($arYears["DESCRIPTION_TYPE"] == "text"):?>	
								<p><?=$arYears["DESCRIPTION"]?><p/>
							<?else:?>
								<?=$arYears["DESCRIPTION"]?>
							<?endif;?>
						<?else:?>
							<p>Дополнительной информации не указано.</p>
						<?endif;?>
					</div>
				<?endforeach;?>
			</div>
		</div>
	</div>
<?endif;?>