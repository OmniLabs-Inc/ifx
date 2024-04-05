<template>
    <ul v-if="children.length == 1">
        <li class="child">
            <a href="#">{{ children[0].side == "left" ? children[0].user_id : "Empty" }}</a>
            <templae v-if="children[0].side == 'left' && children[0]?.children.length > 0">

                <BinChild :children="children[0].children" />
            </templae>

        </li>
        <li class="child">
            <a href="#">{{ children[0].side == "right" ? children[0].user_id : "Empty" }}</a>
            <template v-if="children[0].side == 'right' && children[0]?.children.length > 0">

                <BinChild :children="children[0].children" />
            </template>

        </li>

    </ul>
    <ul v-if="children.length == 2">
        <li class="child" v-for="(v, index) in tree" :key="index">
            <a href="">{{ v.user_id }}</a>
            <template v-if="v?.children.length > 0">
                <BinChild :children="v.children" />
            </template>

        </li>
    </ul>
</template>

<script>
export default {
    name: 'BinChild',
    data() {
        return {
            tree: {},
        }
    },
    props: {
        children: Object
    },
    setup() {
    },



    async mounted() {
        await this.sorting()
    },
    methods: {
        async sorting() {
            let lft = this.children.filter(el => el.side == "left");
            let rgt = this.children.filter(el => el.side == "right");
            this.tree = lft.concat(rgt);
        },
    },

}
</script>

<style scoped>
.leftnode>ul>li>a {
    background-color: whitesmoke;
}

.rightnode>ul>li>a {
    background-color: orange;
}

.tree ul {
    padding-top: 20px;
    position: relative;
    transition: all 0.5s;
    display: flex;
}

li.child {
    /*width:50%*/
}

.tree li {
    float: left;
    text-align: center;
    list-style-type: none;
    position: relative;
    padding: 20px 5px 0 5px;
    transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

.child {
    /* width:30%;
     min-width:500px;
     */
}

/*We will use ::before and ::after to draw the connectors*/
.tree li::before,
.tree li::after {
    content: "";
    position: absolute;
    top: 0;
    right: 50%;
    border-top: 1px solid #ffffff;
    width: 50%;
    height: 20px;
}

.tree li::after {
    right: auto;
    left: 50%;
    border-left: 1px solid #ffffff;
}

/*We need to remove left-right connectors from elements without any siblings*/
.tree li:only-child::after,
.tree li:only-child::before {
    display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child {
    padding-top: 0;
}

/*Remove left connector from first child and right connector from last child*/
.tree li:first-child::before,
.tree li:last-child::after {
    border: 0 none;
}

/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before {
    border-right: 1px solid #ffffff;
    border-radius: 0 5px 0 0;
    -webkit-border-radius: 0 5px 0 0;
    -moz-border-radius: 0 5px 0 0;
}

.tree li:first-child::after {
    border-radius: 5px 0 0 0;
    -webkit-border-radius: 5px 0 0 0;
    -moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before {
    content: "";
    position: absolute;
    top: 0;
    left: 50%;
    border-left: 1px solid #ccc;
    width: 0;
    height: 20px;
}

.tree li a {
    border: 1px solid #ccc;
    padding: 5px 10px;
    text-decoration: none;
    color: #ffffff;
    font-family: arial, verdana, tahoma;
    font-size: 11px;
    display: inline-block;
    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
}

/*Time for some hover effects*/
/*We will apply the hover effect the the lineage of the element also*/
.tree li a:hover,
.tree li a:hover+ul li a {
    background: #c8e4f8;
    color: #ffffff;
    border: 1px solid #94a0b4;
}

/*Connector styles on hover*/
.tree li a:hover+ul li::after,
.tree li a:hover+ul li::before,
.tree li a:hover+ul::before,
.tree li a:hover+ul ul::before {
    border-color: #94a0b4;
}
</style>
