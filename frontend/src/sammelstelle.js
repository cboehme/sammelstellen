import {html} from 'htm/preact';

export default function Sammelstelle({
        sammelstelle: {briefkasten, name, adresse, oeffnungszeiten, hinweise, website, aktiv},
        onClick = () => {}}) {

    if (briefkasten) {
        return html`
            <article class=${aktiv ? "" : "sammelstellen-sammelstelle-inaktiv"} onclick="${onClick}">
                <h2>Radentscheid-Briefkasten</h2>
                <p class="sammelstellen-info-text">Privater Briefkasten als Einwurfstelle für Unterschriftenlisten</p>
                <ul>
                    <li>${name}</li>
                    <${Adresse} adresse="${adresse}"/>
                    <${Oeffnungszeiten} oeffnungszeiten="${oeffnungszeiten}"/>
                    <${Hinweise} hinweise="${hinweise}"/>
                    <${Website} website="${website}"/>
                </ul>
                <${Inaktiv} aktiv=${aktiv}/>
            </article>`;
    }
    return html`
        <article class=${aktiv ? "" : "sammelstellen-sammelstelle-inaktiv"} onclick="${onClick}">
            <h2>${name}</h2>
            <ul>
                <${Adresse} adresse="${adresse}"/>
                <${Oeffnungszeiten} oeffnungszeiten="${oeffnungszeiten}"/>
                <${Hinweise} hinweise="${hinweise}"/>
                <${Website} website="${website}"/>
            </ul>
            <${Inaktiv} aktiv="${aktiv}"/>
        </article>`;
}

function Adresse({adresse}) {

    if (adresse) {
        return html`<li>${adresse}</li>`;
    }
    return '';
}

function Oeffnungszeiten({oeffnungszeiten}) {

    if (oeffnungszeiten) {
        return html`<li>Öffnungszeiten: ${oeffnungszeiten}</li>`;
    }
    return '';
}

function Hinweise({hinweise}) {

    if (hinweise) {
        return html`<li>${hinweise}</li>`;
    }
    return '';
}

function Website({website}) {

    if (website) {
        // We do not propagate onclick events because then
        // clicking on the Website link would also trigger
        // any click actions on the Sammelstelle:
        return html`<li><a href="${website}" 
                           target="_blank" 
                           rel="noopener noreferer" 
                           onclick="${(ev) => ev.stopPropagation()}">Website der Sammelstelle</a></li>`;
    }
    return '';
}

function Inaktiv({aktiv}) {

    if (aktiv !== true) {
        return html`<p class="sammelstellen-info-text">Diese Sammelstelle ist nicht aktiv</p>`;
    }
    return '';
}