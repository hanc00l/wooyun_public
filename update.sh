#!/bin/bash

mongod --config /usr/local/etc/mongod.conf &

cd scrapy/wooyun
scrapy crawl wooyun -a page_max=100

cd ../wooyun_drops
scrapy crawl wooyun -a page_max=10

cd ../../flask
./app.py
