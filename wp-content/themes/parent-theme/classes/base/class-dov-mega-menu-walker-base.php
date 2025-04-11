<?php

class DOV_Mega_Menu_Walker_Base extends DOV_Walker_Nav_Menu {
	protected bool|WP_Post $mega_menu_item = false;
	protected bool $is_enqueued            = false;
	protected string $mega_menu_content    = '';

	public function start_lvl( &$output, $depth = 0, $args = array() ) : void {
		if ( $this->mega_menu_item ) {
			$output .= $this->get_expend_button( $args, $depth );
			$output .= $this->get_start_expend_wrapper( $args, $depth );
			$output .= '<div class="mega-menu"><theme-tabs class="mega-menu__tabs" hover>';
			$output .= '<p class="mega-menu__title">' . $this->current_link_title . '</p>';
		} else {
			parent::start_lvl( $output, $depth, $args );
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) : void {
		if ( $this->mega_menu_item ) {
			$output .= $this->mega_menu_content . '</theme-tabs></div>';
			$output .= $this->get_end_expend_wrapper();

			$this->mega_menu_item    = false;
			$this->mega_menu_content = '';
		} else {
			parent::end_lvl( $output, $depth, $args );
		}
	}

	/** @noinspection PhpUndefinedFieldInspection */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) : void {
		if ( $this->mega_menu_item ) {
			$output .= '<theme-tabs-button class="mega-menu__tabs-button">' . $data_object->title . '</theme-tabs-button>';

			$this->mega_menu_content .= '<theme-tabs-content class="mega-menu__tabs-content">';
			$this->mega_menu_content .= apply_filters( 'wld_get_mega_menu_tab_content', '', $data_object );
			$this->mega_menu_content .= '</theme-tabs-content>';

			return;
		}

		if ( in_array( '_has-mega-menu', $data_object->classes, true ) ) {
			$this->mega_menu_item = $data_object;
			if ( false === $this->is_enqueued ) {
				$this->is_enqueued = true;
				DOV_Enqueue_Scripts::enqueue_module( 'tabs.js', array() );
			}
		}

		parent::start_el( $output, $data_object, $depth, $args, $current_object_id );
	}

	public function end_el( &$output, $data_object, $depth = 0, $args = null ) : void {
		if ( ! $this->mega_menu_item ) {
			parent::end_el( $output, $data_object, $depth, $args );
		}
	}
}
