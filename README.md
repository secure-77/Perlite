# Perlite
  
![GitHub release (latest by date)](https://img.shields.io/github/v/release/secure-77/perlite) ![GitHub](https://img.shields.io/github/license/secure-77/perlite) ![GitHub last commit](https://img.shields.io/github/last-commit/secure-77/Perlite)


	
A web based markdown viewer optimized for [Obsidian](https://obsidian.md/) Notes

Just put your whole Obsidian vault or markdown folder/file structure in your web directory. The Page will built up by it self. 

Its an open source alternative to  [obisidian publish](https://obsidian.md/publish).

Read more about Perlite and staging tips on my blog post: [Perlite on Secure77](https://secure77.de/perlite).

If you want to discuss about Perlite you can join the thread in the [Obsidian Forum](https://forum.obsidian.md/t/perlite-publish-your-notes-to-your-own-web-server/21712).

## Demo

[Perlite Demo](https://perlite.secure77.de)


![Demo Screenshot](https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/screenshot.png "Demo Screenshot")

## Features

- auto build up, based on folder structure
- Fullscreen mode
- Full responsive
- Search
- Support Obisdian links and images
- Dark and light mode
- [![coverage](https://img.shields.io/badge/Bootswath-Themes-blue)](https://bootswatch.com) (Darkly and Flatly)

## Changelog
[Changelog](https://github.com/secure-77/Perlite/blob/main/Changelog.md)


## Install
Just put the content of the perlite directory in your web root directory, your notes should resident as a subfolder of perlite

for Docker just check the [DOCKER](https://github.com/secure-77/Perlite/blob/main/Docker.md) readme.

### requirements
- web server, tested with ![coverage](https://img.shields.io/badge/NGINX-1.18.0-blue)
- php-fpm, tested with ![coverage](https://img.shields.io/badge/PHP-7.4-green)
- php module mb_strings for the parsedown (apt install php-mbstring)




### Images, Links and Hide Folders

- if you want to exclude specific folders, e.g. your attachment folder you can set the `HIDE_FOLDERS` variable
- folders and files starting with a "." (dot) are exclude by default

In Obsidian, in the Options `Files & Links` you need to set the `New link format` to `Relative path to file`


### Header
is not dynamic at the moment, you need to adjust them into the .php files.

- The About opens a .md file in a modal window (.about.md per default), you can change this in the helper.php `$about`
- The Logo, Blog and so on are nested in the index.php (line 28 e.g. for the link to the Blog)



### dependencies (all included)

- [![coverage](https://img.shields.io/badge/Parsedown-1.7.4-lightgrey)](https://github.com/erusev/parsedown)
- [![coverage](https://img.shields.io/badge/jQuery-3.6.0-lightblue)](https://jquery.com/)
- [![coverage](https://img.shields.io/badge/Bootstrap-5-blue)](https://getbootstrap.com/)
- [![coverage](https://img.shields.io/badge/Highlight.js-11.0.1-green)](https://highlightjs.org/)



## Security
- The [Safemode](https://github.com/erusev/parsedown#security) from Parsedown is active, but i would not recommend to allow untrusted user input.
- You should prevent that the .md files are direct accessible via the browser (only the php engine need access to it) or at least make sure that the md files will be downloaded and not be renderd by browser


## Why Perlite?
[Wiki](https://en.wikipedia.org/wiki/Perlite):
*Perlite is an amorphous volcanic glass ... typically formed by the hydration of obsidian.*
