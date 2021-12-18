const routesAdmin = [
	{
		name: 'Admin',
		path: '/admin',
		component: () =>
			import(/* webpackChunkName: "chunks/admin" */ '../views/Admin'),
		meta: {
			requiresAuth: true,
			title: 'Admin'
		},
		children: [
			{
				name: 'Dashboard',
				path: '/admin/dashboard',
				component: () =>
					import(/* webpackChunkName: "chunks/dashboard" */ '../views/Admin/Dashboard'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.dashboard'
				},
			},
			{
				name: 'Invoices',
				path: '/admin/invoices',
				component: () =>
					import(/* webpackChunkName: "chunks/invoices" */ '../views/Admin/Invoices'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.invoices'
				},
			},
			{
				name: 'Subscriptions',
				path: '/admin/subscriptions',
				component: () =>
					import(/* webpackChunkName: "chunks/subscriptions" */ '../views/Admin/Subscriptions'),
				meta: {
					requiresAuth: true,
					title: 'Subscriptions'
				},
			},
			{
				name: 'Pages',
				path: '/admin/pages',
				component: () =>
					import(/* webpackChunkName: "chunks/pages" */ '../views/Admin/Pages'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.pages'
				},
			},
			{
				name: 'PageEdit',
				path: '/admin/pages/:slug',
				component: () =>
					import(/* webpackChunkName: "chunks/page-edit" */ '../views/Admin/Pages/PageEdit'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.page_edit'
				},
			},
			{
				name: 'Plans',
				path: '/admin/plans',
				component: () =>
					import(/* webpackChunkName: "chunks/plans" */ '../views/Admin/Plans'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.pricing_plans'
				},
			},
			{
				name: 'Users',
				path: '/admin/users',
				component: () =>
					import(/* webpackChunkName: "chunks/users" */ '../views/Admin/Users'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.users_list'
				},
			},
			{
				name: 'UserCreate',
				path: '/admin/user/create',
				component: () =>
					import(/* webpackChunkName: "chunks/user-create" */ '../views/Admin/Users/UserCreate'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.user_create'
				},
			},
			{
				name: 'PlanCreate',
				path: '/admin/plan/create',
				component: () =>
					import(/* webpackChunkName: "chunks/plan-create" */ '../views/Admin/Plans/PlanCreate'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.plan_create'
				},
			},
			{
				name: 'User',
				path: '/admin/user/:id',
				component: () =>
					import(/* webpackChunkName: "chunks/user" */ '../views/Admin/Users/User'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.users_user'
				},
				children: [
					{
						name: 'UserDetail',
						path: '/admin/user/:id/details',
						component: () =>
							import(/* webpackChunkName: "chunks/user-detail" */ '../views/Admin/Users/UserTabs/UserDetail'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.users_detail'
						},
					},
					{
						name: 'UserStorage',
						path: '/admin/user/:id/storage',
						component: () =>
							import(/* webpackChunkName: "chunks/user-storage" */ '../views/Admin/Users/UserTabs/UserStorage'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.users_storage_usage'
						},
					},
					{
						name: 'UserSubscription',
						path: '/admin/user/:id/subscription',
						component: () =>
							import(/* webpackChunkName: "chunks/user-subscription" */ '../views/Admin/Users/UserTabs/UserSubscription'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.subscription'
						},
					},
					{
						name: 'UserPassword',
						path: '/admin/user/:id/password',
						component: () =>
							import(/* webpackChunkName: "chunks/user-password" */ '../views/Admin/Users/UserTabs/UserPassword'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.users_password'
						},
					},
					{
						name: 'UserDelete',
						path: '/admin/user/:id/delete',
						component: () =>
							import(/* webpackChunkName: "chunks/user-delete" */ '../views/Admin/Users/UserTabs/UserDelete'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.users_delete'
						},
					},
				]
			},
			{
				name: 'Plan',
				path: '/admin/plan/:id',
				component: () =>
					import(/* webpackChunkName: "chunks/plan" */ '../views/Admin/Plans/Plan'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.plan'
				},
				children: [
					{
						name: 'PlanSubscribers',
						path: '/admin/plan/:id/subscribers',
						component: () =>
							import(/* webpackChunkName: "chunks/plan-subscribers" */ '../views/Admin/Plans/PlanTabs/PlanSubscribers'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.subscribers'
						},
					},
					{
						name: 'PlanSettings',
						path: '/admin/plan/:id/settings',
						component: () =>
							import(/* webpackChunkName: "chunks/plan-settings" */ '../views/Admin/Plans/PlanTabs/PlanSettings'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.plan_settings',
						},
					},
					{
						name: 'PlanDelete',
						path: '/admin/plan/:id/delete',
						component: () =>
							import(/* webpackChunkName: "chunks/plan-delete" */ '../views/Admin/Plans/PlanTabs/PlanDelete'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.plan_delete',
						},
					},
				]
			},
			{
				name: 'AppSettings',
				path: '/admin/settings',
				component: () =>
					import(/* webpackChunkName: "chunks/app-settings" */ '../views/Admin/AppSettings/AppSettings'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.settings'
				},
				children: [
					{
						name: 'AppAppearance',
						path: '/admin/settings/appearance',
						component: () =>
							import(/* webpackChunkName: "chunks/app-appearance" */ '../views/Admin/AppSettings/AppSettingsTabs/Appearance'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.appearance'
						},
					},
					{
						name: 'AppIndex',
						path: '/admin/settings/index',
						component: () =>
							import(/* webpackChunkName: "chunks/app-index" */ '../views/Admin/AppSettings/AppSettingsTabs/Index'),
						meta: {
							requiresAuth: true,
							title: 'Index'
						},
					},
					{
						name: 'AppBillings',
						path: '/admin/settings/billings',
						component: () =>
							import(/* webpackChunkName: "chunks/app-billings" */ '../views/Admin/AppSettings/AppSettingsTabs/Billings'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.billings'
						},
					},
					{
						name: 'AppEmail',
						path: '/admin/settings/email',
						component: () =>
							import(/* webpackChunkName: "chunks/app-email" */ '../views/Admin/AppSettings/AppSettingsTabs/Email'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.email'
						},
					},
					{
						name: 'AppPayments',
						path: '/admin/settings/payments',
						component: () =>
							import(/* webpackChunkName: "chunks/app-payments" */ '../views/Admin/AppSettings/AppSettingsTabs/Payments'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.payments'
						},
					},
					{
						name: 'AppOthers',
						path: '/admin/settings/others',
						component: () =>
							import(/* webpackChunkName: "chunks/app-others" */ '../views/Admin/AppSettings/AppSettingsTabs/Others'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.others'
						},
					},
				]
			},
			{
				name: 'Language',
				path: '/admin/language',
				component: () =>
					import(/* webpackChunkName: "chunks/app-language" */ '../views/Admin/Languages/Language'),
				meta: {
					requiresAuth: true,
				},
			}
		]
	},
]

export default routesAdmin