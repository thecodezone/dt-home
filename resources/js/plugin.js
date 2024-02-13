import "@disciple.tools/web-components";
import '@spectrum-web-components/menu/sp-menu.js';
import '@spectrum-web-components/menu/sp-menu-group.js';
import '@spectrum-web-components/menu/sp-menu-item.js';
import '@spectrum-web-components/menu/sp-menu-divider.js';
import '@spectrum-web-components/overlay/sp-overlay.js';
import '@spectrum-web-components/icon/sp-icon.js';
import '@spectrum-web-components/icons-ui/icons/sp-icon-triple-gripper.js';
import '@spectrum-web-components/action-group/sp-action-group.js';
import '@spectrum-web-components/button/sp-button.js';
import '@spectrum-web-components/button/sp-clear-button.js';
import '@spectrum-web-components/button/sp-close-button.js';
import "@spectrum-web-components/progress-circle/sp-progress-circle.js";

import { loaded } from "./helpers.js";

import "../css/plugin.css";

import "./app-grid.js";
import "./hidden-app-grid.js";
import "./home-footer.js";
import "./menu.js";
import "./training-video.js";

loaded(() => {
    document.body.classList.add("dom-loaded");
});