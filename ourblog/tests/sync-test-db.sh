#!/bin/bash

echo "dump..."
sudo mysqldump -d our_blog > /tmp/our_blog.sql
echo "import..."
sudo mysql -e "DROP DATABASE IF EXISTS our_blog_test;CREATE DATABASE our_blog_test DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
sudo mysql our_blog_test < /tmp/our_blog.sql
echo "done"
