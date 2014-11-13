(function() {
	if (!!window.__blogEditComment)
		return false;
window.checkForQuote = function(e, node, ENTITY_XML_ID, author_id) {
	if (window.mplCheckForQuote)
		mplCheckForQuote(e, node, ENTITY_XML_ID, author_id)
}


window.__blogLinkEntity = function(entities, formId){
	if (!!window["UC"] && !!window["UC"]["f" + formId])
	{
		window["UC"]["f" + formId].linkEntity(entities);
		for (var ii in entities)
		{
			BX.bind(BX('blog-post-addc-add-' + entities[ii][1]), "click", function(){ window['UC'][ii].reply(); });
			BX.addCustomEvent(window["UC"]["f" + formId].eventNode, 'OnUCFormBeforeShow', function(obj) {
				if (!!obj && !!obj.id && obj.id[0] == ii)
				{
					BX.show(BX('blg-comment-' + entities[ii][1]));
				}
			});
			BX.addCustomEvent(window["UC"]["f" + formId].eventNode, 'OnUCFormAfterHide', function(obj) {
				if (!!obj && !!obj.id && obj.id[0] == ii)
				{
					var
						nodesNew = BX('record-' + obj.id[0] + '-new'),
						nodesNew = (!!nodesNew ? nodesNew.childNodes : []),
						nodes = BX.findChildren(BX('blg-comment-' + entities[ii][1]), {"className" : "feed-com-block-cover" }, false);
					if (!(!!nodesNew && nodesNew.length > 0) && !(!!nodes && nodes.length > 0))
					{
						BX.hide(BX('blg-comment-' + entities[ii][1]));
					}
				}
			});
		}
	}
};

window.__blogEditComment = function(key, postId){
	var data = {
		messageBBCode : top["text"+key],
		messageFields : {arFiles : top["arComFiles"+key], arDocs : top["arComDocs"+key]} };
	BX.onCustomEvent(window, 'OnUCAfterRecordEdit', ['BLOG_' + postId, key, data, 'EDIT']);
};
window.__blogOnUCFormClear = function(obj) {
	var form = obj.form,
		files = form["UF_BLOG_COMMENT_FILE[]"];
	if(files !== null && typeof files != 'undefined')
	{
		var end = false, file = false;
		do
		{
			if (!!form["UF_BLOG_COMMENT_FILE[]"])
			{
				if (!!form["UF_BLOG_COMMENT_FILE[]"][0]) {
					file = form["UF_BLOG_COMMENT_FILE[]"][0];
				} else {
					file = form["UF_BLOG_COMMENT_FILE[]"];
					end = true;
				}
				if (!!window.wduf_places && !!window.wduf_places[file.value])
					window.wduf_places[file.value] = null;
				BX.remove(file);
			}
			else {
				end = true;
			}
		} while (!end);
	}

	filesForm = BX.findChild(SBPC.form, {'className': 'wduf-placeholder-tbody' }, true, false);
	if(filesForm !== null && typeof filesForm != 'undefined')
		BX.cleanNode(filesForm, false);

	filesForm = BX.findChild(SBPC.form, {'className': 'wduf-selectdialog' }, true, false);
	if(filesForm !== null && typeof filesForm != 'undefined')
		BX.hide(filesForm);

	files = form["UF_BLOG_COMMENT_DOC[]"];
	if(files !== null && typeof files != 'undefined')
	{
		var end = false, file = false;
		do
		{
			if(!!form["UF_BLOG_COMMENT_DOC[]"])
			{
				if (!!form["UF_BLOG_COMMENT_DOC[]"][0]) {
					file = form["UF_BLOG_COMMENT_DOC[]"][0];
				} else {
					file = form["UF_BLOG_COMMENT_DOC[]"];
					end = true;
				}
				BX.remove(file);
			}
			else
			{
				end = true;
			}
		} while (!end);
	}
	filesForm = BX.findChild(SBPC.form, {'className': 'file-placeholder-tbody' }, true, false);
	if(filesForm !== null && typeof filesForm != 'undefined')
		BX.cleanNode(filesForm, false);

	filesForm = BX.findChild(SBPC.form, {'className': 'feed-add-photo-block' }, true, true);
	if(filesForm !== null && typeof filesForm != 'undefined')
	{
		for(i = 0; i < filesForm.length; i++)
		{
			if(BX(filesForm[i]).parentNode.id != 'file-image-template')
				BX.remove(BX(filesForm[i]));
		}
	}

	filesForm = BX.findChild(SBPC.form, {'className': 'file-selectdialog' }, true, false);
	if(filesForm !== null && typeof filesForm != 'undefined')
		BX.hide(filesForm);
	return false;
}
window.__blogOnUCFormAfterShow = function(obj, text, data){
	data = (!!data ? data : {});
	BX.onCustomEvent(window, "OnBeforeSocialnetworkCommentShowedUp", ['socialnetwork_blog']);
	var
		post_data = {
			ENTITY_XML_ID : obj.id[0],
			ENTITY_TYPE : obj.entitiesId[obj.id[0]][0],
			ENTITY_ID : obj.entitiesId[obj.id[0]][1],
			parentId : obj.id[1],
			comment_post_id : obj.entitiesId[obj.id[0]][1],
			edit_id : obj.id[1],
			act : (obj.id[1] > 0 ? 'edit' : 'add'),
			logId : obj.entitiesId[obj.id[0]][2]
		};
	for (var ii in post_data)
	{
		if (!obj.form[ii])
			obj.form.appendChild(BX.create('INPUT', {attrs : {name : ii, type: "hidden"}}));
		obj.form[ii].value = post_data[ii];
	}
	obj.form.action = SBPC.actionUrl.replace(/#source_post_id#/, post_data['comment_post_id']);

	var im = BX('captcha');
	if (!!im) {
		BX('captcha_del').appendChild(im);
		im.style.display = "block";
	}
	onLightEditorShow(text, data["arFiles"], data["arDocs"]);
};

window.__blogOnUCFormSubmit =  function(obj, post_data) {
	post_data["decode"] = "Y";
};

window.__blogOnUCAfterRecordAdd = function(ENTITY_XML_ID, response) {
	if (response.errorMessage.length > 0)
		return;

	if (BX('blg-post-inform-' + ENTITY_XML_ID.substr(5)))
	{
		var followNode = BX.findChild(BX('blg-post-inform-' + ENTITY_XML_ID.substr(5)), {'tag':'span', 'className': 'feed-inform-follow'}, true);	
		if (followNode)
		{
			var strFollowOld = (followNode.getAttribute("data-follow") == "Y" ? "Y" : "N");
			if (strFollowOld == "N")
			{
				BX.findChild(followNode, { tagName: 'a' }).innerHTML = BX.message('sonetBPFollowY');
				followNode.setAttribute("data-follow", "Y");
			}
		}
	}
};

window.onLightEditorShow = function(content, arFiles, arDocs){
	if(SBPC.jsObjName.length <= 0)
		return false;
	else if (!window[SBPC.jsObjName])
		return BX.addCustomEvent(window, 'LHE_OnInit', function(){setTimeout(function(){onLightEditorShow(content, arFiles, arDocs);}, 500);});
	var
		res = null, node = null, tmp = null, arRes = new Array(),
		mpFormObj = window[SBPC.jsMPFName];

	if (typeof arDocs == "object" && arDocs.length > 0)
	{
		if (!mpFormObj.WDController && !!mpFormObj.WDControllerInit)
		{
			BX.addCustomEvent(
				BX.findParent(BX.findChild(SBPC.form, {'className': 'wduf-selectdialog'}, true, false)),
				'WDLoadFormControllerInit',
				function(obj) {
					onLightEditorShow(content, arFiles, arDocs);
				}
			);
			return mpFormObj.WDControllerInit();
		}
		if (mpFormObj.WDController && !mpFormObj.WDController.onLightEditorShowObj)
		{
			mpFormObj.WDController.onLightEditorShowObj = new Array();
			BX.addCustomEvent(
				BX.findParent(BX.findChild(SBPC.form, {'className': 'wduf-selectdialog'}, true, false)),
				'OnFileUploadSuccess',
				function(result, obj) {
					if (obj.dialogName == 'AttachFileDialog' && BX.util.in_array(result['element_id'], obj['onLightEditorShowObj'])) {
						mpFormObj.oEditor.SaveContent();
						var content = mpFormObj.oEditor.GetContent();
						content = content.replace(new RegExp('\\&\\#91\\;DOCUMENT ID=(' + result['element_id'] + ')([WIDTHHEIGHT=0-9 ]*)\\&\\#93\\;','gim'), '[DOCUMENT ID=$1$2]');
						mpFormObj.oEditor.SetContent(content);
						mpFormObj.oEditor.SetEditorContent(mpFormObj.oEditor.content);
						mpFormObj.oEditor.SetFocus();
						mpFormObj.oEditor.AutoResize();
					}
				}
			);
		}
	}

	mpFormObj.arFiles = {};
	if (!!arFiles)
		while ((res = arFiles.pop()) && !!res)
			mpFormObj.checkFile(res['id'], res);
	if (!!arDocs && typeof arDocs == "object")
	{
		if (mpFormObj.WDController)
			mpFormObj.WDController.onLightEditorShowObj = new Array();
		while ((res = arDocs.pop()) && !!res)
		{
			var node1 = BX('wdif-doc-' + res), node = null;
			node = (!!node1 ? (node1.tagName == "A" ? node1 : BX.findChild(node1, {'tagName' : "IMG"}, true)) : null);
			tmp = {
				'element_id' : res,
				'element_url' : '',
				'element_name' : '',
				'element_content_type' : (!!node && node.tagName == "IMG" ? 'image/xyz' : 'notimage/xyz'),
				'storage' : 'webdav'
			};
			if (!!node)
			{
				tmp['element_url'] = (node.tagName == "A" ? node.href : node.src);
				tmp['element_name'] = node.getAttribute("alt");
				tmp['width'] = node.getAttribute("data-bx-width");
				tmp['height'] = node.getAttribute("data-bx-height");
				mpFormObj.checkFile(res, tmp);
			}

			if (mpFormObj.WDController)
			{
				if (!!node)
					tmp['element_url'] = node.getAttribute("data-bx-document");
				arRes.push(tmp);
				mpFormObj.WDController.onLightEditorShowObj.push(res);
			}
		}
		if (mpFormObj.WDController && arRes.length > 0)
		{
			mpFormObj.WDControllerInit('show');
			mpFormObj.WDController.agent.values = arRes;
			BX.onCustomEvent(mpFormObj.WDController.controller.parentNode, 'OnFileFromDialogSelected', [mpFormObj.WDController.agent.values, mpFormObj.WDController]);
			mpFormObj.WDController.agent.ShowAttachedFiles();
		}
	}

	mpFormObj.oEditor.ReInit(content || '');
	mpFormObj.oEditor.pFrame.style.height = mpFormObj.oEditor.arConfig.height;
	mpFormObj.oEditor.ResizeFrame();
	mpFormObj.oEditor.AutoResize();
	BX.defer(mpFormObj.oEditor.SetFocus, mpFormObj.oEditor);
}
})(window);