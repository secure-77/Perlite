# Perlite
A web based markdown viewer optimized for Obsidian

Just put your hole Obsidian vault or markdown folder/file structure in the root directory from your web server. The Page will built up by it self. 


## Demo

https://perlite.secure77.de


## Features

- build navigation based on folder structure
- in-place markdown render
- Fullscreen mode
- full responsive
- search
- Obsidian image src replacement
- exclude hidden files and folders, starting with a "." (dot)
- Bootstrap 5 for the design
- hightlight.js for code highlighting



## Install & Requirements

- web server
- php server (tested with php 7.4)
- php module mb_strings for the parsedown (apt install php-mbstring)


### dependencies
- Parsedown.php (included in this repo) https://github.com/erusev/parsedown
- some JS and CSS files from Bootstrap 5 (included in this repo)
- jQuery (included from google)
- hightlight.js (included in this repo)


### Images and Links

- the images should resident in the same folder of your .md file, if you use a global folder for all of your images, you can change the path / replacement statement in the content.php file.
- unfortunately Obsidian links to other files will not work


### Security

- The Safemode (https://github.com/erusev/parsedown#security) from Parsedown is active, but i would not recommend to allow user input.
- You should prevent that the .md files are direct accessible via the browser (only the php engine need access to it) or at least make sure that the md files will be downloaded and not be renderd by browser


## How it works

On visiting the index.php, the site will crawl recursive for all markdown (.md) files in the current directory and subfolders. Based on the folder and files structure, the sidebar (left menu) will build up. By clicking a file, a request with the path of the file will be sent to content.php, this checks if this file is in the current folder structure, get the content and call the parsedown.php to convert the markdown to the html. Obsidian image tags will be replaced by html img and highlight.js will be called for the code highlighting.

Thats it.


## Planned

- activate search for mobile
- toogle sidebar
- toogle between light- and dark-mode
- image viewer
- docker image

