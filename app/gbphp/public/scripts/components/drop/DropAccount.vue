<template>
  <button @click="show = !show" class="button Account">
    My Account
    <div class="drop_acc" v-if="show && (currentUser.id !== 0)">
      <li><a :href="getHrefUser(currentUser.id)">MY ACCOUNT</a></li>
      <li><a href="/order/all">MY ORDERS</a></li>
      <li><a href="/auth/logout">LOG-OUT</a></li>
    </div>
    <div class="drop_acc" v-if="show && (currentUser.id === 0)">
      <li><a href="/auth/">LOG-IN</a></li>
      <li><a href="/user/add/">REGISTER</a></li>
    </div>
  </button>
</template>

<script>
/**
 * Импорт функции для получения ссылки на страницу пользователя
 */
import { getHrefUser } from '../../utils/PathUtils.js';

/**
 * Компонент для отображения выпадающего меню с аккаунтом пользователя
 */
export default {
  name: "drop-account",

  /**
   * Пропсы компонента
   */
  props:{
    /**
     * Текущий пользователь
     */
    currentUser: {
      type: Object,
      required: true
    }
  },

  /**
   * Реактивные данные компонента
   * @returns {{show: boolean}}
   */
  data() {
    return {
      show: false,
    };
  },

  /**
   * Методы компонента
   */
  methods: {
    /**
     * Получить ссылку на страницу пользователя (pathUtils.js)
     */
    getHrefUser,
  },
}
</script>
