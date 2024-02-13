<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ilogic
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php the_field('head_script', 'option') ?> <!-- Head(er) External Code -->
	<?php wp_head(); ?>
	<meta name="theme-color" content="<?php the_field('primary_color', 'option'); ?>" />
	<style>
		:root {
			--color-1: <?php the_field('primary_color', 'option'); ?>;
			--color-2: <?php the_field('secondary_color', 'option'); ?>;
			--color-3: <?php the_field('third_color', 'option'); ?>;
			--color-text: <?php the_field('text_color', 'option'); ?>;
			--before-title-size-1-ld: <?php the_field('before_title_size_1_ld', 'option'); ?>;
			--before-title-size-2-ld: <?php the_field('before_title_size_2_ld', 'option'); ?>;
			--before-title-size-3-ld: <?php the_field('before_title_size_3_ld', 'option'); ?>;
			--before-title-size-1-mt: <?php the_field('before_title_size_1_mt', 'option'); ?>;
			--before-title-size-2-mt: <?php the_field('before_title_size_2_mt', 'option'); ?>;
			--before-title-size-3-mt: <?php the_field('before_title_size_3_mt', 'option'); ?>;
			--title-size-1-ld: <?php the_field('title_size_1_ld', 'option'); ?>;
			--title-size-2-ld: <?php the_field('title_size_2_ld', 'option'); ?>;
			--title-size-3-ld: <?php the_field('title_size_3_ld', 'option'); ?>;
			--title-size-4-ld: <?php the_field('title_size_4_ld', 'option'); ?>;
			--title-size-5-ld: <?php the_field('title_size_5_ld', 'option'); ?>;
			--title-size-1-mt: <?php the_field('title_size_1_mt', 'option'); ?>;
			--title-size-2-mt: <?php the_field('title_size_2_mt', 'option'); ?>;
			--title-size-3-mt: <?php the_field('title_size_3_mt', 'option'); ?>;
			--title-size-4-mt: <?php the_field('title_size_4_mt', 'option'); ?>;
			--title-size-5-mt: <?php the_field('title_size_5_mt', 'option'); ?>;
			--subtitle-size-1-ld: <?php the_field('subtitle_size_1_ld', 'option'); ?>;
			--subtitle-size-2-ld: <?php the_field('subtitle_size_2_ld', 'option'); ?>;
			--subtitle-size-3-ld: <?php the_field('subtitle_size_3_ld', 'option'); ?>;
			--subtitle-size-1-mt: <?php the_field('subtitle_size_1_mt', 'option'); ?>;
			--subtitle-size-2-mt: <?php the_field('subtitle_size_2_mt', 'option'); ?>;
			--subtitle-size-3-mt: <?php the_field('subtitle_size_3_mt', 'option'); ?>;
			--b-space-1-ld: <?php the_field('padding-xs-ld', 'option'); ?>;
			--b-space-2-ld: <?php the_field('padding-s-ld', 'option'); ?>;
			--b-space-3-ld: <?php the_field('padding-m-ld', 'option'); ?>;
			--b-space-4-ld: <?php the_field('padding-l-ld', 'option'); ?>;
			--b-space-5-ld: <?php the_field('padding-xl-ld', 'option'); ?>;
			--b-space-1-mt: <?php the_field('padding-xs-mt', 'option'); ?>;
			--b-space-2-mt: <?php the_field('padding-s-mt', 'option'); ?>;
			--b-space-3-mt: <?php the_field('padding-m-mt', 'option'); ?>;
			--b-space-4-mt: <?php the_field('padding-l-mt', 'option'); ?>;
			--b-space-5-mt: <?php the_field('padding-xl-mt', 'option'); ?>;
		}
	</style>
</head>

