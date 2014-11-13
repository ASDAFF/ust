window.SBPETabs = function()
{
	if (window.SBPETabs.instance != null)
	{
		throw "SBPETabs is a singleton. Use SBPETabs.getInstance to get an object.";
	}

	this.tabs = {};
	this.bodies = {};
	this.active = null;
	this.animation = null;
	this.animationStartHeight = 0;

	this.menu = null;
	this.menuItems = [];

	if (this.inited !== true)
		this.init();

	window.SBPETabs.instance = this;
};

window.SBPETabs.instance = null;

window.SBPETabs.getInstance = function()
{
	if (window.SBPETabs.instance == null)
	{
		window.SBPETabs.instance = new SBPETabs();
	}

	return window.SBPETabs.instance;
};

window.SBPETabs.changePostFormTab = function(type)
{
	var tabsObj = window.SBPETabs.getInstance();
	return tabsObj.setActive(type);
};

window.SBPETabs.prototype = {

	_createOnclick : function(id, name, onclick)
	{
		return function()
		{
			var btn = BX("feed-add-post-form-link-more", true);
			var btnText = BX("feed-add-post-form-link-text", true);
			btnText.innerHTML = name;
			btn.className = "feed-add-post-form-link feed-add-post-form-link-more feed-add-post-form-link-active feed-add-post-form-" + id + "-link";

			window.SBPETabs.changePostFormTab(id);

			if (BX.type.isNotEmptyString(onclick))
			{
				BX.evalGlobal(onclick);
			}

			this.popupWindow.close();
		}
	},

	init : function()
	{
		this.tabContainer = BX('feed-add-post-form-tab');
		var arTabs = BX.findChildren(this.tabContainer, {'tag':'span', 'className': 'feed-add-post-form-link'}, true);
		this.arrow = BX('feed-add-post-form-tab-arrow');
		this.tabs = {}; this.bodies = {};

		for (var i = 0; i < arTabs.length; i++)
		{
			var id = arTabs[i].getAttribute("id").replace("feed-add-post-form-tab-", "");
			this.tabs[id] = arTabs[i];
			if (this.tabs[id].style.display == "none")
			{
				this.menuItems.push({
					tabId : id,
					text : arTabs[i].getAttribute("data-name"),
					className : "feed-add-post-form-" + id,
					onclick : this._createOnclick(id, arTabs[i].getAttribute("data-name"), arTabs[i].getAttribute("data-onclick"))
				});

				this.tabs[id] = this.tabs[id].parentNode;
			}

			this.bodies[id] = BX('feed-add-post-content-' + id);
		}

		if (!!this.tabs['file'])
			this.bodies['file'] = [this.bodies['message']];
		if (!!this.tabs['calendar'])
			this.bodies['calendar'] = [this.bodies['calendar']];
		if (!!this.tabs['vote'])
			this.bodies['vote'] = [this.bodies['message'], this.bodies['vote']];
		if (!!this.tabs['more'])
			this.bodies['more'] = null;
		if (!!this.tabs['important'])
			this.bodies['important'] = [this.bodies['message'], this.bodies['important']];
		if (!!this.tabs['grat'])
			this.bodies['grat'] = [this.bodies['message'], this.bodies['grat']];

		for (var ii in this.bodies)
		{
			if (BX.type.isDomNode(this.bodies[ii]))
				this.bodies[ii] = [this.bodies[ii]];
		}
		this.inited = true;
		this.previousTab = false;
		BX('bx-b-uploadfile-blogPostForm').setAttribute("bx-press", "pressOut");
		BX.bind(BX('bx-b-uploadfile-blogPostForm'), "mousedown", BX.delegate(function(){
			BX('bx-b-uploadfile-blogPostForm').setAttribute("bx-press", (BX('bx-b-uploadfile-blogPostForm').getAttribute("bx-press") == "pressOut" ? "pressOn" : "pressOut"));}, this));
		BX.onCustomEvent(this.tabContainer, "onObjectInit", [this]);

		var form = BX('blogPostForm');
		if (form)
		{
			if (!form.changePostFormTab)
			{
				form.appendChild( BX.create('INPUT', {
					props : {
						'type': 'hidden',
						'name': 'changePostFormTab',
						'value': ''
					}
				}));
			}

			BX.addCustomEvent(window, "changePostFormTab", function(type) {
				if (type != "more")
				{
					form.changePostFormTab.value = type;
				}
			});

			if (form.UF_BLOG_POST_IMPRTNT)
			{
				BX.addCustomEvent(window, "changePostFormTab", function(type) {
					if (type != "more")
					{
						form.UF_BLOG_POST_IMPRTNT.value = type == "important" ? 1 : 0;
					}
				});
			}

		}
	},

	setActive : function(type)
	{
		if (type == null || this.active == type)
			return this.active;
		else if (!this.tabs[type])
			return false;

		this.startAnimation();

		for (var ii in this.tabs)
		{
			if (ii != type)
			{
				BX.removeClass(this.tabs[ii], 'feed-add-post-form-link-active');
				if (this.bodies[ii] == null || this.bodies[type] == null)
					continue;
				for (var jj = 0; jj < this.bodies[ii].length; jj++)
				{
					if (this.bodies[type][jj] != this.bodies[ii][jj])
						BX.adjust(this.bodies[ii][jj], {style : {display : "none"}});
				}
			}
		}

		if (!!this.tabs[type])
		{
			this.active = type;
			BX.addClass(this.tabs[type], 'feed-add-post-form-link-active');
			var tabPosTab = BX.pos(this.tabs[type], true);
			this.arrow.style.left = (tabPosTab.left + 25) + 'px';

			if (this.previousTab == 'file' || type == 'file')
			{
				var
					nodeFile = null,
					nodeDocs = null,
					hasValuesFile = false,
					hasValuesDocs = false,
					messageBody = BX('divoPostFormLHE_blogPostForm');

				if (!!messageBody.childNodes && messageBody.childNodes.length > 0)
				{
					for (var ii in messageBody.childNodes)
					{
						if (messageBody.childNodes[ii].className == "file-selectdialog")
						{
							nodeFile = messageBody.childNodes[ii];
							var
								values1 = BX.findChild(nodeFile, {'className': 'file-placeholder-tbody'}, true),
								values2 = BX.findChildren(nodeFile, {'className': 'feed-add-photo-block'}, true);
							if (values1.rows > 0 || !!values2 && values2.length > 1)
								hasValuesFile = true;
						}
						else if (messageBody.childNodes[ii].className == "wduf-selectdialog")
						{
							nodeDocs = messageBody.childNodes[ii];
							var webdavValues = BX.findChildren(nodeDocs, {"className" : "wd-inline-file"}, true);
							hasValuesDocs = (!!webdavValues && webdavValues.length > 0);

						}
						else if(BX.type.isElementNode(messageBody.childNodes[ii]))
						{
							BX.adjust(messageBody.childNodes[ii], {style : {display : (type == 'file' ? "none" : "")}});
						}
					}

					if (type == 'file')
					{
						if (!!window["PlEditorblogPostForm"])
						{
							if (!!window["PlEditorblogPostForm"].FControllerInit)
							{
								window["PlEditorblogPostForm"].FControllerInit('show');
							}
							if (!!window["PlEditorblogPostForm"].WDControllerInit)
							{
								var func = function(wdObj) {
									if (wdObj.dialogName == 'AttachFileDialog' && wdObj.urlUpload.indexOf('&dropped=Y') < 0) {
										BX.addCustomEvent(wdObj.controller.parentNode, 'OnFileFromDialogSelected', function(){
											BX('bx-b-uploadfile-blogPostForm').setAttribute("bx-press", "pressOn");
											window.SBPETabs.changePostFormTab("message");
										});
										wdObj.urlUpload = wdObj.agent.urlUpload = wdObj.urlUpload.replace('&random_folder=Y', '&dropped=Y');
										wdObj.agent.hUploaderChange = BX.delegate(function() {
											BX('bx-b-uploadfile-blogPostForm').setAttribute("bx-press", "pressOn");
											window.SBPETabs.changePostFormTab("message");
											this.onUploaderChange();
										}, wdObj.agent);
									}
									},
									func2 = function(wdObj){
										if (!wdObj.agent.dropbox){
											setTimeout(function(){func2(wdObj)}, 100);
										} else if (!wdObj.agent.dropbox.socnetInited) {
											wdObj.agent.dropbox.socnetInited = true;
											BX.addCustomEvent(wdObj.agent.dropbox, 'dropFiles', function(){
												BX('bx-b-uploadfile-blogPostForm').setAttribute("bx-press", "pressOn");
												window.SBPETabs.changePostFormTab("message"); });
										}
									};
								if (!!window["PlEditorblogPostForm"].WDController){
									func(window["PlEditorblogPostForm"].WDController);
									func2(window["PlEditorblogPostForm"].WDController);
								} else {
									BX.addCustomEvent(window["PlEditorblogPostForm"].WDControllerNode, 'WDLoadFormControllerInit', func);
									BX.addCustomEvent(window["PlEditorblogPostForm"].WDControllerNode, 'WDLoadFormControllerInit', func2);
								}
								window["PlEditorblogPostForm"].WDControllerInit('show');
							}
						}
						BX.addClass(messageBody, "feed-add-post-form");
						BX.addClass(messageBody, "feed-add-post-edit-form");
						BX.addClass(messageBody, "feed-add-post-edit-form-file");
					}
					else
					{
						BX.removeClass(messageBody, "feed-add-post-form");
						BX.removeClass(messageBody, "feed-add-post-edit-form");
						BX.removeClass(messageBody, "feed-add-post-edit-form-file");
						if (!hasValuesFile && !hasValuesDocs && BX('bx-b-uploadfile-blogPostForm').getAttribute("bx-press")=="pressOut") {
							if (!!nodeFile) nodeFile.style.display = "none";
							if (!!nodeDocs) nodeDocs.style.display = "none";
						}
					}
				}
			}

			if (BX('divoPostFormLHE_blogPostForm').style.display == "none")
			{
				BX.onCustomEvent(BX('divoPostFormLHE_blogPostForm' ), 'OnShowLHE', ['justShow']);
			}

			this.previousTab = type;
			if (!!this.bodies[type])
			{
				for (var jj = 0; jj < this.bodies[type].length; jj++)
				{
					BX.adjust(this.bodies[type][jj], {style : {display : "block"}});
				}
			}
		}

		this.endAnimation();

		this.restoreMoreMenu();

		BX.onCustomEvent(window, "changePostFormTab", [type]);
		return this.active;
	},

	getCurrentTab : function()
	{
		return this.active;
	},

	startAnimation : function()
	{
		if (this.animation)
			this.animation.stop();

		var container = BX("microblog-form", true);
		this.animationStartHeight = container.parentNode.offsetHeight;

		container.parentNode.style.height = this.animationStartHeight + "px";
		container.parentNode.style.overflowY = "hidden";
		container.parentNode.style.position = "relative";
		container.style.opacity = 0;
	},

	endAnimation : function()
	{
		var container = BX("microblog-form", true);

		this.animation = new BX.easing({
			duration : 500,
			start : { height: this.animationStartHeight, opacity : 0 },
			finish : { height: container.offsetHeight + container.offsetTop, opacity : 100 },
			transition : BX.easing.makeEaseOut(BX.easing.transitions.quart),

			step : function(state){
				container.parentNode.style.height = state.height + "px";
				container.style.opacity = state.opacity / 100;
			},

			complete : BX.proxy(function() {
				container.style.cssText = "";
				container.parentNode.style.cssText = "";
				this.animation = null;
			}, this)

		});

		this.animation.animate();
	},

	collapse : function()
	{
		window.SBPETabs.changePostFormTab("message");
		this.startAnimation();
		BX.onCustomEvent(BX("divoPostFormLHE_blogPostForm"), "OnShowLHE", [false]);
		this.endAnimation();

		this.active = null;
	},

	showMoreMenu : function()
	{
		if (!this.menu)
		{
			this.menu = BX.PopupMenu.create(
				"feed-add-post-form-popup",
				BX("feed-add-post-form-link-text"),
				this.menuItems,
				{
					closeByEsc : true,
					offsetTop: 5,
					offsetLeft: 3,
					angle: true
				}
			);
		}

		this.menu.popupWindow.show();
	},

	restoreMoreMenu : function()
	{
		var itemCnt = this.menuItems.length;
		if (itemCnt < 1)
		{
			return;
		}

		for (var i = 0; i < itemCnt; i++)
		{
			if (this.active == this.menuItems[i]["tabId"])
			{
				return;
			}
		}

		var btn = BX("feed-add-post-form-link-more", true);
		var btnText = BX("feed-add-post-form-link-text", true);
		btn.className = "feed-add-post-form-link feed-add-post-form-link-more";
		btnText.innerHTML = BX.message("SBPE_MORE");
	}
};


