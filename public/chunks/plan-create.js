(window.webpackJsonp=window.webpackJsonp||[]).push([[28],{"2Sb1":function(a,e,t){"use strict";var r={name:"PageHeader",props:["title","canBack"],components:{ChevronLeftIcon:t("CjXH").e}},o=(t("qf9i"),t("KHd+")),i=Object(o.a)(r,(function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("div",{staticClass:"page-header"},[a.canBack?t("div",{staticClass:"go-back",on:{click:function(e){return a.$router.back()}}},[t("chevron-left-icon",{attrs:{size:"17"}})],1):a._e(),a._v(" "),t("div",{staticClass:"content"},[t("h1",{staticClass:"title"},[a._v(a._s(a.title))])])])}),[],!1,null,"aafe7e24",null);e.a=i.exports},"3Idf":function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".page-header[data-v-aafe7e24]{display:flex;align-items:center;background:#fff;z-index:9;width:100%;position:-webkit-sticky;position:sticky;top:0;padding-top:20px;padding-bottom:20px}.page-header .title[data-v-aafe7e24]{font-size:1.125em;font-weight:700;color:#1b2539}.page-header .go-back[data-v-aafe7e24]{margin-right:10px;cursor:pointer}.page-header .go-back svg[data-v-aafe7e24]{vertical-align:middle;margin-top:-4px}@media only screen and (max-width:960px){.page-header .title[data-v-aafe7e24]{font-size:1.125em}}@media only screen and (max-width:690px){.page-header[data-v-aafe7e24]{display:none}}@media (prefers-color-scheme:dark){.page-header[data-v-aafe7e24]{background:#111314}.page-header .title[data-v-aafe7e24]{color:#bec6cf}.page-header .icon path[data-v-aafe7e24]{fill:#00bc7e}}",""])},"6gqG":function(a,e,t){var r=t("ydEr");"string"==typeof r&&(r=[[a.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(r,o);r.locals&&(a.exports=r.locals)},"7OGm":function(a,e,t){"use strict";var r=t("6gqG");t.n(r).a},"92Jy":function(a,e,t){"use strict";var r=t("qphJ");t.n(r).a},"D+dh":function(a,e,t){"use strict";var r=t("CjXH"),o={name:"ImageInput",props:["image","error"],components:{ImageIcon:r.y,XIcon:r.Z},data:function(){return{imagePreview:void 0}},computed:{isData:function(){return void 0!==this.imagePreview&&""!==this.imagePreview}},methods:{resetImage:function(){this.imagePreview=void 0,this.$emit("input",void 0)},showImagePreview:function(a){var e=this,t=a.target.files[0].name,r=t.substring(t.lastIndexOf(".")+1).toLowerCase();if(["png","jpg","jpeg","svg"].includes(r)){var o=a.target.files[0],i=new FileReader;i.onload=function(){return e.imagePreview=i.result},i.readAsDataURL(o),this.$emit("input",a.target.files[0])}else alert(this.$t("validation_errors.wrong_image"))}},created:function(){this.image&&(this.imagePreview=this.image)}},i=(t("w9z4"),t("KHd+")),n=Object(i.a)(o,(function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("div",{staticClass:"dropzone",class:{"is-error":a.error}},[a.imagePreview?t("div",{staticClass:"reset-image",on:{click:a.resetImage}},[t("x-icon",{staticClass:"close-icon",attrs:{size:"14"}})],1):a._e(),a._v(" "),t("input",{ref:"file",staticClass:"dummy",attrs:{type:"file"},on:{change:function(e){return a.showImagePreview(e)}}}),a._v(" "),a.imagePreview?t("img",{ref:"image",staticClass:"image-preview",attrs:{src:a.imagePreview}}):a._e(),a._v(" "),t("div",{directives:[{name:"show",rawName:"v-show",value:!a.isData,expression:"! isData"}],staticClass:"dropzone-message"},[t("image-icon",{staticClass:"icon-upload",attrs:{size:"28"}}),a._v(" "),t("span",{staticClass:"dropzone-title"},[a._v("\n            "+a._s(a.$t("input_image.title"))+"\n        ")]),a._v(" "),t("span",{staticClass:"dropzone-description"},[a._v("\n            "+a._s(a.$t("input_image.supported"))+"\n        ")])],1)])}),[],!1,null,"eb0cae00",null);e.a=n.exports},DkdH:function(a,e,t){"use strict";t.r(e);var r=t("o0o1"),o=t.n(r),i=t("A5+z"),n=t("4TWA"),s=t("D+dh"),p=t("D62o"),l=t("eZ9V"),d=t("THmQ"),c=t("Nv84"),f=t("2Sb1"),m=t("KnjL"),v=t("TJPC"),u=t("xCqy"),b=t("vDqi"),g=t.n(b);function h(a,e,t,r,o,i,n){try{var s=a[i](n),p=s.value}catch(a){return void t(a)}s.done?e(p):Promise.resolve(p).then(r,o)}var x={name:"PlanCreate",components:{ValidationProvider:i.ValidationProvider,ValidationObserver:i.ValidationObserver,SectionTitle:d.a,MobileHeader:p.a,SelectInput:n.a,ButtonBase:c.a,ImageInput:s.a,PageHeader:f.a,FormLabel:l.a,required:v.a,InfoBox:m.a},data:function(){return{isLoading:!1,errorMessage:"",isError:!1,plan:{name:"",price:"",capacity:"",description:""}}},methods:{createPlan:function(){var a,e=this;return(a=o.a.mark((function a(){return o.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return a.next=2,e.$refs.createPlan.validate();case 2:if(a.sent){a.next=5;break}return a.abrupt("return");case 5:e.isLoading=!0,g.a.post("/api/plans/store",{attributes:e.plan}).then((function(a){u.a.$emit("toaster",{type:"success",message:e.$t("toaster.plan_created")}),e.$router.push({name:"PlanSettings",params:{id:a.data.data.id}})})).catch((function(a){422==a.response.status&&a.response.data.errors.storage_capacity&&e.$refs.createPlan.setErrors({"storage capacity":e.$t("errors.capacity_digit")}),500==a.response.status&&(e.isError=!0,e.errorMessage=a.response.data.message)})).finally((function(){e.isLoading=!1}));case 7:case"end":return a.stop()}}),a)})),function(){var e=this,t=arguments;return new Promise((function(r,o){var i=a.apply(e,t);function n(a){h(i,r,o,n,s,"next",a)}function s(a){h(i,r,o,n,s,"throw",a)}n(void 0)}))})()}}},y=(t("IpEv"),t("KHd+")),_=Object(y.a)(x,(function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("div",{attrs:{id:"single-page"}},[t("div",{staticClass:"small-width",attrs:{id:"page-content"}},[t("MobileHeader",{attrs:{title:a.$router.currentRoute.meta.title}}),a._v(" "),t("PageHeader",{attrs:{"can-back":!0,title:a.$router.currentRoute.meta.title}}),a._v(" "),t("div",{staticClass:"content-page"},[t("ValidationObserver",{ref:"createPlan",staticClass:"form block-form form-fixed-width",attrs:{tag:"form"},on:{submit:function(e){return e.preventDefault(),a.createPlan(e)}},scopedSlots:a._u([{key:"default",fn:function(e){e.invalid;return[t("div",{staticClass:"form-group"},[t("FormLabel",[a._v("\n                        "+a._s(a.$t("admin_page_plans.form.title_details"))+"\n                    ")]),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_page_plans.form.name"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Name",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(e){var r=e.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.plan.name,expression:"plan.name"}],class:{"is-error":r[0]},attrs:{placeholder:a.$t("admin_page_plans.form.name_plac"),type:"text"},domProps:{value:a.plan.name},on:{input:function(e){e.target.composing||a.$set(a.plan,"name",e.target.value)}}}),a._v(" "),r[0]?t("span",{staticClass:"error-message"},[a._v(a._s(r[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_page_plans.form.description"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Description"},scopedSlots:a._u([{key:"default",fn:function(e){var r=e.errors;return[t("textarea",{directives:[{name:"model",rawName:"v-model",value:a.plan.description,expression:"plan.description"}],class:{"is-error":r[0]},attrs:{placeholder:a.$t("admin_page_plans.form.description_plac")},domProps:{value:a.plan.description},on:{input:function(e){e.target.composing||a.$set(a.plan,"description",e.target.value)}}}),a._v(" "),r[0]?t("span",{staticClass:"error-message"},[a._v(a._s(r[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),t("FormLabel",[a._v("\n                        "+a._s(a.$t("admin_page_plans.form.title_pricing"))+"\n                    ")]),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_page_plans.form.price"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Plan price",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(e){var r=e.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.plan.price,expression:"plan.price"}],class:{"is-error":r[0]},attrs:{placeholder:a.$t("admin_page_plans.form.price_plac"),type:"number",step:"0.01",min:"1",max:"999999999999"},domProps:{value:a.plan.price},on:{input:function(e){e.target.composing||a.$set(a.plan,"price",e.target.value)}}}),a._v(" "),r[0]?t("span",{staticClass:"error-message"},[a._v(a._s(r[0]))]):a._e()]}}],null,!0)})],1),a._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[a._v(a._s(a.$t("admin_page_plans.form.storage"))+":")]),a._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Storage capacity",rules:"required"},scopedSlots:a._u([{key:"default",fn:function(e){var r=e.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:a.plan.capacity,expression:"plan.capacity"}],class:{"is-error":r[0]},attrs:{placeholder:a.$t("admin_page_plans.form.storage_plac"),type:"number",min:"1",max:"999999999"},domProps:{value:a.plan.capacity},on:{input:function(e){e.target.composing||a.$set(a.plan,"capacity",e.target.value)}}}),a._v(" "),r[0]?t("span",{staticClass:"error-message"},[a._v(a._s(r[0]))]):a._e()]}}],null,!0)}),a._v(" "),t("small",{staticClass:"input-help"},[a._v("\n                            "+a._s(a.$t("admin_page_plans.form.storage_helper"))+"\n                        ")])],1)],1),a._v(" "),a.isError?t("div",{staticClass:"form-group"},[t("InfoBox",{staticStyle:{"margin-top":"40px"},attrs:{type:"error"}},[t("p",[a._v(a._s(a.errorMessage))])])],1):a._e(),a._v(" "),t("div",{staticClass:"form-group"},[t("ButtonBase",{attrs:{disabled:a.isLoading,loading:a.isLoading,"button-style":"theme",type:"submit"}},[a._v("\n                        "+a._s(a.$t("admin_page_plans.create_plan_button"))+"\n                    ")])],1)]}}])})],1)],1)])}),[],!1,null,"5f65486a",null);e.default=_.exports},HQM2:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".form[data-v-5f65486a]{max-width:700px}.form.inline-form[data-v-5f65486a]{display:flex;position:relative;justify-content:center;margin:0 auto}.form.inline-form .input-wrapper[data-v-5f65486a]{position:relative}.form.inline-form .input-wrapper .error-message[data-v-5f65486a]{position:absolute;left:0;bottom:-25px}.form.block-form .wrapper-inline[data-v-5f65486a]{display:flex;margin:0 -15px}.form.block-form .wrapper-inline .block-wrapper[data-v-5f65486a]{width:100%;padding:0 15px}.form.block-form .block-wrapper[data-v-5f65486a]{margin-bottom:32px}.form.block-form .block-wrapper label[data-v-5f65486a]{font-size:.875em;color:rgba(27,37,57,.8);font-weight:700;display:block;margin-bottom:7px;text-align:left}.form.block-form .block-wrapper[data-v-5f65486a]:last-child{margin-bottom:0}.form.block-form .button[data-v-5f65486a]{margin-top:50px}.form .inline-wrapper[data-v-5f65486a]{display:flex;align-items:center;justify-content:space-between}.form .inline-wrapper .switch-label .input-help[data-v-5f65486a]{padding-top:0}.form .inline-wrapper .switch-label .input-label[data-v-5f65486a]{font-weight:700;color:#1b2539;font-size:1em;margin-bottom:5px}.form .input-help[data-v-5f65486a]{font-size:.75em;color:rgba(27,37,57,.7);line-height:1.35;padding-top:10px;display:block}.single-line-form[data-v-5f65486a]{display:flex}.single-line-form .submit-button[data-v-5f65486a]{margin-left:20px}.error-message[data-v-5f65486a]{font-size:.875em;color:#fd397a;padding-top:5px;display:block;text-align:left}textarea[data-v-5f65486a]{width:100%}input[type=email][data-v-5f65486a],input[type=number][data-v-5f65486a],input[type=password][data-v-5f65486a],input[type=text][data-v-5f65486a],textarea[data-v-5f65486a]{border:1px solid transparent;transition:all .15s ease;font-size:1em;border-radius:8px;padding:13px 20px;-webkit-appearance:none;-moz-appearance:none;appearance:none;font-weight:700;outline:0;width:100%;box-shadow:0 1px 5px rgba(0,0,0,.12)}input[type=email].is-error[data-v-5f65486a],input[type=number].is-error[data-v-5f65486a],input[type=password].is-error[data-v-5f65486a],input[type=text].is-error[data-v-5f65486a],textarea.is-error[data-v-5f65486a]{border-color:#fd397a;box-shadow:0 1px 5px rgba(253,57,122,.3)}input[type=email][data-v-5f65486a]::-moz-placeholder,input[type=number][data-v-5f65486a]::-moz-placeholder,input[type=password][data-v-5f65486a]::-moz-placeholder,input[type=text][data-v-5f65486a]::-moz-placeholder,textarea[data-v-5f65486a]::-moz-placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-5f65486a]:-ms-input-placeholder,input[type=number][data-v-5f65486a]:-ms-input-placeholder,input[type=password][data-v-5f65486a]:-ms-input-placeholder,input[type=text][data-v-5f65486a]:-ms-input-placeholder,textarea[data-v-5f65486a]:-ms-input-placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-5f65486a]::placeholder,input[type=number][data-v-5f65486a]::placeholder,input[type=password][data-v-5f65486a]::placeholder,input[type=text][data-v-5f65486a]::placeholder,textarea[data-v-5f65486a]::placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-5f65486a]:focus,input[type=number][data-v-5f65486a]:focus,input[type=password][data-v-5f65486a]:focus,input[type=text][data-v-5f65486a]:focus,textarea[data-v-5f65486a]:focus{border-color:#00bc7e;box-shadow:0 1px 5px rgba(0,188,126,.3)}input[type=email][disabled][data-v-5f65486a],input[type=number][disabled][data-v-5f65486a],input[type=password][disabled][data-v-5f65486a],input[type=text][disabled][data-v-5f65486a],textarea[disabled][data-v-5f65486a]{background:#fff;color:rgba(27,37,57,.8);-webkit-text-fill-color:rgba(27,37,57,.8);opacity:1;cursor:not-allowed}.additional-link[data-v-5f65486a]{font-size:1em;margin-top:50px;display:block;color:#1b2539}.additional-link a[data-v-5f65486a],.additional-link b[data-v-5f65486a]{color:#00bc7e;cursor:pointer}.additional-link a[data-v-5f65486a]:hover,.additional-link b[data-v-5f65486a]:hover{text-decoration:underline}@media only screen and (max-width:1024px){.form[data-v-5f65486a]{max-width:100%}}@media only screen and (max-width:960px){.form .button[data-v-5f65486a]{margin-top:20px;width:100%;margin-left:0;margin-right:0}.form input[data-v-5f65486a],.form textarea[data-v-5f65486a]{width:100%;min-width:100%}.form.block-form .block-wrapper[data-v-5f65486a]{display:block}.form.block-form .block-wrapper label[data-v-5f65486a]{width:100%;padding-right:0;display:block;margin-bottom:7px;text-align:left!important;font-size:.875em;padding-top:0}.form.block-form .button[data-v-5f65486a]{margin-top:25px;margin-left:0;margin-right:0}.form.inline-form[data-v-5f65486a]{display:block}.form.inline-form .input-wrapper .error-message[data-v-5f65486a]{position:relative;bottom:0}.form .button[data-v-5f65486a]{padding:14px 32px}.single-line-form[data-v-5f65486a]{display:block}.single-line-form .submit-button[data-v-5f65486a]{margin-left:0;margin-top:20px;width:100%}input[type=email][data-v-5f65486a],input[type=number][data-v-5f65486a],input[type=password][data-v-5f65486a],input[type=text][data-v-5f65486a],textarea[data-v-5f65486a]{padding:14px 20px}}@media only screen and (max-width:690px){.form.block-form .wrapper-inline[data-v-5f65486a]{display:block}}@media (prefers-color-scheme:dark){.form .input-help[data-v-5f65486a]{color:#7d858c}.form.block-form .block-wrapper label[data-v-5f65486a],.form .inline-wrapper .switch-label .input-label[data-v-5f65486a]{color:#bec6cf}input[type=email][data-v-5f65486a],input[type=number][data-v-5f65486a],input[type=password][data-v-5f65486a],input[type=text][data-v-5f65486a],textarea[data-v-5f65486a]{border-color:#1e2024;background:#1e2024;color:#bec6cf}input[type=email][data-v-5f65486a]::-moz-placeholder,input[type=number][data-v-5f65486a]::-moz-placeholder,input[type=password][data-v-5f65486a]::-moz-placeholder,input[type=text][data-v-5f65486a]::-moz-placeholder,textarea[data-v-5f65486a]::-moz-placeholder{color:#7d858c}input[type=email][data-v-5f65486a]:-ms-input-placeholder,input[type=number][data-v-5f65486a]:-ms-input-placeholder,input[type=password][data-v-5f65486a]:-ms-input-placeholder,input[type=text][data-v-5f65486a]:-ms-input-placeholder,textarea[data-v-5f65486a]:-ms-input-placeholder{color:#7d858c}input[type=email][data-v-5f65486a]::placeholder,input[type=number][data-v-5f65486a]::placeholder,input[type=password][data-v-5f65486a]::placeholder,input[type=text][data-v-5f65486a]::placeholder,textarea[data-v-5f65486a]::placeholder{color:#7d858c}input[type=email][disabled][data-v-5f65486a],input[type=number][disabled][data-v-5f65486a],input[type=password][disabled][data-v-5f65486a],input[type=text][disabled][data-v-5f65486a],textarea[disabled][data-v-5f65486a]{background:#1e2024;color:rgba(125,133,140,.8);-webkit-text-fill-color:rgba(125,133,140,.8)}}",""])},IS7u:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".dropzone[data-v-eb0cae00]{border:1px dashed #a1abc2;border-radius:8px;position:relative;text-align:center;display:flex;align-items:center;min-height:175px}.dropzone.is-error[data-v-eb0cae00]{border:2px dashed rgba(253,57,122,.3)}.dropzone.is-error .dropzone-title[data-v-eb0cae00]{color:#fd397a}.dropzone.is-error .icon-upload circle[data-v-eb0cae00],.dropzone.is-error .icon-upload polyline[data-v-eb0cae00],.dropzone.is-error .icon-upload rect[data-v-eb0cae00]{stroke:#fd397a}.dropzone input[type=file][data-v-eb0cae00]{opacity:0;position:absolute;top:0;left:0;right:0;bottom:0;z-index:1;width:100%;cursor:pointer}.dropzone .image-preview[data-v-eb0cae00]{position:absolute;width:100%;height:100%;-o-object-fit:contain;object-fit:contain;left:0;padding:25px;display:block}.dropzone .image-preview.fit-image[data-v-eb0cae00]{-o-object-fit:cover;object-fit:cover;border-radius:12px;overflow:hidden}.dropzone .dropzone-message[data-v-eb0cae00]{padding:50px 0;width:100%}.dropzone .dropzone-message .icon-upload circle[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload polyline[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload rect[data-v-eb0cae00]{stroke:#00bc7e}.dropzone .dropzone-message .dropzone-title[data-v-eb0cae00]{font-size:1em;font-weight:700;display:block}.dropzone .dropzone-message .dropzone-description[data-v-eb0cae00]{color:rgba(27,37,57,.7);font-size:.75em}.dropzone .reset-image[data-v-eb0cae00]{z-index:2;background:#fff;border-radius:50px;display:block;position:absolute;right:0;top:0;cursor:pointer;transform:translateY(-50%) translateX(50%);padding:0 4px;box-shadow:0 1px 5px rgba(0,0,0,.12)}.dropzone .reset-image .close-icon[data-v-eb0cae00]{vertical-align:middle}.dropzone .reset-image .close-icon line path[data-v-eb0cae00]{fill:#1b2539}@media (prefers-color-scheme:dark){.dropzone[data-v-eb0cae00]{border-color:hsla(0,0%,100%,.2)}.dropzone .dropzone-message .icon-upload line[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload path[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload polyline[data-v-eb0cae00]{stroke:#00bc7e}.dropzone .dropzone-message .dropzone-description[data-v-eb0cae00]{color:#7d858c}}",""])},Idvm:function(a,e,t){var r=t("lig4");"string"==typeof r&&(r=[[a.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(r,o);r.locals&&(a.exports=r.locals)},IpEv:function(a,e,t){"use strict";var r=t("ehn2");t.n(r).a},KPNK:function(a,e,t){var r=t("3Idf");"string"==typeof r&&(r=[[a.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(r,o);r.locals&&(a.exports=r.locals)},KnjL:function(a,e,t){"use strict";var r={name:"InfoBox",props:["type"]},o=(t("7OGm"),t("KHd+")),i=Object(o.a)(r,(function(){var a=this.$createElement;return(this._self._c||a)("div",{staticClass:"info-box",class:this.type},[this._t("default")],2)}),[],!1,null,"bf43be5e",null);e.a=i.exports},THmQ:function(a,e,t){"use strict";var r={name:"SectionTitle"},o=(t("92Jy"),t("KHd+")),i=Object(o.a)(r,(function(){var a=this.$createElement;return(this._self._c||a)("b",{staticClass:"text-label"},[this._t("default")],2)}),[],!1,null,"69d97df2",null);e.a=i.exports},Xk6H:function(a,e,t){"use strict";var r=t("Idvm");t.n(r).a},"bN/l":function(a,e,t){var r=t("IS7u");"string"==typeof r&&(r=[[a.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(r,o);r.locals&&(a.exports=r.locals)},eZ9V:function(a,e,t){"use strict";var r={name:"FormLabel",components:{Edit2Icon:t("CjXH").n}},o=(t("Xk6H"),t("KHd+")),i=Object(o.a)(r,(function(){var a=this.$createElement,e=this._self._c||a;return e("div",{staticClass:"form-label"},[e("edit-2-icon",{staticClass:"icon",attrs:{size:"22"}}),this._v(" "),e("b",{staticClass:"label"},[this._t("default")],2)],1)}),[],!1,null,"c1157a8e",null);e.a=i.exports},ehn2:function(a,e,t){var r=t("HQM2");"string"==typeof r&&(r=[[a.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(r,o);r.locals&&(a.exports=r.locals)},lW02:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".text-label[data-v-69d97df2]{font-size:.75em;color:#afafaf;font-weight:700;display:block;margin-bottom:20px}@media (prefers-color-scheme:dark){.text-label[data-v-69d97df2]{color:#00bc7e}}",""])},lig4:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".form-label[data-v-c1157a8e]{display:flex;align-items:center;margin-bottom:30px}.form-label .icon[data-v-c1157a8e]{margin-right:10px}.form-label .icon path[data-v-c1157a8e]{stroke:#00bc7e}.form-label .label[data-v-c1157a8e]{font-size:1.125em;font-weight:700}@media (prefers-color-scheme:dark){.form-label .label[data-v-c1157a8e]{color:#bec6cf}}",""])},qf9i:function(a,e,t){"use strict";var r=t("KPNK");t.n(r).a},qphJ:function(a,e,t){var r=t("lW02");"string"==typeof r&&(r=[[a.i,r,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(r,o);r.locals&&(a.exports=r.locals)},w9z4:function(a,e,t){"use strict";var r=t("bN/l");t.n(r).a},ydEr:function(a,e,t){(a.exports=t("I1BE")(!1)).push([a.i,".info-box[data-v-bf43be5e]{padding:20px;border-radius:8px;margin-bottom:32px;background:#f4f5f6;text-align:left}.info-box.error[data-v-bf43be5e]{background:rgba(253,57,122,.1)}.info-box.error a[data-v-bf43be5e],.info-box.error p[data-v-bf43be5e]{color:#fd397a}.info-box.error a[data-v-bf43be5e]{text-decoration:underline}.info-box p[data-v-bf43be5e]{font-size:.9375em;line-height:1.6;word-break:break-word;font-weight:600}.info-box p[data-v-bf43be5e] a{color:#00bc7e}.info-box a[data-v-bf43be5e],.info-box b[data-v-bf43be5e]{font-weight:700;color:#00bc7e}.info-box a[data-v-bf43be5e]{font-size:.9375em;line-height:1.6}.info-box ul[data-v-bf43be5e]{margin-top:15px}.info-box ul[data-v-bf43be5e],.info-box ul li[data-v-bf43be5e],.info-box ul li a[data-v-bf43be5e]{display:block}@media only screen and (max-width:690px){.info-box[data-v-bf43be5e]{padding:15px}}@media (prefers-color-scheme:dark){.info-box[data-v-bf43be5e]{background:#1e2024}.info-box p[data-v-bf43be5e],.info-box ul li[data-v-bf43be5e]{color:#bec6cf}}",""])}}]);