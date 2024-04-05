<template>
  <div>
    <!-- start banner section -->
    <section class="banner-sec homesection">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-lg-10 col-xl-7">
            <div class="banner-info text-center" data-aos="fade-up">
              <h1 class="mb-4">Creating a Transparent and Accessible Platform for Investors</h1>
              <p class="mb-4">Welcome to The Ascor, the smart contract that is redefining the crypto market. In today's
                age of advancement and modernity, The Ascor was created to stay two steps ahead of the world.</p>
              <div class="contact-btn mb-4">
                <button class="submit-btn" type="submit">Contact Us</button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- end banner section -->
    <!-- start card section -- -->
    <section class="card-sec homesection">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-4" v-for="(data, index) in carddata" :key="index" data-aos="fade-up-right">
            <div :class="`ascor-card px-3 px-lg-5 py-5  mb-5 mb-lg-0 ${data.Class}`">
              <p>{{ data.para }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end card section -- -->

    <!-- start about sec -->
    <section class="about-sec homesection">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-7">
            <div class="about-head">
              <HeadingComponent mainheading="About Us" minheading="About Us" />
            </div>
          </div>
        </div>
        <div class="row align-items-center flex-column-reverse flex-lg-row" data-aos="fade-up-left">
          <div class="col-12 col-lg-7">
            <div class="about-info px-4 my-4">
              <div>
                <p class="mb-3">Welcome to The Ascor, your partner for access to liquidity for alternative assets in the
                  crypto market. Our mission is to redefine the crypto market by providing cutting-edge blockchain
                  technology and a high level of professionalism and security to help you achieve your financial goals.
                </p>

                <p class="mb-3">Our team of experts is dedicated to making every technology in blockchain your ladder to
                  success. We work tirelessly to create a transparent and accessible platform that is easy to use,
                  cost-effective, and compliant with all relevant regulations and laws.</p>

                <p>At The Ascor, we believe in providing our clients with the resources they need to stay ahead of the
                  curve. We offer access to a diverse range of assets, helping you to diversify your portfolio and achieve
                  higher returns than traditional investments.
                </p>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-5">
            <div class="about-img text-center">
              <img src="@/assets/images/about-img-1.png" class="img-fluid" alt="img" />
            </div>
          </div>
        </div>
        <div class="row align-items-center" data-aos="fade-up-right">
          <div class="col-12 col-lg-5">
            <div class="about-img text-center">
              <img src="@/assets/images/about-img-2.png" class="img-fluid" alt="img" />
            </div>
          </div>
          <div class="col-12 col-lg-7">
            <div class="about-info about-info-1 px-4 my-4">
              <div>
                <p class="mb-3">Welcome to The Ascor, your partner for access to liquidity for alternative assets in the
                  crypto market. Our mission is to redefine the crypto market by providing cutting-edge blockchain
                  technology and a high level of professionalism and security to help you achieve your financial goals.
                </p>

                <p class="mb-3">Our team of experts is dedicated to making every technology in blockchain your ladder to
                  success. We work tirelessly to create a transparent and accessible platform that is easy to use,
                  cost-effective, and compliant with all relevant regulations and laws.</p>

                <p>At The Ascor, we believe in providing our clients with the resources they need to stay ahead of the
                  curve. We offer access to a diverse range of assets, helping you to diversify your portfolio and achieve
                  higher returns than traditional investments.
                </p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- end about sec -->

    <!-- start advantage sec -->
    <section class="advantage-sec homesection">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-md-7">
            <div class="advantage-head mb-5">
              <HeadingComponent mainheading="Advantage" minheading="Advantages of The Ascor that 
        may be of interest" />
            </div>
          </div>
        </div>
        <div class="advantage-card-row">
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-md-6 col-xl-4" v-for="(data, index) in AdvantageData" :key="index" data-aos="zoom-in">
                <div class="advantage-card p-3 p-xl-5 mb-3">
                  <div class="advantage-card-info d-flex gap-3 mb-3 align-items-center flex-column flex-xl-row">
                    <div class="advantage-icon">
                      <!-- <img  class="img-fluid" :src="require(`@/assets/images/${data.icon}`)" alt="icon"> -->
                      <img :src="`/images/homeview/${data.icon}`" alt="icon" class="img-fluid">
                    </div>
                    <div class="icon-info">
                      <h6 class="m-0">{{ data.head }}</h6>
                    </div>
                  </div>
                  <p class="m-0">{{ data.para }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end advantage sec -->

    <!-- start benefit section -->
    <section class="benefit-sec homesection">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-7">
            <div class="benefit-head pb-5">
              <HeadingComponent mainheading="Benefits" minheading="Benefits" />
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-lg-4" v-for="(data, index) in Benefitdata" :key="index" data-aos="zoom-out">
            <div :class="`benefit-info p-2 p-md-5 mb-5 ${data.Class}`">
              <div class="benefit-icon mb-3">
                <!-- <img src="@/assets/images/benefit-icon-1.png" class="img-fluid" alt="icon" /> -->
                <img :src="`/images/homeview/${data.icon}`" alt="icon" class="img-fluid">
              </div>
              <div class="benefit-icon-info text-center">
                <h5 class="mt-5 mb-3">{{ data.head }}</h5>
                <p>{{ data.para }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- end benefit section -->

    <!-- start our mission -->
    <section class="our-mission homesection">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="mission-head pb-5">
              <HeadingComponent mainheading="Mission" minheading="Our Mission" />
              <p class="text-center">To redefine the crypto market by providing access to liquidity for alternative assets
                through cutting-edge blockchain technology, while maintaining a high level of professionalism and
                security.</p>
            </div>
          </div>
        </div>
        <div class="row align-items-center flex-column-reverse flex-md-row">
          <div class="col-md-5" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">
            <div class="mission-info">
              <h5 class="mb-3">Investing in the Future with The Ascor</h5>
              <p>To become a leading lending partner for institutional investors in the crypto market, helping our clients
                to achieve their financial goals through access to innovative technologies and a diverse range of assets.
                We strive to create a transparent and accessible platform that is user-friendly and cost-effective, while
                also complying with all relevant regulations and laws. We are dedicated to continuous innovation and
                pushing the boundaries of what is possible in the world of blockchain, while providing a high level of
                expertise and support to our clients.</p>
            </div>
          </div>
          <div class="col-md-7" data-aos="fade-up" data-aos-anchor-placement="top-center">
            <div class="mission-img text-center mb-3 mb-md-0">
              <img src="../assets/images/mission-img.png" class="img-fluid" alt="icon">
            </div>
          </div>
        </div>

      </div>


    </section>

    <!-- end our mission -->
  </div>
</template>
<script>
import HeadingComponent from '../components/HeadingComponent.vue'
export default {
  name: 'HomeView',
  components: {
    HeadingComponent
  },
  data() {
    return {
      carddata: [
        {
          para: 'As a lending partner of choice for institutional investors, The Ascor sources liquidity for alternative assets. Our mission is to support you in reaching a better place in your life through the worlds most advanced technologies.',
          Class: "card_one"
        },
        {
          para: 'The Ascor team is comprised of experts in the blockchain industry who are dedicated to making every technology in blockchain your ladder to success. We work day and night to ensure that our clients have access to the worlds most advanced technologies.',
          Class: "card_two",
        },
        {
          para: 'With The Ascor, you can rest assured that you are working with a team of professionals who have extensive experience in the crypto market. Our team is constantly innovating and pushing the boundaries of what is possible in the world of blockchain.',
          Class: "card_three",
        }
      ],
      AdvantageData: [
        {
          icon: 'advantage-1.png',
          head: 'Access to liquidity for alternative assets',
          para: 'The Ascor is a lending partner of choice for institutional investors who are looking to source liquidity for alternative assets. This gives investors access to a wider range of assets, which can help to diversify their portfolios.'
        },
        {
          icon: 'advantage-2.png',
          head: 'Cutting-edge technology',
          para: 'The Ascor is constantly innovating and pushing the boundaries of what is possible in the world of blockchain. Our team is comprised of experts who are dedicated to making every technology in blockchain your ladder to success.'
        },
        {
          icon: 'advantage-3.png',
          head: 'Professionalism and experience',
          para: 'The Ascor team has extensive experience in the crypto market, and we are committed to providing our clients with a high level of professionalism and expertise. We work tirelessly to ensure that our clients have access to the resources they need to succeed'
        },
        {
          icon: 'advantage-4.png',
          head: 'Easy to use',
          para: 'The Ascor platform is designed to be easy to use, even for those who are new to the world of crypto. Our user-friendly interface makes it simple to navigate and find the information you need'
        },
        {
          icon: 'advantage-5.png',
          head: 'Secure',
          para: 'The Ascor takes security seriously and has implemented a range of measures to protect our clients assets. Our platform is built on a secure, decentralized infrastructure, which provides an added layer of protection'
        },
        {
          icon: 'advantage-6.png',
          head: 'Fast and reliable',
          para: 'The Ascor platform is built to be fast and reliable, ensuring that our clients can access their assets quickly and easily.'
        },
      ],
      Benefitdata: [
        {
          icon: 'benefit-icon-1.png',
          head: 'Improved liquidity',
          para: ' The Ascor provides access to liquidity for alternative assets, which can help investors to diversify their portfolios and improve their overall liquidity.',
          Class: 'benefit_card-one'

        },
        {
          icon: 'benefit-icon-2.png',
          head: 'Higher Returns',
          para: 'By providing access to alternative assets, The Ascor can help investors to achieve higher returns than traditional investments.',
          Class: 'benefit_card-two'
        },
        {
          icon: 'benefit-icon-3.png',
          head: 'Easy access to advanced technologies',
          para: 'The Ascor team is dedicated to making every technology in blockchain accessible to our clients, making it easier for them to stay ahead of the curve.',
          Class: 'benefit_card-three'
        },
        {
          icon: 'benefit-icon-4.png',
          head: 'Reduced costs',
          para: 'The Ascor platform is designed to be cost-effective, with low fees and minimal overhead costs.',
          Class: 'benefit_card-four'
        },
        {
          icon: 'benefit-icon-5.png',
          head: 'Compliance with regulations',
          para: 'The Ascor is committed to complying with all relevant regulations and laws, ensuring that our clients can invest with confidence.',
          Class: 'benefit_card-five'
        },
        {
          icon: 'benefit-icon-6.png',
          head: 'Improved security',
          para: 'The Ascor platform is built on a secure, decentralized infrastructure, providing an added layer of protection for our clients assets.',
          Class: 'benefit_card-six'
        },
      ],
    }
  }

}
</script>
<style scoped>
/*  -- start banner section --*/
.banner-sec {
  background-color: var(--background-color);
  position: relative;
  min-height: 800px;
  display: flex;
  align-items: center;
}

.banner-sec::after {
  position: absolute;
  content: '';
  width: 100%;
  height: 100%;
  background-image: url(../assets/images/banner-img-1.png);
  background-repeat: no-repeat;
  top: 0;
  right: 0;

}

.banner-sec::before {
  position: absolute;
  content: '';
  width: 69%;
  height: 100%;
  background-image: url(../assets/images/banner-img-2.png);
  background-repeat: no-repeat;
  top: -10px;
  right: 0;
}

.banner-info h1 {
  color: var(--white);
  font-size: var(--fs-55);
  font-weight: 600;
}

.banner-info p {
  color: var(--white);
  font-size: var(--fs-14);
}

/*  -- end banner section --*/

/*  -- start card section --*/
.ascor-card {
  backdrop-filter: blur(20px);
  border-radius: 4px;
  position: relative;
  min-height: 238px;
}

.card_one {
  background-image: var(--linear-gradient);
  border: 1px solid var(--blue-border);
}

.card_one::after {
  background-image: url(../assets/images/card-icon-1.png);
}

.card_two {
  background-image: var(--purple-gradient);
  border: 1px solid var(--purple-border);
}

.card_two::after {
  background-image: url(../assets/images/card-icon-2.png);
}

.card_three {
  background-image: var(--yellow-gradient);
  border: 1px solid var(--yellow-border);
}

.card_three::after {
  background-image: url(../assets/images/card-icon-3.png);
}

.ascor-card::after {
  position: absolute;
  content: '';
  width: 70px;
  height: 77px;
  background-repeat: no-repeat;
  background-size: cover;
  top: -46px;
  right: -4px;
  background-position: top right;
  animation-name: upTodownicon;
  animation-duration: 4s;
  animation-iteration-count: infinite;
}

@keyframes upTodownicon {
  0% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(20px);
  }
}

.ascor-card p {
  font-size: var(--fs-14);
  color: var(--white);
}


/*  -- end card section --*/

/*  start about sec  */
.about-sec {
  position: relative;
  min-height: 600px;
}

.about-sec::after {
  position: absolute;
  content: '';
  width: 500px;
  height: 100%;
  background-image: url(../assets/images/about-layer.png);
  background-repeat: no-repeat;
  background-size: cover;
  top: 0;
  left: 0;
  background-position: top left;
}

.about-sec::before {
  position: absolute;
  content: '';
  width: 90px;
  height: 90px;
  background-image: url(../assets/images/about-circle.png);
  background-repeat: no-repeat;
  background-size: cover;
  top: 54px;
  right: 20px;
  background-position: top left;
  animation-name: upTodown;
  animation-duration: 4s;
  animation-iteration-count: infinite;
}

@keyframes upTodown {
  0% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(20px);
  }
}

.about-img {
  animation-name: upTodownimg;
  animation-duration: 4s;
  animation-iteration-count: infinite;
}

@keyframes upTodownimg {
  0% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(20px);
  }
}

