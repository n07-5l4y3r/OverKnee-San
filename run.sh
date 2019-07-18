#!/bin/bash

rpm -qa | grep -qw screen || yum install screen

if screen -list | grep -q "dc_bot"; then
  screen -r dc_bot
else
  DIRECTORY='dirname $0'
  screen -dmS dc_bot
  screen -S dc_bot -X stuff $"cd $DIRECTORY\n"
  screen -S dc_bot -X stuff $'screen -X caption always "%{rw} * | %H * $LOGNAME | %{bw}%c %D | %{-}%-Lw%{rw}%50>%{rW}%n%f* %t %{-}%+Lw%<"\n'
  screen -S dc_bot -X stuff $'clear\n'
  screen -S dc_bot -X stuff $'#OverKnee-San\n'
  screen -S dc_bot -X stuff $'#[CTRL]+[A] -> [D] to detach (will still run in background)\n'
  screen -S dc_bot -X stuff $'bash git_pull.sh\n'
  screen -r dc_bot
fi
