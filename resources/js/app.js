import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key : "80dfb464ef1afbfcbec0",
    cluster : "ap1",
    encrypted: true
});

var notifications = [];

var NOTIFICATION_TYPES = {
    SendDocu: 'App\\Notifications\\SendDocu',
};

$(document).ready(function(){
    $('.sidenav').sidenav();
    $('.alert_close').click(function(){
        $( ".message" ).fadeOut( "slow", function() {
        });
    });
    $('.dropdown-trigger').dropdown({
        coverTrigger : false,
        constrainWidth : false
    });

    if(Laravel.userId) {
        $.get('/notifications', function (data) {
            if(data.length){
                data.map(function (notification){
                    makeNotification(notification);
                })
            }
        });
        window.Echo.private('App.User.' + window.Laravel.userId)
            .notification((notification) => {
                makeNotification(notification);
            });
    }
})

var notificationsWrapper   = $('.dropdown-notification');
var notificationsToggle    = notificationsWrapper.find('a[data-target]');
var notificationsCountElem = notificationsToggle.find('i[data-count]');
var notificationsBadge     = notificationsToggle.find('span.badge');
var notificationsCount     = parseInt(notificationsCountElem.data('count'));
var notificationDropdown   = $('#notif_dropdown');
var notifications          = notificationDropdown.find('ul.dropdown-menu');

if(parseInt(notificationsBadge.text())==0){
    $('span.badge').hide();
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function makeNotification(data){
    console.log(data)
    var existingNotifications = notifications.html();
    var message = messageForNotification(data);
    var newNotificationHtml = `
        <li class="notification active">
        <a href ='/docu/`+ data.data.docu_id +`?read=`+data.id +`'>
            <div class="media">
                <div class="media-body">`
                + message +
                `<div class="notification-meta">
                    <small class="timestamp">`+ data.created_at +`</small>
                </div>
                </div>
            </div>
            </a></li>
    `;
    notifications.html(newNotificationHtml + existingNotifications);

    notificationsCount += 1;
    notificationsCountElem.attr('data-count', notificationsCount);
    notificationDropdown.find('.header').find('.notif-count').text(notificationsCount);
    $('.notifications-text').hide();
    $('span.badge').show();
    notificationsBadge.text(notificationsCount);
}

function messageForNotification(data){
    var message = '';
    if(data.type == NOTIFICATION_TYPES.SendDocu){
        message =  `<strong class="notification-title">Document Recieved!</strong>
        <p class="notification-desc">Document with reference number `+ 
        data.data.reference_number +` was sent to you by `+ data.data.sender +`</p>`
    }
    return message;
}