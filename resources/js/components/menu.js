import {css, html, LitElement} from 'lit';

class MenuComponent extends LitElement {
  static properties = {
    isOpen: {type: Boolean},
    menuItems: {type: Array}
  };
  static styles = css`
    .menu-button {
      float: inline-end;
    }

    .menu-item:hover {
      background-color: transparent !important;
    }

    sp-button.toggle-button {
      cursor: pointer;
      --system-spectrum-button-accent-background-color-default: #e8e7e7;
      --system-spectrum-button-accent-background-color-hover: #e8e7e7;
      --system-spectrum-button-accent-background-color-down: #e8e7e7;
      --system-spectrum-button-accent-background-color-focus: #e8e7e7;
      --spectrum-focus-indicator-color: #e8e7e7;
      --spectrum-component-pill-edge-to-text-100: 0px;
      --spectrum-button-minimum-width-multiplier: 0px;
    }

    .inline-element {
      display: inline-block;
      vertical-align: top;
    }

    sp-icon-triple-gripper {
      color: hsla(198, 45%, 28%, 1);
      --spectrum-icon-size: 25px;
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
      margin: 3px 0px;
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
      display: flex;
    }

    sp-menu-item:hover {
      color: transparent !important;
    }

    :host(:hover) sp-icon-triple-gripper {
      color: #326A82;
    }

    .right-aligned-menu a {
      text-decoration: none !important;
      color: #222 !important;
    }

    .right-aligned-menu a:hover {
      text-decoration: none !important;
      color: rgb(7, 130, 235) !important;
    }

    .menu-title:hover {
      --spectrum-menu-item-label-content-color-hover: rgb(7, 130, 235);
    }

    .menu-item {
      --spectrum-menu-item-label-content-color-hover: rgb(7, 130, 235);
    }
  `;

  constructor() {
    super();
    this.isOpen = false;
    this.menuItems = [];
  }


  togglePopover() {
    this.isOpen = !this.isOpen;
  }

  render() {
    return html`
      <sp-button
        id="trigger"
        placement="right"
        class="menu-button inline-element menu-icon toggle-button ${this.isOpen ? 'active' : ''}"
        @click="${this.togglePopover}"
      >
        <sp-icon-triple-gripper class="menu-icon" slot="icon"></sp-icon-triple-gripper>
      </sp-button>

      ${this.isOpen ? html`
        <sp-overlay open trigger="trigger@click" placement="bottom" style="position: relative">
          <sp-popover .open="${this.isOpen}">
            <sp-dialog>
              <sp-menu class="right-aligned-menu">
                <a href="/">
                  <sp-menu-item class="menu-item">Go to
                    disciple.tools
                  </sp-menu-item>
                </a>
                ${this.menuItems.map(item => html`
                  <a href="${item.href}" class="menu-set">
                    <sp-menu-item class="menu-item">${item.label}</sp-menu-item>
                  </a>`)}
              </sp-menu>
            </sp-dialog>
          </sp-popover>
        </sp-overlay>
      ` : ''}
    `;
  }
}

customElements.define('menu-component', MenuComponent);
