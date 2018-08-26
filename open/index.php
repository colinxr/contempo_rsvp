<?php
	$fName 	= $_GET['fn'];
	$lName 	= $_GET['ln'];
	$email 	= $_GET['em'];

	require_once 'header.php';
?>
<body>
	<div class="container">
		<div class="col left img">
		</div>

		<div class="col right info">
			<?php include 'event-info.php'; ?>

			<?php if (RSVP_TYPE === 'Closed') : ?>
				<?php include '../_inc/alerts/capacity-msg.php'; ?>
			<?php else : ?>
				<form action="validate.php" method="post" autocomplete="off">
				<span class="input input--bfm">
					<input class="input__field input__field--bfm" type="text" id="first-name"  name="rsvp[first-name]" value="<?php echo $fName; ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required />
					<label class="input__label input__label--bfm input__label--bfm-color" for="first-name">
						<span class="input__label-content input__label-content--bfm">First Name</span>
					</label>
				</span>

				<span class="input input--bfm">
					<input class="input__field input__field--bfm" type="text" id="last-name" name="rsvp[last-name]" value="<?php echo $lName; ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required />
					<label class="input__label input__label--bfm input__label--bfm-color" for="last-name">
						<span class="input__label-content input__label-content--bfm">Last Name</span>
					</label>
				</span>

				<span class="input input--bfm">
					<input class="input__field input__field--bfm" type="email" id="email" name="rsvp[email]" value="<?php echo $email; ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required />
					<label class="input__label input__label--bfm input__label--bfm-color" for="email">
						<span class="input__label-content input__label-content--bfm">Email</span>
					</label>
				</span>

				<span class="input input--bfm">
					<input class="input__field input__field--bfm" type="text" id="postal"  name="rsvp[postal]" value="" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required />
					<label class="input__label input__label--bfm input__label--bfm-color" for="postal">
						<span class="input__label-content input__label-content--bfm">Postal Code</span>
					</label>
				</span>

				<span class="input input--bfm input__check">
					<label class="input__label input__label--plus-one" for="plus-one">Bringing a Guest?</label>
					<input type="checkbox" name="rsvp[plus-one]" id="plus-one">
				</span>

				<div class="guests">
					<span class="input input--bfm">
						<input class="input__field input__field--bfm" type="text" id="guest-firstName" name="rsvp[guest-firstName]" />
						<label class="input__label input__label--bfm input__label--bfm-color" for="guest-firstName">
							<span class="input__label-content input__label-content--bfm">Guest's First Name</span>
						</label>
					</span>

					<span class="input input--bfm">
						<input class="input__field input__field--bfm" type="text" id="guest-lastName" name="rsvp[guest-lastName]" />
						<label class="input__label input__label--bfm input__label--bfm-color" for="guest-lastName">
							<span class="input__label-content input__label-content--bfm">Guest's Last Name</span>
						</label>
					</span>

					<span class="input input--bfm">
						<input class="input__field input__field--bfm" type="email" id="guest-email" name="rsvp[guest-email]" />
						<label class="input__label input__label--bfm input__label--bfm-color" for="guest-email">
							<span class="input__label-content input__label-content--bfm">Guest's Email</span>
						</label>
					</span>
				</div>

				<button class="btn btn-1 btn-1a" type="submit" name="rsvp[submit]" value="Submit">Submit</button>

			</form>
			<?php endif; ?>

			<img src="../imgs/sponsor-logos.jpg" class="sponsor-logos"/>
		</div>
	</div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/webshim/1.15.10/dev/polyfiller.js'></script>
	<script type='text/javascript' src='<?php echo BASE_URL; ?>/dist/js/all.js'></script>
	<script>
		(function() {
			// trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
			if (!String.prototype.trim) {
				(function() {
					// Make sure we trim BOM and NBSP
					var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
					String.prototype.trim = function() {
						return this.replace(rtrim, '');
					};
				})();
			}

			[].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
				// in case the input is already filled..
				if( inputEl.value.trim() !== '' ) {
					classie.add( inputEl.parentNode, 'input--filled' );
				}

				// events:
				inputEl.addEventListener( 'focus', onInputFocus );
				inputEl.addEventListener( 'blur', onInputBlur );
			} );

			function onInputFocus( ev ) {
				classie.add( ev.target.parentNode, 'input--filled' );
			}

			function onInputBlur( ev ) {
				if( ev.target.value.trim() === '' ) {
					classie.remove( ev.target.parentNode, 'input--filled' );
				}
			}
		})();
	</script>
	<script>
  	webshim.activeLang('en');
    webshims.polyfill('forms');
    webshims.cfg.no$Switch = true;
  </script>
</body>
</html>
