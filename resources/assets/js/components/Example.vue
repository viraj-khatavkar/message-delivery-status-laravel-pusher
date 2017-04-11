<template>
<div class="container" id="example">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading" id="accordion">
                    <span class="glyphicon glyphicon-comment"></span> Chat
                    <div class="btn-group pull-right">
                        <a type="button" class="btn btn-default btn-xs" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </a>
                    </div>
                </div>
            <div class="panel-collapse collapse" id="collapseOne">
                <div class="panel-body">
                    <ul class="chat">
                        <li class="left clearfix" v-for="conversation in conversations">
                        <span class="chat-img pull-left">
                            <img src="http://placehold.it/50/FA6F57/fff&text=ME" alt="User Avatar" class="img-circle" v-if="conversation.user.id == user.id" />
                            <img src="http://placehold.it/50/55C1E7/fff&text=U" class="img-circle" v-else>
                        </span>
                            <div class="chat-body clearfix">
                                <div class="header">
                                    <strong class="primary-font">{{ conversation.user.name }}</strong> 
                                    <small class="pull-right text-muted" v-if="conversation.user.id == user.id">
                                        <span class="glyphicon glyphicon-time"></span>{{ conversation.status }}
                                    </small>
                                </div>
                                <p>
                                    {{ conversation.message }}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." v-model="message" @keyup.enter="sendMessage"/>
                        <span class="input-group-btn">
                            <button class="btn btn-warning btn-sm" id="btn-chat" @click.prevent="sendMessage">
                                Send</button>
                        </span>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                'message': '',
                'conversations': []
            }
        },
        mounted() {
            this.getConversations();
            this.listen();
        },
        methods: {
            sendMessage() {
                axios.post('/conversations', {message: this.message})
                    .then(response => this.conversations.push(response.data));

                    this.message = '';
            },
            getConversations() {
                axios.get('/conversations').then((response) => this.conversations = response.data);  
            },
            listen() {
                Echo.private('chat')
                    .listen('NewMessage', (e) => {
                        this.conversations.push(e);
                        axios.post('/conversations/'+ e.id +'/delivered');
                    })
                    .listen('MessageDelivered', (e) => {
                        _.find(this.conversations, { 'id': e.id }).status = e.status;
                    });
            }
        }
    }
</script>
