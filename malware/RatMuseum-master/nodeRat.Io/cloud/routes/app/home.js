// [ Clients listing ]
exports.home = function(req, res){

	console.log("Admin GET: Home - Client listing: ".cyan + req.mySession.admin_info[0].admin_pseudo);

	sql_client_list = "SELECT * FROM t_clients WHERE client_active = 1;";
	db.query(sql_client_list, function(err, rows, fields) {
		var clients = rows;

		sql_console_log = "SELECT * FROM t_logs ORDER BY log_id DESC LIMIT 100;";
		db.query(sql_console_log, function(err, rows, fields) {
			var logs = rows;

			res.render('app/home', {
				title    : 'Home - Client listing',
		        section : 'client_listing',
		        admin   : req.mySession.admin_info[0],
						clients : clients,
						logs    : logs
			});

		});

	});

};

// [ CLient dashboard ]
exports.home_post = function(req, res){

	console.log(req.body);

	db.query("INSERT INTO t_clients (client_serial, client_key, client_hostname, client_ip, client_os, client_os_version, client_version, client_privilege_access, client_country, client_socket, client_active) "+
						"VALUES ("+db.escape(req.body.serial)+", "+
						        ""+db.escape(req.body.key)+", "+
						        "'?', "+
						        "'?', "+
						        "'?', "+
						        "'?', "+
						        "'?', "+
						        "'?', "+
						        "'?', "+
						        "'?', "+
						        "'1');");

	res.redirect('/home');

};
