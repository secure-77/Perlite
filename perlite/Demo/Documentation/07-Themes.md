# Themes

Perlite supports Obsidian community themes. It loads all themes from the `.obsidian/themes` folder of your vault.


## Setup

1. Install the themes you want in Obsidian (**Settings → Appearance → Themes → Manage**).
2. Select your default theme in Obsidian. It is stored in `.obsidian/appearance.json`.
3. Publish the `.obsidian` folder together with your vault:

```
MyNotes/
└── .obsidian/
    ├── appearance.json        # default theme ("cssTheme")
    └── themes/
        ├── Minimal/
        │   └── theme.css
        └── Obsidian Nord/
            └── theme.css
```

Perlite uses the `cssTheme` from `appearance.json` as default. Without it, the built-in Perlite style is used.


## Web server access

The browser loads the `theme.css` files and `appearance.json` directly, so your web server must allow them, while the rest of `.obsidian` stays blocked. The provided nginx config ([`web/config/perlite.conf`](https://github.com/secure-77/Perlite/blob/main/web/config/perlite.conf)) already does this:

```nginx
location ~* ^/(.*)/.obsidian/appearance.json$ { allow all; }
location ~* ^/(.*)/.obsidian/(.*)/(.*).css$ { allow all; }
location ~* ^/(.*)/.obsidian/(.*)/(.*).map$ { allow all; }
location ~ \.(git|github|obsidian|trash) { deny all; }
```


## Switching themes

Visitors can switch between all installed themes and between dark and light mode in the [Browser Settings](04-Browser-Settings.md). Their choice is stored in the browser, *Reset* goes back to the default theme.

Some themes are built for features Perlite doesn't have (e.g. theme specific plugins / style settings), so not every theme looks exactly like in Obsidian.


## Custom CSS

To adjust the look without touching the Perlite files, put a `custom.css` into the root of your vault:

```
MyNotes/
├── custom.css
└── README.md
```

Perlite loads it after all built-in styles (`app.css`, `perlite.css`, ...) and themes, so your rules override them. Example:

```css
.custom-page-logo {
  border-radius: 0%;
}
```

If a rule doesn't apply, the original selector is probably more specific, use a more specific selector or `!important`. Changes are picked up immediately (the file is loaded with its modification time as cache buster). Without the file nothing is loaded.

---
Back to [Index](00-Index.md)
