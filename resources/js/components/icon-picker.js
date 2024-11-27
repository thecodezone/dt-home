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
        :host {
            --dt-fields-background-color: #f2f2f2;
            --system-spectrum-actionbutton-background-color-default: var(
                --dt-fields-background-color
            );
            --system-spectrum-actionbutton-background-color-hover: var(
                --dt-fields-background-color
            );
            --system-spectrum-actionbutton-background-color-down: var(
                --dt-fields-background-color
            );
            --highcontrast-actionbutton-background-color-down: var(
                --dt-fields-background-color
            );
            --highcontrast-actionbutton-content-color-default: #ffffff;
            //--spectrum-neutral-content-color-hover: #ffffff;
            --spectrum-neutral-content-color-down: #ffffff;
            --mod-actionbutton-height: 41px;
            --spectrum-action-button-edge-to-hold-icon-medium: 10px;
        }

        @media (prefers-color-scheme: dark) {
            :host {
                --dt-fields-background-color: #333;
                --upload-icon-color: #ffffff;
            }

            .selected-icon {
                color: #ffffff;
            }

            .icon-picker-icon {
                color: #fff;
            }

            .close-icon {
                color: #fff;
            }

            .icon-search-div {
                background-color: #424242;
            }

            .selected-icon .svg-icon {
                filter: invert(1) hue-rotate(180deg);
            }
        }

        @media (prefers-color-scheme: light) {
            :host {
                --upload-icon-color: #0a0a0a;
            }

            .selected-icon {
                color: #0a0a0a;
            }

            .selected-icon .svg-icon {
                filter: none;
            }
        }

        .icon-loader {
            position: absolute;
            z-index: 1000;
            background: var(--dt-fields-background-color);
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
        }

        .upload-icon .sp-upload-icon {
            color: var(--upload-icon-color);
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

    /**
     * Checks if the icon is a URL.
     * @returns {boolean} - True if the icon is a URL, otherwise false.
     */
    isIconURL() {
        const pattern = new RegExp('^(https?:\\/\\/|\\/)', 'i')
        return pattern.test(this.selectedIcon)
    }

    renderIconPicker() {
        if (!this.showIconPicker) return null

        return html`
            <div class="icon-search-div">
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
            </div>
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
                                                              class="mdi ${icon} icon-picker-icon"
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
                    ${this.isIconURL()
                        ? html`<img
                              style="width:100%"
                              class="svg-icon"
                              src="${this.selectedIcon}"
                          />`
                        : html`<i class="mdi ${this.selectedIcon}"></i>`}
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
