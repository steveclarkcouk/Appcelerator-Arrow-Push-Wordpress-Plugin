# Wordpress Appcelerator-Cloud-REST-SDK

Integration of Appcelerator Cloud REST SDK into Wordpress for use with Push Notifcations. This is a very simple plugin that will be developed upon in the future.

Configure Your App For Push

You will need to have your app set up for ArrowDB ( Cloud ACS ) Push Notifcations which can be found here: http://docs.appcelerator.com/platform/latest/#!/guide/Push_Notifications - This is also a good guide https://medium.com/appseed-io/sending-push-notifications-through-appcelerator-cloud-api-93068bfab9f6#.tufnqt20n

Obtain your App Key from Appcelerator

1. Login to platform.appcelerator.com Click on Apps, select your app. 
2. Click on Arrow
3. Select the app version ( Production / Development dependant on your requirements )
4. Click configuration
5. Click show on App Key 

Setting an Admin User

1. Click on Arrow
2. Select the app version ( Production / Development dependant on your requirements )
3. Click Manage Data
4. Click Users 
5. Click create user
6. Fill in the required fields making a note of username and password ( These are required in Plugin Settngs )
7. Ensure Admin is set to true

Install the plugin
Download an install the plugin to the wp-content plugins folder. Then from Wordpress admin click plugins and enable  Appcelerator Arrow DB Push.

Configure the plugin
A New menu item of ArrowDB Push should appear in the left hand side of the admin menu in Wordpress. Click on settings and add your App Key, Username and Password.

Once this is done click on ArrowDBPush for a simple interface. The options are very limited at present but all fields are required.
