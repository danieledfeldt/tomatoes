# tomatoes

camera

# Crontab
I choosed to take a picture every hour from 9:00-18:00. If you would like another interval between your photos and would like help to set up the interval I can recommend you http://crontab-generator.org/. 

Edit your config by typeing `crontab -e` in the terminal and add the following lines 
`9-18 * * * sh /home/pi/timelapse/camera.sh` . 
`0 2 * * * sh /home/pi/videoscript.sh` 

## The camera script (/home/pi/timelapse/camera.sh) 
This script will take a picture with todays date then copy the file to the public html folder. After that it will create a thumbnail since the original picture is not appropriate for the web (it will take up 2.5 mb). 

```
#! /bin/bash
DATE=$(date +"%Y-%m-%d_%H%M") 
raspistill -o /home/pi/timelapse/$DATE.jpg 
cp /home/pi/timelapse/$DATE.jpg /var/www/tomatoes/
convert -thumbnail 800 /var/www/tomatoes/$DATE.jpg /var/www/tomatoes/thumb_800_$DATE.jpg
```

## The video script (/home/pi/videoscript.sh)
This script will
1. Find all .jpg files in the folder except the thumbnails, sort them and list them in a text file.
2. Create a video in avi format from the pictures listed in the text file 
3. Convert the avi to mp4 with ffmpeg. 
```
#! /bin/bash
cd /var/www/tomatoes/
DATE=$(date +"%Y-%m-%d")
find -name "*.jpg*" ! -name "*thumb*" | sort -n > listofvideos.txt
mencoder -nosound -ovc lavc -lavcopts vcodec=mpeg4:aspect=16/9:vbitrate=8000000 -vf scale=1920:1080 -o timelapse2.avi -mf type=jpeg:fps=8 mf://@listofvideos.txt
ffmpeg -i timelapse2.avi -acodec aac -strict experimental -ac 2 -ab 128k -vcodec libx264 -vpre slow -f mp4 -crf 22 -s 640x360 $DATE.mp4
``` 

#More to come 
