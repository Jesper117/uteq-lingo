let Audios = [];

function PlaySound(SoundName, Loop) {
    SoundName = SoundName + '.mp3';

    var audio = new Audio('/sound/' + SoundName);
    audio.loop = Loop || false;
    audio.play();

    Audios.push(audio);
}

function StopAllSounds() {
    console.log('Stopping all sounds');

    Audios.forEach(audio => {
        audio.pause();
        audio.currentTime = 0;

        Audios.splice(Audios.indexOf(audio), 1);
    });
}
