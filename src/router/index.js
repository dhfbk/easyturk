import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import creazioneProgetto from "../views/creazioneProgetto.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home
  },
  {
    path: "/create",
    name: "nuovoProgetto",
    component: creazioneProgetto
  }
];

const router = new VueRouter({
  routes
});

export default router;
