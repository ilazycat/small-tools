#!/bin/sh
# Configure tsinghua isatap ipv6 and nat on openwrt
# all in one script.
# Modify basic config and before you run this script
# you have to mannually assign an IPv6 address for
# your LAN interface. And choose
#       Always announce default router
# option on your lan IPv6 config.
# Then run this script if everything is fine you can
# add this to your rc.local so that it will config
# IPv6 on router start.
#
# Made by LazyCat@iLazyCat
#                 20170409

local_v4_interface=pppoe-wan
local_v6_interface=wan6
local_v6_link=6in4-wan6
local_lan_interface=br-lan

remote_v6="2402:f000:1:1501:200:5efe"
remote_v4="166.111.21.1"

echo "Shutdown IPv6 interface"
ifdown $local_v6_interface
sleep 1

local_wan_v4_addr=$(ip addr show dev $local_v4_interface | grep inet | awk '{print $2}')
echo "Get local wan IP address $local_wan_v4_addr"
local_v6_addr=$remote_v6:$local_wan_v4_addr/64
echo "Set local IPv6 address $local_v6_addr"
uci set network.$local_v6_interface.ip6addr=$local_v6_addr
uci set network.$local_v6_interface.ipaddr=$local_wan_v4_addr
uci commit network
echo "Reload network."
/etc/init.d/network reload
sleep 1

echo "Bring up IPv6 interface"
ifup $local_v6_interface
sleep 1

echo "Setting up firewall rules"
ip6tables -t nat -A POSTROUTING -o $local_v6_link -j MASQUERADE
ip6tables -A FORWARD -m conntrack --ctstate RELATED,ESTABLISHED -j ACCEPT
ip6tables -A FORWARD -i $local_lan_interface -j ACCEPT

echo "Setting up IPv6 route"
ip -6 route del default from 2402:f000:1:1501::/64
ip -6 route add default via $remote_v6:$remote_v4 dev $local_v6_link

echo "Done"
