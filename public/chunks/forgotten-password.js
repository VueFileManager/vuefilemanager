(window.webpackJsonp=window.webpackJsonp||[]).push([[29],{"/qpS":function(a,t,n){"use strict";n.r(t);var e=n("o0o1"),o=n.n(e),r=n("bDRN"),d=n("A5+z"),i=n("j8qy"),p=n("ASoH"),c=n("TJPC"),s=n("L2JU"),l=n("vDqi"),b=n.n(l);function m(a,t,n,e,o,r,d){try{var i=a[r](d),p=i.value}catch(a){return void n(a)}i.done?t(p):Promise.resolve(p).then(e,o)}function u(a,t){var n=Object.keys(a);if(Object.getOwnPropertySymbols){var e=Object.getOwnPropertySymbols(a);t&&(e=e.filter((function(t){return Object.getOwnPropertyDescriptor(a,t).enumerable}))),n.push.apply(n,e)}return n}function v(a,t,n){return t in a?Object.defineProperty(a,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):a[t]=n,a}var f={name:"ForgottenPassword",components:{AuthContentWrapper:r.a,ValidationProvider:d.ValidationProvider,ValidationObserver:d.ValidationObserver,AuthContent:i.a,AuthButton:p.a,required:c.a},computed:function(a){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?u(Object(n),!0).forEach((function(t){v(a,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(a,Object.getOwnPropertyDescriptors(n)):u(Object(n)).forEach((function(t){Object.defineProperty(a,t,Object.getOwnPropertyDescriptor(n,t))}))}return a}({},Object(s.b)(["config"])),data:function(){return{isLoading:!1,recoverEmail:""}},methods:{goToAuthPage:function(a){this.$refs.auth.$children.forEach((function(t){t.isVisible=!1,t.$props.name===a&&(t.isVisible=!0)}))},forgottenPassword:function(){var a,t=this;return(a=o.a.mark((function a(){return o.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return a.next=2,t.$refs.forgotten_password.validate();case 2:if(a.sent){a.next=5;break}return a.abrupt("return");case 5:t.isLoading=!0,b.a.post("/api/password/email",{email:t.recoverEmail}).then((function(){t.isLoading=!1,t.goToAuthPage("password-reset-link-sended")})).catch((function(a){422==a.response.status&&t.$refs.forgotten_password.setErrors({"E-Mail":a.response.data.message}),t.isLoading=!1}));case 7:case"end":return a.stop()}}),a)})),function(){var t=this,n=arguments;return new Promise((function(e,o){var r=a.apply(t,n);function d(a){m(r,e,o,d,i,"next",a)}function i(a){m(r,e,o,d,i,"throw",a)}d(void 0)}))})()}}},h=(n("C9rf"),n("KHd+")),g=Object(h.a)(f,(function(){var a=this,t=a.$createElement,n=a._self._c||t;return n("AuthContentWrapper",{ref:"auth"},[n("AuthContent",{attrs:{name:"forgotten-password",visible:!0}},[a.config.app_logo?n("img",{staticClass:"logo",attrs:{src:a.$getImage(a.config.app_logo),alt:a.config.app_name}}):a._e(),a._v(" "),a.config.app_logo?a._e():n("b",{staticClass:"auth-logo-text"},[a._v(a._s(a.config.app_name))]),a._v(" "),n("h1",[a._v(a._s(a.$t("page_forgotten_password.title")))]),a._v(" "),n("h2",[a._v(a._s(a.$t("page_forgotten_password.subtitle")))]),a._v(" "),n("ValidationObserver",{ref:"forgotten_password",staticClass:"form inline-form",attrs:{tag:"form"},on:{submit:function(t){return t.preventDefault(),a.forgottenPassword(t)}},scopedSlots:a._u([{key:"default",fn:function(t){t.invalid;return[n("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"E-Mail",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var e=t.errors;return[n("input",{directives:[{name:"model",rawName:"v-model",value:a.recoverEmail,expression:"recoverEmail"}],staticClass:"focus-border-theme",class:{"is-error":e[0]},attrs:{placeholder:a.$t("page_login.placeholder_email"),type:"email"},domProps:{value:a.recoverEmail},on:{input:function(t){t.target.composing||(a.recoverEmail=t.target.value)}}}),a._v(" "),e[0]?n("span",{staticClass:"error-message"},[a._v(a._s(e[0]))]):a._e()]}}],null,!0)}),a._v(" "),n("AuthButton",{attrs:{icon:"chevron-right",text:a.$t("page_forgotten_password.button_get_link"),loading:a.isLoading,disabled:a.isLoading}})]}}])}),a._v(" "),n("span",{staticClass:"additional-link"},[a._v(a._s(a.$t("page_forgotten_password.password_remember_text"))+"\n            "),n("router-link",{staticClass:"text-theme",attrs:{to:{name:"SignIn"}}},[a._v("\n                "+a._s(a.$t("page_forgotten_password.password_remember_button"))+"\n            ")])],1)],1),a._v(" "),n("AuthContent",{attrs:{name:"password-reset-link-sended",visible:!1}},[a.config.app_logo?n("img",{staticClass:"logo",attrs:{src:a.$getImage(a.config.app_logo),alt:a.config.app_name}}):a._e(),a._v(" "),a.config.app_logo?a._e():n("b",{staticClass:"auth-logo-text"},[a._v(a._s(a.config.app_name))]),a._v(" "),n("h1",[a._v(a._s(a.$t("page_forgotten_password.pass_sennded_title")))]),a._v(" "),n("h2",[a._v(a._s(a.$t("page_forgotten_password.pass_sennded_subtitle")))]),a._v(" "),n("span",{staticClass:"additional-link"},[a._v(a._s(a.$t("page_forgotten_password.password_remember_text"))+"\n            "),n("router-link",{staticClass:"text-theme",attrs:{to:{name:"SignIn"}}},[a._v("\n                "+a._s(a.$t("page_forgotten_password.password_remember_button"))+"\n            ")])],1)])],1)}),[],!1,null,"d97cba30",null);t.default=g.exports},C9rf:function(a,t,n){"use strict";var e=n("WJ2G");n.n(e).a},WJ2G:function(a,t,n){var e=n("tkLU");"string"==typeof e&&(e=[[a.i,e,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(e,o);e.locals&&(a.exports=e.locals)},tkLU:function(a,t,n){(a.exports=n("I1BE")(!1)).push([a.i,'.form.inline-form[data-v-d97cba30] {\n  display: flex;\n  position: relative;\n  justify-content: center;\n  margin: 0 auto;\n}\n.form.inline-form .input-wrapper[data-v-d97cba30] {\n  position: relative;\n}\n.form.inline-form .input-wrapper .error-message[data-v-d97cba30] {\n  position: absolute;\n  left: 0;\n}\n.form.block-form.create-new-password .block-wrapper label[data-v-d97cba30] {\n  width: 280px;\n}\n.form.block-form .block-wrapper[data-v-d97cba30] {\n  display: flex;\n  align-items: center;\n  margin-top: 25px;\n  justify-content: center;\n}\n.form.block-form .block-wrapper[data-v-d97cba30]:first-child {\n  margin-top: 0;\n}\n.form.block-form .block-wrapper label[data-v-d97cba30] {\n  white-space: nowrap;\n  font-size: 1.125em;\n  font-weight: 700;\n  padding-right: 20px;\n  width: 200px;\n  text-align: right !important;\n  color: #1B2539;\n  text-align: right;\n}\n.form.block-form .button[data-v-d97cba30] {\n  margin-top: 50px;\n}\n.input-wrapper .error-message[data-v-d97cba30] {\n  font-size: 0.875em;\n  color: #fd397a;\n  padding-top: 5px;\n  display: block;\n  text-align: left;\n}\ntextarea[data-v-d97cba30] {\n  width: 100%;\n}\ntextarea[data-v-d97cba30],\ninput[type="password"][data-v-d97cba30],\ninput[type="text"][data-v-d97cba30],\ninput[type="email"][data-v-d97cba30] {\n  background: #f4f5f6;\n  border: 1px solid transparent;\n  transition: 0.15s all ease;\n  font-size: 1em;\n  border-radius: 8px;\n  padding: 13px 20px;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  font-weight: 700;\n  outline: 0;\n  width: 100%;\n}\ntextarea.is-error[data-v-d97cba30],\ninput[type="password"].is-error[data-v-d97cba30],\ninput[type="text"].is-error[data-v-d97cba30],\ninput[type="email"].is-error[data-v-d97cba30] {\n  border-color: #fd397a;\n}\ntextarea[data-v-d97cba30]::-webkit-input-placeholder, input[type="password"][data-v-d97cba30]::-webkit-input-placeholder, input[type="text"][data-v-d97cba30]::-webkit-input-placeholder, input[type="email"][data-v-d97cba30]::-webkit-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-d97cba30]::-moz-placeholder, input[type="password"][data-v-d97cba30]::-moz-placeholder, input[type="text"][data-v-d97cba30]::-moz-placeholder, input[type="email"][data-v-d97cba30]::-moz-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-d97cba30]:-ms-input-placeholder, input[type="password"][data-v-d97cba30]:-ms-input-placeholder, input[type="text"][data-v-d97cba30]:-ms-input-placeholder, input[type="email"][data-v-d97cba30]:-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-d97cba30]::-ms-input-placeholder, input[type="password"][data-v-d97cba30]::-ms-input-placeholder, input[type="text"][data-v-d97cba30]::-ms-input-placeholder, input[type="email"][data-v-d97cba30]::-ms-input-placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[data-v-d97cba30]::placeholder,\ninput[type="password"][data-v-d97cba30]::placeholder,\ninput[type="text"][data-v-d97cba30]::placeholder,\ninput[type="email"][data-v-d97cba30]::placeholder {\n  color: #A4ADB6;\n  font-size: 1em;\n}\ntextarea[disabled][data-v-d97cba30],\ninput[type="password"][disabled][data-v-d97cba30],\ninput[type="text"][disabled][data-v-d97cba30],\ninput[type="email"][disabled][data-v-d97cba30] {\n  color: #A4ADB6;\n  cursor: not-allowed;\n}\n.additional-link[data-v-d97cba30] {\n  font-size: 1em;\n  margin-top: 50px;\n  display: block;\n}\n.additional-link b[data-v-d97cba30], .additional-link a[data-v-d97cba30] {\n  cursor: pointer;\n}\n.additional-link b[data-v-d97cba30]:hover, .additional-link a[data-v-d97cba30]:hover {\n  text-decoration: underline;\n}\n@media only screen and (max-width: 960px) {\n.form .button[data-v-d97cba30] {\n    margin-top: 20px;\n    width: 100%;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form input[data-v-d97cba30], .form textarea[data-v-d97cba30] {\n    width: 100%;\n    min-width: 100%;\n}\n.form.block-form .block-wrapper[data-v-d97cba30] {\n    display: block;\n}\n.form.block-form .block-wrapper label[data-v-d97cba30] {\n    width: 100%;\n    padding-right: 0;\n    display: block;\n    margin-bottom: 7px;\n    text-align: left !important;\n    font-size: 0.875em;\n    padding-top: 0;\n}\n.form.block-form .button[data-v-d97cba30] {\n    margin-top: 25px;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form.inline-form[data-v-d97cba30] {\n    display: block;\n}\n.form.inline-form .input-wrapper .error-message[data-v-d97cba30] {\n    position: relative;\n    bottom: 0;\n}\n.form .button[data-v-d97cba30] {\n    padding: 14px 32px;\n}\ntextarea[data-v-d97cba30],\n  input[type="password"][data-v-d97cba30],\n  input[type="text"][data-v-d97cba30],\n  input[type="email"][data-v-d97cba30] {\n    padding: 14px 20px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.form.block-form .block-wrapper label[data-v-d97cba30] {\n    color: #bec6cf;\n}\ntextarea[data-v-d97cba30],\n  input[type="password"][data-v-d97cba30],\n  input[type="text"][data-v-d97cba30],\n  input[type="email"][data-v-d97cba30] {\n    background: #1e2024;\n    color: #bec6cf;\n}\ntextarea[data-v-d97cba30]::-webkit-input-placeholder, input[type="password"][data-v-d97cba30]::-webkit-input-placeholder, input[type="text"][data-v-d97cba30]::-webkit-input-placeholder, input[type="email"][data-v-d97cba30]::-webkit-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-d97cba30]::-moz-placeholder, input[type="password"][data-v-d97cba30]::-moz-placeholder, input[type="text"][data-v-d97cba30]::-moz-placeholder, input[type="email"][data-v-d97cba30]::-moz-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-d97cba30]:-ms-input-placeholder, input[type="password"][data-v-d97cba30]:-ms-input-placeholder, input[type="text"][data-v-d97cba30]:-ms-input-placeholder, input[type="email"][data-v-d97cba30]:-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-d97cba30]::-ms-input-placeholder, input[type="password"][data-v-d97cba30]::-ms-input-placeholder, input[type="text"][data-v-d97cba30]::-ms-input-placeholder, input[type="email"][data-v-d97cba30]::-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-d97cba30]::placeholder,\n  input[type="password"][data-v-d97cba30]::placeholder,\n  input[type="text"][data-v-d97cba30]::placeholder,\n  input[type="email"][data-v-d97cba30]::placeholder {\n    color: #7d858c;\n}\ntextarea[disabled][data-v-d97cba30],\n  input[type="password"][disabled][data-v-d97cba30],\n  input[type="text"][disabled][data-v-d97cba30],\n  input[type="email"][disabled][data-v-d97cba30] {\n    color: #7d858c;\n}\n}\n.auth-logo-text[data-v-d97cba30] {\n  font-size: 1.375em;\n  font-weight: 800;\n  margin-bottom: 40px;\n  display: block;\n}\n.auth-form[data-v-d97cba30] {\n  text-align: center;\n  max-width: 600px;\n  padding: 25px 20px;\n  display: table-cell;\n  vertical-align: middle;\n}\n.auth-form input[data-v-d97cba30] {\n  min-width: 310px;\n}\n.auth-form .additional-link a[data-v-d97cba30] {\n  font-weight: 700;\n  text-decoration: none;\n}\n.auth-form .user-avatar[data-v-d97cba30] {\n  width: 100px;\n  height: 100px;\n  -o-object-fit: cover;\n     object-fit: cover;\n  margin-bottom: 20px;\n  border-radius: 8px;\n  box-shadow: 0 10px 30px rgba(25, 54, 60, 0.2);\n}\n.auth-form .logo[data-v-d97cba30] {\n  width: 120px;\n  margin-bottom: 20px;\n}\n.auth-form h1[data-v-d97cba30] {\n  font-size: 2.125em;\n  font-weight: 800;\n  line-height: 1.2;\n  margin-bottom: 2px;\n  color: #1B2539;\n}\n.auth-form h2[data-v-d97cba30] {\n  font-size: 1.4375em;\n  font-weight: 500;\n  margin-bottom: 50px;\n  color: #1B2539;\n}\n.auth-form .block-form[data-v-d97cba30] {\n  margin-left: auto;\n  margin-right: auto;\n}\n@media only screen and (min-width: 690px) and (max-width: 960px) {\n.auth-form[data-v-d97cba30] {\n    padding-left: 20%;\n    padding-right: 20%;\n}\n}\n@media only screen and (max-width: 690px) {\n.auth-form[data-v-d97cba30] {\n    width: 100%;\n}\n.auth-form h1[data-v-d97cba30] {\n    font-size: 1.875em;\n}\n.auth-form h2[data-v-d97cba30] {\n    font-size: 1.3125em;\n}\n}\n@media only screen and (max-width: 490px) {\n.auth-form h1[data-v-d97cba30] {\n    font-size: 1.375em;\n}\n.auth-form h2[data-v-d97cba30] {\n    font-size: 1.125em;\n}\n.auth-form input[data-v-d97cba30] {\n    min-width: initial;\n}\n.auth-form .additional-link[data-v-d97cba30] {\n    font-size: 0.9375em;\n}\n}\n@media (prefers-color-scheme: dark) {\n.auth-form h1[data-v-d97cba30], .auth-form h2[data-v-d97cba30], .auth-form .additional-link[data-v-d97cba30] {\n    color: #bec6cf;\n}\n}\n',""])}}]);