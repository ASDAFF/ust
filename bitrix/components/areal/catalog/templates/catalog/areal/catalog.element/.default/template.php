<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="catalog-detail catalog">	
	<div class="ppic detail">
		<div class="image-main image">
			<?if(count($arResult["PHOTOS"])>0):?>
				<a class="fancybox" href="<?=$arResult["PHOTOS"][0]["NATURE"]?>" >
			<?endif;?>
					<table><tr><td>
						<?if(!empty($arResult["PHOTOS"][0]["STANDART"]["src"])):?>
							<img src="<?=$arResult["PHOTOS"][0]["STANDART"]["src"]?>" width="<?=$arResult["PHOTOS"][0]["STANDART"]["width"]?>" height="<?=$arResult["PHOTOS"][0]["STANDART"]["height"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" />
						<?else:?>
							<img src="<?=getImageNoPhoto(338, 278)?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>" />
						<?endif;?>
					</td></tr></table>
			<?if(count($arResult["PHOTOS"])>0):?>
					<span class="full"></span>
					<?if(!empty($arResult["PROPERTIES"]["ACTIONS"]["VALUE"])):?>
						<span class="action popup"><?=$arResult["PROPERTIES"]["ACTIONS"]["NAME"]?></span>
					<?endif;?>
					<?if(!empty($arResult["PROPERTIES"]["NEW"]["VALUE"])):?>
						<span class="new popup"><?=$arResult["PROPERTIES"]["NEW"]["NAME"]?></span>
					<?endif;?>
					<?if(!empty($arResult["PROPERTIES"]["SEASONAL"]["VALUE"])):?>
						<span class="season popup"><?=$arResult["PROPERTIES"]["SEASONAL"]["NAME"]?></span>
					<?endif;?>	
					<?if(!empty($arResult["PROPERTIES"]["SALE"]["VALUE"])):?>
						<span class="sale popup"><?=$arResult["PROPERTIES"]["SALE"]["NAME"]?></span>
					<?endif;?>	
					<?if(!empty($arResult["PROPERTIES"]["CREDIT"]["VALUE"])):?>
						<span class="credit popup"><?=$arResult["PROPERTIES"]["CREDIT"]["NAME"]?></span>
					<?endif;?>		
				</a>
			<?endif;?>
		</div>
		<?if(count($arResult["PHOTOS"])>1):?>
			<div class="jcarousel" id="carousel-detail">
				<ul>
					<?foreach($arResult["PHOTOS"] as $key => $arPhoto):?>
						<li>
							<a href="<?=$arPhoto["STANDART"]["src"]?>" width-pic="<?=$arPhoto["STANDART"]["width"]?>" height-pic="<?=$arPhoto["STANDART"]["height"]?>" class="image <?if($key==0):?>active<?endif;?>">
								<table><tr><td>
									<img big-pic="<?=$arPhoto["NATURE"]?>" src="<?=$arPhoto["SMALL"]["src"]?>" width="<?=$arPhoto["SMALL"]["width"]?>" height="<?=$arPhoto["SMALL"]["height"]?>" />
								</td></tr></table>
							</a>
						</li>
					<?endforeach;?>
				</ul>
				<button class="jcarousel-prev"></button>
				<button class="jcarousel-next"></button>
			</div>
		<?endif;?>
		<div class="cl"></div>
	</div>	
	<?if(!empty($arResult["CHARACTERISTICS"])):?>
		<div class="properties">
			<div class="characteristics<?if($arParams["TYPE"] == "ELEMENT" && !empty($arResult["PROPERTIES"]["PRICE"]["VALUE"])):?> with-price<?endif;?> <?if(count($arResult["PHOTOS"]) <= 1):?> short<?endif;?>">
				<div class="title">Общие характеристики</div>
				<div class="chars">
					<?if(isset($arResult["CHARACTERISTICS"]["TEXT"]) && isset($arResult["CHARACTERISTICS"]["TYPE"])):?>
						<?if($arResult["CHARACTERISTICS"]["TYPE"] == "text"):?>
							<p><?=$arResult["CHARACTERISTICS"]["TEXT"]?></p>
						<?else:?>
							<?=$arResult["CHARACTERISTICS"]["TEXT"]?>
						<?endif;?>
					<?else:?>
						<?$char = 0;?>
						<?foreach($arResult["CHARACTERISTICS"] as $val):?>
							<?if(!empty($val["VALUE"])):?>
								<?$char = 1;?>
								<div>
									<span class="name"><?=$val["NAME"]?></span>
									<span class="value"><?=$val["VALUE"]?></span>
									<div class="clear"></div>
								</div>
							<?endif;?>
						<?endforeach;?>
						<?if($char == 0):?>
							<p>К сожалению, для данной модели не указаны характеристики.</p>
						<?endif;?>
					<?endif;?>
				</div>
			</div>
			<?if($arParams["TYPE"] == "ELEMENT" && !empty($arResult["PROPERTIES"]["PRICE"]["VALUE"])):?>
				<div class="price">
					<?if($arResult["PROPERTIES"]["PRICE"]["VALUE"] > 0):?>
						<?=CurrencyFormat($arResult["PROPERTIES"]["PRICE"]["VALUE"], "RUB");?>
					<?else:?>
						Не указана
					<?endif;?>
				</div>
			<?endif;?>
		</div>
	<?endif;?>	
	<div class="clear"></div>
	
	<div class="tabs-detail border_gray" id="tabs-detail">
		<ul>
			<?if(!empty($arResult["PREVIEW_TEXT"])):?>
				<li><a href="#description">Описание</a><span></span></li>
			<?endif;?>
			<li><a href="#characteristics">Характеристики</a><span></span></li>
			<?if(!empty($arResult["ATTACHMENTS"])):?>
				<li><a href="#attachments">Навесное оборудование</a><span></span></li>
			<?endif;?>
			<?if(!empty($arResult["OPTIONS"])):?>
				<li><a href="#options">Опции</a><span></span></li>
			<?endif;?>
			<?if(!empty($arResult["VIDEO"])):?>
				<li><a href="#video">Видео</a><span></span></li>
			<?endif;?>
			<div class="clear"></div>
		</ul>
		<?if(!empty($arResult["PREVIEW_TEXT"])):?>
			<div class="page" id="description">
				<?if(!empty($arResult["PREVIEW_TEXT"])):?>
					<?if($arResult["PREVIEW_TEXT_TYPE"] == "text"):?>
						<p><?=$arResult["PREVIEW_TEXT"]?></p>
					<?else:?>
						<?=$arResult["PREVIEW_TEXT"]?>
					<?endif;?>
				<?endif;?>
			</div>
		<?endif;?>
		<?if(!empty($arResult["OPTIONS"])):?>
			<div class="page" id="options">
				<?if(!empty($arResult["OPTIONS"])):?>
					<?if($arResult["OPTIONS"]["TYPE"] == "text"):?>
						<p><?=$arResult["OPTIONS"]["TEXT"]?></p>
					<?else:?>
						<?=$arResult["OPTIONS"]["TEXT"]?>
					<?endif;?>
				<?endif;?>
			</div>
		<?endif;?>
		<div class="page" id="characteristics">
			<?pr($arResult);?>
			<?/*foreach($arResult["GROUPING_CHARS"] as $group_type):?>
				<?foreach()?>
			<?endforeach;*/?>
			
			
			
		</div>
		<?if(!empty($arResult["ATTACHMENTS"])):?>
			<div class="page" id="attachments">
				<?foreach($arResult["ATTACHMENTS"] as $key => $attachment):?>
					<?if(!empty($attachment["URL"])):?>
						<a class="attachment image<?if(($key+1)%6 == 0):?> last<?endif;?>" title="<?=$attachment["NAME"]?>" href="<?=$attachment["URL"]?>" >
					<?else:?>
						<div class="attachment image<?if(($key+1)%6 == 0):?> last<?endif;?>">
					<?endif;?>
							<table><tr><td>
							<?if(!empty($attachment["PREVIEW_PICTURE"]["src"])):?>
								<img src="<?=$attachment["PREVIEW_PICTURE"]["src"]?>" width="<?=$attachment["PREVIEW_PICTURE"]["width"]?>" height="<?=$attachment["PREVIEW_PICTURE"]["height"]?>" alt="<?=$attachment["NAME"]?>" title="<?=$attachment["NAME"]?>" />
							<?else:?>
								<img src="<?=getImageNoPhoto(100, 100)?>" alt="<?=$attachment["NAME"]?>" title="<?=$attachment["NAME"]?>" />
							<?endif;?>
							</td></tr></table>
							<span><?=$attachment["NAME"]?></span>
					<?if(!empty($attachment["URL"])):?>
						</a>
					<?else:?>
						</div>
					<?endif;?>
				<?endforeach;?>
				<div class="clear"></div>
			</div>
		<?endif;?>
		<?if(!empty($arResult["VIDEO"])):?>
			<div class="page" id="video">
				<?foreach($arResult["VIDEO"] as $key => $arVideo):?>
					<div class="item-video <?if(($key+1)%3 == 0):?> last <?endif;?>">
						<div class="video">
							<a class="video_show" href="#" id="video_<?=$arVideo["ID"]?>" title="Посмотреть видео">
								<?if(!empty($arVideo["PREVIEW_PICTURE"]["src"])):?>
									<img src="<?=$arVideo["PREVIEW_PICTURE"]["src"]?>" width="<?=$arVideo["PREVIEW_PICTURE"]["width"]?>" height="<?=$arVideo["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arVideo["NAME"]?>" title="<?=$arVideo["NAME"]?>" />
								<?endif;?>
								<span class="frame"></span>
								<span class="icon"></span>
								<span class="video-overlay"></span>
							</a>
						</div>
						<div class="name"><a class="video_show" href="#" id="video_<?=$arVideo["ID"]?>" title="Посмотреть видео"><?=$arVideo["NAME"]?></a></div>
					</div>
				<?endforeach;?>
			</div>
		<?endif;?>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>
<div class="dialog" id="video_player"></div>