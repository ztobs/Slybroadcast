// For customers

function restoreCu(){
    $("#record, #live").removeClass("disabled");
    $("#pause").replaceWith('<a class="button one" id="pause">Pause</a>');
    $(".one").addClass("disabled");
    Fr.voice.stop();
}

function makeWaveformCu(){
    var analyser = Fr.voice.recorder.analyser;

    var bufferLength = analyser.frequencyBinCount;
    var dataArray = new Uint8Array(bufferLength);

    /**
     * The Waveform canvas
     */
    var WIDTH = 500,
        HEIGHT = 200;

    var canvasCtx = $("#level")[0].getContext("2d");
    canvasCtx.clearRect(0, 0, WIDTH, HEIGHT);

    function draw() {
        var drawVisual = requestAnimationFrame(draw);

        analyser.getByteTimeDomainData(dataArray);

        canvasCtx.fillStyle = 'rgb(200, 200, 200)';
        canvasCtx.fillRect(0, 0, WIDTH, HEIGHT);
        canvasCtx.lineWidth = 2;
        canvasCtx.strokeStyle = 'rgb(0, 0, 0)';

        canvasCtx.beginPath();

        var sliceWidth = WIDTH * 1.0 / bufferLength;
        var x = 0;
        for(var i = 0; i < bufferLength; i++) {
            var v = dataArray[i] / 128.0;
            var y = v * HEIGHT/2;

            if(i === 0) {
                canvasCtx.moveTo(x, y);
            } else {
                canvasCtx.lineTo(x, y);
            }

            x += sliceWidth;
        }
        canvasCtx.lineTo(WIDTH, HEIGHT/2);
        canvasCtx.stroke();
    };
    draw();
}

$(document).ready(function(){
  $(document).on("click", "#record:not(.disabled)", function(){
    Fr.voice.record($("#live").is(":checked"), function(){
      $(".recordButton").addClass("disabled");

      $("#live").addClass("disabled");
      $(".one").removeClass("disabled");

      makeWaveformCu();
    });
  });



  $(document).on("click", "#stop:not(.disabled)", function(){
    restoreCu();
  });

  $(document).on("click", "#play:not(.disabled)", function(){
    if($(this).parent().data("type") === "mp3"){
      Fr.voice.exportMP3(function(url){
        $("#audio").attr("src", url);
        $("#audio")[0].play();
      }, "URL");
    }else{
      Fr.voice.export(function(url){
        $("#audio").attr("src", url);
        $("#audio")[0].play();
      }, "URL");
    }
    restoreCu();
  });

  $(document).on("click", "#download:not(.disabled)", function(){
    if($(this).parent().data("type") === "mp3"){
      Fr.voice.exportMP3(function(url){
        $("<a href='" + url + "' download='MyRecording.mp3'></a>")[0].click();
      }, "URL");
    }else{
      Fr.voice.export(function(url){
        $("<a href='" + url + "' download='MyRecording.wav'></a>")[0].click();
      }, "URL");
    }
    restoreCu();
  });



  $(document).on("click", "#save:not(.disabled)", function(){
    function upload(blob){
      var formData = new FormData();
      formData.append('file', blob);
      formData.append('name', $('#customer_name').val());
      formData.append('phone', $('#customer_phone').val());
      formData.append('type', 'customer');

      $.ajax({
        url: "upload",
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(data) {
              $("#audio").attr("src", data.url);
              $("#audio")[0].play();
              alert("Audio file has been saved, you can now close the popup"+data.insert_id);
          }
      });
    }
    if($(this).parent().data("type") === "mp3"){
      Fr.voice.exportMP3(upload, "blob");
    }else{
      Fr.voice.export(upload, "blob");
    }
    restoreCu();
  });
});


// For Campaigns

function restore(){
    $("#record_, #live_").removeClass("disabled");
    $("#pause_").replaceWith('<a class="button one" id="pause_">Pause</a>');
    $(".one").addClass("disabled");
    Fr.voice.stop();
}

function makeWaveform(){
    var analyser = Fr.voice.recorder.analyser;

    var bufferLength = analyser.frequencyBinCount;
    var dataArray = new Uint8Array(bufferLength);

    /**
     * The Waveform canvas
     */
    var WIDTH = 500,
        HEIGHT = 200;

    var canvasCtx = $("#level_")[0].getContext("2d");
    canvasCtx.clearRect(0, 0, WIDTH, HEIGHT);

    function draw() {
        var drawVisual = requestAnimationFrame(draw);

        analyser.getByteTimeDomainData(dataArray);

        canvasCtx.fillStyle = 'rgb(200, 200, 200)';
        canvasCtx.fillRect(0, 0, WIDTH, HEIGHT);
        canvasCtx.lineWidth = 2;
        canvasCtx.strokeStyle = 'rgb(0, 0, 0)';

        canvasCtx.beginPath();

        var sliceWidth = WIDTH * 1.0 / bufferLength;
        var x = 0;
        for(var i = 0; i < bufferLength; i++) {
            var v = dataArray[i] / 128.0;
            var y = v * HEIGHT/2;

            if(i === 0) {
                canvasCtx.moveTo(x, y);
            } else {
                canvasCtx.lineTo(x, y);
            }

            x += sliceWidth;
        }
        canvasCtx.lineTo(WIDTH, HEIGHT/2);
        canvasCtx.stroke();
    };
    draw();
}

$(document).ready(function(){
    $(document).on("click", "#record_:not(.disabled)", function(){
        Fr.voice.record($("#live_").is(":checked"), function(){
            $(".recordButton").addClass("disabled");

            $("#live_").addClass("disabled");
            $(".one").removeClass("disabled");

            makeWaveform();
        });
    });



    $(document).on("click", "#stop_:not(.disabled)", function(){
        restore();
    });

    $(document).on("click", "#play_:not(.disabled)", function(){
        if($(this).parent().data("type") === "mp3"){
            Fr.voice.exportMP3(function(url){
                $("#audio_").attr("src", url);
                $("#audio_")[0].play();
            }, "URL");
        }else{
            Fr.voice.export(function(url){
                $("#audio_").attr("src", url);
                $("#audio_")[0].play();
            }, "URL");
        }
        restore();
    });


    $(document).on("click", "#save_:not(.disabled)", function(){
        function upload(blob){
            var formData = new FormData();
            formData.append('file', blob);
            formData.append('name', $('#campaign_name').val());
            formData.append('phone', $('#campaign_phone').val());
            formData.append('type', 'campaign');

            $.ajax({
                url: "upload",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    $("#audio_").attr("src", data.url);
                    $("#audio_")[0].play();
                    alert("Audio file has been saved, you can now close the popup"+data.insert_id);
                }
            });
        }
        if($(this).parent().data("type") === "mp3"){
            Fr.voice.exportMP3(upload, "blob");
        }else{
            Fr.voice.export(upload, "blob");
        }
        restore();
    });
});