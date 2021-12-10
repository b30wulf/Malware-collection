from PyQt4.QtGui import *
from libs.moderat.Decorators import *


class Pagination:

    def __init__(self, moderat):

        self.moderat = moderat

        self.all_pages = 0
        self.current_page = 0
        self.all_offline_pages = 0
        self.current_offline_page = 0
        HSpacer = QSpacerItem(20, 40, QSizePolicy.Maximum, QSizePolicy.Maximum)

        self.pagesLayout = QHBoxLayout()
        self.pagesLayout.addItem(HSpacer)
        self.paginationLayout = QHBoxLayout()
        self.pagesLayout.addLayout(self.paginationLayout)
        self.prev_page_button = QPushButton(self.moderat.MString('PAGINATION_PREV'))
        self.prev_page_button.setMaximumWidth(70)
        self.prev_page_button.clicked.connect(lambda: self.prev_page())
        self.curr_page_line = QLineEdit('0')
        self.curr_page_line.setMaximumWidth(50)
        self.curr_page_line.textChanged.connect(lambda: self.set_page(self.curr_page_line.text()))
        self.all_page_label = QLabel('/ 0')
        self.all_page_label.setSizePolicy(QSizePolicy.Fixed, QSizePolicy.Fixed)
        self.next_page_button = QPushButton(self.moderat.MString('PAGINATION_NEXT'))
        self.next_page_button.setMaximumWidth(70)
        self.next_page_button.clicked.connect(lambda: self.next_page())
        self.paginationLayout.addWidget(self.prev_page_button)
        self.paginationLayout.addWidget(self.curr_page_line)
        self.paginationLayout.addWidget(self.all_page_label)
        self.paginationLayout.addWidget(self.next_page_button)
        self.moderat.verticalLayout.addLayout(self.pagesLayout)

        self.offline_pages = {}
        self.offlinePagesLayout = QHBoxLayout()
        self.offlinePagesLayout.addItem(HSpacer)
        self.offlinePaginationLayout = QHBoxLayout()
        self.offlinePagesLayout.addLayout(self.offlinePaginationLayout)

        self.prev_off_page_button = QPushButton(self.moderat.MString('PAGINATION_PREV'))
        self.prev_off_page_button.setMaximumWidth(70)
        self.prev_off_page_button.clicked.connect(lambda: self.prev_off_page())
        self.curr_off_page_line = QLineEdit('0')
        self.curr_off_page_line.setMaximumWidth(50)
        self.curr_off_page_line.textChanged.connect(lambda: self.set_offline_page(self.curr_off_page_line.text()))
        self.all_off_page_label = QLabel('/ 0')
        self.all_off_page_label.setSizePolicy(QSizePolicy.Fixed, QSizePolicy.Fixed)
        self.next_off_page_button = QPushButton(self.moderat.MString('PAGINATION_NEXT'))
        self.next_off_page_button.setMaximumWidth(70)
        self.next_off_page_button.clicked.connect(lambda: self.next_off_page())
        self.offlinePaginationLayout.addWidget(self.prev_off_page_button)
        self.offlinePaginationLayout.addWidget(self.curr_off_page_line)
        self.offlinePaginationLayout.addWidget(self.all_off_page_label)
        self.offlinePaginationLayout.addWidget(self.next_off_page_button)

        self.offlinePagesLayout.addItem(HSpacer)
        self.moderat.verticalLayout_2.addLayout(self.offlinePagesLayout)

    def add_pages(self, number_of_pages):
        self.all_pages = number_of_pages
        self.all_page_label.setText('/ {}'.format(self.all_pages))
        if number_of_pages > 0 and self.current_page == 0:
            self.current_page = 1
        self.set_page(self.current_page)

    @update_clients
    def prev_page(self):
        page = self.curr_page_line.text()
        try:
            page_n = int(page)
        except ValueError:
            return
        self.set_page(page_n - 1)
        self.moderat.tables.update_clients()

    @update_clients
    def next_page(self):
        page = self.curr_page_line.text()
        try:
            page_n = int(page)
        except ValueError:
            return
        self.set_page(page_n + 1)

    def set_page(self, page_n):
        try:
            page_n = int(page_n)
            self.current_page = page_n
            self.prev_page_button.setDisabled(page_n == 1)
            self.next_page_button.setDisabled(page_n == self.all_pages)
            if page_n > self.all_pages:
                page_n = self.all_pages
                self.set_page(page_n)
        except ValueError:
            pass
        self.curr_page_line.setText(str(page_n))

    def add_offline_pages(self, number_of_pages):
        self.all_offline_pages = number_of_pages
        self.all_off_page_label.setText('/ {}'.format(self.all_offline_pages))
        if number_of_pages > 0 and self.current_offline_page == 0:
            self.current_offline_page = 1
        self.set_offline_page(self.current_offline_page)

    @update_clients
    def prev_off_page(self):
        page = self.curr_off_page_line.text()
        try:
            page_n = int(page)
        except ValueError:
            return
        self.set_offline_page(page_n - 1)

    @update_clients
    def next_off_page(self):
        page = self.curr_off_page_line.text()
        try:
            page_n = int(page)
        except ValueError:
            return
        self.set_offline_page(page_n + 1)

    def set_offline_page(self, page_n):
        try:
            page_n = int(page_n)
            self.current_offline_page = page_n
            self.prev_off_page_button.setDisabled(page_n == 1)
            self.next_off_page_button.setDisabled(page_n == self.all_offline_pages)
            if page_n > self.all_offline_pages:
                page_n = self.all_offline_pages
                self.set_offline_page(page_n)
        except ValueError:
            pass
        self.curr_off_page_line.setText(str(page_n))

    def clear_pages(self):
        self.current_page = 0
        self.current_offline_page = 0
        self.add_pages(0)
        self.add_offline_pages(0)