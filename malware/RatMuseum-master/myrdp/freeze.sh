#!/usr/bin/env bash
rm build/ -rf
rm dist/ -rf
. /opt/python2-venvs/myrdp/bin/activate
export VERSION=`python2 -c "import app; print app.__version__"`
export VERSIONED_NAME=myrdp-$VERSION
pyinstaller myrdp.spec
cd dist
mv myrdp $VERSIONED_NAME
tar -jcvf $VERSIONED_NAME.tar.bz2 $VERSIONED_NAME
