import {LitElement, html, css} from 'lit-element';
import mapboxgl from 'mapbox-gl';

export class Sammelstellen extends LitElement {

  map;
  mapStyle;
  sammelstellen;

  static get properties() {
    return {
      mapStyle: {type: String},
      sammelstellen: {type: Object}
    };
  }

  constructor() {
    super();
    this.mapStyle = "";
    this.sammelstellen = [];
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
      console.warn(`Geolocation failed: ${error.message}`)
    });

    this.map.addControl(geolocateControl);
    this.map.on('load', function() {
      geolocateControl.trigger();
    });
  }

  updated(changedProperties) {
    super.updated();
    this.map.setStyle(this.mapStyle);
    this._updateSammelstellen();
  }

  _updateSammelstellen() {
    this.sammelstellen.features.forEach(this._addSammelstelleToMap);
  }

  _addSammelstelleToMap = (sammelstelle) => {

    let markerColor;
    if (sammelstelle.properties.briefkasten) {
      markerColor = '#0c78b2';
    } else {
      markerColor = '#98D800';
    }
    const marker = new mapboxgl.Marker({
      color: markerColor
    });
    marker.getElement().classList.add('SammelstelleMarker');
    marker.getElement().addEventListener('click', ev => {
      this.map.flyTo({
        center: sammelstelle.geometry.coordinates,
        zoom: 15
      })
    });
    marker
        .setLngLat(sammelstelle.geometry.coordinates)
        .setPopup(this.createPopup(sammelstelle))
        .addTo(this.map);
  }

  createPopup(sammelstelle) {
    const popup = new mapboxgl.Popup({
      className: 'SammelstellePopup',
      maxWidth: 'none'
    });
    //popup.setHTML(Mustache.render(Config.popupTemplate, sammelstelle.properties));
    popup.setHTML("Sammelstelle");
    return popup;
  }

}

window.customElements.define('wp-sammelstellen', Sammelstellen);
