'use strict';

jQuery( document ).ready( function( $ ) {
	if ( ! $( '.woosb-products' ).length ) {
		return;
	}

	var _wrap = $( '.woosb-products' ).eq( 0 );
	var _ids = $( '.woosb-ids' ).eq( 0 );
	var _total = $( '.woosb-total' ).eq( 0 );
	var _btn = _wrap.closest( '.summary' ).find( '.single_add_to_cart_button' );
	if ( ! _btn.length ) {
		_btn = _wrap.closest( 'div' ).find( '.single_add_to_cart_button' );
	}

	if ( ! _btn.length ) {
		console.log( 'Have an issue with your template, so might WPC Product Bundles doesn\'t work completely. Please contact us via email contact@wpclever.net to get the help.' );
	}

	woosb_check_ready();

	$( document ).on( 'found_variation', function( e, t ) {
		var _product = $( e['target'] ).closest( '.woosb-product' );
		if ( t['image']['url'] && t['image']['srcset'] ) {
			// change image
			_product.find( '.woosb-thumb-ori' ).hide();
			_product.find( '.woosb-thumb-new' ).html( '<img src="' + t['image']['url'] + '" srcset="' + t['image']['srcset'] + '"/>' ).show();
		}
		if ( t['price_html'] ) {
			// change price
			_product.find( '.woosb-price-ori' ).hide();
			_product.find( '.woosb-price-new' ).html( t['price_html'] ).show();
		}
		if ( t['is_purchasable'] ) {
			// change stock notice
			if ( t['is_in_stock'] ) {
				_wrap.next( 'p.stock' ).show();
				_product.attr( 'data-id', t['variation_id'] );
				_product.attr( 'data-price', t['display_price'] );
			} else {
				_wrap.next( 'p.stock' ).hide();
			}

			// change availability text
			$( e['target'] ).closest( '.variations_form' ).find( 'p.stock' ).remove();
			if ( t['availability_html'] != '' ) {
				$( e['target'] ).closest( '.variations_form' ).append( t['availability_html'] );
			}
		}
		if ( t['variation_description'] != '' ) {
			_product.find( '.woosb-variation-description' ).html( t['variation_description'] ).show();
		} else {
			_product.find( '.woosb-variation-description' ).html( '' ).hide();
		}

		if ( woosb_vars.change_image == 'no' ) {
			// prevent changing the main image
			$( e['target'] ).closest( '.variations_form' ).trigger( 'reset_image' );
		}

		woosb_check_ready();
	} );

	$( document ).on( 'reset_data', function( e ) {
		var _product = $( e['target'] ).closest( '.woosb-product' );

		// reset thumb
		_product.find( '.woosb-thumb-new' ).hide();
		_product.find( '.woosb-thumb-ori' ).show();

		// reset price
		_product.find( '.woosb-price-new' ).hide();
		_product.find( '.woosb-price-ori' ).show();

		// reset stock
		$( e['target'] ).closest( '.variations_form' ).find( 'p.stock' ).remove();

		// reset desc
		_product.find( '.woosb-variation-description' ).html( '' ).hide();

		// reset id
		_product.attr( 'data-id', 0 );
		_product.attr( 'data-price', 0 );
		woosb_check_ready();
	} );

	_btn.on( 'click touch', function( e ) {
		if ( $( this ).hasClass( 'disabled' ) ) {
			if ( $( this ).hasClass( 'woosb-selection' ) ) {
				alert( woosb_vars.alert_selection );
			} else if ( $( this ).hasClass( 'woosb-empty' ) ) {
				alert( woosb_vars.alert_empty );
			} else if ( $( this ).hasClass( 'woosb-min' ) ) {
				alert( woosb_vars.alert_min.replace( '[min]', _wrap.attr( 'data-min' ) ) );
			} else if ( $( this ).hasClass( 'woosb-max' ) ) {
				alert( woosb_vars.alert_max.replace( '[max]', _wrap.attr( 'data-max' ) ) );
			}
			e.preventDefault();
		} else {
			jQuery.ajax( {
				url: woosb_vars.ajax_url,
				type: "POST",
				data: {
					action: 'woosb_custom_data',
					woosb_ids: _ids.val(),
					woosb_nonce: woosb_vars.woosb_nonce
				},
				async: false
			} );
		}
	} );

	_wrap.on( 'keyup change', '.woosb-qty input', function() {
		var qty = parseInt( $( this ).val() );
		var min_qty = parseInt( $( this ).attr( 'min' ) );
		var max_qty = parseInt( $( this ).attr( 'max' ) );
		if ( ! isNaN( min_qty ) && (
			qty < min_qty
		) ) {
			qty = min_qty;
		}
		if ( ! isNaN( max_qty ) && (
			qty > max_qty
		) ) {
			qty = max_qty;
		}
		$( this ).val( qty );
		$( this ).closest( '.woosb-product' ).attr( 'data-qty', qty );
		woosb_check_ready();
	} );

	function woosb_check_ready() {
		var total = 0;
		var is_selection = false;
		var is_empty = true;
		var is_min = false;
		var is_max = false;

		_wrap.find( '.woosb-product' ).each( function() {
			if ( (
				     $( this ).attr( 'data-qty' ) > 0
			     ) && (
				     $( this ).attr( 'data-id' ) == 0
			     ) ) {
				is_selection = true;
			}
			if ( $( this ).attr( 'data-qty' ) > 0 ) {
				is_empty = false;
				total += parseInt( $( this ).attr( 'data-qty' ) );
			}
		} );

		// check min
		if ( _wrap.attr( 'data-min' ) && (
			total < parseInt( _wrap.attr( 'data-min' ) )
		) ) {
			is_min = true;
		}

		// check max
		if ( _wrap.attr( 'data-max' ) && (
			total > parseInt( _wrap.attr( 'data-max' ) )
		) ) {
			is_max = true;
		}

		if ( is_selection || is_empty || is_min || is_max ) {
			_btn.addClass( 'disabled' );
			if ( is_selection ) {
				_btn.addClass( 'woosb-selection' );
			} else {
				_btn.removeClass( 'woosb-selection' );
			}
			if ( is_empty ) {
				_btn.addClass( 'woosb-empty' );
			} else {
				_btn.removeClass( 'woosb-empty' );
			}
			if ( is_min ) {
				_btn.addClass( 'woosb-min' );
			} else {
				_btn.removeClass( 'woosb-min' );
			}
			if ( is_max ) {
				_btn.addClass( 'woosb-max' );
			} else {
				_btn.removeClass( 'woosb-max' );
			}
		} else {
			_btn.removeClass( 'disabled woosb-selection woosb-empty woosb-min woosb-max' );
		}
		woosb_calc_price();
		woosb_save_ids();
	}

	function woosb_calc_price() {
		var total = 0;
		var total_html = '';
		_wrap.find( '.woosb-product' ).each( function() {
			if ( $( this ).attr( 'data-price' ) > 0 ) {
				total += $( this ).attr( 'data-price' ) * $( this ).attr( 'data-qty' );
			}
		} );
		if ( (
			     _wrap.attr( 'data-percent' ) > 0
		     ) && (
			     _wrap.attr( 'data-percent' ) < 100
		     ) ) {
			total = (
				        total * _wrap.attr( 'data-percent' )
			        ) / 100;
		} else if ( _wrap.attr( 'data-sale' ) > 0 ) {
			total = _wrap.attr( 'data-sale' );
		}
		var total_formated = woosb_format_money( total, woosb_vars.price_decimals, '', woosb_vars.price_thousand_separator, woosb_vars.price_decimal_separator );
		switch ( woosb_vars.price_format ) {
			case '%1$s%2$s':
				//left
				total_html += woosb_vars.currency_symbol + '' + total_formated;
				break;
			case '%1$s %2$s':
				//left with space
				total_html += woosb_vars.currency_symbol + ' ' + total_formated;
				break;
			case '%2$s%1$s':
				//right
				total_html += total_formated + '' + woosb_vars.currency_symbol;
				break;
			case '%2$s %1$s':
				//right with space
				total_html += total_formated + ' ' + woosb_vars.currency_symbol;
				break;
			default:
				//default
				total_html += woosb_vars.currency_symbol + '' + total_formated;
		}
		if ( (
			     parseFloat( _wrap.attr( 'data-percent' ) ) > 0
		     ) && (
			     parseFloat( _wrap.attr( 'data-percent' ) ) < 100
		     ) ) {
			var saved = woosb_round( parseFloat( 100 - _wrap.attr( 'data-percent' ) ) );
			total_html += ' (' + woosb_vars.price_saved + ' ' + saved + '%)';
		}
		_total.html( woosb_vars.bundle_price_text + ' ' + total_html ).slideDown();
		$( document ).trigger( 'woosb_calc_price', [total, total_formated, total_html] );
	}

	function woosb_save_ids() {
		var woosb_ids = Array();
		_wrap.find( '.woosb-product' ).each( function() {
			if ( (
				     $( this ).attr( 'data-id' ) > 0
			     ) && (
				     $( this ).attr( 'data-qty' ) > 0
			     ) ) {
				woosb_ids.push( $( this ).attr( 'data-id' ) + '/' + $( this ).attr( 'data-qty' ) );
			}
		} );
		_ids.val( woosb_ids.join( ',' ) );
	}

	function woosb_round( num ) {
		return + (
			Math.round( num + "e+2" ) + "e-2"
		);
	}

	function woosb_format_money( number, places, symbol, thousand, decimal ) {
		number = number || 0;
		places = ! isNaN( places = Math.abs( places ) ) ? places : 2;
		symbol = symbol !== undefined ? symbol : "$";
		thousand = thousand || ",";
		decimal = decimal || ".";
		var negative = number < 0 ? "-" : "",
			i = parseInt( number = Math.abs( + number || 0 ).toFixed( places ), 10 ) + "",
			j = 0;
		if ( i.length > 3 ) {
			j = i.length % 3;
		}
		return symbol + negative + (
			j ? i.substr( 0, j ) + thousand : ""
		) + i.substr( j ).replace( /(\d{3})(?=\d)/g, "$1" + thousand ) + (
			       places ? decimal + Math.abs( number - i ).toFixed( places ).slice( 2 ) : ""
		       );
	}
} );