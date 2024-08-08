import { css, html, LitElement } from 'lit'
import { property, state } from 'lit/decorators.js'
import '@spectrum-web-components/action-menu/sp-action-menu.js'
import '@spectrum-web-components/dialog/sp-dialog.js'
import '@spectrum-web-components/button/sp-button.js'
import '@spectrum-web-components/overlay/overlay-trigger.js'
import { customElement } from 'lit-element'

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
    appData = [] // Declare appData as a property
    @state() overlayVisible = false

    static get styles() {
        return css`
            .footer-container {
                padding: 5px;
                box-sizing: border-box;
                display: flex;
                justify-content: right;
            }

            .trigger-button {
                padding: 10px 20px;
                font-size: 16px;
                border: none;
                background-color: #3f729b;
                color: white;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .trigger-button:hover {
                background-color: #315a7d;
            }

            .custom-dialog-overlay {
                position: fixed;
                bottom: 18px;
                top: 320px;
                right: 25px;
                left: 450px;
                transform: none;
                z-index: 9999;
            }

            sp-dialog {
                --spectrum-dialog-background-color: white; /* Ensure the dialog has a white background */
                background-color: white; /* Fallback in case custom properties are not working */
            }

            sp-dialog::part(heading) {
                color: red; /* Example of targeting a part in shadow DOM */
            }

            @media (max-width: 600px) {
                .custom-dialog-overlay {
                    left: 65px;
                    right: 0;
                    bottom: 0;
                    top: auto;
                    width: 60vw; /* Full width on small screens */
                    height: 40vh; /* Adjust height for small screens */
                    max-width: none; /* Remove max-width restriction */
                    max-height: none; /* Remove max-height restriction */
                }

                sp-dialog {
                    --spectrum-dialog-background-color: white;
                    background-color: white;
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
            sp-menu-item:active {
                border-left: none !important; /* Remove left border on all states */
                background-color: lightgray !important; /* Change hover background color as needed */
                outline: none !important; /* Remove focus outline */
                box-shadow: none !important; /* Ensure no box shadow */
            }

            .no-data {
                color: gray;
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

    renderTrigger() {
        return html` <button class="trigger-button">Open Popup</button> `
    }

    renderAppItems() {
        const hiddenApps = this.hiddenApps

        if (hiddenApps.length === 0) {
            return html` <sp-menu-item class="no-data"
                >No hidden apps available.</sp-menu-item
            >`
        }

        return hiddenApps.map(
            (app) => html`
                <sp-menu-item
                    class="footer-button"
                    id="app-grid__remove-icon-${app.slug}"
                    @click="${(e) => this.handleAppClick(e, app.slug)}"
                >
                    ${app.name}
                </sp-menu-item>
            `
        )
    }

    render() {
        return html`
            <div class="footer-container">
                <overlay-trigger type="modal">
                    <svg
                        slot="trigger"
                        xmlns="http://www.w3.org/2000/svg"
                        height="36px"
                        viewBox="0 0 18 18"
                        width="36px"
                    >
                        <defs>
                            <style>
                                .fill {
                                    fill: hsla(221, 94%, 47%, 1);
                                }
                            </style>
                        </defs>

                        <path
                            class="fill"
                            d="M9,1a8,8,0,1,0,8,8A8,8,0,0,0,9,1Zm5,8.5a.5.5,0,0,1-.5.5H10v3.5a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5V10H4.5A.5.5,0,0,1,4,9.5v-1A.5.5,0,0,1,4.5,8H8V4.5A.5.5,0,0,1,8.5,4h1a.5.5,0,0,1,.5.5V8h3.5a.5.5,0,0,1,.5.5Z"
                        />
                    </svg>

                    <sp-dialog
                        underlay
                        slot="click-content"
                        class="custom-dialog-overlay"
                        size="s"
                        dismissable
                    >
                        <h2 slot="heading">Hidden Apps</h2>
                        <p>
                            <sp-menu label="Choose an app">
                                ${this.renderAppItems()}
                            </sp-menu>
                        </p>
                        <sp-button
                            slot="button"
                            variant="secondary"
                            @click="${() => (this.overlayVisible = false)}"
                        >
                            Close
                        </sp-button>
                    </sp-dialog>
                </overlay-trigger>
            </div>
        `
    }
}
