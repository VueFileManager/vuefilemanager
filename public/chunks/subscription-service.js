(window.webpackJsonp=window.webpackJsonp||[]).push([[78],{"9L6e":function(n,t,e){"use strict";var a=e("tT9u");e.n(a).a},ASoH:function(n,t,e){"use strict";var a={name:"AuthContent",props:["loading","icon","text"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},i=(e("RLEU"),e("KHd+")),r=Object(i.a)(a,(function(){var n=this,t=n.$createElement,e=n._self._c||t;return e("button",{staticClass:"button outline hover-text-theme hover-border-theme"},[e("span",{staticClass:"text-label"},[n._v(n._s(n.text))]),n._v(" "),n.loading?e("span",{staticClass:"icon"},[e("FontAwesomeIcon",{staticClass:"sync-alt svg-color-theme",attrs:{icon:"sync-alt"}})],1):n._e(),n._v(" "),!n.loading&&n.icon?e("span",{staticClass:"icon"},[e("FontAwesomeIcon",{staticClass:"svg-color-theme",attrs:{icon:n.icon}})],1):n._e()])}),[],!1,null,"16e9ad58",null);t.a=r.exports},JHC5:function(n,t,e){var a=e("YUi1");"string"==typeof a&&(a=[[n.i,a,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(a,i);a.locals&&(n.exports=a.locals)},Jx8r:function(n,t,e){(n.exports=e("I1BE")(!1)).push([n.i,"#auth[data-v-31af8372] {\n  height: 100%;\n  width: 100%;\n  display: table;\n}\n",""])},RLEU:function(n,t,e){"use strict";var a=e("JHC5");e.n(a).a},TJPC:function(n,t,e){"use strict";e.d(t,"a",(function(){return r}));function a(n){return null==n}function i(n){return Array.isArray(n)&&0===n.length}var r={validate:function(n,t){var e=(void 0===t?{allowFalse:!0}:t).allowFalse,r={valid:!1,required:!0};return a(n)||i(n)?r:!1!==n||e?(r.valid=!!String(n).trim().length,r):r},params:[{name:"allowFalse",default:!0}],computesRequired:!0}},YUi1:function(n,t,e){(n.exports=e("I1BE")(!1)).push([n.i,".button[data-v-16e9ad58] {\n  cursor: pointer;\n  border-radius: 8px;\n  text-decoration: none;\n  padding: 12px 32px;\n  display: inline-block;\n  margin-left: 15px;\n  margin-right: 15px;\n  white-space: nowrap;\n  transition: 150ms all ease;\n  background: transparent;\n}\n.button .text-label[data-v-16e9ad58] {\n  transition: 150ms all ease;\n  font-size: 1.0625em;\n  font-weight: 800;\n  line-height: 0;\n}\n.button .icon[data-v-16e9ad58] {\n  margin-left: 12px;\n  font-size: 1em;\n}\n.button.solid[data-v-16e9ad58] {\n  background: #00BC7E;\n  border: 2px solid #00BC7E;\n}\n.button.solid .text-label[data-v-16e9ad58] {\n  color: white;\n}\n.button.outline[data-v-16e9ad58] {\n  border: 2px solid #1B2539;\n}\n.button.outline .text-label[data-v-16e9ad58] {\n  color: #1B2539;\n}\n.button.outline .icon path[data-v-16e9ad58] {\n  fill: inherit;\n}\n.button.outline[data-v-16e9ad58]:hover {\n  border-color: inherit;\n}\n.button.outline:hover .text-label[data-v-16e9ad58] {\n  color: inherit;\n}\n@media (prefers-color-scheme: dark) {\n.button.outline[data-v-16e9ad58] {\n    background: #141414;\n    border-color: #bec6cf;\n}\n.button.outline .text-label[data-v-16e9ad58] {\n    color: #bec6cf;\n}\n}\n.sync-alt[data-v-16e9ad58] {\n  -webkit-animation: spin-data-v-16e9ad58 1s linear infinite;\n          animation: spin-data-v-16e9ad58 1s linear infinite;\n}\n@-webkit-keyframes spin-data-v-16e9ad58 {\n0% {\n    transform: rotate(0);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@keyframes spin-data-v-16e9ad58 {\n0% {\n    transform: rotate(0);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n",""])},bDRN:function(n,t,e){"use strict";var a={name:"AuthContentWrapper"},i=(e("iYAH"),e("KHd+")),r=Object(i.a)(a,(function(){var n=this.$createElement;return(this._self._c||n)("div",{attrs:{id:"auth"}},[this._t("default")],2)}),[],!1,null,"31af8372",null);t.a=r.exports},iYAH:function(n,t,e){"use strict";var a=e("lh0Q");e.n(a).a},j8qy:function(n,t,e){"use strict";var a={name:"AuthContent",props:["visible","name"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},i=e("KHd+"),r=Object(i.a)(a,(function(){var n=this.$createElement,t=this._self._c||n;return this.isVisible?t("div",{staticClass:"auth-form"},[this._t("default")],2):this._e()}),[],!1,null,null,null);t.a=r.exports},lh0Q:function(n,t,e){var a=e("Jx8r");"string"==typeof a&&(a=[[n.i,a,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(a,i);a.locals&&(n.exports=a.locals)},sdkW:function(n,t,e){(n.exports=e("I1BE")(!1)).push([n.i,'.form.inline-form[data-v-78e30cfc] {\n  display: flex;\n  position: relative;\n  justify-content: center;\n  margin: 0 auto;\n}\n.form.inline-form .input-wrapper[data-v-78e30cfc] {\n  position: relative;\n}\n.form.inline-form .input-wrapper .error-message[data-v-78e30cfc] {\n  position: absolute;\n  left: 0;\n}\n.form.block-form.create-new-password .block-wrapper label[data-v-78e30cfc] {\n  width: 280px;\n}\n.form.block-form .block-wrapper[data-v-78e30cfc] {\n  display: flex;\n  align-items: center;\n  margin-top: 25px;\n  justify-content: center;\n}\n.form.block-form .block-wrapper[data-v-78e30cfc]:first-child {\n  margin-top: 0;\n}\n.form.block-form .block-wrapper label[data-v-78e30cfc] {\n  white-space: nowrap;\n  font-size: 1.125em;\n  font-weight: 700;\n  padding-right: 20px;\n  width: 200px;\n  text-align: right !important;\n  color: #1B2539;\n  text-align: right;\n}\n.form.block-form .button[data-v-78e30cfc] {\n  margin-top: 50px;\n}\n.input-wrapper .error-message[data-v-78e30cfc] {\n  font-size: 0.875em;\n  color: #fd397a;\n  padding-top: 5px;\n  display: block;\n  text-align: left;\n}\ntextarea[data-v-78e30cfc] {\n  width: 100%;\n}\ntextarea[data-v-78e30cfc],\ninput[type="password"][data-v-78e30cfc],\ninput[type="text"][data-v-78e30cfc],\ninput[type="email"][data-v-78e30cfc] {\n  background: #f4f5f6;\n  border: 1px solid transparent;\n  transition: 0.15s all ease;\n  font-size: 1em;\n  border-radius: 8px;\n  padding: 13px 20px;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  font-weight: 700;\n  outline: 0;\n  width: 100%;\n}\ntextarea.is-error[data-v-78e30cfc],\ninput[type="password"].is-error[data-v-78e30cfc],\ninput[type="text"].is-error[data-v-78e30cfc],\ninput[type="email"].is-error[data-v-78e30cfc] {\n  border-color: #fd397a;\n}\ntextarea[data-v-78e30cfc]::-webkit-input-placeholder, input[type="password"][data-v-78e30cfc]::-webkit-input-placeholder, input[type="text"][data-v-78e30cfc]::-webkit-input-placeholder, input[type="email"][data-v-78e30cfc]::-webkit-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-78e30cfc]::-moz-placeholder, input[type="password"][data-v-78e30cfc]::-moz-placeholder, input[type="text"][data-v-78e30cfc]::-moz-placeholder, input[type="email"][data-v-78e30cfc]::-moz-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-78e30cfc]:-ms-input-placeholder, input[type="password"][data-v-78e30cfc]:-ms-input-placeholder, input[type="text"][data-v-78e30cfc]:-ms-input-placeholder, input[type="email"][data-v-78e30cfc]:-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-78e30cfc]::-ms-input-placeholder, input[type="password"][data-v-78e30cfc]::-ms-input-placeholder, input[type="text"][data-v-78e30cfc]::-ms-input-placeholder, input[type="email"][data-v-78e30cfc]::-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-78e30cfc]::placeholder,\ninput[type="password"][data-v-78e30cfc]::placeholder,\ninput[type="text"][data-v-78e30cfc]::placeholder,\ninput[type="email"][data-v-78e30cfc]::placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[disabled][data-v-78e30cfc],\ninput[type="password"][disabled][data-v-78e30cfc],\ninput[type="text"][disabled][data-v-78e30cfc],\ninput[type="email"][disabled][data-v-78e30cfc] {\n  color: #A4ADB6;\n  cursor: not-allowed;\n}\n.additional-link[data-v-78e30cfc] {\n  font-size: 1em;\n  margin-top: 50px;\n  display: block;\n}\n.additional-link b[data-v-78e30cfc], .additional-link a[data-v-78e30cfc] {\n  cursor: pointer;\n}\n.additional-link b[data-v-78e30cfc]:hover, .additional-link a[data-v-78e30cfc]:hover {\n  text-decoration: underline;\n}\n@media only screen and (max-width: 960px) {\n.form .button[data-v-78e30cfc] {\n    margin-top: 20px;\n    width: 100%;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form input[data-v-78e30cfc], .form textarea[data-v-78e30cfc] {\n    width: 100%;\n    min-width: 100%;\n}\n.form.block-form .block-wrapper[data-v-78e30cfc] {\n    display: block;\n}\n.form.block-form .block-wrapper label[data-v-78e30cfc] {\n    width: 100%;\n    padding-right: 0;\n    display: block;\n    margin-bottom: 7px;\n    text-align: left !important;\n    font-size: 0.875em;\n    padding-top: 0;\n}\n.form.block-form .button[data-v-78e30cfc] {\n    margin-top: 25px;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form.inline-form[data-v-78e30cfc] {\n    display: block;\n}\n.form.inline-form .input-wrapper .error-message[data-v-78e30cfc] {\n    position: relative;\n    bottom: 0;\n}\n.form .button[data-v-78e30cfc] {\n    padding: 14px 32px;\n}\ntextarea[data-v-78e30cfc],\n  input[type="password"][data-v-78e30cfc],\n  input[type="text"][data-v-78e30cfc],\n  input[type="email"][data-v-78e30cfc] {\n    padding: 14px 20px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.form.block-form .block-wrapper label[data-v-78e30cfc] {\n    color: #bec6cf;\n}\ntextarea[data-v-78e30cfc],\n  input[type="password"][data-v-78e30cfc],\n  input[type="text"][data-v-78e30cfc],\n  input[type="email"][data-v-78e30cfc] {\n    background: #1e2024;\n    color: #bec6cf;\n}\ntextarea[data-v-78e30cfc]::-webkit-input-placeholder, input[type="password"][data-v-78e30cfc]::-webkit-input-placeholder, input[type="text"][data-v-78e30cfc]::-webkit-input-placeholder, input[type="email"][data-v-78e30cfc]::-webkit-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-78e30cfc]::-moz-placeholder, input[type="password"][data-v-78e30cfc]::-moz-placeholder, input[type="text"][data-v-78e30cfc]::-moz-placeholder, input[type="email"][data-v-78e30cfc]::-moz-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-78e30cfc]:-ms-input-placeholder, input[type="password"][data-v-78e30cfc]:-ms-input-placeholder, input[type="text"][data-v-78e30cfc]:-ms-input-placeholder, input[type="email"][data-v-78e30cfc]:-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-78e30cfc]::-ms-input-placeholder, input[type="password"][data-v-78e30cfc]::-ms-input-placeholder, input[type="text"][data-v-78e30cfc]::-ms-input-placeholder, input[type="email"][data-v-78e30cfc]::-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-78e30cfc]::placeholder,\n  input[type="password"][data-v-78e30cfc]::placeholder,\n  input[type="text"][data-v-78e30cfc]::placeholder,\n  input[type="email"][data-v-78e30cfc]::placeholder {\n    color: #7d858c;\n}\ntextarea[disabled][data-v-78e30cfc],\n  input[type="password"][disabled][data-v-78e30cfc],\n  input[type="text"][disabled][data-v-78e30cfc],\n  input[type="email"][disabled][data-v-78e30cfc] {\n    color: #7d858c;\n}\n}\n.auth-logo-text[data-v-78e30cfc] {\n  font-size: 1.375em;\n  font-weight: 800;\n  margin-bottom: 40px;\n  display: block;\n}\n.auth-form[data-v-78e30cfc] {\n  text-align: center;\n  max-width: 600px;\n  padding: 25px 20px;\n  display: table-cell;\n  vertical-align: middle;\n}\n.auth-form input[data-v-78e30cfc] {\n  min-width: 310px;\n}\n.auth-form .additional-link a[data-v-78e30cfc] {\n  font-weight: 700;\n  text-decoration: none;\n}\n.auth-form .user-avatar[data-v-78e30cfc] {\n  width: 100px;\n  height: 100px;\n  -o-object-fit: cover;\n     object-fit: cover;\n  margin-bottom: 20px;\n  border-radius: 8px;\n  box-shadow: 0 10px 30px rgba(25, 54, 60, 0.2);\n}\n.auth-form .logo[data-v-78e30cfc] {\n  width: 120px;\n  margin-bottom: 20px;\n}\n.auth-form h1[data-v-78e30cfc] {\n  font-size: 2.125em;\n  font-weight: 800;\n  line-height: 1.2;\n  margin-bottom: 2px;\n  color: #1B2539;\n}\n.auth-form h2[data-v-78e30cfc] {\n  font-size: 1.4375em;\n  font-weight: 500;\n  margin-bottom: 50px;\n  color: #1B2539;\n}\n.auth-form .block-form[data-v-78e30cfc] {\n  margin-left: auto;\n  margin-right: auto;\n}\n@media only screen and (min-width: 690px) and (max-width: 960px) {\n.auth-form[data-v-78e30cfc] {\n    padding-left: 20%;\n    padding-right: 20%;\n}\n}\n@media only screen and (max-width: 690px) {\n.auth-form[data-v-78e30cfc] {\n    width: 100%;\n}\n.auth-form h1[data-v-78e30cfc] {\n    font-size: 1.875em;\n}\n.auth-form h2[data-v-78e30cfc] {\n    font-size: 1.3125em;\n}\n}\n@media only screen and (max-width: 490px) {\n.auth-form h1[data-v-78e30cfc] {\n    font-size: 1.375em;\n}\n.auth-form h2[data-v-78e30cfc] {\n    font-size: 1.125em;\n}\n.auth-form input[data-v-78e30cfc] {\n    min-width: initial;\n}\n.auth-form .additional-link[data-v-78e30cfc] {\n    font-size: 0.9375em;\n}\n}\n@media (prefers-color-scheme: dark) {\n.auth-form h1[data-v-78e30cfc], .auth-form h2[data-v-78e30cfc], .auth-form .additional-link[data-v-78e30cfc] {\n    color: #bec6cf;\n}\n}\n.content-headline[data-v-78e30cfc] {\n  max-width: 630px;\n  margin-left: auto;\n  margin-right: auto;\n}\n.auth-form input[data-v-78e30cfc] {\n  min-width: initial;\n}\n.form[data-v-78e30cfc] {\n  max-width: 580px;\n  text-align: left;\n}\n.submit-wrapper[data-v-78e30cfc] {\n  text-align: right;\n}\n.submit-wrapper .button[data-v-78e30cfc] {\n  margin: 58px 0 50px 0;\n  width: 100%;\n}\n.title-icon[data-v-78e30cfc] {\n  margin-bottom: 10px;\n  -webkit-animation: spinner-data-v-78e30cfc 5s linear infinite;\n          animation: spinner-data-v-78e30cfc 5s linear infinite;\n}\n.title-icon circle[data-v-78e30cfc], .title-icon path[data-v-78e30cfc] {\n  color: inherit;\n}\n@-webkit-keyframes spinner-data-v-78e30cfc {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@keyframes spinner-data-v-78e30cfc {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@media (prefers-color-scheme: dark) {\n.duplicator .duplicator-item[data-v-78e30cfc] {\n    background: #1e2024;\n}\n.duplicator .duplicator-item input[data-v-78e30cfc],\n  .duplicator .duplicator-item textarea[data-v-78e30cfc] {\n    background: #141414;\n}\n}\n.services[data-v-78e30cfc] {\n  margin: 0 auto;\n}\n.service-card[data-v-78e30cfc] {\n  text-align: left;\n  box-shadow: 0 5px 30px 5px rgba(61, 78, 253, 0.25);\n  border-radius: 20px;\n  max-width: 415px;\n  display: inline-block;\n  padding: 30px;\n  background: #3a4bff;\n  background: linear-gradient(135deg, #3a4bff 0%, #6772e5 100%);\n  transition: 200ms all ease;\n}\n.service-card[data-v-78e30cfc]:hover {\n  cursor: pointer;\n  box-shadow: 0 8px 35px 5px rgba(61, 78, 253, 0.4);\n  transform: scale(1.02);\n}\n.service-card .service-logo[data-v-78e30cfc] {\n  margin-bottom: 30px;\n  display: block;\n}\n.service-card .service-content[data-v-78e30cfc] {\n  margin-bottom: 65px;\n}\n.service-card .service-content .service-title[data-v-78e30cfc] {\n  font-size: 1.125em;\n  font-weight: 700;\n  color: white;\n  margin-bottom: 5px;\n  display: block;\n}\n.service-card .service-content .service-description[data-v-78e30cfc] {\n  font-size: 1em;\n  font-weight: 600;\n  color: white;\n  opacity: 0.8;\n}\n.service-card .service-link[data-v-78e30cfc] {\n  display: flex;\n  align-items: center;\n}\n.service-card .service-link .icon[data-v-78e30cfc] {\n  margin-left: 5px;\n}\n.service-card .service-link .icon polyline[data-v-78e30cfc] {\n  stroke: white;\n}\n.service-card .service-link span[data-v-78e30cfc] {\n  font-size: 1em;\n  font-weight: 700;\n  color: white;\n}\n.skip-subscription-setup[data-v-78e30cfc] {\n  border: none !important;\n}\n.auth-form input[data-v-78e30cfc] {\n  min-width: 380px;\n}\n',""])},tT9u:function(n,t,e){var a=e("sdkW");"string"==typeof a&&(a=[[n.i,a,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(a,i);a.locals&&(n.exports=a.locals)},v9ZB:function(n,t,e){"use strict";e.r(t);var a=e("bDRN"),i=e("A5+z"),r=e("j8qy"),c=e("ASoH"),o=e("CjXH"),d=e("TJPC"),l=(e("vDqi"),{name:"SubscriptionService",components:{AuthContentWrapper:a.a,ValidationProvider:i.ValidationProvider,ValidationObserver:i.ValidationObserver,ChevronRightIcon:o.i,SettingsIcon:o.bb,AuthContent:r.a,AuthButton:c.a,required:d.a},data:function(){return{isLoading:!1}},created:function(){this.$scrollTop()}}),s=(e("9L6e"),e("KHd+")),p=Object(s.a)(l,(function(){var n=this,t=n.$createElement,e=n._self._c||t;return e("AuthContentWrapper",{ref:"auth"},[e("AuthContent",{attrs:{name:"subscription-service",visible:!0}},[e("div",{staticClass:"content-headline"},[e("settings-icon",{staticClass:"title-icon",attrs:{size:"40"}}),n._v(" "),e("h1",[n._v("Setup Wizard")]),n._v(" "),e("h2",[n._v("You can charge users for storage space by monthly billing plans. Please, select your charging service or skip this step if you don't want charge users:")])],1),n._v(" "),e("div",{staticClass:"services"},[e("router-link",{staticClass:"service-card",attrs:{to:{name:"StripeCredentials"},tag:"div"}},[e("img",{staticClass:"service-logo",attrs:{src:"/assets/icons/stripe-service.svg",alt:"Stripe"}}),n._v(" "),e("div",{staticClass:"service-content"},[e("b",{staticClass:"service-title"},[n._v("Charging with Stripe")]),n._v(" "),e("p",{staticClass:"service-description"},[n._v("You can create custom storage plans and charge your users with monthly subscription.")])]),n._v(" "),e("router-link",{staticClass:"service-link",attrs:{to:{name:"StripeCredentials"}}},[e("span",[n._v("Set Up Billing and Plans With Stripe")]),n._v(" "),e("chevron-right-icon",{staticClass:"icon",attrs:{size:"22"}})],1)],1)],1),n._v(" "),e("p",{staticClass:"additional-link"},[e("router-link",{attrs:{to:{name:"EnvironmentSetup"}}},[e("AuthButton",{staticClass:"skip-subscription-setup",attrs:{icon:"chevron-right",text:"I will set up Stripe later"}})],1)],1)])],1)}),[],!1,null,"78e30cfc",null);t.default=p.exports}}]);