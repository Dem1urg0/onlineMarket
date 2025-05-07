<template>
  <div class="products" style="display: flex; justify-content: center">
    <section>
      <div class="products__type">
        <div class="products__type__category" @click="showBlock($event)">
          <h2>CATEGORY</h2>
          <div class="products__type__category__list">
            <p v-for="category in categoriesList"
               :class="{'active': filter.category.includes(category.name)}"
               @click="setActiveStatusCategory($event); searchByCategory(); firstPage()">{{ category.name }}</p>
          </div>
        </div>
        <div class="products__type__category" @click="showBlock($event)">
          <h2>BRANDS</h2>
          <div class="products__type__category__list">
            <p v-for="brand in brands" :class="{'active': filter.brands.includes(brand.name)}"
               @click="setActiveStatus($event); searchByBrands(); firstPage()">{{ brand.name }}</p>
          </div>
        </div>
        <div class="products__type__category" @click="showBlock($event)">
          <h2>DESIGNERS</h2>
          <div class="products__type__category__list">
            <p v-for="designer in designers" :class="{'active': filter.designers.includes(designer.name)}"
               @click="setActiveStatus($event); searchByDesigners(); firstPage()">{{ designer.name }}</p>
          </div>
        </div>
      </div>
    </section>
    <div class="products__container">
      <div class="products__container__filter">
        <div class="products__container__filter__types">
          <div class="products__container__filter__types__trending">
            <h2>TRENDING NOW</h2>
            <div class="products__container__filter__types__trending__list">
              <p v-for="(item, index) in topDesignersList" :class="{'active': filter.designers.includes(item.name)}"
                 @click="setActiveStatus($event); searchByDesigners(); firstPage()" :key="index">
                {{ item.name }}
              </p>
            </div>
          </div>
          <div style="height: 88px; width: 2px; background-color: #f3f3f3; margin: 0 10px;"></div>
          <div class="products__container__filter__types__size">
            <h2>SIZE</h2>
            <div class="products__container__filter__types__size__inputs">
              <div class="products__container__filter__types__size__inputs__div" v-for="size in sizesList">
                <p>{{ size.name }}</p>
                <input type="checkbox" :id="size.name" @click="changeSizeArr($event); searchBySize(); firstPage()">
              </div>
            </div>
          </div>
          <div style="height: 88px; width: 2px; background-color: #f3f3f3; margin: 0 10px;"></div>
          <div class="products__container__filter__types__gender">
            <h2>GENDER</h2>
            <div class="products__container__filter__types__gender__inputs">
              <select v-model="filter.gender" @change="searchByGender(); firstPage()">
                <option selected value="">Не выбран</option>
                <option v-for="gender in gendersList" :id="gender.name" :value="gender.name">{{ gender.name }}</option>
              </select>
            </div>
          </div>
          <div style="height: 88px; width: 2px; background-color: #f3f3f3; margin: 0 10px;"></div>
          <div class="products__container__filter__types__price">
            <h2>PRICE</h2>
            <div style="margin-top: 10px; display: flex">
              <input type="range" id="myRange" name="myRange" :min="filter.minPrice" :max="filter.maxPrice"
                     value="{{ filter.minPrice }}" v-model=filter.inputMin @input="searchByPrice(); firstPage()">
              <p style="margin-left: 10px;">{{ filter.inputMin }}</p>
            </div>
            <div style="margin-top: 10px; display: flex">
              <input type="range" id="myRange" name="myRange" :min="filter.minPrice" :max="filter.maxPrice"
                     value="{{ filter.maxPrice }}" v-model=filter.inputMax @input="searchByPrice(); firstPage()">
              <p style="margin-left: 10px;">{{ filter.inputMax }}</p>
            </div>
            <div class="products__container__filter__types__price__numbers">
              <p>$ {{ filter.minPrice }}</p>
              <p>$ {{ filter.maxPrice }}</p>
            </div>
          </div>
        </div>
        <div class="products__container__filter__sort">
          <div class="products__container__filter__sort__sittings">
            <p>Sort By</p>
            <select v-model="filter.sortType" @change="sortBy()">
              <option disabled="" selected="">sort</option>
              <option value="Name">Name</option>
              <option value="Price">Price</option>
            </select>
            <p>Show</p>
            <select v-model="render.renderCount" @change="updateRender(); firstPage();">
              <option selected="" value="6">6</option>
              <option value="9">9</option>
              <option value="12">12</option>
            </select>
          </div>
          <button class="products__container__filter__sort__search" v-if="render.renderType === 'default'"
                  @click=" firstPageForSearch(); search()">&#128269;
          </button>
        </div>
      </div>
      <div class="products__container__content">
        <h2 v-if="renderGoods.length === 0">NOT FOUND</h2>
        <div class="products-page__list">
          <prod-item
              v-for="good in renderGoods"
              :key="good.id"
              :good="good"
              ref="productPageItem"
          ></prod-item>
        </div>
      </div>
      <div class="products__container__footer">
        <div class="products__container__footer__page">
          <div>
            <button @click="changePage(1); pageUp()" :disabled="render.page === 1">&laquo;</button>
            <button v-for="page in render.renderPages" :class="{ active: page === render.page }"
                    :disabled="page === render.page" @click="changePage(page); getPageRender(); pageUp()">{{ page }}
            </button>
            <button @click="changePage(render.maxPages); pageUp()" :disabled="render.page === render.maxPages">&raquo;
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/**
 * Импортируем компонент товара
 */
