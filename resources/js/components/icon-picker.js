import { css, html, LitElement } from 'lit'
import { property } from 'lit/decorators.js'
import { customElement } from 'lit-element'
import '@spectrum-web-components/search/sp-search.js'
import '@spectrum-web-components/icons-workflow/icons/sp-icon-close.js'

@customElement('icon-picker')
class IconPicker extends LitElement {
    @property({ type: Boolean }) showIconPicker = false
    @property({ type: String }) iconSearchQuery = ''
    @property({ type: Array }) materialIcons = []
    @property({ type: String }) name = ''
    @property({ type: String }) label = ''
    @property({ type: String }) placeholder = ''
    @property({ type: Boolean }) required = false
    @property({ type: String, reflect: true }) value = ''

    static styles = css`
        .icon-loader {
            position: absolute;
            z-index: 1000;
            background: white;
            //border: 1px solid #d1d1d1;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.2);
            border-radius: 4px;
            margin-right: 20px;
            margin-top: -15px;
            width: 90%;
        }

        .icon-picker {
            padding-left: 10px;
            margin-top: 0;
            overflow-y: auto;
            max-height: 150px;
        }

        .icon-table {
            width: 100%;
        }

        .icon-table td {
            padding: 10px;
            //text-align: center;
        }

        .icon-table i {
            font-size: 20px; /* Icon Size */
            cursor: pointer;
        }

        .icon-picker i:hover {
            opacity: 0.25;
            cursor: pointer;
        }

        .icon-search {
            margin: 10px;
        }

        .dt__icon {
            display: flex;
        }

        .selected-icon {
            margin-top: 25px;
            font-size: 20px;
            margin-left: 5px;
            width: 20px;
        }

        .upload-icon {
            top: 20px;
            left: 6px;
            --system-spectrum-actionbutton-background-color-default: #cac4c4;
            --system-spectrum-actionbutton-background-color-hover: #cac4c4;
            --system-spectrum-actionbutton-background-color-down: #cac4c4;
            --highcontrast-actionbutton-content-color-default: #ffffff;
            --spectrum-neutral-content-color-hover: #ffffff;
            --spectrum-neutral-content-color-down: #ffffff;
            --mod-actionbutton-height: 41px;
            --spectrum-action-button-edge-to-hold-icon-medium: 10px;
        }

        .upload-icon .sp-upload-icon {
            color: #0a0a0a;
            font-size: 20px;
        }

        .close-icon {
            float: right;
            margin: 10px;
            cursor: pointer;
            pointer-events: all !important;
        }

        .icon-input {
            width: 80% !important;
            float: left;
        }
    `

    constructor() {
        super()
        this.loadIcons()
    }

    connectedCallback() {
        super.connectedCallback()
        this.loadIcons()
    }

    disconnectedCallback() {
        super.disconnectedCallback()
    }

    buildIconClassNameList() {
        const iconClassNames = []
        Array.from(document.styleSheets).forEach((styleSheet) => {
            if (
                styleSheet.href &&
                styleSheet.href.includes(
                    'dt-core/dependencies/mdi/css/materialdesignicons.min.css'
                )
            ) {
                Array.from(styleSheet.cssRules).forEach((rule) => {
                    if (rule.constructor.name === 'CSSStyleRule') {
                        iconClassNames.push({
                            class: rule.selectorText.substring(
                                1,
                                rule.selectorText.indexOf(':')
                            ),
                        })
                    }
                })
            }
        })
        return iconClassNames
    }

    async loadIcons() {
        try {
            const iconClassNames = this.buildIconClassNameList()
            this.materialIcons = iconClassNames
                .map((icon) => icon.class)
                .filter((className) => className !== 'mdi')
        } catch (error) {
            console.error('Error loading icons:', error)
        }
    }

    toggleIconPicker() {
        this.showIconPicker = !this.showIconPicker
        if (!this.showIconPicker) {
            this.iconSearchQuery = ''
        }
    }

    updateIconSearch(e) {
        this.iconSearchQuery = e.target.value.toLowerCase()
    }

    selectIcon(icon) {
        this.value = `mdi ${icon}`
        this.showIconPicker = false
        this.iconSearchQuery = ''
    }

    handleInputChange(e) {
        this.value = e.target.value
    }

    closeIconPicker() {
        this.showIconPicker = false
        this.iconSearchQuery = ''
    }

    get filteredIcons() {
        return this.materialIcons
            .filter((icon) => icon.includes(this.iconSearchQuery))
            .slice(0, 40)
    }

    get selectedIcon() {
        return this.value
    }

    renderIconPicker() {
        if (!this.showIconPicker) return null

        return html`
            <sp-search
                class="icon-search"
                @input="${this.updateIconSearch}"
                size="s"
                quiet
            ></sp-search>
            <sp-icon-close
                @click="${this.closeIconPicker}"
                class="close-icon"
            ></sp-icon-close>
            <div class="icon-picker">
                <table class="icon-table">
                    <tbody>
                        ${this.filteredIcons.length > 0
                            ? this.filteredIcons
                                  .reduce((rows, icon, index) => {
                                      if (index % 8 === 0) rows.push([]) // Start a new row after every 8 icons
                                      rows[rows.length - 1].push(icon) // Add the icon to the current row
                                      return rows
                                  }, [])
                                  .map(
                                      (row) => html`
                                          <tr>
                                              ${row.map(
                                                  (icon) => html`
                                                      <td>
                                                          <i
                                                              @click="${() =>
                                                                  this.selectIcon(
                                                                      icon
                                                                  )}"
                                                              class="mdi ${icon}"
                                                          ></i>
                                                      </td>
                                                  `
                                              )}
                                          </tr>
                                      `
                                  )
                            : html` <tr>
                                  <td colspan="8">No Icons Found</td>
                              </tr>`}
                    </tbody>
                </table>
            </div>
        `
    }

    render() {
        return html`
            <style>
                @import url('https://cdn.jsdelivr.net/npm/@mdi/font@6.6.96/css/materialdesignicons.min.css');
            </style>
            <div class="dt__icon">
                <dt-text
                    class="icon-input"
                    label="${this.label}"
                    name="${this.name}"
                    placeholder="${this.placeholder}"
                    ?required="${this.required}"
                    @change="${this.handleInputChange}"
                    .value="${this.value}"
                ></dt-text>
                <span class="selected-icon">
                    <i class="mdi ${this.selectedIcon}"></i>
                </span>

                <sp-action-button
                    class="upload-icon button change-icon-button"
                    @click="${this.toggleIconPicker}"
                >
                    <sp-icon-upload-to-cloud
                        class="sp-upload-icon"
                    ></sp-icon-upload-to-cloud>
                </sp-action-button>
            </div>
            <div class="icon-loader">${this.renderIconPicker()}</div>
        `
    }
}
