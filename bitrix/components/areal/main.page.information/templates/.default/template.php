<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult)):?>
	<div class="tabs-plate">
		<div class="tabs" id="main_page_information">
			<ul>
				<?if(!empty($arResult["NEWS"])):?><li><a href="#news" link="/o-kompanii/novosti/" class="active">Новости</a></li><?endif;?>
				<?if(!empty($arResult["REVIEWS"])):?><li><a href="#reviews" link="/o-kompanii/otzyvy/">Отзывы</a></li><?endif;?>
				<?if(!empty($arResult["ARTICLES"])):?><li><a href="#articles" link="/o-kompanii/statyi/">Статьи</a></li><?endif;?>
				<?if(!empty($arResult["CERTIFICATES"])):?><li><a href="#certificates" link="/partnery/sertifikaty/">Сертификаты</a></li><?endif;?>
				<a href="" id="selected"></a>
				<div class="clear"></div>
			</ul>
			<?if(!empty($arResult["NEWS"])):?>
				<div class="page" id="news">
					<div class="last-news">
						<ul>
							<?foreach($arResult["NEWS"] as $arNew):?>
								<li>
									<a class="image border_gray" href="<?=$arNew["DETAIL_PAGE_URL"]?>" title="Подробнее">
										<table><tr><td>
										<?if(!empty($arNew["PREVIEW_PICTURE"]["src"])):?>
											<img src="<?=$arNew["PREVIEW_PICTURE"]["src"]?>" width="<?=$arNew["PREVIEW_PICTURE"]["width"]?>" height="<?=$arNew["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arNew["NAME"]?>" title="<?=$arNew["NAME"]?>" />
										<?else:?>
											<img src="<?=getImageNoPhoto(111, 98)?>" alt="<?=$arNew["NAME"]?>" title="<?=$arNew["NAME"]?>" />
										<?endif;?>
										</td></tr></table>
									</a>
									<div class="details">
										<div class="date"><?=$arNew["DATE_CREATE"]?></div>
										<div class="annotation">
											<?if(!empty($arNew["PREVIEW_TEXT"])):?>
												<?if($arNew["PREVIEW_TEXT_TYPE"] == "text"):?>
													<?=$arNew["PREVIEW_TEXT"]?>
												<?elseif($arNew["PREVIEW_TEXT_TYPE"] == "html"):?>
													<?=strip_tags($arNew["PREVIEW_TEXT"]);?>
												<?endif;?>
											<?else:?>
												<?=$arNew["NAME"]?>
											<?endif;?>
										</div>
										<div class="link"><a href="<?=$arNew["DETAIL_PAGE_URL"]?>" title="Подробнее">Подробнее &raquo;</a></div>
									</div>
								</li>
							<?endforeach;?>
						</ul>
						<div class="clear"></div>
					</div>
					<div class="last-news-bot">
						<div class="center"><a href="/o-kompanii/novosti/" title="Все новости">Все новости &raquo;</a></div>
						<div class="right">
							<a href="#">Подписаться на рассылку »</a>
							<a href="/o-kompanii/novosti/rss/" class="rss" title="rss" target="_blank"></a>
							<a href="#" class="mail"></a>
						</div>
					</div>
				</div>
			<?endif;?>
			<?if(!empty($arResult["REVIEWS"])):?>
				<div class="page" id="reviews">
					<div class="last-news">
						<ul>
							<?foreach($arResult["REVIEWS"] as $arReview):?>
								<li>
									<a rel="group_rewiews" class="image border_gray <?if(!empty($arReview["BIG_PICTURE"])):?> fancybox <?endif;?>" href="<?=$arReview["BIG_PICTURE"]?>">
										<table><tr><td>
										<?if(!empty($arReview["SMALL_PICTURE"]["src"])):?>
											<img src="<?=$arReview["SMALL_PICTURE"]["src"]?>" width="<?=$arReview["SMALL_PICTURE"]["width"]?>" height="<?=$arReview["SMALL_PICTURE"]["height"]?>" alt="<?=$arReview["NAME"]?>" title="<?=$arReview["NAME"]?>" />
										<?else:?>
											<img src="<?=getImageNoPhoto(111, 98)?>" alt="<?=$arReview["NAME"]?>" title="<?=$arReview["NAME"]?>" />
										<?endif;?>
										</td></tr></table>
									</a>
									<div class="details">
										<div class="date"><?=$arReview["DATE_CREATE"]?></div>
										<div class="annotation"><?=$arReview["NAME"]?></div>
									</div>
								</li>
							<?endforeach;?>
							<div class="clear"></div>
						</ul>
					</div>
					<div class="last-news-bot">
						<div class="center"><a href="/o-kompanii/otzyvy/" title="Все отзывы">Все отзывы &raquo;</a></div>						
					</div>
				</div>
			<?endif;?>
			
			<?if(!empty($arResult["ARTICLES"])):?>
				<div class="page" id="articles">
					<div class="last-news">
						<ul>
							<?foreach($arResult["ARTICLES"] as $arArticle):?>
								<li>
									<a class="image border_gray" href="<?=$arArticle["DETAIL_PAGE_URL"]?>" title="Подробнее">
										<table><tr><td>
										<?if(!empty($arArticle["PREVIEW_PICTURE"]["src"])):?>
											<img src="<?=$arArticle["PREVIEW_PICTURE"]["src"]?>" width="<?=$arArticle["PREVIEW_PICTURE"]["width"]?>" height="<?=$arArticle["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arArticle["NAME"]?>" title="<?=$arArticle["NAME"]?>" />
										<?else:?>
											<img src="<?=getImageNoPhoto(111, 98)?>" alt="<?=$arArticle["NAME"]?>" title="<?=$arArticle["NAME"]?>" />
										<?endif;?>
										</td></tr></table>
									</a>
									<div class="details">
										<div class="date"><?=$arArticle["DATE_CREATE"]?></div>
										<div class="annotation">
											<?if(!empty($arArticle["PREVIEW_TEXT"])):?>
												<?if($arArticle["PREVIEW_TEXT_TYPE"] == "text"):?>
													<?=$arArticle["PREVIEW_TEXT"]?>
												<?elseif($arArticle["PREVIEW_TEXT_TYPE"] == "html"):?>
													<?=strip_tags($arArticle["PREVIEW_TEXT"]);?>
												<?endif;?>
											<?else:?>
												<?=$arArticle["NAME"]?>
											<?endif;?>
										</div>
										<div class="link"><a href="<?=$arArticle["DETAIL_PAGE_URL"]?>" title="Подробнее">Подробнее &raquo;</a></div>
									</div>
								</li>
							<?endforeach;?>
							<div class="clear"></div>
						</ul>
					</div>
					<div class="last-news-bot">
						<div class="center"><a href="/o-kompanii/statyi/" title="Все новости">Все статьи &raquo;</a></div>
						<div class="right">
							<a href="#">Подписаться на расылку »</a>
							<a href="/o-kompanii/statyi/rss/" class="rss" title="rss" target="_blank"></a>
							<a href="#" class="mail"></a>
						</div>
					</div>
				</div>
			<?endif;?>
			
			<?if(!empty($arResult["CERTIFICATES"])):?>
				<div class="page" id="certificates">
					<div class="last-news">
						<ul>
							<?foreach($arResult["CERTIFICATES"] as $arCertificate):?>
								<li>
									<a rel="group_certificates" class="image border_gray <?if(!empty($arCertificate["BIG_PICTURE"])):?> fancybox <?endif;?>" href="<?=$arCertificate["BIG_PICTURE"]?>">
										<table><tr><td>
										<?if(!empty($arCertificate["SMALL_PICTURE"]["src"])):?>
											<img src="<?=$arCertificate["SMALL_PICTURE"]["src"]?>" width="<?=$arCertificate["SMALL_PICTURE"]["width"]?>" height="<?=$arCertificate["SMALL_PICTURE"]["height"]?>" alt="<?=$arCertificate["NAME"]?>" title="<?=$arCertificate["NAME"]?>" />
										<?else:?>
											<img src="<?=getImageNoPhoto(111, 98)?>" alt="<?=$arCertificate["NAME"]?>" title="<?=$arCertificate["NAME"]?>" />
										<?endif;?>
										</td></tr></table>
									</a>
									<div class="details">
										<div class="date"><?=$arCertificate["DATE_CREATE"]?></div>
										<div class="annotation"><?=$arCertificate["NAME"]?></div>
									</div>
								</li>
							<?endforeach;?>
							<div class="clear"></div>
						</ul>
					</div>
					<div class="last-news-bot">
						<div class="center"><a href="/partnery/sertifikaty/" title="Все сертификаты">Все сертификаты &raquo;</a></div>						
					</div>
				</div>
			<?endif;?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
<?endif;?>