import {css, html, LitElement} from 'lit'
import {customElement} from 'lit-element'
import {property, queryAll} from 'lit/decorators.js'
import {magic_url} from '../helpers.js'

/**
 * Custom element representing an application grid.
 */

@customElement('dt-home-app-grid')
class AppGrid extends LitElement {
    /**
     * Styles for the AppGrid element.
     * @static
     * @type {CSSResult}
     */
    static styles = css`
        .app-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px 0;
            justify-items: center;
        }

        .app-grid__item {
            transition: transform 0.2s;
            position: relative;
            width: 100%;
            cursor: pointer;
            min-width: 0;
            min-height: 0;
        }

        .app-grid__item:hover {
            transform: scale(1.05);
        }

        .app-grid__item--over {
            opacity: 0.2;
        }

        .app-grid__item--dragging {
            background-color: transparent;
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

        .app-grid__remove-icon.hidden {
            display: none;
        }
    `
    @property({ type: Array }) appData = []
    @property({ type: Number }) selectedIndex = -1
    @property({ type: String }) appUrl = ''
    @property({ type: Boolean }) editing = false
    @queryAll('.app-grid__item') items = []
    showRemoveIconId = null
    clickTimer = null
    clickDelay = 300
    longPressTimer = 500
    longPressDuration = 1000

    /**
     * Lifecycle callback when the element is connected to the DOM.
     */
    connectedCallback() {
        super.connectedCallback()
        this.loadAppData()
        document.addEventListener('click', this.handleDocumentClick)
        document.addEventListener('mousedown', this.handleMouseDown)
        document.addEventListener('mouseup', this.handleMouseUp)
        document.addEventListener('mouseleave', this.handleMouseLeave)
    }

    /**
     * Adds the drag-over class to the app.
     * @param {DragEvent} event - The drag event.
     */
    handleDragOver(event) {
        event.preventDefault()
        event.target.classList.add('app-grid__item--over')
    }

    /**
     * Removes the drag-over class from the app.
     */
    handleDragLeave(event) {
        event.target.classList.remove('app-grid__item--over')
    }

    /**
     * Handles the drag event by setting the data being dragged.
     * @param {DragEvent} event - The drag event.
     * @param {number} index - The index of the dragged item.
     */
    handleDragStart(event, index) {
        this.showRemoveIconId = null
        event.dataTransfer.setData('text/plain', index)
        // Hide all remove icons
        this.shadowRoot
            .querySelectorAll('.app-grid__remove-icon')
            .forEach((icon) => {
                icon.classList.add('hidden')
            })
    }

    handleDragEnd(event) {
        this.items.forEach((item) => {
            item.classList.remove('app-grid__item--over')
            item.classList.remove('app-grid__item--dragging')
        })
        // Show all remove icons again
        this.shadowRoot
            .querySelectorAll('.app-grid__remove-icon')
            .forEach((icon) => {
                icon.classList.remove('hidden')
            })
    }

    /**
     * Handles the drop event by reordering the apps.
     * @param {DragEvent} event - The drop event.
     */
    handleDrop(event) {
        event.preventDefault()
        const fromIndex = event.dataTransfer.getData('text/plain')
        const toElement = event.target.closest('.app-grid__item')
        const toIndex = Array.from(this.items).indexOf(toElement)
        if (fromIndex >= 0 && toIndex >= 0) {
            this.reorderApps(fromIndex, toIndex)
        }

        // Call handleDocumentClick to ensure immediate removal of context menu icon
        this.handleDocumentClick(event)
    }

    /**
     * Handles a single click event on an app.
     *
     * @param {Event} event - The click event.
     * @param {number} index - The index of the clicked app.
     *
     * @return {void}
     */
    handleClick(event, slug) {
        if (
            this.showRemoveIconId === null &&
            this.clickTimer === null &&
            this.longPressTimer === null
        ) {
            this.clickTimer = setTimeout(() => {
                this.handleSingleClick(event, slug)
                this.showRemoveIconId = null
                this.requestUpdate()
                this.clickTimer = null
            }, this.clickDelay)
        }
    }

