#!/bin/bash
RSS_LIST=$(grep "http" /var/www/forex/rss/rss/index.php |awk -F 'feed=' '{print $2}'|awk -F '"' '{print $1}')
for i in $RSS_LIST
do
    curl -s -k "https://localhost/rss/rss/?feed="+$i 1,2>/dev/null
done
