<?php get_header(); ?>

<div class="content-width">
	<section class="primary">		
		<h2>Pest Control</h2>
		
		<?php
		$args = array(
			'order' => 'DESC',
			'order_by' => array(
				'primary' => 'current',
				'secondary' => 'votes'
			),
			'status' => 'active'
		);
		
		$bugs = get_bugs( $args );
		
		foreach ( $bugs as $bug ) {
			if ( 1 == $bug->votes ) {
				$votes = '<div class="votes">Found <span class="count">' . $bug->votes . '</span> time</div>';
			} else {
				$votes = '<div class="votes">Found <span class="count">' . $bug->votes . '</span> times</div>';
			}
			?>
			<div class="bug" id="bug-id-<?php echo $bug->id; ?>">
				<h3><?php echo $bug->title; ?></h3>
				
				<span class="submit-meta">Submitted on <?php echo $bug->date; ?>, by <a href="mailto:<?php echo $bug->email; ?>"><?php echo $bug->name; ?></a></span>
				
				<p><?php echo $bug->description; ?></p>
				
				<div class="actions">
					<button class="btn resolved">Bug Resolved</button>
					<?php
					if ( 0 == $bug->current ) {
						echo '<button class="btn current">Currently Being Resolved</button>';
					}
					?>
				</div>
				
				<?php echo $votes; ?>
			</div>
			<?php
		}
		?>
	</section>
	
	<section class="secondary">
		<?php get_admin_sidebar(); ?>
	</section>
</div>

<?php get_footer(); ?>