#!/bin/sh

local_v4_interface=pppoe-wan
local_v6_interface=wan6
local_v6_link=6in4-wan6
local_lan_interface=br-lan

remote_v6="2402:f000:1:1501:200:5efe"
remote_v4="166.111.21.1"

ip link set dev $local_v6_link down

local_wan_v4_addr=$(ip addr show dev $local_v4_interface | grep inet | awk '{print $2}')
local_v6_addr=$remote_v6:$local_wan_v4_addr/64
uci set network.$local_v6_interface.ip6addr=$local_v6_addr
uci commit

ip link set dev $local_v6_link up

ip6tables -t nat -A POSTROUTING -o $local_v6_link -j MASQUERADE
ip6tables -A FORWARD -m conntrack --ctstate RELATED,ESTABLISHED -j ACCEPT
ip6tables -A FORWARD -i $local_lan_interface -j ACCEPT
