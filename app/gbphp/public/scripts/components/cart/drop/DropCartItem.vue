<template>
  <li class="drop__cart__items">
    <div class="drop__cart__items__info">
      <a :href="getHrefGood(cartItem.id)"><img class="drop__cart__items__info__img" :src="hrefImg(cartItem.img)" alt="item"></a>
      <div class="drop__cart__items__info__text">
        <a :href="getHrefGood(cartItem.id)"><h3>{{ cartItem.name }}: {{ cartItem.color }}, {{ cartItem.size }}</h3></a>
        <a :href="getHrefGood(cartItem.id)"><img src="/style/img/star.png" alt="stars_cart"></a>
        <p>Price:{{ cartItem.price }}</p>
        <p>Count:{{ cartItem.count }}</p>
      </div>
    </div>
    <img class="close" src="/style/img/close.png" alt="close" @click="$emit('remove',cartItem)">
  </li>
</template>

<script>
/**
 * Импорт функций для работы с путями товаров
 */
import { getHrefGood, hrefImg } from "../../../utils/PathUtils";

/**
 * Компонент товара в выпадающем меню корзины
 */
export default {
  name: "drop-cart-item",
  /**
   * Пропсы компонента
   */
  props: ['cartItem', 'img'],

  /**
   * Методы компонента
   */
  methods: {
    /**
     * Получение ссылки на товар (утилита pathUtils.js)
     */
    getHrefGood,
    /**
     * Получение ссылки на изображение товара (утилита pathUtils.js)
     */
    hrefImg,
  },
}
</script>


