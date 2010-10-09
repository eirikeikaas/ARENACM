#!/bin/sh

# Create a distribution

ARENA="arena`cat version.txt`"

./updatefiles.php > log.txt
mkdir arena
cp -R admin arena/
cp -R lib arena/
cp -R web arena/
cp -R extensions arena/
tar -czf ${ARENA}.tgz arena
rm -fr arena