.about-head {
  position: relative;
}

.about-head::after {
  position: absolute;
  content: '';
  width: 70px;
  height: 91px;
  background-image: url(../assets/images/arrow.png);
  background-repeat: no-repeat;
  bottom: -37px;
  right: 37%;

}

.about-info p {
  color: var(--white);
  font-size: var(--fs-15);
}

.about-info {
  background-image: url(../assets/images/about-rect.png);
  width: 100%;
  background-repeat: no-repeat;
  background-size: cover;
  min-height: 351px;
  display: flex;
  align-items: center;
}

.about-info-1 {
  background-image: url(../assets/images/about-rect-2.png);
}

/* end about sec */
/* start advantage sec */
.advantage-card-row {
  background-image: url(../assets/images/advantage-bg.png);
  background-size: cover;
  background-repeat: no-repeat;
  width: 100%;
  min-height: 600px;
  position: relative;
  z-index: 9;
}

.advantage-card {
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  /* Note: backdrop-filter has minimal browser support */

  border-radius: 4px;
}

.advantage-card-info h6 {
  color: var(--white);
  font-size: var(--fs-18);
}

.advantage-card p {
  color: var(--white);
  font-size: var(--fs-15);
  text-align: justify;
}

.advantage-card {
  min-height: 330px
}

