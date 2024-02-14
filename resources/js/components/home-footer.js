// Import LitElement base class and html helper function
import {LitElement, html, css} from 'lit';
import {isInstalled, isAndroid} from '../helpers.js';
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
        `;
    }

    render() {
        const currentUrl = window.location.href; // Gets the current URL

        return html`
            <div class="footer-container">

                <sp-action-menu>
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
