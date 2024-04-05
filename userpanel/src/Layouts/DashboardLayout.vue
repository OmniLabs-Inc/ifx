<template>
    <div class="dashboard-bg">
        <section class="dashboardlayout">
            <div class="topbar">
                <div class="logo gap-2">
                    <div class="logo-image">
                        <router-link to="/">
                            <img src="/images/logo.png" alt="logo" class="img-fluid" style="cursor: pointer;width:74px;height:74px;" />
                        </router-link>
                    </div>
                    <!-- <div class="dashboard-info">
                    <span>Dashboar</span>
                </div> -->

                    <div class="menu-logo" @click="sidebarToggle ? sidebarToggle = false : sidebarToggle = true"
                        :style="sidebarToggle ? '' : 'transform:rotate(180deg)'">
                        <img src="/images/dashboard/icon/menu.svg" alt="menu-icon" class="img-fluid"
                            style="max-width: 40px;" />
                    </div>
                    <!--menu-logo-->
                </div>
                <!--logo-->
                <div class="profile-icon d-flex gap-3 align-items-center" @click="hideShow()">
                    <div>
                        <h6 class="m-0 text-white text-end">{{ user?.name }}</h6>
                        <p class="m-0 text-end email">{{ user?.email }}</p>
                        <p class="m-0 text-end">{{ user?.user_unique_id }}</p>
                    </div>
                    <div>
                        <img src="/images/dashboard/profile/user-img2.png" :style="{ width: 40 + 'px', height: 40 + 'px' }"
                            alt="qr-image" class="rounded-circle user-img" />
                    </div>
                </div>

                <div class="show_avatar py-3" v-show="toggle">

                    <ul class="list-unstyled mb-0">
                        <li class="mb-1" @click="$router.push('/profile')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-user">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Profile

                        </li>
                        <li data-bs-toggle="modal" data-bs-target="#logout_modal" data-dismiss="modal"
                            class="text-decoration-none d-flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-log-out">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg> Logout

                        </li>
                    </ul>

                </div>

            </div>
            <!--topbar-->

            <div class="sidebar" :class="sidebarToggle ? '' : 'sidebar-close'">
                <ul class="sidebar-list">
                    <li class="text-nowrap" v-for="(list, index) in SidebarData" :key="index">

                        <router-link :to=list.link v-if="list.children.length == 0">
                            <span v-html="SidebarIcon[list.icon]">
                            </span>

                            {{ list.name }}
                        </router-link>

                        <SubmenuView v-else :index="list.name" :list="list" />

                    </li>

                </ul>
            </div>
            <!--sidebar-->

            <div @click="sidebarMobileClose" class="main-content"
                :class="sidebarToggle ? 'opacity-mobile' : 'main-content-close'">
                <div class="content-box">
                    <slot></slot>
                </div>
            </div>
        </section>
        <!--dashboardlayout-->

        <div class="modal fade" id="logout_modal" tabindex="-1" aria-labelledby="logout_modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0 p-0">
                        <div class="close_box ">
                            <button type="button" id="closelogoutmodal" class="btn-close shadow-none"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="logout_main py-4 text-center">
                            <div class="img_log d-flex align-items-center justify-content-center mb-4 m-auto">
                                <h2>!</h2>
                            </div>
                            <div class=" form_box mb-4">
                                <h3>Please Confirm</h3>
                                <h4>Are you sure you want to logout?</h4>
                            </div>
                            <div class="">
                                <button v-if="loading" type="button" class="btn btn-primary">
                                    <div class="spinner-border text-light" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                                <button v-else type="button" class="btn_log shadow-none border-0 mx-2"
                                    @click="doLogout('logout')">Logout from this
                                    device!</button>
                                <button v-if="loading1" type="button" class="btn btn-primary">
                                    <div class="spinner-border text-light" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                                <button v-else type="button" class="btn_log shadow-none border-0 mx-2"
                                    @click="doLogout('hardlogout')">Logout from all
                                    device!</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Siderbarjson from "../assets/json/Dashboard/Sidebar.json"
import SidebarIcon from "../assets/json/Dashboard/SidebarIcon.js"
import SubmenuView from '../components/UserPanel/SubmenuView.vue';
import ApiClass from "../api/api.js"
export default {
    name: "DashboardLayout",
    components: {
        SubmenuView
    },
    watch: {
        $route(to, from) {
            this.changeStyle(to.fullPath)
        }
    },
    data() {
        return {
            user: '',
            toggle: false,
            sidebarToggle: true,
            SidebarData: Siderbarjson.SidebarData,
            SidebarIcon,
            logout_loading: false,
            exclude: ["/binarytree", "/generation"]
        }
    },

    mounted() {
        this.user = JSON.parse(localStorage.getItem('user'));
        document.addEventListener('click', this.close)
        window.addEventListener("resize", this.onResize);
        this.onResize();
        this.changeStyle(this.$route.fullPath)
    },

    methods: {
        hideShow() {
            this.toggle = !this.toggle
        },

        onResize() {
            if (window.innerWidth <= 768) {
                this.sidebarToggle = false;
                this.logoSmall = true
                return;
            }
        },
        sidebarMobileClose() {
            if (window.innerWidth <= 768) {
                this.sidebarToggle = false;
                return;
            }
        },
        async doLogout(type) {
            this.logout_loading = true;
            let response = await ApiClass.postRequest(type, true);

            if (response?.data) {
                this.logout_loading = false;
                if (response.data.status_code == 0) {
                    this.failed(response.data.message)
                    return
                }
                if (response.data.status_code == 1) {
                    this.success(response.data.message)
                    document.getElementById("closelogoutmodal").click()
                    localStorage.removeItem("token");
                    localStorage.removeItem("user");
                    return this.$router.push("login");
                }
            }
        },
        changeStyle(path) {
            let include = this.exclude.includes(path);
            let element = document.querySelector(".topbar");
            let element2 = document.querySelector(".sidebar");
            if (include) {
                element.style.position = "fixed";
                element2.style.paddingTop = "89px";
                element2.style.zIndex = "99";
            } else {
                element.style.position = "sticky";
                element2.style.paddingTop = "0";
                element2.style.zIndex = "99";
            }
        }
    }
};
</script>

