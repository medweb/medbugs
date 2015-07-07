<?php the_sidebar_content(); ?>

<h3>Currently Working On...</h3>
<ul class="currently-working-on">
	<?php
	$args = array(
		'current' => true
	);
	
	$bugs = get_bugs( $args );
	
	foreach ( $bugs as $bug ) {
		?>
		<li><?php echo $bug->title; ?></li>
		<?php
	}
	?>
</ul>

<!--<h3>The Swarm</h3>
<div class="bug-cloud">
	<span class="bug-cloud-bug" style="font-size: 12px">Navigation</span>
	<span class="bug-cloud-bug" style="font-size: 16px">Library</span>
	<span class="bug-cloud-bug" style="font-size: 10px">Global Health</span>
	<span class="bug-cloud-bug" style="font-size: 18px">Browser</span>
	<span class="bug-cloud-bug" style="font-size: 20px">Footer</span>
	<span class="bug-cloud-bug" style="font-size: 16px">Home page</span>
	<span class="bug-cloud-bug" style="font-size: 14px">Giving section</span>
	<span class="bug-cloud-bug" style="font-size: 18px">Students</span>
</div>-->