function InteractiveJsonProtocolHandler(webSocket) {
	EventEmitter3.call(this);
	this.ws = webSocket;
	this.ws.addEventListener('open', this.onWebSocketOpen.bind(this));
	this.ws.addEventListener('close', this.onWebSocketClose.bind(this));
}

InteractiveJsonProtocolHandler.prototype = {
	__proto__: EventEmitter3.prototype,

	onWebSocketOpen: function (event) {
		this.emit('ws-open', event);
		this.ws.addEventListener('message', this.onMessageReceived.bind(this));
	},

	onWebSocketClose: function (event) {
		let is_unexpected_close = event.code !== 1000; // Abnormal connection close
		this.emit('ws-close', is_unexpected_close, event);
	},

	onMessageReceived: function (event) {
		let data = this._parseMessage(event.data);
		if (!data) return;

		let someoneGotIt = false;
		if (typeof data.exit === 'number') {
			if (data.exit != 0) {
				someoneGotIt = this.emit('exit', data.exit, data.error, data);
			} else {
				someoneGotIt = this.emit('exit', data.exit, data.message, data);
			}
		} else if (data.download) {
			someoneGotIt = this.emit('download', data.download, data);
		} else if (typeof data.progress === 'number') {
			someoneGotIt = this.emit('progress', data.progress, data.total, data);
		} else if (data.message) {
			// This is not the result of a test. Log it just in case.
			someoneGotIt = this.emit('message', data.message, data.type, data);
		}
		// If the message does not belog to any category
		// or there were no listeners to that category, send as generic data.
		if (!someoneGotIt)
			this.emit('data', data);
	},

	_parseMessage: function (message) {
		let json = null;
		if (typeof message === 'object') {
			json = message;
		} else if (typeof message === 'string') {
			try {
				json = JSON.parse(message);
			} catch (e) { // It is not a json
				message = message.trim();
				if (message) {
					json = { message: message };
				}
			}
		}
		return json;
	},

}
