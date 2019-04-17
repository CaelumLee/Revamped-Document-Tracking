import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key : "80dfb464ef1afbfcbec0",
    cluster : "ap1",
    encrypted: true,
    authEndpoint: url + '/broadcasting/auth'
});

var notifications = [];

var NOTIFICATION_TYPES = {
    SendDocu: 'App\\Notifications\\SendDocu',
    PasswordChange : 'App\\Notifications\\PasswordChange',
    DeclineNotif : 'App\\Notifications\\DeclineNotif',
    AcceptNotif : 'App\\Notifications\\AcceptNotif',
    DeadlineNotif : 'App\\Notifications\\DeadlineNotif',
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
        $.get(notif_url, function (data) {
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
    var existingNotifications = notifications.html();
    var message = messageForNotification(data);
    var href = hrefNotification(data);
    var newNotificationHtml = `
        <li class="notification active">
            ${href}
                <div class="media">
                    <div class="media-body">
                        ${message}
                    </div>
                </div>
            </a>
        </li>`;
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
        message =  `<strong class="notification-title">Document Received!</strong>
        <p class="notification-desc">Document with reference number ${data.data.reference_number}
        was sent to you by ${data.data.sender} </p>
        <div class="notification-meta">
            <small class="timestamp">${data.created_at}</small>
        </div>`;
    }
    else if(data.type == NOTIFICATION_TYPES.PasswordChange){
        message = `<strong class="notification-title">Password Change</strong>
        <p class="notification-desc">User ${data.data.user} is requesting for a
        password change. Click here to redirect to user dashboard`
        ;
    }
    else if(data.type == NOTIFICATION_TYPES.DeclineNotif){
        message =  `<strong class="notification-title">Document Disapproved!</strong>
        <p class="notification-desc">Document with reference number ${data.data.reference_number}
         was dissaproved and sent to you by ${data.data.sender}
        <br>Click to see the remarks made</p> 
        <div class="notification-meta">
            <small class="timestamp">${data.created_at}</small>
        </div>`;
    }
    else if(data.type == NOTIFICATION_TYPES.AcceptNotif){
        message =  `<strong class="notification-title">Document Approved!</strong>
        <p class="notification-desc">Document with reference number ${data.data.reference_number} 
        was approved and sent to you by ${data.data.sender} <br>
        Click to see the remarks made</p> 
        <div class="notification-meta">
            <small class="timestamp">${data.created_at}</small>
        </div>`;
    }
    else if(data.type == NOTIFICATION_TYPES.DeadlineNotif){
        message =  `<strong class="notification-title">Deadline to meet!</strong>
        <p class="notification-desc">Document with reference number ${data.data.reference_number}
        that was sent to you needs to be finished
        until tomorrow. Click here to redirect to the record</p> 
        <div class="notification-meta">
            <small class="timestamp">${data.created_at}</small>
        </div>`;
    }
    return message;
}

function hrefNotification(data){
    var href = '';
    if(data.type == NOTIFICATION_TYPES.SendDocu || data.type == NOTIFICATION_TYPES.DeclineNotif
    || data.type == NOTIFICATION_TYPES.AcceptNotif || data.type == NOTIFICATION_TYPES.DeadlineNotif){
        href = `<a href = '${url}/docu/${data.data.docu_id}?read=${data.id}'>`;
    }
    else if(data.type == NOTIFICATION_TYPES.PasswordChange){
        href = `<a href = '${url}/dashboard/allusers?read=${data.id}$username=${data.data.user}'>`
    }
    return href;
}