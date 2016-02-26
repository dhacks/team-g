var mode;
var timeLimit=10; // 録音制限 秒
var host = 'http://localhost/dhacks/'; // http://localhost/wami/ 等
var recording = 'temp.wav'; // ファイル名
var file_name = 'temp.wav';
var recordInterval, playInterval;
var gage = new JustGage({
    id: 'meter', 
    value: 0,
    min: 0, // wamiのlevel戻り値は0-100
    max: 100,
    showMinMax: false,
    title: 'Ready'
}); 
var flag = 0;


Wami.setup({id:'wami'});

/*
function clickRecord(){
    mode = 'record';
    var recordingUrl = host+'save.php?name='+recording;
    Wami.startRecording(recordingUrl,Wami.nameCallback(recordStart),Wami.nameCallback(recordFinish),Wami.nameCallback(wamiError));
}*/

function clickRecord(){

    // 生成する文字列の長さ
    if(file_name == 'temp.wav'){
        var l = 32;
    // 生成する文字列に含める文字セット
    var c = "abcdefghijklmnopqrstuvwxyz0123456789";
    var cl = c.length;
    file_name = "";
    for(var i=0; i<l; i++){
      file_name += c[Math.floor(Math.random()*cl)];
    }
    file_name += '.wav';
    }else{
    }
    mode = 'record';
    var recordingUrl = host+'save.php?name='+file_name;
    Wami.startRecording(recordingUrl,Wami.nameCallback(recordStart),Wami.nameCallback(recordFinish),Wami.nameCallback(wamiError));
    $("input[name=file_name]").val(file_name);
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
    var playUrl = host+'record_data/'+file_name+'?_='+(new Date)/1; // キャッシュ回避
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


function fileReset(){
    file_name = 'temp.wav';
}

function clickOnAir( on_air_name ){
    mode = 'play';
    alert(on_air_name);
    var playUrl = host+'record_data/'+on_air_name+'?_='+(new Date)/1; // キャッシュ回避
    Wami.startPlaying(playUrl,Wami.nameCallback(playStart),Wami.nameCallback(playFinish),Wami.nameCallback(wamiError));
}

/*
function saveFile(){
    // 生成する文字列の長さ
    var l = 32;
    // 生成する文字列に含める文字セット
    var c = "abcdefghijklmnopqrstuvwxyz0123456789";
    var cl = c.length;
    file_name = "";
    for(var i=0; i<l; i++){
      file_name += c[Math.floor(Math.random()*cl)];
    }
    file_name += '.wav';

    alert(file_name);
    var FSO = new ActiveXObject( "Scripting.FileSystemObject" );
    FSO.CopyFile(host+'temp/temp.wav', host+'record_data/'+file_name);
    FSO = null;
    WScript.Echo( "終了" );
}*/