window.BXfpGratSelectCallback = function(item, type_user, name)
{
	BXfpGratMedalSelectCallback(item, 'grat');
};

window.BXfpMedalSelectCallback = function(item, type_user, name)
{
	BXfpGratMedalSelectCallback(item, 'medal');
};

window.BXfpGratMedalSelectCallback = function(item, type)
{
	if (type != 'grat')
		type = 'medal';

	var prefix = 'U';

	BX('feed-add-post-'+type+'-item').appendChild(
		BX.create("span", {
			attrs : { 'data-id' : item.id },
			props : { className : "feed-add-post-"+type+" feed-add-post-destination-users" },
			children: [
				BX.create("input", {
					attrs : { 'type' : 'hidden', 'name' : (type == 'grat' ? 'GRAT' : 'MEDAL')+'['+prefix+'][]', 'value' : item.id }
				}),
				BX.create("span", {
					props : { 'className' : "feed-add-post-"+type+"-text" },
					html : item.name
				}),
				BX.create("span", {
					props : { 'className' : "feed-add-post-del-but"},
					events : {
						'click' : function(e){
							BX.SocNetLogDestination.deleteItem(item.id, 'users', BXSocNetLogGratFormName);
							BX.PreventDefault(e)
						},
						'mouseover' : function(){
							BX.addClass(this.parentNode, 'feed-add-post-'+type+'-hover')
						},
						'mouseout' : function(){
							BX.removeClass(this.parentNode, 'feed-add-post-'+type+'-hover')
						}
					}
				})
			]
		})
	);

	BX('feed-add-post-'+type+'-input').value = '';
	BXfpGratMedalLinkName(type == 'grat' ? BXSocNetLogGratFormName : BXSocNetLogMedalFormName, type);
};

