<template>
  <div class="recomend_items">
    <div class="fetured__list__product" v-for="item of items">
      <div class="fetured__list__product__flex">
        <img class="fetured__list__product_img" :src="'/style/img/prod_items' + item.img + '.png' "
             style="width: auto">
      </div>
      <div class="fetured__list__product__text">
        <a :href="getHrefGood(item.id)" class="fetured__list__product__text__name">{{ item.name }}</a>
        <a :href="getHrefGood(item.id)" class="fetured__list__product__text__price">$ {{ item.price }} <img
            src="/style/img/star.png"
            alt="stars"></a>
      </div>
      <button class="fetured__list__product__add" @click="addProduct(item)">Add to Cart</button>
    </div>
  </div>
</template>

<script>
/**
 * Импортируем функции для работы с ссылками из utils/pathUtils.js
 */
import { getHrefGood } from "../../utils/PathUtils.js";

/**
 * Компонент рекомендуемых товаров на странице товара
 */
export default {
  name: "prod-page-recommend",
  /**
   * Реактивные данные компонента
   * @returns {{items: {prod4: {img: number, price: number, name: string, id: number}, prod3: {img: number, price: number, name: string, id: number}, prod2: {img: number, price: number, name: string, id: number}, prod1: {img: number, price: number, name: string, id: number}}}}
   */
  data() {
    return {
      items: {
        prod1: {
          id: 981,
          img: 1,
          name: 'Mango People T-shirt',
          price: 52
        },
        prod2: {
          id: 982,
          img: 2,
          name: 'Mango People T-shirt',
          price: 52
        },
        prod3: {
          id: 983,
          img: 3,
          name: 'Mango People T-shirt',
          price: 52
        },
        prod4: {
          id: 984,
          img: 4,
          name: 'Mango People T-shirt',
          price: 52
        }
      }

    }
  },
  /**
   * Методы компонента
   */
  methods: {
    /**
     * Получение ссылки на товар (метод из глобального контекста)
     */
    getHrefGood,
  },
}
</script>


