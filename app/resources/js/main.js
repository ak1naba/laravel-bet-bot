console.log(window.Echo)

window.Echo.channel('base-channel')
    .listen('BroadcastEvent', (e) => {
       alert(e.message);
    });