<style scoped>
.dashboard-bg {
    /* background: var(--d-bg); */
    /* background: #1a3156; */
    background :#a8bcdd
}

.dashboardlayout {
    padding: 0;
    min-height: 100vh;
}

.topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    /* background-color: var(--bg-secondary); */
    background-image: linear-gradient(to left bottom, #de4232, #bd1c52, #8a1962, #4e2060, #0d1c4b);
    padding: 15px 10px;
    position: sticky;
    width: 100%;
    z-index: 999;
    top: 0;
}

.logo {
    display: flex;
    align-items: center;
    position: relative;
    width: 245px;
    justify-content: space-between;
    transition: all 0.3s ease;
}

.logo-image img {
    max-width: 230px;
}

.menu-logo {
    transition: all 0.3s ease;
    cursor: pointer;
}

.dashboard-info span {
    color: var(--white);
    font-size: var(--fs-16);
    font-weight: 500;
}


.sidebar {
    /* background-color: var(--bg-secondary); */
    background-image: linear-gradient(to left, #bd1c52, #8a1962, #4e2060);
    /* background-image: linear-gradient(to left, #8a1962, #4e2060); */
    left: 0;
    min-height: 100vh;
    width: 260px;
    position: fixed;
    height: calc(100vh - 9rem);
    z-index: 999;
    overflow-y: auto;
    user-select: none;
    /* top: 60px; */
    border-radius: 5px;
    /* transition: all 0.5s ease; */


    /* padding-top: 55px;
    z-index: 99; */

}

.sidebar-close {
    width: 60px;
}

.sidebar-list {
    padding: 0;
    margin-bottom: 0;
    margin-top: 35px;
}

.sidebar-list li {
    margin-bottom: 5px;
    vertical-align: middle;
}

.sidebar-list li a {
    color: var(--white);
    text-decoration: none;
    font-size: var(--fs-14);
    font-weight: 500;
    text-transform: capitalize;
    display: inline-block;
    width: 100%;
    padding: 10px 10px;
    padding-left: 25px;
}

.sidebar-list li a span {
    margin-right: 15px;
}

.sidebar-list li a span svg {
    color: var(--white);
}

.main-content {
    margin-left: 260px;
    display: flex;
    flex-direction: column;
    padding: 40px 2rem 2rem 2rem;
    transition: margin-left 0.2s;
}

.main-content-close {
    margin-left: 60px !important;
}

.margin-show {
    margin-left: 270px;
}

.margin-hide {
    margin-left: 62px;
}

::-webkit-scrollbar {
    width: 2px;
    border-radius: 10px;
}

/* Track */
::-webkit-scrollbar-track {
    background: var(--white);
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: var(--white);
}

.profile-icon {
    position: relative;
    cursor: pointer;
}

.profile-icon p {
    color: var(--white);
    font-size: var(--fs-14);


}

.show_avatar {
    position: absolute;
    bottom: -114px;
    right: 25px;
    background: var(--bg-secondary);
    color: var(--white);
    box-shadow: var(--card-shadow);
    border-radius: 7px;
    width: 190px;
    border: 1px solid var(--d-bg);
}

.show_avatar ul li {
    color: var(--grey);
    font-size: var(--fs-14);
    padding: 7px 12px;
    transition: all 0.2s ease-in-out;
    cursor: pointer;

}

.show_avatar ul li:hover {
    color: var(--white);
    background: var(--d-bg);
}

.show_avatar ul li a {
    color: var(--grey);
    font-size: var(--fs-14);
}

.show_avatar ul li:hover a {
    color: var(--white);
    font-size: var(--fs-14);
}

/* Modal CSS*/
.modal-content {
    background-color: var(--bg-secondary);
}



.form_box h4 {
    font-size: var(--fs-19);
    color: var(--white);
}

.form_box h3 {
    font-size: var(--fs-22);
    color: var(--white);
    font-weight: 500;
    line-height: 28px;
}

.close_box {
    background: var(--white);
    position: absolute;
    right: -8px;
    top: -8px;
    color: var(--black) !important;
    height: 30px;
    width: 30px;
    border-radius: 50%;
}

.btn-close {
    opacity: 1 !important;
    margin: 0 !important;
    padding: 9px !important;
    font-size: 12px !important;
}

.btn_log {
    background: var(--gradient);
    color: var(--white);
    border: 1px solid var(--text-blue);
    font-size: var(--fs-14);
    font-weight: 500;
    min-width: 100px;
    border-radius: 6px;
    padding: 6px 20px;
}

.img_log {
    height: 90px;
    width: 90px;
    border-radius: 50%;
    border: 3px solid var(--sky-blue);
}

.img_log h2 {
    color: var(--sky-blue);
    font-weight: 500;
    font-size: var(--fs-55);
}

@media all and (min-width: 320px) and (max-width:768px) {
    .main-content-close {
        margin-left: 0px !important;

    }

    .opacity-mobile {
        opacity: 0.3;
    }

    .sidebar-close {
        width: 0px !important;
    }

    .main-content {
        margin-left: 0px !important
    }

    .logo-image {
        width: unset;
    }

    .main-content {
        padding: 2rem 1rem 1rem 1rem;
    }
    .profile-icon .email{
        display:none;
    }
    .menu-logo{

        margin-right:20px;
    }

}
</style>
