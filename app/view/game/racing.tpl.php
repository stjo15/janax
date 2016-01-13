<h1>Janax Test Drive</h1>
<button id='start-race'>Start game</button>
<div id='game-container'>

<?php if($_SESSION) : ?>
<?php 
$bestlap = $_SESSION['user']->bestlap ? $_SESSION['user']->bestlap : ''; 
$numlaps = $_SESSION['user']->numlaps ? $_SESSION['user']->numlaps : 0; 
?>

<div id='racing-top'>
<form id='savelaptime' action='users/laptime' method='post'>
    <input type='hidden' id='savelap' name='bestlap' value='<?=$bestlap?>' />
    <input type='hidden' id='savenumlaps' name='numlaps' value='<?=$numlaps?>' />
    <input type='submit' value='Save and exit' />
</form>
</div>

<?php else : ?>    
<?php $bestlap = ''; ?>
<p class='tip'>Tip! <a href='<?=$this->url->create('users')?>'>Register or login</a> to save your best laptime!</p>
<?php endif; ?>

<canvas id='racing' width='900' height='600'>
Your browser does not support the element HTML5 Canvas. Please use a modern browser.
</canvas>

<div id='laptime'>
<p id='numlaps' class='white'>Laps: 0</p>
<p id='lap' class='white'>Lap: --.--.--</p>
<p id='lastlap' class='white'>Last: --.--.--</p>
<p class='white'>Diff: <span id='diff'>--.--.--</span></p>
<p id='bestlap' class='white'>Best: --.--.--</p>
</div>

<p id='game-controls'><button id='instructions'><i class="fa fa-gamepad fa-2x"></i></button>
<span id='controls'>Throttle: UP/W | Reverse: DOWN/R | Soft brake: DOWN | Hard brake: S | Turn right/left: RIGHT/LEFT, D/A | Honk: H</span></p>

</div>