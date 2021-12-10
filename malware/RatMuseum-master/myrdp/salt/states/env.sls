packages:
  pkg.installed:
    {% if grains['os'] == 'Ubuntu' %}
    - names:
      - binutils
      - python-qt4
      - python-virtualenv
      - python-dev
      - kde-style-breeze-qt4  # additional style
    {% elif grains['os'] == 'Arch' %}
    - names:
      - python2-pyqt4
    {% endif %}

/opt/python2-venvs/myrdp:
  virtualenv.managed:
    - system_site_packages: False
    - requirements: /home/vagrant/myrdp/requirements-freeze.txt

pyqt4:
  module.run:
    - name: file.copy
    {% if grains['os'] == 'Ubuntu' %}
    - src: /usr/lib/python2.7/dist-packages/PyQt4
    {% elif grains['os'] == 'Arch' %}
    - src: /usr/lib/python2.7/site-packages/PyQt4
    {% endif %}
    - dst: /opt/python2-venvs/myrdp/lib/python2.7/site-packages/PyQt4
    - recurse: True
    - remove_existing: True

sip:
  cmd.run:
    {% if grains['os'] == 'Ubuntu' %}
    - name: cp /usr/lib/python2.7/dist-packages/sip* /opt/python2-venvs/myrdp/lib/python2.7/site-packages
    {% elif grains['os'] == 'Arch' %}
    - name: cp /usr/lib/python2.7/site-packages/sip* /opt/python2-venvs/myrdp/lib/python2.7/site-packages
    {% endif %}
