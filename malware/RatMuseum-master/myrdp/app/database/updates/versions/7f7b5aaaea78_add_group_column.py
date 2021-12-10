"""add group column

Revision ID: 7f7b5aaaea78
Revises: 
Create Date: 2017-03-17 19:05:51.167255

"""
from alembic import op
import sqlalchemy as sa


# revision identifiers, used by Alembic.
revision = '7f7b5aaaea78'
down_revision = None
branch_labels = None
depends_on = None


def upgrade():
    op.add_column('hosts', sa.Column('group', sa.String))


def downgrade():
    raise NotImplemented("Sqlite doesn't support drop column in simple way")
    # op.drop_column('hosts', 'group')
