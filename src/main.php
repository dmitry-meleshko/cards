<?php
include("card.php");
include("deck.php");
include("hand.php");


$deck = new Deck();
$deck->display();
$deck->shuffle();
$deck->shuffle();
$deck->shuffle();
$deck->display();

$players = array();
for ($i = 0; $i < 4; $i++) {
    array_push($players, new Hand());
}

// deal cards for 5 hands to each player
for ($n = 0; $n < 5; $n++) {
    for ($i = 0; $i < 4; $i++) { 
        $c = $deck->dealOne();
        $players[$i]->addCard($c);      
    }
}

for ($i = 0; $i < 4; $i++) {
    $hand = $players[$i];
    $hand->display();
    $isSF = $hand->hasStraight(5, true);
    $isS = $hand->hasStraight(5, false);
    print("Straigh Flush / Straight? " . ($isSF ? "Yes" : "No") . " / " . ($isS ? "Yes" : "No") . "\n");
}

// verify deck
$deck->display();

?>