window.BXfpGratUnSelectCallback = function(item, type, search)
{
	BXfpGratMedalUnSelectCallback(item, 'grat');
};

window.BXfpMedalUnSelectCallback = function(item, type, search)
{
	BXfpGratMedalUnSelectCallback(item, 'medal');
};

window.BXfpGratMedalUnSelectCallback = function(item, type)
{
	var elements = BX.findChildren(BX('feed-add-post-'+type+'-item'), {attribute: {'data-id': ''+item.id+''}}, true);
	if (elements != null)
	{
		for (var j = 0; j < elements.length; j++)
			BX.remove(elements[j]);
	}
	BX('feed-add-post-'+type+'-input').value = '';
	BXfpGratMedalLinkName((type == 'grat' ? BXSocNetLogGratFormName : BXSocNetLogMedalFormName), type);
};

window.BXfpGratMedalLinkName = function(name, type)
{
	if (type != 'grat')
		type = 'medal';

	if (BX.SocNetLogDestination.getSelectedCount(name) <= 0)
		BX('bx-'+type+'-tag').innerHTML = BX.message("BX_FPGRATMEDAL_LINK_1");
	else
		BX('bx-'+type+'-tag').innerHTML = BX.message("BX_FPGRATMEDAL_LINK_2");
};

window.BXfpGratOpenDialogCallback = function()
{
	BX.style(BX('feed-add-post-grat-input-box'), 'display', 'inline-block');
	BX.style(BX('bx-grat-tag'), 'display', 'none');
	BX.focus(BX('feed-add-post-grat-input'));
};

