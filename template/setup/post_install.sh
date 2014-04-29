#!/bin/bash


if [ ! -f /apps/zeroconf-PBX-Trunks/etc/asterisk/extensions.conf ] ; then
    echo "Copying default confs."
    mkdir -p /apps/zeroconf-PBX-Trunks/etc/asterisk/
    cp -a /apps/zeroconf-PBX-Trunks/conf/* /apps/zeroconf-PBX-Trunks/etc/asterisk/
    sync
fi
