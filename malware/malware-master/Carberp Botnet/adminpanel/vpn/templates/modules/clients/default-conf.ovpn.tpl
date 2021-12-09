remote {$smarty.post.ip} {$smarty.post.port}

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
tls-auth "{$smarty.post.ip}/ta.key" 1

ns-cert-type server
resolv-retry infinite

ca "{$smarty.post.ip}/ca.crt"
cert "{$smarty.post.ip}/{$smarty.post.name}.crt"
key "{$smarty.post.ip}/{$smarty.post.name}.key"

cipher AES-256-CBC

redirect-gateway
comp-lzo
verb 0
mute 10

fast-io
multihome

ping 100
ping-restart 150