var chats = [];
var chat = {
    key: '',
    id: 1,
    name: '',
    plan: '',
    namberofusers: 100,
    maxusers: 200,
}


var testing = {
    username: 'player',
    chats: [
        { chatid: 12054, key: '' }
    ],

    settings: {

    }
};

//Get chats avalaible to user BEGIN
var availableChats = function () {
    console.log("Avalable Chats");
}

availableChats()

var settings = {
    data: "",

    get: function () {
        if (localStorage.xchat_settings) {
            this.data = JSON.parse(localStorage.getItem('xchat_settings'));
        } else {
            localStorage.setItem('xchat_settings', 'new');
            this.data = localStorage.getItem('xchat_settings');
        }
    },

    createChats: function () {
        this.get();

        for (var i = 0; i < this.data.chats.length; i++) {

            var chat = {
                chatid: this.data.chats[i].chatid,
                key: this.data.chats[i].key
            }

            chats.push(chat);
        }
    },

    save: function () {
        localStorage.setItem('xchat_settings', JSON.stringify(this.data));
    }
};


var commands = {
    clear: function () {
    },

    authorize: function (chatid) {
        for (var i = 0; i < settings.data.chats.length; i++) {
            console.log(chatid);
            if (settings.data.chats[i].chatid == chatid) {

                var auth = settings.data.chats[i];
                auth.name = settings.data.username;
                console.log("auth \n");
                console.log(JSON.stringify(auth));
            }
        }
    }
};