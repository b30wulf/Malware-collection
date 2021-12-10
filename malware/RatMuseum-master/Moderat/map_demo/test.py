from PyQt4.QtCore import *
from PyQt4.QtGui import *
from PyQt4.QtWebKit import *

from mapstyle import style

maphtml = '''
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0px; padding: 0px }
  #map_canvas { height: 100% }
</style>
<script type="text/javascript"
  src="http://maps.google.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">
var map;
var clients = {};
function initialize() {
    var latlng = new google.maps.LatLng(44.0727142, -26.533);
    var myOptions = {
                    zoom: 3,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    disableDefaultUI: true
                    };
     map = new google.maps.Map(document.getElementById("map_canvas"),
                               myOptions);

    var styles = '''+style+'''

    map.setOptions({styles: styles});
 }

 function addMarker(id, lat, lng, ip_address, alias) {
  var myLatLng = new google.maps.LatLng(lat, lng);
  clients[id] = new google.maps.Marker({position: myLatLng,
                                            icon: "C:/Users/uripa/Desktop/moderat/assets/hacked.png",
                                            ip_address: ip_address,
                                            alias: alias,
                                            id: id,
                                           });
  clients[id].setMap(map)
  addInfoWindow(clients[id]);
 }

 function addInfoWindow(client) {

    var header = "<p align='center' style='background: #2c3e50; color: #c9f5f7; padding: 10px;'>" + client.alias + "<br>" + client.ip_address + "</p>"
    var shell = "<input type='button' style='background: transparent; color: #34495e; border: none;' onClick='gotoNode("+client.id+")'value='Remote Shell'/><br>"
    var explorer = "<input type='button' style='background: transparent; color: #34495e; border: none;' onClick='gotoNode("+client.id+")'value='Remote Explorer'/><br>"
    var scripting = "<input type='button' style='background: transparent; color: #34495e; border: none;' onClick='gotoNode("+client.id+")'value='Remote Scripting'/><br>"
    var desktop = "<input type='button' style='background: transparent; color: #34495e; border: none;' onClick='gotoNode("+client.id+")'value='Remote Desktop'/><br>"
    var webcam = "<input type='button' style='background: transparent; color: #34495e; border: none;' onClick='gotoNode("+client.id+")'value='Remote Webcam'/><br>"
    var info = header + shell + explorer + scripting + desktop + webcam + "</div>"

    var infoWindow = new google.maps.InfoWindow({
        content: info
    });

    google.maps.event.addListener(client, 'click', function () {
        infoWindow.open(map, client);
    });
}

function gotoNode(id) {
    self.browse(clients[id].url)
}


</script>
</head>
<body onload="initialize();">
    <div id="map_canvas" style="width:100%; height:100%"></div>
</body>
</html>
'''

class Browser(QApplication):
    def __init__(self):
        QApplication.__init__(self, [])
        self.window = QWidget()
        self.window.setWindowTitle("Google Google Maps Maps")

        self.web = QWebView(self.window)
        self.web.setMinimumSize(800,800)
        self.web.page().mainFrame().addToJavaScriptWindowObject('self', self)
        self.web.setHtml(maphtml)
        self.button = QPushButton('AddMarker')
        self.button.clicked.connect(self.addMarker)
        self.layout = QVBoxLayout(self.window)
        self.layout.addWidget(self.web)
        self.layout.addWidget(self.button)

        self.window.show()
        self.exec_()

    def addMarker(self):
        self.web.page().mainFrame().evaluateJavaScript(QString("addMarker(1, 41.5, 45.2, '92.125.102.146', 'UG-Giorgi')"))
        self.web.page().mainFrame().evaluateJavaScript(QString("addMarker(1, 41.3, 45.1, '94.125.102.146', 'UG-Giorgi')"))
        self.web.page().mainFrame().evaluateJavaScript(QString("addMarker(1, 41.1, 45.3, '92.243.102.146', 'UG-Giorgi')"))
        self.web.page().mainFrame().evaluateJavaScript(QString("addMarker(1, 41.2, 45.4, '102.125.102.146', 'UG-Giorgi')"))

    @pyqtSlot(str)
    def browse(self, url):
        print url

Browser()