<body <?php body_class(); ?>>
<?php the_field('body_top_script', 'option') ?> <!-- Body Top External Script -->
<?php wp_body_open(); ?>
<div id="page" class="site header-version-1">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'ilogic' ); ?></a>
		<?php
			// Check rows existexists.
			if( have_rows('top_buttons', 'option') ): ?>
			<div class="header-top <?php the_field('buttons_position', 'option') ?>">
				<div class="section_inner">

				<?php while( have_rows('top_buttons', 'option') ) : the_row();

					$top_button = get_sub_field('top_button');
					$tb_url = $top_button['url'];
					$tb_title = $top_button['title'];
					$tb_target = $top_button['target'] ? $top_button['target'] : '_self'; ?>

					<a class="il_btn" href="<?php echo esc_url( $tb_url ); ?>" target="<?php echo esc_attr( $tb_target ); ?>"><?php echo esc_html( $tb_title ); ?></a>

				<?php endwhile; ?>
				</div>
			</div>
			<?php endif; ?>

	<?php 
		$post_header_background_color = get_field('post_header_background_color');
	?>
	<header id="masthead" class="header-main " <?php if ( $post_header_background_color && ! is_home() && ! is_category() ) echo 'style="background: ' . $post_header_background_color . '"'; ?>>
		<div class="container header-main-inner">			
			<nav id="site-navigation" class="main-navigation">
			<!-- Mobile Nav Button -->
			<input type="checkbox" class="menu-toggle" id="menu_trigger">
			<label for="menu_trigger">
				<span></span>
				<span></span>
				<span></span>
			</label>
			<!-- Mobile Nav Button -->
				<?php
				// Assuming $post_custom_links is the repeater field
				if ( have_rows('post_custom_links')  && ! is_home() && ! is_category() ){ ?>
					<div class="menu-main-menu-container acf-main-menu-container">
						<ul id="primary-menu" class="menu">
							<?php
								while (have_rows('post_custom_links')) : the_row();
									$menu_link = get_sub_field('link');
									$menu_link_url = $menu_link['url'];
									$menu_link_title = $menu_link['title'];
									$menu_link_target = $menu_link['target'] ? $menu_link['target'] : '_self';
							?>
									<li class="menu-item menu-item-type-custom menu-item-object-custom">
										<a href="<?php echo esc_url($menu_link_url); ?>" target="<?php echo esc_attr($menu_link_target); ?>">
											<?php echo esc_html($menu_link_title); ?>
										</a>
									</li>
							<?php
								endwhile;
							?>
						</ul>
					</div>
				<?php } else {
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
						)
					);
				} ?>
			</nav><!-- #site-navigation -->
			
			<?php
			$post_nav_btn = get_field('post_button_after_nav');
			$nav_btn = get_field('button_after_nav', 'option');
			if( $nav_btn && ! $post_nav_btn ):
				$nav_btn_url = $nav_btn['url'];
				$nav_btn_title = $nav_btn['title'];
				$nav_btn_target = $nav_btn['target'] ? $nav_btn['target'] : '_self';
				?>
				<a class="nav-button il_btn" href="<?php echo esc_url( $nav_btn_url ); ?>" target="<?php echo esc_attr( $nav_btn_target ); ?>"><?php echo esc_html( $nav_btn_title ); ?></a>
			<?php endif; ?>
			<?php 
			if( $post_nav_btn ):
				$post_nav_btn_url = $post_nav_btn['url'];
				$post_nav_btn_title = $post_nav_btn['title'];
				$post_nav_btn_target = $post_nav_btn['target'] ? $post_nav_btn['target'] : '_self';
				?>
				<a class="nav-button il_btn" href="<?php echo esc_url( $post_nav_btn_url ); ?>" target="<?php echo esc_attr( $post_nav_btn_target ); ?>"><?php echo esc_html( $post_nav_btn_title ); ?></a>
			<?php endif; ?>
			
			<div class="logo-wrap">
				<?php
					$post_custom_logo = get_field('post_custom_logo');
					$size = 'full';
					if( ! $post_custom_logo || is_home() || is_category() ) {
						the_custom_logo(); 
					}
				?>
				<?php 
					if ( $post_custom_logo && ! is_home() && ! is_category() ) :
				?>
				<a href="<?php echo get_home_url(); ?>" class="custom-logo-link" rel="home">
					<?php echo wp_get_attachment_image( $post_custom_logo, $size, false ); ?>
				</a>
				<?php endif; ?>
			</div><!-- .site-branding -->
		</div>
	</header><!-- #masthead -->
