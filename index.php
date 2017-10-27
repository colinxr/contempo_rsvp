<?php require_once 'header.php' ;?>
<body>
	<div class="container">
		<div class="wrapper">
			<div class="col left info">
				<?php include '_inc/event-info.php'; ?>
			</div>

			<div class="col right">
				<form action="validate.php" method="post" autocomplete="off">
						<span class="input input--bfm">
							<input class="input__field input__field--bfm" type="text" id="first-name"  name="rsvp[first-name]" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required/>
							<label class="input__label input__label--bfm input__label--bfm-color" for="first-name">
								<span class="input__label-content input__label-content--bfm">First Name</span>
							</label>
						</span>
						<span class="input input--bfm">
							<input class="input__field input__field--bfm" type="text" id="last-name" name="rsvp[last-name]" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required/>
							<label class="input__label input__label--bfm input__label--bfm-color" for="last-name">
								<span class="input__label-content input__label-content--bfm">Last Name</span>
							</label>
						</span>
						<span class="input input--bfm">
							<input class="input__field input__field--bfm" type="email" id="email" name="rsvp[email]" autocomplete]="off" autocorrect="off" autocapitalize="off" spellcheck="false"/ required>
							<label class="input__label input__label--bfm input__label--bfm-color" for="email">
								<span class="input__label-content input__label-content--bfm">Email</span>
							</label>
						</span>

						<span class="input input--bfm">
							<input class="input__field input__field--bfm" type="text" id="postal"  name="rsvp[postal]" autocomplete]="off" autocorrect="off" autocapitalize="off" spellcheck="false"/ required>
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
			</div>
		</div>
	</div>

	<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js'></script>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/webshim/1.15.10/dev/polyfiller.js'></script>
	<script type='text/javascript' src='dist/js/all.min.js'></script>
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
