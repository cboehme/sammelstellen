import {LitElement} from "lit-element";
import {html} from 'lit-html';

class SammelstellenSammelstelle extends LitElement {

    static get properties() {
        return {
            sammelstelle: {type: Object}
        };
    }

    render() {
        if (this.sammelstelle.briefkasten) {
            return html`
                <h1>Radentscheid-Briefkasten</h1>
                <p class="hinweis-briefkasten">Privater Briefkasten als Einwurfstelle für Unterschriftenlisten</p>
                <ul>
                    <li>${this.sammelstelle.name}</li>
                    <li>${this.sammelstelle.adresse}</li>
                    ${this._renderOeffnungszeiten()}
                    ${this._renderHinweise()}
                    ${this._renderWebsite()}
                </ul>
            `;
        }
        return html`
                <h1>${this.sammelstelle.name}</h1>
                <ul>
                    <li>${this.sammelstelle.adresse}</li>
                    ${this._renderOeffnungszeiten()}
                    ${this._renderHinweise()}
                    ${this._renderWebsite()}
                </ul>
            `;
    }

    _renderOeffnungszeiten() {
        if (this.sammelstelle.oeffnungszeiten) {
            return html`<li>Öffnungszeiten: ${this.sammelstelle.oeffnungszeiten}</li>`;
        }
        return '';
    }

    _renderHinweise() {
        if (this.sammelstelle.hinweise) {
            return html`<li>${this.sammelstelle.hinweise}</li>`;
        }
        return '';
    }

    _renderWebsite() {
        if (this.sammelstelle.website) {
            return html`<li><a href="${this.sammelstelle.website}" target="_blank" rel="noopener noreferer">Website der Sammelstelle</a></li>`;
        }
        return '';
    }

    createRenderRoot() {
        return this;
    }
}

window.customElements.define("sammelstellen-sammelstelle", SammelstellenSammelstelle);