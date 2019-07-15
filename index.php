<?php
public function video(){
    //Path To Your Video Folder
		$url = "/var/www/shantanusk/assets/videos/raw";
		// Simple Call: List all files
		//var_dump($this->getDirContents('/var/www/kreate/stories/assets/videos/raw'));

		// Regex Call: List php files only
		$result = $this->getDirContents($url, '/\.mp4$/');
		echo "<pre>";
		print_r($result);
		foreach ($result as $video) {
        //Video Name With Full Path
        $videoname = $video;
        //Start Cut Time
				$startsec = 7;
        //End Cut Time
				$endsec = 20;
        //Output Url With Filename
				$output = str_replace("raw","video", $video);
				$logo = $url."/logo.jpg";
				echo "<br />Video Edit Started....";
        //Screenshot Url with filename
				$output_screen = str_replace(".mp4",".jpg", str_replace("raw","screen", $video));
			  exec("ffmpeg -t $(( $(ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $videoname |cut -d\. -f1) - $endsec )) -ss $startsec -i $videoname -i $logo -filter_complex '[0:v][1:v] overlay=main_w-overlay_w-5:5'  -pix_fmt yuv420p $output;", $result);
				echo "<br />Video Edit Completed";
        //Command To Create Screenshot
        exec("ffmpeg -t $(( $(ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $videoname |cut -d\. -f1) - $endsec )) -ss $startsec -i $videoname -i $logo -filter_complex '[0:v][1:v] overlay=main_w-overlay_w-5:5'  -pix_fmt yuv420p $output_screen;", $result);
				echo "<br />Screenshot Created";
        //Move Video After Edit To Different Folder
				$mv = str_replace("raw","remove", $video);;
				exec("mv $videoname $mv");
		}
	}

  // To List Of Videos In The Folders
	function getDirContents($dir, $filter = '', &$results = array()) {
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);

        if(!is_dir($path)) {
            if(empty($filter) || preg_match($filter, $path)) $results[] = $path;
        } elseif($value != "." && $value != "..") {
            $this->getDirContents($path, $filter, $results);
        }
    }

    return $results;
}
video();
?>
