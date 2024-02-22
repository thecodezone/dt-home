import {css, html, LitElement} from 'lit';
import Sortable from 'sortablejs';
import "./app-icon.js";

/**
 * Represents a grid of application icons.
 *
 * @extends LitElement
 */
class AppGrid extends LitElement {
  static properties = {
    appData: {type: Array},
    selectedIndex: {type: Number},
    appUrl: {type: String}

  };

  /**
   * CSS styles for an app grid.
   *
   * @type {string}
   */
  static styles = css`
    .app-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      padding: 16px;
      justify-items: center;
    }

    .app-grid__item {
      position: relative;
      width: 100%;
    }

    .app-grid__remove-icon {
      position: absolute;
      top: -20px;
      right: 17px;
      background-color: rgb(207 207 215);
      color: #050202;
      padding: 2px 5px;
      cursor: pointer;
      border-radius: 5%;
      font-size: 14;
      z-index: 1;
      font-weight: 100;

    }

    .app-grid__remove-icon::before {
      content: '✖';
      margin-right: 4px;
    }

    .app-grid__icon {
      width: 100%;
      pointer-events: none;
    }

    .no-select {
      -webkit-touch-callout: none; /* iOS Safari */
      -webkit-user-select: none; /* Safari */
      -khtml-user-select: none; /* Konqueror HTML */
      -moz-user-select: none; /* Old versions of Firefox */
      -ms-user-select: none; /* Internet Explorer/Edge */
      user-select: none; /* Non-prefixed version, currently supported by Chrome, Opera and Firefox */
    }

    @media (min-width: 230px) and (max-width: 950px) {
      .app-grid__remove-icon {
        position: absolute;
        top: -20px;
        right: 0;
        background-color: rgb(207 207 215);
        color: #050202;
        padding: 2px 5px;
        cursor: pointer;
        border-radius: 5%;
        font-size: smaller;
        z-index: 1; // ensures it's above the app icon
      }
    }

    @media (min-width: 750px) and (max-width: 950px) {
      .app-grid__remove-icon {
        position: absolute;
        top: -20px;
        right: 17px;
        background-color: rgb(207 207 215);
        color: #050202;
        padding: 2px 5px;
        cursor: pointer;
        border-radius: 5%;
        font-size: smaller;
      }
    }
  `;


  /**
   * Constructor for the class.
   *
   * Initializes instance variables.
   *
   * @constructor
   */
  constructor() {
    super();
    this.appData = [];
    this.selectedIndex = -1; // -1 indicates no selection
    this.sortableInstances = [];
    this.clickTimer = null;
    this.clickDelay = 300;
    this.longPressTimer = null;
    this.longPressDelay = 500;
    this.isDragging = false;
  }


  /**
   * Executes when the element is added to the document's DOM.
   * @memberof YourElement
   * @function connectedCallback
   * @returns {void}
   */

  connectedCallback() {
    super.connectedCallback();
    this.loadAppData();
    this.initSortable();
    this.boundHandleDocumentClick = this.handleDocumentClick.bind(this);
    document.addEventListener('click', this.boundHandleDocumentClick);
  }

  disconnectedCallback() {
    super.disconnectedCallback();
    document.removeEventListener('click', this.boundHandleDocumentClick);
  }

  /**
   * Updates the component state and performs additional actions based on the changed properties.
   *
   * @param {Map<string, any>} changedProperties - The map of changed properties.
   *
   * @return {void}
   */
  updated(changedProperties) {
    super.updated(changedProperties);
    if (changedProperties.has('appData')) {
      // Ensure to call initSortable only when necessary
      this.initSortable();
    }
  }


  /**
   * Initializes the sortable functionality for grid items within the container.
   *
   * @method initSortable
   * @memberOf [Component]
   *
   * @return {void}
   */
  initSortable() {

    this.sortableInstances.forEach(instance => instance.destroy());
    this.sortableInstances = [];
    // Select the container for the grid items
    const appGrids = this.shadowRoot.querySelectorAll('.app-grid');

    appGrids.forEach((appGrid) => {
      const sortableInstance = new Sortable(appGrid, {
        group: 'shared',
        animation: 500, // Set animation duration to 500
        draggable: '.app-grid__item', // Specify draggable items
        delay: 100, // Adjust delay as needed
        fallbackOnBody: true,
        onChoose: (evt) => {
          if (this.longPressActive) {
            evt.preventDefault(); // Prevent drag if long press is active
          }
        },
        onStart: () => {
          if (this.isSortableDisabled) {
            this.sortableInstances.forEach(instance => instance.option('disabled', true));
          }
          this.isDragging = true;
        },
        onEnd: (evt) => {
          this.isDragging = false;
          this.longPressActive = false;
          this.isSortableDisabled = false;
          this.updateOrder(evt);

        }
      });

      this.sortableInstances.push(sortableInstance);
    });
  }


  /**
   * Updates the order of items in the appData array based on the given event object.
   * The event object should contain the properties 'oldIndex' and 'newIndex' which indicate the positions of the item in the array before and after moving.
   * The method removes the item from the old position and inserts it at the new position to update the order.
   * After updating the order, it posts the new order data to the server.
   *
   * @param {object} evt - The event object containing the properties 'oldIndex' and 'newIndex'.
   * @return {void}
   */
  updateOrder(evt) {
    // evt.newIndex and evt.oldIndex can be used to update the order
    const itemMoved = this.appData.splice(evt.oldIndex, 1)[0];
    this.appData.splice(evt.newIndex, 0, itemMoved);

    // Update the database with the new order
    this.postNewOrderToServer();
  }

