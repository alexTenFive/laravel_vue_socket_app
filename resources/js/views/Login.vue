<template>
    <div @keyup.enter="login">
        <h1>Login</h1>

        <p>
            <label for="username">Email</label>

            <input type="text" name="username" v-model="username">
        </p>

        <p>
            <label for="password">Пароль</label>

            <input type="password" name="password" v-model="password">
        </p>

        <button @click="login">Вход</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            username: '',
            password: '',
        };
    },

    methods: {
        login() {
            let data = {
                username: this.username,
                password: this.password
            };

            axios.post('/api/login', data)
                 .then(({ data }) => {
                     auth.login(data.token, data.user);

                     this.$router.push('/chat');
                 })
                 .catch(({ response }) => {
                     alert(response.data.message);
                 })
        }
    }
}
</script>