window.BXfpGratCloseDialogCallback = function()
{
	if (!BX.SocNetLogDestination.isOpenSearch() && BX('feed-add-post-grat-input').value.length <= 0)
	{
		BX.style(BX('feed-add-post-grat-input-box'), 'display', 'none');
		BX.style(BX('bx-grat-tag'), 'display', 'inline-block');
		BXfpdDisableBackspace();
	}
};

window.BXfpGratCloseSearchCallback = function()
{
	if (!BX.SocNetLogDestination.isOpenSearch() && BX('feed-add-post-grat-input').value.length > 0)
	{
		BX.style(BX('feed-add-post-grat-input-box'), 'display', 'none');
		BX.style(BX('bx-grat-tag'), 'display', 'inline-block');
		BX('feed-add-post-grat-input').value = '';
		BXfpdDisableBackspace();
	}

};

window.BXfpGratSearchBefore = function(event)
{
	if (event.keyCode == 8 && BX('feed-add-post-grat-input').value.length <= 0)
	{
		BX.SocNetLogDestination.sendEvent = false;
		BX.SocNetLogDestination.deleteLastItem(BXSocNetLogGratFormName);
	}

	return true;
};

window.BXfpGratSearch = function(event)
{
	if(event.keyCode == 16 || event.keyCode == 17 || event.keyCode == 18)
		return false;

	if (event.keyCode == 13)
	{
		BX.SocNetLogDestination.selectFirstSearchItem(BXSocNetLogGratFormName);
		return true;
	}
	if (event.keyCode == 27)
	{
		BX('feed-add-post-grat-input').value = '';
		BX.style(BX('bx-grat-tag'), 'display', 'inline');
	}
	else
	{
		BX.SocNetLogDestination.search(BX('feed-add-post-grat-input').value, true, BXSocNetLogGratFormName);
	}

	if (!BX.SocNetLogDestination.isOpenDialog() && BX('feed-add-post-grat-input').value.length <= 0)
	{
		BX.SocNetLogDestination.openDialog(BXSocNetLogGratFormName);
	}
	else
	{
		if (BX.SocNetLogDestination.sendEvent && BX.SocNetLogDestination.isOpenDialog())
			BX.SocNetLogDestination.closeDialog();
	}
	if (event.keyCode == 8)
	{
		BX.SocNetLogDestination.sendEvent = true;
	}
	return true;
};

