<?php /** @noinspection UnknownInspectionInspection, HtmlUnknownAttribute */


class DOV_Importer_Base {
	public const ID = 'dov_importer';

	public static array $current = array();

	protected static array $imports = array();

	public static function init() : void {
		add_action( 'admin_init', array( static::class, 'admin_init' ) );
	}

	public static function admin_init() : void {
		if ( static::$imports ) {
			if ( defined( 'WP_LOAD_IMPORTERS' ) ) {
				add_action( 'admin_head', array( static::class, 'style' ) );
				add_action( 'admin_footer', array( static::class, 'script' ) );

				static::register_importer();
			}

			if ( wp_doing_ajax() ) {
				add_action( 'wp_ajax_dov_import_step', array( static::class, 'ajax_step' ) );
				add_action( 'wp_ajax_dov_import_stop', array( static::class, 'ajax_stop' ) );
			}
		}
	}

	protected static function register_importer() : void {
		register_importer(
			static::ID,
			// translators: %s Site Name
			sprintf( __( '%s Imports', 'theme' ), get_bloginfo( 'name' ) ),
			'',
			array( static::class, 'page' )
		);
	}

	public static function add( string $name, callable $callback, int $count = 100, array $fields = array() ) : void {
		static::$imports[ $name ] = array(
			'label'    => ucwords( str_replace( array( '_', '-' ), ' ', $name ) ),
			'callback' => $callback,
			'count'    => $count,
			'fields'   => $fields,
		);
	}

	public static function page() : void {
		// translators: %s Site Name
		$title = sprintf( __( '%s Imports', 'theme' ), get_bloginfo( 'name' ) );

		echo '<div class="wrap dov-import">';
		echo '<h1 class="wp-heading-inline">' . esc_html( $title ) . '</h1>';
		echo '<hr class="wp-header-end">';
		echo '<div id="poststuff">';
		do_action( 'dov_importer_before_blocks' );
		foreach ( static::$imports as $name => $import ) {
			static::the_block( $name, $import['label'], $import['fields'], $import['count'] );
		}
		do_action( 'dov_importer_after_blocks' );
		echo '</div>';
		echo '</div>';
	}

