import {LitElement, html, css} from 'lit';
import {property} from 'lit/decorators.js';

class HomeFooter extends LitElement {
  static properties = {
    appUrl: {type: String}
  };
  @property({type: Object})
  translations = {
    hiddenAppsLabel: 'Hidden apps',
  };
  @property({type: Array})
  appData = []; // Declare appData as a property

  static get styles() {
    return css`
      .footer-container {
        padding: 5px;
        box-sizing: border-box;
        display: flex;
        justify-content: center;
      }

      .footer-button {
        display: inline-block;
        margin: 0px;
        padding: 4px 56px;
        font-size: 15px;
        border: 2px solid rgb(248, 243, 243);
        background-color: rgb(255, 255, 255);
        color: rgb(0, 123, 255);
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease 0s, color 0.3s ease 0s;
        white-space: nowrap;
      }

      .footer-button:hover {
        background-color: #f6f0f0;
        color: #ffffff;
        cursor: pointer;
      }

      .footer-button span {
        text-decoration: none !important;
        color: #222 !important;
      }

      sp-action-menu {
        margin-left: auto;
        font-size: 18px;
        border-radius: 10px;
        --mod-actionbutton-padding: 10px 20px;
      }

      :host {
        --mod-actionbutton-border-radius: 7px;
        font-weight: 100;
        color: #3F729B;
        --mod-actionbutton-background-color-default: #3F729B;
        --mod-actionbutton-content-color-default: #ffff !important;
        --mod-actionbutton-height: 40px;
        --mod-actionbutton-edge-to-text: 20px;
        --mod-popover-border-color: rgb(66, 64, 64);
        --mod-popover-corner-radius: 5px;
      }

      @media (hover: hover) {
        :host(:hover) {
          --mod-actionbutton-background-color-hover: #3F729B;
          --mod-actionbutton-content-color-hover: #ffff;
          --mod-popover-border-color: rgb(66, 64, 64);
        }
      }
    `;
  }


  connectedCallback() {
    super.connectedCallback();
    this.loadAppData();
  }

  loadAppData() {
    const jsonData = this.getAttribute('hidden-data');
    this.appUrl = this.getAttribute('app-url-unhide');
    if (jsonData) {
      this.appData = JSON.parse(jsonData);
    }
  }

  handleRemove(e, appid) {
    e.stopPropagation();
    const appIndex = this.appData.findIndex(app => app.id === appid);
    if (appIndex === -1) {
      console.error('App not found');
      return;
    }
    const appId = this.appData[appIndex].id;
    this.postAppDataToServer(appId);
    this.requestUpdate();
  }

  postAppDataToServer(appId) {

    const url = this.appUrl + "/un-hide-app";
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

  renderAppItems() {
    // Filter appData to only include items where is_hidden is '1'
    const hiddenApps = this.appData.filter(app => app.is_hidden == '1');
    const currentUrl = window.location.href;

    // Map the filtered data to HTML elements
    return hiddenApps.map(app => html`
      <sp-menu-item class="footer-button">
        <span id="app-grid__remove-icon-${app.id}" class="app-grid__remove-icon"
              @click="${(e) => this.handleRemove(e, app.id)}">${app.name}</span>
      </sp-menu-item>
    `);
  }

  render() {
    return html`
      <div class="footer-container">
        <sp-action-menu><span slot="label">&nbsp;  ${this.translations.hiddenAppsLabel}</span>
          ${this.renderAppItems()}
        </sp-action-menu>
      </div>
    `;
  }
}

customElements.define('dt-home-footer', HomeFooter);
