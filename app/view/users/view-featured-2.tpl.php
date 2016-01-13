
<h4 class='xp'><i class="fa fa-trophy fa-2x gold"></i> <?=$user->xp?></h4>

<?php
$bestlap = $user->bestlap ? formatSeconds($user->bestlap) : '--:--:--';
$numlaps = $user->numlaps ? $user->numlaps : '0';

?>

<h4 id='show-best-lap' title='The fastest lap in Volvo S90 test Drive game'><i class="fa fa-flag fa-1x"></i> <?=$bestlap;?></h4>
<h4 id='show-num-laps' title='Number of laps completed in Volvo S90 test Drive game'><i class="fa fa-road fa-1x"></i> <?=$numlaps;?></h4>