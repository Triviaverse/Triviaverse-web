#!/bin/bash

exec > /dev/tty1 2>&1

# Interval (in seconds) between each check for updates from the remote repository
CHECK_INTERVAL=60  # Adjust this value as needed

while true; do
    source ~/.bashrc
    echo "Checking for updates from GitHub..."
    cd ~/Triviaverse-web || exit  # Change to your repo directory, exit if failed
    git fetch origin  # Fetch the latest updates from the remote repository
    LOCAL=$(git rev-parse HEAD)  # Get the current local commit hash
    REMOTE=$(git rev-parse origin/main)  # Get the remote commit hash (replace 'main' if using a different branch)

    if [ "$LOCAL" != "$REMOTE" ]; then
        echo "Updates found, pulling from GitHub..."
        git pull origin main  # Pull the latest updates from the remote (replace 'main' with your branch if needed)
        ~/Triviaverse-web/git_pull.sh  # Trigger the git pull script
    else
        echo "No updates found."
    fi

    sleep $CHECK_INTERVAL  # Wait before checking again
done
