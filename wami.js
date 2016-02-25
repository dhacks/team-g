var mode;
var timeLimit=10; // 録音制限 秒
var host = 'http://localhost/'; // http://localhost/wami/ 等
var recording = 'temp.wav'; // ファイル名
var recordInterval, playInterval;
var gage = new JustGage({
	id: 'meter', 
		value: 0,
		min: 0, // wamiのlevel戻り値は0-100
		max: 100,
		showMinMax: false,
		title: 'Ready'
}); 

Wami.setup({id:'wami'});

function clickRecord(){
	mode = 'record';
	var recordingUrl = host+'save.php?name='+recording;
	Wami.startRecording(recordingUrl,Wami.nameCallback(recordStart),Wami.nameCallback(recordFinish),Wami.nameCallback(wamiError));
}

function stopRecording(){
	Wami.stopRecording();
	recordFinish();
}

function recordStart(){
	setTimeout('stopRecording()',timeLimit * 1000);  // 録音時間制限
	recordInterval = setInterval(function(){
		var level = Wami.getRecordingLevel();
		updateMeter(level);
	},100);
}

function recordFinish(){
	clearInterval(recordInterval);
	updateMeter(0);
}

function clickPlay(){
	mode = 'play';
	var playUrl = host+recording+'?_='+(new Date)/1; // キャッシュ回避
	Wami.startPlaying(playUrl,Wami.nameCallback(playStart),Wami.nameCallback(playFinish),Wami.nameCallback(wamiError));
}

function stopPlaying(){
	Wami.stopPlaying();
	playFinish();
}

function playStart(){
	playInterval = setInterval(function(){
		var level = Wami.getPlayingLevel();
		updateMeter(level);
	},100);
}

function playFinish(){
	clearInterval(playInterval);
	updateMeter(0);
}

function clickStop(){
	if ( mode == 'record' ){
		stopRecording();
	} else if ( mode == 'play' ){
		stopPlaying();
	}
}

function wamiError( e ){
	alert(e);
}

function updateMeter( level ){
	gage.refresh(level);
}
