<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Plyr.io Player -->
	<link rel="stylesheet" href="https://cdn.plyr.io/3.3.12/plyr.css">
	<script type="text/javascript" src="/js/jwplayer.js"></script>
	<!-- <script type="text/javascript" src="//cdn.jsdelivr.net/jwplayer/5.10/jwplayer.js"></script> -->
	<script>
		jwplayer.key = "zGhSOpbt7hbdG53nW3nDZE0vdyyjy0cNdaQNfA==";
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body style="margin:0px;">
	<script type="text/javascript">
		var bttCount = 4;
		var h = 0;
		//if(bttCount<2) h=1; else {if($(window).width()>(100*bttCount)) h=35; else h=100;}
		var video = {
			width: $(window).width(),
			height: $(window).height() - h
		};

		$(window).resize(function() {
			video.width = $(window).width(), video.height = $(window).height() - h, jwplayer().resize(video.width,
				video.height)
		});
	</script>

	<div id="myElement" style="width:100%!important;height:100%!margin-bottom:0px;"></div>
	<?php
	error_reporting(0);
	include "curl_gd.php";
	$base_url = 'http://demo.filedeo.stream/drive';
	function url()
	{
		if (isset($_SERVER['HTTPS'])) {
			$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "http" : "http";
		} else {
			$protocol = 'http';
		}
		return $protocol . "://gd." . $_SERVER['HTTP_HOST'];
	}
	if (isset($_GET['id'])) {
		$eid = htmlspecialchars($_GET['id']);
		$gid = my_simple_crypt($eid, 'd');
		// url() . 
		// $results = file_get_contents('https://gd.nontonindrama.com/Player-Script/json.php?url=https://drive.google.com/file/d/' . $gid . '/preview');
		$gid = get_drive_id("https://drive.google.com/file/d/$gid/view");
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
				width: video.width,
				aspectratio: "16:9",
				flashplayer: "//dldramaid.xyz/jw/jwplayer.flash.swf",
				height: video.height,
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
		</script><?php
						// $results = file_get_contents(url() . '/json.php?url=https://drive.google.com/file/d/' . $gid . '/view');
						// $results = json_decode($results, true);
						// if ($results['file'] == 1) {
						// 	echo '<center>Sorry, the owner hasn\'t given you permission to download this file.</center>';
						// 	exit;
						// } elseif ($results['file'] == 2) {
						// 	echo '<center>Error 404. We\'re sorry. You can\'t access this item because it is in violation of our Terms of Service.</center>';
						// 	exit;
						// }
						if (isset($results)) {
							echo $results;
						}
					}

					?>
</body>

</html>