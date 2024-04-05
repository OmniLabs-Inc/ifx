<template>
    <div>
        <div class="modal_ticket_page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 col-lg-6 col-xl-4">
                        <div class="ticket_info p-3 mb-3">
                            <div class="mb-3">
                                <p class="mb-0">Ticket Information</p>
                                <h3 class="mb-0">#554 - Withdrawal token swapping problem</h3>
                            </div>
                            <div class="d-flex align-items-center justify-content-between info_modal p-2">
                                <div>Customer Username:</div>
                                <div>1348</div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between info_modal p-2">
                                <div>Submitted:</div>
                                <div>2023-02-01 20:05:24</div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between info_modal p-2">
                                <div>Status:</div>
                                <div>Open</div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between info_modal p-2">
                                <div>Priority:</div>
                                <div>Low</div>
                            </div>
                            <div class="info_modal">
                                <button type="button" class="btn_add shadow-none w-100">Add Reply</button>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-md-12 col-lg-6 col-xl-2">
                        <div class="ticket_info p-3 mb-3">
                            <p class="mb-0">Ticket</p>
                            <p class="mb-0">Lucky1999</p>
                        </div>
                    </div>
                    <!-- col end -->
                    <div class="col-md-12 col-lg-12 col-xl-6">
                        <div class="ticket_info pt-3 mb-3">
                            <div class="border_bottom p-3">
                                <p class="mb-0">Hello sir I have withdraw my 24.64 token for swapping in this
                                    TBESoXMywy1M17iY4w7vUbTY6XnSpiyDEX address .problem is transaction was completed but
                                    token not received in swapping option and which time i am withdraw my token internet
                                    connection will lost. Please sir solve my problem requested sir.</p>
                            </div>
                        </div>
                    </div>
                    <!-- col end -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'TicketModal',
}
</script>
<style scoped>
.ticket_info {
    background-color: var(--bg-secondary);
    border-radius: 7px;
}

.ticket_info p {
    color: var(--place-holder);
    font-size: var(--fs-15);
    font-weight: 500;
    word-break: break-all;
}

.ticket_info h3 {
    color: var(--sky-blue);
    text-transform: capitalize;
    /* color: var(--white); */
    font-size: var(--fs-20);
    font-weight: 500;
}

.info_modal {
    color: var(--place-holder);
    font-size: var(--fs-15);
    font-weight: 500;
}

.info_modal .btn_add {
    border: 1px solid var(--text-color);
    background: var(--gradient);
    padding: 6px 10px;
    color: var(--white);
    font-size: var(--fs-15);
    font-weight: 500;
    border-radius: 7px;
}

.border_bottom {
    border-bottom: 1px solid var(--light-border)
}</style>
