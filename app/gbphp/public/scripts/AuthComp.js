/**
 * Компонент авторизации
 *
 * @component auth-form
 */
Vue.component('auth-form', {

    /**
     * Ожидаемые передаваемые свойства в компонент
     */
    props: ['action'],

    /**
     * Регистрация данных компонента
     * @returns {{password: {error: null, value: string}, login: {error: null, value: string}}}
     */
    data() {
        return {
            login: {
                value: '',
                error: null
            },
            password: {
                value: '',
                error: null
            }
        };
    },

    /**
     * Методы компонента
     */
    methods: {
        /**
         * Получить название действия с заглавной буквы
         * @returns {string} - Название действия с заглавной буквы
         */
        getUpCaseAction() {
            return this.action.charAt(0).toUpperCase() + this.action.slice(1);
        },

        /**
         * Валидация логина
         * @returns {string} - возвращает ошибку, если она есть
         */
        validateLogin() {
            const login = this.login.value;

            if (typeof login !== 'string') {
                return this.login.error = 'Login must be a string.';
            }

            if (login.length < 3 || login.length > 20) {
                return this.login.error = 'Login must be between 3 and 20 characters.';
            }

            const loginRegex = /^[a-zA-Z0-9_.]+$/;
            if (!loginRegex.test(login)) {
                return this.login.error = 'Login must contain only letters, numbers, dots and underscores.';
            }

            this.login.error = null;
        },

        /**
         * Валидация пароля
         * @returns {string} - возвращает ошибку, если она есть
         */
        validatePassword() {
            const password = this.password.value;

            if (typeof password !== 'string') {
                return this.password.error = 'Password must be a string.';
            }

            if (password.length < 8 || password.length > 32) {
                return this.password.error = 'Password must be between 8 and 32 characters.';
            }

            if (!/[A-Z]/.test(password)) {
                return this.password.error = 'Password must contain at least one uppercase letter.';
            }

            if (!/[a-z]/.test(password)) {
                return this.password.error = 'Password must contain at least one lowercase letter.';
            }

            if (!/[0-9]/.test(password)) {
                return this.password.error = 'Password must contain at least one digit.';
            }

            if (!/[!@#$%^&*()]/.test(password)) {
                return this.password.error = 'Password must contain at least one special character (!@#$%^&*()).';
            }

            this.password.error = null;
        },

        /**
         * Выполнить действие - вход или регистрация (в зависимости от переданного action)
         * @returns {Promise<void>}
         */
        doAction: async function () {

            if (this.action === 'register') {
                this.validateLogin();
                this.validatePassword();
            }

            if (this.login.error || this.password.error) {
                return;
            }

            let url

            switch (this.action) {
                case 'login':
                    url = '/api/auth/login';
                    break;
                case 'register':
                    url = '/api/auth/register';
                    break;
                default:
                    location.href = '/';
            }
            try {
                const response = await this.$root.postJson(url, {
                    login: this.login.value,
                    password: this.password.value
                })

                if (!response) {
                    console.error('Некорректный ответ от API:', response);
                    this.codeRes = 400;
                    return;
                }

                switch (response.code) {
                    case 200:
                        if (this.action === 'register') {
                            alert('Вы успешно зарегистрированы. Теперь вы можете войти.');
                            location.href = '/auth/login';
                        }
                        location.reload()
                        break;
                    default:
                        alert('Ошибка: ' + response.msg);
                }

                console.log('form submitted');
            } catch (error) {
                console.error('Ошибка', error);
                this.codeRes = 400;
            }
        }
    },
    /**
     * Шаблон компонента
     */
    template: `
    <div class="form__block">
        <div class="container">
            <h1 class="form__title">{{ getUpCaseAction() }}</h1>
            <div>
                <div class="form__field">
                    <label for="name" class="form__label">Login:</label>
                    <input type="text" id="name" name="name" class="form__input" v-model="login.value" required>
                    <p style="color: darkred" v-if="login.error" class="error">{{ login.error }}</p>
                </div>
                <div class="form__field">
                    <label for="password" class="form__label">Password:</label>
                    <input type="password" id="password" name="password" class="form__input" v-model="password.value" required>
                    <p style="color: darkred" v-if="password.error" class="error">{{ password.error }}</p>
                </div>
                <button @click="doAction()" class="form__button">{{ getUpCaseAction() }}</button>
            </div>
        </div>
    </div>
    `
});