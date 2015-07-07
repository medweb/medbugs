<h3>Users</h3>

<?php
if ( 1 == current_user()->id ) {
	?>
	<a class="add-new-user" id="add-new-user" href="#">Add New User</a>
	
	<div class="new-user-form" id="new-user-form">
		<label for="new-user-input">Please enter the user's email address</label>
		<input type="text" id="new-user-input" placeholder="name@email.com">
		<button class="btn publish" id="new-user-submit">Create User</button>
		
		<span>* Upon creation, the new user will be notified by email and assigned a temporary password.</span>
	</div>
	<?php
}
?>

<ul class="user-list">
	<?php
	$args = array(
		'order' => 'ASC',
		'order_by' => 'user_id'
	);
	
	$users = get_users( $args );
	
	foreach ( $users as $user ) {
		if ( 1 == current_user()->id ) {
			if ( 1 != $user->id ) {
				?>
				<li><?php echo $user->username; ?><span><a id="edit-user-<?php echo $user->id; ?>" class="edit" href="#">Edit</a> | <a id="delete-user-<?php echo $user->id; ?>" class="delete" href="#">Delete</a></span></li>
				<li class="change-password-form">
					<label for="old-password-user-<?php echo $user->id; ?>">Please enter your current password</label>
					<input type="password" class="current-password" id="old-password-user-<?php echo $user->id; ?>">
					
					<label for="new-password-user-<?php echo $user->id; ?>">Please enter your new password</label>
					<input type="password" class="new-password-1" id="new-password-user-<?php echo $user->id; ?>">
					
					<label for="new-password-repeat-user-<?php echo $user->id; ?>">Please enter your new password again</label>
					<input type="password" class="new-password-2" id="new-password-repeat-user-<?php echo $user->id; ?>">
					
					<button class="btn change-password">Change Password</button>
				</li>
				<li class="delete-user-form">
					<span class="warning">Are you sure you want to <em>permanently</em> delete this user?</span>
					
					<label for="confirm-delete-user-<?php echo $user->id; ?>">Yes</label>
					<input type="checkbox" value="yes" class="confirm-delete-user" id="confirm-delete-user-<?php echo $user->id; ?>">
					
					<button class="btn delete-user">Delete User</button>
				</li>
				<?php
			} else {
				?>
				<li><?php echo $user->username; ?><span><a id="edit-user-<?php echo $user->id; ?>" class="edit" href="#">Edit</a></span></li>
				<li class="change-password-form">
					<label for="old-password-user-<?php echo $user->id; ?>">Please enter your current password</label>
					<input type="password" class="current-password" id="old-password-user-<?php echo $user->id; ?>">
					
					<label for="new-password-user-<?php echo $user->id; ?>">Please enter your new password</label>
					<input type="password" class="new-password-1" id="new-password-user-<?php echo $user->id; ?>">
					
					<label for="new-password-repeat-user-<?php echo $user->id; ?>">Please enter your new password again</label>
					<input type="password" class="new-password-2" id="new-password-repeat-user-<?php echo $user->id; ?>">
					
					<button class="btn change-password">Change Password</button>
				</li>
				<?php
			}
		} else {
			if ( current_user()->id == $user->id ) {
				?>
				<li><?php echo $user->username; ?><span><a id="edit-user-<?php echo $user->id; ?>" class="edit" href="#">Edit</a></span></li>
				<li class="change-password-form">
					<label for="old-password-user-<?php echo $user->id; ?>">Please enter your current password</label>
					<input type="password" class="current-password" id="old-password-user-<?php echo $user->id; ?>">
					
					<label for="new-password-user-<?php echo $user->id; ?>">Please enter your new password</label>
					<input type="password" class="new-password-1" id="new-password-user-<?php echo $user->id; ?>">
					
					<label for="new-password-repeat-user-<?php echo $user->id; ?>">Please enter your new password again</label>
					<input type="password" class="new-password-2" id="new-password-repeat-user-<?php echo $user->id; ?>">
					
					<button class="btn change-password">Change Password</button>
				</li>
				<?php
			} else {
				?>
				<li><?php echo $user->username; ?></li>
				<?php
			}
		}
	}
	?>
</ul>

<h3>Side Bar Editor</h3>

<textarea class="sidebar-editor" id="sidebar-input"><?php the_sidebar_content(); ?></textarea>
<button class="btn publish" id="sidebar-submit">Publish</button>