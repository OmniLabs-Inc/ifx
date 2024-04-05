<template>
    <div>
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="reward">
                            <h2>Reward income ( One Time)</h2>

                            <div class="reward-table table-responsive">

                                <table class="table text-nowrap align-middle">
                                    <thead>
                                        <tr>
                                            <th>Business/Matching</th>
                                            <th>IFX Reward</th>
                                            <th>*Time</th>
                                            <th>Time Line</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>$1,500</td>
                                            <td>$50</td>
                                            <td>One Times</td>
                                            <td>15 days</td>
                                        </tr>


                                        <tr>
                                            <td>$3,500</td>
                                            <td>$100</td>
                                            <td>One Times</td>
                                            <td>30 Days</td>
                                        </tr>

                                        <tr>
                                            <td>$7,000</td>
                                            <td>$200</td>
                                            <td>One Times</td>
                                            <td>45 Days</td>
                                        </tr>






                                    </tbody>
                                </table>

                            </div>

                        </div>

                    </div>


                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">

                        <div class="reward">
                            <h2>Reward income (Monthly bases)</h2>

                            <div class="reward-table table-responsive">

                                <table class="table text-nowrap align-middle">
                                    <thead>
                                        <tr>
                                            <th>Business/Matching</th>
                                            <th>IFX Reward</th>
                                            <th>*Time</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>$10,000</td>
                                            <td>$50</td>
                                            <td>6 Times</td>

                                        </tr>


                                        <tr>
                                            <td>$15,000</td>
                                            <td>$100</td>
                                            <td>6 Times</td>

                                        </tr>

                                        <tr>
                                            <td>$25,000</td>
                                            <td>$200</td>
                                            <td>6 Times</td>

                                        </tr>

                                        <tr>
                                            <td>$50,000</td>
                                            <td>$300</td>
                                            <td>9 Times</td>

                                        </tr>


                                        <tr>
                                            <td>$1 Lac</td>
                                            <td>$500</td>
                                            <td>9 Times</td>

                                        </tr>

                                        <tr>
                                            <td>$2.5 Lac</td>
                                            <td>$1,000</td>
                                            <td>9Times</td>

                                        </tr>

                                        <tr>
                                            <td>$5 Lac</td>
                                            <td>$2,100</td>
                                            <td>9 Times</td>

                                        </tr>

                                        <tr>
                                            <td>$10 Lac</td>
                                            <td>$5,000</td>
                                            <td>12 Times</td>

                                        </tr>

                                        <tr>
                                            <td>$1 crore</td>
                                            <td>$21,000</td>
                                            <td>12 Times</td>

                                        </tr>

                                        <tr>
                                            <td>$10 crore</td>
                                            <td>$1,00,000</td>
                                            <td>12 Times</td>

                                        </tr>




                                    </tbody>
                                </table>

                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </section>
    </div>
</template>

<script>
export default {
    setup() {

    },
}
</script>

<style scoped>
.table {
    --bs-table-color: var(--white);

}

.reward-table {
    background-image: linear-gradient(to left bottom, #de4232, #bd1c52, #8a1962, #4e2060, #0d1c4b) ;
    margin-top:30px;
    padding: 20px;
}

.reward h2 {
    color: var(--black);
    font-size: 30px;
    font-weight: 600;
}

section {
    padding-top: 30px;
}

.reward {
    margin-top:100px;
}

.reward h2 {
    color: var(--black);
    font-size:var(--fs-28);
    font-weight: 600;
}


.reward h2 {
    color: var(--black);
    font-size:var(--fs-20);
    font-weight: 600;
}

@media all and (min-width:1200px) and (max-width:1399px) {

.reward h2 {
color: var(--black);
font-size:var(--fs-24);
font-weight: 600;
}

}

@media all and (min-width:992px) and (max-width:1199px) {

.reward h2 {
color: var(--black);
font-size:var(--fs-24);
font-weight: 600;
}

}
@media all and (min-width:768px) and (max-width:991px) {

    .reward h2 {
    color: var(--black);
    font-size:var(--fs-22);
    font-weight: 600;
}

}
@media all and (min-width:320px) and (max-width:767px) {


.reward h2 {
    color: var(--black);
    font-size:var(--fs-20);
    font-weight: 600;
}

}
</style>
