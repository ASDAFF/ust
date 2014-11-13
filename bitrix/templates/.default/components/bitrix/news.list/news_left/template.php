<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["ITEMS"])):?>
	<div class="news-left">
		<div class="title">Новости</div>		
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<div class="item">
				<span class="date"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></span>
				<a class="name" href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem["NAME"]?>"><?=$arItem["NAME"]?></a>
			</div>
		<?endforeach;?>
		<a href="/o-kompanii/novosti" title="Все новости" class="all-news">Все новости</a>
	</div>
<?endif;?>