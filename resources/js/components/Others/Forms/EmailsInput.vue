<template>
    <div class="wrapper">
        <label class="input-label">{{ label }}:</label>
        <div class="input-wrapper" :class="{'is-error' : isError}" @click="$refs.input.focus()">
            <div class="email-list" v-for="(email, index) in emails" :key="index">
                <span> 
                    <p>{{email}} </p>
                    <x-icon @click="removeEmail(index)" class="icon" size="14" /> 
                </span>
            </div>
            <input @keydown.delete=removeLastEmail($event) 
                    @keyup="handleEmail()" 
                    v-model="singleEmail" 
                    class="emails" 
                    :placeholder="placeHolder" 
                    :size="inputSize"
                    autocomplete="new-password"
                    ref="input" />
        </div>
        <span class="error-message" v-if="isError">{{ isError }}</span>
    </div>
</template>

<script>
    import {XIcon } from 'vue-feather-icons'
    import {events} from '@/bus'
    export default {
        name: "EmailsInput",
        components: {XIcon},
        props: ["isError", 'label'],
        computed: {
            placeHolder() {
                if(! this.emails.length)
                    return this.$t('shared_form.email_placeholder')
            },
            inputSize() {
                if(this.singleEmail !== undefined || this.singleEmail !== undefined ) {
                    if(this.singleEmail === "" && this.emails.length > 0) {
                        return 1
                    }else {
                        return this.singleEmail.length
                    }
                }
            },
        },
        data () {
            return {
                emails: [],
                singleEmail: undefined
            }
        },
        methods: {
            removeEmail (email) {

                // Remove email forom array of emails 
                this.emails.shift(email)
            },
            removeLastEmail(event) {

                  // If is input empty and presse backspace remove last email from array
                if(event.code === 'Backspace' && this.singleEmail === '' )
                    this.emails.pop()
            },
            handleEmail() {

                if(this.singleEmail.length > 0) {
                    // Get index of @ and last dot 
                    let lastDot = this.singleEmail.lastIndexOf('.')
                    let at = this.singleEmail.indexOf('@')

                    // Check if is after @ some dot, if email have @ anf if dont have more like one 
                    if(lastDot < at || at === -1 || this.singleEmail.match(/@/g).length > 1 ) return
                
                    
                    // After come or backspace push the single email to array or emails
                    if(this.singleEmail.includes(',') || this.singleEmail.includes(' ') ) {
                        
                        let email = this.singleEmail.replace(/[","," "]/, "")

                        this.singleEmail = ""

                        // Push single email to aray of emails
                        this.emails.push(email)

                        events.$emit('emailsInputValues', this.emails)
                    }
                }
            }
        },
        created () {
           this.$nextTick(() => {
                    this.$refs.input.focus()
                })
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/vue-file-manager/_inapp-forms.scss";
    @import '@assets/vue-file-manager/_forms';

    .wrapper {
        margin-bottom: 20px;
    }

    .input-label {
        @include font-size(14);
        font-weight: 700;
        margin-bottom: 8px;
    }

    .input-wrapper   {
        background: white;
        max-width: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        min-height: 50px;
        border-radius: 8px;
        padding: 6px 20px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);
        cursor: text;

        &.is-error {
            border: 1px solid $danger;
            box-shadow: 0 0 7px rgba($danger, 0.3);
        }

        &:focus-within{
            border:1px solid $theme;
            box-shadow: 0 1px 5px rgba($theme, 0.3);
        }
        .email-list {
            white-space: nowrap;
            margin: 4px 0px;

            span {
                display: flex;
                padding: 5px;
                background: rgba($theme, .1); 
                border-radius: 8px;
                margin-right: 5px;
                align-items: center;
                p {
                    color: $theme;
                    font-weight: 700;
                    @include font-size(16);
                }
                .icon {
                    cursor: pointer;
                    margin: 0px 4px;
                }
            }
        }
        .emails {
            width: auto;
            height: 32px ;
            border: none ;
            font-weight: 700;
            @include font-size(16);
            margin: 6px 0px;

            &::placeholder {
                color:rgba($text-muted, .5)
            }
        }
    }

     @media (prefers-color-scheme: dark) {
        .input-wrapper {
            background: $dark_mode_foreground;
            .emails {
                background: $dark_mode_foreground;
                color: $dark_mode_text_primary;

                &::placeholder {
                color:$dark_mode_text_secondary;
            }
            }
        }
    }
    


</style>
