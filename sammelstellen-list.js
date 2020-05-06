import {css, LitElement} from "lit-element";
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
        return this.sammelstellen.features.map(sammelstelle =>
            html`<sammelstellen-sammelstelle .sammelstelle="${sammelstelle.properties}"/>`);
    }
}

window.customElements.define("sammelstellen-list", SammelstellenList);