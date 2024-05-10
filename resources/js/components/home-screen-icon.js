import { css, html, LitElement } from 'lit'
import { customElement } from 'lit-element'
import { property } from 'lit/decorators.js'

/**
 * Represents an application icon component.
 *
 * @extends LitElement
 */
@customElement('dt-home-app-icon')
class AppIcon extends LitElement {
    @property({ type: String }) name = ''
    @property({ type: String }) icon = ''
    @property({ type: Boolean }) isVisible = true

    /**
     * CSS styles for the app icon.
     * @typedef {String} appIconContainerStyle
     */
    static styles = css`
        .app-icon__container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .app-icon__icon {
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: auto 60 / 60;
            background-color: #f0f0f0;
            border-radius: 20px;
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            margin-bottom: 8px;
            cursor: pointer;
            transition: transform 0.3s ease;
            pointer-events: none;
        }

        .app-icon__icon:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .app-icon__icon img {
            width: 40%;
        }

        .app-icon__name {
            font-size: 10px;
            color: #333;
            text-align: center;
            word-wrap: break-word;
            width: 80px;
        }
    `

    /**
     * Renders the app icon.
     * @returns {html} - The rendered HTML for the app icon.
     */
    render() {
        return this.isVisible
            ? html`
                  <div class="app-icon__container">
                      <div class="app-icon__icon">
                          <img src="${this.icon}" />
                      </div>
                      <span class="app-icon__name">${this.name}</span>
                  </div>
              `
            : html``
    }
}
