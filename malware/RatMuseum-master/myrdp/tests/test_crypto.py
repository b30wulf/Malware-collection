# -*- coding: utf-8 -*-
from unittest import TestCase
from app.crypto import CryptoKey


class CryptoTestCase(TestCase):

    testMessage = "some_message_!@#$%^*&'"

    def test_message(self):
        ck = CryptoKey()
        encryptedMsg = ck.encrypt(self.testMessage)
        self.assertEqual(ck.decrypt(encryptedMsg), self.testMessage)

    def test_changeKeyPassphrase(self):
        ck1 = CryptoKey(passphrase="pass")
        encryptedMessage = ck1.encrypt(self.testMessage)
        key = ck1.export("otherPass")

        ck2 = CryptoKey(key, "otherPass")
        self.assertEqual(self.testMessage, ck2.decrypt(encryptedMessage))

    def test_wrongPassword(self):
        ck1 = CryptoKey()
        key = ck1.export("somePass")
        self.assertRaises(ValueError, CryptoKey, key, "wrongPass")

    def test_decryptDifferentKey(self):
        ck1 = CryptoKey()
        ck2 = CryptoKey()
        encryptedMessage = ck1.encrypt(self.testMessage)
        self.assertRaises(ValueError, ck2.decrypt, encryptedMessage)

    def test_unicode(self):
        unicodeMessage = u"łśźćżów~!@#$ '\"%^&*("
        ck = CryptoKey()
        encryptedMsg = ck.encrypt(unicodeMessage)
        self.assertEqual(ck.decrypt(encryptedMsg), unicodeMessage)

    def test_unicodePassphrase(self):
        unicodePassphrase = u"łśźćżów~!@#$ '\"%^&*("
        ck = CryptoKey()
        msg = ck.encrypt(self.testMessage)
        key = ck.export(unicodePassphrase)

        ck2 = CryptoKey(key, unicodePassphrase)
        msg2 = ck2.encrypt(self.testMessage)
        self.assertEqual(ck.decrypt(msg), ck2.decrypt(msg2))
