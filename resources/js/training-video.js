import { LitElement, html } from 'lit';
import { property } from 'lit/decorators.js';

class VideoList extends LitElement {
  @property({ type: Array })
  trainingData = [];

  connectedCallback() {
    super.connectedCallback();
    const data = this.getAttribute('training-data');
    if (data) {
      try {
        this.trainingData = JSON.parse(data);
      } catch (e) {
        console.error('Error parsing training data:', e);
        this.trainingData = [];
      }
    }
  }

  handleVideoClick(training) {
    // Concatenate 'training' before the anchor value
    history.pushState({}, '', `training#${training.anchor}`);
  }


  render() {
    return html`
    <div>
      ${this.trainingData.map(training => html`
        <div id=${training.anchor}>
          ${training.name}<br>
          ${this.renderIframe(training.embed_video)}

          <br>
        </div>
      `)}
    </div>
  `;
  }

  renderIframe(embedCode) {
    const template = document.createElement('template');
    embedCode = embedCode.trim();
    template.innerHTML = embedCode;
    return html`${template.content}`;
  }

  /*renderIframe(videoUrl) {
    return html`
      ${videoUrl}
  `;
  }*/

}

customElements.define('video-list', VideoList);
