/* global acf, jQuery */
( function ($) {
	'use strict';
	if ('undefined' === typeof acf) {
		return;
	}

	acf.fields.dovContactLink = acf.field.extend({
		type: 'dov_contact_link',
		$el: null,

		actions: {
			ready: 'init',
			append: 'init',
		},

		events: {
			'change [data-name="link_type"]': 'changeType',
			'click [data-name="paste"]': 'paste',
		},

		config: {
			selectors: {
				typeHref: '[data-name="link_type"]',
				icon: '[data-name="icon"]',
				url: '[data-name="number"]',
				prepend: '[data-name="prepend"]',
				paste: '[data-name="paste"]',
				title: '[data-name="title"]',
			},
		},

		dom: {},

		focus() {
			this.$el = this.$field.find('.acf-dov-contact-link');
			this.o = acf.get_data(this.$el);

			this.dom.body = $('body');
			this.dom.typeHref = this.$el.find(this.config.selectors.typeHref);
			this.dom.icon = this.$el.find(this.config.selectors.icon);
			this.dom.url = this.$el.find(this.config.selectors.url);
			this.dom.prepend = this.$el.find(this.config.selectors.prepend);
			this.dom.paste = this.$el.find(this.config.selectors.paste);
			this.dom.title = this.$el.find(this.config.selectors.title);
		},

		init() {
		},

		changeType() {
			const v = this.dom.typeHref.filter(':checked').val();
			let c = 'dashicons-phone';
			let t = 'tel';
			let p = 'tel:';
			let h = '+1234567890';

			if ('fax' === v) {
				c = 'dashicons-format-aside';
				p = 'fax:';
			} else if ('email' === v) {
				c = 'dashicons-email';
				t = 'email';
				p = 'mailto:';
				h = 'example@example.com';
			}

			this.dom.icon.attr('class', 'dashicons ' + c);
			this.dom.prepend.html(p);
			this.dom.url.attr('type', t).attr('placeholder', h);
		},

		paste() {
			let v,
				t = this.dom.title.val();

			if (this.dom.typeHref.filter(':checked').val()) {
				v = this.dom.typeHref.filter(':checked').val();
			} else {
				v = this.dom.typeHref.val();
			}

			if ('phone' === v || 'fax' === v) {
				t = t.replace(/[^0-9+]/g, '');
				if ('+' !== t.charAt(0)) {
					t = '+1' + t;
				}
			} else {
				t = this.getEmail(t);
			}

			// eslint-disable-next-line no-alert
			t = window.prompt('Paste number?', t);
			if (t) {
				this.dom.url.val(t);
			}
		},

		getEmail(s) {
			// noinspection RegExpAnonymousGroup
			const r =
					/(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/,
				m = r.exec(s);

			return m ? m[0] : '';
		},
	});
} )(jQuery);