  /**
   * Posts a new order to the server.
   *
   * @returns {void}
   */
  postNewOrderToServer() {
    const url = this.appUrl + "/update-app-order"; // Your API endpoint
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(this.appData), // Send the updated appData array
    })
      .then(response => response.json())
      .then(data => {
        console.log('Order update successful:', data);
      })
      .catch((error) => {
        console.error('Error updating order:', error);
      });
  }


  /**
   * Loads the application data from an external source or a JSON attribute.
   *
   * @returns {void}
   */
  loadAppData() {
    // Fetch your data from an external source or set it from an attribute
    // For this example, let's assume it's set from a JSON attribute
    const jsonData = this.getAttribute('app-data');
    this.appUrl = this.getAttribute('app-url');

    if (jsonData) {
      this.appData = JSON.parse(jsonData);
    }
  }

  /**
   * Handles a single click event.
   *
   * @param {number} index - The index of the clicked element.
   *
   * @return {undefined}
   */
  handleSingleClick(index) {
    if (this.clickTimer === null) {
      this.clickTimer = setTimeout(() => {
        // Your single click logic here
        const selectedApp = this.appData[index];
        if (selectedApp && selectedApp.url) {
          window.location.href = selectedApp.url;
        }
        this.showRemoveIconIndex = null;
        this.requestUpdate();
        this.clickTimer = null;
      }, this.clickDelay);
    }
  }

  /**
   * Handles double click event for a given index.
   *
   * @param {number} index - The index of the item being double clicked.
   * @return {void}
   */
  handleDoubleClick(index) {
    clearTimeout(this.clickTimer);
    this.clickTimer = null;
    // Your double click logic here
    this.showRemoveIconIndex = index;
    this.requestUpdate();
  }

  /**
   * Handles the remove action for an item at the specified index.
   * @param {Event} e - The event object.
   * @param {number} index - The index of the item to be removed.
   * @return {void}
   */
  handleRemove(e, index) {
    e.stopPropagation();
    const appId = this.appData[index].id;

    this.postAppDataToServer(appId);

    this.appData.splice(index, 1);
    this.selectedIndex = -1;
    this.showRemoveIconIndex = null; // Reset the index after removal
    this.requestUpdate(); // Request an update to re-render the component
  }


  /**
   * Sends the app data to the server to update the hidden apps list.
   *
   * @param {number} appId - The ID of the app to be hidden.
   * @return {void}
   */
  postAppDataToServer(appId) {

    const url = this.appUrl + "/update-hide-apps";
    const appToHide = this.appData.find(app => app.id === appId);

    if (!appToHide) {
      console.error('App not found');
      return;
    }
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(appToHide),
    })
      .then(response => response.json())
      .then(data => {
        console.log('Success:', data);
      })
      .catch((error) => {
        console.error('Error:', error);
      });
  }

  handleMouseDown(index, event) {
    if (!this.isDragging && !this.isSortableDisabled) {
      this.longPressTimer = setTimeout(() => {
        this.longPressActive = true;
        this.handleLongPress(index);
        this.isSortableDisabled = false;
      }, this.longPressDelay);

    }
  }

  handleMouseUp() {
    clearTimeout(this.longPressTimer);
    if (this.longPressActive && !this.isDragging) {
      this.longPressActive = false;
      // Consider the context when you need to enable/disable sorting
      this.isSortableDisabled = false; // Reset this flag as appropriate
    }
  }


  handleLongPress(index) {
    this.showRemoveIconIndex = index;
    this.requestUpdate();
  }

  handleDocumentClick(event) {
    // Check if the click is outside the context menu
    const removeIcon = this.shadowRoot.querySelector('.app-grid__remove-icon');
    if (removeIcon && !removeIcon.contains(event.target)) {
      this.showRemoveIconIndex = null;
      this.isDragging = false;
      this.requestUpdate();
    }
  }

  /**
   * Renders the HTML for the application grid.
   *
   * @return {string} The rendered HTML string.
   */
  render() {
    return html`
      <div id="appGrid" class="app-grid no-select">
        ${this.appData.map((app, index) => app.is_hidden !== 1 ? html`
          <div class="app-grid__item no-select"
               data-id="${app.id}"
               @click="${() => this.handleSingleClick(index)}"
               @dblclick="${() => this.handleDoubleClick(index)}"
               @mousedown="${(e) => this.handleMouseDown(index, e)}"
               @mouseup="${this.handleMouseUp}"
               @mouseleave="${this.handleMouseUp}">
            ${this.showRemoveIconIndex === index
              ? html`<span id="app-grid__remove-icon-${app.id}" class="app-grid__remove-icon"
                           @click="${(e) => this.handleRemove(e, index)}">HIDE APP</span>`
              : ''}
            <dt-home-app-icon class="app-grid__icon no-select"
                              name="${app.name}"
                              icon="${app.icon}"
            />
          </div>` : '')}
      </div>
    `;
  }

}

customElements.define('dt-home-app-grid', AppGrid);
