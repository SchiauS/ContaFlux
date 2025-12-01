#!/bin/bash

function exists_in_list() {
    LIST=$1
    DELIMITER=$2
    VALUE=$3
    LIST_WHITESPACES=`echo $LIST | tr "$DELIMITER" " "`
    for x in $LIST_WHITESPACES; do
        if [ "$x" = "$VALUE" ]; then
            return 0
        fi
    done
    return 1
}

status_code=$(curl -L -f -s -o /dev/null -w "%{http_code}" "http://127.0.0.1/")

accepted_status_codes="200"

if exists_in_list "$accepted_status_codes" " " "$status_code"; then
    echo "Healthy status code detected: $status_code"
else
    echo "Unhealthy status code detected: $status_code!"
    exit 1
fi
