import {render} from 'preact';
import {html} from 'htm/preact';
import Sammelstellen from "./sammelstellen";

const App = html`<${Sammelstellen} mapStyle="http://localhost:8080/styles/positron/style.json" shrinkAt="160"/>`;
render(App, document.getElementsByTagName("main")[0]);

/*
class SammelstellenViewerComponent extends HTMLElement {

    constructor() {
        super();

        const shadowDOM = this.attachShadow({mode: open});
        const App = html`<${Sammelstellen} mapStyle="http://localhost:8080/styles/positron/style.json" shrinkAt="160"/>`;
        render(App, shadowDOM);
    }

}

window.customElements.define("sammelstellen-viewer", SammelstellenViewerComponent);
*/