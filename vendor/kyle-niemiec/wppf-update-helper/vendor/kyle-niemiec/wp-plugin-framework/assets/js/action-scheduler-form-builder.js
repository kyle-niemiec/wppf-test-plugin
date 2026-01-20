document.addEventListener( 'DOMContentLoaded', function() {

	/**
	 * This controller is for the Action Scheduler Form Builder, to control to active tabs on each form.
	 * 
	 * @constructor
	 * 
	 * @param HTMLDivElement The form container element.
	 */
	function ActionSchedulerFormController( form ) {
		this.form = form;
		this.init();
	}

	/**
	 * Initialize a form by selecting a tab.
	 */
	ActionSchedulerFormController.prototype.init = function() {
		const tabs = this.getFormTabs();
		this.selectTab( { target: tabs[0] } );
		this.addTabListeners();
	};

	/**
	 * Add the click event listeners to the tab elements.
	 */
	ActionSchedulerFormController.prototype.addTabListeners = function() {
		const tabs = this.getFormTabs();

		tabs.forEach( tab => {
			tab.addEventListener( 'click', this.selectTab.bind( this ) );
		} );
	};

	/**
	 * Get the tab elements for a Action Scheduler form.
	 * 
	 * @return NodeListOf<Element> The tabs.
	 */
	ActionSchedulerFormController.prototype.getFormTabs = function() {
		return this.form.querySelectorAll( '.nav-tab' );
	};

	/**
	 * Get the timer forms for a Action Scheduler form.
	 * 
	 * @return NodeListOf<Element> The tabs.
	 */
	ActionSchedulerFormController.prototype.getForms = function() {
		return this.form.querySelectorAll( '.timer-form' );
	};

	/**
	 * Select a tab in a form set and show its form.
	 * 
	 * @event Event The click event associated with a tab.
	 */
	ActionSchedulerFormController.prototype.selectTab = function( event ) {
		const tabs = this.getFormTabs();

		tabs.forEach( tab => {
			tab.classList.remove( 'nav-tab-active' );
		} );

		event.target.classList.add( 'nav-tab-active' );
		this.showForm( event.target.getAttribute( 'data-timer-type' ) );
	};

	/**
	 * Display a form with the given slug.
	 */
	ActionSchedulerFormController.prototype.showForm = function( slug ) {
		const forms = this.getForms();

		forms.forEach( form => {

			if ( form.getAttribute( 'data-timer-type' ) === slug ) {
				form.style.display = 'block';
			} else {
				form.style.display = 'none';
			}

		} );

		this.form.querySelector( 'input.timer-type' ).value = slug;
	}

	// Start it up
	const forms = document.querySelectorAll( '.action-scheduler-timer-builder' );

	forms.forEach( form => {
		new ActionSchedulerFormController( form );
	} );

} );
