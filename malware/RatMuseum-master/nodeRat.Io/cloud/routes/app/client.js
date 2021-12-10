// [ Client Dashboard  ]
exports.client_dashboard = function(req, res){

	console.log("Admin GET: Client Dashboard (Client:".cyan+req.params['id']+") : ".cyan + req.mySession.admin_info[0].admin_pseudo)

	sql_login_verif = "SELECT * FROM t_clients WHERE client_id = " + db.escape(req.params['id']) + " AND client_active = 1;";
	db.query(sql_login_verif, function(err, rows, fields) {

		var client = rows;
		res.render('app/home', {
			title   : 'Client Dashboard',
	    section : 'client_dashboard',
	    admin   : req.mySession.admin_info[0],
			client  : client[0]
		});

	});

};

// [ Client Dashboard POST update ]
exports.client_dashboard_post = function(req, res){

	db.query("UPDATE t_clients "+
					 "SET client_serial = "+db.escape(req.body.serial)+", "+
					 "client_key = "+db.escape(req.body.key)+", "+
					 "client_active = "+db.escape(req.body.active)+" "+
					 "WHERE client_id = "+db.escape(req.params['id'])+";");

	res.redirect('/client_dashboard/'+req.params['id']);

};

// [ Client Dashboard delete ]
exports.client_dashboard_delete = function(req, res){

	db.query("DELETE FROM t_clients WHERE client_id = "+db.escape(req.params['id'])+";");
	res.redirect('/home');

};

// [ Dashboard  ]
exports.client_remote_shell = function(req, res){

	console.log("Admin GET: Client Remote Shell (Client:".cyan+req.params['id']+") : ".cyan + req.mySession.admin_info[0].admin_pseudo)

	sql_login_verif = "SELECT * FROM t_clients WHERE client_id = " + db.escape(req.params['id']) + " AND client_active = 1;";
	db.query(sql_login_verif, function(err, rows, fields) {

		var client = rows;
		res.render('app/home', {
			title   : 'Client Remote Shell',
			section : 'client_remote_shell',
			admin   : req.mySession.admin_info[0],
			client  : client[0]
		});

	});

};
