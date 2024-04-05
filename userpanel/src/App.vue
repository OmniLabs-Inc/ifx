<template>
  <div v-if="this.$route?.meta?.authOnly">
    <DashboardLayout>
      <router-view />
    </DashboardLayout>
  </div>
  <div v-else>
    <NavbarComponent v-if="this.$route.name == 'HomeView'" />
    <router-view />
    <FooterComponent v-if="this.$route.name == 'HomeView'" />
  </div>
</template>

<script>
// import Navbar from "@/components/Home/Navbar.vue"
// import Footer from "@/components/Home/Footer.vue"
import NavbarComponent from './components/Home/NavbarComponent.vue';
// import HeaderComponent from './components/HeaderComponent.vue';
import FooterComponent from './components/FooterComponent.vue';
import DashboardLayout from './Layouts/DashboardLayout.vue';
import AOS from 'aos';
export default {
  name: "App",
  components: {
    // Navbar,
    // Footer
    DashboardLayout,
    NavbarComponent,
    FooterComponent
  }
}
AOS.init();
</script>

<style>
#app {
  font-family: Poppins, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

@font-face {
  font-family: Poppins;
  src: url(../src/assets/fonts/Poppins-ExtraBold.ttf);
  font-weight: 800;
}

@font-face {
  font-family: Poppins;
  src: url(../src/assets/fonts/Poppins-Bold.ttf);
  font-weight: 700;
}

@font-face {
  font-family: Poppins;
  src: url(../src/assets/fonts/Poppins-SemiBold.ttf);
  font-weight: 600;
}

@font-face {
  font-family: Poppins;
  src: url(../src/assets/fonts/Poppins-Medium.ttf);
  font-weight: 500;
}

@font-face {
  font-family: Poppins;
  src: url(../src/assets/fonts/Poppins-Regular.ttf);
  font-weight: 400;
}


:root {
  --black: #000;
  --white: #fff;
  --background-color: #060606;
  --yellow: #FF9B00;
  --blue-border: #72A9BE;
  --linear-gradient: linear-gradient(116.93deg, rgba(67, 177, 221, 0.24) 10.02%, rgba(0, 102, 142, 0.24) 83.04%);
  --purple-border: #B23B75;
  --purple-gradient: linear-gradient(114.49deg, rgba(55, 27, 34, 0.24) 15.1%, rgba(0, 0, 0, 0.24) 88.32%);
  --yellow-gradient: linear-gradient(112.23deg, rgba(222, 205, 47, 0.24) 15.22%, rgba(111, 100, 10, 0.24) 87.09%);
  --yellow-border: #AC9F4D;
  --light-purple: #9978C2;
  --light-green: #AFCB7F;
  --light-red: #E17979;
  --light-lime: #7FDDE7;



  /* fonts  */
  --fs-8: 0.5rem;
  --fs-10: 0.625rem;
  --fs-12: 0.75rem;
  --fs-13: 0.813rem;
  --fs-14: 0.875rem;
  --fs-15: 0.9375rem;
  --fs-16: 1rem;
  --fs-17: 1.063;
  --fs-18: 1.125rem;
  --fs-19: 1.188rem;
  --fs-20: 1.25rem;
  --fs-22: 1.375rem;
  --fs-24: 1.5rem;
  --fs-25: 1.5625rem;
  --fs-28:1.75rem;
  --fs-30: 1.875rem;
  --fs-33: 2.0625rem;
  --fs-35: 2.188rem;
  --fs-45: 2.8125rem;
  --fs-50: 3.125rem;
  --fs-55: 3.438rem;
}

section.homesection {
  padding: 70px 0px;
  background-color: var(--background-color);
  overflow: hidden;
}

.submit-btn {
  background-color: var(--yellow);
  color: var(--white);
  font-size: var(--fs-17);
  color: var(--white);
  font-weight: 500;
  border: 1px solid var(--yellow);
  padding: 10px 25px;
  border-radius: 4px;
  position: relative;
}

.submit-btn:after {
  position: absolute;
  content: '';
  width: 100%;
  height: 100%;
  border: 1px solid var(--yellow);
  top: 8px;
  left: 8px;
  border-radius: 4px;
}

/*======== SKELTOR LOADER CSS START ========*/
.vue-skeletor {
  position: relative;
  overflow: hidden;
  background-color: rgba(0, 0, 0, 0.12);
}

.vue-skeletor:not(.vue-skeletor--shimmerless):after {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  transform: translateX(-100%);
  background-image: linear-gradient(90deg,
      rgba(255, 255, 255, 0),
      rgba(255, 255, 255, 0.3),
      rgba(37, 22, 22, 0));
  animation: shimmer 1.5s infinite;
  content: "";
}

.vue-skeletor--rect,
.vue-skeletor--circle {
  display: block;
}

.vue-skeletor--circle {
  border-radius: 50%;
}

.vue-skeletor--pill,
.vue-skeletor--text {
  border-radius: 9999px;
}

.vue-skeletor--text {
  line-height: 1;
  display: inline-block;
  width: 100%;
  height: inherit;
  vertical-align: middle;
  top: -1px;
}

@keyframes shimmer {
  100% {
    transform: translateX(100%);
  }
}

/*======== SKELTOR LOADER CSS FINISH ========*/

