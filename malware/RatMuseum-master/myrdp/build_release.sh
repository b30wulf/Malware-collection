#!/usr/bin/env bash
vagrant up
vagrant ssh -c "cd /home/vagrant/myrdp && /home/vagrant/myrdp/freeze.sh"
vagrant halt