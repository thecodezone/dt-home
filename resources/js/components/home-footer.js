import { css, html, LitElement } from 'lit'
import { property } from 'lit/decorators.js'
import '@spectrum-web-components/dialog/sp-dialog.js'
import '@spectrum-web-components/button/sp-button.js'
import '@spectrum-web-components/overlay/overlay-trigger.js'
import '@spectrum-web-components/icons-workflow/icons/sp-icon-upload-to-cloud.js'
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
                --system-spectrum-button-accent-background-color-hover: #3fab3f;
                --system-spectrum-button-accent-background-color-down: #3fab3f;
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
                margin-right: 152px;
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
                margin-left: -439px;
                top: 238px;
            }

            .custom-app {
                margin-right: -102px;
                margin-top: -35px;
                --system-spectrum-actionbutton-background-color-default: #3fab3f;
                --system-spectrum-actionbutton-background-color-hover: #3fab3f;
                --system-spectrum-actionbutton-background-color-down: #3fab3f;
                --highcontrast-actionbutton-content-color-default: #ffffff;
                --spectrum-neutral-content-color-hover: #ffffff;
                --spectrum-neutral-content-color-down: #ffffff;
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

            /* CSS for the modal */

            .trigger {
                text-align: center;
                padding: 7px 13px;
                background: #e34b4b;
                color: #fff;
                font-size: 15px;
                outline: none;
                border: none;
                border-radius: 5px;
            }

            .trigger-submit {
                text-align: center;
                padding: 7px 13px;
                background: #4caf50;
                color: #fff;
                font-size: 15px;
                outline: none;
                border: none;
                border-radius: 5px;
            }


            .modal-content {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: white;
                padding: 1rem 1.5rem;
                width: 24rem;
                border-radius: 0.5rem;
            }

            .close-button {
                float: right;
                width: 1.5rem;
                line-height: 1.5rem;
                text-align: center;
                cursor: pointer;
                border-radius: 0.25rem;
                background-color: lightgray;
            }

            .close-button:hover {
                background-color: darkgray;
            }

            .modal {
                position: fixed;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                opacity: 0;
                visibility: hidden;
                transform: scale(1.1);
                transition: visibility 0s linear 0.25s,
                opacity 0.25s 0s,
                transform 0.25s;
                z-index: 10000; /* Ensure it appears above other content */
            }


            .modal-content {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: white;
                padding: 1rem 1.5rem;
                width: 24rem;
                border-radius: 0.5rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            }

            .modal-close {
                float: right;
                width: 1.5rem;
                line-height: 1.5rem;
                text-align: center;
                cursor: pointer;
                border-radius: 0.25rem;
                background-color: lightgray;
            }

            .modal-close:hover {
                background-color: darkgray;
            }

            .show-modal {
                background-color: unset;
                opacity: 1;
                visibility: visible;
                transform: scale(1);
                transition: visibility 0s linear 0s,
                opacity 0.25s 0s,
                transform 0.25s;
            }

            @media (max-width: 600px) {
                .modal-content {
                    width: 90%;
                    padding: 1rem;
                }
            }

            .error {
                border-color: red; /* or use background-color if preferred */
            }

            #form-alert {
                color: red;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .modal-title > h3 {
                display: contents;
                font-size: 18px;
                font-style: poppins;
            }

            .dt__icon {
                display: flex;
            }

            .upload-icon {
                top: 20px;
                left: 10px;
                --system-spectrum-actionbutton-background-color-default: #cac4c4;
                --system-spectrum-actionbutton-background-color-hover: #cac4c4;
                --system-spectrum-actionbutton-background-color-down: #cac4c4;
                --highcontrast-actionbutton-content-color-default: #ffffff;
                --spectrum-neutral-content-color-hover: #ffffff;
                --spectrum-neutral-content-color-down: #ffffff;
                --mod-actionbutton-height: 41px;
                --spectrum-action-button-edge-to-hold-icon-medium: 10px;
            }
        `
    }

    get hiddenApps() {
        return this.appData.filter((app) => app.is_hidden === 1)
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

    openModal() {
        const modal = this.shadowRoot.getElementById('customModal')
        modal.classList.add('show-modal')
    }

    closeModal() {
        const modal = this.shadowRoot.getElementById('customModal')
        modal.classList.remove('show-modal')
        this.clearForm()
    }

    toggleModal() {
        const overlayTrigger = this.shadowRoot.querySelector('overlay-trigger')
        if (overlayTrigger && overlayTrigger.open) {
            overlayTrigger.open = false // Close the overlay (and the dialog)
        }
        this.openModal()
    }

    validateForm() {
        const form = this.shadowRoot.querySelector('form')
        const requiredFields = form.querySelectorAll('[required]')
        let isValid = true

        // Clear previous error messages
        this.shadowRoot.querySelector('#form-alert').innerText = ''

        requiredFields.forEach((field) => {
            const value = field.value && field.value.trim() // Ensure value exists and is trimmed

            if (!value) {
                isValid = false
                field.setAttribute('aria-invalid', 'true')
                field.classList.add('error') // Optionally, add an error class
            } else {
                field.removeAttribute('aria-invalid')
                field.classList.remove('error')
            }
        })

        // Show alert message if the form is invalid
        if (!isValid) {
            this.shadowRoot.querySelector('#form-alert').innerText =
                'Please fill in the required fields'
        }

        return isValid
    }

    handleSubmit(e) {
        e.preventDefault()
        const isFormValid = this.validateForm()

        if (isFormValid) {
            // Proceed with form submission logic
            this.handleFormSubmit(
                e,
                this.shadowRoot.getElementById('customModal')
            )
            this.clearForm()
            this.closeModal()
        } else {
            console.log('Form is invalid. Please fill in the required fields.')
        }
    }

    clearForm() {
        this.shadowRoot.getElementById('form-alert').innerText = ''
        // Clear dt-text fields
        this.shadowRoot.getElementById('name').value = ''
        this.shadowRoot.getElementById('slug').value = ''

        // Clear other dt-text fields by name
        this.shadowRoot.querySelector('[name="icon"]').value = ''
        this.shadowRoot.querySelector('[name="url"]').value = ''

        // Clear dt-single-select field
        this.shadowRoot.querySelector('[name="type"]').value = ''

        // Clear checkbox
        this.shadowRoot.getElementById('open_in_new_tab').checked = false
    }

    reset_apps() {
        const confirmationResetMessage =
            $home.translations.reset_app_confirmation
        const confirmDelete = confirm(confirmationResetMessage)

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
            return html` <dt-app-menu-item class="no-data">
                ${$home.translations.no_hidden_apps}.
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
                    <div
                        slot="click-content"
                        class="custom-dialog-overlay-button custom-app"
                        id="custom-app"
                    >
                        <sp-action-button @click="${this.toggleModal}"
                        >${$home.translations.custom_app_label}
                        </sp-action-button>
                    </div>
                    <sp-button slot="trigger" class="trigger-button">
                        <sp-icon-add></sp-icon-add>
                    </sp-button>
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
                        ${
                            this.resetApps
                                ? html`
                                      <sp-action-button
                                          class="reset-apps"
                                          @click="${this.reset_apps}"
                                          >${$home.translations
                                              .reset_apps_label}
                                      </sp-action-button>
                                  `
                                : null
                        }
                    </div>
                </overlay-trigger>
            </div>
            <!-- Custom Modal Structure -->
            <div class="modal" id="customModal">

                <div class="modal-content">
                    <div class="modal-title"
                    ><h3>${$home.translations.custom_app_label} </h3>
                        <span class="modal-close" @click="${this.closeModal}"
                        >&times;
          </span
                    </div
                    >
                    <br />
                    <div id="form-alert"></div>
                    <form @submit="${this.handleSubmit}" id="custom-form">
                        <dt-text
                            label="Name"
                            id="name"
                            name="name"
                            placeholder="Name"
                            required
                            tabindex="1"
                            @change="${this.updateSlugField}" <!-- Add event listener here -->

                        ></dt-text>
                        <dt-single-select
                            name="type"
                            label="Type"
                            placeholder="Type"
                            options='[{"id":"Web View","label":"Web View"},{"id":"Link","label":"Link"}]'
                            value=""
                            iconalttext="Icon Alt Text"
                            privatelabel=""
                            onchange=""
                        >
                        </dt-single-select>
                        Open NewTab
                        <input
                            type="checkbox"
                            name="open_in_new_tab"
                            id="open_in_new_tab"
                            value="1"
                        />
                        <br />
                        <br />
                        <div class="dt__icon">
                            <dt-text
                                label="Icon"
                                name="icon"
                                placeholder="Icon"
                                required
                                tabindex="3"
                            ></dt-text>
                            <sp-action-button class="upload-icon button change-icon-button">
                                <sp-icon-upload-to-cloud></sp-icon-upload-to-cloud>
                            </sp-action-button>
                        </div>

                        <dt-text
                            name="url"
                            label="URL"
                            placeholder="URL"
                            required
                            tabindex="4"
                        ></dt-text>
                        <dt-text
                            id="slug"
                            label="Slug"
                            name="slug"
                            placeholder="Slug"
                            required
                            tabindex="5"
                        ></dt-text>

                        <sp-button-group>
                            <sp-button tabindex="6" type="submit" class="trigger-submit">
                                <span>Submit</span>
                            </sp-button>

                            <sp-button
                                class="cre-ac trigger"
                                variant="secondary" @click="${this.closeModal}">
                <span> Close
                </span>
                            </sp-button>
                        </sp-button-group>
                    </form>
                </div>
            </div>
        `
    }
}
