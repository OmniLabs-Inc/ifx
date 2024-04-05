<template>
    <div>
        <div class="heading-component text-center" data-aos="zoom-in">
            <h2 class="m-0">{{mainheading}}</h2>
            <h3 class="mb-3">{{minheading}}</h3>
        </div>
    </div>
</template>
<script>
export default{
    name:'HeadingComponent',
    props:{
        mainheading:String,
        minheading:String,
    },
    data(){
        return{

        }
    }
}
</script>
<style scoped>

.heading-component h2{
    font-size: 60px;
    -webkit-text-stroke: 1px var(--yellow);
    color: var(--background-color);
    font-weight: 700;
}

.heading-component h3{
    font-size: var(--fs-35);
    color: var(--white);
    font-weight: 600;
}
@media all and (min-width:320px) and (max-width:767px){
    .heading-component h2{
        font-size: var(--fs-35);
    }
   
}
</style>