.advantage-sec {
  position: relative;
}

.advantage-sec::after {
  position: absolute;
  content: '';
  background-image: url(../assets/images/advantage-effect.png);
  width: 100%;
  height: 100%;
  right: 0;
  bottom: 33%;
  background-repeat: no-repeat;
  background-size: cover;
}

/* end advantage sec */

/* benefit sec*/
.benefit-info {
  position: relative;
}

.benefit-info::after {
  position: absolute;
  content: '';
  background-image: url(../assets/images/benefit-bg.png);
  background-repeat: no-repeat;
  background-size: contain;
  width: 100%;
  height: 100%;
  bottom: 0;
  right: 0;
  background-position: center bottom;
}

.benefit-icon-info {
  min-height: 157px;
}

.benefit-icon-info h5 {
  color: var(--yellow);
  font-size: var(--fs-22);
}

.benefit_card-five h5 {
  color: var(--light-purple);
}

.benefit_card-five::after {
  background-image: url(../assets/images/benefit-bg-5.png);
}

.benefit_card-three h5 {
  color: var(--light-green);
}

.benefit_card-three::after {
  background-image: url(../assets/images/benefit-bg-3.png);
}

.benefit_card-two h5 {
  color: var(--light-red);
}

.benefit_card-two::after {
  background-image: url(../assets/images/benefit-bg-2.png);
}

