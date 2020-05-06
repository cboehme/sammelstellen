import {css, LitElement} from "lit-element";
import {html} from 'lit-html';

import "./sammelstellen-map";
import "./sammelstellen-list";

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
        this.sammelstellen = {"type": "FeatureCollection", "features": []};
    }

    static get styles() {
        return css`
            sammelstellen-map {
                position: fixed;
                top: 0px;
                bottom: 0px;
                left: 50%;
                right: 0px;
            }
            sammelstellen-list {
                display: block;
                width: 50%;
            }`;
    }

    render() {
        return html`
            <sammelstellen-map mapStyle=${this.mapStyle} .sammelstellen="${this.sammelstellen}"></sammelstellen-map>
            <sammelstellen-list .sammelstellen="${this.sammelstellen}"></sammelstellen-list>
        `;
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
