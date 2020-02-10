<?php
class Hand {
    private const MAX_LEN = 52; // no more than this many cards

    private $_hand;

    function __construct() {
        $this->_hand = array();
    }
    
    // simple insertion sort for one dimentional array
    private static function insertSort(array &$arr) {
        for ($i = 1; $i < count($arr); $i++) {
            $val = $arr[$i];
            for ($j = $i - 1; $j >= 0 && $arr[$j] > $val; $j--) {
                $arr[$j+1] =  $arr[$j];
            }
            $arr[$j+1] = $val;
        }
    }


    function display() {
        print("Hand: ");
        foreach ($this->_hand as $c) {
            print(Card::val2code($c) . " ");
        }
        print("\n");
    }


    function addCard(Card $card) {
        if (count($this->_hand) == self::MAX_LEN) {
            throw new Exceptions("Maximum hand capacity (" . self::MAX_LEN . ") has been reached.");
        }

        array_push($this->_hand, $card->get_card());
        $this->sortBySuit();    // resort
    }


    function sortBySuit() {
        // by default most significant bits are suit ones
        // the data is nearly sorted
        self::insertSort($this->_hand);
    }


    function sortByValue() {
        $map = array();     // keeps track of the old order

        // suit bits need to be shifted to the tail before sorting
        foreach ($this->_hand as $c) {
            $map[$c] = Card::valRankFirst($c);
        }
        $rank1st = array_values($map);
        self::insertSort($rank1st);

        // final resorted array
        $out = array();
        // in the newly sorted order
        foreach ($rank1st as $goofy) {
            // fetch true keys from the map
            array_push($out, array_search($goofy, $map));
        }

        $this->_hand = $out;    // overwrite
    }
    

    function hasStraight(int $len, bool $sameSuit) {
        $mask = 0;
        // Straight mask is 0x11111 for 5 card
        for ($i = 0; $i < $len; $i++) {
            $mask |= 1 << $i;
        }

        // aggregate hands by suits
        $sum = array(
            Card::SUIT["C"] => 0,
            Card::SUIT["D"] => 0,
            Card::SUIT["H"] => 0,
            Card::SUIT["S"] => 0
        );

        foreach ($this->_hand as $c) {
            // exract suit and rank
            $s = Card::val_suit($c);
            $r = Card::val_rank($c);
            $sum[$s] |= $r;
            // duplicate Ace hand to account for Ace-low
            if ($r == Card::RANK["A"]) {
                $sum[$s] |= 1;  // Ace-low becomes 0x01 before "2" (0x10)
            }
        }
        
        $all_cards = 0;
        // evaluate one suit at a time for Flush first
        foreach (array_values($sum) as $cards) {
            $all_cards |= $cards;   // aggregate for future
            if (Hand::match_mask($cards, $mask, $len)) {
                return true;
            }
        }

        if ($sameSuit) return false;    // only Flash was allowed
        
        // evaluate combined cards
        return Hand::match_mask($all_cards, $mask, $len);
    }
    

    static function match_mask(int $cards, int $mask, int $len) {
        // move 0x11111 mask from right to left to match the pattern
        $ops = count(Card::RANK) - $len;
        for ($i = 0; $i <= $ops; $i++) {
            $shifted = $mask << $i;
            if (($cards & $shifted) == $shifted) {
                return true;
            }
        }

        return false;
    }
}

?>