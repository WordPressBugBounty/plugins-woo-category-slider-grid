/**
 *
 * -----------------------------------------------------------
 *
 * ShapedPlugin
 *
 * -----------------------------------------------------------
 *
 */
; (function ($, window, document, undefined) {
	'use strict';

	//
	// Constants
	//
	var SP_WCS = SP_WCS || {};

	SP_WCS.funcs = {};

	SP_WCS.vars = {
		onloaded: false,
		$body: $('body'),
		$window: $(window),
		$document: $(document),
		is_rtl: $('body').hasClass('rtl'),
		code_themes: [],
	};

	//
	// Helper Functions
	//
	SP_WCS.helper = {

		//
		// Generate UID
		//
		uid: function (prefix) {
			return (prefix || '') + Math.random().toString(36).substr(2, 9);
		},

		// Quote regular expression characters
		//
		preg_quote: function (str) {
			return (str + '').replace(/(\[|\-|\])/g, "\\$1");
		},

		//
		// Reneme input names
		//
		name_nested_replace: function ($selector, field_id) {

			var checks = [];
			var regex = new RegExp('(' + SP_WCS.helper.preg_quote(field_id) + ')\\[(\\d+)\\]', 'g');

			$selector.find(':radio').each(function () {
				if (this.checked || this.orginal_checked) {
					this.orginal_checked = true;
				}
			});

			$selector.each(function (index) {
				$(this).find(':input').each(function () {
					this.name = this.name.replace(regex, field_id + '[' + index + ']');
					if (this.orginal_checked) {
						this.checked = true;
					}
				});
			});

		},

		//
		// Debounce
		//
		debounce: function (callback, threshold, immediate) {
			var timeout;
			return function () {
				var context = this, args = arguments;
				var later = function () {
					timeout = null;
					if (!immediate) {
						callback.apply(context, args);
					}
				};
				var callNow = (immediate && !timeout);
				clearTimeout(timeout);
				timeout = setTimeout(later, threshold);
				if (callNow) {
					callback.apply(context, args);
				}
			};
		},

		//
		// Get a cookie
		//
		get_cookie: function (name) {

			var e, b, cookie = document.cookie, p = name + '=';

			if (!cookie) {
				return;
			}

			b = cookie.indexOf('; ' + p);

			if (b === -1) {
				b = cookie.indexOf(p);

				if (b !== 0) {
					return null;
				}
			} else {
				b += 2;
			}

			e = cookie.indexOf(';', b);

			if (e === -1) {
				e = cookie.length;
			}

			return decodeURIComponent(cookie.substring(b + p.length, e));

		},

		//
		// Set a cookie
		//
		set_cookie: function (name, value, expires, path, domain, secure) {

			var d = new Date();

			if (typeof (expires) === 'object' && expires.toGMTString) {
				expires = expires.toGMTString();
			} else if (parseInt(expires, 10)) {
				d.setTime(d.getTime() + (parseInt(expires, 10) * 1000));
				expires = d.toGMTString();
			} else {
				expires = '';
			}

			document.cookie = name + '=' + encodeURIComponent(value) +
				(expires ? '; expires=' + expires : '') +
				(path ? '; path=' + path : '') +
				(domain ? '; domain=' + domain : '') +
				(secure ? '; secure' : '');

		},

		//
		// Remove a cookie
		//
		remove_cookie: function (name, path, domain, secure) {
			SP_WCS.helper.set_cookie(name, '', -1000, path, domain, secure);
		},

	};

	//
	// Custom clone for textarea and select clone() bug
	//
	$.fn.spf_clone = function () {

		var base = $.fn.clone.apply(this, arguments),
			clone = this.find('select').add(this.filter('select')),
			cloned = base.find('select').add(base.filter('select'));

		for (var i = 0; i < clone.length; ++i) {
			for (var j = 0; j < clone[i].options.length; ++j) {

				if (clone[i].options[j].selected === true) {
					cloned[i].options[j].selected = true;
				}

			}
		}

		this.find(':radio').each(function () {
			this.orginal_checked = this.checked;
		});

		return base;

	};

	//
	// Options Navigation
	//
	$.fn.spf_nav_options = function () {
		return this.each(function () {

			var $nav = $(this),
				$links = $nav.find('a'),
				$hidden = $nav.closest('.spf').find('.spf-section-id'),
				$last_section;

			$(window).on('hashchange', function () {

				var hash = window.location.hash.match(new RegExp('tab=([^&]*)'));
				var slug = hash ? hash[1] : $links.first().attr('href').replace('#tab=', '');
				var $link = $('#spf-tab-link-' + slug);

				if ($link.length > 0) {

					$link.closest('.spf-tab-depth-0').addClass('spf-tab-active').siblings().removeClass('spf-tab-active');
					$links.removeClass('spf-section-active');
					$link.addClass('spf-section-active');

					if ($last_section !== undefined) {
						$last_section.hide();
					}

					var $section = $('#spf-section-' + slug);
					$section.css({ display: 'block' });
					$section.spf_reload_script();

					$hidden.val(slug);

					$last_section = $section;

				}

			}).trigger('hashchange');

		});
	};

	//
	// Metabox Tabs
	//
	$.fn.spf_nav_metabox = function () {
		return this.each(function () {

			var $nav = $(this),
				$links = $nav.find('a'),
				unique_id = $nav.data('unique'),
				post_id = $('#post_ID').val() || 'global',
				$last_section,
				$last_link;

			$links.on('click', function (e) {

				e.preventDefault();

				var $link = $(this),
					section_id = $link.data('section');

				if ($last_link !== undefined) {
					$last_link.removeClass('spf-section-active');
				}

				if ($last_section !== undefined) {
					$last_section.hide();
				}

				$link.addClass('spf-section-active');

				var $section = $('#spf-section-' + section_id);
				$section.css({ display: 'block' });
				$section.spf_reload_script();

				SP_WCS.helper.set_cookie('spf-last-metabox-tab-' + post_id + '-' + unique_id, section_id);

				$last_section = $section;
				$last_link = $link;

			});

			var get_cookie = SP_WCS.helper.get_cookie('spf-last-metabox-tab-' + post_id + '-' + unique_id);

			if (get_cookie) {
				$nav.find('a[data-section="' + get_cookie + '"]').trigger('click');
			} else {
				$links.first('a').trigger('click');
			}

		});
	};

	//
	// Metabox Page Templates Listener
	//
	$.fn.spf_page_templates = function () {
		if (this.length) {

			$(document).on('change', '.editor-page-attributes__template select, #page_template', function () {

				var maybe_value = $(this).val() || 'default';

				$('.spf-page-templates').removeClass('spf-show').addClass('spf-hide');
				$('.spf-page-' + maybe_value.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-')).removeClass('spf-hide').addClass('spf-show');

			});

		}
	};

	//
	// Metabox Post Formats Listener
	//
	$.fn.spf_post_formats = function () {
		if (this.length) {

			$(document).on('change', '.editor-post-format select, #formatdiv input[name="post_format"]', function () {

				var maybe_value = $(this).val() || 'default';

				// Fallback for classic editor version
				maybe_value = (maybe_value === '0') ? 'default' : maybe_value;

				$('.spf-post-formats').removeClass('spf-show').addClass('spf-hide');
				$('.spf-post-format-' + maybe_value).removeClass('spf-hide').addClass('spf-show');

			});

		}
	};

	//
	// Sticky Header
	//
	$.fn.spf_sticky = function () {
		return this.each(function () {

			var $this = $(this),
				$window = $(window),
				$inner = $this.find('.spf-header-inner'),
				padding = parseInt($inner.css('padding-left')) + parseInt($inner.css('padding-right')),
				offset = 32,
				scrollTop = 0,
				lastTop = 0,
				ticking = false,
				stickyUpdate = function () {

					var offsetTop = $this.offset().top,
						stickyTop = Math.max(offset, offsetTop - scrollTop),
						winWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

					if (stickyTop <= offset && winWidth > 782) {
						$inner.css({ width: $this.outerWidth() - padding });
						$this.css({ height: $this.outerHeight() }).addClass('spf-sticky');
					} else {
						$inner.removeAttr('style');
						$this.removeAttr('style').removeClass('spf-sticky');
					}

				},
				requestTick = function () {

					if (!ticking) {
						requestAnimationFrame(function () {
							stickyUpdate();
							ticking = false;
						});
					}

					ticking = true;

				},
				onSticky = function () {

					scrollTop = $window.scrollTop();
					requestTick();

				};

			$window.on('scroll resize', onSticky);

			onSticky();

		});
	};

	//
	// Dependency System
	//
	$.fn.spf_dependency = function () {
		return this.each(function () {

			var $this = $(this),
				ruleset = $.spf_deps.createRuleset(),
				depends = [],
				is_global = false;

			$this.children('[data-controller]').each(function () {

				var $field = $(this),
					controllers = $field.data('controller').split('|'),
					conditions = $field.data('condition').split('|'),
					values = $field.data('value').toString().split('|'),
					rules = ruleset;

				if ($field.data('depend-global')) {
					is_global = true;
				}

				$.each(controllers, function (index, depend_id) {

					var value = values[index] || '',
						condition = conditions[index] || conditions[0];

					rules = rules.createRule('[data-depend-id="' + depend_id + '"]', condition, value);

					rules.include($field);

					depends.push(depend_id);

				});

			});

			if (depends.length) {

				if (is_global) {
					$.spf_deps.enable(SP_WCS.vars.$body, ruleset, depends);
				} else {
					$.spf_deps.enable($this, ruleset, depends);
				}

			}

		});
	};

	//
	// Field: code_editor
	//
	$.fn.spf_field_code_editor = function () {
		return this.each(function () {

			if (typeof CodeMirror !== 'function') { return; }

			var $this = $(this),
				$textarea = $this.find('textarea'),
				$inited = $this.find('.CodeMirror'),
				data_editor = $textarea.data('editor');

			if ($inited.length) {
				$inited.remove();
			}

			var interval = setInterval(function () {
				if ($this.is(':visible')) {

					var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor);

					// load code-mirror theme css.
					if (data_editor.theme !== 'default' && SP_WCS.vars.code_themes.indexOf(data_editor.theme) === -1) {

						var $cssLink = $('<link>');

						$('#spf-codemirror-css').after($cssLink);

						$cssLink.attr({
							rel: 'stylesheet',
							id: 'spf-codemirror-' + data_editor.theme + '-css',
							href: data_editor.cdnURL + '/theme/' + data_editor.theme + '.min.css',
							type: 'text/css',
							media: 'all'
						});

						SP_WCS.vars.code_themes.push(data_editor.theme);

					}

					CodeMirror.modeURL = data_editor.cdnURL + '/mode/%N/%N.min.js';
					CodeMirror.autoLoadMode(code_editor, data_editor.mode);

					code_editor.on('change', function (editor, event) {
						$textarea.val(code_editor.getValue()).trigger('change');
					});

					clearInterval(interval);

				}
			});

		});
	};

	//
	// Field: spinner
	//
	$.fn.spf_field_spinner = function () {
		return this.each(function () {

			var $this = $(this),
				$input = $this.find('input'),
				$inited = $this.find('.ui-spinner-button');

			if ($inited.length) {
				$inited.remove();
			}

			$input.spinner({
				max: $input.data('max') || 100,
				min: $input.data('min') || 0,
				step: $input.data('step') || 1,
				spin: function (event, ui) {
					$input.val(ui.value).trigger('change');
				}
			});


		});
	};

	//
	// Field: tabbed
	//
	$.fn.spf_field_tabbed = function () {
		return this.each(function () {
			var $this = $(this),
				$links = $this.find('.spf-tabbed-nav a'),
				$sections = $this.find('.spf-tabbed-section');

			$links.on('click', function (e) {
				e.preventDefault();
				var $link = $(this),
					index = $link.index(),
					$section = $sections.eq(index);

				// Store the active tab index in a cookie
				SP_WCS.helper.set_cookie('activeTabIndex', index);

				$link.addClass('spf-tabbed-active').siblings().removeClass('spf-tabbed-active');
				$section.spf_reload_script();
				$section.removeClass('hidden').siblings().addClass('hidden');
			});
			// Check if there's a stored active tab index in the cookie
			var activeTabIndex = SP_WCS.helper.get_cookie('activeTabIndex');
			// Check if the cookie exists
			if (activeTabIndex !== null) {
				$links.eq(activeTabIndex).trigger('click');
			} else {
				$links.first().trigger('click');
			}
		});
	};

	//
	// Field: switcher
	//
	$.fn.spf_field_switcher = function () {
		return this.each(function () {

			var $switcher = $(this).find('.spf--switcher');

			$switcher.on('click', function () {

				var value = 0;
				var $input = $switcher.find('input');

				if ($switcher.hasClass('spf--active')) {
					$switcher.removeClass('spf--active');
				} else {
					value = 1;
					$switcher.addClass('spf--active');
				}

				$input.val(value).trigger('change');

			});

		});
	};

	//
	// Field: fieldset
	//
	$.fn.spf_field_fieldset = function () {
		return this.each(function () {
			$(this).find('.spf-fieldset-content').spf_reload_script();
		});
	};

	//
	// Field: slider
	//
	$.fn.spf_field_slider = function () {
		return this.each(function () {
			var $this = $(this),
				$input = $this.find('input'),
				$slider = $this.find('.spf-slider-ui'),
				data = $input.data(),
				value = $input.val() || 0;
			if ($slider.hasClass('ui-slider')) {
				$slider.empty();
			}

			$slider.slider({
				range: 'min',
				value: value,
				min: data.min || 0,
				max: data.max || 100,
				step: data.step || 1,
				slide: function (e, o) {
					$input.val(o.value).trigger('change');
				}
			});

			$input.on('keyup', function () {
				$slider.slider('value', $input.val());
			});

		});
	};

	//
	// Field: typography
	//
	$.fn.spf_field_typography = function () {
		return this.each(function () {

			var base = this;
			var $this = $(this);
			var loaded_fonts = [];
			var webfonts = '';
			var googlestyles = '';
			var defaultstyles = '';

			//
			//
			// Sanitize google font subset
			base.sanitize_subset = function (subset) {
				subset = subset.replace('-ext', ' Extended');
				subset = subset.charAt(0).toUpperCase() + subset.slice(1);
				return subset;
			};

			//
			//
			// Sanitize google font styles (weight and style)
			base.sanitize_style = function (style) {
				return googlestyles[style] ? googlestyles[style] : style;
			};

			//
			//
			// Load google font
			base.load_google_font = function (font_family, weight, style) {

				if (font_family && typeof WebFont === 'object') {

					weight = weight ? weight.replace('normal', '') : '';
					style = style ? style.replace('normal', '') : '';

					if (weight || style) {
						font_family = font_family + ':' + weight + style;
					}

					if (loaded_fonts.indexOf(font_family) === -1) {
						WebFont.load({ google: { families: [font_family] } });
					}

					loaded_fonts.push(font_family);

				}

			};

			//
			//
			// Append select options
			base.append_select_options = function ($select, options, condition, type, is_multi) {

				$select.find('option').not(':first').remove();

				var opts = '';

				$.each(options, function (key, value) {

					var selected;
					var name = value;

					// is_multi
					if (is_multi) {
						selected = (condition && condition.indexOf(value) !== -1) ? ' selected' : '';
					} else {
						selected = (condition && condition === value) ? ' selected' : '';
					}

					if (type === 'subset') {
						name = base.sanitize_subset(value);
					} else if (type === 'style') {
						name = base.sanitize_style(value);
					}

					opts += '<option value="' + value + '"' + selected + '>' + name + '</option>';

				});

				$select.append(opts).trigger('spf.change').trigger('chosen:updated');

			};

			base.init = function () {

				//
				//
				// Constants
				var selected_styles = [];
				var $typography = $this.find('.spf--typography');
				var $type = $this.find('.spf--type');
				var unit = $typography.data('unit');
				var exclude_fonts = $typography.data('exclude') ? $typography.data('exclude').split(',') : [];

				//
				//
				// Chosen init
				if ($this.find('.spf--chosen').length) {

					var $chosen_selects = $this.find('select');

					$chosen_selects.each(function () {

						var $chosen_select = $(this),
							$chosen_inited = $chosen_select.parent().find('.chosen-container');

						if ($chosen_inited.length) {
							$chosen_inited.remove();
						}

						$chosen_select.chosen({
							allow_single_deselect: true,
							disable_search_threshold: 15,
							width: '100%'
						});

					});

				}

				//
				//
				// Font family select
				var $font_family_select = $this.find('.spf--font-family');
				var first_font_family = $font_family_select.val();

				// Clear default font family select options
				$font_family_select.find('option').not(':first-child').remove();

				var opts = '';

				$.each(webfonts, function (type, group) {

					// Check for exclude fonts
					if (exclude_fonts && exclude_fonts.indexOf(type) !== -1) { return; }

					opts += '<optgroup label="' + group.label + '">';

					$.each(group.fonts, function (key, value) {

						// use key if value is object
						value = (typeof value === 'object') ? key : value;
						var selected = (value === first_font_family) ? ' selected' : '';
						opts += '<option value="' + value + '" data-type="' + type + '"' + selected + '>' + value + '</option>';

					});

					opts += '</optgroup>';

				});

				// Append google font select options
				//$font_family_select.append(opts).trigger('chosen:updated');

				//
				//
				// Font style select
				var $font_style_block = $this.find('.spf--block-font-style');

				if ($font_style_block.length) {

					var $font_style_select = $this.find('.spf--font-style-select');
					var first_style_value = $font_style_select.val() ? $font_style_select.val().replace(/normal/g, '') : '';

					//
					// Font Style on on change listener
					$font_style_select.on('change spf.change', function (event) {

						var style_value = $font_style_select.val();

						// set a default value
						if (!style_value && selected_styles && selected_styles.indexOf('normal') === -1) {
							style_value = selected_styles[0];
						}

						// set font weight, for eg. replacing 800italic to 800
						var font_normal = (style_value && style_value !== 'italic' && style_value === 'normal') ? 'normal' : '';
						var font_weight = (style_value && style_value !== 'italic' && style_value !== 'normal') ? style_value.replace('italic', '') : font_normal;
						var font_style = (style_value && style_value.substr(-6) === 'italic') ? 'italic' : '';

						$this.find('.spf--font-weight').val(font_weight);
						$this.find('.spf--font-style').val(font_style);

					});

					//
					//
					// Extra font style select
					var $extra_font_style_block = $this.find('.spf--block-extra-styles');

					if ($extra_font_style_block.length) {
						var $extra_font_style_select = $this.find('.spf--extra-styles');
						var first_extra_style_value = $extra_font_style_select.val();
					}

				}

				//
				//
				// Subsets select
				var $subset_block = $this.find('.spf--block-subset');
				if ($subset_block.length) {
					var $subset_select = $this.find('.spf--subset');
					var first_subset_select_value = $subset_select.val();
					var subset_multi_select = $subset_select.data('multiple') || false;
				}

				//
				//
				// Backup font family
				var $backup_font_family_block = $this.find('.spf--block-backup-font-family');

				//
				//
				// Font Family on Change Listener
				$font_family_select.on('change spf.change', function (event) {

					// // Hide subsets on change
					// if( $subset_block.length ) {
					//   $subset_block.addClass('hidden');
					// }

					// // Hide extra font style on change
					// if( $extra_font_style_block.length ) {
					//   $extra_font_style_block.addClass('hidden');
					// }

					// Hide backup font family on change
					if ($backup_font_family_block.length) {
						$backup_font_family_block.addClass('hidden');
					}

					var $selected = $font_family_select.find(':selected');
					var value = $selected.val();
					var type = $selected.data('type');

					if (type && value) {

						// Show backup fonts if font type google or custom
						if ((type === 'google' || type === 'custom') && $backup_font_family_block.length) {
							$backup_font_family_block.removeClass('hidden');
						}

						// Appending font style select options
						if ($font_style_block.length) {

							// set styles for multi and normal style selectors
							var styles = defaultstyles;

							// Custom or gogle font styles
							if (type === 'google' && webfonts[type].fonts[value][0]) {
								styles = webfonts[type].fonts[value][0];
							} else if (type === 'custom' && webfonts[type].fonts[value]) {
								styles = webfonts[type].fonts[value];
							}

							selected_styles = styles;

							// Set selected style value for avoid load errors
							var set_auto_style = (styles.indexOf('normal') !== -1) ? 'normal' : styles[0];
							var set_style_value = (first_style_value && styles.indexOf(first_style_value) !== -1) ? first_style_value : set_auto_style;

							// Append style select options
							base.append_select_options($font_style_select, styles, set_style_value, 'style');

							// Clear first value
							first_style_value = false;

							// Show style select after appended
							$font_style_block.removeClass('hidden');

							// Appending extra font style select options
							if (type === 'google' && $extra_font_style_block.length && styles.length > 1) {

								// Append extra-style select options
								base.append_select_options($extra_font_style_select, styles, first_extra_style_value, 'style', true);

								// Clear first value
								first_extra_style_value = false;

								// Show style select after appended
								$extra_font_style_block.removeClass('hidden');

							}

						}

						// Appending google fonts subsets select options
						if (type === 'google' && $subset_block.length && webfonts[type].fonts[value][1]) {

							var subsets = webfonts[type].fonts[value][1];
							var set_auto_subset = (subsets.length < 2 && subsets[0] !== 'latin') ? subsets[0] : '';
							var set_subset_value = (first_subset_select_value && subsets.indexOf(first_subset_select_value) !== -1) ? first_subset_select_value : set_auto_subset;

							// check for multiple subset select
							set_subset_value = (subset_multi_select && first_subset_select_value) ? first_subset_select_value : set_subset_value;

							base.append_select_options($subset_select, subsets, set_subset_value, 'subset', subset_multi_select);

							first_subset_select_value = false;

							$subset_block.removeClass('hidden');

						}

					} else {

						// Clear subsets options if type and value empty
						if ($subset_block.length) {
							$subset_select.find('option').not(':first-child').remove();
							$subset_select.trigger('chosen:updated');
						}

						// Clear font styles options if type and value empty
						if ($font_style_block.length) {
							$font_style_select.find('option').not(':first-child').remove();
							$font_style_select.trigger('chosen:updated');
						}

					}

					// Update font type input value
					$type.val(type);

				}).trigger('spf.change');

				//
				//
				// Preview
				//var $preview_block = $this.find('.spf--block-preview');
				var $preview_block = '';

				if ($preview_block.length) {

					var $preview = $this.find('.spf--preview');

					// Set preview styles on change
					$this.on('change', SP_WCS.helper.debounce(function (event) {

						$preview_block.removeClass('hidden');

						var font_family = $font_family_select.val(),
							font_weight = $this.find('.spf--font-weight').val(),
							font_style = $this.find('.spf--font-style').val(),
							font_size = $this.find('.spf--font-size').val(),
							font_variant = $this.find('.spf--font-variant').val(),
							line_height = $this.find('.spf--line-height').val(),
							text_align = $this.find('.spf--text-align').val(),
							text_transform = $this.find('.spf--text-transform').val(),
							text_decoration = $this.find('.spf--text-decoration').val(),
							text_color = $this.find('.spf--color').val(),
							word_spacing = $this.find('.spf--word-spacing').val(),
							letter_spacing = $this.find('.spf--letter-spacing').val(),
							custom_style = $this.find('.spf--custom-style').val(),
							type = $this.find('.spf--type').val();

						if (type === 'google') {
							base.load_google_font(font_family, font_weight, font_style);
						}

						var properties = {};

						if (font_family) { properties.fontFamily = font_family; }
						if (font_weight) { properties.fontWeight = font_weight; }
						if (font_style) { properties.fontStyle = font_style; }
						if (font_variant) { properties.fontVariant = font_variant; }
						if (font_size) { properties.fontSize = font_size + unit; }
						if (line_height) { properties.lineHeight = line_height + unit; }
						if (letter_spacing) { properties.letterSpacing = letter_spacing + unit; }
						if (word_spacing) { properties.wordSpacing = word_spacing + unit; }
						if (text_align) { properties.textAlign = text_align; }
						if (text_transform) { properties.textTransform = text_transform; }
						if (text_decoration) { properties.textDecoration = text_decoration; }
						if (text_color) { properties.color = text_color; }

						$preview.removeAttr('style');

						// Customs style attribute
						if (custom_style) { $preview.attr('style', custom_style); }

						$preview.css(properties);

					}, 100));

					// Preview black and white backgrounds trigger
					$preview_block.on('click', function () {

						$preview.toggleClass('spf--black-background');

						var $toggle = $preview_block.find('.spf--toggle');

						if ($toggle.hasClass('fa-toggle-off')) {
							$toggle.removeClass('fa-toggle-off').addClass('fa-toggle-on');
						} else {
							$toggle.removeClass('fa-toggle-on').addClass('fa-toggle-off');
						}

					});

					if (!$preview_block.hasClass('hidden')) {
						$this.trigger('change');
					}

				}

			};

			base.init();

		});
	};

	//
	// Confirm
	//
	$.fn.spf_confirm = function () {
		return this.each(function () {
			$(this).on('click', function (e) {

				var confirm_text = $(this).data('confirm') || window.spf_vars.i18n.confirm;
				var confirm_answer = confirm(confirm_text);
				SP_WCS.vars.is_confirm = true;

				if (!confirm_answer) {
					e.preventDefault();
					SP_WCS.vars.is_confirm = false;
					return false;
				}

			});
		});
	};

	$.fn.serializeObject = function () {

		var obj = {};

		$.each(this.serializeArray(), function (i, o) {
			var n = o.name,
				v = o.value;

			obj[n] = obj[n] === undefined ? v
				: $.isArray(obj[n]) ? obj[n].concat(v)
					: [obj[n], v];
		});

		return obj;

	};

	//
	// Options Save
	//
	$.fn.spf_save = function () {
		return this.each(function () {

			var $this = $(this),
				$buttons = $('.spf-save'),
				$panel = $('.spf-options'),
				flooding = false,
				timeout;

			$this.on('click', function (e) {

				if (!flooding) {

					var $text = $this.data('save'),
						$value = $this.val();

					$buttons.attr('value', $text);

					if ($this.hasClass('spf-save-ajax')) {

						e.preventDefault();

						$panel.addClass('spf-saving');
						$buttons.prop('disabled', true);

						window.wp.ajax.post('spf_' + $panel.data('unique') + '_ajax_save', {
							data: $('#spf-form').serializeJSONSP_WCS()
						})
							.done(function (response) {

								clearTimeout(timeout);

								var $result_success = $('.spf-form-success');

								$result_success.empty().append(response.notice).slideDown('fast', function () {
									timeout = setTimeout(function () {
										$result_success.slideUp('fast');
									}, 2000);
								});

								// clear errors
								$('.spf-error').remove();

								var $append_errors = $('.spf-form-error');

								$append_errors.empty().hide();

								if (Object.keys(response.errors).length) {

									var error_icon = '<i class="spf-label-error spf-error">!</i>';

									$.each(response.errors, function (key, error_message) {

										var $field = $('[data-depend-id="' + key + '"]'),
											$link = $('#spf-tab-link-' + ($field.closest('.spf-section').index() + 1)),
											$tab = $link.closest('.spf-tab-depth-0');

										$field.closest('.spf-fieldset').append('<p class="spf-text-error spf-error">' + error_message + '</p>');

										if (!$link.find('.spf-error').length) {
											$link.append(error_icon);
										}

										if (!$tab.find('.spf-arrow .spf-error').length) {
											$tab.find('.spf-arrow').append(error_icon);
										}

										console.log(error_message);

										$append_errors.append('<div>' + error_icon + ' ' + error_message + '</div>');

									});

									$append_errors.show();

								}

								$panel.removeClass('spf-saving');
								$buttons.prop('disabled', false).attr('value', $value);
								flooding = false;

							})
							.fail(function (response) {
								alert(response.error);
							});

					}

				}

				flooding = true;

			});

		});
	};

	//
	// Taxonomy Framework
	//
	$.fn.spf_taxonomy = function () {
		return this.each(function () {

			var $this = $(this),
				$form = $this.parents('form');

			if ($form.attr('id') === 'addtag') {

				var $submit = $form.find('#submit'),
					$cloned = $this.find('.spf-field').spf_clone();

				$submit.on('click', function () {

					if (!$form.find('.form-required').hasClass('form-invalid')) {

						$this.data('inited', false);

						$this.empty();

						$this.html($cloned);

						$cloned = $cloned.spf_clone();

						$this.spf_reload_script();

					}

				});

			}

		});
	};

	//
	// Shortcode Framework
	//
	$.fn.spf_shortcode = function () {

		var base = this;

		base.shortcode_parse = function (serialize, key) {

			var shortcode = '';

			$.each(serialize, function (shortcode_key, shortcode_values) {

				key = (key) ? key : shortcode_key;

				shortcode += '[' + key;

				$.each(shortcode_values, function (shortcode_tag, shortcode_value) {

					if (shortcode_tag === 'content') {

						shortcode += ']';
						shortcode += shortcode_value;
						shortcode += '[/' + key + '';

					} else {

						shortcode += base.shortcode_tags(shortcode_tag, shortcode_value);

					}

				});

				shortcode += ']';

			});

			return shortcode;

		};

		base.shortcode_tags = function (shortcode_tag, shortcode_value) {

			var shortcode = '';

			if (shortcode_value !== '') {

				if (typeof shortcode_value === 'object' && !$.isArray(shortcode_value)) {

					$.each(shortcode_value, function (sub_shortcode_tag, sub_shortcode_value) {

						// sanitize spesific key/value
						switch (sub_shortcode_tag) {

							case 'background-image':
								sub_shortcode_value = (sub_shortcode_value.url) ? sub_shortcode_value.url : '';
								break;

						}

						if (sub_shortcode_value !== '') {
							shortcode += ' ' + sub_shortcode_tag.replace('-', '_') + '="' + sub_shortcode_value.toString() + '"';
						}

					});

				} else {

					shortcode += ' ' + shortcode_tag.replace('-', '_') + '="' + shortcode_value.toString() + '"';

				}

			}

			return shortcode;

		};

		base.insertAtChars = function (_this, currentValue) {

			var obj = (typeof _this[0].name !== 'undefined') ? _this[0] : _this;

			if (obj.value.length && typeof obj.selectionStart !== 'undefined') {
				obj.focus();
				return obj.value.substring(0, obj.selectionStart) + currentValue + obj.value.substring(obj.selectionEnd, obj.value.length);
			} else {
				obj.focus();
				return currentValue;
			}

		};

		base.send_to_editor = function (html, editor_id) {

			var tinymce_editor;

			if (typeof tinymce !== 'undefined') {
				tinymce_editor = tinymce.get(editor_id);
			}

			if (tinymce_editor && !tinymce_editor.isHidden()) {
				tinymce_editor.execCommand('mceInsertContent', false, html);
			} else {
				var $editor = $('#' + editor_id);
				$editor.val(base.insertAtChars($editor, html)).trigger('change');
			}

		};

		return this.each(function () {

			var $modal = $(this),
				$load = $modal.find('.spf-modal-load'),
				$content = $modal.find('.spf-modal-content'),
				$insert = $modal.find('.spf-modal-insert'),
				$loading = $modal.find('.spf-modal-loading'),
				$select = $modal.find('select'),
				modal_id = $modal.data('modal-id'),
				nonce = $modal.data('nonce'),
				editor_id,
				target_id,
				gutenberg_id,
				sc_key,
				sc_name,
				sc_view,
				sc_group,
				$cloned,
				$button;

			$(document).on('click', '.spf-shortcode-button[data-modal-id="' + modal_id + '"]', function (e) {

				e.preventDefault();

				$button = $(this);
				editor_id = $button.data('editor-id') || false;
				target_id = $button.data('target-id') || false;
				gutenberg_id = $button.data('gutenberg-id') || false;

				$modal.show();

				// single usage trigger first shortcode
				if ($modal.hasClass('spf-shortcode-single') && sc_name === undefined) {
					$select.trigger('change');
				}

			});

			$select.on('change', function () {

				var $option = $(this);
				var $selected = $option.find(':selected');

				sc_key = $option.val();
				sc_name = $selected.data('shortcode');
				sc_view = $selected.data('view') || 'normal';
				sc_group = $selected.data('group') || sc_name;

				$load.empty();

				if (sc_key) {

					$loading.show();

					window.wp.ajax.post('spf-get-shortcode-' + modal_id, {
						shortcode_key: sc_key,
						nonce: nonce
					})
						.done(function (response) {

							$loading.hide();

							var $appended = $(response.content).appendTo($load);

							$insert.parent().removeClass('hidden');

							$cloned = $appended.find('.spf--repeat-shortcode').spf_clone();

							$appended.spf_reload_script();
							$appended.find('.spf-fields').spf_reload_script();

						});

				} else {

					$insert.parent().addClass('hidden');

				}

			});

			$insert.on('click', function (e) {

				e.preventDefault();

				var shortcode = '';
				var serialize = $modal.find('.spf-field:not(.hidden)').find(':input').serializeObjectSP_WCS();

				switch (sc_view) {

					case 'contents':
						var contentsObj = (sc_name) ? serialize[sc_name] : serialize;
						$.each(contentsObj, function (sc_key, sc_value) {
							var sc_tag = (sc_name) ? sc_name : sc_key;
							shortcode += '[' + sc_tag + ']' + sc_value + '[/' + sc_tag + ']';
						});
						break;

					case 'group':

						shortcode += '[' + sc_name;
						$.each(serialize[sc_name], function (sc_key, sc_value) {
							shortcode += base.shortcode_tags(sc_key, sc_value);
						});
						shortcode += ']';
						shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
						shortcode += '[/' + sc_name + ']';

						break;

					case 'repeater':
						shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
						break;

					default:
						shortcode += base.shortcode_parse(serialize);
						break;

				}

				if (gutenberg_id) {

					var content = window.spf_gutenberg_props.attributes.hasOwnProperty('shortcode') ? window.spf_gutenberg_props.attributes.shortcode : '';
					window.spf_gutenberg_props.setAttributes({ shortcode: content + shortcode });

				} else if (editor_id) {

					base.send_to_editor(shortcode, editor_id);

				} else {

					var $textarea = (target_id) ? $(target_id) : $button.parent().find('textarea');
					$textarea.val(base.insertAtChars($textarea, shortcode)).trigger('change');

				}

				$modal.hide();

			});

			$modal.on('click', '.spf--repeat-button', function (e) {

				e.preventDefault();

				var $repeatable = $modal.find('.spf--repeatable');
				var $new_clone = $cloned.spf_clone();
				var $remove_btn = $new_clone.find('.spf-repeat-remove');

				var $appended = $new_clone.appendTo($repeatable);

				$new_clone.find('.spf-fields').spf_reload_script();

				SP_WCS.helper.name_nested_replace($modal.find('.spf--repeat-shortcode'), sc_group);

				$remove_btn.on('click', function () {

					$new_clone.remove();

					SP_WCS.helper.name_nested_replace($modal.find('.spf--repeat-shortcode'), sc_group);

				});

			});

			$modal.on('click', '.spf-modal-close, .spf-modal-overlay', function () {
				$modal.hide();
			});

		});
	};

	//
	// Helper Checkbox Checker
	//
	$.fn.spf_checkbox = function () {
		return this.each(function () {

			var $this = $(this),
				$input = $this.find('.spf--input'),
				$checkbox = $this.find('.spf--checkbox');

			$checkbox.on('click', function () {
				$input.val(Number($checkbox.prop('checked'))).trigger('change');
			});

		});
	};


	//
	// Siblings
	//
	$.fn.spf_siblings = function () {
		return this.each(function () {

			var $this = $(this),
				$siblings = $this.find('.spf--sibling:not(.wcsp-pro-feature)'),
				multiple = $this.data('multiple') || false;

			$siblings.on('click', function () {

				var $sibling = $(this);

				if (multiple) {

					if ($sibling.hasClass('spf--active')) {
						$sibling.removeClass('spf--active');
						$sibling.find('input').prop('checked', false).trigger('change');
					} else {
						$sibling.addClass('spf--active');
						$sibling.find('input').prop('checked', true).trigger('change');
					}

				} else {

					$this.find('input').prop('checked', false);
					$sibling.find('input').prop('checked', true).trigger('change');
					$sibling.addClass('spf--active').siblings().removeClass('spf--active');

				}

			});

		});
	};

	//
	// WP Color Picker
	//
	if (typeof Color === 'function') {

		Color.fn.toString = function () {

			if (this._alpha < 1) {
				return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
			}

			var hex = parseInt(this._color, 10).toString(16);

			if (this.error) { return ''; }

			if (hex.length < 6) {
				for (var i = 6 - hex.length - 1; i >= 0; i--) {
					hex = '0' + hex;
				}
			}

			return '#' + hex;

		};

	}

	SP_WCS.funcs.parse_color = function (color) {

		var value = color.replace(/\s+/g, ''),
			trans = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
			rgba = (trans < 100) ? true : false;

		return { value: value, transparent: trans, rgba: rgba };

	};

	$.fn.spf_color = function () {
		return this.each(function () {

			var $input = $(this),
				picker_color = SP_WCS.funcs.parse_color($input.val()),
				palette_color = window.spf_vars.color_palette.length ? window.spf_vars.color_palette : true,
				$container;

			// Destroy and Reinit
			if ($input.hasClass('wp-color-picker')) {
				$input.closest('.wp-picker-container').after($input).remove();
			}

			$input.wpColorPicker({
				palettes: palette_color,
				change: function (event, ui) {

					var ui_color_value = ui.color.toString();

					$container.removeClass('spf--transparent-active');
					$container.find('.spf--transparent-offset').css('background-color', ui_color_value);
					$input.val(ui_color_value).trigger('change');

				},
				create: function () {

					$container = $input.closest('.wp-picker-container');

					var a8cIris = $input.data('a8cIris'),
						$transparent_wrap = $('<div class="spf--transparent-wrap">' +
							'<div class="spf--transparent-slider"></div>' +
							'<div class="spf--transparent-offset"></div>' +
							'<div class="spf--transparent-text"></div>' +
							'<div class="spf--transparent-button button button-small">transparent</div>' +
							'</div>').appendTo($container.find('.wp-picker-holder')),
						$transparent_slider = $transparent_wrap.find('.spf--transparent-slider'),
						$transparent_text = $transparent_wrap.find('.spf--transparent-text'),
						$transparent_offset = $transparent_wrap.find('.spf--transparent-offset'),
						$transparent_button = $transparent_wrap.find('.spf--transparent-button');

					if ($input.val() === 'transparent') {
						$container.addClass('spf--transparent-active');
					}

					$transparent_button.on('click', function () {
						if ($input.val() !== 'transparent') {
							$input.val('transparent').trigger('change').removeClass('iris-error');
							$container.addClass('spf--transparent-active');
						} else {
							$input.val(a8cIris._color.toString()).trigger('change');
							$container.removeClass('spf--transparent-active');
						}
					});

					$transparent_slider.slider({
						value: picker_color.transparent,
						step: 1,
						min: 0,
						max: 100,
						slide: function (event, ui) {

							var slide_value = parseFloat(ui.value / 100);
							a8cIris._color._alpha = slide_value;
							$input.wpColorPicker('color', a8cIris._color.toString());
							$transparent_text.text((slide_value === 1 || slide_value === 0 ? '' : slide_value));

						},
						create: function () {

							var slide_value = parseFloat(picker_color.transparent / 100),
								text_value = slide_value < 1 ? slide_value : '';

							$transparent_text.text(text_value);
							$transparent_offset.css('background-color', picker_color.value);

							$container.on('click', '.wp-picker-clear', function () {

								a8cIris._color._alpha = 1;
								$transparent_text.text('');
								$transparent_slider.slider('option', 'value', 100);
								$container.removeClass('spf--transparent-active');
								$input.trigger('change');

							});

							$container.on('click', '.wp-picker-default', function () {

								var default_color = SP_WCS.funcs.parse_color($input.data('default-color')),
									default_value = parseFloat(default_color.transparent / 100),
									default_text = default_value < 1 ? default_value : '';

								a8cIris._color._alpha = default_value;
								$transparent_text.text(default_text);
								$transparent_slider.slider('option', 'value', default_color.transparent);

							});

							$container.on('click', '.wp-color-result', function () {
								$transparent_wrap.toggle();
							});

							$('body').on('click.wpcolorpicker', function () {
								$transparent_wrap.hide();
							});

						}
					});
				}
			});

		});
	};

	//
	// ChosenJS
	//
	$.fn.spf_chosen = function () {
		return this.each(function () {

			var $this = $(this),
				$inited = $this.parent().find('.chosen-container'),
				is_multi = $this.attr('multiple') || false,
				set_width = is_multi ? '100%' : 'auto',
				set_options = $.extend({
					allow_single_deselect: true,
					disable_search_threshold: 15,
					width: set_width
				}, $this.data());

			if ($inited.length) {
				$inited.remove();
			}

			$this.chosen(set_options);

		});
	};

	//
	// Number (only allow numeric inputs)
	//
	$.fn.spf_number = function () {
		return this.each(function () {

			$(this).on('keypress', function (e) {

				if (e.keyCode !== 0 && e.keyCode !== 8 && e.keyCode !== 45 && e.keyCode !== 46 && (e.keyCode < 48 || e.keyCode > 57)) {
					return false;
				}

			});

		});
	};

	//
	// Help Tooltip
	//
	$.fn.spf_help = function () {
		return this.each(function () {

			const $this = $(this);
			let $tooltip, offsetLeft;

			// Event handler for mouseenter and mouseleave
			$this.on({
				mouseenter: function () {
					const hasSupportClass = $this.find('.spf-support').length > 0;
					const tooltipClass = hasSupportClass ? 'support-tooltip' : '';
					const titleHelpText = $this.find('.spf-help-text').html();

					// Create or update the tooltip
					$tooltip = $('.spf-tooltip').length > 0
						? $('.spf-tooltip').html(titleHelpText)
						: $('<div>', {
							class: `spf-tooltip ${tooltipClass}`,
							html: titleHelpText
						}).appendTo('body');

					// Adjust regular tooltip position
					const regularOffsetAdjustment = 33;
					offsetLeft = $this.offset().left + regularOffsetAdjustment;

					// Calculate tooltip top offset
					const tooltipTopOffset = $tooltip.outerHeight() / 2 - 14;
					let topOffset = $this.offset().top - tooltipTopOffset;

					// Adjust tooltip position based on support class and RTL settings
					if (hasSupportClass) {
						topOffset = $this.offset().top + 53;
						const supportOffsetAdjustment = SP_WCS.vars.is_rtl ? 26 : 213;
						offsetLeft = $this.offset().left - supportOffsetAdjustment;

						$tooltip.css({
							top: topOffset,
							left: offsetLeft,
						});
					} else {
						const positionAdjustment = SP_WCS.vars.is_rtl
							? { right: $(window).width() - (offsetLeft - 42) }
							: { left: offsetLeft };

						$tooltip.css({
							top: topOffset,
							...positionAdjustment
						});
					}
				},
				mouseleave: function () {
					if (!$tooltip.is(':hover')) {
						$tooltip.remove();
					}
				}
			});

			// Event delegation to handle tooltip removal when leaving the tooltip itself
			$('body').on('mouseleave', '.spf-tooltip', function () {
				if ($tooltip) {
					$tooltip.remove();
				}
			});

		});
	};

	//
	// Customize Refresh
	//
	$.fn.spf_customizer_refresh = function () {
		return this.each(function () {

			var $this = $(this),
				$complex = $this.closest('.spf-customize-complex');

			if ($complex.length) {

				var $input = $complex.find(':input'),
					$unique = $complex.data('unique-id'),
					$option = $complex.data('option-id'),
					obj = $input.serializeObjectSP_WCS(),
					data = (!$.isEmptyObject(obj)) ? obj[$unique][$option] : '',
					control = wp.customize.control($unique + '[' + $option + ']');

				// clear the value to force refresh.
				control.setting._value = null;

				control.setting.set(data);

			} else {

				$this.find(':input').first().trigger('change');

			}

			$(document).trigger('spf-customizer-refresh', $this);

		});
	};

	//
	// Customize Listen Form Elements
	//
	$.fn.spf_customizer_listen = function (options) {

		var settings = $.extend({
			closest: false,
		}, options);

		return this.each(function () {

			if (window.wp.customize === undefined) { return; }

			var $this = (settings.closest) ? $(this).closest('.spf-customize-complex') : $(this),
				$input = $this.find(':input'),
				unique_id = $this.data('unique-id'),
				option_id = $this.data('option-id');

			if (unique_id === undefined) { return; }

			$input.on('change keyup', SP_WCS.helper.debounce(function () {

				var obj = $this.find(':input').serializeObjectSP_WCS();

				if (!$.isEmptyObject(obj) && obj[unique_id]) {

					window.wp.customize.control(unique_id + '[' + option_id + ']').setting.set(obj[unique_id][option_id]);

				}

			}, 250));

		});
	};

	//
	// Customizer Listener for Reload JS
	//
	$(document).on('expanded', '.control-section-spf', function () {

		var $this = $(this);

		if ($this.hasClass('open') && !$this.data('inited')) {
			$this.spf_dependency();
			$this.find('.spf-customize-field').spf_reload_script({ dependency: false });
			$this.find('.spf-customize-complex').spf_customizer_listen();
			$this.data('inited', true);
		}

	});

	//
	// Window on resize
	//
	SP_WCS.vars.$window.on('resize spf.resize', SP_WCS.helper.debounce(function (event) {

		var window_width = navigator.userAgent.indexOf('AppleWebKit/') > -1 ? SP_WCS.vars.$window.width() : window.innerWidth;

		if (window_width <= 782 && !SP_WCS.vars.onloaded) {
			$('.spf-section').spf_reload_script();
			SP_WCS.vars.onloaded = true;
		}

	}, 200)).trigger('spf.resize');

	//
	// Widgets Framework
	//
	$.fn.spf_widgets = function () {
		if (this.length) {

			$(document).on('widget-added widget-updated', function (event, $widget) {
				$widget.find('.spf-fields').spf_reload_script();
			});

			$('.widgets-sortables, .control-section-sidebar').on('sortstop', function (event, ui) {
				ui.item.find('.spf-fields').spf_reload_script_retry();
			});

			$(document).on('click', '.widget-top', function (event) {
				$(this).parent().find('.spf-fields').spf_reload_script();
			});

		}
	};

	//
	// Retry Plugins
	//
	$.fn.spf_reload_script_retry = function () {
		return this.each(function () {

			var $this = $(this);

		});
	};

	//
	// Reload Plugins
	//
	$.fn.spf_reload_script = function (options) {

		var settings = $.extend({
			dependency: true,
		}, options);

		return this.each(function () {

			var $this = $(this);

			// Avoid for conflicts
			if (!$this.data('inited')) {

				// Field plugins
				$this.children('.spf-field-code_editor').spf_field_code_editor();
				$this.children('.spf-field-spinner').spf_field_spinner();
				$this.children('.spf-field-tabbed').spf_field_tabbed();
				$this.children('.spf-field-switcher').spf_field_switcher();
				$this.children('.spf-field-fieldset').spf_field_fieldset();
				$this.children('.spf-field-fieldset_tx').spf_field_fieldset();
				$this.children('.spf-field-fieldset_cpt').spf_field_fieldset();
				$this.children('.spf-field-slider').spf_field_slider();
				$this.children('.spf-field-typography').spf_field_typography();

				// Field colors
				$this.children('.spf-field-gradient_color').find('.spf-color').spf_color();
				$this.children('.spf-field-border').find('.spf-color').spf_color();
				$this.children('.spf-field-box_shadow').find('.spf-color').spf_color();
				$this.children('.spf-field-color_group').find('.spf-color').spf_color();
				$this.children('.spf-field-color').find('.spf-color').spf_color();
				$this.children('.spf-field-typography').find('.spf-color').spf_color();

				// Field allows only number
				$this.children('.spf-field-spacing').find('.spf-number').spf_number();
				$this.children('.spf-field-column').find('.spf-number').spf_number();
				$this.children('.spf-field-dimensions_advanced').find('.spf-number').spf_number();
				$this.children('.spf-field-spinner').find('.spf-number').spf_number();
				$this.children('.spf-field-typography').find('.spf-number').spf_number();

				// Field chosenjs
				$this.children('.spf-field-select').find('.spf-chosen').spf_chosen();
				$this.children('.spf-field-selectf').find('.spf-chosen').spf_chosen();

				// Field Checkbox
				$this.children('.spf-field-checkbox').find('.spf-checkbox').spf_checkbox();

				// Field Siblings
				$this.children('.spf-field-button_set').find('.spf-siblings').spf_siblings();
				$this.children('.spf-field-button_setf').find('.spf-siblings').spf_siblings();
				$this.children('.spf-field-image_select').find('.spf-siblings').spf_siblings();

				// Help Tooptip
				$this.children('.spf-field').find('.spf-help').spf_help();

				if (settings.dependency) {
					$this.spf_dependency();
				}

				$this.data('inited', true);

				$(document).trigger('spf-reload-script', $this);

			}

		});
	};

	//
	// Document ready and run scripts
	//
	$(document).ready(function () {

		$('.spf-save').spf_save();
		$('.spf-confirm').spf_confirm();
		$('.spf-nav-options').spf_nav_options();
		$('.spf-nav-metabox').spf_nav_metabox();
		$('.spf-sticky-header').spf_sticky();
		$('.spf-taxonomy').spf_taxonomy();
		$('.spf-shortcode').spf_shortcode();
		$('.spf-page-templates').spf_page_templates();
		$('.spf-post-formats').spf_post_formats();
		$('.spf-onload').spf_reload_script();
		$('.widget').spf_widgets();
		$('.wcsp-submit-options')
			.spf_help();

	});
	$("select option:contains((Pro))").attr('disabled', true).css('opacity', '0.8');
	$("label:contains((Pro))").css({ 'pointer-events': 'none' }).css('opacity', '0.8');
	$('#spf-section-sp_wcsp_copy_shortcode_1 .wcsp-scode-wrap .wcsp-scode-content .selectable').on('click', function (e) {
		e.preventDefault();
		wcsp_copyToClipboard($(this));
		wcsp_SelectText($(this));
		$(this).trigger('focus').trigger('select');
		jQuery(".wcsp-after-copy-text:not(.woo-cat-pagination-not-work)").animate({
			opacity: 1,
			bottom: 15
		}, 300);
		setTimeout(function () {
			jQuery(".wcsp-after-copy-text:not(.woo-cat-pagination-not-work)").animate({
				opacity: 0,
			}, 200);
			jQuery(".wcsp-after-copy-text:not(.woo-cat-pagination-not-work)").animate({
				bottom: -50
			}, 0);
		}, 2000);
	});
	// $("._wcsp_output input[type='text']").click(function () {
	//   $(this).select();
	// });
	$('.post-type-sp_wcslider .column-shortcode input').on('click', function (e) {
		e.preventDefault();
		/* Get the text field */
		var copyText = $(this);
		/* Select the text field */
		copyText.trigger('select');
		document.execCommand("copy");
		jQuery(".wcsp-after-copy-text").animate({
			opacity: 1,
			bottom: 15
		}, 300);
		setTimeout(function () {
			jQuery(".wcsp-after-copy-text").animate({
				opacity: 0,
			}, 200);
			jQuery(".wcsp-after-copy-text").animate({
				bottom: -50
			}, 0);
		}, 2000);
	});
	function wcsp_copyToClipboard(element) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text()).trigger('select');
		document.execCommand("copy");
		$temp.remove();
	}
	function wcsp_SelectText(element) {
		var r = document.createRange();
		var w = element.get(0);
		r.selectNodeContents(w);
		var sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(r);
	}
	// Check the string is a valid JSON string.
	function isValidJSONString(str) {
		try {
			JSON.parse(str);
		} catch (e) {
			return false;
		}
		return true;
	}
	// Woo-Category-Slider export.
	var $export_type = $('.wcsp_what_export').find('input:checked').val();
	$('.wcsp_what_export').on('change', function () {
		$export_type = $(this).find('input:checked').val();
	});

	$('.wcsp_export .spf--button').on('click', function (event) {
		event.preventDefault();
		var $shortcode_ids = $('.wcsp_post_ids select').val();
		var $ex_nonce = $('#spf_options_nonce').val();
		var selected_shortcode = $export_type === 'selected_shortcodes' ? $shortcode_ids : 'all_shortcodes';
		if ($export_type === 'all_shortcodes' || $export_type === 'selected_shortcodes') {
			var data = {
				action: 'wcsp_export_shortcodes',
				wcsp_ids: selected_shortcode,
				nonce: $ex_nonce,
			}
		} else {
			$('.spf-form-result.spf-form-success').text('No carousel selected.').show();
			setTimeout(function () {
				$('.spf-form-result.spf-form-success').hide().text('');
			}, 3000);
		}
		$.post(ajaxurl, data, function (resp) {
			if (resp) {
				// Convert JSON Array to string.
				if (isValidJSONString(resp)) {
					var json = JSON.stringify(JSON.parse(resp));
				} else {
					var json = JSON.stringify(resp);
				}

				// Convert JSON string to BLOB.
				var blob = new Blob([json], { type: 'application/json' });
				var link = document.createElement('a');
				var wcsp_time = $.now();
				link.href = window.URL.createObjectURL(blob);
				link.download = "woo-category-slider-pro-export-" + wcsp_time + ".json";
				link.click();
				$('.spf-form-result.spf-form-success').text('Exported successfully!').show();
				setTimeout(function () {
					$('.spf-form-result.spf-form-success').hide().text('');
					$('.wcsp_post_ids select').val('').trigger('chosen:updated');
				}, 3000);
			}
		});
	});

	// Category Slider import.
	$('.wcsp_import button.import').on('click', function (event) {
		event.preventDefault();
		var wcsp_shortcodes = $('#import').prop('files')[0];
		if ($('#import').val() != '') {
			var $im_nonce = $('#spf_options_nonce').val();
			var reader = new FileReader();
			reader.readAsText(wcsp_shortcodes);
			reader.onload = function (event) {
				var jsonObj = JSON.stringify(event.target.result);
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						shortcode: jsonObj,
						action: 'wcsp_import_shortcodes',
						nonce: $im_nonce,
					},
					success: function (resp) {
						$('.spf-form-result.spf-form-success').text('Imported successfully!').show();
						setTimeout(function () {
							$('.spf-form-result.spf-form-success').hide().text('');
							$('#import').val('');
							window.location.replace($('#wcsp_shortcode_link_redirect').attr('href'));
						}, 2000);
					}
				});
			}
		} else {
			$('.spf-form-result.spf-form-success').text('No exported json file chosen.').show();
			setTimeout(function () {
				$('.spf-form-result.spf-form-success').hide().text('');
			}, 3000);
		}
	});

	// Live Preview script.
	var preview_box = $('#sp-wcs-preview-box');
	var preview_display = $('#sp_wcsp_live_preview').hide();
	$(document).on('click', '#sp-wcsp-show-preview:contains(Hide)', function (e) {
		e.preventDefault();
		var _this = $(this);
		_this.html('<i class="fa fa-eye" aria-hidden="true"></i> Show Preview');
		preview_box.html('');
		preview_display.hide();
	});
	$(document).on('click', '#sp-wcsp-show-preview:not(:contains(Hide))', function (e) {
		e.preventDefault();
		var layoutPreset = $('.wcsp_layout_presets').find('input[name="sp_wcsp_shortcode_options[wcsp_layout_presets]"]:checked').val();
		var _data = $('form#post').serialize();
		var _this = $(this);
		var data = {
			action: 'sp_wcsp_preview_meta_box',
			data: _data,
			ajax_nonce: $('#spf_metabox_nonce').val()
		};
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			error: function (response) {
				console.log(response)
			},
			success: function (response) {
				preview_display.show();
				preview_box.html(response);
				_this.html('<i class="fa fa-eye-slash" aria-hidden="true"></i> Hide Preview');
				$(document).on('keyup change', '.post-type-sp_wcslider', function (e) {
					e.preventDefault();
					_this.html('<i class="fa fa-refresh" aria-hidden="true"></i> Update Preview');
				});
				$("html, body").animate({ scrollTop: preview_display.offset().top - 50 }, "slow");
			}
		})
	});

	$(document).on('keyup change', '.sp_wcslider_page_wcsp_settings #spf-form', function (e) {
		e.preventDefault();
		var $button = $(this).find('.spf-save');
		$button.css({ "background-color": "#00C263", "pointer-events": "initial" }).val('Save Settings');
	});
	$('.sp_wcslider_page_wcsp_settings .spf-save').on('click', function (e) {
		e.preventDefault();
		$(this).css({ "background-color": "#C5C5C6", "pointer-events": "none" }).val('Changes Saved');
	});

	var navigationVal = $('.sp_category-carousel-nav-position select').find('option:selected').val();
	// show hide the pro feature's notice of carousel navigation.
	if (navigationVal != 'top-right') {
		$('.sp_category-carousel-nav-position').find('.spf-description-wrap').show();
	} else {
		$('.sp_category-carousel-nav-position').find('.spf-description-wrap').hide();
	}

	/* Carousel Navigation - Select Position Preview */
	function navigationPositionPreview(selector, regex) {
		var str = "";
		$(selector + ' option:selected').each(function () {
			str = $(this).val();
			// show hide the pro feature's notice of carousel navigation.
			if (str != 'top-right') {
				$('.sp_category-carousel-nav-position').find('.spf-description-wrap').show();
			} else {
				$('.sp_category-carousel-nav-position').find('.spf-description-wrap').hide();
			}
		});
		var src = $(selector + ' .spf-fieldset img').attr('src');
		var result = src.match(regex);
		if (result && result[1]) {
			src = src.replace(result[1], str);
			$(selector + ' .spf-fieldset img').attr('src', src);
		}
	}
	$('.sp_category-carousel-nav-position').on('change', function () {
		navigationPositionPreview(".sp_category-carousel-nav-position", /carousel-navigation\/(.+)\.svg/);
	});

	// Parent and child custom script.
	$(document).on('click', function () {
		$('.wcsp-chosen-container-free .wcsp-chosen-drop').slideUp(0);
	});

	$('.wcsp-chosen-container-free').on('click', function (event) {
		event.stopPropagation();
		$('.wcsp-chosen-container-free .wcsp-chosen-drop').slideDown(0);
	});

	// Get selector.
	var layoutPresetSelector = $('input[name="sp_wcsp_layout_options[wcsp_layout_presets]"]');
	var layoutPresetValue = $('input[name="sp_wcsp_layout_options[wcsp_layout_presets]"]:checked').val();
	var mainNavFourSelector = $('#sp_wcsp_shortcode_options').find('.spf-nav.spf-nav-metabox li:nth-child(4)');

	// Onchange layout.
	layoutPresetSelector.on( 'change', function () {
		let $this = $(this).val();

		// Show/hide main navigation items.
		if ( ['carousel', 'slider', 'multi_row'].includes( $this ) ) {
			mainNavFourSelector.show();
		} else {
			mainNavFourSelector.hide();
		}
	});

	// Revert the selection to the last valid activated option that was selected before if the disabled/pro option is chosen.
	$('#publishing-action').on('click', '#publish', function (e) {
		if ($('input[name="sp_wcsp_layout_options[wcsp_layout_presets]"]:checked').is(':disabled')) {
			$('input[name="sp_wcsp_layout_options[wcsp_layout_presets]"][value="' + layoutPresetValue + '"]').prop('checked', true);
		}
	});

	const cardStyleSelector = $('input[name="sp_wcsp_shortcode_options[wcsp_make_it_card_style]"]');
	cardStyleSelector.on('change', function () {
		const selectedValue = $(this).val();
		if (selectedValue == 1) {
			// Set the border radius and border width to 4px.
			$(document).find('input[name="sp_wcsp_shortcode_options[wcsp_slide_border][all]"]').val('1');
			$(document).find('input[name="sp_wcsp_shortcode_options[wcsp_slide_border][radius]"]').val('4');
			$(document).find('.wcsp_category_thumb_border .spf--switcher').removeClass('spf--active').find('input').val(0);
		} else {
			// Set the border radius and border width to 0px.
			$(document).find('input[name="sp_wcsp_shortcode_options[wcsp_slide_border][all]"],input[name="sp_wcsp_shortcode_options[wcsp_slide_border][radius]"]').val('0');
			$(document).find('.wcsp_category_thumb_border .spf--switcher:not(.spf--active)').addClass('spf--active').find('input').val(1);
		}
	});


	$('.wcsp-icon-demo_link').on('click', function (e) {
		e.stopPropagation();
	});

	$('.testimonial_approval_status').on('change', 'select', function () {
		var optionVal = $(this).find('option:selected').val(),
			approvalStatusNotice = $('.testimonial-approval-status-notice');
		if (optionVal == 'publish' || optionVal == 'based_on_rating_star') {
			$(approvalStatusNotice).show();
		} else {
			$(approvalStatusNotice).hide();
		}
	});
})(jQuery, window, document);
