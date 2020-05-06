import {LitElement} from "lit-element";
import {html} from 'lit-html';

class SammelstellenSammelstelle extends LitElement {

    static get properties() {
        return {
            sammelstelle: {type: Object}
        };
    }

    render() {
        let oeffnungszeiten = '';
        if (this.sammelstelle.oeffnungszeiten) {
            oeffnungszeiten = html`<li>Öffnungszeiten: ${this.sammelstelle.oeffnungszeiten}</li>`;
        }
        let hinweise = '';
        if (this.sammelstelle.hinweise) {
            hinweise = html`<li>${this.sammelstelle.hinweise}</li>`;
        }
        let website = '';
        if (this.sammelstelle.website) {
            website = html`<li><a href="${this.sammelstelle.website}" target="_blank" rel="noopener noreferer">Website der Sammelstelle</a></li>`;
        }
        if (this.sammelstelle.briefkasten) {
            return html`
                <h1>Radentscheid-Briefkasten</h1>
                <p class="hinweis-briefkasten">Privater Briefkasten als Einwurfstelle für Unterschriftenlisten</p>
                <ul>
                    <li>${this.sammelstelle.name}</li>
                    <li>${this.sammelstelle.adresse}</li>
                    ${oeffnungszeiten}
                    ${hinweise}
                    ${website}
                </ul>
            `;
        }
        return html`
                <h1>${this.sammelstelle.name}</h1>
                <ul>
                    <li>${this.sammelstelle.adresse}</li>
                    ${oeffnungszeiten}
                    ${hinweise}
                    ${website}
                </ul>
            `;
    }

    createRenderRoot() {
        return this;
    }
}

window.customElements.define("sammelstellen-sammelstelle", SammelstellenSammelstelle);