// calendar localizations
$.datepicker.regional['ua'] = {
	closeText: 'Закрити',
	prevText: '<Попер',
	nextText: 'Наст>',
	currentText: 'Сьогодні',
	monthNames: ['Січень','Лютий','Березень','Квітень','Травень','Червень','Липень','Серпень','Вересень','Жовтень','Листопад','Грудень'],
	monthNamesShort: ['Січ','Лют','Бер','Кві','Тра','Чер','Лип','Сер','Вер','Жов','Лис','Гру'],
	dayNames: ['неділя','понеділок','вівторок','середа','четвер','п\'ятница','субота'],
	dayNamesShort: ['нед','пон','втр','срд','чтв','птн','сбт'],
	dayNamesMin: ['Нд','Пн','Вт','Ср','Чт','Пт','Сб'],
	weekHeader: 'Тд',
	firstDay: 1,
	isRTL: false,
	dateFormat: 'yy-mm-dd',
	showMonthAfterYear: false,
	yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['ua']);

$.timepicker.regional['ua'] = {
	timeOnlyTitle: 'Виберіть час',
	timeText: 'Час',
	hourText: 'Години',
	minuteText: 'Хвилини',
	secondText: 'Секунди',
	millisecText: 'Мілісекунди',
	timezoneText: 'Часовий пояс',
	currentText: 'Зараз',
	closeText: 'Закрити',
	timeFormat: 'HH:mm',
	isRTL: false
};
$.timepicker.setDefaults($.timepicker.regional['ua']);

// application
var App = App || {};
$.extend(App, {config:{}});

var initElements = function() {
	var $this = $(this);
	var data = $(this).data();

	if(typeof App.elements[data.element] == 'function' && !data.initialized) {
		App.elements[data.element]($this, data);
		data.initialized = true;
	}
};


App.init = function(options) {
    $.extend(this.config, options);
    
    // Init elements
    $('[data-element]').each(initElements);
    
    // Init actions
    $(document).on('click', function(event) {
        var $_this = $(event.target);
		var $this = $_this.closest('[data-action]');
        var data = $this.length ? $this.data() : {};
        
        if(data.action) {
	        var actions = data.action.toString().split(',');
	        if(actions.length) {
	        	for(var i=0; i<actions.length; i++) {
	        		if(actions[i] && typeof App.actions[actions[i]] == 'function') {
						App.actions[actions[i]]($this, data);
						event.stopPropagation();
						$('[data-element]').each(initElements);
					}	
	        	}
	        }
        }
        
		/*if( data.action && typeof App.actions[ data.action ] == 'function' ) {
			App.actions[ data.action ]($this, data);
			event.stopPropagation();
			$('[data-element]').each(initElements);
		}*/
    });
    
    // Init scroll
    $('.nano').nanoScroller();
};

App.log = function(v) {
	return 'console' in window ? Function.prototype.apply.call(console.log, console, arguments) : null;
};

