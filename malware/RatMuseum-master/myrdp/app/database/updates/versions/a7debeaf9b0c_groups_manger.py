
"""empty message

Revision ID: a7debeaf9b0c
Revises: cfd366892018
Create Date: 2018-09-15 22:02:31.199186

"""
from alembic import op
import sqlalchemy as sa

from app.log import logger


# revision identifiers, used by Alembic.
revision = 'a7debeaf9b0c'
down_revision = 'cfd366892018'
branch_labels = None
depends_on = None

# from app.database.updates.versions.cfd366892018_encrypt_passwords import hostsHelper
# not imported because there was some problems after freeze, so is just copied :/ yea dirty
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
    logger.info(u"Doing groups manager upgrade.")
    groupsTable = op.create_table(
        'groups',
        sa.Column('id', sa.Integer, primary_key=True),
        sa.Column('name', sa.String, nullable=False, unique=True),
        sa.Column('default_user_name', sa.String),
        sa.Column('default_password', sa.String),
    )

    # Sqlite doesn't support drop column, so upgrade is much more complicated ..
    connection = op.get_bind()
    groupIdToName = dict()
    with connection.begin() as trans:
        for row in connection.execute(sa.select([hostsHelper.c.group]).where(hostsHelper.c.group.isnot(None)).distinct()):
            groupName = row[0]
            result = connection.execute(groupsTable.insert().values(name=groupName))
            groupIdToName[result.lastrowid] = groupName

        for groupId, groupName in groupIdToName.items():
            connection.execute(hostsHelper.update().values(group=groupId).where(hostsHelper.c.group==groupName))

    op.rename_table('hosts', 'hosts_backup')
    op.create_table(
        'hosts',
        sa.Column('id', sa.Integer, primary_key=True),
        sa.Column('name', sa.String, nullable=False, unique=True),
        sa.Column('address', sa.String, nullable=False),
        sa.Column('user', sa.String),
        sa.Column('password', sa.String),
        sa.Column('group', sa.Integer, sa.schema.ForeignKey('groups.id'), nullable=True)
    )

    op.execute("INSERT INTO hosts SELECT * FROM hosts_backup;")
    op.drop_table('hosts_backup')


def downgrade():
    raise NotImplemented("Downgrade not supported")
