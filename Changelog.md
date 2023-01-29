## 1.5.2
- fixed $closing variable error
- set focus to search field
- fixed #Header reference in internal links (Issue #32)
- fixed error with links to non existing files (Graph)
- fixed ampersand problem in internal links and graph (Issue #47)
- added mousehover for internal links, feature request #43 
- added popup feature for internal links on mobile (setting), feature request #43 
- fixed padding-right issue on mobile view
- moved some instructions to the wiki
- added light mode (in the settings)
- updated katex to 0.16.4
- fixed problem with two _ (underscorces) per line in katex context


## 1.5.1
- fixed tags containing a hyphen or slash (Issue #39)
- fixed docker-compose-dev.yml config
- fixed callouts and contiuned quotes (Issue #40 and #27)
- fixed logo position
- Demo Vault is default if no NOTES_PATH is defined
- fixed line breaks if LINE_BREAKS isn´t defined


## 1.5
- Complete redesign based on obsidian css
- support for Obsidian themes
- added tag search
- added metadata support
- added different settings for the graph styling
- added different settings for the viewer, like text size
- settings stored in local browser storage
- updated JQuery to version 3.6.1
- updated HighlightJS to version 11.6.0
- updated Docker image to use latest php:fpm-alpine version (8.1.11)
- updatet Nginx config to allow Themes
- added docker compose variable for markdown linebreaks


## 1.4.5 RC
- added "edit button", enable it via cookie, thx to @Tooa

## 1.4.4
- some code cleanup
- replaced mermaid.min.js with the correct one (9.1.2)
- implemented start page (README.md) for non mobile view
- hide graph display, when node has no graph
- some visual graph updates
- changed order: folders are now always on top
- fixed sort order to be case insensitive
- adjusted the docker image and container naming
- updated the nginx version to 1.22 and php to 7.4.30 for docker
- adjusted blockquote style and added callouts support (thanks to @Tooa)
- fixed sidebar menu height
- fixed problem with direct links and `&` chars
- fixed a graph linking error when some files are missing from the metadata.json


## 1.4.3
- added mermaid 8.7.0 (mermaid.min.js)
- changed order for folders and files with underscores (they are now on top)
- added sec77/perlite:latest to the docker hub
- changed the docker compose files, the hub images is used now
- updated vis-js to version 9.1.2
- fixed problems with multiple links in one line


## 1.4.2
- enabled simple line break in parsedown
- fixed sidebar height
- fixed max img width & height
- removed # from title
- added Perlite Discord Server
- added support for custom internal Obsidian link names
- improved style of tables
- url update in browser


## 1.4.1
- some code cleanup
- added separate perlite.svg for the about header
- changed nginx docker config
- fixed multibyte utf basename to support chinese characters in file name and path
- fixed menu collapse problem when folder name contains dots or begins with a number
- fixed image size in mobile view
- fixed image line break issue
- fixed full graph view when current node has no links and no-link filter is enabled
- changed color of current node in the graph view
- LaTeX support 
    - added katex.min.js
    - added katex.min.css
    - added fonts
    - added auto-render.min.js
- fixed navigator when file or folder names are to long


## 1.4
- updated highlight.js to 11.4.0
- updated Bootstrap JS files to 5.1.3
- changed default (dark) theme from darkly to Bootswatch theme "slate"
- fixed direct links when Perlite is in a subfolder of the root direcotry
- removed the flatly theme, a11y-light theme and disabled the "light mode"
- many layout redesigns like
    - changed the header size
    - moved the searchbar to the navbar
    - added Nerd Fonts as default Font
    - added a right navbar for the graph
    - highlight current navbar entry
    - set focus on current navbar entry if called by a link
    - other small layout changes
- added separated scrollbars for navbar and content
- added rel="noopener noreferrer" attributes for external links
- added icon for external links
- added a visual graph via vis.js
    - metadata.json parsing (only adds nodes if they really exists in the folder structure)
    - show graph and direct links for every note in the right navbar
    - added modal for full graph view
    - added filter (hide nodes without link) in the full graph view (saved via cookie)
- improved the search
    - enabled case insensitive
    - included filename for the search
    - removed the "open recent search" (because its unnecessary)
- updated the Readme
- updated the Demo Documents


## 1.3
- added support for inplace links
- added support for PDF files / links
- changed in place image behaviour
- new logo
- added favicon
- adjust image view in about modal
- changed "About" behaviour
- added changelog
- outsourced docker instructions from readme
- changes link behaviour (added link get parameter to fix problems with unwanted queries)
- removed "not allowed" content
- added cookie for theme settings
- adjust mobile margins


## 1.2
- added Docker  
- added direct Links  
- changed base dir logic  
- update Readme


## 1.1
- added mobile responsive
- added search
- added theme support and darkmode 


## 1.0
- First prod. ready release
