#!/bin/bash
for deferral in {11..20}
do
    ./artisan cannex:cache-guaranteed --deferral=$deferral
done

