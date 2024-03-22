import {css, html, LitElement} from 'lit';

/**
 * Custom element representing an application grid.
 */
class AppGrid extends LitElement {
  /**
   * Properties of the AppGrid element.
   * @static
   * @type {Object}
   */
  static properties = {
    appData: {type: Array},
    selectedIndex: {type: Number},
    appUrl: {type: String}
  };

  /**
   * Styles for the AppGrid element.
   * @static
   * @type {CSSResult}
   */
  static styles = css`
    /* CSS styles here */

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
      top: -7px;
      right: -10px;
      background-color: rgb(255, 255, 255);
      color: #fcfbfb;
      padding: 5px 5px 0px 5px;
      cursor: pointer;
      border-radius: 53%;
      font-size: 14px;
      z-index: 1;
      background-color: #f16d71;
      border: 1px solid #7e1919;
    }

    .app-grid__icon {
      width: 100%;
      pointer-events: none;
    }
  `;

  /**
   * Constructor for the AppGrid element.
   */
  constructor() {
    super();
    this.appData = [];
    this.selectedIndex = -1; // -1 indicates no selection
    this.showRemoveIconIndex = null; // To show remove icon for a specific item
    this.clickTimer = null;
    this.clickDelay = 300;
    this.longPressTimer = 200;
    this.longPressDuration = 500;
  }

  /**
   * Lifecycle callback when the element is connected to the DOM.
   */
  connectedCallback() {
    super.connectedCallback();
    this.loadAppData();
    document.addEventListener('click', this.handleDocumentClick);
    document.addEventListener('mousedown', this.handleMouseDown);
    document.addEventListener('mouseup', this.handleMouseUp);
    document.addEventListener('mouseleave', this.handleMouseLeave);
  }

  /**
   * Prevents default behavior for drag events.
   * @param {DragEvent} event - The drag event.
   */
  allowDrop(event) {
    event.preventDefault();
  }

  /**
   * Handles the drag event by setting the data being dragged.
   * @param {DragEvent} event - The drag event.
   * @param {number} index - The index of the dragged item.
   */
  drag(event, index) {
    event.dataTransfer.setData("text/plain", index);
  }

  /**
   * Handles the drop event by reordering the apps.
   * @param {DragEvent} event - The drop event.
   */
  drop(event) {
    event.preventDefault();
    const fromIndex = event.dataTransfer.getData("text/plain");
    const toIndex = event.target.dataset.index;
    this.reorderApps(fromIndex, toIndex);
  }

  /**
   * Reorders apps based on drag and drop.
   * @param {number} fromIndex - The index from which the app is dragged.
   * @param {number} toIndex - The index to which the app is dropped.
   */
  reorderApps(fromIndex, toIndex) {
    const appsCopy = [...this.appData];
    const [removedApp] = appsCopy.splice(fromIndex, 1);
    appsCopy.splice(toIndex, 0, removedApp);
    this.appData = appsCopy;
    this.postNewOrderToServer();
  }

