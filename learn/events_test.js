var events =require('events');
var fileevent = new events.EventEmitter();

var eventhand=function () {
	console.log('事件成功触发');
}

fileevent.on('open',eventhand);

fileevent.emit('open');