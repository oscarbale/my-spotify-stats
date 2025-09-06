# my-spotify stats 
This repository is a simple php script that'll format your data requested from spotify 

## Prerequisites 
You need to have PHP setup on your machine, as this project utilises PHP's built-in web server

You will also need a copy of your listening data from spotify, see information on how to request that information [here](https://support.spotify.com/uk/article/data-rights-and-privacy-settings/) <br>
*Note: This script will only process "Your Extended streaming history" so only include this in `/data`*

## How to run 
You'll need to have PHP setup on your device, then simply clone this repository and place the files from your data
request in the `/data` directory. 

Once completed navigate to the root directory of this repository and run the following command
```
php -S localhost:8000
```