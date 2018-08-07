#!/bin/bash

host=
username=
password=
olddb=
newdb=

mysqlconn="mysql -u$username -p$password -h$host"

#$mysqlconn -e "CREATE DATABASE $newdb"
params=$($mysqlconn -N -e "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE table_schema='$olddb'")

for name in $params; do
      $mysqlconn -e "RENAME TABLE $olddb.$name to $newdb.$name";
done;

#$mysqlconn -e "DROP DATABASE $olddb"