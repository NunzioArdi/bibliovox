let bRecord = $("#bRecord");
let bPause = $("#bPause");
let bPlay = $("#bPlay");
let bReset = $("#bReset");
let bUpload = $("#bUpload");

let recording = false;

let leftchannel = [];
let rightchannel = [];
let recorder = null;
let recordingLength = 0;
let mediaStream = null;
let sampleRate = 44100;
let context = null;
let blob = null;

bRecord.on("click", () => {
    if (!recording)
        navigator.mediaDevices.getUserMedia({audio: true}).then(record).catch(gestionErreurUserMedia);
});

bPause.on("click", pause);

bPlay.on("click", play);

bReset.on("click", reset);

function gestionErreurUserMedia(erreur) {
    let baliseErreur = $("<div class='erreur'>Erreur : " + erreur + "</div>");
    $("#buttons").before(baliseErreur);
}

function record(stream) {

    bRecord.addClass("btn-danger");
    bPause.prop("disabled", false);
    bPlay.prop("disabled", false);
    bReset.prop("disabled", false);

    context = new AudioContext();
    mediaStream = context.createMediaStreamSource(stream);

    let bufferSize = 2048;
    let numberOfInputChannels = 2;
    let numberOfOutputChannels = 2;
    recorder = context.createScriptProcessor(bufferSize, numberOfInputChannels, numberOfOutputChannels);

    recorder.onaudioprocess = function (e) {
        leftchannel.push(new Float32Array(e.inputBuffer.getChannelData(0)));
        rightchannel.push(new Float32Array(e.inputBuffer.getChannelData(1)));
        recordingLength += bufferSize;
    };

    mediaStream.connect(recorder);
    recorder.connect(context.destination);
    recording = true;
}

function pause() {
    if (recording) {
        bRecord.removeClass("btn-danger");
        bUpload.prop("disabled", false);

        // stop recording
        recorder.disconnect(context.destination);
        mediaStream.disconnect(recorder);
        recording = false;

        // we flat the left and right channels down
        // Float32Array[] => Float32Array
        let leftBuffer = flattenArray(leftchannel, recordingLength);
        let rightBuffer = flattenArray(rightchannel, recordingLength);
        // we interleave both channels together
        // [left[0],right[0],left[1],right[1],...]
        let interleaved = interleave(leftBuffer, rightBuffer);

        // we create our wav file
        let buffer = new ArrayBuffer(44 + interleaved.length * 2);
        let view = new DataView(buffer);

        // RIFF chunk descriptor
        writeUTFBytes(view, 0, 'RIFF');
        view.setUint32(4, 44 + interleaved.length * 2, true);
        writeUTFBytes(view, 8, 'WAVE');
        // FMT sub-chunk
        writeUTFBytes(view, 12, 'fmt ');
        view.setUint32(16, 16, true); // chunkSize
        view.setUint16(20, 1, true); // wFormatTag
        view.setUint16(22, 2, true); // wChannels: stereo (2 channels)
        view.setUint32(24, sampleRate, true); // dwSamplesPerSec
        view.setUint32(28, sampleRate * 4, true); // dwAvgBytesPerSec
        view.setUint16(32, 4, true); // wBlockAlign
        view.setUint16(34, 16, true); // wBitsPerSample
        // data sub-chunk
        writeUTFBytes(view, 36, 'data');
        view.setUint32(40, interleaved.length * 2, true);

        // write the PCM samples
        let index = 44;
        let volume = 1;
        for (let i = 0; i < interleaved.length; i++) {
            view.setInt16(index, interleaved[i] * (0x7FFF * volume), true);
            index += 2;
        }

        // our final blob
        blob = new Blob([view], {type: 'audio/wav'});
        updateButtonUpload();
    }
}

function play() {
    if (recording)
        pause();

    if (blob != null) {
        let url = window.URL.createObjectURL(blob);
        console.log(url);
        let audio = new Audio(url);
        console.log(audio);
        audio.play();
    }
}

function reset() {
    if (recording)
        pause();

    leftchannel = [];
    rightchannel = [];
    recorder = null;
    recordingLength = 0;
    mediaStream = null;
    context = null;
    blob = null;
    bUpload.prop("disabled", true);
    bPause.prop("disabled", true);
    bPlay.prop("disabled", true);
    bReset.prop("disabled", true);
    bUpload.off();
}

function updateButtonUpload() {
    bUpload.prop("disabled", false);
    bUpload.click(function () {

        let data = new FormData();
        data.append('newAudio', blob);

        let path = new URL(window.location.href).pathname.split("/");

        let categorie = path[1];

        switch (categorie) {
            case "dictionnaire":
                data.append("id", path[4]);
                break;
            case "recueil":
                data.append("id", path[2]);
                break;
            case "production":
                data.append("nom", $("#prod > h5")[0].innerText);
                break;
        }

        $.ajax({
            url: `/${categorie}/upload`,
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            success: function () {
                if (categorie === "production")
                    window.location.href = window.location.origin + "/production";
                else
                    window.location.reload();
            },
            error: function (response) {
                console.log(response);
            }
        })
    })
}

function flattenArray(channelBuffer, recordingLength) {
    let result = new Float32Array(recordingLength);
    let offset = 0;
    for (let i = 0; i < channelBuffer.length; i++) {
        let buffer = channelBuffer[i];
        result.set(buffer, offset);
        offset += buffer.length;
    }
    return result;
}

function interleave(leftChannel, rightChannel) {
    let length = leftChannel.length + rightChannel.length;
    let result = new Float32Array(length);

    let inputIndex = 0;

    for (let index = 0; index < length;) {
        result[index++] = leftChannel[inputIndex];
        result[index++] = rightChannel[inputIndex];
        inputIndex++;
    }
    return result;
}

function writeUTFBytes(view, offset, string) {
    for (let i = 0; i < string.length; i++) {
        view.setUint8(offset + i, string.charCodeAt(i));
    }
}