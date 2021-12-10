import themes


class Theme:

    def __init__(self, moderat):
        self.moderat = moderat
        if self.moderat.settings.moderatTheme in themes.__all__:
            theme = __import__('libs.themes.{0}.{0}'.format(self.moderat.settings.moderatTheme), globals(), locals(), ['stylesheet'], -1)
            self.stylesheet = theme.stylesheet
        else:
            self.stylesheet = ''
