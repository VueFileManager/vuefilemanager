const routesTeam = [
    {
        name: 'Invitation',
        path: '/team-folder-invitation/:id',
        component: () => import(/* webpackChunkName: "chunks/invitation" */ '../views/Teams/Invitation'),
        meta: {
            requiresAuth: false,
        },
    },
]

export default routesTeam
