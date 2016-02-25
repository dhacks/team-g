<html>
	<head>
		<meta charset="UTF-8" />
	</head>
	<body>
		<div id="recorder" style="width: 340px; text-align: center;">
			<button id="record" onclick="clickRecord()">録音</button>
			<button id="stop" onclick="clickStop()">停止</button>
			<button id="play" onclick="clickPlay()">再生</button>
			<div id="wami"></div>
			<div id="meter" style="width: 340px; height: 200px;"></div> <!--justgageはサイズ指定必須-->
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
		<script src="recorder.js"></script>
		<script src="raphael-2.1.4.min.js"></script>
		<script src="justgage.js"></script>
		<script src="wami.js"></script>
	</body>
</html>


