#!/bin/bash


if [ ! -f /apps/trunk-PBX/etc/asterisk/extensions.conf ] ; then
    echo "Copying default confs."
    mkdir -p /apps/trunk-PBX/etc/asterisk/
    cp -a /apps/trunk-PBX/conf/* /apps/trunk-PBX/etc/asterisk/
    sync
fi
