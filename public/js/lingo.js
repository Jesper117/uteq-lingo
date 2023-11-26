let CurrentRow = 1;
let CurrentColumn = 1;
let Audios = [];

function PlaySound(SoundName, Loop) {
    SoundName = SoundName + '.mp3';

    var audio = new Audio('/sound/' + SoundName);
    audio.loop = Loop || false;
    audio.play();

    Audios.push(audio);
}

function StopAllSounds() {
    Audios.forEach(audio => {
        audio.pause();
        audio.currentTime = 0;

        Audios.splice(Audios.indexOf(audio), 1);
    });
}

function ResetGrid() {
    $('.square.default').empty();
    $('.square').removeClass('correct', 'otherposition', 'incorrect');

    CurrentRow = 1;
    CurrentColumn = 1;
}

function SetLetter(X, Y, Letter, Type) {
    const row = document.querySelector(`.row${Y}`);
    if (row) {
        const cell = row.querySelector(`.square:nth-child(${X})`);
        if (cell) {
            cell.textContent = Letter ? Letter.toUpperCase() : '';
            cell.className = `square ${Type.toLowerCase()}`;
        }
    }
}


function NewWord(CSRF_TOKEN) {
    $.ajax({
        url: '/newword',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN
        },
        success: function (FirstLetter) {
            console.log(FirstLetter);

            FirstLetter = FirstLetter.substring(0, 1); // Temporary

            ResetGrid();
            SetLetter(1, 1, FirstLetter, 'Default');
        }
    });

}

function Guess(Guess, CSRF_TOKEN) {
    $.ajax({
        url: '/guess/' + Guess,
        type: 'POST',
        data: {
            _token: CSRF_TOKEN
        },
        success: function (data) {
            // Loop through the returned data with a delay for each character
            let delay = 0;
            let totalCharacters = Guess.length;

            for (let i = 0; i < totalCharacters; i++) {
                // Use an IIFE (Immediately Invoked Function Expression) to capture the correct value of variables
                (function (i) {
                    // Use setTimeout to introduce a delay before updating the grid for each character
                    setTimeout(function () {
                        // Update the grid with the character
                        const adjustedPosition = i + 1;
                        const currentLetter = Guess[i];

                        // Determine the category for the current character
                        let category;
                        let sound;
                        if (data['Correct'].includes(i)) {
                            category = 'Correct';
                            sound = 'CorrectLetterBeep';
                        } else if (data['OtherPosition'].includes(i)) {
                            category = 'OtherPosition';
                            sound = 'OtherPlaceLetterBeep';
                        } else {
                            category = 'Incorrect';
                            sound = 'WrongLetterBeep';
                        }

                        // Update the grid based on the category
                        SetLetter(adjustedPosition, CurrentRow, currentLetter, category);
                        PlaySound(sound);

                        // Move to the next row for the next guess only after revealing all characters
                        if (--totalCharacters === 0) {
                            CurrentRow++;

                            // Check if the word was guessed correctly
                            if (data['Correct'].length === 5) {
                                StopAllSounds();
                                PlaySound('CorrectWord');
                            } else {
                                // Auto-fill the next row with correct letters from the previous guess
                                if (CurrentRow <= 5) {
                                    autoFillNextRow(data['Correct'] || [], Guess);
                                }

                                // If all rows are used, trigger the end of the game or any relevant action
                                if (CurrentRow > 5) {
                                    StopAllSounds();
                                    PlaySound('RanOutOfTime');
                                } else {
                                    // Clear the input field for the next guess
                                    $('.inputfield').val('').prop('disabled', false).focus();
                                }
                            }
                        }
                    }, delay);
                })(i);

                delay += 220;
            }
        }
    });
}

function autoFillNextRow(positions, Guess) {
    positions.sort((a, b) => a - b); // Sort positions for auto-filling in order
    positions.forEach(position => {
        // Adjust position by adding 1, as positions start from 0
        const adjustedPosition = position + 1;

        // Use setTimeout to introduce a delay before auto-filling the next row
        setTimeout(function () {
            // Auto-fill the next row with correct letters from the previous guess
            SetLetter(adjustedPosition, CurrentRow, Guess[position], 'Default');
        }, 220);
    });
}







$(document).ready(function () {
    $('.inputfield').val('');
    $('.inputfield').focus();

    NewWord(CSRF);
    PlaySound('Tense', true);

    $('.inputfield').keyup(function () {
        if ($(this).val().length == 5) {
            $(this).prop('disabled', true);

            var Input = $(this).val();
            Guess(Input, CSRF);
        }
    });
});
