import { css, html, LitElement } from 'lit'
import '@spectrum-web-components/tooltip/sp-tooltip.js'
import '@spectrum-web-components/icons-workflow/icons/sp-icon-help.js'
import { Overlay } from '@spectrum-web-components/overlay'
import '@spectrum-web-components/overlay/overlay-trigger.js'
import { customElement } from 'lit-element'
import { property } from 'lit/decorators.js'

@customElement('dt-home-tooltip')
class TooltipButton extends LitElement {
    @property({ type: Object })
    translations = {
        helpText: 'Copy this link and share it with people you are coaching',
    }

    static styles = css`
        :host {
            display: inline-block;
            position: relative;
        }

        sp-tooltip {
            --spectrum-tooltip-background-color: #6d6d6d;
            --spectrum-tooltip-text-color: white;
            font-size: 14px;
            padding: 10px;
        }

        sp-icon-help {
            margin-right: 5px;
        }

        .icon-wrapper {
            color: #3fab3f;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }
    `

    constructor() {
        super()
        this.overlay = null
        this.tooltipTimeout = null
    }

    // Method to show the tooltip using Overlay.open()
    async showTooltip() {
        const tooltip = document.createElement('sp-tooltip')
        tooltip.textContent = this.translations.helpText
        // Tooltip message
        tooltip.slot = 'hover-content'
        tooltip.open = true

        const options = {
            placement: 'right',
            trigger: this.shadowRoot.querySelector('.icon-wrapper'),
            type: 'auto',
        }

        this.overlay = await Overlay.open(tooltip, options)
        this.shadowRoot.appendChild(this.overlay)
    }

    // Method to hide the tooltip slowly
    hideTooltip() {
        if (this.overlay) {
            this.tooltipTimeout = setTimeout(() => {
                if (this.overlay) {
                    this.overlay.remove()
                    this.overlay = null
                }
            }, 3000) // 3-second delay for slow hiding
        }
    }

    render() {
        return html`
            <overlay-trigger placement="right" offset="10">
                <div
                    class="icon-wrapper"
                    slot="trigger"
                    @mouseenter="${this.showTooltip}"
                    @mouseleave="${this.hideTooltip}"
                >
                    <sp-icon-help></sp-icon-help>
                    <!-- Help icon added -->
                </div>

                <!-- Tooltip slot via overlay-trigger -->
            </overlay-trigger>
        `
    }
}
