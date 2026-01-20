
/**
 * Construct a WC Panel Tabs instance.
 * 
 * @constructor
 * 
 * @param {HTMLElement} container The element containing the ".panel-wrap" tabs and content.
 */
function WPPF_WcTabNavigator( container ) {

	if ( ! container ) {
		return;
	}

	this.panel = container.querySelector( '.panel-wrap' );
	this.init();
}

/**
 * Add listeners to all tabs and show any active tabs.
 */
WPPF_WcTabNavigator.prototype.init = function() {
	const tabs = this.panel.querySelectorAll( 'ul.wc-tabs > li > a' );
	const that = this;

	tabs.forEach( tab => {
		tab.addEventListener( 'click', that.handleTabClick.bind( that ) )

		if ( tab.parentElement.classList.contains( 'active' ) ) {
			const tabId = tab.parentElement.getAttribute( 'data-tab-id' );
			that.setTabActive( tabId );
			that.showContent( tabId );
		}
	} );
};

/**
 * The event handler for tab click events.
 * 
 * @param {Event} event The event of the click, used for the target.
 */
WPPF_WcTabNavigator.prototype.handleTabClick = function( event ) {
	const tabId = event.target.parentElement.getAttribute( 'data-tab-id' );
	this.setTabActive( tabId );
	this.showContent( tabId );
};

/**
 * Set all tabs to inactive except a specified tag.
 * 
 * @param {string} tabId The 'data-tab-id' attribute.
 */
WPPF_WcTabNavigator.prototype.setTabActive = function( tabId ) {
	const tabs = this.panel.querySelectorAll( 'ul.wc-tabs > li' );

	tabs.forEach( tab => {

		if ( tabId == tab.getAttribute( 'data-tab-id' ) ) {
			tab.classList.add( 'active' );
		} else {
			tab.classList.remove( 'active' );
		}

	} );
};

/**
 * Show a specified tab's content.
 * 
 * @param {string} tabId The 'data-tab-id' attribute of the associated tab.
 */
WPPF_WcTabNavigator.prototype.showContent = function( tabId ) {
	const children = this.panel.children;

	for ( let i = 1; i < children.length; i++ ) {

		if ( children[ i ].classList.contains( tabId ) ) {
			children[ i ].style.display = 'block';
		} else {
			children[ i ].style.display = 'none';
		}

	}
};
