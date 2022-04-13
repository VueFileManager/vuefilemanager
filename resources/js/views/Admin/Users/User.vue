<template>
    <div>
        <div id="page-content" v-if="!isLoading">
            <!--Page Tab links-->
            <div class="card sticky top-0 z-10 pt-4 shadow-card" style="padding-bottom: 0">
                <!--User thumbnail-->
                <div class="mb-3 flex items-center">
                    <!--Image input for replace avatar-->
					<MemberAvatar class="shadow-lg rounded-xl" :size="64" :is-border="false" :member="user" />

                    <!--User name & email-->
                    <div class="ml-4">
                        <b class="text-md block font-bold sm:text-lg">
                            {{ user.data.relationships.settings.data.attributes.first_name }}
                            {{ user.data.relationships.settings.data.attributes.last_name }}

                            <ColorLabel color="purple">
                                {{ $t(user.data.attributes.role) }}
                            </ColorLabel>
                        </b>
                        <small class="block text-xs text-gray-600 sm:text-sm">
                            {{ user.data.attributes.email }}
                        </small>
                    </div>
                </div>

                <CardNavigation :pages="pages" class="-mx-1" />
            </div>

            <!--Router Content-->
            <router-view :user="user" @reload-user="fetchUser" />
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner />
        </div>
    </div>
</template>

<script>
import CardNavigation from '../../../components/UI/Others/CardNavigation'
import { UserIcon, HardDriveIcon, LockIcon, Trash2Icon, FileTextIcon, CreditCardIcon } from 'vue-feather-icons'
import MobileHeader from '../../../components/Mobile/MobileHeader'
import SectionTitle from '../../../components/UI/Labels/SectionTitle'
import ColorLabel from '../../../components/UI/Labels/ColorLabel'
import Spinner from '../../../components/UI/Others/Spinner'
import { events } from '../../../bus'
import { mapGetters } from 'vuex'
import axios from 'axios'
import MemberAvatar from "../../../components/UI/Others/MemberAvatar";

export default {
    name: 'Profile',
    components: {
		MemberAvatar,
        CardNavigation,
        CreditCardIcon,
        HardDriveIcon,
        SectionTitle,
        FileTextIcon,
        MobileHeader,
        ColorLabel,
        Trash2Icon,
        UserIcon,
        LockIcon,
        Spinner,
    },
    watch: {
        '$route.fullPath': function () {
            this.fetchUser()
        },
    },
    computed: {
        ...mapGetters(['config']),
        admin() {
            return this.$store.getters.user ? this.$store.getters.user : undefined
        },
        pages() {
            if (this.config.subscriptionType === 'none') {
                return [
                    {
                        title: this.$t('detail'),
                        route: 'UserDetail',
                    },
                    {
                        title: this.$t('storage'),
                        route: 'UserStorage',
                    },
                    {
                        title: this.$t('password'),
                        route: 'UserPassword',
                    },
                    {
                        title: this.$t('delete_account'),
                        route: 'UserDelete',
                    },
                ]
            }

            return [
                {
                    title: this.$t('detail'),
                    route: 'UserDetail',
                },
                {
                    title: this.$t('storage'),
                    route: 'UserStorage',
                },
                {
                    title: this.$t('billing'),
                    route: 'UserSubscription',
                },
                {
                    title: this.$t('password'),
                    route: 'UserPassword',
                },
                {
                    title: this.$t('delete_account'),
                    route: 'UserDelete',
                },
            ]
        },
    },
    data() {
        return {
            isLoading: true,
            user: undefined,
        }
    },
    methods: {
        fetchUser() {
            axios.get('/api/admin/users/' + this.$route.params.id).then((response) => {
                this.user = response.data
                this.isLoading = false
            })
        },
    },
    created() {
        this.fetchUser()

        events.$on('reload:user', () => this.fetchUser())
    },
}
</script>
