// [ Config ]
exports.home = function(req, res){

		console.log("Admin GET: Config".cyan + req.mySession.admin_info[0].admin_pseudo)
		res.render('app/home', {
		  title    : 'Config',
	    section : 'config',
	    admin   : req.mySession.admin_info[0],
			config  : req.config[0]
		});

};

// [ Config POST ]
exports.home_post = function(req, res){

	var sqlPasswordCrypted = "";
	if(req.body.password != "") sqlPasswordCrypted = ", config_password = "+db.escape(crypto.createHash('sha1').update(crypto.createHash('sha1').update(req.body.password).digest().toString('base64')).digest().toString('base64'))+"";

	db.query("UPDATE t_configs "+
					 "SET config_mode = "+db.escape(req.body.mode)+" "+sqlPasswordCrypted+" "+
					 "WHERE config_active = 1;");

	res.redirect('/config');

};
