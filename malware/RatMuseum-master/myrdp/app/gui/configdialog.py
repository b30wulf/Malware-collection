# -*- coding: utf-8 -*-
from PyQt4.QtGui import QComboBox, QDialog


class ConfigDialog(QDialog):
    def __init__(self, configObject, ui_dialogConfig, attributes, optionalAttributes):
        super(ConfigDialog, self).__init__()
        self.ui = ui_dialogConfig()
        self.ui.setupUi(self)

        self.configObject = configObject
        self.attributes = attributes
        self.optionalAttributes = optionalAttributes

    def getTextFieldValue(self, field):
        """ field value or None
        :param field: object id
        :return: value or None
        """
        fieldObject = getattr(self.ui, field)
        if not isinstance(fieldObject, QComboBox):
            value = fieldObject.text()
        else:
            value = fieldObject.lineEdit().text()
        if value == '':
            if field not in self.optionalAttributes:
                raise ValueError(u"Complete the required fields")
            return None
        return unicode(value)

    def collectFieldsValues(self):
        attributesDict = {}
        for attr in self.attributes:
            attributesDict[attr] = self.getTextFieldValue(attr)
        return attributesDict

    def setErrorLabel(self, text):
        self.ui.informationLabel.setText(text)

    def _execDialog(self):
        """
        :return: dictionary {
            "code": return code,
            "name": host name if host should be connected
            }
        """
        response = dict()
        retCode = self.exec_()
        response["code"] = retCode

        return response

    def setInputValues(self, values):
        for attribute in self.attributes:
            field = getattr(self.ui, attribute)
            value = values.get(attribute)

            if value is None:
                value = ''

            field.setText(value)

    def add(self):
        self.ui.buttonBox.accepted.connect(lambda: self._accept("create"))
        return self._execDialog()

    def edit(self, elementName):
        values = self.configObject.getFormattedValues(elementName, self.attributes)
        self.setInputValues(values)
        self.ui.buttonBox.accepted.connect(lambda: self._accept("update", elementName))
        return self._execDialog()

    def _accept(self, action, elementName=None):
        try:
            attributesDict = self.collectFieldsValues()
            if action == "create":
                self.configObject.create(**attributesDict)
            elif action == "update":
                self.configObject.updateValues(elementName, attributesDict)
            else:
                raise NotImplementedError("Not supported action")
        except Exception as e:
            self.setErrorLabel(e.message)
        else:
            self.accept()
