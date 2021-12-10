# -*- coding: utf-8 -*-
"""encrypt passwords

Revision ID: cfd366892018
Revises: 7f7b5aaaea78
Create Date: 2018-02-25 18:52:09.840857

"""
from alembic import op
import sqlalchemy as sa

from app.config import Config
from app.log import logger

# revision identifiers, used by Alembic.
revision = 'cfd366892018'
down_revision = '7f7b5aaaea78'
branch_labels = None
depends_on = None

hostsHelper = sa.Table(
    'hosts',
    sa.MetaData(),
    sa.Column('id', sa.Integer, primary_key=True),
    sa.Column('address', sa.String, nullable=False),
    sa.Column('name', sa.String),
    sa.Column('user', sa.String),
    sa.Column('password', sa.String),
    sa.Column('group', sa.String)
)


def upgrade():
    cryptoKey = Config().getPrivateKey()
    connection = op.get_bind()

    with connection.begin() as trans:
        for host in connection.execute(hostsHelper.select().where(hostsHelper.c.password.isnot(None))):
            try:
                passwordToUpdate = cryptoKey.encrypt(host.password)
                connection.execute(
                    hostsHelper.update().where(
                        hostsHelper.c.id == host.id
                    ).values(password=passwordToUpdate)
                )
            except Exception as e:
                logger.error(u"Error when trying to update host '{}'.".format(host.name))
                raise e


def downgrade():
    raise NotImplemented("Downgrade not supported")
