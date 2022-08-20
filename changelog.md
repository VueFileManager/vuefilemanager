## Version 2.2.7
#### Release date: 20. August 2022
- fixed upload button issue in mobile

## Version 2.2.6
#### Release date: 17. August 2022
- fixed upload button issue

## Version 2.2.5
#### Release date: 11. August 2022
- improved tracking for Google Analytics

## Version 2.2.4
#### Release date: 8. August 2022
- Account verification email is now sending in preferred app language
- Fixed issue where metered billing mode still count in some cases members after team folder was deleted
- In case if cron isn't set, in Settings/Server tab you will see suggestions for cron command

## Version 2.2.3
#### Release date: 6. August 2022
- Subscription module is now available for Regular license type 

## Version 2.2.2
#### Release date: 16. July 2022
- Fixed issue when email wasn't sent in active app language
- Fixed issue with subscription module loading

## Version 2.2.1
#### Release date: 1. July 2022
### New Usage Restriction Rules for User Accounts for Metered Billing
- Allow limiting max usage before users will be forced to increase balance in first month of account existence
- Force users to increase balance when usage is bigger than their current balance

#### Release date: 29. Jun 2022
- Fixed reCaptcha for the contact form

## Version 2.2.0.13
#### Release date: 29. Jun 2022
- Extended login time up to 3 months
- Fixed deleted at language string in grid view browsed in trash section

- ## Version 2.2.0.12
#### Release date: 28. Jun 2022
- Fixed paystack transaction issue

## Version 2.2.0.11
#### Release date: 27. Jun 2022
- Added hint to set cron command in dashboard panel when cron is not set correctly

## Version 2.2.0.10
#### Release date: 27. Jun 2022
- Fixed issue with downloading certain file types when you are using Backblaze storage driver
- Fixed issue when Google Analytics doesn't record visitors

## Version 2.2.0.9
#### Release date: 23. Jun 2022
- Added spinner when pdf is loading
- Set sandbox/live mode in PayPal key configuration setup form
- Fixed issue when after deleting user, the related subscription wasn't deleted
- Fixed issue when you perform composer update with private repository
- Fixed issue where change in sorting option will duplicate the content in file view
- Fixed issue where Dragged & Dropped folder from desktop didn't start uploading
- Fixed issue when you upload empty .txt file, it stops the upload process

## Version 2.2.0.8
#### Release date: 15. Jun 2022
- Fixed issue when you tried switch to another language, the language stay same

## Version 2.2.0.7
#### Release date: 13. Jun 2022
- Solved issue when you click on the PayPal logo it makes the new copy of the payment buttons

## Version 2.2.0.6
#### Release date: 13. Jun 2022
- Solved issue when user wasn't redirected into stripe checkout

## Version 2.2.0.5
#### Release date: 12. Jun 2022
- Solved issue with showing trash content (Affected since 2.2.0)

## Version 2.2
#### Release date: 18. May 2022

### Fixes
- Solved issue with database backup notifications
- Solved issue where after team member was invited into team folder, email with urging the recipient to create new account was sent
- You are now allowed to set price for metered billing in 3 decimal places
- Solved UI issue with empty notification popup
- Plans in fixed subscription are now automatically sorted from lower to higher price
- When new user was created via admin, the verification email was send
- Item name in list view type is now extended on the full page width

### New Features
- API version 1 released
- Paginated records loading with infinity scroller
- If you send file request for native user via email option, the push notification will be sent to the user
- Ability to test your websocket (Broadcasting) connection via Admin / Server / Broadcasting tab

## Version 2.1.3
#### Release date: 10. May 2022
- Ability to manually synchronize plans in fixed subscription type
- Improved sanitization for .env values to prevent crash your app
- Improved reCaptcha validation errors
- Fixed issue when upload doesn't start after you drag the file into empty view
- Fixed issue when homepage flash to sign in screen after the homepage was disabled in admin panel
- Fixed trash navigator issue
- Fixed issue when you create plan with 0 team members amount

