exports.dependency_loggedOut_app = function(req, res, next) {

     next();

}

exports.logged_app = function(req, res, next) {

    if (req.mySession.admin) {

        next();

    }else{

        res.redirect('/login');

    }

}

exports.dependency_logs_app = function(req, res, next) {

  var dataPost = "";
  if(req.method == "POST")
    dataPost = JSON.stringify(req.body);

  db.query("insert INTO t_logs"+
                   "(log_datetime,"+
                   "log_ip,"+
                   "log_type,"+
                   "log_origin,"+
                   "log_text,"+
                   "log_data)"+
      "VALUES (NOW(),"+
              ""+db.escape(req.connection.remoteAddress)+","+
              ""+db.escape(req.method)+","+
              "'web',"+
              ""+db.escape(req.url)+","+
              ""+db.escape(dataPost)+");", function(err, results) {
                next();
              });

}

exports.dependency_loggedIn_app = function(req, res, next) {

  sql_login_verif = "SELECT * FROM t_configs WHERE config_active = 1;";
	db.query(sql_login_verif, function(err, rows, fields) {
    req.config = rows;
    next();
  });

}
