from filterWindow import FilterWindow


class Filter:

    def __init__(self, moderat):

        self.moderat = moderat
        self.moderat.filters = {}
        self.setDefaultFilters()

    def setDefaultFilters(self):
        self.moderat.filters = {}

    def handlePopup(self):
        self.filter = FilterWindow(self.moderat, self.moderat.filters)
        self.filter.show()