App.dialog = {
	isVisible: false,
    open: function(content) {
    	if(!this.isVisible && content && content.toString().length) {
    		this.isVisible = true;
    		
	    	// hide embed objects
	    	$('object, embed, iframe').css('visibility', 'hidden');
	    	
	    	// create popup
	    	if(!$('#popup').length) {
	    		$('body').append('<div id="shadow"></div>');
	    		$('body').append('<div id="popup"><div class="point"><span title="Закрити" data-action="dialogClose" class="abs icon close"></span></div><div class="content"></div></div>');
	    	}
	    	
	    	// refresh content
	    	this.refresh(content);
    	}
    },
    
    loading: function(message) {
    	this.open(message && message.toString().length ? '<div class="loading msg">' + message + '</div>' : '<div class="loading"></div>');
    },
    
    refresh: function(content) {
    	// show shadow
    	$('#shadow').css({
        	'top': 0,
			'left': 0,
        	'width': Math.max(document.body.scrollWidth, /*document.documentElement.scrollWidth,*/ document.body.offsetWidth, /*document.documentElement.offsetWidth,*/ document.body.clientWidth, document.documentElement.clientWidth) + 'px', 
        	'height': Math.max(document.body.scrollHeight, /*document.documentElement.scrollHeight,*/ document.body.offsetHeight, /*document.documentElement.offsetHeight,*/ document.body.clientHeight, document.documentElement.clientHeight) + 'px'
        }).show();
    	
    	// add content
    	$('#popup .content').html(content);

    	// show popup
        var left = ((parseInt($(window).width() / 2) + $(window).scrollLeft()) - $('#popup').width() / 2);
        var top = ((parseInt($(window).height() / 2) + $(window).scrollTop()) - $('#popup').height() / 2);
        $('#popup').css({
            'left': left < 30 ? 30 : left + 'px',
            'top': top < 30 ? 30 : top + 'px'
        }).show();
        
        // bind resize window
        var obj = this;
        $(window).unbind('resize.Dialog').bind('resize.Dialog', function(){ obj.refresh(); });
    },
    
    close: function() {
    	if(this.isVisible) {
    		this.isVisible = false;
    		
	    	// clear popup
	    	$('#popup .content').html('');
	    	
	    	// hide popup
	        $('#popup').hide();
	        
	        // hide shadow
	        $('#shadow').hide();
	        
	        // show embed objects
	        $('object, embed, iframe').css('visibility', 'visible');
	        
	        // unbind resize window
	       	$(window).unbind('resize.Dialog');
    	}
    }
};

App.ajax = {
    request: function( options ) {
        options = $.extend({}, {type: 'post', dataType:'json', data:{}}, options);
        options.url = App.config.appDir ? "/" + App.config.appDir + (options.url ? options.url : "/") : ( options.url ? options.url : "/");
        
        var ajax = $.ajax(options);
        ajax.always(function(response) {
            if(!response.success || typeof response.success == 'function') {
                if(typeof options.onError == 'function') {
					options.onError(response); 
                }else{
					if (response.message) 
						App.log(response.message);
					else
						App.log('Unknown error. "success" is false or empty');
				}
            }else{
                (typeof options.onDone == 'function') && options.onDone(response);
            }
			
			if(typeof options.always == 'function') {
				options.always(response);
			}
            
			
			$('[data-element]').each(initElements);
            
        });
    }
};

App.conference = {
	url: 'rtmp://nfbmedia.com/vod',
	
	start: function(type, id) {
		var videoId = 'video' + id;
		$('#' + videoId).show();
		this.createSwfObject(videoId, {src: this.url + '?' + type + '=' + id});
		
		// connect
		
	},
	
	stop: function(id) {
		var videoId = 'video' + id;
		var videoObject = this.getSwfObject(videoId);
		if(videoObject) {
			videoObject.setProperty('src', '');
//			videoObject.setProperty('live', true);
			$(videoObject).remove();
		}
	},
	
	listen: function(id) {
		setTimeout(function() {
			App.ajax.request({
				url: '/' + App.config.appController + '/getmultimediastatus/' + id,
				data: {},
				onDone: function(response) {
					if(response.status == 0) {
						App.conference.listen(id);
					}
					
					if(response.status == 1) {
						App.conference.start('play', id);
					}
				}
			});
		}, 1000);
	},
	
	createSwfObject: function(videoId, vars) {
		var flashParams = {
			allowFullScreen: true, 
			wmode: 'window'
		};
		
		var flashVars = {
			cameraQuality: 90, 
			enableFullscreen: true, 
			controls: true
		};
        
        swfobject.embedSWF('/swf/VideoIO.swf', videoId, $('#' + videoId).width(), $('#' + videoId).height(), '9.0.28', 'expressInstall.swf', $.extend(flashVars, vars), flashParams, {id: videoId});
	},
	
	getSwfObject: function(movieName) {
    	var isIE = navigator.appName.indexOf("Microsoft") != -1;
    	return (isIE) ? window[movieName] : document[movieName];
	}
};

