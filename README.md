# Perlite
  
![GitHub release (latest by date)](https://img.shields.io/github/v/release/secure-77/perlite) ![GitHub](https://img.shields.io/github/license/secure-77/perlite) ![GitHub last commit](https://img.shields.io/github/last-commit/secure-77/Perlite)


	
A web based markdown viewer optimized for Obsidian Notes

Just put your hole Obsidian vault or markdown folder/file structure in your web directory. The Page will built up by it self. 

Its an open source alternative to obisidian publish.


## Demo

[Perlite Demo](https://perlite.secure77.de)


<img src="https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/screenshot.png" alt="Demo Screenshot" style="width:100%;"/>

## Features

- auto build up, based on folder structure
- Fullscreen mode
- Full responsive
- Search
- Obsidian image optimized
- Dark and light mode
- [![coverage](https://img.shields.io/badge/Bootswath-Themes-blue)](https://bootswatch.com) (Darkly and Flatly)




## Install
Just put the content of the perlite directory in your web directory, your notes should resident as a subfolder of perlite

### requirements
- web server, tested with ![coverage](https://img.shields.io/badge/NGINX-1.18.0-blue)
- php-fpm, tested with ![coverage](https://img.shields.io/badge/PHP-7.4-green)
- php module mb_strings for the parsedown (apt install php-mbstring)



## Docker

run docker composer
```bash
docker-compose up -d
```

You can adjust your nginx and php settings via the Dockerfiles in the folders web and perlite

#### development
there is also a development enivorment availible, this will map the perlite/ folder to the container, so you can edit the php files without rebuild of the containers
```bash
docker-compose --file docker-compose-dev.yml up -d
```


## Settings

### Path to Notes

define the starting point (root path/folder) of you notes via the environment variable `NOTES_PATH`.
if you dont want to use the variable you can also adjust the $rootDir in the `helper.php`, keep in mind the folder need to be a subfolder of your web root directory

Example: if your root web directory is `/var/www/html/perlite` and you put your notes into `/var/www/html/perlite/notes/WriteUps` set root path to `notes/WriteUps` and comment out the `getenv('NOTES_PATH');`

```php
$rootDir = 'notes/WriteUps';
//$rootDir = getenv('NOTES_PATH');
```

if you dont specify any NOTES_PATH, Perlite will take the webroot directory as starting point. 


#### Docker
in the docker-compose.yml 
- define the starting point (root path/folder) of you notes via the environment variable NOTES_PATH
- define the path form your host to the container via the VOLUME

for example use this to mount you local folder `/home/user/myNotes` to your container and set the root folder 

```yml
environment:
    - NOTES_PATH=Notes
    - HIDE_FOLDERS=docs,private,trash
volumes:
     - /home/user/myNotes:/var/www/perlite/Notes:ro
```

if you dont specify any NOTES_PATH, Perlite will take `/var/www/perlite` (in the container) as starting point. This will allow you to mount more then one notes folder to the container

Example
```yml
environment:
    - NOTES_PATH=
    - HIDE_FOLDERS=docs,private,trash
volumes:
     - /home/user/myNotes:/var/www/perlite/Notes:ro
     - /home/user/documents:/var/www/perlite/Documentation:ro
     - /usr/share/lists:/var/www/perlite/Wordlists:ro
```




### Images, Links and Hide Folders

- if you want to exclude specific folders, e.g. your attachment folder you can set the `HIDE_FOLDERS` variable
- folders and files starting with a "." (dot) are exclude by default


In Obsidian, in the Options `Files & Links` you need to set the `New link format` to `Relative path to file`
- unfortunately Obsidian links to other files then images will not work at the moment (feature is planed)

### Header
is not dynamic at the moment, you need to adjust them into the .php files.

- The About opens a MD files in a modal window (README.md per default), you can change this in the helper.php `$about`
- The Logo, Blog and so on are nested in the index.php (line 28 e.g. for the link to the Blog)



### dependencies (all included)

- [![coverage](https://img.shields.io/badge/Parsedown-1.7.4-lightgrey)](https://github.com/erusev/parsedown)
- [![coverage](https://img.shields.io/badge/jQuery-3.6.0-lightblue)](https://jquery.com/)
- [![coverage](https://img.shields.io/badge/Bootstrap-5-blue)](https://getbootstrap.com/)
- [![coverage](https://img.shields.io/badge/Highlight.js-11.0.1-green)](https://highlightjs.org/)



## Security
- The [Safemode](https://github.com/erusev/parsedown#security) from Parsedown is active, but i would not recommend to allow untrusted user input.
- You should prevent that the .md files are direct accessible via the browser (only the php engine need access to it) or at least make sure that the md files will be downloaded and not be renderd by browser


## How it works
On visiting the index.php, the site will crawl recursive for all markdown (.md) files starting in the `$rootDir`. Based on the folder and files structure, the sidebar (left menu) will build up. By clicking a file, a request with the path of the file will be sent to content.php, this checks if this file is in the current folder structure, get the content and call the parsedown.php to convert the markdown to the html. Obsidian image tags will be replaced by html img and highlight.js will be called for the code highlighting.



## Planned
- activate search for mobile
- fix pdf links
- save theme setting in cookie

