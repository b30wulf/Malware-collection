from PyQt4 import QtGui, QtCore

class CompletionTextEdit(QtGui.QTextEdit):
    def __init__(self, parent=None):
        super(CompletionTextEdit, self).__init__(parent)
        self.setMinimumWidth(400)
        self.completer = None
        self.moveCursor(QtGui.QTextCursor.End)

    def setCompleter(self, completer):
        if self.completer:
            self.disconnect(self.completer, 0, self, 0)
        if not completer:
            return

        completer.setWidget(self)
        completer.setCompletionMode(QtGui.QCompleter.PopupCompletion)
        completer.setCaseSensitivity(QtCore.Qt.CaseInsensitive)
        self.completer = completer
        self.connect(self.completer,
            QtCore.SIGNAL("activated(const QString&)"), self.insertCompletion)

    def insertCompletion(self, completion):
        tc = self.textCursor()
        extra = (completion.length() -
            self.completer.completionPrefix().length())
        tc.movePosition(QtGui.QTextCursor.Left)
        tc.movePosition(QtGui.QTextCursor.EndOfWord)
        tc.insertText(completion.right(extra))
        self.setTextCursor(tc)

    def textUnderCursor(self):
        tc = self.textCursor()
        tc.select(QtGui.QTextCursor.WordUnderCursor)
        return tc.selectedText()

    def focusInEvent(self, event):
        if self.completer:
            self.completer.setWidget(self);
        QtGui.QTextEdit.focusInEvent(self, event)

    def keyPressEvent(self, event):
        if self.completer and self.completer.popup().isVisible():
            if event.key() in (
            QtCore.Qt.Key_Enter,
            QtCore.Qt.Key_Return,
            QtCore.Qt.Key_Escape,
            QtCore.Qt.Key_Tab,
            QtCore.Qt.Key_Backtab):
                event.ignore()
                return

        ## has ctrl-E been pressed??
        isShortcut = (event.modifiers() == QtCore.Qt.ControlModifier and
                      event.key() == QtCore.Qt.Key_Space)
        if (not self.completer or not isShortcut):
            QtGui.QTextEdit.keyPressEvent(self, event)

        ## ctrl or shift key on it's own??
        ctrlOrShift = event.modifiers() in (QtCore.Qt.ControlModifier ,
                QtCore.Qt.ShiftModifier)
        if ctrlOrShift and event.text().isEmpty():
            # ctrl or shift key on it's own
            return

        eow = QtCore.QString("~!@#$%^&*()_+{}|:\"<>?,./;'[]\\-=") #end of word

        hasModifier = ((event.modifiers() != QtCore.Qt.NoModifier) and
                        not ctrlOrShift)

        completionPrefix = self.textUnderCursor()

        if (not isShortcut and (hasModifier or event.text().isEmpty() or
        completionPrefix.length() < 1 or
        eow.contains(event.text().right(1)))):
            self.completer.popup().hide()
            return

        if (completionPrefix != self.completer.completionPrefix()):
            self.completer.setCompletionPrefix(completionPrefix)
            popup = self.completer.popup()
            popup.setStyleSheet('background-color: #455F7A;\ncolor: #c9f5f7;\n')
            popup.setCurrentIndex(
                self.completer.completionModel().index(0,0))

        cr = self.cursorRect()
        cr.setWidth(self.completer.popup().sizeHintForColumn(0)
            + self.completer.popup().verticalScrollBar().sizeHint().width())
        self.completer.complete(cr)