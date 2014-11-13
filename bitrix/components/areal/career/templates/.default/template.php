<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="left">
	<?if(!empty($arResult["VACANCIES"])):?>
		<?foreach($arResult["VACANCIES"] as $key => $arBan):?>
			<a href="<?=$arBan["URL"]?>" class="vacancy border_gray image <?if(($key+1)%2 == 0):?>last<?endif;?>" title="<?=$arBan["NAME"]?>" target="_blank">
				<span><?=$arBan["NAME"]?></span>
				<table><tr><td>	
					<img src="<?=$arBan["PREVIEW_PICTURE"]["src"]?>" width="<?=$arBan["PREVIEW_PICTURE"]["width"]?>" height="<?=$arBan["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arBan["NAME"]?>" title="<?=$arBan["NAME"]?>" />
				</td></tr></table>
			</a>
		<?endforeach;?>
		<div class="clear"></div>
	<?endif;?>
	<?if(!empty($arResult["SLIDERS"])):?>
		<?foreach($arResult["SLIDERS"] as $key => $arSlider):?>
			<?if($key < 2):?>
				<div class="slider border_gray <?if(($key+1)%2 == 0):?>last<?endif;?>">
					<a href="<?=$arSlider["URL"]?>" title="<?=$arSlider["NAME"]?>" target="_blank">
						<span class="title"><?=$arSlider["NAME"]?></span>
						<span><?if($arSlider["PREVIEW_TEXT_TYPE"] == "text"):?><?=$arSlider["PREVIEW_TEXT"]?><?else:?><?=strip_tags($arSlider["PREVIEW_TEXT"]);?><?endif;?></span>
					</a>
					<div class="carousel">
						<ul id="career_banner_<?=$arSlider["ID"]?>" class="slider-career">
							<?foreach($arSlider["PHOTO"] as $key => $photo):?>
								<li class="image">
									<table><tr><td>
										<img src="<?=$photo["src"]?>" width="<?=$photo["width"]?>" height="<?=$photo["height"]?>" alt="<?=$arSlider["NAME"]?>" title="<?=$arSlider["NAME"]?>">
									</td></tr></table>
								</li>
							<?endforeach;?>
						</ul>
						<?if(count($arSlider["PHOTO"]) > 1):?>
							<div class="nav">
								<a href="#" class="career_prev" id="prev_career_banner_<?=$arSlider["ID"]?>"></a> 
								<a href="#" class="career_next" id="next_career_banner_<?=$arSlider["ID"]?>"></a>
							</div>
						<?endif;?>
					</div>
				</div>
			<?endif;?>
		<?endforeach;?>
	<?endif;?>
	<div class="clear"></div>
	<div class="contacts border_gray">
           <div class="contact link">
                    <span class="title"><a href="/o-kompanii/kontakty/karera/">Наши контакты:</a></span>
			Тел.: <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/phone.php"), false);?>, <?=GetPhoneFromTown()?><br>
			E-mail: <a class="e-mail" title="hr" href="#ust-co.ru"></a>
		</div></a>
	</div>
</div>
<div class="right">
	<?$APPLICATION->IncludeComponent("areal:form.career", ".default");?>
	<div class="slider border_gray last">
		<a href="<?=$arResult["SLIDERS"][2]["URL"]?>" title="<?=$arResult["SLIDERS"][2]["NAME"]?>" target="_blank">
			<span class="title"><?=$arResult["SLIDERS"][2]["NAME"]?></span>
			<span><?if($arResult["SLIDERS"][2]["PREVIEW_TEXT_TYPE"] == "text"):?><?=$arResult["SLIDERS"][2]["PREVIEW_TEXT"]?><?else:?><?=strip_tags($arResult["SLIDERS"][2]["PREVIEW_TEXT"]);?><?endif;?></span>
		</a>
		<div class="carousel">
			<ul id="career_banner_<?=$arResult["SLIDERS"][2]["ID"]?>" class="slider-career">
				<?foreach($arResult["SLIDERS"][2]["PHOTO"] as $key => $photo):?>
					<li class="image">
						<table><tr><td>
							<img src="<?=$photo["src"]?>" width="<?=$photo["width"]?>" height="<?=$photo["height"]?>" alt="<?=$arResult["SLIDERS"][2]["NAME"]?>" title="<?=$arResult["SLIDERS"][2]["NAME"]?>">
						</td></tr></table>
					</li>
				<?endforeach;?>
			</ul>
			<?if(count($arResult["SLIDERS"][2]["PHOTO"]) > 1):?>
				<div class="nav">
					<a href="#" class="career_prev" id="prev_career_banner_<?=$arResult["SLIDERS"][2]["ID"]?>"></a> 
					<a href="#" class="career_next" id="next_career_banner_<?=$arResult["SLIDERS"][2]["ID"]?>"></a>
				</div>
			<?endif;?>
		</div>
	</div>
</div>
<div class="clear"></div>