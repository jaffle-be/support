#!/bin/sh

# we first want to install our dependencies
# We'll do this globally on the server
sudo apt-get update

# We're gonne use grunt :-)

# So lets start with nodejs and npm

sudo apt-get install nodejs
sudo apt-get install npm

#update to the latest stable version of node

sudo npm cache clean -f
sudo npm install -g n
sudo n stable

sudo npm config set registry http://registry.npmjs.org/
# update npm to the latest version
sudo npm update npm
# not sure if this is before or after, so i run it twice :-)
sudo npm config set registry http://registry.npmjs.org/

# install our grunt tool
sudo npm install -g grunt-cli

# also include bower for dependencies
sudo npm install -g bower

# @todo Find a way that will automatically watch files, even on a server reboot.