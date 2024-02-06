import {css, html, LitElement} from 'lit';

class AppIcon extends LitElement {
  static properties = {
    name: {type: String},
    icon: {type: String},
    isVisible: {type: Boolean}
  };
  static styles = css`
    .app-icon-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin: 10px;
    }

    .app-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px;
      background-color: #f0f0f0;
      border-radius: 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
      margin-bottom: 8px;
      cursor: pointer;
      transition: transform 0.3s ease;
    }

    .app-icon:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .app-icon img {
      width: 60px;
      height: 60px;
    }

    .app-name {
      font-size: 14px;
      color: #333;
      text-align: center;
      word-wrap: break-word;
      width: 80px;
    }
  `;

  constructor() {
    super();
    this.name = '';
    this.icon = '';
    this.isVisible = true;
  }


  render() {
    return this.isVisible ? html`
      <div class="app-icon-container">
        <div class="app-icon">
          <img src="${this.icon}"/>
        </div>
        <span class="app-name">${this.name}</span>

      </div>
    ` : html``;
  }
}

customElements.define('dt-launcher-app-icon', AppIcon);