	protected static function the_block( string $name, string $label, array $fields, int $count ) : void {
		$transient      = get_transient( static::get_transient_name( $name ) );
		$values         = $transient['fields'] ?? array();
		$log            = $transient['log'] ?? array();
		$remaining_rows = $transient['remaining_rows'] ?? array();
		$full_rows      = $transient['full_rows'] ?? array();
		$count          = absint( $transient['count'] ?? $count );
		$main_info      = $transient['main_info'] ?? '';
		$has_progress   = false !== $transient;
		$progress       = $full_rows ? 100 - round( count( $remaining_rows ) * 100 / count( $full_rows ) ) : 0;
		$file_id        = $name . '-file';
		$progress_id    = $name . '-progress';
		$class          = $has_progress ? 'has-progress' : '';
		$file_enable    = apply_filters( 'dov_importer_file_enable', true, $name );
		$file_required  = apply_filters( 'dov_importer_file_required', $file_enable, $name );
		?>
		<div class="postbox dov-import-block hide-if-no-js <?php echo sanitize_html_class( $class ); ?>"
			 data-name="<?php echo $name; ?>">
			<h2 class="hndle" style="cursor: default"><span><?php echo esc_html( $label ); ?></span></h2>
			<div class="inside">
				<form>
					<div class="row stopped">
						<?php esc_html_e( 'Stopped!', 'theme' ); ?>
					</div>
					<div class="row progress">
						<label for="<?php echo esc_attr( $progress_id ); ?>">
							<?php esc_html_e( 'Progress:', 'theme' ); ?>
						</label>
						<progress id="<?php echo esc_attr( $progress_id ); ?>"
								  max="100"
								  value="<?php echo esc_attr( $progress ); ?>">
					</div>
					<?php if ( $file_enable ) : ?>
						<div class="row field field-file">
							<label for="<?php echo esc_attr( $file_id ); ?>">
								<?php esc_html_e( 'XLSX or CSV file', 'theme' ); ?>
							</label>
							<input
								name="<?php echo esc_attr( $file_id ); ?>"
								id="<?php echo esc_attr( $file_id ); ?>"
								type="file"
								class="<?php echo $file_required ? 'required' : ''; ?>"
								accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,.csv">
						</div>
					<?php endif; ?>
					<?php
					foreach ( $fields as $field ) {
						if ( empty( $field['name'] ) ) {
							continue;
						}

						$id          = esc_attr( $name . '-' . $field['name'] );
						$label       = esc_html( $field['label'] ?? '' );
						$description = esc_html( $field['description'] ?? '' );
						$type        = esc_attr( $field['type'] ?? 'text' );
						$placeholder = esc_attr( $field['placeholder'] ?? '' );
						$value       = $values[ $field['name'] ] ?? $field['default_value'] ?? '';
						$required    = $field['required'] ?? false;
						$options     = $field['options'] ?? array();
						$is_checked  = (bool) ( $field['checked'] ?? false );
						$class       = esc_attr( $field['class'] ?? '' );

						if ( empty( $class ) && ( 'text' === $type || 'textarea' === $type ) ) {
							$class = 'large-text';
						}

						if ( 'checkbox' === $type || 'radio' === $type ) {
							$is_checked = $value ? true : $is_checked;
						}

						if ( $value && 'textarea' === $type ) {
							$value = esc_textarea( $value );
						} elseif ( 'checkbox' === $type ) {
							$value = 'on';
						} else {
							$value = esc_attr( $value );
						}

						if ( $required ) {
							$class .= ' required';
						}

						echo '<div class="row field">';
						printf(
							'<label for="%s">%s</label>',
							esc_attr( $id ),
							esc_html( $label )
						);
						if ( 'select' === $type ) {
							$options_html = '';
							foreach ( $options as $option_value => $text ) {
								$option_value = esc_attr( $option_value );
								$options_html = printf(
									'<option value="%s" %s>%s</option>',
									$option_value,
									selected( $option_value, $value, false ),
									esc_html( $text )
								);
							}

							printf(
								'<select name="%s" id="%s" class="%s"><option value="">%s</option>%s</select>',
								$id,
								$id,
								$class,
								$placeholder ?: __( 'Select...', 'theme' ),
								$options_html
							);
						} elseif ( 'textarea' === $type ) {
							$height = absint( $field['height'] ?? 100 );
							printf(
								'<textarea name="%s" id="%s" placeholder="%s" class="%s" style="%s">%s</textarea>',
								$id,
								$id,
								$placeholder,
								$class,
								'height:' . $height . 'px;',
								$value
							);
						} elseif ( 'upload' === $type ) {
							$images          = '';
							$attachments_ids = wp_parse_id_list( $value );
							if ( $attachments_ids ) {
								foreach ( $attachments_ids as $attachment_id ) {
									$images .= sprintf(
										'<span class="thumbnail">%s</span>',
										wp_get_attachment_image( $attachment_id, 'thumbnail', true )
									);
								}
							}

							printf(
								'
								<div class="uploads %s" data-multiple="%s">
									%s
									<button type="button" class="button button-primary button-small select-uploads" id="%s">
										Select
									</button>
									<button type="button" class="button button-default button-small clear-uploads" style="%s">
										Clear
									</button>
									<input type="hidden" name="%s" value="%s">
								</div>
								',
								$class,
								empty( $field['multiple'] ) ? 'false' : 'true',
								$images,
								$id,
								$images ? 'display:inline-block' : 'display: none;',
								$id,
								$value
							);
						} else {
							printf(
								'<input %s name="%s" id="%s" placeholder="%s" value="%s" class="%s" %s>',
								'type="' . $type . '"',
								$id,
								$id,
								$placeholder,
								$value,
								$class,
								$is_checked ? 'checked' : ''
							);
						}

						if ( $description ) {
							echo '<p class="description">' . esc_html( $description ) . '</p>';
						}
						echo '</div>';
					}
					?>
					<div class="row log">
						<?php foreach ( $log as $row ) : ?>
							<?php $type = $row[2] ?? 'info'; ?>
							<div class="log-row log-row-<?php echo sanitize_html_class( $type ); ?>">
								<span class="log-row-date"><?php echo esc_html( $row[0] ?? '' ); ?></span>
								<span class="log-row-text"><?php echo esc_html( $row[1] ?? '' ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
					<div class="row main-info"><?php echo esc_html( $main_info ); ?></div>
					<?php if ( apply_filters( 'dov_importer_show_step_count_field', true, $name ) ) : ?>
						<div class="row count">
							<?php $id = esc_attr( $name . '-count' ); ?>
							<label for="<?php echo esc_attr( $id ); ?>">
								<?php esc_html_e( 'Step rows:', 'theme' ); ?>
							</label>
							<input type="number" name="count" class="small-text" min="0"
								   id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $count ); ?>">
						</div>
					<?php endif; ?>
					<div class="row buttons">
						<button type="submit" class="button button-primary start">
							<?php esc_html_e( 'Start', 'theme' ); ?>
						</button>
						<button type="button" class="button button-default button-disabled pause">
							<?php esc_html_e( 'Pause', 'theme' ); ?>
						</button>
						<button type="button"
								class="button button-default <?php echo $has_progress ? '' : 'button-disabled'; ?> stop">
							<?php esc_html_e( 'Stop', 'theme' ); ?>
						</button>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

	public static function get_transient_name( string $name ) : string {
		return 'dov_importer_' . $name;
	}

	public static function style() : void {
		?>
		<!--suppress CssUnusedSymbol -->
		<style>
			.dov-import * {
				box-sizing: border-box;
			}

			.dov-import .row:not(:first-child) {
				margin-top: 1em;
			}

			.dov-import .field-file [type="file"] {
				width: 100%;
			}

			.dov-import .postbox.in-progress .field,
			.dov-import .postbox.has-progress .field,
			.dov-import .postbox.in-progress .count,
			.dov-import .postbox.has-progress .count,
			.dov-import .postbox .stopped,
			.dov-import .postbox .progress {
				display: none !important;
			}

			.dov-import .postbox.in-progress .progress {
				display: block !important;
			}

			.dov-import .postbox.has-progress .stopped {
				display: block !important;
				color: red;
				font-size: 1.2em;
				font-weight: bold;
			}

			.dov-import .field:after {
				content: '';
				display: block;
				border-bottom: .1em #ccc solid;
				margin-top: 1em;
			}

			.dov-import .field .description {
				margin-bottom: -0.5em;
			}

			.dov-import .field .thumbnail {
				position: relative;
				display: inline-flex;
				width: 150px;
				height: 150px;
				box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.1), inset 0 0 0 1px rgba(0, 0, 0, 0.05);
				background: #eee;
				margin: 0 .3em .3em 0;
				vertical-align: top;
			}

			.dov-import .field .thumbnail:after {
				content: "";
				display: block;
				position: absolute;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.1);
				overflow: hidden;
			}

