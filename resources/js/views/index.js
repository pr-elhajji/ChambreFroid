import Vue from "vue";
import id from "vue2-datepicker/locale/es/id";
// Use it to import all Vue file containing this folder as Components
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component("home", require("./home").default);

Vue.component("pages-404", require("./utility/404").default);
Vue.component("pages-500", require("./utility/500").default);
Vue.component("pages-maintenance", require("./utility/maintenance").default);

//Tableau de bord
Vue.component("dashboard", require("./dashboard/dashboard-simple").default);

// Chambre
Vue.component("chambres", require("./chambres/chambres-grid").default);
Vue.component("chambre-details", require("./chambres/chambre-details").default);
Vue.component("chambres-list", require("./chambres/chambres-list").default);

//varietes

Vue.component("varietes-list", require("./varietes/varietes-list").default);

//Users
Vue.component( "users-profile",require("./users/users-profile").default);

Vue.component("login", require("./account/login").default);
Vue.component("register", require("./account/register").default);
Vue.component("forgot-password", require("./account/forgot-password").default);

Vue.component("reset-password", require("./account/reset-password").default);

//Configuration
Vue.component("configuration", require("./configuration").default);

//report
Vue.component("report", require("./reports/report").default);


