from contextlib import contextmanager as _contextmanager

from fabric.api import run
from fabric.context_managers import cd, prefix
from fabric.contrib.project import rsync_project
from fabric.state import env


EXCLUDES = ('fabfile.*', 'deploy_sell.*', '**.swp', '.git*', 'out')

TARGET_DIR = 'apps/smsg_r'
env.user = 'admin'
env.shell = "scl enable python27"
env.use_ssh_config = True
env.activate = 'source /home/admin/virtualenv/django/bin/activate'


@_contextmanager
def virtualenv():
    with cd(TARGET_DIR), prefix(env.activate):
        yield


def server_reload():
    run("touch apps/uwsgi.ctl")


def install_requirements():
    with virtualenv():
        run("pip install -r requirements.txt")


def sync_source():
    extra_opts = "--exclude-from=.gitignore"
    run("mkdir -p %s" % TARGET_DIR)
    rsync_project(remote_dir=TARGET_DIR, local_dir="./", exclude=EXCLUDES, delete=True, extra_opts=extra_opts)


def migrate():
    with virtualenv():
        run("python manage.py migrate")


def collectstatic():
    with virtualenv():
        run("python manage.py collectstatic --noinput")


def setpassword():
    with virtualenv():
        run("python manage.py changepassword")


def deploy():
    sync_source()
    install_requirements()
    collectstatic()
    migrate()
    server_reload()
