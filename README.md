# Perlite
  
![GitHub release (latest by date)](https://img.shields.io/github/v/release/secure-77/perlite) ![GitHub](https://img.shields.io/github/license/secure-77/perlite) ![GitHub last commit](https://img.shields.io/github/last-commit/secure-77/Perlite)


	
A web based markdown viewer optimized for [Obsidian](https://obsidian.md/) Notes

Just put your whole Obsidian vault or markdown folder/file structure in your web directory. The page builds itself. 

Its an open source alternative to  [obisidian publish](https://obsidian.md/publish).

Read more about Perlite and staging tips on my blog post: [Perlite on Secure77](https://secure77.de/perlite).

If you want to discuss about Perlite you can join the thread in the [Obsidian Forum](https://forum.obsidian.md/t/perlite-publish-your-notes-to-your-own-web-server/21712).

## Demo

[Perlite Demo](https://perlite.secure77.de/?link=%2FWriteUps%2FHackTheBox%2FDeveloper%2FWriteUp)


![Demo Screenshot](https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/screenshot.png "Demo Screenshot")

![Graph Screenshot](https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/graph.png "Graph Screenshot")

## Features

- Auto build up, based on your folder (vault) structure
- Fullscreen mode
- Full responsive
- No manual parsing or converting necessary
- Full interactive Graph
- Search
- Support Obisdian links and images
- [![coverage](https://img.shields.io/badge/Bootswath-Themes-blue)](https://bootswatch.com) (Slate)

## Changelog
[Changelog](https://github.com/secure-77/Perlite/blob/main/Changelog.md)


## Install
Just put the content of the perlite directory in your web root directory, your notes should resident as a subfolder of perlite

for Docker just check the [DOCKER](https://github.com/secure-77/Perlite/blob/main/Docker.md) readme.

### requirements
- web server, tested with ![coverage](https://img.shields.io/badge/NGINX-1.21.6-blue)
- php-fpm, tested with ![coverage](https://img.shields.io/badge/PHP-7.4-green)
- php module mb_strings for the parsedown (apt install php-mbstring)


### required opsidian options
- in the options `Files & Links` you need to set the `New link format` to `Relative path to file`
![Link Options](https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/link.png "Link Options")

#### Graph
- for the Graph you need to install the plugin [Metadata Extractor](https://github.com/kometenstaub/metadata-extractor), you can do this via the build in Community Plugin Browser
- make sure to turn on the plugin
- in the settings set the path of the metadata.json to your local vault root folder (like `C:\Users\John\MyNotes\metadata.json`, when your vault is MyNotes). if you transfer your vault later to a webserver, make sure the metadata.json will be transferred too.
- also dont forget set a timer how often the file should be written or just enable `Write JSON files automatically when Obsidian launches`

![Plugin Options](https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/plugin_options.png "Plugin Options")

### Hide Folders and Files

- if you want to exclude specific folders, e.g. your attachment folder you can set the `HIDE_FOLDERS` variable
- folders and files starting with a "." (dot) are exclude by default
![Folders Options](https://raw.githubusercontent.com/secure-77/Perlite/main/Demo/folders.png "Folders Options")

### Advanced Options and Infos

#### Header and Logo
you can adjust them via the index.php

- The About opens a .md file in a modal window (.about.md per default), you can change this in the helper.php `$about`
- The Logo, Blog and so on are nested in the index.php (line 28 e.g. for the link to the Blog)

#### Graph
- The Graph is implemented with vis.js, so there are many options you can play on with, you can adjust them via the `options` object in the perlite.js

#### Python Parser
- there is a pythonParser.py to genereate the vis.js JSON structure out of the metadata.json. This script is not beeing used anymore but i will let it here.


### dependencies (all included)

- [![coverage](https://img.shields.io/badge/Parsedown-1.7.4-lightgrey)](https://github.com/erusev/parsedown)
- [![coverage](https://img.shields.io/badge/jQuery-3.6.0-lightblue)](https://jquery.com/)
- [![coverage](https://img.shields.io/badge/Bootstrap-5-blue)](https://getbootstrap.com/)
- [![coverage](https://img.shields.io/badge/Highlight.js-11.4.0-green)](https://highlightjs.org/)
- [![coverage](https://img.shields.io/badge/vis.js-9.1.0-yellow)](https://https://visjs.org//)



## Security
- The [Safemode](https://github.com/erusev/parsedown#security) from Parsedown is active, but i would not recommend to allow untrusted user input.
- You should prevent that the .md files are direct accessible via the browser (only the php engine need access to it) or at least make sure that the md files will be downloaded and not be rendered by browser
- You should prevent that the metadata.json file is direct accessible via the browser (only the php engine need access to it). The extracted metadata.json contains the whole obsidian structure, so this file could be sensitive if you plan to exclude some files or folders from Perlite. However, the parsing is done by the php engine and it checks for every path if the file really exists in the provided vault, so files you excluded from the vault will also not be visible in the graph, but they are still present in the metadata.json. This is why you should prevent access to it.


## Why Perlite?
[Wiki](https://en.wikipedia.org/wiki/Perlite):
*Perlite is an amorphous volcanic glass ... typically formed by the hydration of obsidian.*
