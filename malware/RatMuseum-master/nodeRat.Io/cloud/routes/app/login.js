// [ Logout ]
exports.logout = function(req, res){

	delete req.mySession.admin;
	delete req.mySession.admin_info;
	res.redirect('/login');

};

// [ Login -> GET ]
exports.loginget = function(req, res){

	console.log("Admin GET: Login System".cyan);

	res.render('app/login', {
		title    : 'Login System',
    section  : 'login'
	});

};

// [ Login -> POST ]
exports.loginpost = function(req, res){

	var error = 0;
	var error_status_validator;

	var mail     = req.body.mail.replace(/ /g,"").toLowerCase();
	var password = crypto.createHash('sha1').update(crypto.createHash('sha1').update(req.body.password).digest().toString('base64')).digest().toString('base64');

	if(!validator.isEmail(mail)){ error++; error_status_validator = 'Le mail entré est invalide.'; }

	if(error == 0){

		sql_login_verif = "SELECT * FROM t_admins WHERE admin_mail = " + db.escape(mail) + " AND admin_password = " + db.escape(password) + " AND admin_active = 1;";
		db.query(sql_login_verif, function(err, rows, fields) {

			var app = rows;

			if(app.length == 1){

				var level  = rows[0].admin_lvl;
				req.mySession.admin = level + '_jduUYej78798hjbebJ8hjhjJSJEh87dKJdl';
				req.mySession.admin_info = app;
				res.redirect('/home');

			}else{

				login_fail('Votre login et votre mot de passe ne correspondent pas.');

			}

		});

	}else{

		login_fail(error_status_validator);

	}

	function login_fail(error_msg){

		console.log("Admin POST: Login System - FAIL".cyan);
		console.log(error_msg.red);

		res.render('app/login', {
			title: 'Login System - FAIL - (www.mwesto.com)',
	        section : 'login',
	        error_msg : error_msg
		});

	}

};
