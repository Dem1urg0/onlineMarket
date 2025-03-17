function getHrefGood(id) {
    return '/good/one?id=' + id;
}

Vue.component('drop-filter', {
    data: {
        show: false,
    },
    methods: {
        getHrefGood,
    },
    props: ['filteredWoman', 'filteredMan'],
    template: `
      <div class="drop drop__browse">
        <div class="drop__browse__flex" v-if="filteredWoman.length != 0">
          <h3 class="drop__h3 drop__browse__h3">Women</h3>
          <ul class="drop__menu">
            <li v-for="item of filteredWoman" :key="filteredWoman.indexOf('item')">
              <a :href="getHrefGood(item.id)" class="drop__link">{{ item }}</a> 
            </li>
          </ul>
        </div>
        <div class="drop__browse__flex" v-if="filteredMan.length != 0">
          <h3 class="drop__h3 drop__browse__h3">Men</h3>
          <ul class="drop__menu">
            <li v-for="item of filteredMan" :key="filteredMan.indexOf('item')">
              <a :href="getHrefGood(item.id)" class="drop__link">{{ item }}</a></li>
          </ul>
        </div>
        <img style="width: 236px" v-if="filteredWoman.length === 0" src="html/img/no_product_found.png" alt="no_product_img">
      </div>
    `
})
Vue.component('drop-def', {
    props: ['categories', 'brands', 'designers'],
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
    mounted() {
        this.initData();
    },
    methods: {
        initData() {
            this.brandsNames = this.brands;
            this.categoriesNames = this.categories;
            this.designersNames = this.designers;
        },
        showDrop() {
            this.show = !this.show;
        },
        getLinkForTitle(title) {
            let link = '';
            if (!title || !this.titles.includes(title)) {
                return link;
            }

            if (this.genders.includes(title)) {
                return link = '/good/all?gender=' + title.toLowerCase();
            }

        },
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
    template: `
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
    `
})
Vue.component('account', {
    props: ['currentUser'],
    data() {
        return {
            show: false,
        };
    },
    methods: {
        getUserHref(id) {
            return '/user/one?id=' + id;
        }
    },
    template: `
      <button @click="show = !show" class="button Account">
        My Account
        <div class="drop_acc" v-if="show && (currentUser.id !== 0)">
            <li><a :href="getUserHref(currentUser.id)">MY ACCOUNT</a></li>
            <li><a href="/order/all">MY ORDERS</a></li>
            <li><a href="/auth/logout">LOG-OUT</a></li>
        </div>
        <div class="drop_acc" v-if="show && (currentUser.id === 0)">
            <li><a href="/auth/">LOG-IN</a></li>
            <li><a href="/user/add/">REGISTER</a></li>
        </div>
      </button>
    `
});
