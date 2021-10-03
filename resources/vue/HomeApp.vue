<template>
  <div class="ads-block">
    <h1 class="font-monospace">>> Доска объявлений</h1>
    <div class="ads-block__tab">
      <div class="ads-block__tab-links">
        <router-link to="ads-list">
          <span>Все объявления</span>
          <span class="badge bg-dark">{{ data.ads.length }}</span>
        </router-link>
        <router-link to="ads-form">
          <span>Добавить новые</span>
        </router-link>
      </div>
      <div class="ads-block__tab-content">
        <router-view
            :prop="{ ads: JSON.stringify(data.ads), token: data.token, env: JSON.stringify(data.env) }"></router-view>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "HomeApp",
  props: {
    prop: {
      env: {
        type: String,
        required: true
      },
      token: {
        type: String,
        required: true
      },
      ads: {
        type: String,
        required: false,
        default: ""
      }
    }
  },
  data() {
    return {
      data: {
        env: JSON.parse(this.prop.env),
        token: this.prop.token,
        ads: JSON.parse(this.prop.ads)
      }
    }
  },
  mounted() {
    this.$root.$on('reload', (res) => {
      this.data.ads = res.data;
    })
  },
}
</script>

<style lang="scss" scoped>
@import "../scss/mixins";

::v-deep .ads-block {
  &__tab {
    margin-top: $mt-tab_m;
    display: flex;
    flex-direction: row;

    @media screen and (max-width: 992px) {
      flex-direction: column;
    }

    &-links {
      width: 20%;
      text-align: center;

      @media screen and (max-width: 992px) {
        width: 100%;
        display: flex;
        flex-direction: row;
      }

      a {
        width: 100%;
        padding: 10px;
        display: inline-block;
        color: #333;
        text-decoration: none;

        span {
          &:first-child {
            text-decoration: underline;
          }
        }

        &.router-link-active {
          padding-right: 8px;
          border-width: 0 2px 0 0;
          border-color: $bg-shadow;
          border-style: dashed;
          box-shadow: 12px 5px 10px -6px $bg-shadow;
          background: $bg-light;
          font-weight: bold;
          text-decoration: none;

          @media screen and (max-width: 992px) {
            padding-right: 10px;
            padding-bottom: 8px;
            border-width: 0 0 2px 0;
            box-shadow: none;
          }

          span {
            &:first-child {
              text-decoration: none;
            }
          }
        }
      }
    }

    &-content {
      width: 80%;
      padding: 10px;
      box-shadow: 0 5px 10px -6px $bg-shadow;
      background: $bg-light;

      @media screen and (max-width: 992px) {
        width: 100%;
      }

      .table {
        th {
          &[data-date] {
            width: 100px;
          }

          &[data-text] {
            width: auto;
          }

          &[data-contacts] {
            width: 25%;
          }
        }

        td {
          padding: 5px;
        }
      }
    }
  }
}
</style>