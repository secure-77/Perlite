# Setup without Docker

Perlite runs on any web server with PHP. No database and no special PHP extensions are needed.


## Requirements

- nginx or Apache
- PHP 7.4 or 8.x (with PHP-FPM for nginx)


## 1. Copy Perlite to your web root

Download the latest [release](https://github.com/secure-77/Perlite/releases) (or clone the repository) and copy the **content** of the `perlite` folder into your web root, e.g. `/var/www/perlite`.

Your vault has to be a folder **inside** the perlite folder:

```
/var/www/
├── .env                 # settings (preferred location, outside of the web root)
└── perlite/             # web root
    ├── index.php
    ├── content.php
    ├── helper.php
    ├── .js/ .styles/ .src/ vendor/
    └── MyNotes/         # your vault
        ├── .obsidian/
        ├── metadata.json
        └── ...
```

Make sure the web server user (e.g. `www-data`) can read all files.


## 2. Create the .env file

Copy `.env.example` from the repository to `.env` and adjust it. `helper.php` looks for the `.env` file in this order:

1. next to the perlite folder (`/var/www/.env`) - **preferred**, it is outside of the web root
2. inside the perlite folder (`/var/www/perlite/.env`) - make sure your web server blocks access to it (the provided nginx config does)

Real environment variables (e.g. set via `env[...]` in the PHP-FPM pool config) take precedence over the `.env` file.


## 3. Set the vault path

`NOTES_PATH` is the vault folder, relative to the perlite folder:

```ini
NOTES_PATH=MyNotes
```

It can also be given relative to the `.env` file, e.g. `./perlite/MyNotes`. This way the same `.env` works for Docker and non Docker setups. In both cases the vault has to be located inside the perlite folder.

All other settings are described in [.env Settings](03-Env-Settings.md).


## 4. Configure the web server

### nginx

Use [`web/config/perlite.conf`](https://github.com/secure-77/Perlite/blob/main/web/config/perlite.conf) as template. The important parts:

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/perlite;
    index index.php;

    # pretty URLs: everything goes to index.php
    location / {
        try_files $uri $uri/ /index.php;
    }

    location ~ \.php$ {
        try_files $uri = 404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;   # adjust to your PHP-FPM
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param REQUEST_URI $request_uri;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    # block sensitive files
    location ~ /\.(ht|env) { deny all; }

    # allow themes
    location ~* ^/(.*)/.obsidian/appearance.json$ { allow all; }
    location ~* ^/(.*)/.obsidian/(.*)/(.*).css$ { allow all; }
    location ~* ^/(.*)/.obsidian/(.*)/(.*).map$ { allow all; }

    location ~ \.(git|github|obsidian|trash) { deny all; }

    # notes and metadata.json are only read by PHP
    location ~ \.(md|json)$ { deny all; }
}
```

#### Hosting in a sub folder

If Perlite is served from a sub path, e.g. `https://example.com/perlite/`, adjust the location and set `URI_PATH`:

```nginx
location /perlite {
    try_files $uri $uri/ /perlite/index.php;
}
```

```ini
URI_PATH=/perlite/
```

### Apache

Perlite needs all requests redirected to `index.php` (pretty URLs). With Apache 2.4 use a `.htaccess` file with `mod_rewrite`, for example:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [L]

# block notes, metadata and settings
<FilesMatch "\.(md|json|env)$">
    Require all denied
</FilesMatch>
<FilesMatch "appearance\.json$">
    Require all granted
</FilesMatch>
```

See issues [#155](https://github.com/secure-77/Perlite/issues/155) and [#161](https://github.com/secure-77/Perlite/issues/161) for more examples.


## 5. Temp folder

Perlite caches the graph relations in a temp folder (`TEMP_PATH`, default: the system temp dir). Make sure PHP can write to it.


## Next Steps

- [Required Obsidian settings](05-Obsidian-Settings.md)
- [Graph setup](06-Graph.md)
- [Themes](07-Themes.md)

---
Back to [Index](00-Index.md)
