import "@disciple.tools/web-components";

/**
 * Import Spectrum Web Components
 */
import '@spectrum-web-components/theme/sp-theme.js';
import '@spectrum-web-components/theme/src/themes.js';
import '@spectrum-web-components/menu/sp-menu.js';
import '@spectrum-web-components/menu/sp-menu-group.js';
import '@spectrum-web-components/menu/sp-menu-item.js';
import '@spectrum-web-components/menu/sp-menu-divider.js';
import '@spectrum-web-components/overlay/sp-overlay.js';
import '@spectrum-web-components/icon/sp-icon.js';
import '@spectrum-web-components/icons-ui/icons/sp-icon-triple-gripper.js';
import '@spectrum-web-components/action-group/sp-action-group.js';
import '@spectrum-web-components/button/sp-clear-button.js';
import '@spectrum-web-components/button/sp-close-button.js';
import "@spectrum-web-components/progress-circle/sp-progress-circle.js";
import '@spectrum-web-components/action-menu/sp-action-menu.js';
import '@spectrum-web-components/tooltip/sp-tooltip.js';
import '@spectrum-web-components/icons-workflow/icons/sp-icon-help.js';
import '@spectrum-web-components/overlay/sync/overlay-trigger.js';
import '@spectrum-web-components/action-menu/sp-action-menu.js';
import '@spectrum-web-components/tooltip/sp-tooltip.js';
import '@spectrum-web-components/icons-workflow/icons/sp-icon-help.js';
import '@spectrum-web-components/overlay/sync/overlay-trigger.js';
import "./components/form-submit.js"

/**
 * Imports
 */
import {loaded} from "./helpers.js";

import "./components/app-grid.js";
import "./components/hidden-app-grid.js";
import "./components/home-footer.js";
import "./components/menu.js";
import "./components/training-video.js"

/**
 * CSS
 */
import "../css/plugin.css";

/**
 * Bootstrap the application
 */
loaded(() => {
  document.body.classList.add("dom-loaded");
});