    /**
     * Handles a single click event.
     *
     * @param {Event} event - The click event.
     * @param {number} index - The index of the app in the appData array.
     * @return {void}
     */
    handleSingleClick(event, slug) {
        const selectedApp = this.appData.find((app) => app.slug === slug)
        if (selectedApp) {
            switch (selectedApp.type) {
                case 'Link':
                    this.visitApp(selectedApp.url, selectedApp)
                    break
                default:
                    this.visitApp(
                        this.addOrUpdateQueryParam(
                            magic_url(`/app/${selectedApp.slug}/`),
                            'dt_home',
                            'true'
                        ),
                        selectedApp
                    )
                    break
            }
        }
    }

    visitApp(url, options) {
        if ( Boolean( JSON.parse( options.open_in_new_tab ?? false ) ) ) {
            window.open(url, '_blank')
        } else {
            window.location.href = url
        }
    }

    /**
     * Adds or updates a query parameter in the given URL.
     *
     * @param {string} url - The URL to modify.
     * @param {string} key - The query parameter key.
     * @param {string} value - The query parameter value.
     * @returns {string} - The updated URL with the new or updated query parameter.
     */
    addOrUpdateQueryParam(url, key, value) {
        const urlObj = new URL(url)
        urlObj.searchParams.set(key, value)
        return urlObj.toString()
    }

    /**
     * Handles a double click event on an app.
     * @param event
     * @param {number} index - The index of the double-clicked app.
     * @param {object} app - The app object.
     */
    handleDoubleClick(event, index, { slug }) {
        clearTimeout(this.clickTimer)
        this.clickTimer = null
        // Your double click logic here
        this.showRemoveIconId = slug
        this.requestUpdate()
    }

    /**
     * Handles removal of an app.
     * @param event
     * @param {number} index - The index of the app to be removed.
     * @param {object} app - The app object.
     */
    handleRemove(event, index, { slug }) {
        event.stopPropagation()
        event.preventDefault()
        const confirmationMessage = $home.translations.remove_app_confirmation
        const confirmed = window.confirm(confirmationMessage)
        if (confirmed) {
            this.postAppDataToServer(slug)
            this.appData.splice(index, 1)
            this.selectedIndex = -1
            this.showRemoveIconId = null
        }
        return false
    }

    /**
     * Handles a click outside the context menu.
     * @param {MouseEvent} event - The click event.
     */
    handleDocumentClick = (event) => {
        // Check if a long press has occurred
        if (this.longPressOccured) {
            // Reset the flag
            this.longPressOccured = false
            return
        }
        // Check if the click is outside the context menu
        const removeIcon = this.shadowRoot.querySelector(
            '.app-grid__remove-icon'
        )
        if (removeIcon && !removeIcon.contains(event.target)) {
            this.showRemoveIconId = null
            this.editing = false // Set editing to false when clicking outside
            this.isDragging = false
            this.requestUpdate()
        }
    }

    /**
     * Reorders apps based on drag and drop.
     * @param {number} fromIndex - The index from which the app is dragged.
     * @param {number} toIndex - The index to which the app is dropped.
     */
    reorderApps(fromIndex, toIndex) {
        const appsCopy = [...this.appData]
        const [removedApp] = appsCopy.splice(fromIndex, 1)
        appsCopy.splice(toIndex, 0, removedApp)
        this.appData = appsCopy
        this.postNewOrderToServer()
    }

