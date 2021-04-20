<template>
    <section id="viewport">

        <!--Mobile Navigation-->
        <MobileNavigation />

        <!--Navigation Sidebar-->
        <SidebarNavigation/>

		<!--Sidebar navigation-->
        <ContentSidebar>
            <ContentGroup title="Invoices" class="navigator">
                <div class="menu-list-wrapper vertical">
                    <router-link :to="{name: 'InvoicesList'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <file-text-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            Invoices
                        </div>
                    </router-link>
                    <router-link :to="{name: 'Users'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <clock-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            Advance Invoices
                        </div>
                    </router-link>
                </div>
            </ContentGroup>
            <ContentGroup title="Others" class="navigator">
                <div class="menu-list-wrapper vertical">
                    <router-link :to="{name: 'Plans'}" class="menu-list-item link">
                        <div class="icon text-theme">
                            <users-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            Clients
                        </div>
                    </router-link>
                </div>
            </ContentGroup>
        </ContentSidebar>

		<!--<ContentFileView/>-->

		<router-view :class="{'is-scaled-down': isScaledDown}" />
    </section>
</template>

<script>
    import { UsersIcon, FileTextIcon, ClockIcon } from 'vue-feather-icons'
    import SidebarNavigation from '@/components/Sidebar/SidebarNavigation'
    import MobileNavigation from '@/components/Others/MobileNavigation'
    import ContentSidebar from '@/components/Sidebar/ContentSidebar'
    import ContentGroup from '@/components/Sidebar/ContentGroup'
	import { mapGetters } from 'vuex'
	import {events} from '@/bus'

	import ContentFileView from '@/components/Others/ContentFileView'

	export default {
        name: 'Settings',
        computed: {
            ...mapGetters([
				'config'
			]),
        },
        components: {
            SidebarNavigation,
            MobileNavigation,
            ContentSidebar,
            FileTextIcon,
            ContentGroup,
            UsersIcon,
			ClockIcon,
			ContentFileView,
        },
		data() {
			return {
				isScaledDown: false
			}
		},
		mounted() {
			events.$on('mobile-menu:show', () => this.isScaledDown = true)
			events.$on('fileItem:deselect', () => this.isScaledDown = false)
			events.$on('mobile-menu:hide', () => this.isScaledDown = false)
		}
    }
</script>

<style lang="scss">
    @import '@assets/vuefilemanager/_mixins';

	@media only screen and (max-width: 690px) {

		.is-scaled-down {
			@include transform(scale(0.95));
		}
	}
</style>