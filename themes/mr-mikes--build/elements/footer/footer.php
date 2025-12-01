<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( 'get_footer', null );

// Getting the Page Header Type
$footer_type = get_field( 'footer_type' );

if ( is_home() ) {
	$page_id = get_option( 'page_for_posts' );
} else {
	$page_id = "";
}

if ( get_field( 'cow', $page_id ) == true ) {
	element( 'cow' );
}

?>

<footer class="footer lazyload" <?php lazy_background( 1650 ); ?>>
    <div class="footer__grunge">
		<?php echo svg( "grunge/12-footer" ); ?>
    </div>

    <div class="footer__container">

        <div class="footer__logo">
            <a href="<?php echo get_home_url(); ?>">
				<?php echo svg( "logo_footer" ); ?>

                <span class='screen-reader-text'>
							<?php bloginfo( 'name' ); ?>
						</span>
            </a>
        </div>

		<?php if ( $footer_type !== 'lp_footer' ) : ?>
            <div class="footer__contact">

                <div class="footer__main-menu">
					<?php bem_menu( 'footer', 'footer-menu' ); ?>
                </div>

                <div class="footer__right-content">
                    <div class="footer__icons">
                        <div class="footer__sns">
                            <a href="https://www.youtube.com/user/MrMikesOnline" target="_blank">
								<?php echo svg( "youtube" ); ?>
                            </a>
                            <a href="https://www.facebook.com/MrMikesOnline" target="_blank">
								<?php echo svg( "facebook" ); ?>
                            </a>
                            <a href="https://instagram.com/mrmikesonline#" target="_blank">
								<?php echo svg( "instagram" ); ?>
                            </a>
                        </div>
                        <div class="footer__apps">
                            <span>MR MIKES <br>Rewards App:</span>
							<?php if ( get_field( 'android', 'option' ) ): ?>
                                <a href="<?php the_field( 'android', 'option' ); ?>" target="_blank">
									<?php echo svg( "icon-android" ); ?>
                                </a>
							<?php endif; ?>
							<?php if ( get_field( 'ios', 'option' ) ): ?>
                                <a href="<?php the_field( 'ios', 'option' ); ?>" target="_blank">
									<?php echo svg( "icon_apple" ); ?>
                                </a>
							<?php endif; ?>
                        </div>
                    </div>

					<?php element( 'newsletterButton' ); ?>

                </div>

            </div>
		<?php endif; ?>

    </div>

</footer>
<div class="footer-copy">
    ©️ 2025 Mr. Mikes Restaurants Corporation Logos of MR MIKES SteakhouseCasual are the registered trademarks of MRM
    Royalties Limited Partnership, used under license.
</div>

</div> <!-- end of barba container-->
</div> <!-- end of barba wrapper-->

<?php element( 'loader' ); ?>

<?php element( 'skipWidget' ); ?>

<?php element( 'doordashWidget' ); ?>

<?php element( 'xdineWidget' ); ?>

<?php wp_footer(); ?>

<?php element( 'balanceWidget' ); ?>

<script type="text/javascript">
    _linkedin_partner_id = "2746876";
    window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
    window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script>
<script type="text/javascript">
    (function () {
        var s = document.getElementsByTagName("script")[0];
        var b = document.createElement("script");
        b.type = "text/javascript";
        b.async = true;
        b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
        s.parentNode.insertBefore(b, s);
    })();
</script>
<noscript>
    <img height="1" width="1" style="display:none;" alt=""
         src="https://px.ads.linkedin.com/collect/?pid=2746876&fmt=gif"/>
</noscript>

</body>

</html>