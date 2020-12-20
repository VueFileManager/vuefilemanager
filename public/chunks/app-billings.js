(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{"+Pqb":function(e,a,t){"use strict";var i={name:"ProgressBar",props:["progress"]},o=(t("woIv"),t("KHd+")),r=Object(o.a)(i,(function(){var e=this.$createElement,a=this._self._c||e;return a("div",{staticClass:"progress-bar"},[a("span",{style:{width:this.progress+"%"}})])}),[],!1,null,"f372b280",null);a.a=r.exports},"+t0u":function(e,a,t){var i=t("QO4y");"string"==typeof i&&(i=[[e.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(e.exports=i.locals)},"13Td":function(e,a,t){"use strict";var i={name:"SetupBox",props:["title","description","theme"]},o=(t("jS71"),t("KHd+")),r=Object(o.a)(i,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",{staticClass:"setup-box",class:e.theme},[t("b",{staticClass:"title"},[e._v(e._s(e.title))]),e._v(" "),t("p",{staticClass:"description"},[e._v(e._s(e.description))]),e._v(" "),e._t("default")],2)}),[],!1,null,"664a78dc",null);a.a=r.exports},"5pbA":function(e,a,t){"use strict";var i=t("+t0u");t.n(i).a},"6gqG":function(e,a,t){var i=t("ydEr");"string"==typeof i&&(i=[[e.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(e.exports=i.locals)},"7OGm":function(e,a,t){"use strict";var i=t("6gqG");t.n(i).a},"D+dh":function(e,a,t){"use strict";var i=t("CjXH"),o={name:"ImageInput",props:["image","error"],components:{ImageIcon:i.B,XIcon:i.db},data:function(){return{imagePreview:void 0}},computed:{isData:function(){return void 0!==this.imagePreview&&""!==this.imagePreview}},methods:{resetImage:function(){this.imagePreview=void 0,this.$emit("input",void 0)},showImagePreview:function(e){var a=this,t=e.target.files[0].name,i=t.substring(t.lastIndexOf(".")+1).toLowerCase();if(["png","jpg","jpeg","svg"].includes(i)){var o=e.target.files[0],r=new FileReader;r.onload=function(){return a.imagePreview=r.result},r.readAsDataURL(o),this.$emit("input",e.target.files[0])}else alert(this.$t("validation_errors.wrong_image"))}},created:function(){this.image&&(this.imagePreview=this.image)}},r=(t("w9z4"),t("KHd+")),n=Object(r.a)(o,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("div",{staticClass:"dropzone",class:{"is-error":e.error}},[e.imagePreview?t("div",{staticClass:"reset-image",on:{click:e.resetImage}},[t("x-icon",{staticClass:"close-icon",attrs:{size:"14"}})],1):e._e(),e._v(" "),t("input",{ref:"file",staticClass:"dummy",attrs:{type:"file"},on:{change:function(a){return e.showImagePreview(a)}}}),e._v(" "),e.imagePreview?t("img",{ref:"image",staticClass:"image-preview",attrs:{src:e.imagePreview}}):e._e(),e._v(" "),t("div",{directives:[{name:"show",rawName:"v-show",value:!e.isData,expression:"! isData"}],staticClass:"dropzone-message"},[t("image-icon",{staticClass:"icon-upload",attrs:{size:"28"}}),e._v(" "),t("span",{staticClass:"dropzone-title"},[e._v("\n            "+e._s(e.$t("input_image.title"))+"\n        ")]),e._v(" "),t("span",{staticClass:"dropzone-description"},[e._v("\n            "+e._s(e.$t("input_image.supported"))+"\n        ")])],1)])}),[],!1,null,"eb0cae00",null);a.a=n.exports},"F12+":function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".progress-bar[data-v-f372b280]{width:100%;height:5px;background:#f4f5f6;margin-top:6px;border-radius:10px}.progress-bar span[data-v-f372b280]{background:#00bc7e;display:block;height:100%;border-radius:10px;max-width:100%}@media (prefers-color-scheme:dark){.progress-bar[data-v-f372b280]{background:#1e2024}}@media only screen and (min-width:680px) and (prefers-color-scheme:dark){.progress-bar[data-v-f372b280]{background:#1e2024}}",""])},FEcZ:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".setup-box[data-v-664a78dc]{padding:20px;border-radius:8px;margin-bottom:30px}.setup-box .title[data-v-664a78dc]{font-size:1.3125em;margin-bottom:5px;display:block;font-weight:700}.setup-box .description[data-v-664a78dc]{font-size:.9375em;line-height:1.5;margin-bottom:20px}.setup-box.base[data-v-664a78dc],.setup-box.danger[data-v-664a78dc]{background:#f4f5f6}.setup-box.danger .title[data-v-664a78dc]{color:#fd397a}.setup-box[data-v-664a78dc] .input-area,.setup-box[data-v-664a78dc] input .input-area,.setup-box[data-v-664a78dc] input[type=number],.setup-box[data-v-664a78dc] input[type=text]{background:#fff}.setup-box[data-v-664a78dc] .form{margin-top:20px}.setup-box[data-v-664a78dc] .form.block-form{max-width:450px}.setup-box[data-v-664a78dc] .form.block-form .single-line-form{display:flex}.setup-box[data-v-664a78dc] .form.block-form .single-line-form .submit-button{margin-left:20px}@media only screen and (max-width:960px){.setup-box[data-v-664a78dc] .form.block-form{max-width:100%}.setup-box[data-v-664a78dc] .form input{min-width:0}}@media only screen and (max-width:690px){.setup-box[data-v-664a78dc]{padding:15px}.setup-box .title[data-v-664a78dc]{font-size:1.0625em;margin-bottom:10px}.setup-box .description[data-v-664a78dc]{font-size:.875em}.setup-box[data-v-664a78dc] .form.block-form .single-line-form{display:block}.setup-box[data-v-664a78dc] .form.block-form .single-line-form .submit-button{margin-left:0;margin-top:10px}}@media (prefers-color-scheme:dark){.setup-box.base[data-v-664a78dc],.setup-box.danger[data-v-664a78dc]{background:#1e2024}.setup-box[data-v-664a78dc] .input-area,.setup-box[data-v-664a78dc] input .input-area,.setup-box[data-v-664a78dc] input[type=number],.setup-box[data-v-664a78dc] input[type=text]{background:#131414}}",""])},IS7u:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".dropzone[data-v-eb0cae00]{border:1px dashed #a1abc2;border-radius:8px;position:relative;text-align:center;display:flex;align-items:center;min-height:175px}.dropzone.is-error[data-v-eb0cae00]{border:2px dashed rgba(253,57,122,.3)}.dropzone.is-error .dropzone-title[data-v-eb0cae00]{color:#fd397a}.dropzone.is-error .icon-upload circle[data-v-eb0cae00],.dropzone.is-error .icon-upload polyline[data-v-eb0cae00],.dropzone.is-error .icon-upload rect[data-v-eb0cae00]{stroke:#fd397a}.dropzone input[type=file][data-v-eb0cae00]{opacity:0;position:absolute;top:0;left:0;right:0;bottom:0;z-index:1;width:100%;cursor:pointer}.dropzone .image-preview[data-v-eb0cae00]{position:absolute;width:100%;height:100%;-o-object-fit:contain;object-fit:contain;left:0;padding:25px;display:block}.dropzone .image-preview.fit-image[data-v-eb0cae00]{-o-object-fit:cover;object-fit:cover;border-radius:12px;overflow:hidden}.dropzone .dropzone-message[data-v-eb0cae00]{padding:50px 0;width:100%}.dropzone .dropzone-message .icon-upload circle[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload polyline[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload rect[data-v-eb0cae00]{stroke:#00bc7e}.dropzone .dropzone-message .dropzone-title[data-v-eb0cae00]{font-size:1em;font-weight:700;display:block}.dropzone .dropzone-message .dropzone-description[data-v-eb0cae00]{color:rgba(27,37,57,.7);font-size:.75em}.dropzone .reset-image[data-v-eb0cae00]{z-index:2;background:#fff;border-radius:50px;display:block;position:absolute;right:0;top:0;cursor:pointer;transform:translateY(-50%) translateX(50%);padding:0 4px;box-shadow:0 1px 5px rgba(0,0,0,.12)}.dropzone .reset-image .close-icon[data-v-eb0cae00]{vertical-align:middle}.dropzone .reset-image .close-icon line path[data-v-eb0cae00]{fill:#1b2539}@media (prefers-color-scheme:dark){.dropzone[data-v-eb0cae00]{border-color:hsla(0,0%,100%,.2)}.dropzone .dropzone-message .icon-upload line[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload path[data-v-eb0cae00],.dropzone .dropzone-message .icon-upload polyline[data-v-eb0cae00]{stroke:#00bc7e}.dropzone .dropzone-message .dropzone-description[data-v-eb0cae00]{color:#7d858c}}",""])},Idvm:function(e,a,t){var i=t("lig4");"string"==typeof i&&(i=[[e.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(e.exports=i.locals)},KnjL:function(e,a,t){"use strict";var i={name:"InfoBox",props:["type"]},o=(t("7OGm"),t("KHd+")),r=Object(o.a)(i,(function(){var e=this.$createElement;return(this._self._c||e)("div",{staticClass:"info-box",class:this.type},[this._t("default")],2)}),[],!1,null,"bf43be5e",null);a.a=r.exports},LE5O:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".detail-storage-item[data-v-56af1b6e]{margin-bottom:35px}.detail-storage-item.disk .icon circle[data-v-56af1b6e],.detail-storage-item.disk .icon line[data-v-56af1b6e],.detail-storage-item.disk .icon path[data-v-56af1b6e],.detail-storage-item.disk .icon polygon[data-v-56af1b6e],.detail-storage-item.disk .icon polyline[data-v-56af1b6e],.detail-storage-item.disk .icon rect[data-v-56af1b6e]{stroke:#1b2539}.detail-storage-item.disk .storage-progress[data-v-56af1b6e] span{background:#1b2539}.detail-storage-item.images .icon circle[data-v-56af1b6e],.detail-storage-item.images .icon line[data-v-56af1b6e],.detail-storage-item.images .icon path[data-v-56af1b6e],.detail-storage-item.images .icon polygon[data-v-56af1b6e],.detail-storage-item.images .icon polyline[data-v-56af1b6e],.detail-storage-item.images .icon rect[data-v-56af1b6e]{stroke:#9d66fe}.detail-storage-item.images .storage-progress[data-v-56af1b6e] span{background:#9d66fe}.detail-storage-item.videos .icon circle[data-v-56af1b6e],.detail-storage-item.videos .icon line[data-v-56af1b6e],.detail-storage-item.videos .icon path[data-v-56af1b6e],.detail-storage-item.videos .icon polygon[data-v-56af1b6e],.detail-storage-item.videos .icon polyline[data-v-56af1b6e],.detail-storage-item.videos .icon rect[data-v-56af1b6e]{stroke:#ffbd2d}.detail-storage-item.videos .storage-progress[data-v-56af1b6e] span{background:#ffbd2d}.detail-storage-item.audios .icon circle[data-v-56af1b6e],.detail-storage-item.audios .icon line[data-v-56af1b6e],.detail-storage-item.audios .icon path[data-v-56af1b6e],.detail-storage-item.audios .icon polygon[data-v-56af1b6e],.detail-storage-item.audios .icon polyline[data-v-56af1b6e],.detail-storage-item.audios .icon rect[data-v-56af1b6e]{stroke:#fe66a1}.detail-storage-item.audios .storage-progress[data-v-56af1b6e] span{background:#fe66a1}.detail-storage-item.documents .icon circle[data-v-56af1b6e],.detail-storage-item.documents .icon line[data-v-56af1b6e],.detail-storage-item.documents .icon path[data-v-56af1b6e],.detail-storage-item.documents .icon polygon[data-v-56af1b6e],.detail-storage-item.documents .icon polyline[data-v-56af1b6e],.detail-storage-item.documents .icon rect[data-v-56af1b6e]{stroke:#fe6057}.detail-storage-item.documents .storage-progress[data-v-56af1b6e] span{background:#fe6057}.detail-storage-item.others .icon circle[data-v-56af1b6e],.detail-storage-item.others .icon line[data-v-56af1b6e],.detail-storage-item.others .icon path[data-v-56af1b6e],.detail-storage-item.others .icon polygon[data-v-56af1b6e],.detail-storage-item.others .icon polyline[data-v-56af1b6e],.detail-storage-item.others .icon rect[data-v-56af1b6e]{stroke:#1b2539}.detail-storage-item.others .storage-progress[data-v-56af1b6e] span{background:#1b2539}.header-storage-item[data-v-56af1b6e]{display:flex;align-items:flex-start;margin-bottom:10px}.header-storage-item .icon[data-v-56af1b6e]{width:35px}.header-storage-item .type[data-v-56af1b6e]{font-size:.9375em;color:#1b2539}.header-storage-item .total-size[data-v-56af1b6e]{font-size:.625em;display:block;color:rgba(27,37,57,.7)}@media (prefers-color-scheme:dark){.header-storage-item .type[data-v-56af1b6e]{color:#bec6cf}.header-storage-item .total-size[data-v-56af1b6e]{color:#7d858c}.detail-storage-item.disk .icon circle[data-v-56af1b6e],.detail-storage-item.disk .icon line[data-v-56af1b6e],.detail-storage-item.disk .icon path[data-v-56af1b6e],.detail-storage-item.disk .icon polygon[data-v-56af1b6e],.detail-storage-item.disk .icon polyline[data-v-56af1b6e],.detail-storage-item.disk .icon rect[data-v-56af1b6e],.detail-storage-item.others .icon circle[data-v-56af1b6e],.detail-storage-item.others .icon line[data-v-56af1b6e],.detail-storage-item.others .icon path[data-v-56af1b6e],.detail-storage-item.others .icon polygon[data-v-56af1b6e],.detail-storage-item.others .icon polyline[data-v-56af1b6e],.detail-storage-item.others .icon rect[data-v-56af1b6e]{stroke:#41454e}.detail-storage-item.disk .storage-progress[data-v-56af1b6e] span,.detail-storage-item.others .storage-progress[data-v-56af1b6e] span{background:#41454e}}",""])},QO4y:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,"",""])},VA79:function(e,a,t){var i=t("F12+");"string"==typeof i&&(i=[[e.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(e.exports=i.locals)},Xk6H:function(e,a,t){"use strict";var i=t("Idvm");t.n(i).a},b0xl:function(e,a,t){"use strict";var i=t("oyp5");t.n(i).a},"bN/l":function(e,a,t){var i=t("IS7u");"string"==typeof i&&(i=[[e.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(e.exports=i.locals)},eZ9V:function(e,a,t){"use strict";var i={name:"FormLabel",components:{Edit2Icon:t("CjXH").q}},o=(t("Xk6H"),t("KHd+")),r=Object(o.a)(i,(function(){var e=this.$createElement,a=this._self._c||e;return a("div",{staticClass:"form-label"},[a("edit-2-icon",{staticClass:"icon",attrs:{size:"22"}}),this._v(" "),a("b",{staticClass:"label"},[this._t("default")],2)],1)}),[],!1,null,"c1157a8e",null);a.a=r.exports},eaI4:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".form[data-v-24d9fd8e]{max-width:700px}.form.inline-form[data-v-24d9fd8e]{display:flex;position:relative;justify-content:center;margin:0 auto}.form.inline-form .input-wrapper[data-v-24d9fd8e]{position:relative}.form.inline-form .input-wrapper .error-message[data-v-24d9fd8e]{position:absolute;left:0;bottom:-25px}.form.block-form .wrapper-inline[data-v-24d9fd8e]{display:flex;margin:0 -15px}.form.block-form .wrapper-inline .block-wrapper[data-v-24d9fd8e]{width:100%;padding:0 15px}.form.block-form .block-wrapper[data-v-24d9fd8e]{margin-bottom:32px}.form.block-form .block-wrapper label[data-v-24d9fd8e]{font-size:.875em;color:rgba(27,37,57,.8);font-weight:700;display:block;margin-bottom:7px;text-align:left}.form.block-form .block-wrapper[data-v-24d9fd8e]:last-child{margin-bottom:0}.form.block-form .button[data-v-24d9fd8e]{margin-top:50px}.form .inline-wrapper[data-v-24d9fd8e]{display:flex;align-items:center;justify-content:space-between}.form .inline-wrapper .switch-label .input-help[data-v-24d9fd8e]{padding-top:0}.form .inline-wrapper .switch-label .input-label[data-v-24d9fd8e]{font-weight:700;color:#1b2539;font-size:1em;margin-bottom:5px}.form .input-help[data-v-24d9fd8e]{font-size:.75em;color:rgba(27,37,57,.7);line-height:1.35;padding-top:10px;display:block}.single-line-form[data-v-24d9fd8e]{display:flex}.single-line-form .submit-button[data-v-24d9fd8e]{margin-left:20px}.error-message[data-v-24d9fd8e]{font-size:.875em;color:#fd397a;padding-top:5px;display:block;text-align:left}textarea[data-v-24d9fd8e]{width:100%}input[type=email][data-v-24d9fd8e],input[type=number][data-v-24d9fd8e],input[type=password][data-v-24d9fd8e],input[type=text][data-v-24d9fd8e],textarea[data-v-24d9fd8e]{border:1px solid transparent;transition:all .15s ease;font-size:1em;border-radius:8px;padding:13px 20px;-webkit-appearance:none;-moz-appearance:none;appearance:none;font-weight:700;outline:0;width:100%;box-shadow:0 1px 5px rgba(0,0,0,.12)}input[type=email].is-error[data-v-24d9fd8e],input[type=number].is-error[data-v-24d9fd8e],input[type=password].is-error[data-v-24d9fd8e],input[type=text].is-error[data-v-24d9fd8e],textarea.is-error[data-v-24d9fd8e]{border-color:#fd397a;box-shadow:0 1px 5px rgba(253,57,122,.3)}input[type=email][data-v-24d9fd8e]::-moz-placeholder,input[type=number][data-v-24d9fd8e]::-moz-placeholder,input[type=password][data-v-24d9fd8e]::-moz-placeholder,input[type=text][data-v-24d9fd8e]::-moz-placeholder,textarea[data-v-24d9fd8e]::-moz-placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-24d9fd8e]:-ms-input-placeholder,input[type=number][data-v-24d9fd8e]:-ms-input-placeholder,input[type=password][data-v-24d9fd8e]:-ms-input-placeholder,input[type=text][data-v-24d9fd8e]:-ms-input-placeholder,textarea[data-v-24d9fd8e]:-ms-input-placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-24d9fd8e]::placeholder,input[type=number][data-v-24d9fd8e]::placeholder,input[type=password][data-v-24d9fd8e]::placeholder,input[type=text][data-v-24d9fd8e]::placeholder,textarea[data-v-24d9fd8e]::placeholder{color:rgba(27,37,57,.5);font-size:.9375em}input[type=email][data-v-24d9fd8e]:focus,input[type=number][data-v-24d9fd8e]:focus,input[type=password][data-v-24d9fd8e]:focus,input[type=text][data-v-24d9fd8e]:focus,textarea[data-v-24d9fd8e]:focus{border-color:#00bc7e;box-shadow:0 1px 5px rgba(0,188,126,.3)}input[type=email][disabled][data-v-24d9fd8e],input[type=number][disabled][data-v-24d9fd8e],input[type=password][disabled][data-v-24d9fd8e],input[type=text][disabled][data-v-24d9fd8e],textarea[disabled][data-v-24d9fd8e]{background:#fff;color:rgba(27,37,57,.8);-webkit-text-fill-color:rgba(27,37,57,.8);opacity:1;cursor:not-allowed}.additional-link[data-v-24d9fd8e]{font-size:1em;margin-top:50px;display:block;color:#1b2539}.additional-link a[data-v-24d9fd8e],.additional-link b[data-v-24d9fd8e]{color:#00bc7e;cursor:pointer}.additional-link a[data-v-24d9fd8e]:hover,.additional-link b[data-v-24d9fd8e]:hover{text-decoration:underline}@media only screen and (max-width:1024px){.form[data-v-24d9fd8e]{max-width:100%}}@media only screen and (max-width:960px){.form .button[data-v-24d9fd8e]{margin-top:20px;width:100%;margin-left:0;margin-right:0}.form input[data-v-24d9fd8e],.form textarea[data-v-24d9fd8e]{width:100%;min-width:100%}.form.block-form .block-wrapper[data-v-24d9fd8e]{display:block}.form.block-form .block-wrapper label[data-v-24d9fd8e]{width:100%;padding-right:0;display:block;margin-bottom:7px;text-align:left!important;font-size:.875em;padding-top:0}.form.block-form .button[data-v-24d9fd8e]{margin-top:25px;margin-left:0;margin-right:0}.form.inline-form[data-v-24d9fd8e]{display:block}.form.inline-form .input-wrapper .error-message[data-v-24d9fd8e]{position:relative;bottom:0}.form .button[data-v-24d9fd8e]{padding:14px 32px}.single-line-form[data-v-24d9fd8e]{display:block}.single-line-form .submit-button[data-v-24d9fd8e]{margin-left:0;margin-top:20px;width:100%}input[type=email][data-v-24d9fd8e],input[type=number][data-v-24d9fd8e],input[type=password][data-v-24d9fd8e],input[type=text][data-v-24d9fd8e],textarea[data-v-24d9fd8e]{padding:14px 20px}}@media only screen and (max-width:690px){.form.block-form .wrapper-inline[data-v-24d9fd8e]{display:block}}@media (prefers-color-scheme:dark){.form .input-help[data-v-24d9fd8e]{color:#7d858c}.form.block-form .block-wrapper label[data-v-24d9fd8e],.form .inline-wrapper .switch-label .input-label[data-v-24d9fd8e]{color:#bec6cf}input[type=email][data-v-24d9fd8e],input[type=number][data-v-24d9fd8e],input[type=password][data-v-24d9fd8e],input[type=text][data-v-24d9fd8e],textarea[data-v-24d9fd8e]{border-color:#1e2024;background:#1e2024;color:#bec6cf}input[type=email][data-v-24d9fd8e]::-moz-placeholder,input[type=number][data-v-24d9fd8e]::-moz-placeholder,input[type=password][data-v-24d9fd8e]::-moz-placeholder,input[type=text][data-v-24d9fd8e]::-moz-placeholder,textarea[data-v-24d9fd8e]::-moz-placeholder{color:#7d858c}input[type=email][data-v-24d9fd8e]:-ms-input-placeholder,input[type=number][data-v-24d9fd8e]:-ms-input-placeholder,input[type=password][data-v-24d9fd8e]:-ms-input-placeholder,input[type=text][data-v-24d9fd8e]:-ms-input-placeholder,textarea[data-v-24d9fd8e]:-ms-input-placeholder{color:#7d858c}input[type=email][data-v-24d9fd8e]::placeholder,input[type=number][data-v-24d9fd8e]::placeholder,input[type=password][data-v-24d9fd8e]::placeholder,input[type=text][data-v-24d9fd8e]::placeholder,textarea[data-v-24d9fd8e]::placeholder{color:#7d858c}input[type=email][disabled][data-v-24d9fd8e],input[type=number][disabled][data-v-24d9fd8e],input[type=password][disabled][data-v-24d9fd8e],input[type=text][disabled][data-v-24d9fd8e],textarea[disabled][data-v-24d9fd8e]{background:#1e2024;color:rgba(125,133,140,.8);-webkit-text-fill-color:rgba(125,133,140,.8)}}.block-form[data-v-24d9fd8e]{max-width:100%}",""])},gahf:function(e,a,t){"use strict";var i={name:"PageTabGroup"},o=(t("yI2c"),t("KHd+")),r=Object(o.a)(i,(function(){var e=this.$createElement;return(this._self._c||e)("div",{staticClass:"page-tab-group"},[this._t("default")],2)}),[],!1,null,"1bb470e4",null);a.a=r.exports},jS71:function(e,a,t){"use strict";var i=t("wsaA");t.n(i).a},l48x:function(e,a,t){"use strict";var i=t("ySns");t.n(i).a},lig4:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".form-label[data-v-c1157a8e]{display:flex;align-items:center;margin-bottom:30px}.form-label .icon[data-v-c1157a8e]{margin-right:10px}.form-label .icon path[data-v-c1157a8e]{stroke:#00bc7e}.form-label .label[data-v-c1157a8e]{font-size:1.125em;font-weight:700}@media (prefers-color-scheme:dark){.form-label .label[data-v-c1157a8e]{color:#bec6cf}}",""])},mYUh:function(e,a,t){"use strict";t.r(a);var i=t("A5+z"),o=t("xnZf"),r=t("gahf"),n=t("4TWA"),d=t("D+dh"),s=t("eZ9V"),l=t("Nv84"),p=t("13Td"),c=t("qefO"),b=t("KnjL"),m=t("TJPC"),f=t("vDqi"),u=t.n(f),g=t("L2JU");function v(e,a){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(e);a&&(i=i.filter((function(a){return Object.getOwnPropertyDescriptor(e,a).enumerable}))),t.push.apply(t,i)}return t}function _(e,a,t){return a in e?Object.defineProperty(e,a,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[a]=t,e}var x={name:"AppAppearance",components:{ValidationObserver:i.ValidationObserver,ValidationProvider:i.ValidationProvider,StorageItemDetail:o.a,PageTabGroup:r.a,SelectInput:n.a,ImageInput:d.a,ButtonBase:l.a,FormLabel:s.a,SetupBox:p.a,required:m.a,PageTab:c.a,InfoBox:b.a},computed:function(e){for(var a=1;a<arguments.length;a++){var t=null!=arguments[a]?arguments[a]:{};a%2?v(Object(t),!0).forEach((function(a){_(e,a,t[a])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(t)):v(Object(t)).forEach((function(a){Object.defineProperty(e,a,Object.getOwnPropertyDescriptor(t,a))}))}return e}({},Object(g.b)(["countries"])),data:function(){return{isLoading:!0,billingInformation:void 0}},mounted:function(){var e=this;u.a.get("/api/settings",{params:{column:"billing_phone_number|billing_postal_code|billing_vat_number|billing_address|billing_country|billing_state|billing_city|billing_name"}}).then((function(a){e.isLoading=!1,e.billingInformation={billing_phone_number:a.data.billing_phone_number,billing_postal_code:a.data.billing_postal_code,billing_vat_number:a.data.billing_vat_number,billing_address:a.data.billing_address,billing_country:a.data.billing_country,billing_state:a.data.billing_state,billing_city:a.data.billing_city,billing_name:a.data.billing_name}}))}},h=(t("l48x"),t("KHd+")),y=Object(h.a)(x,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("PageTab",{staticClass:"form-fixed-width",attrs:{"is-loading":e.isLoading}},[e.billingInformation?t("PageTabGroup",[t("div",{staticClass:"form block-form"},[t("FormLabel",[e._v(e._s(e.$t("admin_settings.billings.section_company")))]),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("admin_settings.billings.company_name"))+":")]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Name",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var i=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.billingInformation.billing_name,expression:"billingInformation.billing_name"}],class:{"is-error":i[0]},attrs:{placeholder:e.$t("admin_settings.billings.company_name_plac"),type:"text"},domProps:{value:e.billingInformation.billing_name},on:{input:[function(a){a.target.composing||e.$set(e.billingInformation,"billing_name",a.target.value)},function(a){return e.$updateText("/settings","billing_name",e.billingInformation.billing_name)}]}}),e._v(" "),i[0]?t("span",{staticClass:"error-message"},[e._v(e._s(i[0]))]):e._e()]}}],null,!1,2655941275)})],1),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("admin_settings.billings.vat"))+":")]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Vat Number",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var i=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.billingInformation.billing_vat_number,expression:"billingInformation.billing_vat_number"}],class:{"is-error":i[0]},attrs:{placeholder:e.$t("admin_settings.billings.vat_plac"),type:"text"},domProps:{value:e.billingInformation.billing_vat_number},on:{input:[function(a){a.target.composing||e.$set(e.billingInformation,"billing_vat_number",a.target.value)},function(a){return e.$updateText("/settings","billing_vat_number",e.billingInformation.billing_vat_number)}]}}),e._v(" "),i[0]?t("span",{staticClass:"error-message"},[e._v(e._s(i[0]))]):e._e()]}}],null,!1,1879464263)})],1),e._v(" "),t("FormLabel",{staticClass:"mt-70"},[e._v(e._s(e.$t("admin_settings.billings.section_billing")))]),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("admin_settings.billings.country"))+":")]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Country",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var i=a.errors;return[t("SelectInput",{attrs:{default:e.billingInformation.billing_country,options:e.countries,placeholder:e.$t("admin_settings.billings.country_plac"),isError:i[0]},on:{input:function(a){return e.$updateText("/settings","billing_country",e.billingInformation.billing_country)}},model:{value:e.billingInformation.billing_country,callback:function(a){e.$set(e.billingInformation,"billing_country",a)},expression:"billingInformation.billing_country"}}),e._v(" "),i[0]?t("span",{staticClass:"error-message"},[e._v(e._s(i[0]))]):e._e()]}}],null,!1,682383683)})],1),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("admin_settings.billings.address"))+":")]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Address",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var i=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.billingInformation.billing_address,expression:"billingInformation.billing_address"}],class:{"is-error":i[0]},attrs:{placeholder:e.$t("admin_settings.billings.address_plac"),type:"text"},domProps:{value:e.billingInformation.billing_address},on:{input:[function(a){a.target.composing||e.$set(e.billingInformation,"billing_address",a.target.value)},function(a){return e.$updateText("/settings","billing_address",e.billingInformation.billing_address)}]}}),e._v(" "),i[0]?t("span",{staticClass:"error-message"},[e._v(e._s(i[0]))]):e._e()]}}],null,!1,1499604818)})],1),e._v(" "),t("div",{staticClass:"wrapper-inline"},[t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("admin_settings.billings.city"))+":")]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing City",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var i=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.billingInformation.billing_city,expression:"billingInformation.billing_city"}],class:{"is-error":i[0]},attrs:{placeholder:e.$t("admin_settings.billings.city_plac"),type:"text"},domProps:{value:e.billingInformation.billing_city},on:{input:[function(a){a.target.composing||e.$set(e.billingInformation,"billing_city",a.target.value)},function(a){return e.$updateText("/settings","billing_city",e.billingInformation.billing_city)}]}}),e._v(" "),i[0]?t("span",{staticClass:"error-message"},[e._v(e._s(i[0]))]):e._e()]}}],null,!1,2593429539)})],1),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("admin_settings.billings.postal_code"))+":")]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Postal Code",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var i=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.billingInformation.billing_postal_code,expression:"billingInformation.billing_postal_code"}],class:{"is-error":i[0]},attrs:{placeholder:e.$t("admin_settings.billings.postal_code_plac"),type:"text"},domProps:{value:e.billingInformation.billing_postal_code},on:{input:[function(a){a.target.composing||e.$set(e.billingInformation,"billing_postal_code",a.target.value)},function(a){return e.$updateText("/settings","billing_postal_code",e.billingInformation.billing_postal_code)}]}}),e._v(" "),i[0]?t("span",{staticClass:"error-message"},[e._v(e._s(i[0]))]):e._e()]}}],null,!1,871160259)})],1)]),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("admin_settings.billings.state"))+":")]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing State",rules:"required"},scopedSlots:e._u([{key:"default",fn:function(a){var i=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.billingInformation.billing_state,expression:"billingInformation.billing_state"}],class:{"is-error":i[0]},attrs:{placeholder:e.$t("admin_settings.billings.state_plac"),type:"text"},domProps:{value:e.billingInformation.billing_state},on:{input:[function(a){a.target.composing||e.$set(e.billingInformation,"billing_state",a.target.value)},function(a){return e.$updateText("/settings","billing_state",e.billingInformation.billing_state)}]}}),e._v(" "),i[0]?t("span",{staticClass:"error-message"},[e._v(e._s(i[0]))]):e._e()]}}],null,!1,2085352051)})],1),e._v(" "),t("div",{staticClass:"block-wrapper"},[t("label",[e._v(e._s(e.$t("admin_settings.billings.phone_number"))+":")]),e._v(" "),t("ValidationProvider",{staticClass:"input-wrapper",attrs:{tag:"div",mode:"passive",name:"Billing Phone Number"},scopedSlots:e._u([{key:"default",fn:function(a){var i=a.errors;return[t("input",{directives:[{name:"model",rawName:"v-model",value:e.billingInformation.billing_phone_number,expression:"billingInformation.billing_phone_number"}],class:{"is-error":i[0]},attrs:{placeholder:e.$t("admin_settings.billings.phone_number_plac"),type:"text"},domProps:{value:e.billingInformation.billing_phone_number},on:{input:[function(a){a.target.composing||e.$set(e.billingInformation,"billing_phone_number",a.target.value)},function(a){return e.$updateText("/settings","billing_phone_number",e.billingInformation.billing_phone_number)}]}}),e._v(" "),i[0]?t("span",{staticClass:"error-message"},[e._v(e._s(i[0]))]):e._e()]}}],null,!1,1212048740)})],1)],1)]):e._e()],1)}),[],!1,null,"24d9fd8e",null);a.default=y.exports},oDxr:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".page-tab-group[data-v-1bb470e4]{margin-bottom:65px}",""])},oyp5:function(e,a,t){var i=t("LE5O");"string"==typeof i&&(i=[[e.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(e.exports=i.locals)},qefO:function(e,a,t){"use strict";var i={name:"PageTab",props:["isLoading"],components:{Spinner:t("zTYo").a}},o=(t("5pbA"),t("KHd+")),r=Object(o.a)(i,(function(){var e=this.$createElement,a=this._self._c||e;return a("div",{staticClass:"page-tab"},[a("div",{directives:[{name:"show",rawName:"v-show",value:this.isLoading,expression:"isLoading"}],attrs:{id:"loader"}},[a("Spinner")],1),this._v(" "),this._t("default")],2)}),[],!1,null,"4339da66",null);a.a=r.exports},w9z4:function(e,a,t){"use strict";var i=t("bN/l");t.n(i).a},woIv:function(e,a,t){"use strict";var i=t("VA79");t.n(i).a},wsaA:function(e,a,t){var i=t("FEcZ");"string"==typeof i&&(i=[[e.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(e.exports=i.locals)},xnZf:function(e,a,t){"use strict";var i=t("+Pqb"),o=t("CjXH"),r={name:"StorageItemDetail",props:["percentage","title","type","used"],components:{HardDriveIcon:o.z,FileTextIcon:o.v,ProgressBar:i.a,MusicIcon:o.L,VideoIcon:o.cb,ImageIcon:o.B,FileIcon:o.u}},n=(t("b0xl"),t("KHd+")),d=Object(n.a)(r,(function(){var e=this,a=e.$createElement,t=e._self._c||a;return t("article",{staticClass:"detail-storage-item",class:e.type},[t("div",{staticClass:"header-storage-item"},[t("div",{staticClass:"icon"},["images"==e.type?t("image-icon",{attrs:{size:"23"}}):e._e(),e._v(" "),"videos"==e.type?t("video-icon",{attrs:{size:"23"}}):e._e(),e._v(" "),"audios"==e.type?t("music-icon",{attrs:{size:"23"}}):e._e(),e._v(" "),"documents"==e.type?t("file-text-icon",{attrs:{size:"23"}}):e._e(),e._v(" "),"others"==e.type?t("file-icon",{attrs:{size:"23"}}):e._e(),e._v(" "),"disk"==e.type?t("hard-drive-icon",{attrs:{size:"23"}}):e._e()],1),e._v(" "),t("div",{staticClass:"title"},[t("b",{staticClass:"type"},[e._v(e._s(e.title))]),e._v(" "),t("span",{staticClass:"total-size"},[e._v(e._s(e.used))])])]),e._v(" "),t("ProgressBar",{staticClass:"storage-progress",attrs:{progress:e.percentage}})],1)}),[],!1,null,"56af1b6e",null);a.a=d.exports},yI2c:function(e,a,t){"use strict";var i=t("zlQ3");t.n(i).a},ySns:function(e,a,t){var i=t("eaI4");"string"==typeof i&&(i=[[e.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(e.exports=i.locals)},ydEr:function(e,a,t){(e.exports=t("I1BE")(!1)).push([e.i,".info-box[data-v-bf43be5e]{padding:20px;border-radius:8px;margin-bottom:32px;background:#f4f5f6;text-align:left}.info-box.error[data-v-bf43be5e]{background:rgba(253,57,122,.1)}.info-box.error a[data-v-bf43be5e],.info-box.error p[data-v-bf43be5e]{color:#fd397a}.info-box.error a[data-v-bf43be5e]{text-decoration:underline}.info-box p[data-v-bf43be5e]{font-size:.9375em;line-height:1.6;word-break:break-word;font-weight:600}.info-box p[data-v-bf43be5e] a{color:#00bc7e}.info-box a[data-v-bf43be5e],.info-box b[data-v-bf43be5e]{font-weight:700;color:#00bc7e}.info-box a[data-v-bf43be5e]{font-size:.9375em;line-height:1.6}.info-box ul[data-v-bf43be5e]{margin-top:15px}.info-box ul[data-v-bf43be5e],.info-box ul li[data-v-bf43be5e],.info-box ul li a[data-v-bf43be5e]{display:block}@media only screen and (max-width:690px){.info-box[data-v-bf43be5e]{padding:15px}}@media (prefers-color-scheme:dark){.info-box[data-v-bf43be5e]{background:#1e2024}.info-box p[data-v-bf43be5e],.info-box ul li[data-v-bf43be5e]{color:#bec6cf}}",""])},zlQ3:function(e,a,t){var i=t("oDxr");"string"==typeof i&&(i=[[e.i,i,""]]);var o={hmr:!0,transform:void 0,insertInto:void 0};t("aET+")(i,o);i.locals&&(e.exports=i.locals)}}]);