(window.webpackJsonp=window.webpackJsonp||[]).push([[67],{"1gEC":function(t,a,n){"use strict";var e=n("wbGD");n.n(e).a},ASoH:function(t,a,n){"use strict";var e={name:"AuthContent",props:["loading","icon","text"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},o=(n("RLEU"),n("KHd+")),r=Object(o.a)(e,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("button",{staticClass:"button outline hover-text-theme hover-border-theme"},[n("span",{staticClass:"text-label"},[t._v(t._s(t.text))]),t._v(" "),t.loading?n("span",{staticClass:"icon"},[n("FontAwesomeIcon",{staticClass:"sync-alt svg-color-theme",attrs:{icon:"sync-alt"}})],1):t._e(),t._v(" "),!t.loading&&t.icon?n("span",{staticClass:"icon"},[n("FontAwesomeIcon",{staticClass:"svg-color-theme",attrs:{icon:t.icon}})],1):t._e()])}),[],!1,null,"16e9ad58",null);a.a=r.exports},JHC5:function(t,a,n){var e=n("YUi1");"string"==typeof e&&(e=[[t.i,e,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,o);e.locals&&(t.exports=e.locals)},RLEU:function(t,a,n){"use strict";var e=n("JHC5");n.n(e).a},YUi1:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,".button[data-v-16e9ad58] {\n  cursor: pointer;\n  border-radius: 8px;\n  text-decoration: none;\n  padding: 12px 32px;\n  display: inline-block;\n  margin-left: 15px;\n  margin-right: 15px;\n  white-space: nowrap;\n  transition: 150ms all ease;\n  background: transparent;\n}\n.button .text-label[data-v-16e9ad58] {\n  transition: 150ms all ease;\n  font-size: 1.0625em;\n  font-weight: 800;\n  line-height: 0;\n}\n.button .icon[data-v-16e9ad58] {\n  margin-left: 12px;\n  font-size: 1em;\n}\n.button.solid[data-v-16e9ad58] {\n  background: #00BC7E;\n  border: 2px solid #00BC7E;\n}\n.button.solid .text-label[data-v-16e9ad58] {\n  color: white;\n}\n.button.outline[data-v-16e9ad58] {\n  border: 2px solid #1B2539;\n}\n.button.outline .text-label[data-v-16e9ad58] {\n  color: #1B2539;\n}\n.button.outline .icon path[data-v-16e9ad58] {\n  fill: inherit;\n}\n.button.outline[data-v-16e9ad58]:hover {\n  border-color: inherit;\n}\n.button.outline:hover .text-label[data-v-16e9ad58] {\n  color: inherit;\n}\n@media (prefers-color-scheme: dark) {\n.button.outline[data-v-16e9ad58] {\n    background: #141414;\n    border-color: #bec6cf;\n}\n.button.outline .text-label[data-v-16e9ad58] {\n    color: #bec6cf;\n}\n}\n.sync-alt[data-v-16e9ad58] {\n  -webkit-animation: spin-data-v-16e9ad58 1s linear infinite;\n          animation: spin-data-v-16e9ad58 1s linear infinite;\n}\n@-webkit-keyframes spin-data-v-16e9ad58 {\n0% {\n    transform: rotate(0);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@keyframes spin-data-v-16e9ad58 {\n0% {\n    transform: rotate(0);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n",""])},dD68:function(t,a,n){"use strict";n.r(a);var e=n("o0o1"),o=n.n(e),r=n("A5+z"),i=n("j8qy"),d=n("ASoH"),c=n("vDqi"),p=n.n(c),l=n("L2JU");function s(t,a,n,e,o,r,i){try{var d=t[r](i),c=d.value}catch(t){return void n(t)}d.done?a(c):Promise.resolve(c).then(e,o)}function u(t,a){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var e=Object.getOwnPropertySymbols(t);a&&(e=e.filter((function(a){return Object.getOwnPropertyDescriptor(t,a).enumerable}))),n.push.apply(n,e)}return n}function m(t,a,n){return a in t?Object.defineProperty(t,a,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[a]=n,t}var v={name:"SharedAuthentication",components:{ValidationObserver:r.ValidationObserver,ValidationProvider:r.ValidationProvider,AuthContent:i.a,AuthButton:d.a},computed:function(t){for(var a=1;a<arguments.length;a++){var n=null!=arguments[a]?arguments[a]:{};a%2?u(Object(n),!0).forEach((function(a){m(t,a,n[a])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):u(Object(n)).forEach((function(a){Object.defineProperty(t,a,Object.getOwnPropertyDescriptor(n,a))}))}return t}({},Object(l.b)(["config"])),data:function(){return{password:"secret",isLoading:!1}},methods:{authenticateProtected:function(){var t,a=this;return(t=o.a.mark((function t(){return o.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,a.$refs.authenticateProtected.validate();case 2:if(t.sent){t.next=5;break}return t.abrupt("return");case 5:a.isLoading=!0,p.a.post("/api/browse/authenticate/"+a.$route.params.token,{password:a.password}).then((function(t){"folder"===t.data.data.attributes.type&&"SharedFileBrowser"!==a.$router.currentRoute.name&&a.$router.push({name:"SharedFileBrowser"}),"folder"!==t.data.data.attributes.type&&"SharedSingleFile"!==a.$router.currentRoute.name&&a.$router.push({name:"SharedSingleFile"})})).catch((function(t){401==t.response.status&&a.$refs.authenticateProtected.setErrors({Password:[t.response.data.message]})})).finally((function(){a.isLoading=!1}));case 7:case"end":return t.stop()}}),t)})),function(){var a=this,n=arguments;return new Promise((function(e,o){var r=t.apply(a,n);function i(t){s(r,e,o,i,d,"next",t)}function d(t){s(r,e,o,i,d,"throw",t)}i(void 0)}))})()}}},f=(n("1gEC"),n("KHd+")),h=Object(f.a)(v,(function(){var t=this,a=t.$createElement,n=t._self._c||a;return n("div",{attrs:{id:"password-view"}},[n("AuthContent",{staticClass:"center",attrs:{name:"password",visible:!0}},[t.config.app_logo?n("img",{staticClass:"logo",attrs:{src:t.$getImage(t.config.app_logo),alt:t.config.app_name}}):t._e(),t._v(" "),t.config.app_logo?t._e():n("b",{staticClass:"auth-logo-text"},[t._v(t._s(t.config.app_name))]),t._v(" "),n("h1",[t._v(t._s(t.$t("page_shared.title")))]),t._v(" "),n("h2",[t._v(t._s(t.$t("page_shared.subtitle")))]),t._v(" "),n("ValidationObserver",{ref:"authenticateProtected",staticClass:"form inline-form",attrs:{tag:"form"},on:{submit:function(a){return a.preventDefault(),t.authenticateProtected(a)}},scopedSlots:t._u([{key:"default",fn:function(a){a.invalid;return[n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Password",rules:"required"},scopedSlots:t._u([{key:"default",fn:function(a){var e=a.errors;return[n("input",{directives:[{name:"model",rawName:"v-model",value:t.password,expression:"password"}],class:{"is-error":e[0]},attrs:{placeholder:t.$t("page_shared.placeholder_pass"),type:"password"},domProps:{value:t.password},on:{input:function(a){a.target.composing||(t.password=a.target.value)}}}),t._v(" "),e[0]?n("span",{staticClass:"error-message"},[t._v(t._s(e[0]))]):t._e()]}}],null,!0)}),t._v(" "),n("AuthButton",{attrs:{icon:"chevron-right",text:t.$t("page_shared.submit"),loading:t.isLoading,disabled:t.isLoading}})]}}])})],1)],1)}),[],!1,null,"21301a0c",null);a.default=h.exports},j8qy:function(t,a,n){"use strict";var e={name:"AuthContent",props:["visible","name"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},o=n("KHd+"),r=Object(o.a)(e,(function(){var t=this.$createElement,a=this._self._c||t;return this.isVisible?a("div",{staticClass:"auth-form"},[this._t("default")],2):this._e()}),[],!1,null,null,null);a.a=r.exports},wbGD:function(t,a,n){var e=n("xSDe");"string"==typeof e&&(e=[[t.i,e,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,o);e.locals&&(t.exports=e.locals)},xSDe:function(t,a,n){(t.exports=n("I1BE")(!1)).push([t.i,'.form.inline-form[data-v-21301a0c] {\n  display: flex;\n  position: relative;\n  justify-content: center;\n  margin: 0 auto;\n}\n.form.inline-form .input-wrapper[data-v-21301a0c] {\n  position: relative;\n}\n.form.inline-form .input-wrapper .error-message[data-v-21301a0c] {\n  position: absolute;\n  left: 0;\n}\n.form.block-form.create-new-password .block-wrapper label[data-v-21301a0c] {\n  width: 280px;\n}\n.form.block-form .block-wrapper[data-v-21301a0c] {\n  display: flex;\n  align-items: center;\n  margin-top: 25px;\n  justify-content: center;\n}\n.form.block-form .block-wrapper[data-v-21301a0c]:first-child {\n  margin-top: 0;\n}\n.form.block-form .block-wrapper label[data-v-21301a0c] {\n  white-space: nowrap;\n  font-size: 1.125em;\n  font-weight: 700;\n  padding-right: 20px;\n  width: 200px;\n  text-align: right !important;\n  color: #1B2539;\n  text-align: right;\n}\n.form.block-form .button[data-v-21301a0c] {\n  margin-top: 50px;\n}\n.input-wrapper .error-message[data-v-21301a0c] {\n  font-size: 0.875em;\n  color: #fd397a;\n  padding-top: 5px;\n  display: block;\n  text-align: left;\n}\ntextarea[data-v-21301a0c] {\n  width: 100%;\n}\ntextarea[data-v-21301a0c],\ninput[type="password"][data-v-21301a0c],\ninput[type="text"][data-v-21301a0c],\ninput[type="email"][data-v-21301a0c] {\n  background: #f4f5f6;\n  border: 1px solid transparent;\n  transition: 0.15s all ease;\n  font-size: 1em;\n  border-radius: 8px;\n  padding: 13px 20px;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  font-weight: 700;\n  outline: 0;\n  width: 100%;\n}\ntextarea.is-error[data-v-21301a0c],\ninput[type="password"].is-error[data-v-21301a0c],\ninput[type="text"].is-error[data-v-21301a0c],\ninput[type="email"].is-error[data-v-21301a0c] {\n  border-color: #fd397a;\n}\ntextarea[data-v-21301a0c]::-webkit-input-placeholder, input[type="password"][data-v-21301a0c]::-webkit-input-placeholder, input[type="text"][data-v-21301a0c]::-webkit-input-placeholder, input[type="email"][data-v-21301a0c]::-webkit-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-21301a0c]::-moz-placeholder, input[type="password"][data-v-21301a0c]::-moz-placeholder, input[type="text"][data-v-21301a0c]::-moz-placeholder, input[type="email"][data-v-21301a0c]::-moz-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-21301a0c]:-ms-input-placeholder, input[type="password"][data-v-21301a0c]:-ms-input-placeholder, input[type="text"][data-v-21301a0c]:-ms-input-placeholder, input[type="email"][data-v-21301a0c]:-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-21301a0c]::-ms-input-placeholder, input[type="password"][data-v-21301a0c]::-ms-input-placeholder, input[type="text"][data-v-21301a0c]::-ms-input-placeholder, input[type="email"][data-v-21301a0c]::-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-21301a0c]::placeholder,\ninput[type="password"][data-v-21301a0c]::placeholder,\ninput[type="text"][data-v-21301a0c]::placeholder,\ninput[type="email"][data-v-21301a0c]::placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[disabled][data-v-21301a0c],\ninput[type="password"][disabled][data-v-21301a0c],\ninput[type="text"][disabled][data-v-21301a0c],\ninput[type="email"][disabled][data-v-21301a0c] {\n  color: #A4ADB6;\n  cursor: not-allowed;\n}\n.additional-link[data-v-21301a0c] {\n  font-size: 1em;\n  margin-top: 50px;\n  display: block;\n}\n.additional-link b[data-v-21301a0c], .additional-link a[data-v-21301a0c] {\n  cursor: pointer;\n}\n.additional-link b[data-v-21301a0c]:hover, .additional-link a[data-v-21301a0c]:hover {\n  text-decoration: underline;\n}\n@media only screen and (max-width: 960px) {\n.form .button[data-v-21301a0c] {\n    margin-top: 20px;\n    width: 100%;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form input[data-v-21301a0c], .form textarea[data-v-21301a0c] {\n    width: 100%;\n    min-width: 100%;\n}\n.form.block-form .block-wrapper[data-v-21301a0c] {\n    display: block;\n}\n.form.block-form .block-wrapper label[data-v-21301a0c] {\n    width: 100%;\n    padding-right: 0;\n    display: block;\n    margin-bottom: 7px;\n    text-align: left !important;\n    font-size: 0.875em;\n    padding-top: 0;\n}\n.form.block-form .button[data-v-21301a0c] {\n    margin-top: 25px;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form.inline-form[data-v-21301a0c] {\n    display: block;\n}\n.form.inline-form .input-wrapper .error-message[data-v-21301a0c] {\n    position: relative;\n    bottom: 0;\n}\n.form .button[data-v-21301a0c] {\n    padding: 14px 32px;\n}\ntextarea[data-v-21301a0c],\n  input[type="password"][data-v-21301a0c],\n  input[type="text"][data-v-21301a0c],\n  input[type="email"][data-v-21301a0c] {\n    padding: 14px 20px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.form.block-form .block-wrapper label[data-v-21301a0c] {\n    color: #bec6cf;\n}\ntextarea[data-v-21301a0c],\n  input[type="password"][data-v-21301a0c],\n  input[type="text"][data-v-21301a0c],\n  input[type="email"][data-v-21301a0c] {\n    background: #1e2024;\n    color: #bec6cf;\n}\ntextarea[data-v-21301a0c]::-webkit-input-placeholder, input[type="password"][data-v-21301a0c]::-webkit-input-placeholder, input[type="text"][data-v-21301a0c]::-webkit-input-placeholder, input[type="email"][data-v-21301a0c]::-webkit-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-21301a0c]::-moz-placeholder, input[type="password"][data-v-21301a0c]::-moz-placeholder, input[type="text"][data-v-21301a0c]::-moz-placeholder, input[type="email"][data-v-21301a0c]::-moz-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-21301a0c]:-ms-input-placeholder, input[type="password"][data-v-21301a0c]:-ms-input-placeholder, input[type="text"][data-v-21301a0c]:-ms-input-placeholder, input[type="email"][data-v-21301a0c]:-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-21301a0c]::-ms-input-placeholder, input[type="password"][data-v-21301a0c]::-ms-input-placeholder, input[type="text"][data-v-21301a0c]::-ms-input-placeholder, input[type="email"][data-v-21301a0c]::-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-21301a0c]::placeholder,\n  input[type="password"][data-v-21301a0c]::placeholder,\n  input[type="text"][data-v-21301a0c]::placeholder,\n  input[type="email"][data-v-21301a0c]::placeholder {\n    color: #7d858c;\n}\ntextarea[disabled][data-v-21301a0c],\n  input[type="password"][disabled][data-v-21301a0c],\n  input[type="text"][disabled][data-v-21301a0c],\n  input[type="email"][disabled][data-v-21301a0c] {\n    color: #7d858c;\n}\n}\n.auth-logo-text[data-v-21301a0c] {\n  font-size: 1.375em;\n  font-weight: 800;\n  margin-bottom: 40px;\n  display: block;\n}\n.auth-form[data-v-21301a0c] {\n  text-align: center;\n  max-width: 600px;\n  padding: 25px 20px;\n  display: table-cell;\n  vertical-align: middle;\n}\n.auth-form input[data-v-21301a0c] {\n  min-width: 310px;\n}\n.auth-form .additional-link a[data-v-21301a0c] {\n  font-weight: 700;\n  text-decoration: none;\n}\n.auth-form .user-avatar[data-v-21301a0c] {\n  width: 100px;\n  height: 100px;\n  -o-object-fit: cover;\n     object-fit: cover;\n  margin-bottom: 20px;\n  border-radius: 8px;\n  box-shadow: 0 10px 30px rgba(25, 54, 60, 0.2);\n}\n.auth-form .logo[data-v-21301a0c] {\n  width: 120px;\n  margin-bottom: 20px;\n}\n.auth-form h1[data-v-21301a0c] {\n  font-size: 2.125em;\n  font-weight: 800;\n  line-height: 1.2;\n  margin-bottom: 2px;\n  color: #1B2539;\n}\n.auth-form h2[data-v-21301a0c] {\n  font-size: 1.4375em;\n  font-weight: 500;\n  margin-bottom: 50px;\n  color: #1B2539;\n}\n.auth-form .block-form[data-v-21301a0c] {\n  margin-left: auto;\n  margin-right: auto;\n}\n@media only screen and (min-width: 690px) and (max-width: 960px) {\n.auth-form[data-v-21301a0c] {\n    padding-left: 20%;\n    padding-right: 20%;\n}\n}\n@media only screen and (max-width: 690px) {\n.auth-form[data-v-21301a0c] {\n    width: 100%;\n}\n.auth-form h1[data-v-21301a0c] {\n    font-size: 1.875em;\n}\n.auth-form h2[data-v-21301a0c] {\n    font-size: 1.3125em;\n}\n}\n@media only screen and (max-width: 490px) {\n.auth-form h1[data-v-21301a0c] {\n    font-size: 1.375em;\n}\n.auth-form h2[data-v-21301a0c] {\n    font-size: 1.125em;\n}\n.auth-form input[data-v-21301a0c] {\n    min-width: initial;\n}\n.auth-form .additional-link[data-v-21301a0c] {\n    font-size: 0.9375em;\n}\n}\n@media (prefers-color-scheme: dark) {\n.auth-form h1[data-v-21301a0c], .auth-form h2[data-v-21301a0c], .auth-form .additional-link[data-v-21301a0c] {\n    color: #bec6cf;\n}\n}\n#password-view[data-v-21301a0c] {\n  width: 100%;\n  display: grid;\n  height: inherit;\n}\n#password-view .center[data-v-21301a0c] {\n  margin: auto;\n}\n',""])}}]);