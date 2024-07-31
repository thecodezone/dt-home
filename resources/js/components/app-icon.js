import {css, html, LitElement} from 'lit'
import {customElement, property} from 'lit-element'

/**
 * Represents an application icon component.
 *
 * @extends LitElement
 */
@customElement('dt-home-app-icon')
class AppIcon extends LitElement {
  @property({type: String}) name = ''
  @property({type: String}) icon = ''
  @property({type: Boolean}) isVisible = true

  static properties = {
    name: {type: String},
    icon: {type: String},
    isVisible: {type: Boolean},
  }

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
      pointer-events: none;
    }

    .app-icon__name {
      font-size: 14px;
      color: #333;
      text-align: center;
      white-space: nowrap;
      width: 100%;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  `

  /**
   * Creates a new instance of the constructor.
   *
   * @constructor
   * @classdesc This constructor initializes a new object with the following properties:
   *      - name: A string representing the name of the object.
   *      - icon: A string representing the icon of the object.
   *      - isVisible: A boolean indicating the visibility of the object.
   *
   * @returns {void} This constructor does not return a value.
   */
  constructor() {
    super()
    this.name = ''
    this.icon = ''
    this.isVisible = true
  }

  /**
   * Renders the app icon.
   * @returns {html} - The rendered HTML for the app icon.
   */
  render() {
    return this.isVisible
      ? html`
        <div class="app-icon__container">
          <div class="app-icon__icon">
            <img src="${this.icon}"/>
          </div>
          <span class="app-icon__name">${this.name}</span>
        </div>
      `
      : html``
  }
}
