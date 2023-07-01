import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// if was Echo.channel this's public channel and convert to private
var channel = Echo.private(`App.Models.User.${userId}`);
// if me don't put . before my-event this's consider as app.events.my-event but if put . before my-event consider as my-event without namespace

// channel.listen(".my-event", function (data) {
// laravel given me ready method called notification and take default event name in the laravel
channel.notification(function (data) {
    console.log(data);
    alert(data.body);
    // this's other way to display
    // alert(JSON.stringify(data));
});
