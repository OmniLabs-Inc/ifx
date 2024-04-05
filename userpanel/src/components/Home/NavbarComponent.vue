<template>
  <div>
    <header class="head">
      <nav class="navbar navbar-expand-lg">
        <div class="container nav-container">
          <router-link class="navbar-brand" to="/" style="background: transparent !important"
            ><img
              src="/images/logo.png"
              alt="logo"
              class="img-fluid"
              style="width:74px;height:74px;"
          /></router-link>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon">
              <svg
                class="svg"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"
              >
                <path
                  d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"
                />
              </svg>
            </span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
              <li class="nav-item" @click="closeToggle">
                <a class="nav-link active" aria-current="page" href="#home"
                  >home</a
                >
              </li>
              <li class="nav-item" @click="closeToggle">
                <!-- <router-link class="nav-link" to="#">features</router-link> -->
                <a class="nav-link" href="#features">features</a>
              </li>

              <li class="nav-item" @click="closeToggle">
                <a class="nav-link" href="#about">about</a>
              </li>

              <li class="nav-item" @click="closeToggle">
                <a class="nav-link" href="#advantage">advantages</a>
              </li>

              <li class="nav-item" @click="closeToggle">
                <a
                  class="nav-link"
                  target="_blank"
                  href="/AlfaWhitepaper.pdf"
                  >whitepaper</a
                >
              </li>

              <li class="nav-item">
                <a
                  class="nav-link" 
                  href="/register"
                  >Register</a
                >
              </li>

              <li class="nav-item">
                <a
                  class="nav-link" 
                  href="/login"
                  >Login</a
                >
              </li>

              <!-- <li class="nav-item" @click="closeToggle">
                <a class="nav-link blink" href="#price"
                  >Live Price <span>: ${{live_price}}</span></a >
              </li> -->
            </ul>
            <!-- <div class="button-box d-flex gap-3">

              <button class="btn" id="login" @click="$router.push('/login')">
                login
              </button>

              <button class="btn" id="signup" @click="$router.push('/register')">
                sign up
              </button>
            </div> -->
          </div>
        </div>
      </nav>
    </header>
  </div>
</template>

<script>
import ApiClass from "@/api/api.js";

export default {
  name: "NavbarComponent",

  data() {
    return {
      live_price: "-",
    };
  },
  created() {
    window.addEventListener("scroll", this.handleSCroll);

	let pp = localStorage.getItem('lpr') ? localStorage.getItem('lpr') : null;
	if(pp != null){
		this.live_price = pp;
	}
	// this.getLivePrice()
  },
  unmounted() {
    window.removeEventListener("scroll", this.handleSCroll);
  },

  methods: {
    handleSCroll() {
      console.log(window.scrollY);
      let header = document.querySelector(".head");
      if (window.scrollY > 100 && !header.className.includes("sticky")) {
        header.classList.add("sticky");
      } else if (window.scrollY < 100) {
        header.classList.remove("sticky");
      }
    },

    async getLivePrice() {
      let res = await ApiClass.getNodeRequest("live-price", true);
      if (res.data.status_code == 1) {
        this.live_price = res.data?.data?.price || "-";

        localStorage.setItem("lpr", this.live_price);
      }
    },

    closeToggle() {
      const menuToggle = document.getElementById("navbarSupportedContent");
      menuToggle.classList.remove("show");
    },
  },
};
</script>

<style scoped>

@keyframes blinker {
  from {opacity: 1.0;}
  to {opacity: 0.0;}
}
.blink{
	text-decoration: blink;
	-webkit-animation-name: blinker;
	-webkit-animation-duration: 0.6s;
	-webkit-animation-iteration-count:infinite;
	-webkit-animation-timing-function:ease-in-out;
	-webkit-animation-direction: alternate;
}

.head {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 11;
}

.head .navbar .navbar-toggler span.navbar-toggler-icon {
  background-image: none;
}

.head .navbar .navbar-toggler span.navbar-toggler-icon svg.svg {
  fill: var(--white);
  display: inline-block;
  height: 30px;
  width: 30px;
}

.head .navbar .container.nav-container {
  max-width: 1420px;
}

.head .navbar .container.nav-container .navbar-brand {
  margin-right: 0;
}

.head .navbar .container.nav-container .navbar-brand img {
  max-width: 150px;
}

.head .navbar .container.nav-container ul.navbar-nav {
  gap: 20px;
}

.head .navbar .container.nav-container ul.navbar-nav li.nav-item a.nav-link {
  color: var(--white);
  text-transform: uppercase;
  font-size: var(--fs-14);
  font-weight: 500;
}

.head .navbar .container.nav-container .button-box .btn {
  min-width: 140px;
  min-height: 43px;
}

.head .navbar .container.nav-container .button-box .btn a {
  color: var(--white);
}

