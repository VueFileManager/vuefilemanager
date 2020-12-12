(window.webpackJsonp=window.webpackJsonp||[]).push([[16],{"6gqG":function(a,t,e){var d=e("ydEr");"string"==typeof d&&(d=[[a.i,d,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(d,r);d.locals&&(a.exports=d.locals)},"7OGm":function(a,t,e){"use strict";var d=e("6gqG");e.n(d).a},"9xkz":function(a,t,e){"use strict";e.r(t);var d=e("o0o1"),r=e.n(d),o=e("A5+z"),i=e("bDRN"),n=e("4TWA"),s=e("eZ9V"),l=e("KnjL"),c=e("j8qy"),p=e("ASoH"),u=e("CjXH"),m=e("TJPC"),b=e("vDqi"),v=e.n(b);function f(a,t,e,d,r,o,i){try{var n=a[o](i),s=n.value}catch(a){return void e(a)}n.done?t(s):Promise.resolve(s).then(d,r)}var h={name:"Database",components:{AuthContentWrapper:i.a,ValidationProvider:o.ValidationProvider,ValidationObserver:o.ValidationObserver,SettingsIcon:u.Q,SelectInput:n.a,AuthContent:c.a,AuthButton:p.a,FormLabel:s.a,required:m.a,InfoBox:l.a},computed:{submitButtonText:function(){return this.isLoading?"Testing and Installing Database":"Test Connection and Install Database"}},data:function(){return{isLoading:!1,isError:!1,errorMessage:"",connectionList:[{label:"MySQL",value:"mysql"}],databaseCredentials:{connection:"mysql",host:"",port:"",name:"",username:"",password:""}}},methods:{databaseCredentialsSubmit:function(){var a,t=this;return(a=r.a.mark((function a(){return r.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return a.next=2,t.$refs.verifyPurchaseCode.validate();case 2:if(a.sent){a.next=5;break}return a.abrupt("return");case 5:t.isLoading=!0,t.isError=!1,v.a.post("/api/setup/database",t.databaseCredentials).then((function(a){t.isLoading=!1,t.$router.push({name:"InstallationDisclaimer"})})).catch((function(a){(a.response.status=500)&&(t.isError=!0,t.errorMessage=a.response.data.message),t.isLoading=!1}));case 8:case"end":return a.stop()}}),a)})),function(){var t=this,e=arguments;return new Promise((function(d,r){var o=a.apply(t,e);function i(a){f(o,d,r,i,n,"next",a)}function n(a){f(o,d,r,i,n,"throw",a)}i(void 0)}))})()}},created:function(){this.$scrollTop()}},x=(e("GLs1"),e("KHd+")),g=Object(x.a)(h,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("AuthContentWrapper",{ref:"auth"},[e("AuthContent",{attrs:{name:"database-credentials",visible:!0}},[e("div",{staticClass:"content-headline"},[e("settings-icon",{staticClass:"title-icon",attrs:{size:"40"}}),a._v(" "),e("h1",[a._v("Setup Wizard")]),a._v(" "),e("h2",[a._v("Set up your database connection to install application database.")])],1),a._v(" "),e("ValidationObserver",{ref:"verifyPurchaseCode",staticClass:"form block-form",attrs:{tag:"form"},on:{submit:function(t){return t.preventDefault(),a.databaseCredentialsSubmit(t)}},scopedSlots:a._u([{key:"default",fn:function(t){t.invalid;return[e("FormLabel",[a._v("Database Credentials")]),a._v(" "),e("InfoBox",[e("p",[a._v("We strongly recommend use MySQL or MariaDB database. Create new database, set all privileges and get credentials. For those who use cPanel or Plesk, here is useful resources:")]),a._v(" "),e("ul",[e("li",[e("a",{attrs:{href:"https://www.inmotionhosting.com/support/edu/cpanel/create-database-2/",target:"_blank"}},[a._v("1. cPanel - MySQL Database Wizard")]),a._v(" "),e("a",{attrs:{href:"https://docs.plesk.com/en-US/obsidian/customer-guide/65157/",target:"_blank"}},[a._v("2. Plesk - Website databases")])])])]),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v("Connection:")]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Connection",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var d=t.errors;return[e("SelectInput",{attrs:{options:a.connectionList,default:"mysql",placeholder:"Select your database connection",isError:d[0]},model:{value:a.databaseCredentials.connection,callback:function(t){a.$set(a.databaseCredentials,"connection",t)},expression:"databaseCredentials.connection"}}),a._v(" "),d[0]?e("span",{staticClass:"error-message"},[a._v(a._s(d[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v("Host:")]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Host",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var d=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.databaseCredentials.host,expression:"databaseCredentials.host"}],class:{"is-error":d[0]},attrs:{placeholder:"Type your database host",type:"text"},domProps:{value:a.databaseCredentials.host},on:{input:function(t){t.target.composing||a.$set(a.databaseCredentials,"host",t.target.value)}}}),a._v(" "),d[0]?e("span",{staticClass:"error-message"},[a._v(a._s(d[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v("Port:")]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Port",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var d=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.databaseCredentials.port,expression:"databaseCredentials.port"}],class:{"is-error":d[0]},attrs:{placeholder:"Type your database port",type:"text"},domProps:{value:a.databaseCredentials.port},on:{input:function(t){t.target.composing||a.$set(a.databaseCredentials,"port",t.target.value)}}}),a._v(" "),d[0]?e("span",{staticClass:"error-message"},[a._v(a._s(d[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v("Database Name:")]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Database Name",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var d=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.databaseCredentials.name,expression:"databaseCredentials.name"}],class:{"is-error":d[0]},attrs:{placeholder:"Select your database name",type:"text"},domProps:{value:a.databaseCredentials.name},on:{input:function(t){t.target.composing||a.$set(a.databaseCredentials,"name",t.target.value)}}}),a._v(" "),d[0]?e("span",{staticClass:"error-message"},[a._v(a._s(d[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v("Database Username:")]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Database Username",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var d=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.databaseCredentials.username,expression:"databaseCredentials.username"}],class:{"is-error":d[0]},attrs:{placeholder:"Select your database name",type:"text"},domProps:{value:a.databaseCredentials.username},on:{input:function(t){t.target.composing||a.$set(a.databaseCredentials,"username",t.target.value)}}}),a._v(" "),d[0]?e("span",{staticClass:"error-message"},[a._v(a._s(d[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),e("div",{staticClass:"block-wrapper"},[e("label",[a._v("Database Password:")]),a._v(" "),e("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Database Password",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(t){var d=t.errors;return[e("input",{directives:[{name:"model",rawName:"v-model",value:a.databaseCredentials.password,expression:"databaseCredentials.password"}],class:{"is-error":d[0]},attrs:{placeholder:"Select your database password",type:"text"},domProps:{value:a.databaseCredentials.password},on:{input:function(t){t.target.composing||a.$set(a.databaseCredentials,"password",t.target.value)}}}),a._v(" "),d[0]?e("span",{staticClass:"error-message"},[a._v(a._s(d[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),a.isError?e("InfoBox",{staticStyle:{"margin-bottom":"10px"},attrs:{type:"error"}},[e("p",[a._v("We couldn't establish database connection. Please double check your database credentials.")]),a._v(" "),e("br"),a._v(" "),e("p",[a._v("Detailed error: "+a._s(a.errorMessage))])]):a._e(),a._v(" "),e("div",{staticClass:"submit-wrapper"},[e("AuthButton",{attrs:{icon:"chevron-right",text:a.submitButtonText,loading:a.isLoading,disabled:a.isLoading}})],1)]}}])})],1)],1)}),[],!1,null,"3d9c02d4",null);t.default=g.exports},ASoH:function(a,t,e){"use strict";var d={name:"AuthContent",props:["loading","icon","text"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},r=(e("s/ZW"),e("KHd+")),o=Object(r.a)(d,(function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("button",{staticClass:"button outline"},[e("span",{staticClass:"text-label"},[a._v(a._s(a.text))]),a._v(" "),a.loading?e("span",{staticClass:"icon"},[e("FontAwesomeIcon",{staticClass:"sync-alt",attrs:{icon:"sync-alt"}})],1):a._e(),a._v(" "),!a.loading&&a.icon?e("span",{staticClass:"icon"},[e("FontAwesomeIcon",{attrs:{icon:a.icon}})],1):a._e()])}),[],!1,null,"59e2dfc0",null);t.a=o.exports},GLs1:function(a,t,e){"use strict";var d=e("ZIJV");e.n(d).a},GwTe:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".button[data-v-59e2dfc0]{cursor:pointer;border-radius:8px;text-decoration:none;padding:12px 32px;display:inline-block;margin-left:15px;margin-right:15px;white-space:nowrap;transition:all .15s ease;background:transparent}.button .text-label[data-v-59e2dfc0]{transition:all .15s ease;font-size:1.0625em;font-weight:800;line-height:0}.button .icon[data-v-59e2dfc0]{margin-left:12px;font-size:1em}.button.solid[data-v-59e2dfc0]{background:#00bc7e;border:2px solid #00bc7e}.button.solid .text-label[data-v-59e2dfc0]{color:#fff}.button.outline[data-v-59e2dfc0]{border:2px solid #1b2539}.button.outline .text-label[data-v-59e2dfc0]{color:#1b2539}.button.outline .icon path[data-v-59e2dfc0]{fill:#00bc7e}.button.outline[data-v-59e2dfc0]:hover{border-color:#00bc7e}.button.outline:hover .text-label[data-v-59e2dfc0]{color:#00bc7e}@media (prefers-color-scheme:dark){.button.outline[data-v-59e2dfc0]{background:#131414;border-color:#bec6cf}.button.outline .text-label[data-v-59e2dfc0]{color:#bec6cf}}.sync-alt[data-v-59e2dfc0]{-webkit-animation:spin-data-v-59e2dfc0 1s linear infinite;animation:spin-data-v-59e2dfc0 1s linear infinite}@-webkit-keyframes spin-data-v-59e2dfc0{0%{transform:rotate(0)}to{transform:rotate(1turn)}}@keyframes spin-data-v-59e2dfc0{0%{transform:rotate(0)}to{transform:rotate(1turn)}}",""])},Idvm:function(a,t,e){var d=e("lig4");"string"==typeof d&&(d=[[a.i,d,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(d,r);d.locals&&(a.exports=d.locals)},Jx8r:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,"#auth[data-v-31af8372]{height:100%;width:100%;display:table}",""])},KnjL:function(a,t,e){"use strict";var d={name:"InfoBox",props:["type"]},r=(e("7OGm"),e("KHd+")),o=Object(r.a)(d,(function(){var a=this.$createElement;return(this._self._c||a)("div",{staticClass:"info-box",class:this.type},[this._t("default")],2)}),[],!1,null,"bf43be5e",null);t.a=o.exports},N6C7:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".form[data-v-3d9c02d4]{max-width:700px}.form.inline-form[data-v-3d9c02d4]{display:flex;position:relative;justify-content:center;margin:0 auto}.form.inline-form .input-wrapper[data-v-3d9c02d4]{position:relative}.form.inline-form .input-wrapper .error-message[data-v-3d9c02d4]{position:absolute;left:0;bottom:-25px}.form.block-form .wrapper-inline[data-v-3d9c02d4]{display:flex;margin:0 -15px}.form.block-form .wrapper-inline .block-wrapper[data-v-3d9c02d4]{width:100%;padding:0 15px}.form.block-form .block-wrapper[data-v-3d9c02d4]{margin-bottom:32px}.form.block-form .block-wrapper label[data-v-3d9c02d4]{font-size:.875em;color:rgba(27,37,57,.8);font-weight:700;display:block;margin-bottom:7px;text-align:left}.form.block-form .block-wrapper[data-v-3d9c02d4]:last-child{margin-bottom:0}.form.block-form .button[data-v-3d9c02d4]{margin-top:50px}.form .inline-wrapper[data-v-3d9c02d4]{display:flex;align-items:center;justify-content:space-between}.form .inline-wrapper .switch-label .input-help[data-v-3d9c02d4]{padding-top:0}.form .inline-wrapper .switch-label .input-label[data-v-3d9c02d4]{font-weight:700;color:#1b2539;font-size:1em;margin-bottom:5px}.form .input-help[data-v-3d9c02d4]{font-size:.75em;color:rgba(27,37,57,.7);line-height:1.35;padding-top:10px;display:block}.single-line-form[data-v-3d9c02d4]{display:flex}.single-line-form .submit-button[data-v-3d9c02d4]{margin-left:20px}.error-message[data-v-3d9c02d4]{font-size:.875em;color:#fd397a;padding-top:5px;display:block;text-align:left}textarea[data-v-3d9c02d4]{width:100%}input[type=email][data-v-3d9c02d4],input[type=number][data-v-3d9c02d4],input[type=password][data-v-3d9c02d4],input[type=text][data-v-3d9c02d4],textarea[data-v-3d9c02d4]{border:1px solid transparent;transition:all .15s ease;font-size:1em;border-radius:8px;padding:13px 20px;-webkit-appearance:none;-moz-appearance:none;appearance:none;font-weight:700;outline:0;width:100%;box-shadow:0 1px 5px rgba(0,0,0,.12)}input[type=email].is-error[data-v-3d9c02d4],input[type=number].is-error[data-v-3d9c02d4],input[type=password].is-error[data-v-3d9c02d4],input[type=text].is-error[data-v-3d9c02d4],textarea.is-error[data-v-3d9c02d4]{border-color:#fd397a;box-shadow:0 1px 5px rgba(253,57,122,.3)}input[type=email][data-v-3d9c02d4]::-moz-placeholder,input[type=number][data-v-3d9c02d4]::-moz-placeholder,input[type=password][data-v-3d9c02d4]::-moz-placeholder,input[type=text][data-v-3d9c02d4]::-moz-placeholder,textarea[data-v-3d9c02d4]::-moz-placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-3d9c02d4]:-ms-input-placeholder,input[type=number][data-v-3d9c02d4]:-ms-input-placeholder,input[type=password][data-v-3d9c02d4]:-ms-input-placeholder,input[type=text][data-v-3d9c02d4]:-ms-input-placeholder,textarea[data-v-3d9c02d4]:-ms-input-placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-3d9c02d4]::placeholder,input[type=number][data-v-3d9c02d4]::placeholder,input[type=password][data-v-3d9c02d4]::placeholder,input[type=text][data-v-3d9c02d4]::placeholder,textarea[data-v-3d9c02d4]::placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-3d9c02d4]:focus,input[type=number][data-v-3d9c02d4]:focus,input[type=password][data-v-3d9c02d4]:focus,input[type=text][data-v-3d9c02d4]:focus,textarea[data-v-3d9c02d4]:focus{border-color:#00bc7e;box-shadow:0 1px 5px rgba(0,188,126,.3)}input[type=email][disabled][data-v-3d9c02d4],input[type=number][disabled][data-v-3d9c02d4],input[type=password][disabled][data-v-3d9c02d4],input[type=text][disabled][data-v-3d9c02d4],textarea[disabled][data-v-3d9c02d4]{background:#fff;color:rgba(27,37,57,.8);-webkit-text-fill-color:rgba(27,37,57,.8);opacity:1;cursor:not-allowed}.additional-link[data-v-3d9c02d4]{font-size:1em;margin-top:50px;display:block;color:#1b2539}.additional-link a[data-v-3d9c02d4],.additional-link b[data-v-3d9c02d4]{color:#00bc7e;cursor:pointer}.additional-link a[data-v-3d9c02d4]:hover,.additional-link b[data-v-3d9c02d4]:hover{text-decoration:underline}@media only screen and (max-width:1024px){.form[data-v-3d9c02d4]{max-width:100%}}@media only screen and (max-width:960px){.form .button[data-v-3d9c02d4]{margin-top:20px;width:100%;margin-left:0;margin-right:0}.form input[data-v-3d9c02d4],.form textarea[data-v-3d9c02d4]{width:100%;min-width:100%}.form.block-form .block-wrapper[data-v-3d9c02d4]{display:block}.form.block-form .block-wrapper label[data-v-3d9c02d4]{width:100%;padding-right:0;display:block;margin-bottom:7px;text-align:left!important;font-size:.875em;padding-top:0}.form.block-form .button[data-v-3d9c02d4]{margin-top:25px;margin-left:0;margin-right:0}.form.inline-form[data-v-3d9c02d4]{display:block}.form.inline-form .input-wrapper .error-message[data-v-3d9c02d4]{position:relative;bottom:0}.form .button[data-v-3d9c02d4]{padding:14px 32px}.single-line-form[data-v-3d9c02d4]{display:block}.single-line-form .submit-button[data-v-3d9c02d4]{margin-left:0;margin-top:20px;width:100%}input[type=email][data-v-3d9c02d4],input[type=number][data-v-3d9c02d4],input[type=password][data-v-3d9c02d4],input[type=text][data-v-3d9c02d4],textarea[data-v-3d9c02d4]{padding:14px 20px}}@media only screen and (max-width:690px){.form.block-form .wrapper-inline[data-v-3d9c02d4]{display:block}}@media (prefers-color-scheme:dark){.form .input-help[data-v-3d9c02d4]{color:#7d858c}.form.block-form .block-wrapper label[data-v-3d9c02d4],.form .inline-wrapper .switch-label .input-label[data-v-3d9c02d4]{color:#bec6cf}input[type=email][data-v-3d9c02d4],input[type=number][data-v-3d9c02d4],input[type=password][data-v-3d9c02d4],input[type=text][data-v-3d9c02d4],textarea[data-v-3d9c02d4]{border-color:#1e2024;background:#1e2024;color:#bec6cf}input[type=email][data-v-3d9c02d4]::-moz-placeholder,input[type=number][data-v-3d9c02d4]::-moz-placeholder,input[type=password][data-v-3d9c02d4]::-moz-placeholder,input[type=text][data-v-3d9c02d4]::-moz-placeholder,textarea[data-v-3d9c02d4]::-moz-placeholder{color:#7d858c}input[type=email][data-v-3d9c02d4]:-ms-input-placeholder,input[type=number][data-v-3d9c02d4]:-ms-input-placeholder,input[type=password][data-v-3d9c02d4]:-ms-input-placeholder,input[type=text][data-v-3d9c02d4]:-ms-input-placeholder,textarea[data-v-3d9c02d4]:-ms-input-placeholder{color:#7d858c}input[type=email][data-v-3d9c02d4]::placeholder,input[type=number][data-v-3d9c02d4]::placeholder,input[type=password][data-v-3d9c02d4]::placeholder,input[type=text][data-v-3d9c02d4]::placeholder,textarea[data-v-3d9c02d4]::placeholder{color:#7d858c}input[type=email][disabled][data-v-3d9c02d4],input[type=number][disabled][data-v-3d9c02d4],input[type=password][disabled][data-v-3d9c02d4],input[type=text][disabled][data-v-3d9c02d4],textarea[disabled][data-v-3d9c02d4]{background:#1e2024;color:rgba(125,133,140,.8);-webkit-text-fill-color:rgba(125,133,140,.8)}}.auth-logo-text[data-v-3d9c02d4]{font-size:1.375em;font-weight:800;margin-bottom:40px;display:block}.auth-form[data-v-3d9c02d4]{text-align:center;max-width:600px;padding:25px 20px;display:table-cell;vertical-align:middle}.auth-form input[data-v-3d9c02d4]{min-width:310px}.auth-form .additional-link a[data-v-3d9c02d4]{font-weight:700;text-decoration:none}.auth-form .user-avatar[data-v-3d9c02d4]{width:100px;height:100px;-o-object-fit:cover;object-fit:cover;margin-bottom:20px;border-radius:8px;box-shadow:0 10px 30px rgba(25,54,60,.2)}.auth-form .logo[data-v-3d9c02d4]{width:120px;margin-bottom:20px}.auth-form h1[data-v-3d9c02d4]{font-size:2.125em;font-weight:800;line-height:1.2;margin-bottom:2px;color:#1b2539}.auth-form h2[data-v-3d9c02d4]{font-size:1.4375em;font-weight:500;margin-bottom:50px;color:#1b2539}.auth-form .block-form[data-v-3d9c02d4]{margin-left:auto;margin-right:auto}@media only screen and (min-width:690px) and (max-width:960px){.auth-form[data-v-3d9c02d4]{padding-left:20%;padding-right:20%}}@media only screen and (max-width:690px){.auth-form[data-v-3d9c02d4]{width:100%}.auth-form h1[data-v-3d9c02d4]{font-size:1.875em}.auth-form h2[data-v-3d9c02d4]{font-size:1.3125em}}@media only screen and (max-width:490px){.auth-form h1[data-v-3d9c02d4]{font-size:1.375em}.auth-form h2[data-v-3d9c02d4]{font-size:1.125em}.auth-form input[data-v-3d9c02d4]{min-width:0}.auth-form .additional-link[data-v-3d9c02d4]{font-size:.9375em}}@media (prefers-color-scheme:dark){.auth-form .additional-link[data-v-3d9c02d4],.auth-form h1[data-v-3d9c02d4],.auth-form h2[data-v-3d9c02d4]{color:#bec6cf}}.content-headline[data-v-3d9c02d4]{max-width:630px;margin-left:auto;margin-right:auto}.auth-form input[data-v-3d9c02d4]{min-width:0}.duplicator .duplicator-add-button[data-v-3d9c02d4]{width:100%}.duplicator .duplicator-item[data-v-3d9c02d4]{box-shadow:0 1px 5px rgba(0,0,0,.12);border-radius:8px;background:#fff;padding:25px;margin:0 -25px 32px;position:relative}.duplicator .duplicator-item .duplicator-title[data-v-3d9c02d4]{font-size:1.125em;margin-bottom:20px;display:block;font-weight:700}.duplicator .duplicator-item .delete-item[data-v-3d9c02d4]{position:absolute;top:15px;right:15px;cursor:pointer}.duplicator .duplicator-item .delete-item:hover line[data-v-3d9c02d4]{stroke:#00bc7e}.duplicator .duplicator-item input[data-v-3d9c02d4],.duplicator .duplicator-item textarea[data-v-3d9c02d4]{box-shadow:none;background:#fafafa}.form[data-v-3d9c02d4]{max-width:580px;text-align:left}.submit-wrapper[data-v-3d9c02d4]{text-align:right}.submit-wrapper .button[data-v-3d9c02d4]{margin:58px 0 50px;width:100%}.title-icon[data-v-3d9c02d4]{margin-bottom:10px;-webkit-animation:spinner-data-v-3d9c02d4 5s linear infinite;animation:spinner-data-v-3d9c02d4 5s linear infinite}.title-icon circle[data-v-3d9c02d4],.title-icon path[data-v-3d9c02d4]{stroke:#00bc7e}@-webkit-keyframes spinner-data-v-3d9c02d4{0%{transform:rotate(0deg)}to{transform:rotate(1turn)}}@keyframes spinner-data-v-3d9c02d4{0%{transform:rotate(0deg)}to{transform:rotate(1turn)}}@media (prefers-color-scheme:dark){.duplicator .duplicator-item[data-v-3d9c02d4]{background:#1e2024}.duplicator .duplicator-item input[data-v-3d9c02d4],.duplicator .duplicator-item textarea[data-v-3d9c02d4]{background:#131414}}",""])},NbAf:function(a,t,e){var d=e("GwTe");"string"==typeof d&&(d=[[a.i,d,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(d,r);d.locals&&(a.exports=d.locals)},Xk6H:function(a,t,e){"use strict";var d=e("Idvm");e.n(d).a},ZIJV:function(a,t,e){var d=e("N6C7");"string"==typeof d&&(d=[[a.i,d,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(d,r);d.locals&&(a.exports=d.locals)},bDRN:function(a,t,e){"use strict";var d={name:"AuthContentWrapper"},r=(e("iYAH"),e("KHd+")),o=Object(r.a)(d,(function(){var a=this.$createElement;return(this._self._c||a)("div",{attrs:{id:"auth"}},[this._t("default")],2)}),[],!1,null,"31af8372",null);t.a=o.exports},eZ9V:function(a,t,e){"use strict";var d={name:"FormLabel",components:{Edit2Icon:e("CjXH").p}},r=(e("Xk6H"),e("KHd+")),o=Object(r.a)(d,(function(){var a=this.$createElement,t=this._self._c||a;return t("div",{staticClass:"form-label"},[t("edit-2-icon",{staticClass:"icon",attrs:{size:"22"}}),this._v(" "),t("b",{staticClass:"label"},[this._t("default")],2)],1)}),[],!1,null,"c1157a8e",null);t.a=o.exports},iYAH:function(a,t,e){"use strict";var d=e("lh0Q");e.n(d).a},j8qy:function(a,t,e){"use strict";var d={name:"AuthContent",props:["visible","name"],data:function(){return{isVisible:!1}},created:function(){this.isVisible=this.visible}},r=e("KHd+"),o=Object(r.a)(d,(function(){var a=this.$createElement,t=this._self._c||a;return this.isVisible?t("div",{staticClass:"auth-form"},[this._t("default")],2):this._e()}),[],!1,null,null,null);t.a=o.exports},lh0Q:function(a,t,e){var d=e("Jx8r");"string"==typeof d&&(d=[[a.i,d,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};e("aET+")(d,r);d.locals&&(a.exports=d.locals)},lig4:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".form-label[data-v-c1157a8e]{display:flex;align-items:center;margin-bottom:30px}.form-label .icon[data-v-c1157a8e]{margin-right:10px}.form-label .icon path[data-v-c1157a8e]{stroke:#00bc7e}.form-label .label[data-v-c1157a8e]{font-size:1.125em;font-weight:700}@media (prefers-color-scheme:dark){.form-label .label[data-v-c1157a8e]{color:#bec6cf}}",""])},"s/ZW":function(a,t,e){"use strict";var d=e("NbAf");e.n(d).a},ydEr:function(a,t,e){(a.exports=e("I1BE")(!1)).push([a.i,".info-box[data-v-bf43be5e]{padding:20px;border-radius:8px;margin-bottom:32px;background:#f4f5f6;text-align:left}.info-box.error[data-v-bf43be5e]{background:rgba(253,57,122,.1)}.info-box.error a[data-v-bf43be5e],.info-box.error p[data-v-bf43be5e]{color:#fd397a}.info-box.error a[data-v-bf43be5e]{text-decoration:underline}.info-box p[data-v-bf43be5e]{font-size:.9375em;line-height:1.6;word-break:break-word;font-weight:600}.info-box p[data-v-bf43be5e] a{color:#00bc7e}.info-box a[data-v-bf43be5e],.info-box b[data-v-bf43be5e]{font-weight:700;color:#00bc7e}.info-box a[data-v-bf43be5e]{font-size:.9375em;line-height:1.6}.info-box ul[data-v-bf43be5e]{margin-top:15px}.info-box ul[data-v-bf43be5e],.info-box ul li[data-v-bf43be5e],.info-box ul li a[data-v-bf43be5e]{display:block}@media only screen and (max-width:690px){.info-box[data-v-bf43be5e]{padding:15px}}@media (prefers-color-scheme:dark){.info-box[data-v-bf43be5e]{background:#1e2024}.info-box p[data-v-bf43be5e],.info-box ul li[data-v-bf43be5e]{color:#bec6cf}}",""])}}]);