<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>	
<?if(!empty($arResult["SERVICE"])):?>
	<?foreach($arResult["SERVICE"] as $town => $service):?>
		<div class="town">
			<p class="name"><?=$town?></p>
			<?foreach($service as $key => $arServ):?>
				<?if(!empty($arServ["ADDRESS"])):?>
					<p>Адрес: <?=$arServ["ADDRESS"]?></p>
				<?endif;?>
				<?if(!empty($arServ["PHONE"])):?>
					<p>Тел: <?=$arServ["PHONE"]?></p>
				<?endif;?>
				<?if(!empty($arServ["SHEDULE_WORK"]["TEXT"])):?>
					<p>График работы: <br /><?=$arServ["SHEDULE_WORK"]["TEXT"]?></p>
				<?endif;?>
				<?if(!empty($arServ["EMAIL"])):?>
					<p>Email: <a class="e-mail" title="<?=$arServ["EMAIL"][0]?>" href="#<?=$arServ["EMAIL"][1]?>"></a></p>
				<?endif;?>
			<?endforeach;?>
		</div>
	<?endforeach;?>
<?else:?>
	<p>Информации о сервисных центрах не найдено.</p>
<?endif;?>