.benefit_card-four h5 {
  color: var(--light-lime);
}

.benefit_card-four::after {
  background-image: url(../assets/images/benefit-bg-4.png);
}

.benefit_card-six h5 {
  color: var(--white);
}

.benefit_card-six::after {
  background-image: url(../assets/images/benefit-bg-6.png);
}

.benefit-icon-info p {
  color: var(--white);
  font-size: var(--fs-14);
}

.benefit-icon {
  position: absolute;
  top: -35px;
  right: 38%;
}

.benefit-sec {
  background-image: url(../assets/images/benefit-sec-bg.png);
  width: 100%;
  background-size: cover;
  background-repeat: no-repeat;

}

/* start mission-head  */
.our-mission {
  background-image: url(../assets/images/mission-bg.png);
  width: 100%;
  background-size: cover;
  background-repeat: no-repeat;
}

.mission-head p {
  color: var(--white);
  font-size: var(--fs-14);
}

.mission-info h5 {
  color: var(--white);
  font-size: var(--fs-20);
}

.mission-info p {
  color: var(--white);
  font-size: var(--fs-15);
}

.mission-img {
  position: relative;
}

.mission-img::after {
  content: '';
  position: absolute;
  background-image: url(../assets/images/mission-arrow.png);
  background-repeat: no-repeat;
  background-size: cover;
  width: 170px;
  height: 131px;
  top: -17px;
  left: -51px;
}