			.dov-import .field .thumbnail img {
				display: block;
				margin: auto;
				max-width: 100%;
				height: auto;
			}

			.dov-import .field label {
				display: inline-block;
				padding: 0 .2em;
				vertical-align: top;
			}

			.dov-import .invalid-field label {
				color: red;
			}

			.dov-import .invalid-field input,
			.dov-import .invalid-field textarea,
			.dov-import .invalid-field select {
				border-color: red;
			}

			.dov-import .progress {
				display: flex;
				flex-wrap: nowrap;
				justify-content: space-between;
				align-items: center;
			}

			.dov-import .progress progress {
				transition: all 1s ease;
				width: calc(100% - 5em);
			}

			.dov-import .in-progress .main-info {
				font-weight: 600;
			}

			.dov-import .log-row {
				display: flex;
			}

			.dov-import .log-row-date {
				display: inline-block;
				width: 6em;
				flex-shrink: 0;
			}

			.dov-import .log-row-text {
				display: inline-block;
			}

			.dov-import .log-row-success {
				color: green;
			}

			.dov-import .log-row-warning {
				color: orangered;
			}

			.dov-import .log-row-error {
				color: red;
			}

			.dov-import .in-progress .log-row-init:after,
			.dov-import .in-progress .main-info:not(:empty):after {
				content: '.';
				animation: loading 1s ease alternate infinite;
			}

