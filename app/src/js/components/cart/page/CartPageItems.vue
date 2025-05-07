<template>
  <div>
    <h2 v-if="cartItems.length === 0">Cart is empty</h2>
    <div v-if="cartItems.length !== 0" v-for="item of cartItems" :key="item.id">
      <div class="products__item">
        <img class="products__item__img" style="width: 100px; height: 115px;" :src=item.img alt="img">
        <div class="products__item__name">
          <h2>{{ item.name }}</h2>
          <p><b>Color:</b> &ensp;{{ item.color }} <br>
            <b>Size:</b> &ensp;{{ item.size }} </p>
        </div>
        <p class="products__item__uprice">$ {{ item.price }}</p>
        <p class='products__item__quantity'>
          <button class="products__item__quantity__button" @click="addToCart(item); emitTotal()">+</button>
          {{item.count}}
          <button class="products__item__quantity__button" @click="decCart(item); emitTotal()">-</button>
        </p>
        <p class="products__item__shipping">FREE</p>
        <p class="products__item__sprice">{{ item.price * item.count }}</p>
        <div class="products__item__button__box">
          <button class="products__item__action" @click=""><img
              class="products__item__actionimg" src="@/assets/img/close.png" alt="close" @click="removeFromCart(item); emitTotal()">
          </button>
        </div>
      </div>
      <div class="line products__item__line"></div>
    </div>
    <h3 style="display: flex;justify-content: end;color: #656565;">TOTAL PRICE: {{totalPrice}}</h3>
  </div>
</template>

<script>
/**
 * Импорт функций для работы с корзиной
 */
import { getCart, addProduct, decProduct, removeProduct, clearCart, total } from "@/js/utils/CartUtils.js";

/**
 * Компонент товаров страницы корзины
 */
export default {
  name: "cart-page-items",
  /**
   * Реактивные данные компонента
   * @returns {{totalPrice: number, cartItems: *[]}}
   */
  data() {
    return {
      cartItems: [],
      totalPrice: 0,
    }
  },

  /**
   * Код выполняемый после монтирования компонента.
   * Получает данные корзины и общую стоимость
   */
  mounted() {
    this.cartItems = getCart();
    this.emitTotal();
  },

  /**
   * Методы компонента
   */
  methods: {
    /**
     * Получение пути к изображению
     * @param img
     * @returns {string}
     */
    /**
     * Добавление товара в корзину (утилита cartUtils.js)
     * @param product
     */
    addProduct,
    addToCart(product) {
      addProduct(product);
      this.cartItems = getCart();
      this.totalPrice = total(this.cartItems);
    },
    /**
     * Уменьшение количества товара в корзине на 1 (утилита cartUtils.js)
     * @param product
     */
    decProduct,
    decCart(product) {
      decProduct(product);
      this.cartItems = getCart();
      this.totalPrice = total(this.cartItems);
    },

    /**
     * Удаление товара из корзины (утилита cartUtils.js)
     * @param product
     */
    removeProduct,
    removeFromCart(product) {
      removeProduct(product);
      this.cartItems = getCart();
      this.totalPrice = total(this.cartItems);
    },

    /**
     * Эмит события изменения общей стоимости корзины
     */
    emitTotal() {
      this.totalPrice = total(this.cartItems);
      this.$emit('total-changed', this.totalPrice);
    },

    /**
     * Очистка корзины (утилита cartUtils.js)
     */
    clearCart,
    clearAllCart() {
      clearCart();
      this.cartItems = getCart();
      this.emitTotal();
    }
  },
}
</script>
