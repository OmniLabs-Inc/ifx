<template>
  <div class="accordion-item">
    <h2 class="accordion-header" :id="'headingTwo' + index">
      <button
        class="accordion-button shadow-none collapsed"
       
        type="button"
        data-bs-toggle="collapse"
        :data-bs-target="'#collapseTwo' + index"
        aria-expanded="false"
        :aria-controls="'collapseTwo' + index"
      >
        <span v-html="SidebarIcon[list.icon]" style="margin-left: 15px;"> </span>
        {{ list.name }}
      </button>
    </h2>

    <div
      :id="'collapseTwo' + index"
      :class="
        index == 0
          ? 'accordion-collapse collapse'
          : 'accordion-collapse collapse'
      "
      :aria-labelledby="'headingTwo' + index"
      data-bs-parent="#accordionExample"
    >
      <div class="accordion-body pt-0">
        <ul class="sidebar-list" id="submenu-list">
          <li v-for="(child, index1) in list.children" :key="index1">
            <SubmenuView
              :index="index1"
              :list="child"
              v-if="child?.children?.length > 0"
            />

            <router-link  :to="child.link" v-else>
              <span>
                <span v-html="SidebarIcon[child.icon]"></span>
              </span>
              {{ child.name }}
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import SidebarIcon from "../../assets/json/Dashboard/SidebarIcon.js";

export default {
  name: "SubmenuView",
  props: {
    index: String,
    list: Object,
  },

  data() {
    return {
      SidebarIcon,
    };
  },
};
</script>

<style lang="scss" scoped>
.accordion-item {
  .accordion-header {
    .accordion-button {
      text-transform: capitalize;
      color: var(--white);
      font-size: var(--fs-14);
      font-weight: 500;
      padding: 10px 10px;
    

      span {
        margin-right: 18px;

        img {
          max-width: 18px;
          
        }
      }

      &:not(.collapsed) {
        color: var(--white);
        background-image: var(--gradient);
      }
    }
  }

  .accordion-body {
    background-color: var(--purpal);

    #submenu-list {
      padding-top: 10px;
      margin-top: 0;
    }
  }

  .accordion-button::after {
    background-image: url(/images/dashboard/icon/arrow-down.svg);
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    width: 27px;
    height: 27px;
    transition: all 0.5s ease;
  }

  .accordion-button:not(.collapsed)::after {
    transform: rotate(180deg);
    transition: all 0.5s ease;
  }

  .sidebar-list {
    padding: 0;
    margin-bottom: 0;
    margin-top: 35px;

    li {
      vertical-align: middle;

      a {
        color: var(--white);
        text-decoration: none;
        font-size: var(--fs-14);
        font-weight: 400;
        text-transform: capitalize;
        display: inline-block;
        width: 100%;
        padding: 10px 10px;
        padding-left: 25px;

        span {
          margin-right: 5px;
        }
      }
    }
  }
}
</style>
