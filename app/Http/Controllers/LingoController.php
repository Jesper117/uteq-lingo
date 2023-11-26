<?php

namespace App\Http\Controllers;

class LingoController
{
    public function index()
    {
        return view('lingo');
    }

    public function NewWord()
    {
        $words = array(
            'appel',
            'armen',
            'basis',
            'bedel',
            'beleg',
            'beter',
            'beven',
            'bezig',
            'buren',
            'dalen',
            'delen',
            'diner',
            'drank',
            'druil',
            'duren',
            'dwerg',
            'erger',
            'filet',
            'films',
            'flora',
            'frons',
            'fruit',
            'garen',
            'gazon',
            'gilde',
            'graal',
            'graat',
            'gruis',
            'halen',
            'heden',
            'hotel'
        );

        $word = $words[rand(0, count($words) - 1)];
        session(['word' => $word]);

        //return substr($word, 0, 1);
        return $word;
    }

    public function Guess($Guess)
    {
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

        return $result;
    }



}