## Version 2.1.2
#### Release date: 8. May 2022
- Fixed issue with chunk uploads (Critical issue affected since 2.1.1)
- Fixed issue with creating plan with unlimited team members

## Version 2.1.1
#### Release date: 29. April 2022
- Fixed issue with reading image upload

## Version 2.1.0
#### Release date: 25. April 2022
- New remote upload function
- Broadcast server implementation, for more info check [Broadcasting Guide](https://gist.github.com/MakingCG/a702a112be63bc6f0032dd55522327cf#broadcasting)

## Version 2.0.19
#### Release date: 21. April 2022
- Fixed issue with Backblaze credentials set up
- The switch button in archived plans is now hidden
- updated readme.md
- Fixed upload progressbar in mobile version
- Added Viet Nam language into the language editor

## Version 2.0.18
#### Release date: 19. April 2022
- Fixed issue with Google login button
- Fixed issue with PayPal subscription

## Version 2.0.17
#### Release date: 12. April 2022
- Added option to use FTP as VueFileManager file storage server

## Version 2.0.16
#### Release date: 8. April 2022
- Test mailgun, ses and postmark connection before storing your credentials into the app
- UI enhancements & fixes

## Version 2.0.15
#### Release date: 6. April 2022
- Wasabi region list updated
- The delay after first upload in file request when the interface wasn't showing was removed
- If adsense banner location isn't filled, the ads space won't be showed
- Fixed issue when in grid view you tried to move image into another folder
- Fixed issue when you can't move or delete items via mobile multiselect function
- You can now open searched file from spotlight in FilePreview mode to access file settings and functions

## Version 2.0.14
#### Release date: 5. April 2022
- Added option to set default max team members for new user registrations
- Added new app settings shortcuts findable by spotlight - Application, Login & Registration, Appearance, Adsense, Homepage, Environment, Server
- Now in 2fa set up challenge user is required to confirm his app setup with code

## Version 2.0.13
#### Release date: 4. April 2022
- Auto plan synchronization improvements.
- Fixed issue with thumbnails in grid preview type
- PayPal sandbox option was added into PayPal credentials setup
- Fixed issue in mobile spotlight where you trying open searched image

## Version 2.0.12
#### Release date: 2. April 2022
- Added status column to the fixed plan table
- Ability to delete fixed plan if there isn't any subscribed user
- Improved error handling in subscription module

## Version 2.0.11
#### Release date: 1. April 2022
- Improved email setup in administration settings and setup wizard
- Ability to set custom s3 compatible service in administration settings and setup wizard
- Test s3 connection before set up in administration settings and setup wizard
- Test smtp connection before set up in administration settings and setup wizard

## Version 2.0.0 - 2.0.10
#### Release date: 31. March 2022
- You can now generate link for direct download via context menu in copy link input
- You can now upgrade from regular to extended license version via admin panel
- Fixed issue with .dwg file icon
- You can now set uploading chunk file size directly in your admin
- Team Folders and Shared With Me root folder has now disabled delete/move function
- When you invite someone to your team folder, all his usage (storage, bandwidth) will be on behalf of you, not the member
- Fixed issue when you are moving folders between your Team Folders and Private files and vice versa
- Fixed issue when downloading bandwidth doesn't write to the user total bandwidth stats
- Fixed issue with language strings which indicate limited/unlimited team members in fixed plans
- Fixed issue when you are trying delete user registered with only the first name
- Fixed issue with Adsense where banners wasn't placed correctly by their described location

## Version 2.0.0
#### Release date: 21. March 2022

**User Interface**
- Fullscreen mode can be set by double arrow icon on top of navigation sidebar in file page or by action in spotlight
- In mobile version you can now by opening image or document move seamlessly to another by swipe gesture
- Emoji picker was redesigned and now offer better experience to pick your favourite emoji for your folder icon
- Actions like create folder, upload files and any others was moved and grouped by 'Frequently used' and 'Others' category under single button
- User profile, Dashboard and other admin pages was redesigned
- Mac users can now switch between native emojis and twemojis
- New button to switch between dark/light mode was added under the main navigation in desktop
- Now when you undo in your browser, it goes back to the previous folder

**Spotlight**
- Search through your files and folders
- Can be used as quick navigation through your folders or app pages
- Ability to search actions like toggle emojis, toggle dark/light mode, creating file request, new user and many more
- Search your users by activating user filter with keyboard shortcut (u+space)
- Ability to call spotlight with cmd/ctrl + k from any location in app like admin or user profile
- Ability from any location in the app to show document, image or video in file preview component
- Ability to use keyboard shortcuts to navigate in spotlight like arrow up/down, enter

**Collaboration**
- New Team Folders and Shared with Me categories
- Ability to create new team folder and invite users by email invitation, or if user is app user, then push notification will be sent as well.
- Ability to convert existing folder into Team Folder and invite members into it.
- Ability to dissolve your team folder. All members permissions will be revoked and your folder will be moved into My Files category.
- Ability to leave folder if you are member of someone team folder
- Received invitation can be accepted or declined
- User avatar indicate in file icon who is the owner of the file
- New team heads icon in desktop and mobile toolbar to indicate team members in the team folder

**Metered Billing**
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

**Fixed Billing**
- Plans with yearly billing period is now supported
- New subscription widget with subscription details in user profile
- New Payment Method widget to manage user credit cards (available only for Stripe)
- New popup component to subscribe or upgrade account plan

**Notification Center**
- Ability to see notifications by Unread and Read category
- Interactive notifications to seamlessly perform actions from it if needed
- If broadcasting is set, notifications will be received just in time and showed in bottom right corner of the app
- Ability to clear all notifications by button

**Zip**
- New on the fly zipping system zips files without additional storage usage
- User can now select any files with any folders and zip them together

**Sharing**
- Redesigned sharing popup component for better user experience
- Shared video file (.mp4) has now ability to play video in shared page
- Ability to get share link via QR code
- Ability to generate embeddable code with shared item (beta)
- App logo was added into single file share pages

**File Request**
- User can generate file request by opening context menu over single folder or call file request from spotlight
- Ability to set custom folder name if file request will be filled
- Ability to leave a message for guest
- Ability to send file request by email for certain email recipient
- Full-fledged reach UI for guests to upload and manage their files directly in file request
- Push notification about filled file request will be received for user

**Folder Upload**
- Now user can upload their folders. The same folder structure will be recreated in the app

**User Settings**
- New appearance option was added where user can set his theme mode by dark, light or based by system option
- New default emoji option was added where Apple user can switch between native emojis or twemoji
- 2fa setup challenge option was added. User can store and generate backup keys.
- New Personal Access Token section was added where user can generate access token for API requests.
- New 'Current Password' input was added into change password functionality
- New widget to track the latest upload and download was added into the Storage tab
- Storage usage widget was redesigned
- New Billing tab with all subscription related items was added

**Login & Registration**
- Email confirmation for new account registrations can be required
- Integrated database with more than 550 disposable temporary email services to automatically deny new account registrations
- Users can now set up 2 factor verification with their favourite authenticator app
- reCaptcha was added to provide security for your registration and contact form
- Social authentication was implemented with Facebook, Google and GitHub drivers.

**Adsense**
- Adsense will be integrated into VueFileManager
- The ads are showing in 3 locations - File Viewport, Download Page and Homepage

**Setup Wizard**
- Server check before you running installation, it will show you if you had set up your server correctly
- Dark mode support
- Now you don't need to set up your subscription system in setup wizard

**Broadcasting**
- Pusher implementation for live communication
- Native websocket server as replacement for Pusher (more details soon)
- Live notification

**System**
- Database backups on daily basis
- After you upload image, additionals thumbnails will be generated to provide you faster browsign experience through your image gallery

**Admin & Settings**

**Dashboard**
- New widget to track the latest upload and download was added
- New widget with the latest transactions was added into extended license version
- New earnings widget was added
- New alerts will tell you if you are missing plan or you don't have running cron correctly

**Settings / Server**
- Ability to see if cron job is running correctly
- Ability to download your server log from admin panel
- Ability to see latest 5 database backups
- Ability to check if writable permission for exact folders are set correctly
- Ability to check if you have correctly set php version and php.ini variables
- Ability to see if you have installed all required php extensions

**Settings / Environment**
- Ability to set Broadcasting from admin settings
- Ability to set Storage Driver from admin settings
- Ability to set Mail Driver from admin settings

**Settings / Appearance**
- Ability to change entire VueFileManager color scheme
- Ability to set dark mode logo for main and horizontal logo
- Ability to set your own OG Image
- Ability to set your own Touch Image

**Settings / Login & Registration**
- New option where you can require email verification was added
- New widgets to set up Facebook, Google and GitHub social authentication

**Settings / Application**
- New options to set up reCaptcha

**Settings / Adsense**
- Ability to manage Google Adsense

**Dev**
- PHP 8 support
- New DDD design for the backend
- Shipped with the latest version of Laravel 9.x
- Passport was replaced by Sanctum
- New artisan command that expressly installs the entire application
- New artisan command that expressly installs the entire application with the demo data
- ~80% Of the frontend code was migrated into Tailwind v3. We will continue to reach 100% tailwind friendly


## Version 1.8.3.13
#### Release date: 9. December 2021
- Fixed issue when visitor can't download files from shared folder with 'Can only view and download' privilege
- Fixed issue with sending contact form

## Version 1.8.3.11-12
#### Release date: 7. July 2021
- Fixed issue when you tried load folders/files with special characters name

## Version 1.8.3.10
#### Release date: 29. June 2021
- Security patch (Critical severity)

## Version 1.8.3.9
#### Release date: 18. June 2021

**Fixes:**
- Fixed issue when in some cases your server can suffer with increasing RAM usage
- Fixed translation issue in some user mobile pages
- Mobile navigation fix with hidden back button
- Fixed issue where in some case special characters was excerpted from uploaded filename

## Version 1.8.3.8
#### Release date: 15. June 2021
- Alibaba Cloud OSS support

## Version 1.8.3.7
#### Release date: 9. June 2021
- App name fix in email templates

## Version 1.8.3.6
#### Release date: 7. June 2021
- Translation improvements on backend

## Version 1.8.3.5
#### Release date: 24. May 2021
- Translation improvements on backend

## Version 1.8.3.4
#### Release date: 15. May 2021
- Upload progressbar fix

## Version 1.8.3.3
#### Release date: 10. May 2021
- Security update (upgrade required)

## Version 1.8.3.2
#### Release date: 17. April 2021

**Fixes:**
- Fixed multi select on desktop browser

## Version 1.8.3.1
#### Release date: 18. April 2021

**Fixes:**
- Fixed previewing files in mobile devices

## Version 1.8.3
#### Release date: 17. April 2021

**Features:**
- Ability to create new language
- Ability to set default language for aplication
- Ability to show pdf files in VueFileManager


## Version 1.8.2.3
#### Release date: 21. March 2021

**Fixes:**
- Fixed not found page
- Fixed admin menu in regular license


## Version 1.8.2.2
#### Release date: 12. March 2021

**Fixes:**
- Fixed file preview in single shared file wasn't showing correctly


## Version 1.8.2.1
#### Release date: 25. February 2021

**Fixes:**
- Fixed jumping upload progressbar

## Version 1.8.2
#### Release date: 20. February 2021

**Folders:**
- Ability to change folder icon as Emoji
- Ability to change folder icon color from 22 colors set

**Fixes:**
- Added zip folder into mobile folder menu
- Removed Autofocus in mobile creating folder

## Version 1.8.1
#### Release date: 7. February 2021

**Functions:**
- Ability to zip and download folder with content within
- Ability to send shared folder/file to multiple email recipients
- Ability to send share link in existing shared items
- Every user has ability to change his timezone in profile settings

**UI & UX:**
- After click on logout if your request is long, you will see processing window
- Move/Share/Delete icons is unactive when you don't have selected file in desktop version
- Move/Share/Delete icons is hidden in touchable devices
- Delete item in contextmenu is now highlighted as red
- Hidden scroller in navigation panel on windows
- Delete icon for clear text field in rename popup
- After opening video, it's start playing automatically
- Autofocus and selecting folder name after creating it in desktop version

**Fixes:**
- Max Upload frontend validation fix
- In latest uploads files are sorted alwas from newest
- Download icon in image/video preview on mobile device and shared folder with read/download privilegies

**Regular License:**
- Included landing page
- Included Privacy Policy, Terms of Condition and Cookies policy pages
- Included contact form page

## Version 1.8
#### Release date: 21. December 2020

**User Interface:**

- Added navigator to shared page with folders when you have multiple folders within for better user experience
- New popup to rename your item in desktop & mobile version
- Trash moved to Home page under 'Recent Uploads' navigation item
- Ability to collapse navigator or favourites widget in File page
- Ability to remove uploaded logo and get back to text logo
- Ability to cancel uploading via x button
- Prevent browser 'Go Back' when you undesirably trying to go to previous page after sign in to your account

**Sorting**

- New sorting menu in desktop version buttons panel with multiple actions
- New view button with sorting options in mobile version
- Ability to sort files & folders in date or alphabetical order

**Bulk Operations**

- Select multiple items in desktop version by holding shift key or ctrl/cmd key for increase or decrease selection
- New multi select mode in mobile version activated by clicking on 'Select' button below search bar
- Ability to select all or deselect all items in mobile version of multi select
- Select multiple items and delete them by delete/backspace key
- Select multiple items and move them by drag&drop to another folder in view or move them via move option
- Ability to move multiple item by dragging them to folder in navigator panel
- Ability to set multiple favourite folders by dragging them to favourites group in sidebar panel
- Ability to cancel multiple shareing items in 'My Sharing Items' sections via context menu action
- New compact UI component for indicate dragging action
- Ability to download multiple files from selection as zip
- New scrollbar for the Windows users

**Bug Fixes:**

- Title name of favourite folder overflowed sidebar when you have long title
- Share button in image preview appear when you are in shared public folder
- Fixed search indexer overload in high traffic demands scenario
- Folder tree in navigator now has correct ordering with folder list in file view

## Version 1.7.12
#### Release date: 16. November 2020

- Limit maximum upload size on single file

## Version 1.7.11
#### Release date: 24. October 2020

- Reading exif data and showing them in file info panel
- Ability to ban mimetypes. You can find blacklist setup in Settings / Application
- Lazy loading for images. If you have hundreds of images in your folder, you will have experience with seamlessly loading.
- Fixed bug with context menu overflow in shared page
- Fixed button overflow on small mobile screens in popups.

## Version 1.7.10
#### Release date: 31. August 2020

- Ability to see file in full preview (images, videos[mp4, web, ogv], audios)

## Version 1.7.9
#### Release date: 26. August 2020

- Ability to set expiration for shared link
- If user is logged in, after visit SignIn page will be redirected to files page

## Version 1.7.8
#### Release date: 25. August 2020

- Ability to read file metadata in shared link for instant messengers

## Version 1.7.7
#### Release date: 24. August 2020

- Backend pagination for native data
- Added clear cache button to app settings
- Removed deprecated commands
- Code splitting support for Vue router
- Added region list to setup wizard
- added htaccess to redirect domain root to /public folder
- Fixed issue with non VueFileManager created Stripe plans
- Fixed email setup in app settings
- Additional text logo fix in Sign pages

## Version 1.7.6
#### Release date: 19. August 2020

- Folder delete fix

## Version 1.7.5
#### Release date: 10. August 2020
- Bug fixes and UX enhancement


## Version 1.7.4
#### Release date: 5. August 2020
- Bug fixes and UX enhancement


## Version 1.7.3
#### Release date: 30. July 2020

- Chunk Upload
- Multipart upload support on external storages (Ability to upload bigger files)
- Progressbar now show upload in %

## Version 1.7.2
#### Release date: 22. July 2020

- Readme update
- Improvement on Setup Wizard

## Version 1.7.1
#### Release date: 21. July 2020

- Fixed register button in Regular license

## Version 1.7
#### Release date: 20. July 2020

**Software as a Service**

- Ability to create monthly subscription plans with storage space
- Ability to charge customers for storage space with Stripe payment service
- List all of your customer invoices
- Included Legal pages (Terms of Service, Privacy Policy, Cookie Policy)
- Included Cookie disclaimer in SaaS version
- Landing page for present your service solution

**Settings - Application**

- Storage limitation option
- Default storage space for new accounts option
- Allow user registration option
- Set your contact email for message form in landing page
- Set your google analytics code to track visitors

**Settings - Appearance**

- App title option
- App description option
- Change your logo and favicon option

**Settings - Billing**

- Set your company name and VAT number options in SaaS version
- Set your billing country adress. options in SaaS version

**Settings - Payments**

- Allow subscription payments option
- Get your stripe webhook link option

**Settings - Homepage**

- Ability to change content, show/hide sections for Landing page presentation

**Settings - Email**

- Ability to set your email credentials from admin panel

**Admin - Users**

- Subscription plan details
- List of all user invoices

**Dashboard**

- Showed widget with your total users
- Showed widget with total space usage
- Showed widget with total premium users
- Showed widget with latest registrations
- Showed latest VueFileManager version
- Showed license type (Extend/Regular)

**User**

- Added billing information to profile settings in Saas Version
- Added subscription plan page to get information about current subscription. Ability to cancel or renew subscription
- Added payment card page with ability to list all registered card, add new credit card, remove card or set selected card as default payment.
- Added invoices page where are listed all invoices
- Added invoice page detail with ability to print invoice with

**VueFileManager design**

- Circle notice icon in avatar in case of incomplete payment or required action to upgrade storage capacity

**Setup Wizard**

- The UI for Install and configure your VueFIlemanager
- Purchase code verification
- Database credentials setup
- Ability to setup Stripe for Extended License (Set Credentials, set billing informations and create subscription plans)
- Storage Setup
- Email credentials setup
- General settings setup
- Admin setup

**Storage**

- Added support for Object Cloud Storage by Wasabi
- Added support for Backblaze B2 Cloud Storage
  Fixes
- Fixed issue when you move parent directory to it’s children directory, then your folder disappear.
- Fixed issue which appear on specifical hosting configuration when you can’t load shared folder content.

**Others**

- Improved form design
- Profile settings get sidebar panel navigation
- Improved design for important notices
- Updated i18n language files
- Added agreement in registration page for SaaS version
- Upgraded laravel framework from 6 to 7 version

**Upgrading**

- All upgrading backend operations is running on background after putting new source code to app directory (Don't forget create backup of your database and storage before make any changes in your production application)
- UI page for additional options when it’s neccesarily
- Upgrading To 1.7 From 1.6.x or newest

## Version 1.6.4
#### Release date: 9. June 2020

- Fileview xscroller fix

## Version 1.6.3
#### Release date: 5. June 2020

- Fixed svg upload
- Fixed scrolling in main file view

## Version 1.6.2
#### Release date: 31. May 2020

- Fixed popups on mobile version

## Version 1.6.1
#### Release date: 31. May 2020

- Fixed npm building crash on web server

## Version 1.6
#### Release date: 28. May 2020

**User Management**

- List of all registered users
- Ability to create new user
- Change user role (Admin or User)
- Change user storage capacity in GBs
- User storage capacity usage preview
- Send email to user for password reset
- Ability to delete user with all user data and stored files
  Others
- Redesigned User profile settings
- Small bug fixes
- New artisan command for upgrade app (for more info check upgrade guide)
- Upgrading To 1.6 From 1.4.2 or newest

## Version 1.5.2
#### Release date: 22. May 2020

- Empty page fix
- Log out in mobile fix
- Dark mode fixes
- Password reset bug fixes

## Version 1.5
#### Release date: 19. May 2020

**UI & UX**

- New navigation menu with Home, Shared, Trash, Settings locations in desktop
- New dynamic sidebar with folder tree navigation, file categories and tools
- Folder tree navigator in main file page
- Added Participant uploads category to shared page
- Added recent uploads category to file page
- New settings page with separated Profile, Password and Storage
- Updated colors for dark mode
- Improvement desgin across all components
- Added (X) close button to all popups
- Added editing icons (move, share) to main bar
- More transparent context menus with action groups
  Mobile
- New main navigation menu
- Added item thumbnails to mobile contextmenu
  Others
- Public shared image now open in an original image page
- New page with storage usage details

## Version 1.4.2
#### Release date: 6. May 2020

**External Storage Services**

- Amazon Web Services S3 support
- Digital Ocean Spaces support
- Updated documentation
- New blog with some handy tips and news about VueFileManager

## Version 1.4.1
#### Release date: 4. May 2020

**Translations**

- Added Chinese (Simplified) language (thanks haimang to contribute)
  Bugs
- Go to profile in mobile version
- Internal front-end bug
  Error logs
- Added when passport client isn’t set up correctly
- Added when database connection isn’t available

**Others**

- Updated terminal info when you are running php artisan setup:prod
- Updated documentation with more describing installation process

## Version 1.4
#### Release date: 1. May 2020

**Sharing**

- Share your folders and files
- Added sidebar group with shared items
- Added UI icons to item preview to look which item is shared and which item was uploaded by participant

**Folders**

- User can share folder content
- User can protect folder content by password
- User can set permission to interact with shared folder - only view and download or view, download, upload, rename, delete and move items

**Files**

- User can share single file
- User can protect single file by password

**Others**

- Small UI improvements
- Bug fixes
- Upgrading to 1.4 from 1.3.x

## Version 1.3.1
#### Release date: 8. April 2020

- Bug fixes
- Upgrading to 1.3.1 from 1.2

## Version 1.3
#### Release date: 3. April 2020

- i18n localization support
- Added SK, EN language files
- Video/Audio preview in file preview panel (Thanks to Joshua Fouyon to participating on this feature)
- Drop uploading (You can now drag files from desktop and drop it to VueFileManager)
- Fixed bug when rename item in safari browser
- Fixed bug when you drag folder from trash to favourites in sidebar panel
- small functions and design improvements
- Upgrading to 1.3 from 1.2

## Version 1.2
#### Release date: 29. March 2020

- Move your items by folder tree
- Fixed bug with image rotation on iOS Device
- Improved animations
- Small design changes
- iOS web app capable support

## Version 1.1.1
#### Release date: 17. March 2020

- Bug fixes

## Version 1.1
#### Release date: 15. March 2020

**User Authentication**

- Login to user account
- Register new user account
- Reset user password

**Functionality**

- Added locations to menu
- Added trash for deleted folders & files
- Restore files or folders from trash
- Empty trash function
- Favourites folders
- List of 5 latest uploads
- Profile settings page
- Storage info and upload limits

**Design**

- Night Mode
- Navigation sidebar
- Quick action buttons in mobile version
- Improved mobile UX
- Other small design improvements

**Settings**

- Enable/Disable user account registration
- Set storage limitation
- Set storage capacity for all users

## Version 1
#### Release date: 25. February 2020
- Official release