<?php

class Card {
    // bitfields represent rank
    const RANK = array(
        // preserve 1 << 0 for Ace-low situations
        "2" => 1 << 1,
        "3" => 1 << 2,
        "4" => 1 << 3,
        "5" => 1 << 4,
        "6" => 1 << 5,
        "7" => 1 << 6,
        "8" => 1 << 7,
        "9" => 1 << 8,
        "T" => 1 << 9,
        "J" => 1 << 10,
        "Q" => 1 << 11,
        "K" => 1 << 12,
        "A" => 1 << 13
    );
    const RANK_MASK = 0x3ffe;

    // suit accounts for the space used up by rank
    const SUIT = array(
        "C" => 1 << 14,
        "D" => 1 << 15,
        "H" => 1 << 16,
        "S" => 1 << 17
    );
    const SUIT_MASK = 0x3c000;

    // converts 2-letter code ("2C", "JS", "KD") into int value
    static function code2val(string $code) {
        if (strlen($code) != 2) {
            throw new Exception("Card '$code' has to be a 2-letter code");
        }
        $r = $code[0];
        $s = $code[1];

        if (!array_key_exists($r, self::RANK) || !array_key_exists($s, self::SUIT)) {
            throw new Exception("Card '$code' is not a valid code");
        }

        return self::RANK[$r] + self::SUIT[$s];
    }


    static function val2code(int $val) {
        $r = $val & self::RANK_MASK;    // clear suit values, leave rank only
        $s = $val & self::SUIT_MASK;

        return array_search($r, self::RANK) . array_search($s, self::SUIT);
    }

    // helper function to swap the default suit bit first order
    static function valRankFirst(int $valSuit) {
        $s = $valSuit & self::SUIT_MASK;    // clear rank values leaving only suit bit
        $r = $valSuit & self::RANK_MASK;

        // swap rank and suit positions
        return ($r << 4) + ($s >> 13);
    }

    static function val_suit(int $val) {
        return $val & self::SUIT_MASK; 
    }

    static function val_rank(int $val) {
        return $val & self::RANK_MASK; 
    }

    // returns hex value of the card
    static function val_hex(int $val) {
        return dechex($val);
    }
    
    // internally card value is stored as an integer
    // consisting of a rank bit followed by a suit bit
    private int $_card;

    function __construct(string $code) {
        // convert provided 2-letter code into internal representation
        $this->_card = self::code2val($code);
        //echo("Card() ". self::val2code($this->_card) . " [" . self::val_hex($this->_card) . "] created\n");
    }

    // returns internal value of the card
    function get_card() {
        return $this->_card;
    }

    // returns human readable code of the card
    function display() {
        print(self::val2code($this->_card));
    }
}
?>