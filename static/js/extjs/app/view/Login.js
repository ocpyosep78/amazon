Ext.define('Stiki.view.Login' ,{
    extend: 'Ext.container.Container',
    alias : 'widget.loginpage',
	
    initComponent: function() {
        Ext.applyIf(this, {
            items: [{ xtype: 'loginwindow' }]
        });
        this.callParent(arguments);
    }
});

Ext.define( 'Stiki.view.LoginWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.loginwindow',
    y: 106,
    width: 300,
    height: 150,
    closable: false,
    draggable: false,
    layout: 'anchor',
    
    initComponent: function() {
        Ext.apply(this, {
            items: [{ 
                xtype: 'form',
                url: URLS.base + 'panel/home/login',
                border: 0,
                bodyStyle: 'padding: 10px;',
                defaultType: 'textfield',
                items: [{
                    xtype:'container',
                    html: '<div id="loginmsg" style="padding: 10px 0 0 0;"><p>Masukkan username/password untuk login</p></div>'
                },{
                    name: 'email', id: 'login_email',
                    fieldLabel: 'Email',
                    required:true
                },{
                    name: 'passwd', id: 'login_passwd',
                    fieldLabel: 'Password',
                    inputType: 'password',
                    required:true
                }]
            }],
            buttons: [ { name: 'loginButton', text: 'Login', id: 'login_submit' } ]
        });
        this.callParent(arguments);
    }
});
