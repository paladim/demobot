(function() {

chats_list_box = $('.chats-list');
messages_box = $('.messages') ;
current_chat = "-1";


function periodic() {
	make_ajax('index.php?c=telegram&m=get_chats_update', get_chats_update);
}
setInterval(periodic, 6000);

function get_chats_update(json){
	for(var i in json)
	{
	     var id = json[i].chat_id;
	     make_ajax('index.php?c=telegram&m=get_chat', get_chat, {"chat_id" : id});
	     var chats = $(".chat");
	     for(var j in chats){
		if (chats[j].id != id) $("#"+chats[j].id+" .info-msg-event").removeClass().addClass("info-msg-event info-msg-event_hid");
	     }
	     if (id == current_chat) chat_click($("#"+id).get(0),1);
	}
	if (json.length == 0) {
	     var chats = $(".chat");
	     for(var j in chats){
		$("#"+chats[j].id+" .info-msg-event").removeClass().addClass("info-msg-event info-msg-event_hid");
	     }
	}
	
}

function get_chat(json){
	for(var i in json)
	{
	     var id = json[i].chat_id;
	     var html = json[i].chat_html;
	     element = $("#"+id);
	     if (element.length){
		element.unbind();		
		element.remove();
	     }
	     chats_list_box.append(html);
             bind_click("#"+id,chat_click);
	     $("#"+id+" .info-msg-event").removeClass("info-msg-event_hid");
	     if (current_chat == id){
		$("#"+current_chat).addClass("chat_active");
             }
	}
	$("abbr.timeago").timeago();
}

function make_ajax(url,handle = null,data = {}, async = true) {
	var html = $.ajax({
	  url: url,
	  method: "GET",
	  data: data,
	  dataType: "json",
	  async: async,
	  success: function(json) {
	     if (handle) handle(json);
		}
	});
};
 

function get_chats() {
	//make_ajax('index.php?c=telegram&m=get_chats', add_to_chats_list_box);
	make_ajax('index.php?c=telegram&m=get_chats', get_chat);
}

function add_to_chats_list_box(json) {
	for(var i in json)
	{
	     var id = json[i].chat_id;
	     var html = json[i].chat_html;
	     chats_list_box.prepend(html);
             bind_click("#"+id,chat_click);
	}
	$("abbr.timeago").timeago();
}

function bind_click(id,handle){
	$(id).click(function() {handle(this)});
}

function chat_click(id,quickly = null){
	old_chat = current_chat;
	if ((quickly == null) && (current_chat != id.id)){
		messages_box.html('');
		current_chat = id.id
		make_ajax("index.php?c=telegram&m=get_messages&chat_id="+id.id,add_messages_for_messages_box);
		$("#"+current_chat).addClass("chat_active");
		$("#"+old_chat).removeClass("chat_active");
		click_chat_modify_star(current_chat);
	}
	if ((quickly != null) && (current_chat == id.id)){
		str = $('.message').get(0).id;
		msg_id = str.substring(7, str.length - -1);
		make_ajax("index.php?c=telegram&m=get_messages&chat_id="+id.id,add_messages_for_messages_box, {"message_id": msg_id});
	}
}

function add_messages_for_messages_box(json){
	for(var i in json)
	{
	     var id = json[i].message_id;
	     var looked = json[i].message_looked;
	     var html = json[i].message_html;
	     messages_box.prepend(html);
	     if (looked == "0") { 
		$("#message"+id).onVisibleScroll(function(o){
			str = o.get(0).id;
			id = str.substring(7, str.length - -1);
			make_ajax("index.php?c=telegram&m=looked&message_id="+id);
		},".messages")
	     }
	}
	$("abbr.timeago").timeago();
}

jQuery.fn.onVisibleScroll = function (trigger,container) {
    	var o = $(this[0]);
    	var c = $(container);
    	//if (o.length < 1) return o;
   	var o_top = null;
    	var o_height = null;
    	var c_top = null;
    	var c_height = null;
	var o_id = this[0].id;
	var flag_exit = false;

    	var timer = setInterval(function () {
		o_top = o.offset().top;	
		o_height = o.height();
		c_top = c.offset().top;	
		c_height = c.height();

		if ( (o_top+5 > c_top) && ((o_top + o_height - 10) < (c_top + c_height))) flag_exit = true;

		if (flag_exit == true){
			console.log(o_top);
			console.log(c_top);
			trigger(o);
			clearInterval(timer);
		}
	}, 5000);

    	return o;
};

function send_message(o = null){
	if (current_chat != "-1") {
		make_ajax('index.php?c=telegram&m=send_message', null, {"chat_id": current_chat, "text": $('.out-message').val()}, false);
		chat_click($("#"+current_chat).get(0),1);
	}
	$(".out-message").val("");
}

$('.out-message').keyup(function(e){
    if(e.keyCode == 13)
    {
	send_message();
    }
});


function send_modify_selected() {
	if (current_chat != "-1") {
		make_ajax('index.php?c=telegram&m=selected', modify_selected, {"chat_id": current_chat}, true);
	}
}

function modify_selected(json) {
	if (json.status == true && json.chat_id == current_chat) {
		modify_star(json.selected);
		modify_chat_star(json.chat_id,json.selected);
	}
}

function click_chat_modify_star(chat_id){
	var _flag = $("#star"+chat_id+".chat-star-img__selected");
	if (_flag.length){
		modify_star(1);
	} else {
		modify_star(0);
	}
}

function modify_chat_star(chat_id,selected) {
	if (selected == 0) {
	     $("#star"+chat_id).removeClass("chat-star-img__selected");
	} else {
	     $("#star"+chat_id).addClass("chat-star-img__selected");
	}
}

function modify_star(selected) {
	if (selected == 0) {
	     $("#star").removeClass("star-img__selected");
	} else {
	     $("#star").addClass("star-img__selected");
	}
}

bind_click(".send-out-message", send_message);
bind_click("#star", send_modify_selected);

get_chats();

periodic();

$("abbr.timeago").timeago();
//var menuId = $( "ul.nav" ).first().attr( "id" );

//
//    init: function() {
//      this.cacheDOM();
//      this.bindEvents();
//     this.render();
//  },
// cacheDOM: function() {
//      this.$chatHistory = $('.chat-history');
//      this.$button = $('button');
//      this.$textarea = $('#message-to-send');
//      this.$chatHistoryList = this.$chatHistory.find('ul');
//    },
//    bindEvents: function() {
//      this.$button.on('click', this.addMessage.bind(this));
//      this.$textarea.on('keyup', this.addMessageEnter.bind(this));
//    },
//    render: function() {
//      this.scrollToBottom();
//      if (this.messageToSend.trim() !== '') {
//        var template = Handlebars.compile($("#message-template").html());
//        var context = {
//          messageOutput: this.messageToSend,
//          time: this.getCurrentTime()
//        };
//
//        this.$chatHistoryList.append(template(context));
//        this.scrollToBottom();
//        this.$textarea.val('');
//
//        // responses
//        var templateResponse = Handlebars.compile($("#message-response-template").html());
//        var contextResponse = {
//          response: this.getRandomItem(this.messageResponses),
//          time: this.getCurrentTime()
//        };
//
//        setTimeout(function() {
//          this.$chatHistoryList.append(templateResponse(contextResponse));
//          this.scrollToBottom();
//        }.bind(this), 1500);
//
//      }
//
//    },
//
//    addMessage: function() {
//      this.messageToSend = this.$textarea.val()
//      this.render();
//    },
//    addMessageEnter: function(event) {
//      // enter was pressed
//      if (event.keyCode === 13) {
//        this.addMessage();
//      }
//    },
//    scrollToBottom: function() {
//      this.$chatHistory.scrollTop(this.$chatHistory[0].scrollHeight);
//    },
//    getCurrentTime: function() {
//      return new Date().toLocaleTimeString().
//      replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
//    },
//    getRandomItem: function(arr) {
//      return arr[Math.floor(Math.random() * arr.length)];
//    }
//
//  };
//
//  chat.init();
//
//  var searchFilter = {
//    options: {
//      valueNames: ['name']
//    },
//    init: function() {
//      var userList = new List('people-list', this.options);
//      var noItems = $('<li id="no-items-found">No items found</li>');
//
//      userList.on('updated', function(list) {
//        if (list.matchingItems.length === 0) {
//          $(list.list).append(noItems);
//        } else {
//          noItems.detach();
//        }
//      });
//    }
//  };
//
//  searchFilter.init();
//
})();

