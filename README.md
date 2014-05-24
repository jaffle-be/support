support
=======

A base package to work with all jaffle components


#FRONTEND

oke, this should be helpfull for everything that has to do with frontend stuff.

we might eventually need to put this into another repository.
but, for now, to get things going we add it all right in here.

So do not be afraid to use longer namespaces, as it might all be moved anyway.
Which might be pretty combersome at that time, but the improvement itself should be the motivation :-)

**make use of commands**

frontend:install
frontend:deploy-grunt
frontend:deploy-blade

##what do we want?

todo:

we need to upgrade our deploy script to distinguish between
 - a single tenant application ,
 - a multi tenant application (currently not supported yet)


###Serverside dependencies

we want helpers to install the correct server side dependencies such as:

yuicompressor
less

perhaps we want to use guard? to manage the file watching part.
we've done it before, it worked out pretty well.

To install everything, we might wanne make use of a simple shell script. (which should be executed ass root for now)


###automatic compilation of javascript and css

we want to solve our neverending problem of ... right how do i structure this..

make use of config arrays as described on github issues.

make sure only compilation of files needing it are compiled. (to much sites will slow things down significantly)



### a deployment script

we need a script which sets up all directories at the right spot.
it should also paste in layout files with proper seperation into header, footer, etc, etc

create the basic files for less (master.less and variables.less) and javascript
(javascript might not need any, but still you'll need the autocompilation script based on the configuration files)

(also try to install the bootstrap library at a correct spot - with a separate composer.json file - and make sure it gets installed.)



