<template>
  <div class="fetured__list">
    <prod-item ref="productsPageItem" v-for="item in goods" :good="item"></prod-item>
  </div>
</template>

<script>
/**
 * Импортируем компонент товара
 */
import ProdItem from "./ProdItem.vue";
/**
 * Импортируем функции для работы с изображениями и ссылками из utils/pathUtils.js
 */
import { hrefImg, getHrefGood } from "../../utils/PathUtils.js";
/**
 * Импортируем функции сортировки хранилища из utils/storageUtils.js
 */
import { sortStorage, sortStorageForGoods, addStoragesToGoods } from "../../utils/StorageUtils.js";


/**
 * Компонент товаров на главной странице
 */
export default {
  name: "prod-on-home-page",
  components: {ProdItem},

  /**
   * Реактивные данные компонента
   * @returns {{goods: *[], storage: *[]}}
   */
  data() {
    return {
      goods: [],
      storage: []
    }
  },
  /**
   * Код выполняемый при монтировании компонента
   * Получение товаров и хранилища и инициализация товаров
   */
  mounted() {
    this.getGoods()
        .then(() => this.getStorage())
        .then(() => this.initializeGoods())
        .catch(error => console.error(error));
  },
  /**
   * Методы компонента
   */
  methods: {
    /**
     * Глобальные методы
     */
    hrefImg,
    getHrefGood,
    sortStorage,
    sortStorageForGoods,
    addStoragesToGoods,

    /**
     * Инициализация товаров и хранилища
     */
    initializeGoods() {
      this.storage = this.sortStorageForGoods(this.storage);
      this.goods = this.addStoragesToGoods(this.goods, this.storage);
    },

    /**
     * Получение товаров с сервера
     * @returns {Promise<void>}
     */
    async getGoods() {
      const params = {
        page: {
          renderCount: 4,
          page: 1,
        },
      }
      try {
        const response = await this.$parent.postJson('/api/good/getFilteredGoods', params)

        if (!response) {
          console.error('Некорректный ответ от API:', response);
          return;
        }
        switch (response.code) {
          case 200:
            this.goods = response.data;
            break;
          default:
            console.log('Error fetching products data:', response.msg);
        }
      } catch (error) {
        console.error('Ошибка:', error);
        this.codeRes = 400;
      }
    },

    /**
     * Получение хранилища товаров с сервера
     * @returns {Promise<void>}
     */
    async getStorage() {
      try {
        const response = await this.$parent.postJson('/api/good/getStorage', this.goods)

        if (!response) {
          console.error('Некорректный ответ от API:', response);
          return;
        }
        switch (response.code) {
          case 200:
            this.storage = response.data;
            break;
          default:
            console.log('Error fetching storage data:', response.msg);
        }
      } catch (error) {
        console.error('Ошибка:', error);
        this.codeRes = 400;
      }
    },
  },
}
</script>