;(function(){

if (!!BX.SocNetGratSelector)
	return;

BX.SocNetGratSelector =
{
	popupWindow: null,
	obWindowCloseIcon: {},
	sendEvent: true,
	obCallback: {},
	gratsContentElement: null,
	itemSelectedImageItem: {},
	itemSelectedInput: {},

	searchTimeout: null,
	obDepartmentEnable: {},
	obSonetgroupsEnable: {},
	obLastEnable: {},
	obWindowClass: {},
	obPathToAjax: {},
	obDepartmentLoad: {},
	obDepartmentSelectDisable: {},
	obItems: {},
	obItemsLast: {},
	obItemsSelected: {},

	obElementSearchInput: {},
	obElementBindMainPopup: {},
	obElementBindSearchPopup: {}
};

BX.SocNetGratSelector.init = function(arParams)
{
	if(!arParams.name)
		arParams.name = 'lm';

	BX.SocNetGratSelector.obCallback[arParams.name] = arParams.callback;
	BX.SocNetGratSelector.obWindowCloseIcon[arParams.name] = typeof (arParams.obWindowCloseIcon) == 'undefined' ? true : arParams.obWindowCloseIcon;
	BX.SocNetGratSelector.itemSelectedImageItem[arParams.name] = arParams.itemSelectedImageItem;
	BX.SocNetGratSelector.itemSelectedInput[arParams.name] = arParams.itemSelectedInput;
};

BX.SocNetGratSelector.openDialog = function(name)
{
	if(!name)
		name = 'lm';

	if (BX.SocNetGratSelector.popupWindow != null)
	{
		BX.SocNetGratSelector.popupWindow.close();
		return false;
	}

	var arGratsItems = [];
	for (var i = 0; i < arGrats.length; i++)
	{
		arGratsItems[arGratsItems.length] = BX.create("span", {
			props: {
				className: 'feed-add-grat-box ' + arGrats[i].style
			},
			attrs: {
				'title': arGrats[i].title
			},
			events: {
				'click' : BX.delegate(function(e){
					BX.SocNetGratSelector.selectItem(name, this.code, this.style, this.title);
					BX.PreventDefault(e)
				}, arGrats[i])
			}
		});
	}
	var arGratsRows = [];
	var rownum = 1;
	for (var i = 0; i < arGratsItems.length; i++)
	{
		if (i >= arGratsItems.length/2)
			rownum = 2;

		if (arGratsRows[rownum] == null || arGratsRows[rownum] == 'undefined')
			arGratsRows[rownum] = BX.create("div", {
				props: {
					className: 'feed-add-grat-list-row'
				}
			});
		arGratsRows[rownum].appendChild(arGratsItems[i]);
	}

	BX.SocNetGratSelector.gratsContentElement = BX.create("div", {
		children: [
			BX.create("div", {
				props: {
					className: 'feed-add-grat-list-title'
				},
				html: BX.message('BLOG_GRAT_POPUP_TITLE')
			}),
			BX.create("div", {
				props: {
					className: 'feed-add-grat-list'
				},
				children: arGratsRows
			})
		]
	});

	BX.SocNetGratSelector.popupWindow = new BX.PopupWindow('BXSocNetGratSelector', BX('feed-add-post-grat-type-selected'), {
		autoHide: true,
		offsetLeft: 25,
		bindOptions: { forceBindPosition: true },
		closeByEsc: true,
		closeIcon : BX.SocNetGratSelector.obWindowCloseIcon[name] ? { 'top': '5px', 'right': '10px' } : false,
		events : {
			onPopupShow : function() {
				if(BX.SocNetGratSelector.sendEvent && BX.SocNetGratSelector.obCallback[name] && BX.SocNetGratSelector.obCallback[name].openDialog)
					BX.SocNetGratSelector.obCallback[name].openDialog();
			},
			onPopupClose : function() {
				this.destroy();
			},
			onPopupDestroy : BX.proxy(function() {
				BX.SocNetGratSelector.popupWindow = null;
				if(BX.SocNetGratSelector.sendEvent && BX.SocNetGratSelector.obCallback[name] && BX.SocNetGratSelector.obCallback[name].closeDialog)
					BX.SocNetGratSelector.obCallback[name].closeDialog();
			}, this)
		},
		content: BX.SocNetGratSelector.gratsContentElement,
		angle : {
			position: "bottom",
			offset : 20
		}
	});
	BX.SocNetGratSelector.popupWindow.setAngle({});
	BX.SocNetGratSelector.popupWindow.show();
};

BX.SocNetGratSelector.selectItem = function(name, code, style, title)
{
	BX.SocNetGratSelector.itemSelectedImageItem[name].className = 'feed-add-grat-medal ' + style;
	BX.SocNetGratSelector.itemSelectedImageItem[name].title = title;
	BX.SocNetGratSelector.itemSelectedInput[name].value = code;
	BX.SocNetGratSelector.popupWindow.close();
};

})(); // one-time-use

