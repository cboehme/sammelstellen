import {css, LitElement} from "lit-element";
import {html} from 'lit-html';

import "./sammelstellen-map";
import "./sammelstellen-list";

class SammelstellenView extends LitElement {

    static get properties() {
        return {
            mapStyle: {type: String},
            sammelstellen: {type: Object},
            selected: {type: String}
        }
    }

    constructor() {
        super();
        this.mapStyle = "";
        this.sammelstellen = {"type": "FeatureCollection", "features": []};
        this.selected = null;
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
            <sammelstellen-map mapStyle=${this.mapStyle} 
                               .sammelstellen="${this.sammelstellen}" 
                               selected="${this.selected}"></sammelstellen-map>
            <sammelstellen-list .sammelstellen="${this.sammelstellen}" 
                                @sammelstelle-selected="${this.sammelstelleSelected}"></sammelstellen-list>
        `;
    }

    sammelstelleSelected(event) {
        this.selected = event.sammelstelleId;
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
