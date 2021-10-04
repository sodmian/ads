<template>
  <div>
    <p class="lead">{{ $route.name }}</p>
    <div class="row">
      <div class="col-12 col-lg-6">
        <div class="input-group input-group-sm">
          <input type="hidden" name="token" :value="data.token">
          <input type="file" ref="file" accept=".csv" class="form-control" :class="csvUploadInputStateClass"
                 @change="csvPreload">
          <button class="btn" :class="csvUploadBtnStateClass" type="button"
                  :disabled="!data.file || data.errors.length > 0" @click="csvUpload">
            Загрузить
          </button>
        </div>
      </div>
    </div>
    <div class="mt-3 mb-0 alert alert-warning" :class="{'d-none': !data.errors.length}">
      <ul>
        <li v-for="error in data.errors">{{ error }}</li>
      </ul>
    </div>
    <div class="mt-3 alert alert-dark small" role="alert">
      <p class="alert-heading h5">Общие требования к файлу:</p>
      <p>
        &mdash; Формат поддерживаемого файла - CSV<br>
        &mdash; Первая колонка должна содержать текст объявления (длина строки не более
        {{ data.env.APP_CSV_HEADER_LEN }} символов)<br>
        &mdash; Вторая колонка должна содержать ваши контактные данные (длина строки не более
        {{ data.env.APP_CSV_CONTACTS_LEN }} символов)<br>
        &mdash; В качестве разделителя должен использоваться символ
        <mark>;</mark>
      </p>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "AdsForm",
  props: {
    prop: {
      env: {
        type: String,
        required: true
      },
      token: {
        type: String,
        required: true
      }
    }
  },
  data() {
    return {
      data: {
        errors: [],
        env: JSON.parse(this.prop.env),
        token: this.prop.token,
        file: ''
      }
    }
  },
  computed: {
    csvUploadInputStateClass: function () {
      return {
        'is-valid': this.data.file && !this.data.errors.length,
        'is-invalid': this.data.file && this.data.errors.length > 0
      }
    },
    csvUploadBtnStateClass: function () {
      return {
        'btn-outline-secondary': !this.data.file,
        'btn-outline-danger': this.data.errors.length,
        'btn-outline-success': this.data.file && !this.data.errors.length
      }
    }
  },
  methods: {
    csvPreload: function () {
      let self = this;
      self.data.file = self.$refs.file.files[0];
      self.data.errors = [];

      let formData = new FormData();
      formData.append('file', self.data.file);
      formData.append('token', self.data.token);

      axios.post('/index.php?route=ads.csvPreload',
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
      ).then(function (res) {
        if (res.data.errors.length > 0) {
          self.data.errors = res.data.errors;
        }
      }).catch(function () {
        alert('Не удалось проверить файл.')
      });
    },
    csvUpload: function () {
      let self = this;

      let formData = new FormData();
      formData.append('file', self.data.file);
      formData.append('token', self.data.token);

      axios.post('/index.php?route=ads.csvUpload',
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
      ).then(function (res) {
        if (res.data.errors.length > 0) {
          self.data.errors = res.data.errors;
        } else {
          self.$root.$emit('reload', res.data)
          alert('Объявления выгружены.')
        }
      }).catch(function () {
        alert('Не удалось загрузить файл.')
      });
    }
  }
}
</script>