(window.webpackJsonp=window.webpackJsonp||[]).push([[20],{"61SX":function(t,a,n){"use strict";var e=n("cYTN");n.n(e).a},ASoH:function(t,a,n){"use strict";var e={name:"AuthContent",props:["loading","icon","text"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},i=(n("RLEU"),n("KHd+")),o=Object(i.a)(e,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("button",{staticClass:"button outline hover-text-theme hover-border-theme"},[n("span",{staticClass:"text-label"},[t._v(t._s(t.text))]),t._v(" "),t.loading?n("span",{staticClass:"icon"},[n("FontAwesomeIcon",{staticClass:"sync-alt svg-color-theme",attrs:{icon:"sync-alt"}})],1):t._e(),t._v(" "),!t.loading&&t.icon?n("span",{staticClass:"icon"},[n("FontAwesomeIcon",{staticClass:"svg-color-theme",attrs:{icon:t.icon}})],1):t._e()])}),[],!1,null,"16e9ad58",null);a.a=o.exports},JHC5:function(t,a,n){var e=n("YUi1");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},Jx8r:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,"#auth[data-v-31af8372] {\n  height: 100%;\n  width: 100%;\n  display: table;\n}\n",""])},KnjL:function(t,a,n){"use strict";var e={name:"InfoBox",props:["type"]},i=(n("pFam"),n("KHd+")),o=Object(i.a)(e,(function(){var t=this.$createElement;return(this._self._c||t)("div",{staticClass:"info-box",class:this.type},[this._t("default")],2)}),[],!1,null,"8e7c42f6",null);a.a=o.exports},LedX:function(t,a,n){"use strict";var e=n("WEWl");n.n(e).a},"Qqv+":function(t,a,n){var e=n("biqn");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},RLEU:function(t,a,n){"use strict";var e=n("JHC5");n.n(e).a},WEWl:function(t,a,n){var e=n("sGz8");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},YUi1:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".button[data-v-16e9ad58] {\n  cursor: pointer;\n  border-radius: 8px;\n  text-decoration: none;\n  padding: 12px 32px;\n  display: inline-block;\n  margin-left: 15px;\n  margin-right: 15px;\n  white-space: nowrap;\n  transition: 150ms all ease;\n  background: transparent;\n}\n.button .text-label[data-v-16e9ad58] {\n  transition: 150ms all ease;\n  font-size: 1.0625em;\n  font-weight: 800;\n  line-height: 0;\n}\n.button .icon[data-v-16e9ad58] {\n  margin-left: 12px;\n  font-size: 1em;\n}\n.button.solid[data-v-16e9ad58] {\n  background: #00BC7E;\n  border: 2px solid #00BC7E;\n}\n.button.solid .text-label[data-v-16e9ad58] {\n  color: white;\n}\n.button.outline[data-v-16e9ad58] {\n  border: 2px solid #1B2539;\n}\n.button.outline .text-label[data-v-16e9ad58] {\n  color: #1B2539;\n}\n.button.outline .icon path[data-v-16e9ad58] {\n  fill: inherit;\n}\n.button.outline[data-v-16e9ad58]:hover {\n  border-color: inherit;\n}\n.button.outline:hover .text-label[data-v-16e9ad58] {\n  color: inherit;\n}\n@media (prefers-color-scheme: dark) {\n.button.outline[data-v-16e9ad58] {\n    background: #141414;\n    border-color: #bec6cf;\n}\n.button.outline .text-label[data-v-16e9ad58] {\n    color: #bec6cf;\n}\n}\n.sync-alt[data-v-16e9ad58] {\n  -webkit-animation: spin-data-v-16e9ad58 1s linear infinite;\n          animation: spin-data-v-16e9ad58 1s linear infinite;\n}\n@-webkit-keyframes spin-data-v-16e9ad58 {\n0% {\n    transform: rotate(0);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@keyframes spin-data-v-16e9ad58 {\n0% {\n    transform: rotate(0);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n",""])},bDRN:function(t,a,n){"use strict";var e={name:"AuthContentWrapper"},i=(n("iYAH"),n("KHd+")),o=Object(i.a)(e,(function(){var t=this.$createElement;return(this._self._c||t)("div",{attrs:{id:"auth"}},[this._t("default")],2)}),[],!1,null,"31af8372",null);a.a=o.exports},biqn:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".info-box[data-v-8e7c42f6] {\n  padding: 20px;\n  border-radius: 8px;\n  margin-bottom: 32px;\n  background: #f4f5f6;\n  text-align: left;\n}\n.info-box.error[data-v-8e7c42f6] {\n  background: rgba(253, 57, 122, 0.1);\n}\n.info-box.error p[data-v-8e7c42f6], .info-box.error a[data-v-8e7c42f6] {\n  color: #fd397a;\n}\n.info-box.error a[data-v-8e7c42f6] {\n  text-decoration: underline;\n}\n.info-box p[data-v-8e7c42f6] {\n  font-size: 15px;\n  line-height: 1.6;\n  word-break: break-word;\n  font-weight: 600;\n}\n.info-box p[data-v-8e7c42f6] a {\n  font-size: 15px;\n}\n.info-box p[data-v-8e7c42f6] b {\n  font-size: 15px;\n  font-weight: 700;\n}\n.info-box b[data-v-8e7c42f6] {\n  font-weight: 700;\n}\n.info-box a[data-v-8e7c42f6] {\n  font-weight: 700;\n  font-size: 0.9375em;\n  line-height: 1.6;\n}\n.info-box ul[data-v-8e7c42f6] {\n  margin-top: 15px;\n  display: block;\n}\n.info-box ul li[data-v-8e7c42f6] {\n  display: block;\n}\n.info-box ul li a[data-v-8e7c42f6] {\n  display: block;\n}\n@media only screen and (max-width: 690px) {\n.info-box[data-v-8e7c42f6] {\n    padding: 15px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.info-box[data-v-8e7c42f6] {\n    background: #1e2024;\n}\n.info-box p[data-v-8e7c42f6] {\n    color: #bec6cf;\n}\n.info-box ul li[data-v-8e7c42f6] {\n    color: #bec6cf;\n}\n}\n",""])},cYTN:function(t,a,n){var e=n("id5q");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},iYAH:function(t,a,n){"use strict";var e=n("lh0Q");n.n(e).a},id5q:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,'.form[data-v-02336c22] {\n  max-width: 700px;\n}\n.form.inline-form[data-v-02336c22] {\n  display: flex;\n  position: relative;\n  justify-content: center;\n  margin: 0 auto;\n}\n.form.inline-form .input-wrapper[data-v-02336c22] {\n  position: relative;\n}\n.form.inline-form .input-wrapper .error-message[data-v-02336c22] {\n  position: absolute;\n  left: 0;\n  bottom: -25px;\n}\n.form.block-form .wrapper-inline[data-v-02336c22] {\n  display: flex;\n  margin: 0 -15px 0;\n}\n.form.block-form .wrapper-inline .block-wrapper[data-v-02336c22] {\n  width: 100%;\n  padding: 0 15px;\n}\n.form.block-form .block-wrapper[data-v-02336c22] {\n  margin-bottom: 22px;\n}\n.form.block-form .block-wrapper label[data-v-02336c22] {\n  font-size: 0.875em;\n  color: rgba(27, 37, 57, 0.8);\n  font-weight: 700;\n  display: block;\n  margin-bottom: 7px;\n  text-align: left;\n}\n.form.block-form .block-wrapper[data-v-02336c22]:last-child {\n  margin-bottom: 0;\n}\n.form.block-form .button[data-v-02336c22] {\n  margin-top: 50px;\n}\n.form .inline-wrapper[data-v-02336c22] {\n  display: flex;\n  align-items: center;\n  justify-content: space-between;\n}\n.form .inline-wrapper .switch-label .input-help[data-v-02336c22] {\n  padding-top: 0;\n}\n.form .inline-wrapper .switch-label .input-label[data-v-02336c22] {\n  font-weight: 700;\n  color: #1B2539;\n  font-size: 1em;\n  margin-bottom: 5px;\n}\n.form .input-help[data-v-02336c22] {\n  font-size: 0.75em;\n  color: rgba(27, 37, 57, 0.7);\n  line-height: 1.35;\n  padding-top: 10px;\n  display: block;\n}\n.single-line-form[data-v-02336c22] {\n  display: flex;\n}\n.single-line-form .submit-button[data-v-02336c22] {\n  margin-left: 20px;\n}\n.error-message[data-v-02336c22] {\n  font-size: 0.875em;\n  color: #fd397a;\n  padding-top: 5px;\n  display: block;\n  text-align: left;\n}\ntextarea[data-v-02336c22] {\n  width: 100%;\n}\ntextarea[data-v-02336c22],\ninput[type="password"][data-v-02336c22],\ninput[type="text"][data-v-02336c22],\ninput[type="number"][data-v-02336c22],\ninput[type="date"][data-v-02336c22],\ninput[type="email"][data-v-02336c22] {\n  border: 1px solid transparent;\n  transition: 150ms all ease;\n  font-size: 1em;\n  border-radius: 8px;\n  padding: 13px 20px;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  font-weight: 700;\n  outline: 0;\n  width: 100%;\n  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);\n  background: white;\n}\ntextarea.is-error[data-v-02336c22],\ninput[type="password"].is-error[data-v-02336c22],\ninput[type="text"].is-error[data-v-02336c22],\ninput[type="number"].is-error[data-v-02336c22],\ninput[type="date"].is-error[data-v-02336c22],\ninput[type="email"].is-error[data-v-02336c22] {\n  border-color: #fd397a;\n}\ntextarea[data-v-02336c22]::-webkit-input-placeholder, input[type="password"][data-v-02336c22]::-webkit-input-placeholder, input[type="text"][data-v-02336c22]::-webkit-input-placeholder, input[type="number"][data-v-02336c22]::-webkit-input-placeholder, input[type="date"][data-v-02336c22]::-webkit-input-placeholder, input[type="email"][data-v-02336c22]::-webkit-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-02336c22]::-moz-placeholder, input[type="password"][data-v-02336c22]::-moz-placeholder, input[type="text"][data-v-02336c22]::-moz-placeholder, input[type="number"][data-v-02336c22]::-moz-placeholder, input[type="date"][data-v-02336c22]::-moz-placeholder, input[type="email"][data-v-02336c22]::-moz-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-02336c22]:-ms-input-placeholder, input[type="password"][data-v-02336c22]:-ms-input-placeholder, input[type="text"][data-v-02336c22]:-ms-input-placeholder, input[type="number"][data-v-02336c22]:-ms-input-placeholder, input[type="date"][data-v-02336c22]:-ms-input-placeholder, input[type="email"][data-v-02336c22]:-ms-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-02336c22]::-ms-input-placeholder, input[type="password"][data-v-02336c22]::-ms-input-placeholder, input[type="text"][data-v-02336c22]::-ms-input-placeholder, input[type="number"][data-v-02336c22]::-ms-input-placeholder, input[type="date"][data-v-02336c22]::-ms-input-placeholder, input[type="email"][data-v-02336c22]::-ms-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-02336c22]::placeholder,\ninput[type="password"][data-v-02336c22]::placeholder,\ninput[type="text"][data-v-02336c22]::placeholder,\ninput[type="number"][data-v-02336c22]::placeholder,\ninput[type="date"][data-v-02336c22]::placeholder,\ninput[type="email"][data-v-02336c22]::placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[disabled][data-v-02336c22],\ninput[type="password"][disabled][data-v-02336c22],\ninput[type="text"][disabled][data-v-02336c22],\ninput[type="number"][disabled][data-v-02336c22],\ninput[type="date"][disabled][data-v-02336c22],\ninput[type="email"][disabled][data-v-02336c22] {\n  background: white;\n  color: rgba(27, 37, 57, 0.8);\n  -webkit-text-fill-color: rgba(27, 37, 57, 0.8);\n  opacity: 1;\n  cursor: not-allowed;\n}\ninput[type="color"][data-v-02336c22] {\n  width: 38px !important;\n  height: 40px;\n  border: none;\n  outline: none;\n  background: none;\n  min-width: initial !important;\n}\n.additional-link[data-v-02336c22] {\n  font-size: 1em;\n  margin-top: 50px;\n  display: block;\n}\n.additional-link b[data-v-02336c22], .additional-link a[data-v-02336c22] {\n  cursor: pointer;\n}\n.additional-link b[data-v-02336c22]:hover, .additional-link a[data-v-02336c22]:hover {\n  text-decoration: underline;\n}\n@media only screen and (max-width: 1024px) {\n.form[data-v-02336c22] {\n    max-width: 100%;\n}\n}\n@media only screen and (max-width: 960px) {\n.form .button[data-v-02336c22] {\n    margin-top: 20px;\n    width: 100%;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form input[data-v-02336c22], .form textarea[data-v-02336c22] {\n    width: 100%;\n    min-width: 100%;\n}\n.form.block-form .block-wrapper[data-v-02336c22] {\n    display: block;\n}\n.form.block-form .block-wrapper label[data-v-02336c22] {\n    width: 100%;\n    padding-right: 0;\n    display: block;\n    margin-bottom: 7px;\n    text-align: left !important;\n    font-size: 0.875em;\n    padding-top: 0;\n}\n.form.block-form .button[data-v-02336c22] {\n    margin-top: 25px;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form.inline-form[data-v-02336c22] {\n    display: block;\n}\n.form.inline-form .input-wrapper .error-message[data-v-02336c22] {\n    position: relative;\n    bottom: 0;\n}\n.form .button[data-v-02336c22] {\n    padding: 14px 32px;\n}\n.single-line-form[data-v-02336c22] {\n    display: block;\n}\n.single-line-form .submit-button[data-v-02336c22] {\n    margin-left: 0;\n    margin-top: 20px;\n    width: 100%;\n}\ntextarea[data-v-02336c22],\n  input[type="password"][data-v-02336c22],\n  input[type="number"][data-v-02336c22],\n  input[type="date"][data-v-02336c22],\n  input[type="text"][data-v-02336c22],\n  input[type="email"][data-v-02336c22] {\n    padding: 14px 20px;\n}\n}\n@media only screen and (max-width: 690px) {\n.form.block-form .wrapper-inline[data-v-02336c22] {\n    display: block;\n    margin-bottom: 32px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.form .input-help[data-v-02336c22] {\n    color: #7d858c;\n}\n.form.block-form .block-wrapper label[data-v-02336c22] {\n    color: #bec6cf;\n}\n.form .inline-wrapper .switch-label .input-label[data-v-02336c22] {\n    color: #bec6cf;\n}\ntextarea[data-v-02336c22],\n  input[type="password"][data-v-02336c22],\n  input[type="text"][data-v-02336c22],\n  input[type="date"][data-v-02336c22],\n  input[type="number"][data-v-02336c22],\n  input[type="email"][data-v-02336c22] {\n    border-color: #1e2024;\n    background: #1e2024;\n    color: #bec6cf;\n}\ntextarea[data-v-02336c22]::-webkit-input-placeholder, input[type="password"][data-v-02336c22]::-webkit-input-placeholder, input[type="text"][data-v-02336c22]::-webkit-input-placeholder, input[type="date"][data-v-02336c22]::-webkit-input-placeholder, input[type="number"][data-v-02336c22]::-webkit-input-placeholder, input[type="email"][data-v-02336c22]::-webkit-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-02336c22]::-moz-placeholder, input[type="password"][data-v-02336c22]::-moz-placeholder, input[type="text"][data-v-02336c22]::-moz-placeholder, input[type="date"][data-v-02336c22]::-moz-placeholder, input[type="number"][data-v-02336c22]::-moz-placeholder, input[type="email"][data-v-02336c22]::-moz-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-02336c22]:-ms-input-placeholder, input[type="password"][data-v-02336c22]:-ms-input-placeholder, input[type="text"][data-v-02336c22]:-ms-input-placeholder, input[type="date"][data-v-02336c22]:-ms-input-placeholder, input[type="number"][data-v-02336c22]:-ms-input-placeholder, input[type="email"][data-v-02336c22]:-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-02336c22]::-ms-input-placeholder, input[type="password"][data-v-02336c22]::-ms-input-placeholder, input[type="text"][data-v-02336c22]::-ms-input-placeholder, input[type="date"][data-v-02336c22]::-ms-input-placeholder, input[type="number"][data-v-02336c22]::-ms-input-placeholder, input[type="email"][data-v-02336c22]::-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-02336c22]::placeholder,\n  input[type="password"][data-v-02336c22]::placeholder,\n  input[type="text"][data-v-02336c22]::placeholder,\n  input[type="date"][data-v-02336c22]::placeholder,\n  input[type="number"][data-v-02336c22]::placeholder,\n  input[type="email"][data-v-02336c22]::placeholder {\n    color: #7d858c;\n}\ntextarea[disabled][data-v-02336c22],\n  input[type="password"][disabled][data-v-02336c22],\n  input[type="text"][disabled][data-v-02336c22],\n  input[type="date"][disabled][data-v-02336c22],\n  input[type="number"][disabled][data-v-02336c22],\n  input[type="email"][disabled][data-v-02336c22] {\n    background: #1e2024;\n    color: rgba(125, 133, 140, 0.8);\n    -webkit-text-fill-color: rgba(125, 133, 140, 0.8);\n}\n.popup-wrapper textarea[data-v-02336c22],\n  .popup-wrapper input[type="password"][data-v-02336c22],\n  .popup-wrapper input[type="date"][data-v-02336c22],\n  .popup-wrapper input[type="text"][data-v-02336c22],\n  .popup-wrapper input[type="number"][data-v-02336c22],\n  .popup-wrapper input[type="email"][data-v-02336c22] {\n    background: #25272c;\n}\n}\n.auth-logo-text[data-v-02336c22] {\n  font-size: 1.375em;\n  font-weight: 800;\n  margin-bottom: 40px;\n  display: block;\n}\n.auth-form[data-v-02336c22] {\n  text-align: center;\n  max-width: 600px;\n  padding: 25px 20px;\n  display: table-cell;\n  vertical-align: middle;\n}\n.auth-form input[data-v-02336c22] {\n  min-width: 310px;\n}\n.auth-form .additional-link a[data-v-02336c22] {\n  font-weight: 700;\n  text-decoration: none;\n}\n.auth-form .user-avatar[data-v-02336c22] {\n  width: 100px;\n  height: 100px;\n  -o-object-fit: cover;\n     object-fit: cover;\n  margin-bottom: 20px;\n  border-radius: 8px;\n  box-shadow: 0 10px 30px rgba(25, 54, 60, 0.2);\n}\n.auth-form .logo[data-v-02336c22] {\n  width: 120px;\n  margin-bottom: 20px;\n}\n.auth-form h1[data-v-02336c22] {\n  font-size: 2.125em;\n  font-weight: 800;\n  line-height: 1.2;\n  margin-bottom: 2px;\n  color: #1B2539;\n}\n.auth-form h2[data-v-02336c22] {\n  font-size: 1.4375em;\n  font-weight: 500;\n  margin-bottom: 50px;\n  color: #1B2539;\n}\n.auth-form .block-form[data-v-02336c22] {\n  margin-left: auto;\n  margin-right: auto;\n}\n@media only screen and (min-width: 690px) and (max-width: 960px) {\n.auth-form[data-v-02336c22] {\n    padding-left: 20%;\n    padding-right: 20%;\n}\n}\n@media only screen and (max-width: 690px) {\n.auth-form[data-v-02336c22] {\n    width: 100%;\n}\n.auth-form h1[data-v-02336c22] {\n    font-size: 1.875em;\n}\n.auth-form h2[data-v-02336c22] {\n    font-size: 1.3125em;\n}\n}\n@media only screen and (max-width: 490px) {\n.auth-form h1[data-v-02336c22] {\n    font-size: 1.375em;\n}\n.auth-form h2[data-v-02336c22] {\n    font-size: 1.125em;\n}\n.auth-form input[data-v-02336c22] {\n    min-width: initial;\n}\n.auth-form .additional-link[data-v-02336c22] {\n    font-size: 0.9375em;\n}\n}\n@media (prefers-color-scheme: dark) {\n.auth-form h1[data-v-02336c22], .auth-form h2[data-v-02336c22], .auth-form .additional-link[data-v-02336c22] {\n    color: #bec6cf;\n}\n}\n.content-headline[data-v-02336c22] {\n  max-width: 630px;\n  margin-left: auto;\n  margin-right: auto;\n}\n.auth-form input[data-v-02336c22] {\n  min-width: initial;\n}\n.form[data-v-02336c22] {\n  max-width: 580px;\n  text-align: left;\n}\n.submit-wrapper[data-v-02336c22] {\n  text-align: right;\n}\n.submit-wrapper .button[data-v-02336c22] {\n  margin: 58px 0 50px 0;\n  width: 100%;\n}\n.title-icon[data-v-02336c22] {\n  margin-bottom: 10px;\n  -webkit-animation: spinner-data-v-02336c22 5s linear infinite;\n          animation: spinner-data-v-02336c22 5s linear infinite;\n}\n.title-icon circle[data-v-02336c22], .title-icon path[data-v-02336c22] {\n  color: inherit;\n}\n@-webkit-keyframes spinner-data-v-02336c22 {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@keyframes spinner-data-v-02336c22 {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@media (prefers-color-scheme: dark) {\n.duplicator .duplicator-item[data-v-02336c22] {\n    background: #1e2024;\n}\n.duplicator .duplicator-item input[data-v-02336c22],\n  .duplicator .duplicator-item textarea[data-v-02336c22] {\n    background: #141414;\n}\n}\n',""])},j8qy:function(t,a,n){"use strict";var e={name:"AuthContent",props:["visible","name"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},i=n("KHd+"),o=Object(i.a)(e,(function(){var t=this.$createElement,a=this._self._c||t;return this.isVisible?a("div",{staticClass:"auth-form"},[this._t("default")],2):this._e()}),[],!1,null,null,null);a.a=o.exports},lh0Q:function(t,a,n){var e=n("Jx8r");"string"==typeof e&&(e=[[t.i,e,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,i);e.locals&&(t.exports=e.locals)},mveV:function(t,a,n){"use strict";n.r(a);var e=n("o0o1"),i=n.n(e),o=n("A5+z"),r=n("bDRN"),p=n("4TWA"),l=n("xxrA"),s=n("D+dh"),c=n("eZ9V"),d=n("KnjL"),u=n("j8qy"),v=n("ASoH"),m=n("CjXH"),f=n("TJPC"),h=n("vDqi"),b=n.n(h);function g(t,a,n,e,i,o,r){try{var p=t[o](r),l=p.value}catch(t){return void n(t)}p.done?a(l):Promise.resolve(l).then(e,i)}var x={name:"EnvironmentSetup",components:{AuthContentWrapper:r.a,ValidationProvider:o.ValidationProvider,ValidationObserver:o.ValidationObserver,SettingsIcon:m.bb,SelectInput:p.a,SwitchInput:l.a,AuthContent:u.a,ImageInput:s.a,AuthButton:v.a,FormLabel:c.a,required:f.a,InfoBox:d.a},data:function(){return{isLoading:!1,app:{title:"",description:"",logo:void 0,logo_horizontal:void 0,favicon:void 0,og_image:void 0,touch_icon:void 0,contactMail:"",googleAnalytics:"",defaultStorage:"5",userRegistration:1,storageLimitation:1}}},methods:{appSetupSubmit:function(){var t,a=this;return(t=i.a.mark((function t(){var n;return i.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,a.$refs.appSetup.validate();case 2:if(t.sent){t.next=5;break}return t.abrupt("return");case 5:a.isLoading=!0,(n=new FormData).append("title",a.app.title),n.append("description",a.app.description),n.append("contactMail",a.app.contactMail),n.append("userRegistration",Boolean(a.app.userRegistration)?1:0),n.append("storageLimitation",Boolean(a.app.storageLimitation)?1:0),a.app.googleAnalytics&&n.append("googleAnalytics",a.app.googleAnalytics),a.app.defaultStorage&&n.append("defaultStorage",a.app.defaultStorage),a.app.logo&&n.append("logo",a.app.logo),a.app.logo_horizontal&&n.append("logo_horizontal",a.app.logo_horizontal),a.app.og_image&&n.append("og_image",a.app.og_image),a.app.touch_icon&&n.append("touch_icon",a.app.touch_icon),a.app.favicon&&n.append("favicon",a.app.favicon),b.a.post("/api/setup/app-setup",n,{headers:{"Content-Type":"multipart/form-data"}}).then((function(t){a.isLoading=!1,a.$router.push({name:"AdminAccount"})})).catch((function(t){a.isLoading=!1}));case 20:case"end":return t.stop()}}),t)})),function(){var a=this,n=arguments;return new Promise((function(e,i){var o=t.apply(a,n);function r(t){g(o,e,i,r,p,"next",t)}function p(t){g(o,e,i,r,p,"throw",t)}r(void 0)}))})()}},created:function(){this.$scrollTop()}},w=(n("61SX"),n("KHd+")),y=Object(w.a)(x,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("AuthContentWrapper",{ref:"auth"},[n("AuthContent",{attrs:{name:"database-credentials",visible:!0}},[n("div",{staticClass:"content-headline"},[n("settings-icon",{staticClass:"title-icon",attrs:{size:"40"}}),t._v(" "),n("h1",[t._v("Setup Wizard")]),t._v(" "),n("h2",[t._v("Set up your application appearance, analytics, etc.")])],1),t._v(" "),n("ValidationObserver",{ref:"appSetup",staticClass:"form block-form",attrs:{tag:"form"},on:{submit:function(a){return a.preventDefault(),t.appSetupSubmit(a)}},scopedSlots:t._u([{key:"default",fn:function(a){a.invalid;return[n("FormLabel",[t._v("General Settings")]),t._v(" "),n("div",{staticClass:"block-wrapper"},[n("label",[t._v("App Title:")]),t._v(" "),n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Title",rules:"required"},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.errors;return[n("input",{directives:[{name:"model",rawName:"v-model",value:t.app.title,expression:"app.title"}],class:{"is-error":e[0]},attrs:{placeholder:"Type your app title",type:"text"},domProps:{value:t.app.title},on:{input:function(a){a.target.composing||t.$set(t.app,"title",a.target.value)}}}),t._v(" "),e[0]?n("span",{staticClass:"error-message"},[t._v(t._s(e[0]))]):t._e()]}}],null,!0)})],1),t._v(" "),n("div",{staticClass:"block-wrapper"},[n("label",[t._v("App Description:")]),t._v(" "),n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Description",rules:"required"},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.errors;return[n("input",{directives:[{name:"model",rawName:"v-model",value:t.app.description,expression:"app.description"}],class:{"is-error":e[0]},attrs:{placeholder:"Type your app description",type:"text"},domProps:{value:t.app.description},on:{input:function(a){a.target.composing||t.$set(t.app,"description",a.target.value)}}}),t._v(" "),e[0]?n("span",{staticClass:"error-message"},[t._v(t._s(e[0]))]):t._e()]}}],null,!0)})],1),t._v(" "),n("div",{staticClass:"block-wrapper"},[n("label",[t._v("App Logo (optional):")]),t._v(" "),n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Logo"},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.errors;return[n("ImageInput",{attrs:{error:e[0]},model:{value:t.app.logo,callback:function(a){t.$set(t.app,"logo",a)},expression:"app.logo"}})]}}],null,!0)})],1),t._v(" "),n("div",{staticClass:"block-wrapper"},[n("label",[t._v("App Logo Horizontal (optional):")]),t._v(" "),n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Logo"},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.errors;return[n("ImageInput",{attrs:{error:e[0]},model:{value:t.app.logo_horizontal,callback:function(a){t.$set(t.app,"logo_horizontal",a)},expression:"app.logo_horizontal"}})]}}],null,!0)})],1),t._v(" "),n("div",{staticClass:"block-wrapper"},[n("label",[t._v("App Favicon (optional):")]),t._v(" "),n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Favicon"},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.errors;return[n("ImageInput",{attrs:{error:e[0]},model:{value:t.app.favicon,callback:function(a){t.$set(t.app,"favicon",a)},expression:"app.favicon"}})]}}],null,!0)})],1),t._v(" "),n("div",{staticClass:"block-wrapper"},[n("label",[t._v("OG Image (optional):")]),t._v(" "),n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Favicon"},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.errors;return[n("ImageInput",{attrs:{error:e[0]},model:{value:t.app.og_image,callback:function(a){t.$set(t.app,"og_image",a)},expression:"app.og_image"}}),t._v(" "),n("small",{staticClass:"input-help"},[t._v("Image that appear when someone shares the content to Facebook or any other social medium. Preferred size is 1200x627")])]}}],null,!0)})],1),t._v(" "),n("div",{staticClass:"block-wrapper"},[n("label",[t._v("App Touch Icon (optional):")]),t._v(" "),n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"App Favicon"},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.errors;return[n("ImageInput",{attrs:{error:e[0]},model:{value:t.app.touch_icon,callback:function(a){t.$set(t.app,"touch_icon",a)},expression:"app.touch_icon"}}),t._v(" "),n("small",{staticClass:"input-help"},[t._v("If user store bookmark on his phone screen, this icon appear in app thumbnail. Preferred size is 156x156")])]}}],null,!0)})],1),t._v(" "),n("FormLabel",{staticClass:"mt-70"},[t._v("Others Information")]),t._v(" "),n("div",{staticClass:"block-wrapper"},[n("label",[t._v("Contact Email:")]),t._v(" "),n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Contact Email",rules:"required"},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.errors;return[n("input",{directives:[{name:"model",rawName:"v-model",value:t.app.contactMail,expression:"app.contactMail"}],class:{"is-error":e[0]},attrs:{placeholder:"Type your contact email",type:"email"},domProps:{value:t.app.contactMail},on:{input:function(a){a.target.composing||t.$set(t.app,"contactMail",a.target.value)}}}),t._v(" "),e[0]?n("span",{staticClass:"error-message"},[t._v(t._s(e[0]))]):t._e()]}}],null,!0)})],1),t._v(" "),n("div",{staticClass:"block-wrapper"},[n("label",[t._v("Google Analytics Code (optional):")]),t._v(" "),n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Google Analytics Code"},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.errors;return[n("input",{directives:[{name:"model",rawName:"v-model",value:t.app.googleAnalytics,expression:"app.googleAnalytics"}],class:{"is-error":e[0]},attrs:{placeholder:"Paste your Google Analytics Code",type:"text"},domProps:{value:t.app.googleAnalytics},on:{input:function(a){a.target.composing||t.$set(t.app,"googleAnalytics",a.target.value)}}}),t._v(" "),e[0]?n("span",{staticClass:"error-message"},[t._v(t._s(e[0]))]):t._e()]}}],null,!0)})],1),t._v(" "),n("div",{staticClass:"block-wrapper"},[n("div",{staticClass:"input-wrapper"},[n("div",{staticClass:"inline-wrapper"},[n("div",{staticClass:"switch-label"},[n("label",{staticClass:"input-label"},[t._v("Storage Limitation:")]),t._v(" "),n("small",{staticClass:"input-help"},[t._v("If this value is off, all users will have infinity storage capacity and you won't be "),n("br"),t._v("able to charge your users for storage plan.")])]),t._v(" "),n("SwitchInput",{staticClass:"switch",attrs:{state:t.app.storageLimitation},model:{value:t.app.storageLimitation,callback:function(a){t.$set(t.app,"storageLimitation",a)},expression:"app.storageLimitation"}})],1)])]),t._v(" "),t.app.storageLimitation?n("div",{staticClass:"block-wrapper"},[n("label",[t._v("Default Storage Space for Accounts:")]),t._v(" "),n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Default Storage Space",rules:"required"},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.errors;return[n("input",{directives:[{name:"model",rawName:"v-model",value:t.app.defaultStorage,expression:"app.defaultStorage"}],class:{"is-error":e[0]},attrs:{min:"1",max:"999999999",placeholder:"Set default storage space in GB",type:"number"},domProps:{value:t.app.defaultStorage},on:{input:function(a){a.target.composing||t.$set(t.app,"defaultStorage",a.target.value)}}}),t._v(" "),e[0]?n("span",{staticClass:"error-message"},[t._v(t._s(e[0]))]):t._e()]}}],null,!0)})],1):t._e(),t._v(" "),n("div",{staticClass:"block-wrapper"},[n("div",{staticClass:"input-wrapper"},[n("div",{staticClass:"inline-wrapper"},[n("div",{staticClass:"switch-label"},[n("label",{staticClass:"input-label"},[t._v("Allow User Registration:")]),t._v(" "),n("small",{staticClass:"input-help"},[t._v("You can disable public registration for new users. You will still able to "),n("br"),t._v("create new users in administration panel.")])]),t._v(" "),n("SwitchInput",{staticClass:"switch",attrs:{state:t.app.userRegistration},model:{value:t.app.userRegistration,callback:function(a){t.$set(t.app,"userRegistration",a)},expression:"app.userRegistration"}})],1)])]),t._v(" "),n("div",{staticClass:"submit-wrapper"},[n("AuthButton",{attrs:{icon:"chevron-right",text:"Save and Create Admin",loading:t.isLoading,disabled:t.isLoading}})],1)]}}])})],1)],1)}),[],!1,null,"02336c22",null);a.default=y.exports},pFam:function(t,a,n){"use strict";var e=n("Qqv+");n.n(e).a},sGz8:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".input-wrapper[data-v-421ca226] {\n  display: flex;\n  width: 100%;\n}\n.input-wrapper .input-label[data-v-421ca226] {\n  color: #1B2539;\n}\n.input-wrapper .switch-content[data-v-421ca226] {\n  width: 100%;\n}\n.input-wrapper .switch-content[data-v-421ca226]:last-child {\n  width: 80px;\n}\n.switch[data-v-421ca226] {\n  width: 50px;\n  height: 28px;\n  border-radius: 50px;\n  display: block;\n  background: #f1f1f5;\n  position: relative;\n  transition: 0.3s all ease;\n}\n.switch .switch-button[data-v-421ca226] {\n  transition: 0.3s all ease;\n  width: 22px;\n  height: 22px;\n  border-radius: 50px;\n  display: block;\n  background: white;\n  position: absolute;\n  top: 3px;\n  left: 3px;\n  box-shadow: 0 2px 4px rgba(37, 38, 94, 0.1);\n  cursor: pointer;\n}\n.switch.active .switch-button[data-v-421ca226] {\n  left: 25px;\n}\n@media (prefers-color-scheme: dark) {\n.switch[data-v-421ca226] {\n    background: #1e2024;\n}\n.popup-wrapper .switch[data-v-421ca226] {\n    background: #25272c;\n}\n}\n",""])},xxrA:function(t,a,n){"use strict";var e={name:"SwitchInput",props:["label","name","state","info"],data:function(){return{isSwitched:void 0}},methods:{changeState:function(){this.isSwitched=!this.isSwitched,this.$emit("input",this.isSwitched)}},mounted:function(){this.isSwitched=this.state}},i=(n("LedX"),n("KHd+")),o=Object(i.a)(e,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("div",{staticClass:"input-wrapper"},[n("div",{staticClass:"switch-content"},[t.label?n("label",{staticClass:"input-label"},[t._v(t._s(t.label)+":")]):t._e(),t._v(" "),t.info?n("small",{staticClass:"input-info"},[t._v(t._s(t.info))]):t._e()]),t._v(" "),n("div",{staticClass:"switch-content text-right"},[n("div",{staticClass:"switch",class:{active:t.isSwitched},on:{click:t.changeState}},[n("div",{staticClass:"switch-button"})])])])}),[],!1,null,"421ca226",null);a.a=o.exports}}]);