import ProdItem from "@/js/components/product/ProdItem.vue";
/**
 * Импортируем функции для сортировки хранилища из utils/storageUtils.js
 */
import {sortStorage, sortStorageForGoods, addStoragesToGoods} from "@/js/utils/StorageUtils.js";


/**
 * Компонент страницы товаров
 */
export default {
  name: "prods-page",
  components: {ProdItem},
  /**
   * Пропсы компонента
   */
  props: {
    /**
     * Товары
     */
    goods: {
      type: Array,
      required: true
    },
    /**
     * Категории
     */
    categories: {
      type: Array,
      required: true
    },
    /**
     * Бренды
     */
    brands: {
      type: Array,
      required: true
    },
    /**
     * Дизайнеры
     */
    designers: {
      type: Array,
      required: true
    },
    /**
     * Топ дизайнеры
     */
    topDesigners: {
      type: Array,
      required: true
    },
    /**
     * Размеры
     */
    sizes: {
      type: Array,
      required: true
    },
    /**
     * Хранилище товаров
     */
    storage: {
      type: Object,
      required: true
    },
    /**
     * Максимальная цена
     */
    maxPrice: {
      type: Number,
      required: true
    },
    /**
     * Максимальное количество страниц
     */
    maxPages: {
      type: Number,
      required: false
    },
    /**
     * Тип рендера
     */
    renderType: {
      type: String,
      required: true
    },
    /**
     * Пол
     */
    gender: {
      type: String,
      required: false
    },
    /**
     * Категория
     */
    category: {
      type: [String, Array],
      required: false
    },
    /**
     * Бренд
     */
    brand: {
      type: [String, Array],
      required: false
    },
    /**
     * Дизайнер
     */
    designer: {
      type: [String, Array],
      required: false
    }
  },

  /**
   * Реактивные данные компонента
   * @returns {{gendersList: [{name: string},{name: string},{name: string}], designersList: *[], renderGoods: *[], goodsByCategory: *[], goodsByPrice: *[], goodsByGender: *[], goodsByBrands: *[], filter: {designers: *[], brands: *[], size: *[], gender: string, inputMax: number, sortType: string, inputMin: number, minPrice: number, maxPrice: number, category: string}, goodsBySort: *[], topDesignersList: *[], sizesList: *[], goodsBySize: *[], categoriesList: *[], goodsByDesigners: *[], brandsList: *[], render: {maxPages: number, renderCount: number, page: number, renderType: string, renderPages: *[]}}}
   */
  data() {
    return {
      localGoods: [],
      renderGoods: [],

      goodsByDesigners: [],
      goodsByCategory: [],
      goodsBySize: [],
      goodsByPrice: [],
      goodsByBrands: [],
      goodsBySort: [],
      goodsByGender: [],

      sizesList: [],
      designersList: [],
      brandsList: [],
      categoriesList: [],
      topDesignersList: [],
      gendersList: [
        {name: 'man'},
        {name: 'woman'},
        {name: 'unisex'}
      ],
      storageLocal: {},
      filter: {
        size: [],
        gender: '',
        sortType: '',

        minPrice: 0,
        maxPrice: 1,
        inputMin: 0,
        inputMax: 0,

        designers: [],
        brands: [],
        category: '',
      },
      render: {
        renderType: 'default',
        renderCount: 6,
        page: 1,
        maxPages: 1,
        renderPages: [],
      }
    }
  },

  /**
   * Код выполняемый при монтировании компонента
   * Инициализация данных: товаров, типа рендера, фильтров
   */
  mounted() {
    this.render.renderType = this.renderType;
    this.storageLocal = this.storage;
    this.initializeData();
    this.localGoods = this.goods;
    this.initializeGoods(this.localGoods, this.maxPages || 1);
    this.initializeGETFilter()
  },
  methods: {
    /**
     * Глобальные методы
     */
    // МЕТОДЫ РАБОТЫ С ДАННЫМИ ХРАНИЛИЩА
    /**
     * Сортировка хранилища товара
     */
    sortStorage,

    /**
     * Сортировка хранилища товара для всех товаров
     */
    sortStorageForGoods,

    /**
     * Добавление хранилища к товарам
     */
    addStoragesToGoods,

    // БАЗОВЫЕ МЕТОДЫ И ИНИЦИАЛИЗАЦИЯ
    /**
     * Проверка на тип рендера
     * @returns {boolean} - true, если много товаров
     */
    isMany() {
      return this.render.renderType === 'many';
    },
    /**
     * Инициализация товаров
     * @param goods - товары
     * @param pagesCount - количество страниц
     */
    initializeGoods(goods, pagesCount) {
      this.storageLocal = this.sortStorageForGoods(this.storageLocal);
      goods = this.addStoragesToGoods(goods, this.storageLocal);
      if (this.isMany()) {
        this.renderGoods = goods.slice(0, this.render.renderCount)
        this.goodsByDesigners = goods
        this.goodsByCategory = goods
        this.goodsBySize = goods
        this.goodsByPrice = goods
        this.goodsByBrands = goods
        this.goodsBySort = goods
        this.goodsByGender = goods
      } else {
        this.renderGoods = goods
      }
      this.render.maxPages = pagesCount || 1;
      this.getPageRender();
    },

    /**
     * Инициализация списков
     */
    initializeData() {
      this.designersList = this.designers;
      this.brandsList = this.brands;
      this.categoriesList = this.categories;
      this.topDesignersList = this.topDesigners;
      this.sizesList = this.sizes;

      this.filter.inputMax = this.maxPrice;
      this.filter.maxPrice = this.maxPrice;
    },

    /**
     * Инициализация фильтров по GET-параметрам
     */
    initializeGETFilter() {
      this.filter.gender = this.gender || '';
      this.searchByGender();
      this.filter.category = this.category || '';
      this.searchByCategory();
      if (this.brand?.length) {
        this.filter.brands = [this.brand];
        this.searchByBrands();
      }
      if (this.designer?.length) {
        this.filter.designers = [this.designer];
        this.searchByDesigners();
      }
    },

    // МЕТОДЫ ФИЛЬТРАЦИИ
    /**
     * Сортировка товаров по имени или цене
     */
    sortBy() {
      if (!this.isMany()) return;
      if (this.filter.sortType === "Name") {
        this.goodsBySort = this.localGoods.sort((a, b) => a.name > b.name ? 1 : -1);
      }
      if (this.filter.sortType === "Price") {
        this.goodsBySort = this.localGoods.sort((a, b) => a.price > b.price ? 1 : -1);
      }
      this.updateRender();
    },

    /**
     * Установка минимальной и максимальной цены
     */
    setMinMax() {
      const min = Math.min(this.filter.inputMin, this.filter.inputMax);
      const max = Math.max(this.filter.inputMin, this.filter.inputMax);
      this.filter.inputMin = min;
      this.filter.inputMax = max;
    },

    /**
     * Фильтрация по цене
     */
    searchByPrice() {
      this.setMinMax();
      if (!this.isMany()) return;

      this.goodsByPrice = this.localGoods.filter(good => good.price >= this.filter.inputMin
          && good.price <= this.filter.inputMax);
      this.updateRender();
    },

    /**
     * Фильтрация по категории
     */
    searchByCategory() {
      if (!this.isMany()) return;

      if (this.filter.category === '' || this.filter.category.length === 0) {
        this.goodsByCategory = this.localGoods;
      } else {
        const category_id = this.categoriesList.find(category => category.name === this.filter.category).id;
        this.goodsByCategory = this.localGoods.filter(good => good.category_id === category_id);
      }

      this.updateRender();
    },

    /**
     * Фильтрация по дизайнерам
     */
    searchByDesigners() {
      if (!this.isMany()) return;

      if (this.filter.designers.length === 0) {
        this.goodsByDesigners = this.localGoods;
      } else {
        this.goodsByDesigners = [];
        this.localGoods.forEach(good => {
          this.filter.designers.forEach(designer => {

            const designer_id = this.designersList.find(checkDesigner => checkDesigner.name === designer).id;

            if (good.designer_id === designer_id && this.goodsByDesigners.indexOf(good) === -1) {
              this.goodsByDesigners.push(good);
            }
          });
        })
      }
      this.updateRender();
    },

    /**
     * Фильтрация по бренду
     */
    searchByBrands() {
      if (!this.isMany()) return;

      if (this.filter.brands.length === 0) {
        this.goodsByBrands = this.localGoods;
      } else {
        this.goodsByBrands = [];
        this.localGoods.forEach(good => {
          this.filter.brands.forEach(brand => {

            const brand_id = this.brandsList.find(checkBrand => checkBrand.name === brand).id;

            if (good.brand_id === brand_id && this.goodsByBrands.indexOf(good) === -1) {
              this.goodsByBrands.push(good);
            }
          });
        })
      }
      this.updateRender();
    },

    /**
     * Фильтрация по размеру
     */
    searchBySize() {
      if (!this.isMany()) return;

      this.goodsBySize = [];

      this.localGoods.forEach(good => {
        this.filter.size.forEach(size => {

          if (good.storage[size] && this.goodsBySize.indexOf(good) === -1) {
            this.goodsBySize.push(good);
          }
        });
      })
      if (this.filter.size.length === 0) {
        this.goodsBySize = this.localGoods;
      }
      this.updateRender();
    },

    /**
     * Фильтрация по полу
     */
    searchByGender() {
      if (!this.isMany()) return;

      this.goodsByGender = [];

      if (this.filter.gender === '' || this.filter.gender.length === 0) {
        this.goodsByGender = this.localGoods;
      } else {
        this.goodsByGender = this.localGoods.filter(good => good.gender === this.filter.gender);
      }

      this.updateRender();
    },

    // МЕТОДЫ ПОИСКА И ОБНОВЛЕНИЯ ДАННЫХ
    /**
     * Обновить рендер
     */
    updateRender() {
      this.getRenderGoods();
      this.getPagesCount()
      this.getPageRender();
    },

    /**
     * Поиск товаров по параметрам и пагинации по api
     * @returns {Promise<void>}
     */
    async search() {
      if (this.isMany()) return;
      const params = {
        designers: this.filter.designers,
        brands: this.filter.brands,
        category: this.filter.category,
        sizes: this.filter.size,
        sort: this.filter.sortType.toLowerCase(),
        gender: this.filter.gender,

        price: {
          first: this.filter.inputMin,
          second: this.filter.inputMax,
        },
        page: {
          renderCount: this.render.renderCount || 6,
          page: this.render.page || 1,
        },
      };

      try {
        const response = await this.$parent.postJson('/api/good/getFilteredGoods', params);

        if (!response) {
          console.error('Некорректный ответ от API:', response);
          this.codeRes = 400;
          return;
        }

        switch (response.code) {
          case 200:
            this.localGoods = response.data;
            this.render.renderType = 'default'
            this.storageLocal = response.storage;
            this.initializeGoods(this.localGoods, response.pagesCount);
            break;
          case 400:
            console.log('Bad Request');
            break;
          case 500:
            console.log('Server Error');
            break;
          case 404:
            console.log('Not Found');
            this.render.page = 1;
            this.renderGoods = [];
            this.render.maxPages = 1;
            this.updateRender();
            break;
        }
      } catch (error) {
        console.error('Ошибка', error);
        this.codeRes = 400;
      }
    }
    ,

    // МЕТОДЫ РАБОТЫ СО СТРАНИЦАМИ
    /**
     * Смена страницы
     * @param number
     */
    changePage(number) {
      if (this.isMany()) {
        if (number < 1 || number > this.render.maxPages) return;
        this.render.page = number;
        this.getRenderGoods();
      } else {
        this.render.page = number; //default
        this.search();
      }
    }
    ,

    /**
     * Подсчет количества страниц
     */
    getPagesCount() {
      if (!this.isMany()) return;

      if (Math.ceil(this.goodsBySort.length / this.render.renderCount) !== 0) {
        this.render.maxPages = Math.ceil(this.goodsBySort.length / this.render.renderCount);
      }
    }
    ,

    /**
     * Первая страница
     */
    firstPage() {
      if (!this.isMany()) return;
      this.render.page = 1;
      this.getRenderGoods();
    }
    ,

    /**
     * Первая страница для поиска
     */
    firstPageForSearch() {
      if (this.isMany()) return;
      this.render.page = 1;
    }
    ,

    /**
     * Установка товаров рендера
     */
    getRenderGoods() {
      if (!this.isMany()) return;

      this.goodsBySort = this.localGoods.filter(good => this.goodsBySize.includes(good));
      this.goodsBySort = this.goodsBySort.filter(good => this.goodsByCategory.includes(good));
      this.goodsBySort = this.goodsBySort.filter(good => this.goodsByDesigners.includes(good));
      this.goodsBySort = this.goodsBySort.filter(good => this.goodsByBrands.includes(good));
      this.goodsBySort = this.goodsBySort.filter(good => this.goodsByPrice.includes(good));
      this.goodsBySort = this.goodsBySort.filter(good => this.goodsByGender.includes(good));

      this.renderGoods = this.goodsBySort.slice((this.render.page - 1) * this.render.renderCount, this.render.page * this.render.renderCount);
    }
    ,

    /**
     * Заполнения массива рендера страниц
     */
    getPageRender() {
      let p = this.render.page;
      let max = this.render.maxPages;
      this.render.renderPages = [];
      for (let i = p; i > 0 && (i > p - 3 || (p === max && i > p - 5) || (p === max - 1 && i > p - 4)); i--) {
        this.render.renderPages.unshift(i);
      }
      for (let i = p + 1; i <= max && (i < p + 3 || (p === 1 && i < p + 5) || (p === 2 && i < p + 4)); i++) {
        this.render.renderPages.push(i);
      }
    }
    ,

    /**
     * Переход на верх страницы
     */
    pageUp() {
      window.scrollTo(0, 200);
    }
    ,

    // ВСПОМОГАТЕЛЬНЫЕ МЕТОДЫ ДЛЯ ИНТЕРФЕЙСА
    /**
     * Показать блок с категориями по клику
     * @param event - событие
     */
    showBlock(event) {
      let block = event.target.nextElementSibling;
      if (block && block.classList.contains('products__type__category__list')) {
        block.style.display = block.style.display === 'block' ? 'none' : 'block';
      }
    }
    ,

    /**
     * Установить активный статус и Заполнить фильтр по бренду или дизайнеру
     * @param event - событие
     */
    setActiveStatus(event) {
      let block = event.target;

      let nameOfList = event.target.parentElement.previousElementSibling.innerText.toLowerCase();

      if (nameOfList === 'trending now') {
        nameOfList = 'designers';
      }

      let name = block.innerText;

      if (this.filter[nameOfList].includes(name)) {
        this.filter[nameOfList] = this.filter[nameOfList].filter(item => item !== name);
      } else {
        this.filter[nameOfList].push(name);
      }
    }
    ,

    /**
     * Установить активный статус и Заполнить фильтр по категории
     * @param event - событие
     */
    setActiveStatusCategory(event) {
      const block = event.target;
      const name = block.innerText;

      block.parentElement.querySelectorAll('.active')
          .forEach(element => element.classList.remove('active'));

      if (this.filter.category !== name) {
        this.filter.category = name;
        block.classList.add('active');
      } else {
        this.filter.category = '';
      }
    }
    ,

    /**
     * Заполнить фильтр по цене
     * @param event - событие
     */
    changeSizeArr(event) {
      let block = event.target;
      let size = event.target.id;
      if (this.filter.size.includes(size)) {
        this.filter.size = this.filter.size.filter(item => item !== size);
        event.target.checked = false;
      } else {
        this.filter.size.push(size);
      }
    }
    ,
  },
}
</script>


