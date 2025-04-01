<template>
  <div class="fetured__list__product products-page__list__product">
    <img class="fetured__list__product_img" :src="hrefImg(good.img)">
    <div class="fetured__list__product__text">
      <a :href="getHrefGood(good.id)" class="fetured__list__product__text__name">{{ good.name }}</a>
      <a :href="getHrefGood(good.id)" class="fetured__list__product__text__price">$ {{ good.price }}<img
          src="/style/img/star.png"
          alt="stars"></a>
    </div>
    <div class="fetured__list__product__add">
      <div class="prod_characteristic_color" style="margin: 0">
        <h4 style="color: white">CHOOSE COLOR</h4>
        <select class="prod_characteristic_color_menu" v-model="chosenColor">
          <template v-if="chosenSize">
            <option  value="" disabled selected>Color</option>
            <option v-for="color in good.storage[chosenSize]" :key="color">
              {{ color }}
            </option>
          </template>
          <option v-if="!chosenSize" value="" disabled selected>Select size</option>
        </select>
      </div>
      <div class="prod_characteristic_size" style="margin: 26px 0 0">
        <h4 style="color: white">CHOOSE SIZE</h4>
        <select class="prod_characteristic_size_menu" v-model="chosenSize">
          <option value="" disabled selected>Size</option>
          <template v-if="good.storage">
            <option v-for="size in Object.keys(good.storage)">{{ size }}</option>
          </template>
        </select>
      </div>
      <button style="margin-top: 13px;" @click="addToCartWithParams(good); $root.$refs.dropCart.updateCart()">Add to Cart</button>
    </div>
  </div>
</template>

<script>
/**
 * Импортируем функции для работы с ссылками из utils/pathUtils.js
 */
import { hrefImg, getHrefGood } from "../../utils/PathUtils.js";
/**
 * Импортируем функции для добавления товара в корзину из utils/cartUtils.js
 */
import { addProduct } from "../../utils/CartUtils.js";


/**
 * Компонент товара на странице товаров
 */
export default {
  name: "prod-item",

  /**
   * Пропсы компонента
   */
  props: {
    good: {
      type: Object,
      required: true
    }
  },
  /**
   * Реактивные данные компонента
   * @returns {{chosenSize: string, chosenColor: string}}
   */
  data() {
    return {
      chosenSize: '',
      chosenColor: ''
    }
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
    addProduct,

    /**
     * Добавление товара в корзину с параметрами
     * @param good - товар
     */
    addToCartWithParams(good) {
      if (!this.chosenColor || !this.chosenSize) {
        return;
      }
      good.size = this.chosenSize;
      good.color = this.chosenColor;
      good.storage.unset;
      addProduct(good);
    }
  },
}
</script>

