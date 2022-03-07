<?php

class DataLayer
{
    static function getGender()
    {
        return array('female', 'non-binary', 'male');
    }

    static function getIndoor()
    {
        return array('TV', 'Movies', 'Cooking', 'Cooking', 'Board Games', 'Puzzles',
            'Reading', 'Playing Cards', 'Video Games');
    }

    static function getOutdoor()
    {
        return array('Camping', 'Kayaking', 'Rock Climbing', 'Hiking', 'Surfing',
            'Snowboarding', 'Swimming');
    }
}
