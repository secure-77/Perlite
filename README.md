# Perlite
  
![GitHub release (latest by date)](https://img.shields.io/github/v/release/secure-77/perlite) ![GitHub](https://img.shields.io/github/license/secure-77/perlite) ![GitHub last commit](https://img.shields.io/github/last-commit/secure-77/Perlite)


A web based markdown viewer optimized for [Obsidian](https://obsidian.md/) Notes

Just put your whole Obsidian vault or markdown folder/file structure in your web directory. The page builds itself. 

Its an open source alternative to  [obsidian publish](https://obsidian.md/publish).

Read more about Perlite and staging tips on my blog post: [Perlite on Secure77](https://secure77.de/perlite).
If you want to discuss Perlite you can join the [Perlite Discord Server](https://discord.gg/pkJ347ssWT)


## Demo

[Perlite Demo](https://perlite.secure77.de/)


![Demo Screenshot](https://raw.githubusercontent.com/secure-77/Perlite/main/screenshots/screenshot.png "Demo Screenshot")

![Graph Screenshot](https://raw.githubusercontent.com/secure-77/Perlite/main/screenshots/graph.png "Graph Screenshot")

## Features

- Auto build up, based on your folder (vault) structure
- No Database required
- Obsidian Themes Support
- Fully Responsive
- No manual parsing or converting necessary
- Full interactive Graph
- LaTeX and Mermaid Support
- Link to Obsidian Vault
- Search
- Obsidian tags, links, images and preview Support
- Dark and Light Mode


## Install
Please make sure you read the [required Obsidian settings](perlite/Demo/Documentation/05-Obsidian-Settings.md) first!

You can download the latest release from github or git clone the project and use docker.

- For Docker, please check [Docker Setup](perlite/Demo/Documentation/01-Docker-Setup.md)
- For non Docker please check [Setup without Docker](perlite/Demo/Documentation/02-Setup-without-Docker.md)


## Documentation
Please check the [documentation](perlite/Demo/Documentation/00-Index.md), here you will find further information, for example:

- [.env Settings](perlite/Demo/Documentation/03-Env-Settings.md)
- [Browser Settings](perlite/Demo/Documentation/04-Browser-Settings.md)
- [Graph Setup](perlite/Demo/Documentation/06-Graph.md)
- [Themes](perlite/Demo/Documentation/07-Themes.md)
- [Troubleshooting & FAQ](perlite/Demo/Documentation/08-Troubleshooting-FAQ.md)


## Security
- The [Safemode](https://github.com/erusev/parsedown#security) from Parsedown is active, but I would not recommend to allow untrusted user input.
- You should prevent that the .md files are direct accessible via the browser (only the php engine need access to it) or at least make sure that the md files will be downloaded and not be rendered by browser
- You should prevent that the metadata.json file is direct accessible via the browser (only the php engine need access to it). The extracted metadata.json contains the whole obsidian structure, so this file could be sensitive if you plan to exclude some files or folders from Perlite. However, the parsing is done by the php engine and it checks for every path if the file really exists in the provided vault, so files you excluded from the vault will also not be visible in the graph, but they are still present in the metadata.json. This is why you should prevent access to it.


## Contributing
Want to contribute? Awesome! Please use the [dev branch](https://github.com/secure-77/Perlite/tree/dev) for pull requests.


## Why Perlite?
[Wiki](https://en.wikipedia.org/wiki/Perlite):
*Perlite is an amorphous volcanic glass ... typically formed by the hydration of obsidian.*


## Previous Versions and Changelog

- [Changelog](https://github.com/secure-77/Perlite/blob/main/Changelog.md)
- [Perlite 1.4.4 Demo](https://perlite.secure77.de/1.4.4)
- [Perlite 1.3 Demo](https://perlite.secure77.de/1.3)