  /**
   * Posts the new order of apps to the server.
   */
  postNewOrderToServer() {
    const url = this.appUrl + "/update-app-order"; // Your API endpoint
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(this.appData),
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
   * Loads application data from an external source.
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
   * Handles a single click event on an app.
   * @param {number} index - The index of the clicked app.
   */
  handleSingleClick(index) {
    if (this.showRemoveIconIndex === null && this.clickTimer === null && this.longPressTimer === null) {
      this.clickTimer = setTimeout(() => {
        // Your single click logic here
        const selectedApp = this.appData[index];
        if (selectedApp && selectedApp.slug) {
          window.location.href = `/home/app/${selectedApp.slug}`;
        }
        this.showRemoveIconIndex = null;
        this.requestUpdate();
        this.clickTimer = null;
      }, this.clickDelay);
    }
  }

  /**
   * Handles a double click event on an app.
   * @param {number} index - The index of the double-clicked app.
   */
  handleDoubleClick(index) {
    clearTimeout(this.clickTimer);
    this.clickTimer = null;
    // Your double click logic here
    this.showRemoveIconIndex = index;
    this.requestUpdate();
  }

  /**
   * Handles removal of an app.
   * @param {number} index - The index of the app to be removed.
   */
  handleRemove(index) {
    const appId = this.appData[index].id;
    this.postAppDataToServer(appId);
    this.appData.splice(index, 1);
    this.selectedIndex = -1;
    this.showRemoveIconIndex = null;
  }

  /**
   * Handles a click outside the context menu.
   * @param {MouseEvent} event - The click event.
   */
  handleDocumentClick = (event) => {
    // Check if a long press has occurred
    if (this.longPressOccured) {
      // Reset the flag
      this.longPressOccured = false;
      return;
    }
    // Check if the click is outside the context menu
    const removeIcon = this.shadowRoot.querySelector('.app-grid__remove-icon');
    if (removeIcon && !removeIcon.contains(event.target)) {
      this.showRemoveIconIndex = null;
      this.isDragging = false;
      this.requestUpdate();
    }
  }

  /**
   * Posts data of the app to be hidden to the server.
   * @param {string} appId - The ID of the app to be hidden.
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
      .then(response => {
        if (response.ok) {
          window.location.reload();
        } else {
          // Handle error
        }
      })
      .catch((error) => {
        console.error('Error:', error);
      });
  }

  /**
   * Handles mouse down event on an app.
   * @param {MouseEvent} event - The mouse down event.
   * @param {number} index - The index of the app.
   */
  handleMouseDown = (event, index) => {
    let appGridItem = event.target.closest('.app-grid__item');
    if (!appGridItem) {
      // If the target itself isn't an app grid item, check its parents
      appGridItem = event.target.parentElement.closest('.app-grid__item');
    }
    if (appGridItem) {
      this.longPressTimer = setTimeout(() => {
        this.showContextMenu(index);
        // Set a flag to indicate that a long press has occurred
        this.longPressOccured = true;
      }, this.longPressDuration);
    }
  }

  /**
   * Handles mouse up event.
   */
  handleMouseUp = () => {
    clearTimeout(this.longPressTimer);
    this.longPressTimer = null;
  }

  /**
   * Handles mouse leave event.
   * @param {number} index - The index of the app.
   */
  handleMouseLeave = (index) => {
    clearTimeout(this.longPressTimer);
    // Do something with the index if needed
  }

  /**
   * Shows context menu for the app.
   * @param {number} index - The index of the app.
   */
  showContextMenu(index) {
    // Your logic to show the context menu
    // For example, you can set a property to indicate which index's context menu to show
    this.showRemoveIconIndex = index;
    this.requestUpdate();
  }

  /**
   * Renders the AppGrid element.
   * @returns {TemplateResult} HTML template result.
   */
  render() {
    return html`
      <div class="app-grid">
        ${this.appData.map((app, index) => app.is_hidden !== 1 ? html`
          <div class="app-grid__item"
               data-index="${index}"
               @click="${() => this.handleSingleClick(index)}"
               @dblclick="${() => this.handleDoubleClick(index)}"
               @mousedown="${(event) => this.handleMouseDown(event, index)}"
               @dragstart="${(event) => this.drag(event, index)}"
               @dragover="${this.allowDrop}"
               @drop="${this.drop}"
               draggable="true">
            ${this.showRemoveIconIndex === index
              ? html`<span class="app-grid__remove-icon"
                           @click="${() => this.handleRemove(index)}"><sp-icon-close></sp-icon-close></span>`
              : ''}
            <dt-home-app-icon class="app-grid__icon"
                              name="${app.name}"
                              icon="${app.icon}"></dt-home-app-icon>
          </div>` : '')}
      </div>
    `;
  }
}

customElements.define('dt-home-app-grid', AppGrid);
