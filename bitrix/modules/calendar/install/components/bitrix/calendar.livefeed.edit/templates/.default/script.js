;(function(window){

	//
	window.EditEventManager = function(config)
	{
		this.config = config;
		this.id = this.config.id;
		this.bAMPM = this.config.bAMPM;
		this.bPanelShowed = true;
		this.bFullDay = false;
		this.bReminder = false;
		this.bAdditional = false;
		this.arFiles = this.config.arFiles || [];
		this.parser = {
			postimage : {
				exist : true,
				tag : 'postimage',
				thumb_width : 800,
				regexp : /\[IMG ID=((?:\s|\S)*?)(?:\s*?WIDTH=(\d+)\s*?HEIGHT=(\d+))?\]/ig,
				code : '[IMG ID=#ID##ADDITIONAL#]',
				html : '<img id="#ID#" src="#SRC#" lowsrc="#LOWSRC#" title=""#ADDITIONAL# />'
			},
			postfile : {
				exist : true,
				tag : 'postfile',
				thumb_width : 800,
				regexp : /\[FILE ID=((?:\s|\S)*?)(?:\s*?WIDTH=(\d+)\s*?HEIGHT=(\d+))?\]/ig,
				code : '[FILE ID=#ID##ADDITIONAL#]',
				html : '<span style="color: #2067B0; border-bottom: 1px dashed #2067B0; margin:0 2px;" id="#ID#"#ADDITIONAL#>#NAME#</span>'
			},
			postdocument : {
				exist : true,
				tag : "postdocument", // and parser LHE
				thumb_width : 800,
				regexp : /\[DOCUMENT ID=((?:\s|\S)*?)(?:\s*?WIDTH=(\d+)\s*?HEIGHT=(\d+))?\]/ig,
				code : '[DOCUMENT ID=#ID##ADDITIONAL#]',
				html : '<span style="color: #2067B0; border-bottom: 1px dashed #2067B0; margin:0 2px;" id="#ID#"#ADDITIONAL#>#NAME#</span>'
			}
		};

		this.Init();

		this.defaultValues = {
			remind: {count: 15, type: 'min'}
		};

		this.config.arEvent = this.HandleEvent(this.config.arEvent);
		this.ShowFormData(this.config.arEvent);
	};

	window.EditEventManager.prototype = {
		Init: function()
		{
			var _this = this;
			this.pEditorCont = BX(this.config.editorContId);
			// From-to
			this.pFromToCont = BX('feed-cal-from-to-cont' + this.id);
			this.pFromDate = BX('feed-cal-event-from' + this.id);
			this.pToDate = BX('feed-cal-event-to' + this.id);
			this.pFromTime = BX('feed_cal_event_from_time' + this.id);
			this.pToTime = BX('feed_cal_event_to_time' + this.id);
			this.pFullDay = BX('event-full-day' + this.id);
			this.pFromTs = BX('event-from-ts' + this.id);
			this.pToTs = BX('event-to-ts' + this.id);
			//Reminder
			this.pReminderCont = BX('feed-cal-reminder-cont' + this.id);
			this.pReminder = BX('event-reminder' + this.id);

			this.pEventName = BX('feed-cal-event-name' + this.id);
			this.pForm = this.pEventName.form;
			this.pLocation = BX('event-location' + this.id);
			this.pImportance = BX('event-importance' + this.id);
			this.pAccessibility = BX('event-accessibility' + this.id);
			this.pSection = BX('event-section' + this.id);
			this.pRemCount = BX('event-remind_count' + this.id);
			this.pRemType = BX('event-remind_type' + this.id);

			// Control events
			this.pFullDay.onclick = BX.proxy(this.FullDay, this);
			this.pReminder.onclick = BX.proxy(this.Reminder, this);

			BX.bind(this.pForm, 'submit', BX.proxy(this.OnSubmit, this));
			// *************** Init events ***************
			BX.addCustomEvent('LHE_OnInit', function(pEditor)
			{
				if (pEditor.id == _this.config.LHEId)
					_this.OnEditorInit(pEditor);
			});

			if (!window[this.config.LHEJsObjName] && window['LoadLHE_' + this.config.LHEId])
			{
				window['LoadLHE_' + this.config.LHEId]();
			}
			BX.addCustomEvent('onCalendarLiveFeedShown', BX.proxy(this.OnShow, this));
			BX('cal-editor-show-panel-btn').onclick = BX.proxy(this.ShowTopPanel, this);

			//this.InitWDFileController();

			BX("feed-cal-additional-show").onclick = BX("feed-cal-additional-hide").onclick = BX.proxy(this.ShowAdditionalParams, this);

			this.InitDateTimeControls();


			// repeat
			this.pRepeat = BX('event-repeat' + this.id);
			this.pRepeatDetails = BX('event-repeat-details' + this.id);
			this.RepeatDiapTo = BX('event-repeat-to' + this.id);
			this.RepeatDiapToValue = BX('event-repeat-to-value' + this.id);

			this.pRepeat.onchange = function()
			{
				var value = this.value;
				_this.pRepeatDetails.className = "feed-cal-repeat-details feed-cal-repeat-details-" + value.toLowerCase();
			};
			this.pRepeat.onchange();

			this.RepeatDiapTo.onclick = function(){
				BX.calendar({node: this, field: this, bTime: false});
				BX.focus(this);
			};
			this.RepeatDiapTo.onfocus = function()
			{
				if (!this.value || this.value == _this.config.message.NoLimits)
					this.title = this.value = '';
				this.style.color = '#000000';
			};
			this.RepeatDiapTo.onblur = this.RepeatDiapTo.onchange = function()
			{
				if (this.value && this.value != _this.config.message.NoLimits)
				{
					var until = BX.parseDate(this.value);
					if (until && until.getTime)
						_this.RepeatDiapToValue.value = BX.date.getServerTimestamp(until.getTime());
					this.style.color = '#000000';
					this.title = '';
					return;
				}
				this.title = this.value = _this.config.message.NoLimits;
				this.style.color = '#C0C0C0';
			};
			this.RepeatDiapTo.onchange();

//			var uploadfile = BX('bx-b-uploadfile-' + formID);
//			if (!!uploadfile && !!params["WDLoadFormController"])
//			{
//				BX.bind(uploadfile, 'click', this.WDControllerInit);
//				uploadfile = null;
//			}
//			if (!!params["BFileDLoadFormController"])
//			{
//				var node = !!uploadfile ? uploadfile : BX('bx-b-uploadimage-' + formID);
//				while (!!node) {
//					BX.bind(node, 'click', this.FControllerInit);
//					node = (node == uploadfile ? BX('bx-b-uploadimage-' + formID) : false);
//				}
//			}

		},

		InitDateTimeControls: function()
		{
			var _this = this;
			// Date
			this.pFromDate.onclick = function(){BX.calendar({node: this.parentNode, field: this, bTime: false});};
			this.pToDate.onclick = function(){BX.calendar({node: this.parentNode, field: this, bTime: false});};

			this.pFromDate.onchange = function()
			{
				if(_this._FromDateValue)
				{
					var
						prevF = BX.parseDate(_this._FromDateValue),
						F = BX.parseDate(_this.pFromDate.value),
						T = BX.parseDate(_this.pToDate.value);

					if (F)
					{
						var duration = T.getTime() - prevF.getTime();
						T = new Date(F.getTime() + duration);
						_this.pToDate.value = bxFormatDate(T.getDate(), T.getMonth() + 1, T.getFullYear());
					}
				}
				_this._FromDateValue = _this.pFromDate.value;
			};

			// Time
			this.pFromTime.parentNode.onclick = this.pFromTime.onclick = window['bxShowClock_' + 'feed_cal_event_from_time' + this.id];
			this.pToTime.parentNode.onclick = this.pToTime.onclick = window['bxShowClock_' + 'feed_cal_event_to_time' + this.id];

			this.pFromTime.onchange = function()
			{
				if (_this.pToTime.value == "")
				{
					if(BX.util.trim(_this.pFromDate.value) == BX.util.trim(_this.pToDate.value) && BX.util.trim(_this.pToDate.value) != '')
					{
						var fromTime = _this.ParseTime(this.value);
						if (fromTime.h >= 23)
						{
							_this.pToTime.value = formatTimeByNum(0, fromTime.m, _this.bAMPM);
							var date = BX.parseDate(_this.pFromDate.value);
							if (date)
							{
								date.setDate(date.getDate() + 1);
								_this.pToDate.value = bxFormatDate(date.getDate(), date.getMonth() + 1, date.getFullYear());
							}
						}
						else
						{
							_this.pToTime.value = formatTimeByNum(parseInt(fromTime.h, 10) + 1, fromTime.m, _this.bAMPM);
						}
					}
					else
					{
						_this.pToTime.value = _this.pFromTime.value;
					}
				}
				else if (_this.pToDate.value == '' || _this.pToDate.value == _this.pFromDate.value)
				{
					if (_this.pToDate.value == '')
						_this.pToDate.value = _this.pFromDate.value;

					// 1. We need prev. duration
					if(_this._FromTimeValue)
					{
						var
							F = BX.parseDate(_this.pFromDate.value),
							T = BX.parseDate(_this.pToDate.value),
							prevFromTime = _this.ParseTime(_this._FromTimeValue),
							fromTime = _this.ParseTime(_this.pFromTime.value),
							toTime = _this.ParseTime(_this.pToTime.value);

						F.setHours(prevFromTime.h);
						F.setMinutes(prevFromTime.m);
						T.setHours(toTime.h);
						T.setMinutes(toTime.m);

						var duration = T.getTime() - F.getTime();
						if (duration != 0)
						{
							F.setHours(fromTime.h);
							F.setMinutes(fromTime.m);

							T = new Date(F.getTime() + duration);
							_this.pToDate.value = bxFormatDate(T.getDate(), T.getMonth() + 1, T.getFullYear());
							_this.pToTime.value = formatTimeByNum(T.getHours(), T.getMinutes(), _this.bAMPM);
						}
					}
				}

				_this._FromTimeValue = _this.pFromTime.value;
			};
		},

		OnSubmit: function()
		{
			// Datetime limits
			var fd = BX.parseDate(this.pFromDate.value);
			var td = BX.parseDate(this.pToDate.value);

			if (!fd)
				fd = getUsableDateTime(new Date().getTime()).oDate;

			if (this.pFromTime.value == '' && this.pToTime.value == '')
				this.pFullDay.checked = true;

			if (this.pFullDay.checked)
				this.pFromTime.value = this.pToTime.value = '';

			var fromTime = this.ParseTime(this.pFromTime.value);
			fd.setHours(fromTime.h);
			fd.setMinutes(fromTime.m);
			var
				to,
				from = BX.date.getServerTimestamp(fd.getTime());

			if (td)
			{
				var toTime = this.ParseTime(this.pToTime.value);
				td.setHours(toTime.h);
				td.setMinutes(toTime.m);
				to = BX.date.getServerTimestamp(td.getTime());

				if (from == to && toTime.h == 0 && toTime.m == 0)
				{
					fd.setHours(0);
					fd.setMinutes(0);
					td.setHours(0);
					td.setMinutes(0);

					from = BX.date.getServerTimestamp(fd.getTime());
					to = BX.date.getServerTimestamp(td.getTime());
				}
			}

			this.pFromTs.value = from;
			this.pToTs.value = to;
		},

		HandleEvent: function(oEvent)
		{
			if(oEvent)
			{
				oEvent.DT_FROM_TS = BX.date.getBrowserTimestamp(oEvent.DT_FROM_TS);
				oEvent.DT_TO_TS = BX.date.getBrowserTimestamp(oEvent.DT_TO_TS);

				if (oEvent.DT_FROM_TS > oEvent.DT_TO_TS)
					oEvent.DT_FROM_TS = oEvent.DT_TO_TS;

				if ((oEvent.RRULE && oEvent.RRULE.FREQ && oEvent.RRULE.FREQ != 'NONE'))
				{
					oEvent['~DT_FROM_TS'] = BX.date.getBrowserTimestamp(oEvent['~DT_FROM_TS']);
					oEvent['~DT_TO_TS'] = BX.date.getBrowserTimestamp(oEvent['~DT_TO_TS']);

					if (oEvent.RRULE && oEvent.RRULE.UNTIL)
						oEvent.RRULE.UNTIL = BX.date.getBrowserTimestamp(oEvent.RRULE.UNTIL);
				}
			}
			return oEvent;
		},

		ShowFormData: function(oEvent)
		{
			var bNew = false;
			if (!oEvent || !oEvent.ID)
			{
				bNew = true;
				oEvent = {};
			}

			// Name
			this.pEventName.value = oEvent.NAME || '';

			// From / To
			var fd, td;
			if (oEvent.DT_FROM_TS || oEvent.DT_TO_TS)
			{
				if (!(oEvent.RRULE && oEvent.RRULE.FREQ && oEvent.RRULE.FREQ != 'NONE'))
				{
					fd = bxGetDateFromTS(oEvent.DT_FROM_TS);
					td = bxGetDateFromTS(oEvent.DT_TO_TS);
				}
				else
				{
					fd = bxGetDateFromTS(oEvent['~DT_FROM_TS']),
					td = bxGetDateFromTS(oEvent['~DT_TO_TS']);
				}
			}
			else
			{
				fd = getUsableDateTime(new Date().getTime());
				td = getUsableDateTime(new Date().getTime() + 3600000 /* one hour*/);
			}

			if (fd)
			{
				this._FromDateValue = this.pFromDate.value = bxFormatDate(fd.date, fd.month, fd.year);
				this._FromTimeValue = this.pFromTime.value = fd.bTime ? formatTimeByNum(fd.hour, fd.min, this.bAMPM) : '';
			}
			else
			{
				this._FromDateValue = this._FromTimeValue = this.pFromDate.value = this.pFromTime.value = '';
			}

			if (td)
			{
				this.pToDate.value = bxFormatDate(td.date, td.month, td.year);
				this.pToTime.value = td.bTime ? formatTimeByNum(td.hour, td.min, this.bAMPM) : '';
			}
			else
			{
				this.pToDate.value = this.pToTime.value = '';
			}

			this.pFullDay.checked = oEvent.DT_SKIP_TIME == "Y";
			this.pFullDay.onclick();

			if (bNew)
			{
				this.pLocation.value = '';
				this.pImportance.value = 'normal';
				this.pAccessibility.value = 'busy';
				if (this.pSection.options && this.pSection.options.length > 0)
					this.pSection.value = this.pSection.options[0].value;

				this.pReminder.checked = !!this.defaultValues.remind;
				this.pRemCount.value = (this.defaultValues.remind && this.defaultValues.remind.count) || '15';
				this.pRemType.value = (this.defaultValues.remind && this.defaultValues.remind.type) || 'min';
			}
			else
			{
				this.pLocation.value = oEvent.LOCATION;
				this.pImportance.value = oEvent.IMPORTANCE;
				this.pAccessibility.value = oEvent.ACCESSIBILITY;
				this.pSection.value = oEvent.SECT_ID;


				// Remind
				this.pReminder.checked = oEvent.REMIND && oEvent.REMIND[0];
				this.pRemCount.value = oEvent.REMIND[0].count;
				this.pRemType.value = oEvent.REMIND[0].type;
			}
			this.pReminder.onclick();
		},

		SaveEvent: function()
		{

		},

		OnShow: function()
		{
			// TODO: It's hack. We have to hide it automaticaly
			if (BX('feed-add-post-content-message', true))
				BX('feed-add-post-content-message', true).style.display = 'none';

			BX.focus(this.pEventName);

			// Editor
			this.ShowTopPanel(false, !!this.config.showTopPanel);

			// Extra buttons in bottom panel
			// New Button
			BX('bx_b_file_' + this.id).onclick = BX.proxy(this.InsertFileButton, this);
			// Existent buttons moved from top panel in lhe
			this.__MoveButton('lhe_btn_createlink', BX('bx_b_link_' + this.id));
			this.__MoveButton('lhe_btn_inputvideocal', BX('bx_b_video_' + this.id));
		},

		GetLHE: function()
		{
			if (!this.oLHE)
				this.oLHE = window[this.config.LHEJsObjName];
			return this.oLHE;
		},

		ShowTopPanel: function(bSaveOption, value)
		{
			if (value != undefined && value == this.bPanelShowed)
				return;

			if (!this.pEditorPanelClose)
			{
				this.pEditorPanelClose = this.pEditorCont.appendChild(BX.create("DIV", {props: {className: 'feed-event-add-close-icon'}}));
				BX.bind(this.pEditorPanelClose, "click", BX.proxy(this.ShowTopPanel, this));
			}

			var oEditor = this.GetLHE();
			if (value == undefined)
				value = !this.bPanelShowed;

			if (value)
			{
				oEditor.buttonsHeight = 30;
				BX.removeClass(oEditor.pFrame, 'feed-cal-lhe-hidden-panel');
				this.pEditorPanelClose.style.display = "block";
			}
			else
			{
				oEditor.buttonsHeight = 0;
				BX.addClass(oEditor.pFrame, 'feed-cal-lhe-hidden-panel');
				this.pEditorPanelClose.style.display = "none";
			}
			this.bPanelShowed = value;
			//BX.userOptions.save('main.post.form', 'postEdit', 'showBBCode', 'Y');BX.addClass(this, 'feed-event-form-btn-active');
		},

		FullDay: function(bSaveOption, value)
		{
			if (value == undefined)
				value = !this.bFullDay;

			if (value)
				BX.removeClass(this.pFromToCont, 'feed-cal-full-day');
			else
				BX.addClass(this.pFromToCont, 'feed-cal-full-day');
			this.bFullDay = value;
		},

		Reminder: function(bSaveOption, value)
		{
			if (value == undefined)
				value = !this.bReminder;

			this.pReminderCont.className = value ? 'feed-event-reminder' : 'feed-event-reminder-collapsed';

			this.bReminder = value;
		},

		InsertFileButton: function()
		{
			this.InitWDFileController();
		},

		__MoveButton: function(oldButId, newCont)
		{
			var oEditor = this.GetLHE();
			var el = BX.findChild(oEditor.pButtonsCont, {'attr': {'id': oldButId}}, true, false);
			if (el)
			{
				BX.remove(BX.findParent(el), true);
				BX(newCont).appendChild(el);
				el.style.backgroundImage = 'url(/bitrix/images/1.gif)';
				el.src = '/bitrix/images/1.gif';
				el.style.width = '25px';
				el.style.height = '25px';
				el.onmouseout = '';
				el.onmouseover = '';
				el.className = '';
			}
		},

		InitWDFileController: function()
		{
			this.WDControllerNode = BX.findParent(BX.findChild(BX('editor-outer-cont-' + this.id), {'className': 'wduf-selectdialog'}, true, false));
			BX.addCustomEvent(this.WDControllerNode, 'WDLoadFormControllerInit', BX.proxy(this._OnWDFormInit, this));
			BX.addCustomEvent('WDSelectFileDialogLoaded', BX.delegate(this._OnWDFormInit, this));

			BX.onCustomEvent(this.WDControllerNode, "WDLoadFormController");

			BX.addCustomEvent(
				this.WDControllerNode,
				'OnFileUploadSuccess',
				BX.proxy(
					function(result, obj)
					{
						if (!!this.WDController && obj.id == this.WDController.id)
						{
							__WdUfCalendarGetinfofromnode(result, obj);
							this.OnFileUploadSuccess(result, obj);
						}
					},
					this
				)
			);
			BX.addCustomEvent(
				this.WDControllerNode,
				'OnFileUploadRemove',
				BX.proxy(
					function(result, obj){
						if (!!this.WDController && obj.id == this.WDController.id) {
							this.OnFileUploadRemove(result, obj, 'webdav');
						}
					},
					this
				)
			);

			//BX.onCustomEvent(this.WDControllerNode, "WDLoadFormController", [status]);
		},

		_OnWDFormInit: function(obj)
		{
			var WDControllerCID = this.config.WDControllerCID;
			if (!this.WDController &&
				(
					(WDControllerCID && obj.CID == WDControllerCID)
					||
					(!WDControllerCID && obj.dialogName == 'AttachFileDialog'))
				)
			{
				this.WDController = obj;
				this.OnWDSelectFileDialogLoaded(obj);
			}
		},

		OnFileUploadSuccess : function(result, obj)
		{
			var oEditor = this.GetLHE();
			oEditor.SaveContent();
			//BX('calendar_upload_cid' + this.id).value = obj.CID;

			this.arFiles.push(result.element_id);
			this.parser['postimage']['exist'] = (this.parser['postimage']['exist'] === null ?
				!!oEditor['oSpecialParsers']['postimage'] : this.parser['postimage']['exist']);
			this.parser['postfile']['exist'] = (this.parser['postfile']['exist'] === null ?
				!!oEditor['oSpecialParsers']['postfile'] : this.parser['postfile']['exist']);
			this.parser['postdocument']['exist'] = (this.parser['postdocument']['exist'] === null ?
				!!oEditor['oSpecialParsers']['postdocument'] : this.parser['postdocument']['exist']);

			result["isImage"] = (result.element_content_type && result.element_content_type.substr(0,6) == 'image/');
//			if (result.storage == 'bfile' && !(this.parser['postimage']['exist'] && result.isImage || this.parser['postfile']['exist']))
//				return false;
//			else if (result.storage == 'webdav' && !this.parser['postdocument']['exist'])
//				return false;

			var id = this.CheckFile(result.element_id, result, true);
			if (!!id)
			{
				var f = this.BindToFile(id);
				this.CheckFileInText(this.CheckFile(id));
				if ((!!oEditor.insertImageAfterUpload && f.isImage) || !!oEditor.insertFileAfterUpload)
					this.InsertFile(id);
			}
		},

		OnFileUploadRemove : function(result, obj, storage)
		{
			if (BX.findChild(BX(this.formID), {'attr': {id: 'wd-doc'+result}}, true, false))
				this.deleteFile(result, null, null, storage);
		},

		OnWDSelectFileDialogLoaded : function(wdFD)
		{
			if (!(typeof wdFD == "object" && !!wdFD && !!wdFD.values && !!wdFD.urlGet))
				return false;
			var needToReparse = false, id = 0, data = {}, node = null, arID = {}, preview = null, did = null;

			for (var ii = 0; ii < wdFD.values.length; ii++)
			{
				id = parseInt(wdFD.values[ii].getAttribute("id").replace("wd-doc", ""));
				if (!!arID['id' + id] )
					continue;
				arID['id' + id] = "Y";
				if (id > 0)
				{
					node = BX.findChild(wdFD.values[ii], {'className': 'f-wrap'}, true, false);
					if(!node)
						continue;
					data = {
						'element_id' : id,
						'element_name' : node.innerHTML,
						'parser' : 'postdocument',
						'storage' : 'webdav'
					};
					__WdUfCalendarGetinfofromnode(data, wdFD);
					did = this.CheckFile(id, data);
					if (did)
					{
						this.bindToFile(did);
						needToReparse = (needToReparse === false ? [] : needToReparse);
						needToReparse.push(id);
						wdFD.values[ii].setAttribute("mpfId", did);
						BX.addCustomEvent(
							wdFD.values[ii],
							'OnMkClose',
							BX.proxy(
								function()
								{
									this.CheckFileInText(
										this.CheckFile(BX.proxy_context.getAttribute("mpfId")),
										null,
										arguments[0]
									);
								},
								this
							)
						);
					}
				}
			}

			if (needToReparse !== false && oEditor && this.parser.postdocument.exist)
			{
				oEditor.SaveContent();
				var content = oEditor.GetContent();

				content = content.replace(new RegExp('\\&\\#91\\;DOCUMENT ID=(' + needToReparse.join("|") + ')([WIDTHHEIGHT=0-9 ]*)\\&\\#93\\;','gim'), '[DOCUMENT ID=$1$2]');
				oEditor.SetContent(content);
				oEditor.SetEditorContent(oEditor.content);
				oEditor.SetFocus();
				oEditor.AutoResize();
			}
		},


		BindToFile : function(id)
		{
			var f = this.CheckFile(id);
			if (!!f)
			{
				var intId = (typeof f.id == "string" ? parseInt(f.id.replace(this.sNewFilePostfix, "")) : f.id);
				if (f.isImage && f.storage == 'bfile')
				{

					var
						img = BX.findChild(BX('wd-doc'+intId), {'tagName': 'img'}, true, false),
						img_wrap = BX.findChild(BX('wd-doc'+intId), {'className': 'feed-add-img-wrap'}, true, false),
						img_title = BX.findChild(BX('wd-doc'+intId), {'className': 'feed-add-img-title'}, true, false);

					BX.bind(img_wrap, "click", BX.delegate(function(){this.InsertFile(id);}, this));
					BX.bind(img_title, "click", BX.delegate(function(){this.InsertFile(id);}, this));

					img_wrap.style.cursor = img_title.style.cursor = "pointer";
					img_wrap.title = img_title.title = BX.message('MPF_IMAGE');
				}
				else
				{
					var
						name_wrap = BX.findChild(BX('wd-doc'+intId), {'className': 'f-wrap'}, true, false),
						img_wrap = BX.findChild(BX('wd-doc'+intId), {'className': 'files-preview'}, true, false);
					if(!name_wrap)
						return false;
					BX.bind(name_wrap, "click", BX.delegate(function(){this.InsertFile(id);}, this));

					name_wrap.style.cursor = "pointer";
					name_wrap.title = BX.message('MPF_FILE');
					if (!!img_wrap)
						BX.bind(img_wrap, "click", BX.delegate(function(){this.InsertFile(id);}, this));
				}
			}
			return f;
		},

		StartMonitoring : function(start)
		{
			start = (start === false ? false : start === true ? true : "Y");
			if (start)
			{
				if (start === true || !this.startMonitoringStatus)
				{
					if (this.startMonitoringStatus)
						clearTimeout(this.startMonitoringStatus);
					this.startMonitoringStatus = setTimeout(BX.delegate(function() {this.CheckFilesInText();}, this), 1000);
				}
			}
			else if (this.startMonitoringStatus)
			{
				clearTimeout(this.startMonitoringStatus);
				this.startMonitoringStatus = null;
			}
		},

		CheckFilesInText: function()
		{
			var result = false;
			for (var id in this.arFiles)
			{
				if (this.CheckFileInText(this.arFiles[id]))
					result = true;
			}
			this.StartMonitoring(result);
		},

		CheckFileInText : function(file, reallyInText, parent)
		{
			if (!file)
				return null;
			parent = BX.findChild((!!parent ? parent : BX('wd-doc'+file["id"])), {'className': 'files-info'}, true, false);

			var oEditor = this.GetLHE();
			if (reallyInText !== true)
			{
				if (oEditor.sEditorMode == "code")
				{
//					var
//						text = oEditor.GetCodeEditorContent(),
//						text1 = text.replace(
//							this.parser[file["parser"]]["regexp"],
//							function(str, id, width, height)
//							{
//								if (file["id"] == id)
//									str = str.replace(id, "__" + id + "__");
//								return str;
//							}
//						);
//					reallyInText = (text != text1);
				}
				else if (oEditor.bxTags)
				{
					for (var ii in oEditor.bxTags)
					{
						if (!!oEditor.bxTags[ii] &&
							oEditor.bxTags[ii]["tag"] == file["parser"] &&
							oEditor.bxTags[ii]["params"]["value"] == file["id"])
						{
							if (oEditor.pEditorDocument.getElementById(oEditor.bxTags[ii]["id"]))
							{
								reallyInText = true;
								break;
							}
							else
							{
								oEditor.bxTags[ii] = null;
							}
						}
					}
				}
			}
			reallyInText = (reallyInText === true || reallyInText === false ? reallyInText : false);
			if (BX.type.isDomNode(parent))
			{
				var insertBtn = BX.findChild(parent, {'className': 'insert-btn'}, true, false),
					insertText = BX.findChild(parent, {'className': 'insert-text'}, true, false);
				if (reallyInText)
				{
					parent.setAttribute("tagInText", true);
					if (!insertText)
					{
						parent.appendChild(
							BX.create('SPAN', {
									'props' : {
										'className' : 'insert-text'
									},
									'html' : BX.message("MPF_FILE_IN_TEXT")
								}
							)
						);
					}
					if (!!insertBtn)
						insertBtn.parentNode.removeChild(insertBtn);
				}
				else
				{
					parent.setAttribute("tagInText", false);
					if (!insertBtn)
					{
						parent.appendChild(
							BX.create('SPAN', {
									'props' : {
										'className' : 'insert-btn'
									},
									'html' : BX.message("MPF_FILE_INSERT_IN_TEXT"),
									'events' : {
										'click' : BX.delegate(function(){this.InsertFile(file["~id"]);}, this)
									}
								}
							)
						);
					}
					if (!!insertText)
						insertText.parentNode.removeChild(insertText);
				}
			}
			if (reallyInText)
				this.StartMonitoring();
			return reallyInText;
		},

		CheckFile : function(id, result, isNew)
		{
			isNew = (!!isNew);
			if (typeof result == "object" && result != null)
			{
				bNew = true;
				id = parseInt(id);

				if (!result.element_content_type && !!result.element_name)
					result.element_content_type = (/(\.png|\.jpg|\.jpeg|\.gif|\.bmp)$/i.test(result.element_name) ? 'image/xyz' : 'isnotimage/xyz');

				if (isNew == true && (result.storage == 'bfile' || !result.storage))
					id = id + this.sNewFilePostfix;

				result.isImage = (!!result.isImage ? result.isImage : (result.element_content_type ? (result.element_content_type.indexOf('image') == 0) : false));

				if (result.isImage && result.storage == 'webdav' && !!this.arSize && !!result.element_url)
				{
					result.element_thumbnail = result.element_url + (result.element_url.indexOf("?") < 0 ? "?" : "&") +
						"width=" + this.arSize.width + "&height=" + this.arSize.height;
				}

				if (!result.element_thumbnail && !result.element_url && !!result.src)
					result.element_thumbnail = result.src;
				if (!result.element_image && !!result.thumbnail)
					result.element_image = result.thumbnail;

				var res = {
					id : id,
					name : (!!result.element_name ? result.element_name : 'noname'),
					size: result.element_size,
					url: result.element_url,
					parser: (!!result['parser'] ? result['parser'] : false),
					type: result.element_content_type,
					src: (!!result.element_thumbnail ? result.element_thumbnail : result.element_url),
					lowsrc: (!!result.lowsrc ? result.lowsrc : ''),
					thumbnail: result.element_image,
					isImage: result.isImage,
					storage: result.storage
				};

				if (res.isImage && parseInt(result.width) > 0 && parseInt(result.height) > 0)
				{
					res.width = parseInt(result.width);
					res.height = parseInt(result.height);
					if (!!this.arSize) {
						var
							width = res.width, height = res.height,
							ResizeCoeff = {
								width : (this.arSize["width"] > 0 ? this.arSize["width"] / width : 1),
								height : (this.arSize["height"] > 0 ? this.arSize["height"] / height : 1)
							},
							iResizeCoeff = Math.min(ResizeCoeff["width"], ResizeCoeff["height"]);

						iResizeCoeff = ((0 < iResizeCoeff) && (iResizeCoeff < 1) ? iResizeCoeff : 1);
						res.width = Math.max(1, parseInt(iResizeCoeff * res.width));
						res.height = Math.max(1, parseInt(iResizeCoeff * res.height));
					}
				}

				if (res['isImage'] && !res['src'])
				{
					res = false;
				}
				else if (!res['parser'] && res.storage == 'webdav' && this.parser['postdocument']['exist'])
				{
					res['parser'] = 'postdocument';
				}

				if (!!res && !!res["parser"])
				{
					if (res.storage == 'bfile')
					{
						this.arFiles['' + id] = res;
						this.arFiles['' + id]["~id"] = '' + id;
					}

					this.arFiles[res['parser'] + id] = res;
					this.arFiles[res['parser'] + id]["~id"] = res['parser'] + id;
					return (res['parser'] + id);
				}
			}
			return (typeof this.arFiles[id] == "object" && this.arFiles[id] != null ? this.arFiles[id] : false);
		},

		InsertFile : function(id, width)
		{
			var file = this.CheckFile(id);
			var oEditor = this.GetLHE();
			if (!oEditor || !file)
				return false;

			var
				fileID = file['id'],
				params = '',
				pattern = this.parser[file['parser']][oEditor.sEditorMode == 'html' ? "html" : "code"];

			if (file['isImage'])
			{
				pattern = (oEditor.sEditorMode == "html" ? this.parser["postimage"]["html"] : pattern);
				if (file.width > 0 && file.height > 0 && oEditor.sEditorMode == "html" )
					params = ' style="width:' + file.width + 'px;height:' + file.height + 'px;" onload="this.style=\' \'"';
			}

			if (oEditor.sEditorMode == 'code' && oEditor.bBBCode) // BB Codes
			{
				oEditor.WrapWith(" ", "", pattern.replace("\#ID\#", fileID).replace("\#ADDITIONAL\#", ""));
			}
			else if(oEditor.sEditorMode == 'html') // WYSIWYG
			{
				oEditor.InsertHTML(' ' + pattern.
					replace("\#ID\#", oEditor.SetBxTag(false, {'tag': file.parser, params: {'value' : fileID}})).
					replace("\#SRC\#", file.src).replace("\#URL\#", file.url).
					replace("\#LOWSRC\#", (!!file.lowsrc ? file.lowsrc : '')).
					replace("\#NAME\#", file.name).replace("\#ADDITIONAL\#", params)
				);
				setTimeout(BX.delegate(oEditor.AutoResize, oEditor), 500);
			}
			this.CheckFileInText(file, true);
		},

		DeleteFile: function(id, url, el, storage)
		{
			var oEditor = this.GetLHE();

			id  = id + '';
			storage = (storage != 'webdav' && storage != 'bfile' ? 'bfile' : storage);
			if (typeof url == "string")
			{
				BX.remove(el.parentNode);
				BX.ajax.get(url, function(data){});
			}
			oEditor.SaveContent();
			var content = oEditor.GetContent();

			if (storage == 'bfile')
			{
				content = content.
					replace(new RegExp('\\[IMG ID='+ id +'\\]','g'), '').
					replace(new RegExp('\\[FILE ID='+ id +'\\]','g'), '').
					replace(new RegExp('\\[IMG ID='+ id + this.sNewFilePostfix +'\\]','g'), '').
					replace(new RegExp('\\[FILE ID='+ id + this.sNewFilePostfix +'\\]','g'), '');
			}
			else
			{
				content = content.replace(new RegExp('\\[DOCUMENT ID='+ id +'\\]','g'), '');
			}

			oEditor.SetContent(content);
			oEditor.SetEditorContent(oEditor.content);
			oEditor.SetFocus();
			oEditor.AutoResize();
			this.arFiles[id] = false;
		},

		Parse : function(sName, sContent, pLEditor, parser)
		{
			this.parser[parser]['exist'] = true;
			var
				arParser = this.parser[parser],
				obj = this;

			if (!!arParser)
			{
				sContent = sContent.replace(
					arParser['regexp'],
					function(str, id, width, height)
					{
						var res = "", strAdditional = "",
							file = obj.CheckFile(arParser["tag"] + id),
							template = (file.isImage ? obj['parser']['postimage']['html'] : arParser.html);
						if (!!file)
						{
							if (file.isImage)
							{
								width = parseInt(width); height = parseInt(height);
								strAdditional = ((width && height && pLEditor.bBBParseImageSize) ?
									(" width=\"" + width + "\" height=\"" + height + "\"") : "");
								if (strAdditional == "" && file["width"] > 0 && file["height"] > 0)
									strAdditional = ' style="width:' + file["width"] + 'px;height:' + file["height"] + 'px;" onload="this.style=\' \'"';
							}

							return template.
								replace("\#ID\#", pLEditor.SetBxTag(false, {tag: arParser["tag"], params: {value : id}})).
								replace("\#NAME\#", file['name']).
								replace("\#SRC\#", file['src']).
								replace("\#ADDITIONAL\#", strAdditional).
								replace("\#WIDTH\#", parseInt(width)).
								replace("\#HEIGHT\#", parseInt(height));
						}
						return str;
					}
				)
			}
			return sContent;
		},

		Unparse: function(bxTag, pNode, pLEditor, parser)
		{
			this.parser[parser]['exist'] = true;
			if (bxTag.tag == parser)
			{
				var

					res = "",
					width = parseInt(pNode.arAttributes['width']),
					height = parseInt(pNode.arAttributes['height']),
					strSize = "";

				if (width && height  && pLEditor.bBBParseImageSize)
					strSize = ' WIDTH=' + width + ' HEIGHT=' + height;

				res = this.parser[parser]["code"].
					replace("\#ID\#", bxTag.params.value).
					replace("\#ADDITIONAL\#", strSize).
					replace("\#WIDTH\#", width).
					replace("\#HEIGHT\#", height);
			}
			return res;
		},

		OnEditorInit: function(pEditor)
		{
			var _this = this;

			// If panel hidden
			pEditor.buttonsHeight = 0;

			pEditor.AddParser(
				{
					name: 'postdocument',
					obj: {
						Parse: function(sName, sContent, pLEditor)
						{
							return _this.Parse(sName, sContent, pLEditor, "postdocument");
						},
						UnParse: function(bxTag, pNode, pLEditor)
						{
							return _this.Unparse(bxTag, pNode, pLEditor, "postdocument");
						}
					}
				}
			);
		},

		ShowAdditionalParams: function()
		{
			var value = !this.bAdditional;
			if (!this.pAdditionalCont)
				this.pAdditionalCont = BX("feed-cal-additional");

			if (value)
				BX.removeClass(this.pAdditionalCont, 'feed-event-additional-hidden');
			else
				BX.addClass(this.pAdditionalCont, 'feed-event-additional-hidden');

			this.bAdditional = value;
		},

		ParseTime: function(str)
		{
			var h, m, arTime;
			str = BX.util.trim(str);
			str = str.toLowerCase();

			if (this.bAMPM)
			{
				var ampm = 'pm';
				if (str.indexOf('am') != -1)
					ampm = 'am';

				str = str.replace(/[^\d:]/ig, '');
				arTime = str.split(':');
				h = parseInt(arTime[0] || 0, 10);
				m = parseInt(arTime[1] || 0, 10);

				if (h == 12)
				{
					if (ampm == 'am')
						h = 0;
					else
						h = 12;
				}
				else if (h != 0)
				{
					if (ampm == 'pm' && h < 12)
					{
						h += 12;
					}
				}
			}
			else
			{
				arTime = str.split(':');
				h = arTime[0] || 0;
				m = arTime[1] || 0;

				if (h.toString().length > 2)
					h = parseInt(h.toString().substr(0, 2));
				m = parseInt(m);
			}

			if (isNaN(h) || h > 24)
				h = 0;
			if (isNaN(m) || m > 60)
				m = 0;

			return {h: h, m: m};
		}
	};


	window.ModifyEditorForCalendar = function(editorId, params)
	{
		// Rename image button and change Icon
		LHEButtons['Image'].id = 'ImageLink';
		LHEButtons['Image'].src = '/bitrix/images/calendar/lhelink_image.gif';
		LHEButtons['Image'].name = params.imageLinkText;

		LHEButtons['InputVideoCal'] = {
			id : 'InputVideoCal',
			src : '/bitrix/images/1.gif',
			name : params.videoText,
			handler: function(pBut)
			{
				pBut.pLEditor.OpenDialog({id : 'InputVideoCal', obj: false});
			},
			OnBeforeCreate: function(pLEditor, pBut)
			{
				// Disable in non BBCode mode in html
				pBut.disableOnCodeView = !pLEditor.bBBCode || pLEditor.arConfig.bConvertContentFromBBCodes;
				return pBut;
			},
			parser: {
				name: 'postvideo',
				obj: {
					Parse: function(sName, sContent, pLEditor)
					{
						sContent = sContent.replace(/\[VIDEO\s*?width=(\d+)\s*?height=(\d+)\s*\]((?:\s|\S)*?)\[\/VIDEO\]/ig, function(str, w, h, src)
						{
							var
								w = parseInt(w) || 400,
								h = parseInt(h) || 300,
								src = BX.util.trim(src);

							return '<img id="' + pLEditor.SetBxTag(false, {tag: "postvideo", params: {value : src}}) +
								'" src="/bitrix/images/1.gif" class="bxed-video" width=' + w + ' height=' + h + ' title="' + BX.message.Video + ": " + src + '" />';
						});
						return sContent;
					},
					UnParse: function(bxTag, pNode, pLEditor)
					{
						if (bxTag.tag == 'postvideo')
						{
							return "[VIDEO WIDTH=" + pNode.arAttributes["width"] + " HEIGHT=" + pNode.arAttributes["height"] + "]" + bxTag.params.value + "[/VIDEO]";
						}
						return "";
					}
				}
			}
		};

		window.LHEDailogs['InputVideoCal'] = function(pObj)
		{
			var str = '<table width="100%"><tr>' +
				'<td class="lhe-dialog-label lhe-label-imp"><label for="' + pObj.pLEditor.id + 'lhed_post_video_path"><b>' + params.videoUploadText + ':</b></label></td>' +
				'<td class="lhe-dialog-param">' +
				'<input id="' + pObj.pLEditor.id + 'lhed_post_video_path" value="" size="30"/>' +
				'</td>' +
				'</tr><tr>' +
				'<td></td>' +
				'<td style="padding: 0!important; font-size: 11px!important;">' + params.videoUploadText1 + '</td>' +
				'</tr><tr>' +
				'<td class="lhe-dialog-label lhe-label-imp"><label for="' + pObj.pLEditor.id + 'lhed_post_video_width">' + params.videoUploadText3 + ':</label></td>' +
				'<td class="lhe-dialog-param">' +
				'<input id="' + pObj.pLEditor.id + 'lhed_post_video_width" value="" size="4"/>' +
				' x ' +
				'<input id="' + pObj.pLEditor.id + 'lhed_post_video_height" value="" size="4" />' +
				'</td>' +
				'</tr></table>';

			return {
				title: params.videoUploadText2,
				innerHTML : str,
				width: 480,
				OnLoad: function()
				{
					pObj.pPath = BX(pObj.pLEditor.id + "lhed_post_video_path");
					pObj.pWidth = BX(pObj.pLEditor.id + "lhed_post_video_width");
					pObj.pHeight = BX(pObj.pLEditor.id + "lhed_post_video_height");

					pObj.pLEditor.focus(pObj.pPath);
				},
				OnSave: function()
				{
					var
						src = BX.util.trim(pObj.pPath.value),
						w = parseInt(pObj.pWidth.value) || 400,
						h = parseInt(pObj.pHeight.value) || 300;

					if (src == "")
						return;

					if (pObj.pLEditor.sEditorMode == 'code' && pObj.pLEditor.bBBCode) // BB Codes
					{
						pObj.pLEditor.WrapWith("", "", "[VIDEO WIDTH=" + w + " HEIGHT=" + h + "]" + src + "[/VIDEO]");
					}
					else if(pObj.pLEditor.sEditorMode == 'html') // WYSIWYG
					{
						pObj.pLEditor.InsertHTML('<img id="' + pObj.pLEditor.SetBxTag(false, {tag: "postvideo", params: {value : src}}) + '" src="/bitrix/images/1.gif" class="bxed-video" width=' + w + ' height=' + h + ' title="' + BX.message.Video + ": " + src + '" />');
						pObj.pLEditor.AutoResize();
					}
				}
			};
		};
	};

	var lastWaitElement = null;

	window.__WdUfCalendarGetinfofromnode = function(result, obj)
	{
		var preview = BX.findChild(BX('wd-doc' + result.element_id), {'className': 'files-preview', 'tagName' : 'IMG'}, true, false);
		if (!!preview)
		{
			result.lowsrc = preview.src;
			result.element_url = preview.src.replace(/\Wwidth\=(\d+)/, '').replace(/\Wheight\=(\d+)/, '');
			result.width = parseInt(preview.getAttribute("data-bx-full-width"));
			result.height = parseInt(preview.getAttribute("data-bx-full-height"));
		}
		else if (!!obj.urlGet)
		{
			result.element_url = obj.urlGet.
				replace("#element_id#", result.element_id).
				replace("#ELEMENT_ID#", result.element_id).
				replace("#element_name#", result.element_name).
				replace("#ELEMENT_NAME#", result.element_name);
		}
	}

	// Calbacks for destination
	window.BXEvDestSetLinkName = function(name)
	{
		if (BX.SocNetLogDestination.getSelectedCount(name) <= 0)
			BX('feed-event-dest-add-link').innerHTML = BX.message("BX_FPD_LINK_1");
		else
			BX('feed-event-dest-add-link').innerHTML = BX.message("BX_FPD_LINK_2");
	}

	window.BXEvDestSelectCallback = function(item, type, search)
	{
		var type1 = type;
		prefix = 'S';
		if (type == 'sonetgroups')
			prefix = 'SG';
		else if (type == 'groups')
		{
			prefix = 'UA';
			type1 = 'all-users';
		}
		else if (type == 'users')
			prefix = 'U';
		else if (type == 'department')
			prefix = 'DR';

		BX('feed-event-dest-item').appendChild(
			BX.create("span", { attrs : { 'data-id' : item.id }, props : { className : "feed-event-destination feed-event-destination-"+type1 }, children: [
				BX.create("input", { attrs : { 'type' : 'hidden', 'name' : 'EVENT_PERM['+prefix+'][]', 'value' : item.id }}),
				BX.create("span", { props : { 'className' : "feed-event-destination-text" }, html : item.name}),
				BX.create("span", { props : { 'className' : "feed-event-del-but"}, events : {'click' : function(e){BX.SocNetLogDestination.deleteItem(item.id, type, destinationFormName);BX.PreventDefault(e)}, 'mouseover' : function(){BX.addClass(this.parentNode, 'feed-event-destination-hover')}, 'mouseout' : function(){BX.removeClass(this.parentNode, 'feed-event-destination-hover')}}})
			]})
		);

		BX('feed-event-dest-input').value = '';
		BXEvDestSetLinkName(destinationFormName);
	}

	// remove block
	window.BXEvDestUnSelectCallback = function(item, type, search)
	{
		var elements = BX.findChildren(BX('feed-event-dest-item'), {attribute: {'data-id': ''+item.id+''}}, true);
		if (elements != null)
		{
			for (var j = 0; j < elements.length; j++)
				BX.remove(elements[j]);
		}
		BX('feed-event-dest-input').value = '';
		BXEvDestSetLinkName(destinationFormName);
	}
	window.BXEvDestOpenDialogCallback = function()
	{
		BX.style(BX('feed-event-dest-input-box'), 'display', 'inline-block');
		BX.style(BX('feed-event-dest-add-link'), 'display', 'none');
		BX.focus(BX('feed-event-dest-input'));
	}

	window.BXEvDestCloseDialogCallback = function()
	{
		if (!BX.SocNetLogDestination.isOpenSearch() && BX('feed-event-dest-input').value.length <= 0)
		{
			BX.style(BX('feed-event-dest-input-box'), 'display', 'none');
			BX.style(BX('feed-event-dest-add-link'), 'display', 'inline-block');
			BXEvDestDisableBackspace();
		}
	}

	window.BXEvDestCloseSearchCallback = function()
	{
		if (!BX.SocNetLogDestination.isOpenSearch() && BX('feed-event-dest-input').value.length > 0)
		{
			BX.style(BX('feed-event-dest-input-box'), 'display', 'none');
			BX.style(BX('feed-event-dest-add-link'), 'display', 'inline-block');
			BX('feed-event-dest-input').value = '';
			BXEvDestDisableBackspace();
		}

	}
	window.BXEvDestDisableBackspace = function(event)
	{
		if (BX.SocNetLogDestination.backspaceDisable || BX.SocNetLogDestination.backspaceDisable != null)
			BX.unbind(window, 'keydown', BX.SocNetLogDestination.backspaceDisable);

		BX.bind(window, 'keydown', BX.SocNetLogDestination.backspaceDisable = function(event){
			if (event.keyCode == 8)
			{
				BX.PreventDefault(event);
				return false;
			}
		});
		setTimeout(function(){
			BX.unbind(window, 'keydown', BX.SocNetLogDestination.backspaceDisable);
			BX.SocNetLogDestination.backspaceDisable = null;
		}, 5000);
	}

	window.BXEvDestSearchBefore = function(event)
	{
		if (event.keyCode == 8 && BX('feed-event-dest-input').value.length <= 0)
		{
			BX.SocNetLogDestination.sendEvent = false;
			BX.SocNetLogDestination.deleteLastItem(destinationFormName);
		}

		return true;
	}
	window.BXEvDestSearch = function(event)
	{
		if (event.keyCode == 16 || event.keyCode == 17 || event.keyCode == 18 || event.keyCode == 20 || event.keyCode == 244 || event.keyCode == 224 || event.keyCode == 91)
			return false;

		if (event.keyCode == 13)
		{
			BX.SocNetLogDestination.selectFirstSearchItem(destinationFormName);
			return true;
		}
		if (event.keyCode == 27)
		{
			BX('feed-event-dest-input').value = '';
			BX.style(BX('feed-event-dest-add-link'), 'display', 'inline');
		}
		else
		{
			BX.SocNetLogDestination.search(BX('feed-event-dest-input').value, true, destinationFormName);
		}

		if (!BX.SocNetLogDestination.isOpenDialog() && BX('feed-event-dest-input').value.length <= 0)
		{
			BX.SocNetLogDestination.openDialog(destinationFormName);
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
	}

	function bxFormatDate(d, m, y)
	{
		var str = BX.message.FORMAT_DATE;

		str = str.replace(/YY(YY)?/ig, y);
		str = str.replace(/MMMM/ig, BX.message('MONTH_' + this.Number(m)));
		str = str.replace(/MM/ig, zeroInt(m));
		str = str.replace(/M/ig, BX.message('MON_' + this.Number(m)));
		str = str.replace(/DD/ig, zeroInt(d));

		return str;
	}

	function zeroInt(x)
	{
		x = parseInt(x, 10);
		if (isNaN(x))
			x = 0;
		return x < 10 ? '0' + x.toString() : x.toString();
	}

	function bxGetDateFromTS(ts, getObject)
	{
		var oDate = new Date(ts);
		if (!getObject)
		{
			var
				ho = oDate.getHours() || 0,
				mi = oDate.getMinutes() || 0;

			oDate = {
				date: oDate.getDate(),
				month: oDate.getMonth() + 1,
				year: oDate.getFullYear(),
				bTime: !!(ho || mi),
				oDate: oDate
			};

			if (oDate.bTime)
			{
				oDate.hour = ho;
				oDate.min = mi;
			}
		}

		return oDate;
	}

	function getUsableDateTime(timestamp, roundMin)
	{
		var date = bxGetDateFromTS(timestamp);
		if (!roundMin)
			roundMin = 10;

		date.min = Math.ceil(date.min / roundMin) * roundMin;

		if (date.min == 60)
		{
			if (date.hour == 23)
				date.bTime = false;
			else
				date.hour++;
			date.min = 0;
		}

		date.oDate.setHours(date.hour);
		date.oDate.setMinutes(date.min);
		return date;
	}

	function formatTimeByNum(h, m, bAMPM)
	{
		var res = '';
		if (m == undefined)
			m = '00';
		else
		{
			m = parseInt(m, 10);
			if (isNaN(m))
				m = '00';
			else
			{
				if (m > 59)
					m = 59;
				m = (m < 10) ? '0' + m.toString() : m.toString();
			}
		}

		h = parseInt(h, 10);
		if (h > 24)
			h = 24;
		if (isNaN(h))
			h = 0;

		if (bAMPM)
		{
			var ampm = 'am';

			if (h == 0)
			{
				h = 12;
			}
			else if (h == 12)
			{
				ampm = 'pm';
			}
			else if (h > 12)
			{
				ampm = 'pm';
				h -= 12;
			}

			res = h.toString() + ':' + m.toString() + ' ' + ampm;
		}
		else
		{
			res = ((h < 10) ? '0' : '') + h.toString() + ':' + m.toString();
		}
		return res;
	}

})(window);


