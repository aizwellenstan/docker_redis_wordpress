#!/bin/bash
echo "Please enter your old host (127.0.0.1)"
read oldhost
echo "Please enter your newhost"
read newhost
grep -Ril $oldhost code | xargs -r sed -i 's/'$oldhost'/'$newhost'/g'
