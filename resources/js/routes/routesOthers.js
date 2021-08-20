const routesOthers = [
	{
		name: 'NotFound',
		path: '*',
		component: () =>
			import(/* webpackChunkName: "chunks/not-found" */ '../views/NotFound'),
		meta: {
			requiresAuth: false
		},
	},
]

export default routesOthers