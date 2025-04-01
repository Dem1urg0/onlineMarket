<template>
  <div class="prod_slider">
    <div class="prod_slider__left"><a href="#"><i class="prod_slider__left__arrow fa-solid fa-angle-left"></i></a>
    </div>
    <img class="prod_slider__img" src="/style/img/prod_img.jpg" alt="promo_img">
    <div class="prod_slider__right"><a href="#"><i class="prod_slider__left__arrow fa-solid fa-angle-right"></i></a>
    </div>
    <div class="prod_info">
      <h3>WOMEN COLLECTION</h3>
      <div class="prod_info_line"></div>
      <h2>{{ item.name }}</h2>
      <p>Compellingly actualize fully researched processes before proactive outsourcing. Progressively syndicate
        collaborative architectures before cutting-edge services. Completely visualize parallel core competencies
        rather
        than exceptional portals.</p>
      <section class="prod_info_block">
        <h4>MATERIAL:<span> COTTON</span></h4>
        <h4>DESIGNER:<span> BINBURHAN</span></h4>
      </section>
      <h2 class="prod_price">$ {{ item.price }}</h2>
      <div class="prod_line_long"></div>
      <div class="prod_characteristic">
        <div class="juctcont">
          <div class="prod_characteristic_color">
            <h4>CHOOSE COLOR</h4>
            <select class="prod_characteristic_color_menu" v-model="chosenColor">
              <template v-if="chosenSize">
                <option value="" disabled selected>Color</option>
                <option v-for="color in storage[chosenSize]" :key="color">
                  {{ color }}
                </option>
              </template>
              <option v-if="!chosenSize" value="" disabled selected>Select size</option>
            </select>
          </div>
          <div class="prod_characteristic_size">
            <h4>CHOOSE SIZE</h4>
            <select class="prod_characteristic_size_menu" v-model="chosenSize">
              <option value="" disabled selected>Size</option>
              <template v-if="storage">
                <option v-for="size in Object.keys(storage)">{{ size }}</option>
              </template>
            </select>
          </div>
          <div class="prod_characteristic_quanity">
            <h4>QUANTITY</h4>
            <input class="prod_characteristic_quanity_input" type="number" min="1" max="10" v-model="item.count">
          </div>
        </div>
        <button class="to_cart" @click="addToCart(item, chosenColor, chosenSize)">Add to Cart</button>
        <div v-if="error" class="prod__error">Please, choose color, size and quantity</div>
      </div>
    </div>
  </div>
</template>


<script>
/**
 * Импортируем функцию сортировки хранилища из utils/storageUtils.js
 */
import { sortStorage } from "../../utils/StorageUtils";
/**
 * Импортируем функцию добавления товара в корзину из utils/cartUtils.js
 */
import { addProduct } from "../../utils/CartUtils";
import { emitter } from "../../main.js";

/**
 * Компонент страницы товара
 */
export default {
  name: "prod-page",

  /**
   * Пропсы компонента
   */
  props: {
    /**
     * Товар
     */
    good: {
      type: Object,
      required: true
    },
    /**
     * Хранилище товара
     */
    typesInStorage: {
      type: Object,
      required: true
    }
  },

  /**
   * Реактивные данные компонента
   * @returns {{item: *[], chosenSize: undefined, chosenColor: undefined, storage: *[], error: boolean}}
   */
  data() {
    return {
      item: [],
      storage: [],
      chosenColor: undefined,
      chosenSize: undefined,
      error: false,
    }
  },

  /**
   * Код выполняемый при монтировании компонента
   */
  mounted() {
    this.item = this.good
    this.storage = this.sortStorage(this.typesInStorage)
  },

  /**
   * Методы компонента
   */
  methods: {
    /**
     * Сортировка хранилища товара (метод из глобального контекста)
     */
    sortStorage,
    /**
     * Добавление хранилища к товарам (метод из cartUtils.js)
     */
    addProduct,
    /**
     * Добавление товара в корзину
     * @param item - товар
     * @param color - цвет
     * @param size - размер
     */
    addToCart(item, color, size) {
      this.error = false;
      if (!color || !size || !item.count || item.count < 1) {
        this.error = true;
        return;
      }
      item.size = this.chosenSize;
      item.color = this.chosenColor;

      addProduct(item);
      emitter.emit('cart-updated');
      this.chosenColor = undefined;
      this.chosenSize = undefined;
      item.count = 1;
    },
  },
}
</script>

