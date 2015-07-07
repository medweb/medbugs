(function($){
	var EASE = 'easeInOutCirc',
		$newBugBox = $( '#new-bug' ),
		$currentBugBox = $( '#current-bugs' ),
		$emailAddress = $( '#email-address' ),
		$bugName = $( '#bug-name' ),
		$bugDescription = $( '#bug-description' ),
		$submitBtn = $( '#new-submit' ),
		$emailInput = $( '#new-email' ),
		$nameInput = $( '#new-title' ),
		$descInput = $( '#new-description' ),
		$submitAnotherBug = $( '.submit-another-bug' ),
		$voteBtn = $( '.agree' ),
		$currentBtn = $( '.current' ),
		$resolvedBtn = $( '.resolved' ),
		$newUserBtn = $( '#add-new-user' ),
		$newUserForm = $( '#new-user-form' ),
		$newUserEmailInput = $( '#new-user-input' ),
		$newUserSubmitBtn = $( '#new-user-submit' ),
		$editBtn = $( '.edit', $( '.user-list' ) ),
		$changePasswordBtn = $( '.change-password' ),
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
	
	// Submit new bug
	$submitBtn.on( 'click', function( e ) {
		if ( validEmail( $emailInput.val() ) ) {
			if ( '' !== $nameInput.val() ) {
				if ( '' !== $descInput.val() ) {
					// success
					$newBugBox.fadeTo( 350, 0, EASE );
					$newBugBox.css( 'height', $newBugBox.height() );
					$newBugBox.slideUp( 500, EASE );
					
					// new bug content to prepend
					var newBug = '<div class="bug">';
						newBug += '<h3 class="ucf-gold">' + $nameInput.val() + '</h3>';
						newBug += '<span class="submit-meta">Submitted by you!</span>';
						newBug += '<p>' + $descInput.val() + '</p>';
					newBug += '</div>';
					
					$( newBug ).insertAfter( '.submit-another-bug', $currentBugBox ).hide();
					newBug = $( '.bug:first', $( '#current-bugs' ) );
					newBug.css( {
						'background-color' : '#fff',
						'height' : newBug.height() + 15
					} );
					newBug.delay( 450 ).slideDown( 250, EASE, function() {
						newBug.animate( {
							'background-color' : 'transparent'
						}, 5000, EASE );
					} );
					
					$.ajax( {
						type : 'POST',
						url : mb_localized.ajaxURL,
						data: 'action=new_bug&title=' + $nameInput.val() + '&description=' + $descInput.val() + '&email=' + $emailInput.val() + '&ip=' + mb_localized.userIP
					} ).done( function( data ) {
						//console.log( data );
						$emailInput.val( '' );
						$nameInput.val( '' );
						$descInput.val( '' );
					} );
					
					$submitAnotherBug.delay( 650 ).fadeTo( 350, 1 );
				} else {
					// description not valid
					flashField( $bugDescription, '#ffc809', '#fff' );
				}
			} else {
				// title not valid
				flashField( $bugName, '#ffc809', '#fff' );
			}
		} else {
			// email address not valid
			flashField( $emailAddress, '#ffc809', '#fff' );
		}
	} );
	
	// Reset new bug form
	$submitAnotherBug.on( 'click', function( e ) {
		$newBugBox.slideDown( 500, EASE, function( e ) {
			$newBugBox.fadeTo( 250, 1, EASE );
		} );
	} );
	
	// Vote bug up
	$voteBtn.on( 'click', function( e ) {
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
			data: 'action=vote_bug_up&bug_id=' + bugId
		} ).done( function( data ) {
			var currentCount = $bug.find( '.count' );
			currentCount.html( parseInt( currentCount.html(), 10 ) + 1 );
		} );
	} );
})(jQuery);
