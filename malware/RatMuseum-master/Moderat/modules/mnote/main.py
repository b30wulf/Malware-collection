# -*- coding: utf-8 -*-
# Author: Milan Nikolic <gen2brain@gmail.com>
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

import os
import sys
from libs.gui.loading import Loading

try:
    from PyQt4.QtGui import *
    from PyQt4.QtCore import *
    from PyQt4.QtWebKit import *
except ImportError, err:
    sys.stderr.write("Error: %s%s" % (str(err), os.linesep))
    sys.exit(1)

from ui.htmleditor_ui import Ui_MainWindow


class mainPopup(QMainWindow, Ui_MainWindow):

    def __init__(self, args):
        QMainWindow.__init__(self, None)
        self.setupUi(self)
        self.resize(800, 600)
        self.moderat = args['moderat']
        self.moderat = args['moderat']
        self.client = args['client']
        self.module_id = args['module_id']
        self.p2p = args['p2p']
        self.alias = args['alias']
        self.ip_address = args['ip_address']

        self.information = ''
        self.loading = Loading(self.webView)

        spacer = QWidget(self)
        spacer.setProperty('spacer', '1')
        spacer.setSizePolicy(QSizePolicy.Expanding, QSizePolicy.Minimum)
        self.standardToolBar.insertWidget(self.actionZoomOut, spacer)

        self.zoomLabel = QLabel()
        self.standardToolBar.insertWidget(self.actionZoomOut, self.zoomLabel)

        self.zoomSlider = QSlider(self)
        self.zoomSlider.setOrientation(Qt.Horizontal)
        self.zoomSlider.setMaximumWidth(150)
        self.zoomSlider.setRange(25, 400)
        self.zoomSlider.setSingleStep(25)
        self.zoomSlider.setPageStep(100)
        self.connect(self.zoomSlider,
                SIGNAL("valueChanged(int)"), self.changeZoom)
        self.standardToolBar.insertWidget(self.actionZoomIn, self.zoomSlider)

        self.connect(self.actionExit,
                SIGNAL("triggered()"), SLOT("close()"))
        self.connect(self.actionSave,
                SIGNAL("triggered()"), self.fileSave)
        self.connect(self.actionZoomOut,
                SIGNAL("triggered()"), self.zoomOut)
        self.connect(self.actionZoomIn,
                SIGNAL("triggered()"), self.zoomIn)

        # these are forward to internal QWebView
        self._forward_action(self.actionEditUndo, QWebPage.Undo)
        self._forward_action(self.actionEditRedo, QWebPage.Redo)
        self._forward_action(self.actionEditCut, QWebPage.Cut)
        self._forward_action(self.actionEditCopy, QWebPage.Copy)
        self._forward_action(self.actionEditPaste, QWebPage.Paste)
        self._forward_action(self.actionFormatBold, QWebPage.ToggleBold)
        self._forward_action(self.actionFormatItalic, QWebPage.ToggleItalic)
        self._forward_action(self.actionFormatUnderline, QWebPage.ToggleUnderline)

        # Qt 4.5.0 has a bug: always returns 0 for QWebPage::SelectAll
        self.connect(self.actionEditSelectAll,
                SIGNAL("triggered()"), self.editSelectAll)

        self.connect(self.actionStyleParagraph,
                SIGNAL("triggered()"), self.styleParagraph)
        self.connect(self.actionStyleHeading1,
                SIGNAL("triggered()"), self.styleHeading1)
        self.connect(self.actionStyleHeading2,
                SIGNAL("triggered()"), self.styleHeading2)
        self.connect(self.actionStyleHeading3,
                SIGNAL("triggered()"), self.styleHeading3)
        self.connect(self.actionStyleHeading4,
                SIGNAL("triggered()"), self.styleHeading4)
        self.connect(self.actionStyleHeading5,
                SIGNAL("triggered()"), self.styleHeading5)
        self.connect(self.actionStyleHeading6,
                SIGNAL("triggered()"), self.styleHeading6)
        self.connect(self.actionStylePreformatted,
                SIGNAL("triggered()"), self.stylePreformatted)
        self.connect(self.actionStyleAddress,
                SIGNAL("triggered()"), self.styleAddress)
        self.connect(self.actionFormatFontName,
                SIGNAL("triggered()"), self.formatFontName)
        self.connect(self.actionFormatFontSize,
                SIGNAL("triggered()"), self.formatFontSize)
        self.connect(self.actionFormatTextColor,
                SIGNAL("triggered()"), self.formatTextColor)
        self.connect(self.actionFormatBackgroundColor,
                SIGNAL("triggered()"), self.formatBackgroundColor)

        # no page action exists yet for these, so use execCommand trick
        self.connect(self.actionFormatStrikethrough,
                SIGNAL("triggered()"), self.formatStrikeThrough)
        self.connect(self.actionFormatAlignLeft,
                SIGNAL("triggered()"), self.formatAlignLeft)
        self.connect(self.actionFormatAlignCenter,
                SIGNAL("triggered()"), self.formatAlignCenter)
        self.connect(self.actionFormatAlignRight,
                SIGNAL("triggered()"), self.formatAlignRight)
        self.connect(self.actionFormatAlignJustify,
                SIGNAL("triggered()"), self.formatAlignJustify)
        self.connect(self.actionFormatDecreaseIndent,
                SIGNAL("triggered()"), self.formatDecreaseIndent)
        self.connect(self.actionFormatIncreaseIndent,
                SIGNAL("triggered()"), self.formatIncreaseIndent)
        self.connect(self.actionFormatNumberedList,
                SIGNAL("triggered()"), self.formatNumberedList)
        self.connect(self.actionFormatBulletedList,
                SIGNAL("triggered()"), self.formatBulletedList)

        # necessary to sync our actions
        self.connect(self.webView.page(),
                SIGNAL("selectionChanged()"), self.adjustActions)

        self.connect(self.webView.page(),
                SIGNAL("contentsChanged()"), self.adjustSource)
        self.webView.setFocus()

        self.setWindowTitle(self.client)
        self.InitInfo()

        self.adjustActions()
        self.adjustSource()
        self.setWindowModified(False)
        self.changeZoom(100)

        self.run_command()

    def signal(self, data):
        self.callBack(data)

    def recv_output(self, data):
        self.information = '' if data['payload'] == 'None' else data['payload']
        self.InitInfo()
        self.loading.hide()

    def run_command(self):
        self.moderat.send_message(self.client, 'getNote',
                 session_id=self.moderat.session_id, _to=self.client, module_id=self.module_id)
        self.callBack = self.recv_output

    def _forward_action(self, action1, action2):
        self.connect(action1,
                SIGNAL("triggered()"), self.webView.pageAction(action2), SLOT("trigger()"))
        self.connect(self.webView.pageAction(action2),
                SIGNAL("changed()"), self.adjustActions)

    def _follow_enable(self, a1, a2):
        a1.setEnabled(self.webView.pageAction(a2).isEnabled())

    def _follow_check(self, a1, a2):
        a1.setChecked(self.webView.pageAction(a2).isChecked())

    def maybeSave(self):
        if not self.isWindowModified():
            return True
        ret = QMessageBox.warning(self, self.moderat.MString('NOTE_TITLE'),
                self.moderat.MString('NOTE_WARNING_MSG'), QMessageBox.Save|QMessageBox.Discard|QMessageBox.Cancel)
        if ret == QMessageBox.Save:
            return self.fileSave()
        elif ret == QMessageBox.Cancel:
            return False
        return True

    def InitInfo(self):
        if self.maybeSave():
            self.webView.setHtml(u'<p style="color: #c9f5f7;">{}</p>'.format(
                self.information if len(self.information) > 0 else 'No Information'
            ))
            self.webView.setFocus()
            self.webView.page().setContentEditable(True)
            self.setWindowModified(False)

            # quirk in QWebView: need an initial mouse click to show the cursor
            mx = self.webView.width() / 2
            my = self.webView.height() / 2
            center = QPoint(mx, my)
            e1 = QMouseEvent(QEvent.MouseButtonPress, center,
                    Qt.LeftButton, Qt.LeftButton, Qt.NoModifier)
            e2 = QMouseEvent(QEvent.MouseButtonRelease, center,
                    Qt.LeftButton, Qt.LeftButton, Qt.NoModifier)
            QApplication.postEvent(self.webView, e1)
            QApplication.postEvent(self.webView, e2)

    def fileSave(self):
        content = self.webView.page().mainFrame().toHtml()
        data = content
        self.moderat.moderator.send_msg(u'{}%SPLITTER%{}'.format(self.client, data), 'saveNote', session_id=self.moderat.session_id)
        self.run_command()
        self.setWindowModified(False)
        return True

    def zoomOut(self):
        percent = self.webView.zoomFactor() * 100
        if percent > 25:
            percent -= 25
            percent = 25 * ((percent + 25 - 1) // 25)
            factor = percent / 100
            self.webView.setZoomFactor(factor)
            self.actionZoomOut.setEnabled(percent > 25)
            self.actionZoomIn.setEnabled(True)
            self.zoomSlider.setValue(percent)

    def zoomIn(self):
        percent = self.webView.zoomFactor() * 100
        if percent < 400:
            percent += 25
            percent = 25 * (percent // 25)
            factor = percent / 100
            self.webView.setZoomFactor(factor)
            self.actionZoomIn.setEnabled(percent < 400)
            self.actionZoomOut.setEnabled(True)
            self.zoomSlider.setValue(percent)

    def editSelectAll(self):
        self.webView.triggerPageAction(QWebPage.SelectAll)

    def execCommand(self, cmd, arg=None):
        frame = self.webView.page().mainFrame()
        if arg:
            js = 'document.execCommand("{}", false, "{}")'.format(cmd, arg)
        else:
            js = 'document.execCommand("{}", false, null)'.format(cmd)
        frame.evaluateJavaScript(js)

    def queryCommandState(self, cmd):
        frame = self.webView.page().mainFrame()
        js = 'document.queryCommandState("%1", false, null)'.format(cmd)
        result = frame.evaluateJavaScript(js)
        return str(result).lower() == "true"

    def styleParagraph(self):
        self.execCommand("formatBlock", "p")

    def styleHeading1(self):
        self.execCommand("formatBlock", "h1")

    def styleHeading2(self):
        self.execCommand("formatBlock", "h2")

    def styleHeading3(self):
        self.execCommand("formatBlock", "h3")

    def styleHeading4(self):
        self.execCommand("formatBlock", "h4")

    def styleHeading5(self):
        self.execCommand("formatBlock", "h5")

    def styleHeading6(self):
        self.execCommand("formatBlock", "h6")

    def stylePreformatted(self):
        self.execCommand("formatBlock", "pre")

    def styleAddress(self):
        self.execCommand("formatBlock", "address")

    def formatStrikeThrough(self):
        self.execCommand("strikeThrough")

    def formatAlignLeft(self):
        self.execCommand("justifyLeft")

    def formatAlignCenter(self):
        self.execCommand("justifyCenter")

    def formatAlignRight(self):
        self.execCommand("justifyRight")

    def formatAlignJustify(self):
        self.execCommand("justifyFull")

    def formatIncreaseIndent(self):
        self.execCommand("indent")

    def formatDecreaseIndent(self):
        self.execCommand("outdent")

    def formatNumberedList(self):
        self.execCommand("insertOrderedList")

    def formatBulletedList(self):
        self.execCommand("insertUnorderedList")

    def formatFontName(self):
        families = QFontDatabase().families()
        family = QInputDialog.getItem(self, self.tr("Font"), self.tr("Select font:"),
                families, 0, False)[0]
        self.execCommand("fontName", family)

    def formatFontSize(self):
        sizes = ["xx-small","x-small","small","medium","large","x-large","xx-large"]
        size = QInputDialog.getItem(self, self.tr("Font Size"), self.tr("Select font size:"),
                sizes, sizes.index("medium"), False)[0]

        self.execCommand("fontSize", int(sizes.index(size)))

    def formatTextColor(self):
        color = QColorDialog.getColor(Qt.green, self)
        if color.isValid():
            self.execCommand("foreColor", color.name())

    def formatBackgroundColor(self):
        color = QColorDialog.getColor(Qt.white, self)
        if color.isValid():
            self.execCommand("hiliteColor", color.name())

    def adjustActions(self):
        self._follow_enable(self.actionEditUndo, QWebPage.Undo)
        self._follow_enable(self.actionEditRedo, QWebPage.Redo)
        self._follow_enable(self.actionEditCut, QWebPage.Cut)
        self._follow_enable(self.actionEditCopy, QWebPage.Copy)
        self._follow_enable(self.actionEditPaste, QWebPage.Paste)
        self._follow_check(self.actionFormatBold, QWebPage.ToggleBold)
        self._follow_check(self.actionFormatItalic, QWebPage.ToggleItalic)
        self._follow_check(self.actionFormatUnderline, QWebPage.ToggleUnderline)

        self.actionFormatStrikethrough.setChecked(
                self.queryCommandState("strikeThrough"))
        self.actionFormatNumberedList.setChecked(
                self.queryCommandState("insertOrderedList"))
        self.actionFormatBulletedList.setChecked(
                self.queryCommandState("insertUnorderedList"))

    def adjustSource(self):
        self.setWindowModified(True)
        self.sourceDirty = True

    def changeTab(self, index):
        if self.sourceDirty and index == 1:
            content = self.webView.page().mainFrame().toHtml()
            self.plainTextEdit.setPlainText(content)
            self.sourceDirty = False

    def openLink(self, url):
        msg = "Open {} ?".format(url.toString())
        if (QMessageBox.question(self, self.tr("Open link"), msg,
            QMessageBox.Open|QMessageBox.Cancel)) == QMessageBox.Open:
            QDesktopServices.openUrl(url)

    def changeZoom(self, percent):
        self.actionZoomOut.setEnabled(percent > 25)
        self.actionZoomIn.setEnabled(percent < 400)
        factor = float(percent) / 100
        self.webView.setZoomFactor(factor)

        self.zoomLabel.setText(u'{}: {}%'.format(self.moderat.MString('NOTE_ZOOM'), percent))
        self.zoomSlider.setValue(percent)

    def resizeEvent(self, event):
        self.loading.resize(self.webView.size())
        event.accept()

    def closeEvent(self, event):
        if self.maybeSave():
            event.accept()
        else:
            event.ignore()