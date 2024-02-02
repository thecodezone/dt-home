import {css, html, LitElement} from 'lit';
import "./dt-launcher-app-icon.js";

class DtLauncherAppGrid extends LitElement {
  static properties = {
    appData: {type: Array},
    selectedIndex: {type: Number},
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
  }


  connectedCallback() {
    super.connectedCallback();
    this.loadAppData();

  }

  loadAppData() {
    // Fetch your data from an external source or set it from an attribute
    // For this example, let's assume it's set from a JSON attribute
    const jsonData = this.getAttribute('app-data');
    if (jsonData) {
      this.appData = JSON.parse(jsonData);
    }
  }

  handleClick(index) {
    this.selectedIndex = index;
  }

  handleRemove(e, index) {
    e.stopPropagation();
    this.appData.splice(index, 1); // Remove the item at the specific index
    this.selectedIndex = -1; // Reset the selection
    this.requestUpdate(); // Request an update to re-render the component
  }


  render() {
    return html`
      <div id="appGrid" class="app-grid">
        ${this.appData.map((app, index) => html`
          <div class="app-container" @click="${() => this.handleClick(index)}">
            ${this.selectedIndex === index
              ? html`<span id="remove-icon-${app.id}" class="remove-icon"
                           @click="${(e) => this.handleRemove(e, index)}">HIDE APP</span>`
              : ''}
            <dt-launcher-app-icon name="${app.name}" icon="${app.icon}"></dt-launcher-app-icon>
          </div>`)}
      </div>
    `;
  }

}

customElements.define('dt-launcher-app-grid', DtLauncherAppGrid);
