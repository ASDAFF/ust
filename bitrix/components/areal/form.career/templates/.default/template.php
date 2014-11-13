<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<a name="form"></a>
<div class="tooltip">
	<form name="career" method="post" action="<?=$APPLICATION->GetCurPage(false)?>#form" class="protection" enctype="multipart/form-data">
		<?=bitrix_sessid_post()?>
		<div class="title">Хочешь у нас работать?</div>
		<?if($_REQUEST["success"] == "Y"):?>
			<?echo ShowNote($arResult["SUCCESS_MESSAGE"])?>
		<?endif;?>
		<div class="line">
			<label>ФИО<span class="required">*</span></label>
			<input type="text" name="NAME" class="required" value="<?=$_REQUEST["NAME"]?>" />
			<div class="clear"></div>
		</div>		
		<div class="line">
			<label>Телефон</label>
			<input type="text" name="PHONE" class="phone" value="<?=$_REQUEST["PHONE"]?>" />
			<div class="clear"></div>
		</div>
		<div class="line">
			<label>E-mail<span class="required">*</span></label>
			<input type="text" name="EMAIL" class="required email" value="<?=$_REQUEST["EMAIL"]?>" />
			<div class="clear"></div>
		</div>
		<div class="line resume">
			<label for="work-resume">Резюме</label>
			<div class="input-files">
				<input type="text" />
				<a href="#" class="silver">Загрузить</a>
				<input type="file" name="RESUME" value="" />
			</div>
		</div>
		<div class="clear"></div>
		<p class="hint">* - поля, обязательные для заполнения.</p>
		<div class="line">
			<button type="submit" name="send">Отправить</button>
			<div class="clear"></div>
		</div>
	</form>
</div>