import {css, html, LitElement} from 'lit';
import {customElement} from "lit-element";
import {property, queryAll} from "lit/decorators.js";

/**
 * Custom element representing an application grid.
 */

@customElement('dt-home-app-grid')
class AppGrid extends LitElement {

    @property({type: Array}) appData = [];
    @property({type: Number}) selectedIndex = -1;
    @property({type: String}) appUrl = '';
    @queryAll('.app-grid__item') items = [];

    showRemoveIconId = null;
    clickTimer = null;
    clickDelay = 300;
    longPressTimer = 200;
    longPressDuration = 500;

    /**
     * Styles for the AppGrid element.
     * @static
     * @type {CSSResult}
     */
    static styles = css`
        /* CSS styles here */

        .app-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 16px;
            justify-items: center;
        }

        .app-grid__item {
            position: relative;
            width: 100%;
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
    `;

    /**
     * Lifecycle callback when the element is connected to the DOM.
     */
    connectedCallback() {
        super.connectedCallback();
        this.loadAppData();
        document.addEventListener('click', this.handleDocumentClick);
        document.addEventListener('mousedown', this.handleMouseDown);
        document.addEventListener('mouseup', this.handleMouseUp);
        document.addEventListener('mouseleave', this.handleMouseLeave);
    }

    /**
     * Adds the drag-over class to the app.
     * @param {DragEvent} event - The drag event.
     */
    handleDragOver(event) {
        event.preventDefault()
        event.target.classList.add('app-grid__item--over');
    }

    /**
     * Removes the drag-over class from the app.
     */
    handleDragLeave(event) {
        event.target.classList.remove('app-grid__item--over');
    }

    /**
     * Handles the drag event by setting the data being dragged.
     * @param {DragEvent} event - The drag event.
     * @param {number} index - The index of the dragged item.
     */
    handleDragStart(event, index) {
        this.showRemoveIconId = null;
        event.dataTransfer.setData("text/plain", index);
    }

    handleDragEnd(event) {
        this.items.forEach(item => {
            item.classList.remove('app-grid__item--over')
            item.classList.remove('app-grid__item--dragging')
        });
    }

    /**
     * Handles the drop event by reordering the apps.
     * @param {DragEvent} event - The drop event.
     */

    handleDrop(event) {
        const fromIndex = event.dataTransfer.getData("text/plain");
        const toIndex = event.target.dataset.index;
        this.reorderApps(fromIndex, toIndex);

        // Call handleDocumentClick to ensure immediate removal of context menu icon
        this.handleDocumentClick(event);
    }

    /**
     * Handles a single click event on an app.
     *
     * @param {Event} event - The click event.
     * @param {number} index - The index of the clicked app.
     *
     * @return {void}
     */
    handleSingleClick(event, index) {
        if (this.showRemoveIconId === null && this.clickTimer === null && this.longPressTimer === null) {
            this.clickTimer = setTimeout(() => {
                // Your single click logic here
                const selectedApp = this.appData[index];
                if (selectedApp && selectedApp.slug) {
                    window.location.href = `/home/app/${selectedApp.slug}`;
                }
                this.showRemoveIconId = null;
                this.requestUpdate();
                this.clickTimer = null;
            }, this.clickDelay);
        }
    }

    /**
     * Handles a double click event on an app.
     * @param event
     * @param {number} index - The index of the double-clicked app.
     * @param {object} app - The app object.
     */
    handleDoubleClick(event, index, {id}) {
        clearTimeout(this.clickTimer);
        this.clickTimer = null;
        // Your double click logic here
        this.showRemoveIconId = id;
        this.requestUpdate();
    }

    /**
     * Handles removal of an app.
     * @param event
     * @param {number} index - The index of the app to be removed.
     * @param {object} app - The app object.
     */
    handleRemove(event, index, {id}) {
        this.postAppDataToServer(id);
        this.appData.splice(index, 1);
        this.selectedIndex = -1;
        this.showRemoveIconId = null;
    }

    /**
     * Handles a click outside the context menu.
     * @param {MouseEvent} event - The click event.
     */
    handleDocumentClick = (event) => {
        // Check if a long press has occurred
        if (this.longPressOccured) {
            // Reset the flag
            this.longPressOccured = false;
            return;
        }
        // Check if the click is outside the context menu
        const removeIcon = this.shadowRoot.querySelector('.app-grid__remove-icon');
        if (removeIcon && !removeIcon.contains(event.target)) {
            this.showRemoveIconId = null;
            this.isDragging = false;
            this.requestUpdate();
        }
    }


