<? foreach($suggestions as $idea): ?>
	        <div class="card">
	        	<h3><?= $idea->title ?></h3>
	        	<p><?= round($idea->distance/1000,2) ?>km</p>
	        	<p><?= $idea->score ?></p>
	        </div>
<? endforeach ?>