import { Menu } from '@spectrum-web-components/menu'
import { css } from 'lit'
import { customElement } from 'lit-element'

@customElement('dt-app-menu')
export class AppMenu extends Menu {
    static get styles() {
        return [
            super.styles,
            css`
                :host {
                    display: block;
                }
            `,
        ]
    }
}
