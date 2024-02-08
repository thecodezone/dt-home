import {css, html, LitElement} from 'lit';
import "./app-icon.js";

class HiddenAppGrid extends LitElement {
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
    //debugger
    this.appData = [];
    this.selectedIndex = -1; // -1 indicates no selection
    this.pressTimer = 100;
  }


  connectedCallback() {
    super.connectedCallback();
    this.loadAppData();

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
    const selectedApp = this.appData[index];
    if (selectedApp && selectedApp.url) {
      window.location.href = selectedApp.url; // Navigate in the same tab
    }
    this.showRemoveIconIndex = null; // Reset the index on single click
    this.requestUpdate(); // Request an update to re-render the component
  }


  handleLongPress(index) {
    this.showRemoveIconIndex = index;
    this.requestUpdate();
  }

  startPressTimer(e, index) {
    e.preventDefault();
    this.pressTimer = setTimeout(() => {
      this.handleLongPress(index);
    }, 900); // 900 milliseconds for long press
  }

  clearPressTimer() {
    clearTimeout(this.pressTimer);
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
    const url = this.appUrl + "/update-unhide-apps";
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
      <div id="hiddenAppGrid" class="app-grid">
        ${this.appData.map((app, index) => app.is_hidden == 1 ? html`
          <div class="app-container"
               @click="${() => this.handleSingleClick(index)}"
               @mousedown="${(e) => this.startPressTimer(e, index)}"
               @mouseup="${() => this.clearPressTimer()}"
               @touchstart="${(e) => this.startPressTimer(e, index)}"
               @touchend="${() => this.clearPressTimer()}">
            ${this.showRemoveIconIndex === index
              ? html`<span id="remove-icon-${app.id}" class="remove-icon"
                           @click="${(e) => this.handleRemove(e, index)}">UNHIDE APP</span>`
              : ''}
            <dt-launcher-app-icon name="${app.name}" icon="${app.icon}"></dt-launcher-app-icon>
          </div>` : '')}
      </div>
    `;
  }

}

customElements.define('dt-launcher-hidden-app-grid', HiddenAppGrid);
