import { css, html, LitElement } from 'lit'
import { property } from 'lit/decorators.js'
import '@spectrum-web-components/dialog/sp-dialog.js'
import '@spectrum-web-components/button/sp-button.js'
import '@spectrum-web-components/overlay/overlay-trigger.js'
import { customElement } from 'lit-element'
import './app-menu.js'
import './app-menu-item.js'
import { magic_url } from '../helpers.js'

@customElement('dt-home-footer')
class HomeFooter extends LitElement {
    static properties = {
        appUrl: { type: String },
        resetApps: { type: Boolean },
        buttonColor: { type: String },
    }

    @property({ type: Object })
    translations = {
        hiddenAppsLabel: 'Hidden Apps',
        buttonLabel: 'Ok',
    }

    @property({ type: Array })
    appData = []

    static get styles() {
        return css`
      :host {
        --mod-divider-thickness: 0px;
        --spectrum-spacing-300: 0px;
        --spectrum-spacing-200: 0px;
        --spectrum-dialog-confirm-divider-block-spacing-start: var(
          --spectrum-spacing-300
        );
        --spectrum-dialog-confirm-divider-block-spacing-end: var(
          --spectrum-spacing-200
        );
        --mod-dialog-confirm-padding-grid: 0px;
        --spectrum-dialog-confirm-padding-grid: 0px;
      );
      }

      .footer-container {
        padding: 5px;
        display: flex;
        justify-content: right;
        bottom: 20px;
      }

      .trigger-button {
        --mod-button-min-width: 41px;
        --mod-button-border-width: 6px 3px 2.5px 3px;
        --spectrum-border-width-200: 12px;
        --spectrum-button-bottom-to-text-medium: 0px;
        --spectrum-button-top-to-text-medium: 0px;
        --spectrum-workflow-icon-size-100: 26px;
        --spectrum-button-edge-to-text: 0px;
        --system-spectrum-button-accent-background-color-hover: var(--button-color);
        --system-spectrum-button-accent-background-color-down: var(--button-color);
        --system-spectrum-button-accent-background-color-default: var(--button-color);
        --spectrum-focus-indicator-color: transparent;
        border-radius: 50%;
      }

      sp-icon-add {
        color: white;
      }

      sp-dialog {
        background-color: white;
        border: none; /* Remove any border */
        box-shadow: none; /* Remove any shadow */
        height: 200px; /* Let the content dictate the height */
        padding: 0; /* Remove default padding */
        overflow: hidden; /* Hide overflow */
        margin-right: 50px;
        margin-bottom: -41px;
      }

      .app-row {
        display: flex;
        align-items: center;
        border-bottom: 1px solid lightgray; /* Add bottom border */
      }

      .app-row:last-child {
        border-bottom: none; /* Remove bottom border for the last item */
      }

      .app-icon {
        width: 30px;
        height: 30px;
        margin-right: 8px;
      }

      .material-icons.app-icon {
        font-size: 30px;
        line-height: 30px;
      }

      .app-name {
        flex: 1; /* Ensure the name takes the remaining space */
        color: black; /* Ensure text color is black */
      }

      .app-name:hover {
        color: hsla(216, 100%, 50%, 1);
      }

      .reset-apps {
        color: #ffffff;
        background-color: #e94f54;
        --system-spectrum-actionbutton-background-color-default: var(
          --background-color
        );
        --spectrum-component-height-100: 19px;
        --spectrum-font-size-100: 10px;
        margin-left: -335px;
        top: 204px;
      }

      /* Mobile */
      @media (max-width: 600px) {
        .footer-container {
          position: fixed;
          width: 100%;
          display: flex;
          right: 10px;
        }

        .custom-dialog-overlay {
          left: calc(100vw - 72vw) !important;
          right: 0;
          bottom: 25px;
          top: auto;
          width: 60vw;
          max-height: 400px; /* Set max height */
          overflow: hidden; /* Hide overflow */
        }

        sp-dialog {
          background-color: white;
          height: 200px;
        }
      }

      /* Tablet */
      @media (min-width: 601px) and (max-width: 962px) {
        .footer-container {
          position: fixed;
          width: 100%;
          display: flex;
          right: 10px;
        }

        .custom-dialog-overlay {
          left: calc(100vw - 350px) !important;
          top: auto;
          width: 100px;
        }
      }

      /* Desktop */
      @media (min-width: 963px) and (max-width: 1920px) {
        .footer-container {
          position: fixed;
          width: 100%;
          display: flex;
          right: 10px;
        }

        .custom-dialog-overlay {
          left: calc(100vw - 350px) !important;
          top: auto;
          width: 100px;
        }
      }

      sp-menu-item {
        background-color: transparent !important;
        border-left: none !important;
        transition: none !important;
        padding-left: 0 !important;
        outline: none !important; /* Ensure no focus outline */
        box-shadow: none !important; /* Ensure no box shadow */
      }

      sp-menu-item::part(heading),
      sp-menu-item::part(indicator),
      sp-menu-item::part(checkmark) {
        display: none !important;
      }

      sp-menu-item:hover,
      sp-menu-item:focus,
      sp-menu-item:active,
      sp-menu-item:focus-visible {
        border-left: none !important; /* Remove left border on all states */
        background-color: lightgray !important; /* Change hover background color as needed */
        outline: none !important; /* Remove focus outline */
        box-shadow: none !important; /* Ensure no box shadow */
      }

      sp-menu-item[focused] {
        outline: none !important;
        border-left: none !important; /* Remove blue left border */
        box-shadow: none !important; /* Ensure no box shadow */
      }

      .no-data {
        color: gray;
        padding: 10px; /* Add padding for space */
      }
    `
    }

