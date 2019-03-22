<template>
<div>
    <div class="chat-wrapper">
        <ul class="chat">
            <li class="left clearfix" v-for="message in messages">
                <div class="chat-body clearfix">
                    <div class="header">
                        <strong class="primary-font">
                            {{ message.user.name }}
                        </strong>
                    </div>
                    <p>
                        {{ message.message }}
                    </p>
                </div>
            </li>
        </ul>
    </div>
    <div class="input-group">
        <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="addMessage">

        <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="addMessage">
                Send
            </button>
        </span>
    </div>
    </div>
</template>

<script>
function scrollDOwn(selector) {
                let cW = document.querySelector(selector);
                cW.scrollTop = cW.childNodes[0].offsetHeight;
            }
    //window.onload = setTimeout(()=>{scrollDOwn('.chat-wrapper')},1000);
    export default {
        
        props: ['user'],

        data() {
            return {
                newMessage: '',
                messages: []
            }
        },

        methods: {
            fetchMessages() {
            api.call('get', '/api/messages')
               .then(response => {
                   this.messages = response.data.data;
               });
            },
            
            addMessage(message) {
                let data = {
                    user: auth.user,
                    message: this.newMessage
                };

                this.messages.push(data);
                this.newMessage = '';

                let sendData = {};
                sendData['message'] = data.message;

                api.call('post', '/api/messages', sendData)
                .then(response => {
                    console.log(response.data);
                    //scrollDOwn('.chat-wrapper');
                    console.log(document.querySelector('.chat-wrapper'));
                })
                .catch(({errors}) => {
                    console.log(errors);
                });
            }
        },
        
        created() {
            this.fetchMessages();
        }, 

        mounted() {
            Event.$on('messageListener', (data) => {
                console.log(this.messages);
                console.log(data);
                this.messages.push({
                    message: data.message.message,
                    user: data.user
                });
                //setTimeout(()=>{scrollDOwn('.chat-wrapper')},10)
                console.log(document.querySelector('.chat-wrapper'));
            });
        },
        updated() {
            scrollDOwn('.chat-wrapper');
        }
    }

</script>