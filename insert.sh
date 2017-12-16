#!/bin/sh

for data in `seq 10`
do
mysql -u root -proot xxxx < ./import-order$data.sql
echo import-order$data.sql
done

for data in `seq 10`
do
mysql -u root -proot xxxx < ./import-order-detail$data.sql
echo import-order-detail$data.sql
done

for data in `seq 10`
do
mysql -u root -proot xxxx < ./import-shipping$data.sql
echo import-shipping$data.sql
done

for data in `seq 10`
do
mysql -u root -proot xxxx < ./import-mail-history$data.sql
echo import-mail-history$data.sql
done
