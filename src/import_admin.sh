#!/bin/bash

source ./.env

if test $# -eq 1
then
  sqlFile=$1
else
  sqlFile=admin.sql
fi

if test -e ${sqlFile}
then
  mysql -u"${DB_USERNAME}" -p"${DB_PASSWORD}" "${DB_DATABASE}" < "${sqlFile}"
fi
