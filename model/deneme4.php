<script>

    $('body').desktop({
        apps: [{
            name: 'Computer',
            icon: 'images/computer.png',
            width: 800,
            height: 400,
            left: 200,
            top: 50,
            href: '_layout.html'
        },{
            name: 'Network',
            icon: 'images/network.png',
            left: 300,
            top: 100
        },{
            name: 'Monitor',
            icon: 'images/monitor.png',
            left: 400,
            top: 150
        }],
        menus: [{
            text: 'About Desktop',
            handler: function(){
                $('body').desktop('openApp', {
                    icon: 'images/win.png',
                    name: 'About',
                    width: 400,
                    height: 200,
                    href: '_about.html'
                })
            }
        },{
            text: 'System Update...',
            handler: function(){
                $('body').desktop('openApp', {
                    name: 'System Update'
                })
            }
        }]
    });
</script>