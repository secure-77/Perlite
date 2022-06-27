## 1.4.4 RC (commited but not released)
- some code cleanup
- replaced mermaid js with the correct one (9.1.2)
- implemented start page (README.md) for non mobile view
- hide graph display, when node has no graph
- some visual graph updates
- changed order: folders are now always on top
- fixed sort order to be case insensitive
- adjusted the docker image and container naming
- updated the nginx version to 1.22 and php to 7.4.30 for docker
- adjusted blockquote style
- fixed sidebar menu height


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
