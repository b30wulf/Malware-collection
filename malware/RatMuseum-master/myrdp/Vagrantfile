# virtual machine to build releases

Vagrant.configure("2") do |config|
    config.vm.box = "ubuntu/xenial64"
    config.vm.synced_folder ".", "/home/vagrant/myrdp"

    config.vm.provision :salt do |salt|
        salt.masterless = true
        salt.minion_config = "salt/minion"
        salt.run_highstate = true
    end
end