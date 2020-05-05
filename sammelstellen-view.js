import {LitElement, html} from "lit-element";
import "./sammelstellen-map";

class SammelstellenView extends LitElement {

    static get properties() {
        return {
            mapStyle: {type: String},
            sammelstellen: {type: Object}
        }
    }

    constructor() {
        super();
        this.mapStyle = "";
        this.sammelstellen = {"type":"FeatureCollection","features":[]};
    }

    render() {
        return html`<sammelstellen-map mapStyle=${this.mapStyle} .sammelstellen="${this.sammelstellen}"/>`
    }

    firstUpdated(_changedProperties) {
        super.firstUpdated(_changedProperties);
        this._loadSammelstellen();
    }

    _loadSammelstellen() {
        fetch('/wp-json/sammelstellen/v1/sammelstellen')
            .then(response => response.json())
            .then((data) => this.sammelstellen = data);
    }
}

window.customElements.define("sammelstellen-view", SammelstellenView);
