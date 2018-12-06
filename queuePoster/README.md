# Humblr PHP API
A simple PHP Wrapper that Posts content on Humblr

## Installation
Just install PHP and run the File with php run.php

## Download
[The SDK](/queuePoster/run.php)

## Usage
```php
// Create a new API Object and initialize it with your App Token (See below)
$api=new HumblrAPI("yourToken");

// Add some Media by giving it the Path to the File. You can also specify where to crop (See below)
$api->AddMedia("./imgs/myImage.jpg");

// Add an actual Text to your Status (When adding Media this is optional)
$api->SetStatus("My cool Status...");

// Actually post the Status to your Timeline
$api->Publish();
```

## Full Example for a simple Image Queue
```php
  ...

	// The Path to your Queue folder containing the Images
	define("PATH","./queue");
	
	// The minimum Time between two Posts [WARNING: The Minimum time has to be 60, otherwise the API will block you!]
	define("MIN_SLEEP",60);
	
	// The maximum Time between two Posts
	define("MAX_SLEEP",120);
	
		
	while (true)
	{
		// Get all Images in Path
		$files=glob(PATH."/*.jpg");
		echo "\n\nQueue has ".count($files)." images left...\n";
		
		// Select one at random
		$currentImage=$files[array_rand($files)];
		
		// Post the Image to the API
		$api=new HumblrAPI("YOUR_TOKEN");
		$api->AddMedia($currentImage);
		$api->SetStatus("Your Status Text");
		$api->Publish();
		
		// Remove the Image from the Queue
		unlink($currentImage);
	
		// Wait for the defined amount of time to post the next image
		// Also Display the remaining time
		for ($i=rand(MIN_SLEEP,MAX_SLEEP);$i>=0;$i--)
		{
			echo "\rNext Post in ".$i."s...";
			sleep(1);
		}
	}
```

## Methods
### Constructor
```php
$api=new HumblrAPI($token);
```
$token - The Token you get from your Humblr App. (See below)

### AddMedia
```php
$api->AddMedia($filePath,$focus);
```
$filePath - The Path to the Image File you want to upload
$focus - Where to crop your Thumbnail Image (See here for more Info https://github.com/jonom/jquery-focuspoint#1-calculate-your-images-focus-point)

### SetTimeout
```php
$api->SetTimeout($retry);
```
$retry - How many Times the API should retry an Upload (Default is 15x, which equals to a Timeout of 15 Minutes)

### SetStatus
```php
$api->SetStatus($text);
```
$text - Your Status Text. Is optional when having media. Otherwise you need to specify a Text here!

### SetSpoiler
```php
$api->SetSpoiler($text);
```
$text - Your Spiler Text. Is completely optional

### Publish
```php
$api->Publish();
```
No Arguments, but this function needs to be called to actually Post your Status


## Creating a Humblr App to get acces to your Token
1. Go to https://humblr.social/settings/applications
2. Create an Application
3. Be sure to include the following Permissions (read, write, write:media, write:statuses)
4. Click on your newly created App and copy "Your access token".
