import {css, LitElement} from 'lit-element';
import {html, render} from 'lit-html';
import mapboxgl from 'mapbox-gl';

import "./sammelstellen-sammelstelle";

export class sammelstellenMap extends LitElement {

  map;

  static get properties() {
    return {
      mapStyle: {type: String},
      sammelstellen: {type: Object},
      selected: {type: String}
    };
  }

  constructor() {
    super();
    this.mapStyle = "";
    this.sammelstellen = {"type": "FeatureCollection", "features": []};
    this.selected = null;
    this.markers = new Map();
  }

  static get styles() {
    return css`
      #map {
	    width: 100%;
	    height: 100%;
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
    this.markers = this._update(this.markers, this.sammelstellen.features,
        this._addSammelstelleToMap,
        this._updateSammelstelleOnMap,
        this._removeSammelstelleFromMap);
  }

  _update(prevSammelstellen, newSammelstellen, addSammelstelle, updateSammelstelle, removeSammelstelle) {

    let nextSammelstellen = new Map();
    newSammelstellen.forEach(sammelstelle => {
      const id = sammelstelle.properties.id;
      if (prevSammelstellen.has(id)) {
        nextSammelstellen.set(id, updateSammelstelle(prevSammelstellen.get(id), sammelstelle));
      } else {
        nextSammelstellen.set(id, addSammelstelle(sammelstelle));
      }
    });
    prevSammelstellen.forEach((_, id) => {
      if (!nextSammelstellen.has(id)) {
        removeSammelstelle(prevSammelstellen.get(id));
      }
    });
    return nextSammelstellen;
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
    if (sammelstelle.properties.id === this.selected) {
      this.openPopup(marker);
      this.flyTo(marker);
    }
    return marker;
  }

  createPopup(sammelstelle) {
    const popup = new mapboxgl.Popup({
      className: 'SammelstellePopup',
      maxWidth: 'none'
    });
    const container = document.createElement("div");
    popup.setDOMContent(container);
    const popupInfo = props => html`<sammelstellen-sammelstelle .sammelstelle="${props}"/>`;
    render(popupInfo(sammelstelle.properties), container);
    return popup;
  }

  _updateSammelstelleOnMap = (marker, sammelstelle) => {

    if (sammelstelle.properties.id === this.selected) {
      this.openPopup(marker);
      this.flyTo(marker);
    } else {
      this.closePopup(marker);
    }
    return marker;
  }

  openPopup(marker) {
    if (!marker.getPopup().isOpen()) {
      marker.togglePopup();
    }
  }

  closePopup(marker) {
    if (marker.getPopup().isOpen()) {
      marker.togglePopup();
    }
  }

  flyTo(marker) {
    this.map.flyTo({
      center: marker.getLngLat(),
      zoom: 15
    });
  }

  _removeSammelstelleFromMap = (marker) => {
    marker.remove();
  }
}

window.customElements.define('sammelstellen-map', sammelstellenMap);
