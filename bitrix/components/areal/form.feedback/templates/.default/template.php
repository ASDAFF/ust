<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="tooltip">
	<form name="callback_contacts" method="post" action="<?=$APPLICATION->GetCurPage(false)?>" class="protection">
		<?=bitrix_sessid_post()?>
		<div class="title">Форма обратной связи</div>
		<div class="description">Вы можете задать вопрос о характеристиках товара, условиях заказа либо оставить отзыв о сотрудничестве с компанией.</div>
		<?if($_REQUEST["success"] == "Y"):?>
			<?echo ShowNote($arResult["SUCCESS_MESSAGE"])?>
		<?endif;?>
		<div class="line">
			<label class="top3">ФИО и Название компании<span class="required">*</span></label>
			<input type="text" name="NAME" class="required" value="" />
			<div class="clear"></div>
		</div>
		<div class="line">
			<label>Телефон</label>
			<input type="text" name="PHONE" class="phone" value="" />
			<div class="clear"></div>
		</div>		
		<div class="line">
			<label>E-mail<span class="required">*</span></label>
			<input type="text" name="EMAIL" class="email required" value="" />
			<div class="clear"></div>
		</div>
		<div class="line autocomplete">
			<label>Город</label>				
			<input type="text" class="autocomplete town" name="TOWN" value="<?if(!empty($_SESSION["SELECTED_TOWN"])) echo trim($_SESSION["SELECTED_TOWN"])?>" />
			<div class="b-places"></div>
			<div class="clear"></div>
		</div>
		<?if(!empty($arResult["THEMES"])):?>
			<div class="line">
				<label>Тема</label>
				<select name="THEME">
					<?foreach($arResult["THEMES"] as $key => $theme):?>
						<option value="<?=$key?>"><?=$theme["NAME"]?></option>
					<?endforeach;?>
				</select>
				<div class="clear"></div>
			</div>
		<?endif;?>
		<div class="line">
			<label>Комментарий<span class="required">*</span></label>
			<textarea class="required" type="text" name="COMMENT" /></textarea>
			<div class="clear"></div>
		</div>
		<p class="hint">* - поля, обязательные для заполнения.</p>
		<div class="line">
			<button type="submit" name="send">Отправить</button>
			<div class="clear"></div>
		</div>
	</form>
</div>