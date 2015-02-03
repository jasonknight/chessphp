<?php 
require_once "../classes/pgn.php";

$pgn = new Chess\PGN();
$pgn->load('../pgns/alekhine_bruce_1938.pgn')->parse();
