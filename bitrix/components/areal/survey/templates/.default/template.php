<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="dialog" id="survey">
	<div class="title"></div>
	<div class="survey">
		<?if(!empty($arResult["INRTODUCTION"])):?>
			<?if($arResult["INTRODUCTION_TYPE"] == "text"):?>
				<p><?=$arResult["INRTODUCTION"]?></p>
			<?else:?>
				<?=$arResult["INRTODUCTION"]?>
			<?endif;?>
		<?endif;?>
		<form name="survey_form" method="post" class="ajax" action="<?=$APPLICATION->GetCurPage(false)?>">
			<input type="hidden" name="FormType" value="getSurvey" />
			<div class="line autocomplete">
				<label>Укажите Ваш регион (Город)</label>				
				<input type="text" class="autocomplete town" name="TOWN" value="<?if(!empty($_SESSION["SELECTED_TOWN"])) echo trim($_SESSION["SELECTED_TOWN"])?>" />
				<div class="b-places"></div>
				<div class="clear"></div>
			</div>
			<div class="line">
				<label>Совершали ли Вы ранее покупку в &laquo;Универсал-Спецтехника&raquo;?</label>				
				<span class="yes regular question">Да</span>
				<span class="no regular question">Нет</span>
				<input type="hidden" name="BUY_EARLY" value="" />
				<div class="clear"></div>
			</div>
			<?if(count($arResult["QUESTIONS"]) > 1):?>
				<div class="left">
					<p><b>Оцените по пятибальной шкале:</b></p>
					<?foreach($arResult["QUESTIONS"] as $key => $question):?>
						<div class="line">
							<label><?=$question?></label>				
							<?foreach($arResult["ANSWERS"] as $answer):?>
								<a href="#" class="answer" style="background: url('<?=$answer["PICTURE"]?>') 0% 100% no-repeat;"><span class="question"><i></i><?=$answer["NAME"]?></span></a>
							<?endforeach;?>
							<input type="hidden" name="ANSWERS[<?=$key?>]" value="" />
							<div class="clear"></div>
						</div>
					<?endforeach;?>
				</div>
				<div class="right">
			<?endif;?>
					<p class="description">Если Вы хотите поделиться своим мнением о работе нашей компании, а также сообщить о негативном или позитивном опыте общения с сотрудниками, пожалуйста, заполните форму ниже:</p>
					<div class="line">
						<label>ФИО или Название компании<span class="required">*</span></label>				
						<div class="clear"></div>
						<input type="text" name="NAME" class="required" value="" />
						<div class="clear"></div>
					</div>
					<div class="line">
						<label>Телефон</label>				
						<div class="clear"></div>
						<input type="text" class="phone" name="PHONE" value="" />
						<div class="clear"></div>
					</div>
					<div class="line">
						<label>Email</label>				
						<div class="clear"></div>
						<input type="text" class="email" name="EMAIL" value="" />
						<div class="clear"></div>
					</div>
					<div class="line">
						<label>Текст сообщения</label>				
						<div class="clear"></div>
						<textarea name="COMMENT" placeholder="Ваши комментарии"></textarea>
						<div class="clear"></div>
					</div>
			<?if(count($arResult["QUESTIONS"]) > 1):?>
				</div>
				<div class="clear"></div>
			<?endif;?>
			<button type="submit" name="send">Отправить</button>
		</form>
	</div>
</div>
<div class="dialog" id="survey_ok"></div>