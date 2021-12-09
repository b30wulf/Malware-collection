remote {$config.ip} {$config.port}

proto udp
dev tun

nobind

tun-mtu 1500
client
auth SHA1

persist-key
persist-tun

tls-client
tls-timeout 120
tls-auth "{$config.ip}/ta.key" 1

ns-cert-type server
resolv-retry infinite

ca "{$config.ip}/ca.crt"
cert "{$config.ip}/{$client->name}.crt"
key "{$config.ip}/{$client->name}.key"

cipher AES-256-CBC

redirect-gateway
comp-lzo
verb 3
mute 10

ping 100
ping-restart 150