/*
BlogPostAutoSaveIcon = function () {
	var formId = 'blogPostForm';
	var form = BX(formId);
	if (!form) return;

	auto_lnk = BX('post-form-autosave-icon');
	formHeaders = BX.findChild(form, {'className': /lhe-stat-toolbar-cont/ }, true, true);
	if (formHeaders.length < 1)
		return false;
	formHeader = formHeaders[formHeaders.length-1];
	formHeader.insertBefore(auto_lnk, formHeader.children[0]);
}
*/
BlogPostAutoSave = function (pEditorAutoSave) {

	if(pEditorAutoSave && pEditorAutoSave['id'] != 'idPostFormLHE_blogPostForm')
		return;

	var formId = 'blogPostForm';
	var form = BX(formId);
	if (!form) return;

	var controlID = "idPostFormLHE_blogPostForm";
	var titleID = 'POST_TITLE';
	title = BX(titleID);
	tags = BX(formId).TAGS;

	var	iconClass = "blogPostAutoSave";
	var	actionClass = "blogPostAutoRestore";
	var	actionText = BX.message('AUTOSAVE_R');
	var recoverMessage = BX.message('BLOG_POST_AUTOSAVE');
	var recoverNotify = null;

	var pAutoSaveEditor = window['oPostFormLHE_blogPostForm'];

	if(!pAutoSaveEditor)
	{
		setTimeout("BlogPostAutoSave()", 10);
		return;
	}

	var bindLHEEvents = function(_ob)
	{
		if (pAutoSaveEditor)
		{
			pAutoSaveEditor.fAutosave = _ob;
			BX.bind(pAutoSaveEditor.pEditorDocument, 'keydown', BX.proxy(_ob.Init, _ob));
			BX.bind(pAutoSaveEditor.pTextarea, 'keydown', BX.proxy(_ob.Init, _ob));
			BX.bind(title, 'keydown', BX.proxy(_ob.Init, _ob));
			BX.bind(tags, 'keydown', BX.proxy(_ob.Init, _ob));
		}
	}

	var asId = window['autosave_'+form['autosave_id'].value];

	BX.addCustomEvent(form, 'onAutoSavePrepare', function (ob, h) {
		ob.DISABLE_STANDARD_NOTIFY = true;
		_ob=ob;
		setTimeout(function() { bindLHEEvents(_ob) }, 100);
	});

	asId.Prepare();

	BX.addCustomEvent(form, 'onAutoSave', function(ob, form_data) {
		if (!pAutoSaveEditor) return;

		form_data[controlID+'_type'] = pAutoSaveEditor.sEditorMode;
		var text = "";
		if (pAutoSaveEditor.sEditorMode == 'code')
			text = pAutoSaveEditor.GetCodeEditorContent();
		else
			text = pAutoSaveEditor.GetEditorContent();
		form_data[controlID] = text;
		form_data['TAGS'] = BX(formId).TAGS.value;
	});

	BX.addCustomEvent(form, 'onAutoSaveRestoreFound', function(ob, data) {
		if (BX.util.trim(data[controlID]).length < 1 && BX.util.trim(data[titleID]).length < 1) return;
		ob.Restore();
		});

	BX.addCustomEvent(form, 'onAutoSaveRestore', function(ob, data) {
		if (!pAutoSaveEditor || !data[controlID]) return;

		pAutoSaveEditor.SetView(data[controlID+'_type']);

		if (!!pAutoSaveEditor.sourseBut)
			pAutoSaveEditor.sourseBut.Check((data[controlID+'_type'] == 'code'));
		if (data[controlID+'_type'] == 'code')
			pAutoSaveEditor.SetContent(data[controlID]);
		else
			pAutoSaveEditor.SetEditorContent(data[controlID]);
		BX(titleID).value = data[titleID];
		if(data[titleID].length > 0 && data[titleID] != BX(titleID).getAttribute("placeholder"))
		{
			if(BX('divoPostFormLHE_blogPostForm').style.display != "none")
				showPanelTitle_blogPostForm(true);
			else
				window["bShowTitle"] = true;
			if (!!BX(titleID).__onchange)
				BX(titleID).__onchange();
		}

		var formTags = window["BXPostFormTags_" + formId];
		if(data['TAGS'].length > 0 && formTags)
		{
			var tags = formTags.addTag(data['TAGS']);
			if (tags.length > 0)
			{
				BX.show(formTags.tagsArea);
			}
		}

		if(BX.SocNetLogDestination)
		{
			if(data['SPERM[DR][]'])
			{
				for (var i = 0; i < data['SPERM[DR][]'].length; i++ )
				{
					BX.SocNetLogDestination.selectItem(BXSocNetLogDestinationFormName, '', 3, data['SPERM[DR][]'][i], 'department', false);
				}
			}
			if(data['SPERM[SG][]'])
			{
				for (var i = 0; i < data['SPERM[SG][]'].length; i++ )
				{
					BX.SocNetLogDestination.selectItem(BXSocNetLogDestinationFormName, '', 3, data['SPERM[SG][]'][i], 'sonetgroups', false);
				}
			}
			if(data['SPERM[U][]'])
			{
				for (var i = 0; i < data['SPERM[U][]'].length; i++ )
				{
					BX.SocNetLogDestination.selectItem(BXSocNetLogDestinationFormName, '', 3, data['SPERM[U][]'][i], 'users', false);
				}
			}
			if(!data['SPERM[UA][]'])
			{
				BX.SocNetLogDestination.deleteItem('UA', 'groups', BXSocNetLogDestinationFormName);
			}
		}

		bindLHEEvents(ob);
	});

	BX.addCustomEvent(form, 'onAutoSaveRestoreFinished', function(ob, data) {
		if (!! recoverNotify)
			BX.remove(recoverNotify);
	});
};

