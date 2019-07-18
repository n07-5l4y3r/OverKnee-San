#!/bin/bash

echo updating...

git fetch --all --quiet
git reset --hard --quiet origin/master
git pull --quiet origin master

echo done

echo running...

composer install
php run.php

if echo $TERM | grep -q "screen"; then
  exit
fi
