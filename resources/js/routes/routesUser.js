const routesUser = [
	{
		path: '/platform',
		name: 'Platform',
		component: () =>
			import(/* webpackChunkName: "chunks/platform" */ '../views/Platform'),
		children: [
			{
				name: 'Files',
				path: '/platform/files/:id?',
				component: () =>
					import(/* webpackChunkName: "chunks/files" */ '../views/FileView/Files'),
				meta: {
					requiresAuth: true
				},
			},
			{
				name: 'RecentUploads',
				path: '/platform/recent-uploads',
				component: () =>
					import(/* webpackChunkName: "chunks/recent-uploads" */ '../views/FileView/RecentUploads'),
				meta: {
					requiresAuth: true
				},
			},
			{
				name: 'MySharedItems',
				path: '/platform/my-shared-items',
				component: () =>
					import(/* webpackChunkName: "chunks/my-shared-items" */ '../views/FileView/MySharedItems'),
				meta: {
					requiresAuth: true
				},
			},
			{
				name: 'Trash',
				path: '/platform/trash/:id?',
				component: () =>
					import(/* webpackChunkName: "chunks/trash" */ '../views/FileView/Trash'),
				meta: {
					requiresAuth: true
				},
			},
			{
				name: 'Settings',
				path: '/platform/settings',
				component: () =>
					import(/* webpackChunkName: "chunks/settings" */ '../views/Profile'),
				meta: {
					requiresAuth: true
				},
				children: [
					{
						name: 'Profile',
						path: '/platform/profile',
						component: () =>
							import(/* webpackChunkName: "chunks/profile" */ '../views/User/Settings'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.profile'
						},
					},
					{
						name: 'Password',
						path: '/platform/settings/password',
						component: () =>
							import(/* webpackChunkName: "chunks/settings-password" */ '../views/User/Password'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.settings_password'
						},
					},
					{
						name: 'Storage',
						path: '/platform/settings/storage',
						component: () =>
							import(/* webpackChunkName: "chunks/settings-storage" */ '../views/User/Storage'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.settings_storage'
						},
					},
					{
						name: 'Invoice',
						path: '/platform/settings/invoices',
						component: () =>
							import(/* webpackChunkName: "chunks/settings-invoices" */ '../views/User/Invoices'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.invoices'
						},
					},
					{
						name: 'Subscription',
						path: '/platform/settings/subscription',
						component: () =>
							import(/* webpackChunkName: "chunks/settings-subscription" */ '../views/User/Subscription'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.subscription'
						},
					},
					{
						name: 'PaymentMethods',
						path: '/platform/settings/payment-methods',
						component: () =>
							import(/* webpackChunkName: "chunks/settings-payment-methods" */ '../views/User/PaymentMethods'),
						meta: {
							requiresAuth: true,
							title: 'routes_title.payment_methods'
						},
					},
					{
						name: 'CreatePaymentMethod',
						path: '/platform/settings/create-payment-method',
						component: () =>
							import(/* webpackChunkName: "chunks/settings-create-payment-methods" */ '../views/User/CreatePaymentMethod'),
						meta: {
							requiresAuth: true,
							title: 'Create Payment Method'
						},
					},
				]
			},
			{
				name: 'UpgradePlan',
				path: '/platform/upgrade/plan',
				component: () =>
					import(/* webpackChunkName: "chunks/upgrade-plan" */ '../views/Upgrade/UpgradePlan'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.upgrade_plan'
				},
			},
			{
				name: 'UpgradeBilling',
				path: '/platform/upgrade/billing',
				component: () =>
					import(/* webpackChunkName: "chunks/upgrade-billing" */ '../views/Upgrade/UpgradeBilling'),
				meta: {
					requiresAuth: true,
					title: 'routes_title.upgrade_billing'
				},
			},
		]
	}
]

export default routesUser