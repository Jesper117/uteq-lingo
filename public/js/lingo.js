let CurrentRow = 1;
let CurrentColumn = 1;
let GuessDb = false;

function ResetGrid() {
    $('.square.default').empty();

    $('.square').removeClass('correct', 'otherposition', 'incorrect', 'default');
    $('.square').addClass('default');

    $('.square').text('');

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
            if (FirstLetter.length === 1) {
                ResetGrid();
                SetLetter(1, 1, FirstLetter, 'Default');

                PlaySound('Tense', true);

                $('.inputfield').prop('disabled', false);

                $('.inputfield').val('');
                $('.inputfield').focus();
            } else {
                window.location.replace('/');
            }
        }
    });
}

function Guess(Guess, CSRF_TOKEN) {
    if (GuessDb) {
        return;
    }

    GuessDb = true;

    $.ajax({
        url: '/guess/' + Guess,
        type: 'POST',
        data: {
            _token: CSRF_TOKEN
        },
        success: function (data) {
            let delay = 0;
            let totalCharacters = Guess.length;

            for (let i = 0; i < totalCharacters; i++) {
                (function (i) {
                    setTimeout(function () {
                        const adjustedPosition = i + 1;
                        const currentLetter = Guess[i];

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

                        SetLetter(adjustedPosition, CurrentRow, currentLetter, category);
                        PlaySound(sound);

                        if (--totalCharacters === 0) {
                            CurrentRow++;

                            if (data['Correct'].length === 5) {
                                //StopAllSounds();
                                PlaySound('CorrectWord');
                            } else {
                                if (CurrentRow <= 5) {
                                    autoFillNextRow(data['Correct'] || [], Guess);
                                }

                                if (CurrentRow > 5) {
                                    //StopAllSounds();
                                    PlaySound('RanOutOfTime');
                                } else {
                                    $('.inputfield').val('').prop('disabled', false).focus();
                                }
                            }
                        }
                    }, delay);
                })(i);

                delay += 220;
            }

            setTimeout(function () {
                if (data['OutOfGuesses'] === true || data['Correct'].length === 5) {
                    //StopAllSounds();

                    if (data['OutOfGuesses'] === true) {
                        PlaySound('RanOutOfTime');
                    }

                    setTimeout(function () {
                        ResetGrid();
                        NewWord(CSRF_TOKEN);

                        GuessDb = false;
                    }, 4000);
                }

                GuessDb = false;
            }, delay + 220);
        }
    });
}

function autoFillNextRow(positions, Guess) {
    positions.sort((a, b) => a - b);
    positions.forEach(position => {
        const adjustedPosition = position + 1;

        setTimeout(function () {
            SetLetter(adjustedPosition, CurrentRow, Guess[position], 'Default');
        }, 220);
    });
}


$(document).ready(function () {
    NewWord(CSRF);

    $('.inputfield').keyup(function () {
        if ($(this).val().length == 5) {
            $(this).prop('disabled', true);
            var Input = $(this).val();

            $('.inputfield').val('');

            Guess(Input, CSRF);
        }
    });
});
