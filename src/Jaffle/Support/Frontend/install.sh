#!/bin/sh
# we first want to install our dependencies


#we're gonne use grunt :-)

# so first things first we need nodejs and npm installed

sudo apt-get install nodejs
sudo apt-get install npm

#update the latest stable version of node

sudo npm cache clean -f
sudo npm install -g n
sudo n stable

# update npm to latest version
sudo npm update npm

# install our grunt tool

sudo npm install -g grunt
sudo npm install -g grunt-contrib-watch

sudo npm install -g grunt-contrib-uglify
sudo npm install -g grunt-contrib-concat
sudo npm install -g grunt-contrib-less
