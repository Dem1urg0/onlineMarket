//One product page
function sortStorage(storage) {
    let result = {};
    for (let i = 0; i < storage.length; i++) {
        const item = storage[i];
        const size = item.size;
        const color = item.color;

        if (!result[size]) {
            result[size] = [];
        }
        result[size].push(color);
    }
    return result;
}

function sortStorageForGoods(storages) {
    return Object.entries(storages).reduce((result, [key, value]) => {
        result[key] = this.sortStorage(value);
        return result;
    }, {});
}

function addStoragesToGoods(goods, storages) {
    goods.forEach(good => {
        if (storages[good.id]) {
            Vue.set(good, 'storage', storages[good.id]); // реактивное добавление свойства
        }
    });
    return goods;
}

Vue.component('one-good', {
    props: ['good', 'typesInStorage'],
    data() {
        return {
            item: [],
            storage: [],
            chosenColor: undefined,
            chosenSize: undefined,
            error: false,
        }
    },
    mounted() {
        this.item = this.good
        this.storage = this.sortStorage(this.typesInStorage)
    },
    methods: {
        sortStorage,
        addToCart(item, color, size) {
            this.error = false;
            if (!color || !size || !item.count || item.count < 1) {
                this.error = true;
                return;
            }
            item.size = this.chosenSize;
            item.color = this.chosenColor;

            addProduct(item);
            this.$root.$emit('cart-updated');
            this.chosenColor = undefined;
            this.chosenSize = undefined;
            item.count = 1;
        },
    },
    template: `
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
                    <option  value="" disabled selected>Color</option>
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
      </div>`
})
//Products of recommendation block in One product page //todo
Vue.component('prod-recom', {
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
    methods: {
        getHrefGood,
    },
    template: `
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
    `
})
//Products in home page
Vue.component('product', {
    data() {
        return {
            goods: [],
            storage: []
        }
    },
    mounted() {
        this.getGoods()
            .then(() => this.getStorage())
            .then(() => this.initializeGoods())
            .catch(error => console.error(error));
    },
    methods: {
        getImgSrc,
        getHrefGood,
        sortStorage,
        sortStorageForGoods,
        addStoragesToGoods,

        initializeGoods() {
            this.storage = this.sortStorageForGoods(this.storage);
            this.goods = this.addStoragesToGoods(this.goods, this.storage);
        },

        async getGoods() {
            const params = {
                page: {
                    renderCount: 4,
                    page: 1,
                },
            }
            try {
                const response = await this.$parent.postJson('/api/good/getFilteredGoods', params)

                if (!response) {
                    console.error('Некорректный ответ от API:', response);
                    return;
                }
                switch (response.code) {
                    case 200:
                        this.goods = response.data;
                        break;
                    default:
                        console.log('Error fetching products data:', response.msg);
                }
            } catch (error) {
                console.error('Ошибка:', error);
                this.codeRes = 400;
            }
        },

        async getStorage() {
            try {
                const response = await this.$parent.postJson('/api/good/getStorage', this.goods)

                if (!response) {
                    console.error('Некорректный ответ от API:', response);
                    return;
                }
                switch (response.code) {
                    case 200:
                        this.storage = response.data;
                        break;
                    default:
                        console.log('Error fetching storage data:', response.msg);
                }
            } catch (error) {
                console.error('Ошибка:', error);
                this.codeRes = 400;
            }
        },

    },
    template: `
      <div class="fetured__list">
              <products-page-item ref="productsPageItem" v-for="item in goods" :good="item"></products-page-item>
      </div>
    `
})
//Products page
Vue.component('products-page', {
    props: ['goods', 'categories', 'brands',
        'designers', 'topDesigners', 'sizes',
        'storage', 'maxPrice', 'maxPages',
        'renderType', 'gender', 'category',
        'brand', 'designer'],
    data() {
        return {
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
    mounted() {
        this.render.renderType = this.renderType;
        this.initializeData();
        this.initializeGoods(this.goods, this.maxPages || 1);
        this.initializeGETFilter()
    },
    methods: {
        // БАЗОВЫЕ МЕТОДЫ И ИНИЦИАЛИЗАЦИЯ
        /**
         * Проверка на тип рендера
         * @returns {boolean}
         */
        isMany() {
            return this.render.renderType === 'many';
        },
        /**
         * Инициализация товаров
         * @param goods
         * @param pagesCount
         */
        initializeGoods(goods, pagesCount) {
            this.storage = this.sortStorageForGoods(this.storage);
            goods = this.addStoragesToGoods(goods, this.storage);
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
                this.goodsBySort = this.goods.sort((a, b) => a.name > b.name ? 1 : -1);
            }
            if (this.filter.sortType === "Price") {
                this.goodsBySort = this.goods.sort((a, b) => a.price > b.price ? 1 : -1);
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

            this.goodsByPrice = this.goods.filter(good => good.price >= this.filter.inputMin
                && good.price <= this.filter.inputMax);
            this.updateRender();
        },
        /**
         * Фильтрация по категории
         */
        searchByCategory() {
            if (!this.isMany()) return;

            if (this.filter.category === '') {
                this.goodsByCategory = this.goods;
            } else {
                const category_id = this.categoriesList.find(category => category.name === this.filter.category).id;
                this.goodsByCategory = this.goods.filter(good => good.category_id === category_id);
            }

            this.updateRender();
        },
        /**
         * Фильтрация по дизайнерам
         */
        searchByDesigners() {
            if (!this.isMany()) return;

            if (this.filter.designers.length === 0) {
                this.goodsByDesigners = this.goods;
            } else {
                this.goodsByDesigners = [];
                this.goods.forEach(good => {
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
                this.goodsByBrands = this.goods;
            } else {
                this.goodsByBrands = [];
                this.goods.forEach(good => {
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

            this.goods.forEach(good => {
                this.filter.size.forEach(size => {

                    if (good.storage[size] && this.goodsBySize.indexOf(good) === -1) {
                        this.goodsBySize.push(good);
                    }
                });
            })
            if (this.filter.size.length === 0) {
                this.goodsBySize = this.goods;
            }
            this.updateRender();
        },

        /**
         * Фильтрация по полу
         */
        searchByGender() {
            if (!this.isMany()) return;

            this.goodsByGender = [];

            if (this.filter.gender === '') {
                this.goodsByGender = this.goods;
            } else {
                this.goodsByGender = this.goods.filter(good => good.gender === this.filter.gender);
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

            const responce = await this.$parent.postJson('/api/good/getFilteredGoods', params);

            switch (responce.code) {
                case 200:
                    this.goods = responce.data;
                    this.renderType = 'default'
                    this.storage = responce.storage;
                    this.initializeGoods(this.goods, responce.pagesCount);
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
        },

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
        },
        /**
         * Подсчет количества страниц
         */
        getPagesCount() {
            if (!this.isMany()) return;

            if (Math.ceil(this.goodsBySort.length / this.render.renderCount) !== 0) {
                this.render.maxPages = Math.ceil(this.goodsBySort.length / this.render.renderCount);
            }
        },

        /**
         * Первая страница
         */
        firstPage() {
            if (!this.isMany()) return;
            this.render.page = 1;
            this.getRenderGoods();
        },

        firstPageForSearch() {
            if (this.isMany()) return;
            this.render.page = 1;
        },

        /**
         * Установка товаров рендера
         */
        getRenderGoods() {
            if (!this.isMany()) return;

            this.goodsBySort = this.goods.filter(good => this.goodsBySize.includes(good));
            this.goodsBySort = this.goodsBySort.filter(good => this.goodsByCategory.includes(good));
            this.goodsBySort = this.goodsBySort.filter(good => this.goodsByDesigners.includes(good));
            this.goodsBySort = this.goodsBySort.filter(good => this.goodsByBrands.includes(good));
            this.goodsBySort = this.goodsBySort.filter(good => this.goodsByPrice.includes(good));
            this.goodsBySort = this.goodsBySort.filter(good => this.goodsByGender.includes(good));

            this.renderGoods = this.goodsBySort.slice((this.render.page - 1) * this.render.renderCount, this.render.page * this.render.renderCount);
        },

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
        },

        pageUp() {
            window.scrollTo(0, 200);
        },

        // ВСПОМОГАТЕЛЬНЫЕ МЕТОДЫ ДЛЯ ИНТЕРФЕЙСА
        /**
         * Показать блок с категориями
         * @param event
         */
        showBlock(event) {
            let block = event.target.nextElementSibling;
            if (block.classList.contains('products__type__category__list')) {
                block.style.display = block.style.display === 'block' ? 'none' : 'block';
            }
        },

        /**
         * Установить активный статус и Заполнить фильтр по бренду или дизайнеру
         * @param event
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
        },

        /**
         * Установить активный статус и Заполнить фильтр по категории
         * @param event
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
        },

        /**
         * Заполнить фильтр по цене
         * @param event
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
        },

        // МЕТОДЫ РАБОТЫ С ДАННЫМИ ХРАНИЛИЩА
        /**
         * Сортировка хранилища товара
         */
        sortStorage,

        sortStorageForGoods,

        addStoragesToGoods,
    },
    template:
        `
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
                            <option v-for="gender in gendersList" :id="gender.name" :value="gender.name">{{gender.name}}</option>
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
            <button class="products__container__filter__sort__search" v-if="render.renderType === 'default'" @click=" firstPageForSearch(); search()">&#128269;</button>
            </div>
        </div>
        <div class="products__container__content">
            <h2 v-if="renderGoods.length === 0">NOT FOUND</h2>
            <div class="products-page__list">
            <products-page-item 
                v-for="good in renderGoods" 
                :key="good.id" 
                :good="good"
                ref="productPageItem"
            ></products-page-item>
        </div>
        </div>
        <div class="products__container__footer">
            <div class="products__container__footer__page">
                <div> 
                    <button @click="changePage(1); pageUp()" :disabled="render.page === 1">&laquo;</button>
                    <button v-for="page in render.renderPages" :class="{ active: page === render.page }" :disabled="page == render.page" @click="changePage(page); getPageRender(); pageUp()">{{ page }}</button>
                    <button @click="changePage(render.maxPages); pageUp()" :disabled="render.page === render.maxPages">&raquo;</button>
                </div>               
            </div>
        </d0
    </div>
    </div>
    `
})

//Items for products page
Vue.component('products-page-item', {
    props: ['good'],
    data() {
        return {
            chosenSize: '',
            chosenColor: ''
        }
    },
    methods: {
        getImgSrc,
        getHrefGood,
        addProduct,
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
    template: `
    <div class="fetured__list__product products-page__list__product">
        <img class="fetured__list__product_img" :src="getImgSrc(good.img)">
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
    `
})