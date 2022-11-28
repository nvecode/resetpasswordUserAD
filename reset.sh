#!/bin/bash

server="172.20.20.21"
adminserver="adminskg"
passwordadmin="Cjsadnkjqawdhq1234@1q"

NAME="vdgo-gro01-02"
username=$NAME
idsession=$(sshpass -p $passwordadmin ssh -o StrictHostKeyChecking=no $adminserver@$server 'quser '$username' /server localhost 2>nul' | awk '{print $3}' | sed '1d')
if sshpass -p $passwordadmin ssh -o StrictHostKeyChecking=no $adminserver@$server 'rwinsta '$idsession' /server localhost 2>nul';
then echo "true"
else echo "false"
fi
