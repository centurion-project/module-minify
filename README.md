Minify module for Centurion Framework (http://www.centurion-project.org)

# Description


This module is used to minify the HTML output, and combiny the css and js in one file for each.

# Install

- Add the module to your application.
- Activate the module
- Add this config :


    minify.html = true  
    minify.js = true  
    minify.salt = pingpong  

# TODO

- Try not using the BDD
- Add test unit
- Add comment
- Move all Minify class from minify project to it's own folder
- Merge Minify_View_Helper_HeadLink and Minify_View_Helper_HeadScript
- Separate html and css combiny in config
