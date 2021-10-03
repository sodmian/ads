<template>
  <div v-if="data.ads.length">
    <p class="lead">{{ $route.name }}</p>
    <table class="table table-bordered">
      <tr>
        <th data-date>Дата</th>
        <th data-text>Текст</th>
        <th data-contacts>Контакты</th>
      </tr>
      <tr v-for="ads in data.ads">
        <td>{{ ads.date | date }}</td>
        <td>{{ ads.text }}</td>
        <td>{{ ads.contacts }}</td>
      </tr>
    </table>
  </div>
  <div v-else>
    <div class="alert alert-dark small" role="alert">
      Здесь пока ничего нет, но вы можете уже сейчас
      <router-link to="ads-form" class="alert-link">добавить сюда объявление</router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: "AdsList",
  props: {
    prop: {
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
        ads: JSON.parse(this.prop.ads)
      }
    }
  },
  filters: {
    date: (value) => {
      if (!value) return ''
      let today = new Date(value.toString());
      return today.toLocaleDateString("ru-RU")
    }
  }
}
</script>