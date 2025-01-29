#!/bin/bash

# Monitor the repository for changes (modifications, creation, deletion of files)
while inotifywait -r -e modify,create,delete ~/Triviaverse-web; do
    echo "Changes detected, pulling from Git..."
    ~/Triviaverse-web/git_pull.sh  # Trigger the git pull script
done
