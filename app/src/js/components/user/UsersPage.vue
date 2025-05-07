<template>
  <div class="users">
    <div class="users__container">
      <div class="users__nav">
        <div class="users__nav__block">
          <p>Render Count:</p>
          <div class="button-group">
            <select v-model="sort.renderCount" @change="getRenderUsers();   getPagesCount()">
              <option value="5">5</option>
              <option value="10">10</option>
              <option value="15">15</option>
            </select>
          </div>
        </div>
        <div class="separator"></div>
        <div class="users__nav__block">
          <p>Sort by:</p>
          <select v-model="sort.param.type">
            <option value="" disabled selected>Choose</option>
            <option value="id">ID</option>
            <option value="login">Login</option>
            <option value="role">Role</option>
          </select>
          <input v-if="sort.param.type !== 'role'" v-model="sort.param.value" type="text"
                 placeholder="Enter value" @input="searchByType(); ">
          <select v-else v-model="sort.param.value" @change="searchByType(); ">
            <option value="1">Admin</option>
            <option value="0">User</option>
          </select>
        </div>
        <div class="separator"></div>
        <div class="users__nav__block">
          <p>Orders Range:</p>
          <div class="range-group">
            <input type="range" :min="sort.ordersNumber.minCount" :max="sort.ordersNumber.maxCount"
                   v-model="sort.ordersNumber.inputs.first" @input="searchByOrders(); ">
            <input type="number" v-model="sort.ordersNumber.inputs.first" placeholder="Min" @input="searchByOrders(); ">
          </div>
          <div class="range-group">
            <input type="range" :min="sort.ordersNumber.minCount" :max="sort.ordersNumber.maxCount"
                   v-model="sort.ordersNumber.inputs.second" @input="searchByOrders(); ">
            <input type="number" v-model="sort.ordersNumber.inputs.second" placeholder="Max"
                   @input="searchByOrders(); ">
          </div>
        </div>
        <div class="separator" v-if="renderType === 'default'"></div>
        <button class="users__nav__search" v-if="renderType === 'default'" @click="firstPageForSearch() ;search()">
          &#128269;
        </button>
      </div>
      <h1 class="users__title">Users</h1>
      <div class="users__render">
        <div class="user" v-for="user in usersRender" :key="user.id">
          <h2><a :href="getHrefUser(user.id)">{{ user.login }}</a></h2>
          <p>{{ user.role === '1' ? 'Role: Admin' : 'Role: User' }}</p>
        </div>
        <h1 v-if="users.length === 0">Not Found</h1>
      </div>
    </div>
    <div class="pagination">
      <div class="products__container__footer__page">
        <div>
          <button @click="changePage(1);  getPageRender();" :disabled="sort.page === 1">&laquo;</button>
          <button v-for="page in sort.renderPages" :class="{ active: page === sort.page }"
                  :disabled="page === sort.page"
                  @click="changePage(page); getPageRender();">{{ page }}
          </button>
          <button @click="changePage(sort.maxPages);  getPageRender();" :disabled="sort.page === sort.maxPages">
            &raquo;
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/**
 * Импортируем необходимые зависимости
 */
import {getHrefUser} from '@/js/utils/PathUtils.js';

/**
 * Компонент для отображения пользователей
 */