/* ===========CUSTOM CARD CSS START============== */
.cus_card {
  /* background: var(--bg-secondary); */
  /* background-image: linear-gradient(to right, #de4232, #bd1c52, #8a1962, #4e2060, #0d1c4b); */
  /* background-image: linear-gradient(to left, #bd1c52, #8a1962, #4e2060); */
  background-image: linear-gradient(to left, #8a1962, #4e2060);
  color: var(--white);
  padding: 35px 35px;
  box-shadow: var(--card-shadow);
  border-radius: 7px;
}

/* ===========CUSTOM CARD CSS FINISH============== */


/* ===========PAGINATION CSS START============== */

.pagination {
  justify-content: end !important;
  background-color: transparent !important;
}

.page-link {
  z-index: 3 !important;
  color: var(--purpal) !important;
  background-color: transparent !important;
  box-shadow: none !important;
  border-radius: 2px;
  border-color: var(--border) !important;
  font-size: var(--fs-15) !important;
}

.page-item.active .page-link {
  z-index: 3 !important;
  color: var(--white) !important;
  box-shadow: none !important;
  background-color: var(--sky-blue) !important;
  border-color: var(--sky-blue) !important;
}

.pagination_box ul li {
  border: none !important;
  margin-bottom: 20px !important;
  margin-bottom: 0 !important;
  color: var(--white);
}

.page-item:first-child .page-link,
.page-item:last-child .page-link {
  box-shadow: none !important;
}

.VuePagination__pagination {
  box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
  background-color: var(--bg-secondary);
  color: var(--place-holder);
  gap: 4px;
}

.VuePagination nav p {
  color: var(--white);
  font-size: var(--fs-14);
}

/* ===========PAGINATION CSS FINISH============== */

/* ===========TABLE CSS START============== */

.cus_card-1 {
  background: var(--bg-secondary);
  color: var(--white);
  box-shadow: var(--card-shadow);
  border-radius: 7px;
}

.activation-income .activation-card-header {
  background: var(--bg-secondary);
}

.activation-income .activation-card-header h2 {
  text-transform: capitalize;
  font-size: var(--fs-18);
  color: var(--white);
  font-weight: 600;
}

.activation-income .form-control:focus {
  border-color: var(--place-holder);
}

.activation-income .form-control {
  color: var(--place-holder);
  border: 1px solid var(--place-holder);
  font-size: var(--fs-14);
  padding: 10px 20px;
}

.activation-income .input-group {
  box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
  background-color: var(--d-bg);
}

.activation-income .input-group-text {
  border: 1px solid var(--place-holder);
}

.activation-income .activation-card-head th {
  text-transform: capitalize;
  color: var(--td-color);
  font-weight: 600;
  font-size: var(--fs-15);
}

.activation-income .activation-card-data td {
  color: var(--white);
  font-size: var(--fs-14);
  white-space: nowrap;
}

.activation-income .activation-card-data:hover {
  background-color: var(--d-bg);
  box-shadow: rgb(167 167 167 / 6%) 0px 0px 20px 0px;
  transition: all 0.5s;
}

.activation-income .table>:not(caption)>*>* {
  padding: 20px;
  border-bottom-width: 1px;
  border-color: #5e505026;
}

.activation-income input::placeholder {
  color: var(--place-holder);
}

/* ===========TABLE CSS FINISH============== */
.router-link-active {
  /* background: #1a3156 !important; */
  /* background: #a8bcdd !important; */
}

.text-search-input {
  background-color: transparent !important;
  font-size: 13px !important;
  color: var(--white) !important;
  outline: unset !important;
  box-shadow: unset !important;
}

.search-select .form-select {
  background-color: transparent !important;
  font-size: 13px !important;
  color: var(--white) !important;
  outline: unset !important;
  box-shadow: unset !important;
}

.search-select .form-select option {
  background: var(--d-bg) !important;
  color: var(--white);
}

.table>:not(caption)>*>* {
    padding: 0.5rem 0.5rem;
    color: var(--bs-table-color-state,var(--bs-table-color-type,var(--bs-table-color)));
    background-color: black !important;
    border-bottom-width: var(--bs-border-width);
    box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state,var(--bs-table-bg-type,var(--bs-table-accent-bg)));
}

@media all and (min-width:320px) and (max-width:767px) {
  section {
    padding: 50px 0px;
  }
}



/* limit select  css start*/
.limit_option select option
{
background: var(--bg-secondary);
}
.limit_option{
  line-height: 2;
}
/* limit select  css end*/
.tree-container {
    width: 100% !important;
    /* border: 1px solid var(--active) !important; */
    overflow: scroll !important;
    direction:ltr ;
    
}
 
@media all and (min-width: 320px) and (max-width:767px) {

  .dom-container {
      /* transform: scale(1) translate(175px, -120px) !important;
      transform-origin: center center;  */
      /* direction:rtl !important; */
      

  
  }
  /* svg.svg.vue-tree {
      transform: scale(1) translate(175px, -120px) !important; 
      transform-origin: center center;
      direction:ltr !important;
  }
  .text-inline{
    text-align: center !important;
  }
  .rich-media-node img{
  
  width: 30px !important;
  height: 30px !important;
  }
  .rich-media-node span{

    font-size: 10px !important;
  }


  .node-slot {
    width: 120px !important;
  }*/

  .activation-income .activation-card-header h2 {
    font-size: var(--fs-16);
    margin-bottom: 10px !important;
  } 
}

</style>