<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="title">Заказать обратный звонок</div>
<p>Заполните, пожалуйста, форму, чтобы наш менеджер мог связаться с Вами:</p>
<form name="callback" method="post" action="<?=$APPLICATION->GetCurPage()?>" class="ajax">
	<div class="line">
		<label>Ваше имя<!--span class="required">*</span--></label>
		<input   type="text" name="NAME" value="" />
		<div class="clear"></div>
	</div>
	<div class="line">
		<label>Телефон<span class="required">*</span></label>
		<input class="required phone" type="text" name="PHONE" value="" />
		<div class="clear"></div>
	</div>
	<div class="line autocomplete">
		<label>Город</label>				
		<input type="text" class="autocomplete town" name="TOWN" value="<?if(!empty($_SESSION["SELECTED_TOWN"])) echo trim($_SESSION["SELECTED_TOWN"])?>" />
		<div class="b-places"></div>
		<div class="clear"></div>
	</div>
	<?if(!empty($arResult["TIMES"])):?>
		<div class="line">
			<label>Время звонка</label>
			<select name="TIME">
				<?foreach($arResult["TIMES"] as $key => $time):?>
					<option value="<?=$key?>"><?=$time?></option>
				<?endforeach;?>
			</select>
			<div class="clear"></div>
		</div>
	<?endif;?>
	<div class="line">
		<label>Комментарии<!--span class="required">*</span--></label>
		<textarea  type="text" name="COMMENT" /></textarea>
		<div class="clear"></div>
	</div>
	<?if(!empty($arResult["HINTS"])):?>
		<p class="hints">Например, Интересует 
		<?foreach($arResult["HINTS"] as $key => $hint):?><a href="#" class="callback_hint"><?=$hint?></a><?if(isset($arResult["HINTS"][$key+1])):?>, <?endif;?><?endforeach;?></p>
	<?endif;?>
	<button type="submit" name="send">Заказать звонок</button>
</form>