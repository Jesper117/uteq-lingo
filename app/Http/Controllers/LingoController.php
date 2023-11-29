<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LingoController
{
    public function index()
    {
        $highscores = $this->GetHighscores();

        return view('start', ['highscores' => $highscores]);
    }

    public function GetSession()
    {
        return session()->all();
    }

    private function StoreUser($Username)
    {
        $Username = strtolower($Username);
        $Username = ucwords($Username);

        $User = DB::table('users')->where('name', $Username)->first();

        if ($User === null) {
            DB::table('users')->insert([
                'name' => $Username
            ]);

            return intval(DB::getPdo()->lastInsertId());
        } else {
            return intval($User->id);
        }
    }

    private function StoreScore($UserId, $Score)
    {
        DB::table('scores')->insert([
            'user_id' => $UserId,
            'score' => $Score
        ]);
    }

    private function GetHighscores()
    {
        $Highscores = DB::table('scores')
            ->join('users', 'scores.user_id', '=', 'users.id')
            ->select('users.name', 'scores.score')
            ->orderBy('scores.score', 'desc')
            ->limit(10)
            ->get();


        // Handmatig filteren omdat er in de query niet beide group by en order by gebruikt kunnen worden tegelijk.
        $Names = array();
        $FilteredHighscores = array();
        foreach ($Highscores as $Highscore) {
            if (!in_array($Highscore->name, $Names)) {
                $Names[] = $Highscore->name;
                $FilteredHighscores[] = $Highscore;
            }
        }

        return $FilteredHighscores;
    }

    public function Play(Request $Request)
    {
        $name = $Request->input('name');
        $userId = $this->StoreUser($name);

        session(['userid' => $userId]);
        session(['score' => 0]);

        session(['wordcount' => 0]);
        session(['rowcount' => 0]);

        return view('lingo');
    }

    public function End()
    {
        $score = session('score');
        $userId = session('userid');

        $this->StoreScore($userId, $score);

        return redirect('/');
    }

    public function NewWord()
    {
        $WordCount = session('wordcount');

        if ($WordCount >= 5) {
            $this->End();
            return redirect('/');
        }

        $words = array(
            'appel',
            'armen',
            'basis',
            'beleg',
            'beter',
            'bezig',
            'buren',
            'dalen',
            'diner',
            'drank',
            'dwerg',
            'films',
            'fruit',
            'garen',
            'gazon',
            'halen',
            'heden',
            'hotel'
        );

        $word = $words[rand(0, count($words) - 1)];
        session(['word' => $word]);

        session(['wordcount' => $WordCount + 1]);
        session(['rowcount' => 1]);

        return substr($word, 0, 1);
    }

    public function Guess($Guess)
    {
        $Guess = strtolower($Guess);

        $result = array('Correct' => array(), 'OtherPosition' => array(), 'Incorrect' => array());
        $word = session('word');

        for ($i = 0; $i < strlen($Guess); $i++) {
            if ($Guess[$i] == $word[$i]) {
                $result['Correct'][] = $i;
            }
        }

        for ($i = 0; $i < strlen($Guess); $i++) {
            if ($Guess[$i] != $word[$i] && strpos($word, $Guess[$i]) !== false) {
                $result['OtherPosition'][] = $i;
            }
        }

        for ($i = 0; $i < strlen($Guess); $i++) {
            if ($Guess[$i] != $word[$i] && strpos($word, $Guess[$i]) === false) {
                $result['Incorrect'][] = $i;
            }
        }

        $RowCount = session('rowcount');
        $OutOfGuesses = false;
        if ($RowCount < 5) {
            session(['rowcount' => $RowCount + 1]);
        } else {
            $OutOfGuesses = true;
        }

        if (count($result['Correct']) == strlen($word)) {
            session(['score' => session('score') + 50 + (6 - $RowCount) * 10]);
        }

        $result['OutOfGuesses'] = $OutOfGuesses;

        return $result;
    }
}
