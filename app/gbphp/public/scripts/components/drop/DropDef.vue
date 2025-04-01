<template>
  <ul class="menu">
    <li class="menu__list"><a href="/home" class="menu__link">Home</a></li>
    <div v-for="item of titles">
      <li class="menu__list"><a :href="getLinkForTitle(item)" class="menu__link">{{ item }}</a>
        <div v-if="!genders.includes(item)" class="drop drop_middle">
          <div class="drop__flex">
            <h3 class="drop__h3 drop__browse__h3">{{ item }}</h3>
            <ul class="drop__menu">
              <li v-if="item === 'Categories'" v-for="name in categoriesNames"><a :href="getLinkForFilter(item, name.name)" class="drop__link">{{ name.name }}</a></li>
              <li v-if="item === 'Brands'" v-for="name in brandsNames"><a :href="getLinkForFilter(item, name.name)" class="drop__link" >{{ name.name }}</a></li>
              <li v-if="item === 'Designers'" v-for="name in designersNames"><a :href="getLinkForFilter(item, name.name)" class="drop__link">{{ name.name }}</a></li>
            </ul>
          </div>
        </div>
      </li>
    </div>
  </ul>
</template>

<script>
/**
 * Компонент для отображения выпадающего меню с фильтрами для товаров
 */
export default {
  name: "drop-def",

  /**
   * Пропсы компонента
   */
  props:{
    /**
     * Категории товаров
     */
    categories: {
      type: Array,
      required: true
    },
    /**
     * Бренды товаров
     */
    brands: {
      type: Array,
      required: true
    },
    /**
     * Дизайнеры товаров
     */
    designers: {
      type: Array,
      required: true
    }
  },

  /**
   * Реактивные данные компонента
   * @returns {{designersNames: *[], show: boolean, brandsNames: *[], categoriesNames: *[], genders: string[], titles: string[]}}
   */
  data() {
    return {
      show: false,
      titles: [
        'Man',
        'Woman',
        'Unisex',
        'Categories',
        'Brands',
        'Designers'
      ],
      genders: [
        'Man',
        'Woman',
        'Unisex',
      ],
      brandsNames: [],
      categoriesNames: [],
      designersNames: [],
    }
  },

  /**
   * Код, который выполняется при монтировании компонента
   * Инициализация данных
   */
  mounted() {
    this.initData();
  },

  /**
   * Методы компонента
   */
  methods: {

    /**
     * Инициализация данных из пропсов
     */
    initData() {
      this.brandsNames = this.brands;
      this.categoriesNames = this.categories;
      this.designersNames = this.designers;
    },

    /**
     * Показать/скрыть выпадающее меню
     */
    showDrop() {
      this.show = !this.show;
    },

    /**
     * Получить ссылку для заголовка
     * @param title - название заголовка
     * @returns {string} - ссылка
     */
    getLinkForTitle(title) {
      let link = '';
      if (!title || !this.titles.includes(title)) {
        return link;
      }

      if (this.genders.includes(title)) {
        return link = '/good/all?gender=' + title.toLowerCase();
      }

    },

    /**
     * Получить ссылку для фильтра
     * @param title - название заголовка
     * @param name - название фильтра
     * @returns {string} - ссылка
     */
    getLinkForFilter(title, name) {
      let link = '';
      if (!title || !name || !this.titles.includes(title)) {
        return link;
      }

      if (title === 'Categories') {
        const category = this.categoriesNames.find(item => item.name === name);
        return link = '/good/all?category=' + category.name;
      }
      if (title === 'Brands') {
        const brand = this.brandsNames.find(item => item.name === name);
        return link = '/good/all?brands=' + brand.name;
      }
      if (title === 'Designers') {
        const designer = this.designersNames.find(item => item.name === name);
        return link = '/good/all?designers=' + designer.name;
      }
    }
  },
}
</script>

