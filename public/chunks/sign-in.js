(window.webpackJsonp=window.webpackJsonp||[]).push([[74],{"0QiB":function(t,e,a){"use strict";a("Y7pZ")},ASoH:function(t,e,a){"use strict";var i={name:"AuthContent",props:["loading","icon","text"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},o=(a("RLEU"),a("KHd+")),n=Object(o.a)(i,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("button",{staticClass:"button outline hover-text-theme hover-border-theme"},[a("span",{staticClass:"text-label"},[t._v(t._s(t.text))]),t._v(" "),t.loading?a("span",{staticClass:"icon"},[a("FontAwesomeIcon",{staticClass:"sync-alt svg-color-theme",attrs:{icon:"sync-alt"}})],1):t._e(),t._v(" "),!t.loading&&t.icon?a("span",{staticClass:"icon"},[a("FontAwesomeIcon",{staticClass:"svg-color-theme",attrs:{icon:t.icon}})],1):t._e()])}),[],!1,null,"16e9ad58",null);e.a=n.exports},JHC5:function(t,e,a){var i=a("YUi1");"string"==typeof i&&(i=[[t.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,o);i.locals&&(t.exports=i.locals)},Jx8r:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,"#auth[data-v-31af8372]{height:100%;width:100%;display:table}",""])},RLEU:function(t,e,a){"use strict";a("JHC5")},TJPC:function(t,e,a){"use strict";a.d(e,"a",(function(){return n}));function i(t){return null==t}function o(t){return Array.isArray(t)&&0===t.length}var n={validate:function(t,e){var a=(void 0===e?{allowFalse:!0}:e).allowFalse,n={valid:!1,required:!0};return i(t)||o(t)?n:!1!==t||a?(n.valid=!!String(t).trim().length,n):n},params:[{name:"allowFalse",default:!0}],computesRequired:!0}},Y7pZ:function(t,e,a){var i=a("ii4L");"string"==typeof i&&(i=[[t.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,o);i.locals&&(t.exports=i.locals)},YUi1:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".button[data-v-16e9ad58]{cursor:pointer;border-radius:8px;text-decoration:none;padding:12px 32px;display:inline-block;margin-left:15px;margin-right:15px;white-space:nowrap;transition:all .15s ease;background:transparent}.button .text-label[data-v-16e9ad58]{transition:all .15s ease;font-size:1.0625em;font-weight:800;line-height:0}.button .icon[data-v-16e9ad58]{margin-left:12px;font-size:1em}.button.solid[data-v-16e9ad58]{background:#00bc7e;border:2px solid #00bc7e}.button.solid .text-label[data-v-16e9ad58]{color:#fff}.button.outline[data-v-16e9ad58]{border:2px solid #1b2539}.button.outline .text-label[data-v-16e9ad58]{color:#1b2539}.button.outline .icon path[data-v-16e9ad58]{fill:inherit}.button.outline[data-v-16e9ad58]:hover{border-color:inherit}.button.outline:hover .text-label[data-v-16e9ad58]{color:inherit}@media (prefers-color-scheme:dark){.button.outline[data-v-16e9ad58]{background:#141414;border-color:#bec6cf}.button.outline .text-label[data-v-16e9ad58]{color:#bec6cf}}.sync-alt[data-v-16e9ad58]{-webkit-animation:spin-data-v-16e9ad58 1s linear infinite;animation:spin-data-v-16e9ad58 1s linear infinite}@-webkit-keyframes spin-data-v-16e9ad58{0%{transform:rotate(0)}to{transform:rotate(1turn)}}@keyframes spin-data-v-16e9ad58{0%{transform:rotate(0)}to{transform:rotate(1turn)}}",""])},bDRN:function(t,e,a){"use strict";var i={name:"AuthContentWrapper"},o=(a("iYAH"),a("KHd+")),n=Object(o.a)(i,(function(){var t=this.$createElement;return(this._self._c||t)("div",{attrs:{id:"auth"}},[this._t("default")],2)}),[],!1,null,"31af8372",null);e.a=n.exports},iYAH:function(t,e,a){"use strict";a("lh0Q")},ii4L:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,".form.inline-form[data-v-26e990fb]{display:flex;position:relative;justify-content:center;margin:0 auto}.form.inline-form .input-wrapper[data-v-26e990fb]{position:relative}.form.inline-form .input-wrapper .error-message[data-v-26e990fb]{position:absolute;left:0}.form.block-form.create-new-password .block-wrapper label[data-v-26e990fb]{width:280px}.form.block-form .block-wrapper[data-v-26e990fb]{display:flex;align-items:center;margin-top:25px;justify-content:center}.form.block-form .block-wrapper[data-v-26e990fb]:first-child{margin-top:0}.form.block-form .block-wrapper label[data-v-26e990fb]{white-space:nowrap;font-size:1.125em;font-weight:700;padding-right:20px;width:200px;text-align:right!important;color:#1b2539;text-align:right}.form.block-form .button[data-v-26e990fb]{margin-top:50px}.input-wrapper .error-message[data-v-26e990fb]{font-size:.875em;color:#fd397a;padding-top:5px;display:block;text-align:left}textarea[data-v-26e990fb]{width:100%}input[type=email][data-v-26e990fb],input[type=password][data-v-26e990fb],input[type=text][data-v-26e990fb],textarea[data-v-26e990fb]{background:#f4f5f6;border:1px solid transparent;transition:all .15s ease;font-size:1em;border-radius:8px;padding:13px 20px;-webkit-appearance:none;-moz-appearance:none;appearance:none;font-weight:700;outline:0;width:100%}input[type=email].is-error[data-v-26e990fb],input[type=password].is-error[data-v-26e990fb],input[type=text].is-error[data-v-26e990fb],textarea.is-error[data-v-26e990fb]{border-color:#fd397a}input[type=email][data-v-26e990fb]::-moz-placeholder,input[type=password][data-v-26e990fb]::-moz-placeholder,input[type=text][data-v-26e990fb]::-moz-placeholder,textarea[data-v-26e990fb]::-moz-placeholder{color:#a4adb6;font-size:1em}input[type=email][data-v-26e990fb]:-ms-input-placeholder,input[type=password][data-v-26e990fb]:-ms-input-placeholder,input[type=text][data-v-26e990fb]:-ms-input-placeholder,textarea[data-v-26e990fb]:-ms-input-placeholder{color:#a4adb6;font-size:1em}input[type=email][data-v-26e990fb]::placeholder,input[type=password][data-v-26e990fb]::placeholder,input[type=text][data-v-26e990fb]::placeholder,textarea[data-v-26e990fb]::placeholder{color:#a4adb6;font-size:1em}input[type=email][disabled][data-v-26e990fb],input[type=password][disabled][data-v-26e990fb],input[type=text][disabled][data-v-26e990fb],textarea[disabled][data-v-26e990fb]{color:#a4adb6;cursor:not-allowed}.additional-link[data-v-26e990fb]{font-size:1em;margin-top:50px;display:block}.additional-link a[data-v-26e990fb],.additional-link b[data-v-26e990fb]{cursor:pointer}.additional-link a[data-v-26e990fb]:hover,.additional-link b[data-v-26e990fb]:hover{text-decoration:underline}@media only screen and (max-width:960px){.form .button[data-v-26e990fb]{margin-top:20px;width:100%;margin-left:0;margin-right:0}.form input[data-v-26e990fb],.form textarea[data-v-26e990fb]{width:100%;min-width:100%}.form.block-form .block-wrapper[data-v-26e990fb]{display:block}.form.block-form .block-wrapper label[data-v-26e990fb]{width:100%;padding-right:0;display:block;margin-bottom:7px;text-align:left!important;font-size:.875em;padding-top:0}.form.block-form .button[data-v-26e990fb]{margin-top:25px;margin-left:0;margin-right:0}.form.inline-form[data-v-26e990fb]{display:block}.form.inline-form .input-wrapper .error-message[data-v-26e990fb]{position:relative;bottom:0}.form .button[data-v-26e990fb]{padding:14px 32px}input[type=email][data-v-26e990fb],input[type=password][data-v-26e990fb],input[type=text][data-v-26e990fb],textarea[data-v-26e990fb]{padding:14px 20px}}@media (prefers-color-scheme:dark){.form.block-form .block-wrapper label[data-v-26e990fb]{color:#bec6cf}input[type=email][data-v-26e990fb],input[type=password][data-v-26e990fb],input[type=text][data-v-26e990fb],textarea[data-v-26e990fb]{background:#1e2024;color:#bec6cf}input[type=email][data-v-26e990fb]::-moz-placeholder,input[type=password][data-v-26e990fb]::-moz-placeholder,input[type=text][data-v-26e990fb]::-moz-placeholder,textarea[data-v-26e990fb]::-moz-placeholder{color:#7d858c}input[type=email][data-v-26e990fb]:-ms-input-placeholder,input[type=password][data-v-26e990fb]:-ms-input-placeholder,input[type=text][data-v-26e990fb]:-ms-input-placeholder,textarea[data-v-26e990fb]:-ms-input-placeholder{color:#7d858c}input[type=email][data-v-26e990fb]::placeholder,input[type=password][data-v-26e990fb]::placeholder,input[type=text][data-v-26e990fb]::placeholder,textarea[data-v-26e990fb]::placeholder{color:#7d858c}input[type=email][disabled][data-v-26e990fb],input[type=password][disabled][data-v-26e990fb],input[type=text][disabled][data-v-26e990fb],textarea[disabled][data-v-26e990fb]{color:#7d858c}}.auth-logo-text[data-v-26e990fb]{font-size:1.375em;font-weight:800;margin-bottom:40px;display:block}.auth-form[data-v-26e990fb]{text-align:center;max-width:600px;padding:25px 20px;display:table-cell;vertical-align:middle}.auth-form input[data-v-26e990fb]{min-width:310px}.auth-form .additional-link a[data-v-26e990fb]{font-weight:700;text-decoration:none}.auth-form .user-avatar[data-v-26e990fb]{width:100px;height:100px;-o-object-fit:cover;object-fit:cover;margin-bottom:20px;border-radius:8px;box-shadow:0 10px 30px rgba(25,54,60,.2)}.auth-form .logo[data-v-26e990fb]{width:120px;margin-bottom:20px}.auth-form h1[data-v-26e990fb]{font-size:2.125em;font-weight:800;line-height:1.2;margin-bottom:2px;color:#1b2539}.auth-form h2[data-v-26e990fb]{font-size:1.4375em;font-weight:500;margin-bottom:50px;color:#1b2539}.auth-form .block-form[data-v-26e990fb]{margin-left:auto;margin-right:auto}@media only screen and (min-width:690px) and (max-width:960px){.auth-form[data-v-26e990fb]{padding-left:20%;padding-right:20%}}@media only screen and (max-width:690px){.auth-form[data-v-26e990fb]{width:100%}.auth-form h1[data-v-26e990fb]{font-size:1.875em}.auth-form h2[data-v-26e990fb]{font-size:1.3125em}}@media only screen and (max-width:490px){.auth-form h1[data-v-26e990fb]{font-size:1.375em}.auth-form h2[data-v-26e990fb]{font-size:1.125em}.auth-form input[data-v-26e990fb]{min-width:0}.auth-form .additional-link[data-v-26e990fb]{font-size:.9375em}}@media (prefers-color-scheme:dark){.auth-form .additional-link[data-v-26e990fb],.auth-form h1[data-v-26e990fb],.auth-form h2[data-v-26e990fb]{color:#bec6cf}}",""])},j8qy:function(t,e,a){"use strict";var i={name:"AuthContent",props:["visible","name"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},o=a("KHd+"),n=Object(o.a)(i,(function(){var t=this.$createElement,e=this._self._c||t;return this.isVisible?e("div",{staticClass:"auth-form"},[this._t("default")],2):this._e()}),[],!1,null,null,null);e.a=n.exports},"l9/H":function(t,e,a){"use strict";a.r(e);var i=a("o0o1"),o=a.n(i),n=a("bDRN"),r=a("A5+z"),s=a("j8qy"),l=a("ASoH"),d=a("TJPC"),p=a("L2JU"),c=a("xCqy"),f=a("vDqi"),u=a.n(f);function b(t,e,a,i,o,n,r){try{var s=t[n](r),l=s.value}catch(t){return void a(t)}s.done?e(l):Promise.resolve(l).then(i,o)}function m(t){return function(){var e=this,a=arguments;return new Promise((function(i,o){var n=t.apply(e,a);function r(t){b(n,i,o,r,s,"next",t)}function s(t){b(n,i,o,r,s,"throw",t)}r(void 0)}))}}function v(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(t);e&&(i=i.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,i)}return a}function h(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var g={name:"SignIn",components:{AuthContentWrapper:n.a,ValidationProvider:r.ValidationProvider,ValidationObserver:r.ValidationObserver,AuthContent:s.a,AuthButton:l.a,required:d.a},computed:function(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?v(Object(a),!0).forEach((function(e){h(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):v(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}({},Object(p.b)(["config"])),data:function(){return{isLoading:!1,checkedAccount:void 0,loginPassword:"",loginEmail:""}},methods:{goToAuthPage:function(t){this.$refs.auth.$children.forEach((function(e){e.isVisible=!1,e.$props.name===t&&(e.isVisible=!0)}))},logIn:function(){var t=this;return m(o.a.mark((function e(){return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.$refs.log_in.validate();case 2:if(e.sent){e.next=5;break}return e.abrupt("return");case 5:t.isLoading=!0,u.a.post("/api/user/check",{email:t.loginEmail}).then((function(e){t.isLoading=!1,t.checkedAccount=e.data,t.goToAuthPage("sign-in")})).catch((function(e){404==e.response.status&&t.$refs.log_in.setErrors({"E-Mail":[e.response.data]}),500==e.response.status&&c.a.$emit("alert:open",{emoji:"🤔",title:t.$t("popup_signup_error.title"),message:t.$t("popup_signup_error.message")}),t.isLoading=!1}));case 7:case"end":return e.stop()}}),e)})))()},singIn:function(){var t=this;return m(o.a.mark((function e(){return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,t.$refs.sign_in.validate();case 2:if(e.sent){e.next=5;break}return e.abrupt("return");case 5:t.isLoading=!0,u.a.post("/login",{email:t.loginEmail,password:t.loginPassword}).then((function(){t.isLoading=!1,t.$store.commit("SET_AUTHORIZED",!0),t.$router.push({name:"Files"})})).catch((function(e){422==e.response.status&&t.$refs.sign_in.setErrors({"User Password":[t.$t("validation_errors.incorrect_password")]}),t.isLoading=!1}));case 7:case"end":return e.stop()}}),e)})))()}},created:function(){this.$scrollTop(),this.$store.commit("PROCESSING_POPUP",void 0),this.config.isDev&&(this.loginEmail="howdy@hi5ve.digital",this.loginPassword="vuefilemanager")}},x=(a("0QiB"),a("KHd+")),_=Object(x.a)(g,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("AuthContentWrapper",{ref:"auth"},[a("AuthContent",{attrs:{name:"log-in",visible:!0}},[t.config.app_logo?a("img",{staticClass:"logo",attrs:{src:t.$getImage(t.config.app_logo),alt:t.config.app_name}}):t._e(),t._v(" "),t.config.app_logo?t._e():a("b",{staticClass:"auth-logo-text"},[t._v(t._s(t.config.app_name))]),t._v(" "),a("h1",[t._v(t._s(t.$t("page_login.title")))]),t._v(" "),a("h2",[t._v(t._s(t.$t("page_login.subtitle"))+":")]),t._v(" "),a("ValidationObserver",{ref:"log_in",staticClass:"form inline-form",attrs:{tag:"form"},on:{submit:function(e){return e.preventDefault(),t.logIn(e)}},scopedSlots:t._u([{key:"default",fn:function(e){e.invalid;return[a("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"E-Mail",rules:"required"},scopedSlots:t._u([{key:"default",fn:function(e){var i=e.errors;return[a("input",{directives:[{name:"model",rawName:"v-model",value:t.loginEmail,expression:"loginEmail"}],staticClass:"focus-border-theme",class:{"is-error":i[0]},attrs:{placeholder:t.$t("page_login.placeholder_email"),type:"email"},domProps:{value:t.loginEmail},on:{input:function(e){e.target.composing||(t.loginEmail=e.target.value)}}}),t._v(" "),i[0]?a("span",{staticClass:"error-message"},[t._v(t._s(i[0]))]):t._e()]}}],null,!0)}),t._v(" "),a("AuthButton",{attrs:{icon:"chevron-right",text:t.$t("page_login.button_next"),loading:t.isLoading,disabled:t.isLoading}})]}}])}),t._v(" "),t.config.userRegistration?a("span",{staticClass:"additional-link"},[t._v("\n            "+t._s(t.$t("page_login.registration_text"))+"\n            "),a("router-link",{staticClass:"text-theme",attrs:{to:{name:"SignUp"}}},[t._v("\n                "+t._s(t.$t("page_login.registration_button"))+"\n            ")])],1):t._e()],1),t._v(" "),a("AuthContent",{attrs:{name:"sign-in",visible:!1}},[t.checkedAccount?a("div",{staticClass:"user"},[a("img",{staticClass:"user-avatar",attrs:{src:t.checkedAccount.avatar,alt:t.checkedAccount.name}}),t._v(" "),a("h1",[t._v(t._s(t.$t("page_sign_in.title",{name:t.checkedAccount.name})))]),t._v(" "),a("h2",[t._v(t._s(t.$t("page_sign_in.subtitle"))+":")])]):t._e(),t._v(" "),a("ValidationObserver",{ref:"sign_in",staticClass:"form inline-form",attrs:{tag:"form"},on:{submit:function(e){return e.preventDefault(),t.singIn(e)}},scopedSlots:t._u([{key:"default",fn:function(e){e.invalid;return[a("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"User Password",rules:"required"},scopedSlots:t._u([{key:"default",fn:function(e){var i=e.errors;return[a("input",{directives:[{name:"model",rawName:"v-model",value:t.loginPassword,expression:"loginPassword"}],staticClass:"focus-border-theme",class:{"is-error":i[0]},attrs:{placeholder:t.$t("page_sign_in.placeholder_password"),type:"password"},domProps:{value:t.loginPassword},on:{input:function(e){e.target.composing||(t.loginPassword=e.target.value)}}}),t._v(" "),i[0]?a("span",{staticClass:"error-message"},[t._v(t._s(i[0]))]):t._e()]}}],null,!0)}),t._v(" "),a("AuthButton",{attrs:{icon:"chevron-right",text:t.$t("page_sign_in.button_log_in"),loading:t.isLoading,disabled:t.isLoading}})]}}])}),t._v(" "),a("span",{staticClass:"additional-link"},[t._v(t._s(t.$t("page_sign_in.password_reset_text"))+"\n            "),a("router-link",{staticClass:"text-theme",attrs:{to:{name:"ForgottenPassword"}}},[t._v("\n                "+t._s(t.$t("page_sign_in.password_reset_button"))+"\n            ")])],1)],1)],1)}),[],!1,null,"26e990fb",null);e.default=_.exports},lh0Q:function(t,e,a){var i=a("Jx8r");"string"==typeof i&&(i=[[t.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(i,o);i.locals&&(t.exports=i.locals)}}]);