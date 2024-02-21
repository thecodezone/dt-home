// Import LitElement base class and html helper function
import {css, html, LitElement} from 'lit';
import {property} from 'lit/decorators.js';

class HomeFooter extends LitElement {
  @property({type: Object})
  translations = {
    hiddenAppsLabel: 'Hidden apps',
  };

  static get styles() {
    return css`
      .footer-container {
        padding: 5px;
        box-sizing: border-box;
        display: flex;
        justify-content: center;
      }

      .footer-button {
        display: inline-block;
        margin: 0px;
        padding: 4px 56px;
        font-size: 15px;
        border: 2px solid rgb(0, 123, 255);
        background-color: rgb(255, 255, 255);
        color: rgb(0, 123, 255);
        text-decoration: none;
        border-radius: 2px;
        transition: background-color 0.3s ease 0s, color 0.3s ease 0s;
        white-space: nowrap;
      }

      .footer-button:hover {
        background-color: #007bff;
        color: #ffffff;
        cursor: pointer;
      }

      sp-action-menu {
        margin-left: auto;
        font-size: 18px;
        border-radius: 10px;
        --mod-actionbutton-padding: 10px 20px;
      }

      :host {
        --mod-actionbutton-border-radius: 7px;
        font-weight: 100;
        color: #3F729B;
        --mod-actionbutton-background-color-default: #3F729B;
        --mod-actionbutton-content-color-default: #ffff !important;
        --mod-actionbutton-height: 40px;
        --mod-actionbutton-edge-to-text: 20px;
        --mod-popover-border-color: #f8f6f6;
        --mod-popover-corner-radius: 5px;
      }

      @media (hover: hover) {
        :host(:hover) {
          --mod-actionbutton-background-color-hover: #3F729B;
          --mod-actionbutton-content-color-hover: #ffff;
          --mod-popover-border-color: #f8f6f6;
        }
      }
    `;
  }

  render() {
    const currentUrl = window.location.href; // Gets the current URL

    return html`
      <div class="footer-container">

        <sp-action-menu><span slot="label">&nbsp;  ${this.translations.hiddenAppsLabel}</span>
          <sp-menu-item class="footer-button">
            <a href="${currentUrl}/hidden-apps" variant="cta">
              ${this.translations.hiddenAppsLabel}
            </a>
          </sp-menu-item>
        </sp-action-menu>
      </div>
    `;
  }
}

customElements.define('dt-home-footer', HomeFooter);
