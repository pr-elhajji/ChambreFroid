
<script>
import i18n from "../i18n";
import simplebar from "simplebar-vue";
import axios from "axios";


/**
 * Nav-bar Component
 */
export default {
    data() {
        return {
            languages: [
                {
                    flag: "/images/flags/french.jpg",
                    language: "fr",
                    title: "French",
                },

            ],
            lan: i18n.locale,
            text: null,
            flag: null,
            value: null,
            alerts:[],
        };
    },
    components: { simplebar },
    computed: {
        
    },
    mounted() {
        //this.value = this.languages.find((x) => x.language === i18n.locale);
        //this.text = this.value.title;
        //this.flag = this.value.flag;
        this.getdata();
    },
    methods: {
        
        toggleMenu() {
            this.$parent.toggleMenu();
        },
        toggleRightSidebar() {
            this.$parent.toggleRightSidebar();
        },
        initFullScreen() {
            document.body.classList.toggle("fullscreen-enable");
            if (
                !document.fullscreenElement &&
                /* alternative standard method */ !document.mozFullScreenElement &&
                !document.webkitFullscreenElement
            ) {
                // current working methods
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(
                        Element.ALLOW_KEYBOARD_INPUT
                    );
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        },
        setLanguage(locale, country, flag) {
            this.lan = locale;
            this.text = country;
            this.flag = flag;
            i18n.locale = locale;
            localStorage.setItem("locale", locale);
        },
        getdata(){
            // lire les derniers alerts
            let url="/api/chambres/alerts/0/120";
             axios.get(url)
                .then(response => {
                    this.alerts = response.data;
                }).catch(error => console.log(error));
        }
    },
};
</script>

<template>
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box">
                    <a href="/" class="logo logo-dark">
            <span class="logo-sm">
              <img src="/images/cmcp.jpg" alt height="22" />
            </span>
                        <span class="logo-lg">
              <img src="/images/cmcp.jpg" alt height="60" />
            </span>
                    </a>

                    <a href="/" class="logo logo-light">
            <span class="logo-sm">
              <img src="/images/cmcp.jpg" alt height="22" />
            </span>
                        <span class="logo-lg">
              <img src="/images/cmcp.jpg" alt height="60" />
            </span>
                    </a>
                </div>

                <button
                    id="vertical-menu-btn"
                    type="button"
                    class="btn btn-sm px-3 font-size-16 header-item"
                    @click="toggleMenu"
                >
                    <i class="fa fa-fw fa-bars"></i>
                </button>

                <!-- App 
                <form class="app-search d-none d-lg-block">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Recherche" />
                        <span class="bx bx-search-alt"></span>
                    </div>
                </form>Search-->


            </div>

            <div class="d-flex">

                <div class="dropdown d-none d-lg-inline-block ms-1">
                    <button type="button" class="btn header-item noti-icon" @click="initFullScreen">
                        <i class="bx bx-fullscreen"></i>
                    </button>
                </div>
<!-- alerts -->
            <b-dropdown
                    right
                    menu-class="dropdown-menu-lg p-0 dropdown-menu-end"
                    toggle-class="header-item noti-icon"
                    variant="black"
                >
                <template v-slot:button-content>
                    <i class="bx bx-bell bx-tada"></i>
                    <span
                    class="badge bg-danger rounded-pill"
                    >{{alerts.length}}</span>
                </template>

                <div class="p-3">
                    <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-0">Alerts</h6>
                    </div>
                    <div class="col-auto">
                        <a href="/dashboard" class="small">Toutes les alertes...</a>
                    </div>
                    </div>
                </div>
                <simplebar style="max-height: 230px;">
                    <div v-for="item in alerts" :key="item.chambre">
                        <a :href="'/chambre/details/'+item.chambre" class="text-reset notification-item">
                            <div class="media">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                        <i v-if="item.type=='température'" class="mdi mdi-thermometer-alert"></i> <!-- type d'icone -->
                                        <i v-if="item.type=='porte'" class="mdi mdi-door-open"></i> <!-- type d'icone -->
                                        <i v-if="item.type=='compresseur'" class="mdi mdi-engine-off"></i> <!-- type d'icone -->
                                        <i v-if="item.type=='evaprateur'" class="mdi mdi-air-humidifier-off"></i> <!-- type d'icone -->
                                    </span>
                                 </div>
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1">{{item.type}}</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1"> {{item.message}}</p>
                                        <p class="mb-0">
                                        <i class="mdi mdi-clock-outline"></i>
                                        {{item.date}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> 
                   
                      
                </simplebar>
                <div class="p-2 border-top d-grid">
                    <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                    <i class="mdi mdi-arrow-down-circle me-1"></i>
                    
                    </a>
                </div>
            </b-dropdown>

                <b-dropdown right variant="black" toggle-class="header-item" menu-class="dropdown-menu-end">
                    <template v-slot:button-content>
                        <img
                            class="rounded-circle header-profile-user"
                            src="/images/users/avatar-1.png"
                            alt="Admin"/> <!-- //TODO avatar automatique -->

                        <span class="d-none d-xl-inline-block ms-1">Admin</span>
                        <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                    </template>
                    <!-- item-->

                    <b-dropdown-item href="/users/profile">
                        <i class="bx bx-user font-size-16 align-middle me-1"></i>
                        {{ $t('navbar.dropdown.henry.list.profile') }}
                    </b-dropdown-item>

                    <b-dropdown-divider></b-dropdown-divider>
                    <a href="/logout" class="dropdown-item text-danger">
                        <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i>
                        {{ $t('navbar.dropdown.henry.list.logout') }}
                    </a>
                </b-dropdown>

                <div class="dropdown d-inline-block">
                    <button
                        type="button"
                        class="btn header-item noti-icon right-bar-toggle toggle-right"
                        @click="toggleRightSidebar"
                    >
                        <i class="bx bx-cog bx-spin toggle-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
</template>