    get hiddenApps() {
        return this.appData.filter((app) => app.is_hidden)
    }

    connectedCallback() {
        super.connectedCallback()
        this.loadAppData()
        this.style.setProperty('--button-color', this.buttonColor)
        document.addEventListener('app-hidden', this.handleAppHidden.bind(this))
    }

    disconnectedCallback() {
        super.disconnectedCallback()
        document.removeEventListener(
            'app-hidden',
            this.handleAppHidden.bind(this)
        )
    }

    handleAppHidden(event) {
        const hiddenApp = event.detail.app
        const appIndex = this.appData.findIndex(
            (app) => app.slug === hiddenApp.slug
        )

        if (appIndex > -1) {
            this.appData[appIndex] = hiddenApp
            this.requestUpdate()
        }
    }

    loadAppData() {
        const jsonData = this.getAttribute('hidden-data')
        this.appUrl = this.getAttribute('app-url-unhide')
        this.resetApps = this.getAttribute('reset-apps') === '1'
        this.buttonColor = this.getAttribute('button-color')
        if (jsonData) {
            this.appData = JSON.parse(jsonData)
        }
    }

    postAppDataToServer(appSlug) {
        const url = magic_url('unhide')
        const appToUnHide = this.appData.find((app) => app.slug === appSlug)
        if (!appToUnHide) {
            console.error('App not found')
            return
        }
        // Optimistically update the UI before making the request
        appToUnHide.is_hidden = 0
        this.requestUpdate()

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': $home.nonce,
            },
            body: JSON.stringify(appToUnHide),
        })
            .then((response) => {
                // Handle error
                if (!response.ok) {
                    // If the request fails, revert the UI change
                    appToUnHide.is_hidden = 1
                    this.requestUpdate()
                    console.error('Failed to update the server')
                }
            })
            .catch((error) => {
                appToUnHide.is_hidden = 1
                this.requestUpdate()
                console.error('Error:', error)
            })
    }

    handleAppClick(e, appSlug) {
        e.stopPropagation()
        const appIndex = this.appData.findIndex((app) => app.slug === appSlug)
        if (appIndex === -1) {
            console.error('App not found')
            return
        }
        const appId = this.appData[appIndex].slug
        this.appData[appIndex].is_hidden = 0
        this.postAppDataToServer(appSlug)
        // Dispatch a custom event that the app has been unhidden
        this.dispatchEvent(
            new CustomEvent('app-unhidden', {
                detail: { app: this.appData[appIndex] },
                bubbles: true,
                composed: true,
            })
        )
    }

    isIconURL(icon) {
        return /^(https?:\/\/|data:image|\/|\.\/|\.\.\/)/.test(icon)
    }

    reset_apps() {
        const confirmDelete = confirm(
            'Are you sure you want to reset all apps?'
        )

        if (confirmDelete) {
            fetch(magic_url('reset-apps'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': $home.nonce,
                },
            })
                .then((response) => {
                    if (response.ok) {
                        console.log(response)
                        window.location.reload()
                    } else {
                        // Handle error
                    }
                })
                .catch((error) => {
                    console.error('Error:', error)
                })
        } else {
            return false
        }
    }

    renderAppItems() {
        const hiddenApps = this.hiddenApps.sort((a, b) => b.sort - a.sort)
        if (hiddenApps.length === 0) {
            return html` <dt-app-menu-item class="no-data"
                >No hidden apps available.
            </dt-app-menu-item>`
        }

        return hiddenApps.map(
            (app) => html`
                <dt-app-menu-item
                    @click="${(e) => this.handleAppClick(e, app.slug)}"
                >
                    <div class="app-row">
                        ${this.isIconURL(app.icon)
                            ? html`<img
                                  src="${app.icon}"
                                  class="app-icon"
                                  alt="icon"
                              />`
                            : html`<span
                                  id="app-icon"
                                  class="app-icon material-icons ${app.icon}"
                              ></span>`}
                        <span class="app-name">${app.name}</span>
                    </div>
                </dt-app-menu-item>
            `
        )
    }

    render() {
        return html`
            <style>
                @import url('https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css');
            </style>
            <div class="footer-container">
                <overlay-trigger type="replace" placement="top">
                    <sp-button slot="trigger" class="trigger-button">
                        <sp-icon-add></sp-icon-add>
                    </sp-button>
                    <!--                    <button slot="trigger" class="trigger-button1">-->
                    <!--                        <sp-icon-add></sp-icon-add>-->
                    <!--                    </button>-->
                    <sp-dialog
                        slot="click-content"
                        class="custom-dialog-overlay"
                        size="xs"
                    >
                        <dt-app-menu label="Choose an app">
                            ${this.renderAppItems()}
                        </dt-app-menu>
                    </sp-dialog>

                    <div
                        slot="click-content"
                        class="custom-app custom-dialog-overlay-button"
                    >
                        ${this.resetApps
                            ? html`
                                  <sp-action-button
                                      class="reset-apps"
                                      @click="${this.reset_apps}"
                                      >Reset Apps
                                  </sp-action-button>
                              `
                            : null}
                    </div>
                </overlay-trigger>
            </div>
        `
    }
}
