<?php
class Deck {
    private const MAX_LEN = 52;

    private $_dealt;
    private $_non_dealt;

    function __construct() {
        $this->_dealt = array();
        $this->_non_dealt = array();
        foreach (Card::SUIT as $s => $num_s) {
            foreach (Card::RANK as $r => $num_r) {
                $code = $r . $s; 
                array_push($this->_non_dealt, new Card($code));
            }
        }
    }

    function dealOne() {
        if (count($this->_non_dealt) == 0) {
            return null;
        }

        $c = array_pop($this->_non_dealt);
        array_push($this->_dealt, $c);
        return $c;
    }


    function display() {
        print("Non-dealt: ");
        foreach ($this->_non_dealt as $c) {
            print($c->display() . " ");
        }
        print("\n");

        print("Dealt: ");
        foreach ($this->_dealt as $c) {
            print($c->display() . " ");
        }
        print("\n");

    }

    function shuffle() {
        if (count($this->_non_dealt) != self::MAX_LEN) {
            throw new Exception("Shuffling dealt deck is not allowed!");
        }

        // use this array to draw cards from
        $temp = $this->_non_dealt;
        for ($i = 0; $i < self::MAX_LEN; $i++) {
            do {
                $n = random_int(0, self::MAX_LEN-1);
            } while (is_null($temp[$n]));

            // draw a card and replace it with 0
            $this->_non_dealt[$i] = $temp[$n];
            $temp[$n] = null;
        }
    }
}

?>