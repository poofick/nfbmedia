// Подключаем модуль и ставим на прослушивание 8080-порта - 80й обычно занят под http-сервер
var io = require('socket.io').listen(8080); 

// Отключаем вывод полного лога - пригодится в production'е
io.set('log level', 1);

// Следить за очищением этих масивов!
var conferences = {};
var userSockets = {};

// Навешиваем обработчик на подключение нового клиента
io.sockets.on('connection', function (socket) {
    var session = {};

    socket.on('message', function (msg) {
 switch(msg.event){
     case 'entry':
  // Забиваем масив конференций
  conferences['C' + msg.conferenceId] = conferences['C' + msg.conferenceId] ? conferences['C' + msg.conferenceId] : {'onAir': false};
  conferences['C' + msg.conferenceId]['U' + msg.userId] = {'userId': msg.userId, 'userName': msg.userName, isPublisher: msg.isPublisher, 'onAir': false};
  userSockets['C'+ msg.conferenceId + 'U' + msg.userId] = socket;
  
  session.userId  = msg.userId;
  session.conferenceId = msg.conferenceId;
  
  socket.broadcast.json.send({'event': 'userJoined', 'conferenceId': msg.conferenceId, 'userId': session.userId, 'userName': msg.userName, 'isPublisher': msg.isPublisher});
  
  // Get conference users
  var user, users = [];
  for( user in conferences['C' + msg.conferenceId] ){
      if( user != 'onAir' ) users.push(conferences['C' + msg.conferenceId][user]);
  }
  socket.json.send({'event': 'connected', 'conferenceId': msg.conferenceId, 'onAir': conferences['C' + msg.conferenceId]['onAir'], 'users': users});
  
     break;
     case 'startConference':
  if(conferences['C' + msg.conferenceId]){
      conferences['C' + msg.conferenceId].onAir = true;
  }
  socket.broadcast.json.send({'event': 'startConference', 'conferenceId': msg.conferenceId});
     break;
     case 'stopConference':
  socket.broadcast.json.send({'event': 'stopConference', 'conferenceId': msg.conferenceId});
     break;
     case 'conferenceBroadcast':
  if(msg.userId && msg.action == 'userCameraStop' && conferences['C' + session.conferenceId] && conferences['C' + session.conferenceId]['U' + msg.userId]){
      conferences['C' + session.conferenceId]['U' + msg.userId]['onAir'] = false;
  }
  msg.conferenceId = session.conferenceId;
  socket.broadcast.json.send(msg);
     break;
     case 'pingConferenceUser':
  if(msg.userId && msg.action == 'userCameraPublish' && conferences['C' + session.conferenceId] && conferences['C' + session.conferenceId]['U' + msg.userId]){
      conferences['C' + session.conferenceId]['U' + msg.userId]['onAir'] = true;
  }
  if(msg.userId && userSockets['U' + msg.userId]){
      userSockets['C' + session.conferenceId + 'U' + msg.userId].json.send(msg);
  }
     break;
 }
    });
    
    socket.on('disconnect', function() {
 delete conferences['C' + session.conferenceId]['U' + session.userId];
 delete userSockets['U' + session.userId];
 
        io.sockets.json.send({'event': 'userExit', 'userId': session.userId, 'conferenceId': session.conferenceId});
    });
});