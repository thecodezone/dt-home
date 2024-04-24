import {css, html, LitElement} from 'lit';
import {property} from 'lit/decorators.js';
import '@spectrum-web-components/action-menu/sp-action-menu.js';
import {customElement} from "lit-element";

@customElement('dt-home-footer')
class HomeFooter extends LitElement {
  static properties = {
    appUrl: {type: String}
  };
  @property({type: Object})
  translations = {
    hiddenAppsLabel: 'Hidden Apps',
    buttonLabel: 'Ok',
  };
  @property({type: Array})
  appData = []; // Declare appData as a property

  static get styles() {
    return css`
      .footer-container {
        padding: 5px;
        box-sizing: border-box;

        justify-content: center;
      }


      .footer-button {
        display: flex;
        margin: 0px;
        padding: 5px 130px 11px 10px;
        font-size: 15px;
        border: 2px solid rgb(248, 243, 243);
        background-color: #F5F5F5;
        color: rgb(15, 15, 16);
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease 0s, color 0.3s ease 0s;
        white-space: nowrap;
        --mod-popover-border-width: 1px solid #404040 !important;
      }

      .footer-button:hover {
        background-color: #e1e0e0;
        color: #030000;
        cursor: pointer;
      }


      .footer-button span {
        text-decoration: none !important;
        color: #222 !important;
      }

      .no-data {
        font-size: 14px;
        padding: 4px;
      }

      sp-action-menu {
        margin-left: auto;
        font-size: 18px;
        border-radius: 10px;
        --mod-actionbutton-padding: 10px 20px;
      }

      .hidden__apps {
        --system-spectrum-button-primary-background-color-default: #3F729B;
        --system-spectrum-button-primary-background-color-hover: #3F729B;
        --system-spectrum-button-primary-background-color-down: #1b4465;
        --system-spectrum-button-secondary-background-color-default: #3F729B;
        --system-spectrum-button-secondary-background-color-hover: #3F729B;
        --system-spectrum-button-secondary-background-color-down: #1b4465;
        --system-spectrum-button-secondary-content-color-default: #ffff;
        --system-spectrum-button-secondary-content-color-hover: #ffff;
        --system-spectrum-button-secondary-content-color-down: #ffff;
        --spectrum-component-pill-edge-to-text-100: 40px;
        --mod-button-border-width: 10px;
        --spectrum-button-font-size: 14px;
      }

      :host {
        --mod-actionbutton-border-radius: 7px;
        font-weight: 400;
        color: #3F729B;
        --mod-actionbutton-background-color-default: #3F729B;
        --mod-actionbutton-content-color-default: #ffff !important;
        --mod-actionbutton-height: 40px;
        --mod-actionbutton-edge-to-text: 20px;
        --mod-popover-border-color: rgb(66, 64, 64);
        --mod-popover-corner-radius: 5px;
        --mod-popover-border-width: 1px;
        --spectrum-spacing-100: 20px;
        --mod-actionbutton-background-color-hover-selected: #ffff;
        --mod-actionbutton-background-color-hover: #F5F5F5;
        --mod-actionbutton-background-color-default-selected: #F5F5F5;
        --system-spectrum-actionbutton-selected-border-color-default: rgb(66, 64, 64);
        --mod-actionbutton-border-color-hover: rgb(66, 64, 64);
        --mod-actionbutton-content-color-default-selected: rgb(66, 64, 64);
        --mod-actionbutton-content-color-hover-selected: rgb(66, 64, 64);
        --mod-actionbutton-font-size: 18px;
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

  /**
   * Loads application data from the attributes and parses it into the appData property.
   *
   * @memberof HomeFooter
   * @returns {void}
   */
  loadAppData() {
    const jsonData = this.getAttribute('hidden-data');
    this.appUrl = this.getAttribute('app-url-unhide');
    if (jsonData) {
      this.appData = JSON.parse(jsonData);
    }
  }

  /**
   * Posts the selected app data to the server for un-hiding.
   *
   * @memberof HomeFooter
   * @param {string} appId - The ID of the app to un-hide.
   * @returns {void}
   */
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

  /**
   * Handles the click event on an app item.
   * Calls postAppDataToServer and requests an update.
   *
   * @memberof HomeFooter
   * @param {Event} e - The click event object.
   * @param {string} appId - The ID of the app clicked.
   * @returns {void}
   */
  handleAppClick(e, appid) {
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

  renderAppItems() {
    // Filter appData to only include items where is_hidden is true
    const hiddenApps = this.appData.filter(app => app.is_hidden === 1);

    // Check if the hiddenApps array is empty and return a message if so
    if (hiddenApps.length === 0) {
      return html`
        <sp-menu-item class="no-data">No hidden apps available.</sp-menu-item>`;
    }

    // Map the filtered data to HTML elements if hidden apps are present
    return hiddenApps.map(app => html`
      <sp-menu-item class="footer-button"
                    id="app-grid__remove-icon-${app.id}"
                    class="app-grid__remove-icon"
                    @click="${(e) => this.handleAppClick(e, app.id)}"
      >
        ${app.name}
      </sp-menu-item>

    `);
  }

  render() {
    return html`
      <div class="footer-container">
        <overlay-trigger type="modal">
          <sp-dialog-base underlay slot="click-content">
            <sp-dialog size="s">
              <h2 slot="heading">${this.translations.hiddenAppsLabel}</h2>
              <span slot="label">${this.translations.hiddenAppsLabel}</span>

              <sp-menu
                label="Choose an app">
                ${this.renderAppItems()}
              </sp-menu>

              <sp-button class="hidden__apps"
                         variant="secondary"
                         treatment="fill"
                         slot="button"
                         onclick="this.dispatchEvent(new Event('close', { bubbles: true, composed: true }));">
                ${this.translations.buttonLabel}
              </sp-button>
            </sp-dialog>
          </sp-dialog-base>

          <sp-button slot="trigger" class="hidden__apps" variant="primary">.&nbsp;.&nbsp;.&nbsp;
            ${this.translations.hiddenAppsLabel}
          </sp-button>
        </overlay-trigger>

      </div>
    `;
  }

}
