<?php

class Pluralizer
{
    protected $Vowels;
    protected $Consonants;
    protected $NonRegularWord;

    public function __construct()
    {
        $this->Vowels = array('a', 'e', 'i', 'o', 'u', 'y');
        $this->Consonants = array('b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'v', 'w', 'x', 'z');

        $this->NonRegularWord = array(
            'Child' => 'Children',
            'Woman' => 'Women',
            'Man' => 'Men',
            'Mouse' => 'Mice',
            'Goose' => 'Geese'
        );
    }

    public function Pluralize($word) {
        // Handle special cases
        if (array_key_exists($word, $this->NonRegularWord)) {
            return $this->NonRegularWord[$word];
        } else if ($this->EndWithOr($word, array('ch', 'x', 's'))) {
            return $word . 'es';
        } else if ($this->EndWithOr($word, array('o'))) {
            return $word . 'es';
        }  else if ($this->EndWithOr($word, array('f', 'fe'))) {
            return replaceLastOccurence($word, 'f', 'v') . 'es';
        } else if ($this->EndWithOr($word, array('y'))) {
            return replaceLastOccurence($word, 'y', 'ies');
        } else if ($this->EndWithOr($word, array('ix'))) {
            return replaceLastOccurence($word, 'ix', 'ices');
        } else {
            return $word . 's';
        }
    }

    public function EndWithOr($subject, $array){
        foreach($array as $item){
            if(endsWith($subject, $item)){
                return true;
            }
        }

        return false;
    }

    public function IsConsonant($letter)
    {
        return in_array($letter, $this->Consonants);
    }

    public function IsVowel($letter)
    {
        return in_array($letter, $this->Vowels);
    }
}