    /**
     * Reorders apps based on drag and drop.
     * @param {number} fromIndex - The index from which the app is dragged.
     * @param {number} toIndex - The index to which the app is dropped.
     */
    reorderApps(fromIndex, toIndex) {
        const appsCopy = [...this.appData];
        const [removedApp] = appsCopy.splice(fromIndex, 1);
        appsCopy.splice(toIndex, 0, removedApp);
        this.appData = appsCopy;
        this.postNewOrderToServer();
    }

    /**
     * Posts the new order of apps to the server.
     */
    postNewOrderToServer() {
        const url = this.appUrl + "/update-app-order"; // Your API endpoint
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(this.appData),
        })
            .then(response => response.json())
            .then(data => {
                console.log('Order update successful:', data);
            })
            .catch((error) => {
                console.error('Error updating order:', error);
            });
    }

    /**
     * Loads application data from an external source.
     */
    loadAppData() {
        // Fetch your data from an external source or set it from an attribute
        // For this example, let's assume it's set from a JSON attribute
        const jsonData = this.getAttribute('app-data');
        this.appUrl = this.getAttribute('app-url');

        if (jsonData) {
            this.appData = JSON.parse(jsonData);
        }
    }


    /**
     * Posts data of the app to be hidden to the server.
     * @param {string} appId - The ID of the app to be hidden.
     */
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
     * Handles mouse down event on an app.
     * @param {MouseEvent} event - The mouse down event.
     * @param {number} index - The index of the app.
     */
    handleMouseDown = (event, index, id) => {
        let appGridItem = event.target.closest('.app-grid__item');
        if (!appGridItem) {
            // If the target itself isn't an app grid item, check its parents
            appGridItem = event.target.parentElement.closest('.app-grid__item');
        }
        if (appGridItem) {
            this.longPressTimer = setTimeout(() => {
                this.showContextMenu(id);
                // Set a flag to indicate that a long press has occurred
                this.longPressOccured = true;
            }, this.longPressDuration);
        }
    }

    /**
     * Handles mouse up event.
     */
    handleMouseUp = () => {
        clearTimeout(this.longPressTimer);
        this.longPressTimer = null;
    }

    /**
     * Handles mouse leave event.
     * @param {number} index - The index of the app.
     */
    handleMouseLeave = (index) => {
        clearTimeout(this.longPressTimer);
        // Do something with the index if needed
    }

    /**
     * Shows context menu for the app.
     * @param {number} index - The index of the app.
     */
    showContextMenu(id) {
        // Your logic to show the context menu
        // For example, you can set a property to indicate which index's context menu to show
        this.showRemoveIconId = id;
        this.requestUpdate();
    }

    /**
     * Renders the AppGrid element.
     * @returns {TemplateResult} HTML template result.
     */
    render() {
        return html`
            <div class="app-grid">
                ${this.appData.map((app, index) => app.is_hidden !== 1 ? html`
                    <div class="app-grid__item"
                         data-index="${index}"
                         @click="${(event) => this.handleSingleClick(event, index, app)}"
                         @dblclick="${(event) => this.handleDoubleClick(event, index, app)}"
                         @mousedown="${(event) => this.handleMouseDown(event, index, app)}"
                         @dragstart="${(event) => this.handleDragStart(event, index, app)}"
                         @dragend="${(event) => this.handleDragEnd(event, index, app)}"
                         @dragover="${(event) => this.handleDragOver(event, index, app)}"
                         @dragleave="${(event) => this.handleDragLeave(event, index, app)}"
                         @drop="${(event) => this.handleDrop(event, index, app)}"
                         draggable="true">
                        ${this.showRemoveIconId === app.id
                                ? html`<span class="app-grid__remove-icon"
                                             @click="${(event) => this.handleRemove(event, index, app)}"><sp-icon-close></sp-icon-close></span>`
                                : ''}
                        <dt-home-app-icon class="app-grid__icon"
                                          name="${app.name}"
                                          icon="${app.icon}"></dt-home-app-icon>
                    </div>` : '')}
            </div>
        `;
    }
}