App.form = {
    defaultError: 'Default Error',
    lastValidationError: false,
    lastValidationErrorElement: null,
    
    validate: function($form) {
        // Main validation function ...
        var hasErrors = function($element) {
            // Return error or FALSE on success
            var data  = $element.data();
            var validators = data.validate.split(',');
            var value = $element.val();
            
            for( var i = 0; i < validators.length; i++ ) {
                switch( validators[i] ) {
                    case 'minlength':if( value.length < data.minlength*1 )
                                        {return App.form.lastValidationError = data[ validators[i] + '_error'] || 'Error';}
                        break;              
                    case 'checked'  :if( !$element.get(0).checked )
                                        {return App.form.lastValidationError = data[ validators[i] + '_error'] || 'Error';}
                        break;
                    case 'empty'    :if( !value )
                                        {return App.form.lastValidationError = data[ validators[i] + '_error'] || 'Error';}
                        break;
                    case 'email'    :if (!/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value))
                                        {return App.form.lastValidationError = data[ validators[i] + '_error'] || 'Error';}
                        break;
                    case 'match'    :var matchvalue = $(data.matchAgainstSelector).val();
                                        if( value != matchvalue )
                                        {return App.form.lastValidationError = data[ validators[i] + '_error'] || 'Error';}
                        break;
                }
            }
            return false;
        }
        
        this.lastValidationError        = false;
        this.lastValidationGroupError   = false;
        var elementsToValidate          = $form.is('[data-validate]') ? $form : $form.find('[data-validate]'); // consider [data-groupvalidate]
        var i,j,k;
        var error = false;
        var groupValid = false;
        var groupContainer = null;
        var groupElements = [];
        var $elementToValidate = null;
        
        elementsToValidate.sort(function(a,b) {return $(a).attr('tabindex')*1 - $(b).attr('tabindex')*1});
        
        for( i=0; i<elementsToValidate.length; i++) {
            
            $elementToValidate = $(elementsToValidate[i]);
			
            // Single element validation here
            error = hasErrors($elementToValidate);
            if( error ) {
                this.lastValidationError        = error;
                this.lastValidationErrorElement = $elementToValidate;
                return false;
            }
        }
        
        return true;
    }
};

/************************************************************
 *                                                          *
 *                  ADMIN LOGIC GOES BELOW                  *
 *                                                          *
 ************************************************************/

