
/**
 * Create a WP Admin Notice. Credit to https://isabelcastillo.com/wp-admin-notice-javascript for original idea.
 * 
 * @constructor
 * 
 * @param {string} message The message to display in the Admin Notice.
 * @param {string} type The type of Admin Notice to display.
 */
function WPPF_WpAdminNotice( message, type = 'info' ) {
	this.maybeCreateNoticeContainer();
	this.message = message;
	this.noticeType = this.validateNoticeType( type );
	this.addMessage();
}

/**
 * Create the notice container in the correct area if it does not already exist.
 */
WPPF_WpAdminNotice.prototype.maybeCreateNoticeContainer = function() {
	const containerId = 'wppf-js-admin-notices';
	let container = document.getElementById( containerId );

	if ( ! container ) {
		const wpHeaderEnd = document.querySelector( 'hr.wp-header-end' );

		container = document.createElement( 'div' );
		container.id = containerId;

		wpHeaderEnd.parentElement.insertBefore( container, wpHeaderEnd.nextSibling );
		this.noticeContainer = container;
	} else {
		this.noticeContainer = container;
	}

};

/**
 * Check whether a passed type is a valid notice type. Return the passed type if it is, return 'info' if it is not.
 * 
 * @param {string} type The type to be checked for validity.
 * 
 * @return {string} The passed type, or a valid default type.
 */
WPPF_WpAdminNotice.prototype.validateNoticeType = function( type = 'info' ) {
	types = [ 'info', 'warning', 'error', 'success' ];

	if ( types.includes( type ) ) {
		return type;
	} else {
		return 'info';
	}

};

/**
 * Append the notice to the notice container.
 */
WPPF_WpAdminNotice.prototype.addMessage = function() {
	this.notice = this.createMessageContainer();
	this.noticeContainer.appendChild( this.notice );
};

/**
 * Create and return the message container.
 * 
 * @return {HTMLDivElement} The message container.
 */
WPPF_WpAdminNotice.prototype.createMessageContainer = function() {
	const container = document.createElement( 'div' );
	const message = document.createElement( 'p' );
	const dismiss = document.createElement( 'button' );
	const dismissScreenReader = document.createElement( 'span' );

	message.innerText = this.message;

	const noticeType = 'notice-' + this.noticeType;
	container.classList.add( 'notice', noticeType, 'is-dismissible' );

	dismiss.type = 'button';
	dismiss.className = 'notice-dismiss';
	dismiss.addEventListener( 'click', this.removeNotice.bind( this ) );

	dismissScreenReader.className = 'screen-reader-text';
	dismissScreenReader.innerText = 'Dismiss this notice';

	dismiss.appendChild( dismissScreenReader );
	container.appendChild( message );
	container.appendChild( dismiss );

	return container;
};

/**
 * The action handler for removing the notice.
 */
WPPF_WpAdminNotice.prototype.removeNotice = function() {
	const that = this;

	jQuery( this.notice ).animate( {
			height: 0,
			opacity: 0,
		}, 250, function() {
			that.noticeContainer.removeChild( that.notice );
	} );
};