.head .navbar .container.nav-container .button-box .btn-check:checked + .btn,
.head .navbar .container.nav-container .button-box .btn.active,
.head .navbar .container.nav-container .button-box .btn.show,
.head .navbar .container.nav-container .button-box .btn:first-child:active,
.head
  .navbar
  .container.nav-container
  .button-box
  :not(.btn-check)
  + .btn:active {
  background-color: transparent;
  border-color: transparent;
}

.head .navbar .container.nav-container .button-box button#login {
  /* background-image: url(@/assets/new/images/icon/btn-blue.png); */
  /* background-repeat: no-repeat;
		background-position: center;
		background-size: cover; */
  /* background-color: var(--sky-blue); */
  /* border: 2px solid var(--sky-blue); */
  color: var(--white);
  font-size: var(--fs-16);
  font-weight: 500;
  text-transform: capitalize;
}

.head .navbar .container.nav-container .button-box button#login:hover {
  /* background-image: url(@/assets/new/images/icon/btn-blue.png); */
  border: 2px solid white;
  /* background-color: var(--main-bg); */
  /* transition: all 0.5s ease; */
}

.head .navbar .container.nav-container .button-box button#signup {
  /* background-image: url(@/assets/new/images/icon/btn-white.png);
		background-repeat: no-repeat;
		background-position: center;
		background-size: cover; */
  /* border: 2px solid white; */
  color: var(--white);
  font-size: var(--fs-16);
  font-weight: 500;
  text-transform: capitalize;
  /* transition: all 0.5s ease; */
}

.head .navbar .container.nav-container .button-box button#signup:hover {
  /* background-image: url(@/assets/new/images/icon/btn-blue.png); */
  /* background-color: var(--sky-blue); */
  /* border-color: var(--sky-blue); */
  border: 2px solid white;
  /* transition: all 0.5s ease; */
}

header.head.sticky {
  position: fixed;
  top: 0;
  /* background-color: var(--main-bg); */
  /* background-color: #5c354c; */
  background-image: linear-gradient(to left bottom, #de4232, #bd1c52, #8a1962, #4e2060, #0d1c4b);
  /* box-shadow: var(--box-shadow); */
}

@media all and (min-width: 1025px) and (max-width: 1199px) {
  .head .navbar .container.nav-container .navbar-brand img {
    max-width: 150px;
  }

  .head .navbar .container.nav-container .navbar-collapse ul.navbar-nav {
    gap: 10px;
  }

  .head
    .navbar
    .container.nav-container
    .navbar-collapse
    ul.navbar-nav
    li.nav-item
    a.nav-link {
    font-size: var(--fs-12);
  }

  .head .navbar .container.nav-container .navbar-collapse .button-box .btn {
    min-width: 120px;
    min-height: 30px;
  }
}

@media all and (min-width: 992px) and (max-width: 1024px) {
  .head .navbar .container.nav-container .navbar-brand img {
    max-width: 150px;
  }

  .head .navbar .container.nav-container .navbar-collapse ul.navbar-nav {
    gap: 10px;
  }

  .head
    .navbar
    .container.nav-container
    .navbar-collapse
    ul.navbar-nav
    li.nav-item
    a.nav-link {
    font-size: var(--fs-12);
  }

  .head .navbar .container.nav-container .navbar-collapse .button-box .btn {
    min-width: 120px;
    min-height: 30px;
  }
}

@media all and (min-width: 768px) and (max-width: 991px) {
  .head {
    /* background-color: var(--main-bg); */
      /* background-color: #5c354c; */
      background-image: linear-gradient(to left bottom, #de4232, #bd1c52, #8a1962, #4e2060, #0d1c4b);

  }

  .head .navbar .container.nav-container .navbar-collapse {
    padding: 15px 0px;
  }

  .head .navbar .container.nav-container .navbar-collapse ul.navbar-nav {
    gap: 5px;
  }
}

@media all and (min-width: 320px) and (max-width: 767px) {
  .head {
    /* background-color: var(--main-bg); */
      /* background-color: #5c354c; */
      background-image: linear-gradient(to left bottom, #de4232, #bd1c52, #8a1962, #4e2060, #0d1c4b);

  }

  .head .navbar .container.nav-container .navbar-collapse {
    padding: 15px 0px;
  }

  .head .navbar .container.nav-container .navbar-collapse ul.navbar-nav {
    gap: 5px;
  }

  .head .navbar .container.nav-container .navbar-collapse .button-box {
    display: block !important;
  }

  .head .navbar .container.nav-container .navbar-collapse .button-box #login {
    margin-bottom: 7px;
  }

  .head .navbar .container.nav-container .navbar-collapse .button-box .btn {
    min-width: 170px;
    min-height: 55px;
  }
}
</style>