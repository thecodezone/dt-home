import { css, html, LitElement } from 'lit'
import { property } from 'lit/decorators.js'
import '@spectrum-web-components/dialog/sp-dialog.js'
import '@spectrum-web-components/button/sp-button.js'
import '@spectrum-web-components/overlay/overlay-trigger.js'
import { customElement } from 'lit-element'
import './app-menu.js'
import './app-menu-item.js'

@customElement('dt-home-footer')
class HomeFooter extends LitElement {
    static properties = {
        appUrl: { type: String },
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
                --spectrum-spacing-50: 0px;
                --spectrum-dialog-confirm-description-padding: var(
                    --spectrum-spacing-50
                );
            }

            .footer-container {
                padding: 5px;
                display: flex;
                justify-content: right;
                bottom: 10px;
            }

            .trigger-button {
                border: none;
                color: white;
                cursor: pointer;
                background-color: #1a73e8; /* Blue background */
                border-radius: 50%; /* Make the button circular */
                width: 40px; /* Set width */
                height: 40px; /* Set height */
                display: flex; /* Center the icon */
                justify-content: center; /* Center the icon horizontally */
                align-items: center; /* Center the icon vertically */
            }

            .trigger-button sp-icon-add {
                width: 36px; /* Icon width */
                height: 36px; /* Icon height */
            }

            .custom-dialog-overlay {
                position: fixed;
                inset: 380px 25px 18px 570px;
                bottom: 18px;
                top: 380px; /* Adjusted for better height */
                right: 25px;
                left: 570px;
                transform: none;
                z-index: 9999;
                border: none; /* Remove any border */
                height: 200px; /* Let the content dictate the height */
                max-height: 500px; /* Set max height to avoid overflow */
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); /* Optional: Add a subtle shadow */
            }

            .custom-dialog-overlay-button {
                position: fixed;
                bottom: 18px;
                top: 380px; /* Adjusted for better height */
                right: 25px;
                left: 570px;
                transform: none;
                z-index: 9999;
                border: none; /* Remove any border */
                height: 200px; /* Let the content dictate the height */
                max-height: 500px; /* Set max height to avoid overflow */
            }

            sp-dialog {
                background-color: white; /* Ensure the dialog is transparent */
                box-shadow: none; /* Remove shadow if desired */
                border: none; /* Remove border if necessary */
            }

            .cardButton {
                padding: 4px 21px;
                background-color: hsla(216, 100%, 50%, 1);
                color: white;
                cursor: pointer;
                bottom: 51px;
            }

            .cardButton:hover {
                background-color: hsla(
                    216,
                    100%,
                    60%,
                    1
                ); /* Slightly lighter blue on hover */
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

            @media (max-width: 600px) {
                .footer-container {
                    bottom: 10px; /* Move the button further down */
                    position: fixed;
                    width: 100%;
                    display: flex;
                    right: 10px;
                }

                .custom-dialog-overlay-button {
                    position: absolute;
                    top: 450px;
                    left: 64px;
                }

                .cardButton {
                    padding: 4px 21px;
                    background-color: hsla(216, 100%, 50%, 1);
                    color: white;
                    cursor: pointer;
                    bottom: 45px;
                    white-space: nowrap; /* Prevents text wrapping */
                    overflow: hidden; /* Ensures any overflow is hidden */
                    text-overflow: ellipsis; /* Adds an ellipsis (...) if the text overflows */
                }

                .custom-dialog-overlay {
                    left: 65px;
                    right: 0;
                    bottom: 0;
                    top: auto;
                    width: 60vw;
                    max-height: 400px; /* Set max height */
                    overflow: hidden; /* Hide overflow */
                }

                sp-dialog {
                    background-color: white;
                    height: auto; /* Let the content dictate the height */
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
        return this.appData.filter((app) => app.is_hidden === 1)
    }

    connectedCallback() {
        super.connectedCallback()
        this.loadAppData()
    }

    loadAppData() {
        const jsonData = this.getAttribute('hidden-data')
        this.appUrl = this.getAttribute('app-url-unhide')
        if (jsonData) {
            this.appData = JSON.parse(jsonData)
        }
    }

    postAppDataToServer(appSlug) {
        const url = this.appUrl + '/un-hide-app'
        const appToHide = this.appData.find((app) => app.slug === appSlug)

        if (!appToHide) {
            console.error('App not found')
            return
        }
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': $home.nonce,
            },
            body: JSON.stringify(appToHide),
        })
            .then((response) => {
                if (response.ok) {
                    window.location.reload()
                } else {
                    // Handle error
                }
            })
            .catch((error) => {
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
        this.postAppDataToServer(appId)
        this.requestUpdate()
    }

    isIconURL(icon) {
        return /^(https?:\/\/|data:image|\/|\.\/|\.\.\/)/.test(icon)
    }

    renderAppItems() {
        const hiddenApps = this.hiddenApps

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
                <overlay-trigger type="modal">
                    <button slot="trigger" class="trigger-button">
                        <sp-icon-add></sp-icon-add>
                    </button>
                    <div
                        slot="click-content"
                        class="custom-app custom-dialog-overlay-button"
                    >
                        <sp-button class="cardButton"> Custom App</sp-button>
                    </div>
                    <sp-dialog
                        slot="click-content"
                        class="custom-dialog-overlay"
                        size="xs"
                    >
                        <div class="card">
                            <dt-app-menu label="Choose an app">
                                ${this.renderAppItems()}
                            </dt-app-menu>
                        </div>
                    </sp-dialog>
                </overlay-trigger>
            </div>
        `
    }
}
