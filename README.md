# Embedded block generator
This module is built to quickly generate other modules with the purpose of embedding apps in blocks.
## How to use
To use, simply use the drush command like so:  
`drush ebc-init "Name of my module"`  
This will build the module within the modules/custom directory. It adds .info, .libraries, .links.menu, .permissions .routing, block, form, readme, and empty directories for js, css, and react.
If your project doesn't use React, you can rename that. The generator establishes a settings form with admin links in the Drupal admin menu. The settings form has one field to set the ID of the DIV where the app will be embedded.
Permissions for the settings form are also generated. A block is also generated that is simply a DIV for the app to be emedded into.  

The libraries file assumes a React app, but this can be changed up after generation. For this to work, your react build files should output to the files defined in the library. This will require manually renaming and moving them after build, or setting up build scripts to do it for you.
