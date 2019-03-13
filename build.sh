#!/bin/bash

if [ -f compropago-oc2.ocmod.zip ]; then
    echo -e "\033[1;31mDeleting old file\033[0m"
    rm compropago-oc2.ocmod.zip
fi

# Dependencies
if [ -f upload/vendor/autoload.php ]; then
    echo "Composer status:" && composer status
else
    composer install
fi

echo -e "\033[1;33mRemove .DS_Store files\033[0m"
find . -name ".DS_Store" -delete

echo -e "\033[1;32mBuilding zip extension for OpenCart 3.x\033[0m"
zip compropago-oc2.ocmod.zip -r upload -x "*.DS_Store"