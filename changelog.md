Changelog

User Interface
- Fullscreen mode can be set by double arrow icon on top of navigation sidebar in file page or by action in spotlight
- In mobile version you can now by opening image or document move seamlessly to another by swipe gesture
- Emoji picker was redesigned and now offer better experience to pick your favourite emoji for your folder icon
- Actions like create folder, upload files and any others was moved and grouped by 'Frequently used' and 'Others' category under single button
- User profile, Dashboard and other admin pages was redesigned
- Mac users can now switch between native emojis and twemojis
- New button to switch between dark/light mode was added under the main navigation in desktop
- Now when you undo in your browser, it goes back to the previous folder

Spotlight
- Search through your files and folders
- Can be used as quick navigation through your folders or app pages
- Ability to search actions like toggle emojis, toggle dark/light mode, creating file request, new user and many more
- Search your users by activating user filter with keyboard shortcut (u+space)
- Ability to call spotlight with cmd/ctrl + k from any location in app like admin or user profile
- Ability from any location in the app to show document, image or video in file preview component
- Ability to use keyboard shortcuts to navigate in spotlight like arrow up/down, enter

Collaboration
- New Team Folders and Shared with Me categories
- Ability to create new team folder and invite users by email invitation, or if user is app user, then push notification will be sent as well.
- Ability to convert existing folder into Team Folder and invite members into it.
- Ability to dissolve your team folder. All members permissions will be revoked and your folder will be moved into My Files category.
- Ability to leave folder if you are member of someone team folder
- Received invitation can be accepted or declined
- User avatar indicate in file icon who is the owner of the file
- New team heads icon in desktop and mobile toolbar to indicate team members in the team folder 

Metered Billing
- New metered billing where user can be charged by what he uses.
- Bandwidth, storage, flat fee and members are optional features which can be charged, not required
- Native balance system from which user is charger at the end of current billing period
- User can fund his balance by PayPal and Paystack single payment option
- If Stripe payment method is allowed, user can register his credit card and all future payments for billing period will be automatically charged. If there is any credit in balance and is sufficient, then this amount will be preferred instead of credit card charging
- User has ability to add/delete his payment methods (only Stripe)
- Admin has ability to increase user balance with certain amount
- Ability to set registration bonus for every new user registration
- User can set billing alert with certain amount. When this amount will be reached, the notification will be sent.
- New Usage Estimates widget in user profile which provide price estimates for current billing period
- New transactions widget in user profile with usage history

Fixed Billing
- Plans with yearly billing period is now supported
- New subscription widget with subscription details in user profile
- New Payment Method widget to manage user credit cards (available only for Stripe) 
- New popup component to subscribe or upgrade account plan

Notification Center
- Ability to see notifications by Unread and Read category
- Interactive notifications to seamlessly perform actions from it if needed
- If broadcasting is set, notifications will be received just in time and showed in bottom right corner of the app
- Ability to clear all notifications by button

Zip
- New on the fly zipping system zips files without additional storage usage 
- User can now select any files with any folders and zip them together

Sharing
- Redesigned sharing popup component for better user experience
- Shared video file (.mp4) has now ability to play video in shared page
- Ability to get share link via QR code
- Ability to generate embeddable code with shared item (beta)
- App logo was added into single file share pages

File Request
- User can generate file request by opening context menu over single folder or call file request from spotlight
- Ability to set custom folder name if file request will be filled
- Ability to leave a message for guest
- Ability to send file request by email for certain email recipient
- Full-fledged reach UI for guests to upload and manage their files directly in file request
- Push notification about filled file request will be received for user

Folder Upload
- Now user can upload their folders. The same folder structure will be recreated in the app

User Settings
- New appearance option was added where user can set his theme mode by dark, light or based by system option
- New default emoji option was added where Apple user can switch between native emojis or twemoji
- 2fa setup challenge option was added. User can store and generate backup keys.
- New Personal Access Token section was added where user can generate access token for API requests.
- New 'Current Password' input was added into change password functionality
- New widget to track the latest upload and download was added into the Storage tab
- Storage usage widget was redesigned
- New Billing tab with all subscription related items was added

Login & Registration
- Email confirmation for new account registrations can be required
- Integrated database with more than 550 disposable temporary email services to automatically deny new account registrations
- Users can now set up 2 factor verification with their favourite authenticator app
- reCaptcha was added to provide security for your registration and contact form
- Social authentication was implemented with Facebook, Google and GitHub drivers.

Adsense
- Adsense will be integrated into VueFileManager
- The ads are showing in 3 locations - File Viewport, Download Page and Homepage

Setup Wizard
- Server check before you running installation, it will show you if you had set up your server correctly
- Dark mode support
- Now you don't need to set up your subscription system in setup wizard

Broadcasting
- Pusher implementation for live communication
- Native websocket server as replacement for Pusher (more details soon)
- Live notification

System
- Database backups on daily basis
  
Admin & Settings

Dashboard
- New widget to track the latest upload and download was added
- New widget with the latest transactions was added into extended license version
- New earnings widget was added
- New alerts will tell you if you are missing plan or you don't have running cron correctly

Settings / Server
- Ability to see if cron job is running correctly
- Ability to download your server log from admin panel
- Ability to see latest 5 database backups
- Ability to check if writable permission for exact folders are set correctly
- Ability to check if you have correctly set php version and php.ini variables
- Ability to see if you have installed all required php extensions

Settings / Environment
- Ability to set Broadcasting from admin settings
- Ability to set Storage Driver from admin settings
- Ability to set Mail Driver from admin settings

Settings / Appearance
- Ability to change entire VueFileManager color scheme
- Ability to set dark mode logo for main and horizontal logo
- Ability to set your own OG Image
- Ability to set your own Touch Image

Settings / Login & Registration
- New option where you can require email verification was added
- New widgets to set up Facebook, Google and GitHub social authentication

Settings / Application
- New options to set up reCaptcha

Settings / Adsense
- Ability to manage Google Adsense

Dev
- PHP 8 support
- New DDD design for the backend
- Shipped with the latest version of Laravel 9.x
- Passport was replaced by Sanctum
- New artisan command that expressly installs the entire application
- New artisan command that expressly installs the entire application with the demo data
- ~80% Of the frontend code was migrated into Tailwind v3. We will continue to reach 100% tailwind friendly
