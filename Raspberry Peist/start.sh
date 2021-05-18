#!/usr/bin/env bash

/usr/bin/sshd -D &
su master-baker -c 'cd ~/ && php -S localhost:9999' &
su flask -c /home/flask/ctf-management/run.sh