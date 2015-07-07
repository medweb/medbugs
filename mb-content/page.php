<?php get_header(); ?>

<div class="content-width">
	<section class="primary">		
		<section class="new-bug" id="new-bug">
			<h2>Submit a New Bug</h2>
			
			<fieldset class="text" id="email-address">
				<div class="left">
					<label for="new-email">Your Email Address</label>
				</div>
				
				<div class="right">
					<input type="text" id="new-email" placeholder="your.name@ucf.edu">
				</div>
			</fieldset>
			
			<fieldset class="text" id="bug-name">
				<div class="left">
					<label for="new-title">Name Your Bug</label>
				</div>
				
				<div class="right">
					<input type="text" id="new-title" placeholder="annoying bug">
				</div>
			</fieldset>
			
			<fieldset class="textarea" id="bug-description">
				<label for="new-description">Describe Your Bug</label>
				<textarea id="new-description"></textarea>
			</fieldset>
			
			<button class="btn submit" id="new-submit">Squash This Bug</button>
		</section>
		
		<section class="current-bugs" id="current-bugs">
			<h2>Current Bugs (4)</h2>
			
			<a class="submit-another-bug" href="#">Submit Another Bug</a>
			
			<?php
			$args = array(
				'order' => 'DESC',
				'order_by' => array(
					'primary' => 'votes',
					'secondary' => 'date'
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
						<button class="btn agree">I Found This Bug Too!</button>
					</div>
					
					<?php echo $votes; ?>
				</div>
				<?php
			}
			?>
		</section>
	</section>
	
	<section class="secondary">
		<?php get_sidebar(); ?>
	</section>
</div>

<?php get_footer(); ?>