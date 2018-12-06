<?php

	class HumblrAPI
	{
		private $endpoint="https://humblr.social/api/v1";
	
		private $token=null;
		function __construct($token)
		{
			$this->token=$token;
		}
		
		private $timeout=15;
		public function SetTimeout($timeout)
		{
			$this->timeout=$timeout;
		}
		
		private $mediaIDs=[];
		public function AddMedia($file,$focus="0,0")
		{
			// Only 4 Media
			if (count($this->mediaIDs>=4)
			{
				echo "ERROR: You can add a maximum of 4 media elements only!"."\n";
			}
			else
			{
				// Make sure to retry if Server fails at some Point.
				for ($i=0;$i<$this->timeout;$i++)
				{
					// Post Media to the API
					$media=json_decode(shell_exec('curl --silent -X POST "'.$this->endpoint.'/media" -H "Authorization: Bearer '.$this->token.'" -H "Content-Type: multipart/form-data;" -F "file=@'.$file.'" -F "focus='.$focus.'"'));
					if (isset($media->id))
					{
						// Media upload success
						$this->mediaIDs[]=$media->id;
						break;
					}
					else
					{
						// Media upload error, try again in 60 seconds
						echo "ERROR: Media could not be uploaded!"."\n";
						print_r($media);
						sleep(60);
					}
				}
			}
		}
		
		private $status=null;
		public function SetStatus($text)
		{
			$this-status=$text;
		}
		
		private $spoiler=null;
		public function SetSpoiler($spoiler)
		{
			$this->spoiler=$spoiler;
		}
		
		public function Publish()
		{
			// Make sure to retry if Server fails at some Point.
			for ($i=0;$i<$this->timeout;$i++)
			{
				// Post Media to the API
				$status=json_decode(shell_exec('curl --silent -X POST "'.$this->endpoint.'/statuses" -H "Authorization: Bearer '.$this->token.'" -H "Content-Type: multipart/form-data;" '.implode(" ",array_map(function($v){ return '-F "media_ids[]='.urlencode($v).'"'; },$this->mediaIDs)).' '.($this->status!==null?' -F "status='.urlencode($this->status).'" ':'').' '.($this->spoiler!==null?' -F "spoiler_text='.urlencode($this->spoiler).'" ':'').''));
				if (isset($status->id))
				{
					// Status upload success
					echo "SUCCESS: Status created with ID ".$status->id."\n";
					break;
				}
				else
				{
					// Media upload error, try again in 60 seconds
					echo "ERROR: Media could not be uploaded!"."\n";
					print_r($media);
					sleep(60);
				}
			}
		}
	}


	// Usage:
	$api=new HumblrAPI("yourToken");
	$api->AddMedia("myImage.jpg");
	$api->SetStatus("This is my awesome Status...");
	$api->Publish();



?>
