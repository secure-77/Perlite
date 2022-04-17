# Docker

run docker composer
```bash
docker-compose up -d
```

docker-compose will use the latest prebuild perlite image from docker hub.

You can adjust your nginx and php settings via the Dockerfiles in the folder web. If you want to build your own perlite image you can also find a docker file in perlite folder, dont forget to change the docker-compose files to build a own perlite image

```yml
 perlite:
    build:
        context: ./perlite
```

## development
there is also a development enivorment availible, this will map the perlite/ folder to the container, so you can edit the php files without rebuild of the containers
```bash
docker-compose --file docker-compose-dev.yml up -d
```


## Settings

in the docker-compose.yml 
- define the starting point (root path/folder) of you notes via the environment variable NOTES_PATH
- define the exluded folders via the environment variable HIDE_FOLDERS
- define the path from your host to the container via the VOLUME

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
    - HIDE_FOLDERS=docs,private,trash
volumes:
     - /home/user/myNotes:/var/www/perlite/Notes:ro
     - /home/user/documents:/var/www/perlite/Documentation:ro
     - /usr/share/lists:/var/www/perlite/Wordlists:ro
```

