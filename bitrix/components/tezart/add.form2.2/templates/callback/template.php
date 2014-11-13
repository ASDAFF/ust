<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<?#$APPLICATION->AddHeadScript('/bitrix/components/angry/add.form/templates/.default/jquery-1.5.1.min.js');?>

<div class="success-message"></div>

<div class="contact-form-popap">
	<form name="iblock_add" action="<?=POST_FORM_ACTION_URI?>" method="post">
		
		<?=bitrix_sessid_post()?>
		
		<?if (empty($arResult["MESSAGE"])):?>
			
			<!--поля формы-->
			<?#foreach($arResult["FIELD_FORM"] as $num=>$val):?>
			<?foreach($arParams["PROPERTY_CODES"] as $num=>$val_prop):?>
				
				<?$val = $arResult["FIELD_FORM"][$val_prop]?>
				
				<!--Заголовок поля-->
				<?if($val["IS_REQUIRED"]=="Y"):?>
					<?# для поля местоположение выводим радиобытоны?>
						<div class="name_field"><?=$val["NAME"]?><span class="starrequired">*</span></div>
				<?else:?>
					<div class="name_field"><?=$val["NAME"]?></div>
				<?endif;?>
				
				<!--Поле-->
				<div>
					<?switch ($val["PROPERTY_TYPE"]):
						//если тип текст
						case "T":
						case "HTML":?>
							<div class="field"><textarea class="form-textarea" rows="10px" name="PROPERTY[<?=$val["ID"]?>]" rel="<?=$val["ID"]?>"><?=$_REQUEST['PROPERTY'][$val["ID"]]?></textarea></div>
						<?break;
						
						//если тип строка
						case "S":?>
							<?if ($val["USER_TYPE"] == "HTML" || $val["USER_TYPE"] == "TEXT"):?>
								<div class="field"><textarea class="form-textarea" rows="10px" cols="51px" name="PROPERTY[<?=$val["ID"]?>]" rel="<?=$val["ID"]?>"><?=$_REQUEST['PROPERTY'][$val["ID"]]?></textarea></div>							
							<?else:?>
								<div class="field"><input class="form-input" type="text" name="PROPERTY[<?=$val["ID"]?>]" value="<?=$_REQUEST['PROPERTY'][$val["ID"]]?>" rel="<?=$val["ID"]?>"/></div>
							<?endif?>
						<?break;

						//если тип список
						case "L":?>
							<div class="field">
								<?if ($val["LIST_TYPE"] == "L"):?>
									<select name="PROPERTY[<?=$val["ID"]?>]">
										<?foreach($val["LIST"] as $num_list=>$val_list):?>
											<?if ($_REQUEST["PROPERTY"][$val_list["PROPERTY_ID"]][$val_list["ID"]]):?>
												<option selected value="<?=$val_list["ID"]?>" ><?=$val_list["VALUE"]?></option>
											<?else:?>
												<option value="<?=$val_list["ID"]?>"><?=$val_list["VALUE"]?></option>
											<?endif;?>
										<?endforeach;?>
									</select>
								<?elseif($val["LIST_TYPE"]=="C" && $val["MULTIPLE"] == "Y"):?>
									<?foreach($val["LIST"] as $num_list=>$val_list):?>
										<?if (in_array($val_list["ID"], $_REQUEST["PROPERTY"][$val_list["PROPERTY_ID"]])):?>
											<input checked="checked" id="property_<?=$val_list["ID"]?>" type="checkbox" value="<?=$val_list["ID"]?>" name="PROPERTY[<?=$val_list["PROPERTY_ID"]?>][]">
										<?else:?>
											<input id="property_<?=$val_list["ID"]?>" type="checkbox" value="<?=$val_list["ID"]?>" name="PROPERTY[<?=$val_list["PROPERTY_ID"]?>][]">
										<?endif;?>
										&nbsp;<label for="property_<?=$val_list["ID"]?>"><?=$val_list["VALUE"]?></label>
									<?endforeach;?>
								<?elseif ($val["MULTIPLE"] == "N"):?>
									<?$count = 0;?>
									<?foreach($val["LIST"] as $num_list=>$val_list):?>
										<?if (in_array($val_list["ID"], $_REQUEST["PROPERTY"][$val_list["PROPERTY_ID"]])):?>
											<input checked="checked" id="property_<?=$val_list["ID"]?>" type="radio" value="<?=$val_list["ID"]?>" name="PROPERTY[<?=$val_list["PROPERTY_ID"]?>][]">
											<?$count++;?>
										<?else:?>
											<?if($count == 0):?>
												<input checked="checked" id="property_<?=$val_list["ID"]?>" type="radio" value="<?=$val_list["ID"]?>" name="PROPERTY[<?=$val_list["PROPERTY_ID"]?>][]">
											<?else:?>
												<input id="property_<?=$val_list["ID"]?>" type="radio" value="<?=$val_list["ID"]?>" name="PROPERTY[<?=$val_list["PROPERTY_ID"]?>][]">
											<?endif;?>
										<?endif;?>
										<?$count++;?>
										&nbsp;<label for="property_<?=$val_list["ID"]?>"><?=$val_list["VALUE"]?></label>
										<?#=$val_list["VALUE"]?><br/>
									<?endforeach;?>
								<?endif?>
							</div>
							<?
						break;
						
						//если тип привязка к элементу
						case "E":?>
							<div class="field">
								<?if ($val["LIST_TYPE"] == "L"):?>
									<select name="PROPERTY[<?=$val["ID"]?>]">
										<?foreach($val["LIST"] as $num_list=>$val_list):?>
											<?if ($_REQUEST["PROPERTY"][$val_list["PROPERTY_ID"]][$val_list["ID"]]):?>
												<option selected value="<?=$val_list["ID"]?>" ><?=$val_list["VALUE"]?></option>
											<?else:?>
												<option value="<?=$val_list["ID"]?>"><?=$val_list["VALUE"]?></option>
											<?endif;?>
										<?endforeach;?>
									</select>
								<?endif;?>
							</div>
							
						<?break;
						endswitch;
				?></div>
				<?if($val["IS_REQUIRED"]=="Y"):?>
					<?# подсказка о не заполненности полей ?>
					<div class="hint-form" id="<?=$val["ID"]?>" style="display: none;">Не заполнено обязательное поле «<?=$val["NAME"]?>»</div>
				<?endif;?>
			<?endforeach;?>
			<br/>
			<?# captcha?>
			<?if($arParams["USE_CAPTCHA"] == "Y"):?>
				<div class="captcha_pic">
					<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
					<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
				</div>
				<div class="captcha_desc">Введите слово с картинки<span class="starrequired">*</span>:</div>
				<div class="captcha_field"><input type="text" name="captcha_word" maxlength="50" value=""></div>
			<?endif?>

			<div style="clear: both;"></div>
			<div class="submit">
				<input id="submit-callback" type="submit" name="iblock_submit" value="ОТПРАВИТЬ" />
			</div>
		</form>
		
