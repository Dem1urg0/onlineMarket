<template>
  <div class="cart_menu">
    <img class="cart" src="/style/img/cart.svg" alt="cart" @click="showCart= !showCart">
    <div class="drop drop__cart" v-if="showCart">
      <div class="drop__browse__flex">
        <ul class="drop__menu">
          <drop-cart-item v-for="item of cartItems" :cart-item="item" :key="item.id" ref="cart-item"
                          @remove="removeFromCart(item)"></drop-cart-item>
        </ul>
      </div>
      <div class="drop__cart__price">
        <h3>Total</h3>
        <h3>{{ totalPrice }}</h3>
      </div>
      <a href="/order/checkout">
        <button class="drop__cart__button1">Checkout</button>
      </a>
      <a href="/cart/">
        <button class="drop__cart__button2">Go to cart</button>
      </a>
    </div>
  </div>
</template>

<script>
/**
 * Импорт компонента товара в меню корзине
 */
import DropCartItem from "./DropCartItem.vue";
import {removeProduct, getCart, total} from "../../../utils/CartUtils.js";
import { emitter } from "../../../main.js";

/**
 * Компонент выбрасывающегося меню корзины
 */
export default {
  name: "drop-cart",
  /**
   * Импорт компонента
   */
  components: {DropCartItem},
  /**
   * Реактивные данные компонента
   * @returns {{totalPrice: number, showCart: boolean, cartItems: *[]}}
   */
  data() {
    return {
      cartItems: [],
      showCart: false,
      totalPrice: 0,
    }
  },

  /**
   * Код выполняемый после монтирования компонента.
   * Получает данные корзины и общую стоимость
   */
  mounted() {
    this.cartItems = getCart();
    this.totalPrice = total(this.cartItems);
    emitter.on('cart-updated', this.updateCart);
  },
  beforeUnmount() {
    emitter.off('cart-updated', this.updateCart);
  },
  /**
   * Методы компонента
   */
  methods: {
    /**
     * Удаление товара из корзины (утилита cartUtils.js)
     */
    removeProduct,
    /**
     * Обновляет данные корзины и общую стоимость
     */
    updateCart() {
      this.cartItems = getCart();
      this.totalPrice = total(this.cartItems);
    },

    /**
     * Удаляет товар из корзины
     * @param product
     */
    removeFromCart(product) {
      removeProduct(product);
      this.cartItems = getCart();
      this.totalPrice = total(this.cartItems);
    }
  },
}
</script>


