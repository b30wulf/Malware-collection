import languages


class Translate:

    def __init__(self, moderat):
        self.moderat = moderat
        if self.moderat.settings.moderatLanguage in languages.__all__:
            lang = __import__('libs.languages.%s' % self.moderat.settings.moderatLanguage, globals(), locals(), ['tr'], -1)
            self.tr = lang.tr
        else:
            self.tr = {}

    def word(self, _word):
        if self.tr.has_key(_word):
            return self.tr[_word]
        else:
            return _word