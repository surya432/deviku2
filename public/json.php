<?php
error_reporting(0);
include "curl_gd.php";
$url = isset($_GET['url']) ? htmlspecialchars($_GET['url']) : null;
if (empty($url)) {
	$url = '0ByaRd0R0Qyatcmw2dVhQS0NDU0U';
}
$gid = get_drive_id("https://drive.google.com/file/d/$url/view");
$sourcesvideo = GoogleDrive($gid);

?>

<script type="text/javascript">
	var currentTime = 0;
	var sources_video = <?php
						if ($sourcesvideo) {
							echo '[' . $sourcesvideo . "];";
						} else {
							echo $sourcesvideo;
						}/* else{
									$start->vidlinks($row["embed"]); 
									$start->url= $row["url"].'-'.md5($row["embed"]);
									$link=$start->cloudstreamapi23($row["iframe2"]);
									if($link!=""){
										echo $link;	
									}else{
										echo '['.$file2."]";

									}
							} */
						?>
	
	var daplayer = jwplayer("myElement").setup({
		tracks: [{
			file: '<?php if ($sub) echo $sub; ?>',
			label: "Indonesia",
			"default": true,
			kind: "captions"
		}],
		controls: true,
		displaytitle: true,
		width: w,
		aspectratio: "16:9",
		flashplayer: "//dldramaid.xyz/jw/jwplayer.flash.swf",
		height: h,
		fullscreen: "true",
		skin: {
			"name": "customs",
			"url": "//dldramaid.xyz/jw/prime.min.css"
		},
		captions: {
			color: "#ffffff",
			fontSize: 18,
			backgroundOpacity: 50,
			edgeStyle: "dropshadow",
		},
		autostart: false,
		"primary": "html5",
		//"advertising": {
		//	"tag": "https://www.vidcpm.com/watch.xml?key=e7e88d1f99faf712d70473161f657524&custom=%7B%27width%27%3A%27__player-width__%27%2C%27height%27%3A%27__player-height__%27%7D&vastref=__page-url__&cb=__random-number__",
		//	"client": "vast",
		//	"skipoffset": 5,
		//	"skipmessage": 'Skip this ad in XX',
		//	"vpaidmode": "insecure",
		//	"companiondiv": {
		//		"id": "sample-companion-div",
		//		"height": 250,
		//		"width": 300,
		//	}
		//},
		abouttext: "nontonindrama.com",
		aboutlink: "http://nontonindrama.com",
		sources: sources_video
	}).addButton(
		//"//i.imgur.com/cAHz5k9.png",
		"//i.imgur.com/bfcWPdI.png",
		"Download Video",
		function() {
			showPlayer('download_links');
			var kI = daplayer.getPlaylistItem(),
				kcQ = daplayer.getCurrentQuality();
			if (kcQ < 0) {
				kcQ = 0;
			}
			if (kI.sources[kcQ].file.lastIndexOf('googlevideo.com') > 0) {
				var kF = kI.sources[kcQ].file + "&title=<?php echo htmlspecialchars_decode($urldownload, ENT_QUOTES); ?>";
			} else {
				var kF = kI.sources[kcQ].file + "&title=NontonOnlineDrama.co-<?php echo htmlspecialchars_decode($urldownload, ENT_QUOTES); ?>-" + kI.sources[kcQ].label + ".mp4";
				var kF1 = kF.replace("video.mp4", "<?php echo htmlspecialchars_decode($urldownload, ENT_QUOTES); ?>.mp4");
				//kF1= kF1.replace("/pd2/","/pd/");
				//kF= kF1.replace("/index.m3u8","");
				kF = kF1.replace("e=view", "e=download");
			}
			jwplayer("myElement").pause(true);
			window.open(kF, '_blank');

		},
		"download"
	).on("error", function(e) {
		daplayer.load();
		daplayer.play();
	});
</script>