App.actions = {
	dialogClose: function($this, data) {
		App.dialog.close();
	},
	
	redirect: function($this, data) {
		if(data.url)
			document.location = data.url;
		else
			alert('Url is missing...');
	},
	
	preloadContent: function($this, data) {
		var $element = $(data.loadSelector);
		var params = data.loadParams ? eval('(' + data.loadParams + ')') : {};
		
		if(data.loadType && $.trim($element.html()) == '') {
			App.ajax.request({
				url: '/' + App.config.appController + '/loadcontent',
				data: {type: data.loadType, params: params || {}},
				onDone: function(response) {
					if(response.content) {
						$element.html(response.content);
					}
				}
			});
		}
	},
	
	formSubmit: function($this, data) {
		var $form = data.formId ? $('#'+data.formId) : $this.closest('form');
		var $error = $form.find('.error');
		
		if( $form.length ) {
			$form.submit(function(e) {
				e.preventDefault();
			});
			
			$error.html('').hide();
			
			App.ajax.request({
				url: $form.attr('action'),
				data: $form.serialize(),
				onDone: function(response) {
					if( data.successRedirect  ) {
						document.location = data.successRedirect;
					}else{
						alert(data.success || response.message);
					}
				},
				onError: function(response) {
					if(response.errors) {
						for(var er in response.errors) {
							$error.append(response.errors[er] + '<br />');
						}
						$error.show();
					}
				}
			});
		}
	},
	
	formUserSubmit: function($this, data) {
		var $form = $this.closest('form');
		var $error = $form.find('.error');
		
		if($form.length) {
			$form.submit(function(e) {
				e.preventDefault();
			});
			
			$error.html('').hide();
			
			App.ajax.request({
				url: $form.attr('action'),
				data: $form.serialize(),
				onDone: function(response) {
					if(response.addUserContent) {
						$('#formUser').html(response.addUserContent);
					}
					
					if(response.listUsersDataContent) {
						$('#listUsersData').html(response.listUsersDataContent);
					}
					
//					alert('Дані успішно додано');
					App.dialog.open($('#addUserSuccess').html());
				},
				onError: function(response) {
					if(response.errors) {
						for(var er in response.errors) {
							$error.append(response.errors[er] + '<br />');
						}
						$error.show();
					}
				}
			});
		}
	},
	
	formUserGroupSubmit: function($this, data) {
		var $form = $this.closest('form');
		var $error = $form.find('.error');
		
		if($form.length) {
			$form.submit(function(e) {
				e.preventDefault();
			});
			
			$error.html('').hide();
			
			App.ajax.request({
				url: $form.attr('action'),
				data: $form.serialize(),
				onDone: function(response) {
					if(response.addUserGroupContent) {
						$('#formUserGroup').html(response.addUserGroupContent);
					}
					
					if(response.listUserGroupsContent) {
						$('#listUserGroups').html(response.listUserGroupsContent);
					}
					
//					alert('Дані успішно додано');
					App.dialog.open($('#addUserGroupSuccess').html());
				},
				onError: function(response) {
					if(response.errors) {
						for(var er in response.errors) {
							$error.append(response.errors[er] + '<br />');
						}
						$error.show();
					}
				}
			});
		}
	},
	
	formMessageSubmit: function($this, data) {
		var $form = $this.closest('form');
		var $error = $form.find('.error');
		
		if($form.length) {
			$form.submit(function(e) {
				e.preventDefault();
			});
			
			$error.html('').hide();
			
			App.ajax.request({
				url: $form.attr('action'),
				data: $form.serialize(),
				onDone: function(response) {
					if(response.addMessageContent) {
						$('#formMessage').html(response.addMessageContent);
					}
					
					if(response.listMessagesDataContent) {
						$('#listMessagesData').html(response.listMessagesDataContent);
					}
					
					if($('#sendMessageSuccess').length) {
						App.dialog.open($('#sendMessageSuccess').html());
					}

					var recepient_user_id = $form.find('[name="data[recepient_user_id]"]').val();
					if(recepient_user_id) {
						if($('#sendMessageSuccess2').length) {
							App.dialog.open($('#sendMessageSuccess2').html());
						}
						
						$('#formMessage' + recepient_user_id).hide();
						$('#sendMessage' + recepient_user_id).show();
					}
//					alert('Листа успішно відправлено');
				},
				onError: function(response) {
					if(response.errors) {
						for(var er in response.errors) {
							$error.append(response.errors[er] + '<br />');
						}
						$error.show();
					}
				}
			});
		}
	},
	
	formConferenceSubmit: function($this, data) {
		var $form = $this.closest('form');
		var $error = $form.find('.error');
		
		if($form.length) {
			$form.submit(function(e) {
				e.preventDefault();
			});
			
			$error.html('').hide();
			
			App.ajax.request({
				url: $form.attr('action'),
				data: $form.serialize(),
				onDone: function(response) {
					if(data.successRedirect) {
						document.location = data.successRedirect;
					}
				},
				onError: function(response) {
					if(response.errors) {
						for(var er in response.errors) {
							$error.append(response.errors[er] + '<br />');
						}
						$error.show();
					}
				}
			});
		}
	},
	
	showHider: function($this, data) {
		if(data.hideElement) {
			$(data.hideElement).hide();
			$(data.hideElement).find('input, select, textarea, checkbox').attr('disabled', true);
		}
		
		if(data.showElement) {
			$(data.showElement).show();
			$(data.showElement).find('input, select, textarea, checkbox').removeAttr('disabled');
		}
	},
	
	viewMessage: function($this, data) {
		var $item = $this.closest('.item');
		var $message = $item.find('.message').show();
		
		var height = $message.height();
		$message.css('height', 0).stop().animate({height: height}, 300, function(){ $item.addClass('sel'); $this.hide(); });
		
		if(data.messageId) {
			App.ajax.request({
				url: '/' + App.config.appController + '/viewmessage',
				data: {id: data.messageId},
				onDone: function(response) {
					if(response.countNewMessages) {
						$('#countNewMessages').html(response.countNewMessages > 0 ? '(' + response.countNewMessages + ')' : '');
					}
				}
			});
		}
	},
	
	deleteEntity: function($this, data) {
		if(confirm(data.confirm)) {
			App.ajax.request({
				url: data.actionUrl, 
				onDone: function(response) {
					if(data.successRedirect) {
						document.location = data.successRedirect;
					}
					else{
						response.message && alert(response.message);
					}
				}
			});
		}
	},
	
	deleteUserGroup: function($this, data) {
		if(confirm(data.confirm)) {
			App.ajax.request({
				url: '/' + App.config.appController + '/deleteusergroup',
				data: {id: data.userGroupId},
				onDone: function(response) {
					if(response.listUserGroupsContent) { 
						$('#listUserGroups').html(response.listUserGroupsContent);
					}
				}
			});
		}
	},
	
	deleteMessage: function($this, data) {
		var $item = $this.closest('.item');
		var tabIndex = $this.closest('.list').attr('data-content');
		
//		App.log(tabIndex);
		
		if(data.messageId) {
			App.ajax.request({
				url: '/' + App.config.appController + '/deletemessage',
				data: {id: data.messageId, sentbox: data.sentbox || null, tab_index: tabIndex},
				onDone: function(response) {
					if(response.listMessagesDataContent) {
						$('#listMessagesData').html(response.listMessagesDataContent);
					}
				}
			});
		}
	},
	
	removeUploadFileItem: function($this, data) {
		$this.closest('.upload').stop().animate({'height': 0, 'width': 0}, 800, function(){ $(this).remove(); });
	},
	
	conferenceOn: function($this, data) {
		$this.hide();
		$('#conferenceOff').show();
		
		App.ajax.request({
			url: '/' + App.config.appController + '/updatemultimediastatus/' + data.conferenceId + '/1',
			data: {},
			onDone: function(response) {
				App.conference.start('publish', data.conferenceId);
			}
		});
	},
	
	conferenceOff: function($this, data) {
		$this.hide();
//		$('#conferenceOn').show();
		
		App.ajax.request({
			url: '/' + App.config.appController + '/updatemultimediastatus/' + data.conferenceId + '/2',
			data: {},
			onDone: function(response) {
				App.conference.stop(data.conferenceId);
				$('#player .center').html('Конференцію завершено');	
			}
		});
	}
};

