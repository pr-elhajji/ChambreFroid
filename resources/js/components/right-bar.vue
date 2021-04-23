<script>
import simplebar from "simplebar-vue";

/**
 * Right sidebar component
 */
export default {
  components: {simplebar},
  data() {
    return {
      config: {
        handler: this.handleRightBarClick,
        middleware: this.middleware,
        events: ["click"],
      },
      layout: this.$root.layout.type,
      width: this.$root.layout.width,
      sidebarType: this.$root.layout.sidebar,
      topbar: this.$root.layout.topbar,
      loader: this.$root.layout.loader,
    };
  },

  methods: {
    hide() {
      this.$parent.toggleRightSidebar();
    },
    // eslint-disable-next-line no-unused-vars
    handleRightBarClick(e, el) {
      this.$parent.hideRightSidebar();
    },
    // eslint-disable-next-line no-unused-vars
    middleware(event, el) {
      return !event.target.classList.contains("toggle-right");
    },
    changeLayout(layout) {
        this.sidebarType = "dark";
        this.topbar = "light";
      this.$root.changeLayout({
          type: layout,
          sidebar: "dark",
          topbar: "light"
      });
    },
    changeType(type) {
      return this.$root.changeLayout({ sidebar: type });
    },
    changeWidth(width) {
      return this.$root.changeLayout({ width: width });
    },
    changeTopbartype(value) {
      return this.$root.changeLayout({ topbar: value });
    },
    changeloader() {
      return this.$root.changeLayout({ loader: this.loader });
    },
  },
};
</script>

<template>

</template>

<style lang="scss"></style>
