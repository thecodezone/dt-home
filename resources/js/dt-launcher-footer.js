// Import LitElement base class and html helper function
import { LitElement, html, css } from 'lit';
import '@spectrum-web-components/button/sp-button.js';
import '@spectrum-web-components/button/sp-clear-button.js';
import '@spectrum-web-components/button/sp-close-button.js';
import "@spectrum-web-components/progress-circle/sp-progress-circle.js";
import { isInstalled } from './helpers.js';

class DtLauncherFooter extends LitElement {
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

    `;
  }

  render() {
    const currentUrl = window.location.href; // Gets the current URL
    return html`
      <div class="footer-container">
        ${!isInstalled() ? html`
          <a href="${currentUrl}/training" variant="cta">
            <sp-button class="footer-button" variant="cta"> Install as App</sp-button>
          </a>
        `: ''}
        <sp-button class="footer-button" variant="cta">Hidden App</sp-button>
      </div>
    `;
  }
}

customElements.define('dt-launcher-footer', DtLauncherFooter);

