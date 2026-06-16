/**
 * Minimum admin settings: dynamic rules builder.
 *
 * Vanilla JS, no dependencies. Adds/removes rule rows and disables the target
 * ID field when a rule's scope is "global". Tooltips use the native Popover API
 * with a graceful title fallback.
 */
( function () {
	'use strict';

	var cfg = window.minimumAdmin || {};
	var option = cfg.optionName || 'minimum_settings';
	var scopes = cfg.scopes || { global: 'All products (global)' };
	var i18n = cfg.i18n || {};

	var rules = document.getElementById( 'minimum-rules' );
	if ( ! rules ) {
		return;
	}

	var tbody = document.getElementById( 'minimum-rules-rows' );
	var table = rules.querySelector( '.minimum-rules-table' );
	var empty = document.getElementById( 'minimum-rules-empty' );
	var addBtn = document.getElementById( 'minimum-add-rule' );

	/** Next row index — start past any server-rendered rows. */
	var nextIndex = tbody ? tbody.querySelectorAll( 'tr' ).length : 0;

	function esc( value ) {
		var span = document.createElement( 'span' );
		span.textContent = String( value == null ? '' : value );
		return span.innerHTML;
	}

	function scopeOptions() {
		return Object.keys( scopes )
			.map( function ( value ) {
				return '<option value="' + esc( value ) + '">' + esc( scopes[ value ] ) + '</option>';
			} )
			.join( '' );
	}

	function numberCell( field, label ) {
		return (
			'<td><input type="number" min="0" step="1" class="small-text" name="' +
			esc( option ) +
			'[rules][' +
			nextIndex +
			'][' +
			field +
			']" value="0" aria-label="' +
			esc( label ) +
			'" /></td>'
		);
	}

	function buildRow() {
		var tr = document.createElement( 'tr' );
		tr.className = 'minimum-rule-row';
		tr.innerHTML =
			'<td><select class="minimum-rule-scope" name="' +
			esc( option ) +
			'[rules][' +
			nextIndex +
			'][scope]" aria-label="' +
			esc( i18n.scopeLabel || 'Scope' ) +
			'">' +
			scopeOptions() +
			'</select></td>' +
			'<td><input type="number" min="0" step="1" class="small-text minimum-rule-target" name="' +
			esc( option ) +
			'[rules][' +
			nextIndex +
			'][target]" value="0" aria-label="' +
			esc( i18n.targetLabel || 'Target ID' ) +
			'" /></td>' +
			numberCell( 'min', i18n.minLabel || 'Min' ) +
			numberCell( 'max', i18n.maxLabel || 'Max' ) +
			numberCell( 'step', i18n.stepLabel || 'Step' ) +
			'<td><button type="button" class="button minimum-remove-rule">' +
			esc( i18n.remove || 'Remove' ) +
			'</button></td>';
		nextIndex++;
		return tr;
	}

	function reflectScope( row ) {
		var scope = row.querySelector( '.minimum-rule-scope' );
		var target = row.querySelector( '.minimum-rule-target' );
		if ( ! scope || ! target ) {
			return;
		}
		var isGlobal = 'global' === scope.value;
		target.disabled = isGlobal;
		if ( isGlobal ) {
			target.value = '0';
		}
	}

	function refreshEmptyState() {
		var hasRows = tbody && tbody.querySelectorAll( 'tr' ).length > 0;
		if ( table ) {
			table.hidden = ! hasRows;
		}
		if ( empty ) {
			empty.hidden = !! hasRows;
		}
	}

	if ( addBtn && tbody ) {
		addBtn.addEventListener( 'click', function () {
			var row = buildRow();
			tbody.appendChild( row );
			reflectScope( row );
			refreshEmptyState();
			// Presentation-only: lay the gauge spine in, then drop the hook so
			// a later re-add re-triggers it. Harmless if motion is reduced.
			row.classList.add( 'is-new' );
			row.addEventListener(
				'animationend',
				function () {
					row.classList.remove( 'is-new' );
				},
				{ once: true }
			);
			var firstField = row.querySelector( 'select, input' );
			if ( firstField ) {
				firstField.focus();
			}
		} );
	}

	rules.addEventListener( 'click', function ( event ) {
		var btn = event.target.closest ? event.target.closest( '.minimum-remove-rule' ) : null;
		if ( ! btn ) {
			return;
		}
		var row = btn.closest( '.minimum-rule-row' );
		if ( row ) {
			row.parentNode.removeChild( row );
			refreshEmptyState();
		}
	} );

	rules.addEventListener( 'change', function ( event ) {
		if ( event.target && event.target.classList.contains( 'minimum-rule-scope' ) ) {
			var row = event.target.closest( '.minimum-rule-row' );
			if ( row ) {
				reflectScope( row );
			}
		}
	} );

	// Initialise existing rows.
	Array.prototype.forEach.call(
		rules.querySelectorAll( '.minimum-rule-row' ),
		function ( row ) {
			reflectScope( row );
		}
	);
} )();
