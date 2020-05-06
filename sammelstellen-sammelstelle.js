import {LitElement} from "lit-element";
import {html} from 'lit-html';

class SammelstellenSammelstelle extends LitElement {

    static get properties() {
        return {
            sammelstelle: {type: Object}
        };
    }

    render() {
        return html`<h2>${this.sammelstelle.name}</h2>`;
    }
}

window.customElements.define("sammelstellen-sammelstelle", SammelstellenSammelstelle);