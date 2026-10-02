# Obsidian Settings

Perlite reads your vault as it is, but links and images can only be resolved if Obsidian writes them in a format Perlite understands. **Please check these settings before you publish your vault.**


## Required: Link format

In Obsidian open **Settings → Files and links** and set:

| Option | Value |
|--------|-------|
| **New link format** | `Relative path to file` |
| **Use [[Wikilinks]]** | on (default) |

The Obsidian default `Shortest path when possible` only writes the file name (e.g. `[[My Note]]`). Obsidian can resolve that, Perlite can not.

Alternatively use `Absolute path in vault` and set `ABSOLUTE_PATHS=true` in the [.env](03-Env-Settings.md).

> [!important] Existing links are not converted
> The setting only applies to **new** links. To convert existing links, use the community plugin [Link Converter](https://github.com/ozntel/obsidian-link-converter): set its link format to relative path and run its vault wide conversion command from the command palette. Make a backup of your vault first.


## Attachments

Images and other attachments are links too, so the link format above applies to them as well. Where they are stored (**Settings → Files and links → Default location for new attachments**) doesn't matter.

If an attachment link is broken after moving a note, move the attachment once (e.g. to another folder and back) so Obsidian updates the links.


## Supported link syntax

```markdown
[[Folder/My Note]]                      wikilink
[[Folder/My Note|Display Text]]         wikilink with alias
[[Folder/My Note#Heading]]              link to a heading
[My Note](Folder/My%20Note.md)          markdown link
![[Folder/My Note]]                     embedded note
![[assets/image.png]]                   image
![alt](assets/image.png)                image (markdown syntax)
![[assets/image.png|Description|100x100]]   image with alt text and size
[[assets/file.pdf]]                     linked file (types from ALLOWED_FILE_LINK_TYPES)
```

The notes in the `Demo Documents` folder of the demo vault show more examples.


## The .obsidian folder

Keep the `.obsidian` folder of your vault when you upload it. Perlite uses:

- `.obsidian/themes/` and `.obsidian/appearance.json` for [themes](07-Themes.md)

Everything else in this folder (plugins, workspace, hotkeys) is ignored. Folders starting with `.` are never shown in the navigation.


## Special files in the vault root

| File | Purpose |
|------|---------|
| `README.md` | Start page (configurable with `HOME_FILE`) |
| `metadata.json` | Graph data, see [Graph](06-Graph.md) |
| `.about.md` | Content of the help / about dialog (optional) |
| `custom.css` | Custom styles, override the system styles and themes, see [Themes](07-Themes.md) (optional) |


## Frontmatter and titles

YAML frontmatter is shown as properties above the note. With `ZETTELKASTEN_FILENAMES_ENABLED=true` Perlite shows the frontmatter `title` (or the first H1) instead of the file name.


## Not supported

- Obsidian plugins and their syntax (e.g. Dataview queries) - Perlite only renders markdown
- Editing - Perlite is read-only

---
Back to [Index](00-Index.md)