@media all and (min-width:1200px) and (max-width:1400px) {
  .banner-info h1 {
    font-size: var(--fs-45);
  }

  .ascor-card {
    min-height: 280px;
  }

  .advantage-card {
    min-height: 354px;
  }


}

@media all and (min-width:1025px) and (max-width:1199px) {
  .banner-info h1 {
    font-size: var(--fs-45);
  }

  .ascor-card {
    min-height: 323px;
  }

  .advantage-card {
    min-height: 354px;
  }


}

@media all and (min-width:992px) and (max-width:1024px) {
  .banner-info h1 {
    font-size: var(--fs-45);
  }

  .banner-sec {
    min-height: 600px;
  }

  .ascor-card {
    min-height: 323px;
  }

  .advantage-card {
    min-height: 284px;
  }

  .benefit-icon-info h5 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 2;
    -webkit-box-orient: vertical;
  }

  .benefit-icon-info p {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 2;
    -webkit-box-orient: vertical;
  }

  .benefit-info::after {
    bottom: 69px;
  }
}

@media all and (min-width:768px) and (max-width:991px) {

  .banner-sec {
    position: unset;
  }

  .banner-sec::before {
    display: none;
  }

  .banner-sec::after {
    display: none;
  }

  .banner-info h1 {
    font-size: var(--fs-45);
  }

  .ascor-card {
    min-height: 282px;
  }

  .about-sec::after {
    position: unset;
  }

  .advantage-card {
    min-height: 382px;
  }

  .about-head::after {
    position: unset;
  }

  .benefit-icon-info h5 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    line-clamp: 2;
    -webkit-box-orient: vertical;
  }

  .benefit-info::after {
    bottom: 32px;
    right: 3px;
  }

  .advantage-card {
    min-height: 327px;
  }
}

@media all and (min-width:320px) and (max-width:767px) {
  .banner-info h1 {
    font-size: var(--fs-35);
  }

  .banner-sec {
    position: unset;
    min-height: 600px;
  }

  .banner-sec::before {
    display: none;
  }

  .banner-sec::after {
    display: none;
  }

  .about-head {
    position: unset;
  }

  .about-sec::before {
    position: unset;
  }

  .about-sec::after {
    position: unset;
  }

  .advantage-sec::after {
    position: unset;
  }

  .benefit-info::after {
    bottom: 3px;
  }

  .benefit-icon {
    position: absolute;
    top: -53px;
    right: 32%;
  }

  .benefit-info::after {
    position: unset;
  }
}
</style>