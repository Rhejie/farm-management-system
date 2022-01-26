
import Vue from 'vue';

require('./bootstrap');


axios.defaults.headers.common["X-CSRF-TOKEN"] = window.Laravel.csrfToken;
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

const files = require.context("./", true, /\.vue$/i);
files.keys().map(key =>
    Vue.component(
        key
            .split("/")
            .pop()
            .split(".")[0],
        files(key).default
    )
);

import VueGeolocation from 'vue-browser-geolocation';
Vue.use(VueGeolocation);

import VueQrcodeReader from "vue-qrcode-reader";
Vue.use(VueQrcodeReader);

import * as VueGoogleMaps from 'vue2-google-maps'
Vue.use(VueGoogleMaps, {
    load: {
      key: 'AIzaSyDgh7OHtp1OWtnwRB1PRJHFCv141RlxAmM',
      libraries: 'places',
    },
    installComponents: true
})

import html2canvas from 'html2canvas';

import 'leaflet/dist/leaflet.css';

import ElementUI from "element-ui";
import "element-ui/lib/theme-chalk/index.css";
import "element-ui/lib/theme-chalk/display.css";
import locale from "element-ui/lib/locale/lang/en";

import EventDispatcher from "./service/EventDispatcher.js";
import API from "./api/index.js";
import DateFormatter from "./service/DateFormatter";


import VueHtmlToPaper from 'vue-html-to-paper';


const options = {
  name: '_blank',
  specs: [
    'fullscreen=yes',
    'titlebar=yes',
    'scrollbars=yes'
  ],
  styles: [
    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
    'https://unpkg.com/kidlat-css/css/kidlat.css',
    '/vendors/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
    '/vendors/plugins/fontawesome-free/css/all.min.css',
    '/css/app.css',
  ],
  timeout: 1000, // default timeout before the print window appears
  autoClose: true, // if false, the window will not close after printing
  windowTitle: window.document.title, // override the window title
}

Vue.use(VueHtmlToPaper, options);

import router from "./router";

Vue.prototype.$EventDispatcher = new EventDispatcher();
Vue.prototype.$API = API;
Vue.prototype.$df = new DateFormatter();

Vue.use(ElementUI, { locale, size: "small" });

Vue.config.productionTip = false;
const app = new Vue({
    el: '#app',
    router,
});



function saveimg() {
    alert('asdasd')
}
