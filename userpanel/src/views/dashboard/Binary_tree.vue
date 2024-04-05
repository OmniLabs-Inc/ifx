<template>
    <Sidebar>
        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">/ Tree</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

                <section>
                    <div class="container">
                        <div class="text-inline">
                            <div class="input-group mb-3">
                                <input type="text" v-model="search" class="form-control" placeholder="search" aria-label="search" aria-describedby="basic-addon2">
                                <span class="input-group-text" id="basic-addon2" style="cursor: pointer;" @click="getList(this.search)">search</span>
                            </div>

                            <button type="button" class="btn  tree_btn w-md mb-2 me-3"
                                @click="this.$refs.tree.zoomIn()">+</button>
                            <button type="button" class="btn  tree_btn w-md mb-2 me-3"
                                @click="this.$refs.tree.zoomOut()">-</button>
                            <button type="button" class="btn  tree_btn w-md mb-2 me-3"
                                @click="this.$refs.tree.restoreScale()">Rescale</button>
                        </div>
                        <vue-tree style="width: 1500px; height: 600px; border: 1px solid gray;" :dataset="richMediaData"
                            :config="treeConfig" ref="tree">

                            <template v-slot:node="{ node, collapsed }">
                                <div class="rich-media-node align-items-center" :style="{ border: collapsed ? '2px solid grey' : '', background: (node.status == 1) ? 'green' : '#b31b55'  } ">
                               
                                    <img :src="node.avatar" style="width: 48px; height: 48px; border-raduis: 4px;" v-if="node.root"/> 
                                    <img :src="node.avatar" style="width: 48px; height: 48px; border-raduis: 4px;" v-else @click="addList(node.user_unique_id)"/>
                                    <span style=" font-weight: bold;">{{ node.user_unique_id }}</span>
                                    <span style=" font-weight: bold;">{{ node.name }}</span>
                                    <!-- <span style=" font-weight: bold; white-space:nowrap;" v-if="node.client_sale_logs">Top Up: {{ node.client_sale_logs[0]?node.client_sale_logs[0].amount:0 }}</span>
                                    <span style=" font-weight: bold; white-space:nowrap;" v-if="node.client_sale_logs">Business: {{ node.total_business }}</span> -->
                                    
                                </div>
                            </template>
                        </vue-tree>
                    </div>

                </section>

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
    </Sidebar>
</template>


<script>
import ApiClass from '../../api/api.js';
import objectScan from 'object-scan';
import _ from 'lodash'
export default {
    name: 'Binary_tree',
    
    data() {
        return {
            richMediaData: {
                root:true,
                name: '',
                user_unique_id: null,
                status:0,
                avatar: 'https://icons-for-free.com/iconfiles/png/512/business+costume+male+man+office+user+icon-1320196264882354682.png',
                children: [
                   
                ],
                
            },
            treeConfig: {
                nodeWidth: 120,
                nodeHeight: 80,
                levelHeight: 200
            },
            search:"",
        }
    },
    mounted() {
        this.getList();
        let user = JSON.parse(localStorage.getItem("user"));
        this.richMediaData.name = user.name;
        this.richMediaData.user_unique_id = user.user_unique_id;
        this.richMediaData.status = user.plan_active;
        document.addEventListener('touchstart', this.handleTouchStart, false);
        document.addEventListener('touchmove', this.handleTouchMove, false);
    },
    methods: {

        handleTouchStart(evt) {
            this.xDown = evt.touches[0].clientX;
            this.yDown = evt.touches[0].clientY;
            console.log({x: this.xDown , y : this.yDown});
        },

        handleTouchMove(evt) {
                if ( ! this.xDown || ! this.yDown ) {
                    return;
                }

                let xUp = evt.touches[0].clientX;
                let yUp = evt.touches[0].clientY;

                let xDiff = this.xDown - xUp;
                let yDiff = this.yDown - yUp;
                var element = document.getElementsByClassName("tree-container");

                if ( Math.abs( xDiff ) > Math.abs( yDiff ) ) {/*most significant*/
                    if ( xDiff > 0 ) {
                        
                        element[0].style.direction = "ltr";
                        /* left swipe */
                    } else {
                        element[0].style.direction = "rtl";
                        /* right swipe */
                    }
                } else {
                    if ( yDiff > 0 ) {
                        /* up swipe */
                    } else {
                        /*this. down swipe */
                    }
                }
                /* reset values */
                this.xDown = null;
                this.yDown = null;
            },

        async getList(use_) {

            let find_id = ""; 

            if(use_){
                find_id = use_;
                 

                let response = await ApiClass.getRequest(`s_detail?sponser_id=${find_id}`, true);

                if (response.data.status_code == "1") {
                    this.richMediaData.name = response.data.data.name;
                    this.richMediaData.user_unique_id = response.data.data.user_unique_id;
                    this.richMediaData.status = response.data.data.plan_active;
                }

                if (response.data.status_code == "0") {
                    this.search = "";
                    this.failed(response.data.message);
                    this.getList();
                }

            }else{
                let user = JSON.parse(localStorage.getItem("user")); 
                find_id = user?.user_unique_id ?? 0;

                this.richMediaData.name = user.name;
                this.richMediaData.user_unique_id = user.user_unique_id;
                this.richMediaData.status = user.plan_active;

            }


             let response = await ApiClass.getRequest(`tree_node?sponser_id=${find_id}`, true);
            if (response.data.status_code == "1") {
                let list = response.data.data;
                list.map((v) => {
                    v.avatar = 'https://cdn4.iconfinder.com/data/icons/professions-1-2/151/25-512.png';
                    v.user_unique_id = v.user.user_unique_id;
                    v.name = v.user.name;
                    v.status = v.user.plan_active;
                });
                // console.log(list);
                if (list.length != 0) this.richMediaData.children =   list;
            }
            console.log(this.richMediaData.children);
        },
        async addList(sponser_id) {
            let response = await ApiClass.getRequest(`tree_node?sponser_id=${sponser_id}`, true)
            if (response.data.status_code == "1" && response.data.data.length != 0) {
                let list = response.data.data;
                list.map((v) => {
                    v.avatar = 'https://cdn4.iconfinder.com/data/icons/professions-1-2/151/25-512.png';
                    v.user_unique_id = v.user.user_unique_id;
                    v.name = v.user.name;
                    v.status = v.user.plan_active;
                })
                let path = objectScan(['**'], { joined: true, filterFn: ({ value }) => value === sponser_id })(this.richMediaData);
                console.log(path,'--');
                let x = path[0].replace(".user_unique_id", "");
                this.richMediaData = _.set(this.richMediaData, x + ".children", list);
            }
        }
    },
}
</script>

<style scoped>

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.btn_scale{
   color: var(--white);
    background-image: var(--gradient);
    font-size: var(--fs-14);
    font-weight: 500;
}
.rich-media-node {
    width: 210px;
    padding: 8px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    color: var(--white);
    background-image: var(--gradient);
    border-radius: 4px;
    font-size: var(--fs-14);
    margin-left: 14px;
    font-weight: 500 !important;
}

/* .tree_btn {
    background-color : var(--theme-btn-color);
    border: none;
    color: white
}

.tree_btn:active {
    background-color : var(--card-bg-dark);
}

.tree_btn:focus {
    border: none;
}

.container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.rich-media-node {
    width: 110px;
    padding: 8px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    color: white;
    background-color: var(--card-bg-dark);
    border-radius: 4px;
} */
</style>