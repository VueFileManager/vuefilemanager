(window.webpackJsonp=window.webpackJsonp||[]).push([[45],{ASoH:function(a,t,e){"use strict";var o={name:"AuthContent",props:["loading","icon","text"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},i=(e("s/ZW"),e("KHd+")),n=Object(i.a)(o,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("button",{staticClass:"button outline"},[e("span",{staticClass:"text-label"},[a._v(a._s(a.text))]),a._v(" "),a.loading?e("span",{staticClass:"icon"},[e("FontAwesomeIcon",{staticClass:"sync-alt",attrs:{icon:"sync-alt"}})],1):a._e(),a._v(" "),!a.loading&&a.icon?e("span",{staticClass:"icon"},[e("FontAwesomeIcon",{attrs:{icon:a.icon}})],1):a._e()])}),[],!1,null,"59e2dfc0",null);t.a=n.exports},GwTe:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".button[data-v-59e2dfc0]{cursor:pointer;border-radius:8px;text-decoration:none;padding:12px 32px;display:inline-block;margin-left:15px;margin-right:15px;white-space:nowrap;transition:all .15s ease;background:transparent}.button .text-label[data-v-59e2dfc0]{transition:all .15s ease;font-size:1.0625em;font-weight:800;line-height:0}.button .icon[data-v-59e2dfc0]{margin-left:12px;font-size:1em}.button.solid[data-v-59e2dfc0]{background:#00bc7e;border:2px solid #00bc7e}.button.solid .text-label[data-v-59e2dfc0]{color:#fff}.button.outline[data-v-59e2dfc0]{border:2px solid #1b2539}.button.outline .text-label[data-v-59e2dfc0]{color:#1b2539}.button.outline .icon path[data-v-59e2dfc0]{fill:#00bc7e}.button.outline[data-v-59e2dfc0]:hover{border-color:#00bc7e}.button.outline:hover .text-label[data-v-59e2dfc0]{color:#00bc7e}@media (prefers-color-scheme:dark){.button.outline[data-v-59e2dfc0]{background:#111314;border-color:#bec6cf}.button.outline .text-label[data-v-59e2dfc0]{color:#bec6cf}}.sync-alt[data-v-59e2dfc0]{-webkit-animation:spin-data-v-59e2dfc0 1s linear infinite;animation:spin-data-v-59e2dfc0 1s linear infinite}@-webkit-keyframes spin-data-v-59e2dfc0{0%{transform:rotate(0)}to{transform:rotate(1turn)}}@keyframes spin-data-v-59e2dfc0{0%{transform:rotate(0)}to{transform:rotate(1turn)}}",""])},Jx8r:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,"#auth[data-v-31af8372]{height:100%;width:100%;display:table}",""])},NbAf:function(a,t,e){var o=e("GwTe");"string"==typeof o&&(o=[[a.i,o,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(o,i);o.locals&&(a.exports=o.locals)},bDRN:function(a,t,e){"use strict";var o={name:"AuthContentWrapper"},i=(e("iYAH"),e("KHd+")),n=Object(i.a)(o,(function(){var a=this.$createElement;return(this._self._c||a)("div",{attrs:{id:"auth"}},[this._t("default")],2)}),[],!1,null,"31af8372",null);t.a=n.exports},iYAH:function(a,t,e){"use strict";var o=e("lh0Q");e.n(o).a},j8qy:function(a,t,e){"use strict";var o={name:"AuthContent",props:["visible","name"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},i=e("KHd+"),n=Object(i.a)(o,(function(){var a=this.$createElement,t=this._self._c||a;return this.isVisible?t("div",{staticClass:"auth-form"},[this._t("default")],2):this._e()}),[],!1,null,null,null);t.a=n.exports},"l9/H":function(a,t,e){"use strict";e.r(t);var o=e("o0o1"),i=e.n(o),n=e("bDRN"),r=e("A5+z"),s=e("j8qy"),l=e("ASoH"),d=e("TJPC"),p=e("L2JU"),c=e("xCqy"),u=e("vDqi"),f=e.n(u);function m(a,t,e,o,i,n,r){try{var s=a[n](r),l=s.value}catch(a){return void e(a)}s.done?t(l):Promise.resolve(l).then(o,i)}function v(a){return function(){var t=this,e=arguments;return new Promise((function(o,i){var n=a.apply(t,e);function r(a){m(n,o,i,r,s,"next",a)}function s(a){m(n,o,i,r,s,"throw",a)}r(void 0)}))}}function g(a,t){var e=Object.keys(a);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(a);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(a,t).enumerable}))),e.push.apply(e,o)}return e}function h(a,t,e){return t in a?Object.defineProperty(a,t,{value:e,enumerable:!0,configurable:!0,writable:!0}):a[t]=e,a}var b={name:"SignIn",components:{AuthContentWrapper:n.a,ValidationProvider:r.ValidationProvider,ValidationObserver:r.ValidationObserver,AuthContent:s.a,AuthButton:l.a,required:d.a},computed:function(a){for(var t=1;t<arguments.length;t++){var e=null!=arguments[t]?arguments[t]:{};t%2?g(Object(e),!0).forEach((function(t){h(a,t,e[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(a,Object.getOwnPropertyDescriptors(e)):g(Object(e)).forEach((function(t){Object.defineProperty(a,t,Object.getOwnPropertyDescriptor(e,t))}))}return a}({},Object(p.b)(["config"])),data:function(){return{isLoading:!1,checkedAccount:void 0,loginPassword:"",loginEmail:""}},methods:{goToAuthPage:function(a){this.$refs.auth.$children.forEach((function(t){t.isVisible=!1,t.$props.name===a&&(t.isVisible=!0)}))},logIn:function(){var a=this;return v(i.a.mark((function t(){return i.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,a.$refs.log_in.validate();case 2:if(t.sent){t.next=5;break}return t.abrupt("return");case 5:a.isLoading=!0,f.a.post("/api/user/check",{email:a.loginEmail}).then((function(t){a.isLoading=!1,a.checkedAccount=t.data,a.goToAuthPage("sign-in")})).catch((function(t){404==t.response.status&&a.$refs.log_in.setErrors({"E-Mail":[t.response.data.message]}),500==t.response.status&&c.a.$emit("alert:open",{emoji:"🤔",title:a.$t("popup_signup_error.title"),message:a.$t("popup_signup_error.message")}),a.isLoading=!1}));case 7:case"end":return t.stop()}}),t)})))()},singIn:function(){var a=this;return v(i.a.mark((function t(){return i.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,a.$refs.sign_in.validate();case 2:if(t.sent){t.next=5;break}return t.abrupt("return");case 5:a.isLoading=!0,f.a.post("/api/user/login",{email:a.loginEmail,password:a.loginPassword}).then((function(){a.isLoading=!1,a.$store.commit("SET_AUTHORIZED",!0),a.$router.push({name:"Files"})})).catch((function(t){400==t.response.status&&a.$refs.sign_in.setErrors({"User Password":[a.$t("validation_errors.incorrect_password")]}),401==t.response.status&&"invalid_client"===t.response.data.error&&c.a.$emit("alert:open",{emoji:"🤔",title:a.$t("popup_passport_error.title"),message:a.$t("popup_passport_error.message")}),a.isLoading=!1}));case 7:case"end":return t.stop()}}),t)})))()}},created:function(){this.$scrollTop()}},x=(e("wVBQ"),e("KHd+")),_=Object(x.a)(b,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("AuthContentWrapper",{ref:"auth"},[e("AuthContent",{attrs:{name:"log-in",visible:!0}},[a.config.app_logo?e("img",{staticClass:"logo",attrs:{src:a.$getImage(a.config.app_logo),alt:a.config.app_name}}):a._e(),a._v(" "),a.config.app_logo?a._e():e("b",{staticClass:"auth-logo-text"},[a._v(a._s(a.config.app_name))]),a._v(" "),e("h1",[a._v(a._s(a.$t("page_login.title")))]),a._v(" "),e("h2",[a._v(a._s(a.$t("page_login.subtitle")))]),a._v(" "),e("ValidationObserver",{ref:"log_in",staticClass:"form inline-form",attrs:{tag:"form"},on:{submit:function(t){return t.preventDefault(),a.logIn(t)}},scopedSlots:a._u([{key:"default",fn:function(t){t.invalid;return[e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"E-Mail",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var o=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.loginEmail,expression:"loginEmail"}],class:{"is-error":o[0]},attrs:{placeholder:a.$t("page_login.placeholder_email"),type:"email"},domProps:{value:a.loginEmail},on:{input:function(t){t.target.composing||(a.loginEmail=t.target.value)}}}),a._v(" "),o[0]?e("span",{staticClass:"error-message"},[a._v(a._s(o[0]))]):a._e()]}}],null,!0)}),a._v(" "),e("AuthButton",{attrs:{icon:"chevron-right",text:a.$t("page_login.button_next"),loading:a.isLoading,disabled:a.isLoading}})]}}])}),a._v(" "),a.config.userRegistration?e("span",{staticClass:"additional-link"},[a._v(a._s(a.$t("page_login.registration_text"))+"\n            "),e("router-link",{attrs:{to:{name:"SignUp"}}},[a._v("\n                "+a._s(a.$t("page_login.registration_button"))+"\n            ")])],1):a._e()],1),a._v(" "),e("AuthContent",{attrs:{name:"sign-in",visible:!1}},[a.checkedAccount?e("div",{staticClass:"user"},[e("img",{staticClass:"user-avatar",attrs:{src:a.checkedAccount.avatar,alt:a.checkedAccount.name}}),a._v(" "),e("h1",[a._v(a._s(a.$t("page_sign_in.title",{name:a.checkedAccount.name})))]),a._v(" "),e("h2",[a._v(a._s(a.$t("page_sign_in.subtitle")))])]):a._e(),a._v(" "),e("ValidationObserver",{ref:"sign_in",staticClass:"form inline-form",attrs:{tag:"form"},on:{submit:function(t){return t.preventDefault(),a.singIn(t)}},scopedSlots:a._u([{key:"default",fn:function(t){t.invalid;return[e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"User Password",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var o=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.loginPassword,expression:"loginPassword"}],class:{"is-error":o[0]},attrs:{placeholder:a.$t("page_sign_in.placeholder_password"),type:"password"},domProps:{value:a.loginPassword},on:{input:function(t){t.target.composing||(a.loginPassword=t.target.value)}}}),a._v(" "),o[0]?e("span",{staticClass:"error-message"},[a._v(a._s(o[0]))]):a._e()]}}],null,!0)}),a._v(" "),e("AuthButton",{attrs:{icon:"chevron-right",text:a.$t("page_sign_in.button_log_in"),loading:a.isLoading,disabled:a.isLoading}})]}}])}),a._v(" "),e("span",{staticClass:"additional-link"},[a._v(a._s(a.$t("page_sign_in.password_reset_text"))+"\n            "),e("router-link",{attrs:{to:{name:"ForgottenPassword"}}},[a._v("\n                "+a._s(a.$t("page_sign_in.password_reset_button"))+"\n            ")])],1)],1)],1)}),[],!1,null,"80670a5a",null);t.default=_.exports},lh0Q:function(a,t,e){var o=e("Jx8r");"string"==typeof o&&(o=[[a.i,o,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(o,i);o.locals&&(a.exports=o.locals)},o8cq:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".form.inline-form[data-v-80670a5a]{display:flex;position:relative;justify-content:center;margin:0 auto}.form.inline-form .input-wrapper[data-v-80670a5a]{position:relative}.form.inline-form .input-wrapper .error-message[data-v-80670a5a]{position:absolute;left:0}.form.block-form.create-new-password .block-wrapper label[data-v-80670a5a]{width:280px}.form.block-form .block-wrapper[data-v-80670a5a]{display:flex;align-items:center;margin-top:25px;justify-content:center}.form.block-form .block-wrapper[data-v-80670a5a]:first-child{margin-top:0}.form.block-form .block-wrapper label[data-v-80670a5a]{white-space:nowrap;font-size:1.125em;font-weight:700;padding-right:20px;width:200px;text-align:right!important;color:#1b2539;text-align:right}.form.block-form .button[data-v-80670a5a]{margin-top:50px}.input-wrapper .error-message[data-v-80670a5a]{font-size:.875em;color:#fd397a;padding-top:5px;display:block;text-align:left}textarea[data-v-80670a5a]{width:100%}input[type=email][data-v-80670a5a],input[type=password][data-v-80670a5a],input[type=text][data-v-80670a5a],textarea[data-v-80670a5a]{background:#f4f5f6;border:1px solid transparent;transition:all .15s ease;font-size:1em;border-radius:8px;padding:13px 20px;-webkit-appearance:none;-moz-appearance:none;appearance:none;font-weight:700;outline:0;width:100%}input[type=email].is-error[data-v-80670a5a],input[type=password].is-error[data-v-80670a5a],input[type=text].is-error[data-v-80670a5a],textarea.is-error[data-v-80670a5a]{border-color:#fd397a;box-shadow:0 0 7px rgba(253,57,122,.3)}input[type=email][data-v-80670a5a]::-moz-placeholder,input[type=password][data-v-80670a5a]::-moz-placeholder,input[type=text][data-v-80670a5a]::-moz-placeholder,textarea[data-v-80670a5a]::-moz-placeholder{color:#a4adb6;font-size:1em}input[type=email][data-v-80670a5a]:-ms-input-placeholder,input[type=password][data-v-80670a5a]:-ms-input-placeholder,input[type=text][data-v-80670a5a]:-ms-input-placeholder,textarea[data-v-80670a5a]:-ms-input-placeholder{color:#a4adb6;font-size:1em}input[type=email][data-v-80670a5a]::placeholder,input[type=password][data-v-80670a5a]::placeholder,input[type=text][data-v-80670a5a]::placeholder,textarea[data-v-80670a5a]::placeholder{color:#a4adb6;font-size:1em}input[type=email][data-v-80670a5a]:focus,input[type=password][data-v-80670a5a]:focus,input[type=text][data-v-80670a5a]:focus,textarea[data-v-80670a5a]:focus{border-color:#00bc7e;box-shadow:0 0 7px rgba(0,188,126,.3)}input[type=email][disabled][data-v-80670a5a],input[type=password][disabled][data-v-80670a5a],input[type=text][disabled][data-v-80670a5a],textarea[disabled][data-v-80670a5a]{color:#a4adb6;cursor:not-allowed}.additional-link[data-v-80670a5a]{font-size:1em;margin-top:50px;display:block;color:#1b2539}.additional-link a[data-v-80670a5a],.additional-link b[data-v-80670a5a]{color:#00bc7e;cursor:pointer}.additional-link a[data-v-80670a5a]:hover,.additional-link b[data-v-80670a5a]:hover{text-decoration:underline}@media only screen and (max-width:960px){.form .button[data-v-80670a5a]{margin-top:20px;width:100%;margin-left:0;margin-right:0}.form input[data-v-80670a5a],.form textarea[data-v-80670a5a]{width:100%;min-width:100%}.form.block-form .block-wrapper[data-v-80670a5a]{display:block}.form.block-form .block-wrapper label[data-v-80670a5a]{width:100%;padding-right:0;display:block;margin-bottom:7px;text-align:left!important;font-size:.875em;padding-top:0}.form.block-form .button[data-v-80670a5a]{margin-top:25px;margin-left:0;margin-right:0}.form.inline-form[data-v-80670a5a]{display:block}.form.inline-form .input-wrapper .error-message[data-v-80670a5a]{position:relative;bottom:0}.form .button[data-v-80670a5a]{padding:14px 32px}input[type=email][data-v-80670a5a],input[type=password][data-v-80670a5a],input[type=text][data-v-80670a5a],textarea[data-v-80670a5a]{padding:14px 20px}}@media (prefers-color-scheme:dark){.form.block-form .block-wrapper label[data-v-80670a5a]{color:#bec6cf}input[type=email][data-v-80670a5a],input[type=password][data-v-80670a5a],input[type=text][data-v-80670a5a],textarea[data-v-80670a5a]{background:#1e2024;color:#bec6cf}input[type=email][data-v-80670a5a]::-moz-placeholder,input[type=password][data-v-80670a5a]::-moz-placeholder,input[type=text][data-v-80670a5a]::-moz-placeholder,textarea[data-v-80670a5a]::-moz-placeholder{color:#7d858c}input[type=email][data-v-80670a5a]:-ms-input-placeholder,input[type=password][data-v-80670a5a]:-ms-input-placeholder,input[type=text][data-v-80670a5a]:-ms-input-placeholder,textarea[data-v-80670a5a]:-ms-input-placeholder{color:#7d858c}input[type=email][data-v-80670a5a]::placeholder,input[type=password][data-v-80670a5a]::placeholder,input[type=text][data-v-80670a5a]::placeholder,textarea[data-v-80670a5a]::placeholder{color:#7d858c}input[type=email][disabled][data-v-80670a5a],input[type=password][disabled][data-v-80670a5a],input[type=text][disabled][data-v-80670a5a],textarea[disabled][data-v-80670a5a]{color:#7d858c}}.auth-logo-text[data-v-80670a5a]{font-size:1.375em;font-weight:800;margin-bottom:40px;display:block}.auth-form[data-v-80670a5a]{text-align:center;max-width:600px;padding:25px 20px;display:table-cell;vertical-align:middle}.auth-form input[data-v-80670a5a]{min-width:310px}.auth-form .additional-link a[data-v-80670a5a]{font-weight:700;text-decoration:none}.auth-form .user-avatar[data-v-80670a5a]{width:100px;height:100px;-o-object-fit:cover;object-fit:cover;margin-bottom:20px;border-radius:8px;box-shadow:0 10px 30px rgba(25,54,60,.2)}.auth-form .logo[data-v-80670a5a]{width:120px;margin-bottom:20px}.auth-form h1[data-v-80670a5a]{font-size:2.125em;font-weight:800;line-height:1.2;margin-bottom:2px;color:#1b2539}.auth-form h2[data-v-80670a5a]{font-size:1.4375em;font-weight:500;margin-bottom:50px;color:#1b2539}.auth-form .block-form[data-v-80670a5a]{margin-left:auto;margin-right:auto}@media only screen and (min-width:690px) and (max-width:960px){.auth-form[data-v-80670a5a]{padding-left:20%;padding-right:20%}}@media only screen and (max-width:690px){.auth-form[data-v-80670a5a]{width:100%}.auth-form h1[data-v-80670a5a]{font-size:1.875em}.auth-form h2[data-v-80670a5a]{font-size:1.3125em}}@media only screen and (max-width:490px){.auth-form h1[data-v-80670a5a]{font-size:1.375em}.auth-form h2[data-v-80670a5a]{font-size:1.125em}.auth-form input[data-v-80670a5a]{min-width:0}.auth-form .additional-link[data-v-80670a5a]{font-size:.9375em}}@media (prefers-color-scheme:dark){.auth-form .additional-link[data-v-80670a5a],.auth-form h1[data-v-80670a5a],.auth-form h2[data-v-80670a5a]{color:#bec6cf}}",""])},"s/ZW":function(a,t,e){"use strict";var o=e("NbAf");e.n(o).a},wVBQ:function(a,t,e){"use strict";var o=e("ykt2");e.n(o).a},ykt2:function(a,t,e){var o=e("o8cq");"string"==typeof o&&(o=[[a.i,o,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(o,i);o.locals&&(a.exports=o.locals)}}]);