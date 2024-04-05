import { createRouter, createWebHistory } from "vue-router";
// import HomeView from "../views/HomeView.vue";
import LoginView from "../views/Auth/LoginView.vue";
import HomeView from "../views/HomeView.vue"
const routes = [
    // AUTH PAGES ROUTER //

    // {
    //     path: "/",
    //     name: "HomeView",
    //     component: HomeView
    // },
    {
        path: "/login",
        name: "login",
        component: LoginView,
        meta: { authOnly: false }
    },


    {
        path: "/register",
        name: "register",
        component: () =>
            import(
        /* webpackChunkName: "register" */ "../views/Auth/RegisterView.vue"
            ),
        meta: { authOnly: false }

    },
    {
        path: "/forgotpassword",
        name: "forgotpassword",
        component: () =>
            import(
        /* webpackChunkName: "forgotpassword" */ "../views/Auth/ForgotPasswordView.vue"
            ),
        meta: { authOnly: false }

    },
    {
        path: "/setpassword",
        name: "setforgotpassword",
        component: () =>
            import(
        /* webpackChunkName: "forgotpassword" */ "../views/Auth/SetForgotPassword.vue"
            ),
        props: (route) => ({
            email: route.query.email,
            otp: route.query.otp
        }),
        meta: { authOnly: false }

    },
    {
        path: "/otp",
        name: "otp",
        component: () =>
            import(/* webpackChunkName: "otp" */ "../views/Auth/OTPView.vue"),
        props: (route) => ({
            email: route.query.email,
            type: route.query.type
        }),
        meta: { authOnly: false }

    },

    {
        path: "/changepassword",
        name: "changepassword",
        component: () =>
            import(
        /* webpackChunkName: "changepassword" */ "../views/Auth/ChangePasswordView.vue"
            ),
        meta: { authOnly: false }

    },

    //DASHBOARD ROUTES START//
    {
        path: "/dashboard",
        name: "dashboardview",
        component: () =>
            import(
        /* webpackChunkName: "dashboardview" */ "../views/dashboard/DashboardView.vue"
            ),
        meta: { authOnly: true }
    },

    {
        path: "/packages",
        name: "packages",
        component: () =>
            import(
        /* webpackChunkName: "dashboardview" */ "../views/dashboard/Packages.vue"
            ),
        meta: { authOnly: true }
    },

    {
        path: "/edupackages",
        name: "edupackages",
        component: () =>
            import(
        /* webpackChunkName: "dashboardview" */ "../views/dashboard/PackagesEducation.vue"
            ),
        meta: { authOnly: true }
    },


    {
        path: "/profile",
        name: "profile",
        component: () =>
            import(
        /* webpackChunkName: "profile" */ "../views/dashboard/ProfileView.vue"
            ),
        meta: { authOnly: true }
    },

    {
        path: "/code",
        name: "code",
        component: () =>
            import(
                "../views/dashboard/Code.vue"
            ),
        meta: { authOnly: true }
    },
    {
        path: "/code-reedem",
        name: "code-reedem",
        component: () =>
            import(
                "../views/dashboard/CodeReedem.vue"
            ),
        meta: { authOnly: true }
    },

    {
        path: "/withdraw-history",
        name: "WithdrawHistory",
        component: () =>
            import(
                "../views/dashboard/WithdrawHistory.vue"
            ),
        meta: { authOnly: true }
    },
    {
        path: "/binarytree",
        name: "binarytree",
        component: () =>
            import(
        /* webpackChunkName: "profile" */ "../views/dashboard/Binary_tree.vue"
            ),
        meta: { authOnly: true }
    },

    {
        path: "/generation",
        name: "GenereationView",
        component: () =>
            import(
        /* webpackChunkName: "profile" */ "../views/dashboard/GenereationView.vue"
            ),
        meta: { authOnly: true }
    },
    //INCOME ROUTES START//
    {
        path: "/washoutincome",
        name: "washoutincome",
        component: () =>
            import(
        /* webpackChunkName: "activationincome" */ "../views/dashboard/income/WashoutIncome.vue"
            ),
        meta: { authOnly: true }
    },
    {
        path: "/matchingincome",
        name: "MatchingIncome",
        component: () =>
            import(
        /* webpackChunkName: "principalincome" */ "../views/dashboard/income/MatchingIncome.vue"
            ),
        meta: { authOnly: true }
    },
    {
        path: "/levelincome",
        name: "LevelIncome",
        component: () =>
            import(
        /* webpackChunkName: "principalincome" */ "../views/dashboard/income/LevelIncome.vue"
            ),
        meta: { authOnly: true }
    },
    {
        path: "/reward-income",
        name: "RewardIncome",
        component: () =>
            import(
                "../views/dashboard/income/RewardIncome.vue"
            ),
        meta: { authOnly: true }
    },
    {
        path: "/roi_income",
        name: "roiincome",
        component: () =>
            import(
        /* webpackChunkName: "principalincome" */ "../views/dashboard/income/RoiIncome.vue"
            ),
        meta: { authOnly: true }
    },
    {
        path: "/direct_income",
        name: "directincome",
        component: () =>
            import(
        /* webpackChunkName: "principalincome" */ "../views/dashboard/income/DirectIncome.vue"
            ),
        meta: { authOnly: true }
    },

    //INCOME ROUTES FINISH//

    // REPORTS ROUTES START//
    {
        path: "/stake_report",
        name: "stake plan",
        component: () =>
            import(
        /* webpackChunkName: "stakingincome" */ "../views/dashboard/plan/StakePlan.vue"
            ),
        meta: { authOnly: true }
    },


    {
        path: "/depositreport",
        name: "DepositReport",
        component: () =>
            import(
        /* webpackChunkName: "TokensReport" */ "../views/dashboard/Reports/DepositReport.vue"
            ),
        meta: { authOnly: true }
    },
    {
        path: "/team_business",
        name: "Team Business Report",
        component: () =>
            import(
        /* webpackChunkName: "TokensReport" */ "../views/dashboard/Reports/MatchingIncomeHistory.vue"
            ),
        meta: { authOnly: true }
    },
    // {
    //     path: "/walletreport",
    //     name: "USDTWithdWalletReportrawalReport",
    //     component: () =>
    //         import(
    //     /* webpackChunkName: "USDT Withdrawal Report" */ "../views/dashboard/Reports/WalletReport.vue"
    //         ),
    //     meta: { authOnly: true }
    // },

    // {
        //     "name": "wallet report",
        //     "icon": "reports",
        //     "link": "/walletreport"
    // },

    {
        path: "/directreport",
        name: "Direct Report",
        component: () =>
            import(
        /* webpackChunkName: "Fund Report" */ "../views/dashboard/Reports/DirectReport.vue"
            ),
        meta: { authOnly: true }
    },

    {
        path: "/reward-tree",
        name: "Reward Section",
        component: () =>
            import(
        /* webpackChunkName: "Fund Report" */ "../views/dashboard/RewardTree.vue"
            ),
        meta: { authOnly: true }
    },



    {
        path: "/downlinereport",
        name: "Downline Report",
        component: () =>
            import(
        /* webpackChunkName: "Fund Report" */ "../views/dashboard/Reports/DownlineReport.vue"
            ),
        meta: { authOnly: true }
    },

    // REPORTS ROUTES FINISH//

    // SUPPORT ROUTES START//
    {
        path: "/ticketlist",
        name: "TicketList",
        component: () =>
            import(
        /* webpackChunkName: "testview"*/ "../views/dashboard/TicketList.vue"
            ),
        meta: { authOnly: true }
    },
    {
        path: "/ticketmodal",
        name: "TicketModal",
        component: () =>
            import(
        /* webpackChunkName: "testview" */ "../views/dashboard/TicketModal.vue"
            ),
        meta: { authOnly: true }
    },
    // SUPPORT ROUTES FINISH//


    {
        path: "/stake-by-admin",
        name: "stake-by-admin",
        component: () =>
            import(
        /* webpackChunkName: "stake-by-admin" */ "../views/dashboard/StakedByAdmin.vue"
            ),
        meta: { authOnly: true }
    },

    {
        path: "/direct-detail/:id",
        name: "DirectDetail",
        component: () =>
            import(
        /* webpackChunkName: "profile" */ "../views/dashboard/DirectDetail.vue"
            ),
        meta: { authOnly: true }
    },



    // {
    //   path: "/exchange_transfer",
    //   name: "ExchangeTransfer",
    //   component: () =>
    //     import(
    //         /* webpackChunkName: "testview" */ "../views/dashboard/ExchangeTransfer.vue"
    //     ),
    //   meta: { authOnly: true }
    // },

    // {
    //   path: "/swap",
    //   name: "swap",
    //   component: () =>
    //     import(
    //       "../views/dashboard/swap/Swap.vue"
    //     ),
    //   meta: { authOnly: true }
    // },
    // {
    //   path: "/swap-history",
    //   name: "swap history",
    //   component: () =>
    //     import(
    //       "../views/dashboard/swap/SwapHistory.vue"
    //     ),
    //   meta: { authOnly: true }
    // },
    //DASHBOARD ROUTES FINISH//
];

const router = createRouter({
    history: createWebHistory("/"),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.fullPath == "/") {
        return next({ path: "/login" });
        // return next();
    }
    const loggedIn = localStorage.getItem("token");
    const isAuth = to.matched.some((record) => record.meta.authOnly);
    // console.log(to.fullPath)
    if (isAuth && !loggedIn) {
        return next({ path: "/login" });
    } else if (!isAuth && loggedIn) {
        return next({ path: "/dashboard" });
    } else if (!isAuth && !loggedIn) {
        return next();
    }
    next();
});


export default router;
