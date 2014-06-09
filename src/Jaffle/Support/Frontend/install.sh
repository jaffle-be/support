#!/bin/sh

# we first want to install our dependencies
# We'll do this globally on the server
sudo apt-get update

# We're gonne use grunt :-)

# So lets start with nodejs and npm

sudo apt-get install nodejs
sudo apt-get install npm

#update to the latest stable version of node

sudo npm config set registry http://registry.npmjs.org/
sudo npm cache clean -f
sudo npm install -g n
sudo n stable

# update npm to the latest version
sudo npm update npm

# install our grunt tool
sudo npm install -g grunt-cli

# also include bower for dependencies
sudo npm install -g bower


#install local packages
sudo npm install

# @todo Find a way that will automatically watch files, even on a server reboot.