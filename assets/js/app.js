// Webpack imports
import '../css/app.scss';


/* Routing Setup */
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js'
const routes = require('../../public/js/fos_js_routes.json');

Routing.setRoutingData(routes);

export { Routing };

Vue.prototype.Routing = Routing;



/* Vue Setup */
import Vue from "vue";
import SearchBar from "../js/components/SearchBar.vue";
import ReservationControls from "../js/components/ReservationControls.vue";
import SearchBarResults from "../js/components/SearchBarResults.vue";
import BookList from "../js/components/BookList.vue";
import BookListItem from "../js/components/BookListItem.vue";

Vue.component("searchbar", SearchBar);
Vue.component("searchbar-results", SearchBarResults);
Vue.component("reserve-controls", ReservationControls);
Vue.component("book-list", BookList);
Vue.component("book-list-item", BookListItem);


export const bus = new Vue();

const app = new Vue({
    el: "#app",
});


export const addClickEventsToArray = (arr, fn) => {
    if (arr.length > 0 && arr !== null && arr instanceof NodeList) {
        arr.forEach(item => {
            item.addEventListener("click", fn);
        });
    }
}
