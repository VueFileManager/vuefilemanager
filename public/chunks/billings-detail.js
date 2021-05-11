(window.webpackJsonp=window.webpackJsonp||[]).push([[21],{"4TWA":function(n,a,e){"use strict";var t=e("CjXH"),i=e("LvDl"),o={name:"SelectInput",props:["placeholder","options","isError","default"],components:{Edit2Icon:t.t,UserIcon:t.kb,ChevronDownIcon:t.g},watch:{query:Object(i.debounce)((function(n){this.searchedResults=Object(i.omitBy)(this.options,(function(a){return!a.label.toLowerCase().includes(n.toLowerCase())}))}),200)},computed:{isSearching:function(){return this.searchedResults&&""!==this.query},optionList:function(){return this.isSearching?this.searchedResults:this.options}},data:function(){return{searchedResults:void 0,selected:void 0,isOpen:!1,query:""}},methods:{selectOption:function(n){this.$emit("input",n.value),this.selected=n,this.isOpen=!1},openMenu:function(){var n=this;this.isOpen=!this.isOpen,this.isOpen&&this.$nextTick((function(){return n.$refs.search.focus()}))}},created:function(){var n=this;this.default&&(this.selected=this.options.find((function(a){return a.value===n.default})))}},r=(e("MRmA"),e("KHd+")),l=Object(r.a)(o,(function(){var n=this,a=n.$createElement,e=n._self._c||a;return e("div",{staticClass:"select"},[e("div",{staticClass:"input-area",class:{"is-active":n.isOpen,"is-error":n.isError},on:{click:n.openMenu}},[n.selected?e("div",{staticClass:"selected"},[n.selected.icon?e("div",{staticClass:"option-icon"},["user"===n.selected.icon?e("user-icon",{attrs:{size:"14"}}):n._e(),n._v(" "),"user-edit"===n.selected.icon?e("edit2-icon",{attrs:{size:"14"}}):n._e()],1):n._e(),n._v(" "),e("span",{staticClass:"option-value"},[n._v(n._s(n.selected.label))])]):n._e(),n._v(" "),n.selected?n._e():e("div",{staticClass:"not-selected"},[e("span",{staticClass:"option-value placehoder"},[n._v(n._s(n.placeholder))])]),n._v(" "),e("chevron-down-icon",{staticClass:"chevron",attrs:{size:"19"}})],1),n._v(" "),e("transition",{attrs:{name:"slide-in"}},[n.isOpen?e("div",{staticClass:"input-options"},[n.options.length>5?e("div",{staticClass:"select-search"},[e("input",{directives:[{name:"model",rawName:"v-model",value:n.query,expression:"query"}],ref:"search",staticClass:"search-input focus-border-theme",attrs:{type:"text",placeholder:n.$t("select_search_placeholder")},domProps:{value:n.query},on:{input:function(a){a.target.composing||(n.query=a.target.value)}}})]):n._e(),n._v(" "),e("ul",{staticClass:"option-list"},n._l(n.optionList,(function(a,t){return e("li",{key:t,staticClass:"option-item",on:{click:function(e){return n.selectOption(a)}}},[a.icon?e("div",{staticClass:"option-icon"},["user"===a.icon?e("user-icon",{attrs:{size:"14"}}):n._e(),n._v(" "),"user-edit"===a.icon?e("edit2-icon",{attrs:{size:"14"}}):n._e()],1):n._e(),n._v(" "),e("span",{staticClass:"option-value"},[n._v(n._s(a.label))])])})),0)]):n._e()])],1)}),[],!1,null,"6c344ee4",null);a.a=l.exports},"5b+A":function(n,a,e){"use strict";var t=e("vRyx");e.n(t).a},"8aG9":function(n,a,e){"use strict";var t=e("W0ou");e.n(t).a},"9kSd":function(n,a,e){(n.exports=e("I1BE")(!1)).push([n.i,'.form[data-v-7eaabd11] {\n  max-width: 700px;\n}\n.form.inline-form[data-v-7eaabd11] {\n  display: flex;\n  position: relative;\n  justify-content: center;\n  margin: 0 auto;\n}\n.form.inline-form .input-wrapper[data-v-7eaabd11] {\n  position: relative;\n}\n.form.inline-form .input-wrapper .error-message[data-v-7eaabd11] {\n  position: absolute;\n  left: 0;\n  bottom: -25px;\n}\n.form.block-form .wrapper-inline[data-v-7eaabd11] {\n  display: flex;\n  margin: 0 -15px 0;\n}\n.form.block-form .wrapper-inline .block-wrapper[data-v-7eaabd11] {\n  width: 100%;\n  padding: 0 15px;\n}\n.form.block-form .block-wrapper[data-v-7eaabd11] {\n  margin-bottom: 22px;\n}\n.form.block-form .block-wrapper label[data-v-7eaabd11] {\n  font-size: 0.875em;\n  color: rgba(27, 37, 57, 0.8);\n  font-weight: 700;\n  display: block;\n  margin-bottom: 7px;\n  text-align: left;\n}\n.form.block-form .block-wrapper[data-v-7eaabd11]:last-child {\n  margin-bottom: 0;\n}\n.form.block-form .button[data-v-7eaabd11] {\n  margin-top: 50px;\n}\n.form .inline-wrapper[data-v-7eaabd11] {\n  display: flex;\n  align-items: center;\n  justify-content: space-between;\n}\n.form .inline-wrapper .switch-label .input-help[data-v-7eaabd11] {\n  padding-top: 0;\n}\n.form .inline-wrapper .switch-label .input-label[data-v-7eaabd11] {\n  font-weight: 700;\n  color: #1B2539;\n  font-size: 1em;\n  margin-bottom: 5px;\n}\n.form .input-help[data-v-7eaabd11] {\n  font-size: 0.75em;\n  color: rgba(27, 37, 57, 0.7);\n  line-height: 1.35;\n  padding-top: 10px;\n  display: block;\n}\n.single-line-form[data-v-7eaabd11] {\n  display: flex;\n}\n.single-line-form .submit-button[data-v-7eaabd11] {\n  margin-left: 20px;\n}\n.error-message[data-v-7eaabd11] {\n  font-size: 0.875em;\n  color: #fd397a;\n  padding-top: 5px;\n  display: block;\n  text-align: left;\n}\ntextarea[data-v-7eaabd11] {\n  width: 100%;\n}\ntextarea[data-v-7eaabd11],\ninput[type="password"][data-v-7eaabd11],\ninput[type="text"][data-v-7eaabd11],\ninput[type="number"][data-v-7eaabd11],\ninput[type="date"][data-v-7eaabd11],\ninput[type="email"][data-v-7eaabd11] {\n  border: 1px solid transparent;\n  transition: 150ms all ease;\n  font-size: 1em;\n  border-radius: 8px;\n  padding: 13px 20px;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  font-weight: 700;\n  outline: 0;\n  width: 100%;\n  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);\n  background: white;\n}\ntextarea.is-error[data-v-7eaabd11],\ninput[type="password"].is-error[data-v-7eaabd11],\ninput[type="text"].is-error[data-v-7eaabd11],\ninput[type="number"].is-error[data-v-7eaabd11],\ninput[type="date"].is-error[data-v-7eaabd11],\ninput[type="email"].is-error[data-v-7eaabd11] {\n  border-color: #fd397a;\n}\ntextarea[data-v-7eaabd11]::-webkit-input-placeholder, input[type="password"][data-v-7eaabd11]::-webkit-input-placeholder, input[type="text"][data-v-7eaabd11]::-webkit-input-placeholder, input[type="number"][data-v-7eaabd11]::-webkit-input-placeholder, input[type="date"][data-v-7eaabd11]::-webkit-input-placeholder, input[type="email"][data-v-7eaabd11]::-webkit-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-7eaabd11]::-moz-placeholder, input[type="password"][data-v-7eaabd11]::-moz-placeholder, input[type="text"][data-v-7eaabd11]::-moz-placeholder, input[type="number"][data-v-7eaabd11]::-moz-placeholder, input[type="date"][data-v-7eaabd11]::-moz-placeholder, input[type="email"][data-v-7eaabd11]::-moz-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-7eaabd11]:-ms-input-placeholder, input[type="password"][data-v-7eaabd11]:-ms-input-placeholder, input[type="text"][data-v-7eaabd11]:-ms-input-placeholder, input[type="number"][data-v-7eaabd11]:-ms-input-placeholder, input[type="date"][data-v-7eaabd11]:-ms-input-placeholder, input[type="email"][data-v-7eaabd11]:-ms-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-7eaabd11]::-ms-input-placeholder, input[type="password"][data-v-7eaabd11]::-ms-input-placeholder, input[type="text"][data-v-7eaabd11]::-ms-input-placeholder, input[type="number"][data-v-7eaabd11]::-ms-input-placeholder, input[type="date"][data-v-7eaabd11]::-ms-input-placeholder, input[type="email"][data-v-7eaabd11]::-ms-input-placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[data-v-7eaabd11]::placeholder,\ninput[type="password"][data-v-7eaabd11]::placeholder,\ninput[type="text"][data-v-7eaabd11]::placeholder,\ninput[type="number"][data-v-7eaabd11]::placeholder,\ninput[type="date"][data-v-7eaabd11]::placeholder,\ninput[type="email"][data-v-7eaabd11]::placeholder {\n  color: rgba(27, 37, 57, 0.5);\n  font-size: 0.9375em;\n}\ntextarea[disabled][data-v-7eaabd11],\ninput[type="password"][disabled][data-v-7eaabd11],\ninput[type="text"][disabled][data-v-7eaabd11],\ninput[type="number"][disabled][data-v-7eaabd11],\ninput[type="date"][disabled][data-v-7eaabd11],\ninput[type="email"][disabled][data-v-7eaabd11] {\n  background: white;\n  color: rgba(27, 37, 57, 0.8);\n  -webkit-text-fill-color: rgba(27, 37, 57, 0.8);\n  opacity: 1;\n  cursor: not-allowed;\n}\ninput[type="color"][data-v-7eaabd11] {\n  width: 38px !important;\n  height: 40px;\n  border: none;\n  outline: none;\n  background: none;\n  min-width: initial !important;\n}\n.additional-link[data-v-7eaabd11] {\n  font-size: 1em;\n  margin-top: 50px;\n  display: block;\n}\n.additional-link b[data-v-7eaabd11], .additional-link a[data-v-7eaabd11] {\n  cursor: pointer;\n}\n.additional-link b[data-v-7eaabd11]:hover, .additional-link a[data-v-7eaabd11]:hover {\n  text-decoration: underline;\n}\n@media only screen and (max-width: 1024px) {\n.form[data-v-7eaabd11] {\n    max-width: 100%;\n}\n}\n@media only screen and (max-width: 960px) {\n.form .button[data-v-7eaabd11] {\n    margin-top: 20px;\n    width: 100%;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form input[data-v-7eaabd11], .form textarea[data-v-7eaabd11] {\n    width: 100%;\n    min-width: 100%;\n}\n.form.block-form .block-wrapper[data-v-7eaabd11] {\n    display: block;\n}\n.form.block-form .block-wrapper label[data-v-7eaabd11] {\n    width: 100%;\n    padding-right: 0;\n    display: block;\n    margin-bottom: 7px;\n    text-align: left !important;\n    font-size: 0.875em;\n    padding-top: 0;\n}\n.form.block-form .button[data-v-7eaabd11] {\n    margin-top: 25px;\n    margin-left: 0;\n    margin-right: 0;\n}\n.form.inline-form[data-v-7eaabd11] {\n    display: block;\n}\n.form.inline-form .input-wrapper .error-message[data-v-7eaabd11] {\n    position: relative;\n    bottom: 0;\n}\n.form .button[data-v-7eaabd11] {\n    padding: 14px 32px;\n}\n.single-line-form[data-v-7eaabd11] {\n    display: block;\n}\n.single-line-form .submit-button[data-v-7eaabd11] {\n    margin-left: 0;\n    margin-top: 20px;\n    width: 100%;\n}\ntextarea[data-v-7eaabd11],\n  input[type="password"][data-v-7eaabd11],\n  input[type="number"][data-v-7eaabd11],\n  input[type="date"][data-v-7eaabd11],\n  input[type="text"][data-v-7eaabd11],\n  input[type="email"][data-v-7eaabd11] {\n    padding: 14px 20px;\n}\n}\n@media only screen and (max-width: 690px) {\n.form.block-form .wrapper-inline[data-v-7eaabd11] {\n    display: block;\n    margin-bottom: 32px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.form .input-help[data-v-7eaabd11] {\n    color: #7d858c;\n}\n.form.block-form .block-wrapper label[data-v-7eaabd11] {\n    color: #bec6cf;\n}\n.form .inline-wrapper .switch-label .input-label[data-v-7eaabd11] {\n    color: #bec6cf;\n}\ntextarea[data-v-7eaabd11],\n  input[type="password"][data-v-7eaabd11],\n  input[type="text"][data-v-7eaabd11],\n  input[type="date"][data-v-7eaabd11],\n  input[type="number"][data-v-7eaabd11],\n  input[type="email"][data-v-7eaabd11] {\n    border-color: #1e2024;\n    background: #1e2024;\n    color: #bec6cf;\n}\ntextarea[data-v-7eaabd11]::-webkit-input-placeholder, input[type="password"][data-v-7eaabd11]::-webkit-input-placeholder, input[type="text"][data-v-7eaabd11]::-webkit-input-placeholder, input[type="date"][data-v-7eaabd11]::-webkit-input-placeholder, input[type="number"][data-v-7eaabd11]::-webkit-input-placeholder, input[type="email"][data-v-7eaabd11]::-webkit-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-7eaabd11]::-moz-placeholder, input[type="password"][data-v-7eaabd11]::-moz-placeholder, input[type="text"][data-v-7eaabd11]::-moz-placeholder, input[type="date"][data-v-7eaabd11]::-moz-placeholder, input[type="number"][data-v-7eaabd11]::-moz-placeholder, input[type="email"][data-v-7eaabd11]::-moz-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-7eaabd11]:-ms-input-placeholder, input[type="password"][data-v-7eaabd11]:-ms-input-placeholder, input[type="text"][data-v-7eaabd11]:-ms-input-placeholder, input[type="date"][data-v-7eaabd11]:-ms-input-placeholder, input[type="number"][data-v-7eaabd11]:-ms-input-placeholder, input[type="email"][data-v-7eaabd11]:-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-7eaabd11]::-ms-input-placeholder, input[type="password"][data-v-7eaabd11]::-ms-input-placeholder, input[type="text"][data-v-7eaabd11]::-ms-input-placeholder, input[type="date"][data-v-7eaabd11]::-ms-input-placeholder, input[type="number"][data-v-7eaabd11]::-ms-input-placeholder, input[type="email"][data-v-7eaabd11]::-ms-input-placeholder {\n    color: #7d858c;\n}\ntextarea[data-v-7eaabd11]::placeholder,\n  input[type="password"][data-v-7eaabd11]::placeholder,\n  input[type="text"][data-v-7eaabd11]::placeholder,\n  input[type="date"][data-v-7eaabd11]::placeholder,\n  input[type="number"][data-v-7eaabd11]::placeholder,\n  input[type="email"][data-v-7eaabd11]::placeholder {\n    color: #7d858c;\n}\ntextarea[disabled][data-v-7eaabd11],\n  input[type="password"][disabled][data-v-7eaabd11],\n  input[type="text"][disabled][data-v-7eaabd11],\n  input[type="date"][disabled][data-v-7eaabd11],\n  input[type="number"][disabled][data-v-7eaabd11],\n  input[type="email"][disabled][data-v-7eaabd11] {\n    background: #1e2024;\n    color: rgba(125, 133, 140, 0.8);\n    -webkit-text-fill-color: rgba(125, 133, 140, 0.8);\n}\n.popup-wrapper textarea[data-v-7eaabd11],\n  .popup-wrapper input[type="password"][data-v-7eaabd11],\n  .popup-wrapper input[type="date"][data-v-7eaabd11],\n  .popup-wrapper input[type="text"][data-v-7eaabd11],\n  .popup-wrapper input[type="number"][data-v-7eaabd11],\n  .popup-wrapper input[type="email"][data-v-7eaabd11] {\n    background: #25272c;\n}\n}\n.auth-logo-text[data-v-7eaabd11] {\n  font-size: 1.375em;\n  font-weight: 800;\n  margin-bottom: 40px;\n  display: block;\n}\n.auth-form[data-v-7eaabd11] {\n  text-align: center;\n  max-width: 600px;\n  padding: 25px 20px;\n  display: table-cell;\n  vertical-align: middle;\n}\n.auth-form input[data-v-7eaabd11] {\n  min-width: 310px;\n}\n.auth-form .additional-link a[data-v-7eaabd11] {\n  font-weight: 700;\n  text-decoration: none;\n}\n.auth-form .user-avatar[data-v-7eaabd11] {\n  width: 100px;\n  height: 100px;\n  -o-object-fit: cover;\n     object-fit: cover;\n  margin-bottom: 20px;\n  border-radius: 8px;\n  box-shadow: 0 10px 30px rgba(25, 54, 60, 0.2);\n}\n.auth-form .logo[data-v-7eaabd11] {\n  width: 120px;\n  margin-bottom: 20px;\n}\n.auth-form h1[data-v-7eaabd11] {\n  font-size: 2.125em;\n  font-weight: 800;\n  line-height: 1.2;\n  margin-bottom: 2px;\n  color: #1B2539;\n}\n.auth-form h2[data-v-7eaabd11] {\n  font-size: 1.4375em;\n  font-weight: 500;\n  margin-bottom: 50px;\n  color: #1B2539;\n}\n.auth-form .block-form[data-v-7eaabd11] {\n  margin-left: auto;\n  margin-right: auto;\n}\n@media only screen and (min-width: 690px) and (max-width: 960px) {\n.auth-form[data-v-7eaabd11] {\n    padding-left: 20%;\n    padding-right: 20%;\n}\n}\n@media only screen and (max-width: 690px) {\n.auth-form[data-v-7eaabd11] {\n    width: 100%;\n}\n.auth-form h1[data-v-7eaabd11] {\n    font-size: 1.875em;\n}\n.auth-form h2[data-v-7eaabd11] {\n    font-size: 1.3125em;\n}\n}\n@media only screen and (max-width: 490px) {\n.auth-form h1[data-v-7eaabd11] {\n    font-size: 1.375em;\n}\n.auth-form h2[data-v-7eaabd11] {\n    font-size: 1.125em;\n}\n.auth-form input[data-v-7eaabd11] {\n    min-width: initial;\n}\n.auth-form .additional-link[data-v-7eaabd11] {\n    font-size: 0.9375em;\n}\n}\n@media (prefers-color-scheme: dark) {\n.auth-form h1[data-v-7eaabd11], .auth-form h2[data-v-7eaabd11], .auth-form .additional-link[data-v-7eaabd11] {\n    color: #bec6cf;\n}\n}\n.content-headline[data-v-7eaabd11] {\n  max-width: 630px;\n  margin-left: auto;\n  margin-right: auto;\n}\n.auth-form input[data-v-7eaabd11] {\n  min-width: initial;\n}\n.form[data-v-7eaabd11] {\n  max-width: 580px;\n  text-align: left;\n}\n.submit-wrapper[data-v-7eaabd11] {\n  text-align: right;\n}\n.submit-wrapper .button[data-v-7eaabd11] {\n  margin: 58px 0 50px 0;\n  width: 100%;\n}\n.title-icon[data-v-7eaabd11] {\n  margin-bottom: 10px;\n  -webkit-animation: spinner-data-v-7eaabd11 5s linear infinite;\n          animation: spinner-data-v-7eaabd11 5s linear infinite;\n}\n.title-icon circle[data-v-7eaabd11], .title-icon path[data-v-7eaabd11] {\n  color: inherit;\n}\n@-webkit-keyframes spinner-data-v-7eaabd11 {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@keyframes spinner-data-v-7eaabd11 {\n0% {\n    transform: rotate(0deg);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@media (prefers-color-scheme: dark) {\n.duplicator .duplicator-item[data-v-7eaabd11] {\n    background: #1e2024;\n}\n.duplicator .duplicator-item input[data-v-7eaabd11],\n  .duplicator .duplicator-item textarea[data-v-7eaabd11] {\n    background: #141414;\n}\n}\n',""])},ASoH:function(n,a,e){"use strict";var t={name:"AuthContent",props:["loading","icon","text"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},i=(e("RLEU"),e("KHd+")),o=Object(i.a)(t,(function(){var n=this,a=n.$createElement,e=n._self._c||a;return e("button",{staticClass:"button outline hover-text-theme hover-border-theme"},[e("span",{staticClass:"text-label"},[n._v(n._s(n.text))]),n._v(" "),n.loading?e("span",{staticClass:"icon"},[e("FontAwesomeIcon",{staticClass:"sync-alt svg-color-theme",attrs:{icon:"sync-alt"}})],1):n._e(),n._v(" "),!n.loading&&n.icon?e("span",{staticClass:"icon"},[e("FontAwesomeIcon",{staticClass:"svg-color-theme",attrs:{icon:n.icon}})],1):n._e()])}),[],!1,null,"16e9ad58",null);a.a=o.exports},G6zf:function(n,a,e){"use strict";e.r(a);var t=e("o0o1"),i=e.n(t),o=e("A5+z"),r=e("bDRN"),l=e("4TWA"),d=e("eZ9V"),s=e("KnjL"),p=e("j8qy"),c=e("ASoH"),u=e("CjXH"),b=e("TJPC"),m=e("L2JU"),v=e("vDqi"),f=e.n(v);function h(n,a,e,t,i,o,r){try{var l=n[o](r),d=l.value}catch(n){return void e(n)}l.done?a(d):Promise.resolve(d).then(t,i)}function g(n,a){var e=Object.keys(n);if(Object.getOwnPropertySymbols){var t=Object.getOwnPropertySymbols(n);a&&(t=t.filter((function(a){return Object.getOwnPropertyDescriptor(n,a).enumerable}))),e.push.apply(e,t)}return e}function x(n,a,e){return a in n?Object.defineProperty(n,a,{value:e,enumerable:!0,configurable:!0,writable:!0}):n[a]=e,n}var y={name:"BillingsDetail",components:{AuthContentWrapper:r.a,ValidationProvider:o.ValidationProvider,ValidationObserver:o.ValidationObserver,SettingsIcon:u.bb,SelectInput:l.a,AuthContent:p.a,AuthButton:c.a,FormLabel:d.a,required:b.a,InfoBox:s.a},computed:function(n){for(var a=1;a<arguments.length;a++){var e=null!=arguments[a]?arguments[a]:{};a%2?g(Object(e),!0).forEach((function(a){x(n,a,e[a])})):Object.getOwnPropertyDescriptors?Object.defineProperties(n,Object.getOwnPropertyDescriptors(e)):g(Object(e)).forEach((function(a){Object.defineProperty(n,a,Object.getOwnPropertyDescriptor(e,a))}))}return n}({},Object(m.b)(["countries"])),data:function(){return{isLoading:!1,billingInformation:{billing_phone_number:"",billing_postal_code:"",billing_vat_number:"",billing_address:"",billing_country:"",billing_state:"",billing_city:"",billing_name:""}}},methods:{billingInformationSubmit:function(){var n,a=this;return(n=i.a.mark((function n(){return i.a.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,a.$refs.billingInformation.validate();case 2:if(n.sent){n.next=5;break}return n.abrupt("return");case 5:a.isLoading=!0,f.a.post("/api/setup/stripe-billings",a.billingInformation).then((function(){a.$router.push({name:"SubscriptionPlans"})})).catch((function(n){})).finally((function(){a.isLoading=!1}));case 7:case"end":return n.stop()}}),n)})),function(){var a=this,e=arguments;return new Promise((function(t,i){var o=n.apply(a,e);function r(n){h(o,t,i,r,l,"next",n)}function l(n){h(o,t,i,r,l,"throw",n)}r(void 0)}))})()}},created:function(){this.$scrollTop()}},w=(e("5b+A"),e("KHd+")),_=Object(w.a)(y,(function(){var n=this,a=n.$createElement,e=n._self._c||a;return e("AuthContentWrapper",{ref:"auth"},[e("AuthContent",{attrs:{name:"database-credentials",visible:!0}},[e("div",{staticClass:"content-headline"},[e("settings-icon",{staticClass:"title-icon",attrs:{size:"40"}}),n._v(" "),e("h1",[n._v("Setup Wizard")]),n._v(" "),e("h2",[n._v("Set up your billing information.")])],1),n._v(" "),e("ValidationObserver",{ref:"billingInformation",staticClass:"form block-form",attrs:{tag:"form"},on:{submit:function(a){return a.preventDefault(),n.billingInformationSubmit(a)}},scopedSlots:n._u([{key:"default",fn:function(a){a.invalid;return[e("FormLabel",[n._v("Company Information")]),n._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[n._v("Company Name:")]),n._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Name",rules:"required"},scopedSlots:n._u([{key:"default",fn:function(a){var t=a.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:n.billingInformation.billing_name,expression:"billingInformation.billing_name"}],class:{"is-error":t[0]},attrs:{placeholder:"Type your company name",type:"text"},domProps:{value:n.billingInformation.billing_name},on:{input:function(a){a.target.composing||n.$set(n.billingInformation,"billing_name",a.target.value)}}}),n._v(" "),t[0]?e("span",{staticClass:"error-message"},[n._v(n._s(t[0]))]):n._e()]}}],null,!0)})],1),n._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[n._v("VAT Number:")]),n._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Vat Number",rules:"required"},scopedSlots:n._u([{key:"default",fn:function(a){var t=a.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:n.billingInformation.billing_vat_number,expression:"billingInformation.billing_vat_number"}],class:{"is-error":t[0]},attrs:{placeholder:"Type your VAT number",type:"text"},domProps:{value:n.billingInformation.billing_vat_number},on:{input:function(a){a.target.composing||n.$set(n.billingInformation,"billing_vat_number",a.target.value)}}}),n._v(" "),t[0]?e("span",{staticClass:"error-message"},[n._v(n._s(t[0]))]):n._e()]}}],null,!0)})],1),n._v(" "),e("FormLabel",{staticClass:"mt-70"},[n._v("Billing Information")]),n._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[n._v("Billing Country:")]),n._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Country",rules:"required"},scopedSlots:n._u([{key:"default",fn:function(a){var t=a.errors;return[e("SelectInput",{attrs:{options:n.countries,placeholder:"Select your billing country",isError:t[0]},model:{value:n.billingInformation.billing_country,callback:function(a){n.$set(n.billingInformation,"billing_country",a)},expression:"billingInformation.billing_country"}}),n._v(" "),t[0]?e("span",{staticClass:"error-message"},[n._v(n._s(t[0]))]):n._e()]}}],null,!0)})],1),n._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[n._v("Billing Address:")]),n._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Address",rules:"required"},scopedSlots:n._u([{key:"default",fn:function(a){var t=a.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:n.billingInformation.billing_address,expression:"billingInformation.billing_address"}],class:{"is-error":t[0]},attrs:{placeholder:"Type your billing address",type:"text"},domProps:{value:n.billingInformation.billing_address},on:{input:function(a){a.target.composing||n.$set(n.billingInformation,"billing_address",a.target.value)}}}),n._v(" "),t[0]?e("span",{staticClass:"error-message"},[n._v(n._s(t[0]))]):n._e()]}}],null,!0)})],1),n._v(" "),e("div",{staticClass:"wrapper-inline"},[e("div",{staticClass:"block-wrapper"},[e("label",[n._v("Billing City:")]),n._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing City",rules:"required"},scopedSlots:n._u([{key:"default",fn:function(a){var t=a.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:n.billingInformation.billing_city,expression:"billingInformation.billing_city"}],class:{"is-error":t[0]},attrs:{placeholder:"Type your billing city",type:"text"},domProps:{value:n.billingInformation.billing_city},on:{input:function(a){a.target.composing||n.$set(n.billingInformation,"billing_city",a.target.value)}}}),n._v(" "),t[0]?e("span",{staticClass:"error-message"},[n._v(n._s(t[0]))]):n._e()]}}],null,!0)})],1),n._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[n._v("Billing Postal Code:")]),n._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Postal Code",rules:"required"},scopedSlots:n._u([{key:"default",fn:function(a){var t=a.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:n.billingInformation.billing_postal_code,expression:"billingInformation.billing_postal_code"}],class:{"is-error":t[0]},attrs:{placeholder:"Type your billing postal code",type:"text"},domProps:{value:n.billingInformation.billing_postal_code},on:{input:function(a){a.target.composing||n.$set(n.billingInformation,"billing_postal_code",a.target.value)}}}),n._v(" "),t[0]?e("span",{staticClass:"error-message"},[n._v(n._s(t[0]))]):n._e()]}}],null,!0)})],1)]),n._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[n._v("Billing State:")]),n._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing State",rules:"required"},scopedSlots:n._u([{key:"default",fn:function(a){var t=a.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:n.billingInformation.billing_state,expression:"billingInformation.billing_state"}],class:{"is-error":t[0]},attrs:{placeholder:"Type your billing state",type:"text"},domProps:{value:n.billingInformation.billing_state},on:{input:function(a){a.target.composing||n.$set(n.billingInformation,"billing_state",a.target.value)}}}),n._v(" "),t[0]?e("span",{staticClass:"error-message"},[n._v(n._s(t[0]))]):n._e()]}}],null,!0)})],1),n._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[n._v("Billing Phone Number (optional):")]),n._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Phone Number"},scopedSlots:n._u([{key:"default",fn:function(a){var t=a.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:n.billingInformation.billing_phone_number,expression:"billingInformation.billing_phone_number"}],class:{"is-error":t[0]},attrs:{placeholder:"Type your billing phone number",type:"text"},domProps:{value:n.billingInformation.billing_phone_number},on:{input:function(a){a.target.composing||n.$set(n.billingInformation,"billing_phone_number",a.target.value)}}}),n._v(" "),t[0]?e("span",{staticClass:"error-message"},[n._v(n._s(t[0]))]):n._e()]}}],null,!0)})],1),n._v(" "),e("div",{staticClass:"submit-wrapper"},[e("AuthButton",{attrs:{icon:"chevron-right",text:"Save and Create Plans",loading:n.isLoading,disabled:n.isLoading}})],1)]}}])})],1)],1)}),[],!1,null,"7eaabd11",null);a.default=_.exports},JHC5:function(n,a,e){var t=e("YUi1");"string"==typeof t&&(t=[[n.i,t,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(t,i);t.locals&&(n.exports=t.locals)},Jx8r:function(n,a,e){(n.exports=e("I1BE")(!1)).push([n.i,"#auth[data-v-31af8372] {\n  height: 100%;\n  width: 100%;\n  display: table;\n}\n",""])},KnjL:function(n,a,e){"use strict";var t={name:"InfoBox",props:["type"]},i=(e("pFam"),e("KHd+")),o=Object(i.a)(t,(function(){var n=this.$createElement;return(this._self._c||n)("div",{staticClass:"info-box",class:this.type},[this._t("default")],2)}),[],!1,null,"8e7c42f6",null);a.a=o.exports},MRmA:function(n,a,e){"use strict";var t=e("ccqs");e.n(t).a},"Qqv+":function(n,a,e){var t=e("biqn");"string"==typeof t&&(t=[[n.i,t,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(t,i);t.locals&&(n.exports=t.locals)},RLEU:function(n,a,e){"use strict";var t=e("JHC5");e.n(t).a},TJPC:function(n,a,e){"use strict";e.d(a,"a",(function(){return o}));function t(n){return null==n}function i(n){return Array.isArray(n)&&0===n.length}var o={validate:function(n,a){var e=(void 0===a?{allowFalse:!0}:a).allowFalse,o={valid:!1,required:!0};return t(n)||i(n)?o:!1!==n||e?(o.valid=!!String(n).trim().length,o):o},params:[{name:"allowFalse",default:!0}],computesRequired:!0}},VrAE:function(n,a,e){(n.exports=e("I1BE")(!1)).push([n.i,".form-label[data-v-4e7c7547] {\n  display: flex;\n  align-items: center;\n  margin-bottom: 30px;\n}\n.form-label .icon[data-v-4e7c7547] {\n  margin-right: 10px;\n}\n.form-label .icon path[data-v-4e7c7547], .form-label .icon circle[data-v-4e7c7547], .form-label .icon rect[data-v-4e7c7547], .form-label .icon line[data-v-4e7c7547] {\n  color: inherit;\n}\n.form-label .label[data-v-4e7c7547] {\n  font-size: 1.125em;\n  font-weight: 700;\n}\n@media (prefers-color-scheme: dark) {\n.form-label .label[data-v-4e7c7547] {\n    color: #bec6cf;\n}\n}\n",""])},W0ou:function(n,a,e){var t=e("VrAE");"string"==typeof t&&(t=[[n.i,t,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(t,i);t.locals&&(n.exports=t.locals)},YUi1:function(n,a,e){(n.exports=e("I1BE")(!1)).push([n.i,".button[data-v-16e9ad58] {\n  cursor: pointer;\n  border-radius: 8px;\n  text-decoration: none;\n  padding: 12px 32px;\n  display: inline-block;\n  margin-left: 15px;\n  margin-right: 15px;\n  white-space: nowrap;\n  transition: 150ms all ease;\n  background: transparent;\n}\n.button .text-label[data-v-16e9ad58] {\n  transition: 150ms all ease;\n  font-size: 1.0625em;\n  font-weight: 800;\n  line-height: 0;\n}\n.button .icon[data-v-16e9ad58] {\n  margin-left: 12px;\n  font-size: 1em;\n}\n.button.solid[data-v-16e9ad58] {\n  background: #00BC7E;\n  border: 2px solid #00BC7E;\n}\n.button.solid .text-label[data-v-16e9ad58] {\n  color: white;\n}\n.button.outline[data-v-16e9ad58] {\n  border: 2px solid #1B2539;\n}\n.button.outline .text-label[data-v-16e9ad58] {\n  color: #1B2539;\n}\n.button.outline .icon path[data-v-16e9ad58] {\n  fill: inherit;\n}\n.button.outline[data-v-16e9ad58]:hover {\n  border-color: inherit;\n}\n.button.outline:hover .text-label[data-v-16e9ad58] {\n  color: inherit;\n}\n@media (prefers-color-scheme: dark) {\n.button.outline[data-v-16e9ad58] {\n    background: #141414;\n    border-color: #bec6cf;\n}\n.button.outline .text-label[data-v-16e9ad58] {\n    color: #bec6cf;\n}\n}\n.sync-alt[data-v-16e9ad58] {\n  -webkit-animation: spin-data-v-16e9ad58 1s linear infinite;\n          animation: spin-data-v-16e9ad58 1s linear infinite;\n}\n@-webkit-keyframes spin-data-v-16e9ad58 {\n0% {\n    transform: rotate(0);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n@keyframes spin-data-v-16e9ad58 {\n0% {\n    transform: rotate(0);\n}\n100% {\n    transform: rotate(360deg);\n}\n}\n",""])},bDRN:function(n,a,e){"use strict";var t={name:"AuthContentWrapper"},i=(e("iYAH"),e("KHd+")),o=Object(i.a)(t,(function(){var n=this.$createElement;return(this._self._c||n)("div",{attrs:{id:"auth"}},[this._t("default")],2)}),[],!1,null,"31af8372",null);a.a=o.exports},biqn:function(n,a,e){(n.exports=e("I1BE")(!1)).push([n.i,".info-box[data-v-8e7c42f6] {\n  padding: 20px;\n  border-radius: 8px;\n  margin-bottom: 32px;\n  background: #f4f5f6;\n  text-align: left;\n}\n.info-box.error[data-v-8e7c42f6] {\n  background: rgba(253, 57, 122, 0.1);\n}\n.info-box.error p[data-v-8e7c42f6], .info-box.error a[data-v-8e7c42f6] {\n  color: #fd397a;\n}\n.info-box.error a[data-v-8e7c42f6] {\n  text-decoration: underline;\n}\n.info-box p[data-v-8e7c42f6] {\n  font-size: 15px;\n  line-height: 1.6;\n  word-break: break-word;\n  font-weight: 600;\n}\n.info-box p[data-v-8e7c42f6] a {\n  font-size: 15px;\n}\n.info-box p[data-v-8e7c42f6] b {\n  font-size: 15px;\n  font-weight: 700;\n}\n.info-box b[data-v-8e7c42f6] {\n  font-weight: 700;\n}\n.info-box a[data-v-8e7c42f6] {\n  font-weight: 700;\n  font-size: 0.9375em;\n  line-height: 1.6;\n}\n.info-box ul[data-v-8e7c42f6] {\n  margin-top: 15px;\n  display: block;\n}\n.info-box ul li[data-v-8e7c42f6] {\n  display: block;\n}\n.info-box ul li a[data-v-8e7c42f6] {\n  display: block;\n}\n@media only screen and (max-width: 690px) {\n.info-box[data-v-8e7c42f6] {\n    padding: 15px;\n}\n}\n@media (prefers-color-scheme: dark) {\n.info-box[data-v-8e7c42f6] {\n    background: #1e2024;\n}\n.info-box p[data-v-8e7c42f6] {\n    color: #bec6cf;\n}\n.info-box ul li[data-v-8e7c42f6] {\n    color: #bec6cf;\n}\n}\n",""])},c0Cp:function(n,a,e){(n.exports=e("I1BE")(!1)).push([n.i,".select[data-v-6c344ee4] {\n  position: relative;\n  -webkit-user-select: none;\n     -moz-user-select: none;\n      -ms-user-select: none;\n          user-select: none;\n  width: 100%;\n}\n.select-search[data-v-6c344ee4] {\n  background: white;\n  position: -webkit-sticky;\n  position: sticky;\n  top: 0;\n  padding: 13px;\n}\n.select-search .search-input[data-v-6c344ee4] {\n  border: 1px solid transparent;\n  background: #f4f5f6;\n  transition: 150ms all ease;\n  font-size: 0.875em;\n  border-radius: 8px;\n  padding: 13px 20px;\n  -webkit-appearance: none;\n     -moz-appearance: none;\n          appearance: none;\n  font-weight: 700;\n  outline: 0;\n  width: 100%;\n}\n.input-options[data-v-6c344ee4] {\n  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);\n  background: white;\n  border-radius: 8px;\n  position: absolute;\n  overflow: hidden;\n  top: 65px;\n  left: 0;\n  right: 0;\n  z-index: 9;\n  max-height: 295px;\n  overflow-y: auto;\n}\n.input-options .option-item[data-v-6c344ee4] {\n  padding: 13px 20px;\n  display: block;\n  cursor: pointer;\n}\n.input-options .option-item[data-v-6c344ee4]:hover {\n  color: #00BC7E;\n  background: #f4f5f6;\n}\n.input-options .option-item[data-v-6c344ee4]:last-child {\n  border-bottom: none;\n}\n.input-area[data-v-6c344ee4] {\n  border-width: 1px;\n  border-style: solid;\n  border-color: transparent;\n  justify-content: space-between;\n  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);\n  transition: 150ms all ease;\n  align-items: center;\n  border-radius: 8px;\n  padding: 13px 20px;\n  display: flex;\n  outline: 0;\n  width: 100%;\n  cursor: pointer;\n}\n.input-area .chevron[data-v-6c344ee4] {\n  transition: 150ms all ease;\n}\n.input-area.is-active .chevron[data-v-6c344ee4] {\n  transform: rotate(180deg);\n}\n.input-area.is-error[data-v-6c344ee4] {\n  border-color: #fd397a;\n  box-shadow: 0 0 7px rgba(253, 57, 122, 0.3);\n}\n.option-icon[data-v-6c344ee4] {\n  width: 20px;\n  display: inline-block;\n  font-size: 0.625em;\n}\n.option-icon svg[data-v-6c344ee4] {\n  margin-top: -4px;\n  vertical-align: middle;\n}\n.option-value[data-v-6c344ee4] {\n  font-size: 1em;\n  font-weight: 700;\n  width: 100%;\n  vertical-align: middle;\n}\n.option-value.placehoder[data-v-6c344ee4] {\n  color: rgba(27, 37, 57, 0.5);\n}\n.slide-in-enter-active[data-v-6c344ee4] {\n  transition: all 150ms ease;\n}\n.slide-in-enter[data-v-6c344ee4] {\n  opacity: 0;\n  transform: translateY(-50px);\n}\n@media (prefers-color-scheme: dark) {\n.select-search[data-v-6c344ee4] {\n    background: #1e2024;\n}\n.select-search .search-input[data-v-6c344ee4] {\n    background: #141414;\n}\n.input-area[data-v-6c344ee4] {\n    background: #1e2024;\n    border-color: #1e2024;\n}\n.popup-wrapper .input-area[data-v-6c344ee4] {\n    background: #25272c;\n}\n.input-options[data-v-6c344ee4] {\n    background: #1e2024;\n}\n.input-options .option-item[data-v-6c344ee4] {\n    border-bottom: none;\n}\n.input-options .option-item[data-v-6c344ee4]:hover {\n    background: #2a2c32;\n}\n.input-options .option-item:hover .option-icon path[data-v-6c344ee4], .input-options .option-item:hover .option-icon circle[data-v-6c344ee4] {\n    color: inherit;\n}\n.input-options .option-item[data-v-6c344ee4]:last-child {\n    border-bottom: none;\n}\n.option-value.placehoder[data-v-6c344ee4] {\n    color: #7d858c;\n}\n}\n",""])},ccqs:function(n,a,e){var t=e("c0Cp");"string"==typeof t&&(t=[[n.i,t,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(t,i);t.locals&&(n.exports=t.locals)},eZ9V:function(n,a,e){"use strict";var t=e("CjXH"),i={name:"FormLabel",props:["icon"],components:{CreditCardIcon:t.p,SettingsIcon:t.bb,Edit2Icon:t.t,EditIcon:t.u,UserIcon:t.kb,ToolIcon:t.fb}},o=(e("8aG9"),e("KHd+")),r=Object(o.a)(i,(function(){var n=this,a=n.$createElement,e=n._self._c||a;return e("div",{staticClass:"form-label"},[n.icon?n._e():e("edit2-icon",{staticClass:"icon text-theme",attrs:{size:"22"}}),n._v(" "),"credit-card"===n.icon?e("credit-card-icon",{staticClass:"icon text-theme",attrs:{size:"22"}}):n._e(),n._v(" "),"tool"===n.icon?e("tool-icon",{staticClass:"icon text-theme",attrs:{size:"22"}}):n._e(),n._v(" "),"edit"===n.icon?e("edit-icon",{staticClass:"icon text-theme",attrs:{size:"22"}}):n._e(),n._v(" "),"user"===n.icon?e("user-icon",{staticClass:"icon text-theme",attrs:{size:"22"}}):n._e(),n._v(" "),"settings"===n.icon?e("settings-icon",{staticClass:"icon text-theme",attrs:{size:"22"}}):n._e(),n._v(" "),e("b",{staticClass:"label"},[n._t("default")],2)],1)}),[],!1,null,"4e7c7547",null);a.a=r.exports},iYAH:function(n,a,e){"use strict";var t=e("lh0Q");e.n(t).a},j8qy:function(n,a,e){"use strict";var t={name:"AuthContent",props:["visible","name"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},i=e("KHd+"),o=Object(i.a)(t,(function(){var n=this.$createElement,a=this._self._c||n;return this.isVisible?a("div",{staticClass:"auth-form"},[this._t("default")],2):this._e()}),[],!1,null,null,null);a.a=o.exports},lh0Q:function(n,a,e){var t=e("Jx8r");"string"==typeof t&&(t=[[n.i,t,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(t,i);t.locals&&(n.exports=t.locals)},pFam:function(n,a,e){"use strict";var t=e("Qqv+");e.n(t).a},vRyx:function(n,a,e){var t=e("9kSd");"string"==typeof t&&(t=[[n.i,t,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(t,i);t.locals&&(n.exports=t.locals)}}]);