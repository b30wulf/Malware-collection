#!/bin/bash

cd {$config['esa']}

./vars

source ./vars

export KEY_EXPIRE="{$smarty.post.days}"
export KEY_CN="{$smarty.post.name}"
export KEY_NAME="{$smarty.post.name}"
export KEY_OU="{$smarty.post.name}"
export EASY_RSA="{$config['esa']}"

$EASY_RSA/pkitool --batch $*