import { MenuItem } from '@spectrum-web-components/menu'
import { css } from 'lit'
import { customElement } from 'lit-element'

@customElement('dt-app-menu-item')
export class AppMenuItem extends MenuItem {
    static get styles() {
        return [
            super.styles,
            css`
                :host {
                    border-left: 2px solid transparent;
                }

                :host(:hover) {
                    background-color: hsl(209, 72%, 87%);
                    border-color: hsla(221, 94%, 47%, 1);
                }
            `,
        ]
    }
}
