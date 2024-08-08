import { MenuItem } from '@spectrum-web-components/menu'
import { css } from 'lit'
import { customElement } from 'lit-element'

@customElement('dt-app-menu-item')
export class AppMenuItem extends MenuItem {
    static get styles() {
        return [
            super.styles,
            css`
                :host(:hover) {
                    background-color: hsl(209, 72%, 87%);
                    border-left: 2px solid hsla(221, 94%, 47%, 1);
                }
            `,
        ]
    }
}
