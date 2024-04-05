<template>
  <div>
    <header class="header_sec">
      <nav class="navbar navbar-expand-lg ">
        <div class="container">
          <a class="navbar-brand" href="#"><img src="@/assets/images/logo.png" alt="logo" class="img-fluid" /></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
            aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 m-auto my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
              <li class="nav-item">
                <router-link class="nav-link active" aria-current="page" to="/">Home</router-link>
              </li>
              <li class="nav-item" v-if="isLoggedIn">
                <router-link class="nav-link" to="/profile">Profile</router-link>
              </li>
              <li class="nav-item" v-if="isLoggedIn">
                <router-link class="nav-link" to="/dashboard">Dashboard</router-link>
              </li>
              <li class="nav-item" v-if="!isLoggedIn">
                <router-link class="nav-link" to="/login">Login</router-link>
              </li>
            </ul>
            <form class="" role="search">
              <button class="submit-btn mb-3 mb-lg-0" type="submit">Contact Us</button>
            </form>
          </div>
        </div>
      </nav>
    </header>
  </div>
</template>
<script>
export default {
  name: 'HeaderComponent',
  data() {
    return {
      isLoggedIn: false
    }
  },
  mounted() {
    let token = localStorage.getItem('token');
    this.isLoggedIn = token ? true : false
  }
}
</script>
<style scoped>
.navbar {
  background-color: var(--black);
}

ul.navbar-nav.navbar-nav-scroll {
  gap: 42px;
}

.navbar {
  padding: 0;
}

.navbar-nav .nav-link.active,
.navbar-nav .show>.nav-link {
  color: var(--yellow);
  /* border: 1px solid var(--yellow); */
  border-radius: 4px;
}

.nav-link:hover {
  color: var(--yellow);
}

.nav-link {
  /* font-size: var(--fs-17); */
  color: var(--white);
  /* font-weight: 500; */
}

@media all and (min-width:768px) and (max-width:991px) {
  ul.navbar-nav.navbar-nav-scroll {
    gap: 0;
  }

  ul.navbar-nav.navbar-nav-scroll {
    min-height: 200px;
  }
}

@media all and (min-width:320px) and (max-width:767px) {
  ul.navbar-nav.navbar-nav-scroll {
    gap: 0;
  }

  ul.navbar-nav.navbar-nav-scroll {
    min-height: 200px;
  }
}
</style>