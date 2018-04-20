#!/usr/bin/env bash

# https://stackoverflow.com/questions/2013547/assigning-default-values-to-shell-variables-with-a-single-command-in-bash
# Do not change the following 3 lines. Instead, set these variables in your ~/.bash_profile file in your home directory.
: "${PI_HOST_NAME:=pi}"                # DO NOT CHANGE
: "${PI_USER_NAME:=pi}"                # DO NOT CHANGE
: "${PI_DEPLOY_FOLDER:=/var/www/html}" # DO NOT CHANGE

# https://stackoverflow.com/questions/15927911/how-to-check-if-dir-exists-over-ssh-and-return-results-to-host-machine
ssh -q "$PI_USER_NAME@$PI_HOST_NAME" "[ -f $PI_DEPLOY_FOLDER/$0 ]" &> /dev/null
IS_PI_ALIVE=$?

# https://stackoverflow.com/questions/59895/getting-the-source-directory-of-a-bash-script-from-within
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
BASE_DIR="$(dirname "$SCRIPT_DIR")"

usage () {
  cat <<HELP_USAGE

    The Raspberry PI was not found at "$PI_USER_NAME@$PI_HOST_NAME" or
    this git repository has not yet been cloned in the folder:

      $PI_USER_NAME@$PI_HOST_NAME:$PI_DEPLOY_FOLDER

    Please check these bash environment variables and try again:

      PI_HOST_NAME: The ssh host name or IP address of the Raspberry Pi.
      PI_USER_NAME: The ssh username for the Raspberry Pi.
      PI_DEPLOY_FOLDER: The path to this git repository cloned on the Raspberry Pi.

HELP_USAGE
  exit 0
}

if [ $IS_PI_ALIVE -ne 0 ]; then
  usage
fi

# https://kyup.com/tutorials/copy-files-rsync-ssh/
rsync -v -e ssh -r "$BASE_DIR/." "$PI_USER_NAME@$PI_HOST_NAME:$PI_DEPLOY_FOLDER"
