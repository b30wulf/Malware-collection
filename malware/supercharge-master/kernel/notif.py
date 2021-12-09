from plyer import notification

def notify(ip,port,total):
    notification.notify(
        "New Connection.",
        ip + ":" + port + " has successfully Connected.\nTotal : " + total,
        "Super Charge",
        "img/logo.ico"
    )