export default {
  name: 'users-page',

  /**
   * Пропсы компонента
   */
  props: {
    /**
     * Пользователи
     */
    users: {
      type: Array,
      required: true
    },
    /**
     * Максимальное количество заказов
     */
    maxOrdersCount: {
      type: Number,
      required: true
    },
    /**
     * Количество страниц
     */
    pagesCount: {
      type: Number,
      required: true
    },
    /**
     * Тип рендера страниц
     */
    renderPageType: {
      type: String,
      required: true
    }
  },

  /**
   * Реактивные данные компонента
   * @returns {{userSort: *[], usersSearchByOrder: *[], usersSearchByType: *[], usersRender: *[], sort: {ordersNumber: {inputs: {first: number, second: number}, minCount: number, maxCount: number}, param: {type: string, value: string}, maxPages: number, renderCount: number, page: number, renderPages: *[]}, renderType: string}}
   */
  data() {
    return {
      usersRender: [],
      usersSearchByOrder: [],
      usersSearchByType: [],
      userSort: [],
      sort: {
        param: {
          type: '',
          value: '',
        },
        ordersNumber: {
          minCount: 0,
          maxCount: 0,
          inputs: {
            first: 0,
            second: 0,
          }
        },
        renderCount: 5,
        page: 1,
        maxPages: 1,
        renderPages: [],
      },
      renderType: '',
    }
  },

  /**
   * Код, который выполнится после монтирования компонента
   * Инициализация пропсов и компонента
   */
  mounted() {
    this.sort.ordersNumber.maxCount = this.maxOrdersCount;
    this.sort.ordersNumber.inputs.second = this.maxOrdersCount;
    this.renderType = this.renderPageType;
    this.initializeComponent(this.users, this.pagesCount);
  },

  /**
   * Методы компонента
   */
  methods: {
    /**
     * Получение ссылки на пользователя
     * @param id - id пользователя
     * @returns {string}
     */
    getHrefUser,

    /**
     * Проверка на метод отображения
     * @returns {boolean}
     */
    isMany() {
      return this.renderType === 'many';
    },

    /**
     * Инициализация пользователей
     * @param users - пользователи
     * @param pagesCount - количество страниц
     */
    initializeComponent(users, pagesCount) {
      if (this.isMany()) {
        this.usersRender = users.slice(0, this.sort.renderCount)
        this.usersSearchByOrder = users
        this.usersSearchByType = users
        this.usersSort = users
      } else {
        this.usersRender = users
      }
      this.sort.maxPages = pagesCount || 1;
      this.getPagesCount(); //?
      this.getPageRender()
    },

    /**
     * Установка минимального и максимального значения
     */
    setMinMax() {
      const min = Math.min(this.sort.ordersNumber.inputs.first, this.sort.ordersNumber.inputs.second);
      const max = Math.max(this.sort.ordersNumber.inputs.first, this.sort.ordersNumber.inputs.second);
      this.sort.ordersNumber.inputs.first = min;
      this.sort.ordersNumber.inputs.second = max;
    },

    /**
     * Поиск по поисковой строке с разными параметрами на выбор
     */
    searchByType() {
      if (!this.isMany()) return;

      let regexp = new RegExp(this.sort.param.value, 'i'); // many
      this.usersSearchByType = this.users.filter(user => regexp.test(user[this.sort.param.type]));
      this.updateRender();
    },

    /**
     * Поиск по количеству заказов
     */
    searchByOrders() {
      this.setMinMax();
      if (!this.isMany()) return;

      this.usersSearchByOrder = this.users.filter(user => user.count_orders >= this.sort.ordersNumber.inputs.first // many
          && user.count_orders <= this.sort.ordersNumber.inputs.second);
      this.updateRender();

    },

    /**
     * Ререндер пользователей
     */
    getRenderUsers() {
      if (!this.isMany()) return;
      this.usersSort = this.usersSearchByOrder.filter(user => this.usersSearchByType.includes(user)); // many
      this.usersRender = this.usersSort.slice((this.sort.page - 1) * this.sort.renderCount, this.sort.page * this.sort.renderCount);
    },

    /**
     * Смена страницы
     * @param number - новый номер страницы
     */
    changePage(number) {
      if (this.isMany()) {
        if (number < 1 || number > this.sort.maxPages) return; // many
        this.sort.page = number;
        this.getRenderUsers();
      } else {
        this.sort.page = number; //default
        this.search();
      }
    },

    /**
     * Смена страницы на первую
     */
    firstPage() {
      if (!this.isMany()) return;
      this.sort.page = 1;
    },

    /**
     * Смена страницы на первую для поиска
     */
    firstPageForSearch() {
      if (this.isMany()) return;
      this.sort.page = 1;
    },

    /**
     * Получение количества страниц при 'many' типе рендера
     */
    getPagesCount() {
      if (!this.isMany()) return;

      if (Math.ceil(this.usersSort.length / this.sort.renderCount) !== 0) { // many
        this.sort.maxPages = Math.ceil(this.usersSort.length / this.sort.renderCount);
      }
    },

    /**
     * Обновление рендера данных
     */
    updateRender() {
      this.firstPage();
      this.getRenderUsers();
      this.getPagesCount();
      this.getPageRender()
    },

    /**
     * Заполнение массива страниц для рендера
     */
    getPageRender() {
      let p = this.sort.page;
      let max = this.sort.maxPages;
      this.sort.renderPages = [];
      for (let i = p; i > 0 && (i > p - 3 || (p === max && i > p - 5) || (p === max - 1 && i > p - 4)); i--) {
        this.sort.renderPages.unshift(i);
      }
      for (let i = p + 1; i <= max && (i < p + 3 || (p === 1 && i < p + 5) || (p === 2 && i < p + 4)); i++) {
        this.sort.renderPages.push(i);
      }
    },

    /**
     * Поиск пользователей через сервер api
     * @returns {Promise<void>}
     */
    async search() {

      if (this.isMany()) return;
      const params = {
        searchByParams: {
          type: this.sort.param.type,
          value: this.sort.param.value,
        },
        searchByOrders: {
          first: this.sort.ordersNumber.inputs.first,
          second: this.sort.ordersNumber.inputs.second,
        },
        pageInfo: {
          renderCount: this.sort.renderCount || 5,
          page: this.sort.page || 1,
        },
      };

      try {
        const response = await this.$parent.postJson('/api/admin/user/getFilteredUsers', params);

        if (!response) {
          console.error('Некорректный ответ от API:', response);
          this.codeRes = 400;
          return;
        }

        switch (response.code) {
          case 200:
            this.renderType = 'default'
            this.initializeComponent(response.data, response.pagesCount);
            break;
          default:
            this.renderType = 'default'
            this.initializeComponent([], 1);
            break;
        }

      } catch (error) {
        console.error('Ошибка', error);
        this.codeRes = 400;
      }
    }
  }
}
</script>