<?php
IncludeModuleLangFile(__FILE__);

class CASDcropphotoHelper {

	public static function GetJSIB() {
		ob_start();
		?>
		<script type="text/javascript">
			CAdminDialogCrop = function(file_id) {
				var arResult = {
					'entity': '<? echo ($GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/iblock_section_edit.php' || $GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/cat_section_edit.php' ? 'G' : 'E'); ?>',
					'sessid': BX.bitrix_sessid()
				};
				(new BX.CAdminDialog({
					'content_url':'/bitrix/tools/asd_cropphoto.php?fid='+file_id+'&id=<?= intval($_REQUEST['ID']);?>&bid=<?= intval($_REQUEST['IBLOCK_ID']);?>&bxpublic=Y',
					'content_post': arResult,
					'width':'500',
					'height':'450',
					'resizable':true,
					'buttons': [BX.CAdminDialog.btnSave, BX.CAdminDialog.btnCancel]
				})).Show();
			}
			BX.file_input.prototype.DisplayExistFile = function(i)
			{
				var file = this.arConfig.files[i];

				if (file.FILE_NOT_FOUND)
				{
					var pMenu = BX(this.id + '_menu_' + i);
					if (pMenu && this.arConfig.showDel)
					{
						this.SetOpenerMenu(pMenu, [{TEXT: BX.message('ADM_FILE_DELETE'), ONCLICK: BX.proxy(this.DeleteFile, this), GLOBAL_ICON: 'adm-menu-delete'}]);
					}
				}
				else
				{
					if (!this.arConfig.viewMode)
					{
						var pMenu = BX(this.id + '_menu_' + i);
						if (pMenu)
						{
							//var arMenu = this.multiple ? [] : BX.clone(this.arConfig.menuExist);
							var arMenu = BX.clone(this.arConfig.menuExist);
							if(this.arConfig.showDel || this.arConfig.showDesc)
								arMenu.push({SEPARATOR: true});
							if(this.arConfig.showDel)
								arMenu.push({TEXT: BX.message('ADM_FILE_DELETE'), ONCLICK: BX.proxy(this.DeleteFile, this), GLOBAL_ICON: 'adm-menu-delete'});
							if (this.arConfig.showDesc && file.DESCRIPTION == "")
								arMenu.push({TEXT: BX.message('ADM_FILE_ADD_DESC'), ONCLICK: BX.proxy(this.AddDescription, this), GLOBAL_ICON: 'adm-menu-add-desc'});

							if (file.IS_IMAGE) {
								arMenu.push({SEPARATOR: true});
								arMenu.push({'TEXT':'<?= GetMessageJS('ASD_CL_CROP')?>','GLOBAL_ICON':'adm-menu-upload-crop','ONCLICK':'CAdminDialogCrop('+file.ID+')'});
							}

							this.SetOpenerMenu(pMenu, arMenu);

							if (!file.IS_IMAGE)
								pMenu.style.top = '-6px';
						}

						var fileContainer = BX(this.id + '_file_cont_' + i);
						if (fileContainer)
						{
							var
								inpName = file.INPUT_NAME || this.GetInputName('first_input'),
								inp;

							if (this.arConfig.useUpload) // hack for iblock forms (for editing description)
								inp = BX.create('INPUT', {props: {type: "file", name: inpName, className: 'adm-designed-file adm-input-file-none'}});
							else
								inp = BX.create('INPUT', {props: {type: "hidden", value: file.PATH || file.SRC, name: inpName}});

							inp.id = this.id + '_file_hidden_value_' + i;
							fileContainer.appendChild(inp);
						}
					}

					if (file.IS_IMAGE)
					{
						var
							pSpan = BX(this.id + '_file_disp_' + i),
							pImg = BX.findChild(pSpan, {tag: "IMG"}, true);

						if (pImg)
						{
							var
								pNode = pImg,
								h = parseInt(pImg.getAttribute('height') || pImg.offsetHeight),
								w = parseInt(pImg.getAttribute('width') || pImg.offsetWidth);

							if (this.arConfig.minPreviewHeight > h || this.arConfig.minPreviewWidth > w)
							{
								if (this.arConfig.minPreviewHeight < h)
									pSpan.style.height = (h + 4) + 'px';
								if (this.arConfig.minPreviewWidth < w)
									pSpan.style.width = (w + 4) + 'px';

								if (pNode.parentNode.tagName.toLowerCase() == 'a')
									pNode = pNode.parentNode;

								BX.addClass(pSpan, 'adm-input-file-bordered');
								pNode.style.position = 'absolute';
								pNode.style.top = '50%';
								pNode.style.left = '50%';
								pNode.style.marginTop = Math.round(- h / 2) + 'px';
								pNode.style.marginLeft = Math.round(- w / 2) + 'px';
							}
						}
					}
				}
			};
		</script>
		<?
		$script = ob_get_contents();
		ob_end_clean();
		return $script;
	}
}