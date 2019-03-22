<template>
    <div>
        <div>
            <router-link to="/chat">Chat</router-link>
        </div>

        <div v-if="authenticated && user">
            <p>Hello, {{ user.name }}</p>

            <button @click="logout">Logout</button>       
        </div>
        
        <router-link to="/login" v-else>Login</router-link>
        
        <div style="margin-top: 2rem">
            <router-view></router-view>            
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            authenticated: auth.check(),
            user: auth.user,
        };
    },

    methods: {
        logout() {
            auth.logout();
        },

    },
    mounted() {
        Event.$on('userLoggedIn', () => {
            this.authenticated = true;
            this.user = auth.user;
        });

        Event.$on('userLoggedOut', () => {
            this.authenticated = false;
            this.user = null;
            this.$router.push('/login');
        });
    },

    
}

window.Echo.private('chat')
    .listen('MessagePushed', (data) => {
        Event.$emit('messageListener', data);
    });
</script>