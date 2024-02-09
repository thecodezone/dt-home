import {css, html, LitElement} from 'lit';
import Sortable from 'sortablejs';
import "./app-icon.js";

class AppGrid extends LitElement {
  static properties = {
    appData: {type: Array},
    selectedIndex: {type: Number},
    appUrl: {type: String}

  };
  static styles = css`
    .app-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      padding: 16px;
      justify-items: center;
    }

    .app-container {
      position: relative;
      display: inline-block;
    }

    .remove-icon {
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

    .remove-icon::before {
      content: '✖';
      margin-right: 4px;
    }

    @media (min-width: 230px) and (max-width: 950px) {
      .app-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding: 16px;
        justify-items: center;
      }

      .app-container {
        position: relative;
        display: inline-block;
      }

      .remove-icon {
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
      .app-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* Three icons per row */
        gap: 10px;
        padding: 16px;
        justify-items: center;
      }

      .app-container {
        position: relative;
        display: inline-block;
      }

      .remove-icon {
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


  constructor() {
    super();
    this.appData = [];
    this.selectedIndex = -1; // -1 indicates no selection
    this.sortableInstances = [];
    this.clickTimer = null;
    this.clickDelay = 300;
  }


  connectedCallback() {
    super.connectedCallback();
    this.loadAppData();
    this.initSortable();

  }

  updated(changedProperties) {
    super.updated(changedProperties);
    if (changedProperties.has('appData')) {
      this.initSortable(); // Initialize sortable when appData changes
    }
  }


  initSortable() {
    // Select the container for the grid items
    const appGrids = this.shadowRoot.querySelectorAll('.app-grid');
    appGrids.forEach((appGrid) => {
      const sortableInstance = new Sortable(appGrid, {
        group: 'shared',
        animation: 150,
        draggable: '.app-container', // Specify draggable items
        onEnd: (evt) => this.updateOrder(evt)

      });
      this.sortableInstances.push(sortableInstance);

    });
  }

  updateOrder(evt) {
    // evt.newIndex and evt.oldIndex can be used to update the order
    const itemMoved = this.appData.splice(evt.oldIndex, 1)[0];
    this.appData.splice(evt.newIndex, 0, itemMoved);

    // Update the database with the new order
    this.postNewOrderToServer();
  }

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


  loadAppData() {
    // Fetch your data from an external source or set it from an attribute
    // For this example, let's assume it's set from a JSON attribute
    const jsonData = this.getAttribute('app-data');
    this.appUrl = this.getAttribute('app-url');

    if (jsonData) {
      this.appData = JSON.parse(jsonData);
    }
  }

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

  handleDoubleClick(index) {
    clearTimeout(this.clickTimer);
    this.clickTimer = null;
    // Your double click logic here
    this.showRemoveIconIndex = index;
    this.requestUpdate();
  }

  handleRemove(e, index) {
    e.stopPropagation();
    const appId = this.appData[index].id;

    this.postAppDataToServer(appId);

    this.appData.splice(index, 1);
    this.selectedIndex = -1;
    this.showRemoveIconIndex = null; // Reset the index after removal
    this.requestUpdate(); // Request an update to re-render the component
  }


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

  render() {
    return html`
      <div id="appGrid" class="app-grid">
        ${this.appData.map((app, index) => app.is_hidden !== 1 ? html`
          <div class="app-container" data-id="${app.id}"
               @click="${() => this.handleSingleClick(index)}"
               @dblclick="${() => this.handleDoubleClick(index)}"
          >
            ${this.showRemoveIconIndex === index
              ? html`<span id="remove-icon-${app.id}" class="remove-icon"
                           @click="${(e) => this.handleRemove(e, index)}">HIDE APP</span>`
              : ''}
            <dt-launcher-app-icon class=" app-icon" name="${app.name}" icon="${app.icon}"></dt-launcher-app-icon>
          </div>` : '')}
      </div>
    `;
  }

}

customElements.define('dt-launcher-app-grid', AppGrid);
