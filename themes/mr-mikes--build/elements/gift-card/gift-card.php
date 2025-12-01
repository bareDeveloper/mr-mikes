<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wp;
$base_url = get_permalink();
$section_id = get_sub_field( 'section_id' );
$bgImg = get_sub_field( 'background_image' );
$contentBgType = get_sub_field( 'content_background' );
$modules = get_field('modules');
if ($modules) {
    $second_tab_path = $modules[0]['second_tab_path'] ?? '';
	if (!preg_match('/^\//', $second_tab_path)) {
		$second_tab_path = '/' . $second_tab_path;
	}
}
// Retrieve the current URL path
$current_url_path = add_query_arg( array(), $wp->request );

// Check if the current URL matches the second tab path
$is_second_tab_active = ( '/' . $current_url_path == $second_tab_path );
// Background image logic
$bgImgUrl = $bgImg ? "background-image:url(" . $bgImg['url'] . ");" : "";
$hasBg = $bgImg ? "has-bg" : "";

// Content background class
$contentBgClass = $contentBgType === "gradient" ? "bg-gradient-content" : "";
$contentBgColor = get_sub_field( 'content_bg_color' );
$contentContainerStyles = ! empty( $contentBgColor ) ? "background-color: " . $contentBgColor . ";" : "";

// print_r($modules[0]['banner_image']);
$bannerImg = (isset($modules[0]['banner_image']) &&  $modules[0]['banner_image'] != '') ? $modules[0]['banner_image'] :'/wp-content/themes/mr-mikes--build/static/images/bg-gift-card-deskop.png';
?>

<div class="giftcard__header" style="background: url('<?php echo $bannerImg; ?>') no-repeat center right;background-size: cover;
    object-fit: cover;
    background-position: left;
    height: 410px;
    display: flex;
    align-items: center;">
	<div class="giftcard__header__content">
		<h1><?php the_sub_field( 'main_title' ); ?></h1>
		<p><?php the_sub_field( 'main_description' ); ?></p>
	</div>
</div>

<div class="tab giftcard__tab" data-base-url="<?php echo esc_url($base_url); ?>">
    <button class="tablinks <?php if ( ! $is_second_tab_active ) echo 'active'; ?>" data-tab="Content1">
        <span><?php the_sub_field('first_tab_title'); ?></span>
    </button>
    <button class="tablinks <?php if ( $is_second_tab_active ) echo 'active'; ?>" data-tab="Content2" data-full-path="<?php echo esc_attr( $second_tab_path ); ?>">
        <span><?php the_sub_field('second_tab_title'); ?></span>
    </button>
</div>


<div id="Content1" class="tabcontent" style="display: <?php echo $is_second_tab_active ? 'none' : 'block'; ?>;">
	<div class="giftcard__subheader">
		<h3><?php the_sub_field( 'first_tab_subtitle' ); ?></h3>
		<p><?php the_sub_field( 'first_tab_description' ); ?></p>
	</div>
	<div 
	class="text <?php echo esc_attr( $hasBg ); ?>" 
	style="<?php echo esc_attr( $bgImgUrl ); ?>" 
	>
		<div 
		class="text__container giftcard__container <?php echo esc_attr( $contentBgClass ); ?>" 
		<?php if ( ! empty( $contentContainerStyles ) ) : ?> 
			style="<?php echo esc_attr( $contentContainerStyles ); ?>" 
		<?php endif; ?>
		>
			<?php 
			$image = get_sub_field( 'first_tab_image' );
			if ( ! empty( $image ) ) :
				$url = $image['url'];
				$alt = $image['alt']; 
			?>
				<img 
					alt="<?php echo esc_attr( $alt ); ?>" 
					class="giftcard__image" 
					src="<?php echo esc_url( $url ); ?>" 
				/>
			<?php endif; ?>

			<div class="text__contents giftcard__text">
				<?php the_sub_field( 'first_tab_text' ); ?>
			</div>
		</div>
	</div>
</div>


<div id="Content2" class="tabcontent" style="display: <?php echo $is_second_tab_active ? 'block' : 'none'; ?>;">
	<div class="giftcard__subheader">
		<h3><?php the_sub_field( 'second_tab_subtitle' ); ?></h3>
		<p><?php the_sub_field( 'second_tab_description' ); ?></p>
	</div>
	<div 
	class="text <?php echo esc_attr( $hasBg ); ?>" 
	style="<?php echo esc_attr( $bgImgUrl ); ?>" 
	<?php if ( ! empty( $section_id ) ) : ?> 
		id="<?php echo esc_attr( $section_id ); ?>" 
	<?php endif; ?>
	>
		<div 
		class="text__container giftcard__container <?php echo esc_attr( $contentBgClass ); ?>" 
		<?php if ( ! empty( $contentContainerStyles ) ) : ?> 
			style="<?php echo esc_attr( $contentContainerStyles ); ?>" 
		<?php endif; ?>
		>
			<?php 
			$image = get_sub_field( 'second_tab_image' );
			if ( ! empty( $image ) ) :
				$url = $image['url'];
				$alt = $image['alt']; 
			?>
				<img 
					alt="<?php echo esc_attr( $alt ); ?>" 
					class="giftcard__image" 
					src="<?php echo esc_url( $url ); ?>" 
				/>
			<?php endif; ?>

			<div class="text__contents giftcard__text">
				<?php the_sub_field( 'second_tab_text' ); ?>
			</div>
		</div>
	</div>
</div>