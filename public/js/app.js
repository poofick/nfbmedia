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
    processing: false,
    request: function( options ) {
	if( App.ajax.processing ) return false;
	App.ajax.processing = true;
	
        options = $.extend({}, {type: 'post', dataType:'json', data:{}}, options);
        options.url = App.config.appDir ? '/' + App.config.appDir + (options.url ? options.url : '/') : (options.url ? options.url : '/');
        
        var ajax = $.ajax(options);
        ajax.always(function(response) {
            if(!response.success || typeof response.success == 'function') {
                if(typeof options.onError == 'function') {
					options.onError(response); 
                }else{
					if(response.message) 
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
			App.ajax.processing = false;
            
        });
    }
};

App.conference = {
	url: 'rtmp://nfbmedia.com/vod',
	userUrl: 'rtmp://nfbmedia.com/pub',
	
	id: null,
	
	type: null,
	
	onAir: 0,
	
	user: {
		id: null,
		name: '',
		isPublisher: false,
		onAir: false
	},
	
	server: {
		socket: null,
		
		init: function(id, userId, userName, isPublisher) {
			var obj = this;
			
			setTimeout(function() {
				if(typeof io !== 'undefined') {
					App.conference.id = id;
					
			        if(0 && navigator.userAgent.toLowerCase().indexOf('chrome') != -1) { // Chrome
			            obj.socket = io.connect('http://nfbmedia.com:8080', {'transports': ['xhr-polling']});
			        } 
			        else {
			            obj.socket = io.connect('http://nfbmedia.com:8080');
			        }
			        
			        obj.socket.on('connect', function() {
			        	App.log('connect', userId, userName, isPublisher);
			            obj.onconnect(userId, userName, isPublisher);
			            
			            obj.socket.on('message', function(msg) {
			                App.log(msg, msg.conferenceId);
			                
			                var msgName = 'msg_' + msg.event;
			                App.log(msgName);
			                
			                if(typeof obj[msgName] == 'function') {
			                	if(App.conference.id == msg.conferenceId) {
			                    	obj[msgName](msg);
			                	}
			                } 
			                else {
			                    App.log('Conference Server - Unknown Message:' + msg);
			                }
			            });
			        });
				}
				else {
					App.log('Soket IO not defined');
				}
			}, 500);
		},
		
		onconnect: function(userId, userName, isPublisher) {  
			if(this.socket) {
				App.conference.user = {id: userId, name: userName, isPublisher: isPublisher};
	        	this.socket.json.send({event: 'entry', conferenceId: App.conference.id, userId: userId, userName: userName, isPublisher: isPublisher});
			}
	    },
	    
	    send: function(msg) {
	    	if(this.socket) {
	    		App.log('Soket IO Send', msg);
	        	this.socket.json.send(msg);
	    	}
	    },
	    
	    msg_connected: function(msg) {  
	    	// start conference
	    	if(msg.onAir) {
				App.conference.start();
	    	}

			// add users
			App.conference.userList.addItems(msg.users);
	    },
	    
	    msg_userJoined: function(msg) {
			App.conference.userList.addItems([{userId: msg.userId, userName: msg.userName, isPublisher: msg.isPublisher}]);
	    },
	    
	    msg_userExit: function(msg) {
			App.conference.userList.removeItem(msg.userId);
	    },
	    
	    msg_startConference: function(msg) {
			App.conference.start();
	    },
	    
	    msg_stopConference: function(msg) {
			App.conference.stop();
	    },
	    
	    msg_pingConferenceUser: function(msg) {
	    	switch(msg.action) {
	    		case 'userCameraPublish':
		    			App.conference.userCamera.start(App.conference.user.id, 1);
		    			App.conference.server.send({event: 'conferenceBroadcast', action: 'userCameraPlay', conferenceId: App.conference.id, userId: App.conference.user.id});
	    			break;
	    	}
	    },
	    
	    msg_conferenceBroadcast: function(msg) {
	    	switch(msg.action) {
	    		case 'userCameraPlay':
	    				App.conference.userCamera.start(msg.userId, 0);
	    			break;
	    			
	    		case 'userCameraStop':
	    				App.conference.userCamera.stop(msg.userId);
	    			break;
	    			
	    		case 'userChatAddMessage':
	    				App.conference.userChat.addMessage(msg.userName, msg.userMessage);
	    			break;
	    	}
	    }
	},
	
	start: function() {
		if(this.id) {
			var type = this.user.isPublisher ? 'publish' : 'play';
			
			var videoId = 'video' + this.id;
			$('#' + videoId).show();
			this.createSwfObject(videoId, {src: this.url + '?' + type + '=' + this.id});
			
			this.onAir = 1;
			
			// show close button / hide start button
			$('#conferenceOn').hide();
			$('#conferenceOff').show();
			
			// start
			if(type == 'publish') {
				this.server.send({event: 'startConference', conferenceId: this.id});
			}
			
			App.conference.userList.refresh();
		}
	},
	
	stop: function() {
		if(this.id) {
			var videoId = 'video' + this.id;
			var videoObject = this.getSwfObject(videoId);
			if(videoObject) {
				videoObject.setProperty('src', '');
				$(videoObject).remove();
				
				this.onAir = 2;
				
				// show status
				$('#player .status').html('Конференцію завершено');
				
				// show popup
				App.dialog.open($('#closeConference').html());
				
				// stop
				var type = this.user.isPublisher ? 'publish' : 'play';
				if(type == 'publish') {
					this.server.send({event: 'stopConference', conferenceId: this.id});
				}
				
				App.conference.userList.refresh();
			}
		}
	},
	
	createSwfObject: function(videoId, vars) {
		var flashParams = {
			allowFullScreen: true, 
			wmode: 'window'
		};
		
		var flashVars = {
			enableFullscreen: true, 
			controls: true,
			rate: 8,
			gain: 0.4,
			sileneceLevel: 2,
			cameraFPS: 24,
			cameraQuality: 95,
			cameraDimension: '320x240'
//			codec: 'NellyMoser'
//			videoCodec: 'H264Avc'
		};
        
        swfobject.embedSWF('/swf/VideoIO.swf', videoId, $('#' + videoId).width(), $('#' + videoId).height(), '9.0.28', 'expressInstall.swf', $.extend(flashVars, vars), flashParams, {id: videoId});
	},
	
	getSwfObject: function(movieName) {
    	var isIE = navigator.appName.indexOf('Microsoft') != -1;
    	return (isIE) ? window[movieName] : document[movieName];
	},
	
	playVideo: function(id, converting_status, url) {
		if(converting_status == 0) {
			setTimeout(function() {
				App.ajax.request({
					url: '/' + App.config.appController + '/getmultimediaconvertingstatus/' + id,
					data: {},
					onDone: function(response) {
						App.conference.playVideo(id, response.status && response.status == 1 ? 1 : 0, response.url);
					}
				});
			}, 10000);
		}
		else if(url) {
			var videoId = 'video' + id;
			$('#' + videoId).empty();
			$f(videoId, '/swf/flowplayer-3.2.16.swf', 'http:' + url);
			// this.createSwfObject(videoId, {src: url});
		}
	},
	
	// users list
	userList: {
		addItems: function(items) {
			for(var i=0; i<items.length; i++) {
				if(items[i].userId && items[i].userName && !items[i].isPublisher) {
					if($('#user' + items[i].userId).length == 0) {
						var template = $('#conferenceUser').html();
						template = template.split('{$conference.id}').join(App.conference.id);
						template = template.split('{$user.id}').join(items[i].userId);
						template = template.split('{$user.name}').join(items[i].userName);
						$('#usersList').append(template);
						
						if(typeof items[i].onAir !== 'undefined' && items[i].onAir) {
							App.conference.userCamera.start(items[i].userId, 0);
						}
					}
				}
			}
			
			this.refresh();
		},
		
		removeItem: function(itemId) {
			$('#user' + itemId).remove();
			this.refresh();
		},
		
		refresh: function() {
			App.log('Refresh List');
			
			switch(App.conference.onAir) {
				case 0:
				case 1:
						if($('#usersList .item').length > 1) {
							$('#usersList .item.null').hide();
						}
						else {
							$('#usersList .item.null').show();
						}
						
						if(App.conference.onAir == 1 && App.conference.user.isPublisher) {
							$('#usersList .play').show(); // status = 0 onAir = false
						}
					break;
					
				case 2: 
						$('#usersList .item[id^="user"]').remove();
						$('#usersList .item.null').show();
					break;	
			}
		}
	},
	
	// users video 
	userCamera: {
		status: false,
		
		action: function(user_id) {
			this.status = !this.status;
			App.log(user_id, this.status);
			
			if(this.status) {
				App.conference.server.send({event: 'pingConferenceUser', action: 'userCameraPublish', conferenceId: App.conference.id, userId: user_id});
			}
			else {
				App.conference.server.send({event: 'conferenceBroadcast', action: 'userCameraStop', conferenceId: App.conference.id, userId: user_id});
				this.stop(user_id);
			}
		},
		
		start: function(user_id, status) {
			this.status = true;
			
			var userVideoId = 'video' + App.conference.id + '_' + user_id;
			if($('#' + userVideoId).length) {
				$('#' + userVideoId).parent().show();
				App.conference.createSwfObject(userVideoId, {src: App.conference.userUrl + '?' + (status ? 'publish' : 'play') + '=' + App.conference.id + '_' + user_id, controls: false, volume: 0.9, cameraDimension: '265x150'});
			}
			
			this.refresh(user_id);
		},
		
		stop: function(user_id) {
			this.status = false;
			
			var userVideoId = 'video' + App.conference.id + '_' + user_id;
			if($('#' + userVideoId).length) {
				var userVideoObject = App.conference.getSwfObject(userVideoId);
				if(userVideoObject) {
					userVideoObject.setProperty('src', '');
					$(userVideoObject).parent().hide();
				}
			}
			
			this.refresh(user_id);
		},
		
		refresh: function(user_id) {
			if(this.status) {
				$('#user' + user_id + ' .play').addClass('active');
			}
			else {
				$('#user' + user_id + ' .play').removeClass('active');
			}
		}
	},
	
	// users chat
	userChat: {
		sendMessage: function(msg) {
			App.conference.server.send({event: 'conferenceBroadcast', action: 'userChatAddMessage', conferenceId: App.conference.id, userName: App.conference.user.name, userMessage: msg});
			this.addMessage(App.conference.user.name, msg);
		},
		
		addMessage: function(name, msg) {
			if(msg.length) {
				var template = $('#chatMessage').html();
				template = template.split('{$name}').join(name);
				template = template.split('{$message}').join(msg);
				$('#messagesList').append(template);
				
				$('#messagesList').closest('.nano').nanoScroller({scrollTop: $('#messagesList')[0].scrollHeight});
			}
		}
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
			
		App.dialog.close();	
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
			
			var level = $form.find('[name="data[level]"]').val();
			if(level == 2) { // general director
				$form.find('[name="data[group_id]"]').attr('disabled', 'disabled');
				$form.find('[name="data[group_title]"]').attr('disabled', 'disabled');
			}
			
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
		App.log(App.conference.id);
		if(App.conference.id) {
			$this.hide();
			
			App.ajax.request({
				url: '/' + App.config.appController + '/updatemultimediastatus/' + App.conference.id + '/1',
				data: {},
				onDone: function(response) {
					App.conference.start();
				}
			});
		}
	},
	
	conferenceOff: function($this, data) {
		if(App.conference.id) {
			$this.hide();
			
			App.ajax.request({
				url: '/' + App.config.appController + '/updatemultimediastatus/' + App.conference.id + '/2',
				data: {},
				onDone: function(response) {
					App.conference.stop();
				}
			});
		}
	},
	
	conferenceUserCamera: function($this, data) {
		if(App.conference.id && App.conference.onAir == 1) {
			App.conference.userCamera.action(data.userId);
		}
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
	
	datetimepicker: function($this, data) {
		if(data.type == 'date')	{
			$this.datepicker({
				changeMonth: true,
				changeYear: true
			});
		}
		else if(data.type == 'time') {
			$this.timepicker();	
		}
		else {
			$this.datetimepicker();	
		}
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
	
	conferenceUserChat: function($this, data) {
		var $input = $this.find('input.text');
		var $button = $this.find('input.button');
		
		function sendMessage() {
			var message = $.trim($input.val());
			if(message.length) {
				App.conference.userChat.sendMessage(message);
				$input.val('');
			}
		}
		
		$button.on('click', function() {
			sendMessage();
		});
		
		$input.keydown(function(e){
		    if(e.keyCode == 13) {
		    	sendMessage();
		    }
		});
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