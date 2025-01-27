import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
//theme
import "primevue/resources/themes/lara-light-indigo/theme.css";

//core
import "primevue/resources/primevue.min.css";
import './assets/scss/main.scss'
//BOOTSTRAP
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
//SKELTOR
import { Skeletor } from "vue-skeletor";
//PAGINATION
import Pagination from 'v-pagination-3'

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

//Prime

import PrimeVue from 'primevue/config';

import VueTree from "@ssthouse/vue3-tree-chart";
import "@ssthouse/vue3-tree-chart/dist/vue3-tree-chart.css";




const mixins = {
  methods: {
    toastMessage: function (icon, message) {
      this.$swal({
        title: message,
        position: "top-end",
        icon,
        toast: true,
        timer: "3000",
        showConfirmButton: false,
      });
    },
    success: function (message) {
      this.$swal({
        title: message,
        position: "top-end",
        icon: "success",
        toast: true,
        timer: "3000",
        showConfirmButton: false,
      });
    },
    failed: function (message) {
      this.$swal({
        title: message,
        position: "top-end",
        icon: "error",
        toast: true,
        timer: "3000",
        showConfirmButton: false,
      });
    },
    onHandleKeyPress: function (e, decimal) {
      // ==============  Event charCode  =================
      // (DOT) = 46, backspace = 8, 0 =  48, 1 =  49, 2 =	50, 3 =	51, 4 =	52, 5 =	53, 6 =	54, 7 =	55, 8 =	56 , 9 = 57
      let res = e.charCode != 8 && ((e.charCode >= 48 && e.charCode <= 57) || (e.charCode == 46 && e.target.value.indexOf(".") == -1));
      if ((e.charCode != 46 && e.target.value.indexOf(".") == -1)) {
        e.target.value = e.target.value.replace(/^0+/, "");
      }
      if ((e.charCode == 46) && (decimal > 0) && (e.target.value.indexOf(".") == -1)) {
        e.target.value = `${e.target.value.slice(0, e.target.selectionStart)}.${e.target.value.slice(e.target.selectionStart, e.target.value.length).slice(0, decimal)}`;
        res = false;
      }
      if (decimal != null && res && e.target.value.indexOf(".") != -1) {
        res = ((e.target.value.split(".")[1].length < parseInt(decimal)) || (e.target.value.slice(0, e.target.selectionStart).indexOf(".") == -1));
      }
      if (e.key == "0" && (e.target.selectionStart == "0" || e.target.selectionStart == "1") && e.target.value.indexOf(0) == "0") {
        e.preventDefault()
      }
      if (e.charCode != 46 && (e.charCode >= 48 && e.charCode <= 57) && e.target.value.slice(0, e.target.selectionStart) == 0 && e.target.selectionStart == 1) {
        e.target.value = `${e.key}${e.target.value.slice(e.target.selectionStart, e.target.value.length)}`;
        e.preventDefault()
      }
      // Only for Zero Decimal value of pair and currency
      if (e.charCode == 46 && decimal == 0) {
        e.preventDefault();
      }
      // if user directly entry dot without entering zero at zeroth place
      if (e.charCode == 46 && decimal > 0 && e.target.value == ".") {
        e.target.value = "0.";
        e.preventDefault();
      }

      //if value gt>1 does not enter 0 at zero index
      if (e.target.value > 1 && e.target.selectionStart == "0" && e.key == "0") {
        e.preventDefault()
      }
      return res ? res : e.preventDefault();
    },

    onHandlePaste: function (e, d) {
      let vvv = e.clipboardData.getData('Text');


      if (isNaN(vvv)) {  // if string
        e.preventDefault();
        return false;
      }
      vvv = Math.abs(vvv).toString();
      if (vvv.indexOf(".") == -1) { // if not include decimal
        e.target.value = vvv;
        e.preventDefault();
        return true;
      }

      if (vvv.indexOf(".") > -1) {

        if (d == 0) {
          vvv = vvv.replace('.', '').toString();
        }

        let s = (d == 0) ? vvv.replace('.', '') : vvv.split('.');
        e.target.value = (d == 0) ? s : `${s[0]}.${s[1].slice(0, d)}`;
        e.preventDefault()
        return false;
      }

    },

    onHandleKeyUp: function (e) {

      if ((e.keyCode === 8 || e.keyCode == 46) && !e.target.value.includes(".") && e.target.value.indexOf("0") == 0) {
        e.target.value = e.target.value != "" ? parseInt(e.target.value) : "";
      }

      if ((e.keyCode === 8 || e.keyCode == 46) && (e.target.value.includes(".")) && (e.target.value.indexOf("0") == 0 && e.target.selectionStart == 0)) {
        e.target.value = e.target.value != "" ? parseFloat(e.target.value) : "";
      }
    },

    onHandleKeyDown: function (e) {
      // we are using it for stop ctrl+z
      if (e.ctrlKey && e.which === 90) {
        e.preventDefault(); return false;
      }
    },


  }
};


createApp(App).use(store).use(router).component(Skeletor.name, Skeletor).component("vue-tree", VueTree).component('pagination', Pagination).use(VueSweetalert2).use(PrimeVue).mixin(mixins).mount('#app')
