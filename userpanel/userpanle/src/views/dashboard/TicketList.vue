<template>
<div>
        <div class="row">
            <div class="col-md-12">
                <div class="head_table mb-4">
                    <h3 class="mb-0 text-capitalize">
                        <img src="/images/svg/life.svg" alt="text" class="img-fluid me-1" />
                        ticket summary
                    </h3>
                </div>
            </div>
            <!-- table start -->
            <div class="col-md-12">
                <div class="activation-card p-2">
                    <div class="search_box  p-3">
                        <div class="input-group rounded-2">
                            <input type="text" class="form-control bg-transparent border-0 shadow-none" placeholder="Search" aria-label="Username" aria-describedby="basic-addon1" />
                            <span class="input-group-text bg-transparent shadow-none border-0" id="basic-addon1">

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: var(--place-holder); transform: ; msfilter: ">
                                    <path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path>
                                </svg>

                            </span>
                        </div>
                    </div>
                    <div class="activation-card-body table-responsive">
                        <table class="table text-nowrap align-middle text-center">
                            <thead>
                                <tr class="activation-card-head">
                                    <th scope="col">Ticket</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Priority</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Last Reply</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody v-if="loading">
                                <tr v-for="(data, index) in TicketData" :key="index" class="activation-card-data">
                                    <td>{{ data.ticket }}</td>
                                    <td>{{ data.sub }}</td>
                                    <td>{{ data.priority }}</td>
                                    <td>
                                        <span class="badge d-flex align-items-center justify-content-center m-auto">{{ data.status }}</span>
                                    </td>
                                    <td>{{ data.last }}</td>
                                    <td>
                                        <div>
                                            <p class="mb-0">{{ data.date }}</p>
                                            <p class="mb-0">{{ data.time }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <router-link to="/ticketmodal">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: var(--white); transform: ; msfilter: ">
                                                <path d="M12 5c-7.633 0-9.927 6.617-9.948 6.684L1.946 12l.105.316C2.073 12.383 4.367 19 12 19s9.927-6.617 9.948-6.684l.106-.316-.105-.316C21.927 11.617 19.633 5 12 5zm0 11c-2.206 0-4-1.794-4-4s1.794-4 4-4 4 1.794 4 4-1.794 4-4 4z"></path>
                                                <path d="M12 10c-1.084 0-2 .916-2 2s.916 2 2 2 2-.916 2-2-.916-2-2-2z"></path>
                                            </svg>
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr v-for="i in 10" :key="i" class="activation-card-data">
                                    <td>
                                        <Skeletor />
                                    </td>
                                    <td>
                                        <Skeletor />
                                    </td>
                                    <td>
                                        <Skeletor />
                                    </td>
                                    <td>
                                        <Skeletor />
                                    </td>
                                    <td>
                                        <Skeletor />
                                    </td>
                                    <td>
                                        <Skeletor />
                                    </td>
                                    <td>
                                        <Skeletor />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- table end -->

            <!-- pagination start -->
            <div class="col-md-12">
                <div class="pagination_box d-flex justify-content-end mt-4" style="color: white">
                    <pagination v-model="page" :records="recordData" :per-page="perPageData" :options="options" @paginate="referrals" />
                </div>
            </div>
            <!-- pagination end -->
        </div>
</div>
</template>

<script>
import TicketListjson from "../../assets/json/Dashboard/TicketList.json";
export default {
    name: "TicketList",
    data() {
        return {
            loading: true,
            TicketData: TicketListjson.TicketData,
            page: 1,
            recordData: 100,
            perPageData: 10,
            options: {
                edgeNavigation: false,
                chunksNavigation: false,
                chunk: 3,
                texts: false,
                format: false,
            },
        };
    },
};
</script>

<style scoped>
.head_table h3 {
    color: var(--sky-blue);
    font-size: var(--fs-22);
    font-weight: 600;
}

.activation-card {
    background: var(--bg-secondary);
    color: var(--white);
    box-shadow: var(--card-shadow);
    border-radius: 7px;
}

.activation-card-header {
    background: var(--bg-secondary);
}

.activation-card-header h2 {
    text-transform: capitalize;
    font-size: var(--fs-16);
}

.form-control:focus {
    border-color: var(--place-holder);
}

.form-control {
    color: var(--place-holder);
    border: 1px solid var(--place-holder);
    font-size: var(--fs-14);
    padding: 10px 20px;
}

.input-group {
    box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
    background-color: var(--d-bg);
    width: 263px;
    margin: auto;
    margin-right: 0;
}

.input-group-text {
    border: 1px solid var(--place-holder);
}

.activation-card-head th {
    text-transform: capitalize;
    color: var(--white);
    font-weight: 600;
    font-size: var(--fs-14);
}

.activation-card-data td {
    color: var(--white);
    font-size: var(--fs-13);
    font-weight: 500;
}

.table> :not(caption)>*>* {
    padding: 13px 13px;
    border-bottom-width: 1px;
    border-color: #5e505026;
}

::placeholder {
    color: var(--place-holder);
}

.activation-card-data .badge {
    height: 20px;
    width: 80px;
    background-color: var(--light-sblue);
    border-radius: 5px;
    font-size: var(--fs-12)
}

.head_table_content p {
    color: var(--white);
    font-size: var(--fs-14);
    font-weight: 500;
}

.head_table_content .form-select {
    color: var(--place-holder);
    border: 1px solid var(--place-holder);
    font-size: var(--fs-14);
    box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
    background-color: var(--d-bg);
}

.activation-card-data:hover {
    background-color: var(--d-bg);
}
</style>