<?endif;?> 
</div>


<script>
	$(document).ready(function() {
	
		function CheckData(el)
		{
			idel = el.attr("rel");
			
			if(el.val().length == 0)
			{
				el.addClass("errors")
				$("#"+idel).css('display','block')
			}
			else
			{
				el.removeClass("errors")
				$("#"+idel).css('display','none')
			}
		}
		
		$("[name='PROPERTY[83]'], [name='PROPERTY[62]']").keyup(function(){
			CheckData($(this));
		});
		
		$('#submit-callback').click(function() {

			if(($("[name='PROPERTY[83]']").val().length > 0) && ($("[name='PROPERTY[62]']").val().length > 0))
			{
				postAjax();
				return false;
			}
			else
			{
				CheckData($("[name='PROPERTY[83]']"));
				CheckData($("[name='PROPERTY[62]']"));
				return false;
			}
		});
	
		
		function postAjax()
		{
			$.post('/forms/callback.php', {
				"ajax":"Y", 
				"callback":"Y",
				"iblock_submit":"Y",
				'PROPERTY[83]': $("[name='PROPERTY[83]']").val(), 
				'PROPERTY[62]': $("[name='PROPERTY[62]']").val(), 
				'PROPERTY[63]': $("[name='PROPERTY[63]']").val(), 
				'PROPERTY[84]': $("[name='PROPERTY[84]']").val(), 
			}, 
				function(data) {
				AfterSubmit(data);
			}, 'json');
			
			function AfterSubmit(data) {
				if(data["message"])
				{
					$(".success-message").html(data["message"]);
					$(".success-message").css('display','block')
					$(".contact-form-popap").css('display','none')
				}
				else
				{
	//				window.location.href = "http://http://gutenpack.tezart.com.ua/";
				}

			}
			
		}
		
	});
</script>