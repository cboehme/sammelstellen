import {render} from 'preact';
import {html} from 'htm/preact';
import Sammelstellen from "./sammelstellen";

export default function embedSammelstellen(container, src, mapStyle, startPushRight, compactMap) {
    const App = html`
        <${Sammelstellen} src=${src} 
                          mapStyle=${mapStyle}
                          startPushRight=${startPushRight} 
                          compactMap=${compactMap}/>`;
    render(App, container);
}