			@keyframes loading {
				60% {
					text-shadow: 0.35em 0 0 currentColor;
				}
				100% {
					text-shadow: 0.35em 0 0 currentColor, 0.75em 0 0 currentColor;
				}
			}

			@media all and ( min-width: 376px ) {
				.dov-import .log-row-date {
					width: 10em;
				}

				.dov-import .row.count {
					float: right;
					margin-top: 0;
				}
			}

			@media all and ( min-width: 768px ) {
				.dov-import .field label {
					width: 10em;
				}

				.dov-import .field .uploads,
				.dov-import .field input,
				.dov-import .field textarea,
				.dov-import .field select {
					max-width: calc(100% - 10em);
				}

				.dov-import .field .uploads {
					display: inline-block;
				}

				.dov-import .field .description {
					padding-left: 10em;
				}
			}
		</style>
		<?php
	}

	public static function script() : void {
		?>
		<!--suppress HtmlRequiredAltAttribute, HtmlRequiredAltAttribute, RequiredAttributes, JSCheckFunctionSignatures, JSUnresolvedVariable, JSUnresolvedFunction -->
		<script>
			( function ($) {
				$.fn.dovImport = function () {
					if (this && this.length) {
						this.each(function () {
							const
								$block = $(this),
								$form = $('form', this),
								$start = $('button.start', this),
								$pause = $('button.pause', this),
								$stop = $('button.stop', this),
								$log = $('.log', this),
								$info = $('.main-info', this),
								$progress = $('.progress progress', this),
								$selectUploads = $('.select-uploads', this),
								$clearUploads = $('.clear-uploads', this);

							let jqXHR = null;

							$form.on('submit', function (e) {
								e.preventDefault();

								if (!$start.hasClass('button-disabled')) {
									const selectors = [
										'input.required:visible',
										'select.required:visible',
										'textarea.required:visible',
										'.required:visible input',
									].join(',');

									const $required = $(selectors, this);

									let valid = true;
									$required.each(function () {
										if (!$.trim($(this).val())) {
											valid = false;
											$(this).closest('.field').addClass('invalid-field');
										}
									});

									if (valid) {
										$log.html('<div class="log-row log-row-init">Processing</div>');
										$info.empty();
										step();
										$('html, body').animate({
											scrollTop: $('.log-row-init', this).offset().top - 200
										}, 500);
									} else {
										$('html, body').animate({
											scrollTop: $('.invalid-field', this).offset().top - 200
										}, 500);
									}
								}
							});

							$form.on('change', '.invalid-field', function () {
								const $input = $(':input:not(button)', this);
								if ($.trim($input.val().toString())) {
									$(this).removeClass('invalid-field');
								}
							});

							$pause.on('click', function () {
								if (!$pause.hasClass('button-disabled')) {
									pause();
								}
							});

							$stop.on('click', function () {
								if (!$stop.hasClass('button-disabled')) {
									stop();
								}
							});

							$selectUploads.on('click', function () {
								const
									$wrap = $(this).parent(),
									multiple = $wrap.attr('data-multiple') === 'true',
									uploader = wp.media({ multiple: multiple });

								uploader.on('select', function () {
									const values = [],
										$thumbnails = [];

									if (multiple) {
										uploader.state().get('selection').each(function (attachment) {
											values.push(attachment.get('id'));
											$thumbnails.push(getThumbnail(attachment));
										});
									} else {
										const attachment = uploader.state().get('selection').first();

										values.push(attachment.get('id'));
										$thumbnails.push(getThumbnail(attachment));
									}

									$wrap.find('.thumbnail').remove().end().prepend($thumbnails);
									$wrap.find('input').val(values.join(',')).trigger('change');
									$wrap.find('.clear-uploads').toggle($thumbnails.length > 0);
								}).open();
							});

							$clearUploads.on('click', function () {
								$(this).hide();
								$(this).parent().find('.thumbnail').remove();
								$(this).parent().find('input').val('');
							});

							function step() {
								if (jqXHR) {
									jqXHR.abort();
									jqXHR = null;
								}

								const data = new FormData($form.get(0));

								data.append('action', 'dov_import_step');
								data.append('_ajax_nonce', '<?php echo esc_js( wp_create_nonce( 'dov_importer' ) ); ?>');
								data.append('name', $block.attr('data-name'));

								jqXHR = $.ajax({
									url: ajaxurl,
									data: data,
									type: 'post',
									dataType: 'json',
									contentType: false,
									processData: false
								});

								jqXHR.done(function (response) {
									$block.trigger('dov_import_response', response);

									const
										success = response.success || false,
										data = response.data || {};

									update(data);
									if (success) {
										if (data.progress > -1) {
											step();
										} else {
											stop();
										}
									} else {
										alert('Error');
										stop();
									}
								});

								jqXHR.fail(function (response) {
									if (504 === response.status) {
										step();
									} else if (0 !== response.status) {
										alert('Error');
										stop();
									}
								});

								setInProgress();
							}

							function pause() {
								if (jqXHR) {
									jqXHR.abort();
									jqXHR = null;
								}

								setHasProgress();
							}

							function stop() {
								if (jqXHR) {
									jqXHR.abort();
									jqXHR = null;
								}

								$.ajax({
									url: ajaxurl,
									data: {
										action: 'dov_import_stop',
										name: $block.attr('data-name')
									},
									type: 'post',
									dataType: 'json',
								});

								setNotProgress();
							}

							function setHasProgress() {
								$block.removeClass('in-progress');
								$block.addClass('has-progress');

								$start.removeClass('button-disabled');
								$pause.addClass('button-disabled');
								$stop.removeClass('button-disabled');
							}

							function setInProgress() {
								$block.addClass('in-progress');
								$block.removeClass('has-progress');

								$start.addClass('button-disabled');
								$pause.removeClass('button-disabled');
								$stop.removeClass('button-disabled');
							}

							function setNotProgress() {
								$block.removeClass('in-progress');
								$block.removeClass('has-progress');

								$start.removeClass('button-disabled');
								$pause.addClass('button-disabled');
								$stop.addClass('button-disabled');

								$progress.val(0);

								$('.log-row-init').remove();
							}

							function update(data) {
								if (data.log) {
									const $logs = [];
									$.each(data.log, function (i, row) {
										const
											$date = $('<span>', { class: 'log-row-date', text: row[0] }),
											$text = $('<span>', { class: 'log-row-text', text: row[1] }),
											$row = $('<div>', { class: 'log-row log-row-' + ( row[2] || 'info' ) });

										$logs.push($row.append($date).append($text));
									});

									$log.empty().append($logs);
								}

								$info.text(data.main_info || '');

								if (data.progress) {
									$progress.val(data.progress);
								}
							}

							function getThumbnail(attachment) {
								const type = attachment.get('type');

								let src;

								if ('image' === type) {
									const sizes = attachment.get('sizes');
									if (sizes && sizes['thumbnail']) {
										src = sizes['thumbnail']['url'];
									} else {
										src = attachment.get('url');
									}
								} else {
									src = attachment.get('icon');
								}

								const $img = $('<img>', {
									src: src,
									alt: attachment.get('alt')
								});

								return $('<span>', { class: 'thumbnail', append: $img });
							}
						});
					}

					return this;
				};

				$('.dov-import-block').dovImport();
			} )(jQuery);
		</script>
		<?php
	}

	public static function ajax_stop() : void {
		$name = sanitize_text_field( $_POST['name'] ?? '' ); // phpcs:ignore WordPress.Security.NonceVerification
		if ( ! isset( static::$imports[ $name ] ) ) {
			wp_die( 'No Import' );
		}

		delete_transient( static::get_transient_name( $name ) );

		wp_send_json_success();
	}

	public static function ajax_step() : void {
		check_ajax_referer( 'dov_importer' );

		ignore_user_abort( false );

		$name = sanitize_text_field( $_POST['name'] ?? '' );
		if ( ! isset( static::$imports[ $name ] ) ) {
			wp_die( 'No Import' );
		}

		$import          = static::$imports[ $name ];
		static::$current = get_transient( static::get_transient_name( $name ) );

		if ( empty( static::$current ) ) {
			$file_name     = $name . '-file';
			$file          = ! empty( $_FILES[ $file_name ]['size'] ) ? $_FILES[ $file_name ] : array();
			$file_enable   = apply_filters( 'dov_importer_file_enable', true, $name );
			$file_required = apply_filters( 'dov_importer_file_required', $file_enable, $name );
			$fields        = array();

			if ( $file_required && empty( $file ) ) {
				wp_die( 'No File' );
			}

			$rows = array();
			if ( $file ) {
				$rows = DOV_XLSX_CSV::to_array( $file['tmp_name'], $file['name'] );

				unlink( $file['tmp_name'] );

				if ( is_wp_error( $rows ) ) {
					wp_die( 'Invalid Ext' );
				}

				if ( $rows ) {
					array_shift( $rows );
				}
			}

			foreach ( $import['fields'] as $field ) {
				if ( empty( $field['name'] ) ) {
					continue;
				}

				$post_key = $name . '-' . $field['name'];
				$type     = esc_attr( $field['type'] ?? 'text' );
				$value    = wp_kses_post( $_POST[ $post_key ] ?? '' );

				if ( 'checkbox' === $type ) {
					$value = $value ? 'on' : 'off';
				}

				$fields[ $field['name'] ] = $value;
			}

			$rows            = apply_filters( 'dov_importer_get_rows', $rows, $name );
			static::$current = array(
				'fields'         => $fields,
				'log'            => array(),
				'remaining_rows' => $rows,
				'full_rows'      => $rows,
				'count'          => absint( $_POST['count'] ?? $import['count'] ),
			);

			set_transient( static::get_transient_name( $name ), static::$current, DAY_IN_SECONDS );

			static::$current['main_info'] = sprintf( // translators: %d all rows
				esc_html__( 'Processed 0 rows of %d', 'theme' ),
				count( $rows )
			);

			wp_send_json_success(
				array(
					'log'       => static::$current['log'],
					'main_info' => static::$current['main_info'],
					'progress'  => 0,
				)
			);
		}

		$count = absint( static::$current['count'] ?? 0 );
		if ( $count ) {
			$current_rows         = array_slice( static::$current['remaining_rows'], 0, $count );
			$after_remaining_rows = array_slice( static::$current['remaining_rows'], $count );
		} else {
			$current_rows         = static::$current['remaining_rows'];
			$after_remaining_rows = array();
		}

		ob_flush();
		flush();
		if ( connection_aborted() ) {
			return;
		}

		/** @noinspection PhpUnhandledExceptionInspection */
		$callback_info = new ReflectionFunction( $import['callback'] );
		$args          = array_slice(
			array(
				$current_rows,
				$after_remaining_rows,
			),
			0,
			$callback_info->getNumberOfParameters()
		);

		call_user_func_array( $import['callback'], $args );

		if ( $after_remaining_rows ) {
			$full                              = count( static::$current['full_rows'] );
			$remaining                         = count( $after_remaining_rows );
			$done                              = $full - $remaining;
			static::$current['remaining_rows'] = $after_remaining_rows;
			static::$current['main_info']      = sprintf( // translators: %1$d - done rows, %2$d all rows
				esc_html__( 'Processed %1$d rows of %2$d', 'theme' ),
				$done,
				$full
			);
			set_transient( static::get_transient_name( $name ), static::$current, DAY_IN_SECONDS );
			$progress = 100 - round( $remaining * 100 / $full );
		} else {
			static::log( 'Complete!' );
			static::$current['main_info'] = '';
			delete_transient( static::get_transient_name( $name ) );
			$progress = - 1;
		}

		wp_send_json_success(
			array(
				'log'       => static::$current['log'],
				'main_info' => static::$current['main_info'],
				'progress'  => $progress,
			)
		);
	}

	public static function log( string $message, string $type = 'info', $id = null ) : void {
		if ( static::$current ) {
			if ( 'progress' === $type ) {
				$type = 'init';
			}

			$log_item = array(
				current_time( 'mysql' ),
				$message,
				$type, // info, success, warning, error, progress, init
			);

			if ( null !== $id ) {
				static::$current['log'][ $id ] = $log_item;
			} else {
				static::$current['log'][] = $log_item;
			}
		}
	}
}
