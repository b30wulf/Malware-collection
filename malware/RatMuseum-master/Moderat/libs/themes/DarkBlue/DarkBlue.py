import os
icons_path = os.path.join(
    os.path.dirname(os.path.abspath(__file__)),
    'icons').replace('\\', '/')

stylesheet = r'''
QMainWindow::separator::horizontal {{
    background: url({0}/split-h.png);
    width: 16px;
}}

QMainWindow::separator::vertical {{
    background: url({0}/split-v.png);
    height: 16px;
}}

QWidget {{
    background-color: #2c3e50;
    color: #c9f5f7;
}}

QWidget[spacer="1"] {{
    background: transparent;
}}

QSplitter::handle:horizontal {{
    width: 16px;
    background: url({0}/split-v.png);
}}

QSplitter::handle:vertical {{
    height: 16px;
    background: url({0}/split-h.png);
}}

QToolBar {{
    background: #131d26;
    padding: 3px;
    border: none;
}}

QToolBar::handle {{
    image: url({0}/move.png);
}}

QToolBar::separator {{
    background: #131d26;
}}

QWebView {{
    background: #131d26;
    padding: 5px;
}}

QLabel {{
    background: transparent;
}}

QSpacer {{
    background: transparent;
}}

QPushButton {{
    background: #34495e;
    border: 2px outset #202d3a;
    border-radius: 3px;
    min-width: 80px;
    padding: 5px;
    margin: 0px;
}}

QPushButton[mainMenu="1"] {{
    border: none;
    border-radius: none;
    padding: 3px;
    min-width: 10px;
    background-color: #2c3e50;
}}

QPushButton[mainMenu="1"]:hover {{
    border: none;
    border-radius: none;
    padding: 3px;
    min-width: 10px;
    background-color: #2c3e50;
}}

QPushButton[mainMenu="1"]:pressed {{
    border: none;
    border-radius: none;
    padding: 3px;
    min-width: 10px;
    background-color: #2c3e50;
}}

QPushButton[counter="1"] {{
    border: none;
    color: #33b288;
    min-width: 30px;
    background: transparent;
}}

QPushButton[counter="1"]:hover {{
    border: none;
    padding: 20px;
    min-width: 30px;
    background: transparent;
}}

QPushButton[counter="1"]:pressed {{
    border: none;
    min-width: 30px;
    background: transparent;
}}

QPushButton:hover {{
    background: qlineargradient(x1: 0, y1: 0, x2: 0, y2: 1,
        stop: 0 #364d63, stop: 0.4 #32475b,
        stop: 0.5 #32475b, stop: 1.0 #364d63);
}}

QPushButton:pressed {{
    background: qlineargradient(x1: 0, y1: 0, x2: 0, y2: 1,
        stop: 0 #2c3e50, stop: 0.4 #34495e,
        stop: 0.5 #34495e, stop: 1.0 #2c3e50);
}}

QPushButton:checked {{
    border: 2px inset #202d3a;
    background-color: #2f4356;
}}

QTextEdit {{
    color: #2ecc71;
    border: 1px inset #33495e;
    background-color: #131d26;
    background-image: url(assets/bg.png);
    background-repeat: no-repeat;
    background-position: center;
    padding: 5px;
}}

QPlainTextEdit {{
    color: #2ecc71;
    border: 1px inset #33495e;
    background-color: #131d26;
    background-image: url(assets/bg.png);
    background-repeat: no-repeat;
    background-position: center;
    padding: 5px;
}}

QTabWidget::pane {{
    padding-top: 0px;
}}

QTabWidget::tab-bar {{
    left: 10px;
}}

QTabBar::tab {{
    padding: 3px;
    padding-right: 12px;
    color: #c9f5f7;
    border-bottom: 1px solid #7f8c8d;
}}

QTabBar::tab:selected,
QTabBar::tab:hover {{
    background: #34495e;
}}

QTabBar::tab:selected {{
    border-bottom: 2px solid #3498db;
}}

QTabBar::tab:!selected {{
    background: #2c3e50;
}}

QLineEdit {{
    background-color: #273747;
    padding: 5px;
    border: 1px solid #243342;
}}
    
QLineEdit:focus {{
    border: 1px ridge #34495e;
}}
QLineEdit:hover {{
    border: 1px ridge #34495e;
}}

QLineEdit[filter="1"] {{
    background-color: transparent;
    padding: 5px;
    border: none;
}}

QLineEdit[filter="1"]:focus {{
    border: none;
}}
QLineEdit[filter="1"]:hover {{
    border: none;
}}

QComboBox {{
    background: #34495e;
    selection-background-color: #395168;
    padding: 5px;
    border: 1px solid #243342;
}}

QComboBox QListView {{
    border: none;
}}

QComboBox:editable {{
    background: #34495e;
    border: 1px ridge #34495e;
}}

QComboBox:!editable, QComboBox::drop-down:editable {{
    background: qlineargradient(x1: 0, y1: 0, x2: 0, y2: 1,
                             stop: 0 #34495e, stop: 0.4 #395168,
                             stop: 0.5 #2c3e50, stop: 1.0 #33495e);
}}

QComboBox:!editable:on, QComboBox::drop-down:editable:on {{
    background: qlineargradient(x1: 0, y1: 0, x2: 0, y2: 1,
                                 stop: 0 #34495e, stop: 0.4 #395168,
                                 stop: 0.5 #2c3e50, stop: 1.0 #33495e);
}}

QComboBox:on {{
    padding-top: 3px;
    padding-left: 4px;
}}

QComboBox::drop-down {{
    subcontrol-origin: padding;
    subcontrol-position: top right;
    width: 15px;
    border: none;
}}

QComboBox::down-arrow {{
    image: url({0}/drop_down.png);
}}

QComboBox::down-arrow:on {{
    top: 1px;
    left: 1px;
}}



QCheckBox {{
    spacing: 15px;
    border: none;
    padding: 0px;
    margin-top: 5px;
}}

QCheckBox::indicator {{
    width: 13px;
    height: 13px;
}}

QCheckBox::indicator:unchecked {{
    image: url({0}/checkbox-uncheck.png);
}}

QCheckBox::indicator:checked {{
    image: url({0}/checkbox-check.png);
}}

QHeaderView::section {{
    background-color: #2c3e50;
    padding: 2px;
    color: #cff7f8;
    font: 75 10px "MS Shell Dlg 2";
    border: 1px solid;
    border-top: none;
    border-bottom: none;
    border-color: #34495e;
}}

QTableWidget {{
    background-position: center;
    border:  none;
    padding: 5px;
    margin-left: 1px;
    margin-right: 1px;
    color: #cff7f8;
    font: 8pt "MS Shell Dlg 2";
    background-color: #131d26;
    alternate-background-color: #111b23;
    background-image: url({0}/bg.png);
    background-repeat: no-repeat;
}}

QTableWidget:item:selected {{
    background-color: #192530;
    color: #cff7f8;
}}

QToolTip {{
    background-color: #2c3e50;
    color: #cff7f8;
    border: #2c3e50 solid 1px;
}}

QCalendarWidget QWidget#qt_calendar_navigationbar {{
   background-color: #2c3e50;
}}

QCalendarWidget QToolButton {{
    height: 16px;
    padding: 1px;
    width: 150px;
    color: #c9f5f7;
    font-size: 12px;
    icon-size: 16px, 16px;
    background-color: #2c3e50;
    border: none;
}}

QCalendarWidget QMenu {{
    width: 150px;
    left: 20px;
    color: #c9f5f7;
    font-size: 12px;
    background-color: #2c3e50;
}}

QCalendarWidget QSpinBox {{
    font-size:12px;
    color: white;
    background-color: #2c3e50;
    selection-color: #2c3e50;
}}

QCalendarWidget QSpinBox::up-button {{
    subcontrol-origin: border;  
    subcontrol-position: top right;
    width:12px;
}}

QCalendarWidget QSpinBox::down-button {{
    subcontrol-origin: border;
    subcontrol-position: bottom right;
    width:12px;
}}

QCalendarWidget QSpinBox::up-arrow {{
    width:12px;  
    height:12px;
}}

QCalendarWidget QSpinBox::down-arrow {{
    width:12px;  
    height:12px;
}}
 
QCalendarWidget QWidget {{
    alternate-background-color: #34495e;
}}

QCalendarWidget QAbstractItemView:enabled {{
    font-size:12px;
    color: #c9f5f7;
    background-color: #2c3e50;
    selection-background-color: #34495e;
    selection-color: lime;
}}

QCalendarWidget QAbstractItemView:disabled {{
    color: grey;
}}
 
QProgressBar:horizontal {{
    border: 1px ridge;
    border-color: #2c3e50;
    background-color: #34495e;
    padding: 1px;
    text-align: bottom;
    color: #c9f5f7;
}}
QProgressBar::chunk:horizontal {{
    background: #c9f5f7;
    margin-right: 1px;
    width: 5px;
    color: #c9f5f7;
}}

QGroupBox {{
    border: none;
    padding-top: 8px;
    padding-left: 8px;
    padding-right: 8px;
}}

QGroupBox::title {{
    subcontrol-origin: margin;
    subcontrol-position: top left; /* position at the top center */
    padding: 0 3px;
    color: #bdc3c7;
    background-color: qlineargradient(x1: 0, y1: 0, x2: 0, y2: 1,
        stop: 0 #FFOECE, stop: 1 #FFFFFF);
}}

QMenuBar {{
    background-color: #34495e;
    border: 1px ridge #000;
}}

QMenuBar::item {{
    background-color: #34495e;
    color: #c9f5f7;
    padding: 8px;
}}

QMenuBar::item::selected {{
    background-color: #2c3e50;
}}

QMenuBar::item::checked {{
    border-color: red;
}}

QMenu {{
    background-color: #34495e;
}}

QMenu::item {{
    border: none;
    padding: 5px;
    padding-left: 13px;
    padding-right: 13px;
}}

QMenu::item::selected {{
    background-color: #2c3e50;
    color: #ecf0f1;
    border-bottom: 1px solid #3498db;
}}

QMenu::separator {{
    height: 1px;
    background: #7f8c8d;
    margin-left: 10px;
    margin-right: 5px;
}}

QMenu::indicator {{
    width: 13px;
    height: 13px;
}}

QDockWidget {{
    titlebar-close-icon: url({0}/close_widget.png);
    titlebar-normal-icon: url({0}/undock.png);
}}

QDockWidget::title {{
    border: none;
    text-align: center;
    color: #f1c40f;
    background: #227a5d;
    padding: 5px;
}}

QDockWidget::close-button,
QDockWidget::float-button {{
    border: 1px solid transparent;
    background: #227a5d;
    padding: 0px;
}}

QDockWidget::close-button:hover,
QDockWidget::float-button:hover {{
    background: #227a5d;
}}

QDockWidget::close-button:pressed,
QDockWidget::float-button:pressed {{
    padding: 1px -1px -1px 1px;
}}

QToolButton {{
    background: transparent;
}}

QScrollBar:vertical {{
    border: none;
    background: #131d26;
    width: 15px;
}}

QScrollBar::handle:vertical {{
    background-color: rgb(44, 63, 81, 150);
    min-height: 25px;
}}

QScrollBar::add-line:vertical {{
    border: none;
    background: #131d26;;
    height: 20px;
    subcontrol-position: bottom;
    subcontrol-origin: margin;
}}

QScrollBar::sub-line:vertical {{
    border: none;
    background: #131d26;
    height: 20px;
    subcontrol-position: top;
    subcontrol-origin: margin;
}}


QScrollBar::add-page:vertical, QScrollBar::sub-page:vertical {{
    background: none;
}}

QScrollBar:horizontal {{
    border: none;
    background: #131d26;
    height: 15px;
}}

QScrollBar::handle:horizontal {{
    background-color: rgb(44, 63, 81, 150);
    min-height: 25px;
}}

QScrollBar::add-line:horizontal {{
    border: none;
    background: #131d26;
    width: 20px;
    subcontrol-position: right;
    subcontrol-origin: margin;
 }}

QScrollBar::sub-line:horizontal {{
    border: none;
    background: #131d26;
    width: 20px;
    subcontrol-position: left;
    subcontrol-origin: margin;
}}

QScrollBar::add-page:horizontal, QScrollBar::sub-page:horizontal {{
    background: none;
}}

QSlider::groove:horizontal {{
    border: none;
    background: transparent;
    height: 3px;
}}

QSlider::sub-page:horizontal {{
    background: #3498db;
    border: none;
    height: 3px;
}}

QSlider::add-page:horizontal {{
    background: transparent;
    height: 3px;
}}

QSlider::handle:horizontal {{
    background: #c9f5f7;
    width: 13px;
    margin-top: -2px;
    margin-bottom: -2px;
}}

/*
ICONS
*/

QPushButton[onlineClientsTab="1"] {{
    border: none;
    min-width: 10px;
    background: transparent;
    image: url({0}/online-clients.png);
}}

QPushButton[directClientsTab="1"] {{
    border: none;
    min-width: 10px;
    background: transparent;
    image: url({0}/direct.png);
}}

QPushButton[offlineClientsTab="1"] {{
    border: none;
    min-width: 10px;
    background: transparent;
    image: url({0}/offline-clients.png);
}}

QPushButton[moderatorsTab="1"] {{
    border: none;
    min-width: 10px;
    background: transparent;
    image: url({0}/moderators.png);
}}

QPushButton#appearanceButton {{
    border: none;
    min-width: 10px;
    background: transparent;
    image: url({0}/paint.png);
}}

QPushButton#rserverButton {{
    border: none;
    min-width: 10px;
    background: transparent;
    image: url({0}/connect.png);
}}

QPushButton#dserverButton {{
    border: none;
    min-width: 10px;
    background: transparent;
    image: url({0}/direct.png);
}}

QAction[MVIEWER="1"] {{
    border-image: url({0}/add-file.png);
}}
QAction#MVIEWER {{
    border-image: url({0}/list.png);
}}

/*
MSCRIPT
*/
QToolButton#fromFile {{
    border-image: url({0}/add-file.png);
}}
QToolButton#openList {{
    border-image: url({0}/list.png);
}}
QToolButton#saveScript {{
    border-image: url({0}/save-as.png);
}}

QToolButton#runScript {{
    border-image: url({0}/run-script.png);
}}
QToolButton#runTest {{
    border-image: url({0}/run-test.png);
}}

/*
MEXPLORER
*/
QToolButton#upFolder {{
    border-image: url({0}/add-file.png);
}}
QToolButton#newFile {{
    border-image: url({0}/add-file.png);
}}
QToolButton#newFolder {{
    border-image: url({0}/add-folder.png);
}}

'''.format(icons_path)