App.elements = {
	img: function($this, data) {
		var src = $this.attr('src');
		var i = new Image(); 
		var e = function() { 
			$this.attr('src', data.defaultSrc); 
		}
		if(src){
			i.onerror = e;
			i.src = src;
		}
		else {
			e();
		}
	},
	
	autoselect: function($this, data) {
		$this.val( $this.attr('data-value') ); 
	},
	
	input: function($this, data) {
		$this.on('keydown keypress', function(e) {
			switch(data.type) {
				case 'number':var ch = String.fromCharCode(e.which);
								if( !(/[\d]/.test(ch)) 
									&& e.which != 0
									&& e.which != 8 
									&& e.which != 46
									&& e.which != 37
									&& e.which != 39
								) {
									e.preventDefault();
									return false;
								}
					break;
			}
		});
	},
	
	datepicker: function($this, data) {
		$this.datetimepicker();
	},
	
	tooltip: function($this, data) {
		if(!$('#tooltip').length) {
			$('body').append($('<div id="tooltip" />'))
		}
	
		var $tooltip = $('#tooltip').hide();
		
		$this.hover(function() {
			// abs position
			var position = $this.offset();
			
			if(data.text.length) {
				$tooltip.css({top: position.top - $this.height() - 5, left: position.left + $this.width() + 5}).html(data.text).show();
			}
		}, function() {
			$tooltip.html('').hide();
		});
	},
	
	tabulator: function($this, data) {
		var $tabs = $this.find('[data-tab]');
//		var classSelected = data.classSelected;
		
		$this.find('[data-content]').hide().filter('[data-content="' + data.selected + '"]').show();
		
		$tabs.css('opacity', 0.4).removeClass(data.classSelected ? data.classSelected : '').filter('[data-tab="'+data.selected+'"]').css('opacity', 1).addClass(data.classSelected ? data.classSelected : '');
		$tabs.on('click', function() {
			$this.find('[data-content]').hide().filter('[data-content="'+ $(this).data('tab') +'"]').show();
			$tabs.css('opacity', 0.4).removeClass(data.classSelected ? data.classSelected : '').filter(this).css('opacity', 1).addClass(data.classSelected ? data.classSelected : '');
		});
	},
	
	wizard: function($this, data) {
		var $steps = $this.find('.menu [data-step]');
		
		var $slider = $this.find('.slider');
		var $sliderContent = $slider.find('.content');
		var sliderWidth = $slider.width();
		var sliderHeight = $slider.height();
		
		var $slides = $slider.find('[data-slide]');
		var slidesCount = $slides.length;
		
		var $steps2 = $slides.find('[data-step]');
		
		// default settings
		$sliderContent.css({'width': sliderWidth*slidesCount, 'height': sliderHeight});
		$slides.css({'width': sliderWidth, 'height': sliderHeight});
		
		// change step
		function changeStep(step) {
			$steps.css('opacity', 0.4).removeClass(data.classSelected ? data.classSelected : '');
			for(var i=1; i<=step; i++) {
				$steps.filter('[data-step="' + i + '"]').css('opacity', 1).addClass(data.classSelected ? data.classSelected : '');			
			}
			
			$sliderContent.stop().animate({'left': -1*sliderWidth*(step - 1)}, 600);
		}
		
		// steps click	
		$steps.on('click', function() {
			changeStep($(this).attr('data-step'));
		});
		
		// buttons
		$steps2.on('click', function() {
			changeStep($(this).attr('data-step'));
		});

		changeStep(data.selected);
	},
	
	checkboxTree: function($this, data) {
		var $form = $this.closest('form');
		if($form.length) {
			var checkGroup = function(index, checked) {
				$this.find('[data-check-group="' + index + '"]').attr('checked', !!checked);
				$this.find('[data-check="' + index + '"]').attr('checked', !!checked).map(function() { checkOne($(this).val(), $(this).attr('checked')); });
				
				if(checked) {
					$this.find('[data-check-content="' + index + '"]').show();
				}
				else {
					$this.find('[data-check-content="' + index + '"]').hide();
				}
				
//				
			}
			
			var checkOne = function(value, checked) {
//				App.log(value, !!checked);	
				$form.find('[data-element="checkboxTree"] [type="checkbox"][value="' + value + '"]').attr('checked', !!checked);
			}
		
			$('[data-check-group]').on('click', function() {
				checkGroup($(this).attr('data-check-group'), $(this).is(':checked'));
			});
			
			$('[data-check]').on('click', function() {
				checkOne($(this).val(), $(this).attr('checked'));
			});
			
			var $checkedCheckbox = $this.find('[data-check]:checked');
			if($checkedCheckbox.length) {
				// check all
				$checkedCheckbox.each(function() {
					checkGroup($(this).closest('[data-check-content]').attr('data-check-content'), true);
				});
				
				// check selected
				$this.find('[data-check]:checked').removeAttr('checked');
				var checkedArr = $checkedCheckbox.map(function() { return $(this).val(); }).get();
				for(var i=0; i<checkedArr.length; i++) {
					$this.find('[value="' + checkedArr[i] + '"]').attr('checked', true);
				}
			}
		}
	},
	
	multiuploader: function($this, data) {
		App.uploadFileIndex = App.uploadFileIndex ? App.uploadFileIndex : 1;
		$this.find('input[type=file]').fileupload({
	        dataType: 'json',
	        type: 'POST',
	        done: function (e, response) {
	        	var result = response.result;
	        	if(result && result.success && typeof result.content !== undefined) {
	        		// add data
	        		App.uploadFileIndex++; 
	        		$this.find('.files').append(result.content.split('{$index}').join(App.uploadFileIndex));
                }	
	        }
		});	
	},
	
	avataruploader: function($this, data) {
		$this.find('input[type=file]').fileupload({
	        dataType: 'json',
	        type: 'POST',
	        done: function (e, response) {
	        	var result = response.result;
	        	if(result && result.success && typeof result.content !== undefined) {
	        		// update data
	        		$this.find('[name="data[avatar]"]').val(result.content.avatar);
	        		$this.find('[name="data[avatar_thumb]"]').val(result.content.avatar_thumb);
	        		
	        		// update image
	        		$this.find('img').attr('src', result.content.avatar_thumb);
                }	
	        }
		});	
	},
	
	search: function($this, data) {
		var $form = $this.find('[data-search-form]');
		var $reset = $this.find('[data-search-reset]');
		var $content = $this.find('[data-search-content]');
		
		if($form.length) {
			function submitForm() {
				App.ajax.request({
					url: $form.attr('action'),
					data: $form.serialize(),
					onDone: function(response) {
						if(response.content) {
							$content.html(response.content);
						}
					}
				});
			}
			
			$form.submit(function(e){ 
				e.preventDefault(); 
			});
			
			$reset.on('click', function(){
				// reset
				$form.find('select, input[type="text"], input[type="password"], textarea').val('');
				$form.get(0).reset();

				// submit
				$reset.addClass('sel');
				submitForm();
			});
			
			$form.find('select').on('change', function(){
				// submit
				$reset.removeClass('sel');
				submitForm();
			});
		}
	},
	
	logicConferenceType: function($this, data) {
		var $form = $this.closest('form');
		var $type = $form.find('[name="data[type]"]');
		
		function changeConferenceType(type) {
			if(type == 1) { // public
				$form.find('[data-check-group], [data-check]').attr({'checked': true, 'disabled': true});
				$form.find('[data-check-content]').show();
			}
			
			if(type == 2) { // private
				$form.find('[data-check-group], [data-check]').removeAttr('checked').removeAttr('disabled');
				$form.find('[data-check-content]').hide();
			}
		}
		
		$type.each(function(){
			if($(this).attr('checked')) {
				changeConferenceType(this.value);
				return false;
			}
		});
		
		$type.on('change', function(event) {
			changeConferenceType(this.value);
		});
	},
	
	logicUserLevel: function($this, data) {
		var $form = $this.closest('form');
		var $level = $form.find('[name="data[level]"]');
		
//		App.log($form, $level);
		
		function changeUserLevel(level) {
			$form.find('.groups').hide();
		
			if(level == 3) { // REGIONAL DIRECTOR
				$form.find('.groups-title').show().find('input').removeAttr('disabled');
				$form.find('.groups-id').hide().find('select').attr('disabled', true);
				$form.find('.groups').show();
			}
			
			if(level == 4) { // CUSTOMER
				$form.find('.groups-title').hide().find('input').attr('disabled', true);
				$form.find('.groups-id').show().find('select').removeAttr('disabled');
				$form.find('.groups').show();
			}
		}
		
		$level.on('change', function(event) {
			changeUserLevel(this.value);
		});
		
		changeUserLevel($level.val());
	}
};