    /**
     * Posts the new order of apps to the server.
     */
    postNewOrderToServer() {
        const url = magic_url('reorder')
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': $home.nonce,
            },
            body: JSON.stringify(this.appData),
        })
            .then((response) => response.json())
            .then((data) => {
                console.log('Order update successful:', data)
            })
            .catch((error) => {
                console.error('Error updating order:', error)
            })
    }

    /**
     * Loads application data from an external source.
     */
    loadAppData() {
        // Fetch your data from an external source or set it from an attribute
        // For this example, let's assume it's set from a JSON attribute
        const jsonData = this.getAttribute('app-data')
        this.appUrl = this.getAttribute('app-url')

        if (jsonData) {
            this.appData = JSON.parse(jsonData)
        }
    }

    /**
     * Posts data of the app to be hidden to the server.
     * @param {string} appId - The ID of the app to be hidden.
     */
    postAppDataToServer(slug) {
        const url = magic_url('hide')
        const appToHide = this.appData.find((app) => app.slug === slug)

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
                    console.log(response)
                    window.location.reload()
                } else {
                    // Handle error
                }
            })
            .catch((error) => {
                console.error('Error:', error)
            })
    }

    /**
     * Handles mouse down event on an app.
     * @param {MouseEvent} event - The mouse down event.
     * @param {number} index - The index of the app.
     */
    handleMouseDown = (event, index, slug) => {
        let appGridItem = event.target.closest('.app-grid__item')
        if (!appGridItem) {
            // If the target itself isn't an app grid item, check its parents
            appGridItem = event.target.parentElement.closest('.app-grid__item')
        }
        if (appGridItem) {
            this.longPressTimer = setTimeout(() => {
                this.editing = true // Enable editing mode on long press
                this.showContextMenu(slug)
                // Set a flag to indicate that a long press has occurred
                this.longPressOccured = true
            }, this.longPressDuration)
        }
    }

    /**
     * Handles mouse up event.
     */
    handleMouseUp = () => {
        clearTimeout(this.longPressTimer)
        this.longPressTimer = null
    }

    /**
     * Handles mouse leave event.
     * @param {number} index - The index of the app.
     */
    handleMouseLeave = (index) => {
        clearTimeout(this.longPressTimer)
        // Do something with the index if needed
    }

    /**
     * Shows context menu for the app.
     * @param {number} index - The index of the app.
     */
    showContextMenu(slug) {
        // Your logic to show the context menu
        // For example, you can set a property to indicate which index's context menu to show
        this.showRemoveIconId = slug
        this.requestUpdate()
    }

    handleTouchStart = (event, index, slug) => {
        let appGridItem = event.target.closest('.app-grid__item')
        if (!appGridItem) {
            appGridItem = event.target.parentElement.closest('.app-grid__item')
        }
        if (appGridItem) {
            this.longPressTimer = setTimeout(() => {
                this.editing = true // Enable editing mode on long press
                this.showContextMenu(slug)
                this.longPressOccured = true
            }, this.longPressDuration)
        }
    }

    handleTouchEnd = () => {
        clearTimeout(this.longPressTimer)
        this.longPressTimer = null
    }

    /**
     * Renders the AppGrid element.
     * @returns {TemplateResult} HTML template result.
     */
    render() {
        return html`
            <div class="app-grid">
                ${this.appData
                    .filter((app) => !app.is_hidden) // Filter out hidden apps
                    .map(
                        (app, index) => html`
                            <div
                                class="app-grid__item ${this.editing
                                    ? 'editing'
                                    : ''}"
                                data-index="${index}"
                                @touchstart="${(event) =>
                                    this.handleTouchStart(event, index, app)}"
                                @touchend="${this.handleTouchEnd}"
                                @touchcancel="${this.handleTouchEnd}"
                                @click="${(event) =>
                                    !this.editing &&
                                    this.handleClick(event, app.slug, app)}"
                                @mousedown="${(event) =>
                                    this.handleMouseDown(event, index, app)}"
                                @dragstart="${(event) =>
                                    this.handleDragStart(event, index, app)}"
                                @dragend="${(event) =>
                                    this.handleDragEnd(event, index, app)}"
                                @dragover="${(event) =>
                                    this.handleDragOver(event, index, app)}"
                                @dragleave="${(event) =>
                                    this.handleDragLeave(event, index, app)}"
                                @drop="${(event) =>
                                    this.handleDrop(event, index, app)}"
                                draggable="${this.editing ? 'true' : 'false'}"
                            >
                                ${this.editing
                                    ? html`
                                          <span
                                              class="app-grid__remove-icon ${this
                                                  .showRemoveIconId
                                                  ? ''
                                                  : 'hidden'}"
                                              @click="${(event) =>
                                                  this.handleRemove(
                                                      event,
                                                      index,
                                                      app
                                                  )}"
                                          >
                                              <sp-icon-close></sp-icon-close>
                                          </span>
                                      `
                                    : ''}
                                <dt-home-app-icon
                                    class="app-grid__icon"
                                    name="${app.name}"
                                    icon="${app.icon}"
                                ></dt-home-app-icon>
                            </div>
                        `
                    )}
            </div>
        `
    }
}
