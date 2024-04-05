<template>
  <div>
      <div class="row staked-admin justify-content-center">
        <div class="staked-cal rounded-bottom-3 col-11 col-md-10 mx-auto p-0">
          <div class="staked-heading rounded-top-3 py-3">
            <h5 class="m-0 text-center">Staking</h5>
          </div>
          <form>
            <div class="modal-body p-4">
              <form class="p-4">
                <div class="form_box">
                  <p class="user-label p-0 d-flex align-items-center gap-2">
                    Username
                  </p>

                  <div class="input-group user-input-box mb-4">
                    <input
                      type="text"
                      class="user-input-main p-2 form-control shadow-none"
                      placeholder="Enter Username "
                      aria-describedby="basic-addon1"
                    />
                  </div>
                </div>
                <div class="form_box">
                  <p class="user-label p-0 d-flex align-items-center gap-2">
                    Amount
                  </p>

                  <div class="input-group user-input-box mb-4">
                    <input
                      type="text"
                      class="user-input-main p-2 form-control shadow-none"
                      placeholder="Enter Amount "
                      aria-describedby="basic-addon1"
                    />
                  </div>
                </div>
                <div class="form_box">
                  <p class="user-label p-0 d-flex align-items-center gap-2">
                    Select Wallet
                  </p>
                  <select class="form-select user-input-main mb-4 p-2" aria-label="Default select example">
                  <option selected>Select Wallet</option>
                  <option value="1">USDT</option>
                  <option value="2"> QBC</option>
                </select>
                </div>
 
                <div class="form_box">
                  <p class="user-label p-0 d-flex align-items-center gap-2">
                    Transaction Hash
                  </p>

                  <div class="input-group user-input-box mb-4">
                    <input
                      type="text"
                      class="user-input-main p-2 form-control shadow-none"
                      placeholder="Enter Transaction Hash"
                      aria-describedby="basic-addon1"
                    />
                  </div>
                </div>
              </form>
            </div>
          </form>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  name: "StakedByAdmin",
};
</script>

<style scoped>
.staked-cal {
  background: var(--d-bg);
  box-shadow: rgb(0 0 0 / 45%) 0px 5px 15px;
}
.staked-heading {
  background: var(--gradient);
  color: var(--white);
}
.form_box .user-label {
  color: var(--white);
  font-size: var(--fs-17);
  margin-bottom: 6px;
}
.user-input-box .user-input-main,
.form-select {
  background-color: var(--bg-secondary);
  color: var(--white);
  font-size: var(--fs-15);
  border: unset;
  box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
}
.user-input-box .user-input-main::placeholder {
  color: var(--place-holder);
  font-size: var(--fs-15);
}
</style>
