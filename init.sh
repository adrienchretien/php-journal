#!/bin/sh

# Variables
CONFIG_FILE="config/config"

# Functions

##
# Replace value by another according the key in the config file.
# $1 = key
# $2 = new value
config_replace() {
	grep -l $1": .*" $CONFIG_FILE | xargs sed -i 's|'$1': .*|'$1': '$2'|'
}

##
# Generate a random string
random_str() {
	str=$(< /dev/urandom tr -dc '[:graph:]' | tr -d "'|" | head -c $1)
	echo $(echo $str | sed 's.[&\\].×.g')
}

# Main
# Replace a the secret property value with a random 64 chararcters string
config_replace "secret" $(random_str 64)