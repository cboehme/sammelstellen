import {LitElement, html, css} from 'lit-element';
import mapboxgl from 'mapbox-gl';

export class Sammelstellen extends LitElement {

  static get properties() {
    return {
      mapStyle: {type: String}
    };
  }

  static get styles() {
    return css`
      :host {
        display: block;
        position: absolute;
        top: 0px;
        bottom: 0px;
        left: 0px;
        right: 0px;
      }
      #map {
	    position: absolute;
        top: 0px;
	    bottom: 0px;
	    left: 0px;
        right: 0px;
      }`;
  }

  render() {
    return html`<link href='./node_modules/mapbox-gl/dist/mapbox-gl.css' rel='stylesheet'/><div id="map"></div>`;
  }

  firstUpdated() {
    super.connectedCallback();
    const containerElement = this.renderRoot.getElementById('map');
    this._createMap(containerElement);
  }

  _createMap(containerElement) {
    this.map = new mapboxgl.Map({
      container: containerElement,
      center: [7.1006600, 50.7358510],
      zoom: 12
    }).on('load', function () {
      const mapSource = this.getSource("openmaptiles");
      this.setMaxBounds(mapSource.bounds);
    });
    this.map.resize();
    this._addNavigationControl();
    this._addGeolocateControl();
  }

  _addNavigationControl() {
    this.map.addControl(new mapboxgl.NavigationControl({
      visualizePitch: true
    }));
  }

  _addGeolocateControl() {
    const geolocateControl = new mapboxgl.GeolocateControl({
      positionOptions: {
        enableHighAccuracy: true
      },
      showAccuracyCircle: false
    });
    geolocateControl.on('error', (error) => {
      console.log(`Geolocation failed: ${error.message}`)
    });

    this.map.addControl(geolocateControl);
    this.map.on('load', function() {
      geolocateControl.trigger();
    });
  }

  updated(updatedProperties) {
    super.updated();
    this.map.setStyle(this.mapStyle);
  }

}

window.customElements.define('wp-sammelstellen', Sammelstellen);
