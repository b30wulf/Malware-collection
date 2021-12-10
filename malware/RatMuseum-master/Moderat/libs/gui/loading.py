import math
from PyQt4.QtCore import Qt
from PyQt4.QtGui import *


class Loading(QWidget):
    def __init__(self, parent=None):

        QWidget.__init__(self, parent)
        palette = QPalette(self.palette())
        palette.setColor(palette.Background, Qt.transparent)
        self.setPalette(palette)

    def paintEvent(self, event):

        painter = QPainter()
        painter.begin(self)
        painter.setRenderHint(QPainter.Antialiasing)
        painter.fillRect(event.rect(), QBrush(QColor(0, 0, 0, 70)))
        painter.setPen(QPen(Qt.NoPen))

        for i in range(6):
            if (self.counter / 5) % 6 == i:
                painter.setBrush(QBrush(QColor('#32475b')))
            elif (self.counter / 4) % 6 == i:
                painter.setBrush(QBrush(QColor('#364d63')))
            elif (self.counter / 3) % 6 == i:
                painter.setBrush(QBrush(QColor('#3c546d')))
            elif (self.counter / 2) % 6 == i:
                painter.setBrush(QBrush(QColor('#415c77')))
            else:
                painter.setBrush(QBrush(QColor('#709ecc')))
            painter.drawEllipse(
                self.width() / 2 + 30 * math.cos(2 * math.pi * i / 6.0) - 10,
                self.height() / 2 + 30 * math.sin(2 * math.pi * i / 6.0) - 10,
                20, 20)

        painter.end()

    def showEvent(self, event):

        self.timer = self.startTimer(50)
        self.counter = 0

    def timerEvent(self, event):

        self.counter += 1
        self.update()