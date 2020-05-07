import {css, LitElement} from "lit-element";
import {html} from 'lit-html';

import {SammelstelleSelectedEvent} from './events';

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
            html`<sammelstellen-sammelstelle 
                    .sammelstelle="${sammelstelle.properties}" 
                    @click="${() => this.sammelstelleSelected(sammelstelle.properties.id)}"/>`);
    }

    sammelstelleSelected(sammelstelleId) {
        this.dispatchEvent(new SammelstelleSelectedEvent(sammelstelleId));
    }

}

window.customElements.define("sammelstellen-list", SammelstellenList);