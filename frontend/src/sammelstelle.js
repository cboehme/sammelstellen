import {html} from 'htm/preact';

export default function Sammelstelle({
        sammelstelle: {briefkasten, name, adresse, oeffnungszeiten, hinweise, website},
        onClick = () => {}}) {

    if (briefkasten) {
        return html`
            <article onclick="${onClick}">
                <h2>Radentscheid-Briefkasten</h2>
                <p class="hinweis-briefkasten">Privater Briefkasten als Einwurfstelle für Unterschriftenlisten</p>
                <ul>
                    <li>${name}</li>
                    <li>${adresse}</li>
                    <${Oeffnungszeiten} oeffnungszeiten="${(oeffnungszeiten)}"/>
                    <${Hinweise} hinweise="${hinweise}"/>
                    <${Website} website="${website}"/>
                </ul>
            </article>`;
    }
    return html`
        <article onclick="${onClick}">
            <h2>${name}</h2>
            <ul>
                <li>${adresse}</li>
                <${Oeffnungszeiten} oeffnungszeiten="${(oeffnungszeiten)}"/>
                <${Hinweise} hinweise="${hinweise}"/>
                <${Website} website="${website}"/>
            </ul>
        </article>`;
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
