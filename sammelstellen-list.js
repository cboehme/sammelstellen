import {LitElement} from "lit-element";
import {html} from 'lit-html';

import "./sammelstellen-sammelstelle";

class SammelstellenList extends LitElement {

    static get properties() {
        return {
            sammelstellen: {type: Object}
        }
    }

    constructor() {
        super();
        this.sammelstellen = {"type": "FeatureCollection", "features": []};
    }

    render() {
        const sammelstellenList = this.sammelstellen.features.map(sammelstelle =>
            html`<sammelstellen-sammelstelle .sammelstelle="${sammelstelle.properties}"/>`
        );
        return html`<ol>${sammelstellenList}</ol>`;
    }
}

window.customElements.define("sammelstellen-list", SammelstellenList);