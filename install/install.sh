#! /bin/bash

echo ''
echo "Title Web Solution's"
echo '  ______                    _             __   ______            __    '
echo ' /_  __/__  _________ ___  (_)___  ____ _/ /  /_  __/___  ____  / /____'
echo '  / / / _ \/ ___/ __ `__ \/ / __ \/ __ `/ /    / / / __ \/ __ \/ / ___/'
echo ' / / /  __/ /  / / / / / / / / / / /_/ / /    / / / /_/ / /_/ / (__  ) '
echo '/_/  \___/_/  /_/ /_/ /_/_/_/ /_/\__,_/_/    /_/  \____/\____/_/____/  '
echo ''
echo 'for your Infrastructure'
echo ''




echo "";

echo "Please enter the project name (something along \"My Companies Infrastrucure\"):"
read PROJECTNAME



echo ""
echo "* Now creating project configuration for $PROJECTNAME"




echo "Projectname: \"$PROJECTNAME\"
Environments:
  #you can add all your servers here - just copy/paste them from all your ttools projects
  Server1:
    #TODO add
  Server2:
    #TODO add
  Server3:
    #TODO add
Menu:
  Heading1:
    Title: Servers
    #add all your servers here - you can of course call them what you want
    Item1:
      Title: SSH Server1
      Command: \"ttools/core/local/ssh.sh Server1\"
    Item2:
      Title: SSH Server2
      Command: \"ttools/core/local/ssh.sh Server2\"
    Item3:
      Title: SSH Server3
      Command: \"ttools/core/local/ssh.sh Server3\"
" > ttools/config.yml


echo ""
echo "* Now installing ttools libraries"

#core 
git submodule add git://github.com/titledk/ttools-core.git ttools/core;
#(needs to be on the "onedir" branch for now)
cd ttools/core;
git checkout onedir;
cd ../..;

#git helpers
git submodule add https://github.com/titledk/ttools-githelpers.git ttools/githelpers
#(needs to be on the "onedir" branch for now)
cd ttools/githelpers;
git checkout onedir;
cd ../..;


echo ""
echo "* Now installing the ttools binary"


##the ttools binary
echo "#!/bin/sh
./ttools/core/lib/ttools.sh \"\$@\"" > tt;
chmod u+x tt;

echo "";
echo "Installation is done. You can now run Terminal Tools by running \"./tt\"";
echo "Remember to commit the changes to your repository."


echo ""


