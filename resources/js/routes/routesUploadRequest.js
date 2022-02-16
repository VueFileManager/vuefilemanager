const routesShared = [
    {
        name: 'Request',
        path: '/request',
        component: () => import(/* webpackChunkName: "chunks/request" */ '../views/UploadRequest'),
        meta: {
            requiresAuth: false,
        },
        children: [
            {
                name: 'RequestUpload',
                path: '/request/:token/upload/:id?',
                component: () =>
                    import(/* webpackChunkName: "chunks/request-upload" */ '../views/FileView/UploadRequestFiles'),
                meta: {
                    requiresAuth: false,
                },
            },
        ],
    },
]

export default routesShared
