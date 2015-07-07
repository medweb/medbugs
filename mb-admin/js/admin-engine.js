(function($){
	var EASE = 'easeInOutCirc',
		$currentBtn = $( '.current' ),
		$resolvedBtn = $( '.resolved' ),
		$newUserBtn = $( '#add-new-user' ),
		$newUserForm = $( '#new-user-form' ),
		$newUserEmailInput = $( '#new-user-input' ),
		$newUserSubmitBtn = $( '#new-user-submit' ),
		$editBtn = $( '.edit', $( '.user-list' ) ),
		$changePasswordBtn = $( '.change-password' ),
		$deleteBtn = $( '.delete', $( '.user-list' ) ),
		$deleteUserBtn = $( '.delete-user' ),
		$sidebarSubmitBtn = $( '#sidebar-submit' ),
		$sidebarInput = $( '#sidebar-input' );
	
	// Field activity alert
	function flashField( $el, startColor, endColor ) {
		$el.css( 'background-color', startColor ).stop().animate( {
			'background-color' : endColor
		}, 500 );
	}
	
	// Email validation
	function validEmail( address ) {
		reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;		
		return reg.test( address );
	}
	
	// Resolve bug
	$resolvedBtn.on( 'click', function( e ) {
		var $bug = $( this ).parent().parent()
			bugId = $bug.attr( 'id' );
		
		bugId = bugId.replace( 'bug-id-', '' );
		
		$bug.slideUp( 500, EASE );
		
		$.ajax( {
			type : 'POST',
			url : mb_localized.ajaxURL,
			data: 'action=resolve_bug&bug_id=' + bugId
		} ).done( function( data ) {
			//console.log( data );
		} );
	} );
	
	// Set bug as currently being worked on
	$currentBtn.on( 'click', function( e ) {
		$( this ).fadeTo( 250, 0, EASE, function() {
			$( this ).attr( 'disabled', 'disabled' );
			$( this ).css( 'cursor', 'default' );
		} );
		
		var $bug = $( this ).parent().parent()
			bugId = $bug.attr( 'id' );
		
		bugId = bugId.replace( 'bug-id-', '' );
		
		$.ajax( {
			type : 'POST',
			url : mb_localized.ajaxURL,
			data: 'action=current&bug_id=' + bugId
		} ).done( function( data ) {
			//console.log( data );
		} );
	} );
	
	// Update sidebar content
	$sidebarSubmitBtn.on( 'click', function( e ) {
		$.ajax( {
			type : 'POST',
			url : mb_localized.ajaxURL,
			data: 'action=update_sidebar&sidebar_content=' + $sidebarInput.val()
		} ).done( function( data ) {
			flashField( $sidebarInput, '#49b7ff', '#eee' );
		} );
	} );
	
	// Create new user
	newUserFormFullHeight = $newUserForm.height() + 15; // makes up for jQuery slidedown height calculation bug
	
	$newUserBtn.on( 'click', function( e ) {
		e.preventDefault();
		
		if ( 'Add New User' === $newUserBtn.text() ) {
			$newUserForm.hide().css( 'height', newUserFormFullHeight + 'px' );
			$newUserForm.stop().slideDown( 350, EASE );
			$newUserBtn.text( 'Cancel' );
		} else if ( 'Cancel' === $newUserBtn.text() ) {
			$newUserEmailInput.val( '' );
			$newUserForm.stop().slideUp( 350, EASE );
			$newUserBtn.text( 'Add New User' );
		}
	} );
	
	$newUserSubmitBtn.on( 'click', function( e ) {
		if ( validEmail( $newUserEmailInput.val() ) ) {
			$.ajax( {
				type : 'POST',
				url : mb_localized.ajaxURL,
				data: 'action=new_user&email=' + $newUserEmailInput.val()
			} ).done( function( data ) {
				$newUserForm.stop().slideUp( 350, EASE );
				$newUserBtn.text( 'Add New User' );
			} );
		} else {
			flashField( $newUserEmailInput, '#ffc809', '#fff' );
		}
	} );
	
	// Change user password
	$editBtn.on( 'click', function( e ) {
		e.preventDefault();
		
		var $passwordForm = $( this ).parent().parent().next( '.change-password-form' );
		
		if ( 'Edit' === $( this ).text() ) {
			$passwordForm.stop().slideDown( 350, EASE );
			$( this ).text( 'Cancel' );
		} else if ( 'Cancel' === $( this ).text() ) {
			//$newUserEmailInput.val( '' );
			$passwordForm.stop().slideUp( 350, EASE );
			$( this ).text( 'Edit' );
		}
	} );
	
	$changePasswordBtn.on( 'click', function( e ) {
		var $THIS = $( this ),
			$oldPasswordField = $THIS.parent().find( '.current-password' ),
			$newPasswordField1 = $THIS.parent().find( '.new-password-1' ),
			$newPasswordField2 = $THIS.parent().find( '.new-password-2' ),
			$editBtn = $THIS.parent().prev( 'li' ).find( '.edit' ),
			userId = $newPasswordField1.attr( 'id' );
			
		userId = userId.replace( 'new-password-user-', '' );
		
		if ( '' !== $oldPasswordField.val() ) {
			if ( '' !== $newPasswordField1.val() ) {
				if ( '' !== $newPasswordField2.val() ) {
					if ( $newPasswordField1.val() === $newPasswordField2.val() ) {
						// success
						$.ajax( {
							type : 'POST',
							url : mb_localized.ajaxURL,
							data : 'action=change_user_password&user_id=' + userId + '&old_password=' + $oldPasswordField.val() + '&new_password=' + $newPasswordField1.val()
						} ).done( function( data ) {
							//console.log( data );
							
							$oldPasswordField.val( '' );
							$newPasswordField1.val( '' );
							$newPasswordField2.val( '' );
								
							$THIS.parent().stop().slideUp( 350, EASE );
							$editBtn.text( 'Edit' );
						} );
					} else {
						flashField( $newPasswordField1, '#ffc809', '#fff' );
						flashField( $newPasswordField2, '#ffc809', '#fff' );
					}
				} else {
					flashField( $newPasswordField2, '#ffc809', '#fff' );
				}
			} else {
				flashField( $newPasswordField1, '#ffc809', '#fff' );
			}
		} else {
			flashField( $oldPasswordField, '#ffc809', '#fff' );
		}
	} );
	
	// Delete user
	$deleteBtn.on( 'click', function( e ) {
		e.preventDefault();
		
		var $deleteUserForm = $( this ).parent().parent().next( '.change-password-form' ).next( '.delete-user-form' );
		
		if ( 'Delete' === $( this ).text() ) {
			$deleteUserForm.stop().slideDown( 350, EASE );
			$( this ).text( 'Cancel' );
		} else if ( 'Cancel' === $( this ).text() ) {
			$deleteUserForm.stop().slideUp( 350, EASE );
			$( this ).text( 'Delete' );
		}
	} );
	
	$deleteUserBtn.on( 'click', function( e ) {
		var $THIS = $( this ),
			$confirmCheckbox = $THIS.parent().find( '.confirm-delete-user' ),
			$confirmLabel = $confirmCheckbox.prev( 'label' ),
			$deleteBtn = $THIS.parent().prev( 'li' ).prev( 'li' ).find( '.edit' ),
			userId = $confirmCheckbox.attr( 'id' );
			
		userId = userId.replace( 'confirm-delete-user-', '' );
		
		if ( $confirmCheckbox.attr( 'checked' ) ) {
			// success
			$.ajax( {
				type : 'POST',
				url : mb_localized.ajaxURL,
				data : 'action=delete_user&user_id=' + userId
			} ).done( function( data ) {
				$THIS.parent().stop().slideUp( 350, EASE, function( e ) {
					$THIS.parent().prev( 'li' ).prev( 'li' ).stop().slideUp( 350, EASE );
				} );
				$deleteBtn.text( 'Delete' );
			} );
		} else {
			console.log( userId );
			
			flashField( $confirmLabel, '#ffc809', 'transparent' );
		}
	} );
})(jQuery);
