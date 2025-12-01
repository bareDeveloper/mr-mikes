<?php element('header'); ?>

	<div class="error-404 lazyload" <?php echo lazy_background(get_field('404_background_image', 'option')); ?>>

		<div class="grid-edges">

			<div class="error-404__container">
			
				<div class="error-404__headline">
					<?php
						element('headline', [ 
							'text' => 'Nothing found.',
							'style' => 'h1'
						]);
					?>
				</div>
				

				<div class="error-404__button">
					<?php
						element('button', [
							'class' => 'btn-red',
							'link' => site_url(),
							'button' => 'Try starting at the beginning again.'
						]);
					?>
				</div>

			</div>

		</div>

	</div>

<?php element('footer');