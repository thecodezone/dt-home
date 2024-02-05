import {css, html, LitElement} from 'lit';
import '@spectrum-web-components/menu/sp-menu.js';
import '@spectrum-web-components/menu/sp-menu-group.js';
import '@spectrum-web-components/menu/sp-menu-item.js';
import '@spectrum-web-components/menu/sp-menu-divider.js';
import '@spectrum-web-components/overlay/sp-overlay.js';
import '@spectrum-web-components/icon/sp-icon.js';
import '@spectrum-web-components/icons-ui/icons/sp-icon-triple-gripper.js';

class MenuComponent extends LitElement {
  static properties = {
    isOpen: {type: Boolean}
  };
  static styles = css`
    .menu-button {
      float: inline-end;
    }

    .inline-element {
      display: inline-block;
      vertical-align: top;
    }

    sp-icon-triple-gripper {
      border: 2px solid #1658a9;
      border-radius: 50%;
      padding: 5px;
      display: inline-block;
    }

    sp-popover {
      background-color: #ffff;
      min-width: 240%;
      margin-left: -202%;
      border: 2px solid #7a76767d;
      padding: 10px;
    }

    .right-aligned-menu sp-menu-item {
      padding: 5px 0px;
      font-weight: 100;
    }

    .menu-title {
      font-weight: 100;
    }

    @media (min-width: 230px) and (max-width: 950px) {
      sp-popover {
        background-color: #ffff;
        min-width: 175%;
        margin-left: -94%;
        border: 2px solid #7a76767d;
        padding: 10px;
      }
    }

    @media (min-width: 750px) and (max-width: 950px) {
      sp-popover {
        background-color: #ffff;
        min-width: 175%;
        margin-left: -152%;
        border: 2px solid #7a76767d;
        padding: 10px;
      }
    }

    .right-aligned-menu {
      text-align: left;
    }


    .toggle-button {
      background-color: #f0f0f0;
      border-radius: 19px;
      display: flex;
    }

    .toggle-button.active {
      background-color: #0782eb;
      border-radius: 19px;
      display: flex;
    }
  `;

  constructor() {
    super();
    this.isOpen = false;
  }

  togglePopover() {
    this.isOpen = !this.isOpen;
  }

  render() {
    return html`
      <sp-button
        id="trigger"
        placement="right"
        class="menu-button inline-element toggle-button ${this.isOpen ? 'active' : ''}"
        @click="${this.togglePopover}"
      >
        <sp-icon-triple-gripper slot="icon"></sp-icon-triple-gripper>
      </sp-button>

      ${this.isOpen ? html`
        <sp-overlay open trigger="trigger@click" placement="bottom" style="position: relative">
          <sp-popover .open="${this.isOpen}">
            <sp-dialog>
              <h4 slot="heading" class="menu-title">Go to disciple.tools</h4>
              <sp-menu class="right-aligned-menu">
                <sp-menu-item>Training</sp-menu-item>
                <sp-menu-item>Log Out</sp-menu-item>
              </sp-menu>
            </sp-dialog>
          </sp-popover>
        </sp-overlay>
      ` : ''}
    `;
  }

}

customElements.define('menu-component', MenuComponent);