BX.ready(function(){
	BX.addCustomEvent(
		BX('divoPostFormLHE_blogPostForm'),
		'OnAfterShowLHE',
		function() {
			var div = [
					BX('feed-add-post-form-notice-blockblogPostForm'),
					BX('feed-add-buttons-blockblogPostForm'),
					BX('feed-add-post-content-message-add-ins')];
			for (var ii = 0; ii < div.length; ii++) {
				if (!!div[ii]) {
					BX.adjust(div[ii], {style:{display:"block",height:"auto", opacity:1}});
				}
			}
			if(window["bShowTitle"])
				showPanelTitle_blogPostForm(true, false);
		}
	);
	BX.addCustomEvent(
		BX('divoPostFormLHE_blogPostForm'),
		'OnAfterHideLHE',
		function() {
			var ii = 0,
				div = [
					BX('feed-add-post-form-notice-blockblogPostForm'),
					BX('feed-add-buttons-blockblogPostForm'),
					BX('feed-add-post-content-message-add-ins')];
			for (ii = 0; ii < div.length; ii++) {
				if (!!div[ii]) {
					BX.adjust(div[ii], {style:{display:"block",height:"0px", opacity:0}});
				}
			}
			if(window["bShowTitle"])
				showPanelTitle_blogPostForm(false, false);
		}
	);
});