<!--
The section will automatically be id = section- $ i so that it can be referenced in a link

The page should have one <H1> heading. But as a rule, a manager can assemble a page from blocks into
which in HTML was not <H1> or vice versa, the manager can use two sides with <H1>.
In order to display only the first heading <H1> and lower the rest, use the dov_get_the_title ($ title, $ level, $ class) function to display the heading, and the main heading in the block should always be the first level, even if it is the second in the layout. On the first call to this function, <H1> will be displayed, and on all subsequent calls, the headers will be lowered.
And in this regard, if the layout designer is hooked on a tag, you need to ask him to hook on a class.
-->
<section class="block" <?php dov_the( 'background', '100x100' ); ?>>
	<div class="inner">
		<?php
		/**
		 * Easy use of the most common fields
		 */
		?>
		<?php dov_the( 'title', 'section-title' ); ?>
		<?php dov_the( 'text' ); ?>
		<?php dov_the( 'image', '100x100' ); ?>
		<?php dov_the( 'link' ); ?>
		<?php dov_the( 'button', 'btn' ); ?>
		<?php dov_the( 'phone' ); ?>
		<?php dov_the( 'email' ); ?>
		<?php dov_the( 'form' ); ?>
		<?php dov_the( 'map' ); ?>
		<?php dov_the( 'menu' ); ?>

		<hr>

		<?php
		/**
		 * If the field is "repeater" or 'flexible_content" then a sub cycle will be set for it
		 *
		 * $wrapper - To wrap elements in one element you can specify in this format
		 */
		?>
		<?php while ( dov_loop( 'items', '<div class="block__items">' ) ) : ?>
			<div class="block__item">
				<?php dov_the( 'title' ); ?>
				<?php dov_the( 'text' ); ?>
			</div>
		<?php endwhile; ?>

		<hr>

		<?php
		/**
		 * If this field is of type "relationship" then the global variable $post will be set
		 *
		 * $wrapper - For more complex element wrapping you can use "%s"
		 */
		?>
		<?php while ( dov_loop( 'posts', '<div class="wrapper"><div class="inner">%s</div></div>' ) ) : ?>
			<div class="block__post">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( '460x320' ); ?>
				</a>
				<h2><?php the_title(); ?></h2>
				<?php dov_the( 'custom_post_field' ); ?>
			</div>
		<?php endwhile; ?>

		<hr>

		<?php
		/**
		 * If the field is of type "link", "dov_contact_link" or value URL then the content will be wrapped in a <a>
		 * If another type then the content will be wrapped in a <div>
		 *
		 * $class - html attribute class
		 * $force - by default, if there is no value, the content will be wrapped in a <div>, this can be disabled
		 * $attrs - you can also add other html attributes for the wrapper the $attrs parameter
		 */
		?>
		<?php while ( dov_wrap( 'link', 'block__link', false, array( 'data-i' => 1 ) ) ) : ?>
			<?php dov_the( 'title' ); ?>
			<?php dov_the( 'text' ); ?>
		<?php endwhile; ?>

		<hr>

		<?php
		/**
		 * Checks if there are non-empty fields
		 *
		 * $selectors_and_maybe_conditional - selectors separated by commas, you can also specify
		 * 'AND' as the first argument, in this case all fields must be filled
		 */
		?>
		<?php if ( dov_has( 'title', 'text' ) ) : ?>
			<div class="block__content">
				<?php dov_the( 'title' ); ?>
				<?php dov_the( 'text' ); ?>
			</div>
		<?php endif; ?>

		<hr>

		<?php
		/**
		 * By default, the first heading on the page will be H1,
		 * all subsequent H2 unless a specific level is configured in the admin.
		 *
		 * Title field supports:
		 * $class
		 * $wrapper
		 */
		dov_the( 'title', 'title', '<div class="block__title">' );
		?>

		<?php
		/**
		 * Often you do not need a tall wysiwyg, its height can be adjusted in the admin panel.
		 *
		 * Text, textarea or wysiwyg fields supports:
		 * $wrapper
		 */
		dov_the( 'text', '<div class="block__text">' );
		?>

		<?php
		/**
		 * Image field supports:
		 * $size - image must always have a size
		 * $attr - @see wp_get_attachment_image() $attr param
		 * $wrapper
		 */
		dov_the( 'image', '460x320', array( 'class' => 'alignright' ), '<div class="block__img">' );
		?>

		<?php
		/**
		 * Link field supports:
		 * $class
		 * $empty
		 * $wrapper
		 */
		dov_the( 'link', 'more-link', false, '<div class="block__link">' );
		?>

		<?php
		/**
		 * DOV Contact Link field supports:
		 * $class
		 * $wrapper
		 * $attrs
		 */
		dov_the( 'phone', 'phone-link', '<p>', array( 'data-i' => 1 ) );
		?>

		<?php
		/**
		 * Form field supports:
		 * $show_title - whether to show the form header, hide by default
		 * $show_description- whether to show the form description, hide by default
		 * $wrapper
		 */
		dov_the( 'form', true, true, '<div class="block__form">' );
		?>

		<?php
		/**
		 * Google Map field supports:
		 * $marker - marker icon, should be in the "images" folder of the active theme
		 * $attrs
		 */
		dov_the( 'map', 'marker.png', array( 'data-i' => 1 ) );
		?>

		<?php
		/**
		 * Menu field supports:
		 * $args - @see wp_nav_menu() $args param
		 */
		dov_the( 'menu', array( 'container' => 'nav' ) );
